<?php

final class SyncSetFactory 
{
	
	public static function create($itemType, $desktopConnectionParams, $syncfile_id) 
	{
		switch($itemType) {
			
			case 'location':
				require_once('app/controllers/sync/set/Location.php');
				return new SyncSetLocation($desktopConnectionParams, $syncfile_id);

			case 'trainer':
				require_once('app/controllers/sync/set/Trainer.php');
				return new SyncSetTrainer($desktopConnectionParams, $syncfile_id);
			
			case 'training':
				require_once('app/controllers/sync/set/Training.php');
				return new SyncSetTraining($desktopConnectionParams, $syncfile_id);
				
			case 'training_location':
				require_once('app/controllers/sync/set/TrainingLocation.php');
				return new SyncSetTrainingLocation($desktopConnectionParams, $syncfile_id);
			
			case 'facility':
				require_once ('app/controllers/sync/set/Facility.php');
				return new SyncSetFacility($desktopConnectionParams, $syncfile_id);
				
			case 'person':
				require_once('app/controllers/sync/set/Person.php');
				return new SyncSetPerson($desktopConnectionParams, $syncfile_id);
				
			case 'facility_sponsor_option':
				require_once('app/controllers/sync/set/SimpleOptionList.php');
				return new SyncSetSimpleOptionList($desktopConnectionParams, $syncfile_id, $itemType, 'facility_sponsor_phrase');
				
			case 'facility_type_option':
				require_once('app/controllers/sync/set/SimpleOptionList.php');
				return new SyncSetSimpleOptionList($desktopConnectionParams, $syncfile_id, $itemType, 'facility_type_phrase');
			
			case 'person_title_option':
				require_once('app/controllers/sync/set/SimpleOptionList.php');
				return new SyncSetSimpleOptionList($desktopConnectionParams, $syncfile_id, $itemType, 'title_phrase');
			
			case 'person_suffix_option':
				require_once('app/controllers/sync/set/SimpleOptionList.php');
				return new SyncSetSimpleOptionList($desktopConnectionParams, $syncfile_id, $itemType, 'suffix_phrase');
			
			case 'person_custom_1_option':
				require_once('app/controllers/sync/set/SimpleOptionList.php');
				return new SyncSetSimpleOptionList($desktopConnectionParams, $syncfile_id, $itemType, 'custom1_phrase');
			
			case 'person_custom_2_option':
				require_once('app/controllers/sync/set/SimpleOptionList.php');
				return new SyncSetSimpleOptionList($desktopConnectionParams, $syncfile_id, $itemType, 'custom2_phrase');
			
			case 'person_primary_responsibility_option':
				require_once('app/controllers/sync/set/SimpleOptionList.php');
				return new SyncSetSimpleOptionList($desktopConnectionParams, $syncfile_id, $itemType, 'responsibility_phrase');

			case 'person_secondary_responsibility_option':
				require_once('app/controllers/sync/set/SimpleOptionList.php');
				return new SyncSetSimpleOptionList($desktopConnectionParams, $syncfile_id, $itemType, 'responsibility_phrase');
			
			case 'person_qualification_option':
				require_once('app/controllers/sync/set/SimpleOptionList.php');
				return new SyncSetSimpleOptionList($desktopConnectionParams, $syncfile_id, $itemType, 'qualifcation_phrase');

			case 'age_range_option':
				require_once('app/controllers/sync/set/SimpleOptionList.php');
				return new SyncSetSimpleOptionList($desktopConnectionParams, $syncfile_id, $itemType, 'age_range_phrase');
			
			case 'training_category_option_to_training_title_option':
				return null;
				
			case 'trainer_affiliation_option':
				require_once('app/controllers/sync/set/SimpleOptionList.php');
				return new SyncSetSimpleOptionList($desktopConnectionParams, $syncfile_id, $itemType, 'trainer_affiliation_phrase');
			
			case 'trainer_skill_option':
				require_once('app/controllers/sync/set/SimpleOptionList.php');
				return new SyncSetSimpleOptionList($desktopConnectionParams, $syncfile_id, $itemType, 'trainer_skill_phrase');
			
			case 'training_custom_1_option':
				require_once('app/controllers/sync/set/SimpleOptionList.php');
				return new SyncSetSimpleOptionList($desktopConnectionParams, $syncfile_id, $itemType, 'custom1_phrase');
			
			case 'training_custom_2_option':
				require_once('app/controllers/sync/set/SimpleOptionList.php');
				return new SyncSetSimpleOptionList($desktopConnectionParams, $syncfile_id, $itemType, 'custom2_phrase');
			
			case 'training_level_option':
				require_once('app/controllers/sync/set/SimpleOptionList.php');
				return new SyncSetSimpleOptionList($desktopConnectionParams, $syncfile_id, $itemType, 'training_level_phrase');
			
			case 'training_method_option':
				require_once('app/controllers/sync/set/SimpleOptionList.php');
				return new SyncSetSimpleOptionList($desktopConnectionParams, $syncfile_id, $itemType, 'training_method_phrase');
			
			case 'training_organizer_option':
				require_once('app/controllers/sync/set/SimpleOptionList.php');
				return new SyncSetSimpleOptionList($desktopConnectionParams, $syncfile_id, $itemType, 'training_organizer_phrase');
			
			case 'training_pepfar_categories_option':
				require_once('app/controllers/sync/set/SimpleOptionList.php');
				return new SyncSetSimpleOptionList($desktopConnectionParams, $syncfile_id, $itemType, 'pepfar_category_phrase');
			
			case 'training_funding_option':
				require_once('app/controllers/sync/set/SimpleOptionList.php');
				return new SyncSetSimpleOptionList($desktopConnectionParams, $syncfile_id, $itemType, 'funding_phrase');
				
			case 'training_got_curriculum_option':
				require_once('app/controllers/sync/set/SimpleOptionList.php');
				return new SyncSetSimpleOptionList($desktopConnectionParams, $syncfile_id, $itemType, 'training_got_curriculum_phrase');
				
			case 'training_category_option':
				require_once('app/controllers/sync/set/SimpleOptionList.php');
				return new SyncSetSimpleOptionList($desktopConnectionParams, $syncfile_id, $itemType, 'training_category_phrase');
				
			case 'training_topic_option':
				require_once('app/controllers/sync/set/SimpleOptionList.php');
				return new SyncSetSimpleOptionList($desktopConnectionParams, $syncfile_id, $itemType, 'training_topic_phrase');
				
			case 'training_title_option':
				require_once('app/controllers/sync/set/SimpleOptionList.php');
				return new SyncSetSimpleOptionList($desktopConnectionParams, $syncfile_id, $itemType, 'training_title_phrase');
				
			case 'trainer_type_option':
				require_once('app/controllers/sync/set/SimpleOptionList.php');
				return new SyncSetSimpleOptionList($desktopConnectionParams, $syncfile_id, $itemType, 'trainer_type_phrase');

			// Added by Sean Smith: 9/22/11
			// Fixed but where Person::Primary and secondary language combo boxes not populated
			// (sqlite db not populated with language domain)
			// See also SyncCompare.php: line ~78
			case 'trainer_language_option':
				require_once('app/controllers/sync/set/SimpleOptionList.php');
				return new SyncSetSimpleOptionList($desktopConnectionParams, $syncfile_id, $itemType, 'language_phrase');
				
			
			case 'trainer_to_trainer_language_option':
				require_once('app/controllers/sync/set/MultiOptionList.php');
				return new SyncSetMultiOptionList($desktopConnectionParams, $syncfile_id, $itemType, 
					array('trainer_id','trainer'),
					array('trainer_language_option_id', 'trainer_language_option'),
					array('is_primary'));
				
			case 'trainer_to_trainer_skill_option':
				require_once('app/controllers/sync/set/MultiOptionList.php');
				return new SyncSetMultiOptionList($desktopConnectionParams, $syncfile_id, $itemType, 
					array('trainer_id','trainer'),
					array('trainer_skill_option_id','trainer_skill_option'));
				
			case 'training_to_training_topic_option':
				require_once('app/controllers/sync/set/MultiOptionList.php');
				return new SyncSetMultiOptionList($desktopConnectionParams, $syncfile_id, $itemType, 
					array('training_id','training'),
					array('training_topic_option_id','training_topic_option'));
				
			case 'training_to_training_pepfar_categories_option':
				require_once('app/controllers/sync/set/MultiOptionList.php');
				return new SyncSetMultiOptionList($desktopConnectionParams, $syncfile_id, $itemType, 
					array('training_id','training'),
					array('training_pepfar_categories_option_id','training_pepfar_categories_option'),
					array('duration_days'));
				
			case 'training_to_training_funding_option':
				require_once('app/controllers/sync/set/MultiOptionList.php');
				return new SyncSetMultiOptionList($desktopConnectionParams, $syncfile_id, $itemType, 
					array('training_id','training'),
					array('training_funding_option_id','training_funding_option'),
					array('funding_amount'));
			
			case 'person_to_training':
				require_once('app/controllers/sync/set/Participant.php');
				return new SyncSetParticipant($desktopConnectionParams, $syncfile_id);
							
			case 'training_to_trainer':
				require_once('app/controllers/sync/set/TrainingToTrainer.php');
				return new SyncSetTrainingToTrainer($desktopConnectionParams, $syncfile_id);

			case 'training_to_person_qualification_option':
				require_once('app/controllers/sync/set/MultiOptionList.php');
				return new SyncSetMultiOptionList($desktopConnectionParams, $syncfile_id, $itemType, 
					array('training_id','training'),
					null,
					array('id','person_qualification_option','person_count_na','person_count_male','person_count_female','age_range_option_id'));
			#case 'person_history':
							
			default:
				throw new Exception($itemType.' set not defined');
				
		}
		
	}
	
	
}

