/*
2014-11-01
Tamara Astakhova
For Request: CHAI project: Commodity type
*/

drop table `commodity`;

CREATE TABLE `commodity` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` int(11) NOT NULL,
  `date` date NOT NULL,
  `consumption` int(11) DEFAULT NULL,
  `stock_out` char(1) NOT NULL DEFAULT 'N',
  `facility_id` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT '0',
  `timestamp_created` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_by` int(11) DEFAULT NULL,
  `timestamp_modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=59 DEFAULT CHARSET=latin1;

CREATE TABLE `commodity_type_option` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `commodity_type` varchar(100) NOT NULL,
  `created_by` int(11) DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL,
  `timestamp_updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `timestamp_created` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `is_deleted` tinyint(1) NOT NULL,
  `uuid` varchar(36) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;


ALTER TABLE `commodity_name_option` ADD COLUMN `commodity_type_id` int(11) DEFAULT NULL;

INSERT INTO `acl`(`id`,`acl`) VALUES ('add_new_facility','add_new_facility');

ALTER TABLE `user_to_acl` 
CHANGE COLUMN `acl_id` `acl_id` ENUM('ps_view_student_grades','ps_edit_student_grades','ps_view_student','ps_edit_student','in_service','pre_service','edit_employee','edit_course','view_course','duplicate_training','approve_trainings','master_approver','edit_people','view_people','edit_training_location','edit_facility','view_facility','view_create_reports','training_organizer_option_all','training_title_option_all','use_offline_app','admin_files','facility_and_person_approver','edit_evaluations','edit_country_options','acl_editor_training_category','acl_editor_people_qualifications','acl_editor_people_responsibility','acl_editor_training_organizer','acl_editor_people_trainer','acl_editor_training_topic','acl_editor_people_titles','acl_editor_training_level','acl_editor_refresher_course','acl_editor_people_trainer_skills','acl_editor_pepfar_category','acl_editor_people_languages','acl_editor_funding','acl_editor_people_affiliations','acl_editor_recommended_topic','acl_editor_nationalcurriculum','acl_editor_people_suffix','acl_editor_method','acl_editor_people_active_trainer','acl_editor_facility_types','acl_editor_ps_classes','acl_editor_facility_sponsors','acl_editor_ps_cadres','acl_editor_ps_degrees','acl_editor_ps_funding','acl_editor_ps_institutions','acl_editor_ps_languages','acl_editor_ps_nationalities','acl_editor_ps_joindropreasons','acl_editor_ps_sponsors','acl_editor_ps_tutortypes','acl_editor_ps_coursetypes','acl_editor_ps_religions','add_edit_users','acl_admin_training','acl_admin_people','acl_admin_facilities','import_training','import_training_location','import_facility','import_person','add_new_facility') NOT NULL DEFAULT 'view_course' ;





