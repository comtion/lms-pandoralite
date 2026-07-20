<?php
class Dashboard_model extends CI_Model {

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

        public function log_usersys($type_device){
            $user = $this->session->userdata('user');
            $this->db->select('lms_lg.id');
            $this->db->from('lms_lg');
            // $this->db->join('lms_emp','lms_lg.emp_id = lms_emp.emp_id');
            if(in_array($user['ug_id'], array('2','6'))){
              $this->db->where('lms_lg.emp_id in (select lms_emp.emp_id from lms_emp where lms_emp.com_id = '.$user['com_id'].') ');
            }
            $this->db->like('lms_lg.device',$type_device);
            $query = $this->db->get();
            return $query->num_rows();
        }

        public function course_select(){
            $user = $this->session->userdata('user');
            $this->db->select('cos_id,ccode,cname_th,cname_eng');
            $this->db->from('lms_cos');
            $this->db->where('lms_cos.com_id',$user['com_id']);
            $this->db->where('lms_cos.cos_status','1');
            $query = $this->db->get();
            return $query->result_array();
        }

        public function courseCourses($emp_c, $lang) {
          $this->db->select('lms_plv.course_id');
          $this->db->distinct();
          $this->db->from('lms_plv');
          $this->db->join('lms_emp', 'lms_plv.org_c = lms_emp.main_pos');
          $this->db->where('lms_emp.emp_c', $emp_c);
          $query = $this->db->get();
          return $query->result_array();
        }

        public function getCourses($emp_c, $lang) {
          $this->db->select('lms_cos.ccode, lms_cos.cname, lms_cos.cdesc, lms_cos.coursetype, lms_cos.approve_pp, lms_cos.time_open, lms_cos.time_end
            , lms_ens.enroll_status1, lms_ens.enroll_status2');
          $this->db->distinct();
          $this->db->from('lms_cos');
          $this->db->join('lms_ens', 'lms_ens.course_id = lms_cos.ccode');
          $this->db->where('lms_ens.emp_c', $emp_c);
          $this->db->where('lang', $lang);
          $this->db->where('lms_cos.cos_status', 1);
          $this->db->where('lms_ens.del_type is null');
          $this->db->order_by('lms_ens.time_request', 'DESC');
          $query = $this->db->get();
          return $query->result_array();
        }

        public function divideStatus($emp_c, $course, $role) {//$lesFlag, $qizFlag, $role) {
          date_default_timezone_set("Asia/Bangkok");
          /*$l = $lesFlag == 'true'?TRUE:FALSE;
          $q = $qizFlag == 'true'?TRUE:FALSE;*/

          $this->db->select('lms_cos.time_end, lms_ens.finish_time, lms_ens.first_time, lms_qiz_tc.sum_score');
          $this->db->distinct();
          $this->db->from('lms_cos');
          $this->db->join('lms_ens', 'lms_ens.course_id = lms_cos.ccode');
          $this->db->join('lms_qiz', 'lms_qiz.courses_id = lms_ens.course_id', 'left');
          $this->db->join('lms_qiz_tc', 'lms_qiz_tc.quiz_id = lms_qiz.qcode', 'left');
          $this->db->where('lms_cos.ccode', $course);
          $this->db->where('lms_ens.emp_c', $emp_c);
          $query = $this->db->get();
          $result = $query->row_array();

          if(is_null($result['first_time'])) {
            $status = 'Not Started';
          } elseif(is_null($result['finish_time']) && $result['time_end'] > strtotime(date('Y-m-d H:i'))) {
            $status = 'In Progress';
          } elseif(!is_null($result['finish_time'])) {
            $status = 'Completed';
          } else {
            $status = 'In Progress';
          }

          /*if(in_array($role, array("superadmin", "admin"))) {
            if(strtotime($course['time_open']) > strtotime(date('Y-m-d H:i'))) $status = 'Not Started';
            elseif($l && $q) $status = 'Completed';
            //elseif(strtotime($course['time_end']) < strtotime(date('Y-m-d H:i'))) $status = 'Completed';
            else $status = 'In Progress';
          } else {
            $level = $this->getLevel($emp_c);

            if($level == '1' && $course['enroll_status1'] == 'no') $status = 'Not Started';
            elseif($level == '2' && ($course['enroll_status1'] == 'no'
              || $course['enroll_status2'] == 'no')) $status = 'Not Started';
            elseif(strtotime($course['time_open']) > strtotime(date('Y-m-d H:i'))) $status = 'Not Started';
            elseif($l && $q) $status = 'Completed';
            //elseif(strtotime($course['time_end']) < strtotime(date('Y-m-d H:i'))) $status = 'Completed';
            else $status = 'In Progress';
          }*/

          return $status;
        }

        public function countCourses($course, $counter) {
          if($course['status'] == 'Completed') $counter['coc']++;
          elseif($course['status'] == 'In Progress') $counter['coip']++;
          elseif($course['status'] == 'Not Started') $counter['cons']++;

          return $counter;
        }

        public function getAnnoucements($emp_c, $emps, $lang) {
          //get all emp_c
          $emps_c = [];
          foreach($emps as $emp) {
            $emps_c[] = $emp['emp_c'];
          }
          //get level-1 emps
          $this->db->select('lms_emp.emp_c, lms_emp.prefix, lms_emp.fname
            , lms_emp.lname, lms_cos.ccode, lms_cos.cname, lms_ens.time_request');
          $this->db->from('lms_ens');
          $this->db->join('lms_emp', 'lms_emp.emp_c = lms_ens.emp_c');
          $this->db->join('lms_cos', 'lms_cos.ccode = lms_ens.course_id');
          $this->db->where('lms_cos.lang', $lang);
          $this->db->where('lms_emp.lang', $lang);
          $this->db->where('lms_emp.level', 1);
          $this->db->where('lms_ens.enroll_status1 !=', 'yes');
          $this->db->where('lms_cos.cos_status', 1);

          $this->db->where_in('lms_ens.emp_c', $emps_c);

          $this->db->order_by('lms_ens.time_request', 'ASC');
          $this->db->limit(2);
          $query = $this->db->get();
          $l1emps = $query->result_array();
          //get level-2 emps
          $this->db->select('lms_emp.emp_c, lms_emp.prefix, lms_emp.fname
            , lms_emp.lname, lms_cos.ccode, lms_cos.cname, lms_ens.time_request');
          $this->db->from('lms_ens');
          $this->db->join('lms_emp', 'lms_emp.emp_c = lms_ens.emp_c');
          $this->db->join('lms_cos', 'lms_cos.ccode = lms_ens.course_id');
          $this->db->where('lms_cos.lang', $lang);
          $this->db->where('lms_emp.lang', $lang);
          $this->db->where('lms_emp.level', 2);
          $this->db->where('lms_emp.lead', $emp_c);
          $this->db->where('lms_ens.enroll_status1 !=', 'yes');
          $this->db->where('lms_cos.cos_status', 1);

          $this->db->where_in('lms_ens.emp_c', $emps_c);

          $this->db->order_by('lms_ens.time_request', 'ASC');
          $this->db->limit(2);
          $query = $this->db->get();
          $l2emps = $query->result_array();
          //get level-2 emps for high-level lead
          $this->db->select('lms_emp.emp_c, lms_emp.prefix, lms_emp.fname
            , lms_emp.lname, lms_cos.ccode, lms_cos.cname, lms_ens.time_request');
          $this->db->from('lms_ens');
          $this->db->join('lms_emp', 'lms_emp.emp_c = lms_ens.emp_c');
          $this->db->join('lms_cos', 'lms_cos.ccode = lms_ens.course_id');
          $this->db->where('lms_cos.lang', $lang);
          $this->db->where('lms_emp.lang', $lang);
          $this->db->where('lms_emp.level', 2);
          $this->db->where('lms_emp.lead !=', $emp_c);
          $this->db->where('lms_ens.enroll_status1', 'yes');
          $this->db->where('lms_ens.enroll_status2 !=', 'yes');
          $this->db->where('lms_cos.cos_status', 1);

          $this->db->where_in('lms_ens.emp_c', $emps_c);

          $this->db->order_by('lms_ens.time_request', 'ASC');
          $this->db->limit(2);
          $query = $this->db->get();
          $l2emps2 = $query->result_array();
          //array concat
          $alemps = [];
          foreach($l1emps as $emp) {
            $alemps[] = $emp;
          }
          foreach($l2emps as $emp) {
            $alemps[] = $emp;
          }
          foreach($l2emps2 as $emp) {
            $alemps[] = $emp;
          }
          return $alemps;
        }

        public function getBadges($emp_c) {
          $this->db->select('lms_bad.id , lms_bad.badges_name, lms_bad.badges_img, lms_bad.courses_id, lms_cos.cname, lms_ens.finish_time,lms_emp.prefix,lms_emp.fname,lms_emp.lname,lms_org2.name');
          $this->db->distinct();
          $this->db->from('lms_uhb_tc');
          $this->db->join('lms_bad', 'lms_bad.id = lms_uhb_tc.badges_id');
          $this->db->join('lms_cos', 'lms_cos.ccode = lms_bad.courses_id');
          $this->db->join('lms_ens', 'lms_ens.course_id = lms_cos.ccode');
          $this->db->join('lms_emp', 'lms_emp.emp_c = lms_ens.emp_c');
          $this->db->join('lms_org2', 'lms_org2.code = lms_emp.org2');
          $this->db->where('lms_uhb_tc.emp_c', $emp_c);
          $this->db->where('lms_ens.emp_c', $emp_c);
          $this->db->where('lms_ens.finish_time is not null');
          $this->db->order_by('lms_ens.finish_time', 'DESC');
          $query = $this->db->get();
          return $query->result_array();
        }

        public function getCourse($emp_c, $lang) {
          date_default_timezone_set("Asia/Bangkok");
          $this->db->select('lms_cos.ccode, lms_cos.cname, lms_cos.time_open, lms_cos.time_end');
          $this->db->distinct();
          $this->db->from('lms_cos');
          $this->db->join('lms_ens', 'lms_ens.course_id = lms_cos.ccode');
          $this->db->where('lms_ens.emp_c', $emp_c);
          $this->db->where('lang', $lang);
          $this->db->where('lms_cos.time_open <=', date('Y-m-d H:i'));
          $this->db->where('lms_cos.time_end >=', date('Y-m-d H:i'));
          $this->db->where('lms_cos.cos_status', 1);
          $this->db->order_by('lms_ens.time_request', 'DESC');
          $query = $this->db->get();
          return $query->row_array();
        }


        public function fetch_grade($user,$grade='') {
          date_default_timezone_set("Asia/Bangkok");
          $thaimonth = array("","มกราคม","กุมภาพันธ์","มีนาคม","เมษายน","พฤษภาคม","มิถุนายน","กรกฎาคม","สิงหาคม","กันยายน","ตุลาคม","พฤศจิกายน","ธันวาคม");
          $lang = $this->session->userdata("lang") == null ? "thai" : $this->session->userdata("lang");
          $this->db->from('lms_cos_enroll');
          $this->db->join('lms_cos','lms_cos_enroll.cos_id = lms_cos.id');
          $this->db->where('lms_cos_enroll.emp_id',$user['emp_id']);
          $this->db->where('lms_cos_enroll.cosen_grade',$grade);
          $query = $this->db->get();
          $fetch = $query->result_array();
          $num = 1;$count = 0;
          $fetch_arr = array();
          foreach ($fetch as $key => $value) {
              $output = array();
              $output['0'] = $num;$num++;
              if($lang=="thai"){
                $output['1'] = $value['cname_th'];
              }else{
                $output['1'] = $value['cname_eng'];
              }
              $output['2'] = $value['cosen_grade'];
              $output['3'] = $value['cosen_score'];
              if($value['cosen_finishtime']!="0000-00-00 00:00:00"){
                $output['4'] = date('d/m/Y H:i',strtotime($value['cosen_finishtime']));
              }else{
                $output['4'] = '-';
              }
              $count++;
              array_push($fetch_arr, $output);
          }
          return $fetch_arr;
        }

        public function getEvents($emp_c, $lang) {
          date_default_timezone_set("Asia/Bangkok");
          $this->db->select('lms_cos.ccode, lms_cos.cname, lms_cos.time_open');
          $this->db->distinct();
          $this->db->from('lms_cos');
          $this->db->join('lms_ens', 'lms_ens.course_id = lms_cos.ccode');
          $this->db->where('lms_ens.emp_c', $emp_c);
          $this->db->where('lang', $lang);
          $this->db->where('time_open >', date('Y-m-d H:i'));
          $this->db->where('lms_cos.cos_status', 1);
          $this->db->order_by('lms_cos.time_open', 'ASC');
          $this->db->limit(3);
          $query = $this->db->get();
          return $query->result_array();
        }

        public function getOnlineUsers() {
          $this->db->select('useri');
          $this->db->where('st_on', 'online');
          $this->db->order_by('last_act', 'DESC');
          $query = $this->db->get('lms_usp');

          return $query->result_array();
        }

        public function getEnrollStatus($course_id, $emp_c) {
          $this->db->select('lms_ens.course_id, lms_ens.emp_c, lms_ens.enroll_status1, lms_ens.enroll_status2, lms_emp.lead, lms_emp.level');
          $this->db->from('lms_ens');
          $this->db->join('lms_emp', 'lms_emp.emp_c = lms_ens.emp_c');
          $this->db->where('lms_ens.course_id', $course_id);
          $this->db->where('lms_ens.emp_c', $emp_c);
          $query = $this->db->get();
          return $query->row_array();
        }

        public function approveEmp($emp_c, $status) {
          /*if($status['enroll_status1'] == 'no') {
            $data = array(
              'approver_id1' => $emp_c,
              'enroll_status1' => 'yes'
            );
          } else {
            $data = array(
              'approver_id2' => $emp_c,
              'enroll_status2' => 'yes'
            );
          }

          $this->db->group_start();
          $this->db->or_where('lms_ens.approver_id1', NULL);
          $this->db->or_where('lms_ens.approver_id1 !=', $emp_c);
          $this->db->group_end();
          $this->db->group_start();
          $this->db->or_where('lms_ens.approver_id2', NULL);
          $this->db->or_where('lms_ens.approver_id2 !=', $emp_c);
          $this->db->group_end();

          $this->db->where('course_id', $status['course_id']);
          $this->db->where('emp_c', $status['emp_c']);
          $this->db->update('lms_ens', $data);*/

          if($status['level'] == 1) {
            $data = array(
              'approver_id1' => $emp_c,
              'enroll_status1' => 'yes'
            );
          } else {
            if($status['lead'] == $emp_c) {
              $data = array(
                'approver_id1' => $emp_c,
                'enroll_status1' => 'yes'
              );
            } else {
              $data = array(
                'approver_id2' => $emp_c,
                'enroll_status2' => 'yes'
              );
            }
          }

          $this->db->where('course_id', $status['course_id']);
          $this->db->where('emp_c', $status['emp_c']);
          $this->db->update('lms_ens', $data);
        }

        public function rejectEmp($course_id, $emp_c) {
          $this->db->where('course_id', $course_id);
          $this->db->where('emp_c', $emp_c);
          $this->db->delete('lms_ens');
        }

        //for admin
        public function getAllCourses($lang ,$wcode = "") {
          //echo 'Ohm : '.$wcode;
          $this->db->select('ccode, cname, cdesc, coursetype, time_open, time_end');
          $this->db->distinct();
          if( $wcode != "" ){
            $this->db->where('wcode', $wcode);
          }
          $this->db->where('lang', $lang);
          $this->db->where('status', 1);
          $this->db->order_by('time_create', 'DESC');
          $query = $this->db->get('lms_cos');
          return $query->result_array();
        }

        public function getRecentCourse($lang) {
          date_default_timezone_set("Asia/Bangkok");
          $this->db->select('ccode, cname, time_open, time_end');
          $this->db->distinct();
          $this->db->where('lang', $lang);
          $this->db->where('time_open <=', date('Y-m-d H:i'));
          $this->db->where('time_end >=', date('Y-m-d H:i'));
          $this->db->where('status', 1);
          $this->db->order_by('time_open', 'ASC');
          $this->db->order_by('time_end', 'ASC');
          $query = $this->db->get('lms_cos');
          return $query->row_array();
        }

        public function getAllEvents($lang) {
          date_default_timezone_set("Asia/Bangkok");
          $this->db->select('ccode, cname, time_open');
          $this->db->distinct();
          $this->db->where('lang', $lang);
          $this->db->where('time_open >', date('Y-m-d H:i'));
          $this->db->where('status', 1);
          $this->db->order_by('time_open', 'ASC');
          $query = $this->db->get('lms_cos', 3);
          return $query->result_array();
        }

        private function getLevel($emp_c) {
          $this->db->select('level');
          $this->db->where('emp_c', $emp_c);
          $query = $this->db->get('lms_emp');
          $row = $query->row_array();
          return $row['level'];
        }

        public function getDetail($employee, $lang) {
          $this->db->select('emp_c, prefix, fname, lname, email ');
          $this->db->where('emp_c', $employee);
          $this->db->where('lang', $lang);
          $query = $this->db->get('lms_emp');
      		return $query->row_array();
        }

        public function getCos($ccode, $lang) {
          $this->db->select('ccode, cname');
          $this->db->from('lms_cos');
          $this->db->where('ccode', $ccode);
          $this->db->where('lang', $lang);
          $query = $this->db->get();
          return $query->row_array();
        }

        public function getL2Head($employee, $lang) {
          $this->db->select('lead');
          $this->db->from('lms_emp');
          $this->db->where('emp_c', $employee);
          $this->db->where('lang', $lang);
          $query = $this->db->get();
          $row = $query->row_array();
          $head = $row['lead'];

          $this->db->select('lead');
          $this->db->from('lms_emp');
          $this->db->where('emp_c', $head);
          $this->db->where('lang', $lang);
          $query = $this->db->get();
          $row = $query->row_array();
          $head2 = $row['lead'];

          $this->db->select('emp_c, prefix, fname, lname, email, lead');
          $this->db->from('lms_emp');
          $this->db->where('emp_c', $head2);
          $this->db->where('lang', $lang);
          $query = $this->db->get();
          return $query->row_array();
        }
}
