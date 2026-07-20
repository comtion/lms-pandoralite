ALTER TABLE `lms_audit_logs`
  ADD COLUMN `audit_prev_hash` CHAR(64) DEFAULT NULL AFTER `audit_changed_values`,
  ADD COLUMN `audit_hash` CHAR(64) DEFAULT NULL AFTER `audit_prev_hash`,
  ADD KEY `idx_audit_hash` (`audit_hash`),
  ADD KEY `idx_audit_prev_hash` (`audit_prev_hash`);
