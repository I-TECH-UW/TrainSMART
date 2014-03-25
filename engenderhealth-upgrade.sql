
/***********************************************************************************/

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

/********************************/
/* engenderhealth - new updates */
/********************************/

/* facility latitude and longitude */
ALTER TABLE  `facility` ADD  `lat` DECIMAL( 9, 6 ) NULL DEFAULT NULL AFTER  `facility_name` , ADD  `long` DECIMAL( 9, 6 ) NULL DEFAULT NULL AFTER  `lat`;
/* sys settings */
/* custom fields */
/*ALTER TABLE `_system` DROP `display_training_partner`; */
/*ALTER TABLE `_system` DROP 	ADD  `display_training_partner` TINYINT( 1 ) NOT NULL DEFAULT  '0', */

ALTER TABLE  `_system`
  ADD  `require_duplicate_acl` TINYINT( 1 ) NOT NULL DEFAULT  '0',
  ADD  `module_attendance_enabled` TINYINT( 1 ) NOT NULL DEFAULT '0',
  ADD  `display_sponsor_dates`     TINYINT( 1 ) NOT NULL DEFAULT '0',
	ADD  `require_sponsor_dates`     TINYINT( 1 ) NOT NULL DEFAULT '0',
  ADD  `allow_multi_sponsors`      TINYINT( 1 ) NOT NULL DEFAULT '0',
  ADD  `allow_multi_approvers`     TINYINT( 1 ) NOT NULL DEFAULT '0',
	ADD  `fiscal_year_start`        DATETIME NULL DEFAULT NULL ,
	ADD  `display_gender`           TINYINT( 1 ) NOT NULL DEFAULT  '1',
	ADD  `display_training_custom3` TINYINT( 1 ) NOT NULL DEFAULT  '0',
	ADD  `display_training_custom4` TINYINT( 1 ) NOT NULL DEFAULT  '0',
	ADD  `display_people_custom3`   TINYINT( 1 ) NOT NULL DEFAULT  '0',
	ADD  `display_people_custom4`   TINYINT( 1 ) NOT NULL DEFAULT  '0',
	ADD  `display_people_custom5`   TINYINT( 1 ) NOT NULL DEFAULT  '0',
	ADD  `display_training_pepfar`  TINYINT( 1 ) NOT NULL DEFAULT  '1',
	ADD  `require_trainer_skill`    TINYINT( 1 ) NOT NULL DEFAULT  '0';
ALTER TABLE  `_system`
	ADD `display_region_d` TINYINT(1) NOT NULL DEFAULT '0',
	ADD `display_region_e` TINYINT(1) NOT NULL DEFAULT '0',
	ADD `display_region_f` TINYINT(1) NOT NULL DEFAULT '0',
	ADD `display_region_g` TINYINT(1) NOT NULL DEFAULT '0',
	ADD `display_region_h` TINYINT(1) NOT NULL DEFAULT '0',
	ADD `display_region_i` TINYINT(1) NOT NULL DEFAULT '0';

/* some labels */
INSERT INTO  `translation` VALUES (NULL , NULL ,  'training',  'Training', NULL , NULL ,  '0', CURRENT_TIMESTAMP ,  '2012-10-22 00:00:00');
INSERT INTO  `translation` VALUES (NULL , NULL ,  'trainer',  'Trainer', NULL , NULL ,  '0', CURRENT_TIMESTAMP ,  '2012-10-22 00:00:00');
INSERT INTO  `translation` VALUES (NULL , NULL ,  'Training',  'Training', NULL , NULL ,  '0', CURRENT_TIMESTAMP ,  '2012-10-22 00:00:00');
INSERT INTO  `translation` VALUES (NULL , NULL ,  'Trainer',  'Trainer', NULL , NULL ,  '0', CURRENT_TIMESTAMP ,  '2012-10-22 00:00:00');
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
ALTER TABLE  `user_to_acl` CHANGE  `acl_id`  `acl_id` ENUM ('in_service', 'pre_service', 'edit_course', 'view_course', 'duplicate_training', 'approve_trainings', 'master_approver', 'edit_people', 'view_people', 'edit_facility', 'view_facility', 'view_create_reports', 'training_organizer_option_all', 'training_title_option_all', 'use_offline_app', 'admin_files', 'facility_and_person_approver', 'edit_evaluations', 'edit_country_options', 'acl_editor_training_category', 'acl_editor_people_qualifications', 'acl_editor_people_responsibility', 'acl_editor_training_organizer', 'acl_editor_people_trainer', 'acl_editor_training_topic', 'acl_editor_people_titles', 'acl_editor_training_level', 'acl_editor_refresher_course', 'acl_editor_people_trainer_skills', 'acl_editor_pepfar_category', 'acl_editor_people_languages', 'acl_editor_funding', 'acl_editor_people_affiliations', 'acl_editor_recommended_topic', 'acl_editor_nationalcurriculum', 'acl_editor_people_suffix', 'acl_editor_method', 'acl_editor_people_active_trainer', 'acl_editor_facility_types', 'acl_editor_ps_classes', 'acl_editor_facility_sponsors', 'acl_editor_ps_cadres', 'acl_editor_ps_degrees', 'acl_editor_ps_funding', 'acl_editor_ps_institutions', 'acl_editor_ps_languages', 'acl_editor_ps_nationalities', 'acl_editor_ps_joindropreasons', 'acl_editor_ps_sponsors', 'acl_editor_ps_tutortypes', 'acl_editor_ps_coursetypes', 'acl_editor_ps_religions', 'add_edit_users', 'acl_admin_training', 'acl_admin_people', 'acl_admin_facilities', 'import_training', 'import_training_location', 'import_facility', 'import_person' )  NOT NULL DEFAULT 'view_course';
INSERT INTO `acl` VALUES ('duplicate_training', 'duplicate_training');
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

/* evaluations */
ALTER TABLE  `evaluation_question` CHANGE  `question_type`  `question_type` ENUM(  'Likert',  'Text',  'Likert3',  'Likert3NA', 'LikertNA' ) NOT NULL DEFAULT  'Likert';
ALTER TABLE  `evaluation_response` ADD     `trainer_person_id` INT( 11 ) NULL DEFAULT NULL AFTER  `evaluation_to_training_id`;

/* score - improved indexing */
ALTER TABLE  `dev_test`.`score` ADD INDEX  `scorelabel` (  `score_label` )

/* facility sponsrs */
ALTER TABLE `facility`   ADD `sponsorstartdate` TINYINT(1) NULL DEFAULT NULL;
ALTER TABLE `facility`   ADD `approved` TINYINT(1) NULL DEFAULT NULL;

/* custom fields 'training' */
ALTER TABLE  `training`
  ADD  `custom_3` VARCHAR( 255 ) NULL DEFAULT  '',
  ADD  `custom_4` VARCHAR( 255 ) NULL DEFAULT  '';
INSERT INTO  `translation` VALUES (NULL , NULL ,  'Training Custom 3',  'Custom field 3', NULL , NULL ,  '0',  CURRENT_TIMESTAMP ,  '2012-10-22 00:00:00');
INSERT INTO  `translation` VALUES (NULL , NULL ,  'Training Custom 4',  'Custom field 4', NULL , NULL ,  '0',  CURRENT_TIMESTAMP ,  '2012-10-22 00:00:00');
