/*
2014-09-14
Greg Rossum
For Request: Employee Funding Mechanisms
*/



alter table employee add column employee_code varchar(32) default null;
alter table employee add unique key employee_code_key (employee_code);

CREATE TABLE `employee_to_partner_to_subpartner_to_funder_to_mechanism` (
  `id` int(11) NOT NULL auto_increment,
  `partner_to_subpartner_to_funder_to_mechanism_id` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `partner_id` int(11) NOT NULL,
  `subpartner_id` int(11) NOT NULL,
  `partner_funder_option_id` int(11) NOT NULL,
  `mechanism_option_id` int(11) NOT NULL,
  `percentage` int(11) NOT NULL default '0',
  `created_by` int(11) default NULL,
  `is_deleted` tinyint(1) NOT NULL default '0',
  `timestamp_created` timestamp NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`),
  KEY `idx2` (`employee_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2974 DEFAULT CHARSET=utf8;


CREATE TABLE `mechanism_option` (
  `id` int(11) NOT NULL auto_increment,
  `uuid` char(36) default NULL,
  `mechanism_phrase` varchar(128) NOT NULL,
  `modified_by` int(11) default NULL,
  `created_by` int(11) default NULL,
  `is_deleted` tinyint(1) NOT NULL default '0',
  `timestamp_updated` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `timestamp_created` timestamp NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `name_unique` (`mechanism_phrase`),
  KEY `modified_by` (`modified_by`),
  KEY `created_by` (`created_by`)
) ENGINE=InnoDB AUTO_INCREMENT=116 DEFAULT CHARSET=utf8;




CREATE TABLE `subpartner_to_funder_to_mechanism` (
  `id` int(11) NOT NULL auto_increment,
  `subpartner_id` int(11) NOT NULL,
  `partner_funder_option_id` int(11) NOT NULL,
  `mechanism_option_id` int(11) NOT NULL,
  `funding_end_date` datetime default NULL,
  `created_by` int(11) default NULL,
  `is_deleted` tinyint(1) NOT NULL default '0',
  `timestamp_created` timestamp NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`),
  KEY `subpartner_id` (`subpartner_id`)
) ENGINE=InnoDB AUTO_INCREMENT=216 DEFAULT CHARSET=utf8;

CREATE TABLE `partner_to_subpartner_to_funder_to_mechanism` (
  `id` int(11) NOT NULL auto_increment,
  `subpartner_to_funder_to_mechanism_id` int(11) NOT NULL,
  `partner_id` int(11) NOT NULL,
  `subpartner_id` int(11) NOT NULL,
  `partner_funder_option_id` int(11) NOT NULL,
  `mechanism_option_id` int(11) NOT NULL,
  `funding_end_date` datetime default NULL,
  `created_by` int(11) default NULL,
  `is_deleted` tinyint(1) NOT NULL default '0',
  `timestamp_created` timestamp NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`),
  KEY `partner_id` (`partner_id`)
) ENGINE=InnoDB AUTO_INCREMENT=286 DEFAULT CHARSET=utf8;

