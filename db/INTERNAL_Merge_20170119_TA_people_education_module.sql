/**********************************************************************
2017-01-19 
Tamara Astakhova
People education module
#331.1
**********************************************************************/

ALTER TABLE _system ADD module_people_education tinyint(1) NOT NULL DEFAULT '0';

CREATE TABLE `education_type_option` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uuid` char(36) DEFAULT NULL,
  `education_type_phrase` varchar(128) NOT NULL DEFAULT '',
  `modified_by` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  `timestamp_updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `timestamp_created` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name_unique` (`education_type_phrase`),
  KEY `modified_by` (`modified_by`),
  KEY `created_by` (`created_by`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

CREATE TABLE `education_school_name_option` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uuid` char(36) DEFAULT NULL,
  `school_name_phrase` varchar(128) NOT NULL DEFAULT '',
  `modified_by` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  `timestamp_updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `timestamp_created` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name_unique` (`school_name_phrase`),
  KEY `modified_by` (`modified_by`),
  KEY `created_by` (`created_by`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

CREATE TABLE `education_country_option` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uuid` char(36) DEFAULT NULL,
  `education_country_phrase` varchar(128) NOT NULL DEFAULT '',
  `modified_by` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  `timestamp_updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `timestamp_created` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name_unique` (`education_country_phrase`),
  KEY `modified_by` (`modified_by`),
  KEY `created_by` (`created_by`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

ALTER TABLE `person` ADD COLUMN `education_type_option_id` int(11) DEFAULT NULL;
ALTER TABLE `person` ADD COLUMN `education_school_name_option_id` int(11) DEFAULT NULL;
ALTER TABLE `person` ADD COLUMN `education_country_option_id` int(11) DEFAULT NULL;
ALTER TABLE `person` ADD COLUMN `education_date_graduation` int(11) DEFAULT NULL;

========================================================================================
LAST RELEASE:
========================================================================================

ALTER TABLE person DROP COLUMN education_type_option_id;
ALTER TABLE person DROP COLUMN education_school_name_option_id;
ALTER TABLE person DROP COLUMN education_country_option_id;
ALTER TABLE person DROP COLUMN education_date_graduation;

CREATE TABLE `person_to_education` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `person_id` int(11) NOT NULL,
  `education_type_option_id` int(11) NOT NULL,
  `education_school_name_option_id` int(11) DEFAULT NULL,
 `education_country_option_id` int(11) DEFAULT NULL,
`education_date_graduation` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

========================================================================================
NEW RELEASE:
========================================================================================

INSERT INTO translation (key_phrase, phrase) values ('Education History', 'Education History');
INSERT INTO translation (key_phrase, phrase) values ('Type of Education', 'Type of Education');
INSERT INTO translation (key_phrase, phrase) values ('Official School Name', 'Official School Name');
INSERT INTO translation (key_phrase, phrase) values ('Education Country', 'Education Country');
INSERT INTO translation (key_phrase, phrase) values ('Year of Graduation/Completion', 'Year of Graduation/Completion');

