ALTER TABLE `lms_audit_logs`
  ADD COLUMN `audit_com_id` VARCHAR(64) DEFAULT NULL AFTER `audit_emp_id`,
  ADD KEY `idx_audit_com_created` (`audit_com_id`, `audit_created_at`);
