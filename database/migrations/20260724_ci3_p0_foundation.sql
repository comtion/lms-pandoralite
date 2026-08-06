-- CI3 P0 foundation. Additive and safe to run on an existing installation.

CREATE TABLE IF NOT EXISTS `lms_rate_limits` (
  `rate_key` CHAR(64) NOT NULL,
  `hit_count` INT UNSIGNED NOT NULL DEFAULT 1,
  `window_started_at` INT UNSIGNED NOT NULL,
  `expires_at` DATETIME NOT NULL,
  PRIMARY KEY (`rate_key`),
  KEY `idx_rate_limit_expiry` (`expires_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `lms_notifications` (
  `noti_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `com_id` INT NOT NULL,
  `emp_id` VARCHAR(64) NOT NULL,
  `type` VARCHAR(50) NOT NULL,
  `ref_type` VARCHAR(50) NULL,
  `ref_id` VARCHAR(64) NULL,
  `title` VARCHAR(255) NOT NULL,
  `message` TEXT NULL,
  `url` VARCHAR(500) NULL,
  `priority` VARCHAR(10) NOT NULL DEFAULT 'normal',
  `is_read` TINYINT(1) NOT NULL DEFAULT 0,
  `created_at` DATETIME NOT NULL,
  `read_at` DATETIME NULL,
  PRIMARY KEY (`noti_id`),
  KEY `idx_noti_user` (`com_id`, `emp_id`, `is_read`, `created_at`),
  KEY `idx_noti_reference` (`ref_type`, `ref_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Compatibility for installations that already ran
-- 20260701_course_new_notifications.sql.
SET @p0_sql = IF(
  (SELECT COUNT(*) FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'lms_notifications' AND COLUMN_NAME = 'com_id') = 0,
  'ALTER TABLE `lms_notifications` ADD COLUMN `com_id` INT NOT NULL DEFAULT 0 AFTER `noti_id`',
  'SELECT 1'
);
PREPARE p0_stmt FROM @p0_sql; EXECUTE p0_stmt; DEALLOCATE PREPARE p0_stmt;
ALTER TABLE `lms_notifications`
  MODIFY COLUMN `emp_id` VARCHAR(64) NOT NULL,
  MODIFY COLUMN `url` VARCHAR(500) NULL;
SET @p0_sql = IF(
  (SELECT COUNT(*) FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'lms_notifications' AND COLUMN_NAME = 'ref_type') = 0,
  'ALTER TABLE `lms_notifications` ADD COLUMN `ref_type` VARCHAR(50) NULL AFTER `type`',
  'SELECT 1'
);
PREPARE p0_stmt FROM @p0_sql; EXECUTE p0_stmt; DEALLOCATE PREPARE p0_stmt;
SET @p0_sql = IF(
  (SELECT COUNT(*) FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'lms_notifications' AND COLUMN_NAME = 'priority') = 0,
  'ALTER TABLE `lms_notifications` ADD COLUMN `priority` VARCHAR(10) NOT NULL DEFAULT ''normal'' AFTER `url`',
  'SELECT 1'
);
PREPARE p0_stmt FROM @p0_sql; EXECUTE p0_stmt; DEALLOCATE PREPARE p0_stmt;

CREATE TABLE IF NOT EXISTS `lms_notification_preferences` (
  `com_id` INT NOT NULL,
  `emp_id` VARCHAR(64) NOT NULL,
  `in_app_enabled` TINYINT(1) NOT NULL DEFAULT 1,
  `email_enabled` TINYINT(1) NOT NULL DEFAULT 1,
  `digest_frequency` VARCHAR(20) NOT NULL DEFAULT 'immediate',
  `quiet_hours_start` TIME NULL,
  `quiet_hours_end` TIME NULL,
  `updated_at` DATETIME NOT NULL,
  PRIMARY KEY (`com_id`, `emp_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `lms_notification_outbox` (
  `outbox_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `com_id` INT NOT NULL,
  `emp_id` VARCHAR(64) NOT NULL,
  `channel` VARCHAR(20) NOT NULL,
  `recipient` VARCHAR(255) NULL,
  `subject` VARCHAR(255) NOT NULL,
  `body` LONGTEXT NULL,
  `status` VARCHAR(20) NOT NULL DEFAULT 'pending',
  `attempts` SMALLINT UNSIGNED NOT NULL DEFAULT 0,
  `available_at` DATETIME NOT NULL,
  `locked_at` DATETIME NULL,
  `sent_at` DATETIME NULL,
  `last_error` TEXT NULL,
  `created_at` DATETIME NOT NULL,
  PRIMARY KEY (`outbox_id`),
  KEY `idx_outbox_due` (`status`, `available_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `lms_course_lifecycle` (
  `cos_id` INT NOT NULL,
  `com_id` INT NOT NULL,
  `lifecycle_status` VARCHAR(20) NOT NULL DEFAULT 'draft',
  `version_no` INT UNSIGNED NOT NULL DEFAULT 1,
  `submitted_by` VARCHAR(64) NULL,
  `submitted_at` DATETIME NULL,
  `reviewed_by` VARCHAR(64) NULL,
  `reviewed_at` DATETIME NULL,
  `published_by` VARCHAR(64) NULL,
  `published_at` DATETIME NULL,
  `closed_at` DATETIME NULL,
  `archived_by` VARCHAR(64) NULL,
  `archived_at` DATETIME NULL,
  `rejection_reason` TEXT NULL,
  `updated_at` DATETIME NOT NULL,
  PRIMARY KEY (`cos_id`),
  KEY `idx_lifecycle_company_status` (`com_id`, `lifecycle_status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `lms_course_lifecycle_history` (
  `history_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `cos_id` INT NOT NULL,
  `com_id` INT NOT NULL,
  `from_status` VARCHAR(20) NULL,
  `to_status` VARCHAR(20) NOT NULL,
  `version_no` INT UNSIGNED NOT NULL,
  `reason` TEXT NULL,
  `changed_by` VARCHAR(64) NOT NULL,
  `changed_at` DATETIME NOT NULL,
  PRIMARY KEY (`history_id`),
  KEY `idx_lifecycle_history_course` (`cos_id`, `changed_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `lms_enrollment_requests` (
  `request_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `com_id` INT NOT NULL,
  `cos_id` INT NOT NULL,
  `emp_id` VARCHAR(64) NOT NULL,
  `request_type` VARCHAR(20) NOT NULL DEFAULT 'self',
  `status` VARCHAR(20) NOT NULL DEFAULT 'pending',
  `requested_by` VARCHAR(64) NOT NULL,
  `requested_at` DATETIME NOT NULL,
  `reviewed_by` VARCHAR(64) NULL,
  `reviewed_at` DATETIME NULL,
  `decision_reason` TEXT NULL,
  `starts_at` DATETIME NULL,
  `expires_at` DATETIME NULL,
  `created_at` DATETIME NOT NULL,
  `updated_at` DATETIME NOT NULL,
  PRIMARY KEY (`request_id`),
  KEY `idx_enrollment_user_status` (`com_id`, `cos_id`, `emp_id`, `status`),
  KEY `idx_enrollment_approval` (`com_id`, `status`, `requested_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

SET @p0_sql = IF(
  (SELECT COUNT(*) FROM information_schema.STATISTICS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'lms_enrollment_requests' AND INDEX_NAME = 'uniq_active_enrollment_request') > 0,
  'ALTER TABLE `lms_enrollment_requests` DROP INDEX `uniq_active_enrollment_request`, ADD INDEX `idx_enrollment_user_status` (`com_id`,`cos_id`,`emp_id`,`status`)',
  'SELECT 1'
);
PREPARE p0_stmt FROM @p0_sql; EXECUTE p0_stmt; DEALLOCATE PREPARE p0_stmt;

CREATE TABLE IF NOT EXISTS `lms_enrollment_waitlist` (
  `waitlist_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `com_id` INT NOT NULL,
  `cos_id` INT NOT NULL,
  `emp_id` VARCHAR(64) NOT NULL,
  `position_no` INT UNSIGNED NOT NULL,
  `status` VARCHAR(20) NOT NULL DEFAULT 'waiting',
  `joined_at` DATETIME NOT NULL,
  `promoted_at` DATETIME NULL,
  PRIMARY KEY (`waitlist_id`),
  UNIQUE KEY `uniq_waitlist_user` (`com_id`, `cos_id`, `emp_id`),
  KEY `idx_waitlist_position` (`com_id`, `cos_id`, `status`, `position_no`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `lms_enrollment_policies` (
  `cos_id` INT NOT NULL,
  `com_id` INT NOT NULL,
  `enrollment_mode` VARCHAR(20) NOT NULL DEFAULT 'approval',
  `capacity` INT UNSIGNED NULL,
  `waitlist_enabled` TINYINT(1) NOT NULL DEFAULT 1,
  `starts_at` DATETIME NULL,
  `expires_at` DATETIME NULL,
  `allow_reenroll` TINYINT(1) NOT NULL DEFAULT 0,
  `updated_by` VARCHAR(64) NOT NULL,
  `updated_at` DATETIME NOT NULL,
  PRIMARY KEY (`cos_id`),
  KEY `idx_enrollment_policy_company` (`com_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `lms_job_runs` (
  `job_run_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `job_name` VARCHAR(100) NOT NULL,
  `status` VARCHAR(20) NOT NULL,
  `started_at` DATETIME NOT NULL,
  `finished_at` DATETIME NULL,
  `duration_ms` INT UNSIGNED NULL,
  `message` TEXT NULL,
  `metrics_json` LONGTEXT NULL,
  PRIMARY KEY (`job_run_id`),
  KEY `idx_job_latest` (`job_name`, `started_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `lms_backup_runs` (
  `backup_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `backup_type` VARCHAR(20) NOT NULL,
  `status` VARCHAR(20) NOT NULL,
  `file_path` VARCHAR(500) NULL,
  `file_size` BIGINT UNSIGNED NULL,
  `checksum_sha256` CHAR(64) NULL,
  `started_at` DATETIME NOT NULL,
  `finished_at` DATETIME NULL,
  `message` TEXT NULL,
  PRIMARY KEY (`backup_id`),
  KEY `idx_backup_latest` (`backup_type`, `started_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
