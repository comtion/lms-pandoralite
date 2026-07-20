<?php
class Pretest_model extends CI_Model {

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
    public function createPL($pre_id, $pLv)
    {
      $this->db->where('pre_id', $pre_id);
      $this->db->delete('lms_pre_plv');
      foreach ($pLv as $each) {
        if ($each != 'on'){
          $data = array(
            'pre_id' => $pre_id,
            'org_c' => $each
          );
          $this->db->insert('lms_pre_plv', $data);
        }
      }
    }
    public function checkPlv($pre_id)
    {
      $user = $this->session->userdata("user");
      //print_r($user);
      $this->db->from('lms_pre_plv');
      $this->db->where('pre_id', $pre_id);
      $this->db->where('org_c', $user['plv']);
      $query = $this->db->get();
      return ($query->num_rows() > 0);
    }
    public function insertPretestName($data)
    {
      date_default_timezone_set("Asia/Bangkok");
      $time = date('Y-m-d H:i');
      $data['time_create'] = date("Y-m-d H:i");
      $data['time_mod'] = date("Y-m-d H:i");
      $this->db->insert('lms_ptn', $data);

      $this->db->select('max(id) as id');
      $this->db->from('lms_ptn');
      $query = $this->db->get();
      $result = $query->result_array();
      return $result[0]['id'];
    }

    public function updatePretestName($data)
    {
      date_default_timezone_set("Asia/Bangkok");
      $data['time_mod'] =  date("Y-m-d H:i");
      $this->db->where('id', $data['id'])
               ->update('lms_ptn', $data);
    }

    public function insertPretestQuestion($data)
    {
      date_default_timezone_set("Asia/Bangkok");
      $time = date('Y-m-d H:i');
      $data['time_create'] = date("Y-m-d H:i");
      $data['time_mod'] = date("Y-m-d H:i");
      $this->db->insert('lms_ptq', $data);
    }

    public function updatePretestQuestion($data)
    {
      date_default_timezone_set("Asia/Bangkok");
      $time = date('Y-m-d H:i');
      $data['time_mod'] = date("Y-m-d H:i");
      $this->db->where('id', $data['id'])
               ->update('lms_ptq', $data);
    }

    public function insertCategoryName($data)
    {
      date_default_timezone_set("Asia/Bangkok");
      $time = date('Y-m-d H:i');
      $data['time_create'] = date("Y-m-d H:i");
      $data['time_mod'] = date("Y-m-d H:i");
      $this->db->insert('lms_ptc', $data);
    }


    public function getAllEmp( $lang , $org4inzone,$to_select=''){
      $dataReturn = Array();
      //print_r( $allplv );
      $this->load->model('Manage_model', 'manage', FALSE);
      $this->manage->loadDB();
      if($to_select!=''||$org4inzone!=''){
        //print_r($to_select);
        if($to_select!=''){
          foreach ($to_select as $to_sel ) {
                $org = $this->manage->checkOrgStatus($to_sel);
                $select = "lms_emp.emp_c,lms_emp.prefix,lms_emp.fname,lms_emp.lname,lms_emp.org1,lms_emp.org2,lms_emp.org3,lms_emp.main_pos,lms_emp.status";

                $this->db->select($select);
                $this->db->distinct();
                $this->db->join('lms_pos', 'lms_pos.pos_code = lms_emp.main_pos', 'LEFT');
                if($org4inzone!=''){
                  $this->db->where('lms_emp.org4', $org4inzone);
                }
                $this->db->where('lms_emp.lang', $lang);

                if ($org=="org1"){
                    $this->db->where('lms_emp.org1', $to_sel);
                }else if($org=="org2"){
                    $this->db->where('lms_emp.org2', $to_sel);
                }else if($org=="org3"){
                    $this->db->where('lms_emp.org3', $to_sel);
                }
                $query = $this->db->get('lms_emp');
                $result = $query->result_array();
                foreach( $result as $res ){
                  array_push( $dataReturn, $res );
                }
          }
        }else{
                $select = "lms_emp.emp_c,lms_emp.prefix,lms_emp.fname,lms_emp.lname,lms_emp.org1,lms_emp.org2,lms_emp.org3,lms_emp.main_pos,lms_emp.status";

                $this->db->select($select);
                $this->db->distinct();
                $this->db->join('lms_pos', 'lms_pos.pos_code = lms_emp.main_pos', 'LEFT');
                if($org4inzone!=''){
                  $this->db->where('lms_emp.org4', $org4inzone);
                }
                $this->db->where('lms_emp.lang', $lang);
                $query = $this->db->get('lms_emp');
                $result = $query->result_array();
                foreach( $result as $res ){
                  array_push( $dataReturn, $res );
                }
        }
      }
          
      return $dataReturn;
    }

    public function getValOrg($numorg = '',$val=''){
      $this->db->select('lms_org'.$numorg.'.name')
               ->from('lms_org'.$numorg)
               ->where('lms_org'.$numorg.'.code',$val);
      $query = $this->db->get();
      $fetch = $query->row_array();
      return $fetch['name'];
    }

      public function fetch_report($lang , $org4inzone,$to_select=''){

          $lang = $this->session->userdata("lang") == null ? "thailand" : $this->session->userdata("lang") ;
          $all_emp = $this->getAllEmp( $lang,$org4inzone,$to_select);
                              
                    
          $data = array();
          $num = 1;
          foreach( $all_emp as $empset ){ 
              $this->db->where('lms_pts.emp_c', $empset['emp_c']);
              $this->db->join('lms_ptn','lms_pts.CourseCode = lms_ptn.pre_code');
              $query = $this->db->get('lms_pts');
              $result = $query->result_array();

              //print_r( $result );
              if( sizeof($result) > 0 ){ // check for registered : isRegistered
                foreach( $result as $res ){
                  $output = array(
                      $org4inzone,
                      $this->getValOrg('1',$empset['org1']),
                      $this->getValOrg('2',$empset['org2']),
                      $this->getValOrg('3',$empset['org3']),
                      $empset['emp_c'],
                      $empset['prefix'].$empset['fname']." ".$empset['lname'],
                      $res['TotalScore'],
                      $res['pre_score'],
                      $res['Score01'],
                      $res['Score02'],
                      $res['Score03'],
                      $res['Score04'],
                      $res['Score05'],
                      $res['Score06'],
                      $res['Score07'],
                      $res['Score08'],
                      $res['Score09'],
                      $res['Score10'],
                      date('d/m/Y H:i',strtotime($res['CreateDate']))
                  );

                  array_push($data, $output);
                }
              }

              $num++;
          }
          /*$learnData = $this->getLearningAll( $all_emp_must_learn, $lang );
          $learnData['mustlearn'] = sizeof( $all_emp_must_learn );
          $learnData['company'] = 'ทั้งหมด';*/
          //print_r($data);

        return $data;
      }

    public function setPTNIDforCategoryName($ptn_id,$preCodeOld)
    {
      date_default_timezone_set("Asia/Bangkok");
      $time_mod = date("Y-m-d H:i");
      $this->db->set('ptn_id',$ptn_id)
               ->set('time_mod',$time_mod)
               ->where('pre_code',$preCodeOld)
               ->update('lms_ptc');
    }

    public function updateCategoryName($data,$ptn_id)
    {
      date_default_timezone_set("Asia/Bangkok");
      $time = date('Y-m-d H:i');
      $data['time_mod'] = date("Y-m-d H:i");
      $this->db->set('pre_code',$data['pre_code'])
               ->set('category_name',$data['category_name'])
               ->where('ptn_id', $ptn_id)
               ->where('category_code',$data['category_code'])
               ->update('lms_ptc',$data);
    }

    public function updatePTUQ($pre_code,$ptn_id)
    {
      date_default_timezone_set("Asia/Bangkok");
      $time_mod = date("Y-m-d H:i");
      $this->db->set('pre_code',$pre_code)
               ->set('time_mod',$time_mod)
               ->where('ptn_id', $ptn_id)
               ->update('lms_ptu_q');
    }

    public function updatePTUN($pre_code,$ptn_id)
    {
      date_default_timezone_set("Asia/Bangkok");
      $time_mod = date("Y-m-d H:i");
      $this->db->set('pre_code',$pre_code)
               ->set('time_mod',$time_mod)
               ->where('ptn_id', $ptn_id)
               ->update('lms_ptu_n');
    }

    public function updatePTS($pre_code,$ptn_id)
    {
      date_default_timezone_set("Asia/Bangkok");
      $this->db->set('CourseCode',$pre_code)
               ->where('ptn_id', $ptn_id)
               ->update('lms_pts');
    }

    function get_data()
    {
        return $this->db->get('lms_ptn')->result();
    }
    function get_data_array()
    {
        return $this->db->get('lms_ptn')->result_array();
    }

    function get_ptq($pre_code)
    {
        $this->db->where('pre_code', $pre_code);
        $query = $this->db->get('lms_ptq');
        return $query->result();
    }

    function get_pts($pre_code,$emp_c)
    {
        $this->db->where('CourseCode', $pre_code)
                 ->where('emp_c', $emp_c)
                 ->order_by('id','desc')
                 ->limit(1);
        $query = $this->db->get('lms_pts');
        return $query->result();
    }

    function get_ptuq($pre_code,$emp_c)
    {
        $this->db->where('pre_code', $pre_code)
                 ->where('emp_c', $emp_c);
        $query = $this->db->get('lms_ptu_q');
        return $query->result();
    }

    function get_pretest_name_by_id($id= '')
    {
        $this->db->where('pre_code', $id);
        $query = $this->db->get('lms_ptn');
        return $query->result();
    }

    function get_pretest_question_by_id($id= '')
    {
      $this->db->select('lms_ptn.id,lms_ptn.pre_code,lms_ptn.pre_name,lms_ptn.pre_des,lms_ptn.pre_random, lms_ptq.id, lms_ptq.pre_code, lms_ptq.pre_no, lms_ptq.category_code, lms_ptq.question, lms_ptq.choice1, lms_ptq.choice2, lms_ptq.choice3, lms_ptq.choice4, lms_ptq.ans')
               ->from('lms_ptn')
               ->join('lms_ptq', 'lms_ptn.pre_code = lms_ptq.pre_code')
               ->where('lms_ptq.pre_code', $id);
      $query = $this->db->get();
      return $query->result();
    }

    function selectQuestionWhereCode_1($id= '')
    {
      $this->db->select('lms_ptn.id,lms_ptn.pre_code,lms_ptn.pre_name,lms_ptn.pre_des,lms_ptn.pre_random, lms_ptq.id, lms_ptq.pre_code, lms_ptq.pre_no, lms_ptq.category_code, lms_ptq.question, lms_ptq.choice1, lms_ptq.choice2, lms_ptq.choice3, lms_ptq.choice4, lms_ptq.ans')
               ->from('lms_ptn')
               ->join('lms_ptq', 'lms_ptn.pre_code = lms_ptq.pre_code')
               ->where('lms_ptq.pre_code', $id)
               ->where('lms_ptq.category_code', 1);
      $query = $this->db->get();
      return $query->result();
    }

    function selectQuestionWhereCode_2($id= '')
    {
      $this->db->select('lms_ptn.id,lms_ptn.pre_code,lms_ptn.pre_name,lms_ptn.pre_des,lms_ptn.pre_random, lms_ptq.id, lms_ptq.pre_code, lms_ptq.pre_no, lms_ptq.category_code, lms_ptq.question, lms_ptq.choice1, lms_ptq.choice2, lms_ptq.choice3, lms_ptq.choice4, lms_ptq.ans')
               ->from('lms_ptn')
               ->join('lms_ptq', 'lms_ptn.pre_code = lms_ptq.pre_code')
               ->where('lms_ptq.pre_code', $id)
               ->where('lms_ptq.category_code', 2);
      $query = $this->db->get();
      return $query->result();
    }

    function selectQuestionWhereCode_3($id= '')
    {
      $this->db->select('lms_ptn.id,lms_ptn.pre_code,lms_ptn.pre_name,lms_ptn.pre_des,lms_ptn.pre_random, lms_ptq.id, lms_ptq.pre_code, lms_ptq.pre_no, lms_ptq.category_code, lms_ptq.question, lms_ptq.choice1, lms_ptq.choice2, lms_ptq.choice3, lms_ptq.choice4, lms_ptq.ans')
               ->from('lms_ptn')
               ->join('lms_ptq', 'lms_ptn.pre_code = lms_ptq.pre_code')
               ->where('lms_ptq.pre_code', $id)
               ->where('lms_ptq.category_code', 3);
      $query = $this->db->get();
      return $query->result();
    }

    function selectQuestionWhereCode_4($id= '')
    {
      $this->db->select('lms_ptn.id,lms_ptn.pre_code,lms_ptn.pre_name,lms_ptn.pre_des,lms_ptn.pre_random, lms_ptq.id, lms_ptq.pre_code, lms_ptq.pre_no, lms_ptq.category_code, lms_ptq.question, lms_ptq.choice1, lms_ptq.choice2, lms_ptq.choice3, lms_ptq.choice4, lms_ptq.ans')
               ->from('lms_ptn')
               ->join('lms_ptq', 'lms_ptn.pre_code = lms_ptq.pre_code')
               ->where('lms_ptq.pre_code', $id)
               ->where('lms_ptq.category_code', 4);
      $query = $this->db->get();
      return $query->result();
    }

    function selectQuestionWhereCode_5($id= '')
    {
      $this->db->select('lms_ptn.id,lms_ptn.pre_code,lms_ptn.pre_name,lms_ptn.pre_des,lms_ptn.pre_random, lms_ptq.id, lms_ptq.pre_code, lms_ptq.pre_no, lms_ptq.category_code, lms_ptq.question, lms_ptq.choice1, lms_ptq.choice2, lms_ptq.choice3, lms_ptq.choice4, lms_ptq.ans')
               ->from('lms_ptn')
               ->join('lms_ptq', 'lms_ptn.pre_code = lms_ptq.pre_code')
               ->where('lms_ptq.pre_code', $id)
               ->where('lms_ptq.category_code', 5);
      $query = $this->db->get();
      return $query->result();
    }


    function selectQuestionWhereCode_6($id= '')
    {
      $this->db->select('lms_ptn.id,lms_ptn.pre_code,lms_ptn.pre_name,lms_ptn.pre_des,lms_ptn.pre_random, lms_ptq.id, lms_ptq.pre_code, lms_ptq.pre_no, lms_ptq.category_code, lms_ptq.question, lms_ptq.choice1, lms_ptq.choice2, lms_ptq.choice3, lms_ptq.choice4, lms_ptq.ans')
               ->from('lms_ptn')
               ->join('lms_ptq', 'lms_ptn.pre_code = lms_ptq.pre_code')
               ->where('lms_ptq.pre_code', $id)
               ->where('lms_ptq.category_code', 6);
      $query = $this->db->get();
      return $query->result();
    }

    function selectQuestionWhereCode_7($id= '')
    {
      $this->db->select('lms_ptn.id,lms_ptn.pre_code,lms_ptn.pre_name,lms_ptn.pre_des,lms_ptn.pre_random, lms_ptq.id, lms_ptq.pre_code, lms_ptq.pre_no, lms_ptq.category_code, lms_ptq.question, lms_ptq.choice1, lms_ptq.choice2, lms_ptq.choice3, lms_ptq.choice4, lms_ptq.ans')
               ->from('lms_ptn')
               ->join('lms_ptq', 'lms_ptn.pre_code = lms_ptq.pre_code')
               ->where('lms_ptq.pre_code', $id)
               ->where('lms_ptq.category_code', 7);
      $query = $this->db->get();
      return $query->result();
    }

    function selectQuestionWhereCode_8($id= '')
    {
      $this->db->select('lms_ptn.id,lms_ptn.pre_code,lms_ptn.pre_name,lms_ptn.pre_des,lms_ptn.pre_random, lms_ptq.id, lms_ptq.pre_code, lms_ptq.pre_no, lms_ptq.category_code, lms_ptq.question, lms_ptq.choice1, lms_ptq.choice2, lms_ptq.choice3, lms_ptq.choice4, lms_ptq.ans')
               ->from('lms_ptn')
               ->join('lms_ptq', 'lms_ptn.pre_code = lms_ptq.pre_code')
               ->where('lms_ptq.pre_code', $id)
               ->where('lms_ptq.category_code', 8);
      $query = $this->db->get();
      return $query->result();
    }

    function selectQuestionWhereCode_9($id= '')
    {
      $this->db->select('lms_ptn.id,lms_ptn.pre_code,lms_ptn.pre_name,lms_ptn.pre_des,lms_ptn.pre_random, lms_ptq.id, lms_ptq.pre_code, lms_ptq.pre_no, lms_ptq.category_code, lms_ptq.question, lms_ptq.choice1, lms_ptq.choice2, lms_ptq.choice3, lms_ptq.choice4, lms_ptq.ans')
               ->from('lms_ptn')
               ->join('lms_ptq', 'lms_ptn.pre_code = lms_ptq.pre_code')
               ->where('lms_ptq.pre_code', $id)
               ->where('lms_ptq.category_code', 9);
      $query = $this->db->get();
      return $query->result();
    }

    function selectQuestionWhereCode_10($id= '')
    {
      $this->db->select('lms_ptn.id,lms_ptn.pre_code,lms_ptn.pre_name,lms_ptn.pre_des,lms_ptn.pre_random, lms_ptq.id, lms_ptq.pre_code, lms_ptq.pre_no, lms_ptq.category_code, lms_ptq.question, lms_ptq.choice1, lms_ptq.choice2, lms_ptq.choice3, lms_ptq.choice4, lms_ptq.ans')
               ->from('lms_ptn')
               ->join('lms_ptq', 'lms_ptn.pre_code = lms_ptq.pre_code')
               ->where('lms_ptq.pre_code', $id)
               ->where('lms_ptq.category_code', 10);
      $query = $this->db->get();
      return $query->result();
    }


    function selectCategoryWhereCode_1($id= '')
    {
      $this->db->select('*')
               ->from('lms_ptq')
               ->join('lms_ptc', 'lms_ptq.pre_code = lms_ptc.pre_code')
               ->where('lms_ptc.pre_code', $id)
               ->where('lms_ptc.category_code', 1);
      $query = $this->db->get('',1);
      return $query->result();
    }

    function selectCategoryWhereCode_2($id= '')
    {
      $this->db->select('*')
               ->from('lms_ptq')
               ->join('lms_ptc', 'lms_ptq.pre_code = lms_ptc.pre_code')
               ->where('lms_ptc.pre_code', $id)
               ->where('lms_ptc.category_code', 2);
      $query = $this->db->get('',1);
      return $query->result();
    }

    function selectCategoryWhereCode_3($id= '')
    {
      $this->db->select('*')
               ->from('lms_ptq')
               ->join('lms_ptc', 'lms_ptq.pre_code = lms_ptc.pre_code')
               ->where('lms_ptc.pre_code', $id)
               ->where('lms_ptc.category_code', 3);
      $query = $this->db->get('',1);
      return $query->result();
    }

    function selectCategoryWhereCode_4($id= '')
    {
      $this->db->select('*')
               ->from('lms_ptq')
               ->join('lms_ptc', 'lms_ptq.pre_code = lms_ptc.pre_code')
               ->where('lms_ptc.pre_code', $id)
               ->where('lms_ptc.category_code', 4);
      $query = $this->db->get('',1);
      return $query->result();
    }

    function selectCategoryWhereCode_5($id= '')
    {
      $this->db->select('*')
               ->from('lms_ptq')
               ->join('lms_ptc', 'lms_ptq.pre_code = lms_ptc.pre_code')
               ->where('lms_ptc.pre_code', $id)
               ->where('lms_ptc.category_code', 5);
      $query = $this->db->get('',1);
      return $query->result();
    }

    function selectCategoryWhereCode_6($id= '')
    {
      $this->db->select('*')
               ->from('lms_ptq')
               ->join('lms_ptc', 'lms_ptq.pre_code = lms_ptc.pre_code')
               ->where('lms_ptc.pre_code', $id)
               ->where('lms_ptc.category_code', 6);
      $query = $this->db->get('',1);
      return $query->result();
    }

    function selectCategoryWhereCode_7($id= '')
    {
      $this->db->select('*')
               ->from('lms_ptq')
               ->join('lms_ptc', 'lms_ptq.pre_code = lms_ptc.pre_code')
               ->where('lms_ptc.pre_code', $id)
               ->where('lms_ptc.category_code', 7);
      $query = $this->db->get('',1);
      return $query->result();
    }

    function selectCategoryWhereCode_8($id= '')
    {
      $this->db->select('*')
               ->from('lms_ptq')
               ->join('lms_ptc', 'lms_ptq.pre_code = lms_ptc.pre_code')
               ->where('lms_ptc.pre_code', $id)
               ->where('lms_ptc.category_code', 8);
      $query = $this->db->get('',1);
      return $query->result();
    }

    function selectCategoryWhereCode_9($id= '')
    {
      $this->db->select('*')
               ->from('lms_ptq')
               ->join('lms_ptc', 'lms_ptq.pre_code = lms_ptc.pre_code')
               ->where('lms_ptc.pre_code', $id)
               ->where('lms_ptc.category_code', 9);
      $query = $this->db->get('',1);
      return $query->result();
    }

    function selectCategoryWhereCode_10($id= '')
    {
      $this->db->select('*')
               ->from('lms_ptq')
               ->join('lms_ptc', 'lms_ptq.pre_code = lms_ptc.pre_code')
               ->where('lms_ptc.pre_code', $id)
               ->where('lms_ptc.category_code', 10);
      $query = $this->db->get('',1);
      return $query->result();
    }

    function checkUserAnswer_1($id= '',$emp_c,$time)
    {
      $this->db->select('*')
               ->from('lms_ptn')
               ->join('lms_ptq', 'lms_ptn.pre_code = lms_ptq.pre_code')
               ->join('lms_ptu_q', 'lms_ptu_q.pre_no = lms_ptq.pre_no')
               ->where('lms_ptq.pre_code = lms_ptu_q.pre_code')
               ->where('lms_ptq.pre_no = lms_ptu_q.pre_no')
               ->where('lms_ptq.category_code = lms_ptu_q.category_code')
               ->where('lms_ptu_q.question_id = lms_ptq.id')
               ->where('lms_ptu_q.emp_c', $emp_c)
               ->where('lms_ptu_q.pre_code', $id)
               ->where('lms_ptu_q.category_code', 1)
               ->where('lms_ptu_q.time', $time);
      $query = $this->db->get();
      return $query->result();
    }

    function checkUserAnswer_2($id= '',$emp_c,$time)
    {
      $this->db->select('*')
               ->from('lms_ptn')
               ->join('lms_ptq', 'lms_ptn.pre_code = lms_ptq.pre_code')
               ->join('lms_ptu_q', 'lms_ptu_q.pre_no = lms_ptq.pre_no')
               ->where('lms_ptq.pre_code = lms_ptu_q.pre_code')
               ->where('lms_ptq.pre_no = lms_ptu_q.pre_no')
               ->where('lms_ptq.category_code = lms_ptu_q.category_code')
               ->where('lms_ptu_q.question_id = lms_ptq.id')
               ->where('lms_ptu_q.emp_c', $emp_c)
               ->where('lms_ptu_q.pre_code', $id)
               ->where('lms_ptu_q.category_code', 2)
               ->where('lms_ptu_q.time', $time);
      $query = $this->db->get();
      return $query->result();
    }

    function checkUserAnswer_3($id= '',$emp_c,$time)
    {
      $this->db->select('*')
               ->from('lms_ptn')
               ->join('lms_ptq', 'lms_ptn.pre_code = lms_ptq.pre_code')
               ->join('lms_ptu_q', 'lms_ptu_q.pre_no = lms_ptq.pre_no')
               ->where('lms_ptq.pre_code = lms_ptu_q.pre_code')
               ->where('lms_ptq.pre_no = lms_ptu_q.pre_no')
               ->where('lms_ptq.category_code = lms_ptu_q.category_code')
               ->where('lms_ptu_q.question_id = lms_ptq.id')
               ->where('lms_ptu_q.emp_c', $emp_c)
               ->where('lms_ptu_q.pre_code', $id)
               ->where('lms_ptu_q.category_code', 3)
               ->where('lms_ptu_q.time', $time);
      $query = $this->db->get();
      return $query->result();
    }

    function checkUserAnswer_4($id= '',$emp_c,$time)
    {
      $this->db->select('*')
               ->from('lms_ptn')
               ->join('lms_ptq', 'lms_ptn.pre_code = lms_ptq.pre_code')
               ->join('lms_ptu_q', 'lms_ptu_q.pre_no = lms_ptq.pre_no')
               ->where('lms_ptq.pre_code = lms_ptu_q.pre_code')
               ->where('lms_ptq.pre_no = lms_ptu_q.pre_no')
               ->where('lms_ptq.category_code = lms_ptu_q.category_code')
               ->where('lms_ptu_q.question_id = lms_ptq.id')
               ->where('lms_ptu_q.emp_c', $emp_c)
               ->where('lms_ptu_q.pre_code', $id)
               ->where('lms_ptu_q.category_code', 4)
               ->where('lms_ptu_q.time', $time);
      $query = $this->db->get();
      return $query->result();
    }

    function checkUserAnswer_5($id= '',$emp_c,$time)
    {
      $this->db->select('*')
               ->from('lms_ptn')
               ->join('lms_ptq', 'lms_ptn.pre_code = lms_ptq.pre_code')
               ->join('lms_ptu_q', 'lms_ptu_q.pre_no = lms_ptq.pre_no')
               ->where('lms_ptq.pre_code = lms_ptu_q.pre_code')
               ->where('lms_ptq.pre_no = lms_ptu_q.pre_no')
               ->where('lms_ptq.category_code = lms_ptu_q.category_code')
               ->where('lms_ptu_q.question_id = lms_ptq.id')
               ->where('lms_ptu_q.emp_c', $emp_c)
               ->where('lms_ptu_q.pre_code', $id)
               ->where('lms_ptu_q.category_code', 5)
               ->where('lms_ptu_q.time', $time);
      $query = $this->db->get();
      return $query->result();
    }

    function checkUserAnswer_6($id= '',$emp_c,$time)
    {
      $this->db->select('*')
               ->from('lms_ptn')
               ->join('lms_ptq', 'lms_ptn.pre_code = lms_ptq.pre_code')
               ->join('lms_ptu_q', 'lms_ptu_q.pre_no = lms_ptq.pre_no')
               ->where('lms_ptq.pre_code = lms_ptu_q.pre_code')
               ->where('lms_ptq.pre_no = lms_ptu_q.pre_no')
               ->where('lms_ptq.category_code = lms_ptu_q.category_code')
               ->where('lms_ptu_q.question_id = lms_ptq.id')
               ->where('lms_ptu_q.emp_c', $emp_c)
               ->where('lms_ptu_q.pre_code', $id)
               ->where('lms_ptu_q.category_code', 6)
               ->where('lms_ptu_q.time', $time);
      $query = $this->db->get();
      return $query->result();
    }

    function checkUserAnswer_7($id= '',$emp_c,$time)
    {
      $this->db->select('*')
               ->from('lms_ptn')
               ->join('lms_ptq', 'lms_ptn.pre_code = lms_ptq.pre_code')
               ->join('lms_ptu_q', 'lms_ptu_q.pre_no = lms_ptq.pre_no')
               ->where('lms_ptq.pre_code = lms_ptu_q.pre_code')
               ->where('lms_ptq.pre_no = lms_ptu_q.pre_no')
               ->where('lms_ptq.category_code = lms_ptu_q.category_code')
               ->where('lms_ptu_q.question_id = lms_ptq.id')
               ->where('lms_ptu_q.emp_c', $emp_c)
               ->where('lms_ptu_q.pre_code', $id)
               ->where('lms_ptu_q.category_code', 7)
               ->where('lms_ptu_q.time', $time);
      $query = $this->db->get();
      return $query->result();
    }

    function checkUserAnswer_8($id= '',$emp_c,$time)
    {
      $this->db->select('*')
               ->from('lms_ptn')
               ->join('lms_ptq', 'lms_ptn.pre_code = lms_ptq.pre_code')
               ->join('lms_ptu_q', 'lms_ptu_q.pre_no = lms_ptq.pre_no')
               ->where('lms_ptq.pre_code = lms_ptu_q.pre_code')
               ->where('lms_ptq.pre_no = lms_ptu_q.pre_no')
               ->where('lms_ptq.category_code = lms_ptu_q.category_code')
               ->where('lms_ptu_q.question_id = lms_ptq.id')
               ->where('lms_ptu_q.emp_c', $emp_c)
               ->where('lms_ptu_q.pre_code', $id)
               ->where('lms_ptu_q.category_code', 8)
               ->where('lms_ptu_q.time', $time);
      $query = $this->db->get();
      return $query->result();
    }

    function checkUserAnswer_9($id= '',$emp_c,$time)
    {
      $this->db->select('*')
               ->from('lms_ptn')
               ->join('lms_ptq', 'lms_ptn.pre_code = lms_ptq.pre_code')
               ->join('lms_ptu_q', 'lms_ptu_q.pre_no = lms_ptq.pre_no')
               ->where('lms_ptq.pre_code = lms_ptu_q.pre_code')
               ->where('lms_ptq.pre_no = lms_ptu_q.pre_no')
               ->where('lms_ptq.category_code = lms_ptu_q.category_code')
               ->where('lms_ptu_q.question_id = lms_ptq.id')
               ->where('lms_ptu_q.emp_c', $emp_c)
               ->where('lms_ptu_q.pre_code', $id)
               ->where('lms_ptu_q.category_code', 9)
               ->where('lms_ptu_q.time', $time);
      $query = $this->db->get();
      return $query->result();
    }

    function checkUserAnswer_10($id= '',$emp_c,$time)
    {
      $this->db->select('*')
               ->from('lms_ptn')
               ->join('lms_ptq', 'lms_ptn.pre_code = lms_ptq.pre_code')
               ->join('lms_ptu_q', 'lms_ptu_q.pre_no = lms_ptq.pre_no')
               ->where('lms_ptq.pre_code = lms_ptu_q.pre_code')
               ->where('lms_ptq.pre_no = lms_ptu_q.pre_no')
               ->where('lms_ptq.category_code = lms_ptu_q.category_code')
               ->where('lms_ptu_q.question_id = lms_ptq.id')
               ->where('lms_ptu_q.emp_c', $emp_c)
               ->where('lms_ptu_q.pre_code', $id)
               ->where('lms_ptu_q.category_code', 10)
               ->where('lms_ptu_q.time', $time);
      $query = $this->db->get();
      return $query->result();
    }

    public function insertUserDataWithPretestName($data)
    {
      date_default_timezone_set("Asia/Bangkok");
      $time = date('Y-m-d H:i');
      $data['time_create'] = date("Y-m-d H:i");
      $this->db->insert('lms_ptu_n', $data);
    }

    public function selectBy_precode_empc($code,$emp_c)
    {
      $this->db->select('lms_ptu_n.emp_c,lms_ptu_n.pre_code')
              ->from('lms_ptu_n')
              ->where('lms_ptu_n.pre_code', $code)
              ->where('lms_ptu_n.emp_c', $emp_c);
      $query = $this->db->get();
      return $query->result();
    }

    public function insertUsertPretestQuestion($data)
    {
      date_default_timezone_set("Asia/Bangkok");
      $time = date('Y-m-d H:i');
      $data['time_create'] = date("Y-m-d H:i");
      $data['time_mod'] = date("Y-m-d H:i");
      $this->db->insert('lms_ptu_q', $data);
    }

    public function updateUsertPretestQuestion($data,$emp_c)
    {
      date_default_timezone_set("Asia/Bangkok");
      $time = date('Y-m-d H:i');
      $data['time_mod'] = date("Y-m-d H:i");
      $this->db->where('emp_c', $emp_c)
               ->where('question_id', $data['question_id'])
               ->where('time', $data['time']);
      $this->db->update('lms_ptu_q', $data);
    }

    function check_answer($pre_code,$pre_id)
    {
      $this->db->select('lms_ptq.pre_code, lms_ptq.pre_no, lms_ptq.ans')
               ->from('lms_ptq')
               ->where('lms_ptq.pre_code', $pre_code)
               ->where('lms_ptq.id', $pre_id);
        $query = $this->db->get();
        return $query->result();
    }

    function getUserAnswer($pre_code,$emp_c,$time) //for report page
    {
      $this->db->select('lms_ptu_q.pre_code, lms_ptu_q.pre_no, lms_ptu_q.user_ans, lms_ptu_q.real_ans')
               ->from('lms_ptu_q')
               ->where('lms_ptu_q.pre_code', $pre_code)
               ->where('lms_ptu_q.emp_c', $emp_c)
               ->where('lms_ptu_q.time', $time);
        $query = $this->db->get();
        return $query->result();
    }

    function getFullScore($pre_code) //for report page
    {
      $this->db->select('lms_ptq.pre_code, lms_ptq.pre_no')
               ->from('lms_ptq')
               ->where('lms_ptq.pre_code', $pre_code);
        $query = $this->db->get();
        return $query->result();
    }

    function getUserScore($pre_code,$emp_c,$time)
    {
      $this->db->select('lms_ptu_q.emp_c ,lms_ptu_q.pre_code, lms_ptu_q.user_ans, lms_ptu_q.real_ans')
               ->from('lms_ptu_q')
               ->where('lms_ptu_q.emp_c', $emp_c)
               ->where('lms_ptu_q.pre_code', $pre_code)
               ->where('lms_ptu_q.user_ans = lms_ptu_q.real_ans')
               ->where('lms_ptu_q.time', $time);
        $query = $this->db->get();
        return $query->result();
    }

    function getUserScoreByCategory_1($pre_code,$emp_c,$time)
    {
      $this->db->select('lms_ptu_q.emp_c ,lms_ptu_q.pre_code ,lms_ptu_q.category_code, lms_ptu_q.user_ans, lms_ptu_q.real_ans')
               ->from('lms_ptu_q')
               ->where('lms_ptu_q.emp_c', $emp_c)
               ->where('lms_ptu_q.pre_code', $pre_code)
               ->where('lms_ptu_q.category_code', 1)
               ->where('lms_ptu_q.user_ans = lms_ptu_q.real_ans')
               ->where('lms_ptu_q.time', $time);
        $query = $this->db->get();
        return $query->result();
    }

    function getUserScoreByCategory_2($pre_code,$emp_c,$time)
    {
      $this->db->select('lms_ptu_q.emp_c ,lms_ptu_q.pre_code ,lms_ptu_q.category_code, lms_ptu_q.user_ans, lms_ptu_q.real_ans')
               ->from('lms_ptu_q')
               ->where('lms_ptu_q.emp_c', $emp_c)
               ->where('lms_ptu_q.pre_code', $pre_code)
               ->where('lms_ptu_q.category_code', 2)
               ->where('lms_ptu_q.user_ans = lms_ptu_q.real_ans')
               ->where('lms_ptu_q.time', $time);
        $query = $this->db->get();
        return $query->result();
    }

    function getUserScoreByCategory_3($pre_code,$emp_c,$time)
    {
      $this->db->select('lms_ptu_q.emp_c ,lms_ptu_q.pre_code ,lms_ptu_q.category_code, lms_ptu_q.user_ans, lms_ptu_q.real_ans')
               ->from('lms_ptu_q')
               ->where('lms_ptu_q.emp_c', $emp_c)
               ->where('lms_ptu_q.pre_code', $pre_code)
               ->where('lms_ptu_q.category_code', 3)
               ->where('lms_ptu_q.user_ans = lms_ptu_q.real_ans')
               ->where('lms_ptu_q.time', $time);
        $query = $this->db->get();
        return $query->result();
    }

    function getUserScoreByCategory_4($pre_code,$emp_c,$time)
    {
      $this->db->select('lms_ptu_q.emp_c ,lms_ptu_q.pre_code ,lms_ptu_q.category_code, lms_ptu_q.user_ans, lms_ptu_q.real_ans')
               ->from('lms_ptu_q')
               ->where('lms_ptu_q.emp_c', $emp_c)
               ->where('lms_ptu_q.pre_code', $pre_code)
               ->where('lms_ptu_q.category_code', 4)
               ->where('lms_ptu_q.user_ans = lms_ptu_q.real_ans')
               ->where('lms_ptu_q.time', $time);
        $query = $this->db->get();
        return $query->result();
    }

    function getUserScoreByCategory_5($pre_code,$emp_c,$time)
    {
      $this->db->select('lms_ptu_q.emp_c ,lms_ptu_q.pre_code ,lms_ptu_q.category_code, lms_ptu_q.user_ans, lms_ptu_q.real_ans')
               ->from('lms_ptu_q')
               ->where('lms_ptu_q.emp_c', $emp_c)
               ->where('lms_ptu_q.pre_code', $pre_code)
               ->where('lms_ptu_q.category_code', 5)
               ->where('lms_ptu_q.user_ans = lms_ptu_q.real_ans')
               ->where('lms_ptu_q.time', $time);
        $query = $this->db->get();
        return $query->result();
    }

    function getUserScoreByCategory_6($pre_code,$emp_c,$time)
    {
      $this->db->select('lms_ptu_q.emp_c ,lms_ptu_q.pre_code ,lms_ptu_q.category_code, lms_ptu_q.user_ans, lms_ptu_q.real_ans')
               ->from('lms_ptu_q')
               ->where('lms_ptu_q.emp_c', $emp_c)
               ->where('lms_ptu_q.pre_code', $pre_code)
               ->where('lms_ptu_q.category_code', 6)
               ->where('lms_ptu_q.user_ans = lms_ptu_q.real_ans')
               ->where('lms_ptu_q.time', $time);
        $query = $this->db->get();
        return $query->result();
    }

    function getUserScoreByCategory_7($pre_code,$emp_c,$time)
    {
      $this->db->select('lms_ptu_q.emp_c ,lms_ptu_q.pre_code ,lms_ptu_q.category_code, lms_ptu_q.user_ans, lms_ptu_q.real_ans')
               ->from('lms_ptu_q')
               ->where('lms_ptu_q.emp_c', $emp_c)
               ->where('lms_ptu_q.pre_code', $pre_code)
               ->where('lms_ptu_q.category_code', 7)
               ->where('lms_ptu_q.user_ans = lms_ptu_q.real_ans')
               ->where('lms_ptu_q.time', $time);
        $query = $this->db->get();
        return $query->result();
    }

    function getUserScoreByCategory_8($pre_code,$emp_c,$time)
    {
      $this->db->select('lms_ptu_q.emp_c ,lms_ptu_q.pre_code ,lms_ptu_q.category_code, lms_ptu_q.user_ans, lms_ptu_q.real_ans')
               ->from('lms_ptu_q')
               ->where('lms_ptu_q.emp_c', $emp_c)
               ->where('lms_ptu_q.pre_code', $pre_code)
               ->where('lms_ptu_q.category_code', 8)
               ->where('lms_ptu_q.user_ans = lms_ptu_q.real_ans')
               ->where('lms_ptu_q.time', $time);
        $query = $this->db->get();
        return $query->result();
    }

    function getUserScoreByCategory_9($pre_code,$emp_c,$time)
    {
      $this->db->select('lms_ptu_q.emp_c ,lms_ptu_q.pre_code ,lms_ptu_q.category_code, lms_ptu_q.user_ans, lms_ptu_q.real_ans')
               ->from('lms_ptu_q')
               ->where('lms_ptu_q.emp_c', $emp_c)
               ->where('lms_ptu_q.pre_code', $pre_code)
               ->where('lms_ptu_q.category_code', 9)
               ->where('lms_ptu_q.user_ans = lms_ptu_q.real_ans')
               ->where('lms_ptu_q.time', $time);
        $query = $this->db->get();
        return $query->result();
    }

    function getUserScoreByCategory_10($pre_code,$emp_c,$time)
    {
      $this->db->select('lms_ptu_q.emp_c ,lms_ptu_q.pre_code ,lms_ptu_q.category_code, lms_ptu_q.user_ans, lms_ptu_q.real_ans')
               ->from('lms_ptu_q')
               ->where('lms_ptu_q.emp_c', $emp_c)
               ->where('lms_ptu_q.pre_code', $pre_code)
               ->where('lms_ptu_q.category_code', 10)
               ->where('lms_ptu_q.user_ans = lms_ptu_q.real_ans')
               ->where('lms_ptu_q.time', $time);
        $query = $this->db->get();
        return $query->result();
    }

    public function insertUserScore($data)
    {
      date_default_timezone_set("Asia/Bangkok");
      $data['CreateDate'] = date("Y-m-d H:i");
      $this->db->insert('lms_pts', $data);
    }

    public function get_pts_id_from_lms_pts($data)
    {
      $this->db->select('*')
               ->from('lms_pts')
               ->where('lms_pts.emp_c', $data['emp_c'])
               ->where('lms_pts.CourseCode', $data['CourseCode'])
               ->order_by('lms_pts.id','DESC')
               ->limit(1);
      $query = $this->db->get();
      return $query->result();
    }

    public function insertPTUN_pts_id($get_pts_id,$data)
    {
      date_default_timezone_set("Asia/Bangkok");
      $time = date('Y-m-d H:i');
      $this->db->where('pre_code', $data['CourseCode'])
               ->where('emp_c', $data['emp_c'])
               ->set('pts_id', $get_pts_id)
               ->set('time_mod', $time)
               ->update('lms_ptu_n');
    }

    public function checkAlready($emp_c)
    {
      $this->db->select('lms_ptn.pre_code, lms_ptn.pre_name, lms_ptn.pre_score, lms_pts.id, lms_pts.emp_c, lms_pts.CourseCode, lms_pts.TotalScore, lms_pts.CreateDate')
               ->from('lms_ptn')
               ->join('lms_pts', 'lms_pts.CourseCode = lms_ptn.pre_code')
               // ->group_by('lms_pts.CourseCode')
               // ->order_by('lms_pts.id','DESC')
               ->where('lms_pts.emp_c', $emp_c);
      $query = $this->db->get();
      return $query->result();
    }

    public function getUserScoreFromPTUN($emp_c)
    {
      $this->db->select('*')
               ->from('lms_ptu_n')
               ->where('emp_c', $emp_c);
      $query = $this->db->get();

      return $query->result();
    }

    public function check_pts($emp_c)
    {
      $this->db->select('lms_pts.emp_c, lms_pts.CourseCode')
               ->from('lms_pts')
               ->where('lms_pts.emp_c', $emp_c);
      $query = $this->db->get();
      return $query->result();
    }

    public function check_ptun($emp_c)
    {
      $this->db->select('lms_ptu_n.emp_c, lms_ptu_n.pre_code')
               ->from('lms_ptu_n')
               ->where('lms_ptu_n.emp_c', $emp_c);
      $query = $this->db->get();
      return $query->result();
    }

    public function checkAlreadyByemp_c($emp_c,$testIntersect)
    {
      $this->db->select('lms_ptn.id, lms_ptn.pre_code, lms_ptn.pre_name, lms_ptn.pre_des, lms_ptn.pre_random, lms_pts.emp_c, lms_pts.CourseCode')
               ->from('lms_pts')
               ->join('lms_ptn', 'lms_pts.CourseCode = lms_ptn.pre_code' ,'Right')
               ->where('lms_ptn.pre_code =' ,$testIntersect);
     $query = $this->db->get();
     return $query->result();
    }

    function viewFullScore($pre_code,$emp_c) //for view page
    {
      $this->db->select('lms_ptq.pre_code, lms_ptq.pre_no')
               ->from('lms_ptq')
               ->where('lms_ptq.pre_code', $pre_code);
        $query = $this->db->get();
        return $query->result();
    }

    function checkRandom($pre_code)
    {
        $this->db->select('lms_ptn.pre_code, lms_ptn.pre_name, lms_ptn.pre_score, lms_ptn.pre_des, lms_ptn.pre_random')
                 ->from('lms_ptn')
                 ->where('pre_code', $pre_code);
        $query = $this->db->get();
        return $query->result();
    }

    function checkFullScore($pre_code)
    {
        $this->db->select('lms_ptq.pre_code, lms_ptq.pre_no')
                 ->from('lms_ptq')
                 ->where('lms_ptq.pre_code', $pre_code)
                 ->where('lms_ptq.pre_no != 0');
        $query = $this->db->get();
        return $query->result();
    }

    function updateFullScore($pre_code,$fullscore,$mod_by)
    {
      date_default_timezone_set("Asia/Bangkok");
      $time_mod = date("Y-m-d H:i");

      $this->db->where('pre_code', $pre_code)
               ->set('pre_score',$fullscore)
               ->set('time_mod',$time_mod)
               ->set('mod_by',$mod_by)
               ->update('lms_ptn');
    }

    function removeFromPTQ($pre_code,$category_code,$pre_no)
    {
      $this->db->where('pre_code',$pre_code)
               ->where('category_code',$category_code)
               ->where('pre_no',$pre_no)
               ->delete('lms_ptq');
    }

    function checkOldNo($pre_code,$category_code)
    {
      $this->db->select('*')
               ->from('lms_ptq')
               ->where('lms_ptq.pre_code',$pre_code)
               ->where('lms_ptq.category_code',$category_code);
      $query = $this->db->get();
      return $query->result();
    }

    function runNewNo($data)
    {
      date_default_timezone_set("Asia/Bangkok");
      $data['time_mod'] = date('Y-m-d H:i');
      $this->db->where('id', $data['id'])
               ->where('pre_code', $data['pre_code'])
               ->where('category_code', $data['category_code'])
               ->update('lms_ptq', $data);
    }

    public function insertZero($pre_code)
    {
      $this->db->set('pre_code', $pre_code)
               ->set('pre_no',0)
               ->set('category_code',0)
               ->insert('lms_ptq');
    }

    public function removeZero($pre_code)
    {
      $this->db->where('pre_code',$pre_code)
               ->where('pre_no',0)
               ->where('category_code',0)
               ->delete('lms_ptq');
    }

    public function deletePretest($pre_code)
    {
      $this->db->where('pre_code',$pre_code)
               ->delete('lms_ptn');
      $this->db->where('pre_code',$pre_code)
               ->delete('lms_ptc');
      $this->db->where('pre_code',$pre_code)
               ->delete('lms_ptq');
    }

    public function generateCode()
    {
      $this->db->order_by('id',"desc")
               ->limit(1);
      $query = $this->db->get('lms_ptn');
      return $query->result();
    }

    public function CheckIdAvailable($data)
    {
     $this->db->where('id', $data);
     $query = $this->db->get('lms_ptn');
     if($query->num_rows() > 0)
     return $query->result();
    }
    
    public function CheckCodeAvailable($data)
    {
     $this->db->where('pre_code', $data);
     $query = $this->db->get('lms_ptn');
     if($query->num_rows() > 0)
     return $query->result();
    }

    public function removeDataFromPTU_Q($emp_c,$pre_code)
    {
      $this->db->where('pre_code',$pre_code)
               ->where('emp_c',$emp_c)
               ->delete('lms_ptu_q');
    }

    public function checkUserPTS($emp_c,$pre_code){
      $this->db->where('CourseCode', $pre_code)
               ->where('emp_c', $emp_c);
      $query = $this->db->get('lms_pts');
      return $query->result();
    }

    public function getPTS($emp_c,$pre_code){
      $this->db->where('emp_c', $emp_c)
               ->where('CourseCode', $pre_code);
      $query = $this->db->get('lms_pts');
      return $query->result();
    }

    public function checkPretestTime($pre_code,$emp_c){
      $this->db->select('time')
               ->from('lms_ptu_q')
               ->where('pre_code',$pre_code)
               ->where('emp_c',$emp_c)
               ->limit(1)
               ->order_by('id','DESC');
      $query = $this->db->get();
      return $query->result();         
    }
}
