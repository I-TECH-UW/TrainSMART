ALTER TABLE `classes`
ADD COLUMN `credits`  int(10) NOT NULL DEFAULT 0 AFTER `coursetopic`,
ADD COLUMN `class_modules_id`  int(10) NOT NULL DEFAULT 0 AFTER `credits`;
ADD COLUMN `custom_1`  varchar(255) NULL AFTER `class_modules_id`,
ADD COLUMN `custom_2`  varchar(255) NULL AFTER `custom_1`;

ADD COLUMN `coursetypeid`  int(10) NULL DEFAULT 0 AFTER `title`;

CREATE TABLE `class_modules` (
  `id` int(10) NOT NULL auto_increment,
  `external_id` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `lookup_coursetype_id` int(10) NOT NULL DEFAULT 0,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
