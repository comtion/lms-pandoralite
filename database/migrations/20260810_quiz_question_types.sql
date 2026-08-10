-- Extended CI4 quiz question types. Safe to run more than once.
DROP PROCEDURE IF EXISTS add_lms_quiz_question_column;

DELIMITER //
CREATE PROCEDURE add_lms_quiz_question_column(IN p_name VARCHAR(64), IN p_definition TEXT, IN p_after VARCHAR(64))
BEGIN
  IF NOT EXISTS (
    SELECT 1 FROM information_schema.COLUMNS
    WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'lms_ques' AND COLUMN_NAME = p_name
  ) THEN
    SET @sql = CONCAT('ALTER TABLE lms_ques ADD COLUMN ', p_name, ' ', p_definition, ' AFTER ', p_after);
    PREPARE statement FROM @sql;
    EXECUTE statement;
    DEALLOCATE PREPARE statement;
  END IF;
END//
DELIMITER ;

CALL add_lms_quiz_question_column('ques_numeric_answer', 'DECIMAL(20,6) NULL', 'ques_blank_score_mode');
CALL add_lms_quiz_question_column('ques_numeric_tolerance', 'DECIMAL(20,6) NOT NULL DEFAULT 0', 'ques_numeric_answer');
CALL add_lms_quiz_question_column('ques_text_match_mode', 'VARCHAR(20) NOT NULL DEFAULT ''exact''', 'ques_numeric_tolerance');

DROP PROCEDURE IF EXISTS add_lms_quiz_question_column;
