/**********************************************************************
2017-02-13
Tamara Astakhova
Participants attestation module
#331.2
**********************************************************************/

ALTER TABLE _system ADD module_participants_attestation tinyint(1) NOT NULL DEFAULT '0';

CREATE TABLE `attestation_category_option` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uuid` char(36) DEFAULT NULL,
  `attestation_category_phrase` varchar(128) NOT NULL DEFAULT '',
  `modified_by` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  `timestamp_updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `timestamp_created` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name_unique` (`attestation_category_phrase`),
  KEY `modified_by` (`modified_by`),
  KEY `created_by` (`created_by`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

CREATE TABLE `attestation_level_option` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uuid` char(36) DEFAULT NULL,
  `attestation_level_phrase` varchar(128) NOT NULL DEFAULT '',
  `modified_by` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  `timestamp_updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `timestamp_created` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name_unique` (`attestation_level_phrase`),
  KEY `modified_by` (`modified_by`),
  KEY `created_by` (`created_by`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;


CREATE TABLE `person_to_attestation` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `person_id` int(11) NOT NULL,
  `attestation_category_option_id` int(11) NOT NULL,
  `attestation_level_option_id` int(11) DEFAULT NULL,
`attestation_date` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;


INSERT INTO translation (key_phrase, phrase) values ('Attestation Progress', 'Attestation Progress');
INSERT INTO translation (key_phrase, phrase) values ('Attestation Category', 'Attestation Category');
INSERT INTO translation (key_phrase, phrase) values ('Attestation Level', 'Attestation Level');
INSERT INTO translation (key_phrase, phrase) values ('Attestation Year', 'Attestation Year');

