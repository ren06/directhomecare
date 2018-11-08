-- phpMyAdmin SQL Dump
-- version 3.3.9
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jun 13, 2012 at 03:58 PM
-- Server version: 5.5.8
-- PHP Version: 5.3.5

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `directhomecare`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbl_document`
--

CREATE TABLE IF NOT EXISTS `tbl_document` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `type` int(3) NOT NULL,
  `order` int(2) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UK_tbl_document` (`name`,`order`,`type`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AVG_ROW_LENGTH=5461 AUTO_INCREMENT=12 ;

--
-- Dumping data for table `tbl_document`
--

INSERT INTO `tbl_document` (`id`, `name`, `type`, `order`) VALUES
(8, 'dementia', 1, 7),
(7, 'emergency_first_aid', 1, 6),
(11, 'food_hygiene', 1, 10),
(5, 'moving_and_handling', 1, 4),
(1, 'nvq_1', 1, 1),
(2, 'nvq_2', 1, 2),
(4, 'nvq_3', 1, 3),
(10, 'parkinson_s_disease', 1, 9),
(6, 'personal_care_for_the_elderly', 1, 5),
(3, 'photo', 3, 1),
(9, 'stroke_awarness', 1, 8);
