<?php
class Home_model extends CI_Model {
      public function __construct(){
        // Call the CI_Model constructor
        parent::__construct();
      }
      public function loadDB(){
        $this->load->database();
      }
      public function closeDB(){
        $this->db->close();
      }

      public function homeC($lang){
        $this->db->select('*,count(lms_cos.ccode) as total');
        $this->db->from('lms_cos');
        $this->db->join('lms_plv', 'lms_cos.ccode = lms_plv.course_id', 'right');
        $this->db->where('lms_cos.hidden', 1);
        $this->db->where('lms_cos.status', 1);
        $this->db->where('lms_cos.seat', null);
        $this->db->where('lms_cos.lang', $lang);
        $this->db->group_by('lms_cos.ccode');
        $query = $this->db->get();
        return $query->result_array();
      }

      public function onlineUser(){
        $this->db->from('lms_usp');
        $this->db->where('st_on', "online");
        $query = $this->db->get();
        return $query->result_array();
      }

      public function getfaq(){
        $this->db->from('lms_faq');
        $query = $this->db->get();
        return $query->result_array();
      }

      public function getfaq_detail(){
        $this->db->from('lms_faq_q');
        $query = $this->db->get();
        return $query->result_array();
      }

      public function gettestimonials(){
        $this->db->from('lms_testimonials');
        $query = $this->db->get();
        return $query->result_array();
      }
      public function getmenu(){
        $sess = $this->session->userdata("user");
        $this->db->from('lms_mainmenu');
        if(isset($sess['emp_id']) && $sess['emp_id']!=""){
          $this->db->where('com_id',$sess['com_id']);
        }else{
          $this->db->where('com_id','2');
        }
        $this->db->where('mm_status','1');
        $query = $this->db->get();
        return $query->result_array();
      }

      public function record_sort($records, $field, $reverse=false)
      {
          $hash = array();
          
          foreach($records as $record)
          {
              $hash[$record[$field]] = $record;
          }
          
          ($reverse)? krsort($hash) : ksort($hash);
          
          $records = array();
          
          foreach($hash as $record)
          {
              $records []= $record;
          }
          
          return $records;
      }

      public function get_thefirstofcourse(){
        $lang = $this->session->userdata("lang") == null ? "thai" : $this->session->userdata("lang") ;
        $sess = $this->session->userdata("user");
        date_default_timezone_set("Asia/Bangkok");
        $arr = array();
        $this->db->from('lms_cos');
        if($sess['com_admin']=="CUSTOMER"||$sess['Is_admin']=="0"){
          $this->db->where('com_id',$sess['com_id']);
        }

        $this->db->where('status','1');
        $query = $this->db->get();
        $fetch = $query->result_array();
        foreach ($fetch as $key => $value) {
          $sub = array();

          $this->db->from('lms_cos_enroll');
          $this->db->join('lms_emp','lms_cos_enroll.emp_id = lms_emp.emp_id');
          $this->db->join('lms_usp','lms_emp.emp_id = lms_usp.emp_id');
          $this->db->where('lms_cos_enroll.cos_id',$value['id']);
          $this->db->order_by('lms_cos_enroll.cosen_score','DESC');
          $this->db->limit(1);
          $query_ens = $this->db->get();
          $num_ens = $query_ens->num_rows();
          if($num_ens>0){
            $fetch_ens = $query_ens->row_array();
            $sub['fullname_th'] = $fetch_ens['fullname_th'];
            $sub['fullname_en'] = $fetch_ens['fullname_en'];
            $sub['img_profile'] = $fetch_ens['img_profile'];
            $sub['useri'] = $fetch_ens['useri'];
            $sub['cosen_score'] = $fetch_ens['cosen_score'];
            $sub['cosen_grade'] = $fetch_ens['cosen_grade'];
            $sub['cname_th'] = $value['cname_th'];
            $sub['cname_en'] = $value['cname_en'];
            array_push($arr,$sub);
          }
        }
        $arr = $this->record_sort($arr, "cosen_score",true);
        return $arr;
      }

      public function get_samplecourse(){
        $sess = $this->session->userdata("user");
        //$this->db->select('lms_cos.id,lms_cos.cname_th,lms_cos.cname_en,lms_cos.pic,lms_cos.seat_count');
        $this->db->from('lms_cos');
        $this->db->join('lms_cos_sort','lms_cos.id = lms_cos_sort.cos_id');
        $this->db->join('lms_company','lms_cos.com_id = lms_company.com_id');
        if($sess['emp_c']!=""){
          $this->db->where('lms_cos.com_id',$sess['com_id']);
        }else{
          $this->db->where('lms_company.com_admin','OWNER');
        }
        $this->db->order_by('lms_cos_sort.coss_num','ASC');
        $query = $this->db->get();
        if($sess['emp_c']!=""){
          $ar = $query->result_array();
          if($sess['com_admin']=="CUSTOMER"||$sess['Is_admin']=='0'){
              foreach ($ar as $key_coses => $value_coses) {
                  $this->db->select('cosen_status,cosen_status_sub');
                  $this->db->from('lms_cos_enroll');
                  $this->db->where('lms_cos_enroll.cos_id', $value_coses['id'] );
                  $this->db->where('lms_cos_enroll.emp_id', $sess['emp_id'] );
                  $query_enroll = $this->db->get();
                  $ar_enroll = $query_enroll->result_array();
                  $ar[$key_coses]['enroll'] = $ar_enroll;
                  $this->db->select('cosen_status,cosen_status_sub');
                  $this->db->from('lms_cos_enroll');
                  $this->db->where('lms_cos_enroll.cos_id', $value_coses['id'] );
                  $this->db->where('lms_cos_enroll.cosen_status', '1' );
                  $query_enroll_count = $this->db->get();
                  $ar_enroll_count = $query_enroll_count->result_array();
                  $ar[$key_coses]['enroll_count'] = countArray($ar_enroll_count);
              }
              return $ar;
          }else{
              return $ar;
          }
        }else{
          return $query->result_array();
        }
      }

      public function getpic(){
        $sess = $this->session->userdata("user");
        if(isset($sess)&&countArray($sess)>0){
          $this->db->from('lms_ban');
          if($sess['com_admin']=="com_associated"){
            $this->db->where('com_id', $sess['com_id']);
          }else{
            $this->db->where('com_id', '3');
          }
        }else{
          $this->db->from('lms_about_ban');
        }
        $this->db->where('hidden', 1);
        $this->db->order_by('id','DESC');
        $this->db->limit(8);
        $query = $this->db->get();
        return $query->result_array();
      }


      public function getpic_all(){
        $sess = $this->session->userdata("user");
        if(isset($sess)&&countArray($sess)>0){
          $this->db->from('lms_ban');
          $this->db->where('com_id', $sess['com_id']);
        }else{
          $this->db->from('lms_about_ban');
        }
        $this->db->where('hidden', 1);
        $this->db->order_by('id','DESC');
        $this->db->limit(8);
        $query = $this->db->get();
        return $query->result_array();
      }

      public function getallpic(){
        $this->db->from('lms_ban');
        $query = $this->db->get();
        return $query->result_array();
      }

      public function sethide($id,$cm){
        $this->db->from('lms_ban');
        $this->db->where('id', $id);
        $data = array(
          'hidden' => $cm
        );
        $this->db->update('lms_ban', $data);
      }

      public function savepic($data){
        date_default_timezone_set("Asia/Bangkok");
        $time = date('Y-m-d H:i');
        $this->db->set('time_created',  $time);
        $this->db->insert('lms_ban', $data);
      }

      public function updatepic($data,$id){
        $this->db->from('lms_ban');
        $this->db->where('id', $id);
        date_default_timezone_set("Asia/Bangkok");
        $time = date('Y-m-d H:i');
        $this->db->set('time_created',  $time);
        $this->db->update('lms_ban', $data);
      }

      public function getidpic($id)
      {
        $this->db->from('lms_ban');
        $this->db->where('id', $id);
        $query = $this->db->get();
        return $query->result_array();
      }

      public function deletepic($id){
        $this->db->where('id', $id);
        $this->db->delete('lms_ban');
      }


}
