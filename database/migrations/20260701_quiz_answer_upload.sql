ALTER TABLE lms_ques
  ADD COLUMN ques_upload_required TINYINT(1) NOT NULL DEFAULT 0 AFTER ques_isSavescore,
  ADD COLUMN ques_upload_type VARCHAR(20) NOT NULL DEFAULT 'both' AFTER ques_upload_required,
  ADD COLUMN ques_upload_max_mb INT NOT NULL DEFAULT 10 AFTER ques_upload_type,
  ADD COLUMN ques_upload_note TEXT NULL AFTER ques_upload_max_mb;

ALTER TABLE lms_ques_tc
  ADD COLUMN tc_upload_file VARCHAR(255) NULL AFTER tc_note,
  ADD COLUMN tc_upload_original VARCHAR(255) NULL AFTER tc_upload_file,
  ADD COLUMN tc_upload_type VARCHAR(20) NULL AFTER tc_upload_original;
