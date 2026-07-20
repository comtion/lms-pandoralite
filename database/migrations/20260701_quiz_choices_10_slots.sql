DROP PROCEDURE IF EXISTS add_lms_ques_mul_column;

DELIMITER //
CREATE PROCEDURE add_lms_ques_mul_column(IN p_column_name VARCHAR(64), IN p_column_definition TEXT, IN p_after_column VARCHAR(64))
BEGIN
  IF NOT EXISTS (
    SELECT 1
    FROM information_schema.COLUMNS
    WHERE TABLE_SCHEMA = DATABASE()
      AND TABLE_NAME = 'lms_ques_mul'
      AND COLUMN_NAME = p_column_name
  ) THEN
    SET @alter_sql = CONCAT('ALTER TABLE lms_ques_mul ADD COLUMN ', p_column_name, ' ', p_column_definition, ' AFTER ', p_after_column);
    PREPARE alter_stmt FROM @alter_sql;
    EXECUTE alter_stmt;
    DEALLOCATE PREPARE alter_stmt;
  END IF;
END//
DELIMITER ;

CALL add_lms_ques_mul_column('mul_c6_th', 'TEXT NULL', 'mul_c5_th');
CALL add_lms_ques_mul_column('mul_c7_th', 'TEXT NULL', 'mul_c6_th');
CALL add_lms_ques_mul_column('mul_c8_th', 'TEXT NULL', 'mul_c7_th');
CALL add_lms_ques_mul_column('mul_c9_th', 'TEXT NULL', 'mul_c8_th');
CALL add_lms_ques_mul_column('mul_c10_th', 'TEXT NULL', 'mul_c9_th');
CALL add_lms_ques_mul_column('mul_c6_eng', 'TEXT NULL', 'mul_c5_eng');
CALL add_lms_ques_mul_column('mul_c7_eng', 'TEXT NULL', 'mul_c6_eng');
CALL add_lms_ques_mul_column('mul_c8_eng', 'TEXT NULL', 'mul_c7_eng');
CALL add_lms_ques_mul_column('mul_c9_eng', 'TEXT NULL', 'mul_c8_eng');
CALL add_lms_ques_mul_column('mul_c10_eng', 'TEXT NULL', 'mul_c9_eng');
CALL add_lms_ques_mul_column('mul_c6_jp', 'TEXT NULL', 'mul_c5_jp');
CALL add_lms_ques_mul_column('mul_c7_jp', 'TEXT NULL', 'mul_c6_jp');
CALL add_lms_ques_mul_column('mul_c8_jp', 'TEXT NULL', 'mul_c7_jp');
CALL add_lms_ques_mul_column('mul_c9_jp', 'TEXT NULL', 'mul_c8_jp');
CALL add_lms_ques_mul_column('mul_c10_jp', 'TEXT NULL', 'mul_c9_jp');

ALTER TABLE lms_ques_mul
  MODIFY COLUMN mul_answer VARCHAR(255) NOT NULL;

DROP PROCEDURE IF EXISTS add_lms_ques_mul_column;
