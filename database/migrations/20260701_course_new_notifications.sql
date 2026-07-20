CREATE TABLE IF NOT EXISTS `lms_course_notification_schedules` (
  `cn_id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `cos_id` INT NOT NULL,
  `enabled` TINYINT(1) NOT NULL DEFAULT 0,
  `channels` VARCHAR(50) NOT NULL DEFAULT 'system,email',
  `audience_type` VARCHAR(30) NOT NULL DEFAULT 'all',
  `target_departments` TEXT NULL,
  `target_users` TEXT NULL,
  `send_at` DATETIME NOT NULL,
  `status` VARCHAR(20) NOT NULL DEFAULT 'pending',
  `created_by` VARCHAR(50) NULL,
  `updated_by` VARCHAR(50) NULL,
  `created_at` DATETIME NOT NULL,
  `updated_at` DATETIME NOT NULL,
  `dispatched_at` DATETIME NULL,
  PRIMARY KEY (`cn_id`),
  UNIQUE KEY `uniq_course_notification_schedule` (`cos_id`),
  KEY `idx_course_notification_due` (`enabled`, `status`, `send_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `lms_course_notification_logs` (
  `cnl_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `cn_id` INT UNSIGNED NOT NULL,
  `cos_id` INT NOT NULL,
  `emp_id` INT NULL,
  `channel` VARCHAR(20) NOT NULL,
  `recipient_email` VARCHAR(255) NULL,
  `status` VARCHAR(20) NOT NULL,
  `message` TEXT NULL,
  `created_at` DATETIME NOT NULL,
  PRIMARY KEY (`cnl_id`),
  KEY `idx_course_notification_log_schedule` (`cn_id`),
  KEY `idx_course_notification_log_course` (`cos_id`),
  KEY `idx_course_notification_log_recipient` (`emp_id`, `channel`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `lms_notifications` (
  `noti_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `emp_id` INT NOT NULL,
  `type` VARCHAR(50) NOT NULL,
  `ref_id` INT NULL,
  `title` VARCHAR(255) NOT NULL,
  `message` TEXT NULL,
  `url` VARCHAR(255) NULL,
  `is_read` TINYINT(1) NOT NULL DEFAULT 0,
  `created_at` DATETIME NOT NULL,
  `read_at` DATETIME NULL,
  PRIMARY KEY (`noti_id`),
  KEY `idx_notifications_user` (`emp_id`, `is_read`, `created_at`),
  KEY `idx_notifications_ref` (`type`, `ref_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
