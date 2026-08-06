<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Course_lifecycle_model extends CI_Model
{
	private $transitions = array(
		'draft' => array('submitted', 'archived'),
		'submitted' => array('reviewing', 'draft'),
		'reviewing' => array('approved', 'rejected'),
		'rejected' => array('draft', 'submitted', 'archived'),
		'approved' => array('scheduled', 'published', 'draft', 'archived'),
		'scheduled' => array('published', 'closed', 'archived'),
		'published' => array('closed', 'archived'),
		'closed' => array('published', 'archived'),
		'archived' => array('draft'),
	);

	public function get($courseId, $companyId)
	{
		$course = $this->db->where(array('cos_id' => (int) $courseId, 'com_id' => (int) $companyId))
			->get('lms_cos')->row_array();
		if (!$course) {
			return NULL;
		}
		$lifecycle = $this->db->get_where('lms_course_lifecycle', array(
			'cos_id' => (int) $courseId,
			'com_id' => (int) $companyId,
		))->row_array();
		if (!$lifecycle) {
			$lifecycle = array(
				'cos_id' => (int) $courseId,
				'com_id' => (int) $companyId,
				'lifecycle_status' => 'draft',
				'version_no' => 1,
				'updated_at' => date('Y-m-d H:i:s'),
			);
			$this->db->insert('lms_course_lifecycle', $lifecycle);
		}
		$lifecycle['course'] = $course;
		return $lifecycle;
	}

	public function checklist($courseId, $companyId)
	{
		$record = $this->get($courseId, $companyId);
		if (!$record) {
			return array('valid' => FALSE, 'errors' => array('Course not found'));
		}
		$course = $record['course'];
		$errors = array();
		if (trim((string) ($course['cname_th'] ?? '')) === '' &&
			trim((string) ($course['cname_eng'] ?? '')) === '' &&
			trim((string) ($course['cname_jp'] ?? '')) === '') {
			$errors[] = 'Course title is required';
		}
		if ((int) $this->db->where(array('cos_id' => (int) $courseId, 'les_isDelete' => 0))
			->count_all_results('lms_les') === 0) {
			$errors[] = 'At least one active lesson is required';
		}
		if (empty($course['cos_pic'])) {
			$errors[] = 'Course cover image is required';
		}
		return array('valid' => empty($errors), 'errors' => $errors);
	}

	public function transition($courseId, $companyId, $toStatus, $userId, $reason = '')
	{
		$record = $this->get($courseId, $companyId);
		if (!$record) {
			return array('ok' => FALSE, 'message' => 'Course not found');
		}
		$from = $record['lifecycle_status'];
		if (!isset($this->transitions[$from]) || !in_array($toStatus, $this->transitions[$from], TRUE)) {
			return array('ok' => FALSE, 'message' => 'Invalid lifecycle transition: '.$from.' → '.$toStatus);
		}
		if (in_array($toStatus, array('submitted', 'approved', 'scheduled', 'published'), TRUE)) {
			$check = $this->checklist($courseId, $companyId);
			if (!$check['valid']) {
				return array('ok' => FALSE, 'message' => 'Course is not ready', 'errors' => $check['errors']);
			}
		}
		if ($toStatus === 'rejected' && trim($reason) === '') {
			return array('ok' => FALSE, 'message' => 'Rejection reason is required');
		}

		$now = date('Y-m-d H:i:s');
		$update = array('lifecycle_status' => $toStatus, 'updated_at' => $now);
		if ($toStatus === 'submitted') $update += array('submitted_by' => $userId, 'submitted_at' => $now);
		if (in_array($toStatus, array('approved', 'rejected'), TRUE)) {
			$update += array('reviewed_by' => $userId, 'reviewed_at' => $now, 'rejection_reason' => $toStatus === 'rejected' ? $reason : NULL);
		}
		if ($toStatus === 'published') $update += array('published_by' => $userId, 'published_at' => $now);
		if ($toStatus === 'closed') $update['closed_at'] = $now;
		if ($toStatus === 'archived') $update += array('archived_by' => $userId, 'archived_at' => $now);
		if ($toStatus === 'draft' && $from !== 'draft') $update['version_no'] = (int) $record['version_no'] + 1;

		$this->db->trans_start();
		$this->db->where(array('cos_id' => (int) $courseId, 'com_id' => (int) $companyId))
			->update('lms_course_lifecycle', $update);
		$this->db->insert('lms_course_lifecycle_history', array(
			'cos_id' => (int) $courseId,
			'com_id' => (int) $companyId,
			'from_status' => $from,
			'to_status' => $toStatus,
			'version_no' => isset($update['version_no']) ? $update['version_no'] : (int) $record['version_no'],
			'reason' => $reason,
			'changed_by' => (string) $userId,
			'changed_at' => $now,
		));
		$this->db->trans_complete();
		return array('ok' => $this->db->trans_status(), 'status' => $toStatus);
	}

	public function history($courseId, $companyId)
	{
		return $this->db->where(array('cos_id' => (int) $courseId, 'com_id' => (int) $companyId))
			->order_by('changed_at', 'DESC')
			->get('lms_course_lifecycle_history')->result_array();
	}
}
