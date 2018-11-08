﻿-- Script was generated by Devart dbForge Studio for MySQL, Version 5.0.97.0
-- Product home page: http://www.devart.com/dbforge/mysql/studio
-- Script date 06/05/2013 23:35:43
-- Server version: 5.5.8
-- Client version: 4.1
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;

SET NAMES 'utf8';

USE directhomecare;

DROP TABLE IF EXISTS tbl_address;
CREATE TABLE tbl_address (
  id INT(11) NOT NULL AUTO_INCREMENT,
  data_type INT(1) NOT NULL COMMENT 'carer 0, location 1, booking 2, mission 3, billing 4',
  address_line_1 VARCHAR(50) NOT NULL,
  address_line_2 VARCHAR(50) DEFAULT NULL,
  city VARCHAR(50) NOT NULL,
  county VARCHAR(50) NOT NULL,
  post_code VARCHAR(10) NOT NULL,
  country VARCHAR(50) NOT NULL,
  landline VARCHAR(20) DEFAULT NULL,
  longitude DOUBLE DEFAULT NULL,
  latitude DOUBLE DEFAULT NULL,
  valid_from DATETIME DEFAULT NULL,
  valid_to DATETIME DEFAULT NULL,
  PRIMARY KEY (id)
)
ENGINE = INNODB
AUTO_INCREMENT = 335
AVG_ROW_LENGTH = 265
CHARACTER SET utf8
COLLATE utf8_unicode_ci;

DROP TABLE IF EXISTS tbl_calendar;
CREATE TABLE tbl_calendar (
  id INT(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (id),
  UNIQUE INDEX id (id)
)
ENGINE = INNODB
AUTO_INCREMENT = 1
CHARACTER SET utf8
COLLATE utf8_unicode_ci;

DROP TABLE IF EXISTS tbl_carer_experience;
CREATE TABLE tbl_carer_experience (
  id INT(11) NOT NULL AUTO_INCREMENT,
  start_date DATE DEFAULT NULL,
  end_date DATE DEFAULT NULL,
  employer VARCHAR(50) DEFAULT NULL,
  id_carer INT(11) DEFAULT NULL,
  PRIMARY KEY (id),
  INDEX id_carer (id_carer)
)
ENGINE = INNODB
AUTO_INCREMENT = 1
CHARACTER SET utf8
COLLATE utf8_unicode_ci;

DROP TABLE IF EXISTS tbl_client;
CREATE TABLE tbl_client (
  id INT(11) NOT NULL AUTO_INCREMENT,
  first_name VARCHAR(50) NOT NULL,
  last_name VARCHAR(50) NOT NULL,
  email_address VARCHAR(80) NOT NULL,
  `password` VARCHAR(128) NOT NULL,
  date_birth DATE DEFAULT NULL,
  mobile_phone VARCHAR(15) DEFAULT NULL,
  wizard_completed TINYINT(1) NOT NULL DEFAULT 0,
  created DATETIME NOT NULL,
  modified DATETIME NOT NULL,
  PRIMARY KEY (id)
)
ENGINE = INNODB
AUTO_INCREMENT = 413
AVG_ROW_LENGTH = 359
CHARACTER SET utf8
COLLATE utf8_unicode_ci;

DROP TABLE IF EXISTS tbl_client_carer_rating;
CREATE TABLE tbl_client_carer_rating (
  id INT(11) NOT NULL AUTO_INCREMENT,
  id_carer_availability INT(11) DEFAULT NULL,
  id_client INT(11) DEFAULT NULL,
  carer_rating INT(11) DEFAULT NULL,
  client_rating INT(11) DEFAULT NULL,
  PRIMARY KEY (id),
  INDEX id_carer_availability (id_carer_availability),
  INDEX id_client (id_client)
)
ENGINE = INNODB
AUTO_INCREMENT = 1
CHARACTER SET utf8
COLLATE utf8_unicode_ci;

DROP TABLE IF EXISTS tbl_condition;
CREATE TABLE tbl_condition (
  id INT(11) NOT NULL AUTO_INCREMENT,
  name VARCHAR(30) NOT NULL,
  type INT(2) NOT NULL,
  `order` INT(3) NOT NULL,
  PRIMARY KEY (id),
  UNIQUE INDEX UK_tbl_condition (name, type, `order`)
)
ENGINE = INNODB
AUTO_INCREMENT = 22
AVG_ROW_LENGTH = 1489
CHARACTER SET utf8
COLLATE utf8_unicode_ci;

DROP TABLE IF EXISTS tbl_document_type;
CREATE TABLE tbl_document_type (
  type INT(3) NOT NULL,
  description VARCHAR(50) NOT NULL,
  PRIMARY KEY (type),
  UNIQUE INDEX type (type)
)
ENGINE = INNODB
AVG_ROW_LENGTH = 2730
CHARACTER SET utf8
COLLATE utf8_unicode_ci;

DROP TABLE IF EXISTS tbl_live_in_request;
CREATE TABLE tbl_live_in_request (
  id INT(11) NOT NULL AUTO_INCREMENT,
  start_date DATE NOT NULL,
  end_date DATE NOT NULL,
  id_client INT(11) NOT NULL,
  id_service_location INT(11) NOT NULL,
  start_time TIME NOT NULL,
  status TINYINT(1) NOT NULL,
  created DATETIME NOT NULL,
  modified DATETIME NOT NULL,
  PRIMARY KEY (id),
  INDEX FK_tbl_live_in_request_tbl_client_id (id_client),
  INDEX FK_tbl_live_in_request_tbl_service_location_request_id (id_service_location)
)
ENGINE = INNODB
AUTO_INCREMENT = 139
AVG_ROW_LENGTH = 1365
CHARACTER SET utf8
COLLATE utf8_unicode_ci;

DROP TABLE IF EXISTS tbl_mission_live_in;
CREATE TABLE tbl_mission_live_in (
  id_mission INT(11) NOT NULL,
  start_date_time DATETIME NOT NULL,
  end_date_time DATETIME NOT NULL,
  PRIMARY KEY (id_mission)
)
ENGINE = INNODB
CHARACTER SET utf8
COLLATE utf8_unicode_ci;

DROP TABLE IF EXISTS tbl_money_mouvement;
CREATE TABLE tbl_money_mouvement (
  id INT(11) NOT NULL AUTO_INCREMENT,
  amount FLOAT(2, 0) DEFAULT NULL,
  type INT(2) DEFAULT NULL,
  PRIMARY KEY (id)
)
ENGINE = INNODB
AUTO_INCREMENT = 1
CHARACTER SET utf8
COLLATE utf8_unicode_ci;

DROP TABLE IF EXISTS tbl_prices;
CREATE TABLE tbl_prices (
  id INT(4) NOT NULL AUTO_INCREMENT,
  name VARCHAR(30) NOT NULL,
  user INT(1) NOT NULL,
  amount FLOAT(5, 2) NOT NULL,
  currency VARCHAR(3) NOT NULL,
  valid_from DATETIME NOT NULL,
  valid_to DATETIME NOT NULL,
  PRIMARY KEY (id)
)
ENGINE = INNODB
AUTO_INCREMENT = 13
AVG_ROW_LENGTH = 1638
CHARACTER SET utf8
COLLATE utf8_unicode_ci;

DROP TABLE IF EXISTS tbl_service_user;
CREATE TABLE tbl_service_user (
  id INT(11) NOT NULL AUTO_INCREMENT,
  first_name VARCHAR(50) NOT NULL,
  last_name VARCHAR(50) NOT NULL,
  date_birth DATE NOT NULL,
  gender INT(11) NOT NULL,
  PRIMARY KEY (id)
)
ENGINE = INNODB
AUTO_INCREMENT = 1062
AVG_ROW_LENGTH = 73
CHARACTER SET utf8
COLLATE utf8_unicode_ci;

DROP TABLE IF EXISTS tbl_carer;
CREATE TABLE tbl_carer (
  id INT(11) NOT NULL AUTO_INCREMENT,
  id_address INT(11) DEFAULT NULL,
  first_name VARCHAR(50) NOT NULL,
  last_name VARCHAR(50) NOT NULL,
  email_address VARCHAR(60) NOT NULL,
  `password` VARCHAR(128) NOT NULL,
  date_birth DATE NOT NULL,
  gender INT(1) NOT NULL DEFAULT 0,
  hourly_work INT(1) NOT NULL DEFAULT 0,
  nationality VARCHAR(50) NOT NULL,
  country_birth VARCHAR(50) NOT NULL,
  mobile_phone VARCHAR(15) NOT NULL,
  live_in INT(1) NOT NULL DEFAULT 0,
  live_in_work_radius INT(1) NOT NULL DEFAULT 0,
  hourly_work_radius INT(1) NOT NULL DEFAULT 0,
  work_with_male INT(1) NOT NULL DEFAULT 0,
  work_with_female INT(1) NOT NULL DEFAULT 0,
  driving_licence INT(1) DEFAULT 0,
  car_owner INT(1) DEFAULT 0,
  last_login_date DATE DEFAULT NULL,
  account_number VARCHAR(20) DEFAULT NULL,
  wizard_completed TINYINT(1) NOT NULL DEFAULT 0,
  terms_conditions TINYINT(1) NOT NULL DEFAULT 0,
  dh_rating INT(5) DEFAULT NULL,
  sort_code VARCHAR(10) DEFAULT NULL,
  modified DATETIME NOT NULL,
  created DATETIME NOT NULL,
  PRIMARY KEY (id),
  CONSTRAINT FK_tbl_carer_tbl_address_id FOREIGN KEY (id_address)
    REFERENCES tbl_address(id) ON DELETE RESTRICT ON UPDATE RESTRICT
)
ENGINE = INNODB
AUTO_INCREMENT = 538
AVG_ROW_LENGTH = 630
CHARACTER SET utf8
COLLATE utf8_unicode_ci;

DROP TABLE IF EXISTS tbl_client_location_address;
CREATE TABLE tbl_client_location_address (
  id_client INT(11) NOT NULL,
  id_address INT(11) NOT NULL,
  PRIMARY KEY (id_client, id_address),
  INDEX FK_tbl_service_location (id_client),
  CONSTRAINT FK_tbl_service_location_address_tbl_address_id FOREIGN KEY (id_address)
    REFERENCES tbl_address(id) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT FK_tbl_service_location_tbl_client_id FOREIGN KEY (id_client)
    REFERENCES tbl_client(id) ON DELETE RESTRICT ON UPDATE RESTRICT
)
ENGINE = INNODB
AVG_ROW_LENGTH = 431
CHARACTER SET utf8
COLLATE utf8_unicode_ci;

DROP TABLE IF EXISTS tbl_client_service_user;
CREATE TABLE tbl_client_service_user (
  id_client INT(11) NOT NULL,
  id_service_user INT(11) NOT NULL,
  CONSTRAINT FK_tbl_client_service_user_tbl_client_id FOREIGN KEY (id_client)
    REFERENCES tbl_client(id) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT FK_tbl_client_service_user_tbl_service_user_id FOREIGN KEY (id_service_user)
    REFERENCES tbl_service_user(id) ON DELETE RESTRICT ON UPDATE RESTRICT
)
ENGINE = INNODB
AVG_ROW_LENGTH = 606
CHARACTER SET utf8
COLLATE utf8_general_ci;

DROP TABLE IF EXISTS tbl_credit_card;
CREATE TABLE tbl_credit_card (
  id INT(11) NOT NULL AUTO_INCREMENT,
  id_client INT(11) NOT NULL,
  id_address INT(11) NOT NULL,
  name_on_card VARCHAR(40) NOT NULL,
  card_type TINYINT(1) NOT NULL,
  card_number CHAR(100) NOT NULL,
  last_three_digits CHAR(100) NOT NULL,
  expiry_date VARCHAR(100) NOT NULL,
  valid_from DATETIME NOT NULL,
  valid_to DATETIME NOT NULL,
  PRIMARY KEY (id),
  CONSTRAINT FK_tbl_credit_card_tbl_address_id FOREIGN KEY (id_address)
    REFERENCES tbl_address(id) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT FK_tbl_credit_card_tbl_client_id FOREIGN KEY (id_client)
    REFERENCES tbl_client(id) ON DELETE RESTRICT ON UPDATE RESTRICT
)
ENGINE = INNODB
AUTO_INCREMENT = 114
AVG_ROW_LENGTH = 528
CHARACTER SET utf8
COLLATE utf8_general_ci;

DROP TABLE IF EXISTS tbl_document;
CREATE TABLE tbl_document (
  id INT(11) NOT NULL AUTO_INCREMENT,
  name VARCHAR(30) NOT NULL,
  type INT(3) NOT NULL,
  `order` INT(2) NOT NULL,
  PRIMARY KEY (id),
  UNIQUE INDEX UK_tbl_document (name, `order`, type),
  CONSTRAINT FK_tbl_document_tbl_document_type_type FOREIGN KEY (type)
    REFERENCES tbl_document_type(type) ON DELETE RESTRICT ON UPDATE RESTRICT
)
ENGINE = INNODB
AUTO_INCREMENT = 60
AVG_ROW_LENGTH = 963
CHARACTER SET utf8
COLLATE utf8_unicode_ci;

DROP TABLE IF EXISTS tbl_document_requirement;
CREATE TABLE tbl_document_requirement (
  id INT(11) NOT NULL AUTO_INCREMENT,
  id_document_type INT(3) NOT NULL,
  requirement VARCHAR(50) NOT NULL,
  PRIMARY KEY (id),
  CONSTRAINT FK_tbl_document_requirement_tbl_document_type_type FOREIGN KEY (id_document_type)
    REFERENCES tbl_document_type(type) ON DELETE RESTRICT ON UPDATE RESTRICT
)
ENGINE = INNODB
AUTO_INCREMENT = 13
AVG_ROW_LENGTH = 1365
CHARACTER SET utf8
COLLATE utf8_unicode_ci;

DROP TABLE IF EXISTS tbl_service_user_condition;
CREATE TABLE tbl_service_user_condition (
  id_condition INT(11) NOT NULL,
  id_service_user INT(11) NOT NULL,
  PRIMARY KEY (id_condition, id_service_user),
  CONSTRAINT FK_tbl_service_user_condition_tbl_condition_id FOREIGN KEY (id_condition)
    REFERENCES tbl_condition(id) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT FK_tbl_service_user_condition_tbl_service_user_id FOREIGN KEY (id_service_user)
    REFERENCES tbl_service_user(id) ON DELETE RESTRICT ON UPDATE RESTRICT
)
ENGINE = INNODB
AVG_ROW_LENGTH = 49
CHARACTER SET utf8
COLLATE utf8_general_ci;

DROP TABLE IF EXISTS tbl_age_group;
CREATE TABLE tbl_age_group (
  id INT(11) NOT NULL AUTO_INCREMENT,
  id_carer INT(11) NOT NULL,
  age_group INT(2) NOT NULL,
  PRIMARY KEY (id),
  CONSTRAINT FK_tbl_age_group_tbl_carer_id FOREIGN KEY (id_carer)
    REFERENCES tbl_carer(id) ON DELETE RESTRICT ON UPDATE RESTRICT
)
ENGINE = INNODB
AUTO_INCREMENT = 116
AVG_ROW_LENGTH = 546
CHARACTER SET utf8
COLLATE utf8_unicode_ci;

DROP TABLE IF EXISTS tbl_booking;
CREATE TABLE tbl_booking (
  id INT(11) NOT NULL AUTO_INCREMENT,
  id_client INT(11) NOT NULL,
  id_credit_card INT(11) NOT NULL COMMENT 'Credit card selected by user that will be used for the next payment',
  id_address INT(11) NOT NULL COMMENT 'Service location of next missions',
  start_date_time DATETIME NOT NULL,
  end_date_time DATETIME DEFAULT NULL,
  recurring SMALLINT(1) NOT NULL DEFAULT 0,
  type TINYINT(1) NOT NULL COMMENT 'Live in 1 or Hourly 2',
  discarded_by_client INT(1) NOT NULL DEFAULT 0,
  id_calendar INT(11) DEFAULT NULL,
  created DATETIME NOT NULL,
  modified DATETIME NOT NULL,
  PRIMARY KEY (id),
  CONSTRAINT FK_booking_tbl_calendar_id FOREIGN KEY (id_calendar)
    REFERENCES tbl_calendar(id) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT FK_tbl_booking_live_in_tbl_client_id FOREIGN KEY (id_client)
    REFERENCES tbl_client(id) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT FK_tbl_booking_tbl_address_id FOREIGN KEY (id_address)
    REFERENCES tbl_address(id) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT FK_tbl_booking_tbl_credit_card_id FOREIGN KEY (id_credit_card)
    REFERENCES tbl_credit_card(id) ON DELETE RESTRICT ON UPDATE RESTRICT
)
ENGINE = INNODB
AUTO_INCREMENT = 213
AVG_ROW_LENGTH = 399
CHARACTER SET utf8
COLLATE utf8_unicode_ci;

DROP TABLE IF EXISTS tbl_carer_availability;
CREATE TABLE tbl_carer_availability (
  id INT(11) NOT NULL AUTO_INCREMENT,
  day_week INT(1) NOT NULL,
  time_slot INT(2) NOT NULL,
  id_carer INT(11) NOT NULL,
  PRIMARY KEY (id),
  INDEX FK_tbl_availability_carer_tbl_carer_id (id_carer),
  UNIQUE INDEX UK_tbl_availability_carer (day_week, time_slot, id_carer),
  CONSTRAINT FK_tbl_carer_availability_tbl_carer_id FOREIGN KEY (id_carer)
    REFERENCES tbl_carer(id) ON DELETE RESTRICT ON UPDATE RESTRICT
)
ENGINE = INNODB
AUTO_INCREMENT = 1
CHARACTER SET utf8
COLLATE utf8_unicode_ci;

DROP TABLE IF EXISTS tbl_carer_condition;
CREATE TABLE tbl_carer_condition (
  id_carer INT(11) NOT NULL,
  id_condition INT(11) NOT NULL,
  PRIMARY KEY (id_carer, id_condition),
  CONSTRAINT FK_tbl_carer_condition_tbl_carer_id FOREIGN KEY (id_carer)
    REFERENCES tbl_carer(id) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT FK_tbl_carer_condition_tbl_condition_id FOREIGN KEY (id_condition)
    REFERENCES tbl_condition(id) ON DELETE RESTRICT ON UPDATE RESTRICT
)
ENGINE = INNODB
AVG_ROW_LENGTH = 409
CHARACTER SET utf8
COLLATE utf8_general_ci;

DROP TABLE IF EXISTS tbl_carer_document;
CREATE TABLE tbl_carer_document (
  id INT(11) NOT NULL AUTO_INCREMENT,
  id_document INT(11) NOT NULL,
  id_carer INT(11) NOT NULL,
  status INT(1) NOT NULL,
  active INT(1) NOT NULL DEFAULT 0,
  year_obtained INT(4) DEFAULT NULL,
  id_content INT(11) DEFAULT NULL,
  `text` VARCHAR(255) DEFAULT NULL,
  reject_reason VARCHAR(255) DEFAULT NULL,
  PRIMARY KEY (id),
  INDEX FK_tbl_carer_document_tbl_file_content_id (id_content),
  UNIQUE INDEX UK_tbl_carer_document (id_carer, id_document, year_obtained, active),
  CONSTRAINT FK_tbl_carer_document_tbl_carer_id FOREIGN KEY (id_carer)
    REFERENCES tbl_carer(id) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT FK_tbl_carer_document_tbl_document_id FOREIGN KEY (id_document)
    REFERENCES tbl_document(id) ON DELETE RESTRICT ON UPDATE RESTRICT
)
ENGINE = INNODB
AUTO_INCREMENT = 45
AVG_ROW_LENGTH = 2048
CHARACTER SET utf8
COLLATE utf8_unicode_ci;

DROP TABLE IF EXISTS tbl_client_document;
CREATE TABLE tbl_client_document (
  id INT(11) NOT NULL AUTO_INCREMENT,
  id_document INT(11) NOT NULL,
  id_client INT(11) NOT NULL,
  year_obtained INT(4) NOT NULL DEFAULT 0,
  status INT(1) NOT NULL,
  id_content INT(11) DEFAULT NULL,
  PRIMARY KEY (id),
  INDEX FK_tbl_carer_document_tbl_document_id (id_document),
  UNIQUE INDEX id_content (id_content),
  UNIQUE INDEX UK_tbl_carer_document (id_client, id_document, status, year_obtained),
  CONSTRAINT FK_tbl_client_document_tbl_document_id FOREIGN KEY (id_document)
    REFERENCES tbl_document(id) ON DELETE RESTRICT ON UPDATE RESTRICT
)
ENGINE = INNODB
AUTO_INCREMENT = 1
CHARACTER SET utf8
COLLATE utf8_unicode_ci;

DROP TABLE IF EXISTS tbl_credit_card_expiry;
CREATE TABLE tbl_credit_card_expiry (
  id INT(11) NOT NULL AUTO_INCREMENT,
  id_credit_card INT(11) NOT NULL,
  number_email_sent INT(1) NOT NULL,
  created DATETIME NOT NULL,
  modified DATETIME NOT NULL,
  PRIMARY KEY (id),
  CONSTRAINT FK_tbl_credit_card_expiry_tbl_credit_card_id FOREIGN KEY (id_credit_card)
    REFERENCES tbl_credit_card(id) ON DELETE RESTRICT ON UPDATE RESTRICT
)
ENGINE = INNODB
AUTO_INCREMENT = 8
AVG_ROW_LENGTH = 2340
CHARACTER SET utf8
COLLATE utf8_general_ci;

DROP TABLE IF EXISTS tbl_booking_gap;
CREATE TABLE tbl_booking_gap (
  id INT(11) NOT NULL AUTO_INCREMENT,
  id_booking INT(11) NOT NULL,
  start_date_time DATETIME NOT NULL,
  end_date_time DATETIME NOT NULL,
  type INT(1) NOT NULL,
  PRIMARY KEY (id),
  CONSTRAINT FK_tbl_booking_gap_tbl_booking_id FOREIGN KEY (id_booking)
    REFERENCES tbl_booking(id) ON DELETE RESTRICT ON UPDATE RESTRICT
)
ENGINE = INNODB
AUTO_INCREMENT = 1
CHARACTER SET utf8
COLLATE utf8_general_ci;

DROP TABLE IF EXISTS tbl_booking_service_user;
CREATE TABLE tbl_booking_service_user (
  id_booking INT(11) NOT NULL,
  id_service_user INT(11) NOT NULL,
  PRIMARY KEY (id_booking, id_service_user),
  CONSTRAINT FK_tbl_booking_service_user_tbl_booking_id FOREIGN KEY (id_booking)
    REFERENCES tbl_booking(id) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT FK_tbl_booking_service_user_tbl_service_user_id FOREIGN KEY (id_service_user)
    REFERENCES tbl_service_user(id) ON DELETE RESTRICT ON UPDATE RESTRICT
)
ENGINE = INNODB
AVG_ROW_LENGTH = 327
CHARACTER SET utf8
COLLATE utf8_general_ci;

DROP TABLE IF EXISTS tbl_mission_payment;
CREATE TABLE tbl_mission_payment (
  id INT(11) NOT NULL AUTO_INCREMENT,
  id_booking INT(11) NOT NULL,
  id_credit_card INT(11) NOT NULL COMMENT 'Credit card used for the payment',
  start_date_time DATETIME NOT NULL COMMENT 'Range of missions',
  end_date_time DATETIME NOT NULL COMMENT 'Range of missions',
  modified DATETIME NOT NULL,
  created DATETIME NOT NULL,
  transaction_id VARCHAR(30) DEFAULT NULL COMMENT 'Reference to PayPal transaction id, could be null if payment with voucher',
  PRIMARY KEY (id),
  CONSTRAINT FK_tbl_mission_payment_tbl_booking_id FOREIGN KEY (id_booking)
    REFERENCES tbl_booking(id) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT FK_tbl_mission_payment_tbl_credit_card_id FOREIGN KEY (id_credit_card)
    REFERENCES tbl_credit_card(id) ON DELETE RESTRICT ON UPDATE RESTRICT
)
ENGINE = INNODB
AUTO_INCREMENT = 344
AVG_ROW_LENGTH = 309
CHARACTER SET utf8
COLLATE utf8_unicode_ci;

DROP TABLE IF EXISTS tbl_client_transaction;
CREATE TABLE tbl_client_transaction (
  id INT(11) NOT NULL AUTO_INCREMENT,
  id_client INT(11) NOT NULL,
  id_mission_payment INT(11) NOT NULL,
  type INT(2) NOT NULL,
  currency VARCHAR(3) NOT NULL,
  paid_cash FLOAT(7, 2) DEFAULT NULL,
  paid_credit FLOAT(7, 2) DEFAULT NULL,
  reimbursed FLOAT(7, 2) DEFAULT NULL,
  credit FLOAT(7, 2) DEFAULT NULL,
  credit_balance FLOAT(7, 2) NOT NULL DEFAULT 0.00,
  created DATETIME NOT NULL,
  PRIMARY KEY (id),
  CONSTRAINT FK_tbl_client_transaction_tbl_client_id FOREIGN KEY (id_client)
    REFERENCES tbl_client(id) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT FK_tbl_client_transaction_tbl_mission_payment_id FOREIGN KEY (id_mission_payment)
    REFERENCES tbl_mission_payment(id) ON DELETE RESTRICT ON UPDATE RESTRICT
)
ENGINE = INNODB
AUTO_INCREMENT = 241
AVG_ROW_LENGTH = 172
CHARACTER SET utf8
COLLATE utf8_unicode_ci;

DROP TABLE IF EXISTS tbl_mission;
CREATE TABLE tbl_mission (
  id INT(11) NOT NULL AUTO_INCREMENT,
  id_address INT(11) NOT NULL COMMENT 'Service location of mission',
  id_booking INT(11) NOT NULL,
  id_mission_payment INT(11) NOT NULL,
  type INT(2) NOT NULL DEFAULT 0 COMMENT 'MISSION_LIVE_IN = 1, MISSION_HOURLY = 2',
  status INT(2) NOT NULL DEFAULT 0 COMMENT 'ACTIVE = 0, CANCELLED_BY_CARER = 1, CANCELLED_BY_CLIENT = 2',
  start_date_time DATETIME NOT NULL,
  end_date_time DATETIME NOT NULL,
  discarded_by_carer TINYINT(1) NOT NULL DEFAULT 0 COMMENT '0 = false, 1 = true',
  discarded_by_cllient TINYINT(1) NOT NULL DEFAULT 0 COMMENT '0 = false, 1 = true',
  carer_credited INT(1) NOT NULL DEFAULT 0,
  cancel_by_client_date DATETIME NOT NULL,
  created DATETIME NOT NULL,
  modified DATETIME NOT NULL,
  PRIMARY KEY (id),
  CONSTRAINT FK_tbl_mission_tbl_address_id FOREIGN KEY (id_address)
    REFERENCES tbl_address(id) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT FK_tbl_mission_tbl_booking_id FOREIGN KEY (id_booking)
    REFERENCES tbl_booking(id) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT FK_tbl_mission_tbl_mission_payment_id FOREIGN KEY (id_mission_payment)
    REFERENCES tbl_mission_payment(id) ON DELETE RESTRICT ON UPDATE RESTRICT
)
ENGINE = INNODB
AUTO_INCREMENT = 257
AVG_ROW_LENGTH = 309
CHARACTER SET utf8
COLLATE utf8_unicode_ci;

DROP TABLE IF EXISTS tbl_action_history;
CREATE TABLE tbl_action_history (
  id_carer INT(11) NOT NULL,
  `action` INT(2) NOT NULL,
  id_mission INT(11) NOT NULL,
  created DATETIME NOT NULL,
  modified DATETIME NOT NULL,
  triggered_by INT(2) NOT NULL,
  PRIMARY KEY (id_carer, `action`, id_mission, created),
  CONSTRAINT FK_tbl_action_history_tbl_carer_id FOREIGN KEY (id_carer)
    REFERENCES tbl_carer(id) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT FK_tbl_action_history_tbl_mission_id FOREIGN KEY (id_mission)
    REFERENCES tbl_mission(id) ON DELETE RESTRICT ON UPDATE RESTRICT
)
ENGINE = INNODB
AVG_ROW_LENGTH = 130
CHARACTER SET utf8
COLLATE utf8_unicode_ci;

DROP TABLE IF EXISTS tbl_carer_transaction;
CREATE TABLE tbl_carer_transaction (
  id INT(11) NOT NULL AUTO_INCREMENT,
  id_carer INT(11) NOT NULL,
  id_mission INT(11) DEFAULT NULL,
  created DATETIME NOT NULL,
  type INT(2) NOT NULL,
  currency VARCHAR(3) NOT NULL,
  paid_credit FLOAT(7, 2) DEFAULT NULL,
  credit_balance FLOAT(7, 2) NOT NULL DEFAULT 0.00,
  withdraw FLOAT(7, 2) DEFAULT NULL,
  PRIMARY KEY (id),
  CONSTRAINT FK_tbl_carer_transaction_2_tbl_carer_id FOREIGN KEY (id_carer)
    REFERENCES tbl_carer(id) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT FK_tbl_carer_transaction_2_tbl_mission_id FOREIGN KEY (id_mission)
    REFERENCES tbl_mission(id) ON DELETE RESTRICT ON UPDATE RESTRICT
)
ENGINE = INNODB
AUTO_INCREMENT = 83
AVG_ROW_LENGTH = 210
CHARACTER SET utf8
COLLATE utf8_unicode_ci;

DROP TABLE IF EXISTS tbl_complaint;
CREATE TABLE tbl_complaint (
  id INT(11) NOT NULL AUTO_INCREMENT,
  created_by INT(1) NOT NULL,
  id_client INT(11) NOT NULL,
  id_carer INT(11) NOT NULL,
  id_mission INT(11) NOT NULL,
  type INT(2) NOT NULL,
  solved INT(1) NOT NULL,
  created DATETIME NOT NULL,
  modified DATETIME NOT NULL,
  PRIMARY KEY (id),
  CONSTRAINT FK_tbl_carer_feedback FOREIGN KEY (id_carer)
    REFERENCES tbl_carer(id) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT FK_tbl_carer_feedback_tbl_client_id FOREIGN KEY (id_client)
    REFERENCES tbl_client(id) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT FK_tbl_carer_feedback_tbl_mission_id FOREIGN KEY (id_mission)
    REFERENCES tbl_mission(id) ON DELETE RESTRICT ON UPDATE RESTRICT
)
ENGINE = INNODB
AUTO_INCREMENT = 24
AVG_ROW_LENGTH = 8192
CHARACTER SET utf8
COLLATE utf8_unicode_ci;

DROP TABLE IF EXISTS tbl_mission_carers;
CREATE TABLE tbl_mission_carers (
  id INT(11) NOT NULL AUTO_INCREMENT,
  id_mission INT(11) NOT NULL,
  id_applying_carer INT(11) NOT NULL,
  status TINYINT(1) NOT NULL,
  created DATETIME NOT NULL,
  modified DATETIME NOT NULL,
  discarded INT(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (id),
  INDEX id_applying_carer (id_applying_carer),
  INDEX id_live_in_mission (id_mission),
  INDEX status (status),
  CONSTRAINT FK_tbl_live_in_mission_carers_tbl_carer_id FOREIGN KEY (id_applying_carer)
    REFERENCES tbl_carer(id) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT FK_tbl_mission_carers_tbl_mission_id FOREIGN KEY (id_mission)
    REFERENCES tbl_mission(id) ON DELETE RESTRICT ON UPDATE RESTRICT
)
ENGINE = INNODB
AUTO_INCREMENT = 65
AVG_ROW_LENGTH = 630
CHARACTER SET utf8
COLLATE utf8_unicode_ci;

DROP TABLE IF EXISTS tbl_mission_hourly;
CREATE TABLE tbl_mission_hourly (
  id_mission INT(11) NOT NULL,
  end_date DATE NOT NULL,
  PRIMARY KEY (id_mission),
  CONSTRAINT FK_tbl_mission_hourly_tbl_mission_id FOREIGN KEY (id_mission)
    REFERENCES tbl_mission(id) ON DELETE RESTRICT ON UPDATE RESTRICT
)
ENGINE = INNODB
CHARACTER SET utf8
COLLATE utf8_unicode_ci;

DROP TABLE IF EXISTS tbl_mission_service_user;
CREATE TABLE tbl_mission_service_user (
  id_mission INT(11) NOT NULL,
  id_service_user INT(11) NOT NULL,
  PRIMARY KEY (id_mission, id_service_user),
  CONSTRAINT FK_tbl_mission_service_user_tbl_mission_id FOREIGN KEY (id_mission)
    REFERENCES tbl_mission(id) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT FK_tbl_mission_service_user_tbl_service_user_id FOREIGN KEY (id_service_user)
    REFERENCES tbl_service_user(id) ON DELETE RESTRICT ON UPDATE RESTRICT
)
ENGINE = INNODB
AVG_ROW_LENGTH = 260
CHARACTER SET utf8
COLLATE utf8_general_ci;

DROP TABLE IF EXISTS tbl_mission_slot_aborted;
CREATE TABLE tbl_mission_slot_aborted (
  id INT(11) NOT NULL AUTO_INCREMENT,
  id_mission INT(11) NOT NULL,
  start_date_time DATETIME NOT NULL,
  end_date_time DATETIME NOT NULL,
  reported_by INT(1) NOT NULL COMMENT 'carer or client',
  aborted_by INT(1) NOT NULL COMMENT 'carer or client',
  type INT(2) NOT NULL COMMENT 'carer did not come/not coming, client did not open door/client did not want the service',
  created_by INT(1) NOT NULL COMMENT 'carer, client or admin (version de l''admin is the truth)',
  created DATETIME NOT NULL,
  modified DATETIME NOT NULL,
  PRIMARY KEY (id),
  CONSTRAINT FK_tbl_mission_aborted_tbl_mission_id FOREIGN KEY (id_mission)
    REFERENCES tbl_mission(id) ON DELETE RESTRICT ON UPDATE RESTRICT
)
ENGINE = INNODB
AUTO_INCREMENT = 26
AVG_ROW_LENGTH = 2340
CHARACTER SET utf8
COLLATE utf8_unicode_ci;

DROP TABLE IF EXISTS tbl_complaint_post;
CREATE TABLE tbl_complaint_post (
  id INT(11) NOT NULL AUTO_INCREMENT,
  id_complaint INT(11) NOT NULL,
  author INT(1) NOT NULL COMMENT '1: client 2: carer: 3:admin',
  visible_by INT(1) NOT NULL COMMENT '1: client 2: carer: 4: all (client, carer and admin)',
  `text` VARCHAR(255) NOT NULL,
  created DATETIME NOT NULL,
  modified DATETIME NOT NULL,
  PRIMARY KEY (id),
  CONSTRAINT FK_tbl_complaint_post_tbl_complaint_id FOREIGN KEY (id_complaint)
    REFERENCES tbl_complaint(id) ON DELETE RESTRICT ON UPDATE RESTRICT
)
ENGINE = INNODB
AUTO_INCREMENT = 101
AVG_ROW_LENGTH = 1092
CHARACTER SET utf8
COLLATE utf8_unicode_ci;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;