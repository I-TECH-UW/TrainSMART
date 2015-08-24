
ALTER TABLE `link_student_cohort`
ADD COLUMN `examdate`  date NULL AFTER `isgraduated`,
ADD COLUMN `certificate_issue_date`  date NULL AFTER `examdate`,
ADD COLUMN `certificate_received_date`  date NULL AFTER `certificate_issue_date`,
ADD COLUMN `certificate_number`  int NULL AFTER `certificate_received_date`;

ALTER TABLE `person`
ADD COLUMN `workplace_id`  int NULL AFTER `spouse_name`;

CREATE TABLE `workplace` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(255) NOT NULL,
  `work_address_1` varchar(255) default NULL,
  `work_address_2` varchar(255) default NULL,
  `work_location_id` int(11) default NULL,
  `work_phone` varchar(255) default NULL,
  `start_date` date default NULL,
  `end_date` date default NULL,
  `employer_name` varchar(255) default NULL,
  `employer_address_1` varchar(255) default NULL,
  `employer_address_2` varchar(255) default NULL,
  `employer_location_id` int(11) default NULL,
  `contact_person` varchar(255) default NULL,
  `contact_phone` varchar(255) default NULL,
  `contact_email` varchar(255) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

