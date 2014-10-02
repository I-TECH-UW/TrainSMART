-- MySQL dump 10.13  Distrib 5.1.26-rc, for apple-darwin8.11.1 (i686)
--
-- Host: localhost    Database: itech_clean
-- ------------------------------------------------------
-- Server version 5.1.37-log

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
--
-- Table structure for table `_system`
--

SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `_system` (
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
  `display_region_c` tinyint(1) unsigned NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `_system`
--

LOCK TABLES `_system` WRITE;
/*!40000 ALTER TABLE `_system` DISABLE KEYS */;
INSERT INTO `_system` VALUES ('Country','','en_EN.UTF-8','en_EN.UTF-8',0,0,1,1,1,5,1,1,1,1,1,1,1,1,1,1,1,0,0,1,1,1,1,1,1,1,1,1,1,1,1,0,0,0,0,0,1,1,0,0,0,1,1,1);
/*!40000 ALTER TABLE `_system` ENABLE KEYS */;
alter table _system add column display_facility_custom1 tinyint(1) NOT NULL DEFAULT '0';
UNLOCK TABLES;

--
-- Table structure for table `acl`
--

SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `acl` (
  `id` varchar(32) NOT NULL DEFAULT '',
  `acl` varchar(32) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `acl`
--

LOCK TABLES `acl` WRITE;
/*!40000 ALTER TABLE `acl` DISABLE KEYS */;
INSERT INTO `acl` VALUES ('add_edit_users','add_edit_users'),('approve_trainings','approve_trainings'),('edit_country_options','edit_country_options'),('edit_course','edit_course'),('edit_people','edit_people'),('training_organizer_option_all','training_organizer_option_all'),('training_title_option_all','training_title_option_all'),('view_course','view_course'),('view_create_reports','view_create_reports'),('view_people','view_people');
/*!40000 ALTER TABLE `acl` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `evaluation`
--


SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `evaluation` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL DEFAULT '',
  `modified_by` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  `timestamp_updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `timestamp_created` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `modified_by` (`modified_by`),
  KEY `created_by` (`created_by`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `evaluation`
--

LOCK TABLES `evaluation` WRITE;
/*!40000 ALTER TABLE `evaluation` DISABLE KEYS */;
/*!40000 ALTER TABLE `evaluation` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `evaluation_question`
--


SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `evaluation_question` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
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
  KEY `modified_by` (`modified_by`),
  KEY `created_by` (`created_by`),
  KEY `evaluation_id` (`evaluation_id`),
  CONSTRAINT `evaluation_question_ibfk_1` FOREIGN KEY (`evaluation_id`) REFERENCES `evaluation` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `evaluation_question`
--

LOCK TABLES `evaluation_question` WRITE;
/*!40000 ALTER TABLE `evaluation_question` DISABLE KEYS */;
/*!40000 ALTER TABLE `evaluation_question` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `evaluation_question_response`
--


SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `evaluation_question_response` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
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
  KEY `modified_by` (`modified_by`),
  KEY `created_by` (`created_by`),
  KEY `evaluation_id` (`evaluation_response_id`),
  CONSTRAINT `eval_response` FOREIGN KEY (`evaluation_response_id`) REFERENCES `evaluation_response` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `evaluation_question_response`
--

LOCK TABLES `evaluation_question_response` WRITE;
/*!40000 ALTER TABLE `evaluation_question_response` DISABLE KEYS */;
/*!40000 ALTER TABLE `evaluation_question_response` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `evaluation_response`
--


SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `evaluation_response` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `evaluation_to_training_id` int(11) NOT NULL,
  `modified_by` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  `timestamp_updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `timestamp_created` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `modified_by` (`modified_by`),
  KEY `created_by` (`created_by`),
  KEY `evaluation_to_training_id` (`evaluation_to_training_id`),
  CONSTRAINT `evaluation_response_ibfk_1` FOREIGN KEY (`evaluation_to_training_id`) REFERENCES `evaluation_to_training` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `evaluation_response`
--

LOCK TABLES `evaluation_response` WRITE;
/*!40000 ALTER TABLE `evaluation_response` DISABLE KEYS */;
/*!40000 ALTER TABLE `evaluation_response` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `evaluation_to_training`
--


SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `evaluation_to_training` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `evaluation_id` int(11) NOT NULL,
  `training_id` int(11) NOT NULL,
  `created_by` int(11) DEFAULT NULL,
  `timestamp_created` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_idx` (`evaluation_id`,`training_id`),
  KEY `created_by` (`created_by`),
  KEY `training_id` (`training_id`),
  KEY `e_id` (`evaluation_id`),
  CONSTRAINT `evaluation_to_training_ibfk_1` FOREIGN KEY (`evaluation_id`) REFERENCES `evaluation` (`id`),
  CONSTRAINT `evaluation_to_training_ibfk_2` FOREIGN KEY (`training_id`) REFERENCES `training` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `evaluation_to_training`
--

LOCK TABLES `evaluation_to_training` WRITE;
/*!40000 ALTER TABLE `evaluation_to_training` DISABLE KEYS */;
/*!40000 ALTER TABLE `evaluation_to_training` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `external_course`
--


SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `external_course` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `person_id` int(11) unsigned NOT NULL,
  `title` varchar(128) NOT NULL,
  `training_location` varchar(128) DEFAULT '',
  `training_start_date` date DEFAULT NULL,
  `training_length_value` int(11) DEFAULT NULL,
  `training_length_interval` enum('day') NOT NULL DEFAULT 'day',
  `training_funder` varchar(128) DEFAULT '',
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  `timestamp_created` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `external_course`
--

LOCK TABLES `external_course` WRITE;
/*!40000 ALTER TABLE `external_course` DISABLE KEYS */;
/*!40000 ALTER TABLE `external_course` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `facility`
--


SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `facility` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `facility_name` varchar(128) NOT NULL DEFAULT '',
  `uuid` char(38) DEFAULT '',
  `address_1` varchar(128) NOT NULL DEFAULT '',
  `address_2` varchar(128) NOT NULL DEFAULT '',
  `location_id` int(10) unsigned DEFAULT NULL,
  `postal_code` varchar(20) DEFAULT '',
  `phone` varchar(32) DEFAULT '',
  `fax` varchar(32) DEFAULT '',
  `sponsor_option_id` int(11) DEFAULT NULL,
  `type_option_id` int(11) DEFAULT NULL,
  `facility_comments` varchar(255) DEFAULT '',
  `modified_by` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  `timestamp_updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `timestamp_created` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `facility_name` (`facility_name`),
  KEY `created_by` (`created_by`),
  KEY `modified_by` (`modified_by`),
  KEY `sponsor_option_id` (`sponsor_option_id`),
  KEY `type_option_id` (`type_option_id`),
  KEY `facility_ibfk_5` (`location_id`),
  CONSTRAINT `facility_ibfk_1` FOREIGN KEY (`sponsor_option_id`) REFERENCES `facility_sponsor_option` (`id`),
  CONSTRAINT `facility_ibfk_2` FOREIGN KEY (`type_option_id`) REFERENCES `facility_type_option` (`id`),
  CONSTRAINT `facility_ibfk_5` FOREIGN KEY (`location_id`) REFERENCES `location` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `facility`
--

LOCK TABLES `facility` WRITE;
/*!40000 ALTER TABLE `facility` DISABLE KEYS */;
/*!40000 ALTER TABLE `facility` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `facility_sponsor_option`
--


SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `facility_sponsor_option` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `facility_sponsor_phrase` varchar(128) NOT NULL DEFAULT '',
  `modified_by` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  `timestamp_updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `timestamp_created` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name_unique` (`facility_sponsor_phrase`),
  KEY `created_by` (`created_by`),
  KEY `modified_by` (`modified_by`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `facility_sponsor_option`
--

LOCK TABLES `facility_sponsor_option` WRITE;
/*!40000 ALTER TABLE `facility_sponsor_option` DISABLE KEYS */;
/*!40000 ALTER TABLE `facility_sponsor_option` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `facility_type_option`
--


SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `facility_type_option` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `facility_type_phrase` varchar(128) NOT NULL DEFAULT '',
  `modified_by` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  `timestamp_updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `timestamp_created` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name_unique` (`facility_type_phrase`),
  KEY `modified_by` (`modified_by`),
  KEY `created_by` (`created_by`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `facility_type_option`
--

LOCK TABLES `facility_type_option` WRITE;
/*!40000 ALTER TABLE `facility_type_option` DISABLE KEYS */;
/*!40000 ALTER TABLE `facility_type_option` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `file`
--


SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `file` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
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
  KEY `parent_key` (`parent_table`,`parent_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `file`
--

LOCK TABLES `file` WRITE;
/*!40000 ALTER TABLE `file` DISABLE KEYS */;
/*!40000 ALTER TABLE `file` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `location`
--


SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `location` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(36) DEFAULT NULL,
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
  UNIQUE KEY `uuid_uniq` (`uuid`),
  KEY `parent_id` (`parent_id`),
  KEY `modified_by` (`modified_by`),
  KEY `created_by` (`created_by`),
  CONSTRAINT `parent_fk` FOREIGN KEY (`parent_id`) REFERENCES `location` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
--
-- Table structure for table `person`
--


SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `person` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uuid` char(38) DEFAULT '',
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
  `primary_responsibility_option_id` int(11) NOT NULL,
  `secondary_responsibility_option_id` int(11) NOT NULL,
  `comments` varchar(255) DEFAULT '',
  `person_custom_1_option_id` int(11) DEFAULT NULL,
  `person_custom_2_option_id` int(11) DEFAULT NULL,
  `home_address_1` varchar(128) DEFAULT '',
  `home_address_2` varchar(128) DEFAULT '',
  `home_location_id` int(10) unsigned DEFAULT NULL,
  `home_postal_code` int(11) DEFAULT NULL,
  `active` enum('active','inactive','deceased') NOT NULL DEFAULT 'active',
  `timestamp_created` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `timestamp_updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` int(11) DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uuid_idx` (`uuid`),
  KEY `modified_by` (`modified_by`),
  KEY `created_by` (`created_by`),
  KEY `facility_id` (`facility_id`),
  KEY `home_location_ibfk_9` (`home_location_id`),
  CONSTRAINT `home_location_ibfk_9` FOREIGN KEY (`home_location_id`) REFERENCES `location` (`id`),
  CONSTRAINT `person_ibfk_1` FOREIGN KEY (`facility_id`) REFERENCES `facility` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `person`
--

LOCK TABLES `person` WRITE;
/*!40000 ALTER TABLE `person` DISABLE KEYS */;
/*!40000 ALTER TABLE `person` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `person_active_trainer_option`
--


SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `person_active_trainer_option` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `active_trainer_phrase` varchar(128) NOT NULL DEFAULT '',
  `modified_by` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  `timestamp_updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `timestamp_created` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name_unique` (`active_trainer_phrase`),
  KEY `modified_by` (`modified_by`),
  KEY `created_by` (`created_by`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `person_active_trainer_option`
--

LOCK TABLES `person_active_trainer_option` WRITE;
/*!40000 ALTER TABLE `person_active_trainer_option` DISABLE KEYS */;
/*!40000 ALTER TABLE `person_active_trainer_option` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `person_custom_1_option`
--


SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `person_custom_1_option` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `custom1_phrase` varchar(128) NOT NULL DEFAULT '',
  `modified_by` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  `timestamp_updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `timestamp_created` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name_unique` (`custom1_phrase`),
  KEY `modified_by` (`modified_by`),
  KEY `created_by` (`created_by`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `person_custom_1_option`
--

LOCK TABLES `person_custom_1_option` WRITE;
/*!40000 ALTER TABLE `person_custom_1_option` DISABLE KEYS */;
/*!40000 ALTER TABLE `person_custom_1_option` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `person_custom_2_option`
--


SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `person_custom_2_option` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `custom2_phrase` varchar(128) NOT NULL DEFAULT '',
  `modified_by` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  `timestamp_updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `timestamp_created` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name_unique` (`custom2_phrase`),
  KEY `created_by` (`created_by`),
  KEY `modified_by` (`modified_by`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `person_custom_2_option`
--

LOCK TABLES `person_custom_2_option` WRITE;
/*!40000 ALTER TABLE `person_custom_2_option` DISABLE KEYS */;
/*!40000 ALTER TABLE `person_custom_2_option` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `person_history`
--


SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `person_history` (
  `vid` int(11) NOT NULL AUTO_INCREMENT,
  `person_id` int(11) NOT NULL,
  `uuid` char(38) DEFAULT '',
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
  `primary_responsibility_option_id` int(11) NOT NULL,
  `secondary_responsibility_option_id` int(11) NOT NULL,
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
  KEY `person_id` (`person_id`),
  CONSTRAINT `person_history_ibfk_1` FOREIGN KEY (`person_id`) REFERENCES `person` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `person_history`
--

LOCK TABLES `person_history` WRITE;
/*!40000 ALTER TABLE `person_history` DISABLE KEYS */;
/*!40000 ALTER TABLE `person_history` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `person_qualification_option`
--


SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `person_qualification_option` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
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
  KEY `modified_by` (`modified_by`),
  KEY `created_by` (`created_by`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `person_qualification_option`
--

LOCK TABLES `person_qualification_option` WRITE;
/*!40000 ALTER TABLE `person_qualification_option` DISABLE KEYS */;
INSERT INTO `person_qualification_option` VALUES (1,NULL,'Laboratory',0,NULL,NULL,0,'2008-12-03 18:04:17','0000-00-00 00:00:00'),(2,NULL,'Nurse',0,NULL,NULL,0,'2008-12-03 18:04:17','0000-00-00 00:00:00'),(3,NULL,'Other',0,NULL,NULL,0,'2008-12-03 18:04:17','0000-00-00 00:00:00'),(4,NULL,'Physician',0,NULL,NULL,0,'2008-12-03 18:04:17','0000-00-00 00:00:00'),(5,NULL,'Mid-Level Clinician',0,NULL,NULL,0,'2008-12-03 18:04:17','0000-00-00 00:00:00'),(6,NULL,'Community Based Worker',0,NULL,NULL,0,'2008-12-03 18:04:17','0000-00-00 00:00:00'),(7,NULL,'Pharmacy',0,NULL,NULL,0,'2008-12-03 18:04:17','0000-00-00 00:00:00'),(8,NULL,'Social Services',0,NULL,NULL,0,'2008-12-03 18:04:17','0000-00-00 00:00:00'),(9,NULL,'Dental Services',0,NULL,NULL,0,'2008-12-03 18:04:17','0000-00-00 00:00:00'),(10,NULL,'Paramedical',0,NULL,NULL,0,'2008-12-03 18:04:17','0000-00-00 00:00:00');
/*!40000 ALTER TABLE `person_qualification_option` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `person_responsibility_option`
--


SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `person_responsibility_option` (
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
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `person_responsibility_option`
--

LOCK TABLES `person_responsibility_option` WRITE;
/*!40000 ALTER TABLE `person_responsibility_option` DISABLE KEYS */;
/*!40000 ALTER TABLE `person_responsibility_option` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `person_suffix_option`
--


SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `person_suffix_option` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `suffix_phrase` varchar(128) NOT NULL DEFAULT '',
  `modified_by` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  `timestamp_updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `timestamp_created` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name_unique` (`suffix_phrase`),
  KEY `created_by` (`created_by`),
  KEY `modified_by` (`modified_by`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `person_suffix_option`
--

LOCK TABLES `person_suffix_option` WRITE;
/*!40000 ALTER TABLE `person_suffix_option` DISABLE KEYS */;
/*!40000 ALTER TABLE `person_suffix_option` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `person_title_option`
--


SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `person_title_option` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title_phrase` varchar(128) NOT NULL DEFAULT '',
  `modified_by` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  `timestamp_updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `timestamp_created` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name_unique` (`title_phrase`),
  KEY `created_by` (`created_by`),
  KEY `modified_by` (`modified_by`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `person_title_option`
--

LOCK TABLES `person_title_option` WRITE;
/*!40000 ALTER TABLE `person_title_option` DISABLE KEYS */;
/*!40000 ALTER TABLE `person_title_option` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `person_to_training`
--


SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `person_to_training` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `person_id` int(11) NOT NULL,
  `training_id` int(11) NOT NULL,
  `created_by` int(11) DEFAULT NULL,
  `timestamp_created` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_idx` (`person_id`,`training_id`),
  UNIQUE KEY `training_person_uniq` (`training_id`,`person_id`),
  KEY `created_by` (`created_by`),
  KEY `person_id` (`person_id`),
  KEY `training_id` (`training_id`),
  CONSTRAINT `person_to_training_ibfk_1` FOREIGN KEY (`person_id`) REFERENCES `person` (`id`),
  CONSTRAINT `person_to_training_ibfk_2` FOREIGN KEY (`training_id`) REFERENCES `training` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `person_to_training`
--

LOCK TABLES `person_to_training` WRITE;
/*!40000 ALTER TABLE `person_to_training` DISABLE KEYS */;
/*!40000 ALTER TABLE `person_to_training` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `person_to_training_topic_option`
--


SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `person_to_training_topic_option` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `person_id` int(11) NOT NULL,
  `training_topic_option_id` int(11) NOT NULL,
  `created_by` int(11) DEFAULT NULL,
  `timestamp_created` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_idx` (`person_id`,`training_topic_option_id`),
  KEY `created_by` (`created_by`),
  KEY `person_id` (`person_id`),
  KEY `training_topic_option_id` (`training_topic_option_id`),
  CONSTRAINT `person_to_training_topic_option_ibfk_1` FOREIGN KEY (`person_id`) REFERENCES `person` (`id`),
  CONSTRAINT `person_to_training_topic_option_ibfk_4` FOREIGN KEY (`training_topic_option_id`) REFERENCES `training_topic_option` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `person_to_training_topic_option`
--

LOCK TABLES `person_to_training_topic_option` WRITE;
/*!40000 ALTER TABLE `person_to_training_topic_option` DISABLE KEYS */;
/*!40000 ALTER TABLE `person_to_training_topic_option` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `score`
--


SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `score` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `person_to_training_id` int(11) NOT NULL,
  `training_date` date NOT NULL,
  `score_value` int(11) NOT NULL,
  `score_label` varchar(255) NOT NULL,
  `modified_by` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  `timestamp_updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `timestamp_created` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `score`
--

LOCK TABLES `score` WRITE;
/*!40000 ALTER TABLE `score` DISABLE KEYS */;
/*!40000 ALTER TABLE `score` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `trainer`
--


SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `trainer` (
  `person_id` int(11) NOT NULL,
  `type_option_id` int(11) NOT NULL DEFAULT '0',
  `active_trainer_option_id` int(11) DEFAULT NULL,
  `affiliation_option_id` int(11) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `modified_by` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  `timestamp_updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `timestamp_created` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`person_id`),
  UNIQUE KEY `person_idx` (`person_id`),
  KEY `modified_by` (`modified_by`),
  KEY `created_by` (`created_by`),
  KEY `type_option_id` (`type_option_id`),
  KEY `affiliation_option_id` (`affiliation_option_id`),
  CONSTRAINT `trainer_ibfk_1` FOREIGN KEY (`person_id`) REFERENCES `person` (`id`),
  CONSTRAINT `trainer_ibfk_2` FOREIGN KEY (`type_option_id`) REFERENCES `trainer_type_option` (`id`),
  CONSTRAINT `trainer_ibfk_3` FOREIGN KEY (`affiliation_option_id`) REFERENCES `trainer_affiliation_option` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `trainer`
--

LOCK TABLES `trainer` WRITE;
/*!40000 ALTER TABLE `trainer` DISABLE KEYS */;
/*!40000 ALTER TABLE `trainer` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `trainer_affiliation_option`
--


SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `trainer_affiliation_option` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `trainer_affiliation_phrase` varchar(128) NOT NULL DEFAULT '',
  `modified_by` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  `timestamp_updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `timestamp_created` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name_unique` (`trainer_affiliation_phrase`),
  KEY `created_by` (`created_by`),
  KEY `modified_by` (`modified_by`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `trainer_affiliation_option`
--

LOCK TABLES `trainer_affiliation_option` WRITE;
/*!40000 ALTER TABLE `trainer_affiliation_option` DISABLE KEYS */;
/*!40000 ALTER TABLE `trainer_affiliation_option` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `trainer_history`
--


SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `trainer_history` (
  `vid` int(11) NOT NULL AUTO_INCREMENT,
  `pvid` int(11) NOT NULL,
  `person_id` int(11) NOT NULL,
  `type_option_id` int(11) NOT NULL DEFAULT '0',
  `active_trainer_option_id` int(11) DEFAULT NULL,
  `affiliation_option_id` int(11) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `modified_by` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  `timestamp_updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `timestamp_created` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`vid`),
  KEY `type_option_id` (`type_option_id`),
  KEY `affiliation_option_id` (`affiliation_option_id`),
  KEY `trainer_history_ibfk_1` (`person_id`),
  CONSTRAINT `trainer_history_ibfk_1` FOREIGN KEY (`person_id`) REFERENCES `person` (`id`),
  CONSTRAINT `trainer_history_ibfk_2` FOREIGN KEY (`type_option_id`) REFERENCES `trainer_type_option` (`id`),
  CONSTRAINT `trainer_history_ibfk_3` FOREIGN KEY (`affiliation_option_id`) REFERENCES `trainer_affiliation_option` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `trainer_history`
--

LOCK TABLES `trainer_history` WRITE;
/*!40000 ALTER TABLE `trainer_history` DISABLE KEYS */;
/*!40000 ALTER TABLE `trainer_history` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `trainer_language_option`
--


SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `trainer_language_option` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `language_phrase` varchar(128) NOT NULL DEFAULT '',
  `modified_by` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  `timestamp_updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `timestamp_created` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name_unique` (`language_phrase`),
  KEY `modified_by` (`modified_by`),
  KEY `created_by` (`created_by`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `trainer_language_option`
--

LOCK TABLES `trainer_language_option` WRITE;
/*!40000 ALTER TABLE `trainer_language_option` DISABLE KEYS */;
/*!40000 ALTER TABLE `trainer_language_option` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `trainer_skill_option`
--


SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `trainer_skill_option` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `trainer_skill_phrase` varchar(128) NOT NULL DEFAULT '',
  `modified_by` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  `timestamp_updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `timestamp_created` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name_unique` (`trainer_skill_phrase`),
  KEY `modified_by` (`modified_by`),
  KEY `created_by` (`created_by`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `trainer_skill_option`
--

LOCK TABLES `trainer_skill_option` WRITE;
/*!40000 ALTER TABLE `trainer_skill_option` DISABLE KEYS */;
/*!40000 ALTER TABLE `trainer_skill_option` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `trainer_to_trainer_language_option`
--


SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `trainer_to_trainer_language_option` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `trainer_id` int(11) NOT NULL,
  `trainer_language_option_id` int(11) NOT NULL,
  `is_primary` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `created_by` int(11) DEFAULT NULL,
  `timestamp_created` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `created_by` (`created_by`),
  KEY `trainer_language_option_id` (`trainer_language_option_id`),
  KEY `trainer_id` (`trainer_id`),
  CONSTRAINT `trainer_to_trainer_language_option_ibfk_1` FOREIGN KEY (`trainer_language_option_id`) REFERENCES `trainer_language_option` (`id`),
  CONSTRAINT `trainer_to_trainer_language_option_ibfk_2` FOREIGN KEY (`trainer_id`) REFERENCES `trainer` (`person_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `trainer_to_trainer_language_option`
--

LOCK TABLES `trainer_to_trainer_language_option` WRITE;
/*!40000 ALTER TABLE `trainer_to_trainer_language_option` DISABLE KEYS */;
/*!40000 ALTER TABLE `trainer_to_trainer_language_option` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `trainer_to_trainer_skill_option`
--


SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `trainer_to_trainer_skill_option` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `trainer_id` int(11) NOT NULL,
  `trainer_skill_option_id` int(11) NOT NULL,
  `created_by` int(11) DEFAULT NULL,
  `timestamp_created` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `created_by` (`created_by`),
  KEY `trainer_id` (`trainer_id`),
  KEY `trainer_skill_option_id` (`trainer_skill_option_id`),
  CONSTRAINT `trainer_to_trainer_skill_option_ibfk_1` FOREIGN KEY (`trainer_id`) REFERENCES `trainer` (`person_id`),
  CONSTRAINT `trainer_to_trainer_skill_option_ibfk_2` FOREIGN KEY (`trainer_skill_option_id`) REFERENCES `trainer_skill_option` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `trainer_to_trainer_skill_option`
--

LOCK TABLES `trainer_to_trainer_skill_option` WRITE;
/*!40000 ALTER TABLE `trainer_to_trainer_skill_option` DISABLE KEYS */;
/*!40000 ALTER TABLE `trainer_to_trainer_skill_option` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `trainer_type_option`
--


SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `trainer_type_option` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `trainer_type_phrase` varchar(128) NOT NULL DEFAULT '',
  `modified_by` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  `timestamp_updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `timestamp_created` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name_unique` (`trainer_type_phrase`),
  KEY `created_by` (`created_by`),
  KEY `modified_by` (`modified_by`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `trainer_type_option`
--

LOCK TABLES `trainer_type_option` WRITE;
/*!40000 ALTER TABLE `trainer_type_option` DISABLE KEYS */;
/*!40000 ALTER TABLE `trainer_type_option` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `training`
--


SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `training` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `course_id` int(11) DEFAULT NULL,
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
  `uuid` char(38) DEFAULT '',
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
  PRIMARY KEY (`id`),
  KEY `created_by` (`created_by`),
  KEY `modified_by` (`modified_by`),
  KEY `course_id` (`course_id`),
  KEY `training_title_option_id` (`training_title_option_id`),
  CONSTRAINT `training_ibfk_2` FOREIGN KEY (`training_title_option_id`) REFERENCES `training_title_option` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `training`
--

LOCK TABLES `training` WRITE;
/*!40000 ALTER TABLE `training` DISABLE KEYS */;
/*!40000 ALTER TABLE `training` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `training_approval_history`
--


SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `training_approval_history` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `training_id` int(11) NOT NULL,
  `approval_status` enum('resubmitted','rejected','approved','new') NOT NULL DEFAULT 'new',
  `message` text,
  `recipients` varchar(512) DEFAULT NULL,
  `created_by` int(11) NOT NULL,
  `timestamp_created` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `training_id` (`training_id`),
  KEY `created_by` (`created_by`),
  KEY `created_by_2` (`created_by`),
  KEY `time_idx` (`timestamp_created`),
  CONSTRAINT `training_idx` FOREIGN KEY (`training_id`) REFERENCES `training` (`id`),
  CONSTRAINT `user_idx` FOREIGN KEY (`created_by`) REFERENCES `user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `training_approval_history`
--

LOCK TABLES `training_approval_history` WRITE;
/*!40000 ALTER TABLE `training_approval_history` DISABLE KEYS */;
/*!40000 ALTER TABLE `training_approval_history` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `training_category_option`
--


SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `training_category_option` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `training_category_phrase` varchar(128) NOT NULL,
  `modified_by` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  `timestamp_updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `timestamp_created` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name_unique` (`training_category_phrase`),
  KEY `modified_by` (`modified_by`),
  KEY `created_by` (`created_by`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `training_category_option`
--

LOCK TABLES `training_category_option` WRITE;
/*!40000 ALTER TABLE `training_category_option` DISABLE KEYS */;
/*!40000 ALTER TABLE `training_category_option` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `training_category_option_to_training_title_option`
--


SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `training_category_option_to_training_title_option` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `training_category_option_id` int(11) NOT NULL,
  `training_title_option_id` int(11) NOT NULL,
  `created_by` int(11) DEFAULT NULL,
  `timestamp_created` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_training_cat` (`training_category_option_id`,`training_title_option_id`),
  KEY `training_category_option_id` (`training_category_option_id`),
  KEY `created_by` (`created_by`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `training_category_option_to_training_title_option`
--

LOCK TABLES `training_category_option_to_training_title_option` WRITE;
/*!40000 ALTER TABLE `training_category_option_to_training_title_option` DISABLE KEYS */;
/*!40000 ALTER TABLE `training_category_option_to_training_title_option` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `training_custom_1_option`
--


SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `training_custom_1_option` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `custom1_phrase` varchar(128) NOT NULL DEFAULT '',
  `modified_by` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  `timestamp_updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `timestamp_created` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name_unique` (`custom1_phrase`),
  KEY `modified_by` (`modified_by`),
  KEY `created_by` (`created_by`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `training_custom_1_option`
--

LOCK TABLES `training_custom_1_option` WRITE;
/*!40000 ALTER TABLE `training_custom_1_option` DISABLE KEYS */;
/*!40000 ALTER TABLE `training_custom_1_option` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `training_custom_2_option`
--


SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `training_custom_2_option` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `custom2_phrase` varchar(128) NOT NULL DEFAULT '',
  `modified_by` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  `timestamp_updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `timestamp_created` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name_unique` (`custom2_phrase`),
  KEY `modified_by` (`modified_by`),
  KEY `created_by` (`created_by`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `training_custom_2_option`
--

LOCK TABLES `training_custom_2_option` WRITE;
/*!40000 ALTER TABLE `training_custom_2_option` DISABLE KEYS */;
/*!40000 ALTER TABLE `training_custom_2_option` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `training_funding_option`
--


SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `training_funding_option` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `funding_phrase` varchar(128) NOT NULL DEFAULT '',
  `is_default` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `modified_by` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  `timestamp_updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `timestamp_created` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name_unique` (`funding_phrase`),
  KEY `created_by` (`created_by`),
  KEY `modified_by` (`modified_by`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `training_funding_option`
--

LOCK TABLES `training_funding_option` WRITE;
/*!40000 ALTER TABLE `training_funding_option` DISABLE KEYS */;
/*!40000 ALTER TABLE `training_funding_option` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `training_got_curriculum_option`
--


SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `training_got_curriculum_option` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `training_got_curriculum_phrase` varchar(128) NOT NULL DEFAULT '',
  `modified_by` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  `timestamp_updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `timestamp_created` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name_unique` (`training_got_curriculum_phrase`),
  KEY `created_by` (`created_by`),
  KEY `modified_by` (`modified_by`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `training_got_curriculum_option`
--

LOCK TABLES `training_got_curriculum_option` WRITE;
/*!40000 ALTER TABLE `training_got_curriculum_option` DISABLE KEYS */;
/*!40000 ALTER TABLE `training_got_curriculum_option` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `training_level_option`
--


SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `training_level_option` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `training_level_phrase` varchar(128) NOT NULL DEFAULT '',
  `is_default` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `modified_by` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  `timestamp_updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `timestamp_created` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name_unique` (`training_level_phrase`),
  KEY `modified_by` (`modified_by`),
  KEY `created_by` (`created_by`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `training_level_option`
--

LOCK TABLES `training_level_option` WRITE;
/*!40000 ALTER TABLE `training_level_option` DISABLE KEYS */;
/*!40000 ALTER TABLE `training_level_option` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `training_location`
--


SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `training_location` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `training_location_name` varchar(128) NOT NULL DEFAULT '',
  `location_id` int(10) unsigned DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  `timestamp_updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `timestamp_created` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `modified_by` (`modified_by`),
  KEY `created_by` (`created_by`),
  KEY `training_location_ibfk_9` (`location_id`),
  CONSTRAINT `training_location_ibfk_1` FOREIGN KEY (`modified_by`) REFERENCES `user` (`id`),
  CONSTRAINT `training_location_ibfk_2` FOREIGN KEY (`created_by`) REFERENCES `user` (`id`),
  CONSTRAINT `training_location_ibfk_9` FOREIGN KEY (`location_id`) REFERENCES `location` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `training_location`
--

LOCK TABLES `training_location` WRITE;
/*!40000 ALTER TABLE `training_location` DISABLE KEYS */;
/*!40000 ALTER TABLE `training_location` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `training_method_option`
--


SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `training_method_option` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `training_method_phrase` varchar(128) NOT NULL,
  `modified_by` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  `timestamp_updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `timestamp_created` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name_unique` (`training_method_phrase`),
  KEY `modified_by` (`modified_by`),
  KEY `created_by` (`created_by`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `training_method_option`
--

LOCK TABLES `training_method_option` WRITE;
/*!40000 ALTER TABLE `training_method_option` DISABLE KEYS */;
/*!40000 ALTER TABLE `training_method_option` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `training_organizer_option`
--


SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `training_organizer_option` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `training_organizer_phrase` varchar(128) NOT NULL DEFAULT '',
  `is_default` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `modified_by` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  `timestamp_updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `timestamp_created` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name_unique` (`training_organizer_phrase`),
  KEY `created_by` (`created_by`),
  KEY `modified_by` (`modified_by`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `training_organizer_option`
--

LOCK TABLES `training_organizer_option` WRITE;
/*!40000 ALTER TABLE `training_organizer_option` DISABLE KEYS */;
/*!40000 ALTER TABLE `training_organizer_option` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `training_pepfar_categories_option`
--


SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `training_pepfar_categories_option` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
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
  KEY `created_by` (`created_by`),
  KEY `modified_by` (`modified_by`),
  KEY `id_training_method_option_id` (`id_training_method_option_id`),
  CONSTRAINT `training_pepfar_categories_option_ibfk_1` FOREIGN KEY (`id_training_method_option_id`) REFERENCES `training_method_option` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `training_pepfar_categories_option`
--

LOCK TABLES `training_pepfar_categories_option` WRITE;
/*!40000 ALTER TABLE `training_pepfar_categories_option` DISABLE KEYS */;
/*!40000 ALTER TABLE `training_pepfar_categories_option` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `training_recommend`
--


SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `training_recommend` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `person_qualification_option_id` int(11) DEFAULT NULL,
  `training_topic_option_id` int(11) DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  `timestamp_updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `timestamp_created` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `person_qualification_option_id` (`person_qualification_option_id`,`training_topic_option_id`),
  KEY `created_by` (`created_by`),
  KEY `modified_by` (`modified_by`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `training_recommend`
--

LOCK TABLES `training_recommend` WRITE;
/*!40000 ALTER TABLE `training_recommend` DISABLE KEYS */;
/*!40000 ALTER TABLE `training_recommend` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `training_title_option`
--


SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `training_title_option` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `training_title_phrase` varchar(128) NOT NULL,
  `modified_by` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  `timestamp_updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `timestamp_created` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name_unique` (`training_title_phrase`),
  KEY `modified_by` (`modified_by`),
  KEY `created_by` (`created_by`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `training_title_option`
--

LOCK TABLES `training_title_option` WRITE;
/*!40000 ALTER TABLE `training_title_option` DISABLE KEYS */;
/*!40000 ALTER TABLE `training_title_option` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `training_to_person_qualification_option`
--


SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `training_to_person_qualification_option` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `training_id` int(11) NOT NULL,
  `person_qualification_option_id` int(11) NOT NULL,
  `person_count_na` int(11) NOT NULL DEFAULT '0',
  `person_count_male` int(11) NOT NULL DEFAULT '0',
  `person_count_female` int(11) NOT NULL DEFAULT '0',
  `created_by` int(11) DEFAULT NULL,
  `timestamp_created` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `training_qual_uniq` (`training_id`,`person_qualification_option_id`),
  KEY `person_qualification_option_id` (`person_qualification_option_id`),
  CONSTRAINT `training_to_person_qualification_option_ibfk_2` FOREIGN KEY (`person_qualification_option_id`) REFERENCES `person_qualification_option` (`id`),
  CONSTRAINT `training_to_person_qualification_option_ibfk_3` FOREIGN KEY (`training_id`) REFERENCES `training` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `training_to_person_qualification_option`
--

LOCK TABLES `training_to_person_qualification_option` WRITE;
/*!40000 ALTER TABLE `training_to_person_qualification_option` DISABLE KEYS */;
/*!40000 ALTER TABLE `training_to_person_qualification_option` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `training_to_trainer`
--


SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `training_to_trainer` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `trainer_id` int(11) NOT NULL,
  `training_id` int(11) NOT NULL,
  `duration_days` float NOT NULL,
  `created_by` int(11) DEFAULT NULL,
  `timestamp_created` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_training` (`trainer_id`,`training_id`),
  KEY `created_by` (`created_by`),
  KEY `trainer_id` (`trainer_id`),
  KEY `training_id` (`training_id`),
  CONSTRAINT `training_to_trainer_ibfk_1` FOREIGN KEY (`trainer_id`) REFERENCES `trainer` (`person_id`),
  CONSTRAINT `training_to_trainer_ibfk_2` FOREIGN KEY (`training_id`) REFERENCES `training` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `training_to_trainer`
--

LOCK TABLES `training_to_trainer` WRITE;
/*!40000 ALTER TABLE `training_to_trainer` DISABLE KEYS */;
/*!40000 ALTER TABLE `training_to_trainer` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `training_to_training_funding_option`
--


SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `training_to_training_funding_option` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `training_id` int(11) NOT NULL,
  `training_funding_option_id` int(11) NOT NULL,
  `funding_amount` float DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `timestamp_created` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_training_cat` (`training_funding_option_id`,`training_id`),
  KEY `training_id` (`training_id`),
  KEY `created_by` (`created_by`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `training_to_training_funding_option`
--

LOCK TABLES `training_to_training_funding_option` WRITE;
/*!40000 ALTER TABLE `training_to_training_funding_option` DISABLE KEYS */;
/*!40000 ALTER TABLE `training_to_training_funding_option` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `training_to_training_pepfar_categories_option`
--


SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `training_to_training_pepfar_categories_option` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `training_id` int(11) NOT NULL,
  `training_pepfar_categories_option_id` int(11) NOT NULL,
  `training_method_option_id` int(11) DEFAULT NULL,
  `duration_days` float NOT NULL,
  `created_by` int(11) DEFAULT NULL,
  `timestamp_created` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_training_cat` (`training_pepfar_categories_option_id`,`training_id`),
  KEY `training_id` (`training_id`),
  KEY `created_by` (`created_by`),
  CONSTRAINT `training_to_training_pepfar_categories_option_ibfk_1` FOREIGN KEY (`training_id`) REFERENCES `training` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `training_to_training_pepfar_categories_option`
--

LOCK TABLES `training_to_training_pepfar_categories_option` WRITE;
/*!40000 ALTER TABLE `training_to_training_pepfar_categories_option` DISABLE KEYS */;
/*!40000 ALTER TABLE `training_to_training_pepfar_categories_option` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `training_to_training_topic_option`
--


SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `training_to_training_topic_option` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `training_id` int(11) NOT NULL,
  `training_topic_option_id` int(11) NOT NULL,
  `created_by` int(11) DEFAULT NULL,
  `timestamp_created` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_training_cat` (`training_topic_option_id`,`training_id`),
  KEY `training_id` (`training_id`),
  KEY `created_by` (`created_by`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `training_to_training_topic_option`
--

LOCK TABLES `training_to_training_topic_option` WRITE;
/*!40000 ALTER TABLE `training_to_training_topic_option` DISABLE KEYS */;
/*!40000 ALTER TABLE `training_to_training_topic_option` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `training_topic_option`
--


SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `training_topic_option` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `training_topic_phrase` varchar(128) NOT NULL DEFAULT '',
  `is_default` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `modified_by` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  `timestamp_updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `timestamp_created` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name_unique` (`training_topic_phrase`),
  KEY `created_by` (`created_by`),
  KEY `modified_by` (`modified_by`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `training_topic_option`
--

LOCK TABLES `training_topic_option` WRITE;
/*!40000 ALTER TABLE `training_topic_option` DISABLE KEYS */;
/*!40000 ALTER TABLE `training_topic_option` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `translation`
--


SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `translation` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `key_phrase` varchar(128) NOT NULL DEFAULT '',
  `phrase` varchar(128) NOT NULL DEFAULT '',
  `modified_by` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  `timestamp_updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `timestamp_created` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `modified_by` (`modified_by`),
  KEY `created_by` (`created_by`),
  CONSTRAINT `translation_ibfk_1` FOREIGN KEY (`modified_by`) REFERENCES `user` (`id`),
  CONSTRAINT `translation_ibfk_2` FOREIGN KEY (`created_by`) REFERENCES `user` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=39 DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `translation`
--

LOCK TABLES `translation` WRITE;
/*!40000 ALTER TABLE `translation` DISABLE KEYS */;
INSERT INTO `translation` VALUES (0,'Application Name','TrainSMART',1,NULL,0,'2010-09-20 00:31:29','0000-00-00 00:00:00'),(1,'Country','Country',1,NULL,0,'2008-04-28 20:17:48','0000-00-00 00:00:00'),(2,'Region A (Province)','Region',1,NULL,0,'2010-09-20 00:31:29','0000-00-00 00:00:00'),(3,'Region B (Health District)','Province',1,NULL,0,'2010-09-20 00:31:29','0000-00-00 00:00:00'),(4,'City or Town','City',1,NULL,0,'2010-09-20 00:31:29','0000-00-00 00:00:00'),(5,'Training Name','__training_name__',1,NULL,0,'2010-09-20 00:30:37','0000-00-00 00:00:00'),(6,'Training Organizer','__training_organizer__',1,NULL,0,'2010-09-20 00:30:37','0000-00-00 00:00:00'),(7,'Training Level','__training_level__',1,NULL,0,'2010-09-20 00:30:37','0000-00-00 00:00:00'),(8,'Pre Test Score','__pre_test_score__',1,NULL,0,'2010-09-20 00:30:37','0000-00-00 00:00:00'),(9,'Post Test Score','__post_test_score__',1,NULL,0,'2010-09-20 00:30:37','0000-00-00 00:00:00'),(10,'Training Custom 1','__custom_field_1__',1,NULL,0,'2010-09-20 00:30:37','0000-00-00 00:00:00'),(11,'Training Custom 2','__custom_field_2__',1,NULL,0,'2010-09-20 00:30:37','0000-00-00 00:00:00'),(12,'National ID','__national_id__',1,NULL,0,'2009-12-15 01:06:20','0000-00-00 00:00:00'),(13,'People Custom 1','__custom_field_1__',1,NULL,0,'2009-12-15 01:06:20','0000-00-00 00:00:00'),(14,'People Custom 2','__custom_field_2__',1,NULL,0,'2009-12-15 01:06:20','0000-00-00 00:00:00'),(15,'Is Active','__is_active__',1,NULL,0,'2009-12-15 01:06:20','2008-04-28 20:41:05'),(16,'PEPFAR Category','__pepfar_category__',1,NULL,0,'2010-09-20 00:30:37','2008-04-28 20:42:56'),(17,'First Name','__first_name__',1,NULL,0,'2009-12-15 01:06:20','2008-12-03 18:12:29'),(18,'Middle Name','__middle_name__',1,NULL,0,'2009-12-15 01:06:20','2008-12-03 18:12:38'),(19,'Last Name','__last_name__',1,NULL,0,'2009-12-15 01:06:20','2008-12-03 18:12:46'),(20,'Training of Trainers','__training_of_trainers__',1,NULL,0,'2010-09-20 00:30:37','0000-00-00 00:00:00'),(21,'Course Objectives','__course_objectives__',1,NULL,0,'2010-09-20 00:30:37','0000-00-00 00:00:00'),(22,'Training Category','__training_category__',1,NULL,0,'2010-09-20 00:30:37','0000-00-00 00:00:00'),(23,'Training Topic','__training_topic__',1,NULL,0,'2010-09-20 00:30:37','0000-00-00 00:00:00'),(24,'GOT Curriculum','__national_curriculum__',1,NULL,0,'2010-09-20 00:30:37','0000-00-00 00:00:00'),(25,'GOT Comment','__nat_curriculum_comment__',1,NULL,0,'2010-09-20 00:30:37','0000-00-00 00:00:00'),(26,'Refresher Course','__refresher_course__',1,NULL,0,'2010-09-20 00:30:37','0000-00-00 00:00:00'),(27,'Comments','__comments__',1,NULL,0,'2010-09-20 00:30:37','0000-00-00 00:00:00'),(28,'File Number','__file_number__',1,NULL,0,'2009-12-15 01:06:20','0000-00-00 00:00:00'),(30,'Primary Language','__1st_language__',1,NULL,0,'2010-09-20 00:30:37','2009-11-19 03:36:42'),(31,'Secondary Language','__2nd_language__',1,NULL,0,'2010-09-20 00:30:37','2009-11-19 03:36:57'),(32,'Funding Amount','__funding_amt__',1,NULL,0,'2010-09-20 00:30:37','2009-11-19 03:37:19'),(33,'Training Method','__training_method__',1,NULL,0,'2010-09-20 00:30:37','2009-11-19 03:37:48'),(34,'Title','__title__',1,NULL,0,'2009-12-15 01:06:20','2009-11-20 20:59:19'),(35,'Suffix','__suffix__',1,NULL,0,'2009-12-15 01:06:20','2009-11-20 20:59:30'),(36,'Age','Age',NULL,NULL,0,'2009-11-20 20:59:57','2009-11-20 20:59:57'),(37,'Facility','__facility__',1,NULL,0,'2009-12-15 01:06:29','2009-11-20 22:24:55'),(38,'Region C (Local Region)','County',1,NULL,0,'2010-09-20 00:31:29','0000-00-00 00:00:00');
/*!40000 ALTER TABLE `translation` ENABLE KEYS */;
insert into translation (uuid, key_phrase, phrase) values (uuid(), 'Facility Custom 1', 'Custom 1');
UNLOCK TABLES;

--
-- Table structure for table `user`
--


SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
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
  KEY `modified_by` (`modified_by`),
  KEY `created_by` (`created_by`),
  CONSTRAINT `template_ibfk_1` FOREIGN KEY (`modified_by`) REFERENCES `user` (`id`),
  CONSTRAINT `template_ibfk_2` FOREIGN KEY (`created_by`) REFERENCES `user` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` VALUES (0,'system','','','','',NULL,'',NULL,NULL,0,'2008-03-11 21:17:59','2008-03-11 21:17:59','0000-00-00 00:00:00'),(1,'admin','6a204bd89f3c8348afd5c77c717a097a','admin@example.net','Admin','Admin',NULL,'',1,NULL,0,'2010-10-22 19:16:37','2008-02-27 20:15:43','2010-10-22 19:16:37');
/*!40000 ALTER TABLE `user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_to_acl`
--


SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `user_to_acl` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `acl_id` enum('edit_course','view_course','edit_people','view_people','view_create_reports','edit_country_options','add_edit_users','training_organizer_option_all','training_title_option_all','approve_trainings') NOT NULL DEFAULT 'view_course',
  `user_id` int(11) NOT NULL,
  `created_by` int(11) DEFAULT NULL,
  `timestamp_created` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `created_by` (`created_by`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `user_to_acl_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `user_to_acl`
--

LOCK TABLES `user_to_acl` WRITE;
/*!40000 ALTER TABLE `user_to_acl` DISABLE KEYS */;
INSERT INTO `user_to_acl` VALUES (1,'add_edit_users',1,NULL,'2008-04-28 20:03:31'),(3,'edit_course',1,1,'2008-04-28 20:16:19'),(4,'edit_people',1,1,'2008-04-28 20:16:19'),(5,'view_create_reports',1,1,'2008-04-28 20:16:19'),(6,'training_organizer_option_all',1,NULL,'2008-12-03 18:10:51'),(9,'approve_trainings',1,1,'2009-11-20 22:39:34'),(14,'training_title_option_all',1,1,'2009-12-08 20:11:00'),(15,'edit_country_options',1,1,'2009-12-08 20:11:00');
/*!40000 ALTER TABLE `user_to_acl` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_to_organizer_access`
--


SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `user_to_organizer_access` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `training_organizer_option_id` int(11) DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `created_by` int(11) DEFAULT NULL,
  `timestamp_created` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `created_by` (`created_by`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `user_to_organizer_access`
--

LOCK TABLES `user_to_organizer_access` WRITE;
/*!40000 ALTER TABLE `user_to_organizer_access` DISABLE KEYS */;
/*!40000 ALTER TABLE `user_to_organizer_access` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2010-11-03 21:11:30

insert into `acl` ( `id`, `acl`) values ( 'admin_files', 'admin_files');

ALTER TABLE `user_to_acl` CHANGE COLUMN `acl_id` `acl_id` enum('edit_course','view_course','edit_people','view_people','view_create_reports','edit_country_options','add_edit_users','training_organizer_option_all','training_title_option_all','approve_trainings','admin_files') NOT NULL DEFAULT 'view_course';


-- Desktop
#evaluation
ALTER TABLE `evaluation` ADD COLUMN `uuid` char(36) AFTER `id`;
ALTER TABLE `evaluation` ADD UNIQUE `uuid_idx`(uuid);
ALTER TABLE `evaluation` CHANGE COLUMN `uuid` `uuid` char(36) DEFAULT NULL;


DELIMITER ;;
CREATE TRIGGER `evaluation_insert` BEFORE INSERT ON `evaluation` FOR EACH ROW BEGIN
SET NEW.`uuid` = IFNULL(NEW.`uuid`,UUID());
END;;
DELIMITER ;
UPDATE evaluation
SET uuid = UUID();
#evaluation_question
ALTER TABLE `evaluation_question` ADD COLUMN `uuid` char(36) AFTER `id`;
ALTER TABLE `evaluation_question` ADD UNIQUE `uuid_idx`(uuid);
ALTER TABLE `evaluation_question` CHANGE COLUMN `uuid` `uuid` char(36) DEFAULT NULL;

DELIMITER ;;
CREATE TRIGGER `evaluation_question_insert` BEFORE INSERT ON `evaluation_question` FOR EACH ROW BEGIN
SET NEW.`uuid` = IFNULL(NEW.`uuid`,UUID());
END;;
DELIMITER ;
UPDATE evaluation_question
SET uuid = UUID();
#evaluation_question_response
ALTER TABLE `evaluation_question_response` ADD COLUMN `uuid` char(36) AFTER `id`;
ALTER TABLE `evaluation_question_response` ADD UNIQUE `uuid_idx`(uuid);
ALTER TABLE `evaluation_question_response` CHANGE COLUMN `uuid` `uuid` char(36) DEFAULT NULL;

DELIMITER ;;
CREATE TRIGGER `evaluation_question_response_insert` BEFORE INSERT ON `evaluation_question_response` FOR EACH ROW BEGIN
SET NEW.`uuid` = IFNULL(NEW.`uuid`,UUID());
END;;
DELIMITER ;
UPDATE evaluation_question_response
SET uuid = UUID();
#evaluation_response
ALTER TABLE `evaluation_response` ADD COLUMN `uuid` char(36) AFTER `id`;
ALTER TABLE `evaluation_response` ADD UNIQUE `uuid_idx`(uuid);
ALTER TABLE `evaluation_response` CHANGE COLUMN `uuid` `uuid` char(36) DEFAULT NULL;

DELIMITER ;;
CREATE TRIGGER `evaluation_response_insert` BEFORE INSERT ON `evaluation_response` FOR EACH ROW BEGIN
SET NEW.`uuid` = IFNULL(NEW.`uuid`,UUID());
END;;
DELIMITER ;
UPDATE evaluation_response
SET uuid = UUID();
#evaluation_to_training
ALTER TABLE `evaluation_to_training` ADD COLUMN `uuid` char(36) AFTER `id`;
ALTER TABLE `evaluation_to_training` ADD UNIQUE `uuid_idx`(uuid);
ALTER TABLE `evaluation_to_training` CHANGE COLUMN `uuid` `uuid` char(36) DEFAULT NULL;

DELIMITER ;;
CREATE TRIGGER `evaluation_to_training_insert` BEFORE INSERT ON `evaluation_to_training` FOR EACH ROW BEGIN
SET NEW.`uuid` = IFNULL(NEW.`uuid`,UUID());
END;;
DELIMITER ;
UPDATE evaluation_to_training
SET uuid = UUID();
#external_course
ALTER TABLE `external_course` ADD COLUMN `uuid` char(36) AFTER `id`;
ALTER TABLE `external_course` ADD UNIQUE `uuid_idx`(uuid);
ALTER TABLE `external_course` CHANGE COLUMN `uuid` `uuid` char(36) DEFAULT NULL;

DELIMITER ;;
CREATE TRIGGER `external_course_insert` BEFORE INSERT ON `external_course` FOR EACH ROW BEGIN
SET NEW.`uuid` = IFNULL(NEW.`uuid`,UUID());
END;;
DELIMITER ;
UPDATE external_course
SET uuid = UUID();
#facility
ALTER TABLE `facility` DROP COLUMN `uuid`;
ALTER TABLE `facility` ADD COLUMN `uuid` char(36) AFTER `id`;
ALTER TABLE `facility` ADD UNIQUE `uuid_idx`(uuid);
ALTER TABLE `facility` CHANGE COLUMN `uuid` `uuid` char(36) DEFAULT NULL;
ALTER TABLE `facility` ADD COLUMN CUSTOM_1 varchar(255) DEFAULT '';

DELIMITER ;;
CREATE TRIGGER `facility_insert` BEFORE INSERT ON `facility` FOR EACH ROW BEGIN
SET NEW.`uuid` = IFNULL(NEW.`uuid`,UUID());
END;;
DELIMITER ;
UPDATE facility
SET uuid = UUID();
#facility_sponsor_option
ALTER TABLE `facility_sponsor_option` ADD COLUMN `uuid` char(36) AFTER `id`;
ALTER TABLE `facility_sponsor_option` ADD UNIQUE `uuid_idx`(uuid);
ALTER TABLE `facility_sponsor_option` CHANGE COLUMN `uuid` `uuid` char(36) DEFAULT NULL;

DELIMITER ;;
CREATE TRIGGER `facility_sponsor_option_insert` BEFORE INSERT ON `facility_sponsor_option` FOR EACH ROW BEGIN
SET NEW.`uuid` = IFNULL(NEW.`uuid`,UUID());
END;;
DELIMITER ;
UPDATE facility_sponsor_option
SET uuid = UUID();
#facility_type_option
ALTER TABLE `facility_type_option` ADD COLUMN `uuid` char(36) AFTER `id`;
ALTER TABLE `facility_type_option` ADD UNIQUE `uuid_idx`(uuid);
ALTER TABLE `facility_type_option` CHANGE COLUMN `uuid` `uuid` char(36) DEFAULT NULL;

DELIMITER ;;
CREATE TRIGGER `facility_type_option_insert` BEFORE INSERT ON `facility_type_option` FOR EACH ROW BEGIN
SET NEW.`uuid` = IFNULL(NEW.`uuid`,UUID());
END;;
DELIMITER ;
UPDATE facility_type_option
SET uuid = UUID();
#file
ALTER TABLE `file` ADD COLUMN `uuid` char(36) AFTER `id`;
ALTER TABLE `file` ADD UNIQUE `uuid_idx`(uuid);
ALTER TABLE `file` CHANGE COLUMN `uuid` `uuid` char(36) DEFAULT NULL;

DELIMITER ;;
CREATE TRIGGER `file_insert` BEFORE INSERT ON `file` FOR EACH ROW BEGIN
SET NEW.`uuid` = IFNULL(NEW.`uuid`,UUID());
END;;
DELIMITER ;
UPDATE file
SET uuid = UUID();
#location
ALTER TABLE `location` DROP COLUMN `uuid`;
ALTER TABLE `location` ADD COLUMN `uuid` char(36) AFTER `id`;
ALTER TABLE `location` ADD UNIQUE `uuid_idx`(uuid);
ALTER TABLE `location` CHANGE COLUMN `uuid` `uuid` char(36) DEFAULT NULL;

DELIMITER ;;
CREATE TRIGGER `location_insert` BEFORE INSERT ON `location` FOR EACH ROW BEGIN
SET NEW.`uuid` = IFNULL(NEW.`uuid`,UUID());
END;;
DELIMITER ;
UPDATE location
SET uuid = UUID();
#person
ALTER TABLE `person` DROP COLUMN `uuid`;
ALTER TABLE `person` ADD COLUMN `uuid` char(36) AFTER `id`;
ALTER TABLE `person` ADD UNIQUE `uuid_idx`(uuid);
ALTER TABLE `person` CHANGE COLUMN `uuid` `uuid` char(36) DEFAULT NULL;

DELIMITER ;;
CREATE TRIGGER `person_insert` BEFORE INSERT ON `person` FOR EACH ROW BEGIN
SET NEW.`uuid` = IFNULL(NEW.`uuid`,UUID());
END;;
DELIMITER ;
UPDATE person
SET uuid = UUID();
#person_active_trainer_option
ALTER TABLE `person_active_trainer_option` ADD COLUMN `uuid` char(36) AFTER `id`;
ALTER TABLE `person_active_trainer_option` ADD UNIQUE `uuid_idx`(uuid);
ALTER TABLE `person_active_trainer_option` CHANGE COLUMN `uuid` `uuid` char(36) DEFAULT NULL;

DELIMITER ;;
CREATE TRIGGER `person_active_trainer_option_insert` BEFORE INSERT ON `person_active_trainer_option` FOR EACH ROW BEGIN
SET NEW.`uuid` = IFNULL(NEW.`uuid`,UUID());
END;;
DELIMITER ;
UPDATE person_active_trainer_option
SET uuid = UUID();
#person_custom_1_option
ALTER TABLE `person_custom_1_option` ADD COLUMN `uuid` char(36) AFTER `id`;
ALTER TABLE `person_custom_1_option` ADD UNIQUE `uuid_idx`(uuid);
ALTER TABLE `person_custom_1_option` CHANGE COLUMN `uuid` `uuid` char(36) DEFAULT NULL;

DELIMITER ;;
CREATE TRIGGER `person_custom_1_option_insert` BEFORE INSERT ON `person_custom_1_option` FOR EACH ROW BEGIN
SET NEW.`uuid` = IFNULL(NEW.`uuid`,UUID());
END;;
DELIMITER ;
UPDATE person_custom_1_option
SET uuid = UUID();
#person_custom_2_option
ALTER TABLE `person_custom_2_option` ADD COLUMN `uuid` char(36) AFTER `id`;
ALTER TABLE `person_custom_2_option` ADD UNIQUE `uuid_idx`(uuid);
ALTER TABLE `person_custom_2_option` CHANGE COLUMN `uuid` `uuid` char(36) DEFAULT NULL;

DELIMITER ;;
CREATE TRIGGER `person_custom_2_option_insert` BEFORE INSERT ON `person_custom_2_option` FOR EACH ROW BEGIN
SET NEW.`uuid` = IFNULL(NEW.`uuid`,UUID());
END;;
DELIMITER ;
UPDATE person_custom_2_option
SET uuid = UUID();
#person_history
ALTER TABLE `person_history` DROP COLUMN `uuid`;
ALTER TABLE `person_history` ADD COLUMN `uuid` char(36) AFTER `person_id`;
ALTER TABLE `person_history` CHANGE COLUMN `uuid` `uuid` char(36) DEFAULT NULL;


#person_qualification_option
ALTER TABLE `person_qualification_option` ADD COLUMN `uuid` char(36) AFTER `id`;
ALTER TABLE `person_qualification_option` ADD UNIQUE `uuid_idx`(uuid);
ALTER TABLE `person_qualification_option` CHANGE COLUMN `uuid` `uuid` char(36) DEFAULT NULL;

DELIMITER ;;
CREATE TRIGGER `person_qualification_option_insert` BEFORE INSERT ON `person_qualification_option` FOR EACH ROW BEGIN
SET NEW.`uuid` = IFNULL(NEW.`uuid`,UUID());
END;;
DELIMITER ;
UPDATE person_qualification_option
SET uuid = UUID();
#person_responsibility_option
ALTER TABLE `person_responsibility_option` ADD COLUMN `uuid` char(36) AFTER `id`;
ALTER TABLE `person_responsibility_option` ADD UNIQUE `uuid_idx`(uuid);
ALTER TABLE `person_responsibility_option` CHANGE COLUMN `uuid` `uuid` char(36) DEFAULT NULL;

DELIMITER ;;
CREATE TRIGGER `person_responsibility_option_insert` BEFORE INSERT ON `person_responsibility_option` FOR EACH ROW BEGIN
SET NEW.`uuid` = IFNULL(NEW.`uuid`,UUID());
END;;
DELIMITER ;
UPDATE person_responsibility_option
SET uuid = UUID();
#person_suffix_option
ALTER TABLE `person_suffix_option` ADD COLUMN `uuid` char(36) AFTER `id`;
ALTER TABLE `person_suffix_option` ADD UNIQUE `uuid_idx`(uuid);
ALTER TABLE `person_suffix_option` CHANGE COLUMN `uuid` `uuid` char(36) DEFAULT NULL;

DELIMITER ;;
CREATE TRIGGER `person_suffix_option_insert` BEFORE INSERT ON `person_suffix_option` FOR EACH ROW BEGIN
SET NEW.`uuid` = IFNULL(NEW.`uuid`,UUID());
END;;
DELIMITER ;
UPDATE person_suffix_option
SET uuid = UUID();
#person_title_option
ALTER TABLE `person_title_option` ADD COLUMN `uuid` char(36) AFTER `id`;
ALTER TABLE `person_title_option` ADD UNIQUE `uuid_idx`(uuid);
ALTER TABLE `person_title_option` CHANGE COLUMN `uuid` `uuid` char(36) DEFAULT NULL;

DELIMITER ;;
CREATE TRIGGER `person_title_option_insert` BEFORE INSERT ON `person_title_option` FOR EACH ROW BEGIN
SET NEW.`uuid` = IFNULL(NEW.`uuid`,UUID());
END;;
DELIMITER ;
UPDATE person_title_option
SET uuid = UUID();
#person_to_training
ALTER TABLE `person_to_training` ADD COLUMN `uuid` char(36) AFTER `id`;
ALTER TABLE `person_to_training` ADD UNIQUE `uuid_idx`(uuid);
ALTER TABLE `person_to_training` CHANGE COLUMN `uuid` `uuid` char(36) DEFAULT NULL;

DELIMITER ;;
CREATE TRIGGER `person_to_training_insert` BEFORE INSERT ON `person_to_training` FOR EACH ROW BEGIN
SET NEW.`uuid` = IFNULL(NEW.`uuid`,UUID());
END;;
DELIMITER ;
UPDATE person_to_training
SET uuid = UUID();
#person_to_training_topic_option
ALTER TABLE `person_to_training_topic_option` ADD COLUMN `uuid` char(36) AFTER `id`;
ALTER TABLE `person_to_training_topic_option` ADD UNIQUE `uuid_idx`(uuid);
ALTER TABLE `person_to_training_topic_option` CHANGE COLUMN `uuid` `uuid` char(36) DEFAULT NULL;

DELIMITER ;;
CREATE TRIGGER `person_to_training_topic_option_insert` BEFORE INSERT ON `person_to_training_topic_option` FOR EACH ROW BEGIN
SET NEW.`uuid` = IFNULL(NEW.`uuid`,UUID());
END;;
DELIMITER ;
UPDATE person_to_training_topic_option
SET uuid = UUID();
#score
ALTER TABLE `score` ADD COLUMN `uuid` char(36) AFTER `id`;
ALTER TABLE `score` ADD UNIQUE `uuid_idx`(uuid);
ALTER TABLE `score` CHANGE COLUMN `uuid` `uuid` char(36) DEFAULT NULL;

DELIMITER ;;
CREATE TRIGGER `score_insert` BEFORE INSERT ON `score` FOR EACH ROW BEGIN
SET NEW.`uuid` = IFNULL(NEW.`uuid`,UUID());
END;;
DELIMITER ;
UPDATE score
SET uuid = UUID();
#trainer
ALTER TABLE `trainer` ADD COLUMN `uuid` char(36) AFTER `person_id`;
ALTER TABLE `trainer` ADD UNIQUE `uuid_idx`(uuid);
ALTER TABLE `trainer` CHANGE COLUMN `uuid` `uuid` char(36) DEFAULT NULL;

DELIMITER ;;
CREATE TRIGGER `trainer_insert` BEFORE INSERT ON `trainer` FOR EACH ROW BEGIN
SET NEW.`uuid` = IFNULL(NEW.`uuid`,UUID());
END;;
DELIMITER ;
UPDATE trainer
SET uuid = UUID();
#trainer_affiliation_option
ALTER TABLE `trainer_affiliation_option` ADD COLUMN `uuid` char(36) AFTER `id`;
ALTER TABLE `trainer_affiliation_option` ADD UNIQUE `uuid_idx`(uuid);
ALTER TABLE `trainer_affiliation_option` CHANGE COLUMN `uuid` `uuid` char(36) DEFAULT NULL;

DELIMITER ;;
CREATE TRIGGER `trainer_affiliation_option_insert` BEFORE INSERT ON `trainer_affiliation_option` FOR EACH ROW BEGIN
SET NEW.`uuid` = IFNULL(NEW.`uuid`,UUID());
END;;
DELIMITER ;
UPDATE trainer_affiliation_option
SET uuid = UUID();
#trainer_history
ALTER TABLE `trainer_history` ADD COLUMN `uuid` char(36) AFTER `person_id`;
ALTER TABLE `trainer_history` CHANGE COLUMN `uuid` `uuid` char(36) DEFAULT NULL;


#trainer_language_option
ALTER TABLE `trainer_language_option` ADD COLUMN `uuid` char(36) AFTER `id`;
ALTER TABLE `trainer_language_option` ADD UNIQUE `uuid_idx`(uuid);
ALTER TABLE `trainer_language_option` CHANGE COLUMN `uuid` `uuid` char(36) DEFAULT NULL;

DELIMITER ;;
CREATE TRIGGER `trainer_language_option_insert` BEFORE INSERT ON `trainer_language_option` FOR EACH ROW BEGIN
SET NEW.`uuid` = IFNULL(NEW.`uuid`,UUID());
END;;
DELIMITER ;
UPDATE trainer_language_option
SET uuid = UUID();
#trainer_skill_option
ALTER TABLE `trainer_skill_option` ADD COLUMN `uuid` char(36) AFTER `id`;
ALTER TABLE `trainer_skill_option` ADD UNIQUE `uuid_idx`(uuid);
ALTER TABLE `trainer_skill_option` CHANGE COLUMN `uuid` `uuid` char(36) DEFAULT NULL;

DELIMITER ;;
CREATE TRIGGER `trainer_skill_option_insert` BEFORE INSERT ON `trainer_skill_option` FOR EACH ROW BEGIN
SET NEW.`uuid` = IFNULL(NEW.`uuid`,UUID());
END;;
DELIMITER ;
UPDATE trainer_skill_option
SET uuid = UUID();
#trainer_to_trainer_language_option
ALTER TABLE `trainer_to_trainer_language_option` ADD COLUMN `uuid` char(36) AFTER `id`;
ALTER TABLE `trainer_to_trainer_language_option` ADD UNIQUE `uuid_idx`(uuid);
ALTER TABLE `trainer_to_trainer_language_option` CHANGE COLUMN `uuid` `uuid` char(36) DEFAULT NULL;

DELIMITER ;;
CREATE TRIGGER `trainer_to_trainer_language_option_insert` BEFORE INSERT ON `trainer_to_trainer_language_option` FOR EACH ROW BEGIN
SET NEW.`uuid` = IFNULL(NEW.`uuid`,UUID());
END;;
DELIMITER ;
UPDATE trainer_to_trainer_language_option
SET uuid = UUID();
#trainer_to_trainer_skill_option
ALTER TABLE `trainer_to_trainer_skill_option` ADD COLUMN `uuid` char(36) AFTER `id`;
ALTER TABLE `trainer_to_trainer_skill_option` ADD UNIQUE `uuid_idx`(uuid);
ALTER TABLE `trainer_to_trainer_skill_option` CHANGE COLUMN `uuid` `uuid` char(36) DEFAULT NULL;

DELIMITER ;;
CREATE TRIGGER `trainer_to_trainer_skill_option_insert` BEFORE INSERT ON `trainer_to_trainer_skill_option` FOR EACH ROW BEGIN
SET NEW.`uuid` = IFNULL(NEW.`uuid`,UUID());
END;;
DELIMITER ;
UPDATE trainer_to_trainer_skill_option
SET uuid = UUID();
#trainer_type_option
ALTER TABLE `trainer_type_option` ADD COLUMN `uuid` char(36) AFTER `id`;
ALTER TABLE `trainer_type_option` ADD UNIQUE `uuid_idx`(uuid);
ALTER TABLE `trainer_type_option` CHANGE COLUMN `uuid` `uuid` char(36) DEFAULT NULL;

DELIMITER ;;
CREATE TRIGGER `trainer_type_option_insert` BEFORE INSERT ON `trainer_type_option` FOR EACH ROW BEGIN
SET NEW.`uuid` = IFNULL(NEW.`uuid`,UUID());
END;;
DELIMITER ;
UPDATE trainer_type_option
SET uuid = UUID();
#training
ALTER TABLE `training` DROP COLUMN `uuid`;
ALTER TABLE `training` ADD COLUMN `uuid` char(36) AFTER `id`;
ALTER TABLE `training` ADD UNIQUE `uuid_idx`(uuid);
ALTER TABLE `training` CHANGE COLUMN `uuid` `uuid` char(36) DEFAULT NULL;

DELIMITER ;;
CREATE TRIGGER `training_insert` BEFORE INSERT ON `training` FOR EACH ROW BEGIN
SET NEW.`uuid` = IFNULL(NEW.`uuid`,UUID());
END;;
DELIMITER ;
UPDATE training
SET uuid = UUID();
#training_approval_history
ALTER TABLE `training_approval_history` ADD COLUMN `uuid` char(36) AFTER `id`;
ALTER TABLE `training_approval_history` ADD UNIQUE `uuid_idx`(uuid);
ALTER TABLE `training_approval_history` CHANGE COLUMN `uuid` `uuid` char(36) DEFAULT NULL;

DELIMITER ;;
CREATE TRIGGER `training_approval_history_insert` BEFORE INSERT ON `training_approval_history` FOR EACH ROW BEGIN
SET NEW.`uuid` = IFNULL(NEW.`uuid`,UUID());
END;;
DELIMITER ;
UPDATE training_approval_history
SET uuid = UUID();
#training_category_option
ALTER TABLE `training_category_option` ADD COLUMN `uuid` char(36) AFTER `id`;
ALTER TABLE `training_category_option` ADD UNIQUE `uuid_idx`(uuid);
ALTER TABLE `training_category_option` CHANGE COLUMN `uuid` `uuid` char(36) DEFAULT NULL;

DELIMITER ;;
CREATE TRIGGER `training_category_option_insert` BEFORE INSERT ON `training_category_option` FOR EACH ROW BEGIN
SET NEW.`uuid` = IFNULL(NEW.`uuid`,UUID());
END;;
DELIMITER ;
UPDATE training_category_option
SET uuid = UUID();
#training_category_option_to_training_title_option
ALTER TABLE `training_category_option_to_training_title_option` ADD COLUMN `uuid` char(36) AFTER `id`;
ALTER TABLE `training_category_option_to_training_title_option` ADD UNIQUE `uuid_idx`(uuid);
ALTER TABLE `training_category_option_to_training_title_option` CHANGE COLUMN `uuid` `uuid` char(36) DEFAULT NULL;

DELIMITER ;;
CREATE TRIGGER `training_category_option_to_training_title_option_insert` BEFORE INSERT ON `training_category_option_to_training_title_option` FOR EACH ROW BEGIN
SET NEW.`uuid` = IFNULL(NEW.`uuid`,UUID());
END;;
DELIMITER ;
UPDATE training_category_option_to_training_title_option
SET uuid = UUID();
#training_custom_1_option
ALTER TABLE `training_custom_1_option` ADD COLUMN `uuid` char(36) AFTER `id`;
ALTER TABLE `training_custom_1_option` ADD UNIQUE `uuid_idx`(uuid);
ALTER TABLE `training_custom_1_option` CHANGE COLUMN `uuid` `uuid` char(36) DEFAULT NULL;

DELIMITER ;;
CREATE TRIGGER `training_custom_1_option_insert` BEFORE INSERT ON `training_custom_1_option` FOR EACH ROW BEGIN
SET NEW.`uuid` = IFNULL(NEW.`uuid`,UUID());
END;;
DELIMITER ;
UPDATE training_custom_1_option
SET uuid = UUID();
#training_custom_2_option
ALTER TABLE `training_custom_2_option` ADD COLUMN `uuid` char(36) AFTER `id`;
ALTER TABLE `training_custom_2_option` ADD UNIQUE `uuid_idx`(uuid);
ALTER TABLE `training_custom_2_option` CHANGE COLUMN `uuid` `uuid` char(36) DEFAULT NULL;

DELIMITER ;;
CREATE TRIGGER `training_custom_2_option_insert` BEFORE INSERT ON `training_custom_2_option` FOR EACH ROW BEGIN
SET NEW.`uuid` = IFNULL(NEW.`uuid`,UUID());
END;;
DELIMITER ;
UPDATE training_custom_2_option
SET uuid = UUID();
#training_funding_option
ALTER TABLE `training_funding_option` ADD COLUMN `uuid` char(36) AFTER `id`;
ALTER TABLE `training_funding_option` ADD UNIQUE `uuid_idx`(uuid);
ALTER TABLE `training_funding_option` CHANGE COLUMN `uuid` `uuid` char(36) DEFAULT NULL;

DELIMITER ;;
CREATE TRIGGER `training_funding_option_insert` BEFORE INSERT ON `training_funding_option` FOR EACH ROW BEGIN
SET NEW.`uuid` = IFNULL(NEW.`uuid`,UUID());
END;;
DELIMITER ;
UPDATE training_funding_option
SET uuid = UUID();
#training_got_curriculum_option
ALTER TABLE `training_got_curriculum_option` ADD COLUMN `uuid` char(36) AFTER `id`;
ALTER TABLE `training_got_curriculum_option` ADD UNIQUE `uuid_idx`(uuid);
ALTER TABLE `training_got_curriculum_option` CHANGE COLUMN `uuid` `uuid` char(36) DEFAULT NULL;

DELIMITER ;;
CREATE TRIGGER `training_got_curriculum_option_insert` BEFORE INSERT ON `training_got_curriculum_option` FOR EACH ROW BEGIN
SET NEW.`uuid` = IFNULL(NEW.`uuid`,UUID());
END;;
DELIMITER ;
UPDATE training_got_curriculum_option
SET uuid = UUID();
#training_level_option
ALTER TABLE `training_level_option` ADD COLUMN `uuid` char(36) AFTER `id`;
ALTER TABLE `training_level_option` ADD UNIQUE `uuid_idx`(uuid);
ALTER TABLE `training_level_option` CHANGE COLUMN `uuid` `uuid` char(36) DEFAULT NULL;

DELIMITER ;;
CREATE TRIGGER `training_level_option_insert` BEFORE INSERT ON `training_level_option` FOR EACH ROW BEGIN
SET NEW.`uuid` = IFNULL(NEW.`uuid`,UUID());
END;;
DELIMITER ;
UPDATE training_level_option
SET uuid = UUID();
#training_location
ALTER TABLE `training_location` ADD COLUMN `uuid` char(36) AFTER `id`;
ALTER TABLE `training_location` ADD UNIQUE `uuid_idx`(uuid);
ALTER TABLE `training_location` CHANGE COLUMN `uuid` `uuid` char(36) DEFAULT NULL;

DELIMITER ;;
CREATE TRIGGER `training_location_insert` BEFORE INSERT ON `training_location` FOR EACH ROW BEGIN
SET NEW.`uuid` = IFNULL(NEW.`uuid`,UUID());
END;;
DELIMITER ;
UPDATE training_location
SET uuid = UUID();
#training_method_option
ALTER TABLE `training_method_option` ADD COLUMN `uuid` char(36) AFTER `id`;
ALTER TABLE `training_method_option` ADD UNIQUE `uuid_idx`(uuid);
ALTER TABLE `training_method_option` CHANGE COLUMN `uuid` `uuid` char(36) DEFAULT NULL;

DELIMITER ;;
CREATE TRIGGER `training_method_option_insert` BEFORE INSERT ON `training_method_option` FOR EACH ROW BEGIN
SET NEW.`uuid` = IFNULL(NEW.`uuid`,UUID());
END;;
DELIMITER ;
UPDATE training_method_option
SET uuid = UUID();
#training_organizer_option
ALTER TABLE `training_organizer_option` ADD COLUMN `uuid` char(36) AFTER `id`;
ALTER TABLE `training_organizer_option` ADD UNIQUE `uuid_idx`(uuid);
ALTER TABLE `training_organizer_option` CHANGE COLUMN `uuid` `uuid` char(36) DEFAULT NULL;

DELIMITER ;;
CREATE TRIGGER `training_organizer_option_insert` BEFORE INSERT ON `training_organizer_option` FOR EACH ROW BEGIN
SET NEW.`uuid` = IFNULL(NEW.`uuid`,UUID());
END;;
DELIMITER ;
UPDATE training_organizer_option
SET uuid = UUID();
#training_pepfar_categories_option
ALTER TABLE `training_pepfar_categories_option` ADD COLUMN `uuid` char(36) AFTER `id`;
ALTER TABLE `training_pepfar_categories_option` ADD UNIQUE `uuid_idx`(uuid);
ALTER TABLE `training_pepfar_categories_option` CHANGE COLUMN `uuid` `uuid` char(36) DEFAULT NULL;

DELIMITER ;;
CREATE TRIGGER `training_pepfar_categories_option_insert` BEFORE INSERT ON `training_pepfar_categories_option` FOR EACH ROW BEGIN
SET NEW.`uuid` = IFNULL(NEW.`uuid`,UUID());
END;;
DELIMITER ;
UPDATE training_pepfar_categories_option
SET uuid = UUID();
#training_recommend
ALTER TABLE `training_recommend` ADD COLUMN `uuid` char(36) AFTER `id`;
ALTER TABLE `training_recommend` ADD UNIQUE `uuid_idx`(uuid);
ALTER TABLE `training_recommend` CHANGE COLUMN `uuid` `uuid` char(36) DEFAULT NULL;

DELIMITER ;;
CREATE TRIGGER `training_recommend_insert` BEFORE INSERT ON `training_recommend` FOR EACH ROW BEGIN
SET NEW.`uuid` = IFNULL(NEW.`uuid`,UUID());
END;;
DELIMITER ;
UPDATE training_recommend
SET uuid = UUID();
#training_title_option
ALTER TABLE `training_title_option` ADD COLUMN `uuid` char(36) AFTER `id`;
ALTER TABLE `training_title_option` ADD UNIQUE `uuid_idx`(uuid);
ALTER TABLE `training_title_option` CHANGE COLUMN `uuid` `uuid` char(36) DEFAULT NULL;

DELIMITER ;;
CREATE TRIGGER `training_title_option_insert` BEFORE INSERT ON `training_title_option` FOR EACH ROW BEGIN
SET NEW.`uuid` = IFNULL(NEW.`uuid`,UUID());
END;;
DELIMITER ;
UPDATE training_title_option
SET uuid = UUID();
#training_to_person_qualification_option
ALTER TABLE `training_to_person_qualification_option` ADD COLUMN `uuid` char(36) AFTER `id`;
ALTER TABLE `training_to_person_qualification_option` ADD UNIQUE `uuid_idx`(uuid);
ALTER TABLE `training_to_person_qualification_option` CHANGE COLUMN `uuid` `uuid` char(36) DEFAULT NULL;

DELIMITER ;;
CREATE TRIGGER `training_to_person_qualification_option_insert` BEFORE INSERT ON `training_to_person_qualification_option` FOR EACH ROW BEGIN
SET NEW.`uuid` = IFNULL(NEW.`uuid`,UUID());
END;;
DELIMITER ;
UPDATE training_to_person_qualification_option
SET uuid = UUID();
#training_to_trainer
ALTER TABLE `training_to_trainer` ADD COLUMN `uuid` char(36) AFTER `id`;
ALTER TABLE `training_to_trainer` ADD UNIQUE `uuid_idx`(uuid);
ALTER TABLE `training_to_trainer` CHANGE COLUMN `uuid` `uuid` char(36) DEFAULT NULL;

DELIMITER ;;
CREATE TRIGGER `training_to_trainer_insert` BEFORE INSERT ON `training_to_trainer` FOR EACH ROW BEGIN
SET NEW.`uuid` = IFNULL(NEW.`uuid`,UUID());
END;;
DELIMITER ;
UPDATE training_to_trainer
SET uuid = UUID();
#training_to_training_funding_option
ALTER TABLE `training_to_training_funding_option` ADD COLUMN `uuid` char(36) AFTER `id`;
ALTER TABLE `training_to_training_funding_option` ADD UNIQUE `uuid_idx`(uuid);
ALTER TABLE `training_to_training_funding_option` CHANGE COLUMN `uuid` `uuid` char(36) DEFAULT NULL;

DELIMITER ;;
CREATE TRIGGER `training_to_training_funding_option_insert` BEFORE INSERT ON `training_to_training_funding_option` FOR EACH ROW BEGIN
SET NEW.`uuid` = IFNULL(NEW.`uuid`,UUID());
END;;
DELIMITER ;
UPDATE training_to_training_funding_option
SET uuid = UUID();
#training_to_training_pepfar_categories_option
ALTER TABLE `training_to_training_pepfar_categories_option` ADD COLUMN `uuid` char(36) AFTER `id`;
ALTER TABLE `training_to_training_pepfar_categories_option` ADD UNIQUE `uuid_idx`(uuid);
ALTER TABLE `training_to_training_pepfar_categories_option` CHANGE COLUMN `uuid` `uuid` char(36) DEFAULT NULL;

DELIMITER ;;
CREATE TRIGGER `training_to_training_pepfar_categories_option_insert` BEFORE INSERT ON `training_to_training_pepfar_categories_option` FOR EACH ROW BEGIN
SET NEW.`uuid` = IFNULL(NEW.`uuid`,UUID());
END;;
DELIMITER ;
UPDATE training_to_training_pepfar_categories_option
SET uuid = UUID();
#training_to_training_topic_option
ALTER TABLE `training_to_training_topic_option` ADD COLUMN `uuid` char(36) AFTER `id`;
ALTER TABLE `training_to_training_topic_option` ADD UNIQUE `uuid_idx`(uuid);
ALTER TABLE `training_to_training_topic_option` CHANGE COLUMN `uuid` `uuid` char(36) DEFAULT NULL;

DELIMITER ;;
CREATE TRIGGER `training_to_training_topic_option_insert` BEFORE INSERT ON `training_to_training_topic_option` FOR EACH ROW BEGIN
SET NEW.`uuid` = IFNULL(NEW.`uuid`,UUID());
END;;
DELIMITER ;
UPDATE training_to_training_topic_option
SET uuid = UUID();
#training_topic_option
ALTER TABLE `training_topic_option` ADD COLUMN `uuid` char(36) AFTER `id`;
ALTER TABLE `training_topic_option` ADD UNIQUE `uuid_idx`(uuid);
ALTER TABLE `training_topic_option` CHANGE COLUMN `uuid` `uuid` char(36) DEFAULT NULL;

DELIMITER ;;
CREATE TRIGGER `training_topic_option_insert` BEFORE INSERT ON `training_topic_option` FOR EACH ROW BEGIN
SET NEW.`uuid` = IFNULL(NEW.`uuid`,UUID());
END;;
DELIMITER ;
UPDATE training_topic_option
SET uuid = UUID();
#translation
ALTER TABLE `translation` ADD COLUMN `uuid` char(36) AFTER `id`;
ALTER TABLE `translation` ADD UNIQUE `uuid_idx`(uuid);
ALTER TABLE `translation` CHANGE COLUMN `uuid` `uuid` char(36) DEFAULT NULL;

DELIMITER ;;
CREATE TRIGGER `translation_insert` BEFORE INSERT ON `translation` FOR EACH ROW BEGIN
SET NEW.`uuid` = IFNULL(NEW.`uuid`,UUID());
END;;
DELIMITER ;
UPDATE translation
SET uuid = UUID();
#user
ALTER TABLE `user` ADD COLUMN `uuid` char(36) AFTER `id`;
ALTER TABLE `user` ADD UNIQUE `uuid_idx`(uuid);
ALTER TABLE `user` CHANGE COLUMN `uuid` `uuid` char(36) DEFAULT NULL;

DELIMITER ;;
CREATE TRIGGER `user_insert` BEFORE INSERT ON `user` FOR EACH ROW BEGIN
SET NEW.`uuid` = IFNULL(NEW.`uuid`,UUID());
END;;
DELIMITER ;
UPDATE user
SET uuid = UUID();


CREATE TABLE `syncfile` (
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



-- ----------------------------
--  Table structure for `synclog`
-- ----------------------------
CREATE TABLE `synclog` (
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
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `syncalias` (
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
  KEY `syncfile_id_4` (`syncfile_id`),
  CONSTRAINT `file_fk` FOREIGN KEY (`syncfile_id`) REFERENCES `syncfile` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


ALTER TABLE `person` CHANGE COLUMN `primary_responsibility_option_id` `primary_responsibility_option_id` int(11) DEFAULT '0', CHANGE COLUMN `secondary_responsibility_option_id` `secondary_responsibility_option_id` int(11) DEFAULT '0';

ALTER TABLE `person_history` CHANGE COLUMN `primary_responsibility_option_id` `primary_responsibility_option_id` int(11) DEFAULT '0', CHANGE COLUMN `secondary_responsibility_option_id` `secondary_responsibility_option_id` int(11) DEFAULT '0';

ALTER TABLE `facility` DROP INDEX `facility_name`, ADD UNIQUE `facility_name`(facility_name, location_id);

CREATE TABLE `age_range_option` (
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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

insert into `age_range_option` values('1',0x756e6b6e6f776e,'1',null,null,'0','2011-03-31 15:58:37','0000-00-00 00:00:00'),
 ('2',0x3c3130,'0',null,null,'0','2011-03-31 15:58:53','0000-00-00 00:00:00'),
 ('3',0x31302d3134,'0',null,null,'0','2011-03-31 15:58:59','0000-00-00 00:00:00'),
 ('4',0x31352d3139,'0',null,null,'0','2011-03-31 15:59:05','0000-00-00 00:00:00'),
 ('5',0x32302d3235,'0',null,null,'0','2011-03-31 15:59:11','0000-00-00 00:00:00'),
 ('6',0x32352b,'0',null,null,'0','2011-03-31 15:59:17','0000-00-00 00:00:00');

 ALTER TABLE `training_to_person_qualification_option` ADD COLUMN `age_range_option_id` int(11) UNSIGNED NOT NULL DEFAULT '0' AFTER `person_count_female`;


ALTER TABLE `training_to_person_qualification_option` DROP INDEX `training_qual_uniq`, ADD UNIQUE `training_qual_uniq`(training_id, person_qualification_option_id, age_range_option_id);


/* ======================================================== */
/* ======================================================== */
/* 10/07/2011                                               */
/* ======================================================== */
/* ======================================================== */

# sync data models for some databases (just do for all)
delimiter |
create procedure sync_schema () begin
if not exists(select * from information_schema.columns where table_schema = database() and table_name = 'translation' and column_name = 'uuid') then
  alter table translation add column uuid char(36) default null after id;
  alter table translation add unique key uuid_idx (uuid);
end if;
end|
delimiter ;
call sync_schema();
drop procedure sync_schema;


# create new security acl for ability to download/upload application
alter table user_to_acl change column acl_id acl_id enum('edit_course','view_course','edit_people','view_people','view_create_reports','edit_country_options','add_edit_users','training_organizer_option_all','training_title_option_all','approve_trainings','admin_files','use_offline_app') NOT NULL DEFAULT 'view_course';
delete from acl where id = 'use_offline_app';
insert into acl (id, acl) values ('use_offline_app', 'use_offline_app');
alter table _system add column display_external_classes tinyint(1) NOT NULL default 0;
alter table _system add column display_responsibility_me tinyint(1) NOT NULL default 0;
alter table _system add column display_highest_ed_level tinyint(1) NOT NULL default 0;
alter table _system add column display_attend_reason tinyint(1) NOT NULL default 0;
alter table _system add column display_primary_responsibility tinyint(1) NOT NULL default 0;
alter table _system add column display_secondary_responsibility tinyint(1) NOT NULL default 0;

CREATE TABLE  `person_education_level_option` (
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
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

CREATE TABLE  `person_attend_reason_option` (
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
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

delete from person_attend_reason_option where id = 0 or attend_reason_phrase = 'Other';
insert into person_attend_reason_option (uuid, attend_reason_phrase) values (uuid(), 'Other');
update person_attend_reason_option set id = 0 where lcase(attend_reason_phrase) like '%other%';


alter table person add column higest_edu_level_option_id int NULL;
alter table person add column attend_reason_option_id int NULL;
alter table person add column attend_reason_other varchar(255) NULL;
alter table person add column me_responsibility varchar(255) NULL;

delete from translation where key_phrase = 'M&E Responsibility' and phrase = 'M&E Responsibility';
delete from translation where key_phrase = 'Highest Edu Level' and phrase = 'Highest Edu Level';
delete from translation where key_phrase = 'Highest Education Level' and phrase = 'Highest Education Level';
delete from translation where key_phrase = 'Reason Attending' and phrase = 'Reason Attending';
delete from translation where key_phrase = 'Primary Responsibility' and phrase = 'Primary Responsibility';
delete from translation where key_phrase = 'Secondary Responsibility' and phrase = 'Secondary Responsibility';
insert into translation (uuid, key_phrase, phrase) values (uuid(), 'M&E Responsibility', 'M&E Responsibility');
insert into translation (uuid, key_phrase, phrase) values (uuid(), 'Highest Education Level', 'Highest Education Level');
insert into translation (uuid, key_phrase, phrase) values (uuid(), 'Reason Attending', 'Reason Attending');
insert into translation (uuid, key_phrase, phrase) values (uuid(), 'Primary Responsibility', 'Primary Responsibility');
insert into translation (uuid, key_phrase, phrase) values (uuid(), 'Secondary Responsibility', 'Secondary Responsibility');



/* ======================================================== */
/* ======================================================== */
/* 10/10/2011                                               */
/* ======================================================== */
/* ======================================================== */

delimiter |
create procedure sync_schema () begin
if exists(select * from information_schema.columns where table_schema = database() and table_name = 'person' and column_name = 'highest_level_option_id') then
  alter table person change column highest_level_option_id highest_edu_level_option_id INT NULL;
end if;
end|
delimiter ;
call sync_schema();
drop procedure sync_schema;



/* ======================================================== */
/* ======================================================== */
/* 10/11/2011                                               */
/* ======================================================== */
/* ======================================================== */

alter table person change column primary_responsibility_option_id primary_responsibility_option_id int null;
alter table person change column secondary_responsibility_option_id secondary_responsibility_option_id int null;


/* ======================================================== */
/* ======================================================== */
/* 10/17/2011                                               */
/* ======================================================== */
/* ======================================================== */

# sync data models for some databases (just do for all)
DELIMITER |
CREATE PROCEDURE sync_schema () BEGIN
IF EXISTS(SELECT * FROM information_schema.columns WHERE table_schema = database() AND table_name = 'person_secondary_responsibility_option') THEN
  DROP TABLE person_secondary_responsibility_option;
END IF;
IF EXISTS(SELECT * FROM information_schema.columns WHERE table_schema = database() AND table_name = 'person_primary_responsibility_option') THEN
  RENAME TABLE person_primary_responsibility_option TO person_responsibility_option;
END IF;
IF NOT EXISTS(SELECT * FROM information_schema.columns WHERE table_schema = database() AND table_name = 'person_responsibility_option' AND column_name = 'uuid') THEN
  ALTER TABLE person_responsibility_option add column UUID CHAR(36) default null after id;
  ALTER TABLE person_responsibility_option add unique key uuid_idx (UUID);
END IF;
END|
DELIMITER ;
CALL sync_schema();
DROP PROCEDURE sync_schema;


RENAME TABLE person_responsibility_option TO person_primary_responsibility_option;

CREATE TABLE  `person_secondary_responsibility_option` (
  `id` int(11) NOT NULL,
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
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

TRUNCATE TABLE person_secondary_responsibility_option;

INSERT INTO person_secondary_responsibility_option (
  `id`,
  `uuid`,
  `responsibility_phrase`,
  `modified_by`,
  `created_by`,
  `is_deleted`,
  `timestamp_updated`,
  `timestamp_created`
)
SELECT
  `id`,
  `uuid`,
  `responsibility_phrase`,
  `modified_by`,
  `created_by`,
  `is_deleted`,
  `timestamp_updated`,
  `timestamp_created`
FROM person_primary_responsibility_option
ORDER BY ID;

# Needed for stupid Mysql barf on primary key value of zero
SET SQL_MODE=NO_AUTO_VALUE_ON_ZERO;

ALTER TABLE person_secondary_responsibility_option CHANGE COLUMN `id` `id` int NOT NULL AUTO_INCREMENT;



/* ======================================================== */
/* ======================================================== */
/* 11/02/2011                                               */
/* ======================================================== */
/* ======================================================== */

delimiter |
create procedure sync_schema () begin
if exists(select * from information_schema.columns where table_schema = database() and table_name = 'person' and column_name = 'higest_edu_level_option_id') then
  alter table person change column higest_edu_level_option_id highest_edu_level_option_id INT NULL;
end if;
end|
delimiter ;
call sync_schema();
drop procedure sync_schema;

/* ======================================================== */
/* ======================================================== */
/* TRAINSMART 4.00                                                                                               */
/* ======================================================== */
/* ======================================================== */

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET FOREIGN_KEY_CHECKS=0 */;

-- Dumping structure for table acl
CREATE TABLE IF NOT EXISTS `acl` (
  `id` varchar(32) NOT NULL default '',
  `acl` varchar(32) NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


-- Dumping structure for table addresses
CREATE TABLE IF NOT EXISTS `addresses` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `address1` varchar(150) NOT NULL default '',
  `address2` varchar(150) NOT NULL default '',
  `city` varchar(150) NOT NULL default '',
  `postalcode` varchar(15) NOT NULL default '',
  `state` varchar(50) NOT NULL default '',
  `country` varchar(150) NOT NULL default '',
  `id_addresstype` int(10) unsigned NOT NULL default '0',
  `id_geog1` varchar(50) NOT NULL default '0',
  `id_geog2` varchar(50) NOT NULL default '0',
  `id_geog3` varchar(50) NOT NULL default '0',
  `locationid` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `idx2` (`address2`,`id_geog1`,`id_addresstype`,`id_geog2`,`country`,`city`,`id_geog3`,`state`,`postalcode`,`address1`),
  KEY `idxlookups` (`locationid`,`id_geog3`,`id_geog2`,`id_geog1`,`id_addresstype`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- Data exporting was unselected.


-- Dumping structure for table age_range_option
CREATE TABLE IF NOT EXISTS `age_range_option` (
  `id` int(11) NOT NULL auto_increment,
  `age_range_phrase` varchar(128) NOT NULL default '',
  `is_default` tinyint(1) unsigned NOT NULL default '0',
  `modified_by` int(11) default NULL,
  `created_by` int(11) default NULL,
  `is_deleted` tinyint(1) NOT NULL default '0',
  `timestamp_updated` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `timestamp_created` timestamp NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `name_unique` (`age_range_phrase`),
  KEY `created_by` (`created_by`),
  KEY `modified_by` (`modified_by`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

-- Data exporting was unselected.


-- Dumping structure for table cadres
CREATE TABLE IF NOT EXISTS `cadres` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `cadrename` varchar(150) collate utf8_unicode_ci NOT NULL default '',
  `cadredescription` text collate utf8_unicode_ci NOT NULL,
  `status` tinyint(3) unsigned NOT NULL default '1',
  PRIMARY KEY  (`id`),
  KEY `idx2` (`status`,`cadrename`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Data exporting was unselected.


-- Dumping structure for table classes
CREATE TABLE IF NOT EXISTS `classes` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `classname` varchar(150) NOT NULL default '',
  `startdate` varchar(20) NOT NULL default '',
  `enddate` varchar(20) NOT NULL default '',
  `instructorid` int(10) unsigned NOT NULL default '0',
  `coursetypeid` int(10) unsigned NOT NULL default '0',
  `coursetopic` varchar(150) NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `idx2` (`coursetypeid`,`instructorid`,`enddate`,`startdate`,`classname`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- Data exporting was unselected.


-- Dumping structure for table cohort
CREATE TABLE IF NOT EXISTS `cohort` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `cohortid` varchar(25) NOT NULL default '',
  `cohortname` varchar(50) NOT NULL default '',
  `startdate` date NOT NULL default '0000-00-00',
  `graddate` date NOT NULL default '0000-00-00',
  `degree` int(10) unsigned NOT NULL default '0',
  `institutionid` int(10) unsigned NOT NULL default '0',
  `cadreid` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `idx2` (`degree`,`cohortname`,`graddate`,`startdate`,`cohortid`,`institutionid`,`cadreid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- Data exporting was unselected.


-- Dumping structure for table course
CREATE TABLE IF NOT EXISTS `course` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `coursename` varchar(255) NOT NULL default '',
  `startdate` datetime NOT NULL default '0000-00-00 00:00:00',
  `courselength` int(10) unsigned NOT NULL default '0',
  `examid` int(10) unsigned NOT NULL default '0',
  `examname` varchar(255) NOT NULL default '0',
  `examdate` datetime NOT NULL default '0000-00-00 00:00:00',
  `examscore` int(10) unsigned NOT NULL default '0',
  `practicumid` int(10) unsigned NOT NULL default '0',
  `practicumname` varchar(150) NOT NULL default '0',
  `practicumhours` int(10) unsigned NOT NULL default '0',
  `practicumcomplete` int(10) unsigned NOT NULL default '0',
  `degreeid` int(10) unsigned NOT NULL default '0',
  `comment` text NOT NULL,
  `customfield1` varchar(255) NOT NULL default '0',
  `customfield2` varchar(255) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `idx2` (`startdate`,`examid`,`practicumid`,`courselength`,`degreeid`,`examdate`,`coursename`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- Data exporting was unselected.


-- Dumping structure for table evaluation
CREATE TABLE IF NOT EXISTS `evaluation` (
  `id` int(11) NOT NULL auto_increment,
  `uuid` char(36) default NULL,
  `title` varchar(255) NOT NULL default '',
  `modified_by` int(11) default NULL,
  `created_by` int(11) default NULL,
  `is_deleted` tinyint(1) NOT NULL default '0',
  `timestamp_updated` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `timestamp_created` timestamp NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `uuid_idx` (`uuid`),
  KEY `modified_by` (`modified_by`),
  KEY `created_by` (`created_by`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


-- Dumping structure for table evaluation_question
CREATE TABLE IF NOT EXISTS `evaluation_question` (
  `id` int(11) NOT NULL auto_increment,
  `uuid` char(36) default NULL,
  `evaluation_id` int(11) NOT NULL,
  `question_text` varchar(255) NOT NULL default '',
  `question_type` enum('Likert','Text') NOT NULL default 'Likert',
  `weight` int(11) default NULL,
  `modified_by` int(11) default NULL,
  `created_by` int(11) default NULL,
  `is_deleted` tinyint(1) NOT NULL default '0',
  `timestamp_updated` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `timestamp_created` timestamp NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `uuid_idx` (`uuid`),
  KEY `modified_by` (`modified_by`),
  KEY `created_by` (`created_by`),
  KEY `evaluation_id` (`evaluation_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


-- Dumping structure for table evaluation_question_response
CREATE TABLE IF NOT EXISTS `evaluation_question_response` (
  `id` int(11) NOT NULL auto_increment,
  `uuid` char(36) default NULL,
  `evaluation_response_id` int(11) default NULL,
  `evaluation_question_id` int(11) NOT NULL,
  `value_text` varchar(1024) default '',
  `value_int` int(11) default NULL,
  `modified_by` int(11) default NULL,
  `created_by` int(11) default NULL,
  `is_deleted` tinyint(1) NOT NULL default '0',
  `timestamp_updated` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `timestamp_created` timestamp NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `uuid_idx` (`uuid`),
  KEY `modified_by` (`modified_by`),
  KEY `created_by` (`created_by`),
  KEY `evaluation_id` (`evaluation_response_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


-- Dumping structure for table evaluation_response
CREATE TABLE IF NOT EXISTS `evaluation_response` (
  `id` int(11) NOT NULL auto_increment,
  `uuid` char(36) default NULL,
  `evaluation_to_training_id` int(11) NOT NULL,
  `modified_by` int(11) default NULL,
  `created_by` int(11) default NULL,
  `is_deleted` tinyint(1) NOT NULL default '0',
  `timestamp_updated` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `timestamp_created` timestamp NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `uuid_idx` (`uuid`),
  KEY `modified_by` (`modified_by`),
  KEY `created_by` (`created_by`),
  KEY `evaluation_to_training_id` (`evaluation_to_training_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


-- Dumping structure for table evaluation_to_training
CREATE TABLE IF NOT EXISTS `evaluation_to_training` (
  `id` int(11) NOT NULL auto_increment,
  `uuid` char(36) default NULL,
  `evaluation_id` int(11) NOT NULL,
  `training_id` int(11) NOT NULL,
  `created_by` int(11) default NULL,
  `timestamp_created` timestamp NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `unique_idx` (`evaluation_id`,`training_id`),
  UNIQUE KEY `uuid_idx` (`uuid`),
  KEY `created_by` (`created_by`),
  KEY `training_id` (`training_id`),
  KEY `e_id` (`evaluation_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


-- Dumping structure for table exams
CREATE TABLE IF NOT EXISTS `exams` (
  `id` int(10) unsigned NOT NULL default '0',
  `examname` varchar(150) collate utf8_unicode_ci NOT NULL default '',
  `examdate` varchar(20) collate utf8_unicode_ci NOT NULL default '',
  `examgrade` varchar(20) collate utf8_unicode_ci NOT NULL default '',
  `cohortid` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `idx2` (`cohortid`,`examgrade`,`examname`,`examdate`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Data exporting was unselected.


-- Dumping structure for table external_course
CREATE TABLE IF NOT EXISTS `external_course` (
  `id` int(11) NOT NULL auto_increment,
  `uuid` char(36) default NULL,
  `person_id` int(11) unsigned NOT NULL,
  `title` varchar(128) NOT NULL,
  `training_location` varchar(128) default '',
  `training_start_date` date default NULL,
  `training_length_value` int(11) default NULL,
  `training_length_interval` enum('day') NOT NULL default 'day',
  `training_funder` varchar(128) default '',
  `is_deleted` tinyint(1) NOT NULL default '0',
  `timestamp_created` timestamp NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `uuid_idx` (`uuid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- Data exporting was unselected.


-- Dumping structure for table facility
CREATE TABLE IF NOT EXISTS `facility` (
  `id` int(11) NOT NULL auto_increment,
  `uuid` char(36) default NULL,
  `facility_name` varchar(128) NOT NULL default '',
  `address_1` varchar(128) NOT NULL default '',
  `address_2` varchar(128) NOT NULL default '',
  `location_id` int(10) unsigned default NULL,
  `postal_code` varchar(20) default '',
  `phone` varchar(32) default '',
  `fax` varchar(32) default '',
  `sponsor_option_id` int(11) default NULL,
  `type_option_id` int(11) NOT NULL,
  `facility_comments` varchar(255) default '',
  `modified_by` int(11) default NULL,
  `created_by` int(11) default NULL,
  `is_deleted` tinyint(1) NOT NULL default '0',
  `timestamp_updated` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `timestamp_created` timestamp NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `uuid_idx` (`uuid`),
  KEY `created_by` (`created_by`),
  KEY `modified_by` (`modified_by`),
  KEY `sponsor_option_id` (`sponsor_option_id`),
  KEY `type_option_id` (`type_option_id`),
  KEY `facility_ibfk_5` (`location_id`),
  KEY `facility_name` (`facility_name`,`location_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


-- Dumping structure for table facility_partner
CREATE TABLE IF NOT EXISTS `facility_partner` (
  `id` int(11) NOT NULL auto_increment,
  `uuid` char(36) default NULL,
  `partner_name_us` varchar(64) NOT NULL default '',
  `partner_name_them` varchar(64) default '',
  `subpartner` varchar(64) default '',
  `mechanism_id` varchar(32) default '',
  `funder_name` varchar(64) default '',
  `funder_id` varchar(32) default '',
  `timestamp_created` timestamp NOT NULL default '0000-00-00 00:00:00',
  `modified_by` int(11) default NULL,
  `is_deleted` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


-- Dumping structure for table facility_sponsor_option
CREATE TABLE IF NOT EXISTS `facility_sponsor_option` (
  `id` int(11) NOT NULL auto_increment,
  `uuid` char(36) default NULL,
  `facility_sponsor_phrase` varchar(128) NOT NULL default '',
  `modified_by` int(11) default NULL,
  `created_by` int(11) default NULL,
  `is_deleted` tinyint(1) NOT NULL default '0',
  `timestamp_updated` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `timestamp_created` timestamp NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `name_unique` (`facility_sponsor_phrase`),
  UNIQUE KEY `uuid_idx` (`uuid`),
  KEY `created_by` (`created_by`),
  KEY `modified_by` (`modified_by`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


-- Dumping structure for table facility_type_option
CREATE TABLE IF NOT EXISTS `facility_type_option` (
  `id` int(11) NOT NULL auto_increment,
  `uuid` char(36) default NULL,
  `facility_type_phrase` varchar(128) NOT NULL default '',
  `modified_by` int(11) default NULL,
  `created_by` int(11) default NULL,
  `is_deleted` tinyint(1) NOT NULL default '0',
  `timestamp_updated` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `timestamp_created` timestamp NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `name_unique` (`facility_type_phrase`),
  UNIQUE KEY `uuid_idx` (`uuid`),
  KEY `modified_by` (`modified_by`),
  KEY `created_by` (`created_by`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


-- Dumping structure for table file
CREATE TABLE IF NOT EXISTS `file` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `uuid` char(36) default NULL,
  `parent_id` int(10) unsigned NOT NULL,
  `parent_table` varchar(255) NOT NULL default '0',
  `filename` varchar(255) NOT NULL,
  `filemime` varchar(255) NOT NULL,
  `filesize` int(10) unsigned NOT NULL default '0',
  `modified_by` int(11) default NULL,
  `created_by` int(11) default NULL,
  `is_deleted` tinyint(1) NOT NULL default '0',
  `timestamp_updated` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `timestamp_created` timestamp NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `uuid_idx` (`uuid`),
  KEY `parent_key` (`parent_table`,`parent_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- Data exporting was unselected.


-- Dumping structure for table Helper
CREATE TABLE IF NOT EXISTS `Helper` (
  `id` int(10) unsigned NOT NULL auto_increment,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Data exporting was unselected.


-- Dumping structure for table institution
CREATE TABLE IF NOT EXISTS `institution` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `institutionname` varchar(150) NOT NULL default '',
  `address1` varchar(150) NOT NULL default '',
  `address2` varchar(150) NOT NULL default '',
  `city` varchar(150) NOT NULL default '',
  `postalcode` varchar(150) NOT NULL default '',
  `phone` varchar(50) NOT NULL default '',
  `fax` varchar(50) NOT NULL default '',
  `type` int(10) NOT NULL default '0',
  `sponsor` int(10) NOT NULL default '0',
  `degrees` varchar(255) NOT NULL default '',
  `degreetypeid` int(10) unsigned NOT NULL default '0',
  `computercount` int(10) unsigned NOT NULL default '0',
  `tutorcount` int(10) unsigned default '0',
  `studentcount` int(10) unsigned default '0',
  `dormcount` int(10) NOT NULL default '0',
  `bedcount` int(10) NOT NULL default '0',
  `hasdormitories` tinyint(3) unsigned NOT NULL default '0',
  `tutorhousing` int(10) unsigned NOT NULL default '0',
  `tutorhouses` int(10) NOT NULL default '0',
  `yearfounded` int(10) unsigned NOT NULL default '0',
  `comments` text NOT NULL,
  `customfield1` varchar(255) NOT NULL default '',
  `customfield2` varchar(255) NOT NULL default '',
  `geography1` int(10) unsigned NOT NULL default '0',
  `geography2` int(10) unsigned NOT NULL default '0',
  `geography3` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `idx2` (`degreetypeid`,`hasdormitories`,`computercount`,`studentcount`,`tutorcount`,`dormcount`,`tutorhousing`,`yearfounded`,`tutorhouses`,`bedcount`,`institutionname`,`geography1`,`geography2`,`geography3`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- Data exporting was unselected.


-- Dumping structure for table licenses
CREATE TABLE IF NOT EXISTS `licenses` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `licensename` varchar(255) collate utf8_unicode_ci NOT NULL default '',
  `licensedate` varchar(20) collate utf8_unicode_ci NOT NULL default '',
  `cohortid` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `licensename` (`licensename`),
  KEY `licensedate` (`licensedate`),
  KEY `cohortid` (`cohortid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Data exporting was unselected.


-- Dumping structure for table link_cadre_institution
CREATE TABLE IF NOT EXISTS `link_cadre_institution` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `id_cadre` int(10) unsigned NOT NULL default '0',
  `id_institution` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `idx2` (`id_institution`,`id_cadre`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Data exporting was unselected.


-- Dumping structure for table link_cadre_tutor
CREATE TABLE IF NOT EXISTS `link_cadre_tutor` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `id_cadre` int(10) unsigned NOT NULL default '0',
  `id_tutor` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `idx2` (`id_tutor`,`id_cadre`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Data exporting was unselected.


-- Dumping structure for table link_cohorts_classes
CREATE TABLE IF NOT EXISTS `link_cohorts_classes` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `cohortid` int(10) unsigned NOT NULL default '0',
  `classid` int(10) unsigned NOT NULL default '0',
  `status` tinyint(3) unsigned NOT NULL default '1',
  PRIMARY KEY  (`id`),
  KEY `idx2` (`cohortid`,`classid`,`status`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Data exporting was unselected.


-- Dumping structure for table link_facility_addresses
CREATE TABLE IF NOT EXISTS `link_facility_addresses` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `id_facility` int(10) unsigned NOT NULL default '0',
  `id_address` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `idx2` (`id_facility`,`id_address`),
  KEY `FK_link_facility_addresses_addresses` (`id_address`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- Data exporting was unselected.


-- Dumping structure for table link_institution_address
CREATE TABLE IF NOT EXISTS `link_institution_address` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `id_institution` int(10) unsigned NOT NULL default '0',
  `id_address` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `idx2` (`id_institution`,`id_address`),
  KEY `FK_link_institution_address_addresses` (`id_address`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- Data exporting was unselected.


-- Dumping structure for table link_institution_institutiontype
CREATE TABLE IF NOT EXISTS `link_institution_institutiontype` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `id_institution` int(10) unsigned NOT NULL default '0',
  `id_institutiontype` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `idx2` (`id_institution`,`id_institutiontype`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Data exporting was unselected.


-- Dumping structure for table link_student_addresses
CREATE TABLE IF NOT EXISTS `link_student_addresses` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `id_student` int(10) unsigned NOT NULL default '0',
  `id_address` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `idx2` (`id_student`,`id_address`),
  KEY `FK_link_student_addresses_addresses` (`id_address`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- Data exporting was unselected.


-- Dumping structure for table link_student_classes
CREATE TABLE IF NOT EXISTS `link_student_classes` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `studentid` int(10) unsigned NOT NULL default '0',
  `classid` int(10) unsigned NOT NULL default '0',
  `cohortid` int(10) unsigned NOT NULL default '0',
  `linkclasscohortid` int(10) unsigned NOT NULL default '0',
  `grade` varchar(50) collate utf8_unicode_ci NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `idx2` (`studentid`,`classid`,`cohortid`,`linkclasscohortid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Data exporting was unselected.


-- Dumping structure for table link_student_cohort
CREATE TABLE IF NOT EXISTS `link_student_cohort` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `id_student` int(10) unsigned NOT NULL default '0',
  `id_cohort` int(10) unsigned NOT NULL default '0',
  `joindate` date NOT NULL default '0000-00-00',
  `joinreason` int(10) unsigned NOT NULL default '0',
  `dropdate` date NOT NULL default '0000-00-00',
  `dropreason` int(10) unsigned NOT NULL default '0',
  `isgraduated` tinyint(3) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `FK_link_student_cohort_cohort` (`id_cohort`),
  KEY `datekeys` (`joindate`,`dropdate`),
  KEY `idx2` (`isgraduated`,`id_student`,`id_cohort`,`joinreason`,`dropreason`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- Data exporting was unselected.


-- Dumping structure for table link_student_contacts
CREATE TABLE IF NOT EXISTS `link_student_contacts` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `id_student` int(10) unsigned NOT NULL default '0',
  `id_contact` int(10) unsigned NOT NULL default '0',
  `contactvalue` varchar(50) NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `idx2` (`id_student`,`id_contact`),
  KEY `FK_link_student_contacts_lookup_contacts` (`id_contact`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- Data exporting was unselected.


-- Dumping structure for table link_student_facility
CREATE TABLE IF NOT EXISTS `link_student_facility` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `id_student` int(10) unsigned NOT NULL default '0',
  `id_facility` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `idx2` (`id_student`,`id_facility`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- Data exporting was unselected.


-- Dumping structure for table link_student_funding
CREATE TABLE IF NOT EXISTS `link_student_funding` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `studentid` int(10) unsigned NOT NULL default '0',
  `fundingsource` int(10) unsigned NOT NULL default '0',
  `fundingamount` decimal(10,2) unsigned NOT NULL default '0.00',
  PRIMARY KEY  (`id`),
  KEY `idx2` (`studentid`,`fundingsource`,`fundingamount`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- Data exporting was unselected.


-- Dumping structure for table link_student_institution
CREATE TABLE IF NOT EXISTS `link_student_institution` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `id_student` int(10) unsigned NOT NULL default '0',
  `id_institution` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `idx2` (`id_student`,`id_institution`),
  KEY `FK_link_student_institution_institution` (`id_institution`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- Data exporting was unselected.


-- Dumping structure for table link_student_licenses
CREATE TABLE IF NOT EXISTS `link_student_licenses` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `studentid` int(10) unsigned NOT NULL default '0',
  `licenseid` int(10) unsigned NOT NULL default '0',
  `cohortid` int(10) unsigned NOT NULL default '0',
  `linkclasslicenseid` int(10) unsigned NOT NULL default '0',
  `grade` varchar(50) collate utf8_unicode_ci NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `idx2` (`studentid`,`licenseid`,`cohortid`,`linkclasslicenseid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Data exporting was unselected.


-- Dumping structure for table link_student_practicums
CREATE TABLE IF NOT EXISTS `link_student_practicums` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `studentid` int(10) unsigned NOT NULL default '0',
  `practicumid` int(10) unsigned NOT NULL default '0',
  `cohortid` int(10) unsigned NOT NULL default '0',
  `linkcohortpracticumid` int(10) unsigned NOT NULL default '0',
  `hourscompleted` decimal(10,2) NOT NULL default '0.00',
  `grade` varchar(50) collate utf8_unicode_ci NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `idx2` (`studentid`,`practicumid`,`cohortid`,`linkcohortpracticumid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Data exporting was unselected.


-- Dumping structure for table link_tutor_addresses
CREATE TABLE IF NOT EXISTS `link_tutor_addresses` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `id_tutor` int(10) unsigned NOT NULL default '0',
  `id_address` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `idx2` (`id_tutor`,`id_address`),
  KEY `FK_link_tutor_addresses_addresses` (`id_address`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- Data exporting was unselected.


-- Dumping structure for table link_tutor_contacts
CREATE TABLE IF NOT EXISTS `link_tutor_contacts` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `id_tutor` int(10) unsigned NOT NULL default '0',
  `id_contact` int(10) unsigned NOT NULL default '0',
  `contactvalue` varchar(50) NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `idx2` (`id_contact`,`id_tutor`),
  KEY `FK_link_tutor_contacts_tutor` (`id_tutor`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- Data exporting was unselected.


-- Dumping structure for table link_tutor_institution
CREATE TABLE IF NOT EXISTS `link_tutor_institution` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `id_tutor` int(10) unsigned NOT NULL default '0',
  `id_institution` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `idx2` (`id_tutor`,`id_institution`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Data exporting was unselected.


-- Dumping structure for table link_tutor_languages
CREATE TABLE IF NOT EXISTS `link_tutor_languages` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `id_tutor` int(10) unsigned NOT NULL default '0',
  `id_language` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `idx2` (`id_tutor`,`id_language`),
  KEY `FK_link_tutor_languages_lookup_languages` (`id_language`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- Data exporting was unselected.


-- Dumping structure for table link_tutor_tutortype
CREATE TABLE IF NOT EXISTS `link_tutor_tutortype` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `id_tutor` int(10) unsigned NOT NULL default '0',
  `id_tutortype` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `idx2` (`id_tutor`,`id_tutortype`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Data exporting was unselected.


-- Dumping structure for table location
CREATE TABLE IF NOT EXISTS `location` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `uuid` char(36) default NULL,
  `location_name` varchar(128) NOT NULL,
  `parent_id` int(10) unsigned default NULL,
  `tier` smallint(5) unsigned NOT NULL,
  `is_default` tinyint(1) NOT NULL default '0',
  `modified_by` int(11) default NULL,
  `created_by` int(11) default NULL,
  `is_deleted` tinyint(1) NOT NULL default '0',
  `timestamp_updated` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `timestamp_created` timestamp NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `uuid_idx` (`uuid`),
  KEY `parent_id` (`parent_id`),
  KEY `modified_by` (`modified_by`),
  KEY `created_by` (`created_by`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


-- Dumping structure for table location_city
CREATE TABLE IF NOT EXISTS `location_city` (
  `id` int(11) NOT NULL auto_increment,
  `city_name` varchar(128) NOT NULL default '',
  `uuid` char(38) default '',
  `parent_district_id` int(11) default NULL,
  `parent_province_id` int(11) default NULL,
  `modified_by` int(11) default NULL,
  `created_by` int(11) default NULL,
  `is_deleted` tinyint(1) NOT NULL default '0',
  `timestamp_updated` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `timestamp_created` timestamp NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`),
  KEY `modified_by` (`modified_by`),
  KEY `created_by` (`created_by`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


-- Dumping structure for table location_district
CREATE TABLE IF NOT EXISTS `location_district` (
  `id` int(11) NOT NULL auto_increment,
  `district_name` varchar(128) NOT NULL default '',
  `is_default` tinyint(1) unsigned NOT NULL default '0',
  `uuid` char(38) NOT NULL default '',
  `parent_province_id` int(11) default NULL,
  `modified_by` int(11) default NULL,
  `created_by` int(11) default NULL,
  `is_deleted` tinyint(1) NOT NULL default '0',
  `timestamp_updated` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `timestamp_created` timestamp NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`),
  KEY `modified_by` (`modified_by`),
  KEY `created_by` (`created_by`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


-- Dumping structure for table location_province
CREATE TABLE IF NOT EXISTS `location_province` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `province_name` varchar(255) NOT NULL default '',
  `is_default` tinyint(1) NOT NULL default '0',
  `uuid` char(38) NOT NULL default '',
  `modified_by` int(11) default NULL,
  `created_by` int(11) default NULL,
  `is_deleted` tinyint(1) NOT NULL default '0',
  `timestamp_updated` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `timestamp_created` timestamp NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`),
  KEY `modified_by` (`modified_by`),
  KEY `created_by` (`created_by`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


-- Dumping structure for table lookup_addresstype
CREATE TABLE IF NOT EXISTS `lookup_addresstype` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `addresstypename` varchar(50) NOT NULL default '',
  `addresstypeorder` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `idx2` (`addresstypename`,`addresstypeorder`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- Data exporting was unselected.


-- Dumping structure for table lookup_contacts
CREATE TABLE IF NOT EXISTS `lookup_contacts` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `contactname` varchar(50) NOT NULL default '',
  `contactorder` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `idx2` (`contactorder`,`contactname`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- Data exporting was unselected.


-- Dumping structure for table lookup_coursetype
CREATE TABLE IF NOT EXISTS `lookup_coursetype` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `coursetype` varchar(50) NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `idx2` (`coursetype`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- Data exporting was unselected.


-- Dumping structure for table lookup_degrees
CREATE TABLE IF NOT EXISTS `lookup_degrees` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `degree` varchar(255) collate utf8_unicode_ci NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `idx2` (`degree`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Data exporting was unselected.


-- Dumping structure for table lookup_degreetypes
CREATE TABLE IF NOT EXISTS `lookup_degreetypes` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `title` varchar(150) collate utf8_unicode_ci NOT NULL default '',
  `status` tinyint(3) unsigned NOT NULL default '1',
  PRIMARY KEY  (`id`),
  KEY `idx2` (`title`,`status`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Data exporting was unselected.


-- Dumping structure for table lookup_facilitytype
CREATE TABLE IF NOT EXISTS `lookup_facilitytype` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `facilitytypename` varchar(50) NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `idx2` (`facilitytypename`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- Data exporting was unselected.


-- Dumping structure for table lookup_fundingsources
CREATE TABLE IF NOT EXISTS `lookup_fundingsources` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `fundingname` varchar(150) collate utf8_unicode_ci NOT NULL default '',
  `fundingnote` text collate utf8_unicode_ci NOT NULL,
  `status` tinyint(3) unsigned NOT NULL default '1',
  PRIMARY KEY  (`id`),
  KEY `idx2` (`status`,`fundingname`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Data exporting was unselected.


-- Dumping structure for table lookup_gender
CREATE TABLE IF NOT EXISTS `lookup_gender` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `gendername` varchar(50) NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- Data exporting was unselected.


-- Dumping structure for table lookup_geog
CREATE TABLE IF NOT EXISTS `lookup_geog` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `geogname` varchar(50) NOT NULL default '',
  `geogparent` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `idx2` (`geogname`,`geogparent`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- Data exporting was unselected.


-- Dumping structure for table lookup_institutiontype
CREATE TABLE IF NOT EXISTS `lookup_institutiontype` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `typename` varchar(255) collate utf8_unicode_ci NOT NULL default '',
  `status` tinyint(3) unsigned NOT NULL default '1',
  PRIMARY KEY  (`id`),
  KEY `idx2` (`typename`,`status`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Data exporting was unselected.


-- Dumping structure for table lookup_languages
CREATE TABLE IF NOT EXISTS `lookup_languages` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `language` varchar(150) NOT NULL default '',
  `abbreviation` varchar(50) NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `idx2` (`abbreviation`,`language`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- Data exporting was unselected.


-- Dumping structure for table lookup_nationalities
CREATE TABLE IF NOT EXISTS `lookup_nationalities` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `nationality` varchar(50) collate utf8_unicode_ci NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `idx2` (`nationality`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Data exporting was unselected.


-- Dumping structure for table lookup_reasons
CREATE TABLE IF NOT EXISTS `lookup_reasons` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `reason` text collate utf8_unicode_ci NOT NULL,
  `reasontype` enum('join','drop') collate utf8_unicode_ci NOT NULL default 'join',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Data exporting was unselected.


-- Dumping structure for table lookup_sponsors
CREATE TABLE IF NOT EXISTS `lookup_sponsors` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `sponsorname` varchar(150) collate utf8_unicode_ci NOT NULL default '',
  `notes` text collate utf8_unicode_ci NOT NULL,
  `status` tinyint(3) unsigned NOT NULL default '1',
  PRIMARY KEY  (`id`),
  KEY `sponsorname` (`sponsorname`,`status`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Data exporting was unselected.


-- Dumping structure for table lookup_studenttype
CREATE TABLE IF NOT EXISTS `lookup_studenttype` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `studenttype` varchar(150) collate utf8_unicode_ci NOT NULL default '',
  `status` tinyint(3) unsigned NOT NULL default '1',
  PRIMARY KEY  (`id`),
  KEY `idx2` (`studenttype`,`status`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Data exporting was unselected.


-- Dumping structure for table lookup_tutortype
CREATE TABLE IF NOT EXISTS `lookup_tutortype` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `typename` varchar(150) collate utf8_unicode_ci NOT NULL default '',
  `status` tinyint(3) unsigned NOT NULL default '1',
  PRIMARY KEY  (`id`),
  KEY `typename` (`typename`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Data exporting was unselected.


-- Dumping structure for table person
CREATE TABLE IF NOT EXISTS `person` (
  `id` int(11) NOT NULL auto_increment,
  `uuid` char(36) default NULL,
  `title_option_id` int(11) default NULL,
  `first_name` varchar(32) NOT NULL default '',
  `middle_name` varchar(32) default '',
  `last_name` varchar(32) NOT NULL default '',
  `suffix_option_id` int(11) default NULL,
  `national_id` varchar(64) default '',
  `file_number` varchar(64) default NULL,
  `birthdate` date default NULL,
  `gender` enum('male','female','na') NOT NULL default 'na',
  `facility_id` int(11) NOT NULL,
  `phone_work` varchar(32) default '',
  `phone_mobile` varchar(32) default '',
  `fax` varchar(32) default '',
  `phone_home` varchar(32) default '',
  `email` varchar(64) default '',
  `email_secondary` varchar(64) default '',
  `primary_qualification_option_id` int(11) NOT NULL,
  `primary_responsibility_option_id` int(11) default NULL,
  `secondary_responsibility_option_id` int(11) default NULL,
  `comments` varchar(255) default '',
  `person_custom_1_option_id` int(11) default NULL,
  `person_custom_2_option_id` int(11) default NULL,
  `home_address_1` varchar(128) default '',
  `home_address_2` varchar(128) default '',
  `home_location_id` int(10) unsigned default NULL,
  `home_postal_code` int(11) default NULL,
  `active` enum('active','inactive','deceased') NOT NULL default 'active',
  `timestamp_created` timestamp NOT NULL default '0000-00-00 00:00:00',
  `timestamp_updated` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `created_by` int(11) default NULL,
  `modified_by` int(11) default NULL,
  `is_deleted` tinyint(1) NOT NULL default '0',
  `me_responsibility` varchar(255) default NULL,
  `highest_edu_level_option_id` int(11) default NULL,
  `attend_reason_option_id` int(11) default NULL,
  `attend_reason_other` varchar(255) default NULL,
  `highest_level_option_id` int(11) default NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `uuid_idx` (`uuid`),
  KEY `modified_by` (`modified_by`),
  KEY `created_by` (`created_by`),
  KEY `facility_id` (`facility_id`),
  KEY `home_location_ibfk_9` (`home_location_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


-- Dumping structure for table person_active_trainer_option
CREATE TABLE IF NOT EXISTS `person_active_trainer_option` (
  `id` int(11) NOT NULL auto_increment,
  `uuid` char(36) default NULL,
  `active_trainer_phrase` varchar(128) NOT NULL default '',
  `modified_by` int(11) default NULL,
  `created_by` int(11) default NULL,
  `is_deleted` tinyint(1) NOT NULL default '0',
  `timestamp_updated` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `timestamp_created` timestamp NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `name_unique` (`active_trainer_phrase`),
  UNIQUE KEY `uuid_idx` (`uuid`),
  KEY `modified_by` (`modified_by`),
  KEY `created_by` (`created_by`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- Data exporting was unselected.


-- Dumping structure for table person_attend_reason_option
CREATE TABLE IF NOT EXISTS `person_attend_reason_option` (
  `id` int(11) NOT NULL auto_increment,
  `uuid` char(36) default NULL,
  `attend_reason_phrase` varchar(128) NOT NULL default '',
  `modified_by` int(11) default NULL,
  `created_by` int(11) default NULL,
  `is_deleted` tinyint(1) NOT NULL default '0',
  `timestamp_updated` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `timestamp_created` timestamp NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `name_unique` (`attend_reason_phrase`),
  UNIQUE KEY `uuid_idx` (`uuid`),
  KEY `created_by` (`created_by`),
  KEY `modified_by` (`modified_by`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


-- Dumping structure for table person_custom_1_option
CREATE TABLE IF NOT EXISTS `person_custom_1_option` (
  `id` int(11) NOT NULL auto_increment,
  `uuid` char(36) default NULL,
  `custom1_phrase` varchar(128) NOT NULL default '',
  `modified_by` int(11) default NULL,
  `created_by` int(11) default NULL,
  `is_deleted` tinyint(1) NOT NULL default '0',
  `timestamp_updated` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `timestamp_created` timestamp NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `name_unique` (`custom1_phrase`),
  UNIQUE KEY `uuid_idx` (`uuid`),
  KEY `modified_by` (`modified_by`),
  KEY `created_by` (`created_by`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


-- Dumping structure for table person_custom_2_option
CREATE TABLE IF NOT EXISTS `person_custom_2_option` (
  `id` int(11) NOT NULL auto_increment,
  `uuid` char(36) default NULL,
  `custom2_phrase` varchar(128) NOT NULL default '',
  `modified_by` int(11) default NULL,
  `created_by` int(11) default NULL,
  `is_deleted` tinyint(1) NOT NULL default '0',
  `timestamp_updated` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `timestamp_created` timestamp NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `name_unique` (`custom2_phrase`),
  UNIQUE KEY `uuid_idx` (`uuid`),
  KEY `created_by` (`created_by`),
  KEY `modified_by` (`modified_by`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


-- Dumping structure for table person_education_level_option
CREATE TABLE IF NOT EXISTS `person_education_level_option` (
  `id` int(11) NOT NULL auto_increment,
  `uuid` char(36) default NULL,
  `education_level_phrase` varchar(128) NOT NULL default '',
  `modified_by` int(11) default NULL,
  `created_by` int(11) default NULL,
  `is_deleted` tinyint(1) NOT NULL default '0',
  `timestamp_updated` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `timestamp_created` timestamp NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `name_unique` (`education_level_phrase`),
  UNIQUE KEY `uuid_idx` (`uuid`),
  KEY `created_by` (`created_by`),
  KEY `modified_by` (`modified_by`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


-- Dumping structure for table person_history
CREATE TABLE IF NOT EXISTS `person_history` (
  `vid` int(11) NOT NULL auto_increment,
  `person_id` int(11) NOT NULL,
  `uuid` char(36) default NULL,
  `title_option_id` int(11) default NULL,
  `first_name` varchar(32) NOT NULL default '',
  `middle_name` varchar(32) default '',
  `last_name` varchar(32) NOT NULL default '',
  `suffix_option_id` int(11) default NULL,
  `national_id` varchar(64) default '',
  `file_number` varchar(64) default NULL,
  `birthdate` date default NULL,
  `gender` enum('male','female','na') NOT NULL default 'na',
  `facility_id` int(11) NOT NULL,
  `phone_work` varchar(32) default '',
  `phone_mobile` varchar(32) default '',
  `fax` varchar(32) default '',
  `phone_home` varchar(32) default '',
  `email` varchar(64) default '',
  `primary_qualification_option_id` int(11) NOT NULL,
  `primary_responsibility_option_id` int(11) default '0',
  `secondary_responsibility_option_id` int(11) default '0',
  `comments` varchar(255) default '',
  `person_custom_1_option_id` int(11) default NULL,
  `person_custom_2_option_id` int(11) default NULL,
  `active` enum('active','inactive','deceased') NOT NULL default 'active',
  `timestamp_created` timestamp NOT NULL default '0000-00-00 00:00:00',
  `modified_by` int(11) default NULL,
  `is_deleted` tinyint(1) NOT NULL default '0',
  `home_address_1` varchar(128) default '',
  `home_address_2` varchar(128) default '',
  `home_location_id` int(11) default NULL,
  `home_postal_code` int(11) default NULL,
  `email_secondary` varchar(64) default '',
  PRIMARY KEY  (`vid`),
  KEY `modified_by` (`modified_by`),
  KEY `facility_id` (`facility_id`),
  KEY `person_id` (`person_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- Data exporting was unselected.


-- Dumping structure for table person_primary_responsibility_option
CREATE TABLE IF NOT EXISTS `person_primary_responsibility_option` (
  `id` int(11) NOT NULL auto_increment,
  `uuid` char(36) default NULL,
  `responsibility_phrase` varchar(128) NOT NULL default '',
  `modified_by` int(11) default NULL,
  `created_by` int(11) default NULL,
  `is_deleted` tinyint(1) NOT NULL default '0',
  `timestamp_updated` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `timestamp_created` timestamp NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `name_unique` (`responsibility_phrase`),
  UNIQUE KEY `uuid_idx` (`uuid`),
  KEY `created_by` (`created_by`),
  KEY `modified_by` (`modified_by`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


-- Dumping structure for table person_qualification_option
CREATE TABLE IF NOT EXISTS `person_qualification_option` (
  `id` int(11) NOT NULL auto_increment,
  `uuid` char(36) default NULL,
  `parent_id` int(11) default NULL,
  `qualification_phrase` varchar(128) NOT NULL default '',
  `is_default` tinyint(1) unsigned NOT NULL default '0',
  `modified_by` int(11) default NULL,
  `created_by` int(11) default NULL,
  `is_deleted` tinyint(1) NOT NULL default '0',
  `timestamp_updated` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `timestamp_created` timestamp NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `name_unique` (`qualification_phrase`),
  UNIQUE KEY `uuid_idx` (`uuid`),
  KEY `modified_by` (`modified_by`),
  KEY `created_by` (`created_by`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


-- Dumping structure for table person_secondary_responsibility_option
CREATE TABLE IF NOT EXISTS `person_secondary_responsibility_option` (
  `id` int(11) NOT NULL auto_increment,
  `uuid` char(36) default NULL,
  `responsibility_phrase` varchar(128) NOT NULL,
  `modified_by` int(11) default NULL,
  `created_by` int(11) default NULL,
  `is_deleted` tinyint(1) NOT NULL default '0',
  `timestamp_updated` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `timestamp_created` timestamp NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `name_unique` (`responsibility_phrase`),
  UNIQUE KEY `uuid_idx` (`uuid`),
  KEY `created_by` (`created_by`),
  KEY `modified_by` (`modified_by`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


-- Dumping structure for table person_suffix_option
CREATE TABLE IF NOT EXISTS `person_suffix_option` (
  `id` int(11) NOT NULL auto_increment,
  `uuid` char(36) default NULL,
  `suffix_phrase` varchar(128) NOT NULL default '',
  `modified_by` int(11) default NULL,
  `created_by` int(11) default NULL,
  `is_deleted` tinyint(1) NOT NULL default '0',
  `timestamp_updated` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `timestamp_created` timestamp NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `name_unique` (`suffix_phrase`),
  UNIQUE KEY `uuid_idx` (`uuid`),
  KEY `created_by` (`created_by`),
  KEY `modified_by` (`modified_by`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- Data exporting was unselected.


-- Dumping structure for table person_title_option
CREATE TABLE IF NOT EXISTS `person_title_option` (
  `id` int(11) NOT NULL auto_increment,
  `uuid` char(36) default NULL,
  `title_phrase` varchar(128) NOT NULL default '',
  `modified_by` int(11) default NULL,
  `created_by` int(11) default NULL,
  `is_deleted` tinyint(1) NOT NULL default '0',
  `timestamp_updated` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `timestamp_created` timestamp NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `name_unique` (`title_phrase`),
  UNIQUE KEY `uuid_idx` (`uuid`),
  KEY `created_by` (`created_by`),
  KEY `modified_by` (`modified_by`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- Data exporting was unselected.


-- Dumping structure for table person_to_training
CREATE TABLE IF NOT EXISTS `person_to_training` (
  `id` int(11) NOT NULL auto_increment,
  `uuid` char(36) default NULL,
  `person_id` int(11) NOT NULL,
  `training_id` int(11) NOT NULL,
  `created_by` int(11) default NULL,
  `timestamp_created` timestamp NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `unique_idx` (`person_id`,`training_id`),
  UNIQUE KEY `training_person_uniq_2` (`training_id`,`person_id`),
  UNIQUE KEY `uuid_idx` (`uuid`),
  KEY `created_by` (`created_by`),
  KEY `person_id` (`person_id`),
  KEY `training_id` (`training_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


-- Dumping structure for table person_to_training_topic_option
CREATE TABLE IF NOT EXISTS `person_to_training_topic_option` (
  `id` int(11) NOT NULL auto_increment,
  `uuid` char(36) default NULL,
  `person_id` int(11) NOT NULL,
  `training_topic_option_id` int(11) NOT NULL,
  `created_by` int(11) default NULL,
  `timestamp_created` timestamp NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `unique_idx` (`person_id`,`training_topic_option_id`),
  UNIQUE KEY `uuid_idx` (`uuid`),
  KEY `created_by` (`created_by`),
  KEY `person_id` (`person_id`),
  KEY `training_topic_option_id` (`training_topic_option_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


-- Dumping structure for table practicum
CREATE TABLE IF NOT EXISTS `practicum` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `practicumname` varchar(150) collate utf8_unicode_ci NOT NULL default '',
  `practicumdate` varchar(10) collate utf8_unicode_ci NOT NULL default '',
  `facilityid` int(10) unsigned NOT NULL default '0',
  `advisorid` int(10) unsigned NOT NULL default '0',
  `hoursrequired` decimal(10,2) unsigned NOT NULL default '0.00',
  `cohortid` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `textindex` (`practicumname`,`practicumdate`),
  KEY `numberindex` (`advisorid`,`hoursrequired`,`cohortid`,`facilityid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Data exporting was unselected.


-- Dumping structure for table score
CREATE TABLE IF NOT EXISTS `score` (
  `id` int(11) NOT NULL auto_increment,
  `uuid` char(36) default NULL,
  `person_to_training_id` int(11) NOT NULL,
  `training_date` date NOT NULL,
  `score_value` int(11) NOT NULL,
  `score_label` varchar(255) NOT NULL,
  `modified_by` int(11) default NULL,
  `created_by` int(11) default NULL,
  `is_deleted` tinyint(1) NOT NULL default '0',
  `timestamp_updated` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `timestamp_created` timestamp NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `uuid_idx` (`uuid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


-- Dumping structure for table security
CREATE TABLE IF NOT EXISTS `security` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `studentid` int(10) unsigned NOT NULL default '0',
  `haspreservice` tinyint(3) unsigned NOT NULL default '0',
  `hasinstitution` tinyint(3) unsigned NOT NULL default '0',
  `hasinservice` tinyint(3) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `idx2` (`hasinservice`,`hasinstitution`,`haspreservice`,`studentid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- Data exporting was unselected.


-- Dumping structure for table student
CREATE TABLE IF NOT EXISTS `student` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `personid` int(10) unsigned NOT NULL default '0',
  `nationalityid` int(10) unsigned NOT NULL default '0',
  `cadre` int(25) NOT NULL default '0',
  `yearofstudy` int(10) unsigned NOT NULL default '0',
  `comments` varchar(255) NOT NULL default '',
  `geog1` int(10) unsigned NOT NULL default '0',
  `geog2` int(10) unsigned NOT NULL default '0',
  `geog3` int(10) unsigned NOT NULL default '0',
  `isgraduated` tinyint(3) unsigned NOT NULL default '0',
  `studentid` varchar(50) default '',
  `studenttype` int(10) unsigned NOT NULL default '0',
  `advisorid` int(10) unsigned NOT NULL default '0',
  `postgeo1` varchar(50) NOT NULL default '0',
  `postgeo2` varchar(50) NOT NULL default '0',
  `postgeo3` varchar(50) NOT NULL default '0',
  `postaddress1` varchar(255) NOT NULL default '',
  `postfacilityname` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `idx2` (`nationalityid`,`advisorid`,`personid`,`studentid`,`cadre`,`geog1`,`geog2`,`geog3`,`isgraduated`,`postgeo1`,`postgeo2`,`postgeo3`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- Data exporting was unselected.


-- Dumping structure for table syncalias
CREATE TABLE IF NOT EXISTS `syncalias` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `syncfile_id` int(11) unsigned default NULL,
  `item_type` varchar(255) default NULL,
  `left_id` int(11) unsigned NOT NULL,
  `left_uuid` char(36) NOT NULL default '',
  `right_id` int(11) unsigned NOT NULL,
  `right_uuid` char(36) NOT NULL default '',
  `created_by` int(11) default NULL,
  `timestamp_created` timestamp NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`),
  KEY `syncfile_id` (`syncfile_id`),
  KEY `syncfile_id_2` (`syncfile_id`),
  KEY `syncfile_id_3` (`syncfile_id`),
  KEY `syncfile_id_4` (`syncfile_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- Data exporting was unselected.


-- Dumping structure for table syncfile
CREATE TABLE IF NOT EXISTS `syncfile` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `filename` varchar(255) default NULL,
  `filepath` varchar(255) NOT NULL,
  `application_id` char(36) NOT NULL,
  `application_version` float(255,2) NOT NULL,
  `modified_by` int(11) default NULL,
  `created_by` int(11) default NULL,
  `timestamp_updated` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `timestamp_created` timestamp NOT NULL default '0000-00-00 00:00:00',
  `timestamp_last_sync` timestamp NOT NULL default '0000-00-00 00:00:00',
  `timestamp_completed` timestamp NULL default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


-- Dumping structure for table synclog
CREATE TABLE IF NOT EXISTS `synclog` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `fid` int(11) default NULL,
  `item_type` varchar(255) default NULL,
  `left_id` int(11) default NULL,
  `left_data` mediumtext,
  `right_id` int(11) default NULL,
  `right_data` mediumtext,
  `action` varchar(36) default NULL,
  `is_skipped` tinyint(1) unsigned default '0',
  `message` mediumtext,
  `modified_by` int(11) default NULL,
  `created_by` int(11) default NULL,
  `timestamp_updated` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `timestamp_created` timestamp NOT NULL default '0000-00-00 00:00:00',
  `timestamp_completed` timestamp NULL default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- Data exporting was unselected.


-- Dumping structure for table trainer
CREATE TABLE IF NOT EXISTS `trainer` (
  `person_id` int(11) NOT NULL,
  `uuid` char(36) default NULL,
  `type_option_id` int(11) NOT NULL default '0',
  `active_trainer_option_id` int(11) default NULL,
  `affiliation_option_id` int(11) default NULL,
  `is_active` tinyint(1) NOT NULL default '1',
  `modified_by` int(11) default NULL,
  `created_by` int(11) default NULL,
  `is_deleted` tinyint(1) NOT NULL default '0',
  `timestamp_updated` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `timestamp_created` timestamp NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`person_id`),
  UNIQUE KEY `person_idx` (`person_id`),
  UNIQUE KEY `uuid_idx` (`uuid`),
  KEY `modified_by` (`modified_by`),
  KEY `created_by` (`created_by`),
  KEY `type_option_id` (`type_option_id`),
  KEY `affiliation_option_id` (`affiliation_option_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


-- Dumping structure for table trainer_affiliation_option
CREATE TABLE IF NOT EXISTS `trainer_affiliation_option` (
  `id` int(11) NOT NULL auto_increment,
  `uuid` char(36) default NULL,
  `trainer_affiliation_phrase` varchar(128) NOT NULL default '',
  `modified_by` int(11) default NULL,
  `created_by` int(11) default NULL,
  `is_deleted` tinyint(1) NOT NULL default '0',
  `timestamp_updated` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `timestamp_created` timestamp NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `name_unique` (`trainer_affiliation_phrase`),
  UNIQUE KEY `uuid_idx` (`uuid`),
  KEY `created_by` (`created_by`),
  KEY `modified_by` (`modified_by`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


-- Dumping structure for table trainer_history
CREATE TABLE IF NOT EXISTS `trainer_history` (
  `vid` int(11) NOT NULL auto_increment,
  `pvid` int(11) NOT NULL,
  `person_id` int(11) NOT NULL,
  `uuid` char(36) default NULL,
  `type_option_id` int(11) NOT NULL default '0',
  `active_trainer_option_id` int(11) default NULL,
  `affiliation_option_id` int(11) default NULL,
  `is_active` tinyint(1) NOT NULL default '1',
  `modified_by` int(11) default NULL,
  `created_by` int(11) default NULL,
  `is_deleted` tinyint(1) NOT NULL default '0',
  `timestamp_updated` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `timestamp_created` timestamp NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`vid`),
  KEY `type_option_id` (`type_option_id`),
  KEY `affiliation_option_id` (`affiliation_option_id`),
  KEY `trainer_history_ibfk_1` (`person_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


-- Dumping structure for table trainer_language_option
CREATE TABLE IF NOT EXISTS `trainer_language_option` (
  `id` int(11) NOT NULL auto_increment,
  `uuid` char(36) default NULL,
  `language_phrase` varchar(128) NOT NULL default '',
  `modified_by` int(11) default NULL,
  `created_by` int(11) default NULL,
  `is_deleted` tinyint(1) NOT NULL default '0',
  `timestamp_updated` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `timestamp_created` timestamp NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `name_unique` (`language_phrase`),
  UNIQUE KEY `uuid_idx` (`uuid`),
  KEY `modified_by` (`modified_by`),
  KEY `created_by` (`created_by`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


-- Dumping structure for table trainer_skill_option
CREATE TABLE IF NOT EXISTS `trainer_skill_option` (
  `id` int(11) NOT NULL auto_increment,
  `uuid` char(36) default NULL,
  `trainer_skill_phrase` varchar(128) NOT NULL default '',
  `modified_by` int(11) default NULL,
  `created_by` int(11) default NULL,
  `is_deleted` tinyint(1) NOT NULL default '0',
  `timestamp_updated` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `timestamp_created` timestamp NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `name_unique` (`trainer_skill_phrase`),
  UNIQUE KEY `uuid_idx` (`uuid`),
  KEY `modified_by` (`modified_by`),
  KEY `created_by` (`created_by`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


-- Dumping structure for table trainer_to_trainer_language_option
CREATE TABLE IF NOT EXISTS `trainer_to_trainer_language_option` (
  `id` int(11) NOT NULL auto_increment,
  `uuid` char(36) default NULL,
  `trainer_id` int(11) NOT NULL,
  `trainer_language_option_id` int(11) NOT NULL,
  `is_primary` tinyint(1) unsigned NOT NULL default '0',
  `created_by` int(11) default NULL,
  `timestamp_created` timestamp NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `uuid_idx` (`uuid`),
  KEY `created_by` (`created_by`),
  KEY `trainer_language_option_id` (`trainer_language_option_id`),
  KEY `trainer_id` (`trainer_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


-- Dumping structure for table trainer_to_trainer_skill_option
CREATE TABLE IF NOT EXISTS `trainer_to_trainer_skill_option` (
  `id` int(11) NOT NULL auto_increment,
  `uuid` char(36) default NULL,
  `trainer_id` int(11) NOT NULL,
  `trainer_skill_option_id` int(11) NOT NULL,
  `created_by` int(11) default NULL,
  `timestamp_created` timestamp NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `uuid_idx` (`uuid`),
  KEY `created_by` (`created_by`),
  KEY `trainer_id` (`trainer_id`),
  KEY `trainer_skill_option_id` (`trainer_skill_option_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


-- Dumping structure for table trainer_type_option
CREATE TABLE IF NOT EXISTS `trainer_type_option` (
  `id` int(11) NOT NULL auto_increment,
  `uuid` char(36) default NULL,
  `trainer_type_phrase` varchar(128) NOT NULL default '',
  `modified_by` int(11) default NULL,
  `created_by` int(11) default NULL,
  `is_deleted` tinyint(1) NOT NULL default '0',
  `timestamp_updated` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `timestamp_created` timestamp NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `name_unique` (`trainer_type_phrase`),
  UNIQUE KEY `uuid_idx` (`uuid`),
  KEY `created_by` (`created_by`),
  KEY `modified_by` (`modified_by`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


-- Dumping structure for table training
CREATE TABLE IF NOT EXISTS `training` (
  `id` int(11) NOT NULL auto_increment,
  `uuid` char(36) default NULL,
  `training_title_option_id` int(11) NOT NULL,
  `has_known_participants` tinyint(1) unsigned NOT NULL default '1',
  `training_start_date` date NOT NULL,
  `training_end_date` date default NULL,
  `training_length_value` int(11) NOT NULL,
  `training_length_interval` enum('hour','week','day') NOT NULL default 'hour',
  `training_organizer_option_id` int(11) default '0',
  `training_location_id` int(11) default '0',
  `training_level_option_id` int(11) default '0',
  `training_method_option_id` int(11) unsigned default NULL,
  `training_custom_1_option_id` int(11) default NULL,
  `training_custom_2_option_id` int(11) default NULL,
  `training_got_curriculum_option_id` int(11) default NULL,
  `training_primary_language_option_id` int(11) unsigned default NULL,
  `training_secondary_language_option_id` int(11) unsigned default NULL,
  `comments` text NOT NULL,
  `got_comments` text NOT NULL,
  `objectives` text NOT NULL,
  `is_approved` tinyint(1) unsigned NOT NULL default '1',
  `is_tot` tinyint(1) NOT NULL,
  `is_refresher` tinyint(1) NOT NULL,
  `pre` float default NULL,
  `post` float default NULL,
  `modified_by` int(11) default NULL,
  `created_by` int(11) default NULL,
  `is_deleted` tinyint(1) NOT NULL default '0',
  `timestamp_updated` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `timestamp_created` timestamp NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `uuid_idx` (`uuid`),
  KEY `created_by` (`created_by`),
  KEY `modified_by` (`modified_by`),
  KEY `training_title_option_id` (`training_title_option_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


-- Dumping structure for table training_approval_history
CREATE TABLE IF NOT EXISTS `training_approval_history` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `uuid` char(36) default NULL,
  `training_id` int(11) NOT NULL,
  `approval_status` enum('resubmitted','rejected','approved','new') NOT NULL default 'new',
  `message` text,
  `recipients` varchar(512) default NULL,
  `created_by` int(11) NOT NULL,
  `timestamp_created` timestamp NOT NULL default '0000-00-00 00:00:00' on update CURRENT_TIMESTAMP,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `uuid_idx` (`uuid`),
  KEY `training_id` (`training_id`),
  KEY `created_by` (`created_by`),
  KEY `created_by_2` (`created_by`),
  KEY `time_idx` (`timestamp_created`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

-- Data exporting was unselected.


-- Dumping structure for table training_category_option
CREATE TABLE IF NOT EXISTS `training_category_option` (
  `id` int(11) NOT NULL auto_increment,
  `uuid` char(36) default NULL,
  `training_category_phrase` varchar(128) NOT NULL,
  `modified_by` int(11) default NULL,
  `created_by` int(11) default NULL,
  `is_deleted` tinyint(1) NOT NULL default '0',
  `timestamp_updated` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `timestamp_created` timestamp NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `name_unique` (`training_category_phrase`),
  UNIQUE KEY `uuid_idx` (`uuid`),
  KEY `modified_by` (`modified_by`),
  KEY `created_by` (`created_by`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


-- Dumping structure for table training_category_option_to_training_title_option
CREATE TABLE IF NOT EXISTS `training_category_option_to_training_title_option` (
  `id` int(11) NOT NULL auto_increment,
  `uuid` char(36) default NULL,
  `training_category_option_id` int(11) NOT NULL,
  `training_title_option_id` int(11) NOT NULL,
  `created_by` int(11) default NULL,
  `timestamp_created` timestamp NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `unique_training_cat` (`training_category_option_id`,`training_title_option_id`),
  UNIQUE KEY `uuid_idx` (`uuid`),
  KEY `training_category_option_id` (`training_category_option_id`),
  KEY `created_by` (`created_by`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


-- Dumping structure for table training_custom_1_option
CREATE TABLE IF NOT EXISTS `training_custom_1_option` (
  `id` int(11) NOT NULL auto_increment,
  `uuid` char(36) default NULL,
  `custom1_phrase` varchar(128) NOT NULL default '',
  `modified_by` int(11) default NULL,
  `created_by` int(11) default NULL,
  `is_deleted` tinyint(1) NOT NULL default '0',
  `timestamp_updated` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `timestamp_created` timestamp NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `name_unique` (`custom1_phrase`),
  UNIQUE KEY `uuid_idx` (`uuid`),
  KEY `modified_by` (`modified_by`),
  KEY `created_by` (`created_by`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


-- Dumping structure for table training_custom_2_option
CREATE TABLE IF NOT EXISTS `training_custom_2_option` (
  `id` int(11) NOT NULL auto_increment,
  `uuid` char(36) default NULL,
  `custom2_phrase` varchar(128) NOT NULL default '',
  `modified_by` int(11) default NULL,
  `created_by` int(11) default NULL,
  `is_deleted` tinyint(1) NOT NULL default '0',
  `timestamp_updated` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `timestamp_created` timestamp NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `name_unique` (`custom2_phrase`),
  UNIQUE KEY `uuid_idx` (`uuid`),
  KEY `modified_by` (`modified_by`),
  KEY `created_by` (`created_by`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


-- Dumping structure for table training_funding_option
CREATE TABLE IF NOT EXISTS `training_funding_option` (
  `id` int(11) NOT NULL auto_increment,
  `uuid` char(36) default NULL,
  `funding_phrase` varchar(128) NOT NULL default '',
  `is_default` tinyint(1) unsigned NOT NULL default '0',
  `modified_by` int(11) default NULL,
  `created_by` int(11) default NULL,
  `is_deleted` tinyint(1) NOT NULL default '0',
  `timestamp_updated` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `timestamp_created` timestamp NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `name_unique` (`funding_phrase`),
  UNIQUE KEY `uuid_idx` (`uuid`),
  KEY `created_by` (`created_by`),
  KEY `modified_by` (`modified_by`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


-- Dumping structure for table training_got_curriculum_option
CREATE TABLE IF NOT EXISTS `training_got_curriculum_option` (
  `id` int(11) NOT NULL auto_increment,
  `uuid` char(36) default NULL,
  `training_got_curriculum_phrase` varchar(128) NOT NULL default '',
  `modified_by` int(11) default NULL,
  `created_by` int(11) default NULL,
  `is_deleted` tinyint(1) NOT NULL default '0',
  `timestamp_updated` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `timestamp_created` timestamp NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `name_unique` (`training_got_curriculum_phrase`),
  UNIQUE KEY `uuid_idx` (`uuid`),
  KEY `created_by` (`created_by`),
  KEY `modified_by` (`modified_by`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


-- Dumping structure for table training_level_option
CREATE TABLE IF NOT EXISTS `training_level_option` (
  `id` int(11) NOT NULL auto_increment,
  `uuid` char(36) default NULL,
  `training_level_phrase` varchar(128) NOT NULL default '',
  `is_default` tinyint(1) unsigned NOT NULL default '0',
  `modified_by` int(11) default NULL,
  `created_by` int(11) default NULL,
  `is_deleted` tinyint(1) NOT NULL default '0',
  `timestamp_updated` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `timestamp_created` timestamp NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `name_unique` (`training_level_phrase`),
  UNIQUE KEY `uuid_idx` (`uuid`),
  KEY `modified_by` (`modified_by`),
  KEY `created_by` (`created_by`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


-- Dumping structure for table training_location
CREATE TABLE IF NOT EXISTS `training_location` (
  `id` int(11) NOT NULL auto_increment,
  `uuid` char(36) default NULL,
  `training_location_name` varchar(128) NOT NULL default '',
  `location_id` int(10) unsigned default NULL,
  `modified_by` int(11) default NULL,
  `created_by` int(11) default NULL,
  `is_deleted` tinyint(1) NOT NULL default '0',
  `timestamp_updated` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `timestamp_created` timestamp NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `uuid_idx` (`uuid`),
  KEY `modified_by` (`modified_by`),
  KEY `created_by` (`created_by`),
  KEY `training_location_ibfk_9` (`location_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


-- Dumping structure for table training_method_option
CREATE TABLE IF NOT EXISTS `training_method_option` (
  `id` int(11) NOT NULL auto_increment,
  `uuid` char(36) default NULL,
  `training_method_phrase` varchar(128) NOT NULL,
  `modified_by` int(11) default NULL,
  `created_by` int(11) default NULL,
  `is_deleted` tinyint(1) NOT NULL default '0',
  `timestamp_updated` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `timestamp_created` timestamp NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `name_unique` (`training_method_phrase`),
  UNIQUE KEY `uuid_idx` (`uuid`),
  KEY `modified_by` (`modified_by`),
  KEY `created_by` (`created_by`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- Data exporting was unselected.


-- Dumping structure for table training_organizer_option
CREATE TABLE IF NOT EXISTS `training_organizer_option` (
  `id` int(11) NOT NULL auto_increment,
  `uuid` char(36) default NULL,
  `training_organizer_phrase` varchar(128) NOT NULL default '',
  `is_default` tinyint(1) unsigned NOT NULL default '0',
  `modified_by` int(11) default NULL,
  `created_by` int(11) default NULL,
  `is_deleted` tinyint(1) NOT NULL default '0',
  `timestamp_updated` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `timestamp_created` timestamp NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `name_unique` (`training_organizer_phrase`),
  UNIQUE KEY `uuid_idx` (`uuid`),
  KEY `created_by` (`created_by`),
  KEY `modified_by` (`modified_by`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


-- Dumping structure for table training_pepfar_categories_option
CREATE TABLE IF NOT EXISTS `training_pepfar_categories_option` (
  `id` int(11) NOT NULL auto_increment,
  `uuid` char(36) default NULL,
  `pepfar_category_phrase` varchar(128) NOT NULL default '',
  `is_default` tinyint(1) unsigned NOT NULL default '0',
  `id_training_method_option_id` int(11) default NULL,
  `modified_by` int(11) default NULL,
  `created_by` int(11) default NULL,
  `is_deleted` tinyint(1) NOT NULL default '0',
  `timestamp_updated` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `timestamp_created` timestamp NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `name_unique` (`pepfar_category_phrase`),
  UNIQUE KEY `uuid_idx` (`uuid`),
  KEY `created_by` (`created_by`),
  KEY `modified_by` (`modified_by`),
  KEY `id_training_method_option_id` (`id_training_method_option_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


-- Dumping structure for table training_recommend
CREATE TABLE IF NOT EXISTS `training_recommend` (
  `id` int(11) NOT NULL auto_increment,
  `uuid` char(36) default NULL,
  `person_qualification_option_id` int(11) default NULL,
  `training_topic_option_id` int(11) default NULL,
  `modified_by` int(11) default NULL,
  `created_by` int(11) default NULL,
  `is_deleted` tinyint(1) NOT NULL default '0',
  `timestamp_updated` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `timestamp_created` timestamp NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `person_qualification_option_id` (`person_qualification_option_id`,`training_topic_option_id`),
  UNIQUE KEY `uuid_idx` (`uuid`),
  KEY `created_by` (`created_by`),
  KEY `modified_by` (`modified_by`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


-- Dumping structure for table training_title_option
CREATE TABLE IF NOT EXISTS `training_title_option` (
  `id` int(11) NOT NULL auto_increment,
  `uuid` char(36) default NULL,
  `training_title_phrase` varchar(128) NOT NULL,
  `modified_by` int(11) default NULL,
  `created_by` int(11) default NULL,
  `is_deleted` tinyint(1) NOT NULL default '0',
  `timestamp_updated` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `timestamp_created` timestamp NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `name_unique` (`training_title_phrase`),
  UNIQUE KEY `uuid_idx` (`uuid`),
  KEY `modified_by` (`modified_by`),
  KEY `created_by` (`created_by`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


-- Dumping structure for table training_topic_option
CREATE TABLE IF NOT EXISTS `training_topic_option` (
  `id` int(11) NOT NULL auto_increment,
  `uuid` char(36) default NULL,
  `training_topic_phrase` varchar(128) NOT NULL default '',
  `is_default` tinyint(1) unsigned NOT NULL default '0',
  `modified_by` int(11) default NULL,
  `created_by` int(11) default NULL,
  `is_deleted` tinyint(1) NOT NULL default '0',
  `timestamp_updated` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `timestamp_created` timestamp NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `name_unique` (`training_topic_phrase`),
  UNIQUE KEY `uuid_idx` (`uuid`),
  KEY `created_by` (`created_by`),
  KEY `modified_by` (`modified_by`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


-- Dumping structure for table training_to_person_qualification_option
CREATE TABLE IF NOT EXISTS `training_to_person_qualification_option` (
  `id` int(11) NOT NULL auto_increment,
  `uuid` char(36) default NULL,
  `training_id` int(11) NOT NULL,
  `person_qualification_option_id` int(11) NOT NULL,
  `person_count_na` int(11) NOT NULL default '0',
  `person_count_male` int(11) NOT NULL default '0',
  `person_count_female` int(11) NOT NULL default '0',
  `age_range_option_id` int(11) unsigned NOT NULL default '0',
  `created_by` int(11) default NULL,
  `timestamp_created` timestamp NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `training_qual_uniq` (`training_id`,`person_qualification_option_id`,`age_range_option_id`),
  UNIQUE KEY `uuid_idx` (`uuid`),
  KEY `person_qualification_option_id` (`person_qualification_option_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

-- Data exporting was unselected.


-- Dumping structure for table training_to_trainer
CREATE TABLE IF NOT EXISTS `training_to_trainer` (
  `id` int(11) NOT NULL auto_increment,
  `uuid` char(36) default NULL,
  `trainer_id` int(11) NOT NULL,
  `training_id` int(11) NOT NULL,
  `duration_days` float NOT NULL,
  `created_by` int(11) default NULL,
  `timestamp_created` timestamp NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `unique_training` (`trainer_id`,`training_id`),
  UNIQUE KEY `uuid_idx` (`uuid`),
  KEY `created_by` (`created_by`),
  KEY `trainer_id` (`trainer_id`),
  KEY `training_id` (`training_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


-- Dumping structure for table training_to_training_funding_option
CREATE TABLE IF NOT EXISTS `training_to_training_funding_option` (
  `id` int(11) NOT NULL auto_increment,
  `uuid` char(36) default NULL,
  `training_id` int(11) NOT NULL,
  `training_funding_option_id` int(11) NOT NULL,
  `funding_amount` float default NULL,
  `created_by` int(11) default NULL,
  `timestamp_created` timestamp NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `unique_training_cat` (`training_funding_option_id`,`training_id`),
  UNIQUE KEY `uuid_idx` (`uuid`),
  KEY `training_id` (`training_id`),
  KEY `created_by` (`created_by`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


-- Dumping structure for table training_to_training_pepfar_categories_option
CREATE TABLE IF NOT EXISTS `training_to_training_pepfar_categories_option` (
  `id` int(11) NOT NULL auto_increment,
  `uuid` char(36) default NULL,
  `training_id` int(11) NOT NULL,
  `training_pepfar_categories_option_id` int(11) NOT NULL,
  `training_method_option_id` int(11) default NULL,
  `duration_days` float NOT NULL,
  `created_by` int(11) default NULL,
  `timestamp_created` timestamp NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `unique_training_cat` (`training_pepfar_categories_option_id`,`training_id`),
  UNIQUE KEY `uuid_idx` (`uuid`),
  KEY `training_id` (`training_id`),
  KEY `created_by` (`created_by`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


-- Dumping structure for table training_to_training_topic_option
CREATE TABLE IF NOT EXISTS `training_to_training_topic_option` (
  `id` int(11) NOT NULL auto_increment,
  `uuid` char(36) default NULL,
  `training_id` int(11) NOT NULL,
  `training_topic_option_id` int(11) NOT NULL,
  `created_by` int(11) default NULL,
  `timestamp_created` timestamp NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `unique_training_cat` (`training_topic_option_id`,`training_id`),
  UNIQUE KEY `uuid_idx` (`uuid`),
  KEY `training_id` (`training_id`),
  KEY `created_by` (`created_by`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


-- Dumping structure for table translation
CREATE TABLE IF NOT EXISTS `translation` (
  `id` int(11) NOT NULL auto_increment,
  `uuid` char(36) default NULL,
  `key_phrase` varchar(128) NOT NULL default '',
  `phrase` varchar(128) NOT NULL default '',
  `modified_by` int(11) default NULL,
  `created_by` int(11) default NULL,
  `is_deleted` tinyint(1) NOT NULL default '0',
  `timestamp_updated` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `timestamp_created` timestamp NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `uuid_idx` (`uuid`),
  KEY `modified_by` (`modified_by`),
  KEY `created_by` (`created_by`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


-- Dumping structure for table tutor
CREATE TABLE IF NOT EXISTS `tutor` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `personid` varchar(10) NOT NULL default '',
  `tutorsince` int(10) unsigned NOT NULL default '0',
  `tutortimehere` int(10) unsigned NOT NULL default '0',
  `degree` varchar(150) NOT NULL default '',
  `degreeinst` varchar(150) NOT NULL default '',
  `degreeyear` int(10) unsigned NOT NULL default '0',
  `languagesspoken` varchar(255) NOT NULL default '',
  `positionsheld` text NOT NULL,
  `comments` text NOT NULL,
  `facilityid` int(10) unsigned NOT NULL default '0',
  `cadreid` int(10) unsigned NOT NULL default '0',
  `nationalityid` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `idx2` (`facilityid`,`personid`,`cadreid`,`nationalityid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- Data exporting was unselected.


-- Dumping structure for table user
CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) NOT NULL auto_increment,
  `uuid` char(36) default NULL,
  `username` varchar(32) NOT NULL default '',
  `password` varchar(41) NOT NULL default '',
  `email` varchar(64) NOT NULL default '',
  `first_name` varchar(32) NOT NULL default '',
  `last_name` varchar(32) NOT NULL default '',
  `person_id` int(11) default NULL,
  `locale` varchar(255) default '',
  `modified_by` int(11) default NULL,
  `created_by` int(11) default NULL,
  `is_blocked` tinyint(1) NOT NULL default '0',
  `timestamp_updated` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `timestamp_created` timestamp NOT NULL default '0000-00-00 00:00:00',
  `timestamp_last_login` timestamp NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `username_idx` (`username`),
  UNIQUE KEY `email_idx` (`email`),
  UNIQUE KEY `uuid_idx` (`uuid`),
  KEY `modified_by` (`modified_by`),
  KEY `created_by` (`created_by`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


-- Dumping structure for table user_to_acl
CREATE TABLE IF NOT EXISTS `user_to_acl` (
  `id` int(11) NOT NULL auto_increment,
  `acl_id` enum('edit_course','view_course','edit_people','view_people','view_create_reports','edit_country_options','add_edit_users','training_organizer_option_all','training_title_option_all','approve_trainings','admin_files','use_offline_app','pre_service') NOT NULL default 'view_course',
  `user_id` int(11) NOT NULL,
  `created_by` int(11) default NULL,
  `timestamp_created` timestamp NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`),
  KEY `created_by` (`created_by`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


-- Dumping structure for table user_to_organizer_access
CREATE TABLE IF NOT EXISTS `user_to_organizer_access` (
  `id` int(11) NOT NULL auto_increment,
  `training_organizer_option_id` int(11) default NULL,
  `user_id` int(11) NOT NULL,
  `created_by` int(11) default NULL,
  `timestamp_created` timestamp NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`),
  KEY `created_by` (`created_by`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


-- Dumping structure for table _location_missing_links
CREATE TABLE IF NOT EXISTS `_location_missing_links` (
  `id` int(10) unsigned NOT NULL,
  `parent_id` int(10) unsigned NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


-- Dumping structure for table _location_ref
CREATE TABLE IF NOT EXISTS `_location_ref` (
  `id` int(10) unsigned NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


-- Dumping structure for table _system
CREATE TABLE IF NOT EXISTS `_system` (
  `country` varchar(64) NOT NULL default '',
  `country_uuid` char(38) NOT NULL default '' COMMENT 'legacy id',
  `locale` varchar(32) NOT NULL default 'en_EN.UTF-8',
  `locale_enabled` varchar(255) default NULL,
  `allow_multi_pepfar` tinyint(1) NOT NULL default '0',
  `allow_multi_topic` tinyint(1) NOT NULL default '0',
  `display_funding_options` tinyint(1) NOT NULL default '1',
  `display_test_scores_course` tinyint(1) NOT NULL default '1',
  `display_test_scores_individual` tinyint(1) NOT NULL default '1',
  `display_scores_limit` int(11) NOT NULL default '5',
  `display_national_id` tinyint(1) NOT NULL default '1',
  `display_trainer_affiliations` tinyint(1) NOT NULL default '1',
  `display_training_custom1` tinyint(1) NOT NULL default '1',
  `display_training_custom2` tinyint(1) NOT NULL default '1',
  `display_training_pre_test` tinyint(1) NOT NULL default '1',
  `display_training_post_test` tinyint(1) NOT NULL default '1',
  `display_people_custom1` tinyint(1) NOT NULL default '1',
  `display_people_custom2` tinyint(1) NOT NULL default '1',
  `display_region_b` tinyint(1) NOT NULL default '1',
  `display_people_active` tinyint(1) NOT NULL default '1',
  `display_middle_name_last` tinyint(1) NOT NULL default '0',
  `display_training_recommend` tinyint(1) NOT NULL default '0',
  `display_training_trainers` tinyint(1) NOT NULL default '1',
  `display_course_objectives` tinyint(1) NOT NULL default '1',
  `display_training_topic` tinyint(1) NOT NULL default '1',
  `display_training_got_curric` tinyint(1) NOT NULL default '0',
  `display_training_got_comment` tinyint(1) NOT NULL default '0',
  `display_training_refresher` tinyint(1) NOT NULL default '0',
  `display_people_file_num` tinyint(1) NOT NULL default '0',
  `display_people_home_phone` tinyint(1) NOT NULL default '1',
  `display_people_fax` tinyint(1) NOT NULL default '1',
  `module_evaluation_enabled` tinyint(1) unsigned NOT NULL default '1',
  `module_approvals_enabled` tinyint(1) unsigned NOT NULL default '1',
  `module_historical_data_enabled` tinyint(1) unsigned NOT NULL default '1',
  `module_unknown_participants_enabled` tinyint(1) unsigned NOT NULL default '1',
  `display_end_date` tinyint(1) unsigned NOT NULL default '0',
  `display_training_method` tinyint(1) unsigned NOT NULL default '0',
  `display_funding_amount` tinyint(1) unsigned NOT NULL default '0',
  `display_primary_language` tinyint(1) unsigned NOT NULL default '0',
  `display_secondary_language` tinyint(1) unsigned NOT NULL default '0',
  `display_people_title` tinyint(1) unsigned NOT NULL default '1',
  `display_people_suffix` tinyint(1) unsigned NOT NULL default '0',
  `display_people_age` tinyint(1) unsigned NOT NULL default '0',
  `display_people_home_address` tinyint(1) unsigned NOT NULL default '0',
  `display_people_second_email` tinyint(1) unsigned NOT NULL default '0',
  `display_middle_name` tinyint(1) NOT NULL default '1',
  `display_funding_amounts` tinyint(1) NOT NULL default '1',
  `display_region_c` tinyint(1) unsigned NOT NULL default '0',
  `display_external_classes` tinyint(1) NOT NULL default '0',
  `display_responsibility_me` tinyint(1) NOT NULL default '0',
  `display_highest_ed_level` tinyint(1) NOT NULL default '0',
  `display_attend_reason` tinyint(1) NOT NULL default '0',
  `display_primary_responsibility` tinyint(1) NOT NULL default '0',
  `display_secondary_responsibility` tinyint(1) NOT NULL default '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



/* ======================================================== */
/* ======================================================== */
/* 4.01 features, bug fixes and tanzania changes            */
/* ======================================================== */
/* ======================================================== */

# and the table for new training partner module
CREATE TABLE IF NOT EXISTS `organizer_partners` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uuid` char(36) DEFAULT NULL,
  `organizer_id` int(11) NOT NULL,
  `partner1_name` varchar(64) NULL DEFAULT '',
  `subpartner` varchar(64) DEFAULT '',
  `mechanism_id` varchar(32) DEFAULT '',
  `funder_name` varchar(64) DEFAULT '',
  `funder_id` varchar(32) DEFAULT '',
  `timestamp_created` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_by` int(11) DEFAULT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) DEFAULT CHARSET=utf8;
ALTER TABLE  `organizer_partners` ADD INDEX (`mechanism_id`);

DROP TRIGGER IF EXISTS `organizer_partners_insert`;
DELIMITER $$
CREATE TRIGGER `organizer_partners_insert` BEFORE INSERT ON `organizer_partners` FOR EACH ROW BEGIN
  SET NEW.`uuid` = IFNULL(NEW.`uuid`,UUID());
END$$
DELIMITER ;
########

#add 3 translations for new feature
INSERT INTO  `translation` VALUES (NULL , NULL ,  'Facility Comments',  'Facility Comments', NULL , NULL ,  '0',  CURRENT_TIMESTAMP ,  '0000-00-00 00:00:00');
INSERT INTO  `translation` VALUES (NULL , NULL ,  'Qualification Comments',  'Qualification Comments', NULL , NULL ,  '0',  CURRENT_TIMESTAMP ,  '0000-00-00 00:00:00');
INSERT INTO  `translation` VALUES (NULL , NULL ,  'Training Comments',  'Training Comments', NULL , NULL ,  '0',  CURRENT_TIMESTAMP ,  '0000-00-00 00:00:00');

#remove unique requirement of facility_name column
ALTER TABLE `facility` DROP INDEX `facility_name`, ADD INDEX `facility_name`(facility_name, location_id);
#  add system settings for new training partner module
ALTER TABLE `_system` ADD COLUMN `display_training_partner` tinyint(1) NOT NULL DEFAULT '0';

ALTER TABLE `facility` DROP INDEX  `facility_name` , ADD UNIQUE  `facility_name` (  `facility_name` ,  `location_id` );

/* change refresher course to multi select value */
ALTER TABLE  `_system`
  ADD  `multi_opt_refresher_course` TINYINT( 1 ) NOT NULL DEFAULT '0';
ALTER TABLE `training`
  ADD `training_refresher_option_id` INT(11) NULL DEFAULT NULL;
/* tables */

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

/* Skillsmart changes */

CREATE TABLE `comp` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `person` int(11) NOT NULL,
  `question` varchar(3) DEFAULT NULL,
  `option` varchar(128) DEFAULT NULL,
  `active` char(1) NOT NULL DEFAULT 'N',
  PRIMARY KEY (`id`),
  KEY `person` (`person`,`active`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


CREATE TABLE `competencies` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `competencyname` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `status` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `idx2` (`competencyname`,`status`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


CREATE TABLE `competencies_answers` (
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


CREATE TABLE `competencies_questions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `competencyid` bigint(20) unsigned NOT NULL DEFAULT '0',
  `question` text COLLATE utf8_unicode_ci NOT NULL,
  `itemorder` int(11) NOT NULL DEFAULT '1',
  `itemtype` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'question',
  `status` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `idx2` (`itemtype`,`competencyid`,`itemorder`,`status`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


CREATE TABLE `compres` (
  `SNo` bigint(20) NOT NULL AUTO_INCREMENT,
  `person` int(11) NOT NULL,
  `res` int(11) NOT NULL,
  `active` char(1) NOT NULL DEFAULT 'N',
  PRIMARY KEY (`SNo`),
  KEY `person` (`person`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


CREATE TABLE `facs` (
  `sno` bigint(20) NOT NULL AUTO_INCREMENT,
  `person` int(11) NOT NULL,
  `facility` int(11) NOT NULL,
  `facstring` varchar(1024) NOT NULL,
  `active` char(1) NOT NULL DEFAULT 'N',
  PRIMARY KEY (`sno`),
  KEY `person` (`person`,`facility`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


CREATE TABLE `helper` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


CREATE TABLE `jobtitles` (
  `Title` varchar(45) DEFAULT NULL,
  `Option` varchar(12) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


CREATE TABLE `link_institution_degrees` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_institution` int(10) unsigned NOT NULL DEFAULT '0',
  `id_degree` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx2` (`id_institution`,`id_degree`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


CREATE TABLE `link_occupational_competencies` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `competencyid` int(10) unsigned NOT NULL DEFAULT '0',
  `occupationalid` int(10) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `idx2` (`competencyid`,`occupationalid`,`status`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


CREATE TABLE `link_person_training` (
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


CREATE TABLE `link_qualification_competency` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `competencyid` int(10) unsigned NOT NULL DEFAULT '0',
  `qualificationid` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx2` (`competencyid`,`qualificationid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


CREATE TABLE `link_user_institution` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  `institutionid` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx2` (`userid`,`institutionid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


CREATE TABLE `lookup_skillsmart` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `lookupgroup` varchar(150) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `lookupvalue` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `status` int(10) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `idx2` (`status`),
  KEY `idx3` (`lookupgroup`),
  KEY `idx4` (`lookupvalue`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


CREATE TABLE `occupational_categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uuid` char(36) DEFAULT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `category_phrase` varchar(128) NOT NULL DEFAULT '',
  `is_default` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `modified_by` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  `timestamp_updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `timestamp_created` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name_unique` (`category_phrase`),
  UNIQUE KEY `uuid_idx` (`uuid`),
  KEY `modified_by` (`modified_by`),
  KEY `created_by` (`created_by`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


CREATE TABLE `person_responsibility_option` (
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
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


CREATE TABLE `sheet1` (
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


CREATE TABLE `tracking` (
  `UID` int(11) NOT NULL,
  `URL` text NOT NULL,
  `On` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  KEY `IDX` (`UID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


CREATE TABLE `trans` (
  `sno` bigint(20) NOT NULL AUTO_INCREMENT,
  `person` int(11) NOT NULL,
  `id` int(11) NOT NULL,
  `chk` varchar(10) NOT NULL,
  `yr` varchar(10) NOT NULL,
  `transstring` varchar(256) NOT NULL,
  `active` char(1) NOT NULL DEFAULT 'N',
  PRIMARY KEY (`sno`),
  KEY `person` (`person`,`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


CREATE TABLE `user_to_acl_province` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `location_id` int(11) NOT NULL,
  `created_by` int(11) DEFAULT NULL,
  `timestamp_created` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `created_by` (`created_by`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

ALTER TABLE `student`
  ADD COLUMN `institutionid` int(10) unsigned NULL DEFAULT '0';

ALTER TABLE `tutor`
  ADD COLUMN `institutionid` int(10) unsigned NULL DEFAULT '0';

ALTER TABLE `user_to_acl`
  CHANGE COLUMN `acl_id` `acl_id` enum('edit_course','view_course','edit_people','view_people','view_create_reports','edit_country_options','add_edit_users','training_organizer_option_all','training_title_option_all','approve_trainings','admin_files','use_offline_app','pre_service','in_service','edit_institution','view_institution') NOT NULL DEFAULT 'view_course';

ALTER TABLE `_system`
  ADD COLUMN `display_mod_skillsmart` tinyint(1) NOT NULL DEFAULT '1',
  ADD COLUMN `display_occupational_category` tinyint(1) NOT NULL DEFAULT '1',
  ADD COLUMN `display_government_employee` tinyint(1) NOT NULL DEFAULT '1',
  ADD COLUMN `display_professional_bodies` tinyint(1) NOT NULL DEFAULT '1',
  ADD COLUMN `display_race` tinyint(1) NOT NULL DEFAULT '1',
  ADD COLUMN `display_disability` tinyint(1) NOT NULL DEFAULT '1',
  ADD COLUMN `display_nurse_trainer_type` tinyint(1) NOT NULL DEFAULT '1',
  ADD COLUMN `display_provider_start` tinyint(1) NOT NULL DEFAULT '1',
  ADD COLUMN `display_rank_groups` tinyint(1) NOT NULL DEFAULT '1',
  ADD COLUMN `display_supervised` tinyint(1) NOT NULL DEFAULT '1',
  ADD COLUMN `display_training_received` tinyint(1) NOT NULL DEFAULT '1',
  ADD COLUMN `display_facility_department` tinyint(1) NOT NULL DEFAULT '1';

ALTER TABLE `person`
  ADD COLUMN `multi_facility_ids` varchar(255) NULL,
  ADD COLUMN `home_city` varchar(255) NULL DEFAULT '',
  ADD COLUMN `highest_level_option_id` int(11) NULL,
  ADD COLUMN `govemp_option_id` tinyint(4) NULL DEFAULT '0',
  ADD COLUMN `occupational_category_id` int(10) unsigned NULL,
  ADD COLUMN `persal_number` int(10) unsigned NULL,
  ADD COLUMN `bodies_id` int(10) unsigned NULL,
  ADD COLUMN `race_option_id` int(10) unsigned NULL,
  ADD COLUMN `disability_option_id` int(10) unsigned NULL,
  ADD COLUMN `professional_reg_number` int(10) unsigned NULL,
  ADD COLUMN `nationality_id` int(10) unsigned NULL,
  ADD COLUMN `nurse_training_id` int(10) unsigned NULL,
  ADD COLUMN `care_start_year` int(10) unsigned NULL,
  ADD COLUMN `timespent_rank_pregnant` int(10) unsigned NULL,
  ADD COLUMN `timespent_rank_adults` int(10) unsigned NULL,
  ADD COLUMN `timespent_rank_children` int(10) unsigned NULL,
  ADD COLUMN `timespent_rank_pregnant_pct` int(10) unsigned NULL,
  ADD COLUMN `timespent_rank_adults_pct` int(10) unsigned NULL,
  ADD COLUMN `timespent_rank_children_pct` int(10) unsigned NULL,
  ADD COLUMN `supervised_id` int(10) unsigned NULL,
  ADD COLUMN `supervision_frequency_id` int(10) unsigned NULL,
  ADD COLUMN `supervisors_profession` varchar(255) NULL,
  ADD COLUMN `training_recieved_data` text NULL,
  ADD COLUMN `facilitydepartment_id` int(10) unsigned NULL;

ALTER TABLE `person`
  CHANGE `multi_facility_ids` `multi_facility_ids` varchar(255) NULL DEFAULT NULL,
  CHANGE `home_city` `home_city` varchar(255) NULL DEFAULT '',
  CHANGE `highest_level_option_id` `highest_level_option_id` int(11) NULL DEFAULT NULL,
  CHANGE `govemp_option_id` `govemp_option_id` tinyint(4) NULL DEFAULT '0',
  CHANGE `occupational_category_id` `occupational_category_id` int(10) unsigned NULL DEFAULT NULL,
  CHANGE `persal_number` `persal_number` int(10) unsigned NULL DEFAULT NULL,
  CHANGE `bodies_id` `bodies_id` int(10) unsigned NULL DEFAULT NULL,
  CHANGE `race_option_id` `race_option_id` int(10) unsigned NULL DEFAULT NULL,
  CHANGE `disability_option_id` `disability_option_id` int(10) unsigned NULL DEFAULT NULL,
  CHANGE `professional_reg_number` `professional_reg_number` int(10) unsigned NULL DEFAULT NULL,
  CHANGE `nationality_id` `nationality_id` int(10) unsigned NULL DEFAULT NULL,
  CHANGE `nurse_training_id` `nurse_training_id` int(10) unsigned NULL DEFAULT NULL,
  CHANGE `care_start_year` `care_start_year` int(10) unsigned NULL DEFAULT NULL,
  CHANGE `timespent_rank_pregnant` `timespent_rank_pregnant` int(10) unsigned NULL DEFAULT NULL,
  CHANGE `timespent_rank_adults` `timespent_rank_adults` int(10) unsigned NULL DEFAULT NULL,
  CHANGE `timespent_rank_children` `timespent_rank_children` int(10) unsigned NULL DEFAULT NULL,
  CHANGE `timespent_rank_pregnant_pct` `timespent_rank_pregnant_pct` int(10) unsigned NULL DEFAULT NULL,
  CHANGE `timespent_rank_adults_pct` `timespent_rank_adults_pct` int(10) unsigned NULL DEFAULT NULL,
  CHANGE `timespent_rank_children_pct` `timespent_rank_children_pct` int(10) unsigned NULL DEFAULT NULL,
  CHANGE `supervised_id` `supervised_id` int(10) unsigned NULL DEFAULT NULL,
  CHANGE `supervision_frequency_id` `supervision_frequency_id` int(10) unsigned NULL DEFAULT NULL,
  CHANGE `supervisors_profession` `supervisors_profession` varchar(255) NULL DEFAULT NULL,
  CHANGE `training_recieved_data` `training_recieved_data` text NULL DEFAULT NULL,
  CHANGE `facilitydepartment_id` `facilitydepartment_id` int(10) unsigned NULL DEFAULT NULL;

INSERT INTO `acl`         VALUES ('in_service',  'in_service' );
INSERT INTO `acl`         VALUES ('pre_service', 'pre_service');

INSERT INTO `user_to_acl` VALUES (null,'in_service',  1, 1, NOW());
INSERT INTO `user_to_acl` VALUES (null,'pre_service', 1, 1, NOW());

# migration of users
# logic - select users with acl 'edit_course' and give them matching acl's in the new system equal to power as edit_course used to be.
INSERT INTO user_to_acl (acl_id, user_id, created_by, timestamp_created) SELECT 'acl_editor_training_organizer', user_id, 1, NOW() from user_to_acl where acl_id = 'edit_course' ;
INSERT INTO user_to_acl (acl_id, user_id, created_by, timestamp_created) SELECT 'edit_training_location', user_id, 1, NOW() from user_to_acl where acl_id = 'edit_course' ;
INSERT INTO user_to_acl (acl_id, user_id, created_by, timestamp_created) SELECT 'edit_facility', user_id, 1, NOW() from user_to_acl where acl_id = 'edit_course' ;
INSERT INTO user_to_acl (acl_id, user_id, created_by, timestamp_created) SELECT 'acl_editor_training_level', user_id, 1, NOW() from user_to_acl where acl_id = 'edit_course' ;
INSERT INTO user_to_acl (acl_id, user_id, created_by, timestamp_created) SELECT 'acl_editor_method', user_id, 1, NOW() from user_to_acl where acl_id = 'edit_course' ;
INSERT INTO user_to_acl (acl_id, user_id, created_by, timestamp_created) SELECT 'acl_editor_training_topic', user_id, 1, NOW() from user_to_acl where acl_id = 'edit_course' ;
INSERT INTO user_to_acl (acl_id, user_id, created_by, timestamp_created) SELECT 'acl_editor_funding', user_id, 1, NOW() from user_to_acl where acl_id = 'edit_course' ;
INSERT INTO user_to_acl (acl_id, user_id, created_by, timestamp_created) SELECT 'acl_editor_nationalcurriculum', user_id, 1, NOW() from user_to_acl where acl_id = 'edit_course' ;
/* Employee tracking module - for South Africa */
/* requires engenderhealth-upgrade.sql or 'edit_employee' added to acl table and user_to_acl table's acl enumeration */
ALTER TABLE  `_system` ADD  `module_employee_enabled` TINYINT( 1 ) NOT NULL DEFAULT '0' AFTER  `module_unknown_participants_enabled`;
ALTER TABLE  `_system` ADD  `employee_header` TEXT NULL DEFAULT NULL;
UPDATE `_system` SET employee_header = 'Welcome to the TrainSMART employee tracking module, users can only view their own organizations and admins can add users and edit this text through the admin control panel.' WHERE 1;
ALTER TABLE  `_system` ADD  `display_employee_employee_header`       TINYINT( 1 ) NOT NULL DEFAULT '1';
ALTER TABLE  `_system` ADD  `display_employee_partner`               TINYINT( 1 ) NOT NULL DEFAULT '1';
ALTER TABLE  `_system` ADD  `display_employee_sub_partner`           TINYINT( 1 ) NOT NULL DEFAULT '1';
ALTER TABLE  `_system` ADD  `display_employee_site_name`             TINYINT( 1 ) NOT NULL DEFAULT '1';
ALTER TABLE  `_system` ADD  `display_employee_funder`                TINYINT( 1 ) NOT NULL DEFAULT '1';
ALTER TABLE  `_system` ADD  `display_employee_funding_end_date`      TINYINT( 1 ) NOT NULL DEFAULT '1';
ALTER TABLE  `_system` ADD  `display_employee_full_time`             TINYINT( 1 ) NOT NULL DEFAULT '1';
ALTER TABLE  `_system` ADD  `display_employee_funded_hours_per_week` TINYINT( 1 ) NOT NULL DEFAULT '1';
ALTER TABLE  `_system` ADD  `display_employee_staff_category`        TINYINT( 1 ) NOT NULL DEFAULT '1';
ALTER TABLE  `_system` ADD  `display_employee_annual_cost`           TINYINT( 1 ) NOT NULL DEFAULT '1';
ALTER TABLE  `_system` ADD  `display_employee_primary_role`          TINYINT( 1 ) NOT NULL DEFAULT '1';
ALTER TABLE  `_system` ADD  `display_employee_importance`            TINYINT( 1 ) NOT NULL DEFAULT '1';
ALTER TABLE  `_system` ADD  `display_employee_contract_end_date`     TINYINT( 1 ) NOT NULL DEFAULT '1';
ALTER TABLE  `_system` ADD  `display_employee_agreement_end_date`    TINYINT( 1 ) NOT NULL DEFAULT '1';
ALTER TABLE  `_system` ADD  `display_employee_intended_transition`   TINYINT( 1 ) NOT NULL DEFAULT '1';
ALTER TABLE  `_system` ADD  `display_employee_transition_confirmed`  TINYINT( 1 ) NOT NULL DEFAULT '1';
ALTER TABLE  `_system` ADD  `display_employee_incoming_partner`      TINYINT( 1 ) NOT NULL DEFAULT '1';
ALTER TABLE  `_system` ADD  `display_employee_relationship`          TINYINT( 1 ) NOT NULL DEFAULT '1';
ALTER TABLE  `_system` ADD  `display_employee_referral_mechanism`    TINYINT( 1 ) NOT NULL DEFAULT '0';
ALTER TABLE  `_system` ADD  `display_employee_chw_supervisor`        TINYINT( 1 ) NOT NULL DEFAULT '0';
ALTER TABLE  `_system` ADD  `display_employee_trainings_provided`    TINYINT( 1 ) NOT NULL DEFAULT '0';
ALTER TABLE  `_system` ADD  `display_employee_courses_completed`     TINYINT( 1 ) NOT NULL DEFAULT '0';


INSERT INTO  `translation` VALUES (NULL , NULL ,  'Partner', 'Partner', NULL , NULL ,  '0', CURRENT_TIMESTAMP ,  '2012-10-22 00:00:00');
INSERT INTO  `translation` VALUES (NULL , NULL ,  'Sub Partner', 'Sub Partner', NULL , NULL ,  '0', CURRENT_TIMESTAMP ,  '2012-10-22 00:00:00');
INSERT INTO  `translation` VALUES (NULL , NULL ,  'Funder', 'Funder', NULL , NULL ,  '0', CURRENT_TIMESTAMP ,  '2012-10-22 00:00:00');
INSERT INTO  `translation` VALUES (NULL , NULL ,  'Full Time', 'Full Time', NULL , NULL ,  '0', CURRENT_TIMESTAMP ,  '2012-10-22 00:00:00');
INSERT INTO  `translation` VALUES (NULL , NULL ,  'Funded hours per week', 'Funded hours per week', NULL , NULL ,  '0', CURRENT_TIMESTAMP ,  '2012-10-22 00:00:00');
INSERT INTO  `translation` VALUES (NULL , NULL ,  'Staff Category', 'Staff Category', NULL , NULL ,  '0', CURRENT_TIMESTAMP ,  '2012-10-22 00:00:00');
INSERT INTO  `translation` VALUES (NULL , NULL ,  'Annual Cost', 'Annual Cost', NULL , NULL ,  '0', CURRENT_TIMESTAMP ,  '2012-10-22 00:00:00');
INSERT INTO  `translation` VALUES (NULL , NULL ,  'Primary Role', 'Primary Role', NULL , NULL ,  '0', CURRENT_TIMESTAMP ,  '2012-10-22 00:00:00');
INSERT INTO  `translation` VALUES (NULL , NULL ,  'Importance', 'Importance', NULL , NULL ,  '0', CURRENT_TIMESTAMP ,  '2012-10-22 00:00:00');
INSERT INTO  `translation` VALUES (NULL , NULL ,  'Intended Transition', 'Intended Transition', NULL , NULL ,  '0', CURRENT_TIMESTAMP ,  '2012-10-22 00:00:00');
INSERT INTO  `translation` VALUES (NULL , NULL ,  'Incoming partner', 'Incoming partner', NULL , NULL ,  '0', CURRENT_TIMESTAMP ,  '2012-10-22 00:00:00');
INSERT INTO  `translation` VALUES (NULL , NULL ,  'Relationship', 'Relationship between CHW and formal health services', NULL , NULL ,  '0', CURRENT_TIMESTAMP ,  '2012-10-22 00:00:00');
INSERT INTO  `translation` VALUES (NULL , NULL ,  'Referral Mechanism', 'Referral Mechanism', NULL , NULL ,  '0', CURRENT_TIMESTAMP ,  '2012-10-22 00:00:00');
INSERT INTO  `translation` VALUES (NULL , NULL ,  'CHW Supervisor', 'CHW Supervisor', NULL , NULL ,  '0', CURRENT_TIMESTAMP ,  '2012-10-22 00:00:00');
INSERT INTO  `translation` VALUES (NULL , NULL ,  'Trainings provided', 'What training do you provide', NULL , NULL ,  '0', CURRENT_TIMESTAMP ,  '2012-10-22 00:00:00');
INSERT INTO  `translation` VALUES (NULL , NULL ,  'Courses Completed', 'Courses Completed', NULL , NULL ,  '0', CURRENT_TIMESTAMP ,  '2012-10-22 00:00:00');

/* create tables `partner` and `employee` */

CREATE TABLE `partner` (
  `id` INT(11) NULL DEFAULT NULL AUTO_INCREMENT,
  `uuid` CHAR(36) NULL DEFAULT NULL,
  `organizer_option_id` INT( 11 ) NULL DEFAULT NULL,
  `partner` VARCHAR(100) NULL DEFAULT NULL,
  `location_id` INT(11) NULL DEFAULT NULL,
  `address1` VARCHAR(128) NULL DEFAULT NULL,
  `address2` VARCHAR(128) NULL DEFAULT NULL,
  `phone` VARCHAR(64) NULL DEFAULT NULL,
  `fax` VARCHAR(64) NULL DEFAULT NULL,
  `employee_transition_option_id` INT(11) NULL DEFAULT NULL,
  `partner_importance_option_id`  INT(11) NULL DEFAULT NULL,
  `agreement_end_date` DATETIME NULL DEFAULT NULL,
  `transition_confirmed` TINYINT(1) NULL DEFAULT NULL,
  `comments` TEXT NULL DEFAULT NULL,
  `incoming_partner` INT(11) NULL DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `timestamp_created` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
  )
 ENGINE = InnoDB DEFAULT CHARSET=utf8;
DROP TRIGGER IF EXISTS `partner_insert`;
DELIMITER $$
CREATE TRIGGER `partner_insert` BEFORE INSERT ON `partner` FOR EACH ROW BEGIN
  SET NEW.`uuid` = IFNULL(NEW.`uuid`,UUID());
END$$
DELIMITER ;



CREATE TABLE `partner_to_subpartner` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `partner_id` int(11) NOT NULL,
  `subpartner_id` int(11) NOT NULL,
  `created_by` int(11) DEFAULT NULL,
  `timestamp_created` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY (`partner_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `employee` (
  `id` INT(11) NULL DEFAULT NULL AUTO_INCREMENT,
  `uuid` CHAR(36) NULL DEFAULT NULL,
  `partner_id` INT( 11 ) NULL DEFAULT NULL,
  `subpartner_id` INT( 11 ) NULL DEFAULT NULL,
  `location_id` INT(11) NULL DEFAULT NULL,
  `title_option_id` INT(11) NULL DEFAULT NULL,
  `first_name` VARCHAR(32) NULL DEFAULT NULL,
  `middle_name` VARCHAR(32) NULL DEFAULT NULL,
  `last_name`  VARCHAR(32) NULL DEFAULT NULL,
  `national_id` VARCHAR(64) NULL DEFAULT NULL,
  `other_id` VARCHAR(64) NULL DEFAULT NULL,
  `gender` enum('male','female','na') NULL DEFAULT NULL,
  `site_id` INT(11) NULL DEFAULT NULL,
  `address1` VARCHAR(128) NULL DEFAULT NULL,
  `address2` VARCHAR(128) NULL DEFAULT NULL,
  `primary_phone` VARCHAR(64) NULL DEFAULT NULL,
  `secondary_phone` VARCHAR(64) NULL DEFAULT NULL,
  `email` VARCHAR(128) NULL,
  `person_qualification_option_id` INT(11) NULL DEFAULT NULL,
  `employee_category_option_id` INT(11) NULL DEFAULT NULL,
  `primary_role` INT(11) NULL DEFAULT NULL,
  `full_time` TINYINT(1) NULL DEFAULT NULL,
  `funded_hours_per_week` INT(11) NULL DEFAULT NULL,
  `annual_cost` VARCHAR(11) NULL DEFAULT NULL,
  `agreement_end_date` DATETIME NULL DEFAULT NULL,
  `supervisor_id` INT(11) NULL DEFAULT NULL,
  `employee_transition_option_id` INT(11) NULL DEFAULT NULL,
  `transition_confirmed` INT(11) NULL DEFAULT NULL,
  `employee_training_provided_option_id` INT(11) NULL DEFAULT NULL,
  `comments` TEXT NULL DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `timestamp_created` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
  )
 ENGINE = InnoDB DEFAULT CHARSET=utf8;
DROP TRIGGER IF EXISTS `employee_insert`;
DELIMITER $$
CREATE TRIGGER `employee_insert` BEFORE INSERT ON `employee` FOR EACH ROW BEGIN
  SET NEW.`uuid` = IFNULL(NEW.`uuid`,UUID());
END$$
DELIMITER ;


CREATE TABLE `employee_to_relationship` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `employee_id` int(11) NOT NULL,
  `relationship_option_id` int(11) NOT NULL,
  `created_by` int(11) DEFAULT NULL,
  `timestamp_created` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `employee_to_referral` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `employee_id` int(11) NOT NULL,
  `referral_option_id` int(11) NOT NULL,
  `created_by` int(11) DEFAULT NULL,
  `timestamp_created` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



CREATE TABLE `employee_to_role` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `employee_id` int(11) NOT NULL,
  `employee_role_option_id` int(11) NOT NULL,
  `created_by` int(11) DEFAULT NULL,
  `timestamp_created` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



CREATE TABLE `employee_to_course` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `employee_id` varchar(64) NOT NULL,
  `course_name` varchar(64) NULL DEFAULT NULL,
  `provider`    varchar(64) NULL DEFAULT NULL,
  `content`     varchar(64) NULL DEFAULT NULL,
  `duration`    varchar(64) NULL DEFAULT NULL,
  `nqf_level`   varchar(64) NULL DEFAULT NULL,
  `certificate` varchar(64) NULL DEFAULT NULL,
  `accredited`  varchar(64) NULL DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `timestamp_created` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/** OPTION TABLES **/

SET character_set_client = utf8;
CREATE TABLE `employee_category_option` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uuid` CHAR(36) NULL DEFAULT NULL,
  `category_phrase` varchar(128) NOT NULL,
  `modified_by` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  `timestamp_updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `timestamp_created` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name_unique` (`category_phrase`),
  KEY `modified_by` (`modified_by`),
  KEY `created_by` (`created_by`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TRIGGER IF EXISTS `employee_category_insert`;
DELIMITER $$
CREATE TRIGGER `employee_category_insert` BEFORE INSERT ON `employee_category_option` FOR EACH ROW BEGIN
  SET NEW.`uuid` = IFNULL(NEW.`uuid`,UUID());
END$$
DELIMITER ;


SET character_set_client = utf8;
CREATE TABLE `employee_role_option` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uuid` CHAR(36) NULL DEFAULT NULL,
  `role_phrase` varchar(128) NOT NULL,
  `modified_by` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  `timestamp_updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `timestamp_created` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name_unique` (`role_phrase`),
  KEY `modified_by` (`modified_by`),
  KEY `created_by` (`created_by`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TRIGGER IF EXISTS `employee_role_insert`;
DELIMITER $$
CREATE TRIGGER `employee_role_insert` BEFORE INSERT ON `employee_role_option` FOR EACH ROW BEGIN
  SET NEW.`uuid` = IFNULL(NEW.`uuid`,UUID());
END$$
DELIMITER ;


SET character_set_client = utf8;
CREATE TABLE `employee_transition_option` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uuid` CHAR(36) NULL DEFAULT NULL,
  `transition_phrase` varchar(128) NOT NULL,
  `modified_by` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  `timestamp_updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `timestamp_created` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name_unique` (`transition_phrase`),
  KEY `modified_by` (`modified_by`),
  KEY `created_by` (`created_by`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TRIGGER IF EXISTS `employee_transition_insert`;
DELIMITER $$
CREATE TRIGGER `employee_transition_insert` BEFORE INSERT ON `employee_transition_option` FOR EACH ROW BEGIN
  SET NEW.`uuid` = IFNULL(NEW.`uuid`,UUID());
END$$
DELIMITER ;


SET character_set_client = utf8;
CREATE TABLE `employee_relationship_option` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uuid` CHAR(36) NULL DEFAULT NULL,
  `relationship_phrase` varchar(128) NOT NULL,
  `modified_by` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  `timestamp_updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `timestamp_created` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name_unique` (`relationship_phrase`),
  KEY `modified_by` (`modified_by`),
  KEY `created_by` (`created_by`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TRIGGER IF EXISTS `employee_relationship_insert`;
DELIMITER $$
CREATE TRIGGER `employee_relationship_insert` BEFORE INSERT ON `employee_relationship_option` FOR EACH ROW BEGIN
  SET NEW.`uuid` = IFNULL(NEW.`uuid`,UUID());
END$$
DELIMITER ;


SET character_set_client = utf8;
CREATE TABLE `employee_referral_option` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uuid` CHAR(36) NULL DEFAULT NULL,
  `referral_phrase` varchar(128) NOT NULL,
  `modified_by` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  `timestamp_updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `timestamp_created` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name_unique` (`referral_phrase`),
  KEY `modified_by` (`modified_by`),
  KEY `created_by` (`created_by`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TRIGGER IF EXISTS `employee_referral_insert`;
DELIMITER $$
CREATE TRIGGER `employee_referral_insert` BEFORE INSERT ON `employee_referral_option` FOR EACH ROW BEGIN
  SET NEW.`uuid` = IFNULL(NEW.`uuid`,UUID());
END$$
DELIMITER ;


SET character_set_client = utf8;
CREATE TABLE `employee_training_provided_option` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uuid` CHAR(36) NULL DEFAULT NULL,
  `training_provided_phrase` varchar(128) NOT NULL,
  `modified_by` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  `timestamp_updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `timestamp_created` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name_unique` (`training_provided_phrase`),
  KEY `modified_by` (`modified_by`),
  KEY `created_by` (`created_by`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TRIGGER IF EXISTS `employee_training_provided_insert`;
DELIMITER $$
CREATE TRIGGER `employee_training_provided_insert` BEFORE INSERT ON `employee_training_provided_option` FOR EACH ROW BEGIN
  SET NEW.`uuid` = IFNULL(NEW.`uuid`,UUID());
END$$
DELIMITER ;
/* partner option tables */


SET character_set_client = utf8;
CREATE TABLE `partner_importance_option` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uuid` CHAR(36) NULL DEFAULT NULL,
  `importance_phrase` varchar(128) NOT NULL,
  `modified_by` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  `timestamp_updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `timestamp_created` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name_unique` (`importance_phrase`),
  KEY `modified_by` (`modified_by`),
  KEY `created_by` (`created_by`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TRIGGER IF EXISTS `partner_importance_insert`;
DELIMITER $$
CREATE TRIGGER `partner_importance_insert` BEFORE INSERT ON `partner_importance_option` FOR EACH ROW BEGIN
  SET NEW.`uuid` = IFNULL(NEW.`uuid`,UUID());
END$$
DELIMITER ;


SET character_set_client = utf8;
CREATE TABLE `partner_funder_option` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uuid` CHAR(36) NULL DEFAULT NULL,
  `funder_phrase` varchar(128) NOT NULL,
  `modified_by` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  `timestamp_updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `timestamp_created` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name_unique` (`funder_phrase`),
  KEY `modified_by` (`modified_by`),
  KEY `created_by` (`created_by`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TRIGGER IF EXISTS `partner_funder_insert`;
DELIMITER $$
CREATE TRIGGER `partner_funder_insert` BEFORE INSERT ON `partner_funder_option` FOR EACH ROW BEGIN
  SET NEW.`uuid` = IFNULL(NEW.`uuid`,UUID());
END$$
DELIMITER ;


CREATE TABLE `partner_to_funder` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `partner_id` int(11) NOT NULL,
  `partner_funder_option_id` int(11) NOT NULL,
  `funder_end_date` DATETIME NULL DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `timestamp_created` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY (`partner_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


/********************************/
/* engenderhealth - new updates */
/********************************/
/* site rollup feature */
ALTER TABLE  `_system`
  ADD  `module_datashare_enabled`  TINYINT( 1 ) NOT NULL DEFAULT '0',
  ADD  `datashare_password` TEXT NOT NULL DEFAULT '';


CREATE TABLE `datashare_sites` (
  `id`                int(11) NOT NULL AUTO_INCREMENT,
  `uuid`              char(36) DEFAULT NULL,
  `db_name`           VARCHAR(255) NOT NULL,
  `site_password`     TEXT DEFAULT NULL,
  `organizer_access`  TEXT DEFAULT NULL,
  `modified_by`       int(11) DEFAULT NULL,
  `created_by`        int(11) DEFAULT NULL,
  `timestamp_updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `timestamp_created` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `created_by` (`created_by`),
  KEY `modified_by` (`modified_by`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
ALTER TABLE `datashare_sites` ADD UNIQUE `uuid_idx`(uuid);
DELIMITER ;;
CREATE TRIGGER `datashare_sites_insert` BEFORE INSERT ON `datashare_sites` FOR EACH ROW BEGIN
  SET NEW.`uuid` = IFNULL(NEW.`uuid`,UUID());
  END;;
DELIMITER ;


/* facility latitude and longitude */
ALTER TABLE  `facility` ADD  `lat` DECIMAL( 9, 6 ) NULL DEFAULT NULL AFTER  `facility_name` , ADD  `long` DECIMAL( 9, 6 ) NULL DEFAULT NULL AFTER  `lat`;

/* facility sponsors*/

CREATE TABLE `facility_sponsors` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `facility_id` int(11) NOT NULL,
  `facility_sponsor_phrase_id` int(11) DEFAULT NULL,
  `start_date`  timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `end_date`    timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `is_default`  tinyint(1) unsigned NOT NULL DEFAULT '0',
  `modified_by` int(11) DEFAULT NULL,
  `created_by`  int(11) DEFAULT NULL,
  `is_deleted`  tinyint(1) NOT NULL DEFAULT '0',
  `timestamp_updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `timestamp_created` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `created_by` (`created_by`),
  KEY `modified_by` (`modified_by`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
ALTER TABLE `facility_sponsors` ADD COLUMN `uuid` char(36) AFTER `id`;
ALTER TABLE `facility_sponsors` ADD UNIQUE `uuid_idx`(uuid);
ALTER TABLE `facility_sponsors` CHANGE COLUMN `uuid` `uuid` char(36) DEFAULT NULL;
DELIMITER ;;
CREATE TRIGGER `facility_sponsors_insert` BEFORE INSERT ON `facility_sponsors` FOR EACH ROW BEGIN
  SET NEW.`uuid` = IFNULL(NEW.`uuid`,UUID());
  END;;
DELIMITER ;

/********************************/
/* engenderhealth - new updates */
/********************************/

/*ALTER TABLE `_system` DROP `display_training_partner`; */
/*ALTER TABLE `_system` DROP  ADD  `display_training_partner` TINYINT( 1 ) NOT NULL DEFAULT  '0', */

ALTER TABLE  `_system`
  ADD  `require_duplicate_acl` TINYINT( 1 ) NOT NULL DEFAULT  '0',
  ADD  `module_attendance_enabled` TINYINT( 1 ) NOT NULL DEFAULT '0',
  ADD  `display_sponsor_dates`     TINYINT( 1 ) NOT NULL DEFAULT '0',
  ADD  `require_sponsor_dates`     TINYINT( 1 ) NOT NULL DEFAULT '0',
  ADD  `allow_multi_sponsors`      TINYINT( 1 ) NOT NULL DEFAULT '0',
  ADD  `allow_multi_approvers`     TINYINT( 1 ) NOT NULL DEFAULT '0',
  ADD  `fiscal_year_start`         DATETIME NULL DEFAULT NULL ,
  ADD  `display_gender`            TINYINT( 1 ) NOT NULL DEFAULT  '1',
  ADD  `display_training_custom3`  TINYINT( 1 ) NOT NULL DEFAULT  '0',
  ADD  `display_training_custom4`  TINYINT( 1 ) NOT NULL DEFAULT  '0',
  ADD  `display_people_custom3`    TINYINT( 1 ) NOT NULL DEFAULT  '0',
  ADD  `display_people_custom4`    TINYINT( 1 ) NOT NULL DEFAULT  '0',
  ADD  `display_people_custom5`    TINYINT( 1 ) NOT NULL DEFAULT  '0',
  ADD  `display_training_pepfar`   TINYINT( 1 ) NOT NULL DEFAULT  '1',
  ADD  `require_trainer_skill`     TINYINT( 1 ) NOT NULL DEFAULT  '0';
ALTER TABLE  `_system`
  ADD `display_region_d` TINYINT(1) NOT NULL DEFAULT '0',
  ADD `display_region_e` TINYINT(1) NOT NULL DEFAULT '0',
  ADD `display_region_f` TINYINT(1) NOT NULL DEFAULT '0',
  ADD `display_region_g` TINYINT(1) NOT NULL DEFAULT '0',
  ADD `display_region_h` TINYINT(1) NOT NULL DEFAULT '0',
  ADD `display_region_i` TINYINT(1) NOT NULL DEFAULT '0';

/* some labels */
INSERT INTO  `translation` VALUES (NULL , NULL ,  'Training',  'Training', NULL , NULL ,  '0', CURRENT_TIMESTAMP ,  '2012-10-22 00:00:00');
INSERT INTO  `translation` VALUES (NULL , NULL ,  'Trainer',  'Trainer', NULL , NULL ,  '0',   CURRENT_TIMESTAMP ,  '2012-10-22 00:00:00');
INSERT INTO  `translation` VALUES (NULL , NULL ,  'Trainings',  'Trainings', NULL , NULL ,  '0', CURRENT_TIMESTAMP ,  '2012-10-22 00:00:00');
INSERT INTO  `translation` VALUES (NULL , NULL ,  'Trainers',  'Trainers', NULL , NULL ,  '0', CURRENT_TIMESTAMP ,  '2012-10-22 00:00:00');
INSERT INTO  `translation` VALUES (NULL , NULL ,  'Participant',  'Participant', NULL , NULL ,  '0', CURRENT_TIMESTAMP ,  '2012-10-22 00:00:00');
INSERT INTO  `translation` VALUES (NULL , NULL ,  'Participants',  'Participants', NULL , NULL ,  '0', CURRENT_TIMESTAMP ,  '2012-10-22 00:00:00');
INSERT INTO  `translation` VALUES (NULL , NULL ,  'Training Center',  'Training Center', NULL , NULL ,  '0', CURRENT_TIMESTAMP ,  '2012-10-22 00:00:00');
INSERT INTO  `translation` VALUES (NULL , NULL ,  'Region D',  'Local Region', NULL , NULL ,  '0', CURRENT_TIMESTAMP ,  '2012-10-22 00:00:00');
INSERT INTO  `translation` VALUES (NULL , NULL ,  'Region E',  'Local Region', NULL , NULL ,  '0', CURRENT_TIMESTAMP ,  '2012-10-22 00:00:00');
INSERT INTO  `translation` VALUES (NULL , NULL ,  'Region F',  'Local Region', NULL , NULL ,  '0', CURRENT_TIMESTAMP ,  '2012-10-22 00:00:00');
INSERT INTO  `translation` VALUES (NULL , NULL ,  'Region G',  'Local Region', NULL , NULL ,  '0', CURRENT_TIMESTAMP ,  '2012-10-22 00:00:00');
INSERT INTO  `translation` VALUES (NULL , NULL ,  'Region H',  'Local Region', NULL , NULL ,  '0', CURRENT_TIMESTAMP ,  '2012-10-22 00:00:00');
INSERT INTO  `translation` VALUES (NULL , NULL ,  'Region I',  'Local Region', NULL , NULL ,  '0', CURRENT_TIMESTAMP ,  '2012-10-22 00:00:00');

/* people labels */
INSERT INTO  `translation` VALUES (NULL , NULL ,  'People Custom 3',  'People Custom 3', NULL , NULL ,  '0', CURRENT_TIMESTAMP ,  '2012-10-22 00:00:00');
INSERT INTO  `translation` VALUES (NULL , NULL ,  'People Custom 4',  'People Custom 4', NULL , NULL ,  '0', CURRENT_TIMESTAMP ,  '2012-10-22 00:00:00');
INSERT INTO  `translation` VALUES (NULL , NULL ,  'People Custom 5',  'People Custom 5', NULL , NULL ,  '0', CURRENT_TIMESTAMP ,  '2012-10-22 00:00:00');
INSERT INTO  `translation` VALUES (NULL , NULL ,  'Gender',  'Gender', NULL , NULL ,  '0', CURRENT_TIMESTAMP ,  '2012-10-22 00:00:00');
INSERT INTO  `translation` VALUES (NULL , NULL ,  'Address 1',  'Address 1', NULL , NULL ,  '0', CURRENT_TIMESTAMP ,  '2012-10-22 00:00:00');
INSERT INTO  `translation` VALUES (NULL , NULL ,  'Address 2',  'Address 2', NULL , NULL ,  '0', CURRENT_TIMESTAMP ,  '2012-10-22 00:00:00');
INSERT INTO  `translation` VALUES (NULL , NULL ,  'Home phone',  'Home phone', NULL , NULL ,  '0', CURRENT_TIMESTAMP ,  '2012-10-22 00:00:00');

ALTER TABLE  `person`
  ADD  `custom_3` VARCHAR( 255 ) NULL DEFAULT  '',
  ADD  `custom_4` VARCHAR( 255 ) NULL DEFAULT  '',
  ADD  `custom_5` VARCHAR( 255 ) NULL DEFAULT  '';

/* track attendance */
ALTER TABLE `person_to_training`
  ADD  `award_id`   TINYINT( 1 ) NOT NULL DEFAULT '0',
  ADD  `duration_days` float NULL;

/* approval */
ALTER TABLE `person`   ADD `approved` TINYINT(1) NULL DEFAULT NULL;
ALTER TABLE `facility` ADD `approved` TINYINT(1) NULL DEFAULT NULL;
ALTER TABLE `_system`  ADD `module_person_approval` TINYINT(1) NOT NULL DEFAULT '0';
ALTER TABLE `_system`  ADD `module_facility_approval` TINYINT(1) NOT NULL DEFAULT '0';
UPDATE `person` SET `approved` = 1;
UPDATE `facility` SET `approved` = 1;

/* acls */
ALTER TABLE  `user_to_acl` CHANGE  `acl_id`  `acl_id` ENUM ('in_service', 'pre_service', 'edit_employee', 'edit_course', 'view_course', 'duplicate_training', 'approve_trainings', 'master_approver', 'edit_people', 'view_people', 'edit_training_location', 'edit_facility', 'view_facility', 'view_create_reports', 'training_organizer_option_all', 'training_title_option_all', 'use_offline_app', 'admin_files', 'facility_and_person_approver', 'edit_evaluations', 'edit_country_options', 'acl_editor_training_category', 'acl_editor_people_qualifications', 'acl_editor_people_responsibility', 'acl_editor_training_organizer', 'acl_editor_people_trainer', 'acl_editor_training_topic', 'acl_editor_people_titles', 'acl_editor_training_level', 'acl_editor_refresher_course', 'acl_editor_people_trainer_skills', 'acl_editor_pepfar_category', 'acl_editor_people_languages', 'acl_editor_funding', 'acl_editor_people_affiliations', 'acl_editor_recommended_topic', 'acl_editor_nationalcurriculum', 'acl_editor_people_suffix', 'acl_editor_method', 'acl_editor_people_active_trainer', 'acl_editor_facility_types', 'acl_editor_ps_classes', 'acl_editor_facility_sponsors', 'acl_editor_ps_cadres', 'acl_editor_ps_degrees', 'acl_editor_ps_funding', 'acl_editor_ps_institutions', 'acl_editor_ps_languages', 'acl_editor_ps_nationalities', 'acl_editor_ps_joindropreasons', 'acl_editor_ps_sponsors', 'acl_editor_ps_tutortypes', 'acl_editor_ps_coursetypes', 'acl_editor_ps_religions', 'add_edit_users', 'acl_admin_training', 'acl_admin_people', 'acl_admin_facilities', 'import_training', 'import_training_location', 'import_facility', 'import_person' )  NOT NULL DEFAULT 'view_course';
INSERT INTO `acl` (`id` ,`acl`) VALUES ('edit_employee',  'edit_employee');
INSERT INTO `acl` (`id` ,`acl`) VALUES ('duplicate_training', 'duplicate_training');
INSERT INTO `acl` (`id` ,`acl`) VALUES ('import_training',  'import_training');
INSERT INTO `acl` (`id` ,`acl`) VALUES ('import_training_location',  'import_training_location');
INSERT INTO `acl` (`id` ,`acl`) VALUES ('import_facility',  'import_facility');
INSERT INTO `acl` (`id` ,`acl`) VALUES ('import_person',  'import_person');
INSERT INTO `acl` (`id` ,`acl`) VALUES ('facility_and_person_approver',  'facility_and_person_approver');
INSERT INTO `acl` (`id`, `acl`) VALUES ('acl_editor_training_category', 'acl_editor_training_category');
INSERT INTO `acl` (`id`, `acl`) VALUES ('acl_editor_people_qualifications', 'acl_editor_people_qualifications');
INSERT INTO `acl` (`id`, `acl`) VALUES ('acl_editor_people_responsibility', 'acl_editor_people_responsibility');
INSERT INTO `acl` (`id`, `acl`) VALUES ('acl_editor_training_organizer', 'acl_editor_training_organizer');
INSERT INTO `acl` (`id`, `acl`) VALUES ('acl_editor_people_trainer', 'acl_editor_people_trainer');
INSERT INTO `acl` (`id`, `acl`) VALUES ('acl_editor_training_topic', 'acl_editor_training_topic');
INSERT INTO `acl` (`id`, `acl`) VALUES ('acl_editor_people_titles', 'acl_editor_people_titles');
INSERT INTO `acl` (`id`, `acl`) VALUES ('acl_editor_training_level', 'acl_editor_training_level');
INSERT INTO `acl` (`id`, `acl`) VALUES ('acl_editor_people_trainer_skills', 'acl_editor_people_trainer_skills');
INSERT INTO `acl` (`id`, `acl`) VALUES ('acl_editor_pepfar_category', 'acl_editor_pepfar_category');
INSERT INTO `acl` (`id`, `acl`) VALUES ('acl_editor_people_languages', 'acl_editor_people_languages');
INSERT INTO `acl` (`id`, `acl`) VALUES ('acl_editor_funding', 'acl_editor_funding');
INSERT INTO `acl` (`id`, `acl`) VALUES ('acl_editor_people_affiliations', 'acl_editor_people_affiliations');
INSERT INTO `acl` (`id`, `acl`) VALUES ('acl_editor_recommended_topic', 'acl_editor_recommended_topic');
INSERT INTO `acl` (`id`, `acl`) VALUES ('acl_editor_nationalcurriculum', 'acl_editor_nationalcurriculum');
INSERT INTO `acl` (`id`, `acl`) VALUES ('acl_editor_people_suffix', 'acl_editor_people_suffix');
INSERT INTO `acl` (`id`, `acl`) VALUES ('acl_editor_method', 'acl_editor_method');
INSERT INTO `acl` (`id`, `acl`) VALUES ('acl_editor_people_active_trainer', 'acl_editor_people_active_trainer');
INSERT INTO `acl` (`id`, `acl`) VALUES ('acl_editor_facility_types', 'acl_editor_facility_types');
INSERT INTO `acl` (`id`, `acl`) VALUES ('acl_editor_facility_sponsors', 'acl_editor_facility_sponsors');
INSERT INTO `acl` (`id`, `acl`) VALUES ('acl_editor_ps_classes', 'acl_editor_ps_classes');
INSERT INTO `acl` (`id`, `acl`) VALUES ('acl_editor_ps_cadres', 'acl_editor_ps_cadres');
INSERT INTO `acl` (`id`, `acl`) VALUES ('acl_editor_ps_degrees', 'acl_editor_ps_degrees');
INSERT INTO `acl` (`id`, `acl`) VALUES ('acl_editor_ps_funding', 'acl_editor_ps_funding');
INSERT INTO `acl` (`id`, `acl`) VALUES ('acl_editor_ps_institutions', 'acl_editor_ps_institutions');
INSERT INTO `acl` (`id`, `acl`) VALUES ('acl_editor_ps_languages', 'acl_editor_ps_languages');
INSERT INTO `acl` (`id`, `acl`) VALUES ('acl_editor_ps_nationalities', 'acl_editor_ps_nationalities');
INSERT INTO `acl` (`id`, `acl`) VALUES ('acl_editor_ps_joindropreasons', 'acl_editor_ps_joindropreasons');
INSERT INTO `acl` (`id`, `acl`) VALUES ('acl_editor_ps_sponsors', 'acl_editor_ps_sponsors');
INSERT INTO `acl` (`id`, `acl`) VALUES ('acl_editor_ps_tutortypes', 'acl_editor_ps_tutortypes');
INSERT INTO `acl` (`id`, `acl`) VALUES ('acl_editor_ps_coursetypes', 'acl_editor_ps_coursetypes');
INSERT INTO `acl` (`id`, `acl`) VALUES ('acl_editor_ps_religions', 'acl_editor_ps_religions');
INSERT INTO `acl` (`id`, `acl`) VALUES ('acl_admin_training', 'acl_admin_training');
INSERT INTO `acl` (`id`, `acl`) VALUES ('acl_admin_people', 'acl_admin_people');
INSERT INTO `acl` (`id`, `acl`) VALUES ('acl_admin_facilities', 'acl_admin_facilities');
INSERT INTO `acl` (`id`, `acl`) VALUES ('acl_editor_refresher_course', 'acl_editor_refresher_course');
INSERT INTO `acl` (`id`, `acl`) VALUES ('master_approver',  'master_approver');
INSERT INTO `acl` (`id`, `acl`) VALUES ('edit_evaluations',  'edit_evaluations');
INSERT INTO `acl` (`id`, `acl`) VALUES ('edit_facility',  'edit_facility');
INSERT INTO `acl` (`id`, `acl`) VALUES ('view_facility',  'view_facility');
INSERT INTO `acl` (`id`, `acl`) VALUES ('edit_training_location',  'edit_training_location');


/* evaluations */
ALTER TABLE  `evaluation_question` CHANGE  `question_type`  `question_type` ENUM(  'Likert',  'Text',  'Likert3',  'Likert3NA', 'LikertNA' ) NOT NULL DEFAULT  'Likert';
ALTER TABLE  `evaluation_response` ADD     `trainer_person_id` INT( 11 ) NULL DEFAULT NULL AFTER  `evaluation_to_training_id`;

/* score - improved indexing */
ALTER TABLE `score` ADD INDEX  `scorelabel` (  `score_label` );


/* custom fields 'training' */
ALTER TABLE  `training`
  ADD  `custom_3` VARCHAR( 255 ) NULL DEFAULT  '',
  ADD  `custom_4` VARCHAR( 255 ) NULL DEFAULT  '';
INSERT INTO  `translation` VALUES (NULL , NULL ,  'Training Custom 3',  'Custom field 3', NULL , NULL ,  '0',  CURRENT_TIMESTAMP ,  '2012-10-22 00:00:00');
INSERT INTO  `translation` VALUES (NULL , NULL ,  'Training Custom 4',  'Custom field 4', NULL , NULL ,  '0',  CURRENT_TIMESTAMP ,  '2012-10-22 00:00:00');

ALTER TABLE `person`
  CHANGE `multi_facility_ids` `multi_facility_ids` varchar(255) NULL DEFAULT NULL,
  CHANGE `home_city` `home_city` varchar(255) NULL DEFAULT '',
  CHANGE `highest_level_option_id` `highest_level_option_id` int(11) NULL DEFAULT NULL,
  CHANGE `govemp_option_id` `govemp_option_id` tinyint(4) NULL DEFAULT '0',
  CHANGE `occupational_category_id` `occupational_category_id` int(10) unsigned NULL DEFAULT NULL,
  CHANGE `persal_number` `persal_number` int(10) unsigned NULL DEFAULT NULL,
  CHANGE `bodies_id` `bodies_id` int(10) unsigned NULL DEFAULT NULL,
  CHANGE `race_option_id` `race_option_id` int(10) unsigned NULL DEFAULT NULL,
  CHANGE `disability_option_id` `disability_option_id` int(10) unsigned NULL DEFAULT NULL,
  CHANGE `professional_reg_number` `professional_reg_number` int(10) unsigned NULL DEFAULT NULL,
  CHANGE `nationality_id` `nationality_id` int(10) unsigned NULL DEFAULT NULL,
  CHANGE `nurse_training_id` `nurse_training_id` int(10) unsigned NULL DEFAULT NULL,
  CHANGE `care_start_year` `care_start_year` int(10) unsigned NULL DEFAULT NULL,
  CHANGE `timespent_rank_pregnant` `timespent_rank_pregnant` int(10) unsigned NULL DEFAULT NULL,
  CHANGE `timespent_rank_adults` `timespent_rank_adults` int(10) unsigned NULL DEFAULT NULL,
  CHANGE `timespent_rank_children` `timespent_rank_children` int(10) unsigned NULL DEFAULT NULL,
  CHANGE `timespent_rank_pregnant_pct` `timespent_rank_pregnant_pct` int(10) unsigned NULL DEFAULT NULL,
  CHANGE `timespent_rank_adults_pct` `timespent_rank_adults_pct` int(10) unsigned NULL DEFAULT NULL,
  CHANGE `timespent_rank_children_pct` `timespent_rank_children_pct` int(10) unsigned NULL DEFAULT NULL,
  CHANGE `supervised_id` `supervised_id` int(10) unsigned NULL DEFAULT NULL,
  CHANGE `supervision_frequency_id` `supervision_frequency_id` int(10) unsigned NULL DEFAULT NULL,
  CHANGE `supervisors_profession` `supervisors_profession` varchar(255) NULL DEFAULT NULL,
  CHANGE `training_recieved_data` `training_recieved_data` text NULL DEFAULT NULL,
  CHANGE `facilitydepartment_id` `facilitydepartment_id` int(10) unsigned NULL DEFAULT NULL;




# end skillsmart updates

# participant changes - namibia viewing location and budget code for participtans
INSERT INTO  `translation` VALUES (NULL , NULL ,  'Viewing Location',  'Viewing Location', NULL , NULL ,  '0', CURRENT_TIMESTAMP ,  '2012-10-22 00:00:00');
INSERT INTO  `translation` VALUES (NULL , NULL ,  'Budget Code',  'Budget Code', NULL , NULL ,  '0', CURRENT_TIMESTAMP ,  '2012-10-22 00:00:00');

ALTER TABLE  `_system`
  ADD  `display_budget_code` TINYINT( 1 ) NOT NULL DEFAULT  '0',
  ADD  `display_viewing_location` TINYINT( 1 ) NOT NULL DEFAULT '0';

ALTER TABLE `person_to_training`
  ADD `viewing_location_option_id` INT( 11 ) NOT NULL DEFAULT '0',
  ADD `budget_code_option_id`      INT( 11 ) NOT NULL DEFAULT '0';

CREATE TABLE IF NOT EXISTS `person_to_training_viewing_loc_option` (
  `id` int(11) NOT NULL auto_increment,
  `uuid` char(36) default NULL,
  `location_phrase` varchar(128) NOT NULL,
  `modified_by` int(11) default NULL,
  `created_by` int(11) default NULL,
  `is_deleted` tinyint(1) NOT NULL default '0',
  `timestamp_updated` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `timestamp_created` timestamp NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `name_unique` (`location_phrase`),
  UNIQUE KEY `uuid_idx` (`uuid`),
  KEY `modified_by` (`modified_by`),
  KEY `created_by` (`created_by`)
) DEFAULT CHARSET=utf8 ;
DROP TRIGGER IF EXISTS `person_to_training_viewing_loc_insert`;
DELIMITER $$
CREATE TRIGGER `person_to_training_viewing_loc_insert` BEFORE INSERT ON `person_to_training_viewing_loc_option` FOR EACH ROW BEGIN
  SET NEW.`uuid` = IFNULL(NEW.`uuid`,UUID());
END$$
DELIMITER ;

CREATE TABLE IF NOT EXISTS `person_to_training_budget_option` (
  `id` int(11) NOT NULL auto_increment,
  `uuid` char(36) default NULL,
  `budget_code_phrase` varchar(128) NOT NULL,
  `modified_by` int(11) default NULL,
  `created_by` int(11) default NULL,
  `is_deleted` tinyint(1) NOT NULL default '0',
  `timestamp_updated` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `timestamp_created` timestamp NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `name_unique` (`budget_code_phrase`),
  UNIQUE KEY `uuid_idx` (`uuid`),
  KEY `modified_by` (`modified_by`),
  KEY `created_by` (`created_by`)
) DEFAULT CHARSET=utf8 ;

DROP TRIGGER IF EXISTS `person_to_training_budget_insert`;
DELIMITER $$
CREATE TRIGGER `person_to_training_budget_insert` BEFORE INSERT ON `person_to_training_budget_option` FOR EACH ROW BEGIN
  SET NEW.`uuid` = IFNULL(NEW.`uuid`,UUID());
END$$
DELIMITER ;



#skillsmart
INSERT INTO  `translation` VALUES (NULL , NULL ,  'Occupational category', 'Occupational category', NULL, NULL, '0', CURRENT_TIMESTAMP, '2012-10-22 00:00:00');
INSERT INTO  `translation` VALUES (NULL , NULL ,  'Government employee', 'Government employee', NULL, NULL, '0', CURRENT_TIMESTAMP, '2012-10-22 00:00:00');
INSERT INTO  `translation` VALUES (NULL , NULL ,  'Professional bodies', 'Professional bodies', NULL, NULL, '0', CURRENT_TIMESTAMP, '2012-10-22 00:00:00');
INSERT INTO  `translation` VALUES (NULL , NULL ,  'Race', 'Race', NULL, NULL, '0', CURRENT_TIMESTAMP, '2012-10-22 00:00:00');
INSERT INTO  `translation` VALUES (NULL , NULL ,  'Disability', 'Disability', NULL, NULL, '0', CURRENT_TIMESTAMP, '2012-10-22 00:00:00');
INSERT INTO  `translation` VALUES (NULL , NULL ,  'Nurse trainer type', 'Nurse trainer type', NULL, NULL, '0', CURRENT_TIMESTAMP, '2012-10-22 00:00:00');
INSERT INTO  `translation` VALUES (NULL , NULL ,  'Year you started providing care', 'Year you started providing care', NULL, NULL, '0', CURRENT_TIMESTAMP, '2012-10-22 00:00:00');
INSERT INTO  `translation` VALUES (NULL , NULL ,  'Rank patient groups based on time', 'Rank patient groups based on time', NULL, NULL, '0', CURRENT_TIMESTAMP, '2012-10-22 00:00:00');
INSERT INTO  `translation` VALUES (NULL , NULL ,  'Supervised', 'Supervised', NULL, NULL, '0', CURRENT_TIMESTAMP, '2012-10-22 00:00:00');
INSERT INTO  `translation` VALUES (NULL , NULL ,  'Indicate the training you received', 'Indicate the training you received', NULL, NULL, '0', CURRENT_TIMESTAMP, '2012-10-22 00:00:00');
INSERT INTO  `translation` VALUES (NULL , NULL ,  'Facility department', 'Facility department', NULL, NULL, '0', CURRENT_TIMESTAMP, '2012-10-22 00:00:00');

#training completion ("award") lookup table
CREATE TABLE IF NOT EXISTS `person_to_training_award_option` (
  `id` int(11) NOT NULL auto_increment,
  `uuid` char(36) default NULL,
  `award_phrase` varchar(128) NOT NULL,
  `modified_by` int(11) default NULL,
  `created_by` int(11) default NULL,
  `is_deleted` tinyint(1) NOT NULL default '0',
  `timestamp_updated` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `timestamp_created` timestamp NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `name_unique` (`award_phrase`),
  UNIQUE KEY `uuid_idx` (`uuid`),
  KEY `modified_by` (`modified_by`),
  KEY `created_by` (`created_by`)
) DEFAULT CHARSET=utf8 ;

DROP TRIGGER IF EXISTS `person_to_training_award_insert`;
DELIMITER $$
CREATE TRIGGER `person_to_training_award_insert` BEFORE INSERT ON `person_to_training_award_option` FOR EACH ROW BEGIN
  SET NEW.`uuid` = IFNULL(NEW.`uuid`,UUID());
END$$
DELIMITER ;

INSERT INTO  `translation` VALUES (NULL , NULL ,  'Award', 'Complete', NULL, NULL, '0', CURRENT_TIMESTAMP, '2012-10-22 00:00:00');
INSERT INTO  `translation` VALUES (NULL , NULL ,  'Disability Comments', 'Disability Comments', NULL, NULL, '0', CURRENT_TIMESTAMP, '2012-10-22 00:00:00');
INSERT INTO  `person_to_training_award_option` VALUES (NULL , NULL ,  'Complete', NULL, '1', '0', CURRENT_TIMESTAMP, '2012-10-22 00:00:00');
INSERT INTO  `person_to_training_award_option` VALUES (NULL , NULL ,  'Certification', NULL, '1', '0', CURRENT_TIMESTAMP, '2012-10-22 00:00:00');


#requested changes to employee module: dob, race option, other id label

ALTER TABLE  `_system`
    ADD  `display_employee_registration_number` TINYINT( 1 ) NOT NULL DEFAULT  '1',
    ADD  `display_employee_dob`                 TINYINT( 1 ) NOT NULL DEFAULT  '1',
    ADD  `display_employee_race`                TINYINT( 1 ) NOT NULL DEFAULT  '1',
    ADD  `display_employee_other_id`            TINYINT( 1 ) NOT NULL DEFAULT  '1',
    ADD  `display_employee_disability`          TINYINT( 1 ) NOT NULL DEFAULT  '1',
    ADD  `display_employee_salary`              TINYINT( 1 ) NOT NULL DEFAULT  '1',
    ADD  `display_employee_benefits`            TINYINT( 1 ) NOT NULL DEFAULT  '1',
    ADD  `display_employee_additional_expenses` TINYINT( 1 ) NOT NULL DEFAULT  '1',
    ADD  `display_employee_stipend`             TINYINT( 1 ) NOT NULL DEFAULT  '1';
INSERT INTO  `translation` VALUES (NULL , NULL ,  'Other ID', 'I.D./Passport Number', NULL, NULL, '0', CURRENT_TIMESTAMP, '2012-10-22 00:00:00');
INSERT INTO  `translation` VALUES (NULL , NULL ,  'Employee Date of Birth', 'Date of Birth', NULL, NULL, '0', CURRENT_TIMESTAMP, '2012-10-22 00:00:00');
INSERT INTO  `translation` VALUES (NULL , NULL ,  'Disability', 'Disability', NULL, NULL, '0', CURRENT_TIMESTAMP, '2012-10-22 00:00:00');
INSERT INTO  `translation` VALUES (NULL , NULL ,  'Disability Comments', 'Disability Comments', NULL, NULL, '0', CURRENT_TIMESTAMP, '2012-10-22 00:00:00');
INSERT INTO  `translation` VALUES (NULL , NULL ,  'Race', 'Race', NULL, NULL, '0', CURRENT_TIMESTAMP, '2012-10-22 00:00:00');
INSERT INTO  `translation` VALUES (NULL , NULL ,  'Registration Number', 'Registration Number', NULL, NULL, '0', CURRENT_TIMESTAMP, '2012-10-22 00:00:00');
INSERT INTO  `translation` VALUES (NULL , NULL ,  'Salary', 'Salary', NULL, NULL, '0', CURRENT_TIMESTAMP, '2012-10-22 00:00:00');
INSERT INTO  `translation` VALUES (NULL , NULL ,  'Benefits', 'Benefits', NULL, NULL, '0', CURRENT_TIMESTAMP, '2012-10-22 00:00:00');
INSERT INTO  `translation` VALUES (NULL , NULL ,  'Additional Expenses', 'Additional Expenses', NULL, NULL, '0', CURRENT_TIMESTAMP, '2012-10-22 00:00:00');
INSERT INTO  `translation` VALUES (NULL , NULL ,  'Stipend', 'Stipend', NULL, NULL, '0', CURRENT_TIMESTAMP, '2012-10-22 00:00:00');
INSERT INTO  `translation` VALUES (NULL , NULL ,  'Staff Cadre', 'Staff Cadre', NULL, NULL, '0', CURRENT_TIMESTAMP, '2012-10-22 00:00:00');

ALTER TABLE `employee`
    CHANGE COLUMN `person_qualification_option_id` `employee_qualification_option_id` INT(11) NULL DEFAULT NULL,
    ADD COLUMN `disability_option_id` INT(11) AFTER `gender`,
    ADD COLUMN `disability_comments`  VARCHAR(128) AFTER `gender`,
    ADD COLUMN `dob`                  DATETIME AFTER `gender`,
    ADD COLUMN `race_option_id`       INT(11)  AFTER `gender`,
    ADD COLUMN `registration_number`  VARCHAR(40) DEFAULT null,
    ADD COLUMN `salary`               VARCHAR(11) DEFAULT null,
    ADD COLUMN `benefits`             VARCHAR(11) DEFAULT null,
    ADD COLUMN `additional_expenses`  VARCHAR(11) DEFAULT null,
    ADD COLUMN `stipend`              VARCHAR(11) DEFAULT null;

CREATE TABLE IF NOT EXISTS `person_race_option` (
  `id` int(11) NOT NULL auto_increment,
  `uuid` char(36) default NULL,
  `race_phrase` varchar(128) NOT NULL,
  `modified_by` int(11) default NULL,
  `created_by` int(11) default NULL,
  `is_deleted` tinyint(1) NOT NULL default '0',
  `timestamp_updated` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `timestamp_created` timestamp NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `name_unique` (`race_phrase`),
  UNIQUE KEY `uuid_idx` (`uuid`),
  KEY `modified_by` (`modified_by`),
  KEY `created_by` (`created_by`)
) DEFAULT CHARSET=utf8 ;
DROP TRIGGER IF EXISTS `person_race_insert`;
DELIMITER $$
CREATE TRIGGER `person_race_insert` BEFORE INSERT ON `person_race_option` FOR EACH ROW BEGIN
  SET NEW.`uuid` = IFNULL(NEW.`uuid`,UUID());
END$$
DELIMITER ;

CREATE TABLE IF NOT EXISTS `employee_qualification_option` (
  `id` int(11) NOT NULL auto_increment,
  `uuid` char(36) default NULL,
  `qualification_phrase` varchar(128) NOT NULL,
  `qualification_code`   varchar(64)  default NULL,
  `modified_by`          int(11) default NULL,
  `created_by`           int(11) default NULL,
  `is_deleted`           tinyint(1) NOT NULL default '0',
  `timestamp_updated`    timestamp NOT NULL default CURRENT_TIMESTAMP,
  `timestamp_created`    timestamp NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `name_unique` (`qualification_phrase`),
  UNIQUE KEY `uuid_idx` (`uuid`),
  KEY `modified_by` (`modified_by`),
  KEY `created_by` (`created_by`)
) DEFAULT CHARSET=utf8 ;
DROP TRIGGER IF EXISTS `employee_qualification_insert`;
DELIMITER $$
CREATE TRIGGER `employee_qualification_insert` BEFORE INSERT ON `employee_qualification_option` FOR EACH ROW BEGIN
  SET NEW.`uuid` = IFNULL(NEW.`uuid`,UUID());
END$$
DELIMITER ;

#make fields nullable for desktop application
ALTER TABLE  `person_to_training`
  CHANGE  `award_id`  `award_id` TINYINT( 1 ) NULL DEFAULT  '0',
  CHANGE  `viewing_location_option_id`  `viewing_location_option_id` INT( 11 ) NULL DEFAULT  '0',
  CHANGE  `budget_code_option_id`  `budget_code_option_id` INT( 11 ) NULL DEFAULT  '0';

# migrate some acls so ppl can edit evaluations and employees if they are admins..
INSERT INTO user_to_acl SELECT DISTINCT null, 'edit_evaluations', id, 1, NOW() from user_to_acl where acl_id = 'edit_country_options';
INSERT INTO user_to_acl SELECT DISTINCT null, 'edit_employee', id, 1, NOW() from user_to_acl where acl_id = 'edit_country_options';

#south africa requested changes to employee

CREATE TABLE IF NOT EXISTS `employee_fulltime_option` (
  `id` int(11) NOT NULL auto_increment,
  `uuid` char(36) default NULL,
  `fulltime_phrase`      varchar(128) NOT NULL,
  `modified_by`          int(11) default NULL,
  `created_by`           int(11) default NULL,
  `is_deleted`           tinyint(1) NOT NULL default '0',
  `timestamp_updated`    timestamp NOT NULL default CURRENT_TIMESTAMP,
  `timestamp_created`    timestamp NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `name_unique` (`fulltime_phrase`),
  UNIQUE KEY `uuid_idx` (`uuid`),
  KEY `modified_by` (`modified_by`),
  KEY `created_by` (`created_by`)
) DEFAULT CHARSET=utf8 ;
DROP TRIGGER IF EXISTS `employee_fulltime_insert`;
DELIMITER $$
CREATE TRIGGER `employee_fulltime_insert` BEFORE INSERT ON `employee_fulltime_option` FOR EACH ROW BEGIN
  SET NEW.`uuid` = IFNULL(NEW.`uuid`,UUID());
END$$
DELIMITER ;

# default values
INSERT INTO `employee_fulltime_option` (`id`, `uuid`, `fulltime_phrase`, `modified_by`, `created_by`, `is_deleted`, `timestamp_updated`, `timestamp_created`) VALUES (NULL, NULL, 'Full Time', NULL, '1', '0', CURRENT_TIMESTAMP, '0000-00-00 00:00:00'), (NULL, NULL, 'Part Time', NULL, '1', '0', CURRENT_TIMESTAMP, '0000-00-00 00:00:00');

ALTER TABLE `employee`
    CHANGE `full_time` `employee_fulltime_option_id` INT(11) NULL DEFAULT NULL,
    ADD  `employee_transition_complete_option_id` INT NOT NULL AFTER  `employee_transition_option_id`,
    ADD  `option_nationality_id` INT( 11 ) NULL DEFAULT  '0' AFTER  `race_option_id`;

ALTER TABLE `_system`
    ADD COLUMN `display_employee_complete_transition`  tinyint(1) NOT NULL DEFAULT 1,
    ADD COLUMN `display_facility_postal_code`          tinyint(1) NOT NULL DEFAULT 1,
    ADD COLUMN `display_facility_lat_long`             tinyint(1) NOT NULL DEFAULT 1,
    ADD COLUMN `display_employee_nationality`          tinyint(1) NOT NULL DEFAULT 1,
    ADD COLUMN `display_facility_sponsor`              tinyint(1) NOT NULL DEFAULT 1;

# engender health updates
INSERT INTO  `translation` VALUES (NULL , NULL ,  'Sponsor Date',  'Sponsor Date', NULL , NULL ,  '0',  CURRENT_TIMESTAMP ,  '0000-00-00 00:00:00');
INSERT INTO  `translation` VALUES (NULL , NULL ,  'Employee Nationality', 'Nationality', NULL , NULL ,  '0',  CURRENT_TIMESTAMP ,  '0000-00-00 00:00:00');

# requested changes to evaluations 5/1

ALTER TABLE  `evaluation_response` ADD     `person_id` INT( 11 ) NULL DEFAULT NULL AFTER  `trainer_person_id`;


SET character_set_client = utf8;
CREATE TABLE `evaluation_custom_answers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uuid` CHAR(36) NULL DEFAULT NULL,
  `evaluation_id` int(11) NOT NULL,
  `question_id` int(11) NOT NULL,
  `answer_phrase` varchar(128) NOT NULL,
  `modified_by` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  `timestamp_updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `timestamp_created` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `modified_by` (`modified_by`),
  KEY `created_by` (`created_by`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
DROP TRIGGER IF EXISTS `evaluation_custom_answers_insert`;
DELIMITER $$
CREATE TRIGGER `evaluation_custom_answers_insert` BEFORE INSERT ON `evaluation_custom_answers` FOR EACH ROW BEGIN
  SET NEW.`uuid` = IFNULL(NEW.`uuid`,UUID());
END$$
DELIMITER ;

# requested changes to employees 5/1
INSERT INTO  `translation` VALUES (NULL , NULL ,  'Type of Partner',  'Type of Partner', NULL , NULL ,  '0',  CURRENT_TIMESTAMP ,  '0000-00-00 00:00:00');
INSERT INTO  `translation` VALUES (NULL , NULL ,  'Employee Based at','Based at', NULL , NULL ,  '0',  CURRENT_TIMESTAMP ,  '0000-00-00 00:00:00');

ALTER TABLE `_system`
    ADD COLUMN `display_partner_type`  tinyint(1) NOT NULL DEFAULT '1',
    ADD COLUMN `display_employee_base` tinyint(1) NOT NULL DEFAULT '1';

ALTER TABLE `partner` ADD COLUMN `hr_contact_name`  varchar(64) NULL DEFAULT NULL AFTER `fax`;
ALTER TABLE `partner` ADD COLUMN `hr_contact_phone` varchar(40) NULL DEFAULT NULL AFTER `hr_contact_name`;
ALTER TABLE `partner` ADD COLUMN `hr_contact_fax`   varchar(40) NULL DEFAULT NULL AFTER `hr_contact_phone`;
ALTER TABLE `partner` ADD COLUMN `hr_contact_email` varchar(40) NULL DEFAULT NULL AFTER `hr_contact_fax`;
ALTER TABLE `partner` ADD COLUMN `partner_type_option_id` int(11) NULL DEFAULT NULL AFTER `partner`;

ALTER TABLE `employee`
    ADD COLUMN `facility_type_option_id`  int(11) NULL DEFAULT NULL AFTER `site_id`,
    ADD COLUMN `employee_base_option_id`  int(11) NULL DEFAULT NULL AFTER `subpartner_id`,
    ADD COLUMN `transition_date`          DATETIME NULL DEFAULT NULL AFTER `transition_confirmed`,
    CHANGE  `primary_role`  `employee_role_option_id` INT( 11 ) NULL DEFAULT NULL;



SET character_set_client = utf8;
CREATE TABLE `partner_type_option` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uuid` CHAR(36) NULL DEFAULT NULL,
  `type_phrase` varchar(128) NOT NULL,
  `modified_by` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  `timestamp_updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `timestamp_created` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name_unique` (`type_phrase`),
  KEY `modified_by` (`modified_by`),
  KEY `created_by` (`created_by`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TRIGGER IF EXISTS `partner_type_insert`;
DELIMITER $$
CREATE TRIGGER `partner_type_insert` BEFORE INSERT ON `partner_type_option` FOR EACH ROW BEGIN
  SET NEW.`uuid` = IFNULL(NEW.`uuid`,UUID());
END$$
DELIMITER ;


SET character_set_client = utf8;
CREATE TABLE `employee_base_option` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uuid` CHAR(36) NULL DEFAULT NULL,
  `base_phrase` varchar(128) NOT NULL,
  `modified_by` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  `timestamp_updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `timestamp_created` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name_unique` (`base_phrase`),
  KEY `modified_by` (`modified_by`),
  KEY `created_by` (`created_by`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TRIGGER IF EXISTS `employee_base_insert`;
DELIMITER $$
CREATE TRIGGER `employee_base_insert` BEFORE INSERT ON `employee_base_option` FOR EACH ROW BEGIN
  SET NEW.`uuid` = IFNULL(NEW.`uuid`,UUID());
END$$
DELIMITER ;

#employee changes requested - 5/31
ALTER TABLE `employee`
    ADD COLUMN `transition_complete_date` DATETIME NULL DEFAULT NULL AFTER `employee_transition_complete_option_id`;
ALTER TABLE `_system`
    ADD COLUMN `display_facility_comments`  tinyint(1) NOT NULL DEFAULT '1',
    ADD COLUMN `display_employee_actual_transition_date`  tinyint(1) NOT NULL DEFAULT '1',
    ADD COLUMN `display_employee_site_type`  tinyint(1) NOT NULL DEFAULT '1';



SET character_set_client = utf8;
CREATE TABLE `employee_site_type_option` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uuid` CHAR(36) NULL DEFAULT NULL,
  `site_type_phrase` varchar(128) NOT NULL,
  `modified_by` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  `timestamp_updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `timestamp_created` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `modified_by` (`modified_by`),
  KEY `created_by` (`created_by`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
DROP TRIGGER IF EXISTS `employee_site_type_insert`;
DELIMITER $$
CREATE TRIGGER `employee_site_type_insert` BEFORE INSERT ON `employee_site_type_option` FOR EACH ROW BEGIN
  SET NEW.`uuid` = IFNULL(NEW.`uuid`,UUID());
END$$
DELIMITER ;


ALTER TABLE  `employee` ADD  `transition_other` VARCHAR( 64 ) NULL DEFAULT NULL AFTER  `employee_transition_option_id`;
ALTER TABLE  `employee` ADD  `transition_complete_other` VARCHAR( 64 ) NULL DEFAULT NULL AFTER  `employee_transition_complete_option_id`;

#6/14 - preservice changes
ALTER TABLE  `person` ADD  `phone_mobile_2` VARCHAR( 32 ) NULL DEFAULT NULL AFTER  `phone_mobile`;
ALTER TABLE  `tutor` ADD  `is_keypersonal` TINYINT( 1 ) NOT NULL DEFAULT  '0' AFTER  `personid`;
ALTER TABLE  `tutor` ADD INDEX  `is_keypersonalidx` (  `is_keypersonal` );

#7/22 - employee wants an employee number for each partner
ALTER TABLE  `employee` ADD  `partner_employee_number` INT( 11 ) NULL DEFAULT NULL AFTER `partner_id`;
ALTER TABLE  `employee` ADD  `external_funding_percent` INT( 3 ) NULL DEFAULT NULL AFTER `annual_cost`;
ALTER TABLE `_system` ADD COLUMN `display_employee_external_funding` tinyint(1) NOT NULL DEFAULT 0 AFTER `display_employee_annual_cost`;
INSERT INTO  `translation` VALUES (NULL , NULL ,  'Employee Local Currency', 'Employee Local Currency', NULL , NULL ,  '0', CURRENT_TIMESTAMP ,  '2012-10-22 00:00:00');


/*
2014-01-05 Sean Smith 
For Request:
Add a check box for residential vs. non-residential on a students record.  Should fall after local address and before next of kin address
*/
ALTER TABLE `person`
ADD COLUMN `home_is_residential`  tinyint(1) NULL AFTER `home_postal_code`;

/*
2014-01-08 Sean Smith 
For Request:
Adding a pass/fail  credits columns in the class history table for a student.  
Both columns would behave the same way the grade column works, click on it and enter what you want.
*/

/* This was added to undo change to test.trainingdata.org db */
ALTER TABLE link_student_classes CHANGE pass_credits credits VARCHAR(50);
ALTER TABLE link_student_classes DROP COLUMN fail_credits;

ALTER TABLE `link_student_classes`
ADD COLUMN `credits`  varchar(50) NULL AFTER `grade`;

ALTER TABLE `link_student_practicums`
ADD COLUMN `credits`  varchar(50) NULL AFTER `grade`;

ALTER TABLE `link_student_licenses`
ADD COLUMN `credits`  varchar(50) NULL AFTER `grade`;


/*
2014-02-15 Greg Rossum
*/

alter table _system add column display_training_completion tinyint(1) NOT NULL DEFAULT '0';


/*
2014-02-14 Greg Rossum
*/
alter table evaluation_response add person_id int null after evaluation_to_training_id;
alter table evaluation_response change column trainer_person_id trainer_person_id int null;


