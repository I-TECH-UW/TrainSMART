/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET FOREIGN_KEY_CHECKS=0 */;

-- Dumping structure for table skillsmart.comp
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


-- Dumping structure for table skillsmart.compres
CREATE TABLE IF NOT EXISTS `compres` (
  `SNo` bigint(20) NOT NULL AUTO_INCREMENT,
  `person` int(11) NOT NULL,
  `res` int(11) NOT NULL,
  `active` char(1) NOT NULL DEFAULT 'N',
  PRIMARY KEY (`SNo`),
  KEY `person` (`person`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Data exporting was unselected.


-- Dumping structure for table skillsmart.facs
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


-- Dumping structure for table skillsmart.jobtitles
CREATE TABLE IF NOT EXISTS `jobtitles` (
  `Title` varchar(45) DEFAULT NULL,
  `Option` varchar(12) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


-- Dumping structure for table skillsmart.person_responsibility_option
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


-- Dumping structure for table skillsmart.sheet1
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


-- Dumping structure for table skillsmart.tracking
CREATE TABLE IF NOT EXISTS `tracking` (
  `UID` int(11) NOT NULL,
  `URL` text NOT NULL,
  `On` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  KEY `IDX` (`UID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Data exporting was unselected.


-- Dumping structure for table skillsmart.trans
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


-- Dumping structure for table skillsmart.user_to_acl_province
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


ALTER TABLE `training`
	ADD COLUMN `course_id` INT(11) NULL DEFAULT NULL AFTER `timestamp_created`;

ALTER TABLE `_system`
	ADD COLUMN `display_mod_skillsmart` TINYINT(1) NOT NULL DEFAULT '0' AFTER `display_training_partner`;

ALTER TABLE `person`
	ALTER `comments` DROP DEFAULT;

ALTER TABLE `person`
	CHANGE COLUMN `comments` `comments` TEXT NULL AFTER `secondary_responsibility_option_id`;

ALTER TABLE `person`
	ADD COLUMN `home_city` VARCHAR(255) NOT NULL DEFAULT '' AFTER `home_address_2`;

ALTER TABLE `user_to_acl`
	CHANGE COLUMN `acl_id` `acl_id` ENUM('edit_course','view_course','edit_people','view_people','view_create_reports','edit_country_options','add_edit_users','training_organizer_option_all','training_title_option_all','approve_trainings','admin_files','use_offline_app','pre_service','in_service','edit_institution','view_institution') NOT NULL DEFAULT 'view_course' AFTER `id`;

# ADDING IN-SERVICE OPTION TO MAIN ACL OPTIONS TABLE
INSERT INTO `acl` (`id`, `acl`) VALUES ('in_service', 'in_service');


# ADDING MULTI-ENTRY PIVOT TABLE ADDING DEGREES TO INSTITUTIONS
CREATE TABLE `link_institution_degrees` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_institution` int(10) unsigned NOT NULL DEFAULT '0',
  `id_degree` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx2` (`id_institution`,`id_degree`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1


ALTER TABLE `_system`
	ADD COLUMN `display_mod_skillsmart` TINYINT(1) NOT NULL DEFAULT '1',
	ADD COLUMN `display_occupational_category` TINYINT(1) NOT NULL DEFAULT '1',
	ADD COLUMN `display_government_employee` TINYINT(1) NOT NULL DEFAULT '1',
	ADD COLUMN `display_professional_bodies` TINYINT(1) NOT NULL DEFAULT '1',
	ADD COLUMN `display_race` TINYINT(1) NOT NULL DEFAULT '1',
	ADD COLUMN `display_disability` TINYINT(1) NOT NULL DEFAULT '1',
	ADD COLUMN `display_nurse_trainer_type` TINYINT(1) NOT NULL DEFAULT '1',
	ADD COLUMN `display_provider_start` TINYINT(1) NOT NULL DEFAULT '1',
	ADD COLUMN `display_rank_groups` TINYINT(1) NOT NULL DEFAULT '1',
	ADD COLUMN `display_supervised` TINYINT(1) NOT NULL DEFAULT '1',
	ADD COLUMN `display_training_received` TINYINT(1) NOT NULL DEFAULT '1',
	ADD COLUMN `display_facility_department` TINYINT(1) NOT NULL DEFAULT '1';

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


-- Dumping structure for table efelleos_dev.link_user_institution
CREATE TABLE IF NOT EXISTS `link_user_institution` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  `institutionid` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx2` (`userid`,`institutionid`)
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
/*!40014 SET FOREIGN_KEY_CHECKS=1 */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;


ALTER TABLE `person` ADD COLUMN `multi_facility_ids` VARCHAR(255) NOT NULL AFTER `facility_id`;