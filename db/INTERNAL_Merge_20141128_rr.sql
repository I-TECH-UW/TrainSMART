CREATE TABLE `link_user_cadres` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  `cadreid` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx2` (`userid`,`cadreid`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

insert into acl values 
( 'edit_studenttutorinst', 'edit_studenttutorinst'),
( 'acl_delete_ps_cohort', 'acl_delete_ps_cohort'),
( 'view_studenttutorinst', 'view_studenttutorinst'),
( 'acl_delete_ps_student', 'acl_delete_ps_student'),
( 'acl_delete_ps_grades', 'acl_delete_ps_grades');

ALTER TABLE user_to_acl
change acl_id acl_id enum('in_service','pre_service','edit_employee','edit_course','view_course','duplicate_training','approve_trainings','master_approver','edit_people','view_people','edit_training_location','edit_facility','view_facility','view_create_reports','training_organizer_option_all','training_title_option_all','use_offline_app','admin_files','facility_and_person_approver','edit_evaluations','edit_country_options','acl_editor_training_category','acl_editor_people_qualifications','acl_editor_people_responsibility','acl_editor_training_organizer','acl_editor_people_trainer','acl_editor_training_topic','acl_editor_people_titles','acl_editor_training_level','acl_editor_refresher_course','acl_editor_people_trainer_skills','acl_editor_pepfar_category','acl_editor_people_languages','acl_editor_funding','acl_editor_people_affiliations','acl_editor_recommended_topic','acl_editor_nationalcurriculum','acl_editor_people_suffix','acl_editor_method','acl_editor_people_active_trainer','acl_editor_facility_types','acl_editor_ps_classes','acl_editor_facility_sponsors','acl_editor_ps_cadres','acl_editor_ps_degrees','acl_editor_ps_funding','acl_editor_ps_institutions','acl_editor_ps_languages','acl_editor_ps_nationalities','acl_editor_ps_joindropreasons','acl_editor_ps_sponsors','acl_editor_ps_tutortypes','acl_editor_ps_coursetypes','acl_editor_ps_religions','add_edit_users','acl_admin_training','acl_admin_people','acl_admin_facilities','import_training','import_training_location','import_facility','import_person',
'edit_studenttutorinst', 'acl_delete_ps_cohort', 'view_studenttutorinst', 'acl_delete_ps_student', 'acl_delete_ps_grades');


