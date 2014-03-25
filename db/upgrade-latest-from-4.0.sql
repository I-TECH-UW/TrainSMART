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
ALTER TABLE `_system` ADD COLUMN `display_training_partner` tinyint(1) NOT NULL DEFAULT 0;

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
DROP TABLE IF EXISTS `partner`;
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


DROP TABLE IF EXISTS `partner_to_subpartner`;
CREATE TABLE `partner_to_subpartner` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `partner_id` int(11) NOT NULL,
  `subpartner_id` int(11) NOT NULL,
  `created_by` int(11) DEFAULT NULL,
  `timestamp_created` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY (`partner_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `employee`;
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

DROP TABLE IF EXISTS `employee_to_relationship`;
CREATE TABLE `employee_to_relationship` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `employee_id` int(11) NOT NULL,
  `relationship_option_id` int(11) NOT NULL,
  `created_by` int(11) DEFAULT NULL,
  `timestamp_created` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `employee_to_referral`;
CREATE TABLE `employee_to_referral` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `employee_id` int(11) NOT NULL,
  `referral_option_id` int(11) NOT NULL,
  `created_by` int(11) DEFAULT NULL,
  `timestamp_created` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `employee_to_role`;
CREATE TABLE `employee_to_role` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `employee_id` int(11) NOT NULL,
  `employee_role_option_id` int(11) NOT NULL,
  `created_by` int(11) DEFAULT NULL,
  `timestamp_created` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `employee_to_course`;
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
DROP TABLE IF EXISTS `employee_category_option`;
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

DROP TABLE IF EXISTS `employee_role_option`;
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

DROP TABLE IF EXISTS `employee_transition_option`;
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

DROP TABLE IF EXISTS `employee_relationship_option`;
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

DROP TABLE IF EXISTS `employee_referral_option`;
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

DROP TABLE IF EXISTS `employee_training_provided_option`;
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

DROP TABLE IF EXISTS `partner_importance_option`;
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

DROP TABLE IF EXISTS `partner_funder_option`;
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

DROP TABLE IF EXISTS `partner_to_funder`;
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

DROP TABLE IF EXISTS `datashare_sites`;
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
DROP TABLE IF EXISTS `facility_sponsors`;
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

#migrate data: facility sponsor column to facility_sponsors table
INSERT INTO  `facility_sponsors` SELECT NULL , NULL ,  id, sponsor_option_id, '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0', 'NULL', '1', '0', '0000-00-00 00:00:00', NOW() from `facility` where `sponsor_option_id` is not null and `sponsor_option_id` != 0;


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
INSERT INTO  `translation` VALUES (NULL , NULL ,  'Disability', 'Disability', NULL, NULL, '0', CURRENT_TIMESTAMP, '2012-10-22 00:00:00');
INSERT INTO  `translation` VALUES (NULL , NULL ,  'Disability Comments', 'Disability Comments', NULL, NULL, '0', CURRENT_TIMESTAMP, '2012-10-22 00:00:00');
INSERT INTO  `translation` VALUES (NULL , NULL ,  'Race', 'Race', NULL, NULL, '0', CURRENT_TIMESTAMP, '2012-10-22 00:00:00');
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

DROP TABLE IF EXISTS `evaluation_custom_answers`;
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


DROP TABLE IF EXISTS `partner_type_option`;
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

DROP TABLE IF EXISTS `employee_base_option`;
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


DROP TABLE IF EXISTS `employee_site_type_option`;
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
