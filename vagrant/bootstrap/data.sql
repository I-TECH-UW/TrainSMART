-- MySQL dump 10.11
--
-- Host: localhost    Database: itechweb_empty
-- ------------------------------------------------------
-- Server version	5.0.95
CREATE DATABASE  IF NOT EXISTS `itechweb_clean` /*!40100 DEFAULT CHARACTER SET latin1 */;
USE `itechweb_clean`;

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `Helper`
--

DROP TABLE IF EXISTS `Helper`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Helper` (
  `id` int(10) unsigned NOT NULL auto_increment,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `ToLoad0804`
--

DROP TABLE IF EXISTS `ToLoad0804`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ToLoad0804` (
  `clasification` varchar(255) default NULL,
  `role` varchar(255) default NULL,
  `employeecode` varchar(255) default NULL,
  `disability` varchar(255) default NULL,
  `partner` varchar(255) default NULL,
  `based` varchar(255) default NULL,
  `location` varchar(255) default NULL,
  `hours` varchar(255) default NULL,
  `annualsalary` varchar(255) default NULL,
  `annualbenefiets` varchar(255) default NULL,
  `total` varchar(255) default NULL,
  `contractenddate` date default NULL,
  `intendedtransition` varchar(255) default NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `ToLoad0807`
--

DROP TABLE IF EXISTS `ToLoad0807`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ToLoad0807` (
  `clasification` varchar(255) default NULL,
  `role` varchar(255) default NULL,
  `employeecode` varchar(255) default NULL,
  `disability` varchar(255) default NULL,
  `partner` varchar(255) default NULL,
  `based` varchar(255) default NULL,
  `location` varchar(255) default NULL,
  `hours` varchar(255) default NULL,
  `annualsalary` varchar(255) default NULL,
  `annualbenefiets` varchar(255) default NULL,
  `additional` varchar(255) default NULL,
  `total` varchar(255) default NULL,
  `contractenddate` date default NULL,
  `intendedtransition` varchar(255) default NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `ToLoad0807_02`
--

DROP TABLE IF EXISTS `ToLoad0807_02`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ToLoad0807_02` (
  `clasification` varchar(255) default NULL,
  `role` varchar(255) default NULL,
  `employeecode` varchar(255) default NULL,
  `disability` varchar(255) default NULL,
  `disabilitytype` varchar(255) default NULL,
  `partner` varchar(255) default NULL,
  `based` varchar(255) default NULL,
  `location` varchar(255) default NULL,
  `hours` varchar(255) default NULL,
  `annualsalary` varchar(255) default NULL,
  `annualbenefiets` varchar(255) default NULL,
  `additional` varchar(255) default NULL,
  `total` varchar(255) default NULL,
  `contractenddate` date default NULL,
  `intendedtransition` varchar(255) default NULL,
  `transitionother` varchar(255) default NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `_location_missing_links`
--

DROP TABLE IF EXISTS `_location_missing_links`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `_location_missing_links` (
  `id` int(10) unsigned NOT NULL,
  `parent_id` int(10) unsigned NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `_location_ref`
--

DROP TABLE IF EXISTS `_location_ref`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `_location_ref` (
  `id` int(10) unsigned NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `_system`
--

DROP TABLE IF EXISTS `_system`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `_system` (
  `country` varchar(64) NOT NULL default '',
  `country_uuid` char(38) NOT NULL default '' COMMENT 'legacy id',
  `locale` varchar(32) NOT NULL default 'en_EN.UTF-8',
  `locale_enabled` varchar(255) default NULL,
  `allow_multi_pepfar` tinyint(1) NOT NULL default '0',
  `allow_multi_topic` tinyint(1) NOT NULL default '0',
  `display_funding_options` tinyint(1) NOT NULL default '1',
  `display_test_scores_course` tinyint(1) NOT NULL default '1',
  `display_test_scores_individual` tinyint(1) NOT NULL default '1',
  `display_scores_limit` int(11) NOT NULL default '5',
  `display_national_id` tinyint(1) NOT NULL default '1',
  `display_trainer_affiliations` tinyint(1) NOT NULL default '1',
  `display_training_custom1` tinyint(1) NOT NULL default '1',
  `display_training_custom2` tinyint(1) NOT NULL default '1',
  `display_training_pre_test` tinyint(1) NOT NULL default '1',
  `display_training_post_test` tinyint(1) NOT NULL default '1',
  `display_people_custom1` tinyint(1) NOT NULL default '1',
  `display_people_custom2` tinyint(1) NOT NULL default '1',
  `display_region_b` tinyint(1) NOT NULL default '1',
  `display_people_active` tinyint(1) NOT NULL default '1',
  `display_middle_name_last` tinyint(1) NOT NULL default '0',
  `display_training_recommend` tinyint(1) NOT NULL default '0',
  `display_training_trainers` tinyint(1) NOT NULL default '1',
  `display_course_objectives` tinyint(1) NOT NULL default '1',
  `display_training_topic` tinyint(1) NOT NULL default '1',
  `display_training_got_curric` tinyint(1) NOT NULL default '0',
  `display_training_got_comment` tinyint(1) NOT NULL default '0',
  `display_training_refresher` tinyint(1) NOT NULL default '0',
  `display_people_file_num` tinyint(1) NOT NULL default '0',
  `display_people_home_phone` tinyint(1) NOT NULL default '1',
  `display_people_fax` tinyint(1) NOT NULL default '1',
  `module_evaluation_enabled` tinyint(1) unsigned NOT NULL default '1',
  `module_approvals_enabled` tinyint(1) unsigned NOT NULL default '1',
  `module_historical_data_enabled` tinyint(1) unsigned NOT NULL default '1',
  `module_unknown_participants_enabled` tinyint(1) unsigned NOT NULL default '1',
  `module_employee_enabled` tinyint(1) NOT NULL default '0',
  `display_end_date` tinyint(1) unsigned NOT NULL default '0',
  `display_training_method` tinyint(1) unsigned NOT NULL default '0',
  `display_funding_amount` tinyint(1) unsigned NOT NULL default '0',
  `display_primary_language` tinyint(1) unsigned NOT NULL default '0',
  `display_secondary_language` tinyint(1) unsigned NOT NULL default '0',
  `display_people_title` tinyint(1) unsigned NOT NULL default '1',
  `display_people_suffix` tinyint(1) unsigned NOT NULL default '0',
  `display_people_age` tinyint(1) unsigned NOT NULL default '0',
  `display_people_home_address` tinyint(1) unsigned NOT NULL default '0',
  `display_people_second_email` tinyint(1) unsigned NOT NULL default '0',
  `display_middle_name` tinyint(1) NOT NULL default '1',
  `display_funding_amounts` tinyint(1) NOT NULL default '1',
  `display_region_c` tinyint(1) unsigned NOT NULL default '0',
  `display_external_classes` tinyint(1) NOT NULL default '0',
  `display_responsibility_me` tinyint(1) NOT NULL default '0',
  `display_highest_ed_level` tinyint(1) NOT NULL default '0',
  `display_attend_reason` tinyint(1) NOT NULL default '0',
  `display_primary_responsibility` tinyint(1) NOT NULL default '0',
  `display_secondary_responsibility` tinyint(1) NOT NULL default '0',
  `display_training_partner` tinyint(1) NOT NULL default '0',
  `multi_opt_refresher_course` tinyint(1) NOT NULL default '0',
  `display_mod_skillsmart` tinyint(1) NOT NULL default '1',
  `display_occupational_category` tinyint(1) NOT NULL default '1',
  `display_government_employee` tinyint(1) NOT NULL default '1',
  `display_professional_bodies` tinyint(1) NOT NULL default '1',
  `display_race` tinyint(1) NOT NULL default '1',
  `display_disability` tinyint(1) NOT NULL default '1',
  `display_nurse_trainer_type` tinyint(1) NOT NULL default '1',
  `display_provider_start` tinyint(1) NOT NULL default '1',
  `display_rank_groups` tinyint(1) NOT NULL default '1',
  `display_supervised` tinyint(1) NOT NULL default '1',
  `display_training_received` tinyint(1) NOT NULL default '1',
  `display_facility_department` tinyint(1) NOT NULL default '1',
  `employee_header` text,
  `display_employee_employee_header` tinyint(1) NOT NULL default '1',
  `display_employee_partner` tinyint(1) NOT NULL default '1',
  `display_employee_sub_partner` tinyint(1) NOT NULL default '1',
  `display_employee_site_name` tinyint(1) NOT NULL default '1',
  `display_employee_funder` tinyint(1) NOT NULL default '1',
  `display_employee_funding_end_date` tinyint(1) NOT NULL default '1',
  `display_employee_full_time` tinyint(1) NOT NULL default '1',
  `display_employee_funded_hours_per_week` tinyint(1) NOT NULL default '1',
  `display_employee_staff_category` tinyint(1) NOT NULL default '1',
  `display_employee_annual_cost` tinyint(1) NOT NULL default '1',
  `display_employee_external_funding` tinyint(1) NOT NULL default '0',
  `display_employee_primary_role` tinyint(1) NOT NULL default '1',
  `display_employee_importance` tinyint(1) NOT NULL default '1',
  `display_employee_contract_end_date` tinyint(1) NOT NULL default '1',
  `display_employee_agreement_end_date` tinyint(1) NOT NULL default '1',
  `display_employee_intended_transition` tinyint(1) NOT NULL default '1',
  `display_employee_transition_confirmed` tinyint(1) NOT NULL default '1',
  `display_employee_incoming_partner` tinyint(1) NOT NULL default '1',
  `display_employee_relationship` tinyint(1) NOT NULL default '1',
  `display_employee_referral_mechanism` tinyint(1) NOT NULL default '0',
  `display_employee_chw_supervisor` tinyint(1) NOT NULL default '0',
  `display_employee_trainings_provided` tinyint(1) NOT NULL default '0',
  `display_employee_courses_completed` tinyint(1) NOT NULL default '0',
  `module_datashare_enabled` tinyint(1) NOT NULL default '0',
  `datashare_password` text NOT NULL,
  `require_duplicate_acl` tinyint(1) NOT NULL default '0',
  `module_attendance_enabled` tinyint(1) NOT NULL default '0',
  `display_sponsor_dates` tinyint(1) NOT NULL default '0',
  `require_sponsor_dates` tinyint(1) NOT NULL default '0',
  `allow_multi_sponsors` tinyint(1) NOT NULL default '0',
  `allow_multi_approvers` tinyint(1) NOT NULL default '0',
  `fiscal_year_start` datetime default NULL,
  `display_gender` tinyint(1) NOT NULL default '1',
  `display_training_custom3` tinyint(1) NOT NULL default '0',
  `display_training_custom4` tinyint(1) NOT NULL default '0',
  `display_people_custom3` tinyint(1) NOT NULL default '0',
  `display_people_custom4` tinyint(1) NOT NULL default '0',
  `display_people_custom5` tinyint(1) NOT NULL default '0',
  `display_training_pepfar` tinyint(1) NOT NULL default '1',
  `require_trainer_skill` tinyint(1) NOT NULL default '0',
  `display_region_d` tinyint(1) NOT NULL default '0',
  `display_region_e` tinyint(1) NOT NULL default '0',
  `display_region_f` tinyint(1) NOT NULL default '0',
  `display_region_g` tinyint(1) NOT NULL default '0',
  `display_region_h` tinyint(1) NOT NULL default '0',
  `display_region_i` tinyint(1) NOT NULL default '0',
  `module_person_approval` tinyint(1) NOT NULL default '0',
  `module_facility_approval` tinyint(1) NOT NULL default '0',
  `display_budget_code` tinyint(1) NOT NULL default '0',
  `display_training_completion` tinyint(1) NOT NULL default '0',
  `display_viewing_location` tinyint(1) NOT NULL default '0',
  `display_employee_registration_number` tinyint(1) NOT NULL default '1',
  `display_employee_dob` tinyint(1) NOT NULL default '1',
  `display_employee_race` tinyint(1) NOT NULL default '1',
  `display_employee_other_id` tinyint(1) NOT NULL default '1',
  `display_employee_disability` tinyint(1) NOT NULL default '1',
  `display_employee_salary` tinyint(1) NOT NULL default '1',
  `display_employee_benefits` tinyint(1) NOT NULL default '1',
  `display_employee_additional_expenses` tinyint(1) NOT NULL default '1',
  `display_employee_stipend` tinyint(1) NOT NULL default '1',
  `display_employee_complete_transition` tinyint(1) NOT NULL default '1',
  `display_facility_postal_code` tinyint(1) NOT NULL default '1',
  `display_facility_lat_long` tinyint(1) NOT NULL default '1',
  `display_employee_nationality` tinyint(1) NOT NULL default '1',
  `display_facility_sponsor` tinyint(1) NOT NULL default '1',
  `display_partner_type` tinyint(1) NOT NULL default '1',
  `display_employee_base` tinyint(1) NOT NULL default '1',
  `display_facility_comments` tinyint(1) NOT NULL default '1',
  `display_employee_actual_transition_date` tinyint(1) NOT NULL default '1',
  `display_employee_site_type` tinyint(1) NOT NULL default '1',
  `display_facility_custom1` tinyint(1) NOT NULL default '0',
  `ps_display_inst_compl_date` tinyint(1) NOT NULL default '0',
  `ps_display_last_inst_attended` tinyint(1) NOT NULL default '0',
  `ps_display_start_school_date` tinyint(1) NOT NULL default '0',
  `ps_display_equivalence` tinyint(1) NOT NULL default '0',
  `ps_display_last_univ_attended` tinyint(1) NOT NULL default '0',
  `ps_display_person_charge` tinyint(1) NOT NULL default '0',
  `ps_display_custom_field1` tinyint(1) NOT NULL default '0',
  `ps_display_custom_field2` tinyint(1) NOT NULL default '0',
  `ps_display_custom_field3` tinyint(1) NOT NULL default '0',
  `ps_display_marital_status` tinyint(1) NOT NULL default '0',
  `ps_display_spouse_name` tinyint(1) NOT NULL default '0',
  `ps_display_specialty` tinyint(1) NOT NULL default '0',
  `ps_display_contract_type` tinyint(1) NOT NULL default '0',
  `ps_display_local_address` tinyint(1) NOT NULL default '0',
  `ps_display_permanent_address` tinyint(1) NOT NULL default '0',
  `ps_display_religious_denomin` tinyint(1) NOT NULL default '0',
  `ps_display_nationality` tinyint(1) NOT NULL default '0',
  `display_training_category` tinyint(1) NOT NULL default '0',
  `display_training_start_date` tinyint(1) NOT NULL default '0',
  `display_training_length` tinyint(1) NOT NULL default '0',
  `display_training_level` tinyint(1) NOT NULL default '0',
  `display_training_comments` tinyint(1) NOT NULL default '0',
  `display_facilitator_info` tinyint(1) NOT NULL default '0',
  `display_training_score` tinyint(1) NOT NULL default '0',
  `display_facility_address` tinyint(1) NOT NULL default '0',
  `display_facility_phone` tinyint(1) NOT NULL default '0',
  `display_facility_fax` tinyint(1) NOT NULL default '0',
  `display_facility_city` tinyint(1) NOT NULL default '0',
  `display_facility_type` tinyint(1) NOT NULL default '0',
  `display_people_birthdate` tinyint(1) NOT NULL default '0',
  `display_people_comments` tinyint(1) NOT NULL default '0',
  `display_people_facilitator` tinyint(1) NOT NULL default '0',
  `display_country_reports` tinyint(1) NOT NULL default '1',
  `display_facility_commodity` tinyint(1) NOT NULL default '0',
  `ps_display_exam_mark` tinyint(1) NOT NULL default '0',
  `ps_display_ca_mark` tinyint(1) NOT NULL default '0',
  `ps_display_credits` tinyint(1) NOT NULL default '0',
  `display_hours_per_mechanism` tinyint(1) NOT NULL default '0',
  `display_annual_cost_to_mechanism` tinyint(1) NOT NULL default '0',
  `display_email_report_1` tinyint(1) NOT NULL default '0',
  `display_email_report_2` tinyint(1) NOT NULL default '0',
  `display_email_report_3` tinyint(1) NOT NULL default '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `_system`
--

LOCK TABLES `_system` WRITE;
/*!40000 ALTER TABLE `_system` DISABLE KEYS */;
INSERT INTO `_system` VALUES ('Empty','','en_EN.UTF-8','en_EN.UTF-8',0,0,0,1,1,5,0,0,1,1,1,1,0,0,1,0,0,0,0,1,1,1,1,1,1,0,0,0,0,0,0,1,0,0,0,0,0,0,1,0,0,0,0,1,1,0,0,0,0,0,0,0,0,0,1,1,1,1,1,1,1,1,1,1,1,'Welcome to TrainSMART<br><br>I-TECH (University of Washington)',1,1,1,1,1,1,0,1,0,1,1,1,0,1,0,1,0,0,0,0,0,0,0,0,'',0,0,0,0,0,0,'0000-00-00 00:00:00',0,0,0,0,0,0,1,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,1,1,1,1,1,0,0,0,0,1,1,1,1,1,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,1,1,1,1,1,1,1,1,1,1,1,0,1,1,1,1,0,0,0,0,0,0,0,0);
/*!40000 ALTER TABLE `_system` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `acl`
--

DROP TABLE IF EXISTS `acl`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `acl` (
  `id` varchar(32) NOT NULL default '',
  `acl` varchar(32) NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `acl`
--

LOCK TABLES `acl` WRITE;
/*!40000 ALTER TABLE `acl` DISABLE KEYS */;
INSERT INTO `acl` VALUES ('add_edit_users','add_edit_users'),('approve_trainings','approve_trainings'),('edit_country_options','edit_country_options'),('edit_course','edit_course'),('edit_people','edit_people'),('training_organizer_option_all','training_organizer_option_all'),('training_title_option_all','training_title_option_all'),('view_course','view_course'),('view_create_reports','view_create_reports'),('view_people','view_people'),('admin_files','admin_files'),('use_offline_app','use_offline_app'),('in_service','in_service'),('pre_service','pre_service'),('edit_employee','edit_employee'),('duplicate_training','duplicate_training'),('import_training','import_training'),('import_training_location','import_training_location'),('import_facility','import_facility'),('import_person','import_person'),('facility_and_person_approver','facility_and_person_approver'),('acl_editor_training_category','acl_editor_training_category'),('acl_editor_people_qualifications','acl_editor_people_qualifications'),('acl_editor_people_responsibility','acl_editor_people_responsibility'),('acl_editor_training_organizer','acl_editor_training_organizer'),('acl_editor_people_trainer','acl_editor_people_trainer'),('acl_editor_training_topic','acl_editor_training_topic'),('acl_editor_people_titles','acl_editor_people_titles'),('acl_editor_training_level','acl_editor_training_level'),('acl_editor_people_trainer_skills','acl_editor_people_trainer_skills'),('acl_editor_pepfar_category','acl_editor_pepfar_category'),('acl_editor_people_languages','acl_editor_people_languages'),('acl_editor_funding','acl_editor_funding'),('acl_editor_people_affiliations','acl_editor_people_affiliations'),('acl_editor_recommended_topic','acl_editor_recommended_topic'),('acl_editor_nationalcurriculum','acl_editor_nationalcurriculum'),('acl_editor_people_suffix','acl_editor_people_suffix'),('acl_editor_method','acl_editor_method'),('acl_editor_people_active_trainer','acl_editor_people_active_trainer'),('acl_editor_facility_types','acl_editor_facility_types'),('acl_editor_facility_sponsors','acl_editor_facility_sponsors'),('acl_editor_ps_classes','acl_editor_ps_classes'),('acl_editor_ps_cadres','acl_editor_ps_cadres'),('acl_editor_ps_degrees','acl_editor_ps_degrees'),('acl_editor_ps_funding','acl_editor_ps_funding'),('acl_editor_ps_institutions','acl_editor_ps_institutions'),('acl_editor_ps_languages','acl_editor_ps_languages'),('acl_editor_ps_nationalities','acl_editor_ps_nationalities'),('acl_editor_ps_joindropreasons','acl_editor_ps_joindropreasons'),('acl_editor_ps_sponsors','acl_editor_ps_sponsors'),('acl_editor_ps_tutortypes','acl_editor_ps_tutortypes'),('acl_editor_ps_coursetypes','acl_editor_ps_coursetypes'),('acl_editor_ps_religions','acl_editor_ps_religions'),('acl_admin_training','acl_admin_training'),('acl_admin_people','acl_admin_people'),('acl_admin_facilities','acl_admin_facilities'),('acl_editor_refresher_course','acl_editor_refresher_course'),('master_approver','master_approver'),('edit_evaluations','edit_evaluations'),('edit_facility','edit_facility'),('view_facility','view_facility'),('edit_training_location','edit_training_location'),('add_new_facility','add_new_facility'),('edit_partners','edit_partners'),('edit_mechanisms','edit_mechanisms'),('employees_module','employees_module'),('view_training_location','view_training_location'),('view_employee','view_employee'),('view_mechanisms','view_mechanisms'),('view_partners','view_partners');
/*!40000 ALTER TABLE `acl` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `addresses`
--

DROP TABLE IF EXISTS `addresses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `addresses` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `address1` varchar(150) NOT NULL default '',
  `address2` varchar(150) NOT NULL default '',
  `city` varchar(150) NOT NULL default '',
  `postalcode` varchar(15) NOT NULL default '',
  `state` varchar(50) NOT NULL default '',
  `country` varchar(150) NOT NULL default '',
  `id_addresstype` int(10) unsigned NOT NULL default '0',
  `id_geog1` varchar(50) NOT NULL default '0',
  `id_geog2` varchar(50) NOT NULL default '0',
  `id_geog3` varchar(50) NOT NULL default '0',
  `locationid` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `idx2` (`address2`,`id_geog1`,`id_addresstype`,`id_geog2`,`country`,`city`,`id_geog3`,`state`,`postalcode`,`address1`),
  KEY `idxlookups` (`locationid`,`id_geog3`,`id_geog2`,`id_geog1`,`id_addresstype`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `age_range_option`
--

DROP TABLE IF EXISTS `age_range_option`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `age_range_option` (
  `id` int(11) NOT NULL auto_increment,
  `age_range_phrase` varchar(128) NOT NULL default '',
  `is_default` tinyint(1) unsigned NOT NULL default '0',
  `modified_by` int(11) default NULL,
  `created_by` int(11) default NULL,
  `is_deleted` tinyint(1) NOT NULL default '0',
  `timestamp_updated` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `timestamp_created` timestamp NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `name_unique` (`age_range_phrase`),
  KEY `created_by` (`created_by`),
  KEY `modified_by` (`modified_by`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `agency_option`
--

DROP TABLE IF EXISTS `agency_option`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `agency_option` (
  `id` int(11) NOT NULL auto_increment,
  `uuid` char(36) default NULL,
  `agency_phrase` varchar(128) NOT NULL,
  `modified_by` int(11) default NULL,
  `created_by` int(11) default NULL,
  `is_deleted` tinyint(1) NOT NULL default '0',
  `timestamp_updated` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `timestamp_created` timestamp NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `name_unique` (`agency_phrase`),
  KEY `modified_by` (`modified_by`),
  KEY `created_by` (`created_by`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `cadres`
--

DROP TABLE IF EXISTS `cadres`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cadres` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `cadrename` varchar(150) collate utf8_unicode_ci NOT NULL default '',
  `cadredescription` text collate utf8_unicode_ci NOT NULL,
  `status` tinyint(3) unsigned NOT NULL default '1',
  PRIMARY KEY  (`id`),
  KEY `idx2` (`status`,`cadrename`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `caprisa`
--

DROP TABLE IF EXISTS `caprisa`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `caprisa` (
  `employeequalification` varchar(255) default NULL,
  `employeerole` varchar(255) default NULL,
  `firstname` varchar(255) default NULL,
  `lastname` varchar(255) default NULL,
  `partneremployeenumber` varchar(255) default NULL,
  `dob` varchar(255) default NULL,
  `otherid` varchar(255) default NULL,
  `disabilityoptionid` varchar(255) default NULL,
  `partner` varchar(255) default NULL,
  `employeebased` varchar(255) default NULL,
  `sitename` varchar(255) default NULL,
  `sitetype` varchar(255) default NULL,
  `hours` varchar(255) default NULL,
  `salary` varchar(255) default NULL,
  `benefiets` varchar(255) default NULL,
  `annualcost` varchar(255) default NULL,
  `externalfundingpercentage` varchar(255) default NULL,
  `agreementenddate` varchar(255) default NULL,
  `intendedtransition` varchar(255) default NULL,
  `transitondate` varchar(255) default NULL,
  `transitionoutcome` varchar(255) default NULL,
  `actualtransitondate` varchar(255) default NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `classes`
--

DROP TABLE IF EXISTS `classes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `classes` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `classname` varchar(150) NOT NULL default '',
  `startdate` varchar(20) NOT NULL default '',
  `enddate` varchar(20) NOT NULL default '',
  `instructorid` int(10) unsigned NOT NULL default '0',
  `coursetypeid` int(10) unsigned NOT NULL default '0',
  `coursetopic` varchar(150) NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `idx2` (`coursetypeid`,`instructorid`,`enddate`,`startdate`,`classname`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `cohort`
--

DROP TABLE IF EXISTS `cohort`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cohort` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `cohortid` varchar(25) NOT NULL default '',
  `cohortname` varchar(50) NOT NULL default '',
  `startdate` date NOT NULL default '0000-00-00',
  `graddate` date NOT NULL default '0000-00-00',
  `degree` int(10) unsigned NOT NULL default '0',
  `institutionid` int(10) unsigned NOT NULL default '0',
  `cadreid` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `idx2` (`degree`,`cohortname`,`graddate`,`startdate`,`cohortid`,`institutionid`,`cadreid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `commodity`
--

DROP TABLE IF EXISTS `commodity`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `commodity` (
  `id` int(11) NOT NULL auto_increment,
  `name` int(11) NOT NULL,
  `date` date NOT NULL,
  `consumption` int(11) default NULL,
  `stock_out` char(1) NOT NULL default 'N',
  `facility_id` int(11) default NULL,
  `created_by` int(11) default '0',
  `timestamp_created` timestamp NOT NULL default '0000-00-00 00:00:00',
  `modified_by` int(11) default NULL,
  `timestamp_modified` timestamp NOT NULL default CURRENT_TIMESTAMP,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=59 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `commodity_name_option`
--

DROP TABLE IF EXISTS `commodity_name_option`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `commodity_name_option` (
  `id` int(11) NOT NULL auto_increment,
  `commodity_name` varchar(100) NOT NULL,
  `created_by` int(11) default NULL,
  `modified_by` int(11) default NULL,
  `timestamp_updated` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `timestamp_created` timestamp NOT NULL default '0000-00-00 00:00:00',
  `is_deleted` tinyint(1) NOT NULL,
  `uuid` varchar(36) default NULL,
  `commodity_type_id` int(11) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `commodity_type_option`
--

DROP TABLE IF EXISTS `commodity_type_option`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `commodity_type_option` (
  `id` int(11) NOT NULL auto_increment,
  `commodity_type` varchar(100) NOT NULL,
  `created_by` int(11) default NULL,
  `modified_by` int(11) default NULL,
  `timestamp_updated` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `timestamp_created` timestamp NOT NULL default '0000-00-00 00:00:00',
  `is_deleted` tinyint(1) NOT NULL,
  `uuid` varchar(36) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `comp`
--

DROP TABLE IF EXISTS `comp`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `comp` (
  `id` bigint(20) NOT NULL auto_increment,
  `person` int(11) NOT NULL,
  `question` varchar(3) default NULL,
  `option` varchar(128) default NULL,
  `active` char(1) NOT NULL default 'N',
  PRIMARY KEY  (`id`),
  KEY `person` (`person`,`active`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `competencies`
--

DROP TABLE IF EXISTS `competencies`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `competencies` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `competencyname` varchar(255) collate utf8_unicode_ci NOT NULL default '',
  `status` tinyint(4) NOT NULL default '1',
  PRIMARY KEY  (`id`),
  KEY `idx2` (`competencyname`,`status`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `competencies_answers`
--

DROP TABLE IF EXISTS `competencies_answers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `competencies_answers` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `competencyid` int(10) unsigned NOT NULL default '0',
  `personid` int(10) unsigned NOT NULL default '0',
  `questionid` int(10) unsigned NOT NULL default '0',
  `answer` char(1) collate utf8_unicode_ci NOT NULL default 'F',
  `answertext` text collate utf8_unicode_ci NOT NULL,
  `addedon` varchar(20) collate utf8_unicode_ci NOT NULL default '',
  `status` tinyint(4) NOT NULL default '1',
  PRIMARY KEY  (`id`),
  KEY `idx2` (`personid`,`competencyid`,`questionid`,`answer`,`addedon`,`status`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `competencies_questions`
--

DROP TABLE IF EXISTS `competencies_questions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `competencies_questions` (
  `id` bigint(20) unsigned NOT NULL auto_increment,
  `competencyid` bigint(20) unsigned NOT NULL default '0',
  `question` text collate utf8_unicode_ci NOT NULL,
  `itemorder` int(11) NOT NULL default '1',
  `itemtype` varchar(50) collate utf8_unicode_ci NOT NULL default 'question',
  `status` tinyint(4) NOT NULL default '1',
  PRIMARY KEY  (`id`),
  KEY `idx2` (`itemtype`,`competencyid`,`itemorder`,`status`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `compres`
--

DROP TABLE IF EXISTS `compres`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `compres` (
  `SNo` bigint(20) NOT NULL auto_increment,
  `person` int(11) NOT NULL,
  `res` int(11) NOT NULL,
  `active` char(1) NOT NULL default 'N',
  PRIMARY KEY  (`SNo`),
  KEY `person` (`person`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `course`
--

DROP TABLE IF EXISTS `course`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `course` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `coursename` varchar(255) NOT NULL default '',
  `startdate` datetime NOT NULL default '0000-00-00 00:00:00',
  `courselength` int(10) unsigned NOT NULL default '0',
  `examid` int(10) unsigned NOT NULL default '0',
  `examname` varchar(255) NOT NULL default '0',
  `examdate` datetime NOT NULL default '0000-00-00 00:00:00',
  `examscore` int(10) unsigned NOT NULL default '0',
  `practicumid` int(10) unsigned NOT NULL default '0',
  `practicumname` varchar(150) NOT NULL default '0',
  `practicumhours` int(10) unsigned NOT NULL default '0',
  `practicumcomplete` int(10) unsigned NOT NULL default '0',
  `degreeid` int(10) unsigned NOT NULL default '0',
  `comment` text NOT NULL,
  `customfield1` varchar(255) NOT NULL default '0',
  `customfield2` varchar(255) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `idx2` (`startdate`,`examid`,`practicumid`,`courselength`,`degreeid`,`examdate`,`coursename`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `datashare_sites`
--

DROP TABLE IF EXISTS `datashare_sites`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `datashare_sites` (
  `id` int(11) NOT NULL auto_increment,
  `uuid` char(36) default NULL,
  `db_name` varchar(255) NOT NULL,
  `site_password` text,
  `organizer_access` text,
  `modified_by` int(11) default NULL,
  `created_by` int(11) default NULL,
  `timestamp_updated` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `timestamp_created` timestamp NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `uuid_idx` (`uuid`),
  KEY `created_by` (`created_by`),
  KEY `modified_by` (`modified_by`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

/*!50003 SET @SAVE_SQL_MODE=@@SQL_MODE*/;

DELIMITER ;;
/*!50003 SET SESSION SQL_MODE="NO_AUTO_VALUE_ON_ZERO" */;;
/*!50003 CREATE */ /*!50017 DEFINER=`root`@`localhost` */ /*!50003 TRIGGER `datashare_sites_insert` BEFORE INSERT ON `datashare_sites` FOR EACH ROW BEGIN

  SET NEW.`uuid` = IFNULL(NEW.`uuid`,UUID());

  END */;;

DELIMITER ;
/*!50003 SET SESSION SQL_MODE=@SAVE_SQL_MODE*/;

--
-- Table structure for table `employee`
--

DROP TABLE IF EXISTS `employee`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `employee` (
  `id` int(11) NOT NULL auto_increment,
  `uuid` char(36) default NULL,
  `partner_id` int(11) default NULL,
  `partner_employee_number` int(11) NOT NULL,
  `subpartner_id` int(11) default NULL,
  `employee_base_option_id` int(11) default NULL,
  `location_id` int(11) default NULL,
  `title_option_id` int(11) default NULL,
  `first_name` varchar(32) default NULL,
  `middle_name` varchar(32) default NULL,
  `last_name` varchar(32) default NULL,
  `national_id` varchar(64) default NULL,
  `other_id` varchar(64) default NULL,
  `gender` enum('male','female','na') default NULL,
  `race_option_id` int(11) default NULL,
  `option_nationality_id` int(11) default '0',
  `dob` datetime default NULL,
  `disability_comments` varchar(128) default NULL,
  `disability_option_id` int(11) default NULL,
  `site_id` int(11) default NULL,
  `facility_type_option_id` int(11) default NULL,
  `address1` varchar(128) default NULL,
  `address2` varchar(128) default NULL,
  `primary_phone` varchar(64) default NULL,
  `secondary_phone` varchar(64) default NULL,
  `email` varchar(128) default NULL,
  `employee_qualification_option_id` int(11) default NULL,
  `employee_category_option_id` int(11) default NULL,
  `employee_role_option_id` int(11) default NULL,
  `employee_fulltime_option_id` int(11) default NULL,
  `funded_hours_per_week` int(11) default NULL,
  `annual_cost` varchar(11) default NULL,
  `external_funding_percent` int(3) default NULL,
  `agreement_end_date` datetime default NULL,
  `supervisor_id` int(11) default NULL,
  `employee_transition_option_id` int(11) default NULL,
  `transition_other` varchar(64) default NULL,
  `employee_transition_complete_option_id` int(11) NOT NULL,
  `transition_complete_other` varchar(64) default NULL,
  `transition_complete_date` datetime default NULL,
  `transition_confirmed` int(11) default NULL,
  `transition_date` datetime default NULL,
  `employee_training_provided_option_id` int(11) default NULL,
  `comments` text,
  `created_by` int(11) default NULL,
  `timestamp_created` timestamp NOT NULL default '0000-00-00 00:00:00',
  `registration_number` varchar(40) default NULL,
  `salary` varchar(11) default NULL,
  `benefits` varchar(11) default NULL,
  `additional_expenses` varchar(11) default NULL,
  `stipend` varchar(11) default NULL,
  `employee_code` varchar(32) default NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `employee_code_key` (`employee_code`)
) ENGINE=MyISAM AUTO_INCREMENT=18066 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

/*!50003 SET @SAVE_SQL_MODE=@@SQL_MODE*/;

DELIMITER ;;
/*!50003 SET SESSION SQL_MODE="NO_AUTO_VALUE_ON_ZERO" */;;
/*!50003 CREATE */ /*!50017 DEFINER=`root`@`localhost` */ /*!50003 TRIGGER `employee_insert` BEFORE INSERT ON `employee` FOR EACH ROW BEGIN

  SET NEW.`uuid` = IFNULL(NEW.`uuid`,UUID());

END */;;

DELIMITER ;
/*!50003 SET SESSION SQL_MODE=@SAVE_SQL_MODE*/;

--
-- Table structure for table `employee_base_option`
--

DROP TABLE IF EXISTS `employee_base_option`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `employee_base_option` (
  `id` int(11) NOT NULL auto_increment,
  `uuid` char(36) default NULL,
  `base_phrase` varchar(128) NOT NULL,
  `modified_by` int(11) default NULL,
  `created_by` int(11) default NULL,
  `is_deleted` tinyint(1) NOT NULL default '0',
  `timestamp_updated` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `timestamp_created` timestamp NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `name_unique` (`base_phrase`),
  KEY `modified_by` (`modified_by`),
  KEY `created_by` (`created_by`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

/*!50003 SET @SAVE_SQL_MODE=@@SQL_MODE*/;

DELIMITER ;;
/*!50003 SET SESSION SQL_MODE="" */;;
/*!50003 CREATE */ /*!50017 DEFINER=`root`@`localhost` */ /*!50003 TRIGGER `employee_base_insert` BEFORE INSERT ON `employee_base_option` FOR EACH ROW BEGIN

  SET NEW.`uuid` = IFNULL(NEW.`uuid`,UUID());

END */;;

DELIMITER ;
/*!50003 SET SESSION SQL_MODE=@SAVE_SQL_MODE*/;

--
-- Table structure for table `employee_category_option`
--

DROP TABLE IF EXISTS `employee_category_option`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `employee_category_option` (
  `id` int(11) NOT NULL auto_increment,
  `uuid` char(36) default NULL,
  `category_phrase` varchar(128) NOT NULL,
  `modified_by` int(11) default NULL,
  `created_by` int(11) default NULL,
  `is_deleted` tinyint(1) NOT NULL default '0',
  `timestamp_updated` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `timestamp_created` timestamp NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `name_unique` (`category_phrase`),
  KEY `modified_by` (`modified_by`),
  KEY `created_by` (`created_by`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

/*!50003 SET @SAVE_SQL_MODE=@@SQL_MODE*/;

DELIMITER ;;
/*!50003 SET SESSION SQL_MODE="NO_AUTO_VALUE_ON_ZERO" */;;
/*!50003 CREATE */ /*!50017 DEFINER=`root`@`localhost` */ /*!50003 TRIGGER `employee_category_insert` BEFORE INSERT ON `employee_category_option` FOR EACH ROW BEGIN

  SET NEW.`uuid` = IFNULL(NEW.`uuid`,UUID());

END */;;

DELIMITER ;
/*!50003 SET SESSION SQL_MODE=@SAVE_SQL_MODE*/;

--
-- Table structure for table `employee_fulltime_option`
--

DROP TABLE IF EXISTS `employee_fulltime_option`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `employee_fulltime_option` (
  `id` int(11) NOT NULL auto_increment,
  `uuid` char(36) default NULL,
  `fulltime_phrase` varchar(128) NOT NULL,
  `modified_by` int(11) default NULL,
  `created_by` int(11) default NULL,
  `is_deleted` tinyint(1) NOT NULL default '0',
  `timestamp_updated` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `timestamp_created` timestamp NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `name_unique` (`fulltime_phrase`),
  UNIQUE KEY `uuid_idx` (`uuid`),
  KEY `modified_by` (`modified_by`),
  KEY `created_by` (`created_by`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

/*!50003 SET @SAVE_SQL_MODE=@@SQL_MODE*/;

DELIMITER ;;
/*!50003 SET SESSION SQL_MODE="" */;;
/*!50003 CREATE */ /*!50017 DEFINER=`root`@`localhost` */ /*!50003 TRIGGER `employee_fulltime_insert` BEFORE INSERT ON `employee_fulltime_option` FOR EACH ROW BEGIN

  SET NEW.`uuid` = IFNULL(NEW.`uuid`,UUID());

END */;;

DELIMITER ;
/*!50003 SET SESSION SQL_MODE=@SAVE_SQL_MODE*/;

--
-- Table structure for table `employee_qualification_option`
--

DROP TABLE IF EXISTS `employee_qualification_option`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `employee_qualification_option` (
  `id` int(11) NOT NULL auto_increment,
  `uuid` char(36) default NULL,
  `qualification_phrase` varchar(128) NOT NULL,
  `qualification_code` varchar(64) default NULL,
  `modified_by` int(11) default NULL,
  `created_by` int(11) default NULL,
  `is_deleted` tinyint(1) NOT NULL default '0',
  `timestamp_updated` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `timestamp_created` timestamp NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `name_unique` (`qualification_phrase`),
  UNIQUE KEY `uuid_idx` (`uuid`),
  KEY `modified_by` (`modified_by`),
  KEY `created_by` (`created_by`)
) ENGINE=MyISAM AUTO_INCREMENT=68 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

/*!50003 SET @SAVE_SQL_MODE=@@SQL_MODE*/;

DELIMITER ;;
/*!50003 SET SESSION SQL_MODE="" */;;
/*!50003 CREATE */ /*!50017 DEFINER=`root`@`localhost` */ /*!50003 TRIGGER `employee_qualification_insert` BEFORE INSERT ON `employee_qualification_option` FOR EACH ROW BEGIN

  SET NEW.`uuid` = IFNULL(NEW.`uuid`,UUID());

END */;;

DELIMITER ;
/*!50003 SET SESSION SQL_MODE=@SAVE_SQL_MODE*/;

--
-- Table structure for table `employee_referral_option`
--

DROP TABLE IF EXISTS `employee_referral_option`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `employee_referral_option` (
  `id` int(11) NOT NULL auto_increment,
  `uuid` char(36) default NULL,
  `referral_phrase` varchar(128) NOT NULL,
  `modified_by` int(11) default NULL,
  `created_by` int(11) default NULL,
  `is_deleted` tinyint(1) NOT NULL default '0',
  `timestamp_updated` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `timestamp_created` timestamp NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `name_unique` (`referral_phrase`),
  KEY `modified_by` (`modified_by`),
  KEY `created_by` (`created_by`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

/*!50003 SET @SAVE_SQL_MODE=@@SQL_MODE*/;

DELIMITER ;;
/*!50003 SET SESSION SQL_MODE="NO_AUTO_VALUE_ON_ZERO" */;;
/*!50003 CREATE */ /*!50017 DEFINER=`root`@`localhost` */ /*!50003 TRIGGER `employee_referral_insert` BEFORE INSERT ON `employee_referral_option` FOR EACH ROW BEGIN

  SET NEW.`uuid` = IFNULL(NEW.`uuid`,UUID());

END */;;

DELIMITER ;
/*!50003 SET SESSION SQL_MODE=@SAVE_SQL_MODE*/;

--
-- Table structure for table `employee_relationship_option`
--

DROP TABLE IF EXISTS `employee_relationship_option`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `employee_relationship_option` (
  `id` int(11) NOT NULL auto_increment,
  `uuid` char(36) default NULL,
  `relationship_phrase` varchar(128) NOT NULL,
  `modified_by` int(11) default NULL,
  `created_by` int(11) default NULL,
  `is_deleted` tinyint(1) NOT NULL default '0',
  `timestamp_updated` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `timestamp_created` timestamp NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `name_unique` (`relationship_phrase`),
  KEY `modified_by` (`modified_by`),
  KEY `created_by` (`created_by`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

/*!50003 SET @SAVE_SQL_MODE=@@SQL_MODE*/;

DELIMITER ;;
/*!50003 SET SESSION SQL_MODE="NO_AUTO_VALUE_ON_ZERO" */;;
/*!50003 CREATE */ /*!50017 DEFINER=`root`@`localhost` */ /*!50003 TRIGGER `employee_relationship_insert` BEFORE INSERT ON `employee_relationship_option` FOR EACH ROW BEGIN

  SET NEW.`uuid` = IFNULL(NEW.`uuid`,UUID());

END */;;

DELIMITER ;
/*!50003 SET SESSION SQL_MODE=@SAVE_SQL_MODE*/;

--
-- Table structure for table `employee_role_option`
--

DROP TABLE IF EXISTS `employee_role_option`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `employee_role_option` (
  `id` int(11) NOT NULL auto_increment,
  `uuid` char(36) default NULL,
  `role_phrase` varchar(128) NOT NULL,
  `modified_by` int(11) default NULL,
  `created_by` int(11) default NULL,
  `is_deleted` tinyint(1) NOT NULL default '0',
  `timestamp_updated` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `timestamp_created` timestamp NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `name_unique` (`role_phrase`),
  KEY `modified_by` (`modified_by`),
  KEY `created_by` (`created_by`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

/*!50003 SET @SAVE_SQL_MODE=@@SQL_MODE*/;

DELIMITER ;;
/*!50003 SET SESSION SQL_MODE="NO_AUTO_VALUE_ON_ZERO" */;;
/*!50003 CREATE */ /*!50017 DEFINER=`root`@`localhost` */ /*!50003 TRIGGER `employee_role_insert` BEFORE INSERT ON `employee_role_option` FOR EACH ROW BEGIN

  SET NEW.`uuid` = IFNULL(NEW.`uuid`,UUID());

END */;;

DELIMITER ;
/*!50003 SET SESSION SQL_MODE=@SAVE_SQL_MODE*/;

--
-- Table structure for table `employee_site_type_option`
--

DROP TABLE IF EXISTS `employee_site_type_option`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `employee_site_type_option` (
  `id` int(11) NOT NULL auto_increment,
  `uuid` char(36) default NULL,
  `site_type_phrase` varchar(128) NOT NULL,
  `modified_by` int(11) default NULL,
  `created_by` int(11) default NULL,
  `is_deleted` tinyint(1) NOT NULL default '0',
  `timestamp_updated` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `timestamp_created` timestamp NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`),
  KEY `modified_by` (`modified_by`),
  KEY `created_by` (`created_by`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

/*!50003 SET @SAVE_SQL_MODE=@@SQL_MODE*/;

DELIMITER ;;
/*!50003 SET SESSION SQL_MODE="" */;;
/*!50003 CREATE */ /*!50017 DEFINER=`root`@`localhost` */ /*!50003 TRIGGER `employee_site_type_insert` BEFORE INSERT ON `employee_site_type_option` FOR EACH ROW BEGIN

  SET NEW.`uuid` = IFNULL(NEW.`uuid`,UUID());

END */;;

DELIMITER ;
/*!50003 SET SESSION SQL_MODE=@SAVE_SQL_MODE*/;

--
-- Table structure for table `employee_to_course`
--

DROP TABLE IF EXISTS `employee_to_course`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `employee_to_course` (
  `id` int(11) NOT NULL auto_increment,
  `employee_id` varchar(64) NOT NULL,
  `course_name` varchar(64) default NULL,
  `provider` varchar(64) default NULL,
  `content` varchar(64) default NULL,
  `duration` varchar(64) default NULL,
  `nqf_level` varchar(64) default NULL,
  `certificate` varchar(64) default NULL,
  `accredited` varchar(64) default NULL,
  `created_by` int(11) default NULL,
  `timestamp_created` timestamp NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `employee_to_partner_to_subpartner_to_funder_to_mechanism`
--

DROP TABLE IF EXISTS `employee_to_partner_to_subpartner_to_funder_to_mechanism`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
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
) ENGINE=MyISAM AUTO_INCREMENT=2979 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `employee_to_referral`
--

DROP TABLE IF EXISTS `employee_to_referral`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `employee_to_referral` (
  `id` int(11) NOT NULL auto_increment,
  `employee_id` int(11) NOT NULL,
  `referral_option_id` int(11) NOT NULL,
  `created_by` int(11) default NULL,
  `timestamp_created` timestamp NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `employee_to_relationship`
--

DROP TABLE IF EXISTS `employee_to_relationship`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `employee_to_relationship` (
  `id` int(11) NOT NULL auto_increment,
  `employee_id` int(11) NOT NULL,
  `relationship_option_id` int(11) NOT NULL,
  `created_by` int(11) default NULL,
  `timestamp_created` timestamp NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `employee_to_role`
--

DROP TABLE IF EXISTS `employee_to_role`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `employee_to_role` (
  `id` int(11) NOT NULL auto_increment,
  `employee_id` int(11) NOT NULL,
  `employee_role_option_id` int(11) NOT NULL,
  `created_by` int(11) default NULL,
  `timestamp_created` timestamp NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `employee_training_provided_option`
--

DROP TABLE IF EXISTS `employee_training_provided_option`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `employee_training_provided_option` (
  `id` int(11) NOT NULL auto_increment,
  `uuid` char(36) default NULL,
  `training_provided_phrase` varchar(128) NOT NULL,
  `modified_by` int(11) default NULL,
  `created_by` int(11) default NULL,
  `is_deleted` tinyint(1) NOT NULL default '0',
  `timestamp_updated` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `timestamp_created` timestamp NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `name_unique` (`training_provided_phrase`),
  KEY `modified_by` (`modified_by`),
  KEY `created_by` (`created_by`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

/*!50003 SET @SAVE_SQL_MODE=@@SQL_MODE*/;

DELIMITER ;;
/*!50003 SET SESSION SQL_MODE="NO_AUTO_VALUE_ON_ZERO" */;;
/*!50003 CREATE */ /*!50017 DEFINER=`root`@`localhost` */ /*!50003 TRIGGER `employee_training_provided_insert` BEFORE INSERT ON `employee_training_provided_option` FOR EACH ROW BEGIN

  SET NEW.`uuid` = IFNULL(NEW.`uuid`,UUID());

END */;;

DELIMITER ;
/*!50003 SET SESSION SQL_MODE=@SAVE_SQL_MODE*/;

--
-- Table structure for table `employee_transition_option`
--

DROP TABLE IF EXISTS `employee_transition_option`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `employee_transition_option` (
  `id` int(11) NOT NULL auto_increment,
  `uuid` char(36) default NULL,
  `transition_phrase` varchar(128) NOT NULL,
  `modified_by` int(11) default NULL,
  `created_by` int(11) default NULL,
  `is_deleted` tinyint(1) NOT NULL default '0',
  `timestamp_updated` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `timestamp_created` timestamp NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `name_unique` (`transition_phrase`),
  KEY `modified_by` (`modified_by`),
  KEY `created_by` (`created_by`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

/*!50003 SET @SAVE_SQL_MODE=@@SQL_MODE*/;

DELIMITER ;;
/*!50003 SET SESSION SQL_MODE="NO_AUTO_VALUE_ON_ZERO" */;;
/*!50003 CREATE */ /*!50017 DEFINER=`root`@`localhost` */ /*!50003 TRIGGER `employee_transition_insert` BEFORE INSERT ON `employee_transition_option` FOR EACH ROW BEGIN

  SET NEW.`uuid` = IFNULL(NEW.`uuid`,UUID());

END */;;

DELIMITER ;
/*!50003 SET SESSION SQL_MODE=@SAVE_SQL_MODE*/;

--
-- Table structure for table `evaluation`
--

DROP TABLE IF EXISTS `evaluation`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `evaluation` (
  `id` int(11) NOT NULL auto_increment,
  `uuid` char(36) default NULL,
  `title` varchar(255) NOT NULL default '',
  `modified_by` int(11) default NULL,
  `created_by` int(11) default NULL,
  `is_deleted` tinyint(1) NOT NULL default '0',
  `timestamp_updated` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `timestamp_created` timestamp NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `uuid_idx` (`uuid`),
  KEY `modified_by` (`modified_by`),
  KEY `created_by` (`created_by`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

/*!50003 SET @SAVE_SQL_MODE=@@SQL_MODE*/;

DELIMITER ;;
/*!50003 SET SESSION SQL_MODE="NO_AUTO_VALUE_ON_ZERO" */;;
/*!50003 CREATE */ /*!50017 DEFINER=`root`@`localhost` */ /*!50003 TRIGGER `evaluation_insert` BEFORE INSERT ON `evaluation` FOR EACH ROW BEGIN

SET NEW.`uuid` = IFNULL(NEW.`uuid`,UUID());

END */;;

DELIMITER ;
/*!50003 SET SESSION SQL_MODE=@SAVE_SQL_MODE*/;

--
-- Table structure for table `evaluation_custom_answers`
--

DROP TABLE IF EXISTS `evaluation_custom_answers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `evaluation_custom_answers` (
  `id` int(11) NOT NULL auto_increment,
  `uuid` char(36) default NULL,
  `evaluation_id` int(11) NOT NULL,
  `question_id` int(11) NOT NULL,
  `answer_phrase` varchar(128) NOT NULL,
  `modified_by` int(11) default NULL,
  `created_by` int(11) default NULL,
  `is_deleted` tinyint(1) NOT NULL default '0',
  `timestamp_updated` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `timestamp_created` timestamp NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`),
  KEY `modified_by` (`modified_by`),
  KEY `created_by` (`created_by`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

/*!50003 SET @SAVE_SQL_MODE=@@SQL_MODE*/;

DELIMITER ;;
/*!50003 SET SESSION SQL_MODE="" */;;
/*!50003 CREATE */ /*!50017 DEFINER=`root`@`localhost` */ /*!50003 TRIGGER `evaluation_custom_answers_insert` BEFORE INSERT ON `evaluation_custom_answers` FOR EACH ROW BEGIN

  SET NEW.`uuid` = IFNULL(NEW.`uuid`,UUID());

END */;;

DELIMITER ;
/*!50003 SET SESSION SQL_MODE=@SAVE_SQL_MODE*/;

--
-- Table structure for table `evaluation_question`
--

DROP TABLE IF EXISTS `evaluation_question`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `evaluation_question` (
  `id` int(11) NOT NULL auto_increment,
  `uuid` char(36) default NULL,
  `evaluation_id` int(11) NOT NULL,
  `question_text` varchar(255) NOT NULL default '',
  `question_type` enum('Likert','Text','Likert3','Likert3NA','LikertNA') NOT NULL default 'Likert',
  `weight` int(11) default NULL,
  `modified_by` int(11) default NULL,
  `created_by` int(11) default NULL,
  `is_deleted` tinyint(1) NOT NULL default '0',
  `timestamp_updated` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `timestamp_created` timestamp NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `uuid_idx` (`uuid`),
  KEY `modified_by` (`modified_by`),
  KEY `created_by` (`created_by`),
  KEY `evaluation_id` (`evaluation_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

/*!50003 SET @SAVE_SQL_MODE=@@SQL_MODE*/;

DELIMITER ;;
/*!50003 SET SESSION SQL_MODE="NO_AUTO_VALUE_ON_ZERO" */;;
/*!50003 CREATE */ /*!50017 DEFINER=`root`@`localhost` */ /*!50003 TRIGGER `evaluation_question_insert` BEFORE INSERT ON `evaluation_question` FOR EACH ROW BEGIN

SET NEW.`uuid` = IFNULL(NEW.`uuid`,UUID());

END */;;

DELIMITER ;
/*!50003 SET SESSION SQL_MODE=@SAVE_SQL_MODE*/;

--
-- Table structure for table `evaluation_question_response`
--

DROP TABLE IF EXISTS `evaluation_question_response`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `evaluation_question_response` (
  `id` int(11) NOT NULL auto_increment,
  `uuid` char(36) default NULL,
  `evaluation_response_id` int(11) default NULL,
  `evaluation_question_id` int(11) NOT NULL,
  `value_text` varchar(1024) default '',
  `value_int` int(11) default NULL,
  `modified_by` int(11) default NULL,
  `created_by` int(11) default NULL,
  `is_deleted` tinyint(1) NOT NULL default '0',
  `timestamp_updated` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `timestamp_created` timestamp NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `uuid_idx` (`uuid`),
  KEY `modified_by` (`modified_by`),
  KEY `created_by` (`created_by`),
  KEY `evaluation_id` (`evaluation_response_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

/*!50003 SET @SAVE_SQL_MODE=@@SQL_MODE*/;

DELIMITER ;;
/*!50003 SET SESSION SQL_MODE="NO_AUTO_VALUE_ON_ZERO" */;;
/*!50003 CREATE */ /*!50017 DEFINER=`root`@`localhost` */ /*!50003 TRIGGER `evaluation_question_response_insert` BEFORE INSERT ON `evaluation_question_response` FOR EACH ROW BEGIN

SET NEW.`uuid` = IFNULL(NEW.`uuid`,UUID());

END */;;

DELIMITER ;
/*!50003 SET SESSION SQL_MODE=@SAVE_SQL_MODE*/;

--
-- Table structure for table `evaluation_response`
--

DROP TABLE IF EXISTS `evaluation_response`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `evaluation_response` (
  `id` int(11) NOT NULL auto_increment,
  `uuid` char(36) default NULL,
  `evaluation_to_training_id` int(11) NOT NULL,
  `person_id` int(11) default NULL,
  `trainer_person_id` int(11) default NULL,
  `modified_by` int(11) default NULL,
  `created_by` int(11) default NULL,
  `is_deleted` tinyint(1) NOT NULL default '0',
  `timestamp_updated` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `timestamp_created` timestamp NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `uuid_idx` (`uuid`),
  KEY `modified_by` (`modified_by`),
  KEY `created_by` (`created_by`),
  KEY `evaluation_to_training_id` (`evaluation_to_training_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

/*!50003 SET @SAVE_SQL_MODE=@@SQL_MODE*/;

DELIMITER ;;
/*!50003 SET SESSION SQL_MODE="NO_AUTO_VALUE_ON_ZERO" */;;
/*!50003 CREATE */ /*!50017 DEFINER=`root`@`localhost` */ /*!50003 TRIGGER `evaluation_response_insert` BEFORE INSERT ON `evaluation_response` FOR EACH ROW BEGIN

SET NEW.`uuid` = IFNULL(NEW.`uuid`,UUID());

END */;;

DELIMITER ;
/*!50003 SET SESSION SQL_MODE=@SAVE_SQL_MODE*/;

--
-- Table structure for table `evaluation_to_training`
--

DROP TABLE IF EXISTS `evaluation_to_training`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `evaluation_to_training` (
  `id` int(11) NOT NULL auto_increment,
  `uuid` char(36) default NULL,
  `evaluation_id` int(11) NOT NULL,
  `training_id` int(11) NOT NULL,
  `created_by` int(11) default NULL,
  `timestamp_created` timestamp NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `unique_idx` (`evaluation_id`,`training_id`),
  UNIQUE KEY `uuid_idx` (`uuid`),
  KEY `created_by` (`created_by`),
  KEY `training_id` (`training_id`),
  KEY `e_id` (`evaluation_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

/*!50003 SET @SAVE_SQL_MODE=@@SQL_MODE*/;

DELIMITER ;;
/*!50003 SET SESSION SQL_MODE="NO_AUTO_VALUE_ON_ZERO" */;;
/*!50003 CREATE */ /*!50017 DEFINER=`root`@`localhost` */ /*!50003 TRIGGER `evaluation_to_training_insert` BEFORE INSERT ON `evaluation_to_training` FOR EACH ROW BEGIN

SET NEW.`uuid` = IFNULL(NEW.`uuid`,UUID());

END */;;

DELIMITER ;
/*!50003 SET SESSION SQL_MODE=@SAVE_SQL_MODE*/;

--
-- Table structure for table `exams`
--

DROP TABLE IF EXISTS `exams`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `exams` (
  `id` int(10) unsigned NOT NULL default '0',
  `examname` varchar(150) collate utf8_unicode_ci NOT NULL default '',
  `examdate` varchar(20) collate utf8_unicode_ci NOT NULL default '',
  `examgrade` varchar(20) collate utf8_unicode_ci NOT NULL default '',
  `cohortid` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `idx2` (`cohortid`,`examgrade`,`examname`,`examdate`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `external_course`
--

DROP TABLE IF EXISTS `external_course`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `external_course` (
  `id` int(11) NOT NULL auto_increment,
  `uuid` char(36) default NULL,
  `person_id` int(11) unsigned NOT NULL,
  `title` varchar(128) NOT NULL,
  `training_location` varchar(128) default '',
  `training_start_date` date default NULL,
  `training_length_value` int(11) default NULL,
  `training_length_interval` enum('day') NOT NULL default 'day',
  `training_funder` varchar(128) default '',
  `is_deleted` tinyint(1) NOT NULL default '0',
  `timestamp_created` timestamp NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `uuid_idx` (`uuid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

/*!50003 SET @SAVE_SQL_MODE=@@SQL_MODE*/;

DELIMITER ;;
/*!50003 SET SESSION SQL_MODE="NO_AUTO_VALUE_ON_ZERO" */;;
/*!50003 CREATE */ /*!50017 DEFINER=`root`@`localhost` */ /*!50003 TRIGGER `external_course_insert` BEFORE INSERT ON `external_course` FOR EACH ROW BEGIN

SET NEW.`uuid` = IFNULL(NEW.`uuid`,UUID());

END */;;

DELIMITER ;
/*!50003 SET SESSION SQL_MODE=@SAVE_SQL_MODE*/;

--
-- Table structure for table `facility`
--

DROP TABLE IF EXISTS `facility`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `facility` (
  `id` int(11) NOT NULL auto_increment,
  `uuid` char(36) default NULL,
  `facility_name` varchar(128) NOT NULL default '',
  `lat` decimal(9,6) default NULL,
  `long` decimal(9,6) default NULL,
  `address_1` varchar(128) NOT NULL default '',
  `address_2` varchar(128) NOT NULL default '',
  `location_id` int(10) unsigned default NULL,
  `postal_code` varchar(20) default '',
  `phone` varchar(32) default '',
  `fax` varchar(32) default '',
  `sponsor_option_id` int(11) default NULL,
  `type_option_id` int(11) default NULL,
  `facility_comments` varchar(255) default '',
  `modified_by` int(11) default NULL,
  `created_by` int(11) default NULL,
  `is_deleted` tinyint(1) NOT NULL default '0',
  `timestamp_updated` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `timestamp_created` timestamp NOT NULL default '0000-00-00 00:00:00',
  `approved` tinyint(1) default NULL,
  `custom_1` varchar(255) default '',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `uuid_idx` (`uuid`),
  UNIQUE KEY `facility_name` (`facility_name`,`location_id`),
  KEY `created_by` (`created_by`),
  KEY `modified_by` (`modified_by`),
  KEY `sponsor_option_id` (`sponsor_option_id`),
  KEY `type_option_id` (`type_option_id`),
  KEY `facility_ibfk_5` (`location_id`)
) ENGINE=MyISAM AUTO_INCREMENT=6334 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

/*!50003 SET @SAVE_SQL_MODE=@@SQL_MODE*/;

DELIMITER ;;
/*!50003 SET SESSION SQL_MODE="NO_AUTO_VALUE_ON_ZERO" */;;
/*!50003 CREATE */ /*!50017 DEFINER=`root`@`localhost` */ /*!50003 TRIGGER `facility_insert` BEFORE INSERT ON `facility` FOR EACH ROW BEGIN

SET NEW.`uuid` = IFNULL(NEW.`uuid`,UUID());

END */;;

DELIMITER ;
/*!50003 SET SESSION SQL_MODE=@SAVE_SQL_MODE*/;

--
-- Table structure for table `facility_partner`
--

DROP TABLE IF EXISTS `facility_partner`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `facility_partner` (
  `id` int(11) NOT NULL auto_increment,
  `uuid` char(36) default NULL,
  `partner_name_us` varchar(64) NOT NULL default '',
  `partner_name_them` varchar(64) default '',
  `subpartner` varchar(64) default '',
  `mechanism_id` varchar(32) default '',
  `funder_name` varchar(64) default '',
  `funder_id` varchar(32) default '',
  `timestamp_created` timestamp NOT NULL default '0000-00-00 00:00:00',
  `modified_by` int(11) default NULL,
  `is_deleted` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `facility_sponsor_option`
--

DROP TABLE IF EXISTS `facility_sponsor_option`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `facility_sponsor_option` (
  `id` int(11) NOT NULL auto_increment,
  `uuid` char(36) default NULL,
  `facility_sponsor_phrase` varchar(128) NOT NULL default '',
  `modified_by` int(11) default NULL,
  `created_by` int(11) default NULL,
  `is_deleted` tinyint(1) NOT NULL default '0',
  `timestamp_updated` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `timestamp_created` timestamp NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `name_unique` (`facility_sponsor_phrase`),
  UNIQUE KEY `uuid_idx` (`uuid`),
  KEY `created_by` (`created_by`),
  KEY `modified_by` (`modified_by`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

/*!50003 SET @SAVE_SQL_MODE=@@SQL_MODE*/;

DELIMITER ;;
/*!50003 SET SESSION SQL_MODE="NO_AUTO_VALUE_ON_ZERO" */;;
/*!50003 CREATE */ /*!50017 DEFINER=`root`@`localhost` */ /*!50003 TRIGGER `facility_sponsor_option_insert` BEFORE INSERT ON `facility_sponsor_option` FOR EACH ROW BEGIN

SET NEW.`uuid` = IFNULL(NEW.`uuid`,UUID());

END */;;

DELIMITER ;
/*!50003 SET SESSION SQL_MODE=@SAVE_SQL_MODE*/;

--
-- Table structure for table `facility_sponsors`
--

DROP TABLE IF EXISTS `facility_sponsors`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `facility_sponsors` (
  `id` int(11) NOT NULL auto_increment,
  `uuid` char(36) default NULL,
  `facility_id` int(11) NOT NULL,
  `facility_sponsor_phrase_id` int(11) default NULL,
  `start_date` timestamp NOT NULL default '0000-00-00 00:00:00',
  `end_date` timestamp NOT NULL default '0000-00-00 00:00:00',
  `is_default` tinyint(1) unsigned NOT NULL default '0',
  `modified_by` int(11) default NULL,
  `created_by` int(11) default NULL,
  `is_deleted` tinyint(1) NOT NULL default '0',
  `timestamp_updated` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `timestamp_created` timestamp NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `uuid_idx` (`uuid`),
  KEY `created_by` (`created_by`),
  KEY `modified_by` (`modified_by`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

/*!50003 SET @SAVE_SQL_MODE=@@SQL_MODE*/;

DELIMITER ;;
/*!50003 SET SESSION SQL_MODE="NO_AUTO_VALUE_ON_ZERO" */;;
/*!50003 CREATE */ /*!50017 DEFINER=`root`@`localhost` */ /*!50003 TRIGGER `facility_sponsors_insert` BEFORE INSERT ON `facility_sponsors` FOR EACH ROW BEGIN

  SET NEW.`uuid` = IFNULL(NEW.`uuid`,UUID());

  END */;;

DELIMITER ;
/*!50003 SET SESSION SQL_MODE=@SAVE_SQL_MODE*/;

--
-- Table structure for table `facility_type_option`
--

DROP TABLE IF EXISTS `facility_type_option`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `facility_type_option` (
  `id` int(11) NOT NULL auto_increment,
  `uuid` char(36) default NULL,
  `facility_type_phrase` varchar(128) NOT NULL default '',
  `modified_by` int(11) default NULL,
  `created_by` int(11) default NULL,
  `is_deleted` tinyint(1) NOT NULL default '0',
  `timestamp_updated` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `timestamp_created` timestamp NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `name_unique` (`facility_type_phrase`),
  UNIQUE KEY `uuid_idx` (`uuid`),
  KEY `modified_by` (`modified_by`),
  KEY `created_by` (`created_by`)
) ENGINE=MyISAM AUTO_INCREMENT=31 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

/*!50003 SET @SAVE_SQL_MODE=@@SQL_MODE*/;

DELIMITER ;;
/*!50003 SET SESSION SQL_MODE="NO_AUTO_VALUE_ON_ZERO" */;;
/*!50003 CREATE */ /*!50017 DEFINER=`root`@`localhost` */ /*!50003 TRIGGER `facility_type_option_insert` BEFORE INSERT ON `facility_type_option` FOR EACH ROW BEGIN

SET NEW.`uuid` = IFNULL(NEW.`uuid`,UUID());

END */;;

DELIMITER ;
/*!50003 SET SESSION SQL_MODE=@SAVE_SQL_MODE*/;

--
-- Table structure for table `facs`
--

DROP TABLE IF EXISTS `facs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `facs` (
  `sno` bigint(20) NOT NULL auto_increment,
  `person` int(11) NOT NULL,
  `facility` int(11) NOT NULL,
  `facstring` varchar(1024) NOT NULL,
  `active` char(1) NOT NULL default 'N',
  PRIMARY KEY  (`sno`),
  KEY `person` (`person`,`facility`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `file`
--

DROP TABLE IF EXISTS `file`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `file` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `uuid` char(36) default NULL,
  `parent_id` int(10) unsigned NOT NULL,
  `parent_table` varchar(255) NOT NULL default '0',
  `filename` varchar(255) NOT NULL,
  `filemime` varchar(255) NOT NULL,
  `filesize` int(10) unsigned NOT NULL default '0',
  `modified_by` int(11) default NULL,
  `created_by` int(11) default NULL,
  `is_deleted` tinyint(1) NOT NULL default '0',
  `timestamp_updated` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `timestamp_created` timestamp NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `uuid_idx` (`uuid`),
  KEY `parent_key` (`parent_table`,`parent_id`)
) ENGINE=MyISAM AUTO_INCREMENT=31 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

/*!50003 SET @SAVE_SQL_MODE=@@SQL_MODE*/;

DELIMITER ;;
/*!50003 SET SESSION SQL_MODE="NO_AUTO_VALUE_ON_ZERO" */;;
/*!50003 CREATE */ /*!50017 DEFINER=`root`@`localhost` */ /*!50003 TRIGGER `file_insert` BEFORE INSERT ON `file` FOR EACH ROW BEGIN

SET NEW.`uuid` = IFNULL(NEW.`uuid`,UUID());

END */;;

DELIMITER ;
/*!50003 SET SESSION SQL_MODE=@SAVE_SQL_MODE*/;

--
-- Table structure for table `helper`
--

DROP TABLE IF EXISTS `helper`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `helper` (
  `id` int(10) unsigned NOT NULL auto_increment,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `hst`
--

DROP TABLE IF EXISTS `hst`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `hst` (
  `employeequalification` varchar(255) default NULL,
  `employeerole` varchar(255) default NULL,
  `dob` date default NULL,
  `race` varchar(255) default NULL,
  `gender` varchar(255) default NULL,
  `disability` varchar(255) default NULL,
  `disabilitycomments` varchar(255) default NULL,
  `partner` varchar(255) default NULL,
  `employeebased` varchar(255) default NULL,
  `location` varchar(255) default NULL,
  `salary` varchar(255) default NULL,
  `agreementenddate` date default NULL,
  `intendedtransition` varchar(255) default NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `institution`
--

DROP TABLE IF EXISTS `institution`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `institution` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `institutionname` varchar(150) NOT NULL default '',
  `address1` varchar(150) NOT NULL default '',
  `address2` varchar(150) NOT NULL default '',
  `city` varchar(150) NOT NULL default '',
  `postalcode` varchar(150) NOT NULL default '',
  `phone` varchar(50) NOT NULL default '',
  `fax` varchar(50) NOT NULL default '',
  `type` int(10) NOT NULL default '0',
  `sponsor` int(10) NOT NULL default '0',
  `degrees` varchar(255) NOT NULL default '',
  `degreetypeid` int(10) unsigned NOT NULL default '0',
  `computercount` int(10) unsigned NOT NULL default '0',
  `tutorcount` int(10) unsigned default '0',
  `studentcount` int(10) unsigned default '0',
  `dormcount` int(10) NOT NULL default '0',
  `bedcount` int(10) NOT NULL default '0',
  `hasdormitories` tinyint(3) unsigned NOT NULL default '0',
  `tutorhousing` int(10) unsigned NOT NULL default '0',
  `tutorhouses` int(10) NOT NULL default '0',
  `yearfounded` int(10) unsigned NOT NULL default '0',
  `comments` text NOT NULL,
  `customfield1` varchar(255) NOT NULL default '',
  `customfield2` varchar(255) NOT NULL default '',
  `geography1` int(10) unsigned NOT NULL default '0',
  `geography2` int(10) unsigned NOT NULL default '0',
  `geography3` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `idx2` (`degreetypeid`,`hasdormitories`,`computercount`,`studentcount`,`tutorcount`,`dormcount`,`tutorhousing`,`yearfounded`,`tutorhouses`,`bedcount`,`institutionname`,`geography1`,`geography2`,`geography3`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `jobtitles`
--

DROP TABLE IF EXISTS `jobtitles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jobtitles` (
  `Title` varchar(45) default NULL,
  `Option` varchar(12) default NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `licenses`
--

DROP TABLE IF EXISTS `licenses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `licenses` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `licensename` varchar(255) collate utf8_unicode_ci NOT NULL default '',
  `licensedate` varchar(20) collate utf8_unicode_ci NOT NULL default '',
  `cohortid` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `licensename` (`licensename`),
  KEY `licensedate` (`licensedate`),
  KEY `cohortid` (`cohortid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `link_cadre_institution`
--

DROP TABLE IF EXISTS `link_cadre_institution`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `link_cadre_institution` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `id_cadre` int(10) unsigned NOT NULL default '0',
  `id_institution` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `idx2` (`id_institution`,`id_cadre`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `link_cadre_tutor`
--

DROP TABLE IF EXISTS `link_cadre_tutor`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `link_cadre_tutor` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `id_cadre` int(10) unsigned NOT NULL default '0',
  `id_tutor` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `idx2` (`id_tutor`,`id_cadre`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `link_cohorts_classes`
--

DROP TABLE IF EXISTS `link_cohorts_classes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `link_cohorts_classes` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `cohortid` int(10) unsigned NOT NULL default '0',
  `classid` int(10) unsigned NOT NULL default '0',
  `status` tinyint(3) unsigned NOT NULL default '1',
  PRIMARY KEY  (`id`),
  KEY `idx2` (`cohortid`,`classid`,`status`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `link_facility_addresses`
--

DROP TABLE IF EXISTS `link_facility_addresses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `link_facility_addresses` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `id_facility` int(10) unsigned NOT NULL default '0',
  `id_address` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `idx2` (`id_facility`,`id_address`),
  KEY `FK_link_facility_addresses_addresses` (`id_address`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `link_institution_address`
--

DROP TABLE IF EXISTS `link_institution_address`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `link_institution_address` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `id_institution` int(10) unsigned NOT NULL default '0',
  `id_address` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `idx2` (`id_institution`,`id_address`),
  KEY `FK_link_institution_address_addresses` (`id_address`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `link_institution_degrees`
--

DROP TABLE IF EXISTS `link_institution_degrees`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `link_institution_degrees` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `id_institution` int(10) unsigned NOT NULL default '0',
  `id_degree` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `idx2` (`id_institution`,`id_degree`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `link_institution_institutiontype`
--

DROP TABLE IF EXISTS `link_institution_institutiontype`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `link_institution_institutiontype` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `id_institution` int(10) unsigned NOT NULL default '0',
  `id_institutiontype` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `idx2` (`id_institution`,`id_institutiontype`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `link_occupational_competencies`
--

DROP TABLE IF EXISTS `link_occupational_competencies`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `link_occupational_competencies` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `competencyid` int(10) unsigned NOT NULL default '0',
  `occupationalid` int(10) unsigned NOT NULL default '0',
  `status` tinyint(3) unsigned NOT NULL default '1',
  PRIMARY KEY  (`id`),
  KEY `idx2` (`competencyid`,`occupationalid`,`status`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `link_person_training`
--

DROP TABLE IF EXISTS `link_person_training`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `link_person_training` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `personid` int(10) unsigned NOT NULL default '0',
  `trainingid` int(10) unsigned NOT NULL default '0',
  `year` varchar(10) collate utf8_unicode_ci NOT NULL default '',
  `institution` varchar(150) collate utf8_unicode_ci NOT NULL default '',
  `othername` varchar(150) collate utf8_unicode_ci NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `idx2` (`personid`,`trainingid`,`year`),
  KEY `institution` (`institution`),
  KEY `othername` (`othername`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `link_qualification_competency`
--

DROP TABLE IF EXISTS `link_qualification_competency`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `link_qualification_competency` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `competencyid` int(10) unsigned NOT NULL default '0',
  `qualificationid` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `idx2` (`competencyid`,`qualificationid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `link_student_addresses`
--

DROP TABLE IF EXISTS `link_student_addresses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `link_student_addresses` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `id_student` int(10) unsigned NOT NULL default '0',
  `id_address` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `idx2` (`id_student`,`id_address`),
  KEY `FK_link_student_addresses_addresses` (`id_address`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `link_student_classes`
--

DROP TABLE IF EXISTS `link_student_classes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `link_student_classes` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `studentid` int(10) unsigned NOT NULL default '0',
  `classid` int(10) unsigned NOT NULL default '0',
  `cohortid` int(10) unsigned NOT NULL default '0',
  `linkclasscohortid` int(10) unsigned NOT NULL default '0',
  `grade` varchar(50) collate utf8_unicode_ci NOT NULL default '',
  `credits` varchar(50) collate utf8_unicode_ci default NULL,
  `exammark` varchar(50) collate utf8_unicode_ci NOT NULL default '',
  `camark` varchar(50) collate utf8_unicode_ci NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `idx2` (`studentid`,`classid`,`cohortid`,`linkclasscohortid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `link_student_cohort`
--

DROP TABLE IF EXISTS `link_student_cohort`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `link_student_cohort` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `id_student` int(10) unsigned NOT NULL default '0',
  `id_cohort` int(10) unsigned NOT NULL default '0',
  `joindate` date NOT NULL default '0000-00-00',
  `joinreason` int(10) unsigned NOT NULL default '0',
  `dropdate` date NOT NULL default '0000-00-00',
  `dropreason` int(10) unsigned NOT NULL default '0',
  `isgraduated` tinyint(3) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `FK_link_student_cohort_cohort` (`id_cohort`),
  KEY `datekeys` (`joindate`,`dropdate`),
  KEY `idx2` (`isgraduated`,`id_student`,`id_cohort`,`joinreason`,`dropreason`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `link_student_contacts`
--

DROP TABLE IF EXISTS `link_student_contacts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `link_student_contacts` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `id_student` int(10) unsigned NOT NULL default '0',
  `id_contact` int(10) unsigned NOT NULL default '0',
  `contactvalue` varchar(50) NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `idx2` (`id_student`,`id_contact`),
  KEY `FK_link_student_contacts_lookup_contacts` (`id_contact`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `link_student_facility`
--

DROP TABLE IF EXISTS `link_student_facility`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `link_student_facility` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `id_student` int(10) unsigned NOT NULL default '0',
  `id_facility` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `idx2` (`id_student`,`id_facility`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `link_student_funding`
--

DROP TABLE IF EXISTS `link_student_funding`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `link_student_funding` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `studentid` int(10) unsigned NOT NULL default '0',
  `fundingsource` int(10) unsigned NOT NULL default '0',
  `fundingamount` decimal(10,2) unsigned NOT NULL default '0.00',
  PRIMARY KEY  (`id`),
  KEY `idx2` (`studentid`,`fundingsource`,`fundingamount`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `link_student_institution`
--

DROP TABLE IF EXISTS `link_student_institution`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `link_student_institution` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `id_student` int(10) unsigned NOT NULL default '0',
  `id_institution` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `idx2` (`id_student`,`id_institution`),
  KEY `FK_link_student_institution_institution` (`id_institution`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `link_student_licenses`
--

DROP TABLE IF EXISTS `link_student_licenses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `link_student_licenses` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `studentid` int(10) unsigned NOT NULL default '0',
  `licenseid` int(10) unsigned NOT NULL default '0',
  `cohortid` int(10) unsigned NOT NULL default '0',
  `linkclasslicenseid` int(10) unsigned NOT NULL default '0',
  `grade` varchar(50) collate utf8_unicode_ci NOT NULL default '',
  `credits` varchar(50) collate utf8_unicode_ci default NULL,
  PRIMARY KEY  (`id`),
  KEY `idx2` (`studentid`,`licenseid`,`cohortid`,`linkclasslicenseid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `link_student_practicums`
--

DROP TABLE IF EXISTS `link_student_practicums`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `link_student_practicums` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `studentid` int(10) unsigned NOT NULL default '0',
  `practicumid` int(10) unsigned NOT NULL default '0',
  `cohortid` int(10) unsigned NOT NULL default '0',
  `linkcohortpracticumid` int(10) unsigned NOT NULL default '0',
  `hourscompleted` decimal(10,2) NOT NULL default '0.00',
  `grade` varchar(50) collate utf8_unicode_ci NOT NULL default '',
  `credits` varchar(50) collate utf8_unicode_ci default NULL,
  PRIMARY KEY  (`id`),
  KEY `idx2` (`studentid`,`practicumid`,`cohortid`,`linkcohortpracticumid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `link_tutor_addresses`
--

DROP TABLE IF EXISTS `link_tutor_addresses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `link_tutor_addresses` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `id_tutor` int(10) unsigned NOT NULL default '0',
  `id_address` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `idx2` (`id_tutor`,`id_address`),
  KEY `FK_link_tutor_addresses_addresses` (`id_address`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `link_tutor_contacts`
--

DROP TABLE IF EXISTS `link_tutor_contacts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `link_tutor_contacts` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `id_tutor` int(10) unsigned NOT NULL default '0',
  `id_contact` int(10) unsigned NOT NULL default '0',
  `contactvalue` varchar(50) NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `idx2` (`id_contact`,`id_tutor`),
  KEY `FK_link_tutor_contacts_tutor` (`id_tutor`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `link_tutor_institution`
--

DROP TABLE IF EXISTS `link_tutor_institution`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `link_tutor_institution` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `id_tutor` int(10) unsigned NOT NULL default '0',
  `id_institution` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `idx2` (`id_tutor`,`id_institution`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `link_tutor_languages`
--

DROP TABLE IF EXISTS `link_tutor_languages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `link_tutor_languages` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `id_tutor` int(10) unsigned NOT NULL default '0',
  `id_language` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `idx2` (`id_tutor`,`id_language`),
  KEY `FK_link_tutor_languages_lookup_languages` (`id_language`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `link_tutor_tutortype`
--

DROP TABLE IF EXISTS `link_tutor_tutortype`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `link_tutor_tutortype` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `id_tutor` int(10) unsigned NOT NULL default '0',
  `id_tutortype` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `idx2` (`id_tutor`,`id_tutortype`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `link_user_institution`
--

DROP TABLE IF EXISTS `link_user_institution`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `link_user_institution` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `userid` int(10) unsigned NOT NULL default '0',
  `institutionid` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `idx2` (`userid`,`institutionid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `load02112014`
--

DROP TABLE IF EXISTS `load02112014`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `load02112014` (
  `employeequalification` varchar(255) default NULL,
  `employeerole` varchar(255) default NULL,
  `title` varchar(255) default NULL,
  `partneremployeenumber` varchar(255) default NULL,
  `dob` date default NULL,
  `nationality` varchar(255) default NULL,
  `race` varchar(255) default NULL,
  `gender` varchar(255) default NULL,
  `disabilityoption` varchar(255) default NULL,
  `disabilitycomments` varchar(255) default NULL,
  `partner` varchar(255) default NULL,
  `employeebased` varchar(255) default NULL,
  `location` varchar(255) default NULL,
  `sitetype` varchar(255) default NULL,
  `hours` varchar(255) default NULL,
  `salary` varchar(255) default NULL,
  `benefiets` varchar(255) default NULL,
  `additional` varchar(255) default NULL,
  `stipend` varchar(255) default NULL,
  `annualcost` varchar(255) default NULL,
  `externalfundingpercentage` varchar(255) default NULL,
  `agreementenddate` date default NULL,
  `intendedtransition` varchar(255) default NULL,
  `transitionother` varchar(255) default NULL,
  `transitondate` date default NULL,
  `F27` varchar(255) default NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `load02112014_2`
--

DROP TABLE IF EXISTS `load02112014_2`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `load02112014_2` (
  `employeequalification` varchar(255) default NULL,
  `employeerole` varchar(255) default NULL,
  `firstname` varchar(255) default NULL,
  `lastname` varchar(255) default NULL,
  `partneremployeenumber` varchar(255) default NULL,
  `dob` date default NULL,
  `nationality` varchar(255) default NULL,
  `race` varchar(255) default NULL,
  `gender` varchar(255) default NULL,
  `partner` varchar(255) default NULL,
  `employeebased` varchar(255) default NULL,
  `location` varchar(255) default NULL,
  `hours` varchar(255) default NULL,
  `salary` varchar(255) default NULL,
  `agreementenddate` date default NULL,
  `intendedtransition` varchar(255) default NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `load02122014`
--

DROP TABLE IF EXISTS `load02122014`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `load02122014` (
  `employeequalification` varchar(255) default NULL,
  `employeerole` varchar(255) default NULL,
  `firstname` varchar(255) default NULL,
  `lastname` varchar(255) default NULL,
  `partneremployeenumber` varchar(255) default NULL,
  `otherid` varchar(255) default NULL,
  `dob` date default NULL,
  `nationality` varchar(255) default NULL,
  `race` varchar(255) default NULL,
  `gender` varchar(255) default NULL,
  `disability` varchar(255) default NULL,
  `disabilitycomments` varchar(255) default NULL,
  `partner` varchar(255) default NULL,
  `employeebased` varchar(255) default NULL,
  `location` varchar(255) default NULL,
  `sitename` varchar(255) default NULL,
  `sitetype` varchar(255) default NULL,
  `hours` varchar(255) default NULL,
  `salary` varchar(255) default NULL,
  `benefiets` varchar(255) default NULL,
  `additionalexpenses` varchar(255) default NULL,
  `stipend` varchar(255) default NULL,
  `annualcost` varchar(255) default NULL,
  `externalfundingpercentage` varchar(255) default NULL,
  `agreementenddate` date default NULL,
  `intendedtransition` varchar(255) default NULL,
  `transitionother` varchar(255) default NULL,
  `transitondate` date default NULL,
  `transitioncompleteother` varchar(255) default NULL,
  `actualtransitondate` date default NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `load02202014`
--

DROP TABLE IF EXISTS `load02202014`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `load02202014` (
  `employeequalification` varchar(255) default NULL,
  `employeerole` varchar(255) default NULL,
  `employeecode` varchar(255) default NULL,
  `partneremployeenumber` varchar(255) default NULL,
  `dob` date default NULL,
  `nationality` varchar(255) default NULL,
  `disability` varchar(255) default NULL,
  `race` varchar(255) default NULL,
  `gender` varchar(255) default NULL,
  `partner` varchar(255) default NULL,
  `employeebased` varchar(255) default NULL,
  `location` varchar(255) default NULL,
  `sitename` varchar(255) default NULL,
  `hours` varchar(255) default NULL,
  `salary` varchar(255) default NULL,
  `benefiets` varchar(255) default NULL,
  `additionalexpenses` varchar(255) default NULL,
  `stipend` varchar(255) default NULL,
  `annualcost` varchar(255) default NULL,
  `externalfundingpercentage` varchar(255) default NULL,
  `agreementenddate` date default NULL,
  `intendedtransition` varchar(255) default NULL,
  `transitionother` varchar(255) default NULL,
  `transitondate` date default NULL,
  `F26` varchar(255) default NULL,
  `F27` varchar(255) default NULL,
  `F28` varchar(255) default NULL,
  `F29` varchar(255) default NULL,
  `F30` varchar(255) default NULL,
  `F31` varchar(255) default NULL,
  `F32` varchar(255) default NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `location`
--

DROP TABLE IF EXISTS `location`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `location` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `uuid` char(36) default NULL,
  `location_name` varchar(128) NOT NULL,
  `parent_id` int(10) unsigned default NULL,
  `tier` smallint(5) unsigned NOT NULL,
  `is_default` tinyint(1) NOT NULL default '0',
  `modified_by` int(11) default NULL,
  `created_by` int(11) default NULL,
  `is_deleted` tinyint(1) NOT NULL default '0',
  `timestamp_updated` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `timestamp_created` timestamp NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `uuid_idx` (`uuid`),
  KEY `parent_id` (`parent_id`),
  KEY `modified_by` (`modified_by`),
  KEY `created_by` (`created_by`)
) ENGINE=MyISAM AUTO_INCREMENT=12730 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

/*!50003 SET @SAVE_SQL_MODE=@@SQL_MODE*/;

DELIMITER ;;
/*!50003 SET SESSION SQL_MODE="NO_AUTO_VALUE_ON_ZERO" */;;
/*!50003 CREATE */ /*!50017 DEFINER=`root`@`localhost` */ /*!50003 TRIGGER `location_insert` BEFORE INSERT ON `location` FOR EACH ROW BEGIN

SET NEW.`uuid` = IFNULL(NEW.`uuid`,UUID());

END */;;

DELIMITER ;
/*!50003 SET SESSION SQL_MODE=@SAVE_SQL_MODE*/;

--
-- Table structure for table `location_city`
--

DROP TABLE IF EXISTS `location_city`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `location_city` (
  `id` int(11) NOT NULL auto_increment,
  `city_name` varchar(128) NOT NULL default '',
  `uuid` char(38) default '',
  `parent_district_id` int(11) default NULL,
  `parent_province_id` int(11) default NULL,
  `modified_by` int(11) default NULL,
  `created_by` int(11) default NULL,
  `is_deleted` tinyint(1) NOT NULL default '0',
  `timestamp_updated` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `timestamp_created` timestamp NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`),
  KEY `modified_by` (`modified_by`),
  KEY `created_by` (`created_by`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `location_district`
--

DROP TABLE IF EXISTS `location_district`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `location_district` (
  `id` int(11) NOT NULL auto_increment,
  `district_name` varchar(128) NOT NULL default '',
  `is_default` tinyint(1) unsigned NOT NULL default '0',
  `uuid` char(38) NOT NULL default '',
  `parent_province_id` int(11) default NULL,
  `modified_by` int(11) default NULL,
  `created_by` int(11) default NULL,
  `is_deleted` tinyint(1) NOT NULL default '0',
  `timestamp_updated` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `timestamp_created` timestamp NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`),
  KEY `modified_by` (`modified_by`),
  KEY `created_by` (`created_by`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `location_province`
--

DROP TABLE IF EXISTS `location_province`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `location_province` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `province_name` varchar(255) NOT NULL default '',
  `is_default` tinyint(1) NOT NULL default '0',
  `uuid` char(38) NOT NULL default '',
  `modified_by` int(11) default NULL,
  `created_by` int(11) default NULL,
  `is_deleted` tinyint(1) NOT NULL default '0',
  `timestamp_updated` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `timestamp_created` timestamp NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`),
  KEY `modified_by` (`modified_by`),
  KEY `created_by` (`created_by`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `lookup_addresstype`
--

DROP TABLE IF EXISTS `lookup_addresstype`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `lookup_addresstype` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `addresstypename` varchar(50) NOT NULL default '',
  `addresstypeorder` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `idx2` (`addresstypename`,`addresstypeorder`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `lookup_contacts`
--

DROP TABLE IF EXISTS `lookup_contacts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `lookup_contacts` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `contactname` varchar(50) NOT NULL default '',
  `contactorder` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `idx2` (`contactorder`,`contactname`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `lookup_coursetype`
--

DROP TABLE IF EXISTS `lookup_coursetype`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `lookup_coursetype` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `coursetype` varchar(50) NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `idx2` (`coursetype`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `lookup_degrees`
--

DROP TABLE IF EXISTS `lookup_degrees`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `lookup_degrees` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `degree` varchar(255) collate utf8_unicode_ci NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `idx2` (`degree`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `lookup_degreetypes`
--

DROP TABLE IF EXISTS `lookup_degreetypes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `lookup_degreetypes` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `title` varchar(150) collate utf8_unicode_ci NOT NULL default '',
  `status` tinyint(3) unsigned NOT NULL default '1',
  PRIMARY KEY  (`id`),
  KEY `idx2` (`title`,`status`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `lookup_facilitytype`
--

DROP TABLE IF EXISTS `lookup_facilitytype`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `lookup_facilitytype` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `facilitytypename` varchar(50) NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `idx2` (`facilitytypename`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `lookup_fundingsources`
--

DROP TABLE IF EXISTS `lookup_fundingsources`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `lookup_fundingsources` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `fundingname` varchar(150) collate utf8_unicode_ci NOT NULL default '',
  `fundingnote` text collate utf8_unicode_ci NOT NULL,
  `status` tinyint(3) unsigned NOT NULL default '1',
  PRIMARY KEY  (`id`),
  KEY `idx2` (`status`,`fundingname`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `lookup_gender`
--

DROP TABLE IF EXISTS `lookup_gender`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `lookup_gender` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `gendername` varchar(50) NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `lookup_geog`
--

DROP TABLE IF EXISTS `lookup_geog`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `lookup_geog` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `geogname` varchar(50) NOT NULL default '',
  `geogparent` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `idx2` (`geogname`,`geogparent`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `lookup_institutiontype`
--

DROP TABLE IF EXISTS `lookup_institutiontype`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `lookup_institutiontype` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `typename` varchar(255) collate utf8_unicode_ci NOT NULL default '',
  `status` tinyint(3) unsigned NOT NULL default '1',
  PRIMARY KEY  (`id`),
  KEY `idx2` (`typename`,`status`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `lookup_languages`
--

DROP TABLE IF EXISTS `lookup_languages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `lookup_languages` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `language` varchar(150) NOT NULL default '',
  `abbreviation` varchar(50) NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `idx2` (`abbreviation`,`language`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `lookup_nationalities`
--

DROP TABLE IF EXISTS `lookup_nationalities`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `lookup_nationalities` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `nationality` varchar(50) collate utf8_unicode_ci NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `idx2` (`nationality`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `lookup_reasons`
--

DROP TABLE IF EXISTS `lookup_reasons`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `lookup_reasons` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `reason` text collate utf8_unicode_ci NOT NULL,
  `reasontype` enum('join','drop') collate utf8_unicode_ci NOT NULL default 'join',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `lookup_skillsmart`
--

DROP TABLE IF EXISTS `lookup_skillsmart`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `lookup_skillsmart` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `lookupgroup` varchar(150) collate utf8_unicode_ci NOT NULL default '',
  `lookupvalue` varchar(255) collate utf8_unicode_ci NOT NULL default '',
  `status` int(10) unsigned NOT NULL default '1',
  PRIMARY KEY  (`id`),
  KEY `idx2` (`status`),
  KEY `idx3` (`lookupgroup`),
  KEY `idx4` (`lookupvalue`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `lookup_sponsors`
--

DROP TABLE IF EXISTS `lookup_sponsors`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `lookup_sponsors` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `sponsorname` varchar(150) collate utf8_unicode_ci NOT NULL default '',
  `notes` text collate utf8_unicode_ci NOT NULL,
  `status` tinyint(3) unsigned NOT NULL default '1',
  PRIMARY KEY  (`id`),
  KEY `sponsorname` (`sponsorname`,`status`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `lookup_studenttype`
--

DROP TABLE IF EXISTS `lookup_studenttype`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `lookup_studenttype` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `studenttype` varchar(150) collate utf8_unicode_ci NOT NULL default '',
  `status` tinyint(3) unsigned NOT NULL default '1',
  PRIMARY KEY  (`id`),
  KEY `idx2` (`studenttype`,`status`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `lookup_tutortype`
--

DROP TABLE IF EXISTS `lookup_tutortype`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `lookup_tutortype` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `typename` varchar(150) collate utf8_unicode_ci NOT NULL default '',
  `status` tinyint(3) unsigned NOT NULL default '1',
  PRIMARY KEY  (`id`),
  KEY `typename` (`typename`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `mechanism_option`
--

DROP TABLE IF EXISTS `mechanism_option`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
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
) ENGINE=MyISAM AUTO_INCREMENT=116 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `occupational_categories`
--

DROP TABLE IF EXISTS `occupational_categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `occupational_categories` (
  `id` int(11) NOT NULL auto_increment,
  `uuid` char(36) default NULL,
  `parent_id` int(11) default NULL,
  `category_phrase` varchar(128) NOT NULL default '',
  `is_default` tinyint(1) unsigned NOT NULL default '0',
  `modified_by` int(11) default NULL,
  `created_by` int(11) default NULL,
  `is_deleted` tinyint(1) NOT NULL default '0',
  `timestamp_updated` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `timestamp_created` timestamp NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `name_unique` (`category_phrase`),
  UNIQUE KEY `uuid_idx` (`uuid`),
  KEY `modified_by` (`modified_by`),
  KEY `created_by` (`created_by`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `organizer_partners`
--

DROP TABLE IF EXISTS `organizer_partners`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `organizer_partners` (
  `id` int(11) NOT NULL auto_increment,
  `uuid` char(36) default NULL,
  `organizer_id` int(11) NOT NULL,
  `partner1_name` varchar(64) default '',
  `subpartner` varchar(64) default '',
  `mechanism_id` varchar(32) default '',
  `funder_name` varchar(64) default '',
  `funder_id` varchar(32) default '',
  `timestamp_created` timestamp NOT NULL default '0000-00-00 00:00:00',
  `modified_by` int(11) default NULL,
  `is_deleted` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `mechanism_id` (`mechanism_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

/*!50003 SET @SAVE_SQL_MODE=@@SQL_MODE*/;

DELIMITER ;;
/*!50003 SET SESSION SQL_MODE="NO_AUTO_VALUE_ON_ZERO" */;;
/*!50003 CREATE */ /*!50017 DEFINER=`root`@`localhost` */ /*!50003 TRIGGER `organizer_partners_insert` BEFORE INSERT ON `organizer_partners` FOR EACH ROW BEGIN

  SET NEW.`uuid` = IFNULL(NEW.`uuid`,UUID());

END */;;

DELIMITER ;
/*!50003 SET SESSION SQL_MODE=@SAVE_SQL_MODE*/;

--
-- Table structure for table `partner`
--

DROP TABLE IF EXISTS `partner`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `partner` (
  `id` int(11) NOT NULL auto_increment,
  `uuid` char(36) default NULL,
  `organizer_option_id` int(11) default NULL,
  `partner` varchar(100) default NULL,
  `partner_type_option_id` int(11) default NULL,
  `location_id` int(11) default NULL,
  `address1` varchar(128) default NULL,
  `address2` varchar(128) default NULL,
  `phone` varchar(64) default NULL,
  `fax` varchar(64) default NULL,
  `hr_contact_name` varchar(64) default NULL,
  `hr_contact_phone` varchar(40) default NULL,
  `hr_contact_fax` varchar(40) default NULL,
  `hr_contact_email` varchar(40) default NULL,
  `employee_transition_option_id` int(11) default NULL,
  `partner_importance_option_id` int(11) default NULL,
  `agreement_end_date` datetime default NULL,
  `transition_confirmed` tinyint(1) default NULL,
  `comments` text,
  `incoming_partner` int(11) default NULL,
  `created_by` int(11) default NULL,
  `timestamp_created` timestamp NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=385 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

/*!50003 SET @SAVE_SQL_MODE=@@SQL_MODE*/;

DELIMITER ;;
/*!50003 SET SESSION SQL_MODE="NO_AUTO_VALUE_ON_ZERO" */;;
/*!50003 CREATE */ /*!50017 DEFINER=`root`@`localhost` */ /*!50003 TRIGGER `partner_insert` BEFORE INSERT ON `partner` FOR EACH ROW BEGIN

  SET NEW.`uuid` = IFNULL(NEW.`uuid`,UUID());

END */;;

DELIMITER ;
/*!50003 SET SESSION SQL_MODE=@SAVE_SQL_MODE*/;

--
-- Table structure for table `partner_funder_option`
--

DROP TABLE IF EXISTS `partner_funder_option`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `partner_funder_option` (
  `id` int(11) NOT NULL auto_increment,
  `uuid` char(36) default NULL,
  `funder_phrase` varchar(128) NOT NULL,
  `modified_by` int(11) default NULL,
  `created_by` int(11) default NULL,
  `is_deleted` tinyint(1) NOT NULL default '0',
  `timestamp_updated` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `timestamp_created` timestamp NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `name_unique` (`funder_phrase`),
  KEY `modified_by` (`modified_by`),
  KEY `created_by` (`created_by`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

/*!50003 SET @SAVE_SQL_MODE=@@SQL_MODE*/;

DELIMITER ;;
/*!50003 SET SESSION SQL_MODE="NO_AUTO_VALUE_ON_ZERO" */;;
/*!50003 CREATE */ /*!50017 DEFINER=`root`@`localhost` */ /*!50003 TRIGGER `partner_funder_insert` BEFORE INSERT ON `partner_funder_option` FOR EACH ROW BEGIN

  SET NEW.`uuid` = IFNULL(NEW.`uuid`,UUID());

END */;;

DELIMITER ;
/*!50003 SET SESSION SQL_MODE=@SAVE_SQL_MODE*/;

--
-- Table structure for table `partner_importance_option`
--

DROP TABLE IF EXISTS `partner_importance_option`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `partner_importance_option` (
  `id` int(11) NOT NULL auto_increment,
  `uuid` char(36) default NULL,
  `importance_phrase` varchar(128) NOT NULL,
  `modified_by` int(11) default NULL,
  `created_by` int(11) default NULL,
  `is_deleted` tinyint(1) NOT NULL default '0',
  `timestamp_updated` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `timestamp_created` timestamp NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `name_unique` (`importance_phrase`),
  KEY `modified_by` (`modified_by`),
  KEY `created_by` (`created_by`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

/*!50003 SET @SAVE_SQL_MODE=@@SQL_MODE*/;

DELIMITER ;;
/*!50003 SET SESSION SQL_MODE="NO_AUTO_VALUE_ON_ZERO" */;;
/*!50003 CREATE */ /*!50017 DEFINER=`root`@`localhost` */ /*!50003 TRIGGER `partner_importance_insert` BEFORE INSERT ON `partner_importance_option` FOR EACH ROW BEGIN

  SET NEW.`uuid` = IFNULL(NEW.`uuid`,UUID());

END */;;

DELIMITER ;
/*!50003 SET SESSION SQL_MODE=@SAVE_SQL_MODE*/;

--
-- Table structure for table `partner_to_agency`
--

DROP TABLE IF EXISTS `partner_to_agency`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `partner_to_agency` (
  `id` int(11) NOT NULL auto_increment,
  `partner_id` int(11) NOT NULL,
  `agency_option_id` int(11) NOT NULL,
  `created_by` int(11) default NULL,
  `timestamp_created` timestamp NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `partner_to_funder`
--

DROP TABLE IF EXISTS `partner_to_funder`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `partner_to_funder` (
  `id` int(11) NOT NULL auto_increment,
  `partner_id` int(11) NOT NULL,
  `partner_funder_option_id` int(11) default NULL,
  `mechanism_option_id` int(11) default NULL,
  `funder_end_date` datetime default NULL,
  `created_by` int(11) default NULL,
  `timestamp_created` timestamp NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`),
  KEY `partner_id` (`partner_id`)
) ENGINE=MyISAM AUTO_INCREMENT=62 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `partner_to_subpartner`
--

DROP TABLE IF EXISTS `partner_to_subpartner`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `partner_to_subpartner` (
  `id` int(11) NOT NULL auto_increment,
  `partner_id` int(11) NOT NULL,
  `subpartner_id` int(11) NOT NULL,
  `created_by` int(11) default NULL,
  `timestamp_created` timestamp NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`),
  KEY `partner_id` (`partner_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `partner_to_subpartner_to_funder_to_mechanism`
--

DROP TABLE IF EXISTS `partner_to_subpartner_to_funder_to_mechanism`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
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
) ENGINE=MyISAM AUTO_INCREMENT=286 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `partner_type_option`
--

DROP TABLE IF EXISTS `partner_type_option`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `partner_type_option` (
  `id` int(11) NOT NULL auto_increment,
  `uuid` char(36) default NULL,
  `type_phrase` varchar(128) NOT NULL,
  `modified_by` int(11) default NULL,
  `created_by` int(11) default NULL,
  `is_deleted` tinyint(1) NOT NULL default '0',
  `timestamp_updated` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `timestamp_created` timestamp NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `name_unique` (`type_phrase`),
  KEY `modified_by` (`modified_by`),
  KEY `created_by` (`created_by`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

/*!50003 SET @SAVE_SQL_MODE=@@SQL_MODE*/;

DELIMITER ;;
/*!50003 SET SESSION SQL_MODE="" */;;
/*!50003 CREATE */ /*!50017 DEFINER=`root`@`localhost` */ /*!50003 TRIGGER `partner_type_insert` BEFORE INSERT ON `partner_type_option` FOR EACH ROW BEGIN

  SET NEW.`uuid` = IFNULL(NEW.`uuid`,UUID());

END */;;

DELIMITER ;
/*!50003 SET SESSION SQL_MODE=@SAVE_SQL_MODE*/;

--
-- Table structure for table `person`
--

DROP TABLE IF EXISTS `person`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `person` (
  `id` int(11) NOT NULL auto_increment,
  `uuid` char(36) default NULL,
  `title_option_id` int(11) default NULL,
  `first_name` varchar(32) NOT NULL default '',
  `middle_name` varchar(32) default '',
  `last_name` varchar(32) NOT NULL default '',
  `suffix_option_id` int(11) default NULL,
  `national_id` varchar(64) default '',
  `file_number` varchar(64) default NULL,
  `birthdate` date default NULL,
  `gender` enum('male','female','na') NOT NULL default 'na',
  `facility_id` int(11) NOT NULL,
  `phone_work` varchar(32) default '',
  `phone_mobile` varchar(32) default '',
  `phone_mobile_2` varchar(32) default NULL,
  `fax` varchar(32) default '',
  `phone_home` varchar(32) default '',
  `email` varchar(64) default '',
  `email_secondary` varchar(64) default '',
  `primary_qualification_option_id` int(11) NOT NULL,
  `primary_responsibility_option_id` int(11) default NULL,
  `secondary_responsibility_option_id` int(11) default NULL,
  `comments` varchar(255) default '',
  `person_custom_1_option_id` int(11) default NULL,
  `person_custom_2_option_id` int(11) default NULL,
  `home_address_1` varchar(128) default '',
  `home_address_2` varchar(128) default '',
  `home_location_id` int(10) unsigned default NULL,
  `home_postal_code` int(11) default NULL,
  `home_is_residential` tinyint(1) default NULL,
  `active` enum('active','inactive','deceased') NOT NULL default 'active',
  `timestamp_created` timestamp NOT NULL default '0000-00-00 00:00:00',
  `timestamp_updated` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `created_by` int(11) default NULL,
  `modified_by` int(11) default NULL,
  `is_deleted` tinyint(1) NOT NULL default '0',
  `highest_edu_level_option_id` int(11) default NULL,
  `attend_reason_option_id` int(11) default NULL,
  `attend_reason_other` varchar(255) default NULL,
  `me_responsibility` varchar(255) default NULL,
  `multi_facility_ids` varchar(255) default NULL,
  `home_city` varchar(255) default '',
  `highest_level_option_id` int(11) default NULL,
  `govemp_option_id` tinyint(4) default '0',
  `occupational_category_id` int(10) unsigned default NULL,
  `persal_number` int(10) unsigned default NULL,
  `bodies_id` int(10) unsigned default NULL,
  `race_option_id` int(10) unsigned default NULL,
  `disability_option_id` int(10) unsigned default NULL,
  `professional_reg_number` int(10) unsigned default NULL,
  `nationality_id` int(10) unsigned default NULL,
  `nurse_training_id` int(10) unsigned default NULL,
  `care_start_year` int(10) unsigned default NULL,
  `timespent_rank_pregnant` int(10) unsigned default NULL,
  `timespent_rank_adults` int(10) unsigned default NULL,
  `timespent_rank_children` int(10) unsigned default NULL,
  `timespent_rank_pregnant_pct` int(10) unsigned default NULL,
  `timespent_rank_adults_pct` int(10) unsigned default NULL,
  `timespent_rank_children_pct` int(10) unsigned default NULL,
  `supervised_id` int(10) unsigned default NULL,
  `supervision_frequency_id` int(10) unsigned default NULL,
  `supervisors_profession` varchar(255) default NULL,
  `training_recieved_data` text,
  `facilitydepartment_id` int(10) unsigned default NULL,
  `custom_3` varchar(255) default '',
  `custom_4` varchar(255) default '',
  `custom_5` varchar(255) default '',
  `approved` tinyint(1) default NULL,
  `custom_field1` varchar(255) default NULL,
  `custom_field2` varchar(255) default NULL,
  `custom_field3` varchar(255) default NULL,
  `marital_status` varchar(20) default NULL,
  `spouse_name` varchar(50) default NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `uuid_idx` (`uuid`),
  KEY `modified_by` (`modified_by`),
  KEY `created_by` (`created_by`),
  KEY `facility_id` (`facility_id`),
  KEY `home_location_ibfk_9` (`home_location_id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

/*!50003 SET @SAVE_SQL_MODE=@@SQL_MODE*/;

DELIMITER ;;
/*!50003 SET SESSION SQL_MODE="NO_AUTO_VALUE_ON_ZERO" */;;
/*!50003 CREATE */ /*!50017 DEFINER=`root`@`localhost` */ /*!50003 TRIGGER `person_insert` BEFORE INSERT ON `person` FOR EACH ROW BEGIN

SET NEW.`uuid` = IFNULL(NEW.`uuid`,UUID());

END */;;

DELIMITER ;
/*!50003 SET SESSION SQL_MODE=@SAVE_SQL_MODE*/;

--
-- Table structure for table `person_active_trainer_option`
--

DROP TABLE IF EXISTS `person_active_trainer_option`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `person_active_trainer_option` (
  `id` int(11) NOT NULL auto_increment,
  `uuid` char(36) default NULL,
  `active_trainer_phrase` varchar(128) NOT NULL default '',
  `modified_by` int(11) default NULL,
  `created_by` int(11) default NULL,
  `is_deleted` tinyint(1) NOT NULL default '0',
  `timestamp_updated` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `timestamp_created` timestamp NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `name_unique` (`active_trainer_phrase`),
  UNIQUE KEY `uuid_idx` (`uuid`),
  KEY `modified_by` (`modified_by`),
  KEY `created_by` (`created_by`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

/*!50003 SET @SAVE_SQL_MODE=@@SQL_MODE*/;

DELIMITER ;;
/*!50003 SET SESSION SQL_MODE="NO_AUTO_VALUE_ON_ZERO" */;;
/*!50003 CREATE */ /*!50017 DEFINER=`root`@`localhost` */ /*!50003 TRIGGER `person_active_trainer_option_insert` BEFORE INSERT ON `person_active_trainer_option` FOR EACH ROW BEGIN

SET NEW.`uuid` = IFNULL(NEW.`uuid`,UUID());

END */;;

DELIMITER ;
/*!50003 SET SESSION SQL_MODE=@SAVE_SQL_MODE*/;

--
-- Table structure for table `person_attend_reason_option`
--

DROP TABLE IF EXISTS `person_attend_reason_option`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `person_attend_reason_option` (
  `id` int(11) NOT NULL auto_increment,
  `uuid` char(36) default NULL,
  `attend_reason_phrase` varchar(128) NOT NULL default '',
  `modified_by` int(11) default NULL,
  `created_by` int(11) default NULL,
  `is_deleted` tinyint(1) NOT NULL default '0',
  `timestamp_updated` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `timestamp_created` timestamp NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `name_unique` (`attend_reason_phrase`),
  UNIQUE KEY `uuid_idx` (`uuid`),
  KEY `created_by` (`created_by`),
  KEY `modified_by` (`modified_by`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `person_custom_1_option`
--

DROP TABLE IF EXISTS `person_custom_1_option`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `person_custom_1_option` (
  `id` int(11) NOT NULL auto_increment,
  `uuid` char(36) default NULL,
  `custom1_phrase` varchar(128) NOT NULL default '',
  `modified_by` int(11) default NULL,
  `created_by` int(11) default NULL,
  `is_deleted` tinyint(1) NOT NULL default '0',
  `timestamp_updated` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `timestamp_created` timestamp NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `name_unique` (`custom1_phrase`),
  UNIQUE KEY `uuid_idx` (`uuid`),
  KEY `modified_by` (`modified_by`),
  KEY `created_by` (`created_by`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

/*!50003 SET @SAVE_SQL_MODE=@@SQL_MODE*/;

DELIMITER ;;
/*!50003 SET SESSION SQL_MODE="NO_AUTO_VALUE_ON_ZERO" */;;
/*!50003 CREATE */ /*!50017 DEFINER=`root`@`localhost` */ /*!50003 TRIGGER `person_custom_1_option_insert` BEFORE INSERT ON `person_custom_1_option` FOR EACH ROW BEGIN

SET NEW.`uuid` = IFNULL(NEW.`uuid`,UUID());

END */;;

DELIMITER ;
/*!50003 SET SESSION SQL_MODE=@SAVE_SQL_MODE*/;

--
-- Table structure for table `person_custom_2_option`
--

DROP TABLE IF EXISTS `person_custom_2_option`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `person_custom_2_option` (
  `id` int(11) NOT NULL auto_increment,
  `uuid` char(36) default NULL,
  `custom2_phrase` varchar(128) NOT NULL default '',
  `modified_by` int(11) default NULL,
  `created_by` int(11) default NULL,
  `is_deleted` tinyint(1) NOT NULL default '0',
  `timestamp_updated` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `timestamp_created` timestamp NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `name_unique` (`custom2_phrase`),
  UNIQUE KEY `uuid_idx` (`uuid`),
  KEY `created_by` (`created_by`),
  KEY `modified_by` (`modified_by`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

/*!50003 SET @SAVE_SQL_MODE=@@SQL_MODE*/;

DELIMITER ;;
/*!50003 SET SESSION SQL_MODE="NO_AUTO_VALUE_ON_ZERO" */;;
/*!50003 CREATE */ /*!50017 DEFINER=`root`@`localhost` */ /*!50003 TRIGGER `person_custom_2_option_insert` BEFORE INSERT ON `person_custom_2_option` FOR EACH ROW BEGIN

SET NEW.`uuid` = IFNULL(NEW.`uuid`,UUID());

END */;;

DELIMITER ;
/*!50003 SET SESSION SQL_MODE=@SAVE_SQL_MODE*/;

--
-- Table structure for table `person_education_level_option`
--

DROP TABLE IF EXISTS `person_education_level_option`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `person_education_level_option` (
  `id` int(11) NOT NULL auto_increment,
  `uuid` char(36) default NULL,
  `education_level_phrase` varchar(128) NOT NULL default '',
  `modified_by` int(11) default NULL,
  `created_by` int(11) default NULL,
  `is_deleted` tinyint(1) NOT NULL default '0',
  `timestamp_updated` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `timestamp_created` timestamp NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `name_unique` (`education_level_phrase`),
  UNIQUE KEY `uuid_idx` (`uuid`),
  KEY `created_by` (`created_by`),
  KEY `modified_by` (`modified_by`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `person_history`
--

DROP TABLE IF EXISTS `person_history`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `person_history` (
  `vid` int(11) NOT NULL auto_increment,
  `person_id` int(11) NOT NULL,
  `uuid` char(36) default NULL,
  `title_option_id` int(11) default NULL,
  `first_name` varchar(32) NOT NULL default '',
  `middle_name` varchar(32) default '',
  `last_name` varchar(32) NOT NULL default '',
  `suffix_option_id` int(11) default NULL,
  `national_id` varchar(64) default '',
  `file_number` varchar(64) default NULL,
  `birthdate` date default NULL,
  `gender` enum('male','female','na') NOT NULL default 'na',
  `facility_id` int(11) NOT NULL,
  `phone_work` varchar(32) default '',
  `phone_mobile` varchar(32) default '',
  `fax` varchar(32) default '',
  `phone_home` varchar(32) default '',
  `email` varchar(64) default '',
  `primary_qualification_option_id` int(11) NOT NULL,
  `primary_responsibility_option_id` int(11) default '0',
  `secondary_responsibility_option_id` int(11) default '0',
  `comments` varchar(255) default '',
  `person_custom_1_option_id` int(11) default NULL,
  `person_custom_2_option_id` int(11) default NULL,
  `active` enum('active','inactive','deceased') NOT NULL default 'active',
  `timestamp_created` timestamp NOT NULL default '0000-00-00 00:00:00',
  `modified_by` int(11) default NULL,
  `is_deleted` tinyint(1) NOT NULL default '0',
  `home_address_1` varchar(128) default '',
  `home_address_2` varchar(128) default '',
  `home_location_id` int(11) default NULL,
  `home_postal_code` int(11) default NULL,
  `email_secondary` varchar(64) default '',
  PRIMARY KEY  (`vid`),
  KEY `modified_by` (`modified_by`),
  KEY `facility_id` (`facility_id`),
  KEY `person_id` (`person_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `person_primary_responsibility_option`
--

DROP TABLE IF EXISTS `person_primary_responsibility_option`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `person_primary_responsibility_option` (
  `id` int(11) NOT NULL auto_increment,
  `uuid` char(36) default NULL,
  `responsibility_phrase` varchar(128) NOT NULL default '',
  `modified_by` int(11) default NULL,
  `created_by` int(11) default NULL,
  `is_deleted` tinyint(1) NOT NULL default '0',
  `timestamp_updated` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `timestamp_created` timestamp NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `name_unique` (`responsibility_phrase`),
  UNIQUE KEY `uuid_idx` (`uuid`),
  KEY `created_by` (`created_by`),
  KEY `modified_by` (`modified_by`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

/*!50003 SET @SAVE_SQL_MODE=@@SQL_MODE*/;

DELIMITER ;;
/*!50003 SET SESSION SQL_MODE="NO_AUTO_VALUE_ON_ZERO" */;;
/*!50003 CREATE */ /*!50017 DEFINER=`root`@`localhost` */ /*!50003 TRIGGER `person_responsibility_option_insert` BEFORE INSERT ON `person_primary_responsibility_option` FOR EACH ROW BEGIN

SET NEW.`uuid` = IFNULL(NEW.`uuid`,UUID());

END */;;

DELIMITER ;
/*!50003 SET SESSION SQL_MODE=@SAVE_SQL_MODE*/;

--
-- Table structure for table `person_qualification_option`
--

DROP TABLE IF EXISTS `person_qualification_option`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `person_qualification_option` (
  `id` int(11) NOT NULL auto_increment,
  `uuid` char(36) default NULL,
  `parent_id` int(11) default NULL,
  `qualification_phrase` varchar(128) NOT NULL default '',
  `is_default` tinyint(1) unsigned NOT NULL default '0',
  `modified_by` int(11) default NULL,
  `created_by` int(11) default NULL,
  `is_deleted` tinyint(1) NOT NULL default '0',
  `timestamp_updated` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `timestamp_created` timestamp NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `uuid_idx` (`uuid`),
  UNIQUE KEY `name_unique` (`qualification_phrase`,`parent_id`),
  KEY `modified_by` (`modified_by`),
  KEY `created_by` (`created_by`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

/*!50003 SET @SAVE_SQL_MODE=@@SQL_MODE*/;

DELIMITER ;;
/*!50003 SET SESSION SQL_MODE="NO_AUTO_VALUE_ON_ZERO" */;;
/*!50003 CREATE */ /*!50017 DEFINER=`root`@`localhost` */ /*!50003 TRIGGER `person_qualification_option_insert` BEFORE INSERT ON `person_qualification_option` FOR EACH ROW BEGIN

SET NEW.`uuid` = IFNULL(NEW.`uuid`,UUID());

END */;;

DELIMITER ;
/*!50003 SET SESSION SQL_MODE=@SAVE_SQL_MODE*/;

--
-- Table structure for table `person_race_option`
--

DROP TABLE IF EXISTS `person_race_option`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `person_race_option` (
  `id` int(11) NOT NULL auto_increment,
  `uuid` char(36) default NULL,
  `race_phrase` varchar(128) NOT NULL,
  `modified_by` int(11) default NULL,
  `created_by` int(11) default NULL,
  `is_deleted` tinyint(1) NOT NULL default '0',
  `timestamp_updated` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `timestamp_created` timestamp NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `name_unique` (`race_phrase`),
  UNIQUE KEY `uuid_idx` (`uuid`),
  KEY `modified_by` (`modified_by`),
  KEY `created_by` (`created_by`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

/*!50003 SET @SAVE_SQL_MODE=@@SQL_MODE*/;

DELIMITER ;;
/*!50003 SET SESSION SQL_MODE="" */;;
/*!50003 CREATE */ /*!50017 DEFINER=`root`@`localhost` */ /*!50003 TRIGGER `person_race_insert` BEFORE INSERT ON `person_race_option` FOR EACH ROW BEGIN

  SET NEW.`uuid` = IFNULL(NEW.`uuid`,UUID());

END */;;

DELIMITER ;
/*!50003 SET SESSION SQL_MODE=@SAVE_SQL_MODE*/;

--
-- Table structure for table `person_responsibility_option`
--

DROP TABLE IF EXISTS `person_responsibility_option`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `person_responsibility_option` (
  `id` int(11) NOT NULL auto_increment,
  `responsibility_phrase` varchar(128) NOT NULL default '',
  `modified_by` int(11) default NULL,
  `created_by` int(11) default NULL,
  `is_deleted` tinyint(1) NOT NULL default '0',
  `timestamp_updated` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `timestamp_created` timestamp NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `name_unique` (`responsibility_phrase`),
  KEY `created_by` (`created_by`),
  KEY `modified_by` (`modified_by`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `person_secondary_responsibility_option`
--

DROP TABLE IF EXISTS `person_secondary_responsibility_option`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `person_secondary_responsibility_option` (
  `id` int(11) NOT NULL auto_increment,
  `uuid` char(36) default NULL,
  `responsibility_phrase` varchar(128) NOT NULL,
  `modified_by` int(11) default NULL,
  `created_by` int(11) default NULL,
  `is_deleted` tinyint(1) NOT NULL default '0',
  `timestamp_updated` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `timestamp_created` timestamp NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `name_unique` (`responsibility_phrase`),
  UNIQUE KEY `uuid_idx` (`uuid`),
  KEY `created_by` (`created_by`),
  KEY `modified_by` (`modified_by`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `person_suffix_option`
--

DROP TABLE IF EXISTS `person_suffix_option`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `person_suffix_option` (
  `id` int(11) NOT NULL auto_increment,
  `uuid` char(36) default NULL,
  `suffix_phrase` varchar(128) NOT NULL default '',
  `modified_by` int(11) default NULL,
  `created_by` int(11) default NULL,
  `is_deleted` tinyint(1) NOT NULL default '0',
  `timestamp_updated` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `timestamp_created` timestamp NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `name_unique` (`suffix_phrase`),
  UNIQUE KEY `uuid_idx` (`uuid`),
  KEY `created_by` (`created_by`),
  KEY `modified_by` (`modified_by`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

/*!50003 SET @SAVE_SQL_MODE=@@SQL_MODE*/;

DELIMITER ;;
/*!50003 SET SESSION SQL_MODE="NO_AUTO_VALUE_ON_ZERO" */;;
/*!50003 CREATE */ /*!50017 DEFINER=`root`@`localhost` */ /*!50003 TRIGGER `person_suffix_option_insert` BEFORE INSERT ON `person_suffix_option` FOR EACH ROW BEGIN

SET NEW.`uuid` = IFNULL(NEW.`uuid`,UUID());

END */;;

DELIMITER ;
/*!50003 SET SESSION SQL_MODE=@SAVE_SQL_MODE*/;

--
-- Table structure for table `person_title_option`
--

DROP TABLE IF EXISTS `person_title_option`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `person_title_option` (
  `id` int(11) NOT NULL auto_increment,
  `uuid` char(36) default NULL,
  `title_phrase` varchar(128) NOT NULL default '',
  `modified_by` int(11) default NULL,
  `created_by` int(11) default NULL,
  `is_deleted` tinyint(1) NOT NULL default '0',
  `timestamp_updated` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `timestamp_created` timestamp NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `name_unique` (`title_phrase`),
  UNIQUE KEY `uuid_idx` (`uuid`),
  KEY `created_by` (`created_by`),
  KEY `modified_by` (`modified_by`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

/*!50003 SET @SAVE_SQL_MODE=@@SQL_MODE*/;

DELIMITER ;;
/*!50003 SET SESSION SQL_MODE="NO_AUTO_VALUE_ON_ZERO" */;;
/*!50003 CREATE */ /*!50017 DEFINER=`root`@`localhost` */ /*!50003 TRIGGER `person_title_option_insert` BEFORE INSERT ON `person_title_option` FOR EACH ROW BEGIN

SET NEW.`uuid` = IFNULL(NEW.`uuid`,UUID());

END */;;

DELIMITER ;
/*!50003 SET SESSION SQL_MODE=@SAVE_SQL_MODE*/;

--
-- Table structure for table `person_to_training`
--

DROP TABLE IF EXISTS `person_to_training`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `person_to_training` (
  `id` int(11) NOT NULL auto_increment,
  `uuid` char(36) default NULL,
  `person_id` int(11) NOT NULL,
  `training_id` int(11) NOT NULL,
  `created_by` int(11) default NULL,
  `timestamp_created` timestamp NOT NULL default '0000-00-00 00:00:00',
  `award_id` tinyint(1) NOT NULL default '0',
  `duration_days` float default NULL,
  `viewing_location_option_id` int(11) NOT NULL default '0',
  `budget_code_option_id` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `unique_idx` (`person_id`,`training_id`),
  UNIQUE KEY `training_person_uniq` (`training_id`,`person_id`),
  UNIQUE KEY `uuid_idx` (`uuid`),
  KEY `created_by` (`created_by`),
  KEY `person_id` (`person_id`),
  KEY `training_id` (`training_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

/*!50003 SET @SAVE_SQL_MODE=@@SQL_MODE*/;

DELIMITER ;;
/*!50003 SET SESSION SQL_MODE="NO_AUTO_VALUE_ON_ZERO" */;;
/*!50003 CREATE */ /*!50017 DEFINER=`root`@`localhost` */ /*!50003 TRIGGER `person_to_training_insert` BEFORE INSERT ON `person_to_training` FOR EACH ROW BEGIN

SET NEW.`uuid` = IFNULL(NEW.`uuid`,UUID());

END */;;

DELIMITER ;
/*!50003 SET SESSION SQL_MODE=@SAVE_SQL_MODE*/;

--
-- Table structure for table `person_to_training_budget_option`
--

DROP TABLE IF EXISTS `person_to_training_budget_option`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `person_to_training_budget_option` (
  `id` int(11) NOT NULL auto_increment,
  `uuid` char(36) default NULL,
  `budget_code_phrase` varchar(128) NOT NULL,
  `modified_by` int(11) default NULL,
  `created_by` int(11) default NULL,
  `is_deleted` tinyint(1) NOT NULL default '0',
  `timestamp_updated` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `timestamp_created` timestamp NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `name_unique` (`budget_code_phrase`),
  UNIQUE KEY `uuid_idx` (`uuid`),
  KEY `modified_by` (`modified_by`),
  KEY `created_by` (`created_by`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;
/*!40101 SET character_set_client = @saved_cs_client */;

/*!50003 SET @SAVE_SQL_MODE=@@SQL_MODE*/;

DELIMITER ;;
/*!50003 SET SESSION SQL_MODE="NO_AUTO_VALUE_ON_ZERO" */;;
/*!50003 CREATE */ /*!50017 DEFINER=`root`@`localhost` */ /*!50003 TRIGGER `person_to_training_budget_insert` BEFORE INSERT ON `person_to_training_budget_option` FOR EACH ROW BEGIN

  SET NEW.`uuid` = IFNULL(NEW.`uuid`,UUID());

END */;;

DELIMITER ;
/*!50003 SET SESSION SQL_MODE=@SAVE_SQL_MODE*/;

--
-- Table structure for table `person_to_training_topic_option`
--

DROP TABLE IF EXISTS `person_to_training_topic_option`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `person_to_training_topic_option` (
  `id` int(11) NOT NULL auto_increment,
  `uuid` char(36) default NULL,
  `person_id` int(11) NOT NULL,
  `training_topic_option_id` int(11) NOT NULL,
  `created_by` int(11) default NULL,
  `timestamp_created` timestamp NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `unique_idx` (`person_id`,`training_topic_option_id`),
  UNIQUE KEY `uuid_idx` (`uuid`),
  KEY `created_by` (`created_by`),
  KEY `person_id` (`person_id`),
  KEY `training_topic_option_id` (`training_topic_option_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

/*!50003 SET @SAVE_SQL_MODE=@@SQL_MODE*/;

DELIMITER ;;
/*!50003 SET SESSION SQL_MODE="NO_AUTO_VALUE_ON_ZERO" */;;
/*!50003 CREATE */ /*!50017 DEFINER=`root`@`localhost` */ /*!50003 TRIGGER `person_to_training_topic_option_insert` BEFORE INSERT ON `person_to_training_topic_option` FOR EACH ROW BEGIN

SET NEW.`uuid` = IFNULL(NEW.`uuid`,UUID());

END */;;

DELIMITER ;
/*!50003 SET SESSION SQL_MODE=@SAVE_SQL_MODE*/;

--
-- Table structure for table `person_to_training_viewing_loc_option`
--

DROP TABLE IF EXISTS `person_to_training_viewing_loc_option`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `person_to_training_viewing_loc_option` (
  `id` int(11) NOT NULL auto_increment,
  `uuid` char(36) default NULL,
  `location_phrase` varchar(128) NOT NULL,
  `modified_by` int(11) default NULL,
  `created_by` int(11) default NULL,
  `is_deleted` tinyint(1) NOT NULL default '0',
  `timestamp_updated` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `timestamp_created` timestamp NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `name_unique` (`location_phrase`),
  UNIQUE KEY `uuid_idx` (`uuid`),
  KEY `modified_by` (`modified_by`),
  KEY `created_by` (`created_by`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;
/*!40101 SET character_set_client = @saved_cs_client */;

/*!50003 SET @SAVE_SQL_MODE=@@SQL_MODE*/;

DELIMITER ;;
/*!50003 SET SESSION SQL_MODE="NO_AUTO_VALUE_ON_ZERO" */;;
/*!50003 CREATE */ /*!50017 DEFINER=`root`@`localhost` */ /*!50003 TRIGGER `person_to_training_viewing_loc_insert` BEFORE INSERT ON `person_to_training_viewing_loc_option` FOR EACH ROW BEGIN

  SET NEW.`uuid` = IFNULL(NEW.`uuid`,UUID());

END */;;

DELIMITER ;
/*!50003 SET SESSION SQL_MODE=@SAVE_SQL_MODE*/;

--
-- Table structure for table `practicum`
--

DROP TABLE IF EXISTS `practicum`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `practicum` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `practicumname` varchar(150) collate utf8_unicode_ci NOT NULL default '',
  `practicumdate` varchar(10) collate utf8_unicode_ci NOT NULL default '',
  `facilityid` int(10) unsigned NOT NULL default '0',
  `advisorid` int(10) unsigned NOT NULL default '0',
  `hoursrequired` decimal(10,2) unsigned NOT NULL default '0.00',
  `cohortid` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `textindex` (`practicumname`,`practicumdate`),
  KEY `numberindex` (`advisorid`,`hoursrequired`,`cohortid`,`facilityid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `score`
--

DROP TABLE IF EXISTS `score`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `score` (
  `id` int(11) NOT NULL auto_increment,
  `uuid` char(36) default NULL,
  `person_to_training_id` int(11) NOT NULL,
  `training_date` date NOT NULL,
  `score_value` int(11) NOT NULL,
  `score_label` varchar(255) NOT NULL,
  `modified_by` int(11) default NULL,
  `created_by` int(11) default NULL,
  `is_deleted` tinyint(1) NOT NULL default '0',
  `timestamp_updated` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `timestamp_created` timestamp NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `uuid_idx` (`uuid`),
  KEY `scorelabel` (`score_label`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

/*!50003 SET @SAVE_SQL_MODE=@@SQL_MODE*/;

DELIMITER ;;
/*!50003 SET SESSION SQL_MODE="NO_AUTO_VALUE_ON_ZERO" */;;
/*!50003 CREATE */ /*!50017 DEFINER=`root`@`localhost` */ /*!50003 TRIGGER `score_insert` BEFORE INSERT ON `score` FOR EACH ROW BEGIN

SET NEW.`uuid` = IFNULL(NEW.`uuid`,UUID());

END */;;

DELIMITER ;
/*!50003 SET SESSION SQL_MODE=@SAVE_SQL_MODE*/;

--
-- Table structure for table `security`
--

DROP TABLE IF EXISTS `security`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `security` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `studentid` int(10) unsigned NOT NULL default '0',
  `haspreservice` tinyint(3) unsigned NOT NULL default '0',
  `hasinstitution` tinyint(3) unsigned NOT NULL default '0',
  `hasinservice` tinyint(3) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `idx2` (`hasinservice`,`hasinstitution`,`haspreservice`,`studentid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `student`
--

DROP TABLE IF EXISTS `student`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `student` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `personid` int(10) unsigned NOT NULL default '0',
  `nationalityid` int(10) unsigned NOT NULL default '0',
  `cadre` int(25) NOT NULL default '0',
  `yearofstudy` int(10) unsigned NOT NULL default '0',
  `comments` varchar(255) NOT NULL default '',
  `geog1` int(10) unsigned NOT NULL default '0',
  `geog2` int(10) unsigned NOT NULL default '0',
  `geog3` int(10) unsigned NOT NULL default '0',
  `isgraduated` tinyint(3) unsigned NOT NULL default '0',
  `studentid` varchar(50) default '',
  `studenttype` int(10) unsigned NOT NULL default '0',
  `advisorid` int(10) unsigned NOT NULL default '0',
  `postgeo1` varchar(50) NOT NULL default '0',
  `postgeo2` varchar(50) NOT NULL default '0',
  `postgeo3` varchar(50) NOT NULL default '0',
  `postaddress1` varchar(255) NOT NULL default '',
  `postfacilityname` varchar(255) NOT NULL default '',
  `institutionid` int(10) unsigned default '0',
  `hscomldate` date NOT NULL,
  `lastinstatt` varchar(50) NOT NULL,
  `schoolstartdate` date NOT NULL,
  `equivalence` tinyint(4) NOT NULL default '0',
  `lastunivatt` varchar(50) NOT NULL,
  `personincharge` varchar(50) NOT NULL,
  `emergcontact` varchar(50) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `idx2` (`nationalityid`,`advisorid`,`personid`,`studentid`,`cadre`,`geog1`,`geog2`,`geog3`,`isgraduated`,`postgeo1`,`postgeo2`,`postgeo3`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `subpartner_to_funder_to_mechanism`
--

DROP TABLE IF EXISTS `subpartner_to_funder_to_mechanism`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
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
) ENGINE=MyISAM AUTO_INCREMENT=216 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `syncalias`
--

DROP TABLE IF EXISTS `syncalias`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `syncalias` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `syncfile_id` int(11) unsigned default NULL,
  `item_type` varchar(255) default NULL,
  `left_id` int(11) unsigned NOT NULL,
  `left_uuid` char(36) NOT NULL default '',
  `right_id` int(11) unsigned NOT NULL,
  `right_uuid` char(36) NOT NULL default '',
  `created_by` int(11) default NULL,
  `timestamp_created` timestamp NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`),
  KEY `syncfile_id` (`syncfile_id`),
  KEY `syncfile_id_2` (`syncfile_id`),
  KEY `syncfile_id_3` (`syncfile_id`),
  KEY `syncfile_id_4` (`syncfile_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `syncfile`
--

DROP TABLE IF EXISTS `syncfile`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `syncfile` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `filename` varchar(255) default NULL,
  `filepath` varchar(255) NOT NULL,
  `application_id` char(36) NOT NULL,
  `application_version` float(255,2) NOT NULL,
  `modified_by` int(11) default NULL,
  `created_by` int(11) default NULL,
  `timestamp_updated` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `timestamp_created` timestamp NOT NULL default '0000-00-00 00:00:00',
  `timestamp_last_sync` timestamp NOT NULL default '0000-00-00 00:00:00',
  `timestamp_completed` timestamp NULL default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `synclog`
--

DROP TABLE IF EXISTS `synclog`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `synclog` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `fid` int(11) default NULL,
  `item_type` varchar(255) default NULL,
  `left_id` int(11) default NULL,
  `left_data` mediumtext,
  `right_id` int(11) default NULL,
  `right_data` mediumtext,
  `action` varchar(36) default NULL,
  `is_skipped` tinyint(1) unsigned default '0',
  `message` mediumtext,
  `modified_by` int(11) default NULL,
  `created_by` int(11) default NULL,
  `timestamp_updated` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `timestamp_created` timestamp NOT NULL default '0000-00-00 00:00:00',
  `timestamp_completed` timestamp NULL default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `toload02242014`
--

DROP TABLE IF EXISTS `toload02242014`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `toload02242014` (
  `employeequalification` varchar(255) default NULL,
  `employeerole` varchar(255) default NULL,
  `employeecode` varchar(255) default NULL,
  `partneremployeenumber` varchar(255) default NULL,
  `dob` date default NULL,
  `nationality` varchar(255) default NULL,
  `disability` varchar(255) default NULL,
  `partner` varchar(255) default NULL,
  `employeebased` varchar(255) default NULL,
  `location` varchar(255) default NULL,
  `hours` varchar(255) default NULL,
  `salary` varchar(255) default NULL,
  `benefiets` varchar(255) default NULL,
  `additionalexpenses` varchar(255) default NULL,
  `stipend` varchar(255) default NULL,
  `externalfundingpercentage` varchar(255) default NULL,
  `annualcost` varchar(255) default NULL,
  `agreementenddate` date default NULL,
  `intendedtransition` varchar(255) default NULL,
  `transitondate` date default NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `toload02272014`
--

DROP TABLE IF EXISTS `toload02272014`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `toload02272014` (
  `employeequalification` varchar(255) default NULL,
  `employeerole` varchar(255) default NULL,
  `hours` varchar(255) default NULL,
  `salary` varchar(255) default NULL,
  `benefiets` varchar(255) default NULL,
  `annualcost` varchar(255) default NULL,
  `agreementenddate` date default NULL,
  `intendedtransition` varchar(255) default NULL,
  `transitioncompleteother` varchar(255) default NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `toload04112014`
--

DROP TABLE IF EXISTS `toload04112014`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `toload04112014` (
  `employeequalification` varchar(255) default NULL,
  `employeerole` varchar(255) default NULL,
  `firstname` varchar(255) default NULL,
  `lastname` varchar(255) default NULL,
  `partneremployeenumber` varchar(255) default NULL,
  `otherid` varchar(255) default NULL,
  `dob` date default NULL,
  `disability` varchar(255) default NULL,
  `partner` varchar(255) default NULL,
  `employeebased` varchar(255) default NULL,
  `location` varchar(255) default NULL,
  `hours` varchar(255) default NULL,
  `salary` varchar(255) default NULL,
  `agreementenddate` date default NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tracking`
--

DROP TABLE IF EXISTS `tracking`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tracking` (
  `UID` int(11) NOT NULL,
  `URL` text NOT NULL,
  `On` timestamp NOT NULL default CURRENT_TIMESTAMP,
  KEY `IDX` (`UID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `trainer`
--

DROP TABLE IF EXISTS `trainer`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `trainer` (
  `person_id` int(11) NOT NULL,
  `uuid` char(36) default NULL,
  `type_option_id` int(11) NOT NULL default '0',
  `active_trainer_option_id` int(11) default NULL,
  `affiliation_option_id` int(11) NOT NULL,
  `is_active` tinyint(1) NOT NULL default '1',
  `modified_by` int(11) default NULL,
  `created_by` int(11) default NULL,
  `is_deleted` tinyint(1) NOT NULL default '0',
  `timestamp_updated` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `timestamp_created` timestamp NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`person_id`),
  UNIQUE KEY `person_idx` (`person_id`),
  UNIQUE KEY `uuid_idx` (`uuid`),
  KEY `modified_by` (`modified_by`),
  KEY `created_by` (`created_by`),
  KEY `type_option_id` (`type_option_id`),
  KEY `affiliation_option_id` (`affiliation_option_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

/*!50003 SET @SAVE_SQL_MODE=@@SQL_MODE*/;

DELIMITER ;;
/*!50003 SET SESSION SQL_MODE="NO_AUTO_VALUE_ON_ZERO" */;;
/*!50003 CREATE */ /*!50017 DEFINER=`root`@`localhost` */ /*!50003 TRIGGER `trainer_insert` BEFORE INSERT ON `trainer` FOR EACH ROW BEGIN

SET NEW.`uuid` = IFNULL(NEW.`uuid`,UUID());

END */;;

DELIMITER ;
/*!50003 SET SESSION SQL_MODE=@SAVE_SQL_MODE*/;

--
-- Table structure for table `trainer_affiliation_option`
--

DROP TABLE IF EXISTS `trainer_affiliation_option`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `trainer_affiliation_option` (
  `id` int(11) NOT NULL auto_increment,
  `uuid` char(36) default NULL,
  `trainer_affiliation_phrase` varchar(128) NOT NULL default '',
  `modified_by` int(11) default NULL,
  `created_by` int(11) default NULL,
  `is_deleted` tinyint(1) NOT NULL default '0',
  `timestamp_updated` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `timestamp_created` timestamp NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `name_unique` (`trainer_affiliation_phrase`),
  UNIQUE KEY `uuid_idx` (`uuid`),
  KEY `created_by` (`created_by`),
  KEY `modified_by` (`modified_by`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

/*!50003 SET @SAVE_SQL_MODE=@@SQL_MODE*/;

DELIMITER ;;
/*!50003 SET SESSION SQL_MODE="NO_AUTO_VALUE_ON_ZERO" */;;
/*!50003 CREATE */ /*!50017 DEFINER=`root`@`localhost` */ /*!50003 TRIGGER `trainer_affiliation_option_insert` BEFORE INSERT ON `trainer_affiliation_option` FOR EACH ROW BEGIN

SET NEW.`uuid` = IFNULL(NEW.`uuid`,UUID());

END */;;

DELIMITER ;
/*!50003 SET SESSION SQL_MODE=@SAVE_SQL_MODE*/;

--
-- Table structure for table `trainer_history`
--

DROP TABLE IF EXISTS `trainer_history`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `trainer_history` (
  `vid` int(11) NOT NULL auto_increment,
  `pvid` int(11) NOT NULL,
  `person_id` int(11) NOT NULL,
  `uuid` char(36) default NULL,
  `type_option_id` int(11) NOT NULL default '0',
  `active_trainer_option_id` int(11) default NULL,
  `affiliation_option_id` int(11) NOT NULL,
  `is_active` tinyint(1) NOT NULL default '1',
  `modified_by` int(11) default NULL,
  `created_by` int(11) default NULL,
  `is_deleted` tinyint(1) NOT NULL default '0',
  `timestamp_updated` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `timestamp_created` timestamp NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`vid`),
  KEY `type_option_id` (`type_option_id`),
  KEY `affiliation_option_id` (`affiliation_option_id`),
  KEY `trainer_history_ibfk_1` (`person_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `trainer_language_option`
--

DROP TABLE IF EXISTS `trainer_language_option`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `trainer_language_option` (
  `id` int(11) NOT NULL auto_increment,
  `uuid` char(36) default NULL,
  `language_phrase` varchar(128) NOT NULL default '',
  `modified_by` int(11) default NULL,
  `created_by` int(11) default NULL,
  `is_deleted` tinyint(1) NOT NULL default '0',
  `timestamp_updated` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `timestamp_created` timestamp NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `name_unique` (`language_phrase`),
  UNIQUE KEY `uuid_idx` (`uuid`),
  KEY `modified_by` (`modified_by`),
  KEY `created_by` (`created_by`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

/*!50003 SET @SAVE_SQL_MODE=@@SQL_MODE*/;

DELIMITER ;;
/*!50003 SET SESSION SQL_MODE="NO_AUTO_VALUE_ON_ZERO" */;;
/*!50003 CREATE */ /*!50017 DEFINER=`root`@`localhost` */ /*!50003 TRIGGER `trainer_language_option_insert` BEFORE INSERT ON `trainer_language_option` FOR EACH ROW BEGIN

SET NEW.`uuid` = IFNULL(NEW.`uuid`,UUID());

END */;;

DELIMITER ;
/*!50003 SET SESSION SQL_MODE=@SAVE_SQL_MODE*/;

--
-- Table structure for table `trainer_skill_option`
--

DROP TABLE IF EXISTS `trainer_skill_option`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `trainer_skill_option` (
  `id` int(11) NOT NULL auto_increment,
  `uuid` char(36) default NULL,
  `trainer_skill_phrase` varchar(128) NOT NULL default '',
  `modified_by` int(11) default NULL,
  `created_by` int(11) default NULL,
  `is_deleted` tinyint(1) NOT NULL default '0',
  `timestamp_updated` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `timestamp_created` timestamp NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `name_unique` (`trainer_skill_phrase`),
  UNIQUE KEY `uuid_idx` (`uuid`),
  KEY `modified_by` (`modified_by`),
  KEY `created_by` (`created_by`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

/*!50003 SET @SAVE_SQL_MODE=@@SQL_MODE*/;

DELIMITER ;;
/*!50003 SET SESSION SQL_MODE="NO_AUTO_VALUE_ON_ZERO" */;;
/*!50003 CREATE */ /*!50017 DEFINER=`root`@`localhost` */ /*!50003 TRIGGER `trainer_skill_option_insert` BEFORE INSERT ON `trainer_skill_option` FOR EACH ROW BEGIN

SET NEW.`uuid` = IFNULL(NEW.`uuid`,UUID());

END */;;

DELIMITER ;
/*!50003 SET SESSION SQL_MODE=@SAVE_SQL_MODE*/;

--
-- Table structure for table `trainer_to_trainer_language_option`
--

DROP TABLE IF EXISTS `trainer_to_trainer_language_option`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `trainer_to_trainer_language_option` (
  `id` int(11) NOT NULL auto_increment,
  `uuid` char(36) default NULL,
  `trainer_id` int(11) NOT NULL,
  `trainer_language_option_id` int(11) NOT NULL,
  `is_primary` tinyint(1) unsigned NOT NULL default '0',
  `created_by` int(11) default NULL,
  `timestamp_created` timestamp NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `uuid_idx` (`uuid`),
  KEY `created_by` (`created_by`),
  KEY `trainer_language_option_id` (`trainer_language_option_id`),
  KEY `trainer_id` (`trainer_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

/*!50003 SET @SAVE_SQL_MODE=@@SQL_MODE*/;

DELIMITER ;;
/*!50003 SET SESSION SQL_MODE="NO_AUTO_VALUE_ON_ZERO" */;;
/*!50003 CREATE */ /*!50017 DEFINER=`root`@`localhost` */ /*!50003 TRIGGER `trainer_to_trainer_language_option_insert` BEFORE INSERT ON `trainer_to_trainer_language_option` FOR EACH ROW BEGIN

SET NEW.`uuid` = IFNULL(NEW.`uuid`,UUID());

END */;;

DELIMITER ;
/*!50003 SET SESSION SQL_MODE=@SAVE_SQL_MODE*/;

--
-- Table structure for table `trainer_to_trainer_skill_option`
--

DROP TABLE IF EXISTS `trainer_to_trainer_skill_option`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `trainer_to_trainer_skill_option` (
  `id` int(11) NOT NULL auto_increment,
  `uuid` char(36) default NULL,
  `trainer_id` int(11) NOT NULL,
  `trainer_skill_option_id` int(11) NOT NULL,
  `created_by` int(11) default NULL,
  `timestamp_created` timestamp NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `uuid_idx` (`uuid`),
  KEY `created_by` (`created_by`),
  KEY `trainer_id` (`trainer_id`),
  KEY `trainer_skill_option_id` (`trainer_skill_option_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

/*!50003 SET @SAVE_SQL_MODE=@@SQL_MODE*/;

DELIMITER ;;
/*!50003 SET SESSION SQL_MODE="NO_AUTO_VALUE_ON_ZERO" */;;
/*!50003 CREATE */ /*!50017 DEFINER=`root`@`localhost` */ /*!50003 TRIGGER `trainer_to_trainer_skill_option_insert` BEFORE INSERT ON `trainer_to_trainer_skill_option` FOR EACH ROW BEGIN

SET NEW.`uuid` = IFNULL(NEW.`uuid`,UUID());

END */;;

DELIMITER ;
/*!50003 SET SESSION SQL_MODE=@SAVE_SQL_MODE*/;

--
-- Table structure for table `trainer_type_option`
--

DROP TABLE IF EXISTS `trainer_type_option`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `trainer_type_option` (
  `id` int(11) NOT NULL auto_increment,
  `uuid` char(36) default NULL,
  `trainer_type_phrase` varchar(128) NOT NULL default '',
  `modified_by` int(11) default NULL,
  `created_by` int(11) default NULL,
  `is_deleted` tinyint(1) NOT NULL default '0',
  `timestamp_updated` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `timestamp_created` timestamp NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `name_unique` (`trainer_type_phrase`),
  UNIQUE KEY `uuid_idx` (`uuid`),
  KEY `created_by` (`created_by`),
  KEY `modified_by` (`modified_by`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

/*!50003 SET @SAVE_SQL_MODE=@@SQL_MODE*/;

DELIMITER ;;
/*!50003 SET SESSION SQL_MODE="NO_AUTO_VALUE_ON_ZERO" */;;
/*!50003 CREATE */ /*!50017 DEFINER=`root`@`localhost` */ /*!50003 TRIGGER `trainer_type_option_insert` BEFORE INSERT ON `trainer_type_option` FOR EACH ROW BEGIN

SET NEW.`uuid` = IFNULL(NEW.`uuid`,UUID());

END */;;

DELIMITER ;
/*!50003 SET SESSION SQL_MODE=@SAVE_SQL_MODE*/;

--
-- Table structure for table `training`
--

DROP TABLE IF EXISTS `training`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `training` (
  `id` int(11) NOT NULL auto_increment,
  `uuid` char(36) default NULL,
  `course_id` int(11) default NULL,
  `training_title_option_id` int(11) NOT NULL,
  `has_known_participants` tinyint(1) unsigned NOT NULL default '1',
  `training_start_date` date default NULL,
  `training_end_date` date default NULL,
  `training_length_value` int(11) default '0',
  `training_length_interval` enum('hour','week','day') default 'hour',
  `training_organizer_option_id` int(11) default '0',
  `training_location_id` int(11) default '0',
  `training_level_option_id` int(11) default '0',
  `training_method_option_id` int(11) unsigned default NULL,
  `training_custom_1_option_id` int(11) default NULL,
  `training_custom_2_option_id` int(11) default NULL,
  `training_got_curriculum_option_id` int(11) default NULL,
  `training_primary_language_option_id` int(11) unsigned default NULL,
  `training_secondary_language_option_id` int(11) unsigned default NULL,
  `comments` text NOT NULL,
  `got_comments` text NOT NULL,
  `objectives` text NOT NULL,
  `is_approved` tinyint(1) unsigned NOT NULL default '1',
  `is_tot` tinyint(1) NOT NULL,
  `is_refresher` tinyint(1) NOT NULL,
  `pre` float default NULL,
  `post` float default NULL,
  `modified_by` int(11) default NULL,
  `created_by` int(11) default NULL,
  `is_deleted` tinyint(1) NOT NULL default '0',
  `timestamp_updated` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `timestamp_created` timestamp NOT NULL default '0000-00-00 00:00:00',
  `training_refresher_option_id` int(11) default NULL,
  `custom_3` varchar(255) default '',
  `custom_4` varchar(255) default '',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `uuid_idx` (`uuid`),
  KEY `created_by` (`created_by`),
  KEY `modified_by` (`modified_by`),
  KEY `course_id` (`course_id`),
  KEY `training_title_option_id` (`training_title_option_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

/*!50003 SET @SAVE_SQL_MODE=@@SQL_MODE*/;

DELIMITER ;;
/*!50003 SET SESSION SQL_MODE="NO_AUTO_VALUE_ON_ZERO" */;;
/*!50003 CREATE */ /*!50017 DEFINER=`root`@`localhost` */ /*!50003 TRIGGER `training_insert` BEFORE INSERT ON `training` FOR EACH ROW BEGIN

SET NEW.`uuid` = IFNULL(NEW.`uuid`,UUID());

END */;;

DELIMITER ;
/*!50003 SET SESSION SQL_MODE=@SAVE_SQL_MODE*/;

--
-- Table structure for table `training_approval_history`
--

DROP TABLE IF EXISTS `training_approval_history`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `training_approval_history` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `uuid` char(36) default NULL,
  `training_id` int(11) NOT NULL,
  `approval_status` enum('resubmitted','rejected','approved','new') NOT NULL default 'new',
  `message` text,
  `recipients` varchar(512) default NULL,
  `created_by` int(11) NOT NULL,
  `timestamp_created` timestamp NOT NULL default '0000-00-00 00:00:00' on update CURRENT_TIMESTAMP,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `uuid_idx` (`uuid`),
  KEY `training_id` (`training_id`),
  KEY `created_by` (`created_by`),
  KEY `created_by_2` (`created_by`),
  KEY `time_idx` (`timestamp_created`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

/*!50003 SET @SAVE_SQL_MODE=@@SQL_MODE*/;

DELIMITER ;;
/*!50003 SET SESSION SQL_MODE="NO_AUTO_VALUE_ON_ZERO" */;;
/*!50003 CREATE */ /*!50017 DEFINER=`root`@`localhost` */ /*!50003 TRIGGER `training_approval_history_insert` BEFORE INSERT ON `training_approval_history` FOR EACH ROW BEGIN

SET NEW.`uuid` = IFNULL(NEW.`uuid`,UUID());

END */;;

DELIMITER ;
/*!50003 SET SESSION SQL_MODE=@SAVE_SQL_MODE*/;

--
-- Table structure for table `training_category_option`
--

DROP TABLE IF EXISTS `training_category_option`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `training_category_option` (
  `id` int(11) NOT NULL auto_increment,
  `uuid` char(36) default NULL,
  `training_category_phrase` varchar(128) NOT NULL,
  `modified_by` int(11) default NULL,
  `created_by` int(11) default NULL,
  `is_deleted` tinyint(1) NOT NULL default '0',
  `timestamp_updated` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `timestamp_created` timestamp NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `name_unique` (`training_category_phrase`),
  UNIQUE KEY `uuid_idx` (`uuid`),
  KEY `modified_by` (`modified_by`),
  KEY `created_by` (`created_by`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

/*!50003 SET @SAVE_SQL_MODE=@@SQL_MODE*/;

DELIMITER ;;
/*!50003 SET SESSION SQL_MODE="NO_AUTO_VALUE_ON_ZERO" */;;
/*!50003 CREATE */ /*!50017 DEFINER=`root`@`localhost` */ /*!50003 TRIGGER `training_category_option_insert` BEFORE INSERT ON `training_category_option` FOR EACH ROW BEGIN

SET NEW.`uuid` = IFNULL(NEW.`uuid`,UUID());

END */;;

DELIMITER ;
/*!50003 SET SESSION SQL_MODE=@SAVE_SQL_MODE*/;

--
-- Table structure for table `training_category_option_to_training_title_option`
--

DROP TABLE IF EXISTS `training_category_option_to_training_title_option`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `training_category_option_to_training_title_option` (
  `id` int(11) NOT NULL auto_increment,
  `uuid` char(36) default NULL,
  `training_category_option_id` int(11) NOT NULL,
  `training_title_option_id` int(11) NOT NULL,
  `created_by` int(11) default NULL,
  `timestamp_created` timestamp NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `unique_training_cat` (`training_category_option_id`,`training_title_option_id`),
  UNIQUE KEY `uuid_idx` (`uuid`),
  KEY `training_category_option_id` (`training_category_option_id`),
  KEY `created_by` (`created_by`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

/*!50003 SET @SAVE_SQL_MODE=@@SQL_MODE*/;

DELIMITER ;;
/*!50003 SET SESSION SQL_MODE="NO_AUTO_VALUE_ON_ZERO" */;;
/*!50003 CREATE */ /*!50017 DEFINER=`root`@`localhost` */ /*!50003 TRIGGER `training_category_option_to_training_title_option_insert` BEFORE INSERT ON `training_category_option_to_training_title_option` FOR EACH ROW BEGIN

SET NEW.`uuid` = IFNULL(NEW.`uuid`,UUID());

END */;;

DELIMITER ;
/*!50003 SET SESSION SQL_MODE=@SAVE_SQL_MODE*/;

--
-- Table structure for table `training_custom_1_option`
--

DROP TABLE IF EXISTS `training_custom_1_option`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `training_custom_1_option` (
  `id` int(11) NOT NULL auto_increment,
  `uuid` char(36) default NULL,
  `custom1_phrase` varchar(128) NOT NULL default '',
  `modified_by` int(11) default NULL,
  `created_by` int(11) default NULL,
  `is_deleted` tinyint(1) NOT NULL default '0',
  `timestamp_updated` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `timestamp_created` timestamp NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `name_unique` (`custom1_phrase`),
  UNIQUE KEY `uuid_idx` (`uuid`),
  KEY `modified_by` (`modified_by`),
  KEY `created_by` (`created_by`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

/*!50003 SET @SAVE_SQL_MODE=@@SQL_MODE*/;

DELIMITER ;;
/*!50003 SET SESSION SQL_MODE="NO_AUTO_VALUE_ON_ZERO" */;;
/*!50003 CREATE */ /*!50017 DEFINER=`root`@`localhost` */ /*!50003 TRIGGER `training_custom_1_option_insert` BEFORE INSERT ON `training_custom_1_option` FOR EACH ROW BEGIN

SET NEW.`uuid` = IFNULL(NEW.`uuid`,UUID());

END */;;

DELIMITER ;
/*!50003 SET SESSION SQL_MODE=@SAVE_SQL_MODE*/;

--
-- Table structure for table `training_custom_2_option`
--

DROP TABLE IF EXISTS `training_custom_2_option`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `training_custom_2_option` (
  `id` int(11) NOT NULL auto_increment,
  `uuid` char(36) default NULL,
  `custom2_phrase` varchar(128) NOT NULL default '',
  `modified_by` int(11) default NULL,
  `created_by` int(11) default NULL,
  `is_deleted` tinyint(1) NOT NULL default '0',
  `timestamp_updated` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `timestamp_created` timestamp NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `name_unique` (`custom2_phrase`),
  UNIQUE KEY `uuid_idx` (`uuid`),
  KEY `modified_by` (`modified_by`),
  KEY `created_by` (`created_by`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

/*!50003 SET @SAVE_SQL_MODE=@@SQL_MODE*/;

DELIMITER ;;
/*!50003 SET SESSION SQL_MODE="NO_AUTO_VALUE_ON_ZERO" */;;
/*!50003 CREATE */ /*!50017 DEFINER=`root`@`localhost` */ /*!50003 TRIGGER `training_custom_2_option_insert` BEFORE INSERT ON `training_custom_2_option` FOR EACH ROW BEGIN

SET NEW.`uuid` = IFNULL(NEW.`uuid`,UUID());

END */;;

DELIMITER ;
/*!50003 SET SESSION SQL_MODE=@SAVE_SQL_MODE*/;

--
-- Table structure for table `training_funding_option`
--

DROP TABLE IF EXISTS `training_funding_option`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `training_funding_option` (
  `id` int(11) NOT NULL auto_increment,
  `uuid` char(36) default NULL,
  `funding_phrase` varchar(128) NOT NULL default '',
  `is_default` tinyint(1) unsigned NOT NULL default '0',
  `modified_by` int(11) default NULL,
  `created_by` int(11) default NULL,
  `is_deleted` tinyint(1) NOT NULL default '0',
  `timestamp_updated` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `timestamp_created` timestamp NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `name_unique` (`funding_phrase`),
  UNIQUE KEY `uuid_idx` (`uuid`),
  KEY `created_by` (`created_by`),
  KEY `modified_by` (`modified_by`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

/*!50003 SET @SAVE_SQL_MODE=@@SQL_MODE*/;

DELIMITER ;;
/*!50003 SET SESSION SQL_MODE="NO_AUTO_VALUE_ON_ZERO" */;;
/*!50003 CREATE */ /*!50017 DEFINER=`root`@`localhost` */ /*!50003 TRIGGER `training_funding_option_insert` BEFORE INSERT ON `training_funding_option` FOR EACH ROW BEGIN

SET NEW.`uuid` = IFNULL(NEW.`uuid`,UUID());

END */;;

DELIMITER ;
/*!50003 SET SESSION SQL_MODE=@SAVE_SQL_MODE*/;

--
-- Table structure for table `training_got_curriculum_option`
--

DROP TABLE IF EXISTS `training_got_curriculum_option`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `training_got_curriculum_option` (
  `id` int(11) NOT NULL auto_increment,
  `uuid` char(36) default NULL,
  `training_got_curriculum_phrase` varchar(128) NOT NULL default '',
  `modified_by` int(11) default NULL,
  `created_by` int(11) default NULL,
  `is_deleted` tinyint(1) NOT NULL default '0',
  `timestamp_updated` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `timestamp_created` timestamp NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `name_unique` (`training_got_curriculum_phrase`),
  UNIQUE KEY `uuid_idx` (`uuid`),
  KEY `created_by` (`created_by`),
  KEY `modified_by` (`modified_by`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

/*!50003 SET @SAVE_SQL_MODE=@@SQL_MODE*/;

DELIMITER ;;
/*!50003 SET SESSION SQL_MODE="NO_AUTO_VALUE_ON_ZERO" */;;
/*!50003 CREATE */ /*!50017 DEFINER=`root`@`localhost` */ /*!50003 TRIGGER `training_got_curriculum_option_insert` BEFORE INSERT ON `training_got_curriculum_option` FOR EACH ROW BEGIN

SET NEW.`uuid` = IFNULL(NEW.`uuid`,UUID());

END */;;

DELIMITER ;
/*!50003 SET SESSION SQL_MODE=@SAVE_SQL_MODE*/;

--
-- Table structure for table `training_level_option`
--

DROP TABLE IF EXISTS `training_level_option`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `training_level_option` (
  `id` int(11) NOT NULL auto_increment,
  `uuid` char(36) default NULL,
  `training_level_phrase` varchar(128) NOT NULL default '',
  `is_default` tinyint(1) unsigned NOT NULL default '0',
  `modified_by` int(11) default NULL,
  `created_by` int(11) default NULL,
  `is_deleted` tinyint(1) NOT NULL default '0',
  `timestamp_updated` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `timestamp_created` timestamp NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `name_unique` (`training_level_phrase`),
  UNIQUE KEY `uuid_idx` (`uuid`),
  KEY `modified_by` (`modified_by`),
  KEY `created_by` (`created_by`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

/*!50003 SET @SAVE_SQL_MODE=@@SQL_MODE*/;

DELIMITER ;;
/*!50003 SET SESSION SQL_MODE="NO_AUTO_VALUE_ON_ZERO" */;;
/*!50003 CREATE */ /*!50017 DEFINER=`root`@`localhost` */ /*!50003 TRIGGER `training_level_option_insert` BEFORE INSERT ON `training_level_option` FOR EACH ROW BEGIN

SET NEW.`uuid` = IFNULL(NEW.`uuid`,UUID());

END */;;

DELIMITER ;
/*!50003 SET SESSION SQL_MODE=@SAVE_SQL_MODE*/;

--
-- Table structure for table `training_location`
--

DROP TABLE IF EXISTS `training_location`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `training_location` (
  `id` int(11) NOT NULL auto_increment,
  `uuid` char(36) default NULL,
  `training_location_name` varchar(128) NOT NULL default '',
  `location_id` int(10) unsigned default NULL,
  `modified_by` int(11) default NULL,
  `created_by` int(11) default NULL,
  `is_deleted` tinyint(1) NOT NULL default '0',
  `timestamp_updated` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `timestamp_created` timestamp NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `uuid_idx` (`uuid`),
  KEY `modified_by` (`modified_by`),
  KEY `created_by` (`created_by`),
  KEY `training_location_ibfk_9` (`location_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

/*!50003 SET @SAVE_SQL_MODE=@@SQL_MODE*/;

DELIMITER ;;
/*!50003 SET SESSION SQL_MODE="NO_AUTO_VALUE_ON_ZERO" */;;
/*!50003 CREATE */ /*!50017 DEFINER=`root`@`localhost` */ /*!50003 TRIGGER `training_location_insert` BEFORE INSERT ON `training_location` FOR EACH ROW BEGIN

SET NEW.`uuid` = IFNULL(NEW.`uuid`,UUID());

END */;;

DELIMITER ;
/*!50003 SET SESSION SQL_MODE=@SAVE_SQL_MODE*/;

--
-- Table structure for table `training_method_option`
--

DROP TABLE IF EXISTS `training_method_option`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `training_method_option` (
  `id` int(11) NOT NULL auto_increment,
  `uuid` char(36) default NULL,
  `training_method_phrase` varchar(128) NOT NULL,
  `modified_by` int(11) default NULL,
  `created_by` int(11) default NULL,
  `is_deleted` tinyint(1) NOT NULL default '0',
  `timestamp_updated` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `timestamp_created` timestamp NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `name_unique` (`training_method_phrase`),
  UNIQUE KEY `uuid_idx` (`uuid`),
  KEY `modified_by` (`modified_by`),
  KEY `created_by` (`created_by`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

/*!50003 SET @SAVE_SQL_MODE=@@SQL_MODE*/;

DELIMITER ;;
/*!50003 SET SESSION SQL_MODE="NO_AUTO_VALUE_ON_ZERO" */;;
/*!50003 CREATE */ /*!50017 DEFINER=`root`@`localhost` */ /*!50003 TRIGGER `training_method_option_insert` BEFORE INSERT ON `training_method_option` FOR EACH ROW BEGIN

SET NEW.`uuid` = IFNULL(NEW.`uuid`,UUID());

END */;;

DELIMITER ;
/*!50003 SET SESSION SQL_MODE=@SAVE_SQL_MODE*/;

--
-- Table structure for table `training_organizer_option`
--

DROP TABLE IF EXISTS `training_organizer_option`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `training_organizer_option` (
  `id` int(11) NOT NULL auto_increment,
  `uuid` char(36) default NULL,
  `training_organizer_phrase` varchar(128) NOT NULL default '',
  `is_default` tinyint(1) unsigned NOT NULL default '0',
  `modified_by` int(11) default NULL,
  `created_by` int(11) default NULL,
  `is_deleted` tinyint(1) NOT NULL default '0',
  `timestamp_updated` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `timestamp_created` timestamp NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `name_unique` (`training_organizer_phrase`),
  UNIQUE KEY `uuid_idx` (`uuid`),
  KEY `created_by` (`created_by`),
  KEY `modified_by` (`modified_by`)
) ENGINE=MyISAM AUTO_INCREMENT=166 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

/*!50003 SET @SAVE_SQL_MODE=@@SQL_MODE*/;

DELIMITER ;;
/*!50003 SET SESSION SQL_MODE="NO_AUTO_VALUE_ON_ZERO" */;;
/*!50003 CREATE */ /*!50017 DEFINER=`root`@`localhost` */ /*!50003 TRIGGER `training_organizer_option_insert` BEFORE INSERT ON `training_organizer_option` FOR EACH ROW BEGIN

SET NEW.`uuid` = IFNULL(NEW.`uuid`,UUID());

END */;;

DELIMITER ;
/*!50003 SET SESSION SQL_MODE=@SAVE_SQL_MODE*/;

--
-- Table structure for table `training_pepfar_categories_option`
--

DROP TABLE IF EXISTS `training_pepfar_categories_option`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `training_pepfar_categories_option` (
  `id` int(11) NOT NULL auto_increment,
  `uuid` char(36) default NULL,
  `pepfar_category_phrase` varchar(128) NOT NULL default '',
  `is_default` tinyint(1) unsigned NOT NULL default '0',
  `id_training_method_option_id` int(11) default NULL,
  `modified_by` int(11) default NULL,
  `created_by` int(11) default NULL,
  `is_deleted` tinyint(1) NOT NULL default '0',
  `timestamp_updated` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `timestamp_created` timestamp NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `name_unique` (`pepfar_category_phrase`),
  UNIQUE KEY `uuid_idx` (`uuid`),
  KEY `created_by` (`created_by`),
  KEY `modified_by` (`modified_by`),
  KEY `id_training_method_option_id` (`id_training_method_option_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

/*!50003 SET @SAVE_SQL_MODE=@@SQL_MODE*/;

DELIMITER ;;
/*!50003 SET SESSION SQL_MODE="NO_AUTO_VALUE_ON_ZERO" */;;
/*!50003 CREATE */ /*!50017 DEFINER=`root`@`localhost` */ /*!50003 TRIGGER `training_pepfar_categories_option_insert` BEFORE INSERT ON `training_pepfar_categories_option` FOR EACH ROW BEGIN

SET NEW.`uuid` = IFNULL(NEW.`uuid`,UUID());

END */;;

DELIMITER ;
/*!50003 SET SESSION SQL_MODE=@SAVE_SQL_MODE*/;

--
-- Table structure for table `training_recommend`
--

DROP TABLE IF EXISTS `training_recommend`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `training_recommend` (
  `id` int(11) NOT NULL auto_increment,
  `uuid` char(36) default NULL,
  `person_qualification_option_id` int(11) default NULL,
  `training_topic_option_id` int(11) default NULL,
  `modified_by` int(11) default NULL,
  `created_by` int(11) default NULL,
  `is_deleted` tinyint(1) NOT NULL default '0',
  `timestamp_updated` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `timestamp_created` timestamp NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `person_qualification_option_id` (`person_qualification_option_id`,`training_topic_option_id`),
  UNIQUE KEY `uuid_idx` (`uuid`),
  KEY `created_by` (`created_by`),
  KEY `modified_by` (`modified_by`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

/*!50003 SET @SAVE_SQL_MODE=@@SQL_MODE*/;

DELIMITER ;;
/*!50003 SET SESSION SQL_MODE="NO_AUTO_VALUE_ON_ZERO" */;;
/*!50003 CREATE */ /*!50017 DEFINER=`root`@`localhost` */ /*!50003 TRIGGER `training_recommend_insert` BEFORE INSERT ON `training_recommend` FOR EACH ROW BEGIN

SET NEW.`uuid` = IFNULL(NEW.`uuid`,UUID());

END */;;

DELIMITER ;
/*!50003 SET SESSION SQL_MODE=@SAVE_SQL_MODE*/;

--
-- Table structure for table `training_refresher_option`
--

DROP TABLE IF EXISTS `training_refresher_option`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `training_refresher_option` (
  `id` int(11) NOT NULL auto_increment,
  `uuid` char(36) default NULL,
  `refresher_phrase_option` varchar(128) NOT NULL default '',
  `is_default` tinyint(1) unsigned NOT NULL default '0',
  `modified_by` int(11) default NULL,
  `created_by` int(11) default NULL,
  `is_deleted` tinyint(1) NOT NULL default '0',
  `timestamp_updated` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `timestamp_created` timestamp NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `name_unique` (`refresher_phrase_option`),
  UNIQUE KEY `uuid_idx` (`uuid`),
  KEY `created_by` (`created_by`),
  KEY `modified_by` (`modified_by`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

/*!50003 SET @SAVE_SQL_MODE=@@SQL_MODE*/;

DELIMITER ;;
/*!50003 SET SESSION SQL_MODE="NO_AUTO_VALUE_ON_ZERO" */;;
/*!50003 CREATE */ /*!50017 DEFINER=`root`@`localhost` */ /*!50003 TRIGGER `training_refresher_option_insert` BEFORE INSERT ON `training_refresher_option` FOR EACH ROW BEGIN

  SET NEW.`uuid` = IFNULL(NEW.`uuid`,UUID());

  END */;;

DELIMITER ;
/*!50003 SET SESSION SQL_MODE=@SAVE_SQL_MODE*/;

--
-- Table structure for table `training_title_option`
--

DROP TABLE IF EXISTS `training_title_option`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `training_title_option` (
  `id` int(11) NOT NULL auto_increment,
  `uuid` char(36) default NULL,
  `training_title_phrase` varchar(128) NOT NULL,
  `modified_by` int(11) default NULL,
  `created_by` int(11) default NULL,
  `is_deleted` tinyint(1) NOT NULL default '0',
  `timestamp_updated` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `timestamp_created` timestamp NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `name_unique` (`training_title_phrase`),
  UNIQUE KEY `uuid_idx` (`uuid`),
  KEY `modified_by` (`modified_by`),
  KEY `created_by` (`created_by`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

/*!50003 SET @SAVE_SQL_MODE=@@SQL_MODE*/;

DELIMITER ;;
/*!50003 SET SESSION SQL_MODE="NO_AUTO_VALUE_ON_ZERO" */;;
/*!50003 CREATE */ /*!50017 DEFINER=`root`@`localhost` */ /*!50003 TRIGGER `training_title_option_insert` BEFORE INSERT ON `training_title_option` FOR EACH ROW BEGIN

SET NEW.`uuid` = IFNULL(NEW.`uuid`,UUID());

END */;;

DELIMITER ;
/*!50003 SET SESSION SQL_MODE=@SAVE_SQL_MODE*/;

--
-- Table structure for table `training_to_person_qualification_option`
--

DROP TABLE IF EXISTS `training_to_person_qualification_option`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `training_to_person_qualification_option` (
  `id` int(11) NOT NULL auto_increment,
  `uuid` char(36) default NULL,
  `training_id` int(11) NOT NULL,
  `person_qualification_option_id` int(11) NOT NULL,
  `person_count_na` int(11) NOT NULL default '0',
  `person_count_male` int(11) NOT NULL default '0',
  `person_count_female` int(11) NOT NULL default '0',
  `age_range_option_id` int(11) unsigned NOT NULL default '0',
  `created_by` int(11) default NULL,
  `timestamp_created` timestamp NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `training_qual_uniq` (`training_id`,`person_qualification_option_id`,`age_range_option_id`),
  UNIQUE KEY `uuid_idx` (`uuid`),
  KEY `person_qualification_option_id` (`person_qualification_option_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

/*!50003 SET @SAVE_SQL_MODE=@@SQL_MODE*/;

DELIMITER ;;
/*!50003 SET SESSION SQL_MODE="NO_AUTO_VALUE_ON_ZERO" */;;
/*!50003 CREATE */ /*!50017 DEFINER=`root`@`localhost` */ /*!50003 TRIGGER `training_to_person_qualification_option_insert` BEFORE INSERT ON `training_to_person_qualification_option` FOR EACH ROW BEGIN

SET NEW.`uuid` = IFNULL(NEW.`uuid`,UUID());

END */;;

DELIMITER ;
/*!50003 SET SESSION SQL_MODE=@SAVE_SQL_MODE*/;

--
-- Table structure for table `training_to_trainer`
--

DROP TABLE IF EXISTS `training_to_trainer`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `training_to_trainer` (
  `id` int(11) NOT NULL auto_increment,
  `uuid` char(36) default NULL,
  `trainer_id` int(11) NOT NULL,
  `training_id` int(11) NOT NULL,
  `duration_days` float NOT NULL,
  `created_by` int(11) default NULL,
  `timestamp_created` timestamp NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `unique_training` (`trainer_id`,`training_id`),
  UNIQUE KEY `uuid_idx` (`uuid`),
  KEY `created_by` (`created_by`),
  KEY `trainer_id` (`trainer_id`),
  KEY `training_id` (`training_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

/*!50003 SET @SAVE_SQL_MODE=@@SQL_MODE*/;

DELIMITER ;;
/*!50003 SET SESSION SQL_MODE="NO_AUTO_VALUE_ON_ZERO" */;;
/*!50003 CREATE */ /*!50017 DEFINER=`root`@`localhost` */ /*!50003 TRIGGER `training_to_trainer_insert` BEFORE INSERT ON `training_to_trainer` FOR EACH ROW BEGIN

SET NEW.`uuid` = IFNULL(NEW.`uuid`,UUID());

END */;;

DELIMITER ;
/*!50003 SET SESSION SQL_MODE=@SAVE_SQL_MODE*/;

--
-- Table structure for table `training_to_training_funding_option`
--

DROP TABLE IF EXISTS `training_to_training_funding_option`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `training_to_training_funding_option` (
  `id` int(11) NOT NULL auto_increment,
  `uuid` char(36) default NULL,
  `training_id` int(11) NOT NULL,
  `training_funding_option_id` int(11) NOT NULL,
  `funding_amount` float default NULL,
  `created_by` int(11) default NULL,
  `timestamp_created` timestamp NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `unique_training_cat` (`training_funding_option_id`,`training_id`),
  UNIQUE KEY `uuid_idx` (`uuid`),
  KEY `training_id` (`training_id`),
  KEY `created_by` (`created_by`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

/*!50003 SET @SAVE_SQL_MODE=@@SQL_MODE*/;

DELIMITER ;;
/*!50003 SET SESSION SQL_MODE="NO_AUTO_VALUE_ON_ZERO" */;;
/*!50003 CREATE */ /*!50017 DEFINER=`root`@`localhost` */ /*!50003 TRIGGER `training_to_training_funding_option_insert` BEFORE INSERT ON `training_to_training_funding_option` FOR EACH ROW BEGIN

SET NEW.`uuid` = IFNULL(NEW.`uuid`,UUID());

END */;;

DELIMITER ;
/*!50003 SET SESSION SQL_MODE=@SAVE_SQL_MODE*/;

--
-- Table structure for table `training_to_training_pepfar_categories_option`
--

DROP TABLE IF EXISTS `training_to_training_pepfar_categories_option`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `training_to_training_pepfar_categories_option` (
  `id` int(11) NOT NULL auto_increment,
  `uuid` char(36) default NULL,
  `training_id` int(11) NOT NULL,
  `training_pepfar_categories_option_id` int(11) NOT NULL,
  `training_method_option_id` int(11) default NULL,
  `duration_days` float NOT NULL,
  `created_by` int(11) default NULL,
  `timestamp_created` timestamp NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `unique_training_cat` (`training_pepfar_categories_option_id`,`training_id`),
  UNIQUE KEY `uuid_idx` (`uuid`),
  KEY `training_id` (`training_id`),
  KEY `created_by` (`created_by`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

/*!50003 SET @SAVE_SQL_MODE=@@SQL_MODE*/;

DELIMITER ;;
/*!50003 SET SESSION SQL_MODE="NO_AUTO_VALUE_ON_ZERO" */;;
/*!50003 CREATE */ /*!50017 DEFINER=`root`@`localhost` */ /*!50003 TRIGGER `training_to_training_pepfar_categories_option_insert` BEFORE INSERT ON `training_to_training_pepfar_categories_option` FOR EACH ROW BEGIN

SET NEW.`uuid` = IFNULL(NEW.`uuid`,UUID());

END */;;

DELIMITER ;
/*!50003 SET SESSION SQL_MODE=@SAVE_SQL_MODE*/;

--
-- Table structure for table `training_to_training_refresher_option`
--

DROP TABLE IF EXISTS `training_to_training_refresher_option`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `training_to_training_refresher_option` (
  `id` int(11) NOT NULL auto_increment,
  `uuid` char(36) default NULL,
  `training_id` int(11) NOT NULL,
  `training_refresher_option_id` int(11) NOT NULL,
  `created_by` int(11) default NULL,
  `timestamp_created` timestamp NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `unique_training_cat` (`training_refresher_option_id`,`training_id`),
  UNIQUE KEY `uuid_idx` (`uuid`),
  KEY `training_id` (`training_id`),
  KEY `created_by` (`created_by`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

/*!50003 SET @SAVE_SQL_MODE=@@SQL_MODE*/;

DELIMITER ;;
/*!50003 SET SESSION SQL_MODE="NO_AUTO_VALUE_ON_ZERO" */;;
/*!50003 CREATE */ /*!50017 DEFINER=`root`@`localhost` */ /*!50003 TRIGGER `training_to_training_refresher_option_insert` BEFORE INSERT ON `training_to_training_refresher_option` FOR EACH ROW BEGIN

  SET NEW.`uuid` = IFNULL(NEW.`uuid`,UUID());

  END */;;

DELIMITER ;
/*!50003 SET SESSION SQL_MODE=@SAVE_SQL_MODE*/;

--
-- Table structure for table `training_to_training_topic_option`
--

DROP TABLE IF EXISTS `training_to_training_topic_option`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `training_to_training_topic_option` (
  `id` int(11) NOT NULL auto_increment,
  `uuid` char(36) default NULL,
  `training_id` int(11) NOT NULL,
  `training_topic_option_id` int(11) NOT NULL,
  `created_by` int(11) default NULL,
  `timestamp_created` timestamp NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `unique_training_cat` (`training_topic_option_id`,`training_id`),
  UNIQUE KEY `uuid_idx` (`uuid`),
  KEY `training_id` (`training_id`),
  KEY `created_by` (`created_by`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

/*!50003 SET @SAVE_SQL_MODE=@@SQL_MODE*/;

DELIMITER ;;
/*!50003 SET SESSION SQL_MODE="NO_AUTO_VALUE_ON_ZERO" */;;
/*!50003 CREATE */ /*!50017 DEFINER=`root`@`localhost` */ /*!50003 TRIGGER `training_to_training_topic_option_insert` BEFORE INSERT ON `training_to_training_topic_option` FOR EACH ROW BEGIN

SET NEW.`uuid` = IFNULL(NEW.`uuid`,UUID());

END */;;

DELIMITER ;
/*!50003 SET SESSION SQL_MODE=@SAVE_SQL_MODE*/;

--
-- Table structure for table `training_topic_option`
--

DROP TABLE IF EXISTS `training_topic_option`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `training_topic_option` (
  `id` int(11) NOT NULL auto_increment,
  `uuid` char(36) default NULL,
  `training_topic_phrase` varchar(128) NOT NULL default '',
  `is_default` tinyint(1) unsigned NOT NULL default '0',
  `modified_by` int(11) default NULL,
  `created_by` int(11) default NULL,
  `is_deleted` tinyint(1) NOT NULL default '0',
  `timestamp_updated` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `timestamp_created` timestamp NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `name_unique` (`training_topic_phrase`),
  UNIQUE KEY `uuid_idx` (`uuid`),
  KEY `created_by` (`created_by`),
  KEY `modified_by` (`modified_by`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

/*!50003 SET @SAVE_SQL_MODE=@@SQL_MODE*/;

DELIMITER ;;
/*!50003 SET SESSION SQL_MODE="NO_AUTO_VALUE_ON_ZERO" */;;
/*!50003 CREATE */ /*!50017 DEFINER=`root`@`localhost` */ /*!50003 TRIGGER `training_topic_option_insert` BEFORE INSERT ON `training_topic_option` FOR EACH ROW BEGIN

SET NEW.`uuid` = IFNULL(NEW.`uuid`,UUID());

END */;;

DELIMITER ;
/*!50003 SET SESSION SQL_MODE=@SAVE_SQL_MODE*/;

--
-- Table structure for table `trans`
--

DROP TABLE IF EXISTS `trans`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `trans` (
  `sno` bigint(20) NOT NULL auto_increment,
  `person` int(11) NOT NULL,
  `id` int(11) NOT NULL,
  `chk` varchar(10) NOT NULL,
  `yr` varchar(10) NOT NULL,
  `transstring` varchar(256) NOT NULL,
  `active` char(1) NOT NULL default 'N',
  PRIMARY KEY  (`sno`),
  KEY `person` (`person`,`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `translation`
--

DROP TABLE IF EXISTS `translation`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `translation` (
  `id` int(11) NOT NULL auto_increment,
  `uuid` char(36) default NULL,
  `key_phrase` varchar(128) NOT NULL default '',
  `phrase` varchar(128) NOT NULL default '',
  `modified_by` int(11) default NULL,
  `created_by` int(11) default NULL,
  `is_deleted` tinyint(1) NOT NULL default '0',
  `timestamp_updated` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `timestamp_created` timestamp NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `uuid_idx` (`uuid`),
  KEY `modified_by` (`modified_by`),
  KEY `created_by` (`created_by`)
) ENGINE=MyISAM AUTO_INCREMENT=163 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `translation`
--

LOCK TABLES `translation` WRITE;
/*!40000 ALTER TABLE `translation` DISABLE KEYS */;
INSERT INTO `translation` VALUES (0,'ce8c9b02-a3cf-11e2-a984-00163ec54890','Application Name','TrainSMART',1,NULL,0,'2014-10-15 15:01:50','0000-00-00 00:00:00'),(1,'ce8c9d28-a3cf-11e2-a984-00163ec54890','Country','Country',1,NULL,0,'2008-04-28 20:17:48','0000-00-00 00:00:00'),(2,'ce8c9e5e-a3cf-11e2-a984-00163ec54890','Region A (Province)','Province',1,NULL,0,'2014-10-15 15:01:50','0000-00-00 00:00:00'),(3,'ce8c9f8a-a3cf-11e2-a984-00163ec54890','Region B (Health District)','District',1,NULL,0,'2014-10-15 15:01:50','0000-00-00 00:00:00'),(4,'ce8ca0ac-a3cf-11e2-a984-00163ec54890','City or Town','City',1,NULL,0,'2014-10-15 15:01:50','0000-00-00 00:00:00'),(5,'ce8ca1e2-a3cf-11e2-a984-00163ec54890','Training Name','__training_name__',1,NULL,0,'2010-09-20 00:30:37','0000-00-00 00:00:00'),(6,'ce8ca354-a3cf-11e2-a984-00163ec54890','Training Organizer','__training_organizer__',1,NULL,0,'2010-09-20 00:30:37','0000-00-00 00:00:00'),(7,'ce8ca4ee-a3cf-11e2-a984-00163ec54890','Training Level','__training_level__',1,NULL,0,'2010-09-20 00:30:37','0000-00-00 00:00:00'),(8,'ce8ca62e-a3cf-11e2-a984-00163ec54890','Pre Test Score','__pre_test_score__',1,NULL,0,'2010-09-20 00:30:37','0000-00-00 00:00:00'),(9,'ce8ca778-a3cf-11e2-a984-00163ec54890','Post Test Score','__post_test_score__',1,NULL,0,'2010-09-20 00:30:37','0000-00-00 00:00:00'),(10,'ce8ca8c2-a3cf-11e2-a984-00163ec54890','Training Custom 1','__custom_field_1__',1,NULL,0,'2010-09-20 00:30:37','0000-00-00 00:00:00'),(11,'ce8ca9f8-a3cf-11e2-a984-00163ec54890','Training Custom 2','__custom_field_2__',1,NULL,0,'2010-09-20 00:30:37','0000-00-00 00:00:00'),(12,'ce8cab2e-a3cf-11e2-a984-00163ec54890','National ID','ID Number',1,NULL,0,'2014-11-13 13:56:48','0000-00-00 00:00:00'),(13,'ce8cac64-a3cf-11e2-a984-00163ec54890','People Custom 1','__custom_field_1__',1,NULL,0,'2014-11-13 13:56:48','0000-00-00 00:00:00'),(14,'ce8cad90-a3cf-11e2-a984-00163ec54890','People Custom 2','__custom_field_2__',1,NULL,0,'2014-11-13 13:56:48','0000-00-00 00:00:00'),(15,'ce8cb07e-a3cf-11e2-a984-00163ec54890','Is Active','__is_active__',1,NULL,0,'2014-11-13 13:56:48','2008-04-28 20:41:05'),(16,'ce8cb1f0-a3cf-11e2-a984-00163ec54890','PEPFAR Category','__pepfar_category__',1,NULL,0,'2010-09-20 00:30:37','2008-04-28 20:42:56'),(17,'ce8cb2e0-a3cf-11e2-a984-00163ec54890','First Name','First Name',1,NULL,0,'2014-11-13 13:56:48','2008-12-03 18:12:29'),(18,'ce8cb3bc-a3cf-11e2-a984-00163ec54890','Middle Name','Middle Name',1,NULL,0,'2014-11-13 13:56:48','2008-12-03 18:12:38'),(19,'ce8cb498-a3cf-11e2-a984-00163ec54890','Last Name','Last Name',1,NULL,0,'2014-11-13 13:56:48','2008-12-03 18:12:46'),(20,'ce8cb574-a3cf-11e2-a984-00163ec54890','Training of Trainers','__training_of_trainers__',1,NULL,0,'2010-09-20 00:30:37','0000-00-00 00:00:00'),(21,'ce8cb664-a3cf-11e2-a984-00163ec54890','Course Objectives','__course_objectives__',1,NULL,0,'2010-09-20 00:30:37','0000-00-00 00:00:00'),(22,'ce8cb74a-a3cf-11e2-a984-00163ec54890','Training Category','__training_category__',1,NULL,0,'2010-09-20 00:30:37','0000-00-00 00:00:00'),(23,'ce8cb826-a3cf-11e2-a984-00163ec54890','Training Topic','__training_topic__',1,NULL,0,'2010-09-20 00:30:37','0000-00-00 00:00:00'),(24,'ce8cb934-a3cf-11e2-a984-00163ec54890','GOT Curriculum','__national_curriculum__',1,NULL,0,'2010-09-20 00:30:37','0000-00-00 00:00:00'),(25,'ce8cba10-a3cf-11e2-a984-00163ec54890','GOT Comment','__nat_curriculum_comment__',1,NULL,0,'2010-09-20 00:30:37','0000-00-00 00:00:00'),(26,'ce8cbad8-a3cf-11e2-a984-00163ec54890','Refresher Course','__refresher_course__',1,NULL,0,'2010-09-20 00:30:37','0000-00-00 00:00:00'),(27,'ce8cbbb4-a3cf-11e2-a984-00163ec54890','Comments','__comments__',1,NULL,0,'2010-09-20 00:30:37','0000-00-00 00:00:00'),(28,'ce8cbc86-a3cf-11e2-a984-00163ec54890','File Number','File Number',1,NULL,0,'2014-11-13 13:56:48','0000-00-00 00:00:00'),(30,'ce8cbd4e-a3cf-11e2-a984-00163ec54890','Primary Language','__1st_language__',1,NULL,0,'2010-09-20 00:30:37','2009-11-19 03:36:42'),(31,'ce8cbe20-a3cf-11e2-a984-00163ec54890','Secondary Language','__2nd_language__',1,NULL,0,'2010-09-20 00:30:37','2009-11-19 03:36:57'),(32,'ce8cbee8-a3cf-11e2-a984-00163ec54890','Funding Amount','__funding_amt__',1,NULL,0,'2010-09-20 00:30:37','2009-11-19 03:37:19'),(33,'ce8cbfba-a3cf-11e2-a984-00163ec54890','Training Method','__training_method__',1,NULL,0,'2010-09-20 00:30:37','2009-11-19 03:37:48'),(34,'ce8cc08c-a3cf-11e2-a984-00163ec54890','Title','Title',1,NULL,0,'2014-11-13 13:56:48','2009-11-20 20:59:19'),(35,'ce8cc15e-a3cf-11e2-a984-00163ec54890','Suffix','Gender',1,NULL,0,'2014-11-13 13:56:48','2009-11-20 20:59:30'),(36,'ce8cc23a-a3cf-11e2-a984-00163ec54890','Age','Age',NULL,NULL,0,'2009-11-20 20:59:57','2009-11-20 20:59:57'),(37,'ce8cc316-a3cf-11e2-a984-00163ec54890','Facility','Site',1,NULL,0,'2013-06-18 00:15:47','2009-11-20 22:24:55'),(38,'ce8cc3e8-a3cf-11e2-a984-00163ec54890','Region C (Local Region)','Sub-District',1,NULL,0,'2014-10-15 15:01:50','0000-00-00 00:00:00'),(39,'d0001cb6-a3cf-11e2-a984-00163ec54890','M&E Responsibility','M&E Responsibility',1,NULL,0,'2014-11-13 13:56:48','0000-00-00 00:00:00'),(40,'d0002e90-a3cf-11e2-a984-00163ec54890','Highest Education Level','Highest Education Level',1,NULL,0,'2014-11-13 13:56:48','0000-00-00 00:00:00'),(41,'d0003be2-a3cf-11e2-a984-00163ec54890','Reason Attending','Reason Attending',1,NULL,0,'2014-11-13 13:56:48','0000-00-00 00:00:00'),(42,'d00047ea-a3cf-11e2-a984-00163ec54890','Primary Responsibility','Primary Responsibility',1,NULL,0,'2014-11-13 13:56:48','0000-00-00 00:00:00'),(43,'d000574e-a3cf-11e2-a984-00163ec54890','Secondary Responsibility','Secondary Responsibility',1,NULL,0,'2014-11-13 13:56:48','0000-00-00 00:00:00'),(44,'d07dad16-a3cf-11e2-a984-00163ec54890','Facility Comments','Comments',1,NULL,0,'2013-06-18 00:15:47','0000-00-00 00:00:00'),(45,'d07dc22e-a3cf-11e2-a984-00163ec54890','Qualification Comments','Qualification Comments',1,NULL,0,'2014-11-13 13:56:48','0000-00-00 00:00:00'),(46,'d07dcec2-a3cf-11e2-a984-00163ec54890','Training Comments','Training Comments',NULL,NULL,0,'2013-04-13 00:19:32','0000-00-00 00:00:00'),(47,'d0e6b838-a3cf-11e2-a984-00163ec54890','Partner','Partner',1,NULL,0,'2014-11-13 13:57:42','2012-10-22 00:00:00'),(48,'d0e6c3dc-a3cf-11e2-a984-00163ec54890','Sub Partner','Sub Partner',1,NULL,0,'2014-11-13 13:57:42','2012-10-22 00:00:00'),(49,'d0e6ce9a-a3cf-11e2-a984-00163ec54890','Funder','Implementing Agency',1,NULL,0,'2014-11-13 13:57:42','2012-10-22 00:00:00'),(50,'d0e6daa2-a3cf-11e2-a984-00163ec54890','Full Time','Full Time',1,NULL,0,'2014-11-13 13:57:42','2012-10-22 00:00:00'),(51,'d0e6e52e-a3cf-11e2-a984-00163ec54890','Funded hours per week','Hours Worked per Week',1,NULL,0,'2014-11-13 13:57:42','2012-10-22 00:00:00'),(52,'d0e6ef88-a3cf-11e2-a984-00163ec54890','Staff Category','Statutory Council',1,NULL,0,'2014-11-13 13:57:42','2012-10-22 00:00:00'),(53,'d0e6fa00-a3cf-11e2-a984-00163ec54890','Annual Cost','Annual Cost',1,NULL,0,'2014-11-13 13:57:42','2012-10-22 00:00:00'),(54,'d0e70464-a3cf-11e2-a984-00163ec54890','Primary Role','Primary Role',1,NULL,0,'2014-11-13 13:57:42','2012-10-22 00:00:00'),(55,'d0e70eb4-a3cf-11e2-a984-00163ec54890','Importance','Importance',1,NULL,0,'2014-11-13 13:57:42','2012-10-22 00:00:00'),(56,'d0e7194a-a3cf-11e2-a984-00163ec54890','Intended Transition','Intended Transition',1,NULL,0,'2014-11-13 13:57:42','2012-10-22 00:00:00'),(57,'d0e724bc-a3cf-11e2-a984-00163ec54890','Incoming partner','Incoming partner',1,NULL,0,'2014-11-13 13:57:42','2012-10-22 00:00:00'),(58,'d0e73024-a3cf-11e2-a984-00163ec54890','Relationship','Relationship between CHW and formal health services',1,NULL,0,'2014-11-13 13:57:42','2012-10-22 00:00:00'),(59,'d0e73bbe-a3cf-11e2-a984-00163ec54890','Referral Mechanism','Referral Mechanism',1,NULL,0,'2014-11-13 13:57:42','2012-10-22 00:00:00'),(60,'d0e74636-a3cf-11e2-a984-00163ec54890','CHW Supervisor','CHW Supervisor',1,NULL,0,'2014-11-13 13:57:42','2012-10-22 00:00:00'),(61,'d0e750c2-a3cf-11e2-a984-00163ec54890','Trainings provided','What training do you provide',1,NULL,0,'2014-11-13 13:57:42','2012-10-22 00:00:00'),(62,'d0e75a4a-a3cf-11e2-a984-00163ec54890','Courses Completed','Courses Completed',1,NULL,0,'2014-11-13 13:57:42','2012-10-22 00:00:00'),(63,'d11a3c08-a3cf-11e2-a984-00163ec54890','Training','Training',1,NULL,0,'2014-10-15 15:01:50','2012-10-22 00:00:00'),(64,'d11a4a90-a3cf-11e2-a984-00163ec54890','Trainer','Trainer',1,NULL,0,'2014-10-15 15:01:50','2012-10-22 00:00:00'),(65,'d11a58f0-a3cf-11e2-a984-00163ec54890','Trainings','Trainings',1,NULL,0,'2014-10-15 15:01:50','2012-10-22 00:00:00'),(66,'d11a66f6-a3cf-11e2-a984-00163ec54890','Trainers','Trainers',1,NULL,0,'2014-10-15 15:01:50','2012-10-22 00:00:00'),(67,'d11a75d8-a3cf-11e2-a984-00163ec54890','Participant','Participant',1,NULL,0,'2014-10-15 15:01:50','2012-10-22 00:00:00'),(68,'d11a8348-a3cf-11e2-a984-00163ec54890','Participants','Participants',1,NULL,0,'2014-10-15 15:01:50','2012-10-22 00:00:00'),(69,'d11a909a-a3cf-11e2-a984-00163ec54890','Training Center','Training Center',1,NULL,0,'2014-10-15 15:01:50','2012-10-22 00:00:00'),(70,'d11a9e5a-a3cf-11e2-a984-00163ec54890','Region D','Local Region',2,NULL,0,'2013-04-21 21:26:56','2012-10-22 00:00:00'),(71,'d11aac4c-a3cf-11e2-a984-00163ec54890','Region E','Local Region',NULL,NULL,0,'2013-04-13 00:19:33','2012-10-22 00:00:00'),(72,'d11abe8a-a3cf-11e2-a984-00163ec54890','Region F','Local Region',NULL,NULL,0,'2013-04-13 00:19:33','2012-10-22 00:00:00'),(73,'d11acf74-a3cf-11e2-a984-00163ec54890','Region G','Local Region',NULL,NULL,0,'2013-04-13 00:19:33','2012-10-22 00:00:00'),(74,'d11ade74-a3cf-11e2-a984-00163ec54890','Region H','Local Region',NULL,NULL,0,'2013-04-13 00:19:33','2012-10-22 00:00:00'),(75,'d11aef0e-a3cf-11e2-a984-00163ec54890','Region I','Local Region',NULL,NULL,0,'2013-04-13 00:19:33','2012-10-22 00:00:00'),(76,'d11afddc-a3cf-11e2-a984-00163ec54890','People Custom 3','People Custom 3',1,NULL,0,'2014-11-13 13:56:48','2012-10-22 00:00:00'),(77,'d11b1128-a3cf-11e2-a984-00163ec54890','People Custom 4','People Custom 4',1,NULL,0,'2014-11-13 13:56:48','2012-10-22 00:00:00'),(78,'d11b1c90-a3cf-11e2-a984-00163ec54890','People Custom 5','People Custom 5',1,NULL,0,'2014-11-13 13:56:48','2012-10-22 00:00:00'),(79,'d11b278a-a3cf-11e2-a984-00163ec54890','Gender','Gender',1,NULL,0,'2014-11-13 13:56:48','2012-10-22 00:00:00'),(80,'d11b33c4-a3cf-11e2-a984-00163ec54890','Address 1','Address 1',1,NULL,0,'2014-11-13 13:56:48','2012-10-22 00:00:00'),(81,'d11b3f22-a3cf-11e2-a984-00163ec54890','Address 2','Address 2',1,NULL,0,'2014-11-13 13:56:48','2012-10-22 00:00:00'),(82,'d11b4aa8-a3cf-11e2-a984-00163ec54890','Home phone','Home phone',1,NULL,0,'2014-11-13 13:56:48','2012-10-22 00:00:00'),(83,'d141537e-a3cf-11e2-a984-00163ec54890','Training Custom 3','Custom field 3',NULL,NULL,0,'2013-04-13 00:19:33','2012-10-22 00:00:00'),(84,'d14186dc-a3cf-11e2-a984-00163ec54890','Training Custom 4','Custom field 4',NULL,NULL,0,'2013-04-13 00:19:33','2012-10-22 00:00:00'),(85,'d142cbf0-a3cf-11e2-a984-00163ec54890','Viewing Location','Viewing Location',NULL,NULL,0,'2013-04-13 00:19:33','2012-10-22 00:00:00'),(86,'d142d848-a3cf-11e2-a984-00163ec54890','Budget Code','Budget Code',NULL,NULL,0,'2013-04-13 00:19:33','2012-10-22 00:00:00'),(87,'d15a9df2-a3cf-11e2-a984-00163ec54890','Occupational category','Occupational category',NULL,NULL,0,'2013-04-13 00:19:34','2012-10-22 00:00:00'),(88,'d15aaed2-a3cf-11e2-a984-00163ec54890','Government employee','Government employee',NULL,NULL,0,'2013-04-13 00:19:34','2012-10-22 00:00:00'),(89,'d15ab968-a3cf-11e2-a984-00163ec54890','Professional bodies','Professional bodies',NULL,NULL,0,'2013-04-13 00:19:34','2012-10-22 00:00:00'),(90,'d15ac46c-a3cf-11e2-a984-00163ec54890','Race','Race',1,NULL,0,'2014-11-13 13:57:42','2012-10-22 00:00:00'),(91,'d15acf48-a3cf-11e2-a984-00163ec54890','Disability','Disability',1,NULL,0,'2014-11-13 13:57:42','2012-10-22 00:00:00'),(92,'d15ad9fc-a3cf-11e2-a984-00163ec54890','Nurse trainer type','Nurse trainer type',NULL,NULL,0,'2013-04-13 00:19:34','2012-10-22 00:00:00'),(93,'d15ae474-a3cf-11e2-a984-00163ec54890','Year you started providing care','Year you started providing care',NULL,NULL,0,'2013-04-13 00:19:34','2012-10-22 00:00:00'),(94,'d15aeed8-a3cf-11e2-a984-00163ec54890','Rank patient groups based on time','Rank patient groups based on time',NULL,NULL,0,'2013-04-13 00:19:34','2012-10-22 00:00:00'),(95,'d15af932-a3cf-11e2-a984-00163ec54890','Supervised','Supervised',NULL,NULL,0,'2013-04-13 00:19:34','2012-10-22 00:00:00'),(96,'d15b0378-a3cf-11e2-a984-00163ec54890','Indicate the training you received','Indicate the training you received',NULL,NULL,0,'2013-04-13 00:19:34','2012-10-22 00:00:00'),(97,'d15b0c42-a3cf-11e2-a984-00163ec54890','Facility department','Facility department',NULL,NULL,0,'2013-04-13 00:19:34','2012-10-22 00:00:00'),(98,'50301e50-a99c-11e2-a984-00163ec54890','Other ID','Passport Number',1,NULL,0,'2014-11-13 13:57:42','2012-10-22 00:00:00'),(99,'5030af64-a99c-11e2-a984-00163ec54890','Employee Date of Birth','Date of Birth',NULL,NULL,0,'2013-04-20 09:25:59','2012-10-22 00:00:00'),(100,'5030bb12-a99c-11e2-a984-00163ec54890','Disability','Disability',1,NULL,0,'2014-11-13 13:57:42','2012-10-22 00:00:00'),(101,'5030c940-a99c-11e2-a984-00163ec54890','Disability Comments','Disability Comments',1,NULL,0,'2014-11-13 13:57:42','2012-10-22 00:00:00'),(102,'5030d6b0-a99c-11e2-a984-00163ec54890','Race','Race',1,NULL,0,'2014-11-13 13:57:42','2012-10-22 00:00:00'),(103,'5030e42a-a99c-11e2-a984-00163ec54890','Registration Number','Statutory Council Registration Number',1,NULL,0,'2014-11-13 13:57:42','2012-10-22 00:00:00'),(104,'5030f244-a99c-11e2-a984-00163ec54890','Salary','Annual Salary (R)',1,NULL,0,'2014-11-13 13:57:42','2012-10-22 00:00:00'),(105,'5030ff78-a99c-11e2-a984-00163ec54890','Benefits','Annual Benefits (R)',1,NULL,0,'2014-11-13 13:57:42','2012-10-22 00:00:00'),(106,'50310aa4-a99c-11e2-a984-00163ec54890','Additional Expenses','Additional Expenses (R)',1,NULL,0,'2014-11-13 13:57:42','2012-10-22 00:00:00'),(107,'50311580-a99c-11e2-a984-00163ec54890','Stipend','Annual Stipend (R)',1,NULL,0,'2014-11-13 13:57:42','2012-10-22 00:00:00'),(108,'503125c0-a99c-11e2-a984-00163ec54890','Staff Cadre','Occupational Classification',1,NULL,0,'2014-11-13 13:57:42','2012-10-22 00:00:00'),(109,'174738d6-b116-11e2-aba3-00163ec54890','Employee Comments','Comments',NULL,1,0,'2013-04-29 21:45:21','0000-00-00 00:00:00'),(110,'582bfd00-b116-11e2-aba3-00163ec54890','Transition Confirmed','Transition Confirmed',NULL,1,0,'2013-04-29 21:47:10','0000-00-00 00:00:00'),(111,'535c0748-b1f2-11e2-aba3-00163ec54890','Sponsor Date','Sponsor Date',1,NULL,0,'2013-06-18 00:15:47','0000-00-00 00:00:00'),(112,'160ba432-b35c-11e2-aba3-00163ec54890','Employee Nationality','Nationality',1,NULL,0,'2014-11-13 13:57:42','0000-00-00 00:00:00'),(113,'fffefba0-c268-11e2-8c5a-00163ec54890','Type of Partner','Type of Partner',1,NULL,0,'2014-11-13 13:57:42','0000-00-00 00:00:00'),(114,'ffff0cda-c268-11e2-8c5a-00163ec54890','Employee Based at','Based at',1,NULL,0,'2014-11-13 13:57:42','0000-00-00 00:00:00'),(115,'68606222-e282-11e2-8c5a-00163ec54890','Facility Type','Site Type',NULL,NULL,0,'2013-07-01 19:14:09','0000-00-00 00:00:00'),(116,'e7af99a2-f309-11e2-8c5a-00163ec54890','Employee Local Currency','S.A Rands',1,NULL,0,'2014-11-13 13:57:42','2012-10-22 00:00:00'),(117,'f75cff30-5307-11e3-a9aa-00163ec54890','Facility Custom 1','nCustom 1',NULL,NULL,0,'2013-11-21 23:52:23','0000-00-00 00:00:00'),(118,'f3cd706a-271f-11e4-92a3-00163ec54890','ps clinical allocation','Clinical Allocation',1,NULL,0,'2014-07-25 13:40:02','2014-07-18 00:00:00'),(119,'f3cd78b2-271f-11e4-92a3-00163ec54890','ps local address','Local Address',1,NULL,0,'2014-07-25 13:40:02','2014-07-18 00:00:00'),(120,'f3cd7d1c-271f-11e4-92a3-00163ec54890','ps last school attended','Last School Attended',1,NULL,0,'2014-07-25 13:40:02','2014-07-21 00:00:00'),(121,'f3cd8172-271f-11e4-92a3-00163ec54890','ps high school completion date','High School Completion Date',1,NULL,0,'2014-07-25 13:40:02','2014-07-18 00:00:00'),(122,'f3cd85b4-271f-11e4-92a3-00163ec54890','ps last school attended','Last School Attended',1,NULL,0,'2014-07-25 13:40:02','2014-07-21 00:00:00'),(123,'f3cd8ac8-271f-11e4-92a3-00163ec54890','ps school start date','Admission to School Date',1,NULL,0,'2014-07-25 13:40:02','2014-07-21 00:00:00'),(124,'f3cd8f14-271f-11e4-92a3-00163ec54890','ps equivalence','Equivalence',1,NULL,0,'2014-07-25 13:40:02','2014-07-21 00:00:00'),(125,'f3cd9356-271f-11e4-92a3-00163ec54890','ps last university attended','Last University Attended',1,NULL,0,'2014-07-25 13:40:02','2014-07-21 00:00:00'),(126,'f3cd9798-271f-11e4-92a3-00163ec54890','ps person in charge','Person In Charge',1,NULL,0,'2014-07-25 13:40:02','2014-07-21 00:00:00'),(127,'f3cd9be4-271f-11e4-92a3-00163ec54890','ps license and registration','License And Registration',1,NULL,0,'2014-07-25 13:40:02','2014-07-21 00:00:00'),(128,'f3cda030-271f-11e4-92a3-00163ec54890','ps permanent address','Permanent Address',1,NULL,0,'2014-07-25 13:40:02','2014-07-21 00:00:00'),(129,'f3cda486-271f-11e4-92a3-00163ec54890','ps religious denomination','Religious Denomination',1,NULL,0,'2014-07-25 13:40:02','2014-07-21 00:00:00'),(130,'f3cda8d2-271f-11e4-92a3-00163ec54890','ps program enrolled in','Program Enrolled In',1,NULL,0,'2014-07-25 13:40:02','2014-07-21 00:00:00'),(131,'f3cdad1e-271f-11e4-92a3-00163ec54890','ps nationality','Nationality',1,NULL,0,'2014-07-25 13:40:02','2014-07-21 00:00:00'),(132,'f3cdb26e-271f-11e4-92a3-00163ec54890','ps custom field 1','Custom Field 1',1,NULL,0,'2014-07-25 13:40:02','2014-07-22 00:00:00'),(133,'f3cdb6c4-271f-11e4-92a3-00163ec54890','ps custom field 2','Custom Field 2',1,NULL,0,'2014-07-25 13:40:02','2014-07-22 00:00:00'),(134,'f3cdbc82-271f-11e4-92a3-00163ec54890','ps custom field 3','Custom Field 3',1,NULL,0,'2014-07-25 13:40:02','2014-07-22 00:00:00'),(135,'f3cdc13c-271f-11e4-92a3-00163ec54890','ps marital status','Marital Status',1,NULL,0,'2014-07-25 13:40:02','2014-07-22 00:00:00'),(136,'f3cdc60a-271f-11e4-92a3-00163ec54890','ps spouse name','Spouse Name',1,NULL,0,'2014-07-25 13:40:02','2014-07-22 00:00:00'),(137,'f3cdca60-271f-11e4-92a3-00163ec54890','ps specialty','Specialty',1,NULL,0,'2014-07-25 13:40:02','2014-07-22 00:00:00'),(138,'f3cdceb6-271f-11e4-92a3-00163ec54890','ps contract type','Type Of Contract',1,NULL,0,'2014-07-25 13:40:02','2014-07-22 00:00:00'),(139,'6c855df6-27ca-11e4-8854-00163ec54890','ps tutor','Teacher',1,NULL,0,'2014-08-19 17:58:28','0000-00-00 00:00:00'),(140,'6c85659e-27ca-11e4-8854-00163ec54890','ps institution','Training Centre',1,NULL,0,'2014-08-19 17:58:28','0000-00-00 00:00:00'),(141,'6c856b3e-27ca-11e4-8854-00163ec54890','ps zip code','Postal Code / ZIP',1,NULL,0,'2014-08-19 17:58:28','0000-00-00 00:00:00'),(142,'f834a062-4d9c-11e4-b5dd-00163ec54890','Facility Commodity Column Table Commodity Name','Commodity Name',1,NULL,0,'2014-10-06 21:08:50','0000-00-00 00:00:00'),(143,'f84d6ad4-4d9c-11e4-b5dd-00163ec54890','Facility Commodity Column Table Date','Date',1,NULL,0,'2014-10-06 21:08:50','0000-00-00 00:00:00'),(144,'f8643142-4d9c-11e4-b5dd-00163ec54890','Facility Commodity Column Table Consumption','Consumption',1,NULL,0,'2014-10-06 21:08:50','0000-00-00 00:00:00'),(145,'f87b7bb8-4d9c-11e4-b5dd-00163ec54890','Facility Commodity Column Table Out of Stock','Out of Stock',1,NULL,0,'2014-10-06 21:08:50','0000-00-00 00:00:00'),(146,'08dd61f6-4d9d-11e4-b5dd-00163ec54890','Employee','Position',1,NULL,0,'2014-10-15 15:01:50','0000-00-00 00:00:00'),(147,'08fb3776-4d9d-11e4-b5dd-00163ec54890','Employees','Positions',1,NULL,0,'2014-10-15 15:01:50','0000-00-00 00:00:00'),(148,'d6480ee2-4ff6-11e4-b5dd-00163ec54890','Facility Commodity Column Table Commodity Name','Commodity Name',1,NULL,0,'2014-10-09 20:57:10','0000-00-00 00:00:00'),(149,'d649a6da-4ff6-11e4-b5dd-00163ec54890','Facility Commodity Column Table Date','Date',1,NULL,0,'2014-10-09 20:57:10','0000-00-00 00:00:00'),(150,'d649ac7a-4ff6-11e4-b5dd-00163ec54890','Facility Commodity Column Table Consumption','Consumption',1,NULL,0,'2014-10-09 20:57:10','0000-00-00 00:00:00'),(151,'d649b30a-4ff6-11e4-b5dd-00163ec54890','Facility Commodity Column Table Out of Stock','Out of Stock',1,NULL,0,'2014-10-09 20:57:10','0000-00-00 00:00:00'),(152,'d649b8d2-4ff6-11e4-b5dd-00163ec54890','Employee','Position',1,NULL,0,'2014-10-15 15:01:50','0000-00-00 00:00:00'),(153,'d649be36-4ff6-11e4-b5dd-00163ec54890','Employees','Positions',1,NULL,0,'2014-10-15 15:01:50','0000-00-00 00:00:00'),(154,'d65e3f6e-4ff6-11e4-b5dd-00163ec54890','ps exam mark','Exam Mark',1,NULL,0,'2014-10-09 20:57:10','0000-00-00 00:00:00'),(155,'d65e4612-4ff6-11e4-b5dd-00163ec54890','ps ca mark','CA Mark',1,NULL,0,'2014-10-09 20:57:10','0000-00-00 00:00:00'),(156,'d65e4bc6-4ff6-11e4-b5dd-00163ec54890','ps credits','Credits',1,NULL,0,'2014-10-09 20:57:10','0000-00-00 00:00:00'),(157,'923d3464-6af6-11e4-b5dd-00163ec54890','Label Email Report Level 1','Federal',1,NULL,0,'2014-11-13 05:33:17','0000-00-00 00:00:00'),(158,'9247246a-6af6-11e4-b5dd-00163ec54890','Label Email Report Level 2','State',1,NULL,0,'2014-11-13 05:33:17','0000-00-00 00:00:00'),(159,'925588e8-6af6-11e4-b5dd-00163ec54890','Label Email Report Level 3','LGA',1,NULL,0,'2014-11-13 05:33:17','0000-00-00 00:00:00'),(160,'925f7aec-6af6-11e4-b5dd-00163ec54890','Emails Report Level 1','',1,NULL,0,'2014-11-13 05:33:17','0000-00-00 00:00:00'),(161,'926966ec-6af6-11e4-b5dd-00163ec54890','Emails Report Level 2','',1,NULL,0,'2014-11-13 05:33:17','0000-00-00 00:00:00'),(162,'92735904-6af6-11e4-b5dd-00163ec54890','Emails Report Level 3','',1,NULL,0,'2014-11-13 05:33:17','0000-00-00 00:00:00');
/*!40000 ALTER TABLE `translation` ENABLE KEYS */;
UNLOCK TABLES;

/*!50003 SET @SAVE_SQL_MODE=@@SQL_MODE*/;

DELIMITER ;;
/*!50003 SET SESSION SQL_MODE="NO_AUTO_VALUE_ON_ZERO" */;;
/*!50003 CREATE */ /*!50017 DEFINER=`root`@`localhost` */ /*!50003 TRIGGER `translation_insert` BEFORE INSERT ON `translation` FOR EACH ROW BEGIN

SET NEW.`uuid` = IFNULL(NEW.`uuid`,UUID());

END */;;

DELIMITER ;
/*!50003 SET SESSION SQL_MODE=@SAVE_SQL_MODE*/;

--
-- Table structure for table `tutor`
--

DROP TABLE IF EXISTS `tutor`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tutor` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `personid` varchar(10) NOT NULL default '',
  `is_keypersonal` tinyint(1) NOT NULL default '0',
  `tutorsince` int(10) unsigned NOT NULL default '0',
  `tutortimehere` int(10) unsigned NOT NULL default '0',
  `degree` varchar(150) NOT NULL default '',
  `degreeinst` varchar(150) NOT NULL default '',
  `degreeyear` int(10) unsigned NOT NULL default '0',
  `languagesspoken` varchar(255) NOT NULL default '',
  `positionsheld` text NOT NULL,
  `comments` text NOT NULL,
  `facilityid` int(10) unsigned NOT NULL default '0',
  `cadreid` int(10) unsigned NOT NULL default '0',
  `nationalityid` int(10) unsigned NOT NULL default '0',
  `institutionid` int(10) unsigned default '0',
  `specialty` varchar(100) NOT NULL,
  `contract_type` varchar(100) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `idx2` (`facilityid`,`personid`,`cadreid`,`nationalityid`),
  KEY `is_keypersonalidx` (`is_keypersonal`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tutor_contract_option`
--

DROP TABLE IF EXISTS `tutor_contract_option`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tutor_contract_option` (
  `id` int(11) NOT NULL auto_increment,
  `created_by` int(11) default NULL,
  `is_deleted` tinyint(1) NOT NULL default '0',
  `modified_by` int(11) default NULL,
  `uuid` varchar(36) default NULL,
  `contract_phrase` varchar(128) NOT NULL,
  `timestamp_updated` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `timestamp_created` timestamp NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tutor_specialty_option`
--

DROP TABLE IF EXISTS `tutor_specialty_option`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tutor_specialty_option` (
  `id` int(11) NOT NULL auto_increment,
  `created_by` int(11) default NULL,
  `is_deleted` tinyint(1) NOT NULL default '0',
  `modified_by` int(11) default NULL,
  `uuid` varchar(36) default NULL,
  `specialty_phrase` varchar(128) NOT NULL,
  `timestamp_updated` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `timestamp_created` timestamp NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user` (
  `id` int(11) NOT NULL auto_increment,
  `uuid` char(36) default NULL,
  `username` varchar(32) NOT NULL default '',
  `password` varchar(41) NOT NULL default '',
  `email` varchar(64) NOT NULL default '',
  `first_name` varchar(32) NOT NULL default '',
  `last_name` varchar(32) NOT NULL default '',
  `person_id` int(11) default NULL,
  `locale` varchar(255) default '',
  `modified_by` int(11) default NULL,
  `created_by` int(11) default NULL,
  `is_blocked` tinyint(1) NOT NULL default '0',
  `timestamp_updated` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `timestamp_created` timestamp NOT NULL default '0000-00-00 00:00:00',
  `timestamp_last_login` timestamp NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `username_idx` (`username`),
  UNIQUE KEY `email_idx` (`email`),
  UNIQUE KEY `uuid_idx` (`uuid`),
  KEY `modified_by` (`modified_by`),
  KEY `created_by` (`created_by`)
) ENGINE=MyISAM AUTO_INCREMENT=206 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` VALUES (0,'ce906ca0-a3cf-11e2-a984-00163ec54890','system','','','','',NULL,'',NULL,NULL,0,'2008-03-11 21:17:59','2008-03-11 21:17:59','0000-00-00 00:00:00'),(1,'ce906f16-a3cf-11e2-a984-00163ec54890','admin','6a204bd89f3c8348afd5c77c717a097a','admin@example.net','Admin','Admin',NULL,'',1,NULL,0,'2014-11-13 19:20:40','2008-02-27 20:15:43','2014-11-13 19:20:40');
/*!40000 ALTER TABLE `user` ENABLE KEYS */;
UNLOCK TABLES;

/*!50003 SET @SAVE_SQL_MODE=@@SQL_MODE*/;

DELIMITER ;;
/*!50003 SET SESSION SQL_MODE="NO_AUTO_VALUE_ON_ZERO" */;;
/*!50003 CREATE */ /*!50017 DEFINER=`root`@`localhost` */ /*!50003 TRIGGER `user_insert` BEFORE INSERT ON `user` FOR EACH ROW BEGIN

SET NEW.`uuid` = IFNULL(NEW.`uuid`,UUID());

END */;;

DELIMITER ;
/*!50003 SET SESSION SQL_MODE=@SAVE_SQL_MODE*/;

--
-- Table structure for table `user_to_acl`
--

DROP TABLE IF EXISTS `user_to_acl`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_to_acl` (
  `id` int(11) NOT NULL auto_increment,
  `acl_id` enum('ps_view_student_grades','ps_edit_student_grades','ps_view_student','ps_edit_student','in_service','pre_service','employees_module','edit_employee','view_training_location','view_employee','view_mechanisms','view_partners','edit_course','view_course','duplicate_training','approve_trainings','master_approver','edit_people','view_people','edit_training_location','edit_facility','view_facility','view_create_reports','training_organizer_option_all','training_title_option_all','use_offline_app','admin_files','facility_and_person_approver','edit_evaluations','edit_country_options','acl_editor_training_category','acl_editor_people_qualifications','acl_editor_people_responsibility','acl_editor_training_organizer','acl_editor_people_trainer','acl_editor_training_topic','acl_editor_people_titles','acl_editor_training_level','acl_editor_refresher_course','acl_editor_people_trainer_skills','acl_editor_pepfar_category','acl_editor_people_languages','acl_editor_funding','acl_editor_people_affiliations','acl_editor_recommended_topic','acl_editor_nationalcurriculum','acl_editor_people_suffix','acl_editor_method','acl_editor_people_active_trainer','acl_editor_facility_types','acl_editor_ps_classes','acl_editor_facility_sponsors','acl_editor_ps_cadres','acl_editor_ps_degrees','acl_editor_ps_funding','acl_editor_ps_institutions','acl_editor_ps_languages','acl_editor_ps_nationalities','acl_editor_ps_joindropreasons','acl_editor_ps_sponsors','acl_editor_ps_tutortypes','acl_editor_ps_coursetypes','acl_editor_ps_religions','add_edit_users','acl_admin_training','acl_admin_people','acl_admin_facilities','import_training','import_training_location','import_facility','import_person','edit_partners','edit_mechanisms','add_new_facility') NOT NULL default 'view_course',
  `user_id` int(11) NOT NULL,
  `created_by` int(11) default NULL,
  `timestamp_created` timestamp NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`),
  KEY `created_by` (`created_by`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=3186 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_to_acl`
--

LOCK TABLES `user_to_acl` WRITE;
/*!40000 ALTER TABLE `user_to_acl` DISABLE KEYS */;
INSERT INTO `user_to_acl` VALUES (1,'add_edit_users',1,NULL,'2008-04-28 20:03:31'),(3,'edit_course',1,1,'2008-04-28 20:16:19'),(4,'edit_people',1,1,'2008-04-28 20:16:19'),(5,'view_create_reports',1,1,'2008-04-28 20:16:19'),(6,'training_organizer_option_all',1,NULL,'2008-12-03 18:10:51'),(9,'approve_trainings',1,1,'2009-11-20 22:39:34'),(14,'training_title_option_all',1,1,'2009-12-08 20:11:00'),(15,'edit_country_options',1,1,'2009-12-08 20:11:00'),(136,'edit_facility',4,2,'2013-04-21 21:19:07'),(18,'',1,1,'2013-04-13 00:19:32'),(19,'',1,1,'2013-04-13 00:19:32'),(20,'',1,1,'2013-04-13 00:19:32'),(21,'',1,1,'2013-04-13 00:19:32'),(22,'',1,1,'2013-04-13 00:19:32'),(23,'',1,1,'2013-04-13 00:19:32'),(24,'',1,1,'2013-04-13 00:19:32'),(25,'',1,1,'2013-04-13 00:19:32'),(26,'acl_admin_training',2,1,'2013-04-19 16:28:10'),(27,'acl_editor_training_organizer',2,1,'2013-04-19 16:28:10'),(28,'acl_editor_nationalcurriculum',2,1,'2013-04-19 16:28:10'),(29,'duplicate_training',2,1,'2013-04-19 16:28:10'),(30,'acl_editor_people_responsibility',2,1,'2013-04-19 16:28:10'),(31,'edit_facility',2,1,'2013-04-19 16:28:10'),(32,'acl_editor_pepfar_category',2,1,'2013-04-19 16:28:10'),(33,'import_person',2,1,'2013-04-19 16:28:10'),(34,'acl_editor_ps_funding',2,1,'2013-04-19 16:28:10'),(35,'acl_editor_ps_religions',2,1,'2013-04-19 16:28:10'),(36,'view_create_reports',2,1,'2013-04-19 16:28:10'),(37,'acl_admin_facilities',2,1,'2013-04-19 16:28:10'),(38,'acl_editor_training_category',2,1,'2013-04-19 16:28:10'),(39,'acl_editor_funding',2,1,'2013-04-19 16:28:10'),(40,'admin_files',2,1,'2013-04-19 16:28:10'),(41,'acl_editor_people_languages',2,1,'2013-04-19 16:28:10'),(42,'edit_employee',2,1,'2013-04-19 16:28:10'),(43,'acl_editor_people_trainer',2,1,'2013-04-19 16:28:10'),(44,'acl_editor_ps_coursetypes',2,1,'2013-04-19 16:28:10'),(133,'edit_employee',1,1,'2013-04-19 16:58:44'),(46,'acl_editor_ps_languages',2,1,'2013-04-19 16:28:10'),(47,'use_offline_app',2,1,'2013-04-19 16:28:10'),(48,'acl_editor_recommended_topic',2,1,'2013-04-19 16:28:10'),(49,'acl_editor_facility_sponsors',2,1,'2013-04-19 16:28:10'),(50,'acl_editor_training_topic',2,1,'2013-04-19 16:28:10'),(51,'acl_editor_people_active_trainer',2,1,'2013-04-19 16:28:10'),(52,'edit_country_options',2,1,'2013-04-19 16:28:10'),(53,'acl_editor_people_suffix',2,1,'2013-04-19 16:28:10'),(54,'edit_people',2,1,'2013-04-19 16:28:10'),(55,'acl_editor_ps_cadres',2,1,'2013-04-19 16:28:10'),(56,'import_training',2,1,'2013-04-19 16:28:10'),(57,'acl_editor_ps_institutions',2,1,'2013-04-19 16:28:10'),(58,'training_organizer_option_all',2,1,'2013-04-19 16:28:10'),(59,'acl_editor_ps_sponsors',2,1,'2013-04-19 16:28:10'),(60,'acl_admin_people',2,1,'2013-04-19 16:28:10'),(61,'acl_editor_training_level',2,1,'2013-04-19 16:28:10'),(62,'acl_editor_method',2,1,'2013-04-19 16:28:10'),(63,'acl_editor_people_qualifications',2,1,'2013-04-19 16:28:10'),(64,'acl_editor_people_trainer_skills',2,1,'2013-04-19 16:28:10'),(65,'import_facility',2,1,'2013-04-19 16:28:10'),(66,'acl_editor_ps_degrees',2,1,'2013-04-19 16:28:10'),(67,'acl_editor_ps_nationalities',2,1,'2013-04-19 16:28:10'),(68,'acl_editor_refresher_course',2,1,'2013-04-19 16:28:10'),(69,'acl_editor_facility_types',2,1,'2013-04-19 16:28:10'),(70,'add_edit_users',2,1,'2013-04-19 16:28:10'),(71,'acl_editor_people_affiliations',2,1,'2013-04-19 16:28:10'),(72,'edit_course',2,1,'2013-04-19 16:28:10'),(73,'acl_editor_people_titles',2,1,'2013-04-19 16:28:10'),(74,'acl_editor_ps_classes',2,1,'2013-04-19 16:28:10'),(75,'import_training_location',2,1,'2013-04-19 16:28:10'),(76,'acl_editor_ps_joindropreasons',2,1,'2013-04-19 16:28:10'),(77,'training_title_option_all',2,1,'2013-04-19 16:28:10'),(78,'acl_editor_ps_tutortypes',2,1,'2013-04-19 16:28:10'),(84,'edit_facility',3,1,'2013-04-19 16:29:18'),(89,'view_create_reports',3,1,'2013-04-19 16:29:18'),(93,'admin_files',3,1,'2013-04-19 16:29:18'),(95,'edit_employee',3,1,'2013-04-19 16:29:18'),(134,'edit_course',4,2,'2013-04-21 21:19:07'),(102,'acl_editor_facility_sponsors',3,1,'2013-04-19 16:29:18'),(107,'edit_people',3,1,'2013-04-19 16:29:18'),(111,'training_organizer_option_all',3,1,'2013-04-19 16:29:18'),(125,'edit_course',3,1,'2013-04-19 16:29:18'),(137,'view_create_reports',4,2,'2013-04-21 21:19:07'),(157,'edit_training_location',5,2,'2013-07-08 09:36:24'),(139,'edit_employee',4,2,'2013-04-21 21:19:07'),(141,'edit_people',4,2,'2013-04-21 21:19:07'),(156,'acl_editor_people_titles',5,2,'2013-07-08 09:36:24'),(792,'admin_files',10,5,'2013-07-31 09:03:54'),(1212,'view_create_reports',53,18,'2014-01-29 06:11:44'),(1705,'acl_editor_facility_sponsors',106,7,'2014-02-05 04:52:53'),(146,'edit_employee',15,1,'2013-04-30 00:58:58'),(147,'edit_employee',52,1,'2013-04-30 00:58:58'),(148,'edit_employee',105,1,'2013-04-30 00:58:58'),(737,'admin_files',9,5,'2013-07-30 07:31:02'),(1211,'edit_facility',53,18,'2014-01-29 06:11:44'),(1704,'edit_employee',106,7,'2014-02-05 04:52:53'),(152,'edit_employee',15,1,'2013-04-30 01:02:37'),(153,'edit_employee',52,1,'2013-04-30 01:02:37'),(154,'edit_employee',105,1,'2013-04-30 01:02:37'),(155,'edit_facility',1,1,'2013-05-24 14:07:09'),(158,'acl_editor_ps_classes',5,2,'2013-07-08 09:36:24'),(159,'import_training_location',5,2,'2013-07-08 09:36:24'),(160,'acl_editor_ps_joindropreasons',5,2,'2013-07-08 09:36:24'),(161,'training_title_option_all',5,2,'2013-07-08 09:36:24'),(162,'acl_editor_ps_tutortypes',5,2,'2013-07-08 09:36:24'),(163,'acl_admin_training',5,2,'2013-07-08 09:36:24'),(164,'acl_editor_training_organizer',5,2,'2013-07-08 09:36:24'),(165,'acl_editor_nationalcurriculum',5,2,'2013-07-08 09:36:24'),(166,'duplicate_training',5,2,'2013-07-08 09:36:24'),(167,'acl_editor_people_responsibility',5,2,'2013-07-08 09:36:24'),(168,'edit_facility',5,2,'2013-07-08 09:36:24'),(169,'acl_editor_pepfar_category',5,2,'2013-07-08 09:36:24'),(170,'import_person',5,2,'2013-07-08 09:36:24'),(171,'acl_editor_ps_funding',5,2,'2013-07-08 09:36:24'),(172,'acl_editor_ps_religions',5,2,'2013-07-08 09:36:24'),(173,'view_create_reports',5,2,'2013-07-08 09:36:24'),(174,'acl_admin_facilities',5,2,'2013-07-08 09:36:24'),(175,'acl_editor_training_category',5,2,'2013-07-08 09:36:24'),(176,'acl_editor_funding',5,2,'2013-07-08 09:36:24'),(177,'admin_files',5,2,'2013-07-08 09:36:24'),(178,'acl_editor_people_languages',5,2,'2013-07-08 09:36:24'),(179,'edit_employee',5,2,'2013-07-08 09:36:24'),(180,'acl_editor_people_trainer',5,2,'2013-07-08 09:36:24'),(181,'acl_editor_ps_coursetypes',5,2,'2013-07-08 09:36:24'),(182,'acl_editor_ps_languages',5,2,'2013-07-08 09:36:24'),(183,'use_offline_app',5,2,'2013-07-08 09:36:24'),(184,'acl_editor_recommended_topic',5,2,'2013-07-08 09:36:24'),(796,'acl_editor_facility_sponsors',6,5,'2013-08-01 13:37:42'),(186,'acl_editor_training_topic',5,2,'2013-07-08 09:36:24'),(187,'acl_editor_people_active_trainer',5,2,'2013-07-08 09:36:24'),(188,'edit_country_options',5,2,'2013-07-08 09:36:24'),(189,'acl_editor_people_suffix',5,2,'2013-07-08 09:36:24'),(190,'edit_people',5,2,'2013-07-08 09:36:24'),(191,'acl_editor_ps_cadres',5,2,'2013-07-08 09:36:24'),(192,'import_training',5,2,'2013-07-08 09:36:24'),(193,'acl_editor_ps_institutions',5,2,'2013-07-08 09:36:24'),(194,'training_organizer_option_all',5,2,'2013-07-08 09:36:24'),(195,'acl_editor_ps_sponsors',5,2,'2013-07-08 09:36:24'),(196,'acl_admin_people',5,2,'2013-07-08 09:36:24'),(197,'acl_editor_training_level',5,2,'2013-07-08 09:36:24'),(198,'acl_editor_method',5,2,'2013-07-08 09:36:24'),(199,'approve_trainings',5,2,'2013-07-08 09:36:24'),(200,'acl_editor_people_qualifications',5,2,'2013-07-08 09:36:24'),(201,'edit_evaluations',5,2,'2013-07-08 09:36:24'),(202,'acl_editor_people_trainer_skills',5,2,'2013-07-08 09:36:24'),(203,'import_facility',5,2,'2013-07-08 09:36:24'),(204,'acl_editor_ps_degrees',5,2,'2013-07-08 09:36:24'),(205,'acl_editor_ps_nationalities',5,2,'2013-07-08 09:36:24'),(206,'acl_editor_refresher_course',5,2,'2013-07-08 09:36:24'),(207,'acl_editor_facility_types',5,2,'2013-07-08 09:36:24'),(208,'add_edit_users',5,2,'2013-07-08 09:36:24'),(209,'acl_editor_people_affiliations',5,2,'2013-07-08 09:36:24'),(210,'edit_course',5,2,'2013-07-08 09:36:24'),(211,'edit_course',6,5,'2013-07-15 13:17:14'),(212,'edit_training_location',6,5,'2013-07-15 13:17:14'),(782,'acl_editor_people_responsibility',7,5,'2013-07-30 12:50:54'),(214,'edit_facility',6,5,'2013-07-15 13:17:14'),(215,'view_create_reports',6,5,'2013-07-15 13:17:14'),(772,'acl_editor_people_affiliations',7,5,'2013-07-30 12:50:54'),(217,'edit_employee',6,5,'2013-07-15 13:17:14'),(787,'admin_files',12,5,'2013-07-30 13:42:45'),(781,'acl_editor_nationalcurriculum',7,5,'2013-07-30 12:50:54'),(756,'acl_editor_ps_institutions',7,5,'2013-07-30 12:50:54'),(221,'edit_people',6,5,'2013-07-15 13:17:14'),(750,'acl_editor_training_topic',7,5,'2013-07-30 12:50:54'),(764,'edit_evaluations',7,5,'2013-07-30 12:50:54'),(746,'facility_and_person_approver',7,5,'2013-07-30 12:50:54'),(765,'acl_editor_people_trainer_skills',7,5,'2013-07-30 12:50:54'),(754,'acl_editor_ps_cadres',7,5,'2013-07-30 12:50:54'),(740,'acl_editor_ps_religions',7,5,'2013-07-30 12:50:54'),(760,'acl_editor_training_level',7,5,'2013-07-30 12:50:54'),(236,'edit_course',7,5,'2013-07-18 08:20:32'),(767,'acl_editor_ps_degrees',7,5,'2013-07-30 12:50:54'),(238,'edit_training_location',7,5,'2013-07-18 08:20:32'),(753,'acl_editor_people_suffix',7,5,'2013-07-30 12:50:54'),(733,'acl_editor_facility_sponsors',15,5,'2013-07-29 14:10:25'),(745,'acl_editor_people_trainer',7,5,'2013-07-30 12:50:54'),(777,'training_title_option_all',7,5,'2013-07-30 12:50:54'),(743,'acl_editor_funding',7,5,'2013-07-30 12:50:54'),(739,'acl_editor_ps_funding',7,5,'2013-07-30 12:50:54'),(770,'acl_editor_facility_types',7,5,'2013-07-30 12:50:54'),(758,'acl_editor_ps_sponsors',7,5,'2013-07-30 12:50:54'),(247,'duplicate_training',7,5,'2013-07-18 08:20:32'),(771,'add_edit_users',7,5,'2013-07-30 12:50:54'),(249,'edit_facility',7,5,'2013-07-18 08:20:32'),(763,'acl_editor_people_qualifications',7,5,'2013-07-30 12:50:54'),(731,'edit_people',15,5,'2013-07-29 14:10:25'),(749,'acl_editor_recommended_topic',7,5,'2013-07-30 12:50:54'),(741,'acl_admin_facilities',7,5,'2013-07-30 12:50:54'),(254,'view_create_reports',7,5,'2013-07-18 08:20:32'),(735,'edit_course',15,5,'2013-07-29 14:10:25'),(773,'acl_editor_people_titles',7,5,'2013-07-30 12:50:54'),(761,'acl_editor_method',7,5,'2013-07-30 12:50:54'),(258,'admin_files',7,5,'2013-07-18 08:20:32'),(762,'approve_trainings',7,5,'2013-07-30 12:50:54'),(769,'acl_editor_refresher_course',7,5,'2013-07-30 12:50:54'),(742,'acl_editor_training_category',7,5,'2013-07-30 12:50:54'),(747,'acl_editor_ps_coursetypes',7,5,'2013-07-30 12:50:54'),(264,'use_offline_app',7,5,'2013-07-18 08:20:32'),(759,'acl_admin_people',7,5,'2013-07-30 12:50:54'),(768,'acl_editor_ps_nationalities',7,5,'2013-07-30 12:50:54'),(755,'import_training',7,5,'2013-07-30 12:50:54'),(774,'acl_editor_ps_classes',7,5,'2013-07-30 12:50:54'),(2467,'training_organizer_option_all',7,7,'2014-05-22 08:14:50'),(270,'edit_people',7,5,'2013-07-18 08:20:32'),(751,'acl_editor_people_active_trainer',7,5,'2013-07-30 12:50:54'),(734,'edit_training_location',15,5,'2013-07-29 14:10:25'),(748,'acl_editor_ps_languages',7,5,'2013-07-30 12:50:54'),(730,'view_create_reports',15,5,'2013-07-29 14:10:25'),(744,'acl_editor_people_languages',7,5,'2013-07-30 12:50:54'),(736,'edit_facility',15,5,'2013-07-29 14:10:25'),(766,'import_facility',7,5,'2013-07-30 12:50:54'),(778,'acl_editor_ps_tutortypes',7,5,'2013-07-30 12:50:54'),(775,'import_training_location',7,5,'2013-07-30 12:50:54'),(776,'acl_editor_ps_joindropreasons',7,5,'2013-07-30 12:50:54'),(723,'facility_and_person_approver',5,5,'2013-07-24 15:35:02'),(284,'acl_editor_training_organizer',1,1,'2013-07-23 00:46:07'),(285,'view_create_reports',8,5,'2013-07-23 16:02:25'),(790,'admin_files',11,5,'2013-07-30 13:46:55'),(287,'edit_people',8,5,'2013-07-23 16:02:25'),(289,'edit_course',8,5,'2013-07-23 16:02:25'),(290,'edit_training_location',8,5,'2013-07-23 16:02:25'),(291,'edit_facility',8,5,'2013-07-23 16:02:25'),(292,'view_create_reports',9,5,'2013-07-23 16:07:47'),(798,'acl_editor_facility_sponsors',4,5,'2013-08-12 10:35:23'),(294,'edit_people',9,5,'2013-07-23 16:07:47'),(724,'admin_files',6,5,'2013-07-25 14:38:09'),(296,'edit_course',9,5,'2013-07-23 16:07:47'),(297,'edit_training_location',9,5,'2013-07-23 16:07:47'),(298,'edit_facility',9,5,'2013-07-23 16:07:47'),(299,'view_create_reports',10,5,'2013-07-23 16:11:40'),(797,'acl_editor_facility_sponsors',9,5,'2013-08-06 15:17:52'),(301,'edit_people',10,5,'2013-07-23 16:11:40'),(794,'acl_editor_facility_sponsors',11,5,'2013-07-31 10:47:26'),(303,'edit_course',10,5,'2013-07-23 16:11:40'),(304,'edit_training_location',10,5,'2013-07-23 16:11:40'),(305,'edit_facility',10,5,'2013-07-23 16:11:40'),(306,'edit_course',11,5,'2013-07-23 16:15:36'),(307,'edit_training_location',11,5,'2013-07-23 16:15:36'),(308,'edit_facility',11,5,'2013-07-23 16:15:36'),(309,'view_create_reports',11,5,'2013-07-23 16:15:36'),(311,'edit_people',11,5,'2013-07-23 16:15:36'),(804,'edit_training_location',3,5,'2013-09-03 09:46:56'),(313,'edit_course',12,5,'2013-07-23 16:25:55'),(314,'edit_training_location',12,5,'2013-07-23 16:25:55'),(315,'edit_facility',12,5,'2013-07-23 16:25:55'),(316,'view_create_reports',12,5,'2013-07-23 16:25:55'),(788,'admin_files',8,5,'2013-07-30 13:45:08'),(318,'acl_editor_facility_sponsors',12,5,'2013-07-23 16:25:55'),(319,'edit_people',12,5,'2013-07-23 16:25:55'),(789,'acl_editor_facility_sponsors',8,5,'2013-07-30 13:45:08'),(321,'edit_course',13,5,'2013-07-23 16:34:19'),(322,'edit_training_location',13,5,'2013-07-23 16:34:19'),(323,'edit_facility',13,5,'2013-07-23 16:34:19'),(324,'view_create_reports',13,5,'2013-07-23 16:34:19'),(326,'acl_editor_facility_sponsors',13,5,'2013-07-23 16:34:19'),(327,'edit_people',13,5,'2013-07-23 16:34:19'),(786,'admin_files',15,5,'2013-07-30 13:40:41'),(329,'edit_training_location',14,5,'2013-07-24 08:16:18'),(330,'edit_facility',14,5,'2013-07-24 08:16:18'),(331,'view_create_reports',14,5,'2013-07-24 08:16:18'),(800,'edit_training_location',4,5,'2013-08-12 10:35:23'),(333,'acl_editor_facility_sponsors',14,5,'2013-07-24 08:16:18'),(334,'edit_people',14,5,'2013-07-24 08:16:18'),(801,'admin_files',4,5,'2013-08-12 10:35:23'),(336,'edit_course',14,5,'2013-07-24 08:16:18'),(343,'edit_employee',8,7,'2013-07-24 08:45:04'),(752,'edit_country_options',7,5,'2013-07-30 12:50:54'),(1242,'edit_course',56,18,'2014-01-29 07:56:09'),(791,'admin_files',14,5,'2013-07-30 13:52:43'),(783,'acl_editor_pepfar_category',7,5,'2013-07-30 12:50:54'),(799,'training_organizer_option_all',4,5,'2013-08-12 10:35:23'),(784,'import_person',7,5,'2013-07-30 12:50:54'),(721,'edit_employee',7,5,'2013-07-24 15:33:17'),(780,'acl_editor_training_organizer',7,5,'2013-07-30 12:50:54'),(785,'admin_files',13,5,'2013-07-30 13:35:14'),(779,'acl_admin_training',7,5,'2013-07-30 12:50:54'),(642,'edit_employee',9,5,'2013-07-24 08:58:06'),(651,'edit_employee',10,5,'2013-07-24 08:58:56'),(654,'edit_employee',11,5,'2013-07-24 08:59:28'),(663,'edit_employee',12,5,'2013-07-24 09:00:17'),(669,'edit_employee',13,5,'2013-07-24 09:00:55'),(675,'edit_employee',14,5,'2013-07-24 09:02:04'),(805,'acl_editor_ps_nationalities',1,1,'2014-01-10 15:59:22'),(806,'acl_editor_refresher_course',1,1,'2014-01-10 15:59:22'),(807,'acl_editor_facility_types',1,1,'2014-01-10 15:59:22'),(808,'acl_editor_people_affiliations',1,1,'2014-01-10 15:59:22'),(809,'acl_editor_people_titles',1,1,'2014-01-10 15:59:22'),(810,'acl_editor_ps_classes',1,1,'2014-01-10 15:59:22'),(811,'import_training_location',1,1,'2014-01-10 15:59:22'),(812,'acl_editor_ps_joindropreasons',1,1,'2014-01-10 15:59:22'),(813,'acl_editor_ps_tutortypes',1,1,'2014-01-10 15:59:22'),(814,'acl_admin_training',1,1,'2014-01-10 15:59:22'),(815,'acl_editor_nationalcurriculum',1,1,'2014-01-10 15:59:22'),(816,'acl_editor_people_responsibility',1,1,'2014-01-10 15:59:22'),(817,'acl_editor_pepfar_category',1,1,'2014-01-10 15:59:22'),(818,'import_person',1,1,'2014-01-10 15:59:22'),(819,'acl_editor_ps_funding',1,1,'2014-01-10 15:59:22'),(820,'acl_editor_ps_religions',1,1,'2014-01-10 15:59:22'),(821,'acl_admin_facilities',1,1,'2014-01-10 15:59:22'),(822,'acl_editor_training_category',1,1,'2014-01-10 15:59:22'),(823,'acl_editor_funding',1,1,'2014-01-10 15:59:22'),(2471,'edit_employee',110,107,'2014-06-23 12:14:42'),(825,'acl_editor_people_languages',1,1,'2014-01-10 15:59:22'),(826,'acl_editor_people_trainer',1,1,'2014-01-10 15:59:22'),(827,'acl_editor_ps_coursetypes',1,1,'2014-01-10 15:59:22'),(828,'acl_editor_ps_languages',1,1,'2014-01-10 15:59:22'),(829,'acl_editor_recommended_topic',1,1,'2014-01-10 15:59:22'),(830,'acl_editor_facility_sponsors',1,1,'2014-01-10 15:59:22'),(831,'acl_editor_training_topic',1,1,'2014-01-10 15:59:22'),(832,'acl_editor_people_active_trainer',1,1,'2014-01-10 15:59:22'),(833,'acl_editor_people_suffix',1,1,'2014-01-10 15:59:22'),(834,'acl_editor_ps_cadres',1,1,'2014-01-10 15:59:22'),(835,'import_training',1,1,'2014-01-10 15:59:22'),(836,'acl_editor_ps_institutions',1,1,'2014-01-10 15:59:22'),(837,'acl_editor_ps_sponsors',1,1,'2014-01-10 15:59:22'),(838,'acl_admin_people',1,1,'2014-01-10 15:59:22'),(839,'acl_editor_training_level',1,1,'2014-01-10 15:59:22'),(840,'acl_editor_method',1,1,'2014-01-10 15:59:22'),(841,'acl_editor_people_qualifications',1,1,'2014-01-10 15:59:22'),(842,'acl_editor_people_trainer_skills',1,1,'2014-01-10 15:59:22'),(843,'import_facility',1,1,'2014-01-10 15:59:22'),(844,'acl_editor_ps_degrees',1,1,'2014-01-10 15:59:22'),(845,'edit_course',16,7,'2014-01-28 09:27:59'),(846,'edit_facility',16,7,'2014-01-28 09:27:59'),(847,'view_create_reports',16,7,'2014-01-28 09:27:59'),(848,'edit_employee',16,7,'2014-01-28 09:27:59'),(849,'use_offline_app',16,7,'2014-01-28 09:27:59'),(850,'edit_people',16,7,'2014-01-28 09:27:59'),(851,'edit_training_location',16,7,'2014-01-28 09:32:16'),(852,'admin_files',16,7,'2014-01-28 09:32:16'),(853,'edit_course',17,7,'2014-01-28 09:33:48'),(854,'edit_training_location',17,7,'2014-01-28 09:33:48'),(855,'edit_facility',17,7,'2014-01-28 09:33:48'),(856,'view_create_reports',17,7,'2014-01-28 09:33:48'),(857,'admin_files',17,7,'2014-01-28 09:33:48'),(858,'edit_employee',17,7,'2014-01-28 09:33:48'),(859,'in_service',17,7,'2014-01-28 09:33:48'),(860,'edit_people',17,7,'2014-01-28 09:33:48'),(861,'approve_trainings',17,7,'2014-01-28 09:33:48'),(862,'edit_course',18,7,'2014-01-28 09:44:05'),(863,'edit_training_location',18,7,'2014-01-28 09:44:05'),(864,'duplicate_training',18,7,'2014-01-28 09:44:05'),(865,'edit_facility',18,7,'2014-01-28 09:44:05'),(866,'view_create_reports',18,7,'2014-01-28 09:44:05'),(867,'admin_files',18,7,'2014-01-28 09:44:05'),(916,'approve_trainings',19,7,'2014-01-28 10:24:50'),(869,'use_offline_app',18,7,'2014-01-28 09:44:05'),(870,'edit_people',18,7,'2014-01-28 09:44:05'),(871,'training_organizer_option_all',18,7,'2014-01-28 09:44:05'),(872,'acl_editor_ps_nationalities',18,7,'2014-01-28 09:50:33'),(873,'acl_editor_refresher_course',18,7,'2014-01-28 09:50:33'),(874,'acl_editor_facility_types',18,7,'2014-01-28 09:50:33'),(875,'add_edit_users',18,7,'2014-01-28 09:50:33'),(876,'acl_editor_people_affiliations',18,7,'2014-01-28 09:50:33'),(877,'acl_editor_people_titles',18,7,'2014-01-28 09:50:33'),(878,'acl_editor_ps_classes',18,7,'2014-01-28 09:50:33'),(879,'import_training_location',18,7,'2014-01-28 09:50:33'),(880,'acl_editor_ps_joindropreasons',18,7,'2014-01-28 09:50:33'),(881,'training_title_option_all',18,7,'2014-01-28 09:50:33'),(882,'acl_editor_ps_tutortypes',18,7,'2014-01-28 09:50:33'),(883,'acl_admin_training',18,7,'2014-01-28 09:50:33'),(884,'acl_editor_training_organizer',18,7,'2014-01-28 09:50:33'),(885,'acl_editor_nationalcurriculum',18,7,'2014-01-28 09:50:33'),(886,'acl_editor_people_responsibility',18,7,'2014-01-28 09:50:33'),(887,'acl_editor_pepfar_category',18,7,'2014-01-28 09:50:33'),(888,'import_person',18,7,'2014-01-28 09:50:33'),(889,'acl_editor_ps_funding',18,7,'2014-01-28 09:50:33'),(890,'acl_editor_ps_religions',18,7,'2014-01-28 09:50:33'),(891,'acl_admin_facilities',18,7,'2014-01-28 09:50:33'),(892,'acl_editor_training_category',18,7,'2014-01-28 09:50:34'),(893,'acl_editor_funding',18,7,'2014-01-28 09:50:34'),(894,'acl_editor_people_languages',18,7,'2014-01-28 09:50:34'),(895,'acl_editor_people_trainer',18,7,'2014-01-28 09:50:34'),(896,'acl_editor_ps_coursetypes',18,7,'2014-01-28 09:50:34'),(897,'acl_editor_ps_languages',18,7,'2014-01-28 09:50:34'),(898,'acl_editor_recommended_topic',18,7,'2014-01-28 09:50:34'),(1579,'admin_files',91,7,'2014-01-31 11:15:30'),(900,'acl_editor_training_topic',18,7,'2014-01-28 09:50:34'),(901,'acl_editor_people_active_trainer',18,7,'2014-01-28 09:50:34'),(902,'edit_country_options',18,7,'2014-01-28 09:50:34'),(903,'acl_editor_people_suffix',18,7,'2014-01-28 09:50:34'),(904,'acl_editor_ps_cadres',18,7,'2014-01-28 09:50:34'),(905,'import_training',18,7,'2014-01-28 09:50:34'),(906,'acl_editor_ps_institutions',18,7,'2014-01-28 09:50:34'),(907,'acl_editor_ps_sponsors',18,7,'2014-01-28 09:50:34'),(908,'acl_admin_people',18,7,'2014-01-28 09:50:34'),(909,'acl_editor_training_level',18,7,'2014-01-28 09:50:34'),(910,'acl_editor_method',18,7,'2014-01-28 09:50:34'),(911,'acl_editor_people_qualifications',18,7,'2014-01-28 09:50:34'),(912,'acl_editor_people_trainer_skills',18,7,'2014-01-28 09:50:34'),(913,'import_facility',18,7,'2014-01-28 09:50:34'),(914,'acl_editor_ps_degrees',18,7,'2014-01-28 09:50:34'),(915,'edit_employee',18,7,'2014-01-28 09:57:25'),(917,'edit_course',19,7,'2014-01-28 10:24:50'),(918,'edit_training_location',19,7,'2014-01-28 10:24:50'),(919,'edit_facility',19,7,'2014-01-28 10:24:50'),(920,'view_create_reports',19,7,'2014-01-28 10:24:50'),(921,'admin_files',19,7,'2014-01-28 10:24:50'),(922,'edit_employee',19,7,'2014-01-28 10:24:50'),(923,'acl_editor_facility_sponsors',19,7,'2014-01-28 10:24:50'),(924,'edit_people',19,7,'2014-01-28 10:24:50'),(925,'edit_course',20,7,'2014-01-28 10:29:59'),(926,'edit_training_location',20,7,'2014-01-28 10:29:59'),(927,'edit_facility',20,7,'2014-01-28 10:29:59'),(928,'view_create_reports',20,7,'2014-01-28 10:29:59'),(929,'admin_files',20,7,'2014-01-28 10:29:59'),(930,'acl_editor_facility_sponsors',20,7,'2014-01-28 10:29:59'),(931,'edit_people',20,7,'2014-01-28 10:29:59'),(932,'approve_trainings',20,7,'2014-01-28 10:29:59'),(933,'admin_files',21,7,'2014-01-28 10:32:15'),(934,'edit_employee',21,7,'2014-01-28 10:32:15'),(935,'acl_editor_facility_sponsors',21,7,'2014-01-28 10:32:15'),(936,'edit_people',21,7,'2014-01-28 10:32:15'),(937,'edit_course',21,7,'2014-01-28 10:32:15'),(938,'edit_training_location',21,7,'2014-01-28 10:32:15'),(939,'edit_facility',21,7,'2014-01-28 10:32:15'),(940,'view_create_reports',21,7,'2014-01-28 10:32:15'),(941,'acl_editor_facility_sponsors',22,7,'2014-01-28 10:51:41'),(942,'edit_people',22,7,'2014-01-28 10:51:41'),(943,'approve_trainings',22,7,'2014-01-28 10:51:41'),(944,'edit_course',22,7,'2014-01-28 10:51:41'),(945,'edit_training_location',22,7,'2014-01-28 10:51:41'),(946,'edit_facility',22,7,'2014-01-28 10:51:41'),(947,'view_create_reports',22,7,'2014-01-28 10:51:41'),(948,'admin_files',22,7,'2014-01-28 10:51:41'),(949,'edit_employee',22,7,'2014-01-28 10:51:41'),(950,'edit_people',23,7,'2014-01-28 10:52:40'),(951,'approve_trainings',23,7,'2014-01-28 10:52:40'),(952,'edit_course',23,7,'2014-01-28 10:52:40'),(953,'edit_training_location',23,7,'2014-01-28 10:52:40'),(954,'edit_facility',23,7,'2014-01-28 10:52:40'),(955,'view_create_reports',23,7,'2014-01-28 10:52:40'),(956,'admin_files',23,7,'2014-01-28 10:52:40'),(957,'edit_employee',23,7,'2014-01-28 10:52:40'),(958,'edit_facility',24,7,'2014-01-28 10:55:35'),(959,'view_create_reports',24,7,'2014-01-28 10:55:35'),(960,'admin_files',24,7,'2014-01-28 10:55:35'),(961,'edit_employee',24,7,'2014-01-28 10:55:35'),(1210,'duplicate_training',53,18,'2014-01-29 06:11:44'),(1208,'edit_course',53,18,'2014-01-29 06:11:44'),(964,'edit_people',24,7,'2014-01-28 10:55:35'),(1209,'edit_training_location',53,18,'2014-01-29 06:11:44'),(966,'edit_course',24,7,'2014-01-28 10:55:35'),(967,'edit_training_location',24,7,'2014-01-28 10:55:35'),(968,'edit_facility',25,7,'2014-01-28 10:56:52'),(969,'view_create_reports',25,7,'2014-01-28 10:56:52'),(970,'admin_files',25,7,'2014-01-28 10:56:52'),(971,'edit_employee',25,7,'2014-01-28 10:56:52'),(972,'acl_editor_facility_sponsors',25,7,'2014-01-28 10:56:52'),(973,'edit_people',25,7,'2014-01-28 10:56:52'),(974,'approve_trainings',25,7,'2014-01-28 10:56:52'),(975,'edit_course',25,7,'2014-01-28 10:56:52'),(976,'edit_training_location',25,7,'2014-01-28 10:56:52'),(977,'edit_employee',26,7,'2014-01-28 10:59:25'),(978,'acl_editor_facility_sponsors',26,7,'2014-01-28 10:59:25'),(979,'edit_people',26,7,'2014-01-28 10:59:25'),(980,'approve_trainings',26,7,'2014-01-28 10:59:25'),(981,'edit_course',26,7,'2014-01-28 10:59:25'),(982,'edit_training_location',26,7,'2014-01-28 10:59:25'),(983,'edit_facility',26,7,'2014-01-28 10:59:25'),(984,'view_create_reports',26,7,'2014-01-28 10:59:25'),(985,'admin_files',26,7,'2014-01-28 10:59:25'),(986,'edit_facility',27,7,'2014-01-28 11:00:23'),(987,'view_create_reports',27,7,'2014-01-28 11:00:23'),(988,'admin_files',27,7,'2014-01-28 11:00:23'),(989,'edit_employee',27,7,'2014-01-28 11:00:23'),(990,'acl_editor_facility_sponsors',27,7,'2014-01-28 11:00:23'),(991,'edit_people',27,7,'2014-01-28 11:00:23'),(992,'approve_trainings',27,7,'2014-01-28 11:00:23'),(993,'edit_course',27,7,'2014-01-28 11:00:23'),(994,'edit_training_location',27,7,'2014-01-28 11:00:23'),(995,'edit_training_location',28,7,'2014-01-28 11:03:20'),(996,'edit_facility',28,7,'2014-01-28 11:03:20'),(997,'view_create_reports',28,7,'2014-01-28 11:03:20'),(998,'admin_files',28,7,'2014-01-28 11:03:20'),(999,'edit_employee',28,7,'2014-01-28 11:03:20'),(1000,'acl_editor_facility_sponsors',28,7,'2014-01-28 11:03:20'),(1001,'edit_people',28,7,'2014-01-28 11:03:20'),(1002,'approve_trainings',28,7,'2014-01-28 11:03:20'),(1003,'edit_course',28,7,'2014-01-28 11:03:20'),(1004,'approve_trainings',29,7,'2014-01-28 11:04:27'),(1005,'edit_course',29,7,'2014-01-28 11:04:27'),(1006,'edit_training_location',29,7,'2014-01-28 11:04:27'),(1007,'edit_facility',29,7,'2014-01-28 11:04:27'),(1008,'view_create_reports',29,7,'2014-01-28 11:04:27'),(1009,'admin_files',29,7,'2014-01-28 11:04:27'),(1010,'edit_employee',29,7,'2014-01-28 11:04:27'),(1011,'acl_editor_facility_sponsors',29,7,'2014-01-28 11:04:27'),(1012,'edit_people',29,7,'2014-01-28 11:04:27'),(1013,'edit_facility',30,7,'2014-01-28 11:07:50'),(1014,'view_create_reports',30,7,'2014-01-28 11:07:50'),(1015,'admin_files',30,7,'2014-01-28 11:07:50'),(1016,'edit_employee',30,7,'2014-01-28 11:07:50'),(1017,'acl_editor_facility_sponsors',30,7,'2014-01-28 11:07:50'),(1018,'edit_people',30,7,'2014-01-28 11:07:50'),(1019,'edit_course',30,7,'2014-01-28 11:07:50'),(1020,'edit_training_location',30,7,'2014-01-28 11:07:50'),(1021,'edit_facility',31,7,'2014-01-28 11:13:37'),(1022,'view_create_reports',31,7,'2014-01-28 11:13:37'),(1023,'admin_files',31,7,'2014-01-28 11:13:37'),(1024,'acl_editor_facility_sponsors',31,7,'2014-01-28 11:13:37'),(1025,'edit_people',31,7,'2014-01-28 11:13:37'),(1244,'duplicate_training',56,18,'2014-01-29 07:56:09'),(1027,'edit_course',31,7,'2014-01-28 11:13:37'),(1243,'edit_training_location',56,18,'2014-01-29 07:56:09'),(1029,'edit_course',32,7,'2014-01-28 13:44:21'),(1030,'edit_training_location',32,7,'2014-01-28 13:44:21'),(1031,'edit_facility',32,7,'2014-01-28 13:44:21'),(1032,'view_create_reports',32,7,'2014-01-28 13:44:21'),(1033,'admin_files',32,7,'2014-01-28 13:44:21'),(1034,'edit_employee',32,7,'2014-01-28 13:44:21'),(1035,'acl_editor_facility_sponsors',32,7,'2014-01-28 13:44:21'),(1036,'edit_people',32,7,'2014-01-28 13:44:21'),(1037,'approve_trainings',32,7,'2014-01-28 13:44:21'),(1038,'edit_course',33,7,'2014-01-28 13:48:42'),(1039,'edit_training_location',33,7,'2014-01-28 13:48:43'),(1040,'edit_facility',33,7,'2014-01-28 13:48:43'),(1041,'view_create_reports',33,7,'2014-01-28 13:48:43'),(1042,'admin_files',33,7,'2014-01-28 13:48:43'),(1043,'edit_employee',33,7,'2014-01-28 13:48:43'),(1044,'acl_editor_facility_sponsors',33,7,'2014-01-28 13:48:43'),(1045,'edit_people',33,7,'2014-01-28 13:48:43'),(1046,'approve_trainings',33,7,'2014-01-28 13:48:43'),(1047,'edit_training_location',34,7,'2014-01-28 13:51:02'),(1048,'edit_facility',34,7,'2014-01-28 13:51:02'),(1049,'view_create_reports',34,7,'2014-01-28 13:51:02'),(1050,'admin_files',34,7,'2014-01-28 13:51:02'),(1051,'edit_employee',34,7,'2014-01-28 13:51:02'),(1052,'acl_editor_facility_sponsors',34,7,'2014-01-28 13:51:02'),(1053,'edit_people',34,7,'2014-01-28 13:51:02'),(1054,'approve_trainings',34,7,'2014-01-28 13:51:02'),(1055,'edit_course',34,7,'2014-01-28 13:51:02'),(1056,'edit_employee',35,7,'2014-01-28 13:52:05'),(1057,'acl_editor_facility_sponsors',35,7,'2014-01-28 13:52:05'),(1058,'edit_people',35,7,'2014-01-28 13:52:05'),(1059,'approve_trainings',35,7,'2014-01-28 13:52:05'),(1060,'edit_course',35,7,'2014-01-28 13:52:05'),(1061,'edit_training_location',35,7,'2014-01-28 13:52:05'),(1062,'edit_facility',35,7,'2014-01-28 13:52:05'),(1063,'view_create_reports',35,7,'2014-01-28 13:52:05'),(1064,'admin_files',35,7,'2014-01-28 13:52:05'),(1065,'acl_editor_facility_sponsors',36,18,'2014-01-28 13:56:58'),(1066,'edit_people',36,18,'2014-01-28 13:56:58'),(1067,'approve_trainings',36,18,'2014-01-28 13:56:58'),(1068,'edit_course',36,18,'2014-01-28 13:56:58'),(1069,'edit_training_location',36,18,'2014-01-28 13:56:58'),(1070,'edit_facility',36,18,'2014-01-28 13:56:58'),(1071,'view_create_reports',36,18,'2014-01-28 13:56:58'),(1072,'admin_files',36,18,'2014-01-28 13:56:58'),(1073,'edit_employee',36,18,'2014-01-28 13:56:58'),(1074,'edit_people',37,18,'2014-01-28 14:00:46'),(1075,'approve_trainings',37,18,'2014-01-28 14:00:46'),(1076,'edit_course',37,18,'2014-01-28 14:00:46'),(1077,'edit_training_location',37,18,'2014-01-28 14:00:46'),(1078,'edit_facility',37,18,'2014-01-28 14:00:46'),(1079,'view_create_reports',37,18,'2014-01-28 14:00:46'),(1080,'admin_files',37,18,'2014-01-28 14:00:46'),(1081,'acl_editor_facility_sponsors',37,18,'2014-01-28 14:07:38'),(1082,'edit_people',38,18,'2014-01-28 14:18:05'),(1083,'approve_trainings',38,18,'2014-01-28 14:18:05'),(1084,'edit_course',38,18,'2014-01-28 14:18:05'),(1085,'edit_training_location',38,18,'2014-01-28 14:18:05'),(1086,'edit_facility',38,18,'2014-01-28 14:18:05'),(1087,'view_create_reports',38,18,'2014-01-28 14:18:05'),(1088,'admin_files',38,18,'2014-01-28 14:18:05'),(1089,'edit_employee',38,18,'2014-01-28 14:18:05'),(1090,'approve_trainings',39,7,'2014-01-28 15:23:51'),(1091,'edit_course',39,7,'2014-01-28 15:23:51'),(1092,'edit_training_location',39,7,'2014-01-28 15:23:51'),(1093,'edit_facility',39,7,'2014-01-28 15:23:51'),(1094,'view_create_reports',39,7,'2014-01-28 15:23:51'),(1095,'admin_files',39,7,'2014-01-28 15:23:51'),(1096,'edit_employee',39,7,'2014-01-28 15:23:51'),(1097,'acl_editor_facility_sponsors',39,7,'2014-01-28 15:23:51'),(1098,'edit_people',39,7,'2014-01-28 15:23:51'),(1099,'edit_course',40,7,'2014-01-28 15:31:07'),(1100,'edit_training_location',40,7,'2014-01-28 15:31:07'),(1101,'edit_facility',40,7,'2014-01-28 15:31:07'),(1102,'view_create_reports',40,7,'2014-01-28 15:31:07'),(1103,'admin_files',40,7,'2014-01-28 15:31:07'),(1104,'edit_employee',40,7,'2014-01-28 15:31:07'),(1105,'acl_editor_facility_sponsors',40,7,'2014-01-28 15:31:07'),(1106,'edit_people',40,7,'2014-01-28 15:31:07'),(1107,'edit_course',41,7,'2014-01-28 15:33:26'),(1108,'edit_training_location',41,7,'2014-01-28 15:33:26'),(1109,'edit_facility',41,7,'2014-01-28 15:33:26'),(1110,'view_create_reports',41,7,'2014-01-28 15:33:26'),(1111,'admin_files',41,7,'2014-01-28 15:33:26'),(1112,'edit_employee',41,7,'2014-01-28 15:33:26'),(1113,'acl_editor_facility_sponsors',41,7,'2014-01-28 15:33:26'),(1114,'edit_people',41,7,'2014-01-28 15:33:26'),(1115,'approve_trainings',41,7,'2014-01-28 15:33:26'),(1116,'edit_course',42,7,'2014-01-28 15:34:22'),(1117,'edit_training_location',42,7,'2014-01-28 15:34:22'),(1118,'edit_facility',42,7,'2014-01-28 15:34:22'),(1119,'view_create_reports',42,7,'2014-01-28 15:34:22'),(1120,'admin_files',42,7,'2014-01-28 15:34:22'),(1121,'edit_employee',42,7,'2014-01-28 15:34:22'),(1122,'acl_editor_facility_sponsors',42,7,'2014-01-28 15:34:22'),(1123,'edit_people',42,7,'2014-01-28 15:34:22'),(1124,'approve_trainings',42,7,'2014-01-28 15:34:22'),(1125,'edit_course',43,7,'2014-01-28 15:36:25'),(1126,'edit_training_location',43,7,'2014-01-28 15:36:25'),(1127,'edit_facility',43,7,'2014-01-28 15:36:25'),(1128,'view_create_reports',43,7,'2014-01-28 15:36:25'),(1129,'admin_files',43,7,'2014-01-28 15:36:25'),(1130,'edit_employee',43,7,'2014-01-28 15:36:25'),(1131,'acl_editor_facility_sponsors',43,7,'2014-01-28 15:36:25'),(1132,'edit_people',43,7,'2014-01-28 15:36:25'),(1133,'approve_trainings',43,7,'2014-01-28 15:36:25'),(1134,'edit_course',44,7,'2014-01-28 15:41:15'),(1135,'edit_training_location',44,7,'2014-01-28 15:41:15'),(1136,'edit_facility',44,7,'2014-01-28 15:41:15'),(1137,'view_create_reports',44,7,'2014-01-28 15:41:15'),(1138,'admin_files',44,7,'2014-01-28 15:41:15'),(1139,'edit_employee',44,7,'2014-01-28 15:41:15'),(1140,'acl_editor_facility_sponsors',44,7,'2014-01-28 15:41:15'),(1141,'edit_people',44,7,'2014-01-28 15:41:15'),(1142,'approve_trainings',44,7,'2014-01-28 15:41:15'),(1143,'edit_course',45,7,'2014-01-28 15:42:09'),(1144,'edit_training_location',45,7,'2014-01-28 15:42:09'),(1145,'edit_facility',45,7,'2014-01-28 15:42:09'),(1146,'view_create_reports',45,7,'2014-01-28 15:42:09'),(1147,'admin_files',45,7,'2014-01-28 15:42:09'),(1148,'edit_employee',45,7,'2014-01-28 15:42:09'),(1149,'acl_editor_facility_sponsors',45,7,'2014-01-28 15:42:09'),(1150,'edit_people',45,7,'2014-01-28 15:42:09'),(1151,'approve_trainings',45,7,'2014-01-28 15:42:09'),(1152,'edit_course',46,7,'2014-01-28 15:44:53'),(1153,'edit_training_location',46,7,'2014-01-28 15:44:53'),(1154,'edit_facility',46,7,'2014-01-28 15:44:53'),(1155,'view_create_reports',46,7,'2014-01-28 15:44:53'),(1156,'admin_files',46,7,'2014-01-28 15:44:53'),(1157,'edit_employee',46,7,'2014-01-28 15:44:53'),(1158,'edit_people',46,7,'2014-01-28 15:44:53'),(1159,'edit_course',47,7,'2014-01-28 15:48:08'),(1160,'edit_training_location',47,7,'2014-01-28 15:48:08'),(1161,'edit_facility',47,7,'2014-01-28 15:48:08'),(1162,'view_create_reports',47,7,'2014-01-28 15:48:08'),(1163,'admin_files',47,7,'2014-01-28 15:48:08'),(1164,'edit_employee',47,7,'2014-01-28 15:48:08'),(1165,'edit_people',47,7,'2014-01-28 15:48:08'),(1166,'approve_trainings',48,7,'2014-01-28 15:51:16'),(1167,'edit_course',48,7,'2014-01-28 15:51:16'),(1168,'edit_training_location',48,7,'2014-01-28 15:51:16'),(1169,'edit_facility',48,7,'2014-01-28 15:51:16'),(1170,'view_create_reports',48,7,'2014-01-28 15:51:16'),(1171,'admin_files',48,7,'2014-01-28 15:51:16'),(1172,'edit_employee',48,7,'2014-01-28 15:51:16'),(1173,'edit_people',48,7,'2014-01-28 15:51:16'),(1174,'edit_course',49,7,'2014-01-28 15:52:10'),(1175,'edit_training_location',49,7,'2014-01-28 15:52:10'),(1176,'edit_facility',49,7,'2014-01-28 15:52:10'),(1177,'view_create_reports',49,7,'2014-01-28 15:52:10'),(1178,'admin_files',49,7,'2014-01-28 15:52:10'),(1179,'edit_employee',49,7,'2014-01-28 15:52:10'),(1180,'acl_editor_facility_sponsors',49,7,'2014-01-28 15:52:10'),(1181,'edit_people',49,7,'2014-01-28 15:52:10'),(1182,'approve_trainings',49,7,'2014-01-28 15:52:10'),(1183,'edit_course',50,7,'2014-01-28 15:53:10'),(1184,'edit_training_location',50,7,'2014-01-28 15:53:10'),(1185,'edit_facility',50,7,'2014-01-28 15:53:10'),(1186,'view_create_reports',50,7,'2014-01-28 15:53:10'),(1187,'admin_files',50,7,'2014-01-28 15:53:10'),(1188,'edit_employee',50,7,'2014-01-28 15:53:10'),(1189,'acl_editor_facility_sponsors',50,7,'2014-01-28 15:53:11'),(1190,'edit_people',50,7,'2014-01-28 15:53:11'),(1191,'edit_course',51,7,'2014-01-28 15:54:09'),(1192,'edit_training_location',51,7,'2014-01-28 15:54:09'),(1193,'edit_facility',51,7,'2014-01-28 15:54:09'),(1194,'view_create_reports',51,7,'2014-01-28 15:54:09'),(1195,'admin_files',51,7,'2014-01-28 15:54:09'),(1196,'edit_employee',51,7,'2014-01-28 15:54:09'),(1197,'acl_editor_facility_sponsors',51,7,'2014-01-28 15:54:09'),(1198,'edit_people',51,7,'2014-01-28 15:54:09'),(1199,'approve_trainings',51,7,'2014-01-28 15:54:09'),(1200,'approve_trainings',52,7,'2014-01-28 15:55:49'),(1201,'edit_training_location',52,7,'2014-01-28 15:55:49'),(1202,'edit_course',52,7,'2014-01-28 15:55:49'),(1203,'edit_facility',52,7,'2014-01-28 15:55:49'),(1204,'view_create_reports',52,7,'2014-01-28 15:55:49'),(1205,'admin_files',52,7,'2014-01-28 15:55:49'),(1206,'edit_people',52,7,'2014-01-28 15:55:49'),(1207,'acl_editor_facility_sponsors',52,7,'2014-01-28 15:55:49'),(1213,'admin_files',53,18,'2014-01-29 06:11:44'),(1214,'edit_employee',53,18,'2014-01-29 06:11:44'),(1215,'use_offline_app',53,18,'2014-01-29 06:11:44'),(1216,'acl_editor_facility_sponsors',53,18,'2014-01-29 06:11:44'),(1217,'edit_people',53,18,'2014-01-29 06:11:44'),(1218,'approve_trainings',53,18,'2014-01-29 06:11:44'),(1219,'edit_course',54,18,'2014-01-29 06:17:03'),(1220,'edit_training_location',54,18,'2014-01-29 06:17:03'),(1221,'duplicate_training',54,18,'2014-01-29 06:17:03'),(1222,'edit_facility',54,18,'2014-01-29 06:17:03'),(1223,'view_create_reports',54,18,'2014-01-29 06:17:03'),(1224,'admin_files',54,18,'2014-01-29 06:17:03'),(1225,'edit_employee',54,18,'2014-01-29 06:17:03'),(1226,'use_offline_app',54,18,'2014-01-29 06:17:03'),(1227,'acl_editor_facility_sponsors',54,18,'2014-01-29 06:17:03'),(1228,'edit_people',54,18,'2014-01-29 06:17:03'),(1229,'approve_trainings',54,18,'2014-01-29 06:17:03'),(1230,'edit_course',55,18,'2014-01-29 06:44:01'),(1231,'edit_training_location',55,18,'2014-01-29 06:44:01'),(1232,'duplicate_training',55,18,'2014-01-29 06:44:01'),(1233,'edit_facility',55,18,'2014-01-29 06:44:01'),(1234,'view_create_reports',55,18,'2014-01-29 06:44:01'),(1235,'admin_files',55,18,'2014-01-29 06:44:01'),(1236,'edit_employee',55,18,'2014-01-29 06:44:01'),(1237,'use_offline_app',55,18,'2014-01-29 06:44:01'),(1238,'acl_editor_facility_sponsors',55,18,'2014-01-29 06:44:01'),(1239,'edit_people',55,18,'2014-01-29 06:44:01'),(1240,'approve_trainings',55,18,'2014-01-29 06:44:01'),(1241,'edit_employee',31,2,'2014-01-29 07:51:29'),(1245,'edit_facility',56,18,'2014-01-29 07:56:09'),(1246,'view_create_reports',56,18,'2014-01-29 07:56:09'),(1247,'admin_files',56,18,'2014-01-29 07:56:09'),(1248,'edit_employee',56,18,'2014-01-29 07:56:09'),(1249,'use_offline_app',56,18,'2014-01-29 07:56:09'),(1250,'acl_editor_facility_sponsors',56,18,'2014-01-29 07:56:09'),(1251,'edit_people',56,18,'2014-01-29 07:56:09'),(1252,'approve_trainings',56,18,'2014-01-29 07:56:09'),(1253,'edit_course',57,18,'2014-01-29 07:58:47'),(1254,'edit_training_location',57,18,'2014-01-29 07:58:47'),(1863,'acl_editor_facility_sponsors',118,107,'2014-02-06 14:50:27'),(1256,'edit_facility',57,18,'2014-01-29 07:58:47'),(1257,'view_create_reports',57,18,'2014-01-29 07:58:47'),(1258,'admin_files',57,18,'2014-01-29 07:58:47'),(1259,'edit_employee',57,18,'2014-01-29 07:58:47'),(1862,'use_offline_app',118,107,'2014-02-06 14:50:27'),(1261,'edit_people',57,18,'2014-01-29 07:58:47'),(1262,'approve_trainings',57,18,'2014-01-29 07:58:47'),(1263,'edit_training_location',31,7,'2014-01-29 08:07:23'),(1264,'edit_course',58,18,'2014-01-29 08:13:34'),(1265,'edit_training_location',58,18,'2014-01-29 08:13:34'),(1266,'duplicate_training',58,18,'2014-01-29 08:13:34'),(1267,'edit_facility',58,18,'2014-01-29 08:13:34'),(1268,'view_create_reports',58,18,'2014-01-29 08:13:34'),(1269,'admin_files',58,18,'2014-01-29 08:13:34'),(1270,'edit_employee',58,18,'2014-01-29 08:13:34'),(1271,'use_offline_app',58,18,'2014-01-29 08:13:34'),(1272,'acl_editor_facility_sponsors',58,18,'2014-01-29 08:13:34'),(1273,'edit_people',58,18,'2014-01-29 08:13:34'),(1274,'approve_trainings',58,18,'2014-01-29 08:13:34'),(1275,'approve_trainings',59,18,'2014-01-29 08:20:55'),(1276,'edit_course',59,18,'2014-01-29 08:20:55'),(1277,'edit_training_location',59,18,'2014-01-29 08:20:55'),(1278,'duplicate_training',59,18,'2014-01-29 08:20:55'),(1279,'edit_facility',59,18,'2014-01-29 08:20:55'),(1280,'view_create_reports',59,18,'2014-01-29 08:20:55'),(1281,'admin_files',59,18,'2014-01-29 08:20:55'),(1282,'edit_employee',59,18,'2014-01-29 08:20:55'),(1283,'use_offline_app',59,18,'2014-01-29 08:20:55'),(1284,'edit_people',59,18,'2014-01-29 08:20:55'),(1285,'duplicate_training',60,18,'2014-01-29 08:37:47'),(1286,'edit_facility',60,18,'2014-01-29 08:37:47'),(1287,'view_create_reports',60,18,'2014-01-29 08:37:47'),(1288,'admin_files',60,18,'2014-01-29 08:37:47'),(1289,'edit_employee',60,18,'2014-01-29 08:37:47'),(1290,'use_offline_app',60,18,'2014-01-29 08:37:47'),(1291,'edit_people',60,18,'2014-01-29 08:37:47'),(1292,'approve_trainings',60,18,'2014-01-29 08:37:47'),(1293,'edit_course',60,18,'2014-01-29 08:37:47'),(1294,'edit_training_location',60,18,'2014-01-29 08:37:47'),(1295,'admin_files',61,18,'2014-01-29 09:01:03'),(1296,'edit_employee',61,18,'2014-01-29 09:01:03'),(1297,'use_offline_app',61,18,'2014-01-29 09:01:03'),(1298,'edit_people',61,18,'2014-01-29 09:01:03'),(1299,'approve_trainings',61,18,'2014-01-29 09:01:03'),(1300,'edit_course',61,18,'2014-01-29 09:01:03'),(1301,'edit_training_location',61,18,'2014-01-29 09:01:03'),(1302,'duplicate_training',61,18,'2014-01-29 09:01:03'),(1303,'edit_facility',61,18,'2014-01-29 09:01:03'),(1304,'view_create_reports',61,18,'2014-01-29 09:01:03'),(2292,'view_create_reports',155,136,'2014-02-11 13:13:37'),(1306,'edit_facility',62,18,'2014-01-29 09:03:32'),(1307,'view_create_reports',62,18,'2014-01-29 09:03:32'),(1308,'admin_files',62,18,'2014-01-29 09:03:32'),(1309,'edit_employee',62,18,'2014-01-29 09:03:32'),(2293,'admin_files',155,136,'2014-02-11 13:13:37'),(2291,'edit_facility',155,136,'2014-02-11 13:13:37'),(1312,'edit_people',62,18,'2014-01-29 09:03:32'),(1313,'approve_trainings',62,18,'2014-01-29 09:03:32'),(1314,'edit_course',62,18,'2014-01-29 09:03:32'),(1315,'edit_training_location',62,18,'2014-01-29 09:03:32'),(1316,'approve_trainings',63,18,'2014-01-29 09:15:57'),(1317,'edit_course',63,18,'2014-01-29 09:15:57'),(1318,'edit_training_location',63,18,'2014-01-29 09:15:57'),(1319,'duplicate_training',63,18,'2014-01-29 09:15:57'),(1320,'edit_facility',63,18,'2014-01-29 09:15:57'),(1321,'view_create_reports',63,18,'2014-01-29 09:15:57'),(1322,'admin_files',63,18,'2014-01-29 09:15:57'),(1323,'edit_employee',63,18,'2014-01-29 09:15:57'),(1324,'use_offline_app',63,18,'2014-01-29 09:15:57'),(1325,'acl_editor_facility_sponsors',63,18,'2014-01-29 09:15:57'),(1326,'edit_people',63,18,'2014-01-29 09:15:57'),(1327,'approve_trainings',64,18,'2014-01-29 09:18:12'),(1328,'edit_course',64,18,'2014-01-29 09:18:12'),(1329,'edit_training_location',64,18,'2014-01-29 09:18:12'),(1330,'duplicate_training',64,18,'2014-01-29 09:18:12'),(1331,'edit_facility',64,18,'2014-01-29 09:18:12'),(1332,'view_create_reports',64,18,'2014-01-29 09:18:12'),(1333,'admin_files',64,18,'2014-01-29 09:18:12'),(1334,'edit_employee',64,18,'2014-01-29 09:18:12'),(1335,'use_offline_app',64,18,'2014-01-29 09:18:12'),(1336,'edit_people',64,18,'2014-01-29 09:18:12'),(1337,'admin_files',65,18,'2014-01-29 09:36:38'),(1338,'edit_employee',65,18,'2014-01-29 09:36:38'),(1339,'use_offline_app',65,18,'2014-01-29 09:36:38'),(1340,'edit_people',65,18,'2014-01-29 09:36:38'),(1341,'approve_trainings',65,18,'2014-01-29 09:36:38'),(1342,'edit_course',65,18,'2014-01-29 09:36:38'),(1343,'edit_training_location',65,18,'2014-01-29 09:36:38'),(1344,'duplicate_training',65,18,'2014-01-29 09:36:38'),(1345,'edit_facility',65,18,'2014-01-29 09:36:38'),(1346,'view_create_reports',65,18,'2014-01-29 09:36:38'),(1347,'edit_course',66,18,'2014-01-29 09:43:30'),(1348,'edit_training_location',66,18,'2014-01-29 09:43:30'),(1529,'edit_training_location',85,7,'2014-01-30 14:56:29'),(1350,'edit_facility',66,18,'2014-01-29 09:43:30'),(1351,'view_create_reports',66,18,'2014-01-29 09:43:30'),(1352,'admin_files',66,18,'2014-01-29 09:43:30'),(1353,'edit_employee',66,18,'2014-01-29 09:43:30'),(1528,'edit_course',85,7,'2014-01-30 14:56:29'),(1355,'edit_people',66,18,'2014-01-29 09:43:30'),(1356,'approve_trainings',66,18,'2014-01-29 09:43:30'),(1357,'admin_files',67,18,'2014-01-29 09:48:46'),(1358,'edit_employee',67,18,'2014-01-29 09:48:46'),(1359,'use_offline_app',67,18,'2014-01-29 09:48:46'),(1360,'acl_editor_facility_sponsors',67,18,'2014-01-29 09:48:46'),(1361,'edit_people',67,18,'2014-01-29 09:48:46'),(1362,'approve_trainings',67,18,'2014-01-29 09:48:46'),(1363,'edit_course',67,18,'2014-01-29 09:48:46'),(1364,'edit_training_location',67,18,'2014-01-29 09:48:46'),(1365,'duplicate_training',67,18,'2014-01-29 09:48:46'),(1366,'edit_facility',67,18,'2014-01-29 09:48:46'),(1367,'view_create_reports',67,18,'2014-01-29 09:48:46'),(1368,'edit_facility',68,18,'2014-01-29 09:53:23'),(1369,'view_create_reports',68,18,'2014-01-29 09:53:23'),(1370,'admin_files',68,18,'2014-01-29 09:53:23'),(1371,'edit_employee',68,18,'2014-01-29 09:53:23'),(1372,'use_offline_app',68,18,'2014-01-29 09:53:23'),(1373,'acl_editor_facility_sponsors',68,18,'2014-01-29 09:53:23'),(1374,'edit_people',68,18,'2014-01-29 09:53:23'),(1375,'approve_trainings',68,18,'2014-01-29 09:53:23'),(1376,'edit_course',68,18,'2014-01-29 09:53:23'),(1377,'edit_training_location',68,18,'2014-01-29 09:53:23'),(1378,'duplicate_training',68,18,'2014-01-29 09:53:23'),(1379,'admin_files',69,18,'2014-01-29 09:56:39'),(1380,'edit_employee',69,18,'2014-01-29 09:56:39'),(1381,'use_offline_app',69,18,'2014-01-29 09:56:39'),(1382,'edit_people',69,18,'2014-01-29 09:56:39'),(1383,'approve_trainings',69,18,'2014-01-29 09:56:39'),(1384,'edit_course',69,18,'2014-01-29 09:56:39'),(1385,'edit_training_location',69,18,'2014-01-29 09:56:39'),(1386,'duplicate_training',69,18,'2014-01-29 09:56:39'),(1387,'edit_facility',69,18,'2014-01-29 09:56:39'),(1388,'view_create_reports',69,18,'2014-01-29 09:56:39'),(1389,'acl_editor_facility_sponsors',69,18,'2014-01-29 09:59:53'),(1390,'use_offline_app',70,18,'2014-01-29 10:04:12'),(1391,'edit_people',70,18,'2014-01-29 10:04:12'),(1392,'approve_trainings',70,18,'2014-01-29 10:04:12'),(1393,'edit_course',70,18,'2014-01-29 10:04:12'),(1394,'edit_training_location',70,18,'2014-01-29 10:04:12'),(1395,'duplicate_training',70,18,'2014-01-29 10:04:12'),(1396,'edit_facility',70,18,'2014-01-29 10:04:12'),(1397,'view_create_reports',70,18,'2014-01-29 10:04:12'),(1398,'admin_files',70,18,'2014-01-29 10:04:12'),(1399,'edit_employee',70,18,'2014-01-29 10:04:12'),(1400,'edit_course',71,18,'2014-01-29 10:10:19'),(1401,'edit_training_location',71,18,'2014-01-29 10:10:19'),(1402,'duplicate_training',71,18,'2014-01-29 10:10:19'),(1403,'edit_facility',71,18,'2014-01-29 10:10:19'),(1404,'view_create_reports',71,18,'2014-01-29 10:10:19'),(1405,'admin_files',71,18,'2014-01-29 10:10:19'),(1406,'edit_employee',71,18,'2014-01-29 10:10:19'),(1407,'use_offline_app',71,18,'2014-01-29 10:10:19'),(1408,'edit_people',71,18,'2014-01-29 10:10:19'),(1409,'approve_trainings',71,18,'2014-01-29 10:10:19'),(1410,'acl_editor_facility_sponsors',71,18,'2014-01-29 10:15:52'),(1411,'edit_course',72,18,'2014-01-29 10:19:46'),(1412,'edit_training_location',72,18,'2014-01-29 10:19:46'),(1833,'edit_training_location',115,107,'2014-02-06 10:26:32'),(1414,'edit_facility',72,18,'2014-01-29 10:19:46'),(1415,'view_create_reports',72,18,'2014-01-29 10:19:46'),(1416,'admin_files',72,18,'2014-01-29 10:19:46'),(1417,'edit_employee',72,18,'2014-01-29 10:19:46'),(1832,'edit_course',115,107,'2014-02-06 10:26:32'),(1419,'edit_people',72,18,'2014-01-29 10:19:46'),(1420,'approve_trainings',72,18,'2014-01-29 10:19:46'),(1421,'admin_files',73,18,'2014-01-29 10:25:35'),(1422,'edit_employee',73,18,'2014-01-29 10:25:35'),(1423,'use_offline_app',73,18,'2014-01-29 10:25:35'),(1424,'edit_people',73,18,'2014-01-29 10:25:35'),(1425,'approve_trainings',73,18,'2014-01-29 10:25:35'),(1426,'edit_course',73,18,'2014-01-29 10:25:35'),(1427,'edit_training_location',73,18,'2014-01-29 10:25:35'),(1428,'duplicate_training',73,18,'2014-01-29 10:25:35'),(1429,'edit_facility',73,18,'2014-01-29 10:25:35'),(1430,'view_create_reports',73,18,'2014-01-29 10:25:35'),(1431,'admin_files',74,18,'2014-01-29 10:28:19'),(1432,'edit_employee',74,18,'2014-01-29 10:28:19'),(1859,'edit_facility',118,107,'2014-02-06 14:50:27'),(1434,'edit_people',74,18,'2014-01-29 10:28:19'),(1860,'view_create_reports',118,107,'2014-02-06 14:50:27'),(1436,'edit_course',74,18,'2014-01-29 10:28:19'),(1437,'edit_training_location',74,18,'2014-01-29 10:28:19'),(1861,'admin_files',118,107,'2014-02-06 14:50:27'),(1439,'edit_facility',74,18,'2014-01-29 10:28:19'),(1440,'view_create_reports',74,18,'2014-01-29 10:28:19'),(1441,'admin_files',75,7,'2014-01-30 05:46:25'),(1442,'edit_employee',75,7,'2014-01-30 05:46:25'),(1443,'edit_people',75,7,'2014-01-30 05:46:25'),(1444,'approve_trainings',75,7,'2014-01-30 05:46:25'),(1445,'edit_course',75,7,'2014-01-30 05:46:25'),(1446,'edit_training_location',75,7,'2014-01-30 05:46:25'),(1447,'edit_facility',75,7,'2014-01-30 05:46:25'),(1448,'view_create_reports',75,7,'2014-01-30 05:46:25'),(1449,'edit_employee',37,7,'2014-01-30 13:08:01'),(1450,'admin_files',76,7,'2014-01-30 13:19:56'),(1451,'edit_employee',76,7,'2014-01-30 13:19:56'),(1452,'acl_editor_facility_sponsors',76,7,'2014-01-30 13:19:56'),(1453,'edit_people',76,7,'2014-01-30 13:19:56'),(1454,'approve_trainings',76,7,'2014-01-30 13:19:56'),(1455,'edit_course',76,7,'2014-01-30 13:19:56'),(1456,'edit_training_location',76,7,'2014-01-30 13:19:56'),(1457,'edit_facility',76,7,'2014-01-30 13:19:56'),(1458,'view_create_reports',76,7,'2014-01-30 13:19:56'),(1459,'admin_files',77,7,'2014-01-30 13:23:58'),(1460,'edit_employee',77,7,'2014-01-30 13:23:58'),(1461,'acl_editor_facility_sponsors',77,7,'2014-01-30 13:23:58'),(1462,'edit_people',77,7,'2014-01-30 13:23:58'),(1463,'approve_trainings',77,7,'2014-01-30 13:23:58'),(1464,'edit_course',77,7,'2014-01-30 13:23:58'),(1465,'edit_training_location',77,7,'2014-01-30 13:23:58'),(1466,'edit_facility',77,7,'2014-01-30 13:23:58'),(1467,'view_create_reports',77,7,'2014-01-30 13:23:58'),(1468,'view_create_reports',78,7,'2014-01-30 13:37:52'),(1469,'admin_files',78,7,'2014-01-30 13:37:52'),(1470,'edit_employee',78,7,'2014-01-30 13:37:52'),(1471,'use_offline_app',78,7,'2014-01-30 13:37:52'),(1472,'acl_editor_facility_sponsors',78,7,'2014-01-30 13:37:52'),(1473,'edit_people',78,7,'2014-01-30 13:37:52'),(1474,'edit_course',78,7,'2014-01-30 13:37:52'),(1475,'edit_training_location',78,7,'2014-01-30 13:37:52'),(1476,'edit_facility',78,7,'2014-01-30 13:37:52'),(1477,'view_create_reports',79,7,'2014-01-30 13:45:50'),(1478,'admin_files',79,7,'2014-01-30 13:45:50'),(1479,'edit_employee',79,7,'2014-01-30 13:45:50'),(1480,'edit_people',79,7,'2014-01-30 13:45:50'),(1481,'edit_course',79,7,'2014-01-30 13:45:50'),(1482,'edit_training_location',79,7,'2014-01-30 13:45:50'),(1483,'edit_facility',79,7,'2014-01-30 13:45:50'),(1484,'acl_editor_facility_sponsors',80,7,'2014-01-30 14:07:14'),(1485,'edit_people',80,7,'2014-01-30 14:07:14'),(1486,'approve_trainings',80,7,'2014-01-30 14:07:14'),(1487,'edit_course',80,7,'2014-01-30 14:07:14'),(1488,'edit_training_location',80,7,'2014-01-30 14:07:14'),(1489,'edit_facility',80,7,'2014-01-30 14:07:14'),(1490,'view_create_reports',80,7,'2014-01-30 14:07:14'),(1491,'admin_files',80,7,'2014-01-30 14:07:14'),(1492,'edit_employee',80,7,'2014-01-30 14:07:14'),(1493,'acl_editor_facility_sponsors',81,7,'2014-01-30 14:07:54'),(1494,'edit_people',81,7,'2014-01-30 14:07:54'),(1495,'approve_trainings',81,7,'2014-01-30 14:07:54'),(1496,'edit_course',81,7,'2014-01-30 14:07:54'),(1497,'edit_training_location',81,7,'2014-01-30 14:07:54'),(1498,'edit_facility',81,7,'2014-01-30 14:07:54'),(1499,'view_create_reports',81,7,'2014-01-30 14:07:54'),(1500,'admin_files',81,7,'2014-01-30 14:07:54'),(1501,'edit_employee',81,7,'2014-01-30 14:07:54'),(1502,'edit_employee',82,7,'2014-01-30 14:23:19'),(1503,'edit_people',82,7,'2014-01-30 14:23:19'),(1504,'approve_trainings',82,7,'2014-01-30 14:23:19'),(1505,'edit_course',82,7,'2014-01-30 14:23:19'),(1506,'edit_training_location',82,7,'2014-01-30 14:23:19'),(1507,'duplicate_training',82,7,'2014-01-30 14:23:19'),(1508,'edit_facility',82,7,'2014-01-30 14:23:19'),(1509,'view_create_reports',82,7,'2014-01-30 14:23:19'),(1510,'admin_files',82,7,'2014-01-30 14:23:19'),(1511,'edit_people',83,7,'2014-01-30 14:30:34'),(1512,'edit_course',83,7,'2014-01-30 14:30:34'),(1513,'edit_training_location',83,7,'2014-01-30 14:30:34'),(1514,'duplicate_training',83,7,'2014-01-30 14:30:34'),(1515,'edit_facility',83,7,'2014-01-30 14:30:34'),(1516,'view_create_reports',83,7,'2014-01-30 14:30:34'),(1517,'admin_files',83,7,'2014-01-30 14:30:34'),(1518,'edit_employee',83,7,'2014-01-30 14:30:34'),(1519,'use_offline_app',83,7,'2014-01-30 14:30:34'),(1520,'edit_people',84,7,'2014-01-30 14:47:01'),(1521,'approve_trainings',84,7,'2014-01-30 14:47:01'),(1522,'edit_course',84,7,'2014-01-30 14:47:01'),(1523,'edit_training_location',84,7,'2014-01-30 14:47:01'),(1524,'edit_facility',84,7,'2014-01-30 14:47:01'),(1525,'view_create_reports',84,7,'2014-01-30 14:47:01'),(1526,'admin_files',84,7,'2014-01-30 14:47:01'),(1527,'edit_employee',84,7,'2014-01-30 14:47:01'),(1530,'edit_facility',85,7,'2014-01-30 14:56:29'),(1531,'view_create_reports',85,7,'2014-01-30 14:56:29'),(1532,'admin_files',85,7,'2014-01-30 14:56:29'),(1533,'edit_employee',85,7,'2014-01-30 14:56:29'),(1534,'acl_editor_facility_sponsors',85,7,'2014-01-30 14:56:29'),(1535,'edit_people',85,7,'2014-01-30 14:56:29'),(1536,'approve_trainings',85,7,'2014-01-30 14:56:29'),(1537,'edit_course',86,7,'2014-01-30 15:01:30'),(1538,'edit_training_location',86,7,'2014-01-30 15:01:30'),(1539,'edit_facility',86,7,'2014-01-30 15:01:30'),(1540,'view_create_reports',86,7,'2014-01-30 15:01:30'),(1541,'admin_files',86,7,'2014-01-30 15:01:30'),(1542,'edit_employee',86,7,'2014-01-30 15:01:30'),(1543,'edit_people',86,7,'2014-01-30 15:01:30'),(1544,'approve_trainings',86,7,'2014-01-30 15:01:30'),(1545,'edit_course',87,7,'2014-01-30 15:02:12'),(1546,'edit_training_location',87,7,'2014-01-30 15:02:12'),(1547,'edit_facility',87,7,'2014-01-30 15:02:12'),(1548,'view_create_reports',87,7,'2014-01-30 15:02:12'),(1549,'admin_files',87,7,'2014-01-30 15:02:12'),(1550,'edit_employee',87,7,'2014-01-30 15:02:12'),(1551,'acl_editor_facility_sponsors',87,7,'2014-01-30 15:02:12'),(1552,'edit_people',87,7,'2014-01-30 15:02:12'),(1553,'approve_trainings',87,7,'2014-01-30 15:02:12'),(1554,'edit_course',88,7,'2014-01-31 06:55:59'),(1555,'edit_training_location',88,7,'2014-01-31 06:55:59'),(1556,'edit_facility',88,7,'2014-01-31 06:55:59'),(1557,'view_create_reports',88,7,'2014-01-31 06:55:59'),(1558,'admin_files',88,7,'2014-01-31 06:55:59'),(1559,'edit_employee',88,7,'2014-01-31 06:55:59'),(1560,'acl_editor_facility_sponsors',88,7,'2014-01-31 06:55:59'),(1561,'edit_people',88,7,'2014-01-31 06:55:59'),(1562,'edit_facility',89,7,'2014-01-31 06:57:01'),(1563,'view_create_reports',89,7,'2014-01-31 06:57:01'),(1564,'admin_files',89,7,'2014-01-31 06:57:01'),(1565,'edit_employee',89,7,'2014-01-31 06:57:01'),(1566,'acl_editor_facility_sponsors',89,7,'2014-01-31 06:57:01'),(1567,'edit_people',89,7,'2014-01-31 06:57:01'),(1568,'edit_course',89,7,'2014-01-31 06:57:01'),(1569,'edit_training_location',89,7,'2014-01-31 06:57:01'),(1570,'approve_trainings',90,7,'2014-01-31 10:43:52'),(1571,'edit_course',90,7,'2014-01-31 10:43:52'),(1572,'edit_training_location',90,7,'2014-01-31 10:43:52'),(1573,'edit_facility',90,7,'2014-01-31 10:43:52'),(1574,'view_create_reports',90,7,'2014-01-31 10:43:52'),(1575,'admin_files',90,7,'2014-01-31 10:43:52'),(2755,'edit_course',181,1,'2014-08-06 06:02:13'),(1577,'acl_editor_facility_sponsors',90,7,'2014-01-31 10:43:52'),(1578,'edit_people',90,7,'2014-01-31 10:43:52'),(1580,'edit_employee',91,7,'2014-01-31 11:15:30'),(1581,'edit_people',91,7,'2014-01-31 11:15:30'),(1582,'approve_trainings',91,7,'2014-01-31 11:15:30'),(1583,'edit_course',91,7,'2014-01-31 11:15:30'),(1584,'edit_training_location',91,7,'2014-01-31 11:15:30'),(1585,'edit_facility',91,7,'2014-01-31 11:15:30'),(1586,'view_create_reports',91,7,'2014-01-31 11:15:30'),(1587,'edit_facility',92,7,'2014-02-03 07:45:48'),(1588,'view_create_reports',92,7,'2014-02-03 07:45:48'),(1589,'admin_files',92,7,'2014-02-03 07:45:48'),(1590,'edit_employee',92,7,'2014-02-03 07:45:48'),(1591,'edit_people',92,7,'2014-02-03 07:45:48'),(1592,'approve_trainings',92,7,'2014-02-03 07:45:48'),(1593,'edit_course',92,7,'2014-02-03 07:45:48'),(1594,'edit_training_location',92,7,'2014-02-03 07:45:48'),(1595,'edit_facility',93,7,'2014-02-03 07:46:51'),(1596,'view_create_reports',93,7,'2014-02-03 07:46:51'),(1597,'admin_files',93,7,'2014-02-03 07:46:51'),(1598,'edit_employee',93,7,'2014-02-03 07:46:51'),(1599,'acl_editor_facility_sponsors',93,7,'2014-02-03 07:46:51'),(1600,'edit_people',93,7,'2014-02-03 07:46:51'),(1601,'approve_trainings',93,7,'2014-02-03 07:46:51'),(1602,'edit_course',93,7,'2014-02-03 07:46:51'),(1603,'edit_training_location',93,7,'2014-02-03 07:46:51'),(1604,'view_create_reports',94,7,'2014-02-03 15:09:50'),(1605,'admin_files',94,7,'2014-02-03 15:09:50'),(1606,'edit_employee',94,7,'2014-02-03 15:09:50'),(1607,'acl_editor_facility_sponsors',94,7,'2014-02-03 15:09:50'),(1608,'edit_people',94,7,'2014-02-03 15:09:50'),(1609,'approve_trainings',94,7,'2014-02-03 15:09:50'),(1610,'edit_course',94,7,'2014-02-03 15:09:50'),(1611,'edit_training_location',94,7,'2014-02-03 15:09:50'),(1612,'edit_facility',94,7,'2014-02-03 15:09:50'),(1613,'view_create_reports',95,7,'2014-02-03 15:13:04'),(1614,'admin_files',95,7,'2014-02-03 15:13:04'),(1615,'edit_employee',95,7,'2014-02-03 15:13:04'),(1616,'acl_editor_facility_sponsors',95,7,'2014-02-03 15:13:04'),(1617,'edit_people',95,7,'2014-02-03 15:13:04'),(1618,'edit_course',95,7,'2014-02-03 15:13:04'),(1619,'edit_training_location',95,7,'2014-02-03 15:13:04'),(1620,'edit_facility',95,7,'2014-02-03 15:13:04'),(1621,'acl_editor_facility_sponsors',96,7,'2014-02-03 15:14:04'),(1622,'edit_people',96,7,'2014-02-03 15:14:04'),(1623,'edit_course',96,7,'2014-02-03 15:14:04'),(1624,'edit_training_location',96,7,'2014-02-03 15:14:04'),(1625,'edit_facility',96,7,'2014-02-03 15:14:04'),(1626,'view_create_reports',96,7,'2014-02-03 15:14:04'),(1627,'admin_files',96,7,'2014-02-03 15:14:04'),(1628,'edit_employee',96,7,'2014-02-03 15:14:04'),(1629,'acl_editor_facility_sponsors',97,7,'2014-02-03 15:15:18'),(1630,'edit_people',97,7,'2014-02-03 15:15:18'),(1631,'edit_course',97,7,'2014-02-03 15:15:18'),(1632,'edit_training_location',97,7,'2014-02-03 15:15:18'),(1633,'edit_facility',97,7,'2014-02-03 15:15:18'),(1634,'view_create_reports',97,7,'2014-02-03 15:15:18'),(1635,'admin_files',97,7,'2014-02-03 15:15:18'),(1636,'edit_employee',97,7,'2014-02-03 15:15:18'),(1637,'edit_course',98,7,'2014-02-04 06:52:37'),(1638,'edit_training_location',98,7,'2014-02-04 06:52:37'),(1639,'edit_facility',98,7,'2014-02-04 06:52:37'),(1640,'view_create_reports',98,7,'2014-02-04 06:52:37'),(1641,'admin_files',98,7,'2014-02-04 06:52:37'),(1642,'edit_employee',98,7,'2014-02-04 06:52:37'),(1643,'acl_editor_facility_sponsors',98,7,'2014-02-04 06:52:37'),(1644,'edit_people',98,7,'2014-02-04 06:52:37'),(1645,'edit_facility',99,7,'2014-02-04 07:54:43'),(1646,'view_create_reports',99,7,'2014-02-04 07:54:43'),(1647,'admin_files',99,7,'2014-02-04 07:54:43'),(1648,'edit_employee',99,7,'2014-02-04 07:54:43'),(1649,'acl_editor_facility_sponsors',99,7,'2014-02-04 07:54:43'),(1650,'edit_people',99,7,'2014-02-04 07:54:43'),(1651,'edit_course',99,7,'2014-02-04 07:54:43'),(1652,'edit_training_location',99,7,'2014-02-04 07:54:43'),(1653,'edit_course',100,7,'2014-02-04 07:55:53'),(1654,'edit_training_location',100,7,'2014-02-04 07:55:53'),(1655,'edit_facility',100,7,'2014-02-04 07:55:53'),(1656,'view_create_reports',100,7,'2014-02-04 07:55:53'),(1657,'admin_files',100,7,'2014-02-04 07:55:53'),(1658,'edit_employee',100,7,'2014-02-04 07:55:53'),(1659,'acl_editor_facility_sponsors',100,7,'2014-02-04 07:55:53'),(1660,'edit_people',100,7,'2014-02-04 07:55:53'),(1661,'edit_course',101,7,'2014-02-04 08:11:24'),(1662,'edit_training_location',101,7,'2014-02-04 08:11:24'),(1663,'edit_facility',101,7,'2014-02-04 08:11:24'),(1664,'view_create_reports',101,7,'2014-02-04 08:11:24'),(1665,'admin_files',101,7,'2014-02-04 08:11:24'),(1666,'edit_employee',101,7,'2014-02-04 08:11:24'),(1667,'acl_editor_facility_sponsors',101,7,'2014-02-04 08:11:24'),(1668,'edit_people',101,7,'2014-02-04 08:11:24'),(1669,'approve_trainings',101,7,'2014-02-04 08:11:24'),(1670,'edit_employee',102,7,'2014-02-04 09:48:38'),(1671,'edit_people',102,7,'2014-02-04 09:48:38'),(1672,'approve_trainings',102,7,'2014-02-04 09:48:38'),(1673,'edit_course',102,7,'2014-02-04 09:48:38'),(1674,'edit_training_location',102,7,'2014-02-04 09:48:38'),(1675,'edit_facility',102,7,'2014-02-04 09:48:38'),(1676,'view_create_reports',102,7,'2014-02-04 09:48:38'),(1677,'admin_files',102,7,'2014-02-04 09:48:38'),(1678,'edit_course',103,7,'2014-02-04 10:16:33'),(1679,'edit_training_location',103,7,'2014-02-04 10:16:33'),(1680,'edit_facility',103,7,'2014-02-04 10:16:33'),(1681,'view_create_reports',103,7,'2014-02-04 10:16:33'),(1682,'admin_files',103,7,'2014-02-04 10:16:33'),(1683,'edit_employee',103,7,'2014-02-04 10:16:33'),(1684,'acl_editor_facility_sponsors',103,7,'2014-02-04 10:16:33'),(1685,'edit_people',103,7,'2014-02-04 10:16:33'),(1686,'approve_trainings',103,7,'2014-02-04 10:16:33'),(1687,'acl_editor_facility_sponsors',91,7,'2014-02-04 10:17:55'),(1688,'edit_training_location',104,7,'2014-02-04 10:36:36'),(1689,'edit_facility',104,7,'2014-02-04 10:36:36'),(1690,'view_create_reports',104,7,'2014-02-04 10:36:36'),(1691,'admin_files',104,7,'2014-02-04 10:36:36'),(1692,'edit_employee',104,7,'2014-02-04 10:36:36'),(1693,'edit_people',104,7,'2014-02-04 10:36:36'),(1694,'approve_trainings',104,7,'2014-02-04 10:36:36'),(1695,'edit_course',104,7,'2014-02-04 10:36:36'),(1696,'edit_people',105,7,'2014-02-05 04:49:52'),(1697,'training_organizer_option_all',105,7,'2014-02-05 04:49:52'),(1698,'acl_editor_facility_sponsors',105,7,'2014-02-05 04:49:52'),(1699,'approve_trainings',105,7,'2014-02-05 04:49:52'),(1700,'edit_training_location',105,7,'2014-02-05 04:49:52'),(1701,'edit_course',105,7,'2014-02-05 04:49:52'),(1702,'edit_facility',105,7,'2014-02-05 04:49:52'),(1703,'view_create_reports',105,7,'2014-02-05 04:49:52'),(1706,'edit_people',106,7,'2014-02-05 04:52:53'),(1707,'training_organizer_option_all',106,7,'2014-02-05 04:52:53'),(1708,'edit_course',106,7,'2014-02-05 04:52:53'),(1709,'edit_training_location',106,7,'2014-02-05 04:52:53'),(1710,'edit_facility',106,7,'2014-02-05 04:52:53'),(1711,'view_create_reports',106,7,'2014-02-05 04:52:53'),(2655,'acl_editor_facility_types',107,110,'2014-07-02 11:09:07'),(2625,'acl_editor_pepfar_category',107,110,'2014-07-02 11:09:07'),(2641,'acl_editor_people_suffix',107,110,'2014-07-02 11:09:07'),(2637,'acl_editor_facility_sponsors',107,110,'2014-07-02 11:09:07'),(2656,'add_edit_users',107,110,'2014-07-02 11:09:07'),(2631,'acl_editor_funding',107,110,'2014-07-02 11:09:07'),(2645,'acl_editor_ps_sponsors',107,110,'2014-07-02 11:09:07'),(1719,'edit_course',107,7,'2014-02-05 08:15:22'),(2651,'import_facility',107,110,'2014-07-02 11:09:07'),(2658,'acl_editor_people_titles',107,110,'2014-07-02 11:09:07'),(1722,'edit_training_location',107,7,'2014-02-05 08:15:22'),(2644,'acl_editor_ps_institutions',107,110,'2014-07-02 11:09:07'),(2626,'import_person',107,110,'2014-07-02 11:09:07'),(2636,'acl_editor_recommended_topic',107,110,'2014-07-02 11:09:07'),(2666,'acl_editor_nationalcurriculum',107,110,'2014-07-02 11:09:07'),(2634,'acl_editor_ps_coursetypes',107,110,'2014-07-02 11:09:07'),(2661,'acl_editor_ps_joindropreasons',107,110,'2014-07-02 11:09:07'),(2630,'acl_editor_training_category',107,110,'2014-07-02 11:09:07'),(2649,'acl_editor_people_qualifications',107,110,'2014-07-02 11:09:07'),(1731,'edit_facility',107,7,'2014-02-05 08:15:22'),(2662,'training_title_option_all',107,110,'2014-07-02 11:09:07'),(2654,'acl_editor_refresher_course',107,110,'2014-07-02 11:09:07'),(2624,'acl_editor_people_responsibility',107,110,'2014-07-02 11:09:07'),(2640,'edit_country_options',107,110,'2014-07-02 11:09:07'),(2632,'acl_editor_people_languages',107,110,'2014-07-02 11:09:07'),(1737,'view_create_reports',107,7,'2014-02-05 08:15:22'),(2664,'acl_admin_training',107,110,'2014-07-02 11:09:07'),(2628,'acl_editor_ps_religions',107,110,'2014-07-02 11:09:07'),(1740,'admin_files',107,7,'2014-02-05 08:15:22'),(2652,'acl_editor_ps_degrees',107,110,'2014-07-02 11:09:07'),(1742,'edit_employee',107,7,'2014-02-05 08:15:22'),(2653,'acl_editor_ps_nationalities',107,110,'2014-07-02 11:09:07'),(2660,'import_training_location',107,110,'2014-07-02 11:09:07'),(2633,'acl_editor_people_trainer',107,110,'2014-07-02 11:09:07'),(2638,'acl_editor_training_topic',107,110,'2014-07-02 11:09:07'),(2650,'acl_editor_people_trainer_skills',107,110,'2014-07-02 11:09:07'),(2659,'acl_editor_ps_classes',107,110,'2014-07-02 11:09:07'),(2643,'import_training',107,110,'2014-07-02 11:09:07'),(2646,'acl_admin_people',107,110,'2014-07-02 11:09:07'),(1752,'edit_people',107,7,'2014-02-05 08:15:22'),(2648,'acl_editor_method',107,110,'2014-07-02 11:09:07'),(2642,'acl_editor_ps_cadres',107,110,'2014-07-02 11:09:07'),(2627,'acl_editor_ps_funding',107,110,'2014-07-02 11:09:07'),(2639,'acl_editor_people_active_trainer',107,110,'2014-07-02 11:09:07'),(1757,'training_organizer_option_all',107,7,'2014-02-05 08:15:22'),(2635,'acl_editor_ps_languages',107,110,'2014-07-02 11:09:07'),(2657,'acl_editor_people_affiliations',107,110,'2014-07-02 11:09:07'),(2629,'acl_admin_facilities',107,110,'2014-07-02 11:09:07'),(1761,'approve_trainings',107,7,'2014-02-05 08:15:22'),(2647,'acl_editor_training_level',107,110,'2014-07-02 11:09:07'),(2663,'acl_editor_ps_tutortypes',107,110,'2014-07-02 11:09:07'),(1764,'edit_people',108,7,'2014-02-05 10:24:22'),(1765,'approve_trainings',108,7,'2014-02-05 10:24:22'),(1766,'edit_course',108,7,'2014-02-05 10:24:22'),(1767,'edit_training_location',108,7,'2014-02-05 10:24:22'),(1768,'edit_facility',108,7,'2014-02-05 10:24:22'),(1769,'view_create_reports',108,7,'2014-02-05 10:24:22'),(1770,'admin_files',108,7,'2014-02-05 10:24:22'),(1771,'edit_employee',108,7,'2014-02-05 10:24:22'),(1772,'acl_editor_facility_sponsors',108,7,'2014-02-05 10:24:22'),(1810,'edit_training_location',113,107,'2014-02-05 13:19:47'),(1774,'edit_course',109,107,'2014-02-05 11:25:47'),(1775,'edit_training_location',109,107,'2014-02-05 11:25:47'),(1776,'edit_facility',109,107,'2014-02-05 11:25:47'),(1777,'view_create_reports',109,107,'2014-02-05 11:25:47'),(1778,'admin_files',109,107,'2014-02-05 11:25:47'),(1779,'edit_employee',109,107,'2014-02-05 11:25:47'),(1780,'edit_people',109,107,'2014-02-05 11:25:47'),(1781,'edit_course',110,107,'2014-02-05 11:38:44'),(2921,'edit_facility',200,1,'2014-08-22 08:30:11'),(2907,'edit_course',199,1,'2014-08-20 11:39:56'),(1784,'edit_facility',110,107,'2014-02-05 11:38:44'),(1785,'view_create_reports',110,107,'2014-02-05 11:38:44'),(2913,'edit_employee',199,1,'2014-08-20 11:39:56'),(2927,'admin_files',201,1,'2014-08-22 08:31:48'),(2932,'edit_training_location',201,1,'2014-08-22 08:31:48'),(1789,'edit_people',110,107,'2014-02-05 11:38:44'),(1790,'edit_course',111,107,'2014-02-05 11:57:42'),(1791,'edit_training_location',111,107,'2014-02-05 11:57:42'),(1792,'duplicate_training',111,107,'2014-02-05 11:57:42'),(1793,'edit_facility',111,107,'2014-02-05 11:57:42'),(1794,'view_create_reports',111,107,'2014-02-05 11:57:42'),(1795,'admin_files',111,107,'2014-02-05 11:57:42'),(1796,'in_service',111,107,'2014-02-05 11:57:42'),(1797,'use_offline_app',111,107,'2014-02-05 11:57:42'),(1798,'edit_people',111,107,'2014-02-05 11:57:42'),(1799,'edit_course',112,107,'2014-02-05 12:25:59'),(1800,'edit_training_location',112,107,'2014-02-05 12:25:59'),(1801,'edit_facility',112,107,'2014-02-05 12:25:59'),(1802,'view_create_reports',112,107,'2014-02-05 12:25:59'),(1803,'admin_files',112,107,'2014-02-05 12:25:59'),(1804,'edit_employee',112,107,'2014-02-05 12:25:59'),(1805,'acl_editor_facility_sponsors',112,107,'2014-02-05 12:25:59'),(1806,'edit_people',112,107,'2014-02-05 12:25:59'),(1807,'approve_trainings',112,107,'2014-02-05 12:25:59'),(1808,'acl_editor_facility_sponsors',109,107,'2014-02-05 12:33:51'),(1809,'approve_trainings',109,107,'2014-02-05 12:33:51'),(1811,'duplicate_training',113,107,'2014-02-05 13:19:47'),(1812,'edit_facility',113,107,'2014-02-05 13:19:47'),(1813,'view_create_reports',113,107,'2014-02-05 13:19:47'),(1814,'admin_files',113,107,'2014-02-05 13:19:47'),(1815,'edit_employee',113,107,'2014-02-05 13:19:47'),(1816,'use_offline_app',113,107,'2014-02-05 13:19:47'),(1817,'acl_editor_facility_sponsors',113,107,'2014-02-05 13:19:47'),(1818,'edit_people',113,107,'2014-02-05 13:19:47'),(1819,'edit_course',113,107,'2014-02-05 13:19:47'),(1820,'edit_course',114,107,'2014-02-05 13:40:42'),(1821,'edit_training_location',114,107,'2014-02-05 13:40:42'),(1822,'duplicate_training',114,107,'2014-02-05 13:40:42'),(1823,'edit_facility',114,107,'2014-02-05 13:40:42'),(1824,'view_create_reports',114,107,'2014-02-05 13:40:42'),(1825,'admin_files',114,107,'2014-02-05 13:40:42'),(1826,'edit_employee',114,107,'2014-02-05 13:40:42'),(1827,'use_offline_app',114,107,'2014-02-05 13:40:42'),(1828,'acl_editor_facility_sponsors',114,107,'2014-02-05 13:40:42'),(1829,'edit_people',114,107,'2014-02-05 13:40:42'),(1830,'approve_trainings',114,107,'2014-02-05 13:40:42'),(1831,'acl_editor_facility_sponsors',72,7,'2014-02-06 09:04:14'),(1834,'edit_facility',115,107,'2014-02-06 10:26:32'),(1835,'view_create_reports',115,107,'2014-02-06 10:26:32'),(1836,'admin_files',115,107,'2014-02-06 10:26:32'),(1837,'acl_editor_facility_sponsors',115,107,'2014-02-06 10:26:32'),(1838,'edit_people',115,107,'2014-02-06 10:26:32'),(1839,'edit_course',116,107,'2014-02-06 10:44:46'),(1840,'edit_training_location',116,107,'2014-02-06 10:44:46'),(1841,'duplicate_training',116,107,'2014-02-06 10:44:46'),(1842,'edit_facility',116,107,'2014-02-06 10:44:46'),(1843,'view_create_reports',116,107,'2014-02-06 10:44:46'),(1844,'admin_files',116,107,'2014-02-06 10:44:46'),(1845,'use_offline_app',116,107,'2014-02-06 10:44:46'),(1846,'acl_editor_facility_sponsors',116,107,'2014-02-06 10:44:46'),(1847,'edit_people',116,107,'2014-02-06 10:44:46'),(1848,'approve_trainings',116,107,'2014-02-06 10:44:46'),(1849,'use_offline_app',117,107,'2014-02-06 10:46:42'),(1850,'acl_editor_facility_sponsors',117,107,'2014-02-06 10:46:42'),(1851,'edit_people',117,107,'2014-02-06 10:46:42'),(1852,'approve_trainings',117,107,'2014-02-06 10:46:42'),(1853,'edit_course',117,107,'2014-02-06 10:46:42'),(1854,'edit_training_location',117,107,'2014-02-06 10:46:42'),(1855,'duplicate_training',117,107,'2014-02-06 10:46:42'),(1856,'edit_facility',117,107,'2014-02-06 10:46:42'),(1857,'view_create_reports',117,107,'2014-02-06 10:46:42'),(1858,'admin_files',117,107,'2014-02-06 10:46:42'),(1864,'edit_people',118,107,'2014-02-06 14:50:27'),(1865,'edit_course',118,107,'2014-02-06 14:50:27'),(1866,'edit_training_location',118,107,'2014-02-06 14:50:27'),(1867,'duplicate_training',118,107,'2014-02-06 14:50:27'),(1868,'edit_course',119,107,'2014-02-06 15:04:36'),(1869,'edit_training_location',119,107,'2014-02-06 15:04:36'),(1870,'duplicate_training',119,107,'2014-02-06 15:04:36'),(1871,'edit_facility',119,107,'2014-02-06 15:04:36'),(1872,'view_create_reports',119,107,'2014-02-06 15:04:36'),(1873,'admin_files',119,107,'2014-02-06 15:04:36'),(1874,'use_offline_app',119,107,'2014-02-06 15:04:36'),(1875,'acl_editor_facility_sponsors',119,107,'2014-02-06 15:04:36'),(1876,'edit_people',119,107,'2014-02-06 15:04:36'),(1877,'edit_course',120,7,'2014-02-06 15:09:19'),(1878,'edit_training_location',120,7,'2014-02-06 15:09:19'),(1879,'edit_facility',120,7,'2014-02-06 15:09:19'),(1880,'view_create_reports',120,7,'2014-02-06 15:09:19'),(1881,'admin_files',120,7,'2014-02-06 15:09:19'),(1882,'edit_employee',120,7,'2014-02-06 15:09:19'),(1883,'acl_editor_facility_sponsors',120,7,'2014-02-06 15:09:19'),(1884,'edit_people',120,7,'2014-02-06 15:09:19'),(1885,'approve_trainings',120,7,'2014-02-06 15:09:19'),(1886,'edit_course',121,107,'2014-02-07 07:30:58'),(1887,'edit_training_location',121,107,'2014-02-07 07:30:58'),(1888,'duplicate_training',121,107,'2014-02-07 07:30:58'),(1889,'edit_facility',121,107,'2014-02-07 07:30:58'),(1890,'view_create_reports',121,107,'2014-02-07 07:30:58'),(1891,'admin_files',121,107,'2014-02-07 07:30:58'),(1892,'use_offline_app',121,107,'2014-02-07 07:30:58'),(1893,'acl_editor_facility_sponsors',121,107,'2014-02-07 07:30:58'),(1894,'edit_people',121,107,'2014-02-07 07:30:58'),(1895,'use_offline_app',122,107,'2014-02-07 07:35:32'),(1896,'acl_editor_facility_sponsors',122,107,'2014-02-07 07:35:32'),(1897,'edit_people',122,107,'2014-02-07 07:35:32'),(1898,'edit_course',122,107,'2014-02-07 07:35:32'),(1899,'edit_training_location',122,107,'2014-02-07 07:35:33'),(1900,'duplicate_training',122,107,'2014-02-07 07:35:33'),(1901,'edit_facility',122,107,'2014-02-07 07:35:33'),(1902,'view_create_reports',122,107,'2014-02-07 07:35:33'),(1903,'admin_files',122,107,'2014-02-07 07:35:33'),(1904,'edit_employee',122,107,'2014-02-07 07:40:52'),(1905,'edit_course',123,107,'2014-02-07 08:03:34'),(1906,'edit_training_location',123,107,'2014-02-07 08:03:34'),(1907,'duplicate_training',123,107,'2014-02-07 08:03:34'),(1908,'edit_facility',123,107,'2014-02-07 08:03:34'),(1909,'view_create_reports',123,107,'2014-02-07 08:03:34'),(1910,'admin_files',123,107,'2014-02-07 08:03:34'),(1911,'edit_employee',123,107,'2014-02-07 08:03:34'),(1912,'use_offline_app',123,107,'2014-02-07 08:03:34'),(1913,'acl_editor_facility_sponsors',123,107,'2014-02-07 08:03:34'),(1914,'edit_people',123,107,'2014-02-07 08:03:34'),(1915,'edit_employee',119,107,'2014-02-07 08:18:44'),(1916,'edit_training_location',124,107,'2014-02-07 08:31:08'),(1917,'duplicate_training',124,107,'2014-02-07 08:31:08'),(1918,'edit_facility',124,107,'2014-02-07 08:31:08'),(1919,'view_create_reports',124,107,'2014-02-07 08:31:08'),(1920,'admin_files',124,107,'2014-02-07 08:31:08'),(1921,'edit_employee',124,107,'2014-02-07 08:31:08'),(1922,'use_offline_app',124,107,'2014-02-07 08:31:08'),(1923,'acl_editor_facility_sponsors',124,107,'2014-02-07 08:31:08'),(1924,'edit_people',124,107,'2014-02-07 08:31:08'),(1925,'edit_course',124,107,'2014-02-07 08:31:08'),(1926,'acl_editor_facility_sponsors',73,107,'2014-02-07 09:16:38'),(1927,'edit_course',125,107,'2014-02-07 09:21:37'),(1928,'edit_training_location',125,107,'2014-02-07 09:21:37'),(1929,'duplicate_training',125,107,'2014-02-07 09:21:37'),(1930,'edit_facility',125,107,'2014-02-07 09:21:37'),(1931,'view_create_reports',125,107,'2014-02-07 09:21:37'),(1932,'admin_files',125,107,'2014-02-07 09:21:37'),(1933,'edit_employee',125,107,'2014-02-07 09:21:37'),(1934,'use_offline_app',125,107,'2014-02-07 09:21:37'),(1935,'edit_people',125,107,'2014-02-07 09:21:37'),(1936,'view_create_reports',126,107,'2014-02-07 09:45:58'),(1937,'admin_files',126,107,'2014-02-07 09:45:58'),(1938,'edit_employee',126,107,'2014-02-07 09:45:58'),(1939,'use_offline_app',126,107,'2014-02-07 09:45:58'),(1940,'edit_people',126,107,'2014-02-07 09:45:58'),(1941,'edit_course',126,107,'2014-02-07 09:45:58'),(1942,'edit_training_location',126,107,'2014-02-07 09:45:58'),(1943,'duplicate_training',126,107,'2014-02-07 09:45:58'),(1944,'edit_facility',126,107,'2014-02-07 09:45:58'),(1945,'edit_course',127,107,'2014-02-07 09:51:17'),(1946,'edit_training_location',127,107,'2014-02-07 09:51:17'),(1947,'duplicate_training',127,107,'2014-02-07 09:51:17'),(1948,'edit_facility',127,107,'2014-02-07 09:51:17'),(1949,'view_create_reports',127,107,'2014-02-07 09:51:17'),(1950,'admin_files',127,107,'2014-02-07 09:51:17'),(1951,'edit_employee',127,107,'2014-02-07 09:51:17'),(1952,'use_offline_app',127,107,'2014-02-07 09:51:17'),(1953,'acl_editor_facility_sponsors',127,107,'2014-02-07 09:51:17'),(1954,'edit_people',127,107,'2014-02-07 09:51:17'),(1955,'edit_employee',20,107,'2014-02-07 09:52:17'),(1956,'edit_course',128,107,'2014-02-07 10:00:50'),(1957,'edit_training_location',128,107,'2014-02-07 10:00:50'),(1958,'duplicate_training',128,107,'2014-02-07 10:00:50'),(1959,'edit_facility',128,107,'2014-02-07 10:00:50'),(1960,'view_create_reports',128,107,'2014-02-07 10:00:50'),(1961,'admin_files',128,107,'2014-02-07 10:00:50'),(1962,'edit_employee',128,107,'2014-02-07 10:00:50'),(1963,'use_offline_app',128,107,'2014-02-07 10:00:50'),(1964,'acl_editor_facility_sponsors',128,107,'2014-02-07 10:00:50'),(1965,'edit_people',128,107,'2014-02-07 10:00:50'),(1966,'edit_course',129,107,'2014-02-07 10:07:47'),(1967,'edit_training_location',129,107,'2014-02-07 10:07:47'),(1968,'duplicate_training',129,107,'2014-02-07 10:07:47'),(1969,'edit_facility',129,107,'2014-02-07 10:07:47'),(1970,'view_create_reports',129,107,'2014-02-07 10:07:47'),(1971,'admin_files',129,107,'2014-02-07 10:07:47'),(1972,'edit_employee',129,107,'2014-02-07 10:07:47'),(1973,'use_offline_app',129,107,'2014-02-07 10:07:47'),(1974,'acl_editor_facility_sponsors',129,107,'2014-02-07 10:07:47'),(1975,'edit_people',129,107,'2014-02-07 10:07:47'),(1976,'edit_course',130,107,'2014-02-07 10:52:30'),(1977,'edit_training_location',130,107,'2014-02-07 10:52:30'),(1978,'duplicate_training',130,107,'2014-02-07 10:52:30'),(1979,'edit_facility',130,107,'2014-02-07 10:52:30'),(1980,'view_create_reports',130,107,'2014-02-07 10:52:30'),(1981,'admin_files',130,107,'2014-02-07 10:52:30'),(1982,'edit_employee',130,107,'2014-02-07 10:52:30'),(1983,'use_offline_app',130,107,'2014-02-07 10:52:30'),(1984,'acl_editor_facility_sponsors',130,107,'2014-02-07 10:52:30'),(1985,'edit_people',130,107,'2014-02-07 10:52:30'),(1986,'edit_course',131,107,'2014-02-07 11:23:22'),(1987,'edit_training_location',131,107,'2014-02-07 11:23:22'),(1988,'duplicate_training',131,107,'2014-02-07 11:23:22'),(1989,'edit_facility',131,107,'2014-02-07 11:23:22'),(1990,'view_create_reports',131,107,'2014-02-07 11:23:22'),(1991,'admin_files',131,107,'2014-02-07 11:23:22'),(1992,'edit_employee',131,107,'2014-02-07 11:23:22'),(1993,'use_offline_app',131,107,'2014-02-07 11:23:22'),(1994,'acl_editor_facility_sponsors',131,107,'2014-02-07 11:23:22'),(1995,'edit_people',131,107,'2014-02-07 11:23:22'),(1996,'edit_facility',132,107,'2014-02-07 12:14:05'),(1997,'view_create_reports',132,107,'2014-02-07 12:14:05'),(1998,'admin_files',132,107,'2014-02-07 12:14:05'),(1999,'edit_employee',132,107,'2014-02-07 12:14:05'),(2015,'acl_editor_facility_sponsors',79,107,'2014-02-10 06:28:18'),(2001,'acl_editor_facility_sponsors',132,107,'2014-02-07 12:14:05'),(2002,'edit_people',132,107,'2014-02-07 12:14:05'),(2003,'edit_course',132,107,'2014-02-07 12:14:05'),(2004,'edit_training_location',132,107,'2014-02-07 12:14:05'),(2016,'edit_course',134,107,'2014-02-10 06:32:59'),(2006,'approve_trainings',133,7,'2014-02-07 13:54:58'),(2007,'edit_course',133,7,'2014-02-07 13:54:58'),(2008,'edit_training_location',133,7,'2014-02-07 13:54:58'),(2009,'edit_facility',133,7,'2014-02-07 13:54:58'),(2010,'view_create_reports',133,7,'2014-02-07 13:54:58'),(2011,'admin_files',133,7,'2014-02-07 13:54:58'),(2012,'edit_employee',133,7,'2014-02-07 13:54:58'),(2013,'acl_editor_facility_sponsors',133,7,'2014-02-07 13:54:58'),(2014,'edit_people',133,7,'2014-02-07 13:54:58'),(2017,'edit_training_location',134,107,'2014-02-10 06:32:59'),(2018,'duplicate_training',134,107,'2014-02-10 06:32:59'),(2019,'edit_facility',134,107,'2014-02-10 06:32:59'),(2020,'view_create_reports',134,107,'2014-02-10 06:32:59'),(2021,'admin_files',134,107,'2014-02-10 06:32:59'),(2022,'edit_employee',134,107,'2014-02-10 06:32:59'),(2023,'use_offline_app',134,107,'2014-02-10 06:32:59'),(2024,'acl_editor_facility_sponsors',134,107,'2014-02-10 06:32:59'),(2025,'edit_people',134,107,'2014-02-10 06:32:59'),(2026,'edit_facility',135,107,'2014-02-10 07:23:52'),(2027,'acl_editor_people_responsibility',135,107,'2014-02-10 07:23:52'),(2028,'import_person',135,107,'2014-02-10 07:23:52'),(2029,'acl_editor_pepfar_category',135,107,'2014-02-10 07:23:52'),(2030,'acl_editor_ps_funding',135,107,'2014-02-10 07:23:52'),(2031,'view_create_reports',135,107,'2014-02-10 07:23:52'),(2032,'acl_editor_ps_religions',135,107,'2014-02-10 07:23:52'),(2033,'acl_admin_facilities',135,107,'2014-02-10 07:23:52'),(2034,'acl_editor_training_category',135,107,'2014-02-10 07:23:52'),(2035,'admin_files',135,107,'2014-02-10 07:23:52'),(2036,'acl_editor_funding',135,107,'2014-02-10 07:23:52'),(2037,'edit_employee',135,107,'2014-02-10 07:23:52'),(2038,'acl_editor_people_languages',135,107,'2014-02-10 07:23:52'),(2039,'acl_editor_people_trainer',135,107,'2014-02-10 07:23:52'),(2040,'acl_editor_ps_coursetypes',135,107,'2014-02-10 07:23:53'),(2041,'acl_editor_ps_languages',135,107,'2014-02-10 07:23:53'),(2042,'acl_editor_recommended_topic',135,107,'2014-02-10 07:23:53'),(2043,'acl_editor_training_topic',135,107,'2014-02-10 07:23:53'),(2044,'acl_editor_facility_sponsors',135,107,'2014-02-10 07:23:53'),(2045,'edit_country_options',135,107,'2014-02-10 07:23:53'),(2046,'acl_editor_people_active_trainer',135,107,'2014-02-10 07:23:53'),(2047,'edit_people',135,107,'2014-02-10 07:23:53'),(2048,'acl_editor_people_suffix',135,107,'2014-02-10 07:23:53'),(2049,'import_training',135,107,'2014-02-10 07:23:53'),(2050,'acl_editor_ps_cadres',135,107,'2014-02-10 07:23:53'),(2051,'training_organizer_option_all',135,107,'2014-02-10 07:23:53'),(2052,'acl_editor_ps_institutions',135,107,'2014-02-10 07:23:53'),(2053,'acl_editor_ps_sponsors',135,107,'2014-02-10 07:23:53'),(2054,'acl_admin_people',135,107,'2014-02-10 07:23:53'),(2055,'acl_editor_training_level',135,107,'2014-02-10 07:23:53'),(2056,'approve_trainings',135,107,'2014-02-10 07:23:53'),(2057,'acl_editor_method',135,107,'2014-02-10 07:23:53'),(2058,'acl_editor_people_qualifications',135,107,'2014-02-10 07:23:53'),(2059,'import_facility',135,107,'2014-02-10 07:23:53'),(2060,'acl_editor_people_trainer_skills',135,107,'2014-02-10 07:23:53'),(2061,'acl_editor_ps_degrees',135,107,'2014-02-10 07:23:53'),(2062,'acl_editor_ps_nationalities',135,107,'2014-02-10 07:23:53'),(2063,'acl_editor_refresher_course',135,107,'2014-02-10 07:23:53'),(2064,'add_edit_users',135,107,'2014-02-10 07:23:53'),(2065,'acl_editor_facility_types',135,107,'2014-02-10 07:23:53'),(2066,'edit_course',135,107,'2014-02-10 07:23:53'),(2067,'acl_editor_people_affiliations',135,107,'2014-02-10 07:23:53'),(2068,'edit_training_location',135,107,'2014-02-10 07:23:53'),(2069,'acl_editor_people_titles',135,107,'2014-02-10 07:23:53'),(2070,'import_training_location',135,107,'2014-02-10 07:23:53'),(2071,'acl_editor_ps_classes',135,107,'2014-02-10 07:23:53'),(2072,'training_title_option_all',135,107,'2014-02-10 07:23:53'),(2073,'acl_editor_ps_joindropreasons',135,107,'2014-02-10 07:23:53'),(2074,'acl_editor_ps_tutortypes',135,107,'2014-02-10 07:23:53'),(2075,'acl_admin_training',135,107,'2014-02-10 07:23:53'),(2076,'acl_editor_training_organizer',135,107,'2014-02-10 07:23:53'),(2077,'acl_editor_nationalcurriculum',135,107,'2014-02-10 07:23:53'),(2078,'acl_editor_method',136,107,'2014-02-10 07:30:38'),(2079,'approve_trainings',136,107,'2014-02-10 07:30:38'),(2080,'acl_editor_people_qualifications',136,107,'2014-02-10 07:30:38'),(2081,'acl_editor_people_trainer_skills',136,107,'2014-02-10 07:30:38'),(2082,'import_facility',136,107,'2014-02-10 07:30:38'),(2083,'acl_editor_ps_degrees',136,107,'2014-02-10 07:30:38'),(2084,'acl_editor_ps_nationalities',136,107,'2014-02-10 07:30:38'),(2085,'acl_editor_refresher_course',136,107,'2014-02-10 07:30:38'),(2086,'acl_editor_facility_types',136,107,'2014-02-10 07:30:38'),(2087,'add_edit_users',136,107,'2014-02-10 07:30:38'),(2088,'acl_editor_people_affiliations',136,107,'2014-02-10 07:30:38'),(2089,'edit_course',136,107,'2014-02-10 07:30:38'),(2090,'acl_editor_people_titles',136,107,'2014-02-10 07:30:38'),(2091,'edit_training_location',136,107,'2014-02-10 07:30:38'),(2092,'acl_editor_ps_classes',136,107,'2014-02-10 07:30:38'),(2093,'import_training_location',136,107,'2014-02-10 07:30:38'),(2094,'acl_editor_ps_joindropreasons',136,107,'2014-02-10 07:30:38'),(2095,'training_title_option_all',136,107,'2014-02-10 07:30:38'),(2096,'acl_editor_ps_tutortypes',136,107,'2014-02-10 07:30:38'),(2097,'acl_admin_training',136,107,'2014-02-10 07:30:38'),(2098,'acl_editor_training_organizer',136,107,'2014-02-10 07:30:38'),(2099,'acl_editor_nationalcurriculum',136,107,'2014-02-10 07:30:38'),(2100,'acl_editor_people_responsibility',136,107,'2014-02-10 07:30:38'),(2101,'edit_facility',136,107,'2014-02-10 07:30:38'),(2102,'acl_editor_pepfar_category',136,107,'2014-02-10 07:30:38'),(2103,'import_person',136,107,'2014-02-10 07:30:38'),(2104,'acl_editor_ps_funding',136,107,'2014-02-10 07:30:38'),(2105,'acl_editor_ps_religions',136,107,'2014-02-10 07:30:38'),(2106,'view_create_reports',136,107,'2014-02-10 07:30:38'),(2107,'acl_admin_facilities',136,107,'2014-02-10 07:30:38'),(2108,'acl_editor_training_category',136,107,'2014-02-10 07:30:38'),(2109,'acl_editor_funding',136,107,'2014-02-10 07:30:38'),(2110,'admin_files',136,107,'2014-02-10 07:30:38'),(2111,'acl_editor_people_languages',136,107,'2014-02-10 07:30:38'),(2112,'edit_employee',136,107,'2014-02-10 07:30:38'),(2113,'acl_editor_people_trainer',136,107,'2014-02-10 07:30:38'),(2114,'acl_editor_ps_coursetypes',136,107,'2014-02-10 07:30:38'),(2115,'acl_editor_ps_languages',136,107,'2014-02-10 07:30:38'),(2116,'acl_editor_recommended_topic',136,107,'2014-02-10 07:30:38'),(2117,'acl_editor_facility_sponsors',136,107,'2014-02-10 07:30:38'),(2118,'acl_editor_training_topic',136,107,'2014-02-10 07:30:38'),(2119,'acl_editor_people_active_trainer',136,107,'2014-02-10 07:30:38'),(2120,'edit_country_options',136,107,'2014-02-10 07:30:38'),(2121,'acl_editor_people_suffix',136,107,'2014-02-10 07:30:38'),(2122,'edit_people',136,107,'2014-02-10 07:30:38'),(2123,'acl_editor_ps_cadres',136,107,'2014-02-10 07:30:38'),(2124,'import_training',136,107,'2014-02-10 07:30:38'),(2125,'acl_editor_ps_institutions',136,107,'2014-02-10 07:30:38'),(2126,'training_organizer_option_all',136,107,'2014-02-10 07:30:38'),(2127,'acl_editor_ps_sponsors',136,107,'2014-02-10 07:30:38'),(2128,'acl_admin_people',136,107,'2014-02-10 07:30:38'),(2129,'acl_editor_training_level',136,107,'2014-02-10 07:30:38'),(2130,'acl_editor_facility_sponsors',137,136,'2014-02-10 08:33:44'),(2131,'edit_people',137,136,'2014-02-10 08:33:44'),(2132,'edit_course',137,136,'2014-02-10 08:33:44'),(2133,'edit_training_location',137,136,'2014-02-10 08:33:44'),(2134,'edit_facility',137,136,'2014-02-10 08:33:44'),(2135,'view_create_reports',137,136,'2014-02-10 08:33:44'),(2136,'admin_files',137,136,'2014-02-10 08:33:44'),(2137,'edit_employee',137,136,'2014-02-10 08:33:44'),(2138,'acl_editor_facility_sponsors',125,107,'2014-02-10 09:40:26'),(2139,'edit_course',138,135,'2014-02-10 09:42:00'),(2140,'edit_training_location',138,135,'2014-02-10 09:42:00'),(2236,'edit_training_location',148,107,'2014-02-11 09:28:41'),(2142,'edit_facility',138,135,'2014-02-10 09:42:00'),(2143,'view_create_reports',138,135,'2014-02-10 09:42:00'),(2144,'admin_files',138,135,'2014-02-10 09:42:00'),(2145,'edit_employee',138,135,'2014-02-10 09:42:00'),(2237,'edit_facility',148,107,'2014-02-11 09:28:41'),(2147,'acl_editor_facility_sponsors',138,135,'2014-02-10 09:42:00'),(2148,'edit_people',138,135,'2014-02-10 09:42:00'),(2149,'edit_facility',139,135,'2014-02-10 09:43:59'),(2150,'view_create_reports',139,135,'2014-02-10 09:43:59'),(2151,'admin_files',139,135,'2014-02-10 09:43:59'),(2152,'edit_employee',139,135,'2014-02-10 09:43:59'),(2217,'edit_course',146,136,'2014-02-10 13:13:30'),(2154,'acl_editor_facility_sponsors',139,135,'2014-02-10 09:43:59'),(2155,'edit_people',139,135,'2014-02-10 09:43:59'),(2156,'edit_course',139,135,'2014-02-10 09:43:59'),(2157,'edit_training_location',139,135,'2014-02-10 09:43:59'),(2218,'edit_training_location',146,136,'2014-02-10 13:13:30'),(2159,'edit_course',140,135,'2014-02-10 09:45:33'),(2160,'edit_training_location',140,135,'2014-02-10 09:45:33'),(2235,'edit_course',148,107,'2014-02-11 09:28:41'),(2162,'edit_facility',140,135,'2014-02-10 09:45:33'),(2163,'view_create_reports',140,135,'2014-02-10 09:45:33'),(2164,'admin_files',140,135,'2014-02-10 09:45:33'),(2165,'edit_employee',140,135,'2014-02-10 09:45:33'),(2234,'edit_people',148,107,'2014-02-11 09:28:41'),(2167,'acl_editor_facility_sponsors',140,135,'2014-02-10 09:45:33'),(2168,'edit_people',140,135,'2014-02-10 09:45:33'),(2231,'acl_editor_facility_sponsors',147,7,'2014-02-10 18:36:47'),(2170,'acl_editor_facility_sponsors',141,135,'2014-02-10 09:47:20'),(2171,'edit_people',141,135,'2014-02-10 09:47:20'),(2172,'edit_course',141,135,'2014-02-10 09:47:20'),(2173,'edit_training_location',141,135,'2014-02-10 09:47:20'),(2232,'edit_people',147,7,'2014-02-10 18:36:47'),(2175,'edit_facility',141,135,'2014-02-10 09:47:20'),(2233,'acl_editor_facility_sponsors',148,107,'2014-02-11 09:28:41'),(2177,'view_create_reports',141,135,'2014-02-10 09:47:20'),(2178,'admin_files',141,135,'2014-02-10 09:47:20'),(2179,'edit_employee',141,135,'2014-02-10 09:47:20'),(2180,'edit_employee',142,135,'2014-02-10 09:48:44'),(2228,'view_create_reports',147,7,'2014-02-10 18:36:47'),(2182,'edit_people',142,135,'2014-02-10 09:48:44'),(2183,'edit_course',142,135,'2014-02-10 09:48:44'),(2184,'edit_training_location',142,135,'2014-02-10 09:48:44'),(2229,'admin_files',147,7,'2014-02-10 18:36:47'),(2186,'edit_facility',142,135,'2014-02-10 09:48:44'),(2230,'edit_employee',147,7,'2014-02-10 18:36:47'),(2188,'view_create_reports',142,135,'2014-02-10 09:48:44'),(2189,'admin_files',142,135,'2014-02-10 09:48:44'),(2190,'edit_employee',143,135,'2014-02-10 09:50:24'),(2225,'edit_course',147,7,'2014-02-10 18:36:47'),(2192,'acl_editor_facility_sponsors',143,135,'2014-02-10 09:50:24'),(2193,'edit_people',143,135,'2014-02-10 09:50:24'),(2194,'edit_course',143,135,'2014-02-10 09:50:24'),(2195,'edit_training_location',143,135,'2014-02-10 09:50:24'),(2226,'edit_training_location',147,7,'2014-02-10 18:36:47'),(2197,'edit_facility',143,135,'2014-02-10 09:50:24'),(2227,'edit_facility',147,7,'2014-02-10 18:36:47'),(2199,'view_create_reports',143,135,'2014-02-10 09:50:24'),(2200,'admin_files',143,135,'2014-02-10 09:50:24'),(2201,'edit_training_location',144,136,'2014-02-10 10:05:38'),(2202,'edit_facility',144,136,'2014-02-10 10:05:38'),(2203,'view_create_reports',144,136,'2014-02-10 10:05:38'),(2204,'admin_files',144,136,'2014-02-10 10:05:38'),(2205,'edit_employee',144,136,'2014-02-10 10:05:38'),(2206,'acl_editor_facility_sponsors',144,136,'2014-02-10 10:05:38'),(2207,'edit_people',144,136,'2014-02-10 10:05:38'),(2208,'edit_course',144,136,'2014-02-10 10:05:38'),(2209,'edit_course',145,107,'2014-02-10 11:27:43'),(2210,'edit_training_location',145,107,'2014-02-10 11:27:43'),(2211,'edit_facility',145,107,'2014-02-10 11:27:43'),(2212,'view_create_reports',145,107,'2014-02-10 11:27:43'),(2213,'admin_files',145,107,'2014-02-10 11:27:43'),(2214,'edit_employee',145,107,'2014-02-10 11:27:43'),(2215,'acl_editor_facility_sponsors',145,107,'2014-02-10 11:27:43'),(2216,'edit_people',145,107,'2014-02-10 11:27:43'),(2219,'edit_facility',146,136,'2014-02-10 13:13:30'),(2220,'view_create_reports',146,136,'2014-02-10 13:13:30'),(2221,'admin_files',146,136,'2014-02-10 13:13:30'),(2222,'edit_employee',146,136,'2014-02-10 13:13:30'),(2223,'acl_editor_facility_sponsors',146,136,'2014-02-10 13:13:30'),(2224,'edit_people',146,136,'2014-02-10 13:13:30'),(2238,'view_create_reports',148,107,'2014-02-11 09:28:41'),(2239,'admin_files',148,107,'2014-02-11 09:28:41'),(2240,'edit_employee',148,107,'2014-02-11 09:28:41'),(2241,'admin_files',149,107,'2014-02-11 09:33:49'),(2242,'edit_employee',149,107,'2014-02-11 09:33:49'),(2243,'acl_editor_facility_sponsors',149,107,'2014-02-11 09:33:49'),(2244,'edit_people',149,107,'2014-02-11 09:33:49'),(2245,'edit_course',149,107,'2014-02-11 09:33:49'),(2246,'edit_training_location',149,107,'2014-02-11 09:33:49'),(2247,'edit_facility',149,107,'2014-02-11 09:33:49'),(2248,'view_create_reports',149,107,'2014-02-11 09:33:49'),(2249,'edit_employee',150,107,'2014-02-11 09:56:11'),(2250,'acl_editor_facility_sponsors',150,107,'2014-02-11 09:56:11'),(2251,'edit_people',150,107,'2014-02-11 09:56:11'),(2252,'edit_course',150,107,'2014-02-11 09:56:11'),(2253,'edit_training_location',150,107,'2014-02-11 09:56:11'),(2254,'edit_facility',150,107,'2014-02-11 09:56:11'),(2255,'view_create_reports',150,107,'2014-02-11 09:56:11'),(2256,'admin_files',150,107,'2014-02-11 09:56:11'),(2257,'edit_course',151,107,'2014-02-11 10:37:58'),(2258,'edit_training_location',151,107,'2014-02-11 10:37:58'),(2259,'duplicate_training',151,107,'2014-02-11 10:37:58'),(2260,'edit_facility',151,107,'2014-02-11 10:37:58'),(2261,'view_create_reports',151,107,'2014-02-11 10:37:58'),(2262,'admin_files',151,107,'2014-02-11 10:37:58'),(2263,'edit_employee',151,107,'2014-02-11 10:37:58'),(2264,'use_offline_app',151,107,'2014-02-11 10:37:58'),(2265,'acl_editor_facility_sponsors',151,107,'2014-02-11 10:37:58'),(2266,'edit_people',151,107,'2014-02-11 10:37:58'),(2267,'edit_course',152,135,'2014-02-11 10:50:39'),(2268,'edit_training_location',152,135,'2014-02-11 10:50:39'),(2269,'edit_facility',152,135,'2014-02-11 10:50:39'),(2270,'view_create_reports',152,135,'2014-02-11 10:50:39'),(2271,'admin_files',152,135,'2014-02-11 10:50:39'),(2272,'edit_employee',152,135,'2014-02-11 10:50:39'),(2273,'acl_editor_facility_sponsors',152,135,'2014-02-11 10:50:39'),(2274,'edit_people',152,135,'2014-02-11 10:50:39'),(2275,'edit_facility',153,135,'2014-02-11 10:53:05'),(2276,'view_create_reports',153,135,'2014-02-11 10:53:05'),(2277,'admin_files',153,135,'2014-02-11 10:53:05'),(2278,'edit_employee',153,135,'2014-02-11 10:53:05'),(2279,'acl_editor_facility_sponsors',153,135,'2014-02-11 10:53:05'),(2280,'edit_people',153,135,'2014-02-11 10:53:05'),(2281,'edit_course',153,135,'2014-02-11 10:53:05'),(2282,'edit_training_location',153,135,'2014-02-11 10:53:05'),(2283,'admin_files',154,107,'2014-02-11 11:07:25'),(2284,'edit_employee',154,107,'2014-02-11 11:07:25'),(2285,'acl_editor_facility_sponsors',154,107,'2014-02-11 11:07:25'),(2286,'edit_people',154,107,'2014-02-11 11:07:25'),(2287,'edit_course',154,107,'2014-02-11 11:07:25'),(2288,'edit_training_location',154,107,'2014-02-11 11:07:25'),(2289,'edit_facility',154,107,'2014-02-11 11:07:25'),(2290,'view_create_reports',154,107,'2014-02-11 11:07:25'),(2294,'edit_employee',155,136,'2014-02-11 13:13:37'),(2295,'acl_editor_facility_sponsors',155,136,'2014-02-11 13:13:37'),(2296,'edit_people',155,136,'2014-02-11 13:13:37'),(2297,'edit_course',155,136,'2014-02-11 13:13:37'),(2298,'edit_training_location',155,136,'2014-02-11 13:13:37'),(2299,'acl_editor_facility_sponsors',156,135,'2014-02-11 13:51:50'),(2300,'edit_people',156,135,'2014-02-11 13:51:50'),(2301,'edit_course',156,135,'2014-02-11 13:51:50'),(2302,'edit_training_location',156,135,'2014-02-11 13:51:50'),(2303,'edit_facility',156,135,'2014-02-11 13:51:50'),(2304,'view_create_reports',156,135,'2014-02-11 13:51:50'),(2305,'admin_files',156,135,'2014-02-11 13:51:50'),(2306,'edit_employee',156,135,'2014-02-11 13:51:50'),(2307,'edit_course',157,135,'2014-02-11 13:54:04'),(2308,'edit_training_location',157,135,'2014-02-11 13:54:04'),(2309,'edit_facility',157,135,'2014-02-11 13:54:04'),(2310,'view_create_reports',157,135,'2014-02-11 13:54:04'),(2311,'admin_files',157,135,'2014-02-11 13:54:04'),(2312,'edit_employee',157,135,'2014-02-11 13:54:04'),(2313,'acl_editor_facility_sponsors',157,135,'2014-02-11 13:54:04'),(2314,'edit_people',157,135,'2014-02-11 13:54:04'),(2315,'edit_employee',115,135,'2014-02-12 08:59:40'),(2316,'edit_facility',158,136,'2014-02-12 10:14:31'),(2317,'view_create_reports',158,136,'2014-02-12 10:14:31'),(2318,'admin_files',158,136,'2014-02-12 10:14:31'),(2319,'edit_employee',158,136,'2014-02-12 10:14:31'),(2320,'acl_editor_facility_sponsors',158,136,'2014-02-12 10:14:31'),(2321,'edit_people',158,136,'2014-02-12 10:14:31'),(2322,'edit_course',158,136,'2014-02-12 10:14:31'),(2323,'edit_training_location',158,136,'2014-02-12 10:14:31'),(2324,'edit_course',159,135,'2014-02-12 10:21:22'),(2333,'edit_course',160,135,'2014-02-13 14:02:18'),(2326,'edit_facility',159,135,'2014-02-12 10:21:22'),(2327,'view_create_reports',159,135,'2014-02-12 10:21:22'),(2332,'edit_employee',118,135,'2014-02-12 12:29:30'),(2329,'edit_employee',159,135,'2014-02-12 10:21:22'),(2330,'acl_editor_facility_sponsors',159,135,'2014-02-12 10:21:22'),(2331,'edit_people',159,135,'2014-02-12 10:21:22'),(2334,'edit_training_location',160,135,'2014-02-13 14:02:18'),(2335,'edit_facility',160,135,'2014-02-13 14:02:18'),(2336,'view_create_reports',160,135,'2014-02-13 14:02:18'),(2337,'admin_files',160,135,'2014-02-13 14:02:19'),(2338,'acl_editor_facility_sponsors',160,135,'2014-02-13 14:02:19'),(2339,'edit_people',160,135,'2014-02-13 14:02:19'),(2340,'edit_facility',161,107,'2014-02-18 05:11:21'),(2341,'view_create_reports',161,107,'2014-02-18 05:11:21'),(2342,'admin_files',161,107,'2014-02-18 05:11:21'),(2343,'edit_employee',161,107,'2014-02-18 05:11:21'),(2344,'use_offline_app',161,107,'2014-02-18 05:11:21'),(2345,'acl_editor_facility_sponsors',161,107,'2014-02-18 05:11:21'),(2346,'edit_people',161,107,'2014-02-18 05:11:21'),(2347,'edit_course',161,107,'2014-02-18 05:11:21'),(2348,'edit_training_location',161,107,'2014-02-18 05:11:21'),(2349,'duplicate_training',161,107,'2014-02-18 05:11:21'),(2350,'edit_course',162,107,'2014-02-20 06:58:52'),(2351,'edit_training_location',162,107,'2014-02-20 06:58:52'),(2352,'duplicate_training',162,107,'2014-02-20 06:58:52'),(2353,'edit_facility',162,107,'2014-02-20 06:58:52'),(2354,'view_create_reports',162,107,'2014-02-20 06:58:52'),(2355,'admin_files',162,107,'2014-02-20 06:58:52'),(2379,'edit_course',165,107,'2014-02-21 09:23:23'),(2357,'use_offline_app',162,107,'2014-02-20 06:58:52'),(2358,'acl_editor_facility_sponsors',162,107,'2014-02-20 06:58:52'),(2359,'edit_people',162,107,'2014-02-20 06:58:52'),(2360,'edit_course',163,107,'2014-02-20 07:01:48'),(2361,'edit_training_location',163,107,'2014-02-20 07:01:48'),(2362,'duplicate_training',163,107,'2014-02-20 07:01:48'),(2363,'edit_facility',163,107,'2014-02-20 07:01:48'),(2364,'view_create_reports',163,107,'2014-02-20 07:01:48'),(2365,'admin_files',163,107,'2014-02-20 07:01:48'),(2366,'edit_employee',163,107,'2014-02-20 07:01:48'),(2367,'use_offline_app',163,107,'2014-02-20 07:01:48'),(2368,'acl_editor_facility_sponsors',163,107,'2014-02-20 07:01:48'),(2369,'edit_people',163,107,'2014-02-20 07:01:48'),(2370,'edit_course',164,107,'2014-02-20 07:04:13'),(2371,'edit_training_location',164,107,'2014-02-20 07:04:13'),(2372,'edit_facility',164,107,'2014-02-20 07:04:13'),(2373,'view_create_reports',164,107,'2014-02-20 07:04:13'),(2374,'admin_files',164,107,'2014-02-20 07:04:13'),(2375,'edit_employee',164,107,'2014-02-20 07:04:13'),(2376,'acl_editor_facility_sponsors',164,107,'2014-02-20 07:04:13'),(2377,'edit_people',164,107,'2014-02-20 07:04:13'),(2378,'edit_employee',162,107,'2014-02-20 07:06:31'),(2380,'edit_training_location',165,107,'2014-02-21 09:23:23'),(2381,'duplicate_training',165,107,'2014-02-21 09:23:23'),(2382,'edit_facility',165,107,'2014-02-21 09:23:23'),(2383,'view_create_reports',165,107,'2014-02-21 09:23:23'),(2384,'admin_files',165,107,'2014-02-21 09:23:23'),(2385,'edit_employee',165,107,'2014-02-21 09:23:23'),(2386,'use_offline_app',165,107,'2014-02-21 09:23:23'),(2387,'acl_editor_facility_sponsors',165,107,'2014-02-21 09:23:23'),(2388,'edit_people',165,107,'2014-02-21 09:23:23'),(2398,'acl_editor_facility_sponsors',48,7,'2014-02-25 08:02:35'),(2390,'acl_editor_facility_sponsors',166,7,'2014-02-24 13:42:13'),(2391,'edit_people',166,7,'2014-02-24 13:42:13'),(2392,'edit_course',166,7,'2014-02-24 13:42:13'),(2393,'edit_training_location',166,7,'2014-02-24 13:42:13'),(2394,'edit_facility',166,7,'2014-02-24 13:42:13'),(2395,'view_create_reports',166,7,'2014-02-24 13:42:13'),(2396,'admin_files',166,7,'2014-02-24 13:42:13'),(2397,'edit_employee',166,7,'2014-02-24 13:45:10'),(2399,'edit_course',167,7,'2014-02-25 17:34:37'),(2400,'edit_training_location',167,7,'2014-02-25 17:34:37'),(2401,'edit_facility',167,7,'2014-02-25 17:34:37'),(2402,'view_create_reports',167,7,'2014-02-25 17:34:37'),(2403,'admin_files',167,7,'2014-02-25 17:34:37'),(2404,'edit_employee',167,7,'2014-02-25 17:34:37'),(2405,'acl_editor_facility_sponsors',167,7,'2014-02-25 17:34:37'),(2406,'edit_people',167,7,'2014-02-25 17:34:37'),(2407,'edit_course',168,7,'2014-02-25 17:35:51'),(2408,'edit_training_location',168,7,'2014-02-25 17:35:51'),(2409,'edit_facility',168,7,'2014-02-25 17:35:51'),(2410,'view_create_reports',168,7,'2014-02-25 17:35:51'),(2411,'admin_files',168,7,'2014-02-25 17:35:51'),(2412,'edit_employee',168,7,'2014-02-25 17:35:51'),(2413,'acl_editor_facility_sponsors',168,7,'2014-02-25 17:35:51'),(2414,'edit_people',168,7,'2014-02-25 17:35:51'),(2415,'edit_course',169,7,'2014-02-25 17:37:07'),(2416,'edit_training_location',169,7,'2014-02-25 17:37:07'),(2417,'edit_facility',169,7,'2014-02-25 17:37:07'),(2418,'view_create_reports',169,7,'2014-02-25 17:37:07'),(2419,'admin_files',169,7,'2014-02-25 17:37:07'),(2420,'edit_employee',169,7,'2014-02-25 17:37:07'),(2421,'acl_editor_facility_sponsors',169,7,'2014-02-25 17:37:07'),(2422,'edit_people',169,7,'2014-02-25 17:37:07'),(2423,'edit_course',170,107,'2014-02-27 10:47:55'),(2424,'edit_training_location',170,107,'2014-02-27 10:47:55'),(2425,'duplicate_training',170,107,'2014-02-27 10:47:55'),(2426,'edit_facility',170,107,'2014-02-27 10:47:55'),(2427,'view_create_reports',170,107,'2014-02-27 10:47:55'),(2428,'admin_files',170,107,'2014-02-27 10:47:55'),(2429,'edit_employee',170,107,'2014-02-27 10:47:55'),(2430,'use_offline_app',170,107,'2014-02-27 10:47:55'),(2431,'acl_editor_facility_sponsors',170,107,'2014-02-27 10:47:55'),(2432,'edit_people',170,107,'2014-02-27 10:47:55'),(2433,'duplicate_training',171,107,'2014-03-10 06:23:00'),(2434,'edit_facility',171,107,'2014-03-10 06:23:00'),(2435,'view_create_reports',171,107,'2014-03-10 06:23:00'),(2436,'admin_files',171,107,'2014-03-10 06:23:00'),(2437,'edit_employee',171,107,'2014-03-10 06:23:00'),(2438,'in_service',171,107,'2014-03-10 06:23:00'),(2439,'use_offline_app',171,107,'2014-03-10 06:23:00'),(2440,'edit_people',171,107,'2014-03-10 06:23:00'),(2441,'edit_course',171,107,'2014-03-10 06:23:00'),(2442,'edit_training_location',171,107,'2014-03-10 06:23:00'),(2444,'use_offline_app',172,107,'2014-04-15 14:21:07'),(2445,'edit_people',172,107,'2014-04-15 14:21:07'),(2446,'edit_course',172,107,'2014-04-15 14:21:07'),(2447,'edit_training_location',172,107,'2014-04-15 14:21:07'),(2448,'duplicate_training',172,107,'2014-04-15 14:21:07'),(2449,'edit_facility',172,107,'2014-04-15 14:21:07'),(2450,'view_create_reports',172,107,'2014-04-15 14:21:07'),(2451,'admin_files',172,107,'2014-04-15 14:21:07'),(2452,'edit_employee',172,107,'2014-04-16 05:55:51'),(2453,'edit_facility',173,7,'2014-05-14 12:34:32'),(2454,'view_create_reports',173,7,'2014-05-14 12:34:32'),(2456,'admin_files',173,7,'2014-05-14 12:34:32'),(2459,'edit_people',173,7,'2014-05-14 12:34:32'),(3184,'view_mechanisms',173,1,'2014-11-13 10:21:21'),(2463,'edit_course',173,7,'2014-05-14 12:34:32'),(2464,'edit_training_location',173,7,'2014-05-14 12:34:32'),(2477,'edit_course',174,107,'2014-06-27 08:34:06'),(2936,'edit_people',202,1,'2014-08-27 08:03:57'),(2479,'edit_training_location',174,107,'2014-06-27 08:34:06'),(2935,'use_offline_app',202,1,'2014-08-27 08:03:57'),(2902,'edit_employee',198,1,'2014-08-18 13:27:07'),(2912,'admin_files',199,1,'2014-08-20 11:39:56'),(2944,'acl_editor_facility_sponsors',203,7,'2014-08-28 12:56:55'),(2910,'edit_facility',199,1,'2014-08-20 11:39:56'),(2906,'edit_training_location',198,1,'2014-08-18 13:27:07'),(2939,'duplicate_training',202,1,'2014-08-27 08:03:57'),(2934,'edit_employee',202,1,'2014-08-27 08:03:57'),(2899,'edit_facility',198,1,'2014-08-18 13:27:07'),(2940,'edit_facility',202,1,'2014-08-27 08:03:57'),(2490,'edit_facility',174,107,'2014-06-27 08:34:06'),(2930,'training_organizer_option_all',201,1,'2014-08-22 08:31:48'),(2900,'view_create_reports',198,1,'2014-08-18 13:27:07'),(2916,'edit_people',200,1,'2014-08-22 08:30:11'),(2908,'edit_training_location',199,1,'2014-08-20 11:39:56'),(2495,'view_create_reports',174,107,'2014-06-27 08:34:06'),(2904,'edit_people',198,1,'2014-08-18 13:27:07'),(2942,'admin_files',202,1,'2014-08-27 08:03:57'),(2928,'edit_employee',201,1,'2014-08-22 08:31:48'),(2499,'admin_files',174,107,'2014-06-27 08:34:06'),(2929,'edit_people',201,1,'2014-08-22 08:31:48'),(2501,'edit_employee',174,107,'2014-06-27 08:34:06'),(2938,'edit_training_location',202,1,'2014-08-27 08:03:57'),(2909,'duplicate_training',199,1,'2014-08-20 11:39:56'),(2739,'edit_course',179,1,'2014-07-22 07:47:52'),(2914,'use_offline_app',199,1,'2014-08-20 11:39:56'),(2898,'duplicate_training',198,1,'2014-08-18 13:27:07'),(2926,'view_create_reports',201,1,'2014-08-22 08:31:48'),(2919,'edit_training_location',200,1,'2014-08-22 08:30:11'),(2937,'edit_course',202,1,'2014-08-27 08:03:57'),(2922,'view_create_reports',200,1,'2014-08-22 08:30:11'),(2943,'edit_employee',203,7,'2014-08-28 12:56:55'),(2924,'edit_employee',200,1,'2014-08-22 08:30:11'),(2513,'edit_people',174,107,'2014-06-27 08:34:06'),(2918,'edit_course',200,1,'2014-08-22 08:30:11'),(2903,'use_offline_app',198,1,'2014-08-18 13:27:07'),(2915,'edit_people',199,1,'2014-08-20 11:39:56'),(2911,'view_create_reports',199,1,'2014-08-20 11:39:56'),(2905,'edit_course',198,1,'2014-08-18 13:27:07'),(2933,'edit_facility',201,1,'2014-08-22 08:31:48'),(2923,'admin_files',200,1,'2014-08-22 08:30:11'),(2941,'view_create_reports',202,1,'2014-08-27 08:03:57'),(2931,'edit_course',201,1,'2014-08-22 08:31:48'),(2901,'admin_files',198,1,'2014-08-18 13:27:07'),(2917,'training_organizer_option_all',200,1,'2014-08-22 08:30:11'),(2525,'acl_admin_facilities',175,107,'2014-07-01 08:38:50'),(2526,'acl_editor_training_category',175,107,'2014-07-01 08:38:50'),(2527,'acl_editor_funding',175,107,'2014-07-01 08:38:50'),(2528,'admin_files',175,107,'2014-07-01 08:38:50'),(2529,'acl_editor_people_languages',175,107,'2014-07-01 08:38:50'),(2530,'edit_employee',175,107,'2014-07-01 08:38:50'),(2531,'acl_editor_people_trainer',175,107,'2014-07-01 08:38:50'),(2585,'acl_editor_training_topic',110,175,'2014-07-01 08:50:29'),(2582,'acl_editor_ps_languages',110,175,'2014-07-01 08:50:29'),(2590,'import_training',110,175,'2014-07-01 08:50:29'),(2581,'acl_editor_ps_coursetypes',110,175,'2014-07-01 08:50:29'),(2536,'acl_editor_recommended_topic',175,107,'2014-07-01 08:38:50'),(2595,'acl_editor_method',110,175,'2014-07-01 08:50:29'),(2538,'acl_editor_training_topic',175,107,'2014-07-01 08:38:50'),(2539,'acl_editor_people_active_trainer',175,107,'2014-07-01 08:38:50'),(2540,'edit_country_options',175,107,'2014-07-01 08:38:50'),(2541,'acl_editor_people_suffix',175,107,'2014-07-01 08:38:50'),(2542,'edit_people',175,107,'2014-07-01 08:38:50'),(2594,'acl_editor_training_level',110,175,'2014-07-01 08:50:29'),(2544,'import_training',175,107,'2014-07-01 08:38:50'),(2591,'acl_editor_ps_institutions',110,175,'2014-07-01 08:50:29'),(2587,'edit_country_options',110,175,'2014-07-01 08:50:29'),(2547,'acl_admin_people',175,107,'2014-07-01 08:38:50'),(2548,'acl_editor_training_level',175,107,'2014-07-01 08:38:50'),(2549,'acl_editor_method',175,107,'2014-07-01 08:38:50'),(2550,'approve_trainings',175,107,'2014-07-01 08:38:50'),(2551,'acl_editor_people_qualifications',175,107,'2014-07-01 08:38:50'),(2552,'edit_evaluations',175,107,'2014-07-01 08:38:50'),(2553,'acl_editor_people_trainer_skills',175,107,'2014-07-01 08:38:50'),(2583,'acl_editor_recommended_topic',110,175,'2014-07-01 08:50:29'),(2593,'acl_admin_people',110,175,'2014-07-01 08:50:29'),(2589,'acl_editor_ps_cadres',110,175,'2014-07-01 08:50:29'),(2557,'acl_editor_refresher_course',175,107,'2014-07-01 08:38:50'),(2597,'acl_editor_people_trainer_skills',110,175,'2014-07-01 08:50:29'),(2559,'add_edit_users',175,107,'2014-07-01 08:38:50'),(2560,'acl_editor_people_affiliations',175,107,'2014-07-01 08:38:50'),(2561,'edit_course',175,107,'2014-07-01 08:38:50'),(2562,'acl_editor_people_titles',175,107,'2014-07-01 08:38:50'),(2563,'edit_training_location',175,107,'2014-07-01 08:38:50'),(2596,'acl_editor_people_qualifications',110,175,'2014-07-01 08:50:29'),(2584,'acl_editor_facility_sponsors',110,175,'2014-07-01 08:50:29'),(2588,'acl_editor_people_suffix',110,175,'2014-07-01 08:50:29'),(2567,'training_title_option_all',175,107,'2014-07-01 08:38:50'),(2586,'acl_editor_people_active_trainer',110,175,'2014-07-01 08:50:29'),(2569,'acl_admin_training',175,107,'2014-07-01 08:38:50'),(2570,'acl_editor_training_organizer',175,107,'2014-07-01 08:38:50'),(2571,'acl_editor_nationalcurriculum',175,107,'2014-07-01 08:38:50'),(2572,'duplicate_training',175,107,'2014-07-01 08:38:50'),(2573,'acl_editor_people_responsibility',175,107,'2014-07-01 08:38:50'),(2574,'edit_facility',175,107,'2014-07-01 08:38:50'),(2575,'acl_editor_pepfar_category',175,107,'2014-07-01 08:38:50'),(2576,'import_person',175,107,'2014-07-01 08:38:50'),(2592,'acl_editor_ps_sponsors',110,175,'2014-07-01 08:50:29'),(2578,'acl_editor_ps_religions',175,107,'2014-07-01 08:38:50'),(2579,'view_create_reports',175,107,'2014-07-01 08:38:50'),(2598,'import_facility',110,175,'2014-07-01 08:50:29'),(2599,'acl_editor_ps_degrees',110,175,'2014-07-01 08:50:29'),(2600,'acl_editor_ps_nationalities',110,175,'2014-07-01 08:50:29'),(2601,'acl_editor_refresher_course',110,175,'2014-07-01 08:50:29'),(2602,'acl_editor_facility_types',110,175,'2014-07-01 08:50:29'),(2603,'add_edit_users',110,175,'2014-07-01 08:50:29'),(2604,'acl_editor_people_affiliations',110,175,'2014-07-01 08:50:29'),(2605,'acl_editor_people_titles',110,175,'2014-07-01 08:50:29'),(2606,'acl_editor_ps_classes',110,175,'2014-07-01 08:50:29'),(2607,'import_training_location',110,175,'2014-07-01 08:50:29'),(2608,'acl_editor_ps_joindropreasons',110,175,'2014-07-01 08:50:29'),(2609,'training_title_option_all',110,175,'2014-07-01 08:50:29'),(2610,'acl_editor_ps_tutortypes',110,175,'2014-07-01 08:50:29'),(2611,'acl_admin_training',110,175,'2014-07-01 08:50:29'),(2612,'acl_editor_training_organizer',110,175,'2014-07-01 08:50:29'),(2613,'acl_editor_nationalcurriculum',110,175,'2014-07-01 08:50:29'),(2614,'acl_editor_people_responsibility',110,175,'2014-07-01 08:50:29'),(2615,'acl_editor_pepfar_category',110,175,'2014-07-01 08:50:29'),(2616,'import_person',110,175,'2014-07-01 08:50:29'),(2617,'acl_editor_ps_funding',110,175,'2014-07-01 08:50:29'),(2618,'acl_editor_ps_religions',110,175,'2014-07-01 08:50:29'),(2619,'acl_admin_facilities',110,175,'2014-07-01 08:50:29'),(2620,'acl_editor_training_category',110,175,'2014-07-01 08:50:29'),(2621,'acl_editor_funding',110,175,'2014-07-01 08:50:29'),(2622,'acl_editor_people_languages',110,175,'2014-07-01 08:50:29'),(2623,'acl_editor_people_trainer',110,175,'2014-07-01 08:50:29'),(3185,'view_employee',173,1,'2014-11-13 10:21:21'),(3183,'view_partners',173,1,'2014-11-13 10:21:21'),(2711,'edit_course',176,173,'2014-07-04 09:08:53'),(2712,'edit_training_location',176,173,'2014-07-04 09:08:53'),(2713,'duplicate_training',176,173,'2014-07-04 09:08:53'),(2714,'edit_facility',176,173,'2014-07-04 09:08:53'),(2715,'view_create_reports',176,173,'2014-07-04 09:08:53'),(2716,'admin_files',176,173,'2014-07-04 09:08:53'),(2721,'edit_course',177,107,'2014-07-21 09:17:52'),(2718,'use_offline_app',176,173,'2014-07-04 09:08:53'),(2719,'edit_people',176,173,'2014-07-04 09:08:53'),(2720,'edit_employee',176,7,'2014-07-04 09:20:44'),(2722,'edit_training_location',177,107,'2014-07-21 09:17:52'),(2723,'duplicate_training',177,107,'2014-07-21 09:17:52'),(2724,'edit_facility',177,107,'2014-07-21 09:17:52'),(2725,'view_create_reports',177,107,'2014-07-21 09:17:52'),(2726,'admin_files',177,107,'2014-07-21 09:17:52'),(2727,'edit_employee',177,107,'2014-07-21 09:17:53'),(2728,'use_offline_app',177,107,'2014-07-21 09:17:53'),(2729,'edit_people',177,107,'2014-07-21 09:17:53'),(2731,'edit_people',178,107,'2014-07-22 06:47:06'),(2732,'edit_course',178,107,'2014-07-22 06:47:06'),(2733,'edit_training_location',178,107,'2014-07-22 06:47:06'),(2734,'duplicate_training',178,107,'2014-07-22 06:47:06'),(2735,'edit_facility',178,107,'2014-07-22 06:47:06'),(2736,'view_create_reports',178,107,'2014-07-22 06:47:06'),(2737,'admin_files',178,107,'2014-07-22 06:47:06'),(2738,'edit_employee',178,107,'2014-07-22 06:47:06'),(2740,'edit_training_location',179,1,'2014-07-22 07:47:52'),(2741,'edit_facility',179,1,'2014-07-22 07:47:52'),(2742,'view_create_reports',179,1,'2014-07-22 07:47:52'),(2743,'admin_files',179,1,'2014-07-22 07:47:52'),(2744,'edit_employee',179,1,'2014-07-22 07:47:52'),(2745,'edit_people',179,1,'2014-07-22 07:47:52'),(2746,'admin_files',180,1,'2014-07-22 16:56:45'),(2747,'edit_people',180,1,'2014-07-22 16:56:45'),(2748,'view_facility',180,1,'2014-07-22 16:56:45'),(2749,'edit_course',180,1,'2014-07-22 16:56:45'),(2754,'edit_employee',90,1,'2014-08-05 12:47:03'),(2751,'view_create_reports',180,1,'2014-07-22 16:56:45'),(2752,'edit_employee',180,1,'2014-07-22 17:40:30'),(2756,'edit_training_location',181,1,'2014-08-06 06:02:13'),(2757,'edit_facility',181,1,'2014-08-06 06:02:13'),(2758,'view_create_reports',181,1,'2014-08-06 06:02:13'),(2759,'admin_files',181,1,'2014-08-06 06:02:13'),(2760,'edit_employee',181,1,'2014-08-06 06:02:13'),(2761,'edit_people',181,1,'2014-08-06 06:02:13'),(2762,'edit_people',182,1,'2014-08-06 07:08:12'),(2763,'edit_course',182,1,'2014-08-06 07:08:12'),(2764,'edit_training_location',182,1,'2014-08-06 07:08:13'),(2765,'duplicate_training',182,1,'2014-08-06 07:08:13'),(2766,'edit_facility',182,1,'2014-08-06 07:08:13'),(2767,'view_create_reports',182,1,'2014-08-06 07:08:13'),(2768,'admin_files',182,1,'2014-08-06 07:08:13'),(2769,'edit_employee',182,1,'2014-08-06 07:08:13'),(2770,'use_offline_app',182,1,'2014-08-06 07:08:13'),(2771,'edit_course',183,1,'2014-08-06 07:10:42'),(2772,'edit_training_location',183,1,'2014-08-06 07:10:42'),(2818,'edit_course',188,7,'2014-08-07 09:57:55'),(2774,'edit_facility',183,1,'2014-08-06 07:10:42'),(2775,'view_create_reports',183,1,'2014-08-06 07:10:42'),(2776,'admin_files',183,1,'2014-08-06 07:10:42'),(2777,'edit_employee',183,1,'2014-08-06 07:10:42'),(2817,'edit_people',188,7,'2014-08-07 09:57:55'),(2779,'edit_people',183,1,'2014-08-06 07:10:42'),(2780,'view_create_reports',184,1,'2014-08-06 07:14:11'),(2781,'admin_files',184,1,'2014-08-06 07:14:11'),(2782,'edit_employee',184,1,'2014-08-06 07:14:11'),(2783,'use_offline_app',184,1,'2014-08-06 07:14:11'),(2784,'edit_people',184,1,'2014-08-06 07:14:11'),(2785,'edit_course',184,1,'2014-08-06 07:14:11'),(2786,'edit_training_location',184,1,'2014-08-06 07:14:11'),(2787,'duplicate_training',184,1,'2014-08-06 07:14:11'),(2788,'edit_facility',184,1,'2014-08-06 07:14:11'),(2789,'edit_training_location',185,1,'2014-08-06 10:43:55'),(2790,'duplicate_training',185,1,'2014-08-06 10:43:55'),(2791,'edit_facility',185,1,'2014-08-06 10:43:55'),(2792,'view_create_reports',185,1,'2014-08-06 10:43:55'),(2793,'admin_files',185,1,'2014-08-06 10:43:55'),(2794,'edit_employee',185,1,'2014-08-06 10:43:55'),(2795,'use_offline_app',185,1,'2014-08-06 10:43:55'),(2796,'edit_people',185,1,'2014-08-06 10:43:55'),(2797,'edit_course',185,1,'2014-08-06 10:43:55'),(2798,'edit_employee',186,1,'2014-08-06 11:10:42'),(2799,'use_offline_app',186,1,'2014-08-06 11:10:42'),(2800,'edit_people',186,1,'2014-08-06 11:10:42'),(2801,'edit_course',186,1,'2014-08-06 11:10:42'),(2802,'edit_training_location',186,1,'2014-08-06 11:10:42'),(2803,'duplicate_training',186,1,'2014-08-06 11:10:42'),(2804,'edit_facility',186,1,'2014-08-06 11:10:42'),(2805,'view_create_reports',186,1,'2014-08-06 11:10:42'),(2806,'admin_files',186,1,'2014-08-06 11:10:42'),(2807,'view_create_reports',187,1,'2014-08-06 14:39:43'),(2808,'admin_files',187,1,'2014-08-06 14:39:43'),(2809,'edit_employee',187,1,'2014-08-06 14:39:43'),(2816,'approve_trainings',187,7,'2014-08-06 14:46:12'),(2811,'edit_people',187,1,'2014-08-06 14:39:43'),(2812,'edit_course',187,1,'2014-08-06 14:39:43'),(2813,'edit_training_location',187,1,'2014-08-06 14:39:43'),(2819,'edit_training_location',188,7,'2014-08-07 09:57:55'),(2815,'edit_facility',187,1,'2014-08-06 14:39:43'),(2820,'edit_facility',188,7,'2014-08-07 09:57:55'),(2821,'view_create_reports',188,7,'2014-08-07 09:57:55'),(2822,'admin_files',188,7,'2014-08-07 09:57:55'),(2823,'edit_employee',188,7,'2014-08-07 09:57:55'),(2824,'edit_people',189,7,'2014-08-07 09:59:45'),(2825,'edit_course',189,7,'2014-08-07 09:59:45'),(2826,'edit_training_location',189,7,'2014-08-07 09:59:45'),(2827,'duplicate_training',189,7,'2014-08-07 09:59:45'),(2828,'edit_facility',189,7,'2014-08-07 09:59:45'),(2829,'view_create_reports',189,7,'2014-08-07 09:59:45'),(2830,'admin_files',189,7,'2014-08-07 09:59:45'),(2831,'edit_employee',189,7,'2014-08-07 09:59:45'),(2832,'view_create_reports',190,7,'2014-08-07 11:32:24'),(2833,'admin_files',190,7,'2014-08-07 11:32:24'),(2834,'edit_employee',190,7,'2014-08-07 11:32:24'),(2835,'edit_people',190,7,'2014-08-07 11:32:24'),(2836,'edit_course',190,7,'2014-08-07 11:32:24'),(2837,'edit_training_location',190,7,'2014-08-07 11:32:24'),(2838,'edit_facility',190,7,'2014-08-07 11:32:24'),(2839,'edit_course',191,7,'2014-08-08 07:58:45'),(2840,'edit_training_location',191,7,'2014-08-08 07:58:45'),(2841,'edit_facility',191,7,'2014-08-08 07:58:45'),(2842,'view_create_reports',191,7,'2014-08-08 07:58:45'),(2843,'admin_files',191,7,'2014-08-08 07:58:45'),(2844,'edit_employee',191,7,'2014-08-08 07:58:45'),(2845,'edit_people',191,7,'2014-08-08 07:58:45'),(2846,'edit_people',192,7,'2014-08-08 08:08:30'),(2847,'edit_course',192,7,'2014-08-08 08:08:30'),(2848,'edit_training_location',192,7,'2014-08-08 08:08:30'),(2849,'duplicate_training',192,7,'2014-08-08 08:08:30'),(2850,'edit_facility',192,7,'2014-08-08 08:08:30'),(2851,'view_create_reports',192,7,'2014-08-08 08:08:30'),(2852,'admin_files',192,7,'2014-08-08 08:08:30'),(2853,'edit_employee',192,7,'2014-08-08 08:08:30'),(2854,'use_offline_app',192,7,'2014-08-08 08:08:30'),(2855,'edit_course',193,1,'2014-08-11 08:53:53'),(2856,'edit_facility',193,1,'2014-08-11 08:53:53'),(2857,'view_create_reports',193,1,'2014-08-11 08:53:53'),(2858,'admin_files',193,1,'2014-08-11 08:53:53'),(2859,'edit_employee',193,1,'2014-08-11 08:53:53'),(2860,'edit_people',193,1,'2014-08-11 08:53:53'),(2861,'edit_training_location',194,1,'2014-08-11 12:26:07'),(2862,'duplicate_training',194,1,'2014-08-11 12:26:07'),(2863,'edit_facility',194,1,'2014-08-11 12:26:07'),(2864,'view_create_reports',194,1,'2014-08-11 12:26:07'),(2865,'admin_files',194,1,'2014-08-11 12:26:07'),(2866,'edit_employee',194,1,'2014-08-11 12:26:07'),(2867,'use_offline_app',194,1,'2014-08-11 12:26:07'),(2868,'edit_people',194,1,'2014-08-11 12:26:07'),(2869,'edit_course',194,1,'2014-08-11 12:26:07'),(2870,'edit_course',195,1,'2014-08-11 12:27:24'),(2871,'edit_training_location',195,1,'2014-08-11 12:27:24'),(2872,'edit_facility',195,1,'2014-08-11 12:27:24'),(2873,'view_create_reports',195,1,'2014-08-11 12:27:24'),(2874,'admin_files',195,1,'2014-08-11 12:27:24'),(2875,'edit_employee',195,1,'2014-08-11 12:27:24'),(2876,'edit_people',195,1,'2014-08-11 12:27:24'),(2877,'edit_course',196,1,'2014-08-13 12:31:41'),(2878,'edit_training_location',196,1,'2014-08-13 12:31:41'),(2879,'duplicate_training',196,1,'2014-08-13 12:31:41'),(2880,'edit_facility',196,1,'2014-08-13 12:31:41'),(2881,'view_create_reports',196,1,'2014-08-13 12:31:41'),(2882,'admin_files',196,1,'2014-08-13 12:31:41'),(2883,'edit_employee',196,1,'2014-08-13 12:31:41'),(2884,'use_offline_app',196,1,'2014-08-13 12:31:41'),(2885,'acl_editor_facility_sponsors',196,1,'2014-08-13 12:31:41'),(2886,'edit_people',196,1,'2014-08-13 12:31:41'),(2887,'duplicate_training',197,1,'2014-08-14 12:47:40'),(2888,'edit_facility',197,1,'2014-08-14 12:47:40'),(2889,'view_create_reports',197,1,'2014-08-14 12:47:40'),(2890,'admin_files',197,1,'2014-08-14 12:47:40'),(2945,'edit_people',203,7,'2014-08-28 12:56:55'),(2892,'use_offline_app',197,1,'2014-08-14 12:47:40'),(2893,'edit_people',197,1,'2014-08-14 12:47:40'),(2894,'edit_course',197,1,'2014-08-14 12:47:40'),(2895,'edit_training_location',197,1,'2014-08-14 12:47:40'),(2896,'edit_employee',197,1,'2014-08-14 14:23:35'),(2946,'edit_course',203,7,'2014-08-28 12:56:55'),(2947,'edit_training_location',203,7,'2014-08-28 12:56:55'),(2948,'edit_facility',203,7,'2014-08-28 12:56:55'),(2949,'view_create_reports',203,7,'2014-08-28 12:56:55'),(2950,'admin_files',203,7,'2014-08-28 12:56:55'),(2951,'edit_course',204,2,'2014-08-28 17:01:01'),(2952,'edit_training_location',204,2,'2014-08-28 17:01:01'),(2953,'duplicate_training',204,2,'2014-08-28 17:01:01'),(2954,'edit_facility',204,2,'2014-08-28 17:01:01'),(2955,'view_create_reports',204,2,'2014-08-28 17:01:01'),(2956,'admin_files',204,2,'2014-08-28 17:01:01'),(2957,'edit_employee',204,2,'2014-08-28 17:01:01'),(2958,'in_service',204,2,'2014-08-28 17:01:01'),(2959,'use_offline_app',204,2,'2014-08-28 17:01:01'),(2960,'edit_people',204,2,'2014-08-28 17:01:01'),(2961,'admin_files',1,1,'2014-08-28 17:44:29'),(2962,'edit_training_location',205,2,'2014-08-28 17:45:06'),(2963,'duplicate_training',205,2,'2014-08-28 17:45:06'),(2964,'edit_facility',205,2,'2014-08-28 17:45:06'),(2965,'view_create_reports',205,2,'2014-08-28 17:45:06'),(2966,'admin_files',205,2,'2014-08-28 17:45:06'),(2967,'edit_employee',205,2,'2014-08-28 17:45:06'),(2968,'use_offline_app',205,2,'2014-08-28 17:45:06'),(2969,'edit_people',205,2,'2014-08-28 17:45:06'),(2970,'edit_course',205,2,'2014-08-28 17:45:06'),(2971,'employees_module',2,1,'2013-04-19 16:28:10'),(2972,'employees_module',1,1,'2013-04-19 16:58:44'),(2973,'employees_module',3,1,'2013-04-19 16:29:18'),(2974,'employees_module',4,2,'2013-04-21 21:19:07'),(2975,'employees_module',15,1,'2013-04-30 00:58:58'),(2976,'employees_module',52,1,'2013-04-30 00:58:58'),(2977,'employees_module',105,1,'2013-04-30 00:58:58'),(2978,'employees_module',106,7,'2014-02-05 04:52:53'),(2979,'employees_module',15,1,'2013-04-30 01:02:37'),(2980,'employees_module',52,1,'2013-04-30 01:02:37'),(2981,'employees_module',105,1,'2013-04-30 01:02:37'),(2982,'employees_module',5,2,'2013-07-08 09:36:24'),(2983,'employees_module',6,5,'2013-07-15 13:17:14'),(2984,'employees_module',8,7,'2013-07-24 08:45:04'),(2985,'employees_module',7,5,'2013-07-24 15:33:17'),(2986,'employees_module',9,5,'2013-07-24 08:58:06'),(2987,'employees_module',10,5,'2013-07-24 08:58:56'),(2988,'employees_module',11,5,'2013-07-24 08:59:28'),(2989,'employees_module',12,5,'2013-07-24 09:00:17'),(2990,'employees_module',13,5,'2013-07-24 09:00:55'),(2991,'employees_module',14,5,'2013-07-24 09:02:04'),(2992,'employees_module',110,107,'2014-06-23 12:14:42'),(2993,'employees_module',16,7,'2014-01-28 09:27:59'),(2994,'employees_module',17,7,'2014-01-28 09:33:48'),(2995,'employees_module',18,7,'2014-01-28 09:57:25'),(2996,'employees_module',19,7,'2014-01-28 10:24:50'),(2997,'employees_module',21,7,'2014-01-28 10:32:15'),(2998,'employees_module',22,7,'2014-01-28 10:51:41'),(2999,'employees_module',23,7,'2014-01-28 10:52:40'),(3000,'employees_module',24,7,'2014-01-28 10:55:35'),(3001,'employees_module',25,7,'2014-01-28 10:56:52'),(3002,'employees_module',26,7,'2014-01-28 10:59:25'),(3003,'employees_module',27,7,'2014-01-28 11:00:23'),(3004,'employees_module',28,7,'2014-01-28 11:03:20'),(3005,'employees_module',29,7,'2014-01-28 11:04:27'),(3006,'employees_module',30,7,'2014-01-28 11:07:50'),(3007,'employees_module',32,7,'2014-01-28 13:44:21'),(3008,'employees_module',33,7,'2014-01-28 13:48:43'),(3009,'employees_module',34,7,'2014-01-28 13:51:02'),(3010,'employees_module',35,7,'2014-01-28 13:52:05'),(3011,'employees_module',36,18,'2014-01-28 13:56:58'),(3012,'employees_module',38,18,'2014-01-28 14:18:05'),(3013,'employees_module',39,7,'2014-01-28 15:23:51'),(3014,'employees_module',40,7,'2014-01-28 15:31:07'),(3015,'employees_module',41,7,'2014-01-28 15:33:26'),(3016,'employees_module',42,7,'2014-01-28 15:34:22'),(3017,'employees_module',43,7,'2014-01-28 15:36:25'),(3018,'employees_module',44,7,'2014-01-28 15:41:15'),(3019,'employees_module',45,7,'2014-01-28 15:42:09'),(3020,'employees_module',46,7,'2014-01-28 15:44:53'),(3021,'employees_module',47,7,'2014-01-28 15:48:08'),(3022,'employees_module',48,7,'2014-01-28 15:51:16'),(3023,'employees_module',49,7,'2014-01-28 15:52:10'),(3024,'employees_module',50,7,'2014-01-28 15:53:10'),(3025,'employees_module',51,7,'2014-01-28 15:54:09'),(3026,'employees_module',53,18,'2014-01-29 06:11:44'),(3027,'employees_module',54,18,'2014-01-29 06:17:03'),(3028,'employees_module',55,18,'2014-01-29 06:44:01'),(3029,'employees_module',31,2,'2014-01-29 07:51:29'),(3030,'employees_module',56,18,'2014-01-29 07:56:09'),(3031,'employees_module',57,18,'2014-01-29 07:58:47'),(3032,'employees_module',58,18,'2014-01-29 08:13:34'),(3033,'employees_module',59,18,'2014-01-29 08:20:55'),(3034,'employees_module',60,18,'2014-01-29 08:37:47'),(3035,'employees_module',61,18,'2014-01-29 09:01:03'),(3036,'employees_module',62,18,'2014-01-29 09:03:32'),(3037,'employees_module',63,18,'2014-01-29 09:15:57'),(3038,'employees_module',64,18,'2014-01-29 09:18:12'),(3039,'employees_module',65,18,'2014-01-29 09:36:38'),(3040,'employees_module',66,18,'2014-01-29 09:43:30'),(3041,'employees_module',67,18,'2014-01-29 09:48:46'),(3042,'employees_module',68,18,'2014-01-29 09:53:23'),(3043,'employees_module',69,18,'2014-01-29 09:56:39'),(3044,'employees_module',70,18,'2014-01-29 10:04:12'),(3045,'employees_module',71,18,'2014-01-29 10:10:19'),(3046,'employees_module',72,18,'2014-01-29 10:19:46'),(3047,'employees_module',73,18,'2014-01-29 10:25:35'),(3048,'employees_module',74,18,'2014-01-29 10:28:19'),(3049,'employees_module',75,7,'2014-01-30 05:46:25'),(3050,'employees_module',37,7,'2014-01-30 13:08:01'),(3051,'employees_module',76,7,'2014-01-30 13:19:56'),(3052,'employees_module',77,7,'2014-01-30 13:23:58'),(3053,'employees_module',78,7,'2014-01-30 13:37:52'),(3054,'employees_module',79,7,'2014-01-30 13:45:50'),(3055,'employees_module',80,7,'2014-01-30 14:07:14'),(3056,'employees_module',81,7,'2014-01-30 14:07:54'),(3057,'employees_module',82,7,'2014-01-30 14:23:19'),(3058,'employees_module',83,7,'2014-01-30 14:30:34'),(3059,'employees_module',84,7,'2014-01-30 14:47:01'),(3060,'employees_module',85,7,'2014-01-30 14:56:29'),(3061,'employees_module',86,7,'2014-01-30 15:01:30'),(3062,'employees_module',87,7,'2014-01-30 15:02:12'),(3063,'employees_module',88,7,'2014-01-31 06:55:59'),(3064,'employees_module',89,7,'2014-01-31 06:57:01'),(3065,'employees_module',91,7,'2014-01-31 11:15:30'),(3066,'employees_module',92,7,'2014-02-03 07:45:48'),(3067,'employees_module',93,7,'2014-02-03 07:46:51'),(3068,'employees_module',94,7,'2014-02-03 15:09:50'),(3069,'employees_module',95,7,'2014-02-03 15:13:04'),(3070,'employees_module',96,7,'2014-02-03 15:14:04'),(3071,'employees_module',97,7,'2014-02-03 15:15:18'),(3072,'employees_module',98,7,'2014-02-04 06:52:37'),(3073,'employees_module',99,7,'2014-02-04 07:54:43'),(3074,'employees_module',100,7,'2014-02-04 07:55:53'),(3075,'employees_module',101,7,'2014-02-04 08:11:24'),(3076,'employees_module',102,7,'2014-02-04 09:48:38'),(3077,'employees_module',103,7,'2014-02-04 10:16:33'),(3078,'employees_module',104,7,'2014-02-04 10:36:36'),(3079,'employees_module',107,7,'2014-02-05 08:15:22'),(3080,'employees_module',108,7,'2014-02-05 10:24:22'),(3081,'employees_module',109,107,'2014-02-05 11:25:47'),(3082,'employees_module',199,1,'2014-08-20 11:39:56'),(3083,'employees_module',112,107,'2014-02-05 12:25:59'),(3084,'employees_module',113,107,'2014-02-05 13:19:47'),(3085,'employees_module',114,107,'2014-02-05 13:40:42'),(3086,'employees_module',120,7,'2014-02-06 15:09:19'),(3087,'employees_module',122,107,'2014-02-07 07:40:52'),(3088,'employees_module',123,107,'2014-02-07 08:03:34'),(3089,'employees_module',119,107,'2014-02-07 08:18:44'),(3090,'employees_module',124,107,'2014-02-07 08:31:08'),(3091,'employees_module',125,107,'2014-02-07 09:21:37'),(3092,'employees_module',126,107,'2014-02-07 09:45:58'),(3093,'employees_module',127,107,'2014-02-07 09:51:17'),(3094,'employees_module',20,107,'2014-02-07 09:52:17'),(3095,'employees_module',128,107,'2014-02-07 10:00:50'),(3096,'employees_module',129,107,'2014-02-07 10:07:47'),(3097,'employees_module',130,107,'2014-02-07 10:52:30'),(3098,'employees_module',131,107,'2014-02-07 11:23:22'),(3099,'employees_module',132,107,'2014-02-07 12:14:05'),(3100,'employees_module',133,7,'2014-02-07 13:54:58'),(3101,'employees_module',134,107,'2014-02-10 06:32:59'),(3102,'employees_module',135,107,'2014-02-10 07:23:52'),(3103,'employees_module',136,107,'2014-02-10 07:30:38'),(3104,'employees_module',137,136,'2014-02-10 08:33:44'),(3105,'employees_module',138,135,'2014-02-10 09:42:00'),(3106,'employees_module',139,135,'2014-02-10 09:43:59'),(3107,'employees_module',140,135,'2014-02-10 09:45:33'),(3108,'employees_module',141,135,'2014-02-10 09:47:20'),(3109,'employees_module',142,135,'2014-02-10 09:48:44'),(3110,'employees_module',147,7,'2014-02-10 18:36:47'),(3111,'employees_module',143,135,'2014-02-10 09:50:24'),(3112,'employees_module',144,136,'2014-02-10 10:05:38'),(3113,'employees_module',145,107,'2014-02-10 11:27:43'),(3114,'employees_module',146,136,'2014-02-10 13:13:30'),(3115,'employees_module',148,107,'2014-02-11 09:28:41'),(3116,'employees_module',149,107,'2014-02-11 09:33:49'),(3117,'employees_module',150,107,'2014-02-11 09:56:11'),(3118,'employees_module',151,107,'2014-02-11 10:37:58'),(3119,'employees_module',152,135,'2014-02-11 10:50:39'),(3120,'employees_module',153,135,'2014-02-11 10:53:05'),(3121,'employees_module',154,107,'2014-02-11 11:07:25'),(3122,'employees_module',155,136,'2014-02-11 13:13:37'),(3123,'employees_module',156,135,'2014-02-11 13:51:50'),(3124,'employees_module',157,135,'2014-02-11 13:54:04'),(3125,'employees_module',115,135,'2014-02-12 08:59:40'),(3126,'employees_module',158,136,'2014-02-12 10:14:31'),(3127,'employees_module',118,135,'2014-02-12 12:29:30'),(3128,'employees_module',159,135,'2014-02-12 10:21:22'),(3129,'employees_module',161,107,'2014-02-18 05:11:21'),(3130,'employees_module',163,107,'2014-02-20 07:01:48'),(3131,'employees_module',164,107,'2014-02-20 07:04:13'),(3132,'employees_module',162,107,'2014-02-20 07:06:31'),(3133,'employees_module',165,107,'2014-02-21 09:23:23'),(3134,'employees_module',166,7,'2014-02-24 13:45:10'),(3135,'employees_module',167,7,'2014-02-25 17:34:37'),(3136,'employees_module',168,7,'2014-02-25 17:35:51'),(3137,'employees_module',169,7,'2014-02-25 17:37:07'),(3138,'employees_module',170,107,'2014-02-27 10:47:55'),(3139,'employees_module',171,107,'2014-03-10 06:23:00'),(3140,'employees_module',172,107,'2014-04-16 05:55:51'),(3141,'employees_module',173,107,'2014-05-16 04:12:37'),(3142,'employees_module',198,1,'2014-08-18 13:27:07'),(3143,'employees_module',202,1,'2014-08-27 08:03:57'),(3144,'employees_module',201,1,'2014-08-22 08:31:48'),(3145,'employees_module',174,107,'2014-06-27 08:34:06'),(3146,'employees_module',203,7,'2014-08-28 12:56:55'),(3147,'employees_module',200,1,'2014-08-22 08:30:11'),(3148,'employees_module',175,107,'2014-07-01 08:38:50'),(3149,'employees_module',176,7,'2014-07-04 09:20:44'),(3150,'employees_module',177,107,'2014-07-21 09:17:53'),(3151,'employees_module',178,107,'2014-07-22 06:47:06'),(3152,'employees_module',179,1,'2014-07-22 07:47:52'),(3153,'employees_module',90,1,'2014-08-05 12:47:03'),(3154,'employees_module',180,1,'2014-07-22 17:40:30'),(3155,'employees_module',181,1,'2014-08-06 06:02:13'),(3156,'employees_module',182,1,'2014-08-06 07:08:13'),(3157,'employees_module',183,1,'2014-08-06 07:10:42'),(3158,'employees_module',184,1,'2014-08-06 07:14:11'),(3159,'employees_module',185,1,'2014-08-06 10:43:55'),(3160,'employees_module',186,1,'2014-08-06 11:10:42'),(3161,'employees_module',187,1,'2014-08-06 14:39:43'),(3162,'employees_module',188,7,'2014-08-07 09:57:55'),(3163,'employees_module',189,7,'2014-08-07 09:59:45'),(3164,'employees_module',190,7,'2014-08-07 11:32:24'),(3165,'employees_module',191,7,'2014-08-08 07:58:45'),(3166,'employees_module',192,7,'2014-08-08 08:08:30'),(3167,'employees_module',193,1,'2014-08-11 08:53:53'),(3168,'employees_module',194,1,'2014-08-11 12:26:07'),(3169,'employees_module',195,1,'2014-08-11 12:27:24'),(3170,'employees_module',196,1,'2014-08-13 12:31:41'),(3171,'employees_module',197,1,'2014-08-14 14:23:35'),(3172,'employees_module',204,2,'2014-08-28 17:01:01'),(3173,'employees_module',205,2,'2014-08-28 17:45:06'),(3174,'edit_partners',7,1,'2014-11-13 05:50:59'),(3175,'edit_mechanisms',7,1,'2014-11-13 05:50:59'),(3176,'edit_partners',2,1,'2014-11-13 05:51:20'),(3177,'edit_mechanisms',2,1,'2014-11-13 05:51:20'),(3178,'edit_training_location',2,1,'2014-11-13 05:51:20'),(3181,'view_mechanisms',204,1,'2014-11-13 08:55:51'),(3182,'view_partners',204,1,'2014-11-13 08:55:51');
/*!40000 ALTER TABLE `user_to_acl` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_to_acl_province`
--

DROP TABLE IF EXISTS `user_to_acl_province`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_to_acl_province` (
  `id` int(11) NOT NULL auto_increment,
  `user_id` int(11) NOT NULL,
  `location_id` int(11) NOT NULL,
  `created_by` int(11) default NULL,
  `timestamp_created` timestamp NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`),
  KEY `created_by` (`created_by`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `user_to_organizer_access`
--

DROP TABLE IF EXISTS `user_to_organizer_access`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_to_organizer_access` (
  `id` int(11) NOT NULL auto_increment,
  `training_organizer_option_id` int(11) default NULL,
  `user_id` int(11) NOT NULL,
  `created_by` int(11) default NULL,
  `timestamp_created` timestamp NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`),
  KEY `created_by` (`created_by`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=603 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2014-12-04 22:27:53
