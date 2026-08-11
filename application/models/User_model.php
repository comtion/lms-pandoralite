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
      $user = $this->session->userdata('user');
      $verified = $this->session->userdata('auth_verified') === true;
      $hasIdentity = is_array($user)
        && !empty($user['u_id'])
        && !empty($user['emp_id'])
        && !empty($user['useri']);

      if (!$verified || !$hasIdentity) {
        $this->session->unset_userdata(array('user', 'auth_verified'));
        redirect(base_url() . 'home?redirect=' . rawurlencode((string) $dest), 'location', 302);
        exit;
      }

      return true;
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
      if ($this->loginRateLimited($username)) {
        log_message('error', 'Login rate limit exceeded for IP ' . $this->input->ip_address());
        return false;
      }
      $this->db->from('lms_usp');
      $this->db->join('lms_emp', 'lms_usp.emp_id = lms_emp.emp_id');
      //$this->db->join('lms_depart','lms_usp.dep_id = lms_depart.dep_id');
      $this->db->join('lms_company', 'lms_emp.com_id = lms_company.com_id');
      $this->db->join('lms_usp_gp', 'lms_usp.ug_id = lms_usp_gp.ug_id');
      //$this->db->join('lms_position','lms_usp.posi_id = lms_position.posi_id');
      $this->db->where('lms_usp.useri', $username);
      $this->db->where('lms_emp.status', '1');
      $this->db->where('lms_emp.emp_isDelete', '0');
      $this->db->where('lms_usp.u_isDelete', '0');
      $query = $this->db->get();

      if ($query->num_rows() > 0) {
        $result = $query->row_array();
        $storedPassword = isset($result['userp']) ? (string) $result['userp'] : '';
        $isModernHash = preg_match('/^\$(2[ayb]|argon2i|argon2id)\$/', $storedPassword) === 1;
        $passwordValid = $isModernHash
          ? password_verify((string) $password, $storedPassword)
          : hash_equals($storedPassword, (string) $password);
        if (!$passwordValid) {
          $this->recordFailedLogin($username);
          return false;
        }
        $this->clearLoginRateLimit($username);
        if (!$isModernHash || password_needs_rehash($storedPassword, PASSWORD_DEFAULT)) {
          $this->db->where('u_id', $result['u_id'])->update('lms_usp', array(
            'userp' => password_hash((string) $password, PASSWORD_DEFAULT)
          ));
        }

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
          unset($session_data['userp']);
          $lang_last = $result['lang_last'] != "" ? $result['lang_last'] : "english";
          $lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");

          if ($result['emp_firsttime'] == "1" || $result['firsttime'] == 1) {
            $lang_last = $lang;
          }

          $this->session->set_userdata('user', $session_data);
          $this->session->sess_regenerate(TRUE);
          $this->session->set_userdata('lang', $lang_last);
          $this->changeLogs($session_data['useri']);
          if ($session_data['lang'] == "thai") {
            $name = $session_data['fullname_th'];
          } else {
            $name = $session_data['fullname_en'];
          }

          $this->session->set_userdata('name', $name);
          $this->load->helper('cookie');
          setcookie("emp_id", $session_data["emp_id"], array(
            'expires' => 0,
            'path' => '/',
            'secure' => filter_var(getenv('LMS_COOKIE_SECURE') ?: false, FILTER_VALIDATE_BOOLEAN),
            'httponly' => true,
            'samesite' => 'Lax'
          ));

          return true;
        } else {
          return false;
        }
      } else {
        return false;
      }
    }

    /**
     * Decide the complete login outcome in one place.
     * The password supplied here is always plaintext.
     */
    public function authenticate($username, $password)
    {
      $username = strtolower(trim((string) $username));
      $password = (string) $password;

      // A fresh login attempt must earn a new authenticated session.  Never
      // allow a stale user payload to survive an invalid password attempt.
      $this->session->unset_userdata(array('user', 'auth_verified'));

      if ($username === '' || $password === '') {
        return array('status' => 'invalid_credentials');
      }
      if ($this->loginRateLimited($username)) {
        return array('status' => 'rate_limited', 'retry_after' => 900);
      }

      $this->db->from('lms_usp');
      $this->db->join('lms_emp', 'lms_usp.emp_id = lms_emp.emp_id');
      $this->db->join('lms_company', 'lms_emp.com_id = lms_company.com_id');
      $this->db->join('lms_usp_gp', 'lms_usp.ug_id = lms_usp_gp.ug_id');
      $this->db->where('lms_usp.useri', $username);
      $this->db->where('lms_usp.u_isDelete', '0');
      $this->db->where('lms_emp.emp_isDelete', '0');
      $query = $this->db->get();
      if ($query->num_rows() !== 1) {
        // Do comparable work and record the attempt without revealing the account.
        password_verify($password, '$2y$10$7EqJtq98hPqEX7fNZaFWoO5u5G.jRZVnZBVQpQ2vVxKQ9L3e1FQnK');
        $this->recordFailedLogin($username);
        return array('status' => 'invalid_credentials');
      }

      $user = $query->row_array();
      if (!$this->passwordMatches($password, $user['userp'])) {
        $this->recordFailedLogin($username);
        if ($this->loginRateLimited($username)) {
          $this->lock($username);
          return array('status' => 'account_locked');
        }
        return array('status' => 'invalid_credentials');
      }

      $this->clearLoginRateLimit($username);
      if ((string) $user['login'] !== '1') {
        return array('status' => 'account_locked');
      }
      if ((string) $user['status'] !== '1' || (string) $user['u_status'] !== '1' || $this->dateHasPassed($user['inactivedate'])) {
        return array('status' => 'inactive');
      }

      // Transparently upgrade old SHA-256 records after successful verification.
      if (!$this->isModernPasswordHash($user['userp']) || password_needs_rehash($user['userp'], PASSWORD_DEFAULT)) {
        $newHash = password_hash($password, PASSWORD_DEFAULT);
        $this->db->where('u_id', $user['u_id'])->update('lms_usp', array('userp' => $newHash));
        $user['userp'] = $newHash;
      }

      if ((int) $user['firsttime'] === 1) {
        $this->preparePasswordChange($username, 'first_login');
        return array('status' => 'first_login');
      }
      if ($this->dateHasPassed($user['expiredate'])) {
        $this->preparePasswordChange($username, 'password_expired');
        return array('status' => 'password_expired');
      }

      $this->startUserSession($user);
      return array('status' => 'complete');
    }

    private function isModernPasswordHash($hash)
    {
      return preg_match('/^\$(2[ayb]|argon2i|argon2id)\$/', (string) $hash) === 1;
    }

    private function passwordMatches($password, $storedHash)
    {
      $storedHash = (string) $storedHash;
      if ($this->isModernPasswordHash($storedHash)) {
        return password_verify((string) $password, $storedHash);
      }
      return hash_equals($storedHash, hash('sha256', (string) $password));
    }

    private function dateHasPassed($value)
    {
      if (!$value || $value === '0000-00-00' || $value === '0000-00-00 00:00:00') return false;
      $timestamp = strtotime($value);
      return $timestamp !== false && $timestamp <= time();
    }

    private function preparePasswordChange($username, $reason)
    {
      $this->session->sess_regenerate(TRUE);
      $this->session->unset_userdata(array('user', 'auth_verified'));
      $this->session->set_userdata(array(
        'username_firsttime' => $username,
        'firsttime' => $reason === 'first_login',
        'passexpire' => $reason === 'password_expired',
        'password_change_reason' => $reason,
        'password_change_issued_at' => time()
      ));
    }

    private function startUserSession($user)
    {
      unset($user['userp']);
      $lang = !empty($user['lang_last']) ? $user['lang_last'] : 'english';
      $this->session->sess_regenerate(TRUE);
      $this->session->set_userdata(array(
        'user' => $user,
        'auth_verified' => true,
        'lang' => $lang,
		'p0_session_started' => time(),
		'p0_last_activity' => time(),
        'firsttime' => false,
        'passexpire' => false,
        'username_firsttime' => '',
        'password_change_reason' => null,
        'name' => $lang === 'thai' ? $user['fullname_th'] : $user['fullname_en']
      ));
	  $this->session->set_flashdata('login_success', array(
		'name' => $lang === 'thai' ? $user['fullname_th'] : $user['fullname_en']
	  ));
      $this->changeLogs($user['useri']);
      setcookie('emp_id', $user['emp_id'], array(
        'expires' => 0,
        'path' => '/',
        'secure' => filter_var(getenv('LMS_COOKIE_SECURE') ?: false, FILTER_VALIDATE_BOOLEAN),
        'httponly' => true,
        'samesite' => 'Lax'
      ));
    }

    private function loginRateKey($username)
    {
      return hash('sha256', 'login|' . strtolower(trim((string) $username)) . '|' . $this->input->ip_address());
    }

    private function loginRateLimited($username)
    {
      if (!$this->db->table_exists('lms_rate_limits')) {
        return false;
      }
      $key = $this->loginRateKey($username);
      $row = $this->db->get_where('lms_rate_limits', array('rate_key' => $key))->row_array();
      if (!$row) return false;
      if (strtotime($row['expires_at']) <= time()) {
        $this->db->delete('lms_rate_limits', array('rate_key' => $key));
        return false;
      }
      return (int) $row['hit_count'] >= 5;
    }

    private function recordFailedLogin($username)
    {
      if (!$this->db->table_exists('lms_rate_limits')) return;
      $key = $this->loginRateKey($username);
      $row = $this->db->get_where('lms_rate_limits', array('rate_key' => $key))->row_array();
      if (!$row || strtotime($row['expires_at']) <= time()) {
        $this->db->replace('lms_rate_limits', array(
          'rate_key' => $key,
          'hit_count' => 1,
          'window_started_at' => time(),
          'expires_at' => date('Y-m-d H:i:s', time() + 900)
        ));
        return;
      }
      $this->db->where('rate_key', $key)->set('hit_count', 'hit_count + 1', false)->update('lms_rate_limits');
    }

    private function clearLoginRateLimit($username)
    {
      if ($this->db->table_exists('lms_rate_limits')) {
        $this->db->delete('lms_rate_limits', array('rate_key' => $this->loginRateKey($username)));
      }
    }

    public function createPasswordResetToken($username)
    {
      if (!$this->db->table_exists('lms_password_reset_tokens')) return null;
      $this->db->select('lms_usp.u_id, lms_usp.useri, lms_emp.email, lms_emp.fullname_th, lms_emp.fullname_en');
      $this->db->from('lms_usp');
      $this->db->join('lms_emp', 'lms_emp.emp_id = lms_usp.emp_id');
      $this->db->where('lms_usp.useri', strtolower(trim((string) $username)));
      $this->db->where('lms_usp.u_isDelete', '0');
      $this->db->where('lms_emp.emp_isDelete', '0');
      $user = $this->db->get()->row_array();
      if (!$user || !filter_var($user['email'], FILTER_VALIDATE_EMAIL)) return null;

      $token = bin2hex(random_bytes(32));
      $now = date('Y-m-d H:i:s');
      $this->db->where('u_id', $user['u_id'])->where('used_at IS NULL', null, false)
        ->update('lms_password_reset_tokens', array('used_at' => $now));
      $this->db->insert('lms_password_reset_tokens', array(
        'u_id' => $user['u_id'],
        'token_hash' => hash('sha256', $token),
        'requested_ip_hash' => hash('sha256', $this->input->ip_address()),
        'expires_at' => date('Y-m-d H:i:s', time() + 1800),
        'used_at' => null,
        'created_at' => $now
      ));
      if (!$this->db->affected_rows()) return null;
      return array('token' => $token, 'user' => $user);
    }

    public function passwordResetRequestAllowed()
    {
      if (!$this->db->table_exists('lms_rate_limits')) return true;
      $key = hash('sha256', 'password-reset|' . $this->input->ip_address());
      $row = $this->db->get_where('lms_rate_limits', array('rate_key' => $key))->row_array();
      $now = time();
      if (!$row || strtotime($row['expires_at']) <= $now) {
        $this->db->replace('lms_rate_limits', array(
          'rate_key' => $key,
          'hit_count' => 1,
          'window_started_at' => $now,
          'expires_at' => date('Y-m-d H:i:s', $now + 3600)
        ));
        return true;
      }
      if ((int) $row['hit_count'] >= 3) return false;
      $this->db->where('rate_key', $key)->set('hit_count', 'hit_count + 1', false)->update('lms_rate_limits');
      return true;
    }

    public function findPasswordResetToken($token)
    {
      if (!$this->db->table_exists('lms_password_reset_tokens') || !preg_match('/^[a-f0-9]{64}$/', (string) $token)) return null;
      $this->db->select('lms_password_reset_tokens.reset_id, lms_password_reset_tokens.u_id, lms_usp.useri');
      $this->db->from('lms_password_reset_tokens');
      $this->db->join('lms_usp', 'lms_usp.u_id = lms_password_reset_tokens.u_id');
      $this->db->where('token_hash', hash('sha256', $token));
      $this->db->where('used_at IS NULL', null, false);
      $this->db->where('expires_at >', date('Y-m-d H:i:s'));
      $this->db->where('lms_usp.u_isDelete', '0');
      return $this->db->get()->row_array();
    }

    public function consumePasswordResetToken($token, $newPassword)
    {
      $record = $this->findPasswordResetToken($token);
      if (!$record) return false;
      $now = date('Y-m-d H:i:s');
      $this->db->trans_start();
      $this->db->where('reset_id', $record['reset_id'])->where('used_at IS NULL', null, false)
        ->update('lms_password_reset_tokens', array('used_at' => $now));
      if ($this->db->affected_rows() !== 1) {
        $this->db->trans_rollback();
        return false;
      }
      $hash = password_hash((string) $newPassword, PASSWORD_DEFAULT);
      $this->db->where('u_id', $record['u_id'])->update('lms_usp', array(
        'userp' => $hash,
        'login' => 1,
        'firsttime' => 0,
        'u_lockdate' => '0000-00-00 00:00:00',
        'expiredate' => date('Y-m-d H:i:s', strtotime('+90 days'))
      ));
      $this->db->insert('lms_log_password', array('u_id' => $record['u_id'], 'lp_datetime' => $now, 'lp_password' => $hash));
      $this->db->trans_complete();
      $this->clearLoginRateLimit($record['useri']);
      return $this->db->trans_status();
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
