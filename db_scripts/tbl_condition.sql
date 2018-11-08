﻿-- Script was generated by Devart dbForge Studio for MySQL, Version 5.0.97.0
-- Product home page: http://www.devart.com/dbforge/mysql/studio
-- Script date 06/11/2013 14:50:28
-- Server version: 5.5.8
-- Client version: 4.1

-- 
-- Disable foreign keys
-- 
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;

-- 
-- Set character set the client will use to send SQL statements to the server
--
SET NAMES 'utf8';

-- 
-- Set default database
--
USE directhomecare;

-- 
-- Dumping data for table tbl_condition
--
INSERT INTO tbl_condition VALUES 
  (45, 'administer_drugs', 3, 10),
  (19, 'alzheimer', 2, 1),
  (17, 'cant_dressup', 1, 7),
  (16, 'cant_wakeup', 1, 6),
  (14, 'cant_walk', 1, 4),
  (15, 'cant_washup', 1, 5),
  (43, 'carer_car', 3, 8),
  (30, 'companionship', 3, 1),
  (53, 'dementia', 5, 4),
  (48, 'dependant', 4, 3),
  (49, 'disabled', 4, 4),
  (46, 'good_shape', 4, 1),
  (31, 'home_cleaning', 3, 2),
  (32, 'meal_preparation', 3, 3),
  (20, 'memory_problems', 2, 2),
  (21, 'no_mental_problems', 2, 3),
  (18, 'no_physical_problems', 1, 8),
  (12, 'paraplegic', 1, 2),
  (13, 'parkinson', 1, 3),
  (44, 'personal_care', 3, 9),
  (40, 'pet', 3, 5),
  (41, 'public_transport', 3, 6),
  (52, 'severe_memory_loss', 5, 3),
  (51, 'slight_memory_loss', 5, 2),
  (50, 'sound_minded', 5, 1),
  (11, 'tetraplegic', 1, 1),
  (42, 'user_car', 3, 7),
  (39, 'walking', 3, 4),
  (47, 'walking_difficulties', 4, 2);

-- 
-- Enable foreign keys
-- 
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;