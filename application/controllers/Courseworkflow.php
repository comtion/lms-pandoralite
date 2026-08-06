<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Courseworkflow extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->library('p0_guard');
		$this->load->model('Course_lifecycle_model', 'lifecycle');
		$this->load->model('Notification_model', 'notifications');
	}

	public function status($courseId)
	{
		$user = $this->p0_guard->admin();
		$comId = $this->p0_guard->companyId($user);
		$item = $this->lifecycle->get((int) $courseId, $comId);
		if (!$item) {
			$this->p0_guard->json(array('ok' => FALSE, 'message' => 'Course not found'), 404);
			return;
		}
		unset($item['course']);
		$this->p0_guard->json(array(
			'ok' => TRUE,
			'lifecycle' => $item,
			'checklist' => $this->lifecycle->checklist((int) $courseId, $comId),
			'history' => $this->lifecycle->history((int) $courseId, $comId),
		));
	}

	public function transition($courseId)
	{
		$this->p0_guard->requirePost();
		$user = $this->p0_guard->admin();
		$this->p0_guard->rateLimit('course-transition', 60, 60);
		$status = strtolower(trim((string) $this->input->post('status', TRUE)));
		$allowed = array('draft', 'submitted', 'reviewing', 'approved', 'rejected', 'scheduled', 'published', 'closed', 'archived');
		if (!in_array($status, $allowed, TRUE)) {
			$this->p0_guard->json(array('ok' => FALSE, 'message' => 'Invalid status'), 422);
			return;
		}
		$result = $this->lifecycle->transition(
			(int) $courseId,
			$this->p0_guard->companyId($user),
			$status,
			(string) $user['u_id'],
			trim((string) $this->input->post('reason', TRUE))
		);
		if (!empty($result['ok'])) {
			$course = $this->db->select('cos_createby')->get_where('lms_cos', array(
				'cos_id' => (int) $courseId,
				'com_id' => $this->p0_guard->companyId($user),
			))->row_array();
			if (!empty($course['cos_createby'])) {
				$owner = $this->db->select('emp_c')->get_where('lms_usp', array('u_id' => $course['cos_createby']))->row_array();
				if (!empty($owner['emp_c'])) {
					$this->notifications->create(
						$this->p0_guard->companyId($user),
						$owner['emp_c'],
						'course_lifecycle',
						'Course status updated',
						'The course is now '.$status,
						'managecourse/courses_all/'.$courseId,
						array('type' => 'course', 'id' => $courseId, 'priority' => $status === 'rejected' ? 'high' : 'normal')
					);
				}
			}
		}
		$this->p0_guard->json($result, $result['ok'] ? 200 : 422);
	}
}
