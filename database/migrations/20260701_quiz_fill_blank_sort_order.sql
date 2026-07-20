DROP PROCEDURE IF EXISTS add_lms_ques_column;

DELIMITER //
CREATE PROCEDURE add_lms_ques_column(IN p_column_name VARCHAR(64), IN p_column_definition TEXT, IN p_after_column VARCHAR(64))
BEGIN
  IF NOT EXISTS (
    SELECT 1
    FROM information_schema.COLUMNS
    WHERE TABLE_SCHEMA = DATABASE()
      AND TABLE_NAME = 'lms_ques'
      AND COLUMN_NAME = p_column_name
  ) THEN
    SET @alter_sql = CONCAT('ALTER TABLE lms_ques ADD COLUMN ', p_column_name, ' ', p_column_definition, ' AFTER ', p_after_column);
    PREPARE alter_stmt FROM @alter_sql;
    EXECUTE alter_stmt;
    DEALLOCATE PREPARE alter_stmt;
  END IF;
END//
DELIMITER ;

CALL add_lms_ques_column('ques_blank_score_mode', 'VARCHAR(20) NOT NULL DEFAULT ''all_or_nothing''', 'ques_upload_note');

DROP PROCEDURE IF EXISTS add_lms_ques_column;
