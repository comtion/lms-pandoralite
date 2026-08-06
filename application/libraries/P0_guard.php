<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class P0_guard
{
	private $CI;

	public function __construct()
	{
		$this->CI =& get_instance();
		$this->CI->load->database();
	}

	public function user($ajax = TRUE)
	{
		$user = $this->CI->session->userdata('user');
		if (!is_array($user) || empty($user['u_id'])) {
			$this->deny('Authentication required', 401, $ajax);
		}
		return $user;
	}

	public function admin($ajax = TRUE)
	{
		$user = $this->user($ajax);
		$roles = array('superadmin', 'admin', 'admintis', 'admindealer', 'adminzone', 'manager');
		if (empty($user['role']) || !in_array($user['role'], $roles, TRUE)) {
			$this->deny('Permission denied', 403, $ajax);
		}
		return $user;
	}

	public function companyId(array $user)
	{
		if (!isset($user['com_id']) || !ctype_digit((string) $user['com_id'])) {
			$this->deny('Invalid company scope', 403, TRUE);
		}
		return (int) $user['com_id'];
	}

	public function requirePost()
	{
		if (strtoupper($this->CI->input->method(TRUE)) !== 'POST') {
			$this->deny('Method not allowed', 405, TRUE);
		}
	}

	public function rateLimit($bucket, $limit, $windowSeconds)
	{
		$key = hash('sha256', $bucket.'|'.$this->CI->input->ip_address());
		$now = time();
		$row = $this->CI->db->get_where('lms_rate_limits', array('rate_key' => $key))->row_array();
		if (!$row || (int) $row['window_started_at'] <= ($now - $windowSeconds)) {
			$this->CI->db->replace('lms_rate_limits', array(
				'rate_key' => $key,
				'hit_count' => 1,
				'window_started_at' => $now,
				'expires_at' => date('Y-m-d H:i:s', $now + $windowSeconds),
			));
			return;
		}
		if ((int) $row['hit_count'] >= $limit) {
			$this->deny('Too many requests', 429, TRUE);
		}
		$this->CI->db->where('rate_key', $key)->set('hit_count', 'hit_count + 1', FALSE)->update('lms_rate_limits');
	}

	public function json($payload, $status = 200)
	{
		$this->CI->output
			->set_status_header($status)
			->set_content_type('application/json', 'utf-8')
			->set_output(json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
	}

	private function deny($message, $status, $ajax)
	{
		if ($ajax) {
			$this->json(array('ok' => FALSE, 'message' => $message), $status);
			$this->CI->output->_display();
			exit;
		}
		show_error($message, $status);
	}
}
