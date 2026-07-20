<?php
class Questionnaire_model extends CI_Model {

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

        public function fetch_data_questionnaire($com_id="") {
          $thaimonth = array("","มกราคม","กุมภาพันธ์","มีนาคม","เมษายน","พฤษภาคม","มิถุนายน","กรกฎาคม","สิงหาคม","กันยายน","ตุลาคม","พฤศจิกายน","ธันวาคม");
          $lang = $this->session->userdata("lang") == null ? "thai" : $this->session->userdata("lang");
          $user = $this->session->userdata('user');
          $this->load->model('Manage_model', 'manage', FALSE);
          $this->manage->loadDB();
          $arr['page'] = 'questionnaire/create';
          $this->db->from('lms_questionnaire');
          $this->db->where('lms_questionnaire.com_id', $com_id);
          $this->db->where('lms_questionnaire.qn_isDelete', '0');
          $query = $this->db->get();
          $fetch = $query->result_array();
          $num = 1;$count = 0;
          $fetch_arr = array();

          $arr['btn_update'] = $this->manage->chk_permission($arr['page'],'ru_edit');
          $arr['btn_delete'] = $this->manage->chk_permission($arr['page'],'ru_del');
          foreach ($fetch as $key => $value) {
              $output = array();
              $output['num'] = "<span style='float:right;'>".$num."</span>";$num++;
                $qn_lang = "";
                if($value['qn_title_eng']!=""){
                  $qn_lang .= "EN";
                }
                if($value['qn_title_th']!=""){
                  if($qn_lang!=""){
                    $qn_lang .= ",";
                  }
                  $qn_lang .= "TH";
                }
                if($value['qn_title_jp']!=""){
                  if($qn_lang!=""){
                    $qn_lang .= ",";
                  }
                  $qn_lang .= "JP";
                }
              $output['faqlang'] = "<center>".$qn_lang."</center>";
              if($lang=="thai"){ 
                    $qn_title = $value['qn_title_th']!=""?$value['qn_title_th']:$value['qn_title_eng'];
                    $qn_title = $qn_title!=""?$qn_title:$value['qn_title_jp'];
                  }else if($lang=="english"){ 
                    $qn_title = $value['qn_title_eng']!=""?$value['qn_title_eng']:$value['qn_title_jp'];
                    $qn_title = $qn_title!=""?$qn_title:$value['qn_title_th'];
                  }else{
                    $qn_title = $value['qn_title_jp']!=""?$value['qn_title_jp']:$value['qn_title_eng'];
                    $qn_title = $qn_title!=""?$qn_title:$value['qn_title_th'];
                  }
              $output['questionnaire_temp_name'] = $qn_title;
              /*if($lang=="thai"){
              $output['4'] = $value['qn_modifieddate']!="0000-00-00 00:00:00"?date('d/m/',strtotime($value['qn_modifieddate'])).(date('Y',strtotime($value['qn_modifieddate']))+543)." ".date('H:i',strtotime($value['qn_modifieddate'])):"<center>-</center>";
              }else{
              $output['4'] = $value['qn_modifieddate']!="0000-00-00 00:00:00"?date('d/m/Y H:i',strtotime($value['qn_modifieddate'])):"<center>-</center>";
              }*/
              if($lang=="thai"){
              $qn_modifieddate = $value['qn_modifieddate']!="0000-00-00 00:00:00"?date('d/m/',strtotime($value['qn_modifieddate'])).(date('Y',strtotime($value['qn_modifieddate']))+543)." ".date('H:i',strtotime($value['qn_modifieddate'])):"<center>-</center>";
              }else{
              $qn_modifieddate = $value['qn_modifieddate']!="0000-00-00 00:00:00"?date('d/m/Y H:i',strtotime($value['qn_modifieddate'])):"<center>-</center>";
              }
              $qn_modifieddateori = $value['qn_modifieddate']!="0000-00-00 00:00:00"?$value['qn_modifieddate']:"";
              $arrmodifieddate = array(
                'display' => $qn_modifieddate,
                'timestamp' => strtotime($qn_modifieddateori),
              );
              $output['m_updatedate'] = $arrmodifieddate;
                  $update = '<button type="button" name="update_questionnaire" id="'.$value['qn_id'].'" title="Edit" class="btn btn-warning btn-xs update_questionnaire"><i class="mdi mdi-lead-pencil"></i></button>';
                  $delete = '<button type="button" name="delete_questionnaire" id="'.$value['qn_id'].'" class="btn btn-danger btn-xs delete_questionnaire" title="Delete"><i class="mdi mdi-window-close"></i></button>';

                  if($arr['btn_update']!="1"){
                    $update = '';
                  }
                  if($arr['btn_delete']!="1"){
                    $delete = '';
                  }
              $output['buttonall'] = "<center>".'<button type="button" name="questionnaire_detail" id="'.$value['qn_id'].'" title="'.label('question').'" class="btn btn-info btn-xs questionnaire_detail"><i class="mdi mdi-comment-question-outline"></i></button>'.$update.$delete."</center>";
              $count++;
              array_push($fetch_arr, $output);
          }
          return $fetch_arr;
        }

        public function fetch_data_questionnaire_detail($qn_id) {
          $thaimonth = array("","มกราคม","กุมภาพันธ์","มีนาคม","เมษายน","พฤษภาคม","มิถุนายน","กรกฎาคม","สิงหาคม","กันยายน","ตุลาคม","พฤศจิกายน","ธันวาคม");
          $lang = $this->session->userdata("lang") == null ? "thai" : $this->session->userdata("lang");
          $user = $this->session->userdata('user');

          $arr['page'] = 'questionnaire/create';
          $this->load->model('Manage_model', 'manage', FALSE);
          $this->manage->loadDB();
          $this->db->from('lms_questionnaire_de');
          $this->db->where('lms_questionnaire_de.qn_id', $qn_id);
          $query = $this->db->get();
          $fetch = $query->result_array();
          $num = 1;$count = 0;
          $fetch_arr = array();
                  $arr['btn_update'] = $this->manage->chk_permission($arr['page'],'ru_edit');
                  $arr['btn_delete'] = $this->manage->chk_permission($arr['page'],'ru_del');
          foreach ($fetch as $key => $value) {
              $output = array();
              $output['1'] = "<span style='float:right;'>".$num."</span>";$num++;
              if($lang=="thai"){ 
                    $qnde_heading = $value['qnde_heading_th']!=""?$value['qnde_heading_th']:$value['qnde_heading_eng'];
                    $qnde_heading = $qnde_heading!=""?$qnde_heading:$value['qnde_heading_jp'];
                    $qnde_detail = $value['qnde_detail_th']!=""?$value['qnde_detail_th']:$value['qnde_detail_eng'];
                    $qnde_detail = $qnde_detail!=""?$qnde_detail:$value['qnde_detail_jp'];
                  }else if($lang=="english"){ 
                    $qnde_heading = $value['qnde_heading_eng']!=""?$value['qnde_heading_eng']:$value['qnde_heading_jp'];
                    $qnde_heading = $qnde_heading!=""?$qnde_heading:$value['qnde_heading_th'];
                    $qnde_detail = $value['qnde_detail_eng']!=""?$value['qnde_detail_eng']:$value['qnde_detail_jp'];
                    $qnde_detail = $qnde_detail!=""?$qnde_detail:$value['qnde_detail_th'];
                  }else{
                    $qnde_heading = $value['qnde_heading_jp']!=""?$value['qnde_heading_jp']:$value['qnde_heading_eng'];
                    $qnde_heading = $qnde_heading!=""?$qnde_heading:$value['qnde_heading_th'];
                    $qnde_detail = $value['qnde_detail_jp']!=""?$value['qnde_detail_jp']:$value['qnde_detail_eng'];
                    $qnde_detail = $qnde_detail!=""?$qnde_detail:$value['qnde_detail_th'];
                  }
              $output['2'] = $qnde_heading;
              $output['3'] = $qnde_detail;
              
                  $update = '<button type="button" name="update_questionnaire_detail" id="'.$value['qnde_id'].'" title="Edit" class="btn btn-warning btn-xs update_questionnaire_detail"><i class="mdi mdi-lead-pencil"></i></button>';
                  $delete = '<button type="button" name="delete_questionnaire_detail" id="'.$value['qnde_id'].'" class="btn btn-danger btn-xs delete_questionnaire_detail" title="Delete"><i class="mdi mdi-window-close"></i></button>';

                  if($arr['btn_update']!="1"){
                    $update = '';
                  }
                  if($arr['btn_delete']!="1"){
                    $delete = '';
                  }
              $output['0'] = "<center>".$update.$delete."</center>";
              $count++;
              array_push($fetch_arr, $output);
          }
          return $fetch_arr;
        }


    public function create_questionnaire($data)
    {
            date_default_timezone_set("Asia/Bangkok");
            $user = $this->session->userdata('user');
            $this->db->from('lms_questionnaire');
            $this->db->where('com_id', $data['com_id']);
            $this->db->where('qn_title_th', $data['qn_title_th']);
            $this->db->where('qn_title_eng', $data['qn_title_eng']);
            $this->db->where('qn_title_jp', $data['qn_title_jp']);
            $this->db->where('qn_lang', $data['qn_lang']);
            $this->db->where('qn_isDelete', '1');
            $query = $this->db->get();
            if($query->num_rows()==0){
              $this->db->insert('lms_questionnaire', $data);
            }
            $id = $this->db->insert_id();
            return $id;
    }

    public function update_questionnaire($data,$qn_id)
    {
        $this->db->where('qn_id', $qn_id);
        $this->db->update('lms_questionnaire', $data);
    }

    public function create_questionnaire_detail($data)
    {
            date_default_timezone_set("Asia/Bangkok");
            $user = $this->session->userdata('user');
            $this->db->from('lms_questionnaire_de');
            $this->db->where('qn_id', $data['qn_id']);
            $this->db->where('qnde_heading_th', $data['qnde_heading_th']);
            $this->db->where('qnde_detail_th', $data['qnde_detail_th']);
            $this->db->where('qnde_heading_eng', $data['qnde_heading_eng']);
            $this->db->where('qnde_detail_eng', $data['qnde_detail_eng']);
            $this->db->where('qnde_heading_jp', $data['qnde_heading_jp']);
            $this->db->where('qnde_detail_jp', $data['qnde_detail_jp']);
            $this->db->where('qnde_isDelete', '0');
            $query = $this->db->get();
            if($query->num_rows()==0){
              $this->db->insert('lms_questionnaire_de', $data);
            }
            $id = $this->db->insert_id();
            return $id;
    }

    public function update_questionnaire_detail($data,$qnde_id)
    {
        $this->db->where('qnde_id', $qnde_id);
        $this->db->update('lms_questionnaire_de', $data);
    }


    public function insertQuestionnaireDetail($questionnaire_id,$heading_th,$detail_th,$heading_eng,$detail_eng,$heading_jp,$detail_jp)
    {
      date_default_timezone_set("Asia/Bangkok");
      $user = $this->session->userdata('user');
      $time = date('Y-m-d H:i');
      $this->db->from('lms_questionnaire_de')
               ->where('qn_id', $questionnaire_id)
               ->where('qnde_heading_th', $heading_th)
               ->where('qnde_detail_th', $detail_th)
               ->where('qnde_heading_eng', $heading_eng)
               ->where('qnde_detail_eng', $detail_eng)
               ->where('qnde_heading_jp', $heading_jp)
               ->where('qnde_detail_jp', $detail_jp);
            $this->db->where('qnde_isDelete', '0');
      $query = $this->db->get();
      $result = $query->result_array();
      if(countArray($result)==0){
        if($questionnaire_id!=""){
          $data['qn_id'] = $questionnaire_id;
          $data['qnde_heading_th'] = $heading_th;
          $data['qnde_detail_th'] = $detail_th;
          $data['qnde_heading_eng'] = $heading_eng;
          $data['qnde_detail_eng'] = $detail_eng;
          $data['qnde_heading_jp'] = $heading_jp;
          $data['qnde_detail_jp'] = $detail_jp;
          $data['qnde_createby'] = $user['u_id'];
          $data['qnde_createdate'] = $time;
          $data['qnde_modifiedby'] = $user['u_id'];
          $data['qnde_modifieddate'] = $time;
          $this->db->insert('lms_questionnaire_de', $data);
        }
      }
    }

}
