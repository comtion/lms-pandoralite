INSERT INTO `lms_menu`
  (`mu_name_th`, `mu_name_en`, `mu_name_jp`, `mu_path`, `mu_customer`, `mu_num`, `mu_status`, `mu_parent`, `mu_icon`)
SELECT
  'AUDIT LOG',
  'AUDIT LOG',
  'AUDIT LOG',
  'auditlog/view',
  0,
  19,
  1,
  49,
  'mdi mdi-history'
WHERE NOT EXISTS (
  SELECT 1 FROM `lms_menu` WHERE `mu_path` = 'auditlog/view'
);

INSERT INTO `lms_role_usp`
  (`u_id`, `mu_id`, `ru_view`, `ru_add`, `ru_edit`, `ru_del`, `ru_print`)
SELECT
  u.`u_id`,
  m.`mu_id`,
  1,
  0,
  0,
  0,
  1
FROM `lms_usp` u
JOIN `lms_menu` m ON m.`mu_path` = 'auditlog/view'
LEFT JOIN `lms_role_usp` r ON r.`u_id` = u.`u_id` AND r.`mu_id` = m.`mu_id`
WHERE u.`ug_id` = 1
  AND r.`u_id` IS NULL;
