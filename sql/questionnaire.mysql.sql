-- Run once on the existing Eat Rrite MySQL database (phpMyAdmin → SQL).

ALTER TABLE appointments
  ADD COLUMN questionnaire_completed TINYINT(1) NOT NULL DEFAULT 0 AFTER emails_sent;

CREATE TABLE IF NOT EXISTS questionnaire_answers (
  id VARCHAR(32) NOT NULL,
  appointment_id VARCHAR(32) NOT NULL,
  question_key VARCHAR(64) NOT NULL,
  answer TEXT NOT NULL,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  KEY idx_qa_appointment (appointment_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
