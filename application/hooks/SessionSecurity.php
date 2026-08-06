<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class SessionSecurity
{
	public function enforce()
	{
		$CI =& get_instance();
		$user = $CI->session->userdata('user');
		if (!is_array($user) || empty($user)) {
			return;
		}
		$now = time();
		$idleLimit = max(300, (int) (getenv('LMS_SESSION_IDLE_SECONDS') ?: 7200));
		$absoluteLimit = max($idleLimit, (int) (getenv('LMS_SESSION_ABSOLUTE_SECONDS') ?: 43200));
		$last = (int) $CI->session->userdata('p0_last_activity');
		$started = (int) $CI->session->userdata('p0_session_started');
		if (!$started) {
			$CI->session->set_userdata('p0_session_started', $now);
			$started = $now;
		}
		if (($last && ($now - $last) > $idleLimit) || (($now - $started) > $absoluteLimit)) {
			$CI->session->sess_destroy();
			if ($CI->input->is_ajax_request()) {
				$CI->output->set_status_header(401)->set_content_type('application/json')
					->set_output(json_encode(array('ok' => FALSE, 'message' => 'Session expired')));
				$CI->output->_display();
				exit;
			}
			redirect(base_url().'dashboard/login?expired=1');
		}
		$CI->session->set_userdata('p0_last_activity', $now);
	}
}
