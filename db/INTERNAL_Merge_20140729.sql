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

UPDATE translation SET is_deleted = 0 WHERE key_phrase = 'ps clinical allocation';
UPDATE translation SET is_deleted = 0 WHERE key_phrase = 'ps local address';

INSERT INTO `translation`(`id`,`uuid`,`key_phrase`,`phrase`,`modified_by`,`created_by`,`is_deleted`,`timestamp_updated`,`timestamp_created`) VALUES (147,'49bce7cd-0e9e-11e4-a9c4-90b11c6d81a0','ps high school completion date','High School Completion Date',1,null,0,'2014-07-25 13:40:02','2014-07-18 00:00:00');
INSERT INTO `translation`(`id`,`uuid`,`key_phrase`,`phrase`,`modified_by`,`created_by`,`is_deleted`,`timestamp_updated`,`timestamp_created`) VALUES (148,'a1104d8a-1105-11e4-a9c4-90b11c6d81a0','ps last school attended','Last School Attended',1,null,0,'2014-07-25 13:40:02','2014-07-21 00:00:00');
INSERT INTO `translation`(`id`,`uuid`,`key_phrase`,`phrase`,`modified_by`,`created_by`,`is_deleted`,`timestamp_updated`,`timestamp_created`) VALUES (149,'d888fd3f-1105-11e4-a9c4-90b11c6d81a0','ps school start date','Admission to School Date',1,null,0,'2014-07-25 13:40:02','2014-07-21 00:00:00');
INSERT INTO `translation`(`id`,`uuid`,`key_phrase`,`phrase`,`modified_by`,`created_by`,`is_deleted`,`timestamp_updated`,`timestamp_created`) VALUES (150,'f863c26e-1105-11e4-a9c4-90b11c6d81a0','ps equivalence','Equivalence',1,null,0,'2014-07-25 13:40:02','2014-07-21 00:00:00');
INSERT INTO `translation`(`id`,`uuid`,`key_phrase`,`phrase`,`modified_by`,`created_by`,`is_deleted`,`timestamp_updated`,`timestamp_created`) VALUES (151,'5db932ee-1106-11e4-a9c4-90b11c6d81a0','ps last university attended','Last University Attended',1,null,0,'2014-07-25 13:40:02','2014-07-21 00:00:00');
INSERT INTO `translation`(`id`,`uuid`,`key_phrase`,`phrase`,`modified_by`,`created_by`,`is_deleted`,`timestamp_updated`,`timestamp_created`) VALUES (152,'5e7ef46f-1106-11e4-a9c4-90b11c6d81a0','ps person in charge','Person In Charge',1,null,0,'2014-07-25 13:40:02','2014-07-21 00:00:00');
INSERT INTO `translation`(`id`,`uuid`,`key_phrase`,`phrase`,`modified_by`,`created_by`,`is_deleted`,`timestamp_updated`,`timestamp_created`) VALUES (153,'edf8a046-1119-11e4-919a-90b11c6d81a0','ps license and registration','License And Registration',1,null,0,'2014-07-25 13:40:02','2014-07-21 00:00:00');
INSERT INTO `translation`(`id`,`uuid`,`key_phrase`,`phrase`,`modified_by`,`created_by`,`is_deleted`,`timestamp_updated`,`timestamp_created`) VALUES (154,'f0d9acf8-1119-11e4-919a-90b11c6d81a0','ps permanent address','Permanent Address',1,null,0,'2014-07-25 13:40:02','2014-07-21 00:00:00');
INSERT INTO `translation`(`id`,`uuid`,`key_phrase`,`phrase`,`modified_by`,`created_by`,`is_deleted`,`timestamp_updated`,`timestamp_created`) VALUES (155,'1a99f05f-111a-11e4-919a-90b11c6d81a0','ps religious denomination','Religious Denomination',1,null,0,'2014-07-25 13:40:02','2014-07-21 00:00:00');
INSERT INTO `translation`(`id`,`uuid`,`key_phrase`,`phrase`,`modified_by`,`created_by`,`is_deleted`,`timestamp_updated`,`timestamp_created`) VALUES (156,'1ba0fab2-111a-11e4-919a-90b11c6d81a0','ps program enrolled in','Program Enrolled In',1,null,0,'2014-07-25 13:40:02','2014-07-21 00:00:00');
INSERT INTO `translation`(`id`,`uuid`,`key_phrase`,`phrase`,`modified_by`,`created_by`,`is_deleted`,`timestamp_updated`,`timestamp_created`) VALUES (158,'4a7bf303-111a-11e4-919a-90b11c6d81a0','ps nationality','Nationality',1,null,0,'2014-07-25 13:40:02','2014-07-21 00:00:00');
INSERT INTO `translation`(`id`,`uuid`,`key_phrase`,`phrase`,`modified_by`,`created_by`,`is_deleted`,`timestamp_updated`,`timestamp_created`) VALUES (159,'c7e13540-11b2-11e4-919a-90b11c6d81a0','ps custom field 1','Custom Field 1',1,null,0,'2014-07-25 13:40:02','2014-07-22 00:00:00');
INSERT INTO `translation`(`id`,`uuid`,`key_phrase`,`phrase`,`modified_by`,`created_by`,`is_deleted`,`timestamp_updated`,`timestamp_created`) VALUES (160,'ca548760-11b2-11e4-919a-90b11c6d81a0','ps custom field 2','Custom Field 2',1,null,0,'2014-07-25 13:40:02','2014-07-22 00:00:00');
INSERT INTO `translation`(`id`,`uuid`,`key_phrase`,`phrase`,`modified_by`,`created_by`,`is_deleted`,`timestamp_updated`,`timestamp_created`) VALUES (161,'2ef59b5d-11b3-11e4-919a-90b11c6d81a0','ps custom field 3','Custom Field 3',1,null,0,'2014-07-25 13:40:02','2014-07-22 00:00:00');
INSERT INTO `translation`(`id`,`uuid`,`key_phrase`,`phrase`,`modified_by`,`created_by`,`is_deleted`,`timestamp_updated`,`timestamp_created`) VALUES (162,'2fcbd498-11b3-11e4-919a-90b11c6d81a0','ps marital status','Marital Status',1,null,0,'2014-07-25 13:40:02','2014-07-22 00:00:00');
INSERT INTO `translation`(`id`,`uuid`,`key_phrase`,`phrase`,`modified_by`,`created_by`,`is_deleted`,`timestamp_updated`,`timestamp_created`) VALUES (163,'b0bae1cf-11b3-11e4-919a-90b11c6d81a0','ps spouse name','Spouse Name',1,null,0,'2014-07-25 13:40:02','2014-07-22 00:00:00');
INSERT INTO `translation`(`id`,`uuid`,`key_phrase`,`phrase`,`modified_by`,`created_by`,`is_deleted`,`timestamp_updated`,`timestamp_created`) VALUES (164,'09653e93-11ca-11e4-919a-90b11c6d81a0','ps specialty','Specialty',1,null,0,'2014-07-25 13:40:02','2014-07-22 00:00:00');
INSERT INTO `translation`(`id`,`uuid`,`key_phrase`,`phrase`,`modified_by`,`created_by`,`is_deleted`,`timestamp_updated`,`timestamp_created`) VALUES (165,'0a97a688-11ca-11e4-919a-90b11c6d81a0','ps contract type','Type Of Contract',1,null,0,'2014-07-25 13:40:02','2014-07-22 00:00:00');

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



