<?php
class User_model extends CI_Model
  {

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

    public function checkSession($dest)
    {
      if ($this->session->userdata("user") == null) {
        redirect(base_url() . "dashboard/login?redirect=" . $dest);
      } else {
        return true;
      }
    }

    public function sendLogin($dest)
    {
      $lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
      $this->lang->load($lang, $lang);

      $arr['lang'] = $lang;
      $arr['page'] = 'login';
      $arr['dest'] = $dest;

      $this->load->model('Footer_model', 'foot', FALSE);
      $this->foot->loadDB();
      $arr['foote'] = $this->foot->getfooter();
      $this->foot->closeDB();
      $this->load->view('frontend/login', $arr);
    }


    public function getEmp($emp_c, $lang)
    {
      $this->db->distinct();
      $this->db->from('lms_usp');
      $this->db->where('lms_usp.useri', $emp_c);
      $this->db->where('lms_usp.u_isDelete', '0');
      $query = $this->db->get();
      $result = $query->row_array();
      if (count($result) > 0) {
        return $result;
      }
    }

    public function sendRedirect($dest)
    {
      $lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
      $this->lang->load($lang, $lang);

      $arr['lang'] = $lang;
      $arr['page'] = $dest;
      $path = base_url() . $dest;
      redirect($path);
    }

    public function setFirstTime($username)
    {
      date_default_timezone_set("Asia/Bangkok");
      $date = date('Y-m-d H:i');
      $date = new DateTime($date);
      $date->modify('+90 day');
      $dateexpire = date_format($date, 'Y-m-d H:i');
      $data = array('login' => 1, 'firsttime' => 1, 'expiredate' => $dateexpire);
      $this->db->where('useri', $username);
      $this->db->where('lms_usp.u_isDelete', '0');
      $this->db->update('lms_usp', $data);
      return true;
    }

    public function updatePass($username, $password_enc, $password, $firsttime = 0)
    {
      $this->db->where('useri', $username);
      $this->db->where('u_isDelete', '0');
      $query = $this->db->get('lms_usp');
      //echo $query->num_rows();
      if ($query->num_rows() > 0) {
        $result = $query->row_array();
        if ($result['userp'] == $password_enc) {
          return false;
        } else {
          date_default_timezone_set("Asia/Bangkok");
          $date = date('Y-m-d H:i');
          $date = new DateTime($date);
          $date->modify('+90 day');
          $dateexpire = date_format($date, 'Y-m-d H:i');
          $data = array('userp' => $password_enc, 'login' => 1, 'firsttime' => $firsttime, 'expiredate' => $dateexpire);
          $this->db->where('useri', $username);
          $this->db->where('lms_usp.u_isDelete', '0');
          $this->db->update('lms_usp', $data);
          if ($firsttime == 0) {
            $arr_logpass = array(
              'u_id' =>  $result['u_id'],
              'lp_datetime' => date('Y-m-d H:i'),
              'lp_password' => $password_enc
            );
            $this->db->insert('lms_log_password', $arr_logpass);
          }
          return true;
        }
      } else {
        return false;
      }
    }

    public function rechk_login($username, $password)
    {
      $this->db->from('lms_usp');
      $this->db->join('lms_emp', 'lms_usp.emp_id = lms_emp.emp_id');
      //$this->db->join('lms_depart','lms_usp.dep_id = lms_depart.dep_id');
      $this->db->join('lms_company', 'lms_emp.com_id = lms_company.com_id');
      $this->db->join('lms_usp_gp', 'lms_usp.ug_id = lms_usp_gp.ug_id');
      //$this->db->join('lms_position','lms_usp.posi_id = lms_position.posi_id');
      $this->db->where('lms_usp.useri', $username);
      $this->db->where('lms_usp.userp', $password);
      $this->db->where('lms_emp.status', '1');
      $this->db->where('lms_usp.login', '1');
      $this->db->where('lms_emp.emp_isDelete', '0');
      $this->db->where('lms_usp.u_isDelete', '0');
      $query = $this->db->get();
      if ($query->num_rows() > 0) {
        return true;
      } else {
        return false;
      }
    }
    public function checkfirsttime($username, $password)
    {
      $password = hash('sha256', $password);
      $this->db->from('lms_usp');
      $this->db->where('lms_usp.useri', $username);
      $this->db->where('lms_usp.userp', $password);
      $this->db->where('lms_usp.firsttime', '1');
      $this->db->where('lms_usp.u_isDelete', '0');
      $query = $this->db->get();
      if ($query->num_rows() > 0) {
        return true;
      } else {
        return false;
      }
    }
    public function checkconfirm_status($username, $password)
    {
      $password = hash('sha256', $password);
      $this->db->from('lms_usp');
      $this->db->where('lms_usp.useri', $username);
      $this->db->where('lms_usp.userp', $password);
      $this->db->where('lms_usp.confirm_status', '1');
      $this->db->where('lms_usp.u_isDelete', '0');
      $query = $this->db->get();
      if ($query->num_rows() > 0) {
        return true;
      } else {
        return false;
      }
    }

    public function checkLogin($username, $password)
    { //checklogin
      $this->db->from('lms_usp');
      $this->db->join('lms_emp', 'lms_usp.emp_id = lms_emp.emp_id');
      //$this->db->join('lms_depart','lms_usp.dep_id = lms_depart.dep_id');
      $this->db->join('lms_company', 'lms_emp.com_id = lms_company.com_id');
      $this->db->join('lms_usp_gp', 'lms_usp.ug_id = lms_usp_gp.ug_id');
      //$this->db->join('lms_position','lms_usp.posi_id = lms_position.posi_id');
      $this->db->where('lms_usp.useri', $username);
      $this->db->where('lms_usp.userp', $password);
      $this->db->where('lms_emp.status', '1');
      $this->db->where('lms_emp.emp_isDelete', '0');
      $this->db->where('lms_usp.u_isDelete', '0');
      $query = $this->db->get();

      if ($query->num_rows() > 0) {
        $result = $query->row_array();

        if ($result['status'] == "1") {
          $locked = $result['login'];
          if (!$locked) {
            return false;
          }

          if ($result['firsttime'] == 1) {
            $this->session->set_userdata('username_firsttime', $username);
            $this->session->set_userdata('firsttime', true);
            redirect(base_url() . 'dashboard/firsttime');
          } else {
            $this->session->set_userdata('username_firsttime', '');
            $this->session->set_userdata('firsttime', false);
          }
          date_default_timezone_set("Asia/Bangkok");
          $this->session->set_userdata('passexpire', false);

          $session_data = $result;
          $lang_last = $result['lang_last'] != "" ? $result['lang_last'] : "english";
          $lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");

          if ($result['emp_firsttime'] == "1" || $result['firsttime'] == 1) {
            $lang_last = $lang;
          }

          $this->session->set_userdata('user', $session_data);
          $this->session->set_userdata('lang', $lang_last);
          $this->changeLogs($session_data['useri']);
          if ($session_data['lang'] == "thai") {
            $name = $session_data['fullname_th'];
          } else {
            $name = $session_data['fullname_en'];
          }

          $this->session->set_userdata('name', $name);
          $this->load->helper('cookie');
          setcookie("emp_id", $session_data["emp_id"]);

          return true;
        } else {
          return false;
        }
      } else {
        return false;
      }
    }

    public function getUser($username)
    { //checklogin
      date_default_timezone_set("Asia/Bangkok");
      $this->db->from('lms_usp');
      $this->db->join('lms_emp', 'lms_usp.emp_id = lms_emp.emp_id');
      $this->db->where('lms_usp.useri', $username);
      $this->db->where('lms_emp.status', '1');
      $this->db->where('lms_emp.emp_isDelete', '0');
      $this->db->where('lms_usp.u_isDelete', '0');
      $where = '(lms_usp.inactivedate="0000-00-00" or lms_usp.inactivedate > "' . date('Y-m-d') . '" )';
      $this->db->where($where);
      $query = $this->db->get();

      if ($query->num_rows() > 0) {
        $result = $query->row_array();
        return $result;
      }
    }

    public function logout($code)
    {
      $data = array(
        'st_on' => 'offline'
      );
      $this->update($data, $code);
    }

    public function changeLogs($code)
    {
      $data = array(
        'st_on' => 'online'
      );
      $this->update($data, $code);
    }

    private function update($data, $code)
    {
      $this->db->set('last_act', 'NOW()', FALSE);
      // $this->db->set('login', 'true', FALSE);
      $this->db->where('useri', $code);
      $this->db->update('lms_usp', $data);
    }


    public function lockUser($username)
    {
      $this->db->from('lms_usp');
      $this->db->join('lms_emp', 'lms_emp.emp_id = lms_usp.emp_id');
      $this->db->where('useri', $username);
      $query = $this->db->get();

      if ($query->num_rows() > 0) {
        $this->lock($username);
        return true;
      }
      return false;
    }

    public function lock($username)
    {
      date_default_timezone_set("Asia/Bangkok");
      $this->db->set('login', "0", FALSE);
      $this->db->set('u_lockdate', date('Y-m-d H:i:s'));
      $this->db->where('useri', $username);
      $this->db->where('lms_usp.u_isDelete', '0');
      $this->db->update('lms_usp');
    }

    public function isLocked($username)
    {
      $this->db->select("login");
      $this->db->from('lms_usp');
      $this->db->where('useri', $username);
      $this->db->where('login', '0');
      $this->db->where('lms_usp.u_isDelete', '0');
      $query = $this->db->get();
      return ($query->num_rows() > 0);
    }

    public function getLockedAcc()
    {
      $this->db->select('lms_emp.fullname_th, lms_emp.fullname_en, lms_usp.emp_id, lms_usp.useri, lms_emp.emp_c,lms_usp.u_lockdate,lms_emp.com_id');
      $this->db->from('lms_usp');
      $this->db->join('lms_emp', 'lms_emp.emp_id = lms_usp.emp_id');
      $this->db->join('lms_depart', 'lms_usp.dep_id = lms_depart.dep_id', 'left');
      $this->db->where('lms_usp.login', '0');
      $user = $this->session->userdata('user');
      if ($user['ug_id'] != "1") {
        $this->db->where('lms_depart.com_id', $user['com_id']);
      }
      $this->db->order_by('lms_emp.emp_c', 'ASC');
      $query = $this->db->get();
      return $query->result_array();
    }

    public function fetch_data_unlockacc()
    {
      $lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
      $this->load->model('Manage_model', 'manage', FALSE);
      $this->manage->loadDB();
      $page = "dashboard/unlockAcc";
      $arr_permission = $this->manage->chk_permission_page();
      $btn_add = $this->manage->chk_permission($page, 'ru_add');
      $btn_update = $this->manage->chk_permission($page, 'ru_edit');
      $btn_delete = $this->manage->chk_permission($page, 'ru_del');
      $btn_view = $this->manage->chk_permission($page, 'ru_view');

      $this->db->select('lms_emp.fullname_th, lms_emp.fullname_en, lms_usp.emp_id, lms_usp.useri, lms_emp.emp_c');
      $this->db->from('lms_usp');
      $this->db->join('lms_emp', 'lms_emp.emp_id = lms_usp.emp_id');
      $this->db->join('lms_depart', 'lms_usp.dep_id = lms_depart.dep_id', 'left');
      $this->db->where('lms_usp.login', '0');
      $this->db->where('lms_emp.emp_isDelete', '0');
      $this->db->where('lms_usp.u_isDelete', '0');
      $user = $this->session->userdata('user');
      if ($user['ug_for'] == "com_associated") {
        $this->db->where('lms_depart.com_id', $user['com_id']);
      }
      $this->db->order_by('lms_emp.emp_c', 'ASC');
      $query = $this->db->get();
      $fetch = $query->result_array();
      $num = 1;
      $count = 0;
      $fetch_arr = array();
      foreach ($fetch as $key => $value) {
        $output = array();
        $output['0'] = $num;
        $num++;
        $output['1'] = $value['emp_c'];
        $output['2'] = $value['useri'];
        if ($lang == "thai") {
          $output['3'] = $value['fullname_th'];
        } else {
          $output['3'] = $value['fullname_en'];
        }
        $output['4'] = '<form action="' . REAL_PATH . '/dashboard/unlockUser" class="form-inline" method="post"><input type="hidden" name="emp_id" value="' . $value['emp_id'] . '"><input type="hidden" name="useri" value="' . $value['useri'] . '"><button class="btn btn-default display" type="submit" ><i class="mdi mdi-lock-open"></i> ' . label('unlock') . '</button></form>';
        $count++;
        array_push($fetch_arr, $output);
      }
      return $fetch_arr;
    }

    public function unlock($user)
    {
      $sess = $this->session->userdata('user');
      date_default_timezone_set("Asia/Bangkok");
      $arr = array(
        'login' => '1',
        'u_modifiedby' => $sess['u_id'],
        'u_modifieddate' => date('Y-m-d H:i')
      );
      $this->db->where('emp_id', $user);
      $this->db->update('lms_usp', $arr);
    }
  }
  ?>
