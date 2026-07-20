ALTER TABLE `lms_audit_logs`
  ADD KEY `idx_audit_created` (`audit_created_at`),
  ADD KEY `idx_audit_table_row_created` (`audit_table`, `audit_row_key`, `audit_created_at`),
  ADD KEY `idx_audit_username_created` (`audit_username`, `audit_created_at`);
