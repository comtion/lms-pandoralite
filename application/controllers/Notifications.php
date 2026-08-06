<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Notifications extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->library('p0_guard');
		$this->load->model('Notification_model', 'notifications');
	}

	public function index()
	{
		$user = $this->p0_guard->user();
		$comId = $this->p0_guard->companyId($user);
		$empId = $this->employeeId($user);
		$this->p0_guard->json(array(
			'ok' => TRUE,
			'unread' => $this->notifications->unreadCount($comId, $empId),
			'items' => $this->notifications->latest($comId, $empId, $this->input->get('limit')),
		));
	}

	public function read($notificationId)
	{
		$this->p0_guard->requirePost();
		$user = $this->p0_guard->user();
		$ok = $this->notifications->markRead(
			(int) $notificationId,
			$this->p0_guard->companyId($user),
			$this->employeeId($user)
		);
		$this->p0_guard->json(array('ok' => (bool) $ok));
	}

	public function read_all()
	{
		$this->p0_guard->requirePost();
		$user = $this->p0_guard->user();
		$ok = $this->notifications->markAllRead(
			$this->p0_guard->companyId($user),
			$this->employeeId($user)
		);
		$this->p0_guard->json(array('ok' => (bool) $ok));
	}

	public function preferences()
	{
		$user = $this->p0_guard->user();
		$comId = $this->p0_guard->companyId($user);
		$empId = $this->employeeId($user);
		if ($this->input->method(TRUE) === 'GET') {
			$this->p0_guard->json(array('ok' => TRUE, 'preferences' => $this->notifications->preferences($comId, $empId)));
			return;
		}
		$frequency = $this->input->post('digest_frequency', TRUE);
		if (!in_array($frequency, array('immediate', 'daily', 'weekly', 'off'), TRUE)) {
			$frequency = 'immediate';
		}
		$ok = $this->notifications->savePreferences($comId, $empId, array(
			'in_app_enabled' => $this->input->post('in_app_enabled') ? 1 : 0,
			'email_enabled' => $this->input->post('email_enabled') ? 1 : 0,
			'digest_frequency' => $frequency,
			'quiet_hours_start' => $this->validTime($this->input->post('quiet_hours_start')),
			'quiet_hours_end' => $this->validTime($this->input->post('quiet_hours_end')),
		));
		$this->p0_guard->json(array('ok' => (bool) $ok));
	}

	private function employeeId(array $user)
	{
		foreach (array('emp_id', 'emp_c', 'u_id') as $key) {
			if (isset($user[$key]) && $user[$key] !== '') {
				return (string) $user[$key];
			}
		}
		$this->p0_guard->json(array('ok' => FALSE, 'message' => 'Invalid user identity'), 403);
		exit;
	}

	private function validTime($value)
	{
		return preg_match('/^(?:[01]\d|2[0-3]):[0-5]\d(?::[0-5]\d)?$/', (string) $value) ? $value : NULL;
	}
}
