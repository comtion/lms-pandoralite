<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Enrollment_workflow_model extends CI_Model
{
	public function policy($courseId, $companyId)
	{
		$row = $this->db->get_where('lms_enrollment_policies', array(
			'cos_id' => (int) $courseId, 'com_id' => (int) $companyId,
		))->row_array();
		return $row ?: array(
			'cos_id' => (int) $courseId, 'com_id' => (int) $companyId,
			'enrollment_mode' => 'approval', 'capacity' => NULL,
			'waitlist_enabled' => 1, 'allow_reenroll' => 0,
		);
	}

	public function savePolicy($courseId, $companyId, array $data, $userId)
	{
		$course = $this->course($courseId, $companyId);
		if (!$course) return FALSE;
		$data += array('cos_id' => (int) $courseId, 'com_id' => (int) $companyId);
		$data['updated_by'] = (string) $userId;
		$data['updated_at'] = date('Y-m-d H:i:s');
		return $this->db->replace('lms_enrollment_policies', $data);
	}

	public function request($courseId, $companyId, $empId, $requestedBy, $type = 'self')
	{
		$course = $this->course($courseId, $companyId);
		if (!$course) return array('ok' => FALSE, 'message' => 'Course not found');
		$policy = $this->policy($courseId, $companyId);
		if ($policy['enrollment_mode'] === 'closed') return array('ok' => FALSE, 'message' => 'Enrollment is closed');
		$existing = $this->db->where(array('com_id' => $companyId, 'cos_id' => $courseId, 'emp_id' => $empId))
			->where_in('status', array('pending', 'approved', 'waitlisted'))->get('lms_enrollment_requests')->row_array();
		if ($existing) return array('ok' => FALSE, 'message' => 'An active enrollment request already exists');

		$status = $policy['enrollment_mode'] === 'open' ? 'approved' : 'pending';
		if ($this->isFull($course, $policy)) $status = !empty($policy['waitlist_enabled']) ? 'waitlisted' : 'capacity_full';
		$now = date('Y-m-d H:i:s');
		$this->db->trans_start();
		$this->db->insert('lms_enrollment_requests', array(
			'com_id' => (int) $companyId, 'cos_id' => (int) $courseId, 'emp_id' => (string) $empId,
			'request_type' => $type, 'status' => $status, 'requested_by' => (string) $requestedBy,
			'requested_at' => $now, 'starts_at' => $policy['starts_at'] ?? NULL,
			'expires_at' => $policy['expires_at'] ?? NULL, 'created_at' => $now, 'updated_at' => $now,
		));
		$requestId = (int) $this->db->insert_id();
		if ($status === 'approved') $this->activateLegacyEnrollment($course, $empId);
		if ($status === 'waitlisted') $this->joinWaitlist($courseId, $companyId, $empId);
		$this->db->trans_complete();
		return array('ok' => $this->db->trans_status(), 'request_id' => $requestId, 'status' => $status);
	}

	public function pending($companyId, $limit = 100)
	{
		return $this->db->where('com_id', (int) $companyId)->where('status', 'pending')
			->order_by('requested_at', 'ASC')->limit(min(max((int) $limit, 1), 500))
			->get('lms_enrollment_requests')->result_array();
	}

	public function decide($requestId, $companyId, $decision, $reviewer, $reason)
	{
		$request = $this->db->get_where('lms_enrollment_requests', array(
			'request_id' => (int) $requestId, 'com_id' => (int) $companyId, 'status' => 'pending',
		))->row_array();
		if (!$request) return array('ok' => FALSE, 'message' => 'Pending request not found');
		$course = $this->course($request['cos_id'], $companyId);
		$policy = $this->policy($request['cos_id'], $companyId);
		$status = $decision === 'approve' ? 'approved' : 'rejected';
		if ($status === 'approved' && $this->isFull($course, $policy)) {
			$status = !empty($policy['waitlist_enabled']) ? 'waitlisted' : 'capacity_full';
		}
		$now = date('Y-m-d H:i:s');
		$this->db->trans_start();
		$this->db->where('request_id', (int) $requestId)->update('lms_enrollment_requests', array(
			'status' => $status, 'reviewed_by' => (string) $reviewer, 'reviewed_at' => $now,
			'decision_reason' => $reason, 'updated_at' => $now,
		));
		if ($status === 'approved') $this->activateLegacyEnrollment($course, $request['emp_id']);
		if ($status === 'waitlisted') $this->joinWaitlist($request['cos_id'], $companyId, $request['emp_id']);
		$this->db->trans_complete();
		return array('ok' => $this->db->trans_status(), 'status' => $status);
	}

	private function course($courseId, $companyId)
	{
		return $this->db->get_where('lms_cos', array('cos_id' => (int) $courseId, 'com_id' => (int) $companyId))->row_array();
	}

	private function isFull($course, $policy)
	{
		$capacity = !empty($policy['capacity']) ? (int) $policy['capacity'] : (int) ($course['seat_count'] ?? 0);
		if ($capacity <= 0) return FALSE;
		$count = $this->db->where('course_id', $course['ccode'])->where('ens_status !=', 'cancelled')
			->count_all_results('lms_ens');
		return $count >= $capacity;
	}

	private function activateLegacyEnrollment(array $course, $empId)
	{
		$exists = $this->db->get_where('lms_ens', array('course_id' => $course['ccode'], 'emp_c' => $empId))->row_array();
		if ($exists) {
			$this->db->where(array('course_id' => $course['ccode'], 'emp_c' => $empId))
				->update('lms_ens', array('enroll_status1' => 'yes', 'ens_status' => 'approved'));
			return;
		}
		$this->db->insert('lms_ens', array(
			'emp_c' => (string) $empId, 'course_id' => $course['ccode'],
			'enroll_status1' => 'yes', 'ens_status' => 'approved',
			'time_request' => date('Y-m-d H:i'),
		));
	}

	private function joinWaitlist($courseId, $companyId, $empId)
	{
		$max = $this->db->select_max('position_no', 'position')->get_where('lms_enrollment_waitlist', array(
			'com_id' => (int) $companyId, 'cos_id' => (int) $courseId,
		))->row_array();
		$this->db->insert('lms_enrollment_waitlist', array(
			'com_id' => (int) $companyId, 'cos_id' => (int) $courseId, 'emp_id' => (string) $empId,
			'position_no' => ((int) ($max['position'] ?? 0)) + 1, 'status' => 'waiting',
			'joined_at' => date('Y-m-d H:i:s'),
		));
	}
}
