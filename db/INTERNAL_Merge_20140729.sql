/*
2014-07-29 Tamara Astakhova
For Request: Haiti PreService assignment
*/

ALTER TABLE `person` ADD COLUMN `custom_field1` varchar(255) NULL;
ALTER TABLE `person` ADD COLUMN `custom_field2` varchar(255) NULL;
ALTER TABLE `person` ADD COLUMN `custom_field3` varchar(255) NULL;
ALTER TABLE `person` ADD COLUMN `marital_status` varchar(20) NULL;
ALTER TABLE `person` ADD COLUMN `spouse_name` varchar(50) NULL;

ALTER TABLE `student` ADD COLUMN `hscomldate` DATE NOT NULL;
ALTER TABLE `student` ADD COLUMN `lastinstatt` varchar(50) NOT NULL;
ALTER TABLE `student` ADD COLUMN `schoolstartdate` DATE NOT NULL;
ALTER TABLE `student` ADD COLUMN `equivalence` tinyint(4) NOT NULL DEFAULT '0';
ALTER TABLE `student` ADD COLUMN `lastunivatt` varchar(50) NOT NULL;
ALTER TABLE `student` ADD COLUMN `personincharge` varchar(50) NOT NULL;
ALTER TABLE `student` ADD COLUMN `emergcontact`varchar(50) NOT NULL;


ALTER TABLE `tutor` ADD COLUMN `specialty` varchar(100) NOT NULL;
ALTER TABLE `tutor` ADD COLUMN `contract_type` varchar(100) NOT NULL;


ALTER TABLE `_system` ADD COLUMN `ps_display_inst_compl_date` tinyint(1) NOT NULL DEFAULT '0';
ALTER TABLE `_system` ADD COLUMN `ps_display_last_inst_attended` tinyint(1) NOT NULL DEFAULT '0';
ALTER TABLE `_system` ADD COLUMN `ps_display_start_school_date` tinyint(1) NOT NULL DEFAULT '0';
ALTER TABLE `_system` ADD COLUMN `ps_display_equivalence` tinyint(1) NOT NULL DEFAULT '0';
ALTER TABLE `_system` ADD COLUMN `ps_display_last_univ_attended` tinyint(1) NOT NULL DEFAULT '0';
ALTER TABLE `_system` ADD COLUMN `ps_display_person_charge` tinyint(1) NOT NULL DEFAULT '0';
ALTER TABLE `_system` ADD COLUMN `ps_display_custom_field1` tinyint(1) NOT NULL DEFAULT '0';
ALTER TABLE `_system` ADD COLUMN `ps_display_custom_field2` tinyint(1) NOT NULL DEFAULT '0';
ALTER TABLE `_system` ADD COLUMN `ps_display_custom_field3` tinyint(1) NOT NULL DEFAULT '0';
ALTER TABLE `_system` ADD COLUMN `ps_display_marital_status` tinyint(1) NOT NULL DEFAULT '0';
ALTER TABLE `_system` ADD COLUMN `ps_display_spouse_name` tinyint(1) NOT NULL DEFAULT '0';
ALTER TABLE `_system` ADD COLUMN `ps_display_specialty` tinyint(1) NOT NULL DEFAULT '0';
ALTER TABLE `_system` ADD COLUMN `ps_display_contract_type` tinyint(1) NOT NULL DEFAULT '0';
ALTER TABLE `_system` ADD COLUMN `ps_display_local_address` tinyint(1) NOT NULL DEFAULT '0';
ALTER TABLE `_system` ADD COLUMN `ps_display_permanent_address` tinyint(1) NOT NULL DEFAULT '0';
ALTER TABLE `_system` ADD COLUMN `ps_display_religious_denomin` tinyint(1) NOT NULL DEFAULT '0';
ALTER TABLE `_system` ADD COLUMN `ps_display_nationality` tinyint(1) NOT NULL DEFAULT '0';

/* just leave in for case if this records in database */
UPDATE translation SET is_deleted = 0 WHERE key_phrase = 'ps clinical allocation';
UPDATE translation SET is_deleted = 0 WHERE key_phrase = 'ps local address';

INSERT INTO `translation`(`key_phrase`,`phrase`,`modified_by`,`created_by`,`is_deleted`,`timestamp_updated`,`timestamp_created`) VALUES ('ps clinical allocation','Clinical Allocation',1,null,0,'2014-07-25 13:40:02','2014-07-18 00:00:00');
INSERT INTO `translation`(`key_phrase`,`phrase`,`modified_by`,`created_by`,`is_deleted`,`timestamp_updated`,`timestamp_created`) VALUES ('ps local address','Local Address',1,null,0,'2014-07-25 13:40:02','2014-07-18 00:00:00');
INSERT INTO `translation`(`key_phrase`,`phrase`,`modified_by`,`created_by`,`is_deleted`,`timestamp_updated`,`timestamp_created`) VALUES ('ps last school attended','Last School Attended',1,null,0,'2014-07-25 13:40:02','2014-07-21 00:00:00');
INSERT INTO `translation`(`key_phrase`,`phrase`,`modified_by`,`created_by`,`is_deleted`,`timestamp_updated`,`timestamp_created`) VALUES ('ps high school completion date','High School Completion Date',1,null,0,'2014-07-25 13:40:02','2014-07-18 00:00:00');
INSERT INTO `translation`(`key_phrase`,`phrase`,`modified_by`,`created_by`,`is_deleted`,`timestamp_updated`,`timestamp_created`) VALUES ('ps last school attended','Last School Attended',1,null,0,'2014-07-25 13:40:02','2014-07-21 00:00:00');
INSERT INTO `translation`(`key_phrase`,`phrase`,`modified_by`,`created_by`,`is_deleted`,`timestamp_updated`,`timestamp_created`) VALUES ('ps school start date','Admission to School Date',1,null,0,'2014-07-25 13:40:02','2014-07-21 00:00:00');
INSERT INTO `translation`(`key_phrase`,`phrase`,`modified_by`,`created_by`,`is_deleted`,`timestamp_updated`,`timestamp_created`) VALUES ('ps equivalence','Equivalence',1,null,0,'2014-07-25 13:40:02','2014-07-21 00:00:00');
INSERT INTO `translation`(`key_phrase`,`phrase`,`modified_by`,`created_by`,`is_deleted`,`timestamp_updated`,`timestamp_created`) VALUES ('ps last university attended','Last University Attended',1,null,0,'2014-07-25 13:40:02','2014-07-21 00:00:00');
INSERT INTO `translation`(`key_phrase`,`phrase`,`modified_by`,`created_by`,`is_deleted`,`timestamp_updated`,`timestamp_created`) VALUES ('ps person in charge','Person In Charge',1,null,0,'2014-07-25 13:40:02','2014-07-21 00:00:00');
INSERT INTO `translation`(`key_phrase`,`phrase`,`modified_by`,`created_by`,`is_deleted`,`timestamp_updated`,`timestamp_created`) VALUES ('ps license and registration','License And Registration',1,null,0,'2014-07-25 13:40:02','2014-07-21 00:00:00');
INSERT INTO `translation`(`key_phrase`,`phrase`,`modified_by`,`created_by`,`is_deleted`,`timestamp_updated`,`timestamp_created`) VALUES ('ps permanent address','Permanent Address',1,null,0,'2014-07-25 13:40:02','2014-07-21 00:00:00');
INSERT INTO `translation`(`key_phrase`,`phrase`,`modified_by`,`created_by`,`is_deleted`,`timestamp_updated`,`timestamp_created`) VALUES ('ps religious denomination','Religious Denomination',1,null,0,'2014-07-25 13:40:02','2014-07-21 00:00:00');
INSERT INTO `translation`(`key_phrase`,`phrase`,`modified_by`,`created_by`,`is_deleted`,`timestamp_updated`,`timestamp_created`) VALUES ('ps program enrolled in','Program Enrolled In',1,null,0,'2014-07-25 13:40:02','2014-07-21 00:00:00');
INSERT INTO `translation`(`key_phrase`,`phrase`,`modified_by`,`created_by`,`is_deleted`,`timestamp_updated`,`timestamp_created`) VALUES ('ps nationality','Nationality',1,null,0,'2014-07-25 13:40:02','2014-07-21 00:00:00');
INSERT INTO `translation`(`key_phrase`,`phrase`,`modified_by`,`created_by`,`is_deleted`,`timestamp_updated`,`timestamp_created`) VALUES ('ps custom field 1','Custom Field 1',1,null,0,'2014-07-25 13:40:02','2014-07-22 00:00:00');
INSERT INTO `translation`(`key_phrase`,`phrase`,`modified_by`,`created_by`,`is_deleted`,`timestamp_updated`,`timestamp_created`) VALUES ('ps custom field 2','Custom Field 2',1,null,0,'2014-07-25 13:40:02','2014-07-22 00:00:00');
INSERT INTO `translation`(`key_phrase`,`phrase`,`modified_by`,`created_by`,`is_deleted`,`timestamp_updated`,`timestamp_created`) VALUES ('ps custom field 3','Custom Field 3',1,null,0,'2014-07-25 13:40:02','2014-07-22 00:00:00');
INSERT INTO `translation`(`key_phrase`,`phrase`,`modified_by`,`created_by`,`is_deleted`,`timestamp_updated`,`timestamp_created`) VALUES ('ps marital status','Marital Status',1,null,0,'2014-07-25 13:40:02','2014-07-22 00:00:00');
INSERT INTO `translation`(`key_phrase`,`phrase`,`modified_by`,`created_by`,`is_deleted`,`timestamp_updated`,`timestamp_created`) VALUES ('ps spouse name','Spouse Name',1,null,0,'2014-07-25 13:40:02','2014-07-22 00:00:00');
INSERT INTO `translation`(`key_phrase`,`phrase`,`modified_by`,`created_by`,`is_deleted`,`timestamp_updated`,`timestamp_created`) VALUES ('ps specialty','Specialty',1,null,0,'2014-07-25 13:40:02','2014-07-22 00:00:00');
INSERT INTO `translation`(`key_phrase`,`phrase`,`modified_by`,`created_by`,`is_deleted`,`timestamp_updated`,`timestamp_created`) VALUES ('ps contract type','Type Of Contract',1,null,0,'2014-07-25 13:40:02','2014-07-22 00:00:00');

CREATE TABLE `tutor_contract_option` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `created_by` int(11) DEFAULT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  `modified_by` int(11) DEFAULT NULL,
  `uuid` varchar(36) DEFAULT NULL,
  `contract_phrase` varchar(128) NOT NULL,
  `timestamp_updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `timestamp_created` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

CREATE TABLE `tutor_specialty_option` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `created_by` int(11) DEFAULT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  `modified_by` int(11) DEFAULT NULL,
  `uuid` varchar(36) DEFAULT NULL,
  `specialty_phrase` varchar(128) NOT NULL,
  `timestamp_updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `timestamp_created` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;



