-- Eat Rrite MySQL schema (phpMyAdmin → SQL → Run)
-- Matches the former Neon/Prisma tables. Safe to run on an empty database.

SET NAMES utf8mb4;
SET time_zone = '+05:30';

CREATE TABLE IF NOT EXISTS appointments (
  id VARCHAR(32) NOT NULL,
  name VARCHAR(120) NOT NULL,
  email VARCHAR(190) NOT NULL DEFAULT '',
  phone VARCHAR(20) NOT NULL DEFAULT '',
  service VARCHAR(190) NOT NULL DEFAULT '',
  date CHAR(10) NOT NULL,
  time CHAR(5) NOT NULL,
  meet_link VARCHAR(500) NOT NULL DEFAULT '',
  payment_id VARCHAR(80) NOT NULL DEFAULT '',
  order_id VARCHAR(80) NOT NULL DEFAULT '',
  status VARCHAR(32) NOT NULL DEFAULT 'completed',
  booked_at VARCHAR(32) NOT NULL DEFAULT '',
  emails_sent TINYINT(1) NOT NULL DEFAULT 0,
  questionnaire_completed TINYINT(1) NOT NULL DEFAULT 0,
  questionnaire_reminders_sent VARCHAR(32) NOT NULL DEFAULT '',
  error TEXT NULL,
  display_date VARCHAR(64) NOT NULL DEFAULT '',
  display_time VARCHAR(32) NOT NULL DEFAULT '',
  verified_at INT NULL,
  completed_at INT NULL,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  KEY idx_appointments_date_time (date, time),
  KEY idx_appointments_date (date),
  KEY idx_appointments_payment (payment_id),
  KEY idx_appointments_order (order_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS holds (
  id VARCHAR(32) NOT NULL,
  hold_id VARCHAR(64) NOT NULL,
  date CHAR(10) NOT NULL,
  time CHAR(5) NOT NULL,
  order_id VARCHAR(80) NOT NULL DEFAULT '',
  expires_at DATETIME NOT NULL,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY uq_holds_hold_id (hold_id),
  KEY idx_holds_date_time (date, time),
  KEY idx_holds_expires (expires_at),
  KEY idx_holds_order (order_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS disabled_slots (
  id VARCHAR(32) NOT NULL,
  date CHAR(10) NOT NULL,
  time CHAR(5) NOT NULL,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY uq_disabled_date_time (date, time)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS questionnaire_answers (
  id VARCHAR(32) NOT NULL,
  appointment_id VARCHAR(32) NOT NULL,
  question_key VARCHAR(64) NOT NULL,
  answer TEXT NOT NULL,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  KEY idx_qa_appointment (appointment_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS snackbar_orders (
  id VARCHAR(32) NOT NULL,
  name VARCHAR(120) NOT NULL,
  email VARCHAR(190) NOT NULL,
  phone VARCHAR(20) NOT NULL,
  address TEXT NOT NULL,
  quantity INT NOT NULL,
  amount_rupees INT NOT NULL,
  payment_id VARCHAR(80) NOT NULL DEFAULT '',
  order_id VARCHAR(80) NOT NULL DEFAULT '',
  status VARCHAR(32) NOT NULL DEFAULT 'paid',
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  KEY idx_snackbar_payment (payment_id),
  KEY idx_snackbar_order (order_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS cohort_applications (
  id VARCHAR(32) NOT NULL,
  name VARCHAR(120) NOT NULL,
  email VARCHAR(190) NOT NULL,
  phone VARCHAR(20) NOT NULL,
  amount_rupees INT NOT NULL,
  cohort_month CHAR(7) NOT NULL,
  payment_id VARCHAR(80) NOT NULL DEFAULT '',
  order_id VARCHAR(80) NOT NULL DEFAULT '',
  status VARCHAR(32) NOT NULL DEFAULT 'paid',
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  KEY idx_cohort_payment (payment_id),
  KEY idx_cohort_order (order_id),
  KEY idx_cohort_month (cohort_month)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
