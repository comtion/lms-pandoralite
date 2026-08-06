<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Enrollmentworkflow extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->library('p0_guard');
		$this->load->model('Enrollment_workflow_model', 'workflow');
		$this->load->model('Notification_model', 'notifications');
	}

	public function request($courseId)
	{
		$this->p0_guard->requirePost();
		$user = $this->p0_guard->user();
		$this->p0_guard->rateLimit('enrollment-request', 20, 60);
		$empId = $this->employeeId($user);
		$result = $this->workflow->request((int) $courseId, $this->p0_guard->companyId($user), $empId, $empId, 'self');
		$this->p0_guard->json($result, $result['ok'] ? 200 : 422);
	}

	public function pending()
	{
		$user = $this->p0_guard->admin();
		$this->p0_guard->json(array('ok' => TRUE, 'items' => $this->workflow->pending($this->p0_guard->companyId($user))));
	}

	public function decide($requestId)
	{
		$this->p0_guard->requirePost();
		$user = $this->p0_guard->admin();
		$decision = strtolower((string) $this->input->post('decision', TRUE));
		if (!in_array($decision, array('approve', 'reject'), TRUE)) {
			$this->p0_guard->json(array('ok' => FALSE, 'message' => 'Invalid decision'), 422);
			return;
		}
		$reason = trim((string) $this->input->post('reason', TRUE));
		if ($decision === 'reject' && $reason === '') {
			$this->p0_guard->json(array('ok' => FALSE, 'message' => 'Reason is required'), 422);
			return;
		}
		$result = $this->workflow->decide((int) $requestId, $this->p0_guard->companyId($user), $decision, $user['u_id'], $reason);
		if (!empty($result['ok'])) {
			$request = $this->db->get_where('lms_enrollment_requests', array(
				'request_id' => (int) $requestId,
				'com_id' => $this->p0_guard->companyId($user),
			))->row_array();
			if ($request) {
				$this->notifications->create(
					$this->p0_guard->companyId($user),
					$request['emp_id'],
					'enrollment',
					'Enrollment '.$result['status'],
					$reason,
					'course/detail/'.$request['cos_id'],
					array('type' => 'course', 'id' => $request['cos_id'])
				);
			}
		}
		$this->p0_guard->json($result, $result['ok'] ? 200 : 422);
	}

	public function bulk_request($courseId)
	{
		$this->p0_guard->requirePost();
		$user = $this->p0_guard->admin();
		$employees = $this->input->post('employees');
		if (is_string($employees)) $employees = preg_split('/[\s,;]+/', $employees, -1, PREG_SPLIT_NO_EMPTY);
		if (!is_array($employees) || count($employees) > 500) {
			$this->p0_guard->json(array('ok' => FALSE, 'message' => 'Employees must contain 1-500 identifiers'), 422);
			return;
		}
		$results = array('approved' => 0, 'pending' => 0, 'waitlisted' => 0, 'failed' => array());
		foreach (array_values(array_unique($employees)) as $empId) {
			$empId = trim((string) $empId);
			if ($empId === '' || strlen($empId) > 64) continue;
			$result = $this->workflow->request((int) $courseId, $this->p0_guard->companyId($user), $empId, $user['u_id'], 'assigned');
			if (!empty($result['ok'])) {
				$key = isset($results[$result['status']]) ? $result['status'] : 'pending';
				$results[$key]++;
			} else {
				$results['failed'][] = array('emp_id' => $empId, 'message' => $result['message']);
			}
		}
		$this->p0_guard->json(array('ok' => TRUE, 'results' => $results));
	}

	public function policy($courseId)
	{
		$user = $this->p0_guard->admin();
		$comId = $this->p0_guard->companyId($user);
		if ($this->input->method(TRUE) === 'GET') {
			$this->p0_guard->json(array('ok' => TRUE, 'policy' => $this->workflow->policy((int) $courseId, $comId)));
			return;
		}
		$mode = strtolower((string) $this->input->post('enrollment_mode', TRUE));
		if (!in_array($mode, array('open', 'approval', 'assigned', 'closed'), TRUE)) $mode = 'approval';
		$ok = $this->workflow->savePolicy((int) $courseId, $comId, array(
			'enrollment_mode' => $mode,
			'capacity' => max(0, (int) $this->input->post('capacity')) ?: NULL,
			'waitlist_enabled' => $this->input->post('waitlist_enabled') ? 1 : 0,
			'starts_at' => $this->dateTime($this->input->post('starts_at')),
			'expires_at' => $this->dateTime($this->input->post('expires_at')),
			'allow_reenroll' => $this->input->post('allow_reenroll') ? 1 : 0,
		), $user['u_id']);
		$this->p0_guard->json(array('ok' => (bool) $ok), $ok ? 200 : 404);
	}

	private function employeeId(array $user)
	{
		return (string) ($user['emp_c'] ?? $user['emp_id'] ?? $user['u_id']);
	}

	private function dateTime($value)
	{
		if (!$value) return NULL;
		$date = DateTime::createFromFormat('Y-m-d H:i:s', $value) ?: DateTime::createFromFormat('Y-m-d\TH:i', $value);
		return $date ? $date->format('Y-m-d H:i:s') : NULL;
	}
}
