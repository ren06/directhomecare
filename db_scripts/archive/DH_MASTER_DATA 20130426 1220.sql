
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
  (19, 'alzheimer', 2, 1),
  (17, 'cant_dressup', 1, 7),
  (16, 'cant_wakeup', 1, 6),
  (14, 'cant_walk', 1, 4),
  (15, 'cant_washup', 1, 5),
  (20, 'memory_problems', 2, 2),
  (21, 'no_mental_problems', 2, 3),
  (18, 'no_physical_problems', 1, 8),
  (12, 'paraplegic', 1, 2),
  (13, 'parkinson', 1, 3),
  (11, 'tetraplegic', 1, 1);


-- 
-- Dumping data for table tbl_document_type
--
INSERT INTO tbl_document_type VALUES 
  (1, 'diploma'),
  (2, 'identification'),
  (3, 'photo'),
  (4, 'criminal'),
  (5, 'driving_licence'),
  (6, 'text');


-- 
-- Dumping data for table tbl_document
--

SET FOREIGN_KEY_CHECKS=0;

REPLACE INTO `tbl_document` (`id`, `name`, `type`, `order`) VALUES
(60, 'activities_coordinator', 1, 4),
(96, 'ageing_process', 1, 47),
(61, 'autism_awarness', 1, 5),
(62, 'care_planning', 1, 6),
(63, 'challenging_behaviour', 1, 7),
(64, 'child_protection', 1, 8),
(65, 'communication_record_keeping', 1, 9),
(66, 'confidentiality_awarness', 1, 10),
(67, 'conflict_management', 1, 11),
(68, 'coshh', 1, 12),
(21, 'crb_check', 4, 5),
(8, 'dementia', 1, 13),
(69, 'diabetes_awarness', 1, 14),
(104, 'disclosure_scotland', 4, 9),
(70, 'dols', 1, 15),
(45, 'driving_licence', 5, 1),
(71, 'eating_disorders', 1, 16),
(7, 'emergency_first_aid', 1, 17),
(34, 'enhanced_crb_check', 4, 6),
(103, 'enhanced_crb_check_adult_child', 4, 8),
(102, 'enhanced_crb_check_children', 4, 7),
(99, 'enhanced_dbs_check_adult', 4, 2),
(101, 'enhanced_dbs_check_adult_child', 4, 4),
(100, 'enhanced_dbs_check_children', 4, 3),
(72, 'epilepsy_awarness', 1, 18),
(73, 'equality_diversity', 1, 19),
(74, 'fire_safety_awarness', 1, 20),
(11, 'food_hygiene', 1, 21),
(75, 'health_safety_awarness', 1, 22),
(20, 'id', 2, 1),
(76, 'infection_control_awarness', 1, 23),
(77, 'lone_working_awarness', 1, 24),
(78, 'loss_bereavement', 1, 25),
(79, 'medication_awarness', 1, 26),
(80, 'mental_capacity_act_dols', 1, 27),
(5, 'moving_and_handling', 1, 28),
(81, 'mrsac_diff_awarness', 1, 29),
(82, 'nutrition_awarness', 1, 30),
(1, 'nvq_1', 1, 1),
(2, 'nvq_2', 1, 2),
(4, 'nvq_3', 1, 3),
(83, 'observation_skills_carers', 1, 31),
(84, 'ocd_awarness', 1, 32),
(85, 'palliative_care_awarness', 1, 33),
(10, 'parkinson_s_disease', 1, 34),
(86, 'person_centred_care', 1, 35),
(6, 'personal_care_for_the_elderly', 1, 36),
(87, 'personality_disorders', 1, 37),
(3, 'photo', 3, 1),
(88, 'physical_intervention', 1, 38),
(89, 'pressure_sore_awarness', 1, 39),
(90, 'risk_assessment_awarness', 1, 40),
(91, 'safeguarding_children', 1, 41),
(92, 'schizophrenia_awarness', 1, 42),
(93, 'self_harm', 1, 43),
(94, 'sova_awarness', 1, 44),
(98, 'standard_dbs_check', 4, 1),
(95, 'stress_awarness', 1, 45),
(9, 'stroke_awarness', 1, 46),
(58, 'text_motivation', 6, 1),
(59, 'text_personality', 6, 2),
(97, 'wound_assessment', 1, 48);

SET FOREIGN_KEY_CHECKS=1;

-- INSERT INTO tbl_document VALUES 
--   (21, 'crb_check', 4, 1),
--   (8, 'dementia', 1, 7),
--   (45, 'driving_licence', 5, 1),
--   (7, 'emergency_first_aid', 1, 6),
--   (34, 'enhanced_crb_check', 4, 2),
--   (11, 'food_hygiene', 1, 10),
--   (20, 'id', 2, 1),
--   (5, 'moving_and_handling', 1, 4),
--   (1, 'nvq_1', 1, 1),
--   (2, 'nvq_2', 1, 2),
--   (4, 'nvq_3', 1, 3),
--   (10, 'parkinson_s_disease', 1, 9),
--   (6, 'personal_care_for_the_elderly', 1, 5),
--   (3, 'photo', 3, 1),
--   (9, 'stroke_awarness', 1, 8),
--   (58, 'text_motivation', 6, 1),
--   (59, 'text_personality', 6, 2),
-- ;

-- INSERT INTO tbl_document (name, type) VALUES 
-- ('activities_coordinator', 1), 
-- ('autism_awarness', 1), 
-- ('care_planning', 1), 
-- ('challenging_behaviour', 1), 
-- ('child_protection', 1),
-- ('communication_record_keeping', 1),
-- ('confidentiality_awarness', 1),
-- ('conflict_management', 1),
-- ('coshh', 1),
-- ('diabetes_awarness', 1),
-- ('dols', 1),
-- ('eating_disorders', 1),
-- ('epilepsy_awarness', 1),
-- ('equality_diversity', 1),
-- ('fire_safety_awarness', 1),
-- ('health_safety_awarness', 1),
-- ('infection_control_awarness', 1),
-- ('lone_working_awarness', 1),
-- ('loss_bereavement', 1),
-- ('medication_awarness', 1),
-- ('mental_capacity_act_dols', 1),
-- ('mrsac_diff_awarness', 1),
-- ('nutrition_awarness', 1),
-- ('observation_skills_carers', 1),
-- ('ocd_awarness', 1),
-- ('palliative_care_awarness', 1),
-- ('person_centred_care', 1),
-- ('personality_disorders', 1),
-- ('physical_intervention', 1),
-- ('pressure_sore_awarness', 1),
-- ('risk_assessment_awarness', 1),
-- ('safeguarding_children', 1),
-- ('schizophrenia_awarness', 1),
-- ('self_harm', 1),
-- ('sova_awarness', 1),
-- ('stress_awarness', 1),
-- ('ageing_process', 1),
-- ('wound_assessment', 1),
-- ('standard_dbs_check', 4),
-- ('enhanced_dbs_check_adult', 4),
-- ('enhanced_dbs_check_children', 4),
-- ('enhanced_dbs_check_adult_child', 4),
-- ('enhanced_crb_check_children', 4),
-- ('enhanced_crb_check_adult_child', 4),
-- ('disclosure_scotland', 4)
-- ;

-- 
-- Dumping data for table tbl_document_requirement
--

INSERT INTO `tbl_document_requirement` (`id`, `id_document_type`, `requirement`) VALUES
(1, 3, 'nothing_head'),
(2, 3, 'one_person'),
(3, 3, 'no_nudity'),
(4, 3, 'does_not_match_id'),
(5, 6, 'too_short'),
(6, 2, 'does_not_match_id'),
(7, 4, 'does_not_match_id'),
(8, 5, 'does_not_match_id'),
(9, 6, 'no_phone_or_email'),
(10, 5, 'blur'),
(11, 1, 'blur'),
(12, 3, 'blur'),
(13, 2, 'blur'),
(14, 4, 'blur'),
(15, 3, 'not_portrait'),
(16, 1, 'does_not_match_id'),
(17, 6, 'does_not_answer_question'),
(18, 3, 'not_cropped_correctly'),
(19, 3, 'type_and_year_do_not_match'),
;


-- 
-- Dumping data for table tbl_prices
--
INSERT INTO tbl_prices VALUES 
  (1, 'live_in_daily', 1, 99.95, 'GBP', '2012-12-01 00:00:00', '9999-12-31 00:00:00'),
  (2, 'live_in_daily', 2, 85.00, 'GBP', '2012-12-01 00:00:00', '9999-12-31 00:00:00'),
  (3, 'live_in_daily', 1, 85.00, 'GBP', '2012-06-01 00:00:00', '2012-11-30 00:00:00'),
  (4, 'live_in_daily', 2, 75.00, 'GBP', '2012-06-01 00:00:00', '2012-11-30 00:00:00'),
  (7, 'live_in_daily_bank_holiday', 1, 99.00, 'GBP', '2012-12-01 00:00:00', '9999-12-31 00:00:00'),
  (8, 'live_in_daily_bank_holiday', 1, 99.00, 'GBP', '2012-12-01 00:00:00', '9999-12-31 00:00:00'),
  (9, 'live_in_daily_bank_holiday', 1, 99.00, 'GBP', '2012-06-01 00:00:00', '2012-11-30 00:00:00'),
  (10, 'live_in_daily_bank_holiday', 2, 95.00, 'GBP', '2012-06-01 00:00:00', '2012-11-30 00:00:00'),
  (11, 'hourly_price', 1, 9.95, 'GBP', '2013-04-01 18:15:36', '9999-12-31 00:00:00'),
  (12, 'hourly_price', 2, 9.50, 'GBP', '2013-04-01 18:16:30', '9999-12-31 00:00:00');
