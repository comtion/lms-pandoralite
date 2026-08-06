-- Secure authentication additions. Additive and safe to run repeatedly.

CREATE TABLE IF NOT EXISTS `lms_password_reset_tokens` (
  `reset_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `u_id` BIGINT NOT NULL,
  `token_hash` CHAR(64) NOT NULL,
  `requested_ip_hash` CHAR(64) NOT NULL,
  `expires_at` DATETIME NOT NULL,
  `used_at` DATETIME NULL,
  `created_at` DATETIME NOT NULL,
  PRIMARY KEY (`reset_id`),
  UNIQUE KEY `uq_password_reset_token` (`token_hash`),
  KEY `idx_password_reset_user` (`u_id`, `expires_at`, `used_at`),
  CONSTRAINT `fk_password_reset_user` FOREIGN KEY (`u_id`) REFERENCES `lms_usp` (`u_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

