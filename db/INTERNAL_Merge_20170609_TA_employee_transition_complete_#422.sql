/**********************************************************************
2017-06-09
Tamara Astakhova
Employee transition complete separate field
#422
**********************************************************************/

CREATE TABLE `employee_transition_complete_option` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uuid` char(36) DEFAULT NULL,
  `transition_complete_phrase` varchar(128) NOT NULL,
  `modified_by` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  `timestamp_updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `timestamp_created` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name_unique` (`transition_complete_phrase`),
  KEY `modified_by` (`modified_by`),
  KEY `created_by` (`created_by`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

/* ONLY for perfar */
insert into employee_transition_complete_option (transition_complete_phrase) values ('Absorbed by PEPFAR Partner');
insert into employee_transition_complete_option (transition_complete_phrase) values ('Absorbed by SAG');
insert into employee_transition_complete_option (transition_complete_phrase) values ('Retained (Other Funding)');
insert into employee_transition_complete_option (transition_complete_phrase) values ('Retained (SAG Funding)');
insert into employee_transition_complete_option (transition_complete_phrase) values ('Retrenched');
insert into employee_transition_complete_option (transition_complete_phrase) values ('Resigned');
insert into employee_transition_complete_option (transition_complete_phrase) values ('Deceased');

