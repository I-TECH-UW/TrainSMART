
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
  `display_secondary_responsibility` tinyint(1) NOT NULL default '0',
  `display_training_partner` tinyint(1) default NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


/* ======================================================== */
/* ======================================================== */
/* Money Rich Grillz.com bug fixes and tanzania stuff                             */
/* ======================================================== */
/* ======================================================== */

# and the table for new training partner module
DROP TABLE IF EXISTS `facility_partners`;
DROP TABLE IF EXISTS `organizer_partners`;
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
ALTER TABLE `_system` ADD COLUMN `display_training_partner` tinyint(1);
UPDATE  `_system` SET `display_training_partner` = 0 WHERE 1;
