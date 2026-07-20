<?php
class Compliance_model extends CI_Model {
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
      public function insertSendmail($sm_subject,$sm_desc,$com_p,$time_create,$time_modified)
      {
        date_default_timezone_set("Asia/Bangkok");
        $time = date('Y-m-d H:i');
          $data['sm_subject'] = $sm_subject;
          $data['sm_desc'] = $sm_desc;
          $data['com_p'] = $com_p;
          $data['sm_createtime'] = $time_create;
          $data['sm_modifiedtime'] = $time_modified;
          $this->db->insert('lms_comp_sentmail', $data);
          $id = $this->db->insert_id();
          return $id;
      }

      public function updateSendmail($sm_id,$sm_subject,$sm_desc,$com_p,$time_create,$time_modified)
      {
        date_default_timezone_set("Asia/Bangkok");
        $time = date('Y-m-d H:i');
          $data['sm_subject'] = $sm_subject;
          $data['sm_desc'] = $sm_desc;
          $data['com_p'] = $com_p;
          $data['sm_createtime'] = $time_create;
          $data['sm_modifiedtime'] = $time_modified;
          $this->db->where('sm_id',$sm_id);
          $this->db->update('lms_comp_sentmail', $data);
      }

      public function getlist_sendmail($id= '')
      {
        $this->db->select('lms_emp.email')
                 ->distinct()
                 ->from('lms_comp_emp')
                 ->join('lms_comp_sentmail','lms_comp_emp.com_p = lms_comp_sentmail.com_p','INNER')
                 ->join('lms_emp','lms_comp_emp.emp_c = lms_emp.emp_c','INNER')
                 ->where('lms_comp_sentmail.sm_id', $id)
                 ->where('lms_emp.status', 'Active')
                 ->where('lms_emp.status', 'active')
                 ->where('lms_emp.lang', 'thailand')
                 ->where_not_in('lms_comp_emp.status', '1');
        $query = $this->db->get();
        $result = $query->result_array();
        $arr_empc = array();
        foreach ($result as $key => $value) {
         array_push($arr_empc, $value['email']);
        }
        return $arr_empc;
      }

      public function insertComplianceHead($topic_name_th,$topic_name_en,$name_the_executive_th,$name_the_executive_en,$message_from_the_executive_th,$message_from_the_executive_en,$recommendation_th,$recommendation_en,$position_the_executive_th,$position_the_executive_en,$image_file,$lang,$time_start,$time_end,$company_level,$chkbox_showtopic)
      {
        date_default_timezone_set("Asia/Bangkok");
        $time = date('Y-m-d H:i');
          $data['topic_name_th'] = $topic_name_th;
          $data['topic_name_en'] = $topic_name_en;
          $data['name_the_executive_th'] = $name_the_executive_th;
          $data['name_the_executive_en'] = $name_the_executive_en;
          $data['message_from_the_executive_th'] = $message_from_the_executive_th;
          $data['message_from_the_executive_en'] = $message_from_the_executive_en;
          $data['recommendation_th'] = $recommendation_th;
          $data['recommendation_en'] = $recommendation_en;
          $data['position_the_executive_th'] = $position_the_executive_th;
          $data['position_the_executive_en'] = $position_the_executive_en;
          $data['image_the_executive'] = $image_file;
          $data['chkbox_showtopic'] = $chkbox_showtopic;
          $data['org_code'] = $company_level;
          $data['time_start'] = date("Y-m-d H:i",strtotime($time_start));
          $data['time_end'] = date("Y-m-d H:i",strtotime($time_end));
          $data['time_create'] = date("Y-m-d H:i");
          $data['time_mod'] = date("Y-m-d H:i");
          $data['lang'] = $lang;
          $this->db->insert('lms_comp', $data);
          $id = $this->db->insert_id();
          return $id;
      }
      public function updateComplianceHead($comp_id,$topic_name_th,$topic_name_en,$name_the_executive_th,$name_the_executive_en,$message_from_the_executive_th,$message_from_the_executive_en,$recommendation_th,$recommendation_en,$position_the_executive_th,$position_the_executive_en,$image_file,$lang,$time_start,$time_end,$company_level,$chkbox_showtopic)
      {
        date_default_timezone_set("Asia/Bangkok");
        $time = date('Y-m-d H:i');
          $data['topic_name_th'] = $topic_name_th;
          $data['topic_name_en'] = $topic_name_en;
          $data['name_the_executive_th'] = $name_the_executive_th;
          $data['name_the_executive_en'] = $name_the_executive_en;
          $data['message_from_the_executive_th'] = $message_from_the_executive_th;
          $data['message_from_the_executive_en'] = $message_from_the_executive_en;
          $data['chkbox_showtopic'] = $chkbox_showtopic;
          $data['recommendation_th'] = $recommendation_th;
          $data['recommendation_en'] = $recommendation_en;
          $data['position_the_executive_th'] = $position_the_executive_th;
          $data['position_the_executive_en'] = $position_the_executive_en;
          $data['image_the_executive'] = $image_file;
          $data['org_code'] = $company_level;
          $data['time_start'] = date("Y-m-d H:i",strtotime($time_start));
          $data['time_end'] = date("Y-m-d H:i",strtotime($time_end));
          $data['time_mod'] = date("Y-m-d H:i");
          $data['lang'] = $lang;
          $this->db->where('id',$comp_id);
          $this->db->update('lms_comp', $data);
      }

      function selectCompliance($id= '')
      {
        $this->db->from('lms_comp')
                 ->where('lms_comp.id', $id);
        $query = $this->db->get();
        $result = $query->result_array();
        return $query->result_array();
      }
      function selectTopic($id= '')
      {
        $this->db->select('lms_comp_top.id,lms_comp_top.title_name_th,lms_comp_top.title_name_en,lms_comp_top.explanation_begins_th,lms_comp_top.explanation_begins_en,lms_comp_top.end_quote_th,lms_comp_top.end_quote_en')
                 ->from('lms_comp_top')
                 ->where('lms_comp_top.comp_id', $id)
                 ->where('lms_comp_top.status', '1');
        $query = $this->db->get();
        return $query->result();
      }
      function selectTopicDetail($id= '')
      {
        $this->db->from('lms_comp_top')
                 ->join('lms_comp_ques', 'lms_comp_top.id = lms_comp_ques.ctop_id')
                 ->where('lms_comp_top.comp_id', $id)
                 ->where('lms_comp_ques.hidden', '1');
        $query = $this->db->get();
        return $query->result();
      }


      public function insertComplianceTOP($data)
      {
        date_default_timezone_set("Asia/Bangkok");
        $time = date('Y-m-d H:i');
        $this->db->insert('lms_comp_top', $data);
        $id = $this->db->insert_id();
        return $id;
      }

      public function updateComplianceTOP($data,$id)
      {
        date_default_timezone_set("Asia/Bangkok");
        $time = date('Y-m-d H:i');
        $this->db->where('id', $id)
                 ->update('lms_comp_top', $data);
      }

      public function insertComplianceQUES($data)
      {
        date_default_timezone_set("Asia/Bangkok");
        $time = date('Y-m-d H:i');
        $this->db->insert('lms_comp_ques', $data);
        $id = $this->db->insert_id();
                $com_p = "";
                $ctop_id = "";
                $this->db->from('lms_comp_top')
                         ->join('lms_comp_ques', 'lms_comp_top.id = lms_comp_ques.ctop_id')
                         ->where('lms_comp_ques.id', $id);
                $query = $this->db->get();
                $row = $query->row_array();
                $result = $query->result_array();

                foreach ($result as $each) {
                  $com_p = $each['comp_id'];
                  $ctop_id = $each['ctop_id'];
                }
                $this->db->select('emp_c');
                $this->db->distinct();
                $this->db->from('lms_emp');
                $this->db->where('org1', 'TIS');
                $query = $this->db->get();
                $row = $query->row_array();
                $result = $query->result_array();

                foreach ($result as $each) {
                  $emp_c = $each['emp_c'];
                  $this->db->from('lms_comp_emp');
                  $this->db->where('emp_c', $emp_c);
                  $this->db->where('com_p', $com_p);
                  $query = $this->db->get();
                  $row = $query->row_array();
                  $comp_emp_id = "";
                  if($row>0){
                    $result = $query->result_array();
                    foreach ($result as $each) {
                      $comp_emp_id = $each['id'];
                    }
                  }else{
                    $data = array(
                        'emp_c'     => $emp_c,
                        'com_p' => $com_p,
                        'status' => '2',
                        'time_create' => date('Y-m-d H:i'),
                        'time_mod' => date('Y-m-d H:i')
                    );
                    $this->db->insert('lms_comp_emp', $data);
                    $comp_emp_id = $this->db->insert_id();
                  }
                  if($comp_emp_id!=""){
                      $emp_c = $each['emp_c'];
                      $this->db->from('lms_comp_emp_de');
                      $this->db->where('comp_emp_id', $comp_emp_id);
                      $this->db->where('ctop_id', $ctop_id);
                      $this->db->where('ques_id', $id);
                      $query = $this->db->get();
                      $row = $query->row_array();
                      if($row==0){
                        $data = array(
                          'comp_emp_id'     => $comp_emp_id,
                          'ctop_id' => $ctop_id,
                          'ques_id' => $id,
                          'time_mod' => date('Y-m-d H:i')
                        );
                        $this->db->insert('lms_comp_emp_de', $data);
                      }
                  }
                }
        return $id;
      }


      public function updateComplianceQUES($data,$id)
      {
        date_default_timezone_set("Asia/Bangkok");
        $time = date('Y-m-d H:i');
        $this->db->where('id', $id)
                 ->update('lms_comp_ques', $data);
                $com_p = "";
                $ctop_id = "";
                $this->db->from('lms_comp_top')
                         ->join('lms_comp_ques', 'lms_comp_top.id = lms_comp_ques.ctop_id')
                         ->where('lms_comp_ques.id', $id);
                $query = $this->db->get();
                $row = $query->row_array();
                $result = $query->result_array();

                foreach ($result as $each) {
                  $com_p = $each['comp_id'];
                  $ctop_id = $each['ctop_id'];
                }
                $this->db->select('emp_c');
                $this->db->distinct();
                $this->db->from('lms_emp');
                $this->db->where('org1', 'TIS');
                $query = $this->db->get();
                $row = $query->row_array();
                $result = $query->result_array();

                foreach ($result as $each) {
                  $emp_c = $each['emp_c'];
                  $this->db->from('lms_comp_emp');
                  $this->db->where('emp_c', $emp_c);
                  $this->db->where('com_p', $com_p);
                  $query = $this->db->get();
                  $row = $query->row_array();
                  $comp_emp_id = "";
                  if($row>0){
                    $result = $query->result_array();
                    foreach ($result as $each) {
                      $comp_emp_id = $each['id'];
                    }
                  }else{
                    $data = array(
                        'emp_c'     => $emp_c,
                        'com_p' => $com_p,
                        'status' => '2',
                        'time_create' => date('Y-m-d H:i'),
                        'time_mod' => date('Y-m-d H:i')
                    );
                    $this->db->insert('lms_comp_emp', $data);
                    $comp_emp_id = $this->db->insert_id();
                  }
                  if($comp_emp_id!=""){
                      $emp_c = $each['emp_c'];
                      $this->db->from('lms_comp_emp_de');
                      $this->db->where('comp_emp_id', $comp_emp_id);
                      $this->db->where('ctop_id', $ctop_id);
                      $this->db->where('ques_id', $id);
                      $query = $this->db->get();
                      $row = $query->row_array();
                      if($row==0){
                        $data = array(
                          'comp_emp_id'     => $comp_emp_id,
                          'ctop_id' => $ctop_id,
                          'ques_id' => $id,
                          'time_mod' => date('Y-m-d H:i')
                        );
                        $this->db->insert('lms_comp_emp_de', $data);
                      }
                  }
                }
      }

      public function removeFromQUE_DE($id)
      {
        $this->db->where('ques_id', $id);
        $this->db->delete('lms_comp_emp_de');

        $this->db->where('id',$id)
                 ->set('time_create',date('Y-m-d H:i'))
                 ->set('hidden', '0')
                 ->update('lms_comp_ques');
      }

      public function removeFromTopic($id)
      {
        $this->db->where('ctop_id', $id);
        $this->db->delete('lms_comp_emp_de');

        $this->db->where('id',$id)
                 ->set('time_create',date('Y-m-d H:i'))
                 ->set('status', '0')
                 ->update('lms_comp_top');
      }

      public function get_data()
      {
          $query  = $this->db->where('lms_comp.status', '1')
                             ->get('lms_comp');
          $result = $query->result();
          return $result;
      }

      public function get_data_sendmail()
      {
          $query  = $this->db->where('lms_comp_sentmail.sm_status', '1')
                             ->join('lms_comp','lms_comp_sentmail.com_p = lms_comp.id')
                             ->get('lms_comp_sentmail');
          $result = $query->result();
          return $result;
      }


        public function getCompliance_select() {
          $this->db->select('id,topic_name_th');
          $this->db->distinct();
          $this->db->where('status','1');
          $query = $this->db->get('lms_comp');
          return $query->result_array();
        }

      public function getData_sendmail($sm_id)
        {
          $this->db->from('lms_comp_sentmail');
          $this->db->where('sm_id', $sm_id);
          $query = $this->db->get();
          if ($query->num_rows() > 0){
            $sendmail = $query->result_array();
            return $sendmail[0];
          }
        }

      public function removeFromCOMP($id)
      {

        $this->db->where('com_p', $id);
        $this->db->delete('lms_comp_emp');

        $this->db->where('id',$id)
                 ->set('time_mod',date('Y-m-d H:i'))
                 ->set('hidden', '0')
                 ->update('lms_comp');
      }


      public function removeFromSendmail($id)
      {

        $this->db->where('sm_id', $id);
        $this->db->delete('lms_comp_sentmail');

      }

      public function get_data_activity()
      {
          $query  = $this->db->order_by('id', 'desc')
                             ->where('lms_comp.status', '1')
                             ->get('lms_comp');
          $result = $query->result();
          return $result;
      }

      public function get_data_activity_person($emp_c)
      {
        $this->db->select('lms_comp.topic_name_th,lms_comp.org_code,lms_comp.topic_name_en,lms_comp_emp.status,lms_comp.hidden,lms_comp.id,lms_comp_emp.time_mod')
                 ->from('lms_comp_emp')
                 ->join('lms_comp', 'lms_comp_emp.com_p = lms_comp.id')
                 ->where('lms_comp_emp.emp_c', $emp_c)
                 ->where('lms_comp.hidden', '1');
        $query = $this->db->get();
        return $query->result();
      }

      public function get_data_finish_msg()
      {
        $this->db->from('lms_comp_finish_msg')
                 ->where('lms_comp_finish_msg.id', '1');
        $query = $this->db->get();
        return $query->result_array();
      }

      public function chkreommendation($ques_id , $ctop_id)
      {
          $query  = $this->db->select('lms_comp_ques.suggestion_th,lms_comp_ques.suggestion_en')
                             ->where('lms_comp_ques.id', $ques_id)
                             ->where('lms_comp_ques.ctop_id', $ctop_id)
                             ->get('lms_comp_ques');
          $result = $query->result();
          return $result;
      }

      public function isCheckActivity($comp_emp_id){
                $this->db->from('lms_comp_emp_de');
                $this->db->where('comp_emp_id', $comp_emp_id);
                $query = $this->db->get();
                $row = $query->row_array();
                $result = $query->result_array();
                $num = 0;
                foreach ($result as $each) {
                  if($each['status']=="0"){
                    $num++;
                  }
                }
                if($num==0){
                    $this->db->where('id',$comp_emp_id)
                             ->set('time_mod',date('Y-m-d H:i'))
                             ->set('status', '1')
                             ->update('lms_comp_emp');
                }
                if($num!=0&&$num<$row){
                    $this->db->where('id',$comp_emp_id)
                             ->set('time_mod',date('Y-m-d H:i'))
                             ->set('status', '0')
                             ->update('lms_comp_emp');
                }
                return $num;
      }

      public function isCheckDateExp($com_p){
                $this->db->from('lms_comp');
                $this->db->where('id', $com_p);
                $query = $this->db->get();
                $row = $query->row_array();
                $result = $query->result_array();
                $num = 0;
                foreach ($result as $each) {
                  if(date('Y-m-d H:i',strtotime($each['time_end']))>date('Y-m-d H:i')){
                    $num++;
                  }
                }
                if($num>0){
                  $this->db->where('com_p',$com_p)
                           ->where_not_in('status','1')
                             ->set('time_mod',date('Y-m-d H:i'))
                             ->set('status', '3')
                             ->update('lms_comp_emp');
                }
                return $num;
      }

      public function getDataActivity($emp_c , $com_p , $chkbox_showtopic)
      {
        date_default_timezone_set("Asia/Bangkok");
          $this->db->from('lms_comp_emp');
          $this->db->where('emp_c', $emp_c);
          $this->db->where('com_p', $com_p);
          $query = $this->db->get();
          $row = $query->row_array();
          if($row>0){
            $result = $query->result_array();
            foreach ($result as $each) {
              $num = $this->isCheckActivity($each['id']);
                if($num>0){
                  if($each['status']!="1"){
                    $this->db->from('lms_comp_emp_de');
                    $this->db->join('lms_comp_ques', 'lms_comp_emp_de.ques_id = lms_comp_ques.id');
                    $this->db->where('lms_comp_emp_de.comp_emp_id', $each['id']);
                    $this->db->where('lms_comp_emp_de.status', '0');
                    $this->db->order_by('lms_comp_ques.numeral', 'asc');
                    $query = $this->db->get();
                    $row = $query->row_array();
                    $result = $query->result_array();
                    $num = 1;
                    $ques_id = "";
                    $ctop_id = "";
                    $title_name_th = "";
                    $title_name_en = "";
                    foreach ($result as $each) {
                      if($num==1){
                        $ques_id = $each['ques_id'];
                      }
                      $num++;
                    }
                    if($chkbox_showtopic=="1"){
                      $this->db->select('lms_comp_ques.ctop_id,lms_comp_top.title_name_th,lms_comp_top.title_name_en')
                               ->from('lms_comp_top')
                               ->join('lms_comp_ques', 'lms_comp_top.id = lms_comp_ques.ctop_id')
                               ->where('lms_comp_ques.id', $ques_id)
                               ->order_by('lms_comp_ques.numeral', 'asc')
                               ->order_by('lms_comp_ques.ctop_id', 'asc');
                    }else{
                      $this->db->select('lms_comp_ques.ctop_id,lms_comp_top.title_name_th,lms_comp_top.title_name_en')
                               ->from('lms_comp_top')
                               ->join('lms_comp_ques', 'lms_comp_top.id = lms_comp_ques.ctop_id')
                               ->where('lms_comp_ques.id', $ques_id)
                               ->order_by('lms_comp_ques.numeral', 'asc');
                    }
                    $query = $this->db->get();
                    $result = $query->result_array();

                    $num_rechk = countArray($result);
                    foreach ($result as $each) {
                        $title_name_th = $each['title_name_th'];
                        $title_name_en = $each['title_name_en'];
                        $ctop_id = $each['ctop_id'];
                    }
                    $data_resend = array(
                        'num_rechk'     => $num_rechk,
                        'status'     => '0',
                        'ques_id' => $ques_id,
                        'ctop_id' => $ctop_id,
                        'title_name_th' => $title_name_th,
                        'title_name_en' => $title_name_en
                    );
                  }else{
                    $data_resend = array(
                        'num_rechk'     => '0',
                        'status'     => $each['status'],
                        'ques_id' => '0',
                        'ctop_id' => '0',
                        'title_name_th' => '',
                        'title_name_en' => ''
                    );
                  }
                }else{
                  $data_resend = array(
                        'num_rechk'     => '0',
                        'status'     => '5',
                        'ques_id' => '0',
                        'ctop_id' => '0',
                        'title_name_th' => '',
                        'title_name_en' => ''
                  );
                }
              
            }
          }else{
            $data = array(
                'emp_c'     => $emp_c,
                'com_p' => $com_p,
                'time_create' => date('Y-m-d H:i'),
                'time_mod' => date('Y-m-d H:i')
            );
            $this->db->insert('lms_comp_emp', $data);
            $comp_emp_id = $this->db->insert_id();
            $this->db->select('lms_comp_ques.ctop_id,lms_comp_ques.id,lms_comp_top.title_name_th,lms_comp_top.title_name_en')
                     ->from('lms_comp_top')
                     ->join('lms_comp_ques', 'lms_comp_top.id = lms_comp_ques.ctop_id')
                     ->where('lms_comp_top.comp_id', $com_p)
                     ->where('lms_comp_ques.hidden', '1')
                     ->order_by('lms_comp_ques.numeral', 'asc');
            $query = $this->db->get();
            $result = $query->result_array();
            $num = 1;
            $ctop_id = "";
            $ques_id = "";
            $title_name_th = "";
            $title_name_en = "";
            $num_rechk = countArray($result);
            foreach ($result as $each) {
              if($num==1){
                $ctop_id = $each['ctop_id'];
                $ques_id = $each['id'];
                $title_name_th = $each['title_name_th'];
                $title_name_en = $each['title_name_en'];
              }
              $data = array(
                'comp_emp_id'     => $comp_emp_id,
                'ctop_id' => $each['ctop_id'],
                'ques_id' => $each['id'],
                'time_mod' => date('Y-m-d H:i')
              );
              $this->db->insert('lms_comp_emp_de', $data);
              $num++;
            }
            $data_resend = array(
                'num_rechk'     => $num_rechk,
                'status'     => '0',
                'ques_id' => $ques_id,
                'ctop_id' => $ctop_id,
                'title_name_th' => $title_name_th,
                'title_name_en' => $title_name_en
            );
          }
          //print_r($data_resend);
          return $data_resend;
      }
       public function getDataActivityDemo($emp_c , $com_p , $chkbox_showtopic,$arr)
      {
        //print_r($arr);
        if($arr!="0"){
          $array_test = array();
          if($chkbox_showtopic=="1"){
            $this->db->select('lms_comp_ques.ctop_id,lms_comp_ques.id,lms_comp_top.title_name_th,lms_comp_top.title_name_en')
                     ->from('lms_comp_top')
                     ->join('lms_comp_ques', 'lms_comp_top.id = lms_comp_ques.ctop_id')
                     ->where('lms_comp_top.comp_id', $com_p)
                     ->order_by('lms_comp_ques.numeral', 'asc')
                     ->order_by('lms_comp_ques.ctop_id', 'asc');
          }else{
            $this->db->select('lms_comp_ques.ctop_id,lms_comp_ques.id,lms_comp_top.title_name_th,lms_comp_top.title_name_en')
                     ->from('lms_comp_top')
                     ->join('lms_comp_ques', 'lms_comp_top.id = lms_comp_ques.ctop_id')
                     ->where('lms_comp_top.comp_id', $com_p)
                     ->where('lms_comp_ques.hidden', '1')
                     ->order_by('lms_comp_ques.numeral', 'asc');
          }
            foreach ($arr as $key => $value) {
              array_push($array_test, $value);
            }
          //echo $arr;
            $query = $this->db->get();
            $result = $query->result_array();
            $num = 1;
            $ctop_id = "";
            $ques_id = "";
            $title_name_th = "";
            $title_name_en = "";
            $num_rechk = countArray($result);
            foreach ($result as $each) {
              if(!in_array($each['id'], $array_test)){
                if($num==1){
                  $ctop_id = $each['ctop_id'];
                  $ques_id = $each['id'];
                  $title_name_th = $each['title_name_th'];
                  $title_name_en = $each['title_name_en'];
                }
                $num++;
              }
            }
            if(countArray($arr)==countArray($result)){
              $data_resend = array(
                  'num_rechk'     => $num_rechk,
                  'status'     => '5',
                  'ques_id' => $ques_id,
                  'ctop_id' => $ctop_id,
                  'title_name_th' => $title_name_th,
                  'title_name_en' => $title_name_en
              );
            }else{
              $data_resend = array(
                  'num_rechk'     => $num_rechk,
                  'status'     => '0',
                  'ques_id' => $ques_id,
                  'ctop_id' => $ctop_id,
                  'title_name_th' => $title_name_th,
                  'title_name_en' => $title_name_en
              );
            }
        }else{
          if($chkbox_showtopic=="1"){
            $this->db->select('lms_comp_ques.ctop_id,lms_comp_ques.id,lms_comp_top.title_name_th,lms_comp_top.title_name_en')
                     ->from('lms_comp_top')
                     ->join('lms_comp_ques', 'lms_comp_top.id = lms_comp_ques.ctop_id')
                     ->where('lms_comp_top.comp_id', $com_p)
                     ->order_by('lms_comp_ques.ctop_id', 'asc')
                     ->order_by('lms_comp_ques.numeral', 'asc');
          }else{
            $this->db->select('lms_comp_ques.ctop_id,lms_comp_ques.id,lms_comp_top.title_name_th,lms_comp_top.title_name_en')
                     ->from('lms_comp_top')
                     ->join('lms_comp_ques', 'lms_comp_top.id = lms_comp_ques.ctop_id')
                     ->where('lms_comp_top.comp_id', $com_p)
                     ->where('lms_comp_ques.hidden', '1')
                     ->order_by('lms_comp_ques.numeral', 'asc');
          }
            $query = $this->db->get();
            $result = $query->result_array();
            $num = 1;
            $ctop_id = "";
            $ques_id = "";
            $title_name_th = "";
            $title_name_en = "";
            $num_rechk = countArray($result);
            foreach ($result as $each) {
              if($num==1){
                $ctop_id = $each['ctop_id'];
                $ques_id = $each['id'];
                $title_name_th = $each['title_name_th'];
                $title_name_en = $each['title_name_en'];
              }
              $num++;
            }
            $data_resend = array(
                'num_rechk'     => $num_rechk,
                'status'     => '0',
                'ques_id' => $ques_id,
                'ctop_id' => $ctop_id,
                'title_name_th' => $title_name_th,
                'title_name_en' => $title_name_en
            );
        }
        return $data_resend;
      }

       public function getDataQuestion($ques_id , $ctop_id , $com_p ,$emp_c)
      {
          $result = array();
          if($ctop_id!=""&&$ques_id!=""){
              $this->db->from('lms_comp_emp_de')
                       ->join('lms_comp_emp','lms_comp_emp_de.comp_emp_id = lms_comp_emp.id')
                       ->where('lms_comp_emp.emp_c', $emp_c)
                       ->where('lms_comp_emp.com_p', $com_p)
                       ->where('lms_comp_emp_de.status', '0');
              $query_count = $this->db->get();
            if($query_count->num_rows() > 0){
            $this->db->from('lms_comp_ques')
                           ->where('lms_comp_ques.id', $ques_id)
                           ->where('lms_comp_ques.ctop_id', $ctop_id);
            $query = $this->db->get();
            $result = $query->result_array();

              
              $this->db->from('lms_comp_emp_de')
                       ->join('lms_comp_emp','lms_comp_emp_de.comp_emp_id = lms_comp_emp.id')
                       ->where('lms_comp_emp.emp_c', $emp_c)
                       ->where('lms_comp_emp_de.ctop_id', $ctop_id)
                       ->where('lms_comp_emp_de.status', '1');
              $query_counttopic = $this->db->get();
              $result_counttopic = $query_counttopic->result_array();
              $this->db->from('lms_comp_ques')
                       ->join('lms_comp_top','lms_comp_ques.ctop_id = lms_comp_top.id')
                       ->where('lms_comp_top.comp_id', $com_p);
              $query_main = $this->db->get();
              $result_main = $query_main->result_array();

              $this->db->from('lms_comp_emp_de')
                       ->join('lms_comp_emp','lms_comp_emp_de.comp_emp_id = lms_comp_emp.id')
                       ->where('lms_comp_emp.emp_c', $emp_c)
                       ->where('lms_comp_emp.com_p', $com_p)
                       ->where('lms_comp_emp_de.status', '1');
              $query_count = $this->db->get();
              $result_count = $query_count->result_array();
              $this->db->from('lms_comp_ques')
                       ->join('lms_comp_top','lms_comp_ques.ctop_id = lms_comp_top.id')
                       ->where('lms_comp_ques.ctop_id', $ctop_id)
                       ->where('lms_comp_top.comp_id', $com_p);
              $query_maintopic = $this->db->get();
              $result_maintopic = $query_maintopic->result_array();
              
              $arr = array();
                $arr1 = array('correct_choice' => 'choice_a', 'choice_th' => $result[0]['choice_a_th'],'choice_en' =>$result[0]['choice_a_en'],'image_choice' => $result[0]['image_choice_a']);
                $arr2 = array('correct_choice' => 'choice_b', 'choice_th' => $result[0]['choice_b_th'],'choice_en' =>$result[0]['choice_b_en'],'image_choice' => $result[0]['image_choice_b']);
                array_push($arr, $arr1);
                array_push($arr, $arr2);
                shuffle($arr);
              //print_r($arr);
              $result[0]['choice_a_data'] = $arr[0];
              $result[0]['choice_b_data'] = $arr[1];
              $result[0]['maintopic'] = countArray($result_maintopic);
              $result[0]['correct_answer'] = $result_maintopic[0]['correct_answer'];
              $result[0]['title_name_th'] = $result_maintopic[0]['title_name_th'];
              $result[0]['title_name_en'] = $result_maintopic[0]['title_name_en'];
              $result[0]['question_th'] = $result[0]['question_th'];
              $result[0]['question_en'] = $result[0]['question_en'];
              $result[0]['explanation_begins_th'] = $result_maintopic[0]['explanation_begins_th'];
              $result[0]['explanation_begins_en'] = $result_maintopic[0]['explanation_begins_en'];
              $result[0]['end_quote_th'] = $result_maintopic[0]['end_quote_th'];
              $result[0]['end_quote_en'] = $result_maintopic[0]['end_quote_en'];
              $result[0]['counttopic'] = countArray($result_counttopic);
              $result[0]['count_question'] = (countArray($result_count)+1)." / ".countArray($result_main);
              $result[0]['count_total'] = countArray($result_main);
              $result[0]['count_success'] = countArray($result_count);
              $result[0]['maintopic'] = countArray($result_maintopic);
            }else{

              $this->db->from('lms_comp_emp_de')
                       ->join('lms_comp_emp','lms_comp_emp_de.comp_emp_id = lms_comp_emp.id')
                       ->where('lms_comp_emp.emp_c', $emp_c)
                       ->where('lms_comp_emp_de.ctop_id', $ctop_id)
                       ->where('lms_comp_emp_de.status', '1');
              $query_counttopic = $this->db->get();
              $result_counttopic = $query_counttopic->result_array();
              $this->db->from('lms_comp_ques')
                       ->join('lms_comp_top','lms_comp_ques.ctop_id = lms_comp_top.id')
                       ->where('lms_comp_top.comp_id', $com_p);
              $query_main = $this->db->get();
              $result_main = $query_main->result_array();

              $this->db->from('lms_comp_emp_de')
                       ->join('lms_comp_emp','lms_comp_emp_de.comp_emp_id = lms_comp_emp.id')
                       ->where('lms_comp_emp.emp_c', $emp_c)
                       ->where('lms_comp_emp.com_p', $com_p)
                       ->where('lms_comp_emp_de.status', '1');
              $query_count = $this->db->get();
              $result_count = $query_count->result_array();
              $this->db->from('lms_comp_ques')
                       ->join('lms_comp_top','lms_comp_ques.ctop_id = lms_comp_top.id')
                       ->where('lms_comp_ques.ctop_id', $ctop_id)
                       ->where('lms_comp_top.comp_id', $com_p);
              $query_maintopic = $this->db->get();
              $result_maintopic = $query_maintopic->result_array();
              $result[0]['counttopic'] = countArray($result_counttopic);
              $result[0]['count_question'] = (countArray($result_count)+1)." / ".countArray($result_main);
              $result[0]['count_total'] = countArray($result_main);
              $result[0]['count_success'] = countArray($result_count);
              $result[0]['maintopic'] = countArray($result_maintopic);
            }
          }
          //print_r($result);
          return $result;
      }
      public function getDataQuestionDemo($ques_id , $ctop_id , $com_p ,$emp_c ,$arr_topic)
      {
          $array_test = array();
          $this->db->from('lms_comp_ques')
                         ->where('lms_comp_ques.id', $ques_id)
                         ->where('lms_comp_ques.ctop_id', $ctop_id);
          $query = $this->db->get();
          $result = $query->result_array();
          $this->db->from('lms_comp_ques')
                   ->join('lms_comp_top','lms_comp_ques.ctop_id = lms_comp_top.id')
                   ->where('lms_comp_top.comp_id', $com_p);
          $query_main = $this->db->get();
          $result_main = $query_main->result_array();

          $this->db->from('lms_comp_ques')
                   ->join('lms_comp_top','lms_comp_ques.ctop_id = lms_comp_top.id')
                   ->where('lms_comp_ques.ctop_id', $ctop_id)
                   ->where('lms_comp_top.comp_id', $com_p);
          $query_maintopic = $this->db->get();
          $result_maintopic = $query_maintopic->result_array();

          $result[0]['key'] =  "key:".countArray($result_maintopic);
          $num = 0;
          if(isset($arr_topic)){
            if($arr_topic!="0"){
              $num =  countArray($arr_topic)+1;
              foreach ($arr_topic as $key => $value) {
                array_push($array_test, $value);
              }
            }else{
              $num = 0;
            }
          }else{
            $num = 0;
          }
            $result[0]['line'] = countArray($arr_topic);
          $arr = array();
          $arr1 = array('correct_choice' => 'choice_a', 'choice' => $result[0]['choice_a_th'],'choice_a_en' =>$result[0]['choice_a_en'],'image_choice' => $result[0]['image_choice_a']);
          $arr2 = array('correct_choice' => 'choice_b', 'choice' => $result[0]['choice_b_th'],'choice_b_en' =>$result[0]['choice_b_en'],'image_choice' => $result[0]['image_choice_b']);
          array_push($arr, $arr1);
          array_push($arr, $arr2);
          shuffle($arr);
          $result[0]['choice_a_data'] = $arr[0];
          $result[0]['choice_b_data'] = $arr[1];
          $result[0]['title_name_th'] = $result_maintopic[0]['title_name_th'];
          $result[0]['title_name_en'] = $result_maintopic[0]['title_name_en'];
          $result[0]['question_th'] = $result[0]['question_th'];
          $result[0]['question_en'] = $result[0]['question_en'];
          $result[0]['explanation_begins_th'] = $result_maintopic[0]['explanation_begins_th'];
          $result[0]['explanation_begins_en'] = $result_maintopic[0]['explanation_begins_en'];
          $result[0]['end_quote_th'] = $result_maintopic[0]['end_quote_th'];
          $result[0]['end_quote_en'] = $result_maintopic[0]['end_quote_en'];
          $result[0]['choice_a'] = $arr[0]['correct_choice'];
          $result[0]['choice_b'] = $arr[1]['correct_choice'];
          $result[0]['choicebtn1'] = $arr[0]['choice'];
          $result[0]['choicebtn2'] = $arr[1]['choice'];
          $result[0]['image_choice_a'] = $arr[0]['image_choice'];
          $result[0]['image_choice_b'] = $arr[1]['image_choice'];
          //echo countArray($arr_topic);
          if($arr_topic!="0"){
            if(countArray($arr_topic)>0){
              if(in_array($ctop_id, $arr_topic)){
                $result[0]['counttopic'] = countArray($arr_topic);
              }else{
                $result[0]['counttopic'] = '0';
              }
              //print_r($counts);
              
              //echo $counts[$ctop_id];
            }else{
              $result[0]['counttopic'] = countArray($arr_topic);
            }
          }else{
            $result[0]['counttopic'] = "0";
          }
          $result[0]['count_question'] = countArray($result_main);
          $result[0]['count_total'] = countArray($result_main);
          $result[0]['count_success'] = countArray($arr_topic);
          $result[0]['maintopic'] = countArray($result_maintopic);
          return $result;
      }

      public function rechkCountQuestion($comp_id)
      {
          $this->db->from('lms_comp_ques')
                   ->join('lms_comp_top', 'lms_comp_ques.ctop_id = lms_comp_top.id')
                   ->where('lms_comp_top.comp_id', $comp_id);
          $query = $this->db->get();
          $result = $query->result();
          $row = $query->row_array();
          return $row;
      }

      public function rechkDataAnswer($emp_c , $ques_id , $ctop_id , $type_answer ,$correct_answer)
      {
        date_default_timezone_set("Asia/Bangkok");
          $count_answer = 0;
          $id = "";
          $status = "";
          $this->db->select('lms_comp_emp_de.id,lms_comp_emp_de.count_answer,lms_comp_emp_de.status')
                   ->from('lms_comp_emp_de')
                   ->join('lms_comp_emp', 'lms_comp_emp_de.comp_emp_id = lms_comp_emp.id')
                   ->where('lms_comp_emp.emp_c', $emp_c)
                   ->where('lms_comp_emp_de.ctop_id', $ctop_id)
                   ->where('lms_comp_emp_de.ques_id', $ques_id);
          $query = $this->db->get();
          $result = $query->result_array();

          foreach ($result as $each) {
            $status = $each['status'];
            $count_answer = intval($each['count_answer']);
            $id = $each['id'];
          }
          if($status!='1'){
            $count_answer++;
          }
          $status_ans = "";
          if($correct_answer!=$type_answer){
            $status_ans = "0";
            $this->db->where('id',$id)
                     ->set('time_mod',date('Y-m-d H:i'))
                     ->set('count_answer', $count_answer)
                     ->set('status', '0')
                     ->update('lms_comp_emp_de');
          }else{
            $status_ans = "1";
            $this->db->where('id',$id)
                     ->set('time_mod',date('Y-m-d H:i'))
                     ->set('count_answer', $count_answer)
                     ->set('status', '1')
                     ->update('lms_comp_emp_de');
          }

          return $status_ans;
      }

      public function rechkDataAnswerDemo($emp_c , $ques_id , $ctop_id , $type_answer)
      {
        date_default_timezone_set("Asia/Bangkok");
          $count_answer = 0;
          $id = "";
          $status = "";
          $status_ans = "";
          $this->db->from('lms_comp_ques')
                   ->where('lms_comp_ques.ctop_id', $ctop_id)
                   ->where('lms_comp_ques.id', $ques_id)
                   ->where('lms_comp_ques.correct_answer', $type_answer);
          $query = $this->db->get();
          $row = $query->row_array();
          if($row>0){
            $status_ans = "1";
          }else{
            $status_ans = "0";
          }

          return $status_ans;
      }

      public function getCompliance()
      {
        $this->db->from('lms_comp')
                 ->where('lms_comp.hidden', '1')
                 ->order_by('id', 'desc');
        $query = $this->db->get();
        return $query->result();
      }
      public function getFinishMSG()
      {
        $this->db->from('lms_comp_finish_msg');
        $query = $this->db->get();
        return $query->result_array();
      }

      public function rechktopic_incompliance($com_p)
      {
        $this->db->distinct();
        $this->db->select('lms_comp_top.id,lms_comp_top.title_name_th,lms_comp_top.title_name_en');
        $this->db->from('lms_comp_top')
                 ->where('lms_comp_top.status','1')
                 ->where('lms_comp_top.comp_id', $com_p);
        $query = $this->db->get();
        return $query->result_array();
      }

      public function rechkcompany_level()
      {
        $this->db->distinct();
        $this->db->select('lms_org1.code,lms_org1.name');
        $this->db->from('lms_org1')
                 ->where('lms_org1.orgfor', 'tis');
        $query = $this->db->get();
        return $query->result_array();
      }

      public function countReportStaff($comp_id,$lang){
        $output = array();
        $this->db->select('lms_comp_emp.emp_c,lms_comp_emp.status')
             ->distinct()
             ->from('lms_comp_emp')
             ->join('lms_emp','lms_comp_emp.emp_c = lms_emp.emp_c')
             ->where('lms_emp.lang',$lang)
             ->where('lms_comp_emp.com_p', $comp_id);
        $this->db->where('lms_emp.org1', 'TIS');
        $this->db->join('lms_usp','lms_emp.emp_c = lms_usp.emp_c');
        $this->db->where('lms_usp.dummy_status', '0');
        $this->db->where('(lms_emp.status = "active"', NULL, FALSE);
        $this->db->or_where("lms_emp.status = 'Active')", NULL, FALSE);
        $query = $this->db->get();
        $row = $query->result();
        $result_ques = $query->result_array();
        $completed = 0;
        $not_respond = 0;
        $not_completed = 0;
        foreach ($result_ques as $key) {
          if($key['status']=="1"){
            $completed++;
          }else if($key['status']=="2"){
            $not_respond++;
          }else{
            $not_completed++;
          }
        }
        $output['completed'] = $completed;
        $output['not_respond'] = $not_respond;
        $output['not_completed'] = $not_completed;
        $output['total'] = countArray($row);
        return $output;
      }
      public function chkdataComplianceStatus($comp_id,$lang){
        $msg='';
        $msg .= '<tr>';
        $msg .= '<th width="100px"></th>';
        $msg .= '<th width="100px">Employee no.</th>';
        $msg .= '<th width="100px">Name</th>';
        $msg .= '<th width="100px">Surname</th>';
        $msg .= '<th width="100px">Department</th>';
        $msg .= '<th width="100px">E-Mail</th>';
        $msg .= '<th width="100px" align="center">Status</th>';
        $msg .= '</tr>';
        return $msg;
      }
      
      public function chkdataCompliance($comp_id,$lang){
        echo '<tr>';
        echo '<th width="100px">Employee no.</th>';
        echo '<th width="100px">Name</th>';
        echo '<th width="100px">Surname</th>';
        echo '<th width="100px">Department</th>';
                    $this->db->select('lms_comp_ques.id,lms_comp_ques.ctop_id')
                             ->from('lms_comp_top')
                             ->join('lms_comp_ques', 'lms_comp_top.id = lms_comp_ques.ctop_id')
                             ->where('lms_comp_top.comp_id', $comp_id);
                    $query = $this->db->get();
                    $row = $query->result();
                    $result_ques = $query->result_array();
        for($num=1;$num<=countArray($row);$num++){
          echo '<th width="50px" align="center">Q'.$num.'</th>';
        }
        echo '</tr>';
      }
    function getValOrg($numorg = '',$val=''){
      $this->db->select('lms_org'.$numorg.'.name')
               ->from('lms_org'.$numorg)
               ->where('lms_org'.$numorg.'.code',$val);
      $query = $this->db->get();
      $fetch = $query->row_array();
      return $fetch['name'];
    }
      function fetch_report_staff($comp_id,$lang){

                    $this->db->select('lms_comp_ques.id,lms_comp_ques.ctop_id')
                             ->from('lms_comp_top')
                             ->join('lms_comp_ques', 'lms_comp_top.id = lms_comp_ques.ctop_id')
                             ->where('lms_comp_top.comp_id', $comp_id);
                    $query = $this->db->get();
                    $row = $query->result();
                    $result_ques = $query->result_array();
        $this->db->select('lms_emp.emp_c,lms_emp.fname,lms_emp.lname,lms_emp.org1,lms_emp.org2,lms_emp.email');
        $this->db->distinct();
        $this->db->from('lms_emp');
        $this->db->join('lms_usp','lms_emp.emp_c = lms_usp.emp_c');
        $this->db->where('lms_emp.org1', 'TIS');
        $this->db->where('lms_usp.dummy_status', '0');
        $this->db->where('(lms_emp.status = "active"', NULL, FALSE);
        $this->db->or_where("lms_emp.status = 'Active')", NULL, FALSE);
        $this->db->where('lms_emp.lang', 'english');
        $query = $this->db->get();
        $row = $query->row_array();
        $result = $query->result_array();

        $data = array();
        $num = 1;
        foreach ($result as $each) {

          $output = array(
              $each['emp_c'],
              $each['fname'],
              $each['lname'],
              $this->getValOrg('2',$each['org2']) 
          );
          foreach ($result_ques as $ques) {
            $this->db->select('lms_comp_emp_de.count_answer')
                     ->from('lms_comp_emp')
                     ->join('lms_comp_emp_de', 'lms_comp_emp.id = lms_comp_emp_de.comp_emp_id')
                     ->where('lms_comp_emp.emp_c', $each['emp_c'])
                     ->where('lms_comp_emp_de.ctop_id', $ques['ctop_id'])
                     ->where('lms_comp_emp_de.ques_id', $ques['id']);
            $query = $this->db->get();
            $row = $query->result();
            $count_answer = 0;
            if(countArray($row)>0){
              $result_emp = $query->result_array();
              foreach ($result_emp as $emp) {
                $count_answer = intval($emp['count_answer']);
              }
            }
            array_push($output, $count_answer);
            //echo '<td width="50px" align="center">'.$count_answer.'</td>';
          }
          array_push($data, $output);
          $num++;
        }
        return $data;
      }


      function fetch_report_status($comp_id,$lang){

                    $this->db->select('lms_comp_ques.id,lms_comp_ques.ctop_id')
                             ->from('lms_comp_top')
                             ->join('lms_comp_ques', 'lms_comp_top.id = lms_comp_ques.ctop_id')
                             ->where('lms_comp_top.comp_id', $comp_id);
                    $query = $this->db->get();
                    $row = $query->result();
                    $result_ques = $query->result_array();
        $this->db->select('lms_emp.emp_c,lms_emp.fname,lms_emp.lname,lms_emp.org1,lms_emp.org2,lms_emp.email');
        $this->db->distinct();
        $this->db->from('lms_emp');
        $this->db->join('lms_usp','lms_emp.emp_c = lms_usp.emp_c');
        $this->db->where('lms_usp.dummy_status', '0');
        $this->db->where('lms_emp.org1', 'TIS');
        $this->db->where('lms_emp.lang', 'english');
        $this->db->where('(lms_emp.status = "active"', NULL, FALSE);
        $this->db->or_where("lms_emp.status = 'Active')", NULL, FALSE);

        $query = $this->db->get();
        $row = $query->row_array();
        $result = $query->result_array();

        $data = array();
        $num = 1;
        foreach ($result as $each) {
          $msg_status = "";
            $this->db->select('lms_comp_emp.status')
                     ->from('lms_comp_emp')
                     ->where('lms_comp_emp.emp_c', $each['emp_c'])
                     ->where('lms_comp_emp.com_p', $comp_id);
            $query = $this->db->get();
            $row = $query->result();
            $count_answer = 0;
            if(countArray($row)>0){
              $result_emp = $query->result_array();
              foreach ($result_emp as $emp) {
                if($emp['status']=="1"){
                  $msg_status = "Complete";
                }else if($emp['status']=="2"){
                  $msg_status = "No Response";
                }else{
                  $msg_status = "Not Complete";
                }
              }
            }

          $output = array(
              $each['emp_c'],
              $each['fname'],
              $each['lname'],
              $this->getValOrg('2',$each['org2']),
              $each['email'],
              $msg_status
          );
            //echo '<td width="50px" align="center">'.$count_answer.'</td>';
          array_push($data, $output);
          $num++;
        }
        return $data;
      }


      function fetch_report_question($comp_id,$lang){

        $this->db->select('lms_comp_top.title_name_th,lms_comp_top.title_name_en,lms_comp_ques.question_th,lms_comp_ques.question_en,lms_comp_ques.id,lms_comp_ques.ctop_id')
                 ->from('lms_comp_top')
                 ->join('lms_comp_ques', 'lms_comp_top.id = lms_comp_ques.ctop_id')
                 ->where('lms_comp_top.comp_id', $comp_id);
        $query = $this->db->get();
        $row = $query->row_array();
        $result = $query->result_array();

        $data = array();
        $num = 1;
        foreach ($result as $each) {
          $title_name = "";
          if($lang=="thailand"){
            $title_name = $each['title_name_th'];
          }else{
            $title_name = $each['title_name_en'];
          }
          $output = array(
              "Q".$num,
              $title_name,
              $each['question_en'],
              $each['question_th']
          );
          $first = 0;
          $second = 0;
          $third = 0;
          $fourth = 0;
          $fifth = 0;
          $more_than = 0;

            $this->db->select('lms_comp_emp_de.count_answer')
                     ->from('lms_comp_emp')
                     ->join('lms_usp', 'lms_comp_emp.emp_c = lms_usp.emp_c')
                     ->join('lms_comp_emp_de', 'lms_comp_emp.id = lms_comp_emp_de.comp_emp_id')
                     ->where('lms_usp.dummy_status', '0')
                     ->where('lms_comp_emp_de.ctop_id', $each['ctop_id'])
                     ->where('lms_comp_emp_de.ques_id', $each['id']);
            $query = $this->db->get();
            $row = $query->result();
            $count_answer = 0;
            if(countArray($row)>0){
              $result_emp = $query->result_array();
              foreach ($result_emp as $emp) {
                $count_answer = intval($emp['count_answer']);
                if($count_answer==1){
                  $first++;
                }else if($count_answer==2){
                  $second++;
                }else if($count_answer==3){
                  $third++;
                }else if($count_answer==4){
                  $fourth++;
                }else if($count_answer==5){
                  $fifth++;
                }else if($count_answer>5){
                  $more_than++;
                }
              }
            }

          array_push($output, $first);
          array_push($output, $second);
          array_push($output, $third);
          array_push($output, $fourth);
          array_push($output, $fifth);
          array_push($output, $more_than);
          array_push($data, $output);
          $num++;
        }
        return $data;
      }


      public function create_finish($data)
      {
        date_default_timezone_set("Asia/Bangkok");
        if($data['id']!=""){
          $this->db->where('id',$data['id']);
          $this->db->update('lms_comp_finish_msg', $data);
          $id = $data['id'];
        }else{
          $this->db->insert('lms_comp_finish_msg', $data);
          $id = $this->db->insert_id();
        }
        return $id;
      }
}
?>
