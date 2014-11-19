/*
2014-10-01 
Tamara Astakhova
For Request: CHAI project
*/

ALTER TABLE `_system` ADD COLUMN `display_training_category` tinyint(1) NOT NULL DEFAULT '0';
ALTER TABLE `_system` ADD COLUMN `display_training_start_date` tinyint(1) NOT NULL DEFAULT '0';
ALTER TABLE `_system` ADD COLUMN `display_training_length` tinyint(1) NOT NULL DEFAULT '0';
ALTER TABLE `_system` ADD COLUMN `display_training_level` tinyint(1) NOT NULL DEFAULT '0';
ALTER TABLE `_system` ADD COLUMN `display_training_comments` tinyint(1) NOT NULL DEFAULT '0';
ALTER TABLE `_system` ADD COLUMN `display_facilitator_info` tinyint(1) NOT NULL DEFAULT '0';
ALTER TABLE `_system` ADD COLUMN `display_training_score` tinyint(1) NOT NULL DEFAULT '0';
ALTER TABLE `_system` ADD COLUMN `display_facility_address` tinyint(1) NOT NULL DEFAULT '0';
ALTER TABLE `_system` ADD COLUMN `display_facility_phone` tinyint(1) NOT NULL DEFAULT '0';
ALTER TABLE `_system` ADD COLUMN `display_facility_fax` tinyint(1) NOT NULL DEFAULT '0';
ALTER TABLE `_system` ADD COLUMN `display_facility_city` tinyint(1) NOT NULL DEFAULT '0';
ALTER TABLE `_system` ADD COLUMN `display_facility_type` tinyint(1) NOT NULL DEFAULT '0';
ALTER TABLE `_system` ADD COLUMN `display_people_birthdate` tinyint(1) NOT NULL DEFAULT '0';
ALTER TABLE `_system` ADD COLUMN `display_people_comments` tinyint(1) NOT NULL DEFAULT '0';
ALTER TABLE `_system` ADD COLUMN `display_people_facilitator` tinyint(1) NOT NULL DEFAULT '0';
ALTER TABLE `_system` ADD COLUMN `display_country_reports` tinyint(1) NOT NULL DEFAULT '1';
ALTER TABLE `_system` ADD COLUMN `display_facility_commodity` tinyint(1) NOT NULL DEFAULT '0';

ALTER TABLE `training` modify COLUMN `training_start_date` date default NULL;
ALTER TABLE `training` modify COLUMN `training_length_interval` enum('hour','week','day') default NULL;

ALTER TABLE `facility` modify COLUMN `type_option_id` int(11) default NULL;

CREATE TABLE `commodity` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `date` date NOT NULL,
  `consumption` int(11) DEFAULT NULL,
  `stock_out` char(1) NOT NULL DEFAULT 'N',
  `facility_id` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT '0',
  `timestamp_created` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_by` int(11) DEFAULT NULL,
  `timestamp_modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=55 DEFAULT CHARSET=latin1;

CREATE TABLE `commodity_name_option` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `commodity_name` varchar(100) NOT NULL,
  `created_by` int(11) DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL,
  `timestamp_updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `timestamp_created` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `is_deleted` tinyint(1) NOT NULL,
  `uuid` varchar(36) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

INSERT INTO `translation`(`key_phrase`,`phrase`,`modified_by`,`created_by`,`is_deleted`) VALUES ('Facility Commodity Column Table Commodity Name','Commodity Name',1,null,0);
INSERT INTO `translation`(`key_phrase`,`phrase`,`modified_by`,`created_by`,`is_deleted`) VALUES ('Facility Commodity Column Table Date','Date',1,null,0);
INSERT INTO `translation`(`key_phrase`,`phrase`,`modified_by`,`created_by`,`is_deleted`) VALUES ('Facility Commodity Column Table Consumption','Consumption',1,null,0);
INSERT INTO `translation`(`key_phrase`,`phrase`,`modified_by`,`created_by`,`is_deleted`) VALUES ('Facility Commodity Column Table Out of Stock','Out of Stock',1,null,0);




