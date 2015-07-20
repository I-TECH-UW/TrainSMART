-- Clinical Mentoring
INSERT INTO `person_to_assessments` VALUES
(1,1802,522,'2015-07-07',2,1,1),
(2,1802,522,'2015-09-07',2,1,1),
(3,1802,522,'2015-11-07',2,1,1);

drop table person_to_assessments;

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


drop table assessments;
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

-- not used, 
CREATE TABLE `assessments_answers` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `assessmentid` int(10) unsigned NOT NULL DEFAULT '0',
  `personid` int(10) unsigned NOT NULL DEFAULT '0',
  `questionid` int(10) unsigned NOT NULL DEFAULT '0',
  `answer` char(1) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'F',
  `answertext` text COLLATE utf8_unicode_ci NOT NULL,
  `addedon` varchar(20) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `status` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `idx2` (`personid`,`assessmentid`,`questionid`,`answer`,`addedon`,`status`)
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

-- not used
CREATE TABLE `link_qualification_assessment` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `assessmentid` int(10) unsigned NOT NULL DEFAULT '0',
  `qualificationid` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx2` (`assessmentid`,`qualificationid`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



