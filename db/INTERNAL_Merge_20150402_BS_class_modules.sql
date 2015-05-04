ALTER TABLE `classes`
ADD COLUMN `credits`  int(10) NOT NULL DEFAULT 0 AFTER `coursetopic`,
ADD COLUMN `class_modules_id`  int(10) NOT NULL DEFAULT 0 AFTER `credits`,
ADD COLUMN `custom_1`  varchar(255) NULL AFTER `class_modules_id`,
ADD COLUMN `custom_2`  varchar(255) NULL AFTER `custom_1`;

CREATE TABLE `class_modules` (
  `id` int(10) NOT NULL auto_increment,
  `external_id` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `lookup_coursetype_id` int(10) NOT NULL DEFAULT 0,
  `custom_1` varchar(255),
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE `link_student_class_modules` (
  `id` int(11) NOT NULL auto_increment,
  `student_id` int(11) default NULL,
  `class_modules_id` int(11) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;

INSERT IGNORE INTO `translation` (key_phrase, phrase) values ('ps class modules custom 1', 'ps class modules custom 1');

