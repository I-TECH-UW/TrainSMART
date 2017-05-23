/**********************************************************************
2017-05-06
Tamara Astakhova
Employee new fields: Direct Service Delivery Model and Direct Service Delivery Team.
#416
**********************************************************************/

/**************   FOR ALL SITES ********************/
CREATE TABLE `employee_dsdmodel_option` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uuid` char(36) DEFAULT NULL,
  `employee_dsdmodel_phrase` varchar(128) NOT NULL DEFAULT '',
  `modified_by` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  `timestamp_updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `timestamp_created` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name_unique` (`employee_dsdmodel_phrase`),
  KEY `modified_by` (`modified_by`),
  KEY `created_by` (`created_by`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

CREATE TABLE `employee_dsdteam_option` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uuid` char(36) DEFAULT NULL,
  `employee_dsdteam_phrase` varchar(128) NOT NULL DEFAULT '',
  `modified_by` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  `timestamp_updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `timestamp_created` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name_unique` (`employee_dsdteam_phrase`),
  KEY `modified_by` (`modified_by`),
  KEY `created_by` (`created_by`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

ALTER TABLE `link_employee_facility` 
ADD COLUMN `dsd_model_id` INT(10) DEFAULT NULL,
ADD COLUMN `dsd_team_id` INT(10) DEFAULT NULL;

CREATE TABLE `employee_partner_option_to_employee_team_option` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uuid` char(36) DEFAULT NULL,
  `partner_id` int(11) NOT NULL,
  `employee_dsdteam_option_id` int(11) NOT NULL,
  `created_by` int(11) DEFAULT NULL,
  `timestamp_created` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_employee_par` (`partner_id`,`employee_dsdteam_option_id`),
  KEY `employee_parther_option_id` (`partner_id`),
  KEY `created_by` (`created_by`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/**************   FOR pepfarskillsmart ONLY  ********************/
insert into facility_type_option (facility_type_phrase) values ('N/A');
insert into facility (facility_name, location_id,type_option_id) 
select 'N/A', location.id, facility_type_option.id from location, facility_type_option
where location.tier=3 and facility_type_option.facility_type_phrase='N/A';
insert into employee_dsdmodel_option (employee_dsdmodel_phrase) values ('Community site');
insert into employee_dsdmodel_option (employee_dsdmodel_phrase) values ('Facility site - Roving');
insert into employee_dsdmodel_option (employee_dsdmodel_phrase) values ('Facility site - Seconded');
insert into employee_dsdmodel_option (employee_dsdmodel_phrase) values ('Facility site - Surge');
insert into employee_dsdmodel_option (employee_dsdmodel_phrase) values ('Health systems strengthening only');
insert into employee_dsdmodel_option (employee_dsdmodel_phrase) values ('Partner management and operations only');
