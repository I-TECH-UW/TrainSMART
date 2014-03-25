-- --------------------------------------------------------
-- Host:                         184.173.239.126
-- Server version:               5.5.23-55 - Percona Server (GPL), Release rel25.3, Revision 2
-- Server OS:                    Linux
-- HeidiSQL version:             7.0.0.4218
-- Date/time:                    2012-11-28 12:40:26
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

-- Dumping structure for table efelleos_dev.acl
CREATE TABLE IF NOT EXISTS `acl` (
  `id` varchar(32) NOT NULL DEFAULT '',
  `acl` varchar(32) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


-- Dumping structure for table efelleos_dev.addresses
CREATE TABLE IF NOT EXISTS `addresses` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `address1` varchar(150) NOT NULL DEFAULT '',
  `address2` varchar(150) NOT NULL DEFAULT '',
  `city` varchar(150) NOT NULL DEFAULT '',
  `postalcode` varchar(15) NOT NULL DEFAULT '',
  `state` varchar(50) NOT NULL DEFAULT '',
  `country` varchar(150) NOT NULL DEFAULT '',
  `id_addresstype` int(10) unsigned NOT NULL DEFAULT '0',
  `id_geog1` varchar(50) NOT NULL DEFAULT '0',
  `id_geog2` varchar(50) NOT NULL DEFAULT '0',
  `id_geog3` varchar(50) NOT NULL DEFAULT '0',
  `locationid` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx2` (`address2`,`id_geog1`,`id_addresstype`,`id_geog2`,`country`,`city`,`id_geog3`,`state`,`postalcode`,`address1`),
  KEY `idxlookups` (`locationid`,`id_geog3`,`id_geog2`,`id_geog1`,`id_addresstype`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- Data exporting was unselected.


-- Dumping structure for table efelleos_dev.age_range_option
CREATE TABLE IF NOT EXISTS `age_range_option` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `age_range_phrase` varchar(128) NOT NULL DEFAULT '',
  `is_default` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `modified_by` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  `timestamp_updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `timestamp_created` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name_unique` (`age_range_phrase`),
  KEY `created_by` (`created_by`),
  KEY `modified_by` (`modified_by`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

-- Data exporting was unselected.


-- Dumping structure for table efelleos_dev.cadres
CREATE TABLE IF NOT EXISTS `cadres` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cadrename` varchar(150) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `cadredescription` text COLLATE utf8_unicode_ci NOT NULL,
  `status` tinyint(3) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `idx2` (`status`,`cadrename`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Data exporting was unselected.


-- Dumping structure for table efelleos_dev.classes
CREATE TABLE IF NOT EXISTS `classes` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `classname` varchar(150) NOT NULL DEFAULT '',
  `startdate` varchar(20) NOT NULL DEFAULT '',
  `enddate` varchar(20) NOT NULL DEFAULT '',
  `instructorid` int(10) unsigned NOT NULL DEFAULT '0',
  `coursetypeid` int(10) unsigned NOT NULL DEFAULT '0',
  `coursetopic` varchar(150) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `idx2` (`coursetypeid`,`instructorid`,`enddate`,`startdate`,`classname`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- Data exporting was unselected.


-- Dumping structure for table efelleos_dev.cohort
CREATE TABLE IF NOT EXISTS `cohort` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cohortid` varchar(25) NOT NULL DEFAULT '',
  `cohortname` varchar(50) NOT NULL DEFAULT '',
  `startdate` date NOT NULL DEFAULT '0000-00-00',
  `graddate` date NOT NULL DEFAULT '0000-00-00',
  `degree` int(10) unsigned NOT NULL DEFAULT '0',
  `institutionid` int(10) unsigned NOT NULL DEFAULT '0',
  `cadreid` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx2` (`degree`,`cohortname`,`graddate`,`startdate`,`cohortid`,`institutionid`,`cadreid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- Data exporting was unselected.


-- Dumping structure for table efelleos_dev.comp
CREATE TABLE IF NOT EXISTS `comp` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `person` int(11) NOT NULL,
  `question` varchar(3) DEFAULT NULL,
  `option` varchar(128) DEFAULT NULL,
  `active` char(1) NOT NULL DEFAULT 'N',
  PRIMARY KEY (`id`),
  KEY `person` (`person`,`active`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Data exporting was unselected.


-- Dumping structure for table efelleos_dev.competencies
CREATE TABLE IF NOT EXISTS `competencies` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `competencyname` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `status` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `idx2` (`competencyname`,`status`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Data exporting was unselected.


-- Dumping structure for table efelleos_dev.competencies_answers
CREATE TABLE IF NOT EXISTS `competencies_answers` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `competencyid` int(10) unsigned NOT NULL DEFAULT '0',
  `personid` int(10) unsigned NOT NULL DEFAULT '0',
  `questionid` int(10) unsigned NOT NULL DEFAULT '0',
  `answer` char(1) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'F',
  `answertext` text COLLATE utf8_unicode_ci NOT NULL,
  `addedon` varchar(20) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `status` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `idx2` (`personid`,`competencyid`,`questionid`,`answer`,`addedon`,`status`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Data exporting was unselected.


-- Dumping structure for table efelleos_dev.competencies_questions
CREATE TABLE IF NOT EXISTS `competencies_questions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `competencyid` bigint(20) unsigned NOT NULL DEFAULT '0',
  `question` text COLLATE utf8_unicode_ci NOT NULL,
  `itemorder` int(11) NOT NULL DEFAULT '1',
  `itemtype` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'question',
  `status` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `idx2` (`itemtype`,`competencyid`,`itemorder`,`status`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Data exporting was unselected.


-- Dumping structure for table efelleos_dev.compres
CREATE TABLE IF NOT EXISTS `compres` (
  `SNo` bigint(20) NOT NULL AUTO_INCREMENT,
  `person` int(11) NOT NULL,
  `res` int(11) NOT NULL,
  `active` char(1) NOT NULL DEFAULT 'N',
  PRIMARY KEY (`SNo`),
  KEY `person` (`person`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Data exporting was unselected.


-- Dumping structure for table efelleos_dev.course
CREATE TABLE IF NOT EXISTS `course` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `coursename` varchar(255) NOT NULL DEFAULT '',
  `startdate` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `courselength` int(10) unsigned NOT NULL DEFAULT '0',
  `examid` int(10) unsigned NOT NULL DEFAULT '0',
  `examname` varchar(255) NOT NULL DEFAULT '0',
  `examdate` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `examscore` int(10) unsigned NOT NULL DEFAULT '0',
  `practicumid` int(10) unsigned NOT NULL DEFAULT '0',
  `practicumname` varchar(150) NOT NULL DEFAULT '0',
  `practicumhours` int(10) unsigned NOT NULL DEFAULT '0',
  `practicumcomplete` int(10) unsigned NOT NULL DEFAULT '0',
  `degreeid` int(10) unsigned NOT NULL DEFAULT '0',
  `comment` text NOT NULL,
  `customfield1` varchar(255) NOT NULL DEFAULT '0',
  `customfield2` varchar(255) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx2` (`startdate`,`examid`,`practicumid`,`courselength`,`degreeid`,`examdate`,`coursename`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- Data exporting was unselected.


-- Dumping structure for table efelleos_dev.evaluation
CREATE TABLE IF NOT EXISTS `evaluation` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uuid` char(36) DEFAULT NULL,
  `title` varchar(255) NOT NULL DEFAULT '',
  `modified_by` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  `timestamp_updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `timestamp_created` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uuid_idx` (`uuid`),
  KEY `modified_by` (`modified_by`),
  KEY `created_by` (`created_by`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


-- Dumping structure for table efelleos_dev.evaluation_question
CREATE TABLE IF NOT EXISTS `evaluation_question` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uuid` char(36) DEFAULT NULL,
  `evaluation_id` int(11) NOT NULL,
  `question_text` varchar(255) NOT NULL DEFAULT '',
  `question_type` enum('Likert','Text') NOT NULL DEFAULT 'Likert',
  `weight` int(11) DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  `timestamp_updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `timestamp_created` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uuid_idx` (`uuid`),
  KEY `modified_by` (`modified_by`),
  KEY `created_by` (`created_by`),
  KEY `evaluation_id` (`evaluation_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


-- Dumping structure for table efelleos_dev.evaluation_question_response
CREATE TABLE IF NOT EXISTS `evaluation_question_response` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uuid` char(36) DEFAULT NULL,
  `evaluation_response_id` int(11) DEFAULT NULL,
  `evaluation_question_id` int(11) NOT NULL,
  `value_text` varchar(1024) DEFAULT '',
  `value_int` int(11) DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  `timestamp_updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `timestamp_created` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uuid_idx` (`uuid`),
  KEY `modified_by` (`modified_by`),
  KEY `created_by` (`created_by`),
  KEY `evaluation_id` (`evaluation_response_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


-- Dumping structure for table efelleos_dev.evaluation_response
CREATE TABLE IF NOT EXISTS `evaluation_response` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uuid` char(36) DEFAULT NULL,
  `evaluation_to_training_id` int(11) NOT NULL,
  `modified_by` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  `timestamp_updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `timestamp_created` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uuid_idx` (`uuid`),
  KEY `modified_by` (`modified_by`),
  KEY `created_by` (`created_by`),
  KEY `evaluation_to_training_id` (`evaluation_to_training_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


-- Dumping structure for table efelleos_dev.evaluation_to_training
CREATE TABLE IF NOT EXISTS `evaluation_to_training` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uuid` char(36) DEFAULT NULL,
  `evaluation_id` int(11) NOT NULL,
  `training_id` int(11) NOT NULL,
  `created_by` int(11) DEFAULT NULL,
  `timestamp_created` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_idx` (`evaluation_id`,`training_id`),
  UNIQUE KEY `uuid_idx` (`uuid`),
  KEY `created_by` (`created_by`),
  KEY `training_id` (`training_id`),
  KEY `e_id` (`evaluation_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


-- Dumping structure for table efelleos_dev.exams
CREATE TABLE IF NOT EXISTS `exams` (
  `id` int(10) unsigned NOT NULL DEFAULT '0',
  `examname` varchar(150) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `examdate` varchar(20) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `examgrade` varchar(20) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `cohortid` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx2` (`cohortid`,`examgrade`,`examname`,`examdate`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Data exporting was unselected.


-- Dumping structure for table efelleos_dev.external_course
CREATE TABLE IF NOT EXISTS `external_course` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uuid` char(36) DEFAULT NULL,
  `person_id` int(11) unsigned NOT NULL,
  `title` varchar(128) NOT NULL,
  `training_location` varchar(128) DEFAULT '',
  `training_start_date` date DEFAULT NULL,
  `training_length_value` int(11) DEFAULT NULL,
  `training_length_interval` enum('day') NOT NULL DEFAULT 'day',
  `training_funder` varchar(128) DEFAULT '',
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  `timestamp_created` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uuid_idx` (`uuid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- Data exporting was unselected.


-- Dumping structure for table efelleos_dev.facility
CREATE TABLE IF NOT EXISTS `facility` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uuid` char(36) DEFAULT NULL,
  `facility_name` varchar(128) NOT NULL DEFAULT '',
  `address_1` varchar(128) NOT NULL DEFAULT '',
  `address_2` varchar(128) NOT NULL DEFAULT '',
  `location_id` int(10) unsigned DEFAULT NULL,
  `postal_code` varchar(20) DEFAULT '',
  `phone` varchar(32) DEFAULT '',
  `fax` varchar(32) DEFAULT '',
  `sponsor_option_id` int(11) DEFAULT NULL,
  `type_option_id` int(11) NOT NULL,
  `facility_comments` varchar(255) DEFAULT '',
  `modified_by` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  `timestamp_updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `timestamp_created` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uuid_idx` (`uuid`),
  KEY `created_by` (`created_by`),
  KEY `modified_by` (`modified_by`),
  KEY `sponsor_option_id` (`sponsor_option_id`),
  KEY `type_option_id` (`type_option_id`),
  KEY `facility_ibfk_5` (`location_id`),
  KEY `facility_name` (`facility_name`,`location_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Data exporting was unselected.



-- Dumping structure for table efelleos_dev.facility_sponsor_option
CREATE TABLE IF NOT EXISTS `facility_sponsor_option` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uuid` char(36) DEFAULT NULL,
  `facility_sponsor_phrase` varchar(128) NOT NULL DEFAULT '',
  `modified_by` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  `timestamp_updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `timestamp_created` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name_unique` (`facility_sponsor_phrase`),
  UNIQUE KEY `uuid_idx` (`uuid`),
  KEY `created_by` (`created_by`),
  KEY `modified_by` (`modified_by`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


-- Dumping structure for table efelleos_dev.facility_type_option
CREATE TABLE IF NOT EXISTS `facility_type_option` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uuid` char(36) DEFAULT NULL,
  `facility_type_phrase` varchar(128) NOT NULL DEFAULT '',
  `modified_by` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  `timestamp_updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `timestamp_created` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name_unique` (`facility_type_phrase`),
  UNIQUE KEY `uuid_idx` (`uuid`),
  KEY `modified_by` (`modified_by`),
  KEY `created_by` (`created_by`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


-- Dumping structure for table efelleos_dev.facs
CREATE TABLE IF NOT EXISTS `facs` (
  `sno` bigint(20) NOT NULL AUTO_INCREMENT,
  `person` int(11) NOT NULL,
  `facility` int(11) NOT NULL,
  `facstring` varchar(1024) NOT NULL,
  `active` char(1) NOT NULL DEFAULT 'N',
  PRIMARY KEY (`sno`),
  KEY `person` (`person`,`facility`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Data exporting was unselected.


-- Dumping structure for table efelleos_dev.file
CREATE TABLE IF NOT EXISTS `file` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` char(36) DEFAULT NULL,
  `parent_id` int(10) unsigned NOT NULL,
  `parent_table` varchar(255) NOT NULL DEFAULT '0',
  `filename` varchar(255) NOT NULL,
  `filemime` varchar(255) NOT NULL,
  `filesize` int(10) unsigned NOT NULL DEFAULT '0',
  `modified_by` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  `timestamp_updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `timestamp_created` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uuid_idx` (`uuid`),
  KEY `parent_key` (`parent_table`,`parent_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- Data exporting was unselected.


-- Dumping structure for table efelleos_dev.Helper
CREATE TABLE IF NOT EXISTS `Helper` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Data exporting was unselected.


-- Dumping structure for table efelleos_dev.institution
CREATE TABLE IF NOT EXISTS `institution` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `institutionname` varchar(150) NOT NULL DEFAULT '',
  `address1` varchar(150) NOT NULL DEFAULT '',
  `address2` varchar(150) NOT NULL DEFAULT '',
  `city` varchar(150) NOT NULL DEFAULT '',
  `postalcode` varchar(150) NOT NULL DEFAULT '',
  `phone` varchar(50) NOT NULL DEFAULT '',
  `fax` varchar(50) NOT NULL DEFAULT '',
  `type` int(10) NOT NULL DEFAULT '0',
  `sponsor` int(10) NOT NULL DEFAULT '0',
  `degrees` varchar(255) NOT NULL DEFAULT '',
  `degreetypeid` int(10) unsigned NOT NULL DEFAULT '0',
  `computercount` int(10) unsigned NOT NULL DEFAULT '0',
  `tutorcount` int(10) unsigned DEFAULT '0',
  `studentcount` int(10) unsigned DEFAULT '0',
  `dormcount` int(10) NOT NULL DEFAULT '0',
  `bedcount` int(10) NOT NULL DEFAULT '0',
  `hasdormitories` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `tutorhousing` int(10) unsigned NOT NULL DEFAULT '0',
  `tutorhouses` int(10) NOT NULL DEFAULT '0',
  `yearfounded` int(10) unsigned NOT NULL DEFAULT '0',
  `comments` text NOT NULL,
  `customfield1` varchar(255) NOT NULL DEFAULT '',
  `customfield2` varchar(255) NOT NULL DEFAULT '',
  `geography1` int(10) unsigned NOT NULL DEFAULT '0',
  `geography2` int(10) unsigned NOT NULL DEFAULT '0',
  `geography3` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx2` (`degreetypeid`,`hasdormitories`,`computercount`,`studentcount`,`tutorcount`,`dormcount`,`tutorhousing`,`yearfounded`,`tutorhouses`,`bedcount`,`institutionname`,`geography1`,`geography2`,`geography3`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- Data exporting was unselected.


-- Dumping structure for table efelleos_dev.jobtitles
CREATE TABLE IF NOT EXISTS `jobtitles` (
  `Title` varchar(45) DEFAULT NULL,
  `Option` varchar(12) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


-- Dumping structure for table efelleos_dev.licenses
CREATE TABLE IF NOT EXISTS `licenses` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `licensename` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `licensedate` varchar(20) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `cohortid` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `licensename` (`licensename`),
  KEY `licensedate` (`licensedate`),
  KEY `cohortid` (`cohortid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Data exporting was unselected.


-- Dumping structure for table efelleos_dev.link_cadre_institution
CREATE TABLE IF NOT EXISTS `link_cadre_institution` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_cadre` int(10) unsigned NOT NULL DEFAULT '0',
  `id_institution` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx2` (`id_institution`,`id_cadre`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Data exporting was unselected.


-- Dumping structure for table efelleos_dev.link_cadre_tutor
CREATE TABLE IF NOT EXISTS `link_cadre_tutor` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_cadre` int(10) unsigned NOT NULL DEFAULT '0',
  `id_tutor` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx2` (`id_tutor`,`id_cadre`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Data exporting was unselected.


-- Dumping structure for table efelleos_dev.link_cohorts_classes
CREATE TABLE IF NOT EXISTS `link_cohorts_classes` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cohortid` int(10) unsigned NOT NULL DEFAULT '0',
  `classid` int(10) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `idx2` (`cohortid`,`classid`,`status`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Data exporting was unselected.


-- Dumping structure for table efelleos_dev.link_facility_addresses
CREATE TABLE IF NOT EXISTS `link_facility_addresses` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_facility` int(10) unsigned NOT NULL DEFAULT '0',
  `id_address` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx2` (`id_facility`,`id_address`),
  KEY `FK_link_facility_addresses_addresses` (`id_address`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- Data exporting was unselected.


-- Dumping structure for table efelleos_dev.link_institution_address
CREATE TABLE IF NOT EXISTS `link_institution_address` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_institution` int(10) unsigned NOT NULL DEFAULT '0',
  `id_address` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx2` (`id_institution`,`id_address`),
  KEY `FK_link_institution_address_addresses` (`id_address`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- Data exporting was unselected.


-- Dumping structure for table efelleos_dev.link_institution_degrees
CREATE TABLE IF NOT EXISTS `link_institution_degrees` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_institution` int(10) unsigned NOT NULL DEFAULT '0',
  `id_degree` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx2` (`id_institution`,`id_degree`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- Data exporting was unselected.


-- Dumping structure for table efelleos_dev.link_institution_institutiontype
CREATE TABLE IF NOT EXISTS `link_institution_institutiontype` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_institution` int(10) unsigned NOT NULL DEFAULT '0',
  `id_institutiontype` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx2` (`id_institution`,`id_institutiontype`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Data exporting was unselected.


-- Dumping structure for table efelleos_dev.link_occupational_competencies
CREATE TABLE IF NOT EXISTS `link_occupational_competencies` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `competencyid` int(10) unsigned NOT NULL DEFAULT '0',
  `occupationalid` int(10) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `idx2` (`competencyid`,`occupationalid`,`status`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Data exporting was unselected.


-- Dumping structure for table efelleos_dev.link_person_training
CREATE TABLE IF NOT EXISTS `link_person_training` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `personid` int(10) unsigned NOT NULL DEFAULT '0',
  `trainingid` int(10) unsigned NOT NULL DEFAULT '0',
  `year` varchar(10) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `institution` varchar(150) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `othername` varchar(150) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `idx2` (`personid`,`trainingid`,`year`),
  KEY `institution` (`institution`),
  KEY `othername` (`othername`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Data exporting was unselected.


-- Dumping structure for table efelleos_dev.link_qualification_competency
CREATE TABLE IF NOT EXISTS `link_qualification_competency` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `competencyid` int(10) unsigned NOT NULL DEFAULT '0',
  `qualificationid` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx2` (`competencyid`,`qualificationid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Data exporting was unselected.


-- Dumping structure for table efelleos_dev.link_student_addresses
CREATE TABLE IF NOT EXISTS `link_student_addresses` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_student` int(10) unsigned NOT NULL DEFAULT '0',
  `id_address` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx2` (`id_student`,`id_address`),
  KEY `FK_link_student_addresses_addresses` (`id_address`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- Data exporting was unselected.


-- Dumping structure for table efelleos_dev.link_student_classes
CREATE TABLE IF NOT EXISTS `link_student_classes` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `studentid` int(10) unsigned NOT NULL DEFAULT '0',
  `classid` int(10) unsigned NOT NULL DEFAULT '0',
  `cohortid` int(10) unsigned NOT NULL DEFAULT '0',
  `linkclasscohortid` int(10) unsigned NOT NULL DEFAULT '0',
  `grade` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `idx2` (`studentid`,`classid`,`cohortid`,`linkclasscohortid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Data exporting was unselected.


-- Dumping structure for table efelleos_dev.link_student_cohort
CREATE TABLE IF NOT EXISTS `link_student_cohort` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_student` int(10) unsigned NOT NULL DEFAULT '0',
  `id_cohort` int(10) unsigned NOT NULL DEFAULT '0',
  `joindate` date NOT NULL DEFAULT '0000-00-00',
  `joinreason` int(10) unsigned NOT NULL DEFAULT '0',
  `dropdate` date NOT NULL DEFAULT '0000-00-00',
  `dropreason` int(10) unsigned NOT NULL DEFAULT '0',
  `isgraduated` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `FK_link_student_cohort_cohort` (`id_cohort`),
  KEY `datekeys` (`joindate`,`dropdate`),
  KEY `idx2` (`isgraduated`,`id_student`,`id_cohort`,`joinreason`,`dropreason`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- Data exporting was unselected.


-- Dumping structure for table efelleos_dev.link_student_contacts
CREATE TABLE IF NOT EXISTS `link_student_contacts` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_student` int(10) unsigned NOT NULL DEFAULT '0',
  `id_contact` int(10) unsigned NOT NULL DEFAULT '0',
  `contactvalue` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `idx2` (`id_student`,`id_contact`),
  KEY `FK_link_student_contacts_lookup_contacts` (`id_contact`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- Data exporting was unselected.


-- Dumping structure for table efelleos_dev.link_student_facility
CREATE TABLE IF NOT EXISTS `link_student_facility` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_student` int(10) unsigned NOT NULL DEFAULT '0',
  `id_facility` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx2` (`id_student`,`id_facility`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- Data exporting was unselected.


-- Dumping structure for table efelleos_dev.link_student_funding
CREATE TABLE IF NOT EXISTS `link_student_funding` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `studentid` int(10) unsigned NOT NULL DEFAULT '0',
  `fundingsource` int(10) unsigned NOT NULL DEFAULT '0',
  `fundingamount` decimal(10,2) unsigned NOT NULL DEFAULT '0.00',
  PRIMARY KEY (`id`),
  KEY `idx2` (`studentid`,`fundingsource`,`fundingamount`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- Data exporting was unselected.


-- Dumping structure for table efelleos_dev.link_student_institution
CREATE TABLE IF NOT EXISTS `link_student_institution` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_student` int(10) unsigned NOT NULL DEFAULT '0',
  `id_institution` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx2` (`id_student`,`id_institution`),
  KEY `FK_link_student_institution_institution` (`id_institution`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- Data exporting was unselected.


-- Dumping structure for table efelleos_dev.link_student_licenses
CREATE TABLE IF NOT EXISTS `link_student_licenses` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `studentid` int(10) unsigned NOT NULL DEFAULT '0',
  `licenseid` int(10) unsigned NOT NULL DEFAULT '0',
  `cohortid` int(10) unsigned NOT NULL DEFAULT '0',
  `linkclasslicenseid` int(10) unsigned NOT NULL DEFAULT '0',
  `grade` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `idx2` (`studentid`,`licenseid`,`cohortid`,`linkclasslicenseid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Data exporting was unselected.


-- Dumping structure for table efelleos_dev.link_student_practicums
CREATE TABLE IF NOT EXISTS `link_student_practicums` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `studentid` int(10) unsigned NOT NULL DEFAULT '0',
  `practicumid` int(10) unsigned NOT NULL DEFAULT '0',
  `cohortid` int(10) unsigned NOT NULL DEFAULT '0',
  `linkcohortpracticumid` int(10) unsigned NOT NULL DEFAULT '0',
  `hourscompleted` decimal(10,2) NOT NULL DEFAULT '0.00',
  `grade` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `idx2` (`studentid`,`practicumid`,`cohortid`,`linkcohortpracticumid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Data exporting was unselected.


-- Dumping structure for table efelleos_dev.link_tutor_addresses
CREATE TABLE IF NOT EXISTS `link_tutor_addresses` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_tutor` int(10) unsigned NOT NULL DEFAULT '0',
  `id_address` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx2` (`id_tutor`,`id_address`),
  KEY `FK_link_tutor_addresses_addresses` (`id_address`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- Data exporting was unselected.


-- Dumping structure for table efelleos_dev.link_tutor_contacts
CREATE TABLE IF NOT EXISTS `link_tutor_contacts` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_tutor` int(10) unsigned NOT NULL DEFAULT '0',
  `id_contact` int(10) unsigned NOT NULL DEFAULT '0',
  `contactvalue` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `idx2` (`id_contact`,`id_tutor`),
  KEY `FK_link_tutor_contacts_tutor` (`id_tutor`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- Data exporting was unselected.


-- Dumping structure for table efelleos_dev.link_tutor_institution
CREATE TABLE IF NOT EXISTS `link_tutor_institution` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_tutor` int(10) unsigned NOT NULL DEFAULT '0',
  `id_institution` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx2` (`id_tutor`,`id_institution`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Data exporting was unselected.


-- Dumping structure for table efelleos_dev.link_tutor_languages
CREATE TABLE IF NOT EXISTS `link_tutor_languages` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_tutor` int(10) unsigned NOT NULL DEFAULT '0',
  `id_language` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx2` (`id_tutor`,`id_language`),
  KEY `FK_link_tutor_languages_lookup_languages` (`id_language`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- Data exporting was unselected.


-- Dumping structure for table efelleos_dev.link_tutor_tutortype
CREATE TABLE IF NOT EXISTS `link_tutor_tutortype` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_tutor` int(10) unsigned NOT NULL DEFAULT '0',
  `id_tutortype` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx2` (`id_tutor`,`id_tutortype`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Data exporting was unselected.


-- Dumping structure for table efelleos_dev.link_user_institution
CREATE TABLE IF NOT EXISTS `link_user_institution` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  `institutionid` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx2` (`userid`,`institutionid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Data exporting was unselected.


-- Dumping structure for table efelleos_dev.location
CREATE TABLE IF NOT EXISTS `location` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` char(36) DEFAULT NULL,
  `location_name` varchar(128) NOT NULL,
  `parent_id` int(10) unsigned DEFAULT NULL,
  `tier` smallint(5) unsigned NOT NULL,
  `is_default` tinyint(1) NOT NULL DEFAULT '0',
  `modified_by` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  `timestamp_updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `timestamp_created` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uuid_idx` (`uuid`),
  KEY `parent_id` (`parent_id`),
  KEY `modified_by` (`modified_by`),
  KEY `created_by` (`created_by`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


-- Dumping structure for table efelleos_dev.location_city
CREATE TABLE IF NOT EXISTS `location_city` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `city_name` varchar(128) NOT NULL DEFAULT '',
  `uuid` char(38) DEFAULT '',
  `parent_district_id` int(11) DEFAULT NULL,
  `parent_province_id` int(11) DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  `timestamp_updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `timestamp_created` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `modified_by` (`modified_by`),
  KEY `created_by` (`created_by`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


-- Dumping structure for table efelleos_dev.location_district
CREATE TABLE IF NOT EXISTS `location_district` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `district_name` varchar(128) NOT NULL DEFAULT '',
  `is_default` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `uuid` char(38) NOT NULL DEFAULT '',
  `parent_province_id` int(11) DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  `timestamp_updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `timestamp_created` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `modified_by` (`modified_by`),
  KEY `created_by` (`created_by`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


-- Dumping structure for table efelleos_dev.location_province
CREATE TABLE IF NOT EXISTS `location_province` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `province_name` varchar(255) NOT NULL DEFAULT '',
  `is_default` tinyint(1) NOT NULL DEFAULT '0',
  `uuid` char(38) NOT NULL DEFAULT '',
  `modified_by` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  `timestamp_updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `timestamp_created` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `modified_by` (`modified_by`),
  KEY `created_by` (`created_by`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


-- Dumping structure for table efelleos_dev.lookup_addresstype
CREATE TABLE IF NOT EXISTS `lookup_addresstype` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `addresstypename` varchar(50) NOT NULL DEFAULT '',
  `addresstypeorder` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx2` (`addresstypename`,`addresstypeorder`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- Data exporting was unselected.


-- Dumping structure for table efelleos_dev.lookup_contacts
CREATE TABLE IF NOT EXISTS `lookup_contacts` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `contactname` varchar(50) NOT NULL DEFAULT '',
  `contactorder` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx2` (`contactorder`,`contactname`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- Data exporting was unselected.


-- Dumping structure for table efelleos_dev.lookup_coursetype
CREATE TABLE IF NOT EXISTS `lookup_coursetype` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `coursetype` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `idx2` (`coursetype`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- Data exporting was unselected.


-- Dumping structure for table efelleos_dev.lookup_degrees
CREATE TABLE IF NOT EXISTS `lookup_degrees` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `degree` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `idx2` (`degree`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Data exporting was unselected.


-- Dumping structure for table efelleos_dev.lookup_degreetypes
CREATE TABLE IF NOT EXISTS `lookup_degreetypes` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(150) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `idx2` (`title`,`status`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Data exporting was unselected.


-- Dumping structure for table efelleos_dev.lookup_facilitytype
CREATE TABLE IF NOT EXISTS `lookup_facilitytype` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `facilitytypename` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `idx2` (`facilitytypename`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- Data exporting was unselected.


-- Dumping structure for table efelleos_dev.lookup_fundingsources
CREATE TABLE IF NOT EXISTS `lookup_fundingsources` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `fundingname` varchar(150) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `fundingnote` text COLLATE utf8_unicode_ci NOT NULL,
  `status` tinyint(3) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `idx2` (`status`,`fundingname`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Data exporting was unselected.


-- Dumping structure for table efelleos_dev.lookup_gender
CREATE TABLE IF NOT EXISTS `lookup_gender` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `gendername` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- Data exporting was unselected.


-- Dumping structure for table efelleos_dev.lookup_geog
CREATE TABLE IF NOT EXISTS `lookup_geog` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `geogname` varchar(50) NOT NULL DEFAULT '',
  `geogparent` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx2` (`geogname`,`geogparent`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- Data exporting was unselected.


-- Dumping structure for table efelleos_dev.lookup_institutiontype
CREATE TABLE IF NOT EXISTS `lookup_institutiontype` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `typename` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `idx2` (`typename`,`status`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Data exporting was unselected.


-- Dumping structure for table efelleos_dev.lookup_languages
CREATE TABLE IF NOT EXISTS `lookup_languages` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `language` varchar(150) NOT NULL DEFAULT '',
  `abbreviation` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `idx2` (`abbreviation`,`language`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- Data exporting was unselected.


-- Dumping structure for table efelleos_dev.lookup_nationalities
CREATE TABLE IF NOT EXISTS `lookup_nationalities` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nationality` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `idx2` (`nationality`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Data exporting was unselected.


-- Dumping structure for table efelleos_dev.lookup_reasons
CREATE TABLE IF NOT EXISTS `lookup_reasons` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `reason` text COLLATE utf8_unicode_ci NOT NULL,
  `reasontype` enum('join','drop') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'join',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Data exporting was unselected.


-- Dumping structure for table efelleos_dev.lookup_skillsmart
CREATE TABLE IF NOT EXISTS `lookup_skillsmart` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `lookupgroup` varchar(150) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `lookupvalue` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `status` int(10) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `idx2` (`status`),
  KEY `idx3` (`lookupgroup`),
  KEY `idx4` (`lookupvalue`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Data exporting was unselected.


-- Dumping structure for table efelleos_dev.lookup_sponsors
CREATE TABLE IF NOT EXISTS `lookup_sponsors` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `sponsorname` varchar(150) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `notes` text COLLATE utf8_unicode_ci NOT NULL,
  `status` tinyint(3) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `sponsorname` (`sponsorname`,`status`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Data exporting was unselected.


-- Dumping structure for table efelleos_dev.lookup_studenttype
CREATE TABLE IF NOT EXISTS `lookup_studenttype` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `studenttype` varchar(150) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `idx2` (`studenttype`,`status`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Data exporting was unselected.


-- Dumping structure for table efelleos_dev.lookup_tutortype
CREATE TABLE IF NOT EXISTS `lookup_tutortype` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `typename` varchar(150) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `typename` (`typename`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Data exporting was unselected.


-- Dumping structure for table efelleos_dev.organizer_partners
CREATE TABLE IF NOT EXISTS `organizer_partners` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uuid` char(36) DEFAULT NULL,
  `organizer_id` int(11) NOT NULL,
  `partner1_name` varchar(64) DEFAULT '',
  `subpartner` varchar(64) DEFAULT '',
  `mechanism_id` varchar(32) DEFAULT '',
  `funder_name` varchar(64) DEFAULT '',
  `funder_id` varchar(32) DEFAULT '',
  `timestamp_created` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_by` int(11) DEFAULT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `mechanism_id` (`mechanism_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


-- Dumping structure for table efelleos_dev.person
CREATE TABLE IF NOT EXISTS `person` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uuid` char(36) DEFAULT NULL,
  `title_option_id` int(11) DEFAULT NULL,
  `first_name` varchar(32) NOT NULL DEFAULT '',
  `middle_name` varchar(32) DEFAULT '',
  `last_name` varchar(32) NOT NULL DEFAULT '',
  `suffix_option_id` int(11) DEFAULT NULL,
  `national_id` varchar(64) DEFAULT '',
  `file_number` varchar(64) DEFAULT NULL,
  `birthdate` date DEFAULT NULL,
  `gender` enum('male','female','na') NOT NULL DEFAULT 'na',
  `facility_id` int(11) NOT NULL,
  `phone_work` varchar(32) DEFAULT '',
  `phone_mobile` varchar(32) DEFAULT '',
  `fax` varchar(32) DEFAULT '',
  `phone_home` varchar(32) DEFAULT '',
  `email` varchar(64) DEFAULT '',
  `email_secondary` varchar(64) DEFAULT '',
  `primary_qualification_option_id` int(11) NOT NULL,
  `primary_responsibility_option_id` int(11) DEFAULT NULL,
  `secondary_responsibility_option_id` int(11) DEFAULT NULL,
  `comments` text,
  `person_custom_1_option_id` int(11) DEFAULT NULL,
  `person_custom_2_option_id` int(11) DEFAULT NULL,
  `home_address_1` varchar(128) DEFAULT '',
  `home_address_2` varchar(128) DEFAULT '',
  `home_city` varchar(255) NOT NULL DEFAULT '',
  `home_location_id` int(10) unsigned DEFAULT NULL,
  `home_postal_code` int(11) DEFAULT NULL,
  `active` enum('active','inactive','deceased') NOT NULL DEFAULT 'active',
  `timestamp_created` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `timestamp_updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` int(11) DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  `me_responsibility` varchar(255) DEFAULT NULL,
  `highest_edu_level_option_id` int(11) DEFAULT NULL,
  `attend_reason_option_id` int(11) DEFAULT NULL,
  `attend_reason_other` varchar(255) DEFAULT NULL,
  `highest_level_option_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uuid_idx` (`uuid`),
  KEY `modified_by` (`modified_by`),
  KEY `created_by` (`created_by`),
  KEY `facility_id` (`facility_id`),
  KEY `home_location_ibfk_9` (`home_location_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


-- Dumping structure for table efelleos_dev.person_active_trainer_option
CREATE TABLE IF NOT EXISTS `person_active_trainer_option` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uuid` char(36) DEFAULT NULL,
  `active_trainer_phrase` varchar(128) NOT NULL DEFAULT '',
  `modified_by` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  `timestamp_updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `timestamp_created` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name_unique` (`active_trainer_phrase`),
  UNIQUE KEY `uuid_idx` (`uuid`),
  KEY `modified_by` (`modified_by`),
  KEY `created_by` (`created_by`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- Data exporting was unselected.


-- Dumping structure for table efelleos_dev.person_attend_reason_option
CREATE TABLE IF NOT EXISTS `person_attend_reason_option` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uuid` char(36) DEFAULT NULL,
  `attend_reason_phrase` varchar(128) NOT NULL DEFAULT '',
  `modified_by` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  `timestamp_updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `timestamp_created` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name_unique` (`attend_reason_phrase`),
  UNIQUE KEY `uuid_idx` (`uuid`),
  KEY `created_by` (`created_by`),
  KEY `modified_by` (`modified_by`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


-- Dumping structure for table efelleos_dev.person_custom_1_option
CREATE TABLE IF NOT EXISTS `person_custom_1_option` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uuid` char(36) DEFAULT NULL,
  `custom1_phrase` varchar(128) NOT NULL DEFAULT '',
  `modified_by` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  `timestamp_updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `timestamp_created` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name_unique` (`custom1_phrase`),
  UNIQUE KEY `uuid_idx` (`uuid`),
  KEY `modified_by` (`modified_by`),
  KEY `created_by` (`created_by`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


-- Dumping structure for table efelleos_dev.person_custom_2_option
CREATE TABLE IF NOT EXISTS `person_custom_2_option` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uuid` char(36) DEFAULT NULL,
  `custom2_phrase` varchar(128) NOT NULL DEFAULT '',
  `modified_by` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  `timestamp_updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `timestamp_created` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name_unique` (`custom2_phrase`),
  UNIQUE KEY `uuid_idx` (`uuid`),
  KEY `created_by` (`created_by`),
  KEY `modified_by` (`modified_by`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


-- Dumping structure for table efelleos_dev.person_education_level_option
CREATE TABLE IF NOT EXISTS `person_education_level_option` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uuid` char(36) DEFAULT NULL,
  `education_level_phrase` varchar(128) NOT NULL DEFAULT '',
  `modified_by` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  `timestamp_updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `timestamp_created` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name_unique` (`education_level_phrase`),
  UNIQUE KEY `uuid_idx` (`uuid`),
  KEY `created_by` (`created_by`),
  KEY `modified_by` (`modified_by`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


-- Dumping structure for table efelleos_dev.person_history
CREATE TABLE IF NOT EXISTS `person_history` (
  `vid` int(11) NOT NULL AUTO_INCREMENT,
  `person_id` int(11) NOT NULL,
  `uuid` char(36) DEFAULT NULL,
  `title_option_id` int(11) DEFAULT NULL,
  `first_name` varchar(32) NOT NULL DEFAULT '',
  `middle_name` varchar(32) DEFAULT '',
  `last_name` varchar(32) NOT NULL DEFAULT '',
  `suffix_option_id` int(11) DEFAULT NULL,
  `national_id` varchar(64) DEFAULT '',
  `file_number` varchar(64) DEFAULT NULL,
  `birthdate` date DEFAULT NULL,
  `gender` enum('male','female','na') NOT NULL DEFAULT 'na',
  `facility_id` int(11) NOT NULL,
  `phone_work` varchar(32) DEFAULT '',
  `phone_mobile` varchar(32) DEFAULT '',
  `fax` varchar(32) DEFAULT '',
  `phone_home` varchar(32) DEFAULT '',
  `email` varchar(64) DEFAULT '',
  `primary_qualification_option_id` int(11) NOT NULL,
  `primary_responsibility_option_id` int(11) DEFAULT '0',
  `secondary_responsibility_option_id` int(11) DEFAULT '0',
  `comments` varchar(255) DEFAULT '',
  `person_custom_1_option_id` int(11) DEFAULT NULL,
  `person_custom_2_option_id` int(11) DEFAULT NULL,
  `active` enum('active','inactive','deceased') NOT NULL DEFAULT 'active',
  `timestamp_created` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_by` int(11) DEFAULT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  `home_address_1` varchar(128) DEFAULT '',
  `home_address_2` varchar(128) DEFAULT '',
  `home_location_id` int(11) DEFAULT NULL,
  `home_postal_code` int(11) DEFAULT NULL,
  `email_secondary` varchar(64) DEFAULT '',
  PRIMARY KEY (`vid`),
  KEY `modified_by` (`modified_by`),
  KEY `facility_id` (`facility_id`),
  KEY `person_id` (`person_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- Data exporting was unselected.


-- Dumping structure for table efelleos_dev.person_primary_responsibility_option
CREATE TABLE IF NOT EXISTS `person_primary_responsibility_option` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uuid` char(36) DEFAULT NULL,
  `responsibility_phrase` varchar(128) NOT NULL DEFAULT '',
  `modified_by` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  `timestamp_updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `timestamp_created` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name_unique` (`responsibility_phrase`),
  UNIQUE KEY `uuid_idx` (`uuid`),
  KEY `created_by` (`created_by`),
  KEY `modified_by` (`modified_by`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


-- Dumping structure for table efelleos_dev.person_qualification_option
CREATE TABLE IF NOT EXISTS `person_qualification_option` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uuid` char(36) DEFAULT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `qualification_phrase` varchar(128) NOT NULL DEFAULT '',
  `is_default` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `modified_by` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  `timestamp_updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `timestamp_created` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name_unique` (`qualification_phrase`),
  UNIQUE KEY `uuid_idx` (`uuid`),
  KEY `modified_by` (`modified_by`),
  KEY `created_by` (`created_by`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


-- Dumping structure for table efelleos_dev.person_responsibility_option
CREATE TABLE IF NOT EXISTS `person_responsibility_option` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `responsibility_phrase` varchar(128) NOT NULL DEFAULT '',
  `modified_by` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  `timestamp_updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `timestamp_created` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name_unique` (`responsibility_phrase`),
  KEY `created_by` (`created_by`),
  KEY `modified_by` (`modified_by`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


-- Dumping structure for table efelleos_dev.person_secondary_responsibility_option
CREATE TABLE IF NOT EXISTS `person_secondary_responsibility_option` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uuid` char(36) DEFAULT NULL,
  `responsibility_phrase` varchar(128) NOT NULL,
  `modified_by` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  `timestamp_updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `timestamp_created` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name_unique` (`responsibility_phrase`),
  UNIQUE KEY `uuid_idx` (`uuid`),
  KEY `created_by` (`created_by`),
  KEY `modified_by` (`modified_by`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


-- Dumping structure for table efelleos_dev.person_suffix_option
CREATE TABLE IF NOT EXISTS `person_suffix_option` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uuid` char(36) DEFAULT NULL,
  `suffix_phrase` varchar(128) NOT NULL DEFAULT '',
  `modified_by` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  `timestamp_updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `timestamp_created` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name_unique` (`suffix_phrase`),
  UNIQUE KEY `uuid_idx` (`uuid`),
  KEY `created_by` (`created_by`),
  KEY `modified_by` (`modified_by`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- Data exporting was unselected.


-- Dumping structure for table efelleos_dev.person_title_option
CREATE TABLE IF NOT EXISTS `person_title_option` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uuid` char(36) DEFAULT NULL,
  `title_phrase` varchar(128) NOT NULL DEFAULT '',
  `modified_by` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  `timestamp_updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `timestamp_created` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name_unique` (`title_phrase`),
  UNIQUE KEY `uuid_idx` (`uuid`),
  KEY `created_by` (`created_by`),
  KEY `modified_by` (`modified_by`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- Data exporting was unselected.


-- Dumping structure for table efelleos_dev.person_to_training
CREATE TABLE IF NOT EXISTS `person_to_training` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uuid` char(36) DEFAULT NULL,
  `person_id` int(11) NOT NULL,
  `training_id` int(11) NOT NULL,
  `created_by` int(11) DEFAULT NULL,
  `timestamp_created` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_idx` (`person_id`,`training_id`),
  UNIQUE KEY `training_person_uniq_2` (`training_id`,`person_id`),
  UNIQUE KEY `uuid_idx` (`uuid`),
  KEY `created_by` (`created_by`),
  KEY `person_id` (`person_id`),
  KEY `training_id` (`training_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


-- Dumping structure for table efelleos_dev.person_to_training_topic_option
CREATE TABLE IF NOT EXISTS `person_to_training_topic_option` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uuid` char(36) DEFAULT NULL,
  `person_id` int(11) NOT NULL,
  `training_topic_option_id` int(11) NOT NULL,
  `created_by` int(11) DEFAULT NULL,
  `timestamp_created` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_idx` (`person_id`,`training_topic_option_id`),
  UNIQUE KEY `uuid_idx` (`uuid`),
  KEY `created_by` (`created_by`),
  KEY `person_id` (`person_id`),
  KEY `training_topic_option_id` (`training_topic_option_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


-- Dumping structure for table efelleos_dev.practicum
CREATE TABLE IF NOT EXISTS `practicum` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `practicumname` varchar(150) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `practicumdate` varchar(10) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `facilityid` int(10) unsigned NOT NULL DEFAULT '0',
  `advisorid` int(10) unsigned NOT NULL DEFAULT '0',
  `hoursrequired` decimal(10,2) unsigned NOT NULL DEFAULT '0.00',
  `cohortid` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `textindex` (`practicumname`,`practicumdate`),
  KEY `numberindex` (`advisorid`,`hoursrequired`,`cohortid`,`facilityid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Data exporting was unselected.


-- Dumping structure for table efelleos_dev.score
CREATE TABLE IF NOT EXISTS `score` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uuid` char(36) DEFAULT NULL,
  `person_to_training_id` int(11) NOT NULL,
  `training_date` date NOT NULL,
  `score_value` int(11) NOT NULL,
  `score_label` varchar(255) NOT NULL,
  `modified_by` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  `timestamp_updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `timestamp_created` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uuid_idx` (`uuid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


-- Dumping structure for table efelleos_dev.security
CREATE TABLE IF NOT EXISTS `security` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `studentid` int(10) unsigned NOT NULL DEFAULT '0',
  `haspreservice` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `hasinstitution` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `hasinservice` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx2` (`hasinservice`,`hasinstitution`,`haspreservice`,`studentid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- Data exporting was unselected.


-- Dumping structure for table efelleos_dev.sheet1
CREATE TABLE IF NOT EXISTS `sheet1` (
  `Province` varchar(7) DEFAULT NULL,
  `Persal_No` int(8) DEFAULT NULL,
  `First_Name` varchar(16) DEFAULT NULL,
  `Middle_Name` varchar(25) DEFAULT NULL,
  `Last_Name` varchar(19) DEFAULT NULL,
  `RACE` varchar(8) DEFAULT NULL,
  `GENDER` varchar(6) DEFAULT NULL,
  `Disabled` varchar(3) DEFAULT NULL,
  `Job_Title` varchar(31) DEFAULT NULL,
  `District` varchar(12) DEFAULT NULL,
  `Sub_District` varchar(18) DEFAULT NULL,
  `Facility_Name` varchar(45) DEFAULT NULL,
  `Facility_Type` varchar(25) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


-- Dumping structure for table efelleos_dev.student
CREATE TABLE IF NOT EXISTS `student` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `personid` int(10) unsigned NOT NULL DEFAULT '0',
  `nationalityid` int(10) unsigned NOT NULL DEFAULT '0',
  `cadre` int(25) NOT NULL DEFAULT '0',
  `yearofstudy` int(10) unsigned NOT NULL DEFAULT '0',
  `comments` varchar(255) NOT NULL DEFAULT '',
  `geog1` int(10) unsigned NOT NULL DEFAULT '0',
  `geog2` int(10) unsigned NOT NULL DEFAULT '0',
  `geog3` int(10) unsigned NOT NULL DEFAULT '0',
  `isgraduated` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `studentid` varchar(50) DEFAULT '',
  `studenttype` int(10) unsigned NOT NULL DEFAULT '0',
  `advisorid` int(10) unsigned NOT NULL DEFAULT '0',
  `postgeo1` varchar(50) NOT NULL DEFAULT '0',
  `postgeo2` varchar(50) NOT NULL DEFAULT '0',
  `postgeo3` varchar(50) NOT NULL DEFAULT '0',
  `postaddress1` varchar(255) NOT NULL DEFAULT '',
  `postfacilityname` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `idx2` (`nationalityid`,`advisorid`,`personid`,`studentid`,`cadre`,`geog1`,`geog2`,`geog3`,`isgraduated`,`postgeo1`,`postgeo2`,`postgeo3`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- Data exporting was unselected.


-- Dumping structure for table efelleos_dev.syncalias
CREATE TABLE IF NOT EXISTS `syncalias` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `syncfile_id` int(11) unsigned DEFAULT NULL,
  `item_type` varchar(255) DEFAULT NULL,
  `left_id` int(11) unsigned NOT NULL,
  `left_uuid` char(36) NOT NULL DEFAULT '',
  `right_id` int(11) unsigned NOT NULL,
  `right_uuid` char(36) NOT NULL DEFAULT '',
  `created_by` int(11) DEFAULT NULL,
  `timestamp_created` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `syncfile_id` (`syncfile_id`),
  KEY `syncfile_id_2` (`syncfile_id`),
  KEY `syncfile_id_3` (`syncfile_id`),
  KEY `syncfile_id_4` (`syncfile_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- Data exporting was unselected.


-- Dumping structure for table efelleos_dev.syncfile
CREATE TABLE IF NOT EXISTS `syncfile` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `filename` varchar(255) DEFAULT NULL,
  `filepath` varchar(255) NOT NULL,
  `application_id` char(36) NOT NULL,
  `application_version` float(255,2) NOT NULL,
  `modified_by` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `timestamp_updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `timestamp_created` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `timestamp_last_sync` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `timestamp_completed` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


-- Dumping structure for table efelleos_dev.synclog
CREATE TABLE IF NOT EXISTS `synclog` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `fid` int(11) DEFAULT NULL,
  `item_type` varchar(255) DEFAULT NULL,
  `left_id` int(11) DEFAULT NULL,
  `left_data` mediumtext,
  `right_id` int(11) DEFAULT NULL,
  `right_data` mediumtext,
  `action` varchar(36) DEFAULT NULL,
  `is_skipped` tinyint(1) unsigned DEFAULT '0',
  `message` mediumtext,
  `modified_by` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `timestamp_updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `timestamp_created` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `timestamp_completed` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- Data exporting was unselected.


-- Dumping structure for table efelleos_dev.tracking
CREATE TABLE IF NOT EXISTS `tracking` (
  `UID` int(11) NOT NULL,
  `URL` text NOT NULL,
  `On` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  KEY `IDX` (`UID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Data exporting was unselected.


-- Dumping structure for table efelleos_dev.trainer
CREATE TABLE IF NOT EXISTS `trainer` (
  `person_id` int(11) NOT NULL,
  `uuid` char(36) DEFAULT NULL,
  `type_option_id` int(11) NOT NULL DEFAULT '0',
  `active_trainer_option_id` int(11) DEFAULT NULL,
  `affiliation_option_id` int(11) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `modified_by` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  `timestamp_updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `timestamp_created` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`person_id`),
  UNIQUE KEY `person_idx` (`person_id`),
  UNIQUE KEY `uuid_idx` (`uuid`),
  KEY `modified_by` (`modified_by`),
  KEY `created_by` (`created_by`),
  KEY `type_option_id` (`type_option_id`),
  KEY `affiliation_option_id` (`affiliation_option_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


-- Dumping structure for table efelleos_dev.trainer_affiliation_option
CREATE TABLE IF NOT EXISTS `trainer_affiliation_option` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uuid` char(36) DEFAULT NULL,
  `trainer_affiliation_phrase` varchar(128) NOT NULL DEFAULT '',
  `modified_by` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  `timestamp_updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `timestamp_created` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name_unique` (`trainer_affiliation_phrase`),
  UNIQUE KEY `uuid_idx` (`uuid`),
  KEY `created_by` (`created_by`),
  KEY `modified_by` (`modified_by`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


-- Dumping structure for table efelleos_dev.trainer_history
CREATE TABLE IF NOT EXISTS `trainer_history` (
  `vid` int(11) NOT NULL AUTO_INCREMENT,
  `pvid` int(11) NOT NULL,
  `person_id` int(11) NOT NULL,
  `uuid` char(36) DEFAULT NULL,
  `type_option_id` int(11) NOT NULL DEFAULT '0',
  `active_trainer_option_id` int(11) DEFAULT NULL,
  `affiliation_option_id` int(11) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `modified_by` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  `timestamp_updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `timestamp_created` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`vid`),
  KEY `type_option_id` (`type_option_id`),
  KEY `affiliation_option_id` (`affiliation_option_id`),
  KEY `trainer_history_ibfk_1` (`person_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


-- Dumping structure for table efelleos_dev.trainer_language_option
CREATE TABLE IF NOT EXISTS `trainer_language_option` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uuid` char(36) DEFAULT NULL,
  `language_phrase` varchar(128) NOT NULL DEFAULT '',
  `modified_by` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  `timestamp_updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `timestamp_created` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name_unique` (`language_phrase`),
  UNIQUE KEY `uuid_idx` (`uuid`),
  KEY `modified_by` (`modified_by`),
  KEY `created_by` (`created_by`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


-- Dumping structure for table efelleos_dev.trainer_skill_option
CREATE TABLE IF NOT EXISTS `trainer_skill_option` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uuid` char(36) DEFAULT NULL,
  `trainer_skill_phrase` varchar(128) NOT NULL DEFAULT '',
  `modified_by` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  `timestamp_updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `timestamp_created` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name_unique` (`trainer_skill_phrase`),
  UNIQUE KEY `uuid_idx` (`uuid`),
  KEY `modified_by` (`modified_by`),
  KEY `created_by` (`created_by`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


-- Dumping structure for table efelleos_dev.trainer_to_trainer_language_option
CREATE TABLE IF NOT EXISTS `trainer_to_trainer_language_option` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uuid` char(36) DEFAULT NULL,
  `trainer_id` int(11) NOT NULL,
  `trainer_language_option_id` int(11) NOT NULL,
  `is_primary` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `created_by` int(11) DEFAULT NULL,
  `timestamp_created` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uuid_idx` (`uuid`),
  KEY `created_by` (`created_by`),
  KEY `trainer_language_option_id` (`trainer_language_option_id`),
  KEY `trainer_id` (`trainer_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


-- Dumping structure for table efelleos_dev.trainer_to_trainer_skill_option
CREATE TABLE IF NOT EXISTS `trainer_to_trainer_skill_option` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uuid` char(36) DEFAULT NULL,
  `trainer_id` int(11) NOT NULL,
  `trainer_skill_option_id` int(11) NOT NULL,
  `created_by` int(11) DEFAULT NULL,
  `timestamp_created` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uuid_idx` (`uuid`),
  KEY `created_by` (`created_by`),
  KEY `trainer_id` (`trainer_id`),
  KEY `trainer_skill_option_id` (`trainer_skill_option_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


-- Dumping structure for table efelleos_dev.trainer_type_option
CREATE TABLE IF NOT EXISTS `trainer_type_option` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uuid` char(36) DEFAULT NULL,
  `trainer_type_phrase` varchar(128) NOT NULL DEFAULT '',
  `modified_by` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  `timestamp_updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `timestamp_created` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name_unique` (`trainer_type_phrase`),
  UNIQUE KEY `uuid_idx` (`uuid`),
  KEY `created_by` (`created_by`),
  KEY `modified_by` (`modified_by`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


-- Dumping structure for table efelleos_dev.training
CREATE TABLE IF NOT EXISTS `training` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uuid` char(36) DEFAULT NULL,
  `training_title_option_id` int(11) NOT NULL,
  `has_known_participants` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `training_start_date` date NOT NULL,
  `training_end_date` date DEFAULT NULL,
  `training_length_value` int(11) NOT NULL,
  `training_length_interval` enum('hour','week','day') NOT NULL DEFAULT 'hour',
  `training_organizer_option_id` int(11) DEFAULT '0',
  `training_location_id` int(11) DEFAULT '0',
  `training_level_option_id` int(11) DEFAULT '0',
  `training_method_option_id` int(11) unsigned DEFAULT NULL,
  `training_custom_1_option_id` int(11) DEFAULT NULL,
  `training_custom_2_option_id` int(11) DEFAULT NULL,
  `training_got_curriculum_option_id` int(11) DEFAULT NULL,
  `training_primary_language_option_id` int(11) unsigned DEFAULT NULL,
  `training_secondary_language_option_id` int(11) unsigned DEFAULT NULL,
  `comments` text NOT NULL,
  `got_comments` text NOT NULL,
  `objectives` text NOT NULL,
  `is_approved` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `is_tot` tinyint(1) NOT NULL,
  `is_refresher` tinyint(1) NOT NULL,
  `pre` float DEFAULT NULL,
  `post` float DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  `timestamp_updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `timestamp_created` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `course_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uuid_idx` (`uuid`),
  KEY `created_by` (`created_by`),
  KEY `modified_by` (`modified_by`),
  KEY `training_title_option_id` (`training_title_option_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


-- Dumping structure for table efelleos_dev.training_approval_history
CREATE TABLE IF NOT EXISTS `training_approval_history` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` char(36) DEFAULT NULL,
  `training_id` int(11) NOT NULL,
  `approval_status` enum('resubmitted','rejected','approved','new') NOT NULL DEFAULT 'new',
  `message` text,
  `recipients` varchar(512) DEFAULT NULL,
  `created_by` int(11) NOT NULL,
  `timestamp_created` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uuid_idx` (`uuid`),
  KEY `training_id` (`training_id`),
  KEY `created_by` (`created_by`),
  KEY `created_by_2` (`created_by`),
  KEY `time_idx` (`timestamp_created`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

-- Data exporting was unselected.


-- Dumping structure for table efelleos_dev.training_category_option
CREATE TABLE IF NOT EXISTS `training_category_option` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uuid` char(36) DEFAULT NULL,
  `training_category_phrase` varchar(128) NOT NULL,
  `modified_by` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  `timestamp_updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `timestamp_created` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name_unique` (`training_category_phrase`),
  UNIQUE KEY `uuid_idx` (`uuid`),
  KEY `modified_by` (`modified_by`),
  KEY `created_by` (`created_by`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


-- Dumping structure for table efelleos_dev.training_category_option_to_training_title_option
CREATE TABLE IF NOT EXISTS `training_category_option_to_training_title_option` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uuid` char(36) DEFAULT NULL,
  `training_category_option_id` int(11) NOT NULL,
  `training_title_option_id` int(11) NOT NULL,
  `created_by` int(11) DEFAULT NULL,
  `timestamp_created` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_training_cat` (`training_category_option_id`,`training_title_option_id`),
  UNIQUE KEY `uuid_idx` (`uuid`),
  KEY `training_category_option_id` (`training_category_option_id`),
  KEY `created_by` (`created_by`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


-- Dumping structure for table efelleos_dev.training_custom_1_option
CREATE TABLE IF NOT EXISTS `training_custom_1_option` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uuid` char(36) DEFAULT NULL,
  `custom1_phrase` varchar(128) NOT NULL DEFAULT '',
  `modified_by` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  `timestamp_updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `timestamp_created` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name_unique` (`custom1_phrase`),
  UNIQUE KEY `uuid_idx` (`uuid`),
  KEY `modified_by` (`modified_by`),
  KEY `created_by` (`created_by`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


-- Dumping structure for table efelleos_dev.training_custom_2_option
CREATE TABLE IF NOT EXISTS `training_custom_2_option` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uuid` char(36) DEFAULT NULL,
  `custom2_phrase` varchar(128) NOT NULL DEFAULT '',
  `modified_by` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  `timestamp_updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `timestamp_created` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name_unique` (`custom2_phrase`),
  UNIQUE KEY `uuid_idx` (`uuid`),
  KEY `modified_by` (`modified_by`),
  KEY `created_by` (`created_by`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


-- Dumping structure for table efelleos_dev.training_funding_option
CREATE TABLE IF NOT EXISTS `training_funding_option` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uuid` char(36) DEFAULT NULL,
  `funding_phrase` varchar(128) NOT NULL DEFAULT '',
  `is_default` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `modified_by` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  `timestamp_updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `timestamp_created` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name_unique` (`funding_phrase`),
  UNIQUE KEY `uuid_idx` (`uuid`),
  KEY `created_by` (`created_by`),
  KEY `modified_by` (`modified_by`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


-- Dumping structure for table efelleos_dev.training_got_curriculum_option
CREATE TABLE IF NOT EXISTS `training_got_curriculum_option` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uuid` char(36) DEFAULT NULL,
  `training_got_curriculum_phrase` varchar(128) NOT NULL DEFAULT '',
  `modified_by` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  `timestamp_updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `timestamp_created` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name_unique` (`training_got_curriculum_phrase`),
  UNIQUE KEY `uuid_idx` (`uuid`),
  KEY `created_by` (`created_by`),
  KEY `modified_by` (`modified_by`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


-- Dumping structure for table efelleos_dev.training_level_option
CREATE TABLE IF NOT EXISTS `training_level_option` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uuid` char(36) DEFAULT NULL,
  `training_level_phrase` varchar(128) NOT NULL DEFAULT '',
  `is_default` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `modified_by` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  `timestamp_updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `timestamp_created` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name_unique` (`training_level_phrase`),
  UNIQUE KEY `uuid_idx` (`uuid`),
  KEY `modified_by` (`modified_by`),
  KEY `created_by` (`created_by`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


-- Dumping structure for table efelleos_dev.training_location
CREATE TABLE IF NOT EXISTS `training_location` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uuid` char(36) DEFAULT NULL,
  `training_location_name` varchar(128) NOT NULL DEFAULT '',
  `location_id` int(10) unsigned DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  `timestamp_updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `timestamp_created` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uuid_idx` (`uuid`),
  KEY `modified_by` (`modified_by`),
  KEY `created_by` (`created_by`),
  KEY `training_location_ibfk_9` (`location_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


-- Dumping structure for table efelleos_dev.training_method_option
CREATE TABLE IF NOT EXISTS `training_method_option` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uuid` char(36) DEFAULT NULL,
  `training_method_phrase` varchar(128) NOT NULL,
  `modified_by` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  `timestamp_updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `timestamp_created` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name_unique` (`training_method_phrase`),
  UNIQUE KEY `uuid_idx` (`uuid`),
  KEY `modified_by` (`modified_by`),
  KEY `created_by` (`created_by`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- Data exporting was unselected.


-- Dumping structure for table efelleos_dev.training_organizer_option
CREATE TABLE IF NOT EXISTS `training_organizer_option` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uuid` char(36) DEFAULT NULL,
  `training_organizer_phrase` varchar(128) NOT NULL DEFAULT '',
  `is_default` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `modified_by` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  `timestamp_updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `timestamp_created` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name_unique` (`training_organizer_phrase`),
  UNIQUE KEY `uuid_idx` (`uuid`),
  KEY `created_by` (`created_by`),
  KEY `modified_by` (`modified_by`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


-- Dumping structure for table efelleos_dev.training_pepfar_categories_option
CREATE TABLE IF NOT EXISTS `training_pepfar_categories_option` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uuid` char(36) DEFAULT NULL,
  `pepfar_category_phrase` varchar(128) NOT NULL DEFAULT '',
  `is_default` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `id_training_method_option_id` int(11) DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  `timestamp_updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `timestamp_created` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name_unique` (`pepfar_category_phrase`),
  UNIQUE KEY `uuid_idx` (`uuid`),
  KEY `created_by` (`created_by`),
  KEY `modified_by` (`modified_by`),
  KEY `id_training_method_option_id` (`id_training_method_option_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


-- Dumping structure for table efelleos_dev.training_recommend
CREATE TABLE IF NOT EXISTS `training_recommend` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uuid` char(36) DEFAULT NULL,
  `person_qualification_option_id` int(11) DEFAULT NULL,
  `training_topic_option_id` int(11) DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  `timestamp_updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `timestamp_created` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `person_qualification_option_id` (`person_qualification_option_id`,`training_topic_option_id`),
  UNIQUE KEY `uuid_idx` (`uuid`),
  KEY `created_by` (`created_by`),
  KEY `modified_by` (`modified_by`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


-- Dumping structure for table efelleos_dev.training_title_option
CREATE TABLE IF NOT EXISTS `training_title_option` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uuid` char(36) DEFAULT NULL,
  `training_title_phrase` varchar(128) NOT NULL,
  `modified_by` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  `timestamp_updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `timestamp_created` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name_unique` (`training_title_phrase`),
  UNIQUE KEY `uuid_idx` (`uuid`),
  KEY `modified_by` (`modified_by`),
  KEY `created_by` (`created_by`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


-- Dumping structure for table efelleos_dev.training_topic_option
CREATE TABLE IF NOT EXISTS `training_topic_option` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uuid` char(36) DEFAULT NULL,
  `training_topic_phrase` varchar(128) NOT NULL DEFAULT '',
  `is_default` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `modified_by` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  `timestamp_updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `timestamp_created` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name_unique` (`training_topic_phrase`),
  UNIQUE KEY `uuid_idx` (`uuid`),
  KEY `created_by` (`created_by`),
  KEY `modified_by` (`modified_by`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


-- Dumping structure for table efelleos_dev.training_to_person_qualification_option
CREATE TABLE IF NOT EXISTS `training_to_person_qualification_option` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uuid` char(36) DEFAULT NULL,
  `training_id` int(11) NOT NULL,
  `person_qualification_option_id` int(11) NOT NULL,
  `person_count_na` int(11) NOT NULL DEFAULT '0',
  `person_count_male` int(11) NOT NULL DEFAULT '0',
  `person_count_female` int(11) NOT NULL DEFAULT '0',
  `age_range_option_id` int(11) unsigned NOT NULL DEFAULT '0',
  `created_by` int(11) DEFAULT NULL,
  `timestamp_created` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `training_qual_uniq` (`training_id`,`person_qualification_option_id`,`age_range_option_id`),
  UNIQUE KEY `uuid_idx` (`uuid`),
  KEY `person_qualification_option_id` (`person_qualification_option_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

-- Data exporting was unselected.


-- Dumping structure for table efelleos_dev.training_to_trainer
CREATE TABLE IF NOT EXISTS `training_to_trainer` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uuid` char(36) DEFAULT NULL,
  `trainer_id` int(11) NOT NULL,
  `training_id` int(11) NOT NULL,
  `duration_days` float NOT NULL,
  `created_by` int(11) DEFAULT NULL,
  `timestamp_created` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_training` (`trainer_id`,`training_id`),
  UNIQUE KEY `uuid_idx` (`uuid`),
  KEY `created_by` (`created_by`),
  KEY `trainer_id` (`trainer_id`),
  KEY `training_id` (`training_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


-- Dumping structure for table efelleos_dev.training_to_training_funding_option
CREATE TABLE IF NOT EXISTS `training_to_training_funding_option` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uuid` char(36) DEFAULT NULL,
  `training_id` int(11) NOT NULL,
  `training_funding_option_id` int(11) NOT NULL,
  `funding_amount` float DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `timestamp_created` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_training_cat` (`training_funding_option_id`,`training_id`),
  UNIQUE KEY `uuid_idx` (`uuid`),
  KEY `training_id` (`training_id`),
  KEY `created_by` (`created_by`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


-- Dumping structure for table efelleos_dev.training_to_training_pepfar_categories_option
CREATE TABLE IF NOT EXISTS `training_to_training_pepfar_categories_option` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uuid` char(36) DEFAULT NULL,
  `training_id` int(11) NOT NULL,
  `training_pepfar_categories_option_id` int(11) NOT NULL,
  `training_method_option_id` int(11) DEFAULT NULL,
  `duration_days` float NOT NULL,
  `created_by` int(11) DEFAULT NULL,
  `timestamp_created` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_training_cat` (`training_pepfar_categories_option_id`,`training_id`),
  UNIQUE KEY `uuid_idx` (`uuid`),
  KEY `training_id` (`training_id`),
  KEY `created_by` (`created_by`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


-- Dumping structure for table efelleos_dev.training_to_training_topic_option
CREATE TABLE IF NOT EXISTS `training_to_training_topic_option` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uuid` char(36) DEFAULT NULL,
  `training_id` int(11) NOT NULL,
  `training_topic_option_id` int(11) NOT NULL,
  `created_by` int(11) DEFAULT NULL,
  `timestamp_created` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_training_cat` (`training_topic_option_id`,`training_id`),
  UNIQUE KEY `uuid_idx` (`uuid`),
  KEY `training_id` (`training_id`),
  KEY `created_by` (`created_by`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


-- Dumping structure for table efelleos_dev.trans
CREATE TABLE IF NOT EXISTS `trans` (
  `sno` bigint(20) NOT NULL AUTO_INCREMENT,
  `person` int(11) NOT NULL,
  `id` int(11) NOT NULL,
  `chk` varchar(10) NOT NULL,
  `yr` varchar(10) NOT NULL,
  `transstring` varchar(256) NOT NULL,
  `active` char(1) NOT NULL DEFAULT 'N',
  PRIMARY KEY (`sno`),
  KEY `person` (`person`,`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Data exporting was unselected.


-- Dumping structure for table efelleos_dev.translation
CREATE TABLE IF NOT EXISTS `translation` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uuid` char(36) DEFAULT NULL,
  `key_phrase` varchar(128) NOT NULL DEFAULT '',
  `phrase` varchar(128) NOT NULL DEFAULT '',
  `modified_by` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  `timestamp_updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `timestamp_created` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uuid_idx` (`uuid`),
  KEY `modified_by` (`modified_by`),
  KEY `created_by` (`created_by`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


-- Dumping structure for table efelleos_dev.tutor
CREATE TABLE IF NOT EXISTS `tutor` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `personid` varchar(10) NOT NULL DEFAULT '',
  `tutorsince` int(10) unsigned NOT NULL DEFAULT '0',
  `tutortimehere` int(10) unsigned NOT NULL DEFAULT '0',
  `degree` varchar(150) NOT NULL DEFAULT '',
  `degreeinst` varchar(150) NOT NULL DEFAULT '',
  `degreeyear` int(10) unsigned NOT NULL DEFAULT '0',
  `languagesspoken` varchar(255) NOT NULL DEFAULT '',
  `positionsheld` text NOT NULL,
  `comments` text NOT NULL,
  `facilityid` int(10) unsigned NOT NULL DEFAULT '0',
  `cadreid` int(10) unsigned NOT NULL DEFAULT '0',
  `nationalityid` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx2` (`facilityid`,`personid`,`cadreid`,`nationalityid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- Data exporting was unselected.


-- Dumping structure for table efelleos_dev.user
CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uuid` char(36) DEFAULT NULL,
  `username` varchar(32) NOT NULL DEFAULT '',
  `password` varchar(41) NOT NULL DEFAULT '',
  `email` varchar(64) NOT NULL DEFAULT '',
  `first_name` varchar(32) NOT NULL DEFAULT '',
  `last_name` varchar(32) NOT NULL DEFAULT '',
  `person_id` int(11) DEFAULT NULL,
  `locale` varchar(255) DEFAULT '',
  `modified_by` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `is_blocked` tinyint(1) NOT NULL DEFAULT '0',
  `timestamp_updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `timestamp_created` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `timestamp_last_login` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `username_idx` (`username`),
  UNIQUE KEY `email_idx` (`email`),
  UNIQUE KEY `uuid_idx` (`uuid`),
  KEY `modified_by` (`modified_by`),
  KEY `created_by` (`created_by`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


-- Dumping structure for table efelleos_dev.user_to_acl
CREATE TABLE IF NOT EXISTS `user_to_acl` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `acl_id` enum('edit_course','view_course','edit_people','view_people','view_create_reports','edit_country_options','add_edit_users','training_organizer_option_all','training_title_option_all','approve_trainings','admin_files','use_offline_app','pre_service','in_service','edit_institution','view_institution') NOT NULL DEFAULT 'view_course',
  `user_id` int(11) NOT NULL,
  `created_by` int(11) DEFAULT NULL,
  `timestamp_created` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `created_by` (`created_by`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


-- Dumping structure for table efelleos_dev.user_to_acl_province
CREATE TABLE IF NOT EXISTS `user_to_acl_province` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `location_id` int(11) NOT NULL,
  `created_by` int(11) DEFAULT NULL,
  `timestamp_created` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `created_by` (`created_by`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


-- Dumping structure for table efelleos_dev.user_to_organizer_access
CREATE TABLE IF NOT EXISTS `user_to_organizer_access` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `training_organizer_option_id` int(11) DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `created_by` int(11) DEFAULT NULL,
  `timestamp_created` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `created_by` (`created_by`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


-- Dumping structure for table efelleos_dev._location_missing_links
CREATE TABLE IF NOT EXISTS `_location_missing_links` (
  `id` int(10) unsigned NOT NULL,
  `parent_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


-- Dumping structure for table efelleos_dev._location_ref
CREATE TABLE IF NOT EXISTS `_location_ref` (
  `id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


-- Dumping structure for table efelleos_dev._system
CREATE TABLE IF NOT EXISTS `_system` (
  `country` varchar(64) NOT NULL DEFAULT '',
  `country_uuid` char(38) NOT NULL DEFAULT '' COMMENT 'legacy id',
  `locale` varchar(32) NOT NULL DEFAULT 'en_EN.UTF-8',
  `locale_enabled` varchar(255) DEFAULT NULL,
  `allow_multi_pepfar` tinyint(1) NOT NULL DEFAULT '0',
  `allow_multi_topic` tinyint(1) NOT NULL DEFAULT '0',
  `display_funding_options` tinyint(1) NOT NULL DEFAULT '1',
  `display_test_scores_course` tinyint(1) NOT NULL DEFAULT '1',
  `display_test_scores_individual` tinyint(1) NOT NULL DEFAULT '1',
  `display_scores_limit` int(11) NOT NULL DEFAULT '5',
  `display_national_id` tinyint(1) NOT NULL DEFAULT '1',
  `display_trainer_affiliations` tinyint(1) NOT NULL DEFAULT '1',
  `display_training_custom1` tinyint(1) NOT NULL DEFAULT '1',
  `display_training_custom2` tinyint(1) NOT NULL DEFAULT '1',
  `display_training_pre_test` tinyint(1) NOT NULL DEFAULT '1',
  `display_training_post_test` tinyint(1) NOT NULL DEFAULT '1',
  `display_people_custom1` tinyint(1) NOT NULL DEFAULT '1',
  `display_people_custom2` tinyint(1) NOT NULL DEFAULT '1',
  `display_region_b` tinyint(1) NOT NULL DEFAULT '1',
  `display_people_active` tinyint(1) NOT NULL DEFAULT '1',
  `display_middle_name_last` tinyint(1) NOT NULL DEFAULT '0',
  `display_training_recommend` tinyint(1) NOT NULL DEFAULT '0',
  `display_training_trainers` tinyint(1) NOT NULL DEFAULT '1',
  `display_course_objectives` tinyint(1) NOT NULL DEFAULT '1',
  `display_training_topic` tinyint(1) NOT NULL DEFAULT '1',
  `display_training_got_curric` tinyint(1) NOT NULL DEFAULT '0',
  `display_training_got_comment` tinyint(1) NOT NULL DEFAULT '0',
  `display_training_refresher` tinyint(1) NOT NULL DEFAULT '0',
  `display_people_file_num` tinyint(1) NOT NULL DEFAULT '0',
  `display_people_home_phone` tinyint(1) NOT NULL DEFAULT '1',
  `display_people_fax` tinyint(1) NOT NULL DEFAULT '1',
  `module_evaluation_enabled` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `module_approvals_enabled` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `module_historical_data_enabled` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `module_unknown_participants_enabled` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `display_end_date` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `display_training_method` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `display_funding_amount` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `display_primary_language` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `display_secondary_language` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `display_people_title` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `display_people_suffix` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `display_people_age` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `display_people_home_address` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `display_people_second_email` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `display_middle_name` tinyint(1) NOT NULL DEFAULT '1',
  `display_funding_amounts` tinyint(1) NOT NULL DEFAULT '1',
  `display_region_c` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `display_external_classes` tinyint(1) NOT NULL DEFAULT '0',
  `display_responsibility_me` tinyint(1) NOT NULL DEFAULT '0',
  `display_highest_ed_level` tinyint(1) NOT NULL DEFAULT '0',
  `display_attend_reason` tinyint(1) NOT NULL DEFAULT '0',
  `display_primary_responsibility` tinyint(1) NOT NULL DEFAULT '0',
  `display_secondary_responsibility` tinyint(1) NOT NULL DEFAULT '0',
  `display_training_partner` tinyint(1) NOT NULL DEFAULT '0',
  `display_mod_skillsmart` tinyint(1) NOT NULL DEFAULT '1',
  `display_occupational_category` tinyint(1) NOT NULL DEFAULT '1',
  `display_government_employee` tinyint(1) NOT NULL DEFAULT '1',
  `display_professional_bodies` tinyint(1) NOT NULL DEFAULT '1',
  `display_race` tinyint(1) NOT NULL DEFAULT '1',
  `display_disability` tinyint(1) NOT NULL DEFAULT '1',
  `display_nurse_trainer_type` tinyint(1) NOT NULL DEFAULT '1',
  `display_provider_start` tinyint(1) NOT NULL DEFAULT '1',
  `display_rank_groups` tinyint(1) NOT NULL DEFAULT '1',
  `display_supervised` tinyint(1) NOT NULL DEFAULT '1',
  `display_training_received` tinyint(1) NOT NULL DEFAULT '1',
  `display_facility_department` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


-- Dumping structure for trigger efelleos_dev.evaluation_insert
SET @OLDTMP_SQL_MODE=@@SQL_MODE, SQL_MODE='';
DELIMITER //
CREATE TRIGGER `evaluation_insert` BEFORE INSERT ON `evaluation` FOR EACH ROW BEGIN
SET NEW.`uuid` = IFNULL(NEW.`uuid`,UUID());
END//
;DELIMITER ;
SET SQL_MODE=@OLDTMP_SQL_MODE;


-- Dumping structure for trigger efelleos_dev.evaluation_question_insert
SET @OLDTMP_SQL_MODE=@@SQL_MODE, SQL_MODE='';
DELIMITER //
CREATE TRIGGER `evaluation_question_insert` BEFORE INSERT ON `evaluation_question` FOR EACH ROW BEGIN
SET NEW.`uuid` = IFNULL(NEW.`uuid`,UUID());
END//
;DELIMITER ;
SET SQL_MODE=@OLDTMP_SQL_MODE;


-- Dumping structure for trigger efelleos_dev.evaluation_question_response_insert
SET @OLDTMP_SQL_MODE=@@SQL_MODE, SQL_MODE='';
DELIMITER //
CREATE TRIGGER `evaluation_question_response_insert` BEFORE INSERT ON `evaluation_question_response` FOR EACH ROW BEGIN
SET NEW.`uuid` = IFNULL(NEW.`uuid`,UUID());
END//
;DELIMITER ;
SET SQL_MODE=@OLDTMP_SQL_MODE;


-- Dumping structure for trigger efelleos_dev.evaluation_response_insert
SET @OLDTMP_SQL_MODE=@@SQL_MODE, SQL_MODE='';
DELIMITER //
CREATE TRIGGER `evaluation_response_insert` BEFORE INSERT ON `evaluation_response` FOR EACH ROW BEGIN
SET NEW.`uuid` = IFNULL(NEW.`uuid`,UUID());
END//
;DELIMITER ;
SET SQL_MODE=@OLDTMP_SQL_MODE;


-- Dumping structure for trigger efelleos_dev.evaluation_to_training_insert
SET @OLDTMP_SQL_MODE=@@SQL_MODE, SQL_MODE='';
DELIMITER //
CREATE TRIGGER `evaluation_to_training_insert` BEFORE INSERT ON `evaluation_to_training` FOR EACH ROW BEGIN
SET NEW.`uuid` = IFNULL(NEW.`uuid`,UUID());
END//
;DELIMITER ;
SET SQL_MODE=@OLDTMP_SQL_MODE;


-- Dumping structure for trigger efelleos_dev.external_course_insert
SET @OLDTMP_SQL_MODE=@@SQL_MODE, SQL_MODE='';
DELIMITER //
CREATE TRIGGER `external_course_insert` BEFORE INSERT ON `external_course` FOR EACH ROW BEGIN
SET NEW.`uuid` = IFNULL(NEW.`uuid`,UUID());
END//
;DELIMITER ;
SET SQL_MODE=@OLDTMP_SQL_MODE;


-- Dumping structure for trigger efelleos_dev.facility_insert
SET @OLDTMP_SQL_MODE=@@SQL_MODE, SQL_MODE='';
DELIMITER //
CREATE TRIGGER `facility_insert` BEFORE INSERT ON `facility` FOR EACH ROW BEGIN
SET NEW.`uuid` = IFNULL(NEW.`uuid`,UUID());
END//
;DELIMITER ;
SET SQL_MODE=@OLDTMP_SQL_MODE;


-- Dumping structure for trigger efelleos_dev.facility_sponsor_option_insert
SET @OLDTMP_SQL_MODE=@@SQL_MODE, SQL_MODE='';
DELIMITER //
CREATE TRIGGER `facility_sponsor_option_insert` BEFORE INSERT ON `facility_sponsor_option` FOR EACH ROW BEGIN
SET NEW.`uuid` = IFNULL(NEW.`uuid`,UUID());
END//
;DELIMITER ;
SET SQL_MODE=@OLDTMP_SQL_MODE;


-- Dumping structure for trigger efelleos_dev.facility_type_option_insert
SET @OLDTMP_SQL_MODE=@@SQL_MODE, SQL_MODE='';
DELIMITER //
CREATE TRIGGER `facility_type_option_insert` BEFORE INSERT ON `facility_type_option` FOR EACH ROW BEGIN
SET NEW.`uuid` = IFNULL(NEW.`uuid`,UUID());
END//
;DELIMITER ;
SET SQL_MODE=@OLDTMP_SQL_MODE;


-- Dumping structure for trigger efelleos_dev.file_insert
SET @OLDTMP_SQL_MODE=@@SQL_MODE, SQL_MODE='';
DELIMITER //
CREATE TRIGGER `file_insert` BEFORE INSERT ON `file` FOR EACH ROW BEGIN
SET NEW.`uuid` = IFNULL(NEW.`uuid`,UUID());
END//
;DELIMITER ;
SET SQL_MODE=@OLDTMP_SQL_MODE;


-- Dumping structure for trigger efelleos_dev.organizer_partners_insert
SET @OLDTMP_SQL_MODE=@@SQL_MODE, SQL_MODE='';
DELIMITER //
CREATE TRIGGER `organizer_partners_insert` BEFORE INSERT ON `organizer_partners` FOR EACH ROW BEGIN
  SET NEW.`uuid` = IFNULL(NEW.`uuid`,UUID());
END//
;DELIMITER ;
SET SQL_MODE=@OLDTMP_SQL_MODE;


-- Dumping structure for trigger efelleos_dev.person_active_trainer_option_insert
SET @OLDTMP_SQL_MODE=@@SQL_MODE, SQL_MODE='';
DELIMITER //
CREATE TRIGGER `person_active_trainer_option_insert` BEFORE INSERT ON `person_active_trainer_option` FOR EACH ROW BEGIN
SET NEW.`uuid` = IFNULL(NEW.`uuid`,UUID());
END//
;DELIMITER ;
SET SQL_MODE=@OLDTMP_SQL_MODE;


-- Dumping structure for trigger efelleos_dev.person_custom_1_option_insert
SET @OLDTMP_SQL_MODE=@@SQL_MODE, SQL_MODE='';
DELIMITER //
CREATE TRIGGER `person_custom_1_option_insert` BEFORE INSERT ON `person_custom_1_option` FOR EACH ROW BEGIN
SET NEW.`uuid` = IFNULL(NEW.`uuid`,UUID());
END//
;DELIMITER ;
SET SQL_MODE=@OLDTMP_SQL_MODE;


-- Dumping structure for trigger efelleos_dev.person_custom_2_option_insert
SET @OLDTMP_SQL_MODE=@@SQL_MODE, SQL_MODE='';
DELIMITER //
CREATE TRIGGER `person_custom_2_option_insert` BEFORE INSERT ON `person_custom_2_option` FOR EACH ROW BEGIN
SET NEW.`uuid` = IFNULL(NEW.`uuid`,UUID());
END//
;DELIMITER ;
SET SQL_MODE=@OLDTMP_SQL_MODE;


-- Dumping structure for trigger efelleos_dev.person_insert
SET @OLDTMP_SQL_MODE=@@SQL_MODE, SQL_MODE='';
DELIMITER //
CREATE TRIGGER `person_insert` BEFORE INSERT ON `person` FOR EACH ROW BEGIN
SET NEW.`uuid` = IFNULL(NEW.`uuid`,UUID());
END//
;DELIMITER ;
SET SQL_MODE=@OLDTMP_SQL_MODE;


-- Dumping structure for trigger efelleos_dev.person_qualification_option_insert
SET @OLDTMP_SQL_MODE=@@SQL_MODE, SQL_MODE='';
DELIMITER //
CREATE TRIGGER `person_qualification_option_insert` BEFORE INSERT ON `person_qualification_option` FOR EACH ROW BEGIN
SET NEW.`uuid` = IFNULL(NEW.`uuid`,UUID());
END//
;DELIMITER ;
SET SQL_MODE=@OLDTMP_SQL_MODE;


-- Dumping structure for trigger efelleos_dev.person_responsibility_option_insert
SET @OLDTMP_SQL_MODE=@@SQL_MODE, SQL_MODE='';
DELIMITER //
CREATE TRIGGER `person_responsibility_option_insert` BEFORE INSERT ON `person_primary_responsibility_option` FOR EACH ROW BEGIN
SET NEW.`uuid` = IFNULL(NEW.`uuid`,UUID());
END//
;DELIMITER ;
SET SQL_MODE=@OLDTMP_SQL_MODE;


-- Dumping structure for trigger efelleos_dev.person_suffix_option_insert
SET @OLDTMP_SQL_MODE=@@SQL_MODE, SQL_MODE='';
DELIMITER //
CREATE TRIGGER `person_suffix_option_insert` BEFORE INSERT ON `person_suffix_option` FOR EACH ROW BEGIN
SET NEW.`uuid` = IFNULL(NEW.`uuid`,UUID());
END//
;DELIMITER ;
SET SQL_MODE=@OLDTMP_SQL_MODE;


-- Dumping structure for trigger efelleos_dev.person_title_option_insert
SET @OLDTMP_SQL_MODE=@@SQL_MODE, SQL_MODE='';
DELIMITER //
CREATE TRIGGER `person_title_option_insert` BEFORE INSERT ON `person_title_option` FOR EACH ROW BEGIN
SET NEW.`uuid` = IFNULL(NEW.`uuid`,UUID());
END//
;DELIMITER ;
SET SQL_MODE=@OLDTMP_SQL_MODE;


-- Dumping structure for trigger efelleos_dev.person_to_training_insert
SET @OLDTMP_SQL_MODE=@@SQL_MODE, SQL_MODE='';
DELIMITER //
CREATE TRIGGER `person_to_training_insert` BEFORE INSERT ON `person_to_training` FOR EACH ROW BEGIN
SET NEW.`uuid` = IFNULL(NEW.`uuid`,UUID());
END//
;DELIMITER ;
SET SQL_MODE=@OLDTMP_SQL_MODE;


-- Dumping structure for trigger efelleos_dev.person_to_training_topic_option_insert
SET @OLDTMP_SQL_MODE=@@SQL_MODE, SQL_MODE='';
DELIMITER //
CREATE TRIGGER `person_to_training_topic_option_insert` BEFORE INSERT ON `person_to_training_topic_option` FOR EACH ROW BEGIN
SET NEW.`uuid` = IFNULL(NEW.`uuid`,UUID());
END//
;DELIMITER ;
SET SQL_MODE=@OLDTMP_SQL_MODE;


-- Dumping structure for trigger efelleos_dev.score_insert
SET @OLDTMP_SQL_MODE=@@SQL_MODE, SQL_MODE='';
DELIMITER //
CREATE TRIGGER `score_insert` BEFORE INSERT ON `score` FOR EACH ROW BEGIN
SET NEW.`uuid` = IFNULL(NEW.`uuid`,UUID());
END//
;DELIMITER ;
SET SQL_MODE=@OLDTMP_SQL_MODE;


-- Dumping structure for trigger efelleos_dev.trainer_affiliation_option_insert
SET @OLDTMP_SQL_MODE=@@SQL_MODE, SQL_MODE='';
DELIMITER //
CREATE TRIGGER `trainer_affiliation_option_insert` BEFORE INSERT ON `trainer_affiliation_option` FOR EACH ROW BEGIN
SET NEW.`uuid` = IFNULL(NEW.`uuid`,UUID());
END//
;DELIMITER ;
SET SQL_MODE=@OLDTMP_SQL_MODE;


-- Dumping structure for trigger efelleos_dev.trainer_insert
SET @OLDTMP_SQL_MODE=@@SQL_MODE, SQL_MODE='';
DELIMITER //
CREATE TRIGGER `trainer_insert` BEFORE INSERT ON `trainer` FOR EACH ROW BEGIN
SET NEW.`uuid` = IFNULL(NEW.`uuid`,UUID());
END//
;DELIMITER ;
SET SQL_MODE=@OLDTMP_SQL_MODE;


-- Dumping structure for trigger efelleos_dev.trainer_language_option_insert
SET @OLDTMP_SQL_MODE=@@SQL_MODE, SQL_MODE='';
DELIMITER //
CREATE TRIGGER `trainer_language_option_insert` BEFORE INSERT ON `trainer_language_option` FOR EACH ROW BEGIN
SET NEW.`uuid` = IFNULL(NEW.`uuid`,UUID());
END//
;DELIMITER ;
SET SQL_MODE=@OLDTMP_SQL_MODE;


-- Dumping structure for trigger efelleos_dev.trainer_skill_option_insert
SET @OLDTMP_SQL_MODE=@@SQL_MODE, SQL_MODE='';
DELIMITER //
CREATE TRIGGER `trainer_skill_option_insert` BEFORE INSERT ON `trainer_skill_option` FOR EACH ROW BEGIN
SET NEW.`uuid` = IFNULL(NEW.`uuid`,UUID());
END//
;DELIMITER ;
SET SQL_MODE=@OLDTMP_SQL_MODE;


-- Dumping structure for trigger efelleos_dev.trainer_to_trainer_language_option_insert
SET @OLDTMP_SQL_MODE=@@SQL_MODE, SQL_MODE='';
DELIMITER //
CREATE TRIGGER `trainer_to_trainer_language_option_insert` BEFORE INSERT ON `trainer_to_trainer_language_option` FOR EACH ROW BEGIN
SET NEW.`uuid` = IFNULL(NEW.`uuid`,UUID());
END//
;DELIMITER ;
SET SQL_MODE=@OLDTMP_SQL_MODE;


-- Dumping structure for trigger efelleos_dev.trainer_to_trainer_skill_option_insert
SET @OLDTMP_SQL_MODE=@@SQL_MODE, SQL_MODE='';
DELIMITER //
CREATE TRIGGER `trainer_to_trainer_skill_option_insert` BEFORE INSERT ON `trainer_to_trainer_skill_option` FOR EACH ROW BEGIN
SET NEW.`uuid` = IFNULL(NEW.`uuid`,UUID());
END//
;DELIMITER ;
SET SQL_MODE=@OLDTMP_SQL_MODE;


-- Dumping structure for trigger efelleos_dev.trainer_type_option_insert
SET @OLDTMP_SQL_MODE=@@SQL_MODE, SQL_MODE='';
DELIMITER //
CREATE TRIGGER `trainer_type_option_insert` BEFORE INSERT ON `trainer_type_option` FOR EACH ROW BEGIN
SET NEW.`uuid` = IFNULL(NEW.`uuid`,UUID());
END//
;DELIMITER ;
SET SQL_MODE=@OLDTMP_SQL_MODE;


-- Dumping structure for trigger efelleos_dev.training_approval_history_insert
SET @OLDTMP_SQL_MODE=@@SQL_MODE, SQL_MODE='';
DELIMITER //
CREATE TRIGGER `training_approval_history_insert` BEFORE INSERT ON `training_approval_history` FOR EACH ROW BEGIN
SET NEW.`uuid` = IFNULL(NEW.`uuid`,UUID());
END//
;DELIMITER ;
SET SQL_MODE=@OLDTMP_SQL_MODE;


-- Dumping structure for trigger efelleos_dev.training_category_option_insert
SET @OLDTMP_SQL_MODE=@@SQL_MODE, SQL_MODE='';
DELIMITER //
CREATE TRIGGER `training_category_option_insert` BEFORE INSERT ON `training_category_option` FOR EACH ROW BEGIN
SET NEW.`uuid` = IFNULL(NEW.`uuid`,UUID());
END//
;DELIMITER ;
SET SQL_MODE=@OLDTMP_SQL_MODE;


-- Dumping structure for trigger efelleos_dev.training_category_option_to_training_title_option_insert
SET @OLDTMP_SQL_MODE=@@SQL_MODE, SQL_MODE='';
DELIMITER //
CREATE TRIGGER `training_category_option_to_training_title_option_insert` BEFORE INSERT ON `training_category_option_to_training_title_option` FOR EACH ROW BEGIN
SET NEW.`uuid` = IFNULL(NEW.`uuid`,UUID());
END//
;DELIMITER ;
SET SQL_MODE=@OLDTMP_SQL_MODE;


-- Dumping structure for trigger efelleos_dev.training_custom_1_option_insert
SET @OLDTMP_SQL_MODE=@@SQL_MODE, SQL_MODE='';
DELIMITER //
CREATE TRIGGER `training_custom_1_option_insert` BEFORE INSERT ON `training_custom_1_option` FOR EACH ROW BEGIN
SET NEW.`uuid` = IFNULL(NEW.`uuid`,UUID());
END//
;DELIMITER ;
SET SQL_MODE=@OLDTMP_SQL_MODE;


-- Dumping structure for trigger efelleos_dev.training_custom_2_option_insert
SET @OLDTMP_SQL_MODE=@@SQL_MODE, SQL_MODE='';
DELIMITER //
CREATE TRIGGER `training_custom_2_option_insert` BEFORE INSERT ON `training_custom_2_option` FOR EACH ROW BEGIN
SET NEW.`uuid` = IFNULL(NEW.`uuid`,UUID());
END//
;DELIMITER ;
SET SQL_MODE=@OLDTMP_SQL_MODE;


-- Dumping structure for trigger efelleos_dev.training_funding_option_insert
SET @OLDTMP_SQL_MODE=@@SQL_MODE, SQL_MODE='';
DELIMITER //
CREATE TRIGGER `training_funding_option_insert` BEFORE INSERT ON `training_funding_option` FOR EACH ROW BEGIN
SET NEW.`uuid` = IFNULL(NEW.`uuid`,UUID());
END//
;DELIMITER ;
SET SQL_MODE=@OLDTMP_SQL_MODE;


-- Dumping structure for trigger efelleos_dev.training_got_curriculum_option_insert
SET @OLDTMP_SQL_MODE=@@SQL_MODE, SQL_MODE='';
DELIMITER //
CREATE TRIGGER `training_got_curriculum_option_insert` BEFORE INSERT ON `training_got_curriculum_option` FOR EACH ROW BEGIN
SET NEW.`uuid` = IFNULL(NEW.`uuid`,UUID());
END//
;DELIMITER ;
SET SQL_MODE=@OLDTMP_SQL_MODE;


-- Dumping structure for trigger efelleos_dev.training_insert
SET @OLDTMP_SQL_MODE=@@SQL_MODE, SQL_MODE='';
DELIMITER //
CREATE TRIGGER `training_insert` BEFORE INSERT ON `training` FOR EACH ROW BEGIN
SET NEW.`uuid` = IFNULL(NEW.`uuid`,UUID());
END//
;DELIMITER ;
SET SQL_MODE=@OLDTMP_SQL_MODE;


-- Dumping structure for trigger efelleos_dev.training_level_option_insert
SET @OLDTMP_SQL_MODE=@@SQL_MODE, SQL_MODE='';
DELIMITER //
CREATE TRIGGER `training_level_option_insert` BEFORE INSERT ON `training_level_option` FOR EACH ROW BEGIN
SET NEW.`uuid` = IFNULL(NEW.`uuid`,UUID());
END//
;DELIMITER ;
SET SQL_MODE=@OLDTMP_SQL_MODE;


-- Dumping structure for trigger efelleos_dev.training_location_insert
SET @OLDTMP_SQL_MODE=@@SQL_MODE, SQL_MODE='';
DELIMITER //
CREATE TRIGGER `training_location_insert` BEFORE INSERT ON `training_location` FOR EACH ROW BEGIN
SET NEW.`uuid` = IFNULL(NEW.`uuid`,UUID());
END//
;DELIMITER ;
SET SQL_MODE=@OLDTMP_SQL_MODE;


-- Dumping structure for trigger efelleos_dev.training_method_option_insert
SET @OLDTMP_SQL_MODE=@@SQL_MODE, SQL_MODE='';
DELIMITER //
CREATE TRIGGER `training_method_option_insert` BEFORE INSERT ON `training_method_option` FOR EACH ROW BEGIN
SET NEW.`uuid` = IFNULL(NEW.`uuid`,UUID());
END//
;DELIMITER ;
SET SQL_MODE=@OLDTMP_SQL_MODE;


-- Dumping structure for trigger efelleos_dev.training_organizer_option_insert
SET @OLDTMP_SQL_MODE=@@SQL_MODE, SQL_MODE='';
DELIMITER //
CREATE TRIGGER `training_organizer_option_insert` BEFORE INSERT ON `training_organizer_option` FOR EACH ROW BEGIN
SET NEW.`uuid` = IFNULL(NEW.`uuid`,UUID());
END//
;DELIMITER ;
SET SQL_MODE=@OLDTMP_SQL_MODE;


-- Dumping structure for trigger efelleos_dev.training_pepfar_categories_option_insert
SET @OLDTMP_SQL_MODE=@@SQL_MODE, SQL_MODE='';
DELIMITER //
CREATE TRIGGER `training_pepfar_categories_option_insert` BEFORE INSERT ON `training_pepfar_categories_option` FOR EACH ROW BEGIN
SET NEW.`uuid` = IFNULL(NEW.`uuid`,UUID());
END//
;DELIMITER ;
SET SQL_MODE=@OLDTMP_SQL_MODE;


-- Dumping structure for trigger efelleos_dev.training_recommend_insert
SET @OLDTMP_SQL_MODE=@@SQL_MODE, SQL_MODE='';
DELIMITER //
CREATE TRIGGER `training_recommend_insert` BEFORE INSERT ON `training_recommend` FOR EACH ROW BEGIN
SET NEW.`uuid` = IFNULL(NEW.`uuid`,UUID());
END//
;DELIMITER ;
SET SQL_MODE=@OLDTMP_SQL_MODE;


-- Dumping structure for trigger efelleos_dev.training_title_option_insert
SET @OLDTMP_SQL_MODE=@@SQL_MODE, SQL_MODE='';
DELIMITER //
CREATE TRIGGER `training_title_option_insert` BEFORE INSERT ON `training_title_option` FOR EACH ROW BEGIN
SET NEW.`uuid` = IFNULL(NEW.`uuid`,UUID());
END//
;DELIMITER ;
SET SQL_MODE=@OLDTMP_SQL_MODE;


-- Dumping structure for trigger efelleos_dev.training_topic_option_insert
SET @OLDTMP_SQL_MODE=@@SQL_MODE, SQL_MODE='';
DELIMITER //
CREATE TRIGGER `training_topic_option_insert` BEFORE INSERT ON `training_topic_option` FOR EACH ROW BEGIN
SET NEW.`uuid` = IFNULL(NEW.`uuid`,UUID());
END//
;DELIMITER ;
SET SQL_MODE=@OLDTMP_SQL_MODE;


-- Dumping structure for trigger efelleos_dev.training_to_person_qualification_option_insert
SET @OLDTMP_SQL_MODE=@@SQL_MODE, SQL_MODE='';
DELIMITER //
CREATE TRIGGER `training_to_person_qualification_option_insert` BEFORE INSERT ON `training_to_person_qualification_option` FOR EACH ROW BEGIN
SET NEW.`uuid` = IFNULL(NEW.`uuid`,UUID());
END//
;DELIMITER ;
SET SQL_MODE=@OLDTMP_SQL_MODE;


-- Dumping structure for trigger efelleos_dev.training_to_trainer_insert
SET @OLDTMP_SQL_MODE=@@SQL_MODE, SQL_MODE='';
DELIMITER //
CREATE TRIGGER `training_to_trainer_insert` BEFORE INSERT ON `training_to_trainer` FOR EACH ROW BEGIN
SET NEW.`uuid` = IFNULL(NEW.`uuid`,UUID());
END//
;DELIMITER ;
SET SQL_MODE=@OLDTMP_SQL_MODE;


-- Dumping structure for trigger efelleos_dev.training_to_training_funding_option_insert
SET @OLDTMP_SQL_MODE=@@SQL_MODE, SQL_MODE='';
DELIMITER //
CREATE TRIGGER `training_to_training_funding_option_insert` BEFORE INSERT ON `training_to_training_funding_option` FOR EACH ROW BEGIN
SET NEW.`uuid` = IFNULL(NEW.`uuid`,UUID());
END//
;DELIMITER ;
SET SQL_MODE=@OLDTMP_SQL_MODE;


-- Dumping structure for trigger efelleos_dev.training_to_training_pepfar_categories_option_insert
SET @OLDTMP_SQL_MODE=@@SQL_MODE, SQL_MODE='';
DELIMITER //
CREATE TRIGGER `training_to_training_pepfar_categories_option_insert` BEFORE INSERT ON `training_to_training_pepfar_categories_option` FOR EACH ROW BEGIN
SET NEW.`uuid` = IFNULL(NEW.`uuid`,UUID());
END//
;DELIMITER ;
SET SQL_MODE=@OLDTMP_SQL_MODE;


-- Dumping structure for trigger efelleos_dev.training_to_training_topic_option_insert
SET @OLDTMP_SQL_MODE=@@SQL_MODE, SQL_MODE='';
DELIMITER //
CREATE TRIGGER `training_to_training_topic_option_insert` BEFORE INSERT ON `training_to_training_topic_option` FOR EACH ROW BEGIN
SET NEW.`uuid` = IFNULL(NEW.`uuid`,UUID());
END//
;DELIMITER ;
SET SQL_MODE=@OLDTMP_SQL_MODE;


-- Dumping structure for trigger efelleos_dev.translation_insert
SET @OLDTMP_SQL_MODE=@@SQL_MODE, SQL_MODE='';
DELIMITER //
CREATE TRIGGER `translation_insert` BEFORE INSERT ON `translation` FOR EACH ROW BEGIN
SET NEW.`uuid` = IFNULL(NEW.`uuid`,UUID());
END//
;DELIMITER ;
SET SQL_MODE=@OLDTMP_SQL_MODE;


-- Dumping structure for trigger efelleos_dev.user_insert
SET @OLDTMP_SQL_MODE=@@SQL_MODE, SQL_MODE='';
DELIMITER //
CREATE TRIGGER `user_insert` BEFORE INSERT ON `user` FOR EACH ROW BEGIN
SET NEW.`uuid` = IFNULL(NEW.`uuid`,UUID());
END//
;DELIMITER ;
SET SQL_MODE=@OLDTMP_SQL_MODE;


-- Dumping structure for trigger efelleos_dev.uuid_insert
SET @OLDTMP_SQL_MODE=@@SQL_MODE, SQL_MODE='';
DELIMITER //
CREATE TRIGGER `uuid_insert` BEFORE INSERT ON `location` FOR EACH ROW BEGIN
SET NEW.`uuid` = UUID();
END//
;DELIMITER ;
SET SQL_MODE=@OLDTMP_SQL_MODE;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;

/* post - 4.0 changes */
ALTER TABLE `person` ADD COLUMN `multi_facility_ids` VARCHAR(255) NOT NULL AFTER `facility_id`;
ALTER TABLE `facility` DROP INDEX  `facility_name` , ADD UNIQUE  `facility_name` (  `facility_name` ,  `location_id` );


/* change refresher course to multi select value */
ALTER TABLE  `_system`
  ADD  `multi_opt_refresher_course` TINYINT( 1 ) NOT NULL DEFAULT '0';
ALTER TABLE `training`
  ADD `training_refresher_option_id` INT(11) NULL DEFAULT NULL;
/* tables */
DROP TABLE IF EXISTS `training_refresher_option`;
CREATE TABLE `training_refresher_option` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `refresher_phrase_option` varchar(128) NOT NULL DEFAULT '',
  `is_default` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `modified_by` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  `timestamp_updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `timestamp_created` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name_unique` (`refresher_phrase_option`),
  KEY `created_by` (`created_by`),
  KEY `modified_by` (`modified_by`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
DROP TABLE IF EXISTS `training_to_training_refresher_option`;
CREATE TABLE `training_to_training_refresher_option` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `training_id` int(11) NOT NULL,
  `training_refresher_option_id` int(11) NOT NULL,
  `created_by` int(11) DEFAULT NULL,
  `timestamp_created` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_training_cat` (`training_refresher_option_id`,`training_id`),
  KEY `training_id` (`training_id`),
  KEY `created_by` (`created_by`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
ALTER TABLE `training_refresher_option` ADD COLUMN `uuid` char(36) AFTER `id`;
ALTER TABLE `training_refresher_option` ADD UNIQUE `uuid_idx`(uuid);
ALTER TABLE `training_refresher_option` CHANGE COLUMN `uuid` `uuid` char(36) DEFAULT NULL;
ALTER TABLE `training_to_training_refresher_option` ADD COLUMN `uuid` char(36) AFTER `id`;
ALTER TABLE `training_to_training_refresher_option` ADD UNIQUE `uuid_idx`(uuid);
ALTER TABLE `training_to_training_refresher_option` CHANGE COLUMN `uuid` `uuid` char(36) DEFAULT NULL;

DELIMITER ;;
CREATE TRIGGER `training_to_training_refresher_option_insert` BEFORE INSERT ON `training_to_training_refresher_option` FOR EACH ROW BEGIN
  SET NEW.`uuid` = IFNULL(NEW.`uuid`,UUID());
  END;;
CREATE TRIGGER `training_refresher_option_insert` BEFORE INSERT ON `training_refresher_option` FOR EACH ROW BEGIN
  SET NEW.`uuid` = IFNULL(NEW.`uuid`,UUID());
  END;;
DELIMITER ;

/*UPDATE training_to_training_refresher_option SET uuid = UUID(); */
/*UPDATE training_refresher_option SET uuid = UUID(); */
/* end live refresher update */

/*default   values */

insert into `age_range_option` values('1',0x756e6b6e6f776e,'1',null,null,'0','2011-03-31 15:58:37','0000-00-00 00:00:00'),
  ('2',0x3c3130,'0',null,null,'0','2011-03-31 15:58:53','0000-00-00 00:00:00'),
  ('3',0x31302d3134,'0',null,null,'0','2011-03-31 15:58:59','0000-00-00 00:00:00'),
  ('4',0x31352d3139,'0',null,null,'0','2011-03-31 15:59:05','0000-00-00 00:00:00'),
  ('5',0x32302d3235,'0',null,null,'0','2011-03-31 15:59:11','0000-00-00 00:00:00'),
  ('6',0x32352b,'0',null,null,'0','2011-03-31 15:59:17','0000-00-00 00:00:00');
INSERT INTO `_system` (country, country_uuid, locale, locale_enabled, allow_multi_pepfar, allow_multi_topic, display_funding_options, display_test_scores_course, display_test_scores_individual, display_scores_limit, display_national_id, display_trainer_affiliations, display_training_custom1, display_training_custom2, display_training_pre_test, display_training_post_test, display_people_custom1, display_people_custom2, display_region_b, display_people_active, display_middle_name_last, display_training_recommend, display_training_trainers, display_course_objectives, display_training_topic, display_training_got_curric, display_training_got_comment, display_training_refresher, display_people_file_num, display_people_home_phone, display_people_fax, module_evaluation_enabled, module_approvals_enabled, module_historical_data_enabled, module_unknown_participants_enabled, display_end_date, display_training_method, display_funding_amount, display_primary_language, display_secondary_language, display_people_title, display_people_suffix, display_people_age, display_people_home_address, display_people_second_email, display_middle_name, display_funding_amounts, display_region_c) VALUES ('Country','','en_EN.UTF-8','en_EN.UTF-8',0,0,1,1,1,5,1,1,1,1,1,1,1,1,1,1,1,0,0,1,1,1,1,1,1,1,1,1,1,1,1,0,0,0,0,0,1,1,0,0,0,1,1,1);
INSERT INTO `acl` VALUES ('add_edit_users','add_edit_users'),('approve_trainings','approve_trainings'),('edit_country_options','edit_country_options'),('edit_course','edit_course'),('edit_people','edit_people'),('training_organizer_option_all','training_organizer_option_all'),('training_title_option_all','training_title_option_all'),('view_course','view_course'),('view_create_reports','view_create_reports'),('view_people','view_people');
INSERT INTO `person_qualification_option` (`id`, `uuid`, `parent_id`, `qualification_phrase`, `is_default`, `modified_by`, `created_by`, `is_deleted`, `timestamp_updated`, `timestamp_created`) VALUES
  (1, 'c0d71fb4-6b1f-11e2-b4f7-0021704dcdff', NULL, 'Laboratory', 0, NULL, NULL, 0, '2008-12-03 18:04:17', '0000-00-00 00:00:00'),
  (2, 'c0d75d3e-6b1f-11e2-b4f7-0021704dcdff', NULL, 'Nurse', 0, NULL, NULL, 0, '2008-12-03 18:04:17', '0000-00-00 00:00:00'),
  (3, 'c0d75fd7-6b1f-11e2-b4f7-0021704dcdff', NULL, 'Other', 0, NULL, NULL, 0, '2008-12-03 18:04:17', '0000-00-00 00:00:00'),
  (4, 'c0d761fd-6b1f-11e2-b4f7-0021704dcdff', NULL, 'Physician', 0, NULL, NULL, 0, '2008-12-03 18:04:17', '0000-00-00 00:00:00'),
  (5, 'c0d76415-6b1f-11e2-b4f7-0021704dcdff', NULL, 'Mid-Level Clinician', 0, NULL, NULL, 0, '2008-12-03 18:04:17', '0000-00-00 00:00:00'),
  (6, 'c0d765d6-6b1f-11e2-b4f7-0021704dcdff', NULL, 'Community Based Worker', 0, NULL, NULL, 0, '2008-12-03 18:04:17', '0000-00-00 00:00:00'),
  (7, 'c0d767ad-6b1f-11e2-b4f7-0021704dcdff', NULL, 'Pharmacy', 0, NULL, NULL, 0, '2008-12-03 18:04:17', '0000-00-00 00:00:00'),
  (8, 'c0d76984-6b1f-11e2-b4f7-0021704dcdff', NULL, 'Social Services', 0, NULL, NULL, 0, '2008-12-03 18:04:17', '0000-00-00 00:00:00'),
  (9, 'c0d76da4-6b1f-11e2-b4f7-0021704dcdff', NULL, 'Dental Services', 0, NULL, NULL, 0, '2008-12-03 18:04:17', '0000-00-00 00:00:00'),
  (10, 'c0d76f72-6b1f-11e2-b4f7-0021704dcdff', NULL, 'Paramedical', 0, NULL, NULL, 0, '2008-12-03 18:04:17', '0000-00-00 00:00:00');

INSERT INTO `translation` (`id`, `uuid`, `key_phrase`, `phrase`, `modified_by`, `created_by`, `is_deleted`, `timestamp_updated`, `timestamp_created`) VALUES
  (0, 'c1a587f4-6b1f-11e2-b4f7-0021704dcdff', 'Application Name', 'TrainSMART', 1, NULL, 0, '2010-09-20 00:31:29', '0000-00-00 00:00:00'),
  (1, 'c1a58e6f-6b1f-11e2-b4f7-0021704dcdff', 'Country', 'Country', 1, NULL, 0, '2008-04-28 20:17:48', '0000-00-00 00:00:00'),
  (2, 'c1a59090-6b1f-11e2-b4f7-0021704dcdff', 'Region A (Province)', 'Region', 1, NULL, 0, '2010-09-20 00:31:29', '0000-00-00 00:00:00'),
  (3, 'c1a59274-6b1f-11e2-b4f7-0021704dcdff', 'Region B (Health District)', 'Province', 1, NULL, 0, '2010-09-20 00:31:29', '0000-00-00 00:00:00'),
  (4, 'c1a5944b-6b1f-11e2-b4f7-0021704dcdff', 'City or Town', 'City', 1, NULL, 0, '2010-09-20 00:31:29', '0000-00-00 00:00:00'),
  (5, 'c1a59610-6b1f-11e2-b4f7-0021704dcdff', 'Training Name', '__training_name__', 1, NULL, 0, '2010-09-20 00:30:37', '0000-00-00 00:00:00'),
  (6, 'c1a597de-6b1f-11e2-b4f7-0021704dcdff', 'Training Organizer', '__training_organizer__', 1, NULL, 0, '2010-09-20 00:30:37', '0000-00-00 00:00:00'),
  (7, 'c1a599b9-6b1f-11e2-b4f7-0021704dcdff', 'Training Level', '__training_level__', 1, NULL, 0, '2010-09-20 00:30:37', '0000-00-00 00:00:00'),
  (8, 'c1a59b87-6b1f-11e2-b4f7-0021704dcdff', 'Pre Test Score', '__pre_test_score__', 1, NULL, 0, '2010-09-20 00:30:37', '0000-00-00 00:00:00'),
  (9, 'c1a59d48-6b1f-11e2-b4f7-0021704dcdff', 'Post Test Score', '__post_test_score__', 1, NULL, 0, '2010-09-20 00:30:37', '0000-00-00 00:00:00'),
  (10, 'c1a59f04-6b1f-11e2-b4f7-0021704dcdff', 'Training Custom 1', '__custom_field_1__', 1, NULL, 0, '2010-09-20 00:30:37', '0000-00-00 00:00:00'),
  (11, 'c1a5a0c0-6b1f-11e2-b4f7-0021704dcdff', 'Training Custom 2', '__custom_field_2__', 1, NULL, 0, '2010-09-20 00:30:37', '0000-00-00 00:00:00'),
  (12, 'c1a5a26b-6b1f-11e2-b4f7-0021704dcdff', 'National ID', '__national_id__', 1, NULL, 0, '2009-12-15 01:06:20', '0000-00-00 00:00:00'),
  (13, 'c1a5a423-6b1f-11e2-b4f7-0021704dcdff', 'People Custom 1', '__custom_field_1__', 1, NULL, 0, '2009-12-15 01:06:20', '0000-00-00 00:00:00'),
  (14, 'c1a5a5fa-6b1f-11e2-b4f7-0021704dcdff', 'People Custom 2', '__custom_field_2__', 1, NULL, 0, '2009-12-15 01:06:20', '0000-00-00 00:00:00'),
  (15, 'c1a5a7b2-6b1f-11e2-b4f7-0021704dcdff', 'Is Active', '__is_active__', 1, NULL, 0, '2009-12-15 01:06:20', '2008-04-28 20:41:05'),
  (16, 'c1a5a965-6b1f-11e2-b4f7-0021704dcdff', 'PEPFAR Category', '__pepfar_category__', 1, NULL, 0, '2010-09-20 00:30:37', '2008-04-28 20:42:56'),
  (17, 'c1a5ab19-6b1f-11e2-b4f7-0021704dcdff', 'First Name', '__first_name__', 1, NULL, 0, '2009-12-15 01:06:20', '2008-12-03 18:12:29'),
  (18, 'c1a5acde-6b1f-11e2-b4f7-0021704dcdff', 'Middle Name', '__middle_name__', 1, NULL, 0, '2009-12-15 01:06:20', '2008-12-03 18:12:38'),
  (19, 'c1a5ae96-6b1f-11e2-b4f7-0021704dcdff', 'Last Name', '__last_name__', 1, NULL, 0, '2009-12-15 01:06:20', '2008-12-03 18:12:46'),
  (20, 'c1a5b041-6b1f-11e2-b4f7-0021704dcdff', 'Training of Trainers', '__training_of_trainers__', 1, NULL, 0, '2010-09-20 00:30:37', '0000-00-00 00:00:00'),
  (21, 'c1a5b50c-6b1f-11e2-b4f7-0021704dcdff', 'Course Objectives', '__course_objectives__', 1, NULL, 0, '2010-09-20 00:30:37', '0000-00-00 00:00:00'),
  (22, 'c1a5b6da-6b1f-11e2-b4f7-0021704dcdff', 'Training Category', '__training_category__', 1, NULL, 0, '2010-09-20 00:30:37', '0000-00-00 00:00:00'),
  (23, 'c1a5b8c7-6b1f-11e2-b4f7-0021704dcdff', 'Training Topic', '__training_topic__', 1, NULL, 0, '2010-09-20 00:30:37', '0000-00-00 00:00:00'),
  (24, 'c1a5ba7b-6b1f-11e2-b4f7-0021704dcdff', 'GOT Curriculum', '__national_curriculum__', 1, NULL, 0, '2010-09-20 00:30:37', '0000-00-00 00:00:00'),
  (25, 'c1a5bc33-6b1f-11e2-b4f7-0021704dcdff', 'GOT Comment', '__nat_curriculum_comment__', 1, NULL, 0, '2010-09-20 00:30:37', '0000-00-00 00:00:00'),
  (26, 'c1a5bdf8-6b1f-11e2-b4f7-0021704dcdff', 'Refresher Course', '__refresher_course__', 1, NULL, 0, '2010-09-20 00:30:37', '0000-00-00 00:00:00'),
  (27, 'c1a5bfca-6b1f-11e2-b4f7-0021704dcdff', 'Comments', '__comments__', 1, NULL, 0, '2010-09-20 00:30:37', '0000-00-00 00:00:00'),
  (28, 'c1a5c182-6b1f-11e2-b4f7-0021704dcdff', 'File Number', '__file_number__', 1, NULL, 0, '2009-12-15 01:06:20', '0000-00-00 00:00:00'),
  (30, 'c1a5c336-6b1f-11e2-b4f7-0021704dcdff', 'Primary Language', '__1st_language__', 1, NULL, 0, '2010-09-20 00:30:37', '2009-11-19 03:36:42'),
  (31, 'c1a5c4fb-6b1f-11e2-b4f7-0021704dcdff', 'Secondary Language', '__2nd_language__', 1, NULL, 0, '2010-09-20 00:30:37', '2009-11-19 03:36:57'),
  (32, 'c1a5c6b3-6b1f-11e2-b4f7-0021704dcdff', 'Funding Amount', '__funding_amt__', 1, NULL, 0, '2010-09-20 00:30:37', '2009-11-19 03:37:19'),
  (33, 'c1a5c866-6b1f-11e2-b4f7-0021704dcdff', 'Training Method', '__training_method__', 1, NULL, 0, '2010-09-20 00:30:37', '2009-11-19 03:37:48'),
  (34, 'c1a5ca23-6b1f-11e2-b4f7-0021704dcdff', 'Title', '__title__', 1, NULL, 0, '2009-12-15 01:06:20', '2009-11-20 20:59:19'),
  (35, 'c1a5cbf9-6b1f-11e2-b4f7-0021704dcdff', 'Suffix', '__suffix__', 1, NULL, 0, '2009-12-15 01:06:20', '2009-11-20 20:59:30'),
  (36, 'c1a5cdc3-6b1f-11e2-b4f7-0021704dcdff', 'Age', 'Age', NULL, NULL, 0, '2009-11-20 20:59:57', '2009-11-20 20:59:57'),
  (37, 'c1a5cf91-6b1f-11e2-b4f7-0021704dcdff', 'Facility', '__facility__', 1, NULL, 0, '2009-12-15 01:06:29', '2009-11-20 22:24:55'),
  (38, 'c1a5d156-6b1f-11e2-b4f7-0021704dcdff', 'Region C (Local Region)', 'County', 1, NULL, 0, '2010-09-20 00:31:29', '0000-00-00 00:00:00'),
  (39, 'c21c399f-6b1f-11e2-b4f7-0021704dcdff', 'M&E Responsibility', 'M&E Responsibility', NULL, NULL, 0, '2013-01-30 20:58:13', '0000-00-00 00:00:00'),
  (40, 'c21f7fce-6b1f-11e2-b4f7-0021704dcdff', 'Highest Education Level', 'Highest Education Level', NULL, NULL, 0, '2013-01-30 20:58:13', '0000-00-00 00:00:00'),
  (41, 'c21fb9ac-6b1f-11e2-b4f7-0021704dcdff', 'Reason Attending', 'Reason Attending', NULL, NULL, 0, '2013-01-30 20:58:13', '0000-00-00 00:00:00'),
  (42, 'c21ff297-6b1f-11e2-b4f7-0021704dcdff', 'Primary Responsibility', 'Primary Responsibility', NULL, NULL, 0, '2013-01-30 20:58:13', '0000-00-00 00:00:00'),
  (43, 'c2203bb7-6b1f-11e2-b4f7-0021704dcdff', 'Secondary Responsibility', 'Secondary Responsibility', NULL, NULL, 0, '2013-01-30 20:58:13', '0000-00-00 00:00:00'),
  (44, 'c27b9e7f-6b1f-11e2-b4f7-0021704dcdff', 'Facility Comments', 'Facility Comments', NULL, NULL, 0, '2013-01-30 20:58:14', '0000-00-00 00:00:00'),
  (45, 'c27bdc3a-6b1f-11e2-b4f7-0021704dcdff', 'Qualification Comments', 'Qualification Comments', NULL, NULL, 0, '2013-01-30 20:58:14', '0000-00-00 00:00:00'),
  (46, 'c27c0cbe-6b1f-11e2-b4f7-0021704dcdff', 'Training Comments', 'Training Comments', NULL, NULL, 0, '2013-01-30 20:58:14', '0000-00-00 00:00:00');

INSERT INTO `user` (id, uuid, username, `password`, email, first_name, last_name, person_id, locale, modified_by, created_by, is_blocked, timestamp_updated, timestamp_created, timestamp_last_login) VALUES
  (0, 'c1aee550-6b1f-11e2-b4f7-0021704dcdff', 'system', '', '', '', '', NULL, '', NULL, NULL, 0, '2008-03-11 21:17:59', '2008-03-11 21:17:59', '0000-00-00 00:00:00'),
  (1, 'c1aee8d2-6b1f-11e2-b4f7-0021704dcdff', 'admin', '6a204bd89f3c8348afd5c77c717a097a', 'admin@example.net', 'Admin', 'Admin', NULL, '', 1, NULL, 0, '2010-10-22 19:16:37', '2008-02-27 20:15:43', '2010-10-22 19:16:37');
INSERT INTO `user_to_acl` VALUES (1,'add_edit_users',1,NULL,'2008-04-28 20:03:31'),(3,'edit_course',1,1,'2008-04-28 20:16:19'),(4,'edit_people',1,1,'2008-04-28 20:16:19'),(5,'view_create_reports',1,1,'2008-04-28 20:16:19'),(6,'training_organizer_option_all',1,NULL,'2008-12-03 18:10:51'),(9,'approve_trainings',1,1,'2009-11-20 22:39:34'),(14,'training_title_option_all',1,1,'2009-12-08 20:11:00'),(15,'edit_country_options',1,1,'2009-12-08 20:11:00');
insert into `acl` ( `id`, `acl`) values ( 'admin_files', 'admin_files');
insert into acl (id, acl) values ('use_offline_app', 'use_offline_app');
insert into person_attend_reason_option (uuid, attend_reason_phrase) values (uuid(), 'Other');
insert into translation (uuid, key_phrase, phrase) values (uuid(), 'M&E Responsibility', 'M&E Responsibility');
insert into translation (uuid, key_phrase, phrase) values (uuid(), 'Highest Education Level', 'Highest Education Level');
insert into translation (uuid, key_phrase, phrase) values (uuid(), 'Reason Attending', 'Reason Attending');
insert into translation (uuid, key_phrase, phrase) values (uuid(), 'Primary Responsibility', 'Primary Responsibility');
insert into translation (uuid, key_phrase, phrase) values (uuid(), 'Secondary Responsibility', 'Secondary Responsibility');
INSERT INTO  `translation` VALUES (NULL , NULL ,  'Facility Comments',  'Facility Comments', NULL , NULL ,  '0',  CURRENT_TIMESTAMP ,  '0000-00-00 00:00:00');
INSERT INTO  `translation` VALUES (NULL , NULL ,  'Qualification Comments',  'Qualification Comments', NULL , NULL ,  '0',  CURRENT_TIMESTAMP ,  '0000-00-00 00:00:00');
INSERT INTO  `translation` VALUES (NULL , NULL ,  'Training Comments',  'Training Comments', NULL , NULL ,  '0',  CURRENT_TIMESTAMP ,  '0000-00-00 00:00:00');