-- Run once on the existing Eat Rrite MySQL database (phpMyAdmin → SQL).

ALTER TABLE appointments
  ADD COLUMN questionnaire_reminders_sent VARCHAR(32) NOT NULL DEFAULT ''
  AFTER questionnaire_completed;
