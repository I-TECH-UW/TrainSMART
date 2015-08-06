-- Clinical Mentoring

-- instance of assessment
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
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- assessment types
CREATE TABLE `assessments` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `assessment_type_id` int(11) unsigned not null,
  `status` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- assessment titles
CREATE TABLE `lookup_assessment_types` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `assessment_type` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `idx2` (`assessment_type`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- answers
CREATE TABLE `assess` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `person` int(11) NOT NULL,
  `facility` int(11) NOT NULL,
  `date_created` date NOT NULL,
  `question` varchar(3) DEFAULT NULL,
  `option` varchar(2048) DEFAULT NULL,
  `active` char(1) NOT NULL DEFAULT 'N',
  PRIMARY KEY (`id`),
  KEY `person` (`person`,`active`)
) ENGINE=MyISAM AUTO_INCREMENT=3401 DEFAULT CHARSET=latin1;

-- questions
CREATE TABLE `assessments_questions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `assessment_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `question` text COLLATE utf8_unicode_ci NOT NULL,
  `itemorder` int(11) NOT NULL DEFAULT '1',
  `itemtype` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'question',
  `status` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `idx2` (`itemtype`,`assessmentid`,`itemorder`,`status`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- admin
alter table _system add column `module_assessment_enabled` tinyint(1) NOT NULL DEFAULT '0' after  `module_employee_enabled`;
insert into acl values ('edit_assessment','edit_assessment'), ('assessments_module','assessments_module'), ('view_assessment','view_assessment');

