<?php

namespace App\Models;

use CodeIgniter\Model;

class NotificationCenterModel extends Model
{
    public function create(int $companyId, int $employeeId, string $type, string $title, string $message = '', string $url = '', ?int $referenceId = null, string $priority = 'normal'): int
    {
        $this->db->table('lms_notifications')->insert([
            'com_id' => $companyId, 'emp_id' => (string) $employeeId, 'type' => mb_substr($type, 0, 50),
            'ref_type' => $referenceId === null ? null : $type, 'ref_id' => $referenceId,
            'title' => mb_substr(trim($title), 0, 255), 'message' => $message, 'url' => mb_substr($url, 0, 500),
            'priority' => in_array($priority, ['low', 'normal', 'high'], true) ? $priority : 'normal',
            'is_read' => 0, 'created_at' => date('Y-m-d H:i:s'),
        ]);
        return (int) $this->db->insertID();
    }

    public function latest(array $user, int $limit = 20): array
    {
        return $this->db->table('lms_notifications')
            ->where(['com_id' => (int) $user['com_id'], 'emp_id' => (string) $user['emp_id']])
            ->orderBy('created_at', 'DESC')->limit(min(max($limit, 1), 100))->get()->getResultArray();
    }

    public function unread(array $user): int
    {
        return $this->db->table('lms_notifications')
            ->where(['com_id' => (int) $user['com_id'], 'emp_id' => (string) $user['emp_id'], 'is_read' => 0])
            ->countAllResults();
    }

    public function markRead(int $id, array $user): bool
    {
        return $this->db->table('lms_notifications')
            ->where(['noti_id' => $id, 'com_id' => (int) $user['com_id'], 'emp_id' => (string) $user['emp_id']])
            ->update(['is_read' => 1, 'read_at' => date('Y-m-d H:i:s')]);
    }

    public function markAllRead(array $user): bool
    {
        return $this->db->table('lms_notifications')
            ->where(['com_id' => (int) $user['com_id'], 'emp_id' => (string) $user['emp_id'], 'is_read' => 0])
            ->update(['is_read' => 1, 'read_at' => date('Y-m-d H:i:s')]);
    }

    public function preferences(array $user): array
    {
        return $this->db->table('lms_notification_preferences')
            ->where(['com_id' => (int) $user['com_id'], 'emp_id' => (string) $user['emp_id']])->get()->getRowArray()
            ?? ['in_app_enabled' => 1, 'email_enabled' => 1, 'digest_frequency' => 'immediate', 'quiet_hours_start' => null, 'quiet_hours_end' => null];
    }

    public function savePreferences(array $user, array $input): bool
    {
        $frequency = in_array($input['digest_frequency'] ?? '', ['immediate', 'daily', 'weekly', 'off'], true)
            ? $input['digest_frequency'] : 'immediate';
        $time = static fn ($value) => preg_match('/^(?:[01]\d|2[0-3]):[0-5]\d$/', (string) $value) ? $value . ':00' : null;
        return $this->db->table('lms_notification_preferences')->replace([
            'com_id' => (int) $user['com_id'], 'emp_id' => (string) $user['emp_id'],
            'in_app_enabled' => isset($input['in_app_enabled']) ? 1 : 0,
            'email_enabled' => isset($input['email_enabled']) ? 1 : 0,
            'digest_frequency' => $frequency,
            'quiet_hours_start' => $time($input['quiet_hours_start'] ?? null),
            'quiet_hours_end' => $time($input['quiet_hours_end'] ?? null),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
    }
}
