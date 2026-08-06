<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Notification_model extends CI_Model
{
	public function latest($comId, $empId, $limit = 20)
	{
		return $this->db
			->where('com_id', (int) $comId)
			->where('emp_id', (string) $empId)
			->order_by('created_at', 'DESC')
			->limit(min(max((int) $limit, 1), 100))
			->get('lms_notifications')
			->result_array();
	}

	public function unreadCount($comId, $empId)
	{
		return (int) $this->db
			->where(array('com_id' => (int) $comId, 'emp_id' => (string) $empId, 'is_read' => 0))
			->count_all_results('lms_notifications');
	}

	public function create($comId, $empId, $type, $title, $message = '', $url = '', $reference = array())
	{
		$now = date('Y-m-d H:i:s');
		$this->db->insert('lms_notifications', array(
			'com_id' => (int) $comId,
			'emp_id' => (string) $empId,
			'type' => substr((string) $type, 0, 50),
			'ref_type' => isset($reference['type']) ? substr($reference['type'], 0, 50) : NULL,
			'ref_id' => isset($reference['id']) ? substr((string) $reference['id'], 0, 64) : NULL,
			'title' => substr(trim((string) $title), 0, 255),
			'message' => (string) $message,
			'url' => substr((string) $url, 0, 500),
			'priority' => isset($reference['priority']) ? $reference['priority'] : 'normal',
			'is_read' => 0,
			'created_at' => $now,
		));
		return (int) $this->db->insert_id();
	}

	public function markRead($notificationId, $comId, $empId)
	{
		return $this->db
			->where('noti_id', (int) $notificationId)
			->where('com_id', (int) $comId)
			->where('emp_id', (string) $empId)
			->update('lms_notifications', array('is_read' => 1, 'read_at' => date('Y-m-d H:i:s')));
	}

	public function markAllRead($comId, $empId)
	{
		return $this->db
			->where('com_id', (int) $comId)
			->where('emp_id', (string) $empId)
			->where('is_read', 0)
			->update('lms_notifications', array('is_read' => 1, 'read_at' => date('Y-m-d H:i:s')));
	}

	public function preferences($comId, $empId)
	{
		$row = $this->db->get_where('lms_notification_preferences', array(
			'com_id' => (int) $comId,
			'emp_id' => (string) $empId,
		))->row_array();
		return $row ?: array(
			'in_app_enabled' => 1,
			'email_enabled' => 1,
			'digest_frequency' => 'immediate',
			'quiet_hours_start' => NULL,
			'quiet_hours_end' => NULL,
		);
	}

	public function savePreferences($comId, $empId, array $data)
	{
		$data['com_id'] = (int) $comId;
		$data['emp_id'] = (string) $empId;
		$data['updated_at'] = date('Y-m-d H:i:s');
		return $this->db->replace('lms_notification_preferences', $data);
	}
}
