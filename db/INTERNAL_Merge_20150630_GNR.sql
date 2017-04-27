-- Clinical Mentoring

CREATE TABLE `assess` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `person` int(11) NOT NULL,
  `facility` int(11) NOT NULL,
  `date_created` date NOT NULL,
  `assessment_id` int(11) NOT NULL,
  `question` varchar(3) DEFAULT NULL,
  `option` varchar(2048) DEFAULT NULL,
  `active` char(1) NOT NULL DEFAULT 'N',
  PRIMARY KEY (`id`),
  KEY `person` (`person`,`active`)
) ENGINE=InnoDB AUTO_INCREMENT=3940 DEFAULT CHARSET=utf8;

CREATE TABLE `assessments` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `assessment_type_id` int(11) unsigned NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `lookup_assessment_types` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `assessment_type` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `idx2` (`assessment_type`)
) ENGINE=InnoDB AUTO_INCREMENT=38 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `person_to_assessments` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `person_id` int(11) NOT NULL,
  `facility_id` int(11) unsigned NOT NULL,
  `date_created` date NOT NULL,
  `assessment_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx2` (`person_id`,`facility_id`,`date_created`,`assessment_id`)
) ENGINE=InnoDB AUTO_INCREMENT=73 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `geolocations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `longitude` float DEFAULT NULL,
  `latitude` float DEFAULT NULL,
  `device_id` varchar(64) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `username` varchar(32) DEFAULT NULL,
  `password` varchar(32) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=164 DEFAULT CHARSET=utf8;


-- admin
alter table _system add column `module_assessment_enabled` tinyint(1) NOT NULL DEFAULT '0' after  `module_employee_enabled`;
insert into acl values ('edit_assessment','edit_assessment'), ('assessments_module','assessments_module'), ('view_assessment','view_assessment');

