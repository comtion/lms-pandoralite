<?php
class Course_model extends CI_model {
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

  public function course_availability_where($alias = 'lms_cos_detail')
  {
      $now = date('Y-m-d H:i');
      return "((".$alias.".date_start IS NULL OR CAST(".$alias.".date_start AS CHAR) = '' OR ".$alias.".date_start = '0000-00-00 00:00:00' OR ".$alias.".date_start <= '".$now."')"
          ." AND (".$alias.".date_end IS NULL OR CAST(".$alias.".date_end AS CHAR) = '' OR ".$alias.".date_end = '0000-00-00 00:00:00' OR ".$alias.".date_end >= '".$now."'))";
  }

  public function search_course_data($search_txt){
      date_default_timezone_set("Asia/Bangkok");
      $user = $this->session->userdata('user');
      $this->db->distinct();
      $this->db->select('lms_cos.id,lms_cos.cname_th,lms_cos.cname_en,lms_cos.pic');
      $this->db->from('lms_cos');
      $this->db->where('lms_cos.status','1');
      if($user['ug_id']!=""){
        $this->db->join('lms_cos_detail','lms_cos.id = lms_cos_detail.cos_id');
        $this->db->join('lms_cos_detail_ug','lms_cos_detail.cosde_id = lms_cos_detail_ug.cosde_id');
        $this->db->where('lms_cos_detail_ug.ug_id', $user['ug_id'] );
      }
      if($user['com_id']!=""){
        $this->db->where('lms_cos.com_id', $user['com_id'] );
        $where = $this->course_availability_where('lms_cos_detail');
        $this->db->where($where);
      }else{
        $this->db->where('lms_cos.com_id', '2' );
      }
      $search_txt = trim((string) $search_txt);
      $this->db->group_start();
      $this->db->like('lms_cos.cname_th', $search_txt);
      $this->db->or_like('lms_cos.cname_en', $search_txt);
      $this->db->group_end();
      $this->db->limit(12);
      $query_loop = $this->db->get();
      $fetch_loop = $query_loop->result_array();
      return $fetch_loop;
  }

  public function count_seat_register($cos_id){
    $this->db->from('lms_cos_enroll');
    $this->db->where('cos_id',$cos_id);
    $this->db->where('cosen_status','1');
    $query = $this->db->get();
    $num = $query->num_rows();
    return $num;
  }

  public function IsQuizBeforeClass($cos_id,$emp_id){
    $this->db->from('lms_qiz');
    $this->db->where('cos_id',$cos_id);
    $this->db->where('quiz_type','1');
    $query = $this->db->get();
    $num = $query->num_rows();
    $qiz_id = "-";
    if($num>0){
      $count = 1;
      $fetch = $query->result_array();
      foreach ($fetch as $key => $value) {
        $this->db->where('emp_id',$emp_id);
        $this->db->where('qiz_id',$value['qiz_id']);
        $this->db->where('qiz_status','3');
        $this->db->from('lms_qiz_tc');
        $query_chk = $this->db->get();
        $num_chk = $query_chk->num_rows();
        if($num_chk>0){
          $num--;
        }else{
          if($count==1){$qiz_id = $value['qiz_id'];}
        }
        $count++;
      }
      if($num<0){ $num = 0; }
    }
    return $qiz_id;
  }

  public function count_seat_register_thismonth($cos_id,$monselect=''){
    $this->db->from('lms_cos_enroll');
    $this->db->where('cos_id',$cos_id);
    $this->db->like('cosen_timerequest',date('Y-m',strtotime($monselect)));
    $this->db->where('cosen_status','1');
    $query = $this->db->get();
    $num = $query->num_rows();
    return $num;
  }

  public function chart_total($cos_id){
    $arr = array();
    for ($i=1; $i <= 12; $i++) { 
      $month = $i;
      if($i<10){
        $month = "0".$i;
      }
      $val = $this->count_seat_register_thismonth($cos_id,date('Y-').$month);
      array_push($arr, $val);
    }
    return $arr;
  }

  public function course_regis($cos_id){
    date_default_timezone_set("Asia/Bangkok");
    $this->db->from('lms_cos_enroll');
    $this->db->join('lms_emp','lms_cos_enroll.emp_id = lms_emp.emp_id');
    $this->db->join('lms_usp','lms_cos_enroll.emp_id = lms_usp.emp_id');
    $this->db->join('lms_usp_gp','lms_usp.ug_id = lms_usp_gp.ug_id');
    $this->db->where('lms_cos_enroll.cos_id',$cos_id);
    $this->db->where('lms_cos_enroll.cosen_status_sub','1');
    $this->db->order_by('lms_cos_enroll.cosen_score','DESC');
    $this->db->limit(10);
    $query = $this->db->get();
    $num = $query->num_rows();
    $arr = array();
    if($num>0){
      $fetch = $query->result_array();
      foreach ($fetch as $key => $value) {
        $detail = array();
        $detail['fullname_th'] = $value['fullname_th'];
        $detail['fullname_en'] = $value['fullname_en'];
        $detail['ug_name_th'] = $value['ug_name_th'];
        $detail['ug_name_en'] = $value['ug_name_en'];
        $time = 0;
        if($value['cosen_firsttime']!="0000-00-00 00:00:00"&&$value['cosen_finishtime']!="0000-00-00 00:00:00"){
          $start_date = new DateTime('2007-09-01 04:10:58');
          $since_start = $start_date->diff(new DateTime('2012-09-11 10:25:00'));
          $time = $since_start->i.".".$since_start->s;
        }
        $detail['time'] = $time;
        $detail['cosen_score'] = $value['cosen_score'];
        array_push($arr, $detail);
      }
    }
    return $arr;
  }

  public function month_select($cos_id){
    date_default_timezone_set("Asia/Bangkok");
    $this->db->distinct();
    $this->db->select('cosen_timerequest');
    $this->db->from('lms_cos_enroll');
    $this->db->where('lms_cos_enroll.cos_id',$cos_id);
    $this->db->order_by('cosen_timerequest','ASC');
    $query = $this->db->get();
    $fetch = $query->result_array();
    $arr = array();
    foreach ($fetch as $key => $value) {
      if(!in_array(date('Y-m',strtotime($value['cosen_timerequest'])), $arr)){
        array_push($arr, date('Y-m',strtotime($value['cosen_timerequest'])));
      }
    }
    return $arr;
  }

  public function rechk_role_cos($ug_id,$cos_id){
          date_default_timezone_set("Asia/Bangkok");
      $this->db->from('lms_cos_detail');
      $this->db->distinct();
      $this->db->select('lms_cos_detail.date_start,lms_cos_detail.date_end,lms_cos_detail.get_point,lms_cos_detail.get_point');
      $this->db->join('lms_cos_detail_ug','lms_cos_detail.cosde_id = lms_cos_detail_ug.cosde_id');
      if($ug_id!=""){
        $this->db->where('lms_cos_detail_ug.ug_id', $ug_id );
      }
      $this->db->where('lms_cos_detail.cos_id', $cos_id );
      $this->db->where('lms_cos_detail.cosde_status', '1' );
      $where = $this->course_availability_where('lms_cos_detail');
      $this->db->where($where);
      $query_loop = $this->db->get();
      $fetch_loop = $query_loop->result_array();
      return $fetch_loop;
  }
  public function rechk_enroll($cos_id,$emp_c){
      date_default_timezone_set("Asia/Bangkok");
      $this->db->from('lms_cos_enroll');
      $this->db->where('cos_id',$cos_id);
      $this->db->where('emp_id',$emp_c);
      $query = $this->db->get();
      $fetch = $query->row_array();
      return $fetch;
  }

  public function getAllCos($cg_code="",$emp_id='',$txt_search='')
  {
    $user = $this->session->userdata('user');
          date_default_timezone_set("Asia/Bangkok");
    $coses = array();
    if($user['ug_for']=="CUSTOMER"){
      $this->db->from('lms_wkg');
      $this->db->order_by('c_date', 'DESC');
      $this->db->where('wstatus', '1' );
      $this->db->where('com_id', $user['com_id'] );
      $query_loop = $this->db->get();
      $fetch_loop = $query_loop->result_array();
      foreach ($fetch_loop as $key) {
          $this->db->select('lms_cos.id,lms_cos.ccode,lms_cos.wg_id,lms_cos.com_id,lms_cos.cname_th,lms_cos.cname_en,lms_cos.pic,lms_cos.goal_score,lms_cos.seat_count,lms_cos.tc_id,lms_cos.condition,lms_typecos.tc_name_th,lms_typecos.tc_name_en,lms_typecos.tc_color');
          $this->db->from('lms_cos');
          $this->db->join('lms_typecos','lms_cos.tc_id = lms_typecos.tc_id');
          $this->db->where('lms_cos.wg_id', $key['id'] );
          $this->db->where('lms_cos.status', 1);
          
          if($txt_search!=""){
              $where = "(lms_cos.cname_th like '%".$txt_search."%' OR lms_cos.cname_en like '%".$txt_search."%')";
              $this->db->where($where);
          }
          $this->db->order_by('time_create', 'DESC');
          $query = $this->db->get();
          $ar = $query->result_array();
          if(countArray($ar)>0){
            foreach ($ar as $key => $value) {
            array_push($coses, $ar[$key]);
            }
          }
      }
      //print_r($coses);
      if(countArray($coses)>0){
        if($emp_id!=""){
              foreach ($coses as $key_coses => $value_coses) {
                  $this->db->from('lms_cos_enroll');
                  $this->db->where('lms_cos_enroll.cos_id', $value_coses['id'] );
                  $this->db->where('lms_cos_enroll.emp_id', $emp_id );
                  $this->db->where('lms_cos_enroll.cosen_status', '1' );
                  $query_enroll = $this->db->get();
                  $ar_enroll = $query_enroll->result_array();
                  if(countArray($ar_enroll)==0){
                    unset($coses[$key_coses]);
                  }
              }
        }
        foreach ($coses as $key_coses => $value_coses) {
            $ug_id = $user['ug_id'];
            if($user['Is_admin']=="1"&&$user['ug_for']!="CUSTOMER"){
              $ug_id = "";
            }
          $arr_chk = $this->rechk_role_cos($ug_id,$value_coses['id']);
          if(countArray($arr_chk)==0){
            unset($coses[$key_coses]);
          }else{
            $coses[$key_coses]['role_cos'] = $arr_chk[0];
            $this->db->from('lms_cos_enroll');
            $this->db->where('lms_cos_enroll.cos_id', $value_coses['id'] );
            $this->db->where('lms_cos_enroll.cosen_status', '1' );
            $query_count = $this->db->get();
            $ar_count = $query_count->result_array();
            $coses[$key_coses]['enroll_seat'] = countArray($ar_count);
            $this->db->from('lms_bad');
            $this->db->where('lms_bad.courses_id', $value_coses['id'] );
            $query_cert = $this->db->get();
            $ar_cert = $query_cert->result_array();
            $coses[$key_coses]['count_cert'] = countArray($ar_cert);
            $this->db->from('lms_cos_enroll');
            $this->db->where('lms_cos_enroll.cos_id', $value_coses['id'] );
            $this->db->where('lms_cos_enroll.emp_id', $user['emp_id'] );
            $query_enroll = $this->db->get();
            $ar_enroll = $query_enroll->result_array();
            $coses[$key_coses]['enroll'] = $ar_enroll;
            $this->db->select('lms_cosincg.cg_id');
            $this->db->from('lms_cosincg');
            $this->db->where('lms_cosincg.course_id', $value_coses['id'] );
            $this->db->where('lms_cosincg.status_cg', '1' );
            $query_cg = $this->db->get();
            $ar_cg = $query_cg->result_array();
            $arr_cg = array();
            if(countArray($ar_cg)>0){
              foreach ($ar_cg as $key => $value) {
                array_push($arr_cg, $value['cg_id']);
              }
            }
            $coses[$key_coses]['cg_code'] = $arr_cg;
          }
        }
      }
      
    }else{
      $this->db->distinct();
      $this->db->from('lms_cos');
      $this->db->order_by('id', 'DESC');
      $query = $this->db->get();
      $coses = $query->result_array();
      foreach ($coses as $key_coses => $value_coses) {
          $ug_id = $user['ug_id'];
          if($user['Is_admin']=="1"&&$user['ug_for']!="CUSTOMER"){
            $ug_id = "";
          }
          $arr_chk = $this->rechk_role_cos($ug_id,$value_coses['id']);
          if(countArray($arr_chk)==0){
            unset($coses[$key_coses]);
          }else{
            $coses[$key_coses]['role_cos'] = $arr_chk[0];
            $this->db->from('lms_cos_enroll');
            $this->db->where('lms_cos_enroll.cos_id', $value_coses['id'] );
            $this->db->where('lms_cos_enroll.cosen_status', '1' );
            $query_count = $this->db->get();
            $ar_count = $query_count->result_array();
            $coses[$key_coses]['enroll_seat'] = countArray($ar_count);
            $this->db->from('lms_bad');
            $this->db->where('lms_bad.courses_id', $value_coses['id'] );
            $query_cert = $this->db->get();
            $ar_cert = $query_cert->result_array();
            $coses[$key_coses]['count_cert'] = countArray($ar_cert);
            
            $this->db->from('lms_cos_enroll');
            $this->db->where('lms_cos_enroll.cos_id', $value_coses['id'] );
            $this->db->where('lms_cos_enroll.emp_id', $user['emp_id'] );
            $query_enroll = $this->db->get();
            $ar_enroll = $query_enroll->result_array();
            $coses[$key_coses]['enroll'] = $ar_enroll;
            $this->db->select('lms_cosincg.cg_id');
            $this->db->from('lms_cosincg');
            $this->db->where('lms_cosincg.course_id', $value_coses['id'] );
            $this->db->where('lms_cosincg.status_cg', '1' );
            $query_cg = $this->db->get();
            $ar_cg = $query_cg->result_array();
            $arr_cg = array();
            if(countArray($ar_cg)>0){
              foreach ($ar_cg as $key => $value) {
                array_push($arr_cg, $value['cg_id']);
              }
            }
            $coses[$key_coses]['cg_code'] = $arr_cg;
          }
      }
    }
    return $coses;
  }

  public function firsttime_les($cos_id=""){
      $user = $this->session->userdata('user');
      date_default_timezone_set("Asia/Bangkok");
      $this->db->from('lms_cos_enroll');
      $this->db->where('cosen_firsttime', '0000-00-00 00:00:00' );
      $this->db->where('cos_id', $cos_id );
      $this->db->where('emp_id', $user['emp_id'] );
      $query_chk = $this->db->get();
      $fetch_chk = $query_chk->row_array();
      if(isset($fetch_chk["cosen_status_sub"])){
            $data = array(
              'cosen_status_sub' => '2',
              'cosen_firsttime' => date('Y-m-d H:i'),
              'cosen_modtime' => date('Y-m-d H:i')
            );
        $this->db->where('cos_id', $cos_id );
        $this->db->where('emp_id', $user['emp_id'] );
        $this->db->update('lms_cos_enroll', $data);
      }
  }

  public function updatePoint($point,$emp_id,$cos_id){
      $user = $this->session->userdata('user');
      date_default_timezone_set("Asia/Bangkok");
      $this->load->model('Function_query_model', 'func_query', FALSE);

      $row = $this->func_query->query_row('lms_usp','','','','emp_id = "'.$emp_id.'"');

      $usp_point = floatval($row['usp_point'])+floatval($point);

      $row_chk = $this->func_query->numrows('lms_usp_point','','','','u_id = "'.$row['u_id'].'" and cos_id = "'.$cos_id.'"');
      if($row_chk==0){
        $data = array(
          'usp_point' => $usp_point
        );
        $this->db->where('emp_id',$emp_id);
        $this->db->update('lms_usp',$data);

        $data_point = array(
          'cos_id' => $cos_id,
          'u_id' => $row['u_id'],
          'usp_point' => $usp_point,
          'up_createdate' => date('Y-m-d H:i')
        );
        $this->db->insert('lms_usp_point',$data_point);
      }
  }
  public function end_course_updatequiz($cos_id=""){
      $user = $this->session->userdata('user');
      date_default_timezone_set("Asia/Bangkok");

      $this->load->model('Function_query_model', 'func_query', FALSE);
      $var_rechk_les = $this->recheck_status('lesson',$cos_id);
      $var_rechk_qiz = $this->recheck_status('qiz',$cos_id);
      $success = '<b style="color:#009933"><i class="mdi mdi-checkbox-marked-circle-outline"></i> '.label('done').'</b>';
      /*if($var_rechk_les=="00000"){
        $var_rechk_les = $success;
      }
      if($var_rechk_qiz=="00000"){
        $var_rechk_qiz = $success;
      }
      if($var_rechk_les==$success&&$var_rechk_qiz==$success){*/
      $fetch_cos = $this->func_query->query_row('lms_cos_detail','','','','cos_id="'.$cos_id.'"');
      $fetch_cug = $this->func_query->query_row('lms_cug','','','','course_id="'.$cos_id.'"');

      $score = 0;
      $total = 0;
      $fetch_qiz = $this->func_query->query_result('lms_qiz','','','','lms_qiz.cos_id="'.$cos_id.'" and lms_qiz.quiz_type="2" and lms_qiz.quiz_status="1"');

      $num_chk_qiz = 0;
      $num_chk_qiz_status = 0;
      $num_chk_ques = 0;
      foreach ($fetch_qiz as $key_qiz => $value_qiz) {
          $score_total = 0;
          $fetch_quest = $this->func_query->query_result('lms_ques_tc','','','','lms_ques_tc.qiz_id="'.$value_qiz['qiz_id'].'" and lms_ques_tc.emp_id="'.$user['emp_id'].'"');
          if(countArray($fetch_quest)==intval($value_qiz['quiz_numofshown'])){
              $num_chk_qiz++;
          }
          $fetch_amount = $this->func_query->query_row('lms_ques','','','','lms_ques.qiz_id="'.$value_qiz['qiz_id'].'"');
          if(countArray($fetch_amount)>0){
            $num_chk_ques++;
          }
          

          $fetch_chk = $this->func_query->query_row('lms_qiz_tc','','','','lms_qiz_tc.qiz_id="'.$value_qiz['qiz_id'].'" and lms_qiz_tc.emp_id="'.$user['emp_id'].'"');

          foreach ($fetch_quest as $key => $value) {
              $fetch_sum = $this->func_query->query_row('lms_ques','','','','lms_ques.ques_id="'.$value['ques_id'].'" and lms_ques.qiz_id="'.$value_qiz['qiz_id'].'"');
              $score_total += floatval($fetch_sum['ques_score']);
              if($value['tc_finish']!="0000-00-00 00:00:00"){
                  $num_chk_qiz_status++;
              }
          }
                    
          $score += floatval($fetch_chk['sum_score']);
          $total += $score_total;
      }
      $fetch_les = $this->func_query->query_result('lms_les','','','','lms_les.cos_id="'.$cos_id.'" and lms_les.les_type="2" and lms_les.status="1"');

      foreach ($fetch_les as $key_les => $value_les) {
          $fetch_mainscm = $this->func_query->query_row('lms_scm','lms_scm_val','lms_scm.id = lms_scm_val.scm_id','','lms_scm.lessons_id="'.$value_les['les_id'].'" and lms_scm_val.emp_id="'.$user['emp_id'].'" and lms_scm_val.var_name="cmi_core_lesson_status" and (lms_scm_val.var_value="completed" or lms_scm_val.var_value="passed")');
          if(!empty($fetch_mainscm)){
              $fetch_rawscm = $this->func_query->query_row('lms_scm','lms_scm_val','lms_scm.id = lms_scm_val.scm_id','','lms_scm.lessons_id="'.$value_les['les_id'].'" and lms_scm_val.emp_id="'.$user['emp_id'].'" and lms_scm_val.var_name="cmi_core_score_raw" and lms_scm_val.var_value!=""');
              if(!empty($fetch_rawscm)){
                  $fetch_maxscm = $this->func_query->query_row('lms_scm','lms_scm_val','lms_scm.id = lms_scm_val.scm_id','','lms_scm.lessons_id="'.$value_les['les_id'].'" and lms_scm_val.emp_id="'.$user['emp_id'].'" and lms_scm_val.var_name="cmi_core_score_max" and lms_scm_val.var_value!=""');
                  if(!isset($fetch_maxscm)){
                      $total += floatval($fetch_maxscm['var_value']);
                      $score += floatval($fetch_rawscm['var_value']);
                  }
              }
          }
      }
      $cosen_grade = "";
      $cosen_score = 0;
            
      $cosen_reward = '';
      $fetch_chk = $this->func_query->query_row('lms_cos','','','','ccode="'.$cos_id.'"');

      $data = array(
          'cosen_grade' => $cosen_grade,
          'cosen_score' => $cosen_score,
          'time' => date('Y-m-d H:i')
      );
      $status = 0;
      $cosen_status_sub = '2';
      $cosen_finishtime = '0000-00-00 00:00:00';

      if($score>=0&&$total>0){
          $cosen_score = ($score/$total)*100;
          if(isset($fetch_cug['mina'])){
              if($cosen_score>=floatval($fetch_cug['mina'])){
                  $cosen_grade = "A";
              }else if($cosen_score>=floatval($fetch_cug['minb'])){
                  $cosen_grade = "B";
              }else if($cosen_score>=floatval($fetch_cug['minc'])){
                  $cosen_grade = "C";
              }else if($cosen_score>=floatval($fetch_cug['mind'])){
                  $cosen_grade = "D";
              }else{
                  $cosen_grade = "F";
              }
          }
          if(floatval($cosen_score)>=floatval($fetch_chk['goal_score'])){
              if(countArray($fetch_qiz)>0){
                    $cosen_status_sub = 1;
                    $line="Line:547";
                    $cosen_finishtime = date('Y-m-d H:i');
              }else{
                  if(countArray($fetch_les)>0){
                    $cosen_status_sub = 1;
                    $line="Line:556";
                    $cosen_finishtime = date('Y-m-d H:i');
                  }else{
                    $line="Line:559";
                    $cosen_status_sub = 2;
                  }
              }
          }else{
                  $cosen_status_sub = 2;
          }
      }

      $data = array(
          'cosen_grade' => $cosen_grade,
          'cosen_score' => $cosen_score,
          'cosen_reward' => $cosen_reward,
          'cosen_status_sub' => $cosen_status_sub,
          'cosen_finishtime' => $cosen_finishtime,
          'cosen_cancelnote' => $line,
          'cosen_modtime' => date('Y-m-d H:i')
      );
      
      $this->db->where('cos_id', $cos_id );
      $this->db->where('emp_id', $user['emp_id'] );
      $this->db->update('lms_cos_enroll', $data);
      if($cosen_finishtime!="0000-00-00 00:00:00"||$cosen_finishtime!=""){
          $this->db->from('lms_bad');
          $this->db->where('courses_id',$cos_id);
          $query_bad = $this->db->get();
          $fetch_bad = $query_bad->row_array();
          if(!empty($fetch_bad)){
              $this->update_cert($cos_id,$user);
          }
      }
          
  }



 public function update_cert($cos_id,$arr_user){

            $this->load->model('Function_query_model', 'func_query', FALSE);
            date_default_timezone_set("Asia/Bangkok");
            $thaimonth = array("","มกราคม","กุมภาพันธ์","มีนาคม","เมษายน","พฤษภาคม","มิถุนายน","กรกฎาคม","สิงหาคม","กันยายน","ตุลาคม","พฤศจิกายน","ธันวาคม");
            $fetch_chk = $this->func_query->query_row('lms_certificate','','','','cos_id="'.$cos_id.'" and emp_id="'.$arr_user['emp_id'].'"');
            if(isset($fetch_chk)){
              //audit_unlink(base_url()."uploads/certificate/".$fetch_chk['cert_file']);

                          if(is_file(ROOT_DIR."uploads/certificate/".$fetch_chk['cert_file'])) {
                               audit_unlink(ROOT_DIR."uploads/certificate/".$fetch_chk['cert_file']);
                          }
              $this->db->where('cos_id',$cos_id);
              $this->db->where('emp_id',$arr_user['emp_id']);
              $this->db->delete('lms_certificate');
            }


            $fetch_bad = $this->func_query->query_row('lms_bad','','','','courses_id="'.$cos_id.'"');
            $fetch_cos = $this->func_query->query_row('lms_cos','lms_typecos','lms_cos.tc_id = lms_typecos.tc_id','','cos_id="'.$cos_id.'"');
            $this->db->where('cosen_isDelete','0');
            $this->db->where('cos_id',$cos_id);
            $this->db->where('emp_id',$arr_user['emp_id']);
            $this->db->order_by('cosen_id DESC');
            $this->db->from('lms_cos_enroll');
            $query_enroll = $this->db->get();
            $fetch_enroll = $query_enroll->row_array();
            //$fetch_enroll = $this->func_query->query_row('lms_cos_enroll','','','','cos_id="'.$cos_id.'" and emp_id="'.$arr_user['emp_id'].'" and cosen_isDelete="0"','cosen_id DESC');

            if(!empty($fetch_bad)&&!empty($fetch_enroll)){
                header('Cache-Control: no-cache');
                header('Pragma: no-cache');
                header('Expires: 0');

                // REAL PATH

              require_once  './vendor/autoload.php';


							$defaultFontConfig = (new Mpdf\Config\FontVariables())->getDefaults();
							$fontData = $defaultFontConfig['fontdata'];
              $fontDirs = (new Mpdf\Config\ConfigVariables())->getDefaults()['fontDir'];
              $pdf = new \Mpdf\Mpdf([
                'margin_left' => 40,
                'margin_right' => 40,
                'orientation' => 'L',
                'fontDir' => array_merge($fontDirs, [
                  ROOT_DIR.'assets/tfpdf/font',
                ]),
								'fontdata' => $fontData + [ // lowercase letters only in font key
									'cordia' => [
										'R' => 'cordia.ttf',
                  ],
                  'freesans' => [
										'R' => 'FreeSans.ttf',
                    'B' => 'FreeSansBold.ttf',
                  ],
                  'notojpbold' => [
										'R' => 'NotoSansJP-Bold.ttf',
                    'B' => 'NotoSansJP-Bold.ttf',
                    'useOTL' => 0xFF,
                  ]
								],
								'default_font' => 'cordia'
							]);

              if($fetch_enroll['cosen_lang']=="thai"){
                  $path_img = base_url().'uploads/certificate/certificate_original_th.jpg';
              }else{
                  $path_img = base_url().'uploads/certificate/certificate_original.jpg';
              }
              if(!checkValueIsNullTypeString($fetch_bad['badges_img'])){
                if(is_file(ROOT_DIR."uploads/badges/".$fetch_bad['badges_img'])) {
                  $path_img = base_url().'uploads/badges/'.$fetch_bad['badges_img'];
                }
              }
              $pdf->text_input_as_HTML = true;
              $pdf->SetTopMargin(80);
              $pdf->SetLeftMargin(20); // ตั้งระยะขอบซ้ายเป็น 20 มม.
              $pdf->SetRightMargin(20); // ตั้งระยะขอบซ้ายเป็น 20 มม.
              $pdf->AddPage('L','mm','A4');
              $pdf->SetAutoPageBreak(false, 0);
              $pdf->Image($path_img, 0, 0, 297,210, 'jpg', '', true, false,true); //mm 
              $pdf->SetDisplayMode('fullpage');

              if($fetch_enroll['cosen_lang']=="thai"){ 
                  $cname = $fetch_cos['cname_th']!=""?$fetch_cos['cname_th']:$fetch_cos['cname_eng'];
                  $cname = $cname!=""?$cname:$fetch_cos['cname_jp'];
              }else if($fetch_enroll['cosen_lang']=="english"){ 
                  $cname = $fetch_cos['cname_eng']!=""?$fetch_cos['cname_eng']:$fetch_cos['cname_th'];
                  $cname = $cname!=""?$cname:$fetch_cos['cname_jp'];
              }else{
                  $cname = $fetch_cos['cname_jp']!=""?$fetch_cos['cname_jp']:$fetch_cos['cname_eng'];
                  $cname = $cname!=""?$cname:$fetch_cos['cname_th'];
              }

              if(in_array($fetch_enroll['cosen_lang'], array('japan','english'))){
                if($fetch_enroll['cosen_lang']=="japan"){
                    $label_a = 'Complete the course';
                    $label_b = 'Type';
                    $label_c = 'With score';
                    $label_d = 'Issued on';
                }else{
                    $label_a = 'Complete the course';
                    $label_b = 'Type';
                    $label_c = 'With score';
                    $label_d = 'Issued on';
                }
                $tc_name = $fetch_cos['tc_name_en'];
                $dateval = date('d F Y');
                
                $fullname = $arr_user['fullname_en'];
                $com_name = $arr_user['com_name_eng'];
                
                $currentFont = $fetch_enroll['cosen_lang'] == "english" ? 'freesans' : 'notojpbold';
                $layout = certificate_text_layout($fetch_enroll['cosen_lang'] == "english" ? 'EN' : 'JP');
                $pdf->SetTextColor(0,0,0);
                $pdf->SetY($layout['name_y']);
                $pdf->SetFont($currentFont, 'B', $layout['name_size']);
                $pdf->WriteCell(0, $layout['name_height'], $fullname, 0, 1, 'C');

                $pdf->SetY($layout['company_y']);
                $pdf->SetFont($currentFont, 'B', $layout['company_size']);
                $pdf->WriteCell(0, $layout['company_height'], $com_name, 0, 1, 'C');

                $pdf->SetY($layout['course_y']);
                $safeCourse = htmlspecialchars($cname, ENT_QUOTES, 'UTF-8');
                $pdf->WriteHTML("<div style='font-family:$currentFont; font-weight:bold; font-size:{$layout['course_size']}pt; text-align:center; width:100%; line-height:1.1;'>$safeCourse</div>");

                $pdf->SetY($layout['issued_y']);
                $pdf->SetFont($currentFont, 'B', $layout['issued_size']);
                $pdf->WriteCell(0, $layout['issued_height'], $label_d.' '.$dateval, 0, 1, 'C');
              }else{
                $label_a = 'สำเร็จหลักสูตร';
                $label_b = 'ประเภท';
                $label_c = 'ด้วยคะแนน';
                $label_d = 'ให้ไว้ ณ วันที่';
                $tc_name = $fetch_cos['tc_name_th'];
                $dateval = date('d')." ".$thaimonth[intval(date('m'))]." ".(date('Y')+543);

                $fullname = $arr_user['fullname_th'];
                $com_name = $arr_user['com_name_th'];
                
                $layout = certificate_text_layout('TH');
                $pdf->SetY($layout['name_y']);
                $pdf->SetFont('cordia','',$layout['name_size']);
                $pdf->SetTextColor(0,0,0); 
                $pdf->WriteCell(0, $layout['name_height'],$fullname, 0, 1, 'C');
                $pdf->SetY($layout['company_y']);
                $pdf->SetFont('cordia','',$layout['company_size']);
                $pdf->WriteCell(0, $layout['company_height'],$com_name, 0, 1, 'C');
                $pdf->SetFont('cordia','',25);
                $pdf->SetTextColor(0,0,0);   
                $pdf->WriteCell(0,10,'',0,1,'C'); //space
                // $pdf->WriteCell(0,13,$cname,0,1,'C');

                $pdf->SetY($layout['course_y']);
                $pdf->WriteHTML("<p  style='white-space: nowrap; font-size:{$layout['course_size']}pt;text-align: center;'>".$cname."</p>");
                // $pdf->WriteCell(0, 0, $cname, 0, 1, 'C', 0, '', 0, true, false, true, 0, true);
                $pdf->SetY($layout['issued_y']);
                $pdf->SetFont('cordia','',$layout['issued_size']);
                $pdf->SetTextColor(0,0,0);   
                $pdf->WriteCell(0,$layout['issued_height'],$label_d.' '.$dateval,0,1,'C');
              }
              $name_cert = "Certificate_".$cos_id."_".$arr_user['emp_id']."_".date('YmdHis').".pdf";
              $filename = ROOT_DIR."uploads/certificate/".$name_cert;
              $pdf->Output($filename,'F');

                $data = array(
                  'cos_id' => $cos_id,
                  'emp_id' => $arr_user['emp_id'],
                  'cert_file' => $name_cert,
                  'cert_date' => date('Y-m-d'),
                  'cert_createtime' => date('Y-m-d H:i')
                );
                $this->db->insert('lms_certificate', $data);
            }

  }

 public function update_cert_answer($cos_id,$emp_id){

            $this->load->model('Function_query_model', 'func_query', FALSE);
            $this->db->from('lms_usp');
            $this->db->join('lms_emp','lms_usp.emp_id = lms_emp.emp_id');
            //$this->db->join('lms_depart','lms_usp.dep_id = lms_depart.dep_id');
            $this->db->join('lms_company','lms_emp.com_id = lms_company.com_id');
            $this->db->join('lms_usp_gp','lms_usp.ug_id = lms_usp_gp.ug_id');
            //$this->db->join('lms_position','lms_usp.posi_id = lms_position.posi_id');
            $this->db->where('lms_usp.emp_id', $emp_id);
            $this->db->where('lms_emp.status', '1');
            $this->db->where('lms_emp.emp_isDelete', '0');
            $this->db->where('lms_usp.u_isDelete', '0');
            $query_user = $this->db->get();
            $arr_user = $query_user->row_array();
            date_default_timezone_set("Asia/Bangkok");
            $thaimonth = array("","มกราคม","กุมภาพันธ์","มีนาคม","เมษายน","พฤษภาคม","มิถุนายน","กรกฎาคม","สิงหาคม","กันยายน","ตุลาคม","พฤศจิกายน","ธันวาคม");
            $fetch_chk = $this->func_query->query_row('lms_certificate','','','','cos_id="'.$cos_id.'" and emp_id="'.$arr_user['emp_id'].'"');
            if(isset($fetch_chk['cert_file'])){
              //audit_unlink(base_url()."uploads/certificate/".$fetch_chk['cert_file']);

                          if(is_file(ROOT_DIR."uploads/certificate/".$fetch_chk['cert_file'])) {
                               audit_unlink(ROOT_DIR."uploads/certificate/".$fetch_chk['cert_file']);
                          }
              $this->db->where('cos_id',$cos_id);
              $this->db->where('emp_id',$arr_user['emp_id']);
              $this->db->delete('lms_certificate');
            }


            $fetch_bad = $this->func_query->query_row('lms_bad','','','','courses_id="'.$cos_id.'"');
            $fetch_cos = $this->func_query->query_row('lms_cos','lms_typecos','lms_cos.tc_id = lms_typecos.tc_id','','cos_id="'.$cos_id.'"');
            $this->db->where('cosen_isDelete','0');
            $this->db->where('cos_id',$cos_id);
            $this->db->where('emp_id',$arr_user['emp_id']);
            $this->db->order_by('cosen_id DESC');
            $this->db->from('lms_cos_enroll');
            $query_enroll = $this->db->get();
            $fetch_enroll = $query_enroll->row_array();
            //$fetch_enroll = $this->func_query->query_row('lms_cos_enroll','','','','cos_id="'.$cos_id.'" and emp_id="'.$arr_user['emp_id'].'" and cosen_isDelete="0"','cosen_id DESC');

            if(isset($fetch_bad['badges_img'])&&isset($fetch_enroll['cosen_lang'])){
                header('Cache-Control: no-cache');
                header('Pragma: no-cache');
                header('Expires: 0');

                // REAL PATH

              require_once  './vendor/autoload.php';


							$defaultFontConfig = (new Mpdf\Config\FontVariables())->getDefaults();
							$fontData = $defaultFontConfig['fontdata'];
              $fontDirs = (new Mpdf\Config\ConfigVariables())->getDefaults()['fontDir'];
              $pdf = new \Mpdf\Mpdf([
                'margin_left' => 40,
                'margin_right' => 40,
                'orientation' => 'L',
                'fontDir' => array_merge($fontDirs, [
                  ROOT_DIR.'assets/tfpdf/font',
                ]),
                'fontdata' => $fontData + [ // lowercase letters only in font key
                  'cordia' => [
                    'R' => 'cordia.ttf',
                  ],
                  'freesans' => [
                    'R' => 'FreeSans.ttf',
                    'B' => 'FreeSansBold.ttf',
                  ],
                  'notojpbold' => [
                    'R' => 'NotoSansJP-Bold.ttf',
                    'B' => 'NotoSansJP-Bold.ttf',
                    'useOTL' => 0xFF,
                  ]
                ],
                'default_font' => 'cordia'
              ]);
              
              if($fetch_enroll['cosen_lang']=="thai"){
                  $path_img = base_url().'uploads/certificate/certificate_original_th.jpg';
              }else{
                  $path_img = base_url().'uploads/certificate/certificate_original.jpg';
              }
              if($fetch_bad['badges_img']!=""){
                if(is_file(ROOT_DIR."uploads/badges/".$fetch_bad['badges_img'])) {
                  $path_img = base_url().'uploads/badges/'.$fetch_bad['badges_img'];
                }
              }
              $pdf->text_input_as_HTML = true;
              $pdf->SetTopMargin(80);
              $pdf->SetLeftMargin(20); // ตั้งระยะขอบซ้ายเป็น 20 มม.
              $pdf->SetRightMargin(20); // ตั้งระยะขอบซ้ายเป็น 20 มม.
              $pdf->AddPage('L','mm','A4');
              $pdf->SetAutoPageBreak(false, 0);
              $pdf->Image($path_img, 0, 0, 297,210, 'jpg', '', true, false,true); //mm 
              $pdf->SetDisplayMode('fullpage');

              if($fetch_enroll['cosen_lang']=="thai"){ 
                  $cname = $fetch_cos['cname_th']!=""?$fetch_cos['cname_th']:$fetch_cos['cname_eng'];
                  $cname = $cname!=""?$cname:$fetch_cos['cname_jp'];
              }else if($fetch_enroll['cosen_lang']=="english"){ 
                  $cname = $fetch_cos['cname_eng']!=""?$fetch_cos['cname_eng']:$fetch_cos['cname_th'];
                  $cname = $cname!=""?$cname:$fetch_cos['cname_jp'];
              }else{
                  $cname = $fetch_cos['cname_jp']!=""?$fetch_cos['cname_jp']:$fetch_cos['cname_eng'];
                  $cname = $cname!=""?$cname:$fetch_cos['cname_th'];
              }

              if(in_array($fetch_enroll['cosen_lang'], array('japan','english'))){
                if($fetch_enroll['cosen_lang']=="japan"){
                    $label_a = 'Complete the course';
                    $label_b = 'Type';
                    $label_c = 'With score';
                    $label_d = 'Issued on';
                }else{
                    $label_a = 'Complete the course';
                    $label_b = 'Type';
                    $label_c = 'With score';
                    $label_d = 'Issued on';
                }
                $tc_name = $fetch_cos['tc_name_en'];
                $dateval = date('d F Y', strtotime($fetch_enroll['cosen_finishtime']));
                $fullname = $arr_user['fullname_th'];
                $com_name = $arr_user['com_name_th'];
                
                $currentFont = $fetch_enroll['cosen_lang'] == "english" ? 'freesans' : 'notojpbold';
                $layout = certificate_text_layout($fetch_enroll['cosen_lang'] == "english" ? 'EN' : 'JP');
                $pdf->SetTextColor(0,0,0);
                $pdf->SetY($layout['name_y']);
                $pdf->SetFont($currentFont, 'B', $layout['name_size']);
                $pdf->WriteCell(0, $layout['name_height'], $fullname, 0, 1, 'C');

                $pdf->SetY($layout['company_y']);
                $pdf->SetFont($currentFont, 'B', $layout['company_size']);
                $pdf->WriteCell(0, $layout['company_height'], $com_name, 0, 1, 'C');

                $pdf->SetY($layout['course_y']);
                $safeCourse = htmlspecialchars($cname, ENT_QUOTES, 'UTF-8');
                $pdf->WriteHTML("<div style='font-family:$currentFont; font-weight:bold; font-size:{$layout['course_size']}pt; text-align:center; width:100%; line-height:1.1;'>$safeCourse</div>");

                $pdf->SetY($layout['issued_y']);
                $pdf->SetFont($currentFont, 'B', $layout['issued_size']);
                $pdf->WriteCell(0, $layout['issued_height'], $label_d.' '.$dateval, 0, 1, 'C');
              }else{
                $label_a = 'สำเร็จหลักสูตร';
                $label_b = 'ประเภท';
                $label_c = 'ด้วยคะแนน';
                $label_d = 'ให้ไว้ ณ วันที่';
                $tc_name = $fetch_cos['tc_name_th'];
                $dateval = date('d', strtotime($fetch_enroll['cosen_finishtime']))." ".$thaimonth[intval(date('m', strtotime($fetch_enroll['cosen_finishtime'])))]." ".(date('Y', strtotime($fetch_enroll['cosen_finishtime']))+543);

                $fullname = $arr_user['fullname_th'];
                $com_name = $arr_user['com_name_th'];
                
                $layout = certificate_text_layout('TH');
                $pdf->SetY($layout['name_y']);
                $pdf->SetFont('cordia','',$layout['name_size']);
                $pdf->SetTextColor(0,0,0); 
                $pdf->WriteCell(0, $layout['name_height'],$fullname, 0, 1, 'C');
                $pdf->SetY($layout['company_y']);
                $pdf->SetFont('cordia','',$layout['company_size']);
                $pdf->WriteCell(0, $layout['company_height'],$com_name, 0, 1, 'C');
                $pdf->SetFont('cordia','',25);
                $pdf->SetTextColor(0,0,0);   
                $pdf->WriteCell(0,10,'',0,1,'C'); //space
                // $pdf->WriteCell(0,13,$cname,0,1,'C');

                $pdf->SetY($layout['course_y']);
                $pdf->WriteHTML("<p  style='white-space: nowrap; font-size:{$layout['course_size']}pt;text-align: center;'>".$cname."</p>");
                // $pdf->WriteCell(0, 0, $cname, 0, 1, 'C', 0, '', 0, true, false, true, 0, true);
                $pdf->SetY($layout['issued_y']);
                $pdf->SetFont('cordia','',$layout['issued_size']);
                $pdf->SetTextColor(0,0,0);   
                $pdf->WriteCell(0,$layout['issued_height'],$label_d.' '.$dateval,0,1,'C');
              }
              $name_cert = "Certificate_".$cos_id."_".$arr_user['emp_id']."_".date('YmdHis').".pdf";
              $filename = ROOT_DIR."uploads/certificate/".$name_cert;
              $pdf->Output($filename,'F');

                $data = array(
                  'cos_id' => $cos_id,
                  'emp_id' => $arr_user['emp_id'],
                  'cert_file' => $name_cert,
                  'cert_date' => date('Y-m-d', strtotime($fetch_enroll['cosen_finishtime'])),
                  'cert_createtime' => date('Y-m-d H:i')
                );
                $this->db->insert('lms_certificate', $data);
            }

  }

  public function getcourse_group($cg_code="",$txt_search=""){
    $user = $this->session->userdata('user');
    $coscg = array();
    $coses = array();
      $this->db->from('lms_wkg');
      $this->db->order_by('c_date', 'DESC');
      $this->db->where('wstatus', '1' );
      if($user['com_admin']=="CUSTOMER"){
        $this->db->where('com_id', $user['com_id'] );
      }
      $query_loop = $this->db->get();
      $fetch_loop = $query_loop->result_array();
      foreach ($fetch_loop as $key) {
          $this->db->select('lms_cos.id');
          $this->db->from('lms_cos');
          $this->db->where('lms_cos.wg_id', $key['id'] );
          $this->db->where('lms_cos.status', 1);
          $this->db->order_by('time_create', 'DESC');
          if($txt_search!=""){
              $where = "(cname_th like '%".$txt_search."%' OR cname_en like '%".$txt_search."%')";
              $this->db->where($where);
          }
          $query = $this->db->get();
          $ar = $query->result_array();
          if(countArray($ar)>0){
            foreach ($ar as $key => $value) {
            array_push($coses, $ar[$key]);
            }
          }
      }
      if(countArray($coses)>0){
        if($cg_code!=""){
              $this->db->distinct();
              $this->db->select('lms_cog.id,lms_cog.cgtitle_th,lms_cog.cgtitle_en');
              $this->db->from('lms_cog');
              $this->db->where('lms_cog.id', $cg_code );
              $query_cg = $this->db->get();
              $ar_cg = $query_cg->result_array();
              if(countArray($ar_cg)>0){
                foreach ($ar_cg as $key) {
                  array_push($coscg, $key);
                }
              }
        }else{
          foreach ($coses as $key_coses => $value_coses) {
            $ug_id = $user['ug_id'];
            if($user['Is_admin']=="1"&&$user['ug_for']!="CUSTOMER"){
              $ug_id = "";
            }
            $arr_chk = $this->rechk_role_cos($ug_id,$value_coses['id']);
            if(countArray($arr_chk)>0){
              $this->db->select('lms_cog.id,lms_cog.cgtitle_th,lms_cog.cgtitle_en');
              $this->db->from('lms_cosincg');
              $this->db->join('lms_cog','lms_cosincg.cg_id = lms_cog.id');
              $this->db->where('lms_cosincg.course_id', $value_coses['id'] );
              $this->db->where('lms_cosincg.status_cg', '1' );
              $query_cg = $this->db->get();
              $ar_cg = $query_cg->result_array();
              $arr_cg = array();
              if(countArray($ar_cg)>0){
                foreach ($ar_cg as $key) {
                  array_push($coscg, $key);
                }
              }
            }
          }
        }
      }
       $coscg = $this->record_sort($coscg, "id",true);
    return $coscg;
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

  public function chkcg_idincourse($cg_id)
  {
    $this->db->select('lms_cosincg.course_id');
    $this->db->distinct();
    $this->db->from('lms_cosincg');
    $this->db->where('cg_id', $cg_id);
    $this->db->where('status_cg', '1');
    $query = $this->db->get();
    $arr = array();
    $fetch = $query->result_array();
    foreach ($fetch as $key => $value) {
      array_push($arr, $value['course_id']);
    }
    return $arr;
  }


  public function getcg_idincourse($id)
  {
    $this->db->select('lms_cosincg.cg_id');
    $this->db->distinct();
    $this->db->from('lms_cosincg');
    $this->db->where('course_id', $id);
    $this->db->where('status_cg', '1');
    $query = $this->db->get();
    $arr = array();
    $fetch = $query->result_array();
    foreach ($fetch as $key => $value) {
      array_push($arr, $value['cg_id']);
    }
    return $arr;
  }
  //checkdetailpos
  public function checkdetailug($cosde_id)
  {
    $this->db->select('lms_cos_detail_ug.ug_id');
    $this->db->distinct();
    $this->db->from('lms_cos_detail_ug');
    $this->db->where('cosde_id', $cosde_id);
    $query = $this->db->get();
    $arr = array();
    $fetch = $query->result_array();
    foreach ($fetch as $key => $value) {
      array_push($arr, $value['ug_id']);
    }
    return $arr;
  }

  public function register_course($cos_id,$status)
  {
          date_default_timezone_set("Asia/Bangkok");
          $arrMonthThaiTextFull = array("","มกราคม","กุมภาพันธ์","มีนาคม","เมษายน","พฤษภาคม","มิถุนายน","กรกฎาคม","สิงหาคม","กันยายน","ตุลาคม","พฤศจิกายน","ธันวาคม");
          $lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang") ;
          $user = $this->session->userdata('user');
          $this->load->model('Function_query_model', 'func_query', TRUE);
          $this->db->from('lms_cos_enroll');
          $this->db->where('cos_id', $cos_id);
          $this->db->where('emp_id', $user['emp_id']);
          $query = $this->db->get();
          if($query->num_rows()==0){
            $data = array(
              'cos_id' => $cos_id,
              'emp_id' => $user['emp_id'],
              'cosen_status' => $status,
              'cosen_timerequest' => date('Y-m-d H:i'),
              'cosen_modtime' => date('Y-m-d H:i')
            );
            $this->db->insert('lms_cos_enroll', $data);
            $id = $this->db->insert_id();
            $data_log = array(
              'cosen_id' => $id
            );

            //Record Log activity
            $this->load->model('Log_model', 'lg', FALSE);
            $this->load->model('Course_model', 'course', TRUE);
            $this->lg->loadDB();
            $fetch_cos = $this->course->query_data_onupdate($cos_id, 'lms_cos','cos_id');
            if($lang=="thai"){ 
              $cname = $fetch_cos['cname_th']!=""?$fetch_cos['cname_th']:$fetch_cos['cname_eng'];
              $cname = $cname!=""?$cname:$fetch_cos['cname_jp'];
            }else if($lang=="english"){ 
              $cname = $fetch_cos['cname_eng']!=""?$fetch_cos['cname_eng']:$fetch_cos['cname_th'];
              $cname = $cname!=""?$cname:$fetch_cos['cname_jp'];
            }else{
              $cname = $fetch_cos['cname_jp']!=""?$fetch_cos['cname_jp']:$fetch_cos['cname_eng'];
              $cname = $cname!=""?$cname:$fetch_cos['cname_th'];
            }
            $this->lg->record('course', 'Enroll learner: '.$user['useri'].'('.$id.') of Course: '.$cname.' ('.$cos_id.')');

            $this->db->insert('lms_log_enroll', $data_log);
            $id_log = $this->db->insert_id();

            $this->db->from('lms_qiz');
            $this->db->where('cos_id', $cos_id);
            $this->db->where('quiz_status', '1');
            $query_chk = $this->db->get();
            $fetch_chk = $query_chk->result_array();
            if(countArray($fetch_chk)>0){
              foreach ($fetch_chk as $key_chk => $value_chk) {

                $this->db->from('lms_qiz_tc');
                $this->db->where('qiz_id', $value_chk['qiz_id']);
                $this->db->where('emp_id', $user['emp_id']);
                $query_check = $this->db->get();
                $fetch_check = $query_check->result_array();
                if(countArray($fetch_check)==0){
                  $data_insert_qiz = array(
                    'qiz_id' => $value_chk['qiz_id'],
                    'emp_id' => $user['emp_id'],
                    'time_mod' => date('Y-m-d H:i'),
                    'id_log' => $id_log
                  );
                  $this->db->insert('lms_qiz_tc', $data_insert_qiz);
                }
              }
            }

            $this->db->where('sm_id','1');
            $this->db->from('lms_setting_mail');
            $query_setmail = $this->db->get();
            $fetch_setmail = $query_setmail->row_array();

            $this->db->where('smf_type','1');
            $this->db->where('smf_show','1');
            $this->db->from('lms_sendmail_form');
            $query_formatmail = $this->db->get();
            $num_formatmail = $query_formatmail->num_rows();
            if($num_formatmail>0){
              $fetch_formatmail = $query_formatmail->row_array();
              $this->db->where('id',$cos_id);
              $this->db->from('lms_cos');
              $query_cos = $this->db->get();
              $fetch_cos = $query_cos->row_array();
              $fetch_company = $this->func_query->query_row('lms_company','','','','com_id="'.$user['com_id'].'"');
              $subject_th = $fetch_formatmail['smf_subject_th'];
              $subject_en = $fetch_formatmail['smf_subject_en'];
              $message_th = $fetch_formatmail['smf_message_th'];
              $message_en = $fetch_formatmail['smf_message_en'];
              if($subject_th!=""){
                  $subject_th = str_replace("#fullname",$user['fullname_th'],$subject_th);
                  $subject_th = str_replace("#username",$user['useri'],$subject_th);
                  $subject_th = str_replace("#email",$user['email'],$subject_th);
                  $subject_th = str_replace("#coursename",$fetch_cos['cname_th'],$subject_th);
                  $subject_th = str_replace("#date",date('d').$arrMonthThaiTextFull[intval(date('m'))].(date('Y')+543),$subject_th);
                  $subject_th = str_replace("#time",date('H:i'),$subject_th);
                  $subject_th = str_replace("#companyname",$fetch_company['com_code'],$subject_th);
              }
              if($subject_en!=""){
                  $subject_en = str_replace("#fullname",$user['fullname_en'],$subject_en);
                  $subject_en = str_replace("#username",$user['useri'],$subject_en);
                  $subject_en = str_replace("#email",$user['email'],$subject_en);
                  $subject_en = str_replace("#coursename",$fetch_cos['cname_en'],$subject_en);
                  $subject_en = str_replace("#date",date('d').$arrMonthThaiTextFull[intval(date('m'))].(date('Y')+543),$subject_en);
                  $subject_en = str_replace("#time",date('H:i'),$subject_en);
                  $subject_en = str_replace("#companyname",$fetch_company['com_code'],$subject_en);
              }
              if($message_th!=""){
                  $message_th = str_replace("#fullname",$user['fullname_th'],$message_th);
                  $message_th = str_replace("#username",$user['useri'],$message_th);
                  $message_th = str_replace("#email",$user['email'],$message_th);
                  $message_th = str_replace("#coursename",$fetch_cos['cname_th'],$message_th);
                  $message_th = str_replace("#date",date('d').$arrMonthThaiTextFull[intval(date('m'))].(date('Y')+543),$message_th);
                  $message_th = str_replace("#time",date('H:i'),$message_th);
                  $message_th = str_replace("#companyname",$fetch_company['com_code'],$message_th);
              }
              if($message_en!=""){
                  $message_en = str_replace("#fullname",$user['fullname_en'],$message_en);
                  $message_en = str_replace("#username",$user['useri'],$message_en);
                  $message_en = str_replace("#email",$user['email'],$message_en);
                  $message_en = str_replace("#coursename",$fetch_cos['cname_en'],$message_en);
                  $message_en = str_replace("#date",date('d').$arrMonthThaiTextFull[intval(date('m'))].(date('Y')+543),$message_en);
                  $message_en = str_replace("#time",date('H:i'),$message_en);
                  $message_en = str_replace("#companyname",$fetch_company['com_code'],$message_en);
              }
              if($lang == "thai") {
                $this->db->sendEmail( $user['email'] , $message_th, $subject_th,$fetch_setmail);
              } else {
                $this->db->sendEmail( $user['email'] , $message_en, $subject_en,$fetch_setmail);
              }
            }
            return "2";
          }else{
            return "1";
          }
  }

  public function create_course($data,$cg_id)
  {
          date_default_timezone_set("Asia/Bangkok");
          $user = $this->session->userdata('user');
          $this->db->from('lms_cos');
          $this->db->where('com_id', $data['com_id']);
          $this->db->where('ccode', $data['ccode']);
          $this->db->where('cname_th', $data['cname_th']);
          $this->db->where('cname_en', $data['cname_en']);
          $this->db->where('status', '1');
          $query = $this->db->get();
          if($query->num_rows()==0){
            $this->db->insert('lms_cos', $data);
            $id = $this->db->insert_id();
            for($a=0;$a<countArray($cg_id);$a++){
              if($cg_id[$a]!=""){
                  $data_detail['course_id'] = $id;
                  $data_detail['cg_id'] = $cg_id[$a];
                  $this->db->insert('lms_cosincg', $data_detail);
              }
            }
            if($id!=""){
              return "2";
            }else{
              return "3";
            }
          }else{
            return "1";
          }
  }
  public function rechk_id_data($com_id,$ccode,$cname_th,$cname_en){
          $this->db->select('lms_cos.id');
          $this->db->from('lms_cos');
          $this->db->where('com_id', $com_id);
          $this->db->where('ccode', $ccode);
          $this->db->where('cname_th', $cname_th);
          $this->db->where('cname_en', $cname_en);
          $query = $this->db->get();
          $fetch = $query->row_array();
          return $fetch['id'];
  }
  public function rechk_bad_id_data($courses_id){
          $this->db->select('lms_bad.badges_id');
          $this->db->from('lms_bad');
          $this->db->where('courses_id', $courses_id);
          $query = $this->db->get();
          $fetch = $query->row_array();
          return $fetch['badges_id'];
  }
  public function rechk_cug_id_data($courses_id){
          $this->db->select('lms_cug.id');
          $this->db->from('lms_cug');
          $this->db->where('course_id', $courses_id);
          $query = $this->db->get();
          $fetch = $query->row_array();
          return $fetch['id'];
  }
  public function rechk_qiztc($qiz_id){
          date_default_timezone_set("Asia/Bangkok");
          $user = $this->session->userdata('user');
          $this->db->from('lms_qiz_tc');
          $this->db->where('qiz_id', $qiz_id);
          $this->db->where('emp_id', $user['emp_id']);
          $query = $this->db->get();
          $fetch = $query->row_array();
          return $fetch;
  }


  public function create_badges($data,$courses_id)
  {
          date_default_timezone_set("Asia/Bangkok");
          $user = $this->session->userdata('user');
          $this->db->from('lms_bad');
          $this->db->where('badges_name', $data['badges_name']);
          $this->db->where('courses_id', $courses_id);
          $query = $this->db->get();
          if($query->num_rows()==0){
            $data['courses_id'] = $courses_id;
            $this->db->insert('lms_bad', $data);
          }
  }

  public function create_grade($data,$courses_id)
  {
          date_default_timezone_set("Asia/Bangkok");
          $user = $this->session->userdata('user');
          $this->db->from('lms_cug');
          $this->db->where('course_id', $courses_id);
          $query = $this->db->get();
          if($query->num_rows()==0){
            $data['course_id'] = $courses_id;
            $this->db->insert('lms_cug', $data);
          }
  }

  public function create_question($data)
  {
          date_default_timezone_set("Asia/Bangkok");
          $user = $this->session->userdata('user');
          $this->db->from('lms_ques');
          $this->db->where('qiz_id', $data['qiz_id']);
          $this->db->where('ques_name_th', $data['ques_name_th']);
          $this->db->where('ques_name_en', $data['ques_name_en']);
          $this->db->where('ques_status', '1');
          $query = $this->db->get();
          if($query->num_rows()==0){
            $this->db->insert('lms_ques', $data);
          }
          $id = $this->db->insert_id();
          return $id;
  }
  
  public function create_question_multi($data)
  {
          date_default_timezone_set("Asia/Bangkok");
          $user = $this->session->userdata('user');
          $this->db->from('lms_ques_mul');
          $this->db->where('ques_id', $data['ques_id']);
          $query = $this->db->get();
          if($query->num_rows()==0){
            $this->db->insert('lms_ques_mul', $data);
          }else{
            $this->db->where('ques_id', $data['ques_id']);
            $this->db->update('lms_ques_mul', $data);
          }
  }

  public function create_quiz($data)
  {
          date_default_timezone_set("Asia/Bangkok");
          $user = $this->session->userdata('user');
          $this->db->from('lms_qiz');
          $this->db->where('cos_id', $data['cos_id']);
          $this->db->where('quiz_name_th', $data['quiz_name_th']);
          $this->db->where('quiz_name_en', $data['quiz_name_en']);
          $this->db->where('quiz_status', '1');
          $query = $this->db->get();
          if($query->num_rows()==0){
            $this->db->insert('lms_qiz', $data);
          }
          $id = $this->db->insert_id();
          return $id;
  }

  public function create_survey($data)
  {
          date_default_timezone_set("Asia/Bangkok");
          $user = $this->session->userdata('user');
          $this->db->from('lms_survey');
          $this->db->where('cos_id', $data['cos_id']);
          $this->db->where('sv_title_th', $data['sv_title_th']);
          $this->db->where('sv_title_en', $data['sv_title_en']);
          $this->db->where('sv_status', '1');
          $query = $this->db->get();
          if($query->num_rows()==0){
            $this->db->insert('lms_survey', $data);
          }
          $id = $this->db->insert_id();
          return $id;
  }
  public function create_survey_detail($data)
  {
          date_default_timezone_set("Asia/Bangkok");
          $user = $this->session->userdata('user');
          $this->db->from('lms_survey_de');
          $where = '';
          if($data['svde_heading_th']!=""&&$data['svde_detail_th']!=""){
            if($where!=""){
              $where .= ' and ';
            }
            $where .= '(svde_heading_th="'.$data['svde_heading_th'].'" and svde_detail_th="'.$data['svde_detail_th'].'")';
          }
          if($data['svde_heading_eng']!=""&&$data['svde_detail_eng']!=""){
            if($where!=""){
              $where .= ' and ';
            }
            $where .= '(svde_heading_eng="'.$data['svde_heading_eng'].'" and svde_detail_eng="'.$data['svde_detail_eng'].'")';
          }
          if($data['svde_heading_jp']!=""&&$data['svde_detail_jp']!=""){
            if($where!=""){
              $where .= ' and ';
            }
            $where .= '(svde_heading_jp="'.$data['svde_heading_jp'].'" and svde_detail_jp="'.$data['svde_detail_jp'].'")';
          }
          if($where!=""){
          $this->db->where($where);
          }
          $this->db->where('sv_id', $data['sv_id']);
          $this->db->where('svde_isDelete', '0');
          $query = $this->db->get();
          $id = "";
          if($query->num_rows()==0&&$where!=""){
            $this->db->insert('lms_survey_de', $data);
            $id = $this->db->insert_id();
          }
          return $id;
  }

  public function create_period_and_permission($data)
  {
          date_default_timezone_set("Asia/Bangkok");
          $user = $this->session->userdata('user');
          $this->db->from('lms_cos_detail');
          $this->db->where('cos_id', $data['cos_id']);
          $this->db->where('date_start', $data['date_start']);
          $this->db->where('date_end', $data['date_end']);
          $this->db->where('status', '1');
          $query = $this->db->get();
          $id = "-";
          if($query->num_rows()==0){
            $this->db->insert('lms_cos_detail', $data);
            $id = $this->db->insert_id();
          }
          return $id;
  }

  public function create_permission_posi($data)
  {
          date_default_timezone_set("Asia/Bangkok");
          $user = $this->session->userdata('user');
          $this->db->from('lms_cos_detail_ug');
          $this->db->where('cosde_id', $data['cosde_id']);
          $this->db->where('ug_id', $data['ug_id']);
          $query = $this->db->get();
          if($query->num_rows()==0){
            $this->db->insert('lms_cos_detail_ug', $data);
          }
  }

  public function update_period_and_permission($data,$cosde_id)
  {
      date_default_timezone_set("Asia/Bangkok");
      $this->db->where('cosde_id', $cosde_id);
      $this->db->update('lms_cos_detail', $data);
  }
  
  public function clear_permission_posi($cosde_id)
  {
      date_default_timezone_set("Asia/Bangkok");
      $this->db->where('cosde_id', $cosde_id);
      $this->db->delete('lms_cos_detail_ug');
  }


  public function update_grade($data,$courses_id)
  {
      date_default_timezone_set("Asia/Bangkok");
      $this->db->where('course_id', $courses_id);
      $this->db->update('lms_cug', $data);
  }

  public function update_quiz($data,$qiz_id)
  {
      $this->db->where('qiz_id', $qiz_id);
      $this->db->update('lms_qiz', $data);
  }

  public function update_survey($data,$sv_id)
  {
      $this->db->where('sv_id', $sv_id);
      $this->db->update('lms_survey', $data);
  }

  public function update_survey_main($data,$sv_id)
  {
      $this->db->where('sv_id', $sv_id);
      $this->db->update('lms_sv', $data);
  }

  public function update_survey_detail($data,$svde_id)
  {
      $this->db->where('svde_id', $svde_id);
      $this->db->update('lms_survey_de', $data);
  }

  public function update_question($data,$ques_id)
  {
      $this->db->where('ques_id', $ques_id);
      $this->db->update('lms_ques', $data);
  }

  public function update_badges($data,$courses_id)
  {
      $this->db->where('courses_id', $courses_id);
      $this->db->update('lms_bad', $data);
  }

  public function update_course($data,$cg_id,$id)
  {
      date_default_timezone_set("Asia/Bangkok");
      $this->db->where('id', $id);
          
      if ($this->db->update('lms_cos', $data)) {
        $arr_cg_id = $this->getcg_idincourse($id);
        //print_r($arr_cg_id);
        for($a=0;$a<countArray($arr_cg_id);$a++){
          if(!in_array($arr_cg_id[$a], $cg_id)){
              $data_sta['status_cg'] = '0';
              $this->db->where('course_id', $id);
              $this->db->where('cg_id', $arr_cg_id[$a]);
              $this->db->update('lms_cosincg', $data_sta);
          }
        }
        $arr_cg_id = $this->getcg_idincourse($id);
        for($a=0;$a<countArray($cg_id);$a++){
          if($cg_id[$a]!=""){
            if(!in_array($cg_id[$a], $arr_cg_id)){
              $data_detail['course_id'] = $id;
              $data_detail['cg_id'] = $cg_id[$a];
              $this->db->insert('lms_cosincg', $data_detail);
            }
          }
        }
        return "2";
      }else{
        return "0";
      }
  }

        public function update_score($data,$cosen_id){
            $user = $this->session->userdata('user');
            date_default_timezone_set("Asia/Bangkok");
            $this->load->model('Function_query_model', 'func_query', FALSE);

              $fetch_chk = $this->func_query->query_row('lms_cos_enroll','','','','cosen_id="'.$cosen_id.'"');
              if($fetch_chk['cosen_status']=="2"){
                $this->db->where('cosen_id', $cosen_id);
                $this->db->delete('lms_log_enroll');
              }

              $fetch_cos = $this->func_query->query_row('lms_cos','','','','lms_cos.cos_id="'.$fetch_chk['cos_id'].'"');

              $cosen_reward = 0;
              $where_detail = 'lms_cos_detail.cos_id="'.$fetch_chk['cos_id'].'" and cosde_isDelete="0" and cosde_status="1" and '.$this->course_availability_where('lms_cos_detail');
              $fetch_cos_detail = $this->func_query->query_row('lms_cos_detail','','','',$where_detail,'cosde_id DESC');
              if(isset($fetch_cos_detail['get_point'])){
                $cosen_reward = floatval($fetch_cos_detail['get_point']);
              }

              $fetch_cug = $this->func_query->query_row('lms_cug','','','','course_id="'.$fetch_chk['cos_id'].'"');

              if(isset($data['cosen_score'])){
                $cosen_score = floatval($data['cosen_score']);
              }else{
                $cosen_score = 0;
              }
              if($fetch_cos['cos_typegrading']=="1"){
                if($cosen_score>=floatval($fetch_cug['mina'])){
                  $cosen_grade = "A";
                }else if($cosen_score>=floatval($fetch_cug['minb'])){
                  $cosen_grade = "B";
                }else if($cosen_score>=floatval($fetch_cug['minc'])){
                  $cosen_grade = "C";
                }else if($cosen_score>=floatval($fetch_cug['mind'])){
                  $cosen_grade = "D";
                }else{
                  $cosen_grade = "F";
                }
              }else{
                if($cosen_score>=floatval($fetch_cug['mina'])){
                  $cosen_grade = "P";
                }else{
                  $cosen_grade = "F";
                }
              }

              $data_update = array(
                  'cosen_score' => $cosen_score,
                  'cosen_grade' => $cosen_grade,
                  'cosen_reward' => $cosen_reward,
                  'cosen_status_sub' => '1',
                  'cosen_finishtime' => date('Y-m-d H:i'),
                  'cosen_modifiedby' => $user['u_id'],
                  'cosen_modifieddate' => date('Y-m-d H:i'),
              );
              if($fetch_chk['cosen_firsttime']==""||$fetch_chk['cosen_firsttime']=="0000-00-00 00:00:00"){
                $data_update['cosen_firsttime'] = date('Y-m-d H:i');
              }
              if(isset($data['cosen_cancelnote'])&&$data['cosen_cancelnote']!=""){
                $data_update['cosen_cancelnote'] = $data['cosen_cancelnote'];
                $data_update['cosen_status'] = '2';
                $data_update['cosen_grade'] = '';
                $data_update['cosen_score'] = '';
                $data_update['cosen_reward'] = '';
                $data_update['cosen_isDelete'] = '1';
                $data_update['cosen_firsttime'] = '0000-00-00 00:00:00';
                $data_update['cosen_finishtime'] = '0000-00-00 00:00:00';
              }
              $this->db->where('cosen_id', $cosen_id);
              $this->db->update('lms_cos_enroll', $data_update);
              if($cosen_reward>0){
                  $this->updatePoint($cosen_reward,$user['emp_id'],$fetch_chk['cos_id']);
              }
        }
        public function getcg_title($course_id,$lang){
          $this->db->select('lms_cog.cgtitle_th,lms_cog.cgtitle_en');
          $this->db->from('lms_cosincg');
          $this->db->join('lms_cog','lms_cosincg.cg_id = lms_cog.id');
          $this->db->where('lms_cosincg.status_cg', '1');
          $this->db->where('lms_cosincg.course_id', $course_id);
          $query = $this->db->get();
          $fetch = $query->result_array();
          $cg_title = "";
          $num = 1;
          if(countArray($fetch)>0){
            foreach ($fetch as $key => $value) {
              if($lang=="thai"){
                $cg_title .= $value['cgtitle_th'];
              }else{
                $cg_title .= $value['cgtitle_en'];
              }
              if($num<countArray($fetch)){
                $cg_title .= ",";
              }
              $num++;
            }
          }
          return $cg_title;
        }


        public function fetch_data_course($user,$wg_id,$cg_id) {
          $lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
          $this->load->model('Manage_model', 'manage', FALSE);
          $this->manage->loadDB();
          $arr['page'] = "course/available";
          $this->db->select('lms_cos.id,lms_cos.com_id,lms_cos.cname_th,lms_cos.cname_en,lms_cos.time_mod,lms_wkg.wtitle_th,lms_wkg.wtitle_en,lms_company.com_name_th,lms_company.com_name_eng,lms_company.com_admin');
          if($user['com_admin']=="OWNER"&&$user['Is_admin']!="0"){
            $this->db->from('lms_cos');
            $this->db->join('lms_wkg','lms_cos.wg_id = lms_wkg.id');
            $this->db->join('lms_company','lms_cos.com_id = lms_company.com_id');
          }else{
            $this->db->from('lms_cos');
            $this->db->join('lms_wkg','lms_cos.wg_id = lms_wkg.id');
            $this->db->join('lms_company','lms_cos.com_id = lms_company.com_id');
            $this->db->where('lms_cos.com_id', $user['com_id']);
            if($wg_id!=""){
              $this->db->where('lms_cos.wg_id', $wg_id);
            }
          }
          $query = $this->db->get();
          $fetch = $query->result_array();
          $num = 1;$count = 0;
          $fetch_arr = array();
          foreach ($fetch as $key => $value) {
            if($user['com_admin']=="OWNER"&&$user['Is_admin']!="0"){
              $transfer = '<button type="button" name="transfer" id="'.$value['id'].'" data-toggle="modal" data-target="#modal-transfercourse" class="btn btn-success btn-xs transfer" title="'.label('stransfer').'"><i class="fas fa-exchange-alt"></i></button>';
              if($cg_id!=""){
                if(in_array($value['id'], $this->chkcg_idincourse($cg_id))){
                  $output = array();
                  $output['0'] = "<span style='float:right;'>".$num."</span>";$num++;
                  if($lang=="thai"){
                    $output['1'] = $value['cname_th'];
                    $output['3'] = $value['wtitle_th'];
                    $output['4'] = $value['com_name_th'];
                  }else{
                    $output['1'] = $value['cname_en'];
                    $output['3'] = $value['wtitle_en'];
                    $output['4'] = $value['com_name_en'];
                  }
                  $output['2'] = $this->getcg_title($value['id'],$lang);
                  $output['5'] = $value['time_mod'];
                    $update = '<button type="button" name="update" id="'.$value['id'].'" title="'.label('sedit').'" class="btn btn-warning btn-xs update"><i class="mdi mdi-lead-pencil"></i></button>';
                    $delete = '<button type="button" name="delete" id="'.$value['id'].'" class="btn btn-danger btn-xs delete" title="'.label('sdelete').'"><i class="mdi mdi-window-close"></i></button>';

                    $arr['btn_update'] = $this->manage->chk_permission($arr['page'],'ru_edit');
                    $arr['btn_delete'] = $this->manage->chk_permission($arr['page'],'ru_del');
                    if($arr['btn_update']!="1"){
                      $update = '';
                    }
                    if($arr['btn_delete']!="1"){
                      $delete = '';
                    }
                    if($value['com_admin']!="OWNER"){
                      $transfer = '';
                    }
                  $output['6'] = '<input type="hidden" name="com_id'.$value['id'].'" id="com_id'.$value['id'].'" value="'.$value['com_id'].'"><button type="button" name="course_detail" id="'.$value['id'].'" title="Course Detail" class="btn btn-info btn-xs course_detail"><i class="mdi mdi-magnify"></i></button>'.$update.$delete.$transfer.' <button type="button" name="course_detail" id="'.$value['id'].'" title="Analytic" class="btn btn-primary btn-xs " onclick="run_analytic('.$value['id'].')"><i class="mdi mdi-chart-areaspline"></i></button>';
                  $count++;
                  array_push($fetch_arr, $output);
                }
              }else{
                $output = array();
                $output['0'] = "<span style='float:right;'>".$num."</span>";$num++;
                if($lang=="thai"){
                  $output['1'] = $value['cname_th'];
                  $output['3'] = $value['wtitle_th'];
                  $output['4'] = $value['com_name_th'];
                }else{
                  $output['1'] = $value['cname_en'];
                  $output['3'] = $value['wtitle_en'];
                  $output['4'] = $value['com_name_en'];
                }
                $output['2'] = $this->getcg_title($value['id'],$lang);
                $output['5'] = $value['time_mod'];

                  $update = '<button type="button" name="update" id="'.$value['id'].'" title="'.label('sedit').'" class="btn btn-warning btn-xs update"><i class="mdi mdi-lead-pencil"></i></button>';
                  $delete = '<button type="button" name="delete" id="'.$value['id'].'" class="btn btn-danger btn-xs delete" title="'.label('sdelete').'"><i class="mdi mdi-window-close"></i></button>';

                  $arr['btn_update'] = $this->manage->chk_permission($arr['page'],'ru_edit');
                  $arr['btn_delete'] = $this->manage->chk_permission($arr['page'],'ru_del');
                  if($arr['btn_update']!="1"){
                    $update = '';
                  }
                  if($arr['btn_delete']!="1"){
                    $delete = '';
                  }
                    if($value['com_admin']!="OWNER"){
                      $transfer = '';
                    }
                  $output['6'] = '<input type="hidden" name="com_id'.$value['id'].'" id="com_id'.$value['id'].'" value="'.$value['com_id'].'"><button type="button" name="course_detail" id="'.$value['id'].'" title="Course Detail" class="btn btn-info btn-xs course_detail"><i class="mdi mdi-magnify"></i></button>'.$update.$delete.$transfer.' <button type="button" name="course_detail" id="'.$value['id'].'" title="Analytic" class="btn btn-primary btn-xs " onclick="run_analytic('.$value['id'].')"><i class="mdi mdi-chart-areaspline"></i></button>';
                $count++;
                array_push($fetch_arr, $output);
              }
            }else{
              if($cg_id!=""){
                if(in_array($value['id'], $this->chkcg_idincourse($cg_id))){
                  $output = array();
                  $output['0'] = "<span style='float:right;'>".$num."</span>";$num++;
                  if($lang=="thai"){
                    $output['1'] = $value['cname_th'];
                    $output['3'] = $value['wtitle_th'];
                  }else{
                    $output['1'] = $value['cname_en'];
                    $output['3'] = $value['wtitle_en'];
                  }
                  $output['2'] = $this->getcg_title($value['id'],$lang);
                  $output['4'] = $value['time_mod'];
                  
                    $update = '<button type="button" name="update" id="'.$value['id'].'" title="'.label('sedit').'" class="btn btn-warning btn-xs update"><i class="mdi mdi-lead-pencil"></i></button>';
                    $delete = '<button type="button" name="delete" id="'.$value['id'].'" class="btn btn-danger btn-xs delete" title="'.label('sdelete').'"><i class="mdi mdi-window-close"></i></button>';

                    $arr['btn_update'] = $this->manage->chk_permission($arr['page'],'ru_edit');
                    $arr['btn_delete'] = $this->manage->chk_permission($arr['page'],'ru_del');
                    if($arr['btn_update']!="1"){
                      $update = '';
                    }
                    if($arr['btn_delete']!="1"){
                      $delete = '';
                    }
                  $output['5'] = '<input type="hidden" name="com_id'.$value['id'].'" id="com_id'.$value['id'].'" value="'.$value['com_id'].'"><button type="button" name="course_detail" id="'.$value['id'].'" title="Course Detail" class="btn btn-info btn-xs course_detail"><i class="mdi mdi-magnify"></i></button>'.$update.$delete;
                  $count++;
                  array_push($fetch_arr, $output);
                }
              }else{
                $output = array();
                $output['0'] = "<span style='float:right;'>".$num."</span>";$num++;
                if($lang=="thai"){
                  $output['1'] = $value['cname_th'];
                  $output['3'] = $value['wtitle_th'];
                }else{
                  $output['1'] = $value['cname_en'];
                  $output['3'] = $value['wtitle_en'];
                }
                $output['2'] = $this->getcg_title($value['id'],$lang);
                $output['4'] = $value['time_mod'];
                
                  $update = '<button type="button" name="update" id="'.$value['id'].'" title="'.label('sedit').'" class="btn btn-warning btn-xs update"><i class="mdi mdi-lead-pencil"></i></button>';
                  $delete = '<button type="button" name="delete" id="'.$value['id'].'" class="btn btn-danger btn-xs delete" title="'.label('sdelete').'"><i class="mdi mdi-window-close"></i></button>';

                  $arr['btn_update'] = $this->manage->chk_permission($arr['page'],'ru_edit');
                  $arr['btn_delete'] = $this->manage->chk_permission($arr['page'],'ru_del');
                  if($arr['btn_update']!="1"){
                    $update = '';
                  }
                  if($arr['btn_delete']!="1"){
                    $delete = '';
                  }
                $output['5'] = '<input type="hidden" name="com_id'.$value['id'].'" id="com_id'.$value['id'].'" value="'.$value['com_id'].'"><button type="button" name="course_detail" id="'.$value['id'].'" title="Course Detail" class="btn btn-info btn-xs course_detail"><i class="mdi mdi-magnify"></i></button>'.$update.$delete;
                $count++;
                array_push($fetch_arr, $output);
              }
            }
          }
          return $fetch_arr;
        }


        public function fetch_data_register($user,$cos_id) {
          $thaimonth = array("","มกราคม","กุมภาพันธ์","มีนาคม","เมษายน","พฤษภาคม","มิถุนายน","กรกฎาคม","สิงหาคม","กันยายน","ตุลาคม","พฤศจิกายน","ธันวาคม");
          $lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
          $this->db->select('lms_emp.emp_id,lms_emp.emp_c,lms_emp.fullname_th,lms_emp.fullname_en,lms_cos_enroll.cosen_score,lms_cos_enroll.cosen_grade,lms_cos_enroll.cosen_status,lms_cos_enroll.cosen_id,lms_cos_enroll.cosen_finishtime,lms_cos_enroll.cosen_cancelnote');
          $this->db->from('lms_cos_enroll');
          $this->db->join('lms_emp','lms_cos_enroll.emp_id = lms_emp.emp_id');
          $this->db->where('lms_cos_enroll.cos_id',$cos_id);
          $this->db->order_by('lms_cos_enroll.cosen_modtime', 'DESC');
          $query = $this->db->get();
          $fetch = $query->result_array();
          $num = 1;$count = 0;
          $fetch_arr = array();
          foreach ($fetch as $key => $value) {
              $output = array();
              $output['0'] = "<span style='float:right;'>".$num."</span>";$num++;
                $output['1'] = $value['emp_c'];
              if($lang=="thai"){
                $output['2'] = $value['fullname_th'];
              }else{
                $output['2'] = $value['fullname_en'];
              }
              $count++;
              array_push($fetch_arr, $output);
          }
          return $fetch_arr;
        }


        public function fetch_data_enroll_detail($user,$cos_id) {
          $thaimonth = array("","มกราคม","กุมภาพันธ์","มีนาคม","เมษายน","พฤษภาคม","มิถุนายน","กรกฎาคม","สิงหาคม","กันยายน","ตุลาคม","พฤศจิกายน","ธันวาคม");
          $lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
          $this->db->select('lms_emp.emp_id,lms_emp.emp_c,lms_emp.fullname_th,lms_emp.fullname_en,lms_cos_enroll.cosen_score,lms_cos_enroll.cosen_grade,lms_cos_enroll.cosen_status,lms_cos_enroll.cosen_id,lms_cos_enroll.cosen_finishtime,lms_cos_enroll.cosen_cancelnote');
          $this->db->from('lms_cos_enroll');
          $this->db->join('lms_emp','lms_cos_enroll.emp_id = lms_emp.emp_id');
          $this->db->where('lms_cos_enroll.cos_id',$cos_id);
          $query = $this->db->get();
          $fetch = $query->result_array();
          $num = 1;$count = 0;
          $fetch_arr = array();
          foreach ($fetch as $key => $value) {
              $output = array();
              $output['0'] = "<span style='float:right;'>".$num."</span>";$num++;
              if($lang=="thai"){
                $output['1'] = $value['fullname_th'];
              }else{
                $output['1'] = $value['fullname_en'];
              }
              if($value['cosen_status']=="1"){
                $output['2'] = label('real_student');
                $output['3'] = '<input type="number" id="cosen_score'.$value['cosen_id'].'" name="cosen_score'.$value['cosen_id'].'" min="0" max="100" class="form-control" onchange="changeScore('.$value['cosen_id'].')" value="'.$value['cosen_score'].'">';
                $output['4'] = '<button type="button" name="delete_enroll" id="'.$value['cosen_id'].'" class="btn btn-danger btn-xs delete_enroll" title="'.label('sdelete').'"><i class="mdi mdi-window-close"></i></button>';
              }else if($value['cosen_status']=="2"){
                $output['2'] = label('r_canceled')." (".$value['cosen_cancelnote'].")";
                $output['3'] = $value['cosen_score'];
                $output['4'] = '<button type="button" name="Reenroll" id="'.$value['cosen_id'].'" class="btn btn-warning btn-xs Reenroll" title="Re Enroll"><i class="mdi mdi-backup-restore"></i></button>';
              }else{
                $output['2'] = label('not_start');
                $output['3'] = '<input type="number" id="cosen_score'.$value['cosen_id'].'" name="cosen_score'.$value['cosen_id'].'" min="0" max="100" class="form-control" onchange="changeScore('.$value['cosen_id'].')" value="'.$value['cosen_score'].'">';
                $output['4'] = '<button type="button" name="delete_enroll" id="'.$value['cosen_id'].'" class="btn btn-danger btn-xs delete_enroll" title="'.label('sdelete').'"><i class="mdi mdi-window-close"></i></button>';
              }
              $count++;
              array_push($fetch_arr, $output);
          }
          return $fetch_arr;
        }


        public function fetch_data_enroll_qiz_detail($user,$qiz_id) {
          $thaimonth = array("","มกราคม","กุมภาพันธ์","มีนาคม","เมษายน","พฤษภาคม","มิถุนายน","กรกฎาคม","สิงหาคม","กันยายน","ตุลาคม","พฤศจิกายน","ธันวาคม");
          $lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
          $this->load->model('Function_query_model', 'func_query', FALSE);
          $fetch = $this->func_query->query_result('lms_qiz_tc','lms_emp','lms_qiz_tc.emp_id = lms_emp.emp_id','','lms_qiz_tc.qiz_id="'.$qiz_id.'"','','lms_emp.emp_id,lms_emp.emp_c,lms_emp.fullname_th,lms_emp.fullname_en,lms_qiz_tc.sum_score,lms_qiz_tc.qiztc_id,lms_qiz_tc.qiz_status,lms_emp.com_id');
          $num = 1;$count = 0;
          $fetch_arr = array();
          foreach ($fetch as $key => $value) {
              $fetch_company = $this->func_query->query_row('lms_company','','','','com_id="'.$value['com_id'].'"');
              $output = array();
              $output['0'] = "<span style='float:right;'>".$num."</span>";$num++;
              if($lang=="thai"){
                $output['1'] = $value['fullname_th'];
                $output['2'] = $fetch_company['com_name_th'];
              }else{
                $output['1'] = $value['fullname_en'];
                $output['2'] = $fetch_company['com_name_eng'];
              }
              if($value['qiz_status']=="3"){
                $output['3'] = label('done');
              }else if($value['qiz_status']=="1"){
                $output['3'] = label('noProgress');
              }else if($value['qiz_status']=="4"){
                $output['3'] = label('m_cancel');
              }else{
                $output['3'] = label('not_start');
              }
              $output['4'] = '<input type="number" id="sum_score'.$value['id'].'" name="sum_score'.$value['id'].'" min="0" max="100" class="form-control" onchange="changeScore_qiz('.$value['id'].')" value="'.$value['sum_score'].'">';
              $count++;
              array_push($fetch_arr, $output);
          }
          return $fetch_arr;
        }

        public function fetch_data_learning_subject_overview($user) {
          $thaimonth = array("","มกราคม","กุมภาพันธ์","มีนาคม","เมษายน","พฤษภาคม","มิถุนายน","กรกฎาคม","สิงหาคม","กันยายน","ตุลาคม","พฤศจิกายน","ธันวาคม");
          $lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
          $this->db->from('lms_cos');
          $this->db->where('lms_cos.status','1');
          $this->db->where('lms_cos.com_id',$user['com_id']);
          $query = $this->db->get();
          $fetch = $query->result_array();
          $num = 1;$count = 0;
          $fetch_arr = array();
          foreach ($fetch as $key => $value) {
              $output = array();
              if($lang=="thai"){
                $output['0'] = $value['cname_th'];
              }else{
                $output['0'] = $value['cname_en'];
              }
              $seat_count = 0;
                $this->db->from('lms_cos_enroll');
                $this->db->where('lms_cos_enroll.cosen_status','1');
                $this->db->where('cos_id',$value['id']);
                $query_count = $this->db->get();
                $seat_count = $query_count->num_rows();
                //$seat_count = number_format((intval($num_rows)/intval($value['seat_count']))*100,2);
              $output['1'] = '<span class="badge badge-pill badge-info">'.$seat_count.'</span>';

              $this->db->from('lms_cos_enroll');
              //$this->db->join('lms_emp','lms_cos_enroll.emp_id = lms_emp.emp_id');
              $this->db->where('lms_cos_enroll.cos_id',$value['id']);
              $this->db->where('lms_cos_enroll.cosen_status','1');
              $this->db->where('lms_cos_enroll.cosen_status_sub','1');
              $this->db->where('lms_cos_enroll.cosen_firsttime!=','0000-00-00 00:00:00');
              $this->db->where('lms_cos_enroll.cosen_finishtime!=','0000-00-00 00:00:00');
              //$this->db->order_by('lms_cos_enroll.cosen_score','DESC');
              $query_fetch = $this->db->get();
              $num_fetch = $query_fetch->num_rows();
               /* $fullname = '-';
                if($num_fetch>0){
                    $fetch_data = $query_fetch->row_array();
                    if($lang=="thai"){
                      $fullname = $fetch_data['fullname_th'];
                    }else{
                      $fullname = $fetch_data['fullname_en'];
                    }
                }*/
              $output['2'] = '<span class="badge badge-pill badge-success">'.$num_fetch.'</span>';
              /*$str_rating = '';
              if(intval($value['cos_rating'])>0){
                for ($i=1; $i <= intval($value['cos_rating']); $i++) { 
                  $str_rating .= '<i class="fa fa-star text-warning"></i>';
                }
              }
              $output['3'] = $str_rating;*/
              $this->db->from('lms_cos_enroll');
              //$this->db->join('lms_emp','lms_cos_enroll.emp_id = lms_emp.emp_id');
              $this->db->where('lms_cos_enroll.cos_id',$value['id']);
              $this->db->where('lms_cos_enroll.cosen_status','1');
              $this->db->where('lms_cos_enroll.cosen_firsttime!=','0000-00-00 00:00:00');
              $this->db->where('lms_cos_enroll.cosen_finishtime','0000-00-00 00:00:00');
              //$this->db->order_by('lms_cos_enroll.cosen_score','DESC');
              $query_fetch = $this->db->get();
              $num_fetch = $query_fetch->num_rows();
               /* $fullname = '-';
                if($num_fetch>0){
                    $fetch_data = $query_fetch->row_array();
                    if($lang=="thai"){
                      $fullname = $fetch_data['fullname_th'];
                    }else{
                      $fullname = $fetch_data['fullname_en'];
                    }
                }*/
              $output['3'] = '<span class="badge badge-pill badge-warning">'.$num_fetch.'</span>';
              $this->db->from('lms_cos_enroll');
              //$this->db->join('lms_emp','lms_cos_enroll.emp_id = lms_emp.emp_id');
              $this->db->where('lms_cos_enroll.cos_id',$value['id']);
              $this->db->where('lms_cos_enroll.cosen_status','1');
              $this->db->where('lms_cos_enroll.cosen_firsttime','0000-00-00 00:00:00');
              $this->db->where('lms_cos_enroll.cosen_finishtime','0000-00-00 00:00:00');
              //$this->db->order_by('lms_cos_enroll.cosen_score','DESC');
              $query_fetch = $this->db->get();
              $num_fetch = $query_fetch->num_rows();
               /* $fullname = '-';
                if($num_fetch>0){
                    $fetch_data = $query_fetch->row_array();
                    if($lang=="thai"){
                      $fullname = $fetch_data['fullname_th'];
                    }else{
                      $fullname = $fetch_data['fullname_en'];
                    }
                }*/
              $output['4'] = '<span class="badge badge-pill badge-danger">'.$num_fetch.'</span>';
              $output['5'] = '<button type="button" name="course_detail" id="'.$value['id'].'" title="Analytic" class="btn btn-primary btn-xs " onclick="run_analytic('.$value['id'].')"><i class="mdi mdi-chart-areaspline"></i></button>';
              $count++;
              array_push($fetch_arr, $output);
          }
          return $fetch_arr;
        }

        public function fetch_data_lastcomplete($user,$cos_id='') {
          $thaimonth = array("","มกราคม","กุมภาพันธ์","มีนาคม","เมษายน","พฤษภาคม","มิถุนายน","กรกฎาคม","สิงหาคม","กันยายน","ตุลาคม","พฤศจิกายน","ธันวาคม");
          $lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
          $this->db->from('lms_cos_enroll');
          $this->db->join('lms_cos','lms_cos_enroll.cos_id = lms_cos.id');
          $this->db->join('lms_usp','lms_cos_enroll.emp_id = lms_usp.emp_id');
          $this->db->join('lms_emp','lms_usp.emp_id = lms_emp.emp_id');
          $this->db->join('lms_usp_gp','lms_usp.ug_id = lms_usp_gp.ug_id');
          $this->db->where('lms_cos_enroll.cosen_status','1');
          $this->db->where('lms_cos_enroll.cosen_status_sub','1');
          if($cos_id != ""){
            $this->db->where('lms_cos_enroll.cos_id',$cos_id);
          }
          $this->db->order_by('lms_cos_enroll.cosen_finishtime','DESC');
          $query = $this->db->get();
          $fetch = $query->result_array();
          $num = 1;$count = 0;
          $fetch_arr = array();
          foreach ($fetch as $key => $value) {
              $output = array();
              if($value['img_profile']==""){
                $value['img_profile'] = "default_profile.jpg";
              }
              $output['0'] = '<span class="round"><img class="img_profile_cos" id="img_profile_cos'.$num.'" onerror="chk_error(this.id)" src="'.REAL_PATH.'/uploads/profile/'.$value['img_profile'].'" alt="" width="50"></span>';
              if($lang=="thai"){
                $output['1'] = '<h6>'.$value['fullname_th'].'</h6><small class="text-muted">'.$value['ug_name_th'].'</small>';
                $output['2'] = $value['cname_th'];
              }else{
                $output['1'] = '<h6>'.$value['fullname_en'].'</h6><small class="text-muted">'.$value['ug_name_en'].'</small>';
                $output['2'] = $value['cname_en'];
              }
              if($value['cosen_grade']!='F'&&$value['cosen_grade']!=''){
                $status = '<span class="label label-success label-rounded">ผ่าน</span>';
              }else{
                $status = '<span class="label label-danger label-rounded">ไม่ผ่าน</span>';
              }
              $output['3'] = $status;
              $output['4'] = $value['cosen_grade'];
              array_push($fetch_arr, $output);$num++;
          }
          return $fetch_arr;
        }

        public function fetch_data_course_detail($user,$cos_id) {
          $thaimonth = array("","มกราคม","กุมภาพันธ์","มีนาคม","เมษายน","พฤษภาคม","มิถุนายน","กรกฎาคม","สิงหาคม","กันยายน","ตุลาคม","พฤศจิกายน","ธันวาคม");
          $lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
          $this->load->model('Manage_model', 'manage', FALSE);
          $this->manage->loadDB();
          $arr['page'] = "course/available";
          $this->db->select('lms_cos_detail.cosde_id,lms_cos_detail.date_start,lms_cos_detail.date_end');
          $this->db->from('lms_cos_detail');
          $this->db->where('lms_cos_detail.cos_id',$cos_id);
          $query = $this->db->get();
          $fetch = $query->result_array();
          $num = 1;$count = 0;
          $fetch_arr = array();
          foreach ($fetch as $key => $value) {
              $output = array();
              $output['0'] = "<span style='float:right;'>".$num."</span>";$num++;
              if($value['date_start']!="0000-00-00 00:00:00"&&$value['date_end']!="0000-00-00 00:00:00"){
                  if($lang=="thai"){
                      $output['1'] = date('d',strtotime($value['date_start']))." ".$thaimonth[intval(date('m',strtotime($value['date_start'])))]." ".(date('Y',strtotime($value['date_start']))+543)." ".date('H:i',strtotime($value['date_start']));
                      $output['2'] = date('d',strtotime($value['date_end']))." ".$thaimonth[intval(date('m',strtotime($value['date_end'])))]." ".(date('Y',strtotime($value['date_end']))+543)." ".date('H:i',strtotime($value['date_end']));
                  }else{
                      $output['1'] = date('d F Y H:i',strtotime($value['date_start']));
                      $output['2'] = date('d F Y H:i',strtotime($value['date_end']));
                  }
              }else{
                  $output['1'] = label('UnlimitedTime');
                  $output['2'] = label('UnlimitedTime');
              }
                  $update = '<button type="button" name="update_period" id="'.$value['cosde_id'].'" title="'.label('sedit').'" class="btn btn-warning btn-xs update_period"><i class="mdi mdi-lead-pencil"></i></button>';
                  $delete = '<button type="button" name="delete_period" id="'.$value['cosde_id'].'" class="btn btn-danger btn-xs delete_period" title="'.label('sdelete').'"><i class="mdi mdi-window-close"></i></button>';

                  $arr['btn_update'] = $this->manage->chk_permission($arr['page'],'ru_edit');
                  $arr['btn_delete'] = $this->manage->chk_permission($arr['page'],'ru_del');
                  if($arr['btn_update']!="1"){
                    $update = '';
                  }
                  if($arr['btn_delete']!="1"){
                    $delete = '';
                  }
              $output['3'] = $update.$delete;
              $count++;
              array_push($fetch_arr, $output);
          }
          return $fetch_arr;
        }

        public function chk_uploadfileyoutube_cosv($cos_id,$url){
          $this->db->from('lms_cos_video');
          $this->db->where('cos_id',$cos_id);
          $this->db->where('cosv_type','url');
          $this->db->where('cosv_video',$url);
          $query_main = $this->db->get();
          $fetch_main = $query_main->result_array();
          if(countArray($fetch_main)>0){
            return 1;
          }else{
            return 0;
          }
        }

        public function chk_uploadfileyoutube($les_id,$url){
          $this->db->from('lms_med');
          $this->db->where('lessons_id',$les_id);
          $this->db->where('type','url');
          $this->db->where('video',$url);
          $query_main = $this->db->get();
          $fetch_main = $query_main->result_array();
          if(countArray($fetch_main)>0){
            return 1;
          }else{
            return 0;
          }
        }

        public function recheck_total_select($tablename,$field_cos,$id,$status,$select_var,$type,$status_id=""){
            $this->db->select($select_var);
            $this->db->from($tablename);
            $this->db->where($field_cos,$id);
            if($tablename=="lms_qiz"){
              $where = "((lms_qiz.period_open <= '".date('Y-m-d H:i')."' and lms_qiz.period_end >='".date('Y-m-d H:i')."') OR (lms_qiz.period_open = '0000-00-00 00:00:00' and lms_qiz.period_end = '0000-00-00 00:00:00'))";
              $this->db->where($where);
              if($status_id!=""){
                $this->db->where('quiz_type',$status_id);
              }
            }
            if($status!=""){
              $this->db->where($status,'1');
            }
            if($type!=""){
              if($type=="lesson"){
                $where = "(les_type = '1' OR les_type = '2') AND (scm_type = '0' OR scm_type = '2')";
                $this->db->where($where);
              }else if($type=="scorm"){
                $this->db->where('les_type','2');
                $this->db->where('(scm_type =', '0', FALSE);
                $this->db->or_where("scm_type = '2')", NULL, FALSE);
              }else{
                $this->db->where('les_type','2');
                $this->db->where('scm_type','1');
              }
            }
            if($tablename=="lms_les"){
              $this->db->order_by('lms_les.les_sequences', 'ASC');
            }
            $query_main = $this->db->get();
            $fetch_main = $query_main->result_array();
            return $fetch_main;
        }

        public function recheck_total($tablename,$field_cos,$id,$status){
            $this->db->from($tablename);
            $this->db->where($field_cos,$id);
            if($status!=""){
              $this->db->where($status,'1');
            }
            $query_main = $this->db->get();
            $fetch_main = $query_main->result_array();
            return countArray($fetch_main);
        }
        public function recheck_endcos($cos_id){
            $user = $this->session->userdata('user');
            $this->db->from('lms_cos_enroll');
            $this->db->where('cos_id',$cos_id);
            $this->db->where('emp_id',$user['emp_id']);
            $this->db->where('cosen_status_sub','1');
            $query_main = $this->db->get();
            $fetch_main = $query_main->row_array();
            if(countArray($fetch_main)>0){
              return 1;
            }else{
              return 0;
            }
        }

        public function recheck_status($tablename_main,$cos_id,$emp_id=""){
          $user = $this->session->userdata('user');
          if($emp_id!=""){
            $emp_id = $emp_id;
          }else{
            $emp_id = $user['emp_id'];
          }
          if($tablename_main=="lesson"){
            $this->db->from('lms_les');
            $this->db->where('cos_id',$cos_id);
            $this->db->where('status','1');
            $query_main = $this->db->get();
            $fetch_main = $query_main->result_array();
            if(countArray($fetch_main)>0){
              $total = 0;$success = 0;
              foreach ($fetch_main as $key_main => $value_main) {
                $this->db->from('lms_les_tc');
                $this->db->where('les_id',$value_main['les_id']);
                $this->db->where('emp_id',$emp_id);
                $query_tc = $this->db->get();
                $fetch_tc = $query_tc->row_array();
                if(isset($fetch_tc['learn_status'])){
                  $total++;
                  if($fetch_tc['learn_status']=="2"){
                    $success++;
                  }
                }
              }
              if($total>0){
                if($success==countArray($fetch_main)){
                  return '<b style="color:#009933"><i class="mdi mdi-checkbox-marked-circle-outline"></i> '.label('done').'</b>';
                }else{
                  return '<b style="color:#e6b800"><i class="mdi mdi-timer-sand"></i> '.label('inProgress').'</b>';
                }
              }else{
                return '<b style="color:#ff0000"><i class="mdi mdi-close-octagon-outline"></i> '.label('not_start').'</b>';
              }
            }else{
              return '00000';
            }
          }else if($tablename_main=="qiz"){
            $this->db->from('lms_qiz');
            $this->db->where('cos_id',$cos_id);
            $this->db->where('quiz_status','1');
            $query_main = $this->db->get();
            $fetch_main = $query_main->result_array();
            if(countArray($fetch_main)>0){
              $total = 0;$success = 0;
              foreach ($fetch_main as $key_main => $value_main) {
                $this->db->from('lms_qiz_tc');
                $this->db->where('qiz_id',$value_main['qiz_id']);
                $this->db->where('emp_id',$emp_id);
                $query_tc = $this->db->get();
                $fetch_tc = $query_tc->row_array();
                if(isset($fetch_tc['qiz_status'])){
                  $total++;
                  if($fetch_tc['qiz_status']=="3"){
                    $success++;
                  }
                }
              }
              if($total>0){
                if($success==countArray($fetch_main)){
                  return '<b style="color:#009933"><i class="mdi mdi-checkbox-marked-circle-outline"></i> '.label('done').'</b>';
                }else{
                  return '<b style="color:#e6b800"><i class="mdi mdi-timer-sand"></i> '.label('preUnDone').'</b>';
                }
              }else{
                return '<b style="color:#ff0000"><i class="mdi mdi-close-octagon-outline"></i> '.label('not_start').'</b>';
              }
            }else{
              return '00000';
            }
          }else if($tablename_main=="survey"){
            $this->db->from('lms_survey');
            $this->db->where('cos_id',$cos_id);
            $this->db->where('sv_status','1');
            $query_main = $this->db->get();
            $fetch_main = $query_main->result_array();
            if(countArray($fetch_main)>0){
              $total = 0;$success = 0;
              foreach ($fetch_main as $key_main => $value_main) {
                $this->db->from('lms_qn_user');
                $this->db->where('sv_id',$value_main['sv_id']);
                $this->db->where('emp_id',$user['emp_id']);
                $query_tc = $this->db->get();
                $fetch_tc = $query_tc->row_array();
                if(isset($fetch_tc['qnu_status'])){
                  $total++;
                  if($fetch_tc['qnu_status']=="2"){
                    $success++;
                  }
                }
              }
              if($total>0){
                if($success==countArray($fetch_main)){
                  return '<b style="color:#009933"><i class="mdi mdi-checkbox-marked-circle-outline"></i> '.label('done').'</b>';
                }else{
                  return '<b style="color:#e6b800"><i class="mdi mdi-timer-sand"></i> '.label('svUnDone').'</b>';
                }
              }else{
                return '<b style="color:#ff0000"><i class="mdi mdi-close-octagon-outline"></i> '.label('not_start').'</b>';
              }
            }
          }
          
        }

        public function fetch_course_lesson($user,$cos_id,$status_user) {
          date_default_timezone_set("Asia/Bangkok");
          $thaimonth = array("","มกราคม","กุมภาพันธ์","มีนาคม","เมษายน","พฤษภาคม","มิถุนายน","กรกฎาคม","สิงหาคม","กันยายน","ตุลาคม","พฤศจิกายน","ธันวาคม");
          $lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
          $user = $this->session->userdata('user');
          $arr['page'] = "course/available";
          $this->load->model('Manage_model', 'manage', FALSE);
          $this->manage->loadDB();
          $this->db->from('lms_les');
          $this->db->where('lms_les.cos_id',$cos_id);
          if($status_user!=""){
            $this->db->where('((lms_les.time_start="0000-00-00 00:00:00" and lms_les.time_end="0000-00-00 00:00:00")', NULL, FALSE);
            $this->db->or_where("(lms_les.time_start<='".date('Y-m-d H:i')."' and lms_les.time_end>='".date('Y-m-d H:i')."'))", NULL, FALSE);
          }
          $this->db->order_by('lms_les.les_sequences', 'ASC');
          $query = $this->db->get();
          $fetch = $query->result_array();
          $num = 1;$count = 0;
          $fetch_arr = array();
          foreach ($fetch as $key => $value) {
              $output = array();
              $output['0'] = "<span style='float:right;'>".$num."</span>";$num++;
              $time_start_les = "";
              $time_end_les = "";

              if($value['time_start']=="0000-00-00 00:00:00"){
                $value['time_start_var'] = "";
                $value['time_start'] = "";
              }else{
                $value['time_start_var'] = $value['time_start'];
                $value['time_start'] = date('d/F/Y',strtotime($value['time_start']));
              }
              if($value['time_end']=="0000-00-00 00:00:00"){
                $value['time_end_var'] = "";
                $value['time_end'] = "";
              }else{
                $value['time_end_var'] = $value['time_end'];
                $value['time_end'] = date('d/F/Y',strtotime($value['time_end']));
              }
              if($status_user==""){
                if($lang=="thai"){
                  $output['1'] = $value['les_name_th'];

                  if($value['time_start']!=""){
                    $time_start_les = date('d/',strtotime($value['time_start_var'])).$thaimonth[intval(date('m',strtotime($value['time_start_var'])))]."/".(date('Y',strtotime($value['time_start_var']))+543);
                  }
                  if($value['time_end']!=""){
                    $time_end_les = date('d/',strtotime($value['time_end_var'])).$thaimonth[intval(date('m',strtotime($value['time_end_var'])))]."/".(date('Y',strtotime($value['time_end_var']))+543);
                  }
                }else{
                  $output['1'] = $value['les_name_en'];

                  if($value['time_start']!=""){
                    $time_start_les = date('d/F/Y',strtotime($value['time_start_var']));
                  }
                  if($value['time_end']!=""){
                    $time_end_les = date('d/F/Y',strtotime($value['time_end_var']));
                  }
                }
                  $output['2'] = $time_start_les;
                  $output['3'] = $time_end_les;
                  $update = '<button type="button" name="update_lesson" id="'.$value['les_id'].'" title="'.label('sedit').'" class="btn btn-warning btn-xs update_lesson"><i class="mdi mdi-lead-pencil"></i></button>';
                  $delete = '<button type="button" name="delete_lesson" id="'.$value['les_id'].'" class="btn btn-danger btn-xs delete_lesson" title="'.label('sdelete').'"><i class="mdi mdi-window-close"></i></button>';

                  $arr['btn_update'] = $this->manage->chk_permission($arr['page'],'ru_edit');
                  $arr['btn_delete'] = $this->manage->chk_permission($arr['page'],'ru_del');
                  if($arr['btn_update']!="1"){
                    $update = '';
                  }
                  if($arr['btn_delete']!="1"){
                    $delete = '';
                  }
                  $output['4'] = $update.$delete;
              }else{
                if($lang=="thai"){
                  $output['1'] = $value['les_name_th'];
                }else{
                  $output['1'] = $value['les_name_en'];
                }
                //mdi-checkbox-marked-circle-outline success
                //mdi-timer-sand inProgress
                //mdi-close-octagon-outline not_start
                $status = '<b style="color:#ff0000"><i class="mdi mdi-close-octagon-outline"></i> '.label('not_start').'</b>';
                $this->db->from('lms_les_tc');
                $this->db->where('lms_les_tc.les_id',$value['les_id']);
                $this->db->where('lms_les_tc.emp_id',$user['emp_id']);
                $query_chk = $this->db->get();
                $fetch_chk = $query_chk->row_array();
                if(isset($fetch_chk['learn_status'])){
                  if($fetch_chk['learn_status']=="1"){
                    $status = '<b style="color:#e6b800"><i class="mdi mdi-timer-sand"></i> '.label('inProgress').'</b>';
                  }else if($fetch_chk['learn_status']=="2"){
                    $status = '<b style="color:#009933"><i class="mdi mdi-checkbox-marked-circle-outline"></i> '.label('done').'</b>';
                  }else if($fetch_chk['learn_status']=="3"){
                    $status = '<b style="color:orange"><i class="mdi mdi-alert-box"></i> '.label('fail').'</b>';
                  }
                }
                $output['2'] = $status;
                $output['3'] = $value['les_id'];
              }

              array_push($fetch_arr, $output);
          }
          return $fetch_arr;
        }

        public function fetch_course_quiz($user,$cos_id,$status_user) {
          $thaimonth = array("","มกราคม","กุมภาพันธ์","มีนาคม","เมษายน","พฤษภาคม","มิถุนายน","กรกฎาคม","สิงหาคม","กันยายน","ตุลาคม","พฤศจิกายน","ธันวาคม");
          $lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
          $user = $this->session->userdata('user');
          $this->load->model('Manage_model', 'manage', FALSE);
          $this->manage->loadDB();
          $arr['page'] = "course/available";
          $this->db->from('lms_qiz');
          $this->db->where('lms_qiz.cos_id',$cos_id);
          $this->db->where('lms_qiz.quiz_status','1');
          $query = $this->db->get();
          $fetch = $query->result_array();
          $num = 1;$count = 0;
          $fetch_arr = array();
          foreach ($fetch as $key => $value) {
              $output = array();
              $output['0'] = "<span style='float:right;'>".$num."</span>";$num++;
              if($lang=="thai"){
                $output['1'] = $value['quiz_name_th'];
              }else{
                $output['1'] = $value['quiz_name_en'];
              }
                if($value['quiz_type']=="1"){
                  $output['2'] = label('preExam');
                }else{
                  $output['2'] = label('finalExam');
                }
              if($status_user==""){
                $output['3'] = $value['quiz_maxscore'];
                  $update = '<button type="button" name="update_quiz" id="'.$value['qiz_id'].'" title="'.label('sedit').'" class="btn btn-warning btn-xs update_quiz"><i class="mdi mdi-lead-pencil"></i></button>';
                  $delete = '<button type="button" name="delete_quiz" id="'.$value['qiz_id'].'" class="btn btn-danger btn-xs delete_quiz" title="'.label('sdelete').'"><i class="mdi mdi-window-close"></i></button>';

                  $arr['btn_update'] = $this->manage->chk_permission($arr['page'],'ru_edit');
                  $arr['btn_delete'] = $this->manage->chk_permission($arr['page'],'ru_del');
                  if($arr['btn_update']!="1"){
                    $update = '';
                  }
                  if($arr['btn_delete']!="1"){
                    $delete = '';
                  }
                $output['4'] = '<button type="button" name="quiz_detail" id="'.$value['qiz_id'].'" title="Quiz Detail" class="btn btn-info btn-xs quiz_detail"><i class="mdi mdi-magnify"></i></button>'.$update.$delete;
              }else{
                $score = 0;
                $status = '<b style="color:#ff0000"><i class="mdi mdi-close-octagon-outline"></i> '.label('not_start').'</b>';
                $this->db->from('lms_qiz_tc');
                $this->db->where('lms_qiz_tc.qiz_id',$value['qiz_id']);
                $this->db->where('lms_qiz_tc.emp_id',$user['emp_id']);
                $query_chk = $this->db->get();
                $fetch_chk = $query_chk->row_array();
                if(isset($fetch_chk['sum_score'])){
                  $score = floatval($fetch_chk['sum_score']);
                  if($fetch_chk['qiz_status']=="1"){
                    $status = '<b style="color:#e6b800"><i class="mdi mdi-timer-sand"></i> '.label('preUnDone').'</b>';
                  }else if($fetch_chk['qiz_status']=="2"){
                    $status = '<b style="color:orange"><i class="mdi mdi-close-box"></i> '.label('cannot-complete').'</b>';
                  }else if($fetch_chk['qiz_status']=="3"){
                    $status = '<b style="color:#009933"><i class="mdi mdi-checkbox-marked-circle-outline"></i> '.label('done').'</b>';
                  }
                }
                $score_total = 0;
                $this->db->from('lms_ques');
                $this->db->where('lms_ques.qiz_id',$value['qiz_id']);
                $query_sum = $this->db->get();
                $fetch_sum = $query_sum->result_array();
                foreach ($fetch_sum as $key_sum => $value_sum) {
                  $score_total += floatval($value_sum['ques_score']);
                }

                $score = (floatval($fetch_chk['sum_score'])/$score_total)*100;
                $output['3'] = number_format($score,2)." / 100";
                $output['4'] = $status;
                $output['5'] = $value['qiz_id'];
              }
              $count++;
              array_push($fetch_arr, $output);
          }
          return $fetch_arr;
        }

        public function fetch_course_question($user,$qiz_id) {
          function str_replace_func($value=""){
              $value = str_replace("<p>","",$value);
              $value = str_replace("</p>","",$value);
              return $value;
          }
          $thaimonth = array("","มกราคม","กุมภาพันธ์","มีนาคม","เมษายน","พฤษภาคม","มิถุนายน","กรกฎาคม","สิงหาคม","กันยายน","ตุลาคม","พฤศจิกายน","ธันวาคม");
          $lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
          $this->load->model('Manage_model', 'manage', FALSE);
          $this->manage->loadDB();
          $this->db->from('lms_ques');
          $this->db->where('lms_ques.qiz_id',$qiz_id);
          $this->db->where('lms_ques.ques_status','1');
          $arr['page'] = "course/available";
          $query = $this->db->get();
          $fetch = $query->result_array();
          $num = 1;$count = 0;
          $fetch_arr = array();
          foreach ($fetch as $key => $value) {
              $output = array();
              $output['3'] = "";

              $output['0'] = $num;
              if($value['ques_type']=="sa"){
                $output['1'] = label('qt_sa');
              }else if($value['ques_type']=="sub"){
                $output['1'] = label('qt_sub');
              }else{
                $output['1'] = label('qt_multi');
                $this->db->from('lms_ques_mul');
                $this->db->where('lms_ques_mul.ques_id',$value['ques_id']);
                $query_mul = $this->db->get();
                $fetch_mul = $query_mul->result_array();
                if(countArray($fetch_mul)>0){
                  foreach ($fetch_mul as $key_mul => $value_mul) {
                    if($lang=="thai"){
                      if($value_mul['mul_c1_th']!=""){
                        $output['3'] .= "1.".str_replace_func($value_mul['mul_c1_th'])."<br>";
                      }
                      if($value_mul['mul_c2_th']!=""){
                        $output['3'] .= "2.".str_replace_func($value_mul['mul_c2_th'])."<br>";
                      }
                      if($value_mul['mul_c3_th']!=""){
                        $output['3'] .= "3.".str_replace_func($value_mul['mul_c3_th'])."<br>";
                      }
                      if($value_mul['mul_c4_th']!=""){
                        $output['3'] .= "4.".str_replace_func($value_mul['mul_c4_th'])."<br>";
                      }
                      if($value_mul['mul_c5_th']!=""){
                        $output['3'] .= "5.".str_replace_func($value_mul['mul_c5_th'])."<br>";
                      }
                    }else{
                      if($value_mul['mul_c1_en']!=""){
                        $output['3'] .= "1.".str_replace_func($value_mul['mul_c1_en'])."<br>";
                      }
                      if($value_mul['mul_c2_en']!=""){
                        $output['3'] .= "2.".str_replace_func($value_mul['mul_c2_en'])."<br>";
                      }
                      if($value_mul['mul_c3_en']!=""){
                        $output['3'] .= "3.".str_replace_func($value_mul['mul_c3_en'])."<br>";
                      }
                      if($value_mul['mul_c4_en']!=""){
                        $output['3'] .= "4.".str_replace_func($value_mul['mul_c4_en'])."<br>";
                      }
                      if($value_mul['mul_c5_en']!=""){
                        $output['3'] .= "5.".str_replace_func($value_mul['mul_c5_en'])."<br>";
                      }
                    }
                  }
                }
              }
              if($lang=="thai"){
                $output['2'] = $value['ques_name_th'];
              }else{
                $output['2'] = $value['ques_name_en'];
              }
                  $update = '<button type="button" name="update_ques" id="'.$value['ques_id'].'" title="'.label('sedit').'" class="btn btn-warning btn-xs update_ques"><i class="mdi mdi-lead-pencil"></i></button>';
                  $delete = '<button type="button" name="delete_ques" id="'.$value['ques_id'].'" class="btn btn-danger btn-xs delete_ques" title="'.label('sdelete').'"><i class="mdi mdi-window-close"></i></button>';

                  $arr['btn_update'] = $this->manage->chk_permission($arr['page'],'ru_edit');
                  $arr['btn_delete'] = $this->manage->chk_permission($arr['page'],'ru_del');
                  if($arr['btn_update']!="1"){
                    $update = '';
                  }
                  if($arr['btn_delete']!="1"){
                    $delete = '';
                  }
              $output['4'] = '<button type="button" name="check_ques" id="'.$value['ques_id'].'" title="'.label('chk_answer').'" class="btn btn-info btn-xs check_ques"><i class="mdi mdi-check-circle"></i></button>'.$update.$delete;
              $count++;$num++;
              array_push($fetch_arr, $output);
          }
          return $fetch_arr;
        }
        public function fetch_quiz_question_check($user,$ques_id) {
          function str_replace_func($value=""){
              $value = str_replace("<p>","",$value);
              $value = str_replace("</p>","",$value);
              return $value;
          }
          $thaimonth = array("","มกราคม","กุมภาพันธ์","มีนาคม","เมษายน","พฤษภาคม","มิถุนายน","กรกฎาคม","สิงหาคม","กันยายน","ตุลาคม","พฤศจิกายน","ธันวาคม");
          $lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
          $arr_choice = array();
          $this->db->from('lms_ques');
          $this->db->where('lms_ques.ques_id',$ques_id);
          $query_main = $this->db->get();
          $fetch_main = $query_main->row_array();
          if($fetch_main['ques_type']=="multi"){
            $this->db->from('lms_ques_mul');
            $this->db->where('lms_ques_mul.ques_id',$ques_id);
            $query_mul = $this->db->get();
            $fetch_mul = $query_mul->row_array();
              if($lang == "thai"){
                $arr_choice['mul_c1']=str_replace_func($fetch_mul['mul_c1_th']);
                $arr_choice['mul_c2']=str_replace_func($fetch_mul['mul_c2_th']);
                $arr_choice['mul_c3']=str_replace_func($fetch_mul['mul_c3_th']);
                $arr_choice['mul_c4']=str_replace_func($fetch_mul['mul_c4_th']);
                $arr_choice['mul_c5']=str_replace_func($fetch_mul['mul_c5_th']);
              }else{
                $arr_choice['mul_c1']=str_replace_func($fetch_mul['mul_c1_en']);
                $arr_choice['mul_c2']=str_replace_func($fetch_mul['mul_c2_en']);
                $arr_choice['mul_c3']=str_replace_func($fetch_mul['mul_c3_en']);
                $arr_choice['mul_c4']=str_replace_func($fetch_mul['mul_c4_en']);
                $arr_choice['mul_c5']=str_replace_func($fetch_mul['mul_c5_en']);
              }
          }
          $this->db->from('lms_ques_tc');
          $this->db->join('lms_emp','lms_ques_tc.emp_id = lms_emp.emp_id');
          $this->db->where('lms_ques_tc.ques_id',$ques_id);
          $this->db->where('lms_ques_tc.tc_flag','1');
          $this->db->where('lms_ques_tc.tc_save','1');
          $query = $this->db->get();
          $fetch = $query->result_array();
          $num = 1;$count = 0;
          $fetch_arr = array();
          foreach ($fetch as $key => $value) {
              $output = array();
              $output['0'] = $value['emp_c'];
              if($lang=="thai"){
                $output['1'] = $value['fullname_th'];
              }else{
                $output['1'] = $value['fullname_en'];
              }
              if($fetch_main['ques_type']=="multi"){
                $output['2'] = $arr_choice[$value['tc_answer']];
                $output['3'] = "-";
                $output['4'] = $value['tc_score'];
              }else{
                $output['2'] = $value['tc_answer'];
                $output['3'] = '<textarea class="form-control" rows="3" id="tc_note_'.$value['tc_id'].'" name="tc_note_'.$value['tc_id'].'" onchange="changeNote_tc('.$value['tc_id'].')">'.$value['tc_note'].'</textarea>';
                $output['4'] = '<input type="hidden" id="ori_score_'.$value['tc_id'].'" name="ori_score_'.$value['tc_id'].'" value="'.$value['tc_score'].'"><input type="hidden" id="ques_score_'.$value['tc_id'].'" name="ques_score_'.$value['tc_id'].'" value="'.$fetch_main['ques_score'].'"><input type="number" id="score_'.$value['tc_id'].'" name="score_'.$value['tc_id'].'" onchange="changeScore_tc('.$value['tc_id'].')" value="'.$value['tc_score'].'">';
              }
              if(isset($value['tc_upload_file'])&&$value['tc_upload_file']!=""){
                $output['2'] .= '<br><a href="'.base_url().'uploads/quiz_answer/'.$value['tc_upload_file'].'" target="_blank"><i class="mdi mdi-attachment"></i> '.htmlentities($value['tc_upload_original'], ENT_QUOTES).'</a>';
              }
              
              $count++;
              array_push($fetch_arr, $output);
          }
          return $fetch_arr;
        }

        public function fetch_quiz_detail($user,$qiz_id) {
          $arr['page'] = "course/available";
          $thaimonth = array("","มกราคม","กุมภาพันธ์","มีนาคม","เมษายน","พฤษภาคม","มิถุนายน","กรกฎาคม","สิงหาคม","กันยายน","ตุลาคม","พฤศจิกายน","ธันวาคม");
          $lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
          $this->load->model('Manage_model', 'manage', FALSE);
          $this->manage->loadDB();
          $this->db->from('lms_qiz');
          $this->db->where('lms_qiz.cos_id',$cos_id);
          $this->db->where('lms_qiz.quiz_status','1');
          $query = $this->db->get();
          $fetch = $query->result_array();
          $num = 1;$count = 0;
          $fetch_arr = array();
          foreach ($fetch as $key => $value) {
              $output = array();
              $output['0'] = "<span style='float:right;'>".$num."</span>";$num++;
              if($lang=="thai"){
                $output['1'] = $value['quiz_name_th'];
              }else{
                $output['1'] = $value['quiz_name_en'];
              }
              $output['2'] = $value['quiz_maxscore'];

                  $update = '<button type="button" name="update_quiz" id="'.$value['qiz_id'].'" title="'.label('sedit').'" class="btn btn-warning btn-xs update_quiz"><i class="mdi mdi-lead-pencil"></i></button>';
                  $delete = '<button type="button" name="delete_quiz" id="'.$value['qiz_id'].'" class="btn btn-danger btn-xs delete_quiz" title="'.label('sdelete').'"><i class="mdi mdi-window-close"></i></button>';

                  $arr['btn_update'] = $this->manage->chk_permission($arr['page'],'ru_edit');
                  $arr['btn_delete'] = $this->manage->chk_permission($arr['page'],'ru_del');
                  if($arr['btn_update']!="1"){
                    $update = '';
                  }
                  if($arr['btn_delete']!="1"){
                    $delete = '';
                  }
              $output['3'] = '<button type="button" name="quiz_detail" id="'.$value['qiz_id'].'" title="Quiz Detail" class="btn btn-info btn-xs quiz_detail"><i class="mdi mdi-magnify"></i></button>'.$update.$delete;
              $count++;
              array_push($fetch_arr, $output);
          }
          return $fetch_arr;
        }

        public function result_data($tablename,$field_id,$id,$ordername,$typeorder){
          $this->db->from($tablename);
          $this->db->where($field_id,$id);
          if($ordername!=""){
            $this->db->order_by($ordername,$typeorder);
          }
          $query = $this->db->get();
          return $query->result_array();
        }
        public function onchk_answer($qiz_id){
          $user = $this->session->userdata('user');
          $this->db->from('lms_ques');
          $this->db->where('lms_ques.qiz_id',$qiz_id);
          $this->db->where('lms_ques.ques_show','1');
          $this->db->where('lms_ques.ques_status', '1');
          $this->db->order_by('lms_ques.ques_id','ASC');
          $query = $this->db->get();
          $fetch = $query->result_array();
          $num = 0;
              $this->db->from('lms_ques_tc');
              $this->db->where('lms_ques_tc.qiz_id',$qiz_id);
              $this->db->where('lms_ques_tc.emp_id',$user['emp_id']);
              $this->db->where('lms_ques_tc.tc_answer','');
              $query_row = $this->db->get();
              $fetch_row = $query_row->row_array();
              if(isset($fetch_row)){
                $num = countArray($fetch_row);
              }
           /*if($num==0){
              $this->db->from('lms_ques_tc');
              $this->db->where('lms_ques_tc.qiz_id',$qiz_id);
              $this->db->where('lms_ques_tc.emp_id',$user['emp_id']);
              $query_row = $this->db->get();
              $fetch_row = $query_row->row_array();

              $this->db->from('lms_qiz');
              $this->db->where('lms_qiz.qiz_id', $qiz_id);
              $query_qiz = $this->db->get();
              $fetch_qiz = $query_qiz->row_array();
              $quiz_numofshown = intval($fetch_qiz['quiz_numofshown']);
              if(countArray($fetch_row)!=$quiz_numofshown){
                $num = $quiz_numofshown-countArray($fetch_row);
              }
           }*/
           return $num;
        }
        public function cal_score_quiz($qiz_id){
            date_default_timezone_set("Asia/Bangkok");
            $score = 0;
            $this->db->from('lms_ques');
            $this->db->where('lms_ques.qiz_id',$qiz_id);
            $this->db->where('lms_ques.ques_show','1');
            $this->db->where('lms_ques.ques_status', '1');
            $query = $this->db->get();
            $fetch = $query->result_array();
            if(countArray($fetch)>0){
              foreach ($fetch as $key => $value) {
                $score += floatval($value['ques_score']);
              }
              $data = array(
                'quiz_maxscore' => $score,
                'time_mod' => date('Y-m-d H:i')
              );
              $this->db->where('qiz_id', $qiz_id);
              $this->db->update('lms_qiz', $data);
            }
        }
        public function answer_data_update_note($tc_id='',$tc_note=''){
           $user = $this->session->userdata('user');

            $this->db->from('lms_ques_tc');
            $this->db->where('lms_ques_tc.tc_id',$tc_id);
            $query_row = $this->db->get();
            $fetch_row = $query_row->row_array();
            if(isset($fetch_row)){
              $data = array(
                'tc_note' => $tc_note
              );
              $this->db->where('tc_id', $tc_id);
              $this->db->update('lms_ques_tc', $data);
            }
         }
        public function answer_data_update_score($tc_id='',$tc_score='',$tc_note=''){
           $user = $this->session->userdata('user');
          date_default_timezone_set("Asia/Bangkok");
           $this->load->model('Function_query_model', 'func_query', FALSE);
            $this->db->from('lms_ques_tc');
            $this->db->where('lms_ques_tc.tc_id',$tc_id);
            $query_row = $this->db->get();
            $fetch_row = $query_row->row_array();
            if(isset($fetch_row['qiztc_id'])){
                  $qiztc_id = $fetch_row['qiztc_id'];
                  $data = array(
                    'tc_isSavescore' => '1',
                    'tc_score' => $tc_score,
                    'tc_note' => $tc_note
                  );
                  $this->db->where('tc_id', $tc_id);
                  $this->db->update('lms_ques_tc', $data);

                  $this->db->from('lms_qiz');
                  $this->db->where('lms_qiz.qiz_id', $fetch_row['qiz_id']);
                  $query_qiz = $this->db->get();
                  $fetch_qiz = $query_qiz->row_array();

                  $score_sum = 0;
                  $score_per = 0;
                  $score = 0;
                  $fetch_ques_all = $this->func_query->query_result('lms_ques','','','','qiz_id="'.$fetch_row['qiz_id'].'" and ques_isDelete="0"');
                  if(countArray($fetch_ques_all)>0){
                      foreach ($fetch_ques_all as $key_ques_all => $value_ques_all) {
                          $tc_score = 0;
                          $score += floatval($value_ques_all['ques_score']);
                          $fetch_chk = $this->func_query->query_row('lms_ques_tc','','','','qiztc_id="'.$qiztc_id.'" and ques_id="'.$value_ques_all['ques_id'].'"');
                          $fetch_ques = $this->func_query->query_row('lms_ques','','','','ques_id="'.$value_ques_all['ques_id'].'" and ques_type in ("multi","2choice")');
                          if(isset($fetch_ques['ques_score'])){
                            $fetch_quesmulti = $this->func_query->query_row('lms_ques_mul','','','','ques_id="'.$value_ques_all['ques_id'].'"');
                            if(isset($fetch_quesmulti['mul_answer'])){
                              $mul_answer = explode(',', $fetch_quesmulti['mul_answer']);
                              if(in_array($fetch_chk['tc_answer'], $mul_answer)){
                                  $tc_score = floatval($fetch_ques['ques_score']);
                                  $score_sum += floatval($tc_score);
                              }
                            }
                          }else{
                                  $tc_score = floatval($fetch_chk['tc_score']);
                                  $score_sum += floatval($tc_score);
                          }
                      }
                  }

                  if($score_sum>0){
                    $score_per = ($score_sum/$score)*100;
                  }
                  if(floatval($score_per)>=floatval($fetch_qiz['quiz_maxscore'])){
                    $data = array(
                        'time_finish' => date('Y-m-d H:i'),
                        'time_mod' => date('Y-m-d H:i'),
                        'qiz_status' => '3',
                        'sum_score' => $score_sum,
                        'per_score' => $score_per,
                    );
                    $data_tc = array(
                        'tc_finish' => date('Y-m-d H:i'),
                        'tc_flag' => 'true',
                        'tc_save' => 'true'
                    );
                    $this->db->where('emp_id', $fetch_row['emp_id']);
                    $this->db->where('qiz_id', $fetch_row['qiz_id']);
                    $this->db->update('lms_ques_tc', $data_tc);
                  }else{
                    $data = array(
                        'time_mod' => date('Y-m-d H:i'),
                        'qiz_status' => '3',
                        'sum_score' => $score_sum,
                        'per_score' => $score_per,
                    );
                    $checkQuestionTypeInputText = $this->func_query->numrows(
                      'lms_ques', '', '', '', 
                      'qiz_id="'.$fetch_row['qiz_id'].'" and ques_type in ("sa","sub") and ques_isDelete = 0 and ques_id in (select lms_ques_tc.ques_id from lms_ques_tc where lms_ques_tc.qiztc_id = '.$fetch_row['qiztc_id'].')');
                    $queryCheckedFromTeacherSuccess = $this->func_query->numrows(
                      'lms_ques_tc', '', '', '', 
                      'lms_ques_tc.qiztc_id = '.$fetch_row['qiztc_id'].' and tc_isSavescore = 1 and ques_id in (select lms_ques.ques_id from lms_ques where qiz_id="'.$fetch_row['qiz_id'].'" and ques_type in ("sa","sub") and ques_isDelete = 0)');
                    if ($checkQuestionTypeInputText == $queryCheckedFromTeacherSuccess) {
                        $fetchQizTC = $this->func_query->query_row('lms_qiz_tc', '', '', '', 'qiztc_id = '.$fetch_row['qiztc_id']);
                        if (isset($fetch_qiz["quiz_limit"])) {
                            if($fetch_qiz['quiz_limit']=="1"){
                                if($fetch_chk['limit_val'] < intval($fetch_qiz['quiz_limitval'])){
                                    $limit_val = isset($fetchQizTC) ? intval($fetchQizTC['limit_val']) + 1 : 1;
                                    $arrInsertQizTC = array(
                                      'qiz_id'      => $fetchQizTC['qiz_id'],
                                      'emp_id'      => $fetchQizTC['emp_id'],
                                      'time_start'  => date('Y-m-d H:i'),
                                      'time_mod'    => date('Y-m-d H:i'),
                                      'qiz_status'  => '1',
                                      'limit_val'   => $limit_val,
                                      'cosen_id'    => $fetchQizTC["cosen_id"]
                                    );
                                    $this->db->insert('lms_qiz_tc', $arrInsertQizTC);
                                }
                            }else{
                                $limit_val = isset($fetchQizTC) ? intval($fetchQizTC['limit_val']) + 1 : 1;
                                $arrInsertQizTC = array(
                                  'qiz_id'      => $fetchQizTC['qiz_id'],
                                  'emp_id'      => $fetchQizTC['emp_id'],
                                  'time_start'  => date('Y-m-d H:i'),
                                  'time_mod'    => date('Y-m-d H:i'),
                                  'qiz_status'  => '1',
                                  'limit_val'   => $limit_val,
                                  'cosen_id'    => $fetchQizTC["cosen_id"]
                                );
                                $this->db->insert('lms_qiz_tc', $arrInsertQizTC);
                            }
                        }
                    }
                  }
                  $this->db->where('lms_qiz_tc.qiz_id', $fetch_row['qiz_id']);
                  $this->db->where('lms_qiz_tc.qiztc_id', $fetch_row['qiztc_id']);
                  $this->db->where('lms_qiz_tc.emp_id', $fetch_row['emp_id']);
                  $this->db->update('lms_qiz_tc',$data);

                  $this->db->from('lms_qiz');
                  $this->db->where('lms_qiz.qiz_id',$fetch_row['qiz_id']);
                  $query_qiz = $this->db->get();
                  $fetch_qiz = $query_qiz->row_array();

                  $this->db->from('lms_cos_enroll');
                  $this->db->where('lms_cos_enroll.cos_id',$fetch_qiz['cos_id']);
                  $this->db->where('lms_cos_enroll.emp_id',$fetch_row['emp_id']);
                  //$this->db->where('lms_cos_enroll.cosen_status_sub','1');
                  $query_enroll = $this->db->get();
                  $fetch_enroll = $query_enroll->row_array();
                  if(isset($fetch_enroll)){
                    $this->endcos($fetch_qiz['cos_id'], $fetch_row['emp_id']);
                    return "1";
                  }else{
                    return "0";
                  }
            }
        }

        public function endcos($cos_id,$emp_id){
            $lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
            $this->lang->load($lang,$lang);
            $this->load->model('Function_query_model', 'func_query', FALSE);
            $this->load->model('Course_model', 'course', FALSE);
            date_default_timezone_set("Asia/Bangkok");
            $sess = $this->session->userdata("user");
            $this->func_query->loadDB();
            $fetch_chkcos = $this->func_query->query_row('lms_cos','','','','cos_id="'.$cos_id.'"');
            if(isset($fetch_chkcos) && isset($sess["emp_id"])){

                  $fetch_enroll = $this->func_query->query_row('lms_cos_enroll','','','','cos_id="'.$cos_id.'" and emp_id="'.$emp_id.'" and cosen_status="1" and cosen_isDelete="0"','cosen_id DESC');

                  if(isset($fetch_enroll)){
                    $cosen_id = $fetch_enroll['cosen_id'];
                    $status_cos = 0;
                    $amount_les = 0;
                    $amount_qiz = 0;
                    $score = 0;
                    $total = 0;
                    $fetch_qiz = $this->func_query->query_result('lms_qiz','','','','cos_id="'.$cos_id.'" and quiz_type="2" and quiz_show="1" and quiz_status="1" and quiz_isDelete="0"');
                    $num_chk_qiz = 0;
                    $numloopqiz = 0; 
                    $numloopqizpass = 0; 
                    if(countArray($fetch_qiz)>0){
                        foreach ($fetch_qiz as $key_qiz => $value_qiz) {
                            $fetch_chk = $this->func_query->query_row('lms_qiz_tc','','','','qiz_id="'.$value_qiz['qiz_id'].'" and qiz_status="3" and cosen_id="'.$cosen_id.'"','qiztc_id DESC');
                            if(isset($fetch_chk['per_score'])){
                            if($value_qiz['quiz_limit']=="1"){
                              if($fetch_chk['limit_val']<=intval($value_qiz['quiz_limitval'])){
                                if(floatval($fetch_chk['per_score'])>=floatval($value_qiz['quiz_maxscore'])){
                                  $numloopqizpass++;
                                }else{
                                  if($fetch_chk['limit_val']==intval($value_qiz['quiz_limitval'])){
                                    $numloopqizpass++;
                                  }
                                }
                              }
                            }else{
                                if(floatval($fetch_chk['per_score'])>=floatval($value_qiz['quiz_maxscore'])){
                                  $numloopqizpass++;
                                }
                            }
                          }
                          $numloopqiz++;
                            $score_total = 0;
                            $fetch_chk = $this->func_query->query_row('lms_qiz_tc','','','','qiz_id="'.$value_qiz['qiz_id'].'" and qiz_status="3" and cosen_id="'.$cosen_id.'"','qiztc_id DESC');
                            if(isset($fetch_chk['sum_score'])){
                              $amount_qiz++;
                              $fetch_questc = $this->func_query->query_result('lms_ques_tc','','','','qiz_id="'.$value_qiz['qiz_id'].'" and cosen_id="'.$cosen_id.'" and qiztc_id="'.$fetch_chk['qiztc_id'].'"');
                              if(countArray($fetch_questc)==intval($value_qiz['quiz_numofshown'])){
                                $num_chk_qiz++;
                              }
                              /*if(countArray($fetch_questc)>0){
                                foreach ($fetch_questc as $key => $value) {
                                  $fetch_sum = $this->func_query->query_row('lms_ques','','','','ques_id="'.$value['ques_id'].'" and qiz_id="'.$value_qiz['qiz_id'].'"');
                                    $score_total += countArray($fetch_sum)>0?floatval($fetch_sum['ques_score']):0;
                                }
                              }*/
                              
                              $score += isset($fetch_chk['sum_score'])?floatval($fetch_chk['sum_score']):0;
                            }
                            $fetch_sum = $this->func_query->query_row('lms_ques','','','','qiz_id="'.$value_qiz['qiz_id'].'" and ques_id in (select lms_ques_tc.ques_id from lms_ques_tc where qiz_id="'.$value_qiz['qiz_id'].'" and cosen_id="'.$cosen_id.'") and ques_status="1" and ques_isDelete="0"','','SUM(ques_score) as total_score');
                            $total += isset($fetch_chk['sum_score'])?floatval($fetch_sum['total_score']):0;
                        }
                }
                /*$fetch_lesson = $this->func_query->query_result('lms_les','','','','cos_id="'.$cos_id.'" and les_isDelete="0" and les_status="1"');
                if(countArray($fetch_lesson)>0){
                  foreach ($fetch_lesson as $key_lesson => $value_lesson) {
                    $fetch_lestc = $this->func_query->query_row('lms_les_tc','','','','les_id="'.$value_lesson['les_id'].'" and cosen_id="'.$cosen_id.'" and emp_id="'.$emp_id.'"');
                    if(countArray($fetch_lestc)>0){
                      if($fetch_lestc['learn_status']=="2"){
                        $amount_les++;
                      }
                    }
                    if($value_lesson['les_type']=="2"){
                      $fetch_scm = $this->func_query->query_row('lms_scm','lms_scm_val','lms_scm.id = lms_scm_val.scm_id','','lms_scm.lessons_id="'.$value_lesson['les_id'].'" and lms_scm_val.emp_id="'.$emp_id.'" and lms_scm_val.var_name="cmi_core_lesson_status" and (lms_scm_val.var_value="completed" or lms_scm_val.var_value="passed")');
                      if(countArray($fetch_scm)>0){
                        $fetch_rawscm = $this->func_query->query_row('lms_scm','lms_scm_val','lms_scm.id = lms_scm_val.scm_id','','lms_scm.lessons_id="'.$value_lesson['les_id'].'" and lms_scm_val.emp_id="'.$emp_id.'" and lms_scm_val.var_name="cmi_core_score_raw" and lms_scm_val.var_value!=""');
                        if(countArray($fetch_rawscm)>0){
                          $fetch_maxscm = $this->func_query->query_row('lms_scm','lms_scm_val','lms_scm.id = lms_scm_val.scm_id','','lms_scm.lessons_id="'.$value_lesson['les_id'].'" and lms_scm_val.emp_id="'.$emp_id.'" and lms_scm_val.var_name="cmi_core_score_max" and lms_scm_val.var_value!=""');
                          if(countArray($fetch_maxscm)>0){
                                    $total += floatval($fetch_maxscm['var_value']);
                                    $score += floatval($fetch_rawscm['var_value']);
                          }
                        }
                      }
                    }
                  }
                }*/
                $cosen_grade = "";
                    $cosen_score = 0;
                    $cosen_score_per = 0;

                    $cosen_status_sub = '2';
                    $cosen_finishtime = '0000-00-00 00:00:00';
                              //echo "518:".$total."::".$score;
                    if($total>0){
                      if($score>=0&&$total>0){
                        $cosen_score = $score;
                        $cosen_score_per = ($score/$total)*100;
                        $fetch_cug = $this->func_query->query_row('lms_cug','','','','course_id="'.$cos_id.'"');
                        if(countArray($fetch_cug)>0){
                          if($fetch_chkcos['cos_typegrading']=="1"){
                              if($cosen_score_per>=floatval($fetch_cug['mina'])){
                                  $cosen_grade = "A";
                              }else if($cosen_score_per>=floatval($fetch_cug['minb'])){
                                  $cosen_grade = "B";
                              }else if($cosen_score_per>=floatval($fetch_cug['minc'])){
                                  $cosen_grade = "C";
                              }else if($cosen_score_per>=floatval($fetch_cug['mind'])){
                                  $cosen_grade = "D";
                              }else{
                                  $cosen_grade = "F";
                              }
                          }else{
                            if($cosen_score_per>=floatval($fetch_cug['mina'])){
                                  $cosen_grade = "P";
                              }else{
                                  $cosen_grade = "F";
                              }
                          }
                        }
                        if(floatval($cosen_score_per)>=floatval($fetch_chkcos['goal_score'])){
                            $cosen_status_sub = 1;
                            $cosen_finishtime = date('Y-m-d H:i');
                        }else{
                          if($numloopqizpass==$numloopqiz){
                            $cosen_status_sub = 1;
                              $cosen_finishtime = date('Y-m-d H:i');
                          }else{
                              $cosen_status_sub = 2;
                          }
                        }
                      }
                    }else{
                        $cosen_score = 100;
                        $cosen_score_per = 100;

                        $fetch_cug = $this->func_query->query_row('lms_cug','','','','course_id="'.$cos_id.'"');
                        if(isset($fetch_cug['mina'])){
                              if($fetch_chkcos['cos_typegrading']=="1"){
                                  if($cosen_score_per>=floatval($fetch_cug['mina'])){
                                      $cosen_grade = "A";
                                  }else if($cosen_score_per>=floatval($fetch_cug['minb'])){
                                      $cosen_grade = "B";
                                  }else if($cosen_score_per>=floatval($fetch_cug['minc'])){
                                      $cosen_grade = "C";
                                  }else if($cosen_score_per>=floatval($fetch_cug['mind'])){
                                      $cosen_grade = "D";
                                  }else{
                                      $cosen_grade = "F";
                                  }
                              }else{
                                if($cosen_score_per>=floatval($fetch_cug['mina'])){
                                      $cosen_grade = "P";
                                  }else{
                                      $cosen_grade = "F";
                                  }
                              }
                        }
                    }
                    $val_cosen = 0;
                    $total_couse = 0;
                    $fetch_les = $this->func_query->numrows('lms_les','','','','les_isDelete="0" and les_status="1" and cos_id="'.$cos_id.'"');
                    $fetch_lestc = $this->func_query->numrows('lms_les_tc','','','','learn_status="2" and cosen_id="'.$cosen_id.'"');
                    $fetch_qiz = $this->func_query->numrows('lms_qiz','','','','quiz_isDelete="0" and quiz_show="1" and cos_id="'.$cos_id.'"');
                    $fetch_qiztc = $this->func_query->numrows('lms_qiz_tc','','','','qiz_status="3" and cosen_id="'.$cosen_id.'"');
                    $fetch_sv = $this->func_query->numrows('lms_survey','','','','sv_isDelete="0" and sv_status="1" and cos_id="'.$cos_id.'"');
                    $fetch_svtc = $this->func_query->numrows('lms_qn_user','','','','qnu_status="1" and cosen_id="'.$cosen_id.'"');
                    if($fetch_les>0){
                      $total_couse++;
                      if($fetch_les<=$fetch_lestc){
                        $val_cosen++;
                      }
                    }
                    if($fetch_qiz>0){
                      $total_couse++;
                      if($fetch_qiz<=$fetch_qiztc){
                        $val_cosen++;
                      }
                    }
                    if($total_couse==$val_cosen){
                      if($cosen_finishtime!="0000-00-00 00:00:00"&&$cosen_finishtime!=""){
                        $fetch_bad = $this->func_query->query_row('lms_bad','','','','courses_id="'.$cos_id.'"');
                          if(isset($fetch_bad['badges_condition'])){
                            $score_pass = '';
                            if($fetch_bad['badges_condition']=="P"||$fetch_bad['badges_condition']=="F"){
                              $score_pass = floatval($fetch_cug['mina']);
                            }else{
                              if($fetch_bad['badges_condition']=="A"){
                                $score_pass = floatval($fetch_cug['mina']);
                              }else if($fetch_bad['badges_condition']=="B"){
                                $score_pass = floatval($fetch_cug['minb']);
                              }else if($fetch_bad['badges_condition']=="C"){
                                $score_pass = floatval($fetch_cug['minc']);
                              }else if($fetch_bad['badges_condition']=="D"){
                                $score_pass = floatval($fetch_cug['mind']);
                              }
                            }
                            if($cosen_score_per>=$score_pass){
                                $this->update_cert($cos_id,$sess);  
                            }
                          }
                      }
                      $cosen_status_sub = 1;
                      $cosen_finishtime = date('Y-m-d H:i');
                    }else{
                      $cosen_status_sub = 2;
                      $cosen_finishtime = '0000-00-00 00:00:00';
                    }
                    $arr_update = array(
                      'cosen_grade' => $cosen_grade,
                      'cosen_score' => $cosen_score,
                      'cosen_score_per' => $cosen_score_per,
                      'cosen_status_sub' => $cosen_status_sub,
                      'cosen_finishtime' => $cosen_finishtime,
                      'cosen_modifiedby' => $sess['u_id'],
                      'cosen_modifieddate' => date('Y-m-d H:i')
                    );
                    $this->db->where('cosen_id',$fetch_enroll['cosen_id']);
                    $this->db->update('lms_cos_enroll',$arr_update);
                  }
                  
            }
        }

        public function answer_data_onques_last($qiz_id,$ques_id='',$tc_answer=''){
          $user = $this->session->userdata('user');
          date_default_timezone_set("Asia/Bangkok");
          $arr_fetch = array();
          $this->db->where('lms_qiz.qiz_id',$qiz_id);
          $this->db->from('lms_qiz');
          $query_qiz = $this->db->get();
          $fetch_qiz = $query_qiz->row_array();

          $this->db->where('lms_qiz_tc.qiz_id',$qiz_id);
          $this->db->where('lms_qiz_tc.emp_id',$user['emp_id']);
          $this->db->from('lms_qiz_tc');
          $query_qiz_tc = $this->db->get();
          $fetch_qiz_tc = $query_qiz_tc->row_array();
          if(isset($fetch_qiz_tc['qiz_status'])&&$fetch_qiz_tc['qiz_status']=="2"){
            $data_tc = array(
              'time_start' => date('Y-m-d H:i'),
              'time_mod' => date('Y-m-d H:i'),
              'qiz_status' => '1'
            );
            $this->db->where('lms_qiz_tc.qiz_id',$qiz_id);
            $this->db->where('lms_qiz_tc.emp_id',$user['emp_id']);
            $this->db->update('lms_qiz_tc',$data_tc);
          }
          if($ques_id!=''){
            $this->db->from('lms_ques_tc');
            $this->db->where('lms_ques_tc.qiz_id',$qiz_id);
            $this->db->where('lms_ques_tc.ques_id',$ques_id);
            $this->db->where('lms_ques_tc.emp_id',$user['emp_id']);
            $query_row = $this->db->get();
            $fetch_row = $query_row->row_array();
            $tc_score = 0;
              $this->db->from('lms_ques');
              $this->db->join('lms_ques_mul','lms_ques.ques_id = lms_ques_mul.ques_id');
              $this->db->where('lms_ques.qiz_id', $qiz_id);
              $this->db->where('lms_ques.ques_id',$ques_id);
              $query_chk = $this->db->get();
              $fetch_chk = $query_chk->row_array();
              if(isset($fetch_chk['mul_answer'])){
                $arr_answer = explode(",",$fetch_chk['mul_answer']);
                if(in_array($tc_answer, $arr_answer)){
                  $tc_score = floatval($fetch_chk['ques_score']);
                }
              }
            $data = array(
              'qiz_id' => $qiz_id,
              'ques_id' => $ques_id,
              'emp_id' => $user['emp_id'],
              'tc_answer' => $tc_answer,
              'tc_score' => $tc_score,
              'tc_save' => '0'
            );
            if($data['tc_answer']!=""){
              $data['tc_save'] = '1';
            }
            if(countArray($fetch_row)>0){
              $fetch_qiz = $this->rechk_qiztc($qiz_id);
              if($fetch_qiz['qiz_status']!="3"){
                $this->db->where('tc_id', $fetch_row['tc_id']);
                $this->db->update('lms_ques_tc', $data);
              }
            }else{


              $this->db->from('lms_qiz');
              $this->db->where('qiz_id',$qiz_id);
              $query_qiz = $this->db->get();
              $fetch_qiz =$query_qiz->row_array();
              $this->db->select('lms_log_enroll.id_log');
              $this->db->from('lms_cos_enroll');
              $this->db->join('lms_log_enroll','lms_cos_enroll.cosen_id=lms_log_enroll.cosen_id');
              $this->db->where('emp_id',$user['emp_id']);
              $this->db->where('cos_id',$fetch_qiz['cos_id']);
              $query_chk = $this->db->get();
              $fetch_chk = $query_chk->row_array();
              $data['id_log'] = $fetch_chk['id_log'];

              $data['tc_finish'] = date('Y-m-d H:i');
              $data['tc_flag'] = '0';
              $data['tc_save'] = '0';
              $this->db->insert('lms_ques_tc', $data);
            }
          }
        }
        public function query_data_onques($qiz_id,$ques_id_future='',$ques_id='',$tc_answer=''){
          $user = $this->session->userdata('user');
          $arr_fetch = array();
          $this->answer_data_onques_last($qiz_id,$ques_id,$tc_answer);

          $this->db->from('lms_qiz');
          $this->db->where('lms_qiz.qiz_id', $qiz_id);
          $query_qiz = $this->db->get();
          $fetch_qiz = $query_qiz->row_array();
          $quiz_numofshown = intval($fetch_qiz['quiz_numofshown']);
          if(isset($fetch_qiz['quiz_random'])&&$fetch_qiz['quiz_random']=="0"){
            $this->db->from('lms_ques_tc');
            $this->db->where('lms_ques_tc.qiz_id',$qiz_id);
            if($ques_id_future!=""){
              $this->db->where('lms_ques_tc.ques_id',$ques_id_future);
            }
            $this->db->where('lms_ques_tc.emp_id',$user['emp_id']);
            $this->db->order_by('lms_ques_tc.tc_id','ASC');
            $query_rechk = $this->db->get();
            $fetch_rechk = $query_rechk->result_array();
            if(countArray($fetch_rechk)==0){
              $this->db->from('lms_ques');
              $this->db->where('lms_ques.qiz_id',$qiz_id);
              $this->db->where('lms_ques.ques_show','1');
              $this->db->where('lms_ques.ques_status', '1');
              if($quiz_numofshown>0){
                $this->db->limit($quiz_numofshown);
              }
              $query_int = $this->db->get();
              $fetch_int = $query_int->result_array();
              $num = 1;
              foreach ($fetch_int as $key_int => $value_int) {
                if($num==1){
                  $ques_id_first = $value_int['ques_id'];
                }
                $data = array(
                  'qiz_id' => $qiz_id,
                  'ques_id' => $value_int['ques_id'],
                  'emp_id' => $user['emp_id'],
                  'tc_number' => $num
                );
                $this->db->from('lms_qiz');
                $this->db->where('qiz_id',$qiz_id);
                $query_qiz = $this->db->get();
                $fetch_qiz =$query_qiz->row_array();
                $this->db->select('lms_log_enroll.id_log');
                $this->db->from('lms_cos_enroll');
                $this->db->join('lms_log_enroll','lms_cos_enroll.cosen_id=lms_log_enroll.cosen_id');
                $this->db->where('emp_id',$user['emp_id']);
                $this->db->where('cos_id',$fetch_qiz['cos_id']);
                $query_chk = $this->db->get();
                $fetch_chk = $query_chk->row_array();
                $data['id_log'] = $fetch_chk['id_log'];
                $this->db->insert('lms_ques_tc', $data);
                $num++;
              }
              $this->db->from('lms_ques');
              $this->db->where('lms_ques.qiz_id',$qiz_id);
              $this->db->where('lms_ques.ques_id',$ques_id_first);
              $this->db->where('lms_ques.ques_show','1');
              $this->db->where('lms_ques.ques_status', '1');
              $query = $this->db->get();
              $fetch = $query->result_array();
            }else{
              $num = 1;
              foreach ($fetch_rechk as $key_int => $value_int) {
                if($num==1){
                  $ques_id_first = $value_int['ques_id'];
                }
                $num++;
              }
              $this->db->from('lms_ques');
              $this->db->where('lms_ques.qiz_id',$qiz_id);
              $this->db->where('lms_ques.ques_id',$ques_id_first);
              $this->db->where('lms_ques.ques_show','1');
              $this->db->where('lms_ques.ques_status', '1');
              $query = $this->db->get();
              $fetch = $query->result_array();
            }
            /*$this->db->from('lms_ques');
            $this->db->where('lms_ques.qiz_id',$qiz_id);
            $this->db->where('lms_ques.ques_show','1');
            $this->db->where('lms_ques.ques_status', '1');
            if($ques_id_future!=""){
              $this->db->where('lms_ques.ques_id',$ques_id_future);
            }
            $this->db->order_by('lms_ques.ques_id','ASC');
            $query = $this->db->get();
            $fetch = $query->result_array();*/
          }else{
            $this->db->from('lms_ques_tc');
            $this->db->where('lms_ques_tc.qiz_id',$qiz_id);
            if($ques_id_future!=""){
              $this->db->where('lms_ques_tc.ques_id',$ques_id_future);
            }
            $this->db->where('lms_ques_tc.emp_id',$user['emp_id']);
            $this->db->order_by('lms_ques_tc.tc_id','ASC');
            $query_rechk = $this->db->get();
            $fetch_rechk = $query_rechk->result_array();
            if(countArray($fetch_rechk)==0){
              $this->db->from('lms_ques');
              $this->db->where('lms_ques.qiz_id',$qiz_id);
              $this->db->where('lms_ques.ques_show','1');
              $this->db->where('lms_ques.ques_status', '1');
              $this->db->order_by('lms_ques.ques_id','RANDOM');
              if($quiz_numofshown>0){
                $this->db->limit($quiz_numofshown);
              }
              $query_int = $this->db->get();
              $fetch_int = $query_int->result_array();
              $num = 1;
              foreach ($fetch_int as $key_int => $value_int) {
                if($num==1){
                  $ques_id_first = $value_int['ques_id'];
                }
                $data = array(
                  'qiz_id' => $qiz_id,
                  'ques_id' => $value_int['ques_id'],
                  'emp_id' => $user['emp_id'],
                  'tc_number' => $num
                );
                $this->db->from('lms_qiz');
                $this->db->where('qiz_id',$qiz_id);
                $query_qiz = $this->db->get();
                $fetch_qiz =$query_qiz->row_array();
                $this->db->select('lms_log_enroll.id_log');
                $this->db->from('lms_cos_enroll');
                $this->db->join('lms_log_enroll','lms_cos_enroll.cosen_id=lms_log_enroll.cosen_id');
                $this->db->where('emp_id',$user['emp_id']);
                $this->db->where('cos_id',$fetch_qiz['cos_id']);
                $query_chk = $this->db->get();
                $fetch_chk = $query_chk->row_array();
                $data['id_log'] = $fetch_chk['id_log'];
                $this->db->insert('lms_ques_tc', $data);
                $num++;
              }
              $this->db->from('lms_ques');
              $this->db->where('lms_ques.qiz_id',$qiz_id);
              $this->db->where('lms_ques.ques_id',$ques_id_first);
              $this->db->where('lms_ques.ques_show','1');
              $this->db->where('lms_ques.ques_status', '1');
              $query = $this->db->get();
              $fetch = $query->result_array();
            }else{
              $num = 1;
              foreach ($fetch_rechk as $key_int => $value_int) {
                if($num==1){
                  $ques_id_first = $value_int['ques_id'];
                }
                $num++;
              }
              $this->db->from('lms_ques');
              $this->db->where('lms_ques.qiz_id',$qiz_id);
              $this->db->where('lms_ques.ques_id',$ques_id_first);
              $this->db->where('lms_ques.ques_show','1');
              $this->db->where('lms_ques.ques_status', '1');
              $query = $this->db->get();
              $fetch = $query->result_array();
            }
          }
          $num=1;

          $this->db->from('lms_ques_tc');
          $this->db->where('lms_ques_tc.qiz_id',$qiz_id);
          if($ques_id_future!=""){
            $this->db->where('lms_ques_tc.ques_id',$ques_id_future);
          }
          $this->db->where('lms_ques_tc.emp_id',$user['emp_id']);
          $query_row = $this->db->get();
          $fetch_row = $query_row->row_array();
          
          if(countArray($fetch_row)>0){
            foreach ($fetch as $key => $value) {
              $this->db->from('lms_ques_tc');
              $this->db->where('lms_ques_tc.qiz_id',$qiz_id);
              $this->db->where('lms_ques_tc.ques_id',$value['ques_id']);
              $this->db->where('lms_ques_tc.emp_id',$user['emp_id']);
              $query_row = $this->db->get();
              $fetch_row = $query_row->row_array();
              if(isset($fetch_row['tc_answer'])){
                if($num==1){
                  if($fetch[$key]['ques_type']=="multi"){
                    $this->db->from('lms_ques_mul');
                    $this->db->where('lms_ques_mul.ques_id',$fetch[$key]['ques_id']);
                    $query_ques = $this->db->get();
                    $fetch_ques = $query_ques->row_array();
                    $fetch[$key]['type_multi'] = $fetch_ques;
                  }else{
                    $fetch[$key]['type_multi'] = "";
                  }
                  $fetch[$key]['answer'] = $fetch_row['tc_answer'];
                  $fetch[$key]['tc_note'] = $fetch_row['tc_note'];
                  array_push($arr_fetch, $fetch[$key]);
                }
                $num++;
              }
            }
          }else{
            foreach ($fetch as $key => $value) {
                if($num==1){
                  if($fetch[$key]['ques_type']=="multi"){
                    $this->db->from('lms_ques_mul');
                    $this->db->where('lms_ques_mul.ques_id',$fetch[$key]['ques_id']);
                    $query_ques = $this->db->get();
                    $fetch_ques = $query_ques->row_array();
                    $fetch[$key]['type_multi'] = $fetch_ques;
                  }else{
                    $fetch[$key]['type_multi'] = "";
                  }
                  array_push($arr_fetch, $fetch[$key]);
                }
                $num++;
            }
          }
            return $arr_fetch;
        }
        public function query_data_onquestc($qiz_id){
          $user = $this->session->userdata('user');
          $this->db->from('lms_ques_tc');
          $this->db->where('lms_ques_tc.qiz_id',$qiz_id);
          $this->db->where('lms_ques_tc.emp_id',$user['emp_id']);
          $query = $this->db->get();
          $fetch = $query->result_array();
        }


        public function num_data($tablename,$field_id,$id){
          $this->db->from($tablename);
          $this->db->where($field_id,$id);
          $query = $this->db->get();
          return $query->num_rows();
        }

        public function fetch_lesson_document($user,$les_id,$status='') {
          $thaimonth = array("","มกราคม","กุมภาพันธ์","มีนาคม","เมษายน","พฤษภาคม","มิถุนายน","กรกฎาคม","สิงหาคม","กันยายน","ตุลาคม","พฤศจิกายน","ธันวาคม");
          $lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
          $this->db->from('lms_fil');
          $this->db->where('lms_fil.lessons_id',$les_id);
          $query = $this->db->get();
          $fetch = $query->result_array();
          $num = 1;$count = 0;
          $fetch_arr = array();
          foreach ($fetch as $key => $value) {
              $output = array();
              $output['0'] = "<span style='float:right;'>".$num."</span>";$num++;
              if($status=='100'){
                $output['1'] = $value['path_file'];
                if(strpos($value['path_file'], '.xlsx') !== false||strpos($value['path_file'], '.xls') !== false){
                  $output['2'] = '';
                }else{
                  $output['2'] = '<a name="view_document" id="'.base_url().'/uploads/document/'.$value['path_file'].'" class="view_document btn btn-outline-info" title="view_document"><i class="mdi mdi-magnify"></i></a>';
                }
                $output['3'] = '<button type="button" name="delete_document" id="'.$value['id'].'" class="btn btn-danger btn-xs delete_document" title="'.label('sdelete').'"><i class="mdi mdi-window-close"></i></button>';
              }else{
                $output['1'] = $value['path_file'];
                if(strpos($value['path_file'], '.xlsx') !== false||strpos($value['path_file'], '.xls') !== false){
                  $output['2'] = '';
                }else{
                  $output['2'] = '<a name="view_document" id="'.base_url().'/uploads/document/'.$value['path_file'].'" class="view_document btn btn-outline-info" title="view_document"><i class="mdi mdi-magnify"></i></a>';
                }
                $output['3'] = '<a href="'.base_url().'/uploads/document/'.$value['path_file'].'" name="download_doc" id="'.$value['id'].'" class="download_doc" title="download_doc" download>'.label('download_this').'</a>';
                $this->db->from('lms_fil_log');
                $this->db->where('lms_fil_log.fil_id',$value['id']);
                $query_count = $this->db->get();
                $fetch_count = $query_count->result_array();
                $output['4'] = countArray($fetch_count).":".$status;
              }
              $count++;
              array_push($fetch_arr, $output);
          }
          return $fetch_arr;
        }

        public function fetch_cos_document($user,$cos_id,$status='') {
          $thaimonth = array("","มกราคม","กุมภาพันธ์","มีนาคม","เมษายน","พฤษภาคม","มิถุนายน","กรกฎาคม","สิงหาคม","กันยายน","ตุลาคม","พฤศจิกายน","ธันวาคม");
          $lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
          $this->db->from('lms_cos_fil');
          $this->db->where('lms_cos_fil.cos_id',$cos_id);
          $query = $this->db->get();
          $fetch = $query->result_array();
          $num = 1;$count = 0;
          $fetch_arr = array();
          foreach ($fetch as $key => $value) {
              $output = array();
              $output['0'] = "<span style='float:right;'>".$num."</span>";$num++;
              if($lang=="thai"){
                $output['1'] = $value['name_fileth'];
              }else{
                $output['1'] = $value['name_fileen'];
              }
              $output['2'] = '<button type="button" name="edit_document_cos" id="'.$value['fil_cos_id'].'" class="btn btn-warning btn-xs edit_document_cos" title="'.label('sedit').'"><i class="mdi mdi-lead-pencil"></i></button><button type="button" name="delete_document_cos" id="'.$value['fil_cos_id'].'" class="btn btn-danger btn-xs delete_document_cos" title="'.label('sdelete').'"><i class="mdi mdi-window-close"></i></button>';
              $count++;
              array_push($fetch_arr, $output);
          }
          return $fetch_arr;
        }

        public function fetch_lesson_media($user,$les_id) {
          $thaimonth = array("","มกราคม","กุมภาพันธ์","มีนาคม","เมษายน","พฤษภาคม","มิถุนายน","กรกฎาคม","สิงหาคม","กันยายน","ตุลาคม","พฤศจิกายน","ธันวาคม");
          $lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
          $this->db->from('lms_med');
          $this->db->where('lms_med.lessons_id',$les_id);
          $this->db->where('lms_med.type','upload');
          $query = $this->db->get();
          $fetch = $query->result_array();
          $num = 1;$count = 0;
          $fetch_arr = array();
          foreach ($fetch as $key => $value) {
              $output = array();
              $output['1'] = "<span style='float:right;'>".$num."</span>";$num++;
            if($lang=="thai"){ 
              $med_name = $value['med_name_th']!=""?$value['med_name_th']:$value['med_name_eng'];
              $med_name = $med_name!=""?$med_name:$value['med_name_jp'];
            }else if($lang=="english"){ 
              $med_name = $value['med_name_eng']!=""?$value['med_name_eng']:$value['med_name_th'];
              $med_name = $med_name!=""?$med_name:$value['med_name_jp'];
            }else{
              $med_name = $value['med_name_jp']!=""?$value['med_name_jp']:$value['med_name_eng'];
              $med_name = $med_name!=""?$med_name:$value['med_name_th'];
            }
              $output['2'] = $med_name;
              $output['3'] = '<a name="view_video" id="uploads/media/'.$value['video'].'" class="view_video" title="view_video">'.$value['video'].'</a>';
              $output['0'] = '<div class="lesson-media-actions"><button type="button" class="btn btn-xs edit_media" data-media-id="'.(int)$value['id'].'" title="'.label('sedit').'" aria-label="'.label('sedit').'"><i class="mdi mdi-lead-pencil" aria-hidden="true"></i></button><button type="button" name="delete_media" id="'.(int)$value['id'].'" class="btn btn-danger btn-xs delete_media" title="'.label('sdelete').'" aria-label="'.label('sdelete').'"><i class="mdi mdi-window-close" aria-hidden="true"></i></button></div>';
              $count++;
              array_push($fetch_arr, $output);
          }
          return $fetch_arr;
        }

        public function fetch_lesson_media_view($user,$les_id) {
          $lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
          $this->db->from('lms_med');
          $this->db->where('lms_med.lessons_id',$les_id);
          $this->db->where('lms_med.type','upload');
          $query = $this->db->get();
          $fetch = $query->result_array();
          $num = 1;$count = 0;
          $fetch_arr = array();
          foreach ($fetch as $key => $value) {
              $output = array();
              $output['0'] = "<span style='float:right;'>".$num."</span>";$num++;

              if($lang=="thai"){
                $med_name = $value['med_name_th']!=""?$value['med_name_th']:$value['med_name_eng'];
                $med_name = $med_name!=""?$med_name:$value['med_name_jp'];
              } elseif ($lang=="english"){
                $med_name = $value['med_name_eng']!=""?$value['med_name_eng']:$value['med_name_th'];
                $med_name = $med_name!=""?$med_name:$value['med_name_jp'];
              }else{
                $med_name = $value['med_name_jp']!=""?$value['med_name_jp']:$value['med_name_eng'];
                $med_name = $med_name!=""?$med_name:$value['med_name_th'];
              }
              $output['1'] = $med_name;
              $output['2'] = '<a name="view_video" id="uploads/media/'.$value['video'].'" class="view_video" title="view_video">'.$value['video'].'</a>';
              $count++;
              array_push($fetch_arr, $output);
          }
          return $fetch_arr;
        }

        public function fetch_videocourse($user,$cos_id) {
          $thaimonth = array("","มกราคม","กุมภาพันธ์","มีนาคม","เมษายน","พฤษภาคม","มิถุนายน","กรกฎาคม","สิงหาคม","กันยายน","ตุลาคม","พฤศจิกายน","ธันวาคม");
          $lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
          $this->db->from('lms_cos_video');
          $this->db->where('lms_cos_video.cos_id',$cos_id);
          $query = $this->db->get();
          $fetch = $query->result_array();
          $num = 1;$count = 0;
          $fetch_arr = array();
          foreach ($fetch as $key => $value) {
              $output = array();
              $output['0'] = "<span style='float:right;'>".$num."</span>";$num++;
              if($lang=="thai"){
                $output['1'] = $value['cosv_th'];
              }else{
                $output['1'] = $value['cosv_en'];
              }
              $output['2'] = $value['cosv_video'];
              $output['3'] = '<center><button type="button" name="delete_videocourse" id="'.$value['cosv_id'].'" class="btn btn-danger btn-xs delete_videocourse" title="'.label('sdelete').'"><i class="mdi mdi-window-close"></i></button></center>';
              $count++;
              array_push($fetch_arr, $output);
          }
          return $fetch_arr;
        }

        public function query_data_onupdate($id, $datatable,$fieldname) {
          $this->db->from($datatable);

          if($datatable=="lms_usp"){
            $this->db->select('lms_usp.u_id, lms_emp.lang,lms_emp.emp_c,lms_emp.emp_id, lms_emp.prefix_th, lms_emp.fname_th, lms_emp.lname_th,lms_emp.fullname_th, lms_emp.prefix_en, lms_emp.fname_en, lms_emp.lname_en,lms_emp.fullname_en,lms_emp.gender,lms_emp.address_th,lms_emp.address_en,lms_emp.work_phone,lms_emp.phone,lms_emp.email,lms_emp.employ_date,lms_usp.useri, lms_usp_gp.ug_id, lms_usp_gp.ug_name_th,lms_usp_gp.ug_name_en,lms_usp_gp.ug_for,lms_usp_gp.Is_admin,lms_usp.dep_id, lms_depart.dep_name_th,lms_depart.dep_name_en,lms_company.com_id, lms_company.com_name_th,lms_company.com_name_eng,lms_emp.status, lms_emp.lang,lms_emp.is_manager,lms_usp.login ,lms_usp.last_act,lms_usp.firsttime,lms_usp.expiredate,lms_usp.img_profile,lms_position.posi_id');
            $this->db->join('lms_emp','lms_usp.emp_id = lms_emp.emp_id');
            $this->db->join('lms_depart','lms_usp.dep_id = lms_depart.dep_id');
            $this->db->join('lms_company','lms_depart.com_id = lms_company.com_id');
            $this->db->join('lms_usp_gp','lms_usp.ug_id = lms_usp_gp.ug_id');
            $this->db->join('lms_position','lms_usp.posi_id = lms_position.posi_id');
          }
          if($id==""){
            if($id!=""&&$fieldname!=""){
                $this->db->where($fieldname, $id);
            }else if($fieldname!=""){
                $this->db->where($fieldname);
            }
          }else{
            if($id!=""&&$fieldname!=""){
                $this->db->where($fieldname, $id);
            }
          }
          $query = $this->db->get();

          if($datatable=="lms_cos"){
            $fetch = $query->row_array();

            $this->db->from('lms_cos_enroll');
            $this->db->where('lms_cos_enroll.cos_id', $fetch['cos_id'] );
            $this->db->where('lms_cos_enroll.cosen_status', '1' );
            $query_count = $this->db->get();
            $ar_count = $query_count->result_array();
            $this->db->from('lms_emp');
            $this->db->join('lms_usp','lms_emp.emp_id = lms_usp.emp_id');
            $this->db->where('lms_emp.emp_id', $fetch['cos_createby'] );
            $query_emp = $this->db->get();
            $ar_emp = $query_emp->result_array();
            $fetch['emp'] = $ar_emp;
            $fetch['enroll_seat'] = countArray($ar_count);
            return $fetch;
          }else{
            return $query->row_array();
          }
        }

        public function query_data_onupdate_result($id, $datatable,$fieldname) {
          $user = $this->session->userdata('user');
          if($datatable=="lms_ques"){
            $this->db->where('qiz_id',$id);
            $this->db->from('lms_qiz');
            $query = $this->db->get();
            $fetch = $query->row_array();
            $random = "0";
            $quiz_numofshown = intval($fetch['quiz_numofshown']);
            if($fetch['quiz_random']=="1"){
              $random = "1";
            }
            if($user['emp_id']!=""){
                $this->db->where('qiz_id',$id);
                $this->db->where('emp_id',$user['emp_id']);
                $this->db->from('lms_ques_tc');
                $query_chktc = $this->db->get();
                $num_chktc = $query_chktc->num_rows();
                if($num_chktc==0){
                  $this->db->select('lms_log_enroll.id_log');
                  $this->db->from('lms_cos_enroll');
                  $this->db->join('lms_log_enroll','lms_cos_enroll.cosen_id=lms_log_enroll.cosen_id');
                  $this->db->where('lms_cos_enroll.emp_id',$user['emp_id']);
                  $this->db->where('lms_cos_enroll.cos_id',$fetch['cos_id']);
                  $query_chk = $this->db->get();
                  $fetch_chk = $query_chk->row_array();
                  $this->db->where('qiz_id',$id);
                  $this->db->where('ques_show','1');
                  $this->db->where('ques_status','1');
                  $this->db->from('lms_ques');
                  if($random=="1"){
                    $this->db->order_by('ques_id', 'RANDOM');
                  }
                  $this->db->limit($quiz_numofshown);
                  $query_ques = $this->db->get();
                  $result_ques = $query_ques->result_array();
                  $num_tc = 1;
                  foreach ($result_ques as $key_ques => $value_ques) {
                    $arr = array(
                      'qiz_id' => $id,
                      'ques_id' => $value_ques['ques_id'],
                      'emp_id' => $user['emp_id'],
                      'id_log' => $fetch_chk['id_log'],
                      'tc_number' =>$num_tc
                    );
                    $num_tc++;
                    $this->db->insert('lms_ques_tc',$arr);
                  }
                              $this->db->from('lms_qiz_tc');
                              $this->db->where('qiz_id', $id);
                              $this->db->where('emp_id', $user['emp_id']);
                              $query_check = $this->db->get();
                              $fetch_check = $query_check->result_array();
                              if(countArray($fetch_check)==0){
                                $data_insert_qiz = array(
                                  'qiz_id' => $id,
                                  'emp_id' => $user['emp_id'],
                                  'time_mod' => date('Y-m-d H:i'),
                                  'id_log' => $fetch_chk['id_log'],
                                  'qiz_status' => '2'
                                );
                                $this->db->insert('lms_qiz_tc', $data_insert_qiz);
                              }
                }
            }
          }
          $this->db->from($datatable);
          if($datatable=="lms_cos_enroll"){
            $this->db->select('lms_emp.fullname_th,lms_emp.fullname_en,lms_cos_enroll.cosen_score');
            $this->db->join('lms_emp','lms_cos_enroll.emp_id = lms_emp.emp_id');
            $this->db->where('cosen_status_sub','1');
            $this->db->where('cosen_status','1');
            if($user['Is_admin']=="1"){
              $this->db->where_not_in('cosen_score','0');
              $this->db->where_not_in('cosen_grade','F');
              $this->db->where_not_in('cosen_grade','');
              $this->db->order_by('cosen_score','DESC');
            }else{
              $this->db->where('lms_cos_enroll.emp_id',$user['emp_id']);
            }
          }else if($datatable=="lms_cosincg"){
            $this->db->join('lms_cog','lms_cosincg.cg_id = lms_cog.id');
          }else if($datatable=="lms_ques"){
            $this->db->join('lms_ques_tc','lms_ques.ques_id = lms_ques_tc.ques_id');
            $this->db->order_by('tc_number','ASC');
            $this->db->where('lms_ques_tc.emp_id',$user['emp_id']);
            $fieldname = "lms_ques.qiz_id";
          }else if($datatable=="lms_survey"){
            $this->db->where('sv_status','1');
          }
            if($id!=""&&$fieldname!=""){
                $this->db->where($fieldname, $id);
            }
          $query = $this->db->get();
          return $query->result_array();
        }

        public function delete_data_update($id,$field,$table_name,$field_status)
        {
          date_default_timezone_set("Asia/Bangkok");
          $data = array(
                  $field_status => '0'
          );
          $this->db->where($field, $id);
          $this->db->update($table_name,$data);
          return "2";
        }

        public function delete_data_updateval($id_update,$field_id,$table_name,$field,$val) {
          date_default_timezone_set("Asia/Bangkok");
          $this->load->model('Function_query_model', 'func_query', TRUE);
          $user = $this->session->userdata('user');
          if($table_name=="lms_cos_enroll"&&$val=="1"){
            $row_chk = $this->func_query->query_row('lms_cos_enroll','','','',$field_id.'="'.$id_update.'"');
                      $cosen_id = $row_chk['cosen_id'];
                      $data_log = array(
                        'cosen_id' => $cosen_id
                      );
                      $this->db->insert('lms_log_enroll', $data_log);
                      $id_log = $this->db->insert_id();

            $fetch_chk = $this->func_query->query_result('lms_qiz','','','','cos_id="'.$row_chk['cos_id'].'"  and quiz_status="1" and quiz_isDelete="0"');
                      if(countArray($fetch_chk)>0){
                        foreach ($fetch_chk as $key_chk => $value_chk) {
                          $fetch_rechk = $this->func_query->numrows('lms_qiz_tc','','','','qiz_id="'.$value_chk['qiz_id'].'" and emp_id="'.$row_chk['emp_id'].'"');
                          if($fetch_rechk==0){
                            $data_insert_qiz = array(
                              'qiz_id' => $value_chk['qiz_id'],
                              'emp_id' => $row_chk['emp_id'],
                              'time_mod' => date('Y-m-d H:i'),
                              'id_log' => $id_log
                            );
                            $this->db->insert('lms_qiz_tc', $data_insert_qiz);
                          }
                        }
                      }
            $data = array(
              $field => $val,
              'cosen_grade' => '',
              'cosen_cancelnote' => '',
              'cosen_isDelete' => '0',
              'cosen_modifiedby' => $user['u_id'],
              'cosen_modifieddate' => date('Y-m-d H:i')
            );
          }else{
            $data = array(
              $field => $val
            );
          }
          $this->db->where($field_id, $id_update);
          $this->db->update($table_name, $data);
          return "2";
        }

        public function delete_data($id,$field,$table_name)
        {
          $lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
          $this->load->model('Log_model', 'lg', FALSE);
          $sess = $this->session->userdata("user");
          date_default_timezone_set("Asia/Bangkok");
          if($table_name=="lms_med"){
            $fetch_med = $this->query_data_onupdate($id,"lms_med","id");
            if (isset($fetch_med['lessons_id'])) {
              $fetch_les = $this->func_query->query_row('lms_les','lms_cos','lms_les.cos_id = lms_cos.cos_id','','lms_les.les_id="'.$fetch_med['lessons_id'].'"');
              
              $lesname = "";
              if($lang=="thai"){ 
                $lesname = $fetch_les['les_name_th']!=""?$fetch_les['les_name_th']:$fetch_les['les_name_eng'];
                $lesname = $lesname!=""?$lesname:$fetch_les['les_name_jp'];
              }else if($lang=="english"){ 
                $lesname = $fetch_les['les_name_eng']!=""?$fetch_les['les_name_eng']:$fetch_les['les_name_th'];
                $lesname = $lesname!=""?$lesname:$fetch_les['les_name_jp'];
              }else{
                $lesname = $fetch_les['les_name_jp']!=""?$fetch_les['les_name_jp']:$fetch_les['les_name_eng'];
                $lesname = $lesname!=""?$lesname:$fetch_les['les_name_th'];
              }
  
              $this->lg->record('Lesson', 'Delete Video: '.$fetch_med['video'].' of Lesson: '.$lesname.'('.$fetch_med['lessons_id'].')');
              audit_unlink(ROOT_DIR."uploads/media/".$fetch_med['video']);
            }
          }
          if($table_name=="lms_les"){
            $fetch_les = $this->func_query->query_row('lms_les','lms_cos','lms_les.cos_id = lms_cos.cos_id','','lms_les.les_id="'.$id.'"');
            
            $lesname = "";
            if($lang=="thai"){ 
              $lesname = $fetch_les['les_name_th']!=""?$fetch_les['les_name_th']:$fetch_les['les_name_eng'];
              $lesname = $lesname!=""?$lesname:$fetch_les['les_name_jp'];
            }else if($lang=="english"){ 
              $lesname = $fetch_les['les_name_eng']!=""?$fetch_les['les_name_eng']:$fetch_les['les_name_th'];
              $lesname = $lesname!=""?$lesname:$fetch_les['les_name_jp'];
            }else{
              $lesname = $fetch_les['les_name_jp']!=""?$fetch_les['les_name_jp']:$fetch_les['les_name_eng'];
              $lesname = $lesname!=""?$lesname:$fetch_les['les_name_th'];
            }
            
            if($fetch_les['les_type']=="2"){
              $path = $this->check_scorm($id);
              $newDir = ROOT_DIR."uploads/scorm/".$path;
              function emptyDir($dir) {
                  if (is_dir($dir)) {
                      $scn = scandir($dir);
                      foreach ($scn as $files) {
                          if ($files !== '.') {
                              if ($files !== '..') {
                                  if (!is_dir($dir . '/' . $files)) {
                                      audit_unlink($dir . '/' . $files);
                                  } else {
                                      emptyDir($dir . '/' . $files);
                                      rmdir($dir . '/' . $files);
                                  }
                              }
                          }
                      }
                  }
              }
              emptyDir($newDir);
              rmdir($newDir);
              $this->lg->record('Lesson', 'Delete scorm: '.$newDir.' of Lesson: '.$lesname.'('.$id.')');
              $this->course->delete_data($id,'lessons_id','lms_scm');
            }
            $this->lg->record('Lesson', 'Delete lesson: '.$lesname.'('.$id.')');
          }
          if($table_name=="lms_cos_video"){
            $fetch_med = $this->query_data_onupdate($id,"lms_cos_video","cosv_id");
            if($fetch_med['cosv_type']!="1"){
              $fetch_cos = $this->func_query->query_row('lms_cos','','','','lms_cos.cos_id="'.$fetch_med['cos_id'].'"');
              
              $cname = "";
              if($lang=="thai"){ 
                $cname = $fetch_cos['cname_th']!=""?$fetch_cos['cname_th']:$fetch_cos['cname_eng'];
                $cname = $cname!=""?$cname:$fetch_cos['cname_jp'];
              }else if($lang=="english"){ 
                $cname = $fetch_cos['cname_eng']!=""?$fetch_cos['cname_eng']:$fetch_cos['cname_th'];
                $cname = $cname!=""?$cname:$fetch_cos['cname_jp'];
              }else{
                $cname = $fetch_cos['cname_jp']!=""?$fetch_cos['cname_jp']:$fetch_cos['cname_eng'];
                $cname = $cname!=""?$cname:$fetch_cos['cname_th'];
              }

              $this->lg->record('Course', 'Delete video: '.$fetch_med['cosv_video'].' of Course: '.$cname.'('.$fetch_med['cos_id'].')');
              audit_unlink(ROOT_DIR."uploads/cosvideo/".$fetch_med['cosv_video']);
            }
          }
          if($table_name=="lms_fil"){
            $fetch_fil = $this->query_data_onupdate($id,"lms_fil","id");
            $fetch_les = $this->func_query->query_row('lms_les','lms_cos','lms_les.cos_id = lms_cos.cos_id','','lms_les.les_id="'.$fetch_fil['lessons_id'].'"');
            
            $lesname = "";
            if($lang=="thai"){ 
              $lesname = $fetch_les['les_name_th']!=""?$fetch_les['les_name_th']:$fetch_les['les_name_eng'];
              $lesname = $lesname!=""?$lesname:$fetch_les['les_name_jp'];
            }else if($lang=="english"){ 
              $lesname = $fetch_les['les_name_eng']!=""?$fetch_les['les_name_eng']:$fetch_les['les_name_th'];
              $lesname = $lesname!=""?$lesname:$fetch_les['les_name_jp'];
            }else{
              $lesname = $fetch_les['les_name_jp']!=""?$fetch_les['les_name_jp']:$fetch_les['les_name_eng'];
              $lesname = $lesname!=""?$lesname:$fetch_les['les_name_th'];
            }
            $this->lg->record('Lesson', 'Delete document: '.$fetch_fil['path_file'].' of Lesson: '.$lesname.'('.$fetch_fil['lessons_id'].')');
            audit_unlink(ROOT_DIR."uploads/document/".$fetch_fil['path_file']);
          }
          if($table_name=="lms_cos_fil"){
            $fetch_fil = $this->query_data_onupdate($id,"lms_cos_fil","fil_cos_id");
            $fetch_cos = $this->func_query->query_row('lms_cos','','','','lms_cos.cos_id="'.$fetch_fil['cos_id'].'"');
            
            $cname = "";
            if($lang=="thai"){ 
              $cname = $fetch_cos['cname_th']!=""?$fetch_cos['cname_th']:$fetch_cos['cname_eng'];
              $cname = $cname!=""?$cname:$fetch_cos['cname_jp'];
            }else if($lang=="english"){ 
              $cname = $fetch_cos['cname_eng']!=""?$fetch_cos['cname_eng']:$fetch_cos['cname_th'];
              $cname = $cname!=""?$cname:$fetch_cos['cname_jp'];
            }else{
              $cname = $fetch_cos['cname_jp']!=""?$fetch_cos['cname_jp']:$fetch_cos['cname_eng'];
              $cname = $cname!=""?$cname:$fetch_cos['cname_th'];
            }
            $this->lg->record('Course', 'Delete document: '.$fetch_fil['path_file'].' of Course: '.$cname.'('.$fetch_fil['cos_id'].')');
            audit_unlink(ROOT_DIR."uploads/document/".$fetch_fil['path_file']);
          }
          if($table_name=="lms_cos_sort"){
            $this->db->from('lms_cos_sort');
            $this->db->join('lms_cos','lms_cos_sort.cos_id = lms_cos.id');
            $this->db->where('lms_cos_sort.coss_id',$id);
            $query = $this->db->get();
            $fetch = $query->row_array();
            $com_id = $fetch['com_id'];

            $this->db->from('lms_cos_sort');
            $this->db->join('lms_cos','lms_cos_sort.cos_id = lms_cos.id');
            $this->db->where('lms_cos.com_id',$com_id);
            $this->db->where('lms_cos_sort.coss_id!=',$id);
            $this->db->order_by('lms_cos_sort.coss_num','ASC');
            $query_result = $this->db->get();
            $fetch_result = $query_result->result_array();
            if(countArray($fetch_result)>0){
              $coss_num = 1;
              foreach ($fetch_result as $key => $value) {
                $arr = array(
                  'coss_num'=>$coss_num
                );
                $this->db->where('coss_id',$value['coss_id']);
                $this->db->update('lms_cos_sort',$arr);
                $coss_num++;
              }
            }
          }
          $this->db->where($field, $id);
          $this->db->delete($table_name);
          return "2";
        }
        public function insert_data_video($data,$cos_id,$cosv_type,$cosv_video){
          date_default_timezone_set("Asia/Bangkok");
          $user = $this->session->userdata('user');
          $this->db->from('lms_cos_video');
          $this->db->where('cos_id', $cos_id);
          $this->db->where('cosv_type', $cosv_type);
          $this->db->where('cosv_video', $cosv_video);
          $query = $this->db->get();
          if($query->num_rows()==0){
            $this->db->insert('lms_cos_video', $data);
          }
        }
        public function insert_data_media_all($data,$lessons_id){
          date_default_timezone_set("Asia/Bangkok");
          $user = $this->session->userdata('user');
          $this->db->from('lms_med');
          $this->db->where('lessons_id', $lessons_id);
          $query = $this->db->get();
          if($query->num_rows()==0){
            $this->db->insert('lms_med', $data);
          }
        }
        public function insert_data_media($data,$lessons_id,$type,$video){
          date_default_timezone_set("Asia/Bangkok");
          $user = $this->session->userdata('user');
          $this->db->from('lms_med');
          $this->db->where('lessons_id', $lessons_id);
          $this->db->where('type', $type);
          $this->db->where('video', $video);
          $query = $this->db->get();
          if($query->num_rows()==0){
            $this->db->insert('lms_med', $data);
          }
        }
        public function delete_cosv($cos_id,$cosv_type,$cosv_video){
          date_default_timezone_set("Asia/Bangkok");
          $this->db->where('cos_id', $cos_id);
          $this->db->where('cosv_type', $cosv_type);
          $this->db->where('cosv_video', $cosv_video);
          $this->db->delete('lms_cos_video');
        }
        public function delete_med($lessons_id,$type,$video){
          date_default_timezone_set("Asia/Bangkok");
          $this->db->where('lessons_id', $lessons_id);
          $this->db->where('type', $type);
          $this->db->where('video', $video);
          $this->db->delete('lms_med');
        }
        public function delete_med_video($lessons_id,$type){
          date_default_timezone_set("Asia/Bangkok");
          $this->db->where('lessons_id', $lessons_id);
          $this->db->where('type', $type);
          $this->db->delete('lms_med');
        }
        public function delete_document($lessons_id){
          date_default_timezone_set("Asia/Bangkok");
          $this->db->where('lessons_id', $lessons_id);
          $this->db->delete('lms_fil');
        }

        public function insert_data_document($data){
          date_default_timezone_set("Asia/Bangkok");
          $user = $this->session->userdata('user');
          $this->db->from('lms_fil');
          $this->db->where('lessons_id', $data['lessons_id']);
          if(isset($data['id'])&&$data['id']!=""){
            $this->db->where('id', $data['id']);
          }else{
            $this->db->where('path_file', $data['path_file']);
          }
          $query = $this->db->get();
          if($query->num_rows()==0){
            $this->db->insert('lms_fil', $data);
          }else{
            $this->db->where('id', $data['id']);
            $this->db->update('lms_fil',$data);
          }
        }
        public function insert_data_document_cos($data){
          date_default_timezone_set("Asia/Bangkok");
          $user = $this->session->userdata('user');
          if(isset($data['fil_cos_id'])&&$data['fil_cos_id']!=""){
              $this->db->where('fil_cos_id', $data['fil_cos_id']);
              $this->db->update('lms_cos_fil', $data);
          }else{
            $this->db->from('lms_cos_fil');
            $this->db->where('cos_id', $data['cos_id']);
            $this->db->where('path_file', $data['path_file']);
            $query = $this->db->get();
            if($query->num_rows()==0){
              $this->db->insert('lms_cos_fil', $data);
            }
          }
        }
        public function create_lesson($data)
        {
                date_default_timezone_set("Asia/Bangkok");
                $user = $this->session->userdata('user');
                $this->db->from('lms_les');
                $this->db->where('cos_id', $data['cos_id']);
                $this->db->where('les_name_th', $data['les_name_th']);
                $this->db->where('les_name_en', $data['les_name_en']);
                $this->db->where('les_type', $data['les_type']);
                $query = $this->db->get();
                if($query->num_rows()==0){
                  $this->db->from('lms_les');
                  $this->db->where('cos_id', $data['cos_id']);
                  $this->db->order_by('les_sequences','DESC');
                  $query_chk = $this->db->get();
                  $row_chk = $query_chk->row_array();
                  if(isset($row_chk['les_sequences'])){
                    $data['les_sequences'] = intval($row_chk['les_sequences'])+1;
                  }else{
                    $data['les_sequences'] = 1;
                  }
                  $this->db->insert('lms_les', $data);
                }
                $id = $this->db->insert_id();
                return $id;
        }

        public function update_lesson($data,$les_id)
        {
            $this->db->where('les_id', $les_id);
            $this->db->update('lms_les', $data);
            return "2";
        }

        public function create_scorm_id($lessons_id)
        {
                date_default_timezone_set("Asia/Bangkok");
                $user = $this->session->userdata('user');
                $this->db->from('lms_scm');
                $this->db->where('lessons_id', $lessons_id);
                $this->db->where('path', '');
                $query = $this->db->get();
                if($query->num_rows()==0){
                  $data = array(
                    'lessons_id' => $lessons_id
                  );
                  $this->db->insert('lms_scm', $data);
                  $id = $this->db->insert_id();
                }else{
                  $fetch = $query->row_array();
                  $id = $fetch['id'];
                }
                return $id;
        }

        public function create_emptocourse($data)
        {
          $lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
          $this->load->model('Function_query_model', 'func_query', FALSE);
          date_default_timezone_set("Asia/Bangkok");
          $user = $this->session->userdata('user');
          $fetch_cos = $this->func_query->query_row('lms_cos','','','','cos_id="'.$data['cos_id'].'"');
          $fetch = $this->func_query->query_row('lms_emp','lms_usp','lms_emp.emp_id = lms_usp.emp_id','',
          'lms_usp.useri="'.$data['useri'].'" and status="1" and emp_isDelete="0" and (lms_usp.inactivedate="0000-00-00" or lms_usp.inactivedate >= "'.date('Y-m-d H:i').'")');
          if(!isset($fetch['com_id'])){
            $msg = '3835';
          }else{
            $chkcompany = "1";
            if($user['ug_id']!="1"&&$user['com_admin']=="com_associated"){
              if($fetch['com_id']!=$user['com_id']){
                $chkcompany = "0";
              }
            }
            if($chkcompany=="1"){
                  if($lang=="thai"){ 
                    $cname = $fetch_cos['cname_th']!=""?$fetch_cos['cname_th']:$fetch_cos['cname_eng'];
                    $cname = $cname!=""?$cname:$fetch_cos['cname_jp'];
                  }else if($lang=="english"){ 
                    $cname = $fetch_cos['cname_eng']!=""?$fetch_cos['cname_eng']:$fetch_cos['cname_th'];
                    $cname = $cname!=""?$cname:$fetch_cos['cname_jp'];
                  }else{
                    $cname = $fetch_cos['cname_jp']!=""?$fetch_cos['cname_jp']:$fetch_cos['cname_eng'];
                    $cname = $cname!=""?$cname:$fetch_cos['cname_th'];
                  }
                  $fetch_chkseat = $this->func_query->numrows('lms_cos_enroll','','','','cos_id="'.$data['cos_id'].'" and cosen_isDelete="0" and cosen_status="1"');
                  $numrechk_seat = 1;
                  if(intval($fetch_cos['seat_count'])>0){
                    if($fetch_chkseat>=intval($fetch_cos['seat_count'])){
                      $numrechk_seat = 0;
                    }
                  }
                  if($numrechk_seat==1){
                    $emp_id = $fetch['emp_id'];
                    $data_insert = array(
                      'cos_id' => $data['cos_id'],
                      'emp_id' => $emp_id,
                      'cosen_timerequest' => date('Y-m-d H:i'),
                      'cosen_status' => '1',
                      'cosen_status_sub' => '2',
                      'cosen_createby' => $user['u_id'],
                      'cosen_createdate' => date('Y-m-d H:i'),
                      'cosen_modifiedby' => $user['u_id'],
                      'cosen_modifieddate' => date('Y-m-d H:i')
                    );
                    $fetch_ens = $this->func_query->query_row('lms_cos_enroll','','','','emp_id="'.$data_insert['emp_id'].'" and cos_id="'.$data_insert['cos_id'].'" and cosen_isDelete="0"');
                    if(isset($fetch_ens)){
                      $msg = '1';
                    }else{
                      if($this->db->insert('lms_cos_enroll', $data_insert)){

                        $id = $this->db->insert_id();
                        $data_log = array(
                          'cosen_id' => $id
                        );
                        $this->db->insert('lms_log_enroll', $data_log);
                        $id_log = $this->db->insert_id();

                        $fetch_chk = $this->func_query->query_result('lms_qiz','','','','cos_id="'.$data['cos_id'].'" and quiz_status="1"');
                        if(countArray($fetch_chk)>0){
                          foreach ($fetch_chk as $key_chk => $value_chk) {
                              $fetch_check = $this->func_query->query_result('lms_qiz_tc','','','','qiz_id="'.$value_chk['qiz_id'].'" and emp_id="'.$emp_id.'"');
                              if(countArray($fetch_check)==0){
                                $data_insert_qiz = array(
                                  'qiz_id' => $value_chk['qiz_id'],
                                  'emp_id' => $emp_id,
                                  'time_mod' => date('Y-m-d H:i')
                                );
                                $this->db->insert('lms_qiz_tc', $data_insert_qiz);
                              }
                          }
                        }
                        $this->load->model('Log_model', 'lg', FALSE);
                        $this->lg->loadDB();
                        $this->lg->record('course', 'Enroll learner: '.$fetch['useri'].'('.$id.')  of Course: '.$cname.' ('.$data_insert['cos_id'].')');
                        $msg = '2';
                      }else{
                        $msg = '0';
                      }
                    }
                }else{
                    $msg = '4';
                }
            }else{
              $msg = '0';
            }
          }

          return $msg;
        }

        public function cal_score_ques($qiz_id){
              $user = $this->session->userdata('user');

              $this->db->from('lms_ques');
              $this->db->where('lms_ques.qiz_id', $qiz_id);
              $this->db->where('lms_ques.ques_show', '1');
              $this->db->where('lms_ques.ques_status', '1');
              $query_rechk = $this->db->get();
              $num_rechk = $query_rechk->num_rows();
              $score = 0;
              $this->db->from('lms_ques');
              $this->db->join('lms_ques_mul','lms_ques.ques_id = lms_ques_mul.ques_id');
              $this->db->where('lms_ques.qiz_id', $qiz_id);
              $query_chk = $this->db->get();
              $num_chk = $query_chk->num_rows();
                $fetch_chk = $query_chk->result_array();
                foreach ($fetch_chk as $key_chk => $value_chk) {
                    $this->db->from('lms_ques_tc');
                    $this->db->where('lms_ques_tc.ques_id', $value_chk['ques_id']);
                    $this->db->where('lms_ques_tc.emp_id', $user['emp_id']);
                    $query_chk = $this->db->get();
                    $fetch_chk = $query_chk->row_array();
                    if(isset($fetch_chk['tc_score'])){
                      $score += floatval($fetch_chk['tc_score']);
                    }
                }

              $this->db->from('lms_qiz_tc');
              $this->db->where('lms_qiz_tc.qiz_id', $qiz_id);
              $this->db->where('lms_qiz_tc.emp_id', $user['emp_id']);
              $query_score = $this->db->get();
              $fetch_score = $query_score->row_array();
              $limit_val = 0;
              if(isset($fetch_score['qiz_status'])){
                if($fetch_score['qiz_status']!='3'){
                  $limit_val = intval($fetch_score['limit_val'])+1;
                  $data_score = array(
                    'sum_score' => $score,
                    'limit_val' => $limit_val
                  );
                  $this->db->where('lms_qiz_tc.qiz_id', $qiz_id);
                  $this->db->where('lms_qiz_tc.emp_id', $user['emp_id']);
                  $this->db->update('lms_qiz_tc',$data_score);
                }else{
                  $limit_val = intval($fetch_score['limit_val']);
                }
              }
              return $limit_val;
        }


        public function update_scorm_id($scmCode,$path){
              $data = array(
                  'path' => $path
              );
              $this->db->where('id', $scmCode);
              $this->db->update('lms_scm', $data);
        }


        public function update_score_qiz($data,$id){
              $this->db->where('id', $id);
              $this->db->update('lms_qiz_tc', $data);
        }

        public function update_scoreall($cosen_score_all,$cos_id){
              date_default_timezone_set("Asia/Bangkok");
              $user = $this->session->userdata('user');
              $data = array(
                  'cosen_score' => $cosen_score_all,
                  'cosen_modifiedby' => $user['u_id'],
                  'cosen_modifieddate' => date('Y-m-d H:i')
              );
              $this->db->where('cos_id', $cos_id);
              $this->db->update('lms_cos_enroll', $data);
        }

        public function update_scoreall_qiz($qiz_score_all,$qiz_id){
              date_default_timezone_set("Asia/Bangkok");
              $user = $this->session->userdata('user');
              $data = array(
                  'sum_score' => $qiz_score_all,
                  'time_mod' => date('Y-m-d H:i'),
                  'qiz_status' => '3'
              );
              $this->db->where('qiz_id', $qiz_id);
              $this->db->update('lms_qiz_tc', $data);
        }

        public function check_media_cos($cos_id,$cosv_type)
        {
                date_default_timezone_set("Asia/Bangkok");
                $user = $this->session->userdata('user');
                $this->db->select('lms_cos_video.cosv_video');
                $this->db->from('lms_cos_video');
                $this->db->where('cos_id', $cos_id);
                $this->db->where('cosv_type', $cosv_type);
                $query = $this->db->get();
                return $query->result_array();
        }

        public function check_media($lessons_id,$type)
        {
                date_default_timezone_set("Asia/Bangkok");
                $user = $this->session->userdata('user');
                $this->db->select('lms_med.video');
                $this->db->from('lms_med');
                $this->db->where('lessons_id', $lessons_id);
                $this->db->where('type', $type);
                $query = $this->db->get();
                return $query->result_array();
        }

        public function check_scorm($lessons_id)
        {
                date_default_timezone_set("Asia/Bangkok");
                $user = $this->session->userdata('user');
                $this->db->select('lms_scm.path');
                $this->db->from('lms_scm');
                $this->db->where('lessons_id', $lessons_id);
                $query = $this->db->get();
                $num = $query->num_rows();
                if($num>0){
                  $fetch = $query->row_array();
                  return $fetch['path'];
                }else{
                  $data = array(
                    'lessons_id' => $lessons_id
                  );
                  $this->db->insert('lms_scm', $data);
                  $id = $this->db->insert_id();
                  $path = "scorm_".$lessons_id."_".$id;
                  $newDir = ROOT_DIR."uploads/scorm/".$path;
                        mkdir($newDir);
                  $data_update = array(
                    'path' => $path
                  );
                  $this->db->where('id', $id);
                  $this->db->update('lms_scm',$data_update);
                  return $path;
                }
        }

        public function check_scorm_id($lessons_id)
        {
                date_default_timezone_set("Asia/Bangkok");
                $user = $this->session->userdata('user');
                $this->db->select('lms_scm.id');
                $this->db->from('lms_scm');
                $this->db->where('lessons_id', $lessons_id);
                $query = $this->db->get();
                $fetch = $query->row_array();
                return $fetch['id'];
        }

        public function has_completed_survey($cos_id, $emp_id)
        {
            $this->load->model('Function_query_model', 'func_query', FALSE);
            // นับแบบสำรวจทั้งหมดในหลักสูตร
            $this->db->select('COUNT(*) AS total');
            $this->db->from('lms_survey');
            $this->db->where('sv_isDelete', 0);
            $this->db->where('cos_id', $cos_id);
            $total_survey = $this->db->get()->row()->total;

            // นับแบบสำรวจที่ผู้เรียนตอบแล้ว
            $this->db->select('COUNT(DISTINCT lms_qn_user.sv_id) AS completed');
            $this->db->from('lms_qn_user');
            $this->db->join('lms_survey', 'lms_survey.sv_id = lms_qn_user.sv_id');
            $this->db->where('lms_survey.sv_isDelete', 0);
            $this->db->where('lms_survey.cos_id', $cos_id);
            $this->db->where('lms_qn_user.emp_id', $emp_id);
            $completed_survey = $this->db->get()->row()->completed;

            // เปรียบเทียบว่าครบหรือยัง
            return intval($completed_survey) >= intval($total_survey);
        }

        public function get_next_required_survey($cos_id, $emp_id)
        {
            $this->load->model('Function_query_model', 'func_query', FALSE);
            $fetch_chkcos = $this->func_query->query_row('lms_cos', '', '', '', 'cos_id="' . $cos_id . '"', '', 'is_survey_required');
            $this->db->select('sv_id');
            $this->db->from('lms_survey');
            $this->db->where('cos_id', $cos_id);
            $this->db->where("sv_id NOT IN (SELECT sv_id FROM lms_qn_user WHERE emp_id = {$emp_id})", null, false);
            $this->db->order_by('sv_id', 'ASC');
            $query = $this->db->get();
            return intval($fetch_chkcos['is_survey_required']) === 1 ? $query->row_array() : array();
        }

        public function can_show_certificate($cos_id, $emp_id)
        {
            $this->load->model('Function_query_model', 'func_query', FALSE);
            $this->db->from('lms_certificate');
            $this->db->where('cos_id', $cos_id);
            $this->db->where('emp_id', $emp_id);
            $query = $this->db->get();
            return $query->num_rows() > 0;
        }
}
