<?php

require_once ('ITechController.php');
require_once ('ZendFramework/library/Zend/Controller/Action.php');
require_once ('models/table/Location.php');

class DataController extends ITechController {
	
    public function __construct(Zend_Controller_Request_Abstract $request, Zend_Controller_Response_Abstract $response, array $invokeArgs = array() ) {
		parent::__construct ( $request, $response, $invokeArgs = array () );
	
	}

  public function preDispatch() {
    $rtn = parent::preDispatch ();
 
    if (! $this->isLoggedIn ())
      $this->doNoAccessError ();

    if (! $this->hasACL ( 'view_create_reports' )) 
      $this->doNoAccessError ();

    return $rtn;

  }

  public function trainingsAction() {
    try {
  	require_once('models/table/Training.php');
    $table = new Training();
    
     $select = $table->select()
        ->from('training', array('*'))
        ->setIntegrityCheck(false)
        ->joinLeft (array('t'    => 'training_title_option'), "training_title_option_id = t.id",array('training_title' => 'training_title_phrase'))
        ->joinLeft (array('tl'   => 'training_location'), "training.training_location_id = tl.id",array('training_location_name', 'location_id'=>'tl.location_id'))
        ->joinLeft (array('tg'   => 'training_got_curriculum_option'), "training.training_got_curriculum_option_id = tg.id",'training_got_curriculum_phrase')
        ->joinLeft (array('tlvl' => 'training_level_option'), "training.training_level_option_id = tlvl.id",'training_level_phrase');
        // todo inner joins were Causing trainings w/out level / title to not get dl'ed, might not print right w/out title, should replace nulls with 'unknown' or something

    $ids = $this->getSanParam('ids');
    if ($ids){
        $select = $select->where("training.id in ($ids)");
        $ids = explode(',', $ids);
        $this->view->assign('ids', $ids);
    }
    
    // fetch
    $rowRay = $table->fetchAll($select)->toArray();
   //sort by id
   $sorted = array();
   foreach($rowRay as $row) {
    $sorted[$row['id']] = $row;
   }
        
   $sorted = $table->_fill_related($sorted, 'training_got_curriculum_option', 'training_got_curriculum_option_id', 'training_got_curriculum_phrase');

   $locations = Location::getAll();
   foreach($sorted as $id => $row) {
    $city_info = Location::getCityInfo($row['location_id'], $this->setting('num_location_tiers'), $locations);
    if ( count($city_info) ) {
      $sorted[$id]['city_name'] = $locations[$city_info['city_name']]['location_name'];
      $sorted[$id]['province_name'] = $locations[$city_info[1]]['name'];
      if ( $this->setting( 'display_region_b' ) ) $sorted[$id]['district_name'] = $locations[$city_info[2]]['name'];
      if ( $this->setting( 'display_region_c' ) ) $sorted[$id]['region_c_name'] = $locations[$city_info[3]]['name'];
      if ( $this->setting( 'display_region_d' ) ) $sorted[$id]['region_d_name'] = $locations[$city_info[4]]['name'];
      if ( $this->setting( 'display_region_e' ) ) $sorted[$id]['region_e_name'] = $locations[$city_info[5]]['name'];
      if ( $this->setting( 'display_region_f' ) ) $sorted[$id]['region_f_name'] = $locations[$city_info[6]]['name'];
      if ( $this->setting( 'display_region_g' ) ) $sorted[$id]['region_g_name'] = $locations[$city_info[7]]['name'];
      if ( $this->setting( 'display_region_h' ) ) $sorted[$id]['region_h_name'] = $locations[$city_info[8]]['name'];
      if ( $this->setting( 'display_region_i' ) ) $sorted[$id]['region_i_name'] = $locations[$city_info[9]]['name'];
    }
     unset($sorted[$id]['location_id']); 
   }
   
   #todo refresher option 
   $sorted = $table->_fill_related($sorted, 'training_custom_1_option', 'training_custom_1_option_id', 'custom1_phrase');
   $sorted = $table->_fill_related($sorted, 'training_custom_2_option', 'training_custom_2_option_id', 'custom2_phrase');
   $sorted = $table->_fill_related($sorted, 'training_organizer_option', 'training_organizer_option_id', 'training_organizer_phrase');
   $sorted = $table->_fill_related($sorted, 'training_level_option', 'training_level_option_id', 'training_level_phrase');
   $sorted = $table->_fill_related($sorted, 'training_method_option', 'training_method_option_id', 'training_method_phrase');
   $sorted = $table->_fill_related($sorted, 'trainer_language_option', 'training_primary_language_option_id', 'language_phrase');
   $sorted = $table->_fill_related($sorted, 'trainer_language_option', 'training_secondary_language_option_id', 'language_phrase');
   $sorted = $table->_fill_intersection_related($sorted, 'training_funding_option', 'training_to_training_funding_option', 'training_funding_option_id', 'funding_phrase');
   $sorted = $table->_fill_intersection_related($sorted, 'training_pepfar_categories_option', 'training_to_training_pepfar_categories_option', 'training_pepfar_categories_option_id', 'pepfar_category_phrase');
   $sorted = $table->_fill_intersection_related($sorted, 'training_topic_option', 'training_to_training_topic_option', 'training_topic_option_id', 'training_topic_phrase');
  if($this->setting('multi_opt_refresher_course')){
  	$sorted = $table->_fill_intersection_related($sorted, 'training_refresher_option', 'training_to_training_refresher_option', 'training_refresher_option_id', 'refresher_phrase_option');
  }
    
   //fill participants
   require_once('models/table/Person.php');
   $personTable = new Person();
   $select = $personTable->select()->from('person', array('id', 'first_name', 'middle_name', 'last_name'))->setIntegrityCheck(false)->join(array('pt'=>'person_to_training'), "pt.person_id = person.id", array('training_id'));
   $rows = $table->fetchAll($select);
   
   foreach($rows as $row) {
    $tid = $row->training_id;
    if ($ids && array_search ($tid, $ids) === false)
      continue; // dont print this training, the user has filtered by the url param ('ids')
    $ra = $row->toArray();
    unset($ra['training_id']);
    $sorted[$tid]['participants'][] = $ra;
   }
   
   //fill participants
   require_once('models/table/OptionList.php');
   $qualsTable = new OptionList(array('name' => 'training_to_person_qualification_option'));
   $select = $qualsTable->select()->from('training_to_person_qualification_option', array('training_id','person_count_na','person_count_male','person_count_female'))->setIntegrityCheck(false)
   ->join(array('tq'=>'person_qualification_option'), "tq.id = training_to_person_qualification_option.person_qualification_option_id", array('qualification_phrase'));
   $rows = $qualsTable->fetchAll($select);
   
   foreach($rows as $row) {
    $tid = $row->training_id;
    if ($ids && array_search ($tid, $ids) === false)
      continue; // dont print this training, the user has filtered by the url param ('ids')
    $ra = $row->toArray();
    $ra['person_count_na'] = $ra['person_count_na'].'(na)';
    $ra['person_count_male'] = $ra['person_count_male'].'(male)';
    $ra['person_count_female'] = $ra['person_count_female'].'(female)';
    unset($ra['training_id']);
    $sorted[$tid]['unknown participants'][] = $ra;
   }
   
   //fill trainers
   require_once('models/table/TrainingToTrainer.php');
   $personTable = new TrainingToTrainer();
   $select = $personTable->select()->from('training_to_trainer', array('training_id'))->setIntegrityCheck(false)->join(array('p'=>'person'), "training_to_trainer.trainer_id = p.id", array('id', 'first_name', 'middle_name', 'last_name'));
   $rows = $table->fetchAll($select);
   
   foreach($rows as $row) {
    $tid = $row->training_id;
    if ($ids && array_search ($tid, $ids) === false)
      continue; // dont print this training, the user has filtered by the url param ('ids')
    $ra = $row->toArray();
    unset($ra['training_id']);
    $sorted[$tid]['trainers'][] = $ra;
   }
   
   $this->view->assign('data', $sorted);

   if ($this->getSanParam('outputType') == 'csv') 
      $this->sendData ( $this->reportHeaders ( false, $sorted ) );    
       
    } catch (Exception $e) {
      echo $e->getMessage();
      error_log($e->getMessage());      
    }
 }

  public function facilitiesAction() {
    try {
    require_once('models/table/Facility.php');
  	$facilityTable = new Facility();
    $select = $facilityTable->select()
        ->from('facility', array('*'))
        ->setIntegrityCheck(false);
        
    $rowRay = $facilityTable->fetchAll($select)->toArray();
	  $sorted = array();
	   foreach($rowRay as $row) {
	    $sorted[$row['id']] = $row;
	   }
/*
	 $sorted = $facilityTable->_fill_lookup($sorted, 'location_city', 'city_id', 'city_name');
   $sorted = $facilityTable->_fill_lookup($sorted, 'location_district', 'district_id', 'district_name');
   $sorted = $facilityTable->_fill_lookup($sorted, 'location_province', 'province_id', 'province_name');
*/
	   
	 $locations = Location::getAll();
   foreach($sorted as $id => $row) {
    $city_info = Location::getCityInfo($row['location_id'], $this->setting('num_location_tiers'), $locations);
    if ( count($city_info) ) {
      if ( $city_info[0] ) $sorted[$id]['city_name'] = $city_info[0];
      if ( $city_info[1] ) $sorted[$id]['province_name'] = $locations[$city_info[1]]['name'];
      if ( $city_info[2] ) $sorted[$id]['district_name'] = $locations[$city_info[2]]['name'];
      if ( $city_info[3] ) $sorted[$id]['region_c_name'] = $locations[$city_info[3]]['name'];
      if ( $city_info[4] ) $sorted[$id]['region_d_name'] = $locations[$city_info[4]]['name'];
      if ( $city_info[5] ) $sorted[$id]['region_e_name'] = $locations[$city_info[5]]['name'];
      if ( $city_info[6] ) $sorted[$id]['region_f_name'] = $locations[$city_info[6]]['name'];
      if ( $city_info[7] ) $sorted[$id]['region_g_name'] = $locations[$city_info[7]]['name'];
      if ( $city_info[8] ) $sorted[$id]['region_h_name'] = $locations[$city_info[8]]['name'];
      if ( $city_info[9] ) $sorted[$id]['region_i_name'] = $locations[$city_info[9]]['name'];
    }
     unset($sorted[$id]['location_id']); 
        
   }
   
	   
   $sorted = $facilityTable->_fill_lookup($sorted, 'facility_type_option', 'type_option_id', 'facility_type_phrase');
   $sorted = $facilityTable->_fill_lookup($sorted, 'facility_sponsor_option', 'sponsor_option_id', 'facility_sponsor_phrase');
   
    if ($this->getSanParam('outputType') == 'csv') 
      $this->sendData ( $this->reportHeaders ( false, $sorted ) );

   $this->view->assign('data', $sorted);
  } catch (Exception $e) {
      echo $e->getMessage();
      error_log($e->getMessage());      
  }

  }
  
  public function personsAction() {
  	try {
  		
  		//TA:17:12-1
  		$settings = System::getAll();
  		$translation = Translation::getAll();
  	//	print_r($translation); exit;

  		ini_set('memory_limit', -1);
  			
  		require_once('models/table/Person.php');
  		$personTable = new Person();
  		$select = $personTable->select()
  		->from('person', array('*'))
  		->where('is_deleted=0') //TA:104
  		->setIntegrityCheck(false);
  		$rowRay = $personTable->fetchAll($select);
  		$rowRay = @$rowRay->toArray();
  		
  		//print_r($rowRay); exit; // fot debug
  
  		if (sizeof($rowRay) > 10000  && $this->getSanParam('outputType') != 'csv'){
  			$error = 'Data is too large to display, try Export';
  			throw new Exception($error);
  		}
  
  		//sort by id
  		$sorted = array();
  		foreach($rowRay as $row) {
  			
  			//unset($row['suffix_option_id']);
  			//unset($row['title_option_id']);
  			//$sorted[$row['id']] = $row;
  			
  			//TA:
  			$new_row = array();
  			$new_row['ID'] = $row['id'];
  			if($settings['display_people_active']){$new_row[$translation['Is Active']] = $row['active'];}
  			if($settings['display_people_title']){$new_row['title_option_id'] = $row['title_option_id'];}
  			if($settings['display_people_suffix']){$new_row['suffix_option_id'] = $row['suffix_option_id'];}
  			$new_row[$translation['First Name']] = $row['first_name'];
  			if($settings['display_middle_name']){$new_row[$translation['Middle Name']] = $row['middle_name'];}
  			$new_row[$translation['Last Name']] = $row['last_name'];
  			if($settings['display_gender']){$new_row[$translation['Gender']] = $row['gender'];}
  			if($settings['display_people_birthdate']){$new_row['Birthdate'] = $row['birthdate'];}
  			if($settings['display_people_home_address']){
  				$new_row[$translation['Address 1']] = $row['home_address_1'];
  				$new_row[$translation['Address 2']] = $row['home_address_2'];
  			}
  			if($settings['display_people_home_phone']){$new_row[$translation['Home phone']] = $row['phone_home'];}
  			$new_row['Work phone'] = $row['phone_work']; //TA:55 10/7/2015 
  			$new_row['Mobile phone'] = $row['phone_mobile']; //TA:55 10/7/2015
  			if($settings['display_people_fax']){$new_row['Fax'] = $row['fax'];}
  			$new_row['Email'] = $row['email'];
  			$new_row['Secondary email'] = $row['email_secondary']; //TA:55 10/7/2015
  			
  			if($settings['display_national_id']){$new_row[$translation['National ID']] = $row['national_id'];}
  			if($settings['display_people_file_num']){$new_row[$translation['File Number']] = $row['file_number'];}
  			if($settings['display_highest_ed_level']){$new_row['highest_edu_level_option_id'] = $row['highest_edu_level_option_id'];}
  			
   			$new_row['primary_qualification_option_id'] = $row['primary_qualification_option_id'];
  			$new_row['primary_responsibility_option_id'] = $row['primary_responsibility_option_id'];
  			$new_row['secondary_responsibility_option_id'] = $row['secondary_responsibility_option_id'];
  			$new_row['facility_id'] = $row['facility_id'];
  			
  			if($settings['display_people_custom1']){$new_row['person_custom_1_option_id'] = $row['person_custom_1_option_id'];}
  			if($settings['display_people_custom2']){$new_row['person_custom_2_option_id'] = $row['person_custom_2_option_id'];}
  		
  			if($settings['display_attend_reason']){$new_row['attend_reason_option_id'] = $row['attend_reason_option_id'];}
  			if($settings['display_responsibility_me']){$new_row[$translation['M&E Responsibility']] = $row['me_responsibility'];}
  			if($settings['display_primary_responsibility']){$new_row['primary_responsibility_option_id'] = $row['primary_responsibility_option_id'];}
  			if($settings['display_secondary_responsibility']){$new_row['secondary_responsibility_option_id'] = $row['secondary_responsibility_option_id'];}
  			if($settings['display_people_custom3']){$new_row[$translation['People Custom 3']] = $row['custom_3'];} //?
  			if($settings['display_people_custom4']){$new_row[$translation['People Custom 4']] = $row['custom_4'];}//?
  			if($settings['display_people_custom5']){$new_row[$translation['People Custom 5']] = $row['custom_5'];}//?
  			if($settings['display_people_comments']){$new_row[$translation['Comments']] = $row['comments'];}
  			$sorted[$row['id']] = $new_row;
  		}
  
  		/*
  		 $sorted = $personTable->_fill_lookup($sorted, 'location_city', 'home_city_id', 'city_name');
  		$sorted = $personTable->_fill_lookup($sorted, 'location_district', 'home_district_id', 'district_name');
  		$sorted = $personTable->_fill_lookup($sorted, 'location_province', 'home_province_id', 'province_name');
  		*/
  		$locations = Location::getAll();
  			
  		foreach($sorted as $id => $row) {
  			$city_info = Location::getCityInfo($row['home_location_id'], $this->setting('num_location_tiers'), $locations);
  			if ( count($city_info) ) {
  				if ( $city_info[0] ) $sorted[$id]['city_name'] = $city_info[0];
  				if ( $city_info[1] ) $sorted[$id]['province_name'] = $locations[$city_info[1]]['name'];
  				if ( $city_info[2] ) $sorted[$id]['district_name'] = $locations[$city_info[2]]['name'];
  				if ( $city_info[3] ) $sorted[$id]['region_c_name'] = $locations[$city_info[3]]['name'];
  				if ( $city_info[4] ) $sorted[$id]['region_d_name'] = $locations[$city_info[4]]['name'];
  				if ( $city_info[5] ) $sorted[$id]['region_e_name'] = $locations[$city_info[5]]['name'];
  				if ( $city_info[6] ) $sorted[$id]['region_f_name'] = $locations[$city_info[6]]['name'];
  				if ( $city_info[7] ) $sorted[$id]['region_g_name'] = $locations[$city_info[7]]['name'];
  				if ( $city_info[8] ) $sorted[$id]['region_h_name'] = $locations[$city_info[8]]['name'];
  				if ( $city_info[9] ) $sorted[$id]['region_i_name'] = $locations[$city_info[9]]['name'];
  			}
  			unset($sorted[$id]['home_location_id']);
  		}
  			
  		$sorted = $personTable->_fill_lookup($sorted, 'person_qualification_option', 'primary_qualification_option_id', 'qualification_phrase');
  		$sorted = $personTable->_fill_lookup($sorted, 'person_primary_responsibility_option', 'primary_responsibility_option_id', 'responsibility_phrase');
  		$sorted = $personTable->_fill_lookup($sorted, 'person_secondary_responsibility_option', 'secondary_responsibility_option_id', 'responsibility_phrase');
  		$sorted = $personTable->_fill_lookup($sorted, 'person_custom_1_option', 'person_custom_1_option_id', 'custom1_phrase');
  		$sorted = $personTable->_fill_lookup($sorted, 'person_custom_2_option', 'person_custom_2_option_id', 'custom2_phrase');
  		$sorted = $personTable->_fill_lookup($sorted, 'facility', 'facility_id', 'facility_name');
  		//Find string on option table: [option table name], [id in sorrrted arr], [column in option table]
  		$sorted = $personTable->_fill_lookup($sorted, 'person_title_option', 'title_option_id', 'title_phrase');
  		$sorted = $personTable->_fill_lookup($sorted, 'person_suffix_option', 'suffix_option_id', 'suffix_phrase');
  		$sorted = $personTable->_fill_lookup($sorted, 'person_attend_reason_option', 'attend_reason_option_id', 'attend_reason_phrase');
  		$sorted = $personTable->_fill_lookup($sorted, 'person_education_level_option', 'highest_edu_level_option_id', 'education_level_phrase');
  		
  		//replace labels    
  		foreach($sorted as $id => $row) {
  			$sorted[$id]['Facility Name'] = $sorted[$id]['facility_name'];
  			unset($sorted[$id]['facility_name']);
  			$sorted[$id]['Qualification'] = $sorted[$id]['qualification_phrase'];
  			unset($sorted[$id]['qualification_phrase']);
  			$sorted[$id][$translation['Highest Education Level']] = $sorted[$id]['education_level_phrase'];
  			unset($sorted[$id]['education_level_phrase']);
  			$sorted[$id][$translation['Reason Attending']] = $sorted[$id]['attend_reason_phrase'];
  			unset($sorted[$id]['attend_reason_phrase']);
  			$sorted[$id][$translation['Suffix']] = $sorted[$id]['suffix_phrase'];
  			unset($sorted[$id]['suffix_phrase']);
  			$sorted[$id][$translation['Title']] = $sorted[$id]['title_phrase'];
  			unset($sorted[$id]['title_phrase']);
  		}
  			
  		//fill participants
  		$select = $personTable->select()->from('person', array('id'))->setIntegrityCheck(false)
  		->join(array('pt'=>'person_to_training'), "pt.person_id = person.id", array('training_id'))
  		->join(array('t'=>'training'), "pt.training_id = t.id", array())
  		->join(array('tt'=>'training_title_option'), "t.training_title_option_id = tt.id", array('training_title_phrase'));
  			
  			
  		$rows = $personTable->fetchAll($select);
  
  		foreach($rows as $row) {
  			$pid = $row->id;
  			$ra = $row->toArray();
  			unset($ra['id']);
  			$sorted[$pid]['courses'][] = $ra;
  		}
  			
  		//fill trainers	
  		$select = $personTable->select()->from('trainer', array('person_id'))->setIntegrityCheck(false)
  		->join(array('pt'=>'training_to_trainer'), "pt.trainer_id = trainer.person_id", array('training_id'))
  		->join(array('t'=>'training'), "pt.training_id = t.id", array())
  		->join(array('tt'=>'training_title_option'), "t.training_title_option_id = tt.id", array('training_title_phrase'));
  		$rows = $personTable->fetchAll($select);
  		
  		foreach($rows as $row) {
  		    $pid = $row->person_id;
  		    $ra = $row->toArray();
  		    unset($ra['person_id']);
  		    $sorted[$pid]['trained'][] = $ra;
  		}
  		
  		//TA:55 10/7/2015 fill tutor language spoken
  		$select = $personTable->select()->from('tutor', array('personid'))->setIntegrityCheck(false)
  		->join(array('pt'=>'link_tutor_languages'), "pt.id_tutor = tutor.id", array('id_language'))
  		->join(array('tt'=>'lookup_languages'), "pt.id_language = tt.id", array('language'));
  		$rows = $personTable->fetchAll($select);
  		foreach($rows as $row) {
  		    $pid = $row->personid;
  		    $ra = $row->toArray();
  		    unset($ra['personid']);
  		    $sorted[$pid]['Languages spoken'][] = $ra;
  		}
  		
  
  		
  			
  		if ($this->getSanParam('outputType') == 'csv')
  			$this->sendData ( $this->reportHeaders ( false, $sorted ) );
  
  		$this->view->assign('data', $sorted);
  
  	} catch (Exception $e) {
  		echo $e->getMessage()."<br>".PHP_EOL;
  	}
  
  }
  
  }
 
  //old code
// public function personsAction() {
// 	try {
			
// 		ini_set('memory_limit', -1);
			
// 		require_once('models/table/Person.php');
// 		$personTable = new Person();
// 		$select = $personTable->select()
// 		->from('person', array('*'))
// 		//->where('first_name', '=', 'Emily')
// 		->setIntegrityCheck(false);
// 		$rowRay = $personTable->fetchAll($select);
// 		$rowRay = @$rowRay->toArray();

// 		if (sizeof($rowRay) > 10000  && $this->getSanParam('outputType') != 'csv'){
// 			$error = 'Data is too large to display, try Export';
// 			throw new Exception($error);
// 		}

// 		//sort by id
// 		$sorted = array();
// 		foreach($rowRay as $row) {
// 			unset($row['suffix_option_id']);
// 			unset($row['title_option_id']);
// 			$sorted[$row['id']] = $row;
// 		}

// 		/*
// 		 $sorted = $personTable->_fill_lookup($sorted, 'location_city', 'home_city_id', 'city_name');
// 		$sorted = $personTable->_fill_lookup($sorted, 'location_district', 'home_district_id', 'district_name');
// 		$sorted = $personTable->_fill_lookup($sorted, 'location_province', 'home_province_id', 'province_name');
// 		*/
// 		$locations = Location::getAll();
			
// 		foreach($sorted as $id => $row) {
// 			$city_info = Location::getCityInfo($row['home_location_id'], $this->setting('num_location_tiers'), $locations);
// 			if ( count($city_info) ) {
// 				if ( $city_info[0] ) $sorted[$id]['city_name'] = $city_info[0];
// 				if ( $city_info[1] ) $sorted[$id]['province_name'] = $locations[$city_info[1]]['name'];
// 				if ( $city_info[2] ) $sorted[$id]['district_name'] = $locations[$city_info[2]]['name'];
// 				if ( $city_info[3] ) $sorted[$id]['region_c_name'] = $locations[$city_info[3]]['name'];
// 				if ( $city_info[4] ) $sorted[$id]['region_d_name'] = $locations[$city_info[4]]['name'];
// 				if ( $city_info[5] ) $sorted[$id]['region_e_name'] = $locations[$city_info[5]]['name'];
// 				if ( $city_info[6] ) $sorted[$id]['region_f_name'] = $locations[$city_info[6]]['name'];
// 				if ( $city_info[7] ) $sorted[$id]['region_g_name'] = $locations[$city_info[7]]['name'];
// 				if ( $city_info[8] ) $sorted[$id]['region_h_name'] = $locations[$city_info[8]]['name'];
// 				if ( $city_info[9] ) $sorted[$id]['region_i_name'] = $locations[$city_info[9]]['name'];
// 			}
// 			unset($sorted[$id]['home_location_id']);
// 		}
			
// 		$sorted = $personTable->_fill_lookup($sorted, 'person_qualification_option', 'primary_qualification_option_id', 'qualification_phrase');
// 		$sorted = $personTable->_fill_lookup($sorted, 'person_primary_responsibility_option', 'primary_responsibility_option_id', 'responsibility_phrase');
// 		$sorted = $personTable->_fill_lookup($sorted, 'person_secondary_responsibility_option', 'secondary_responsibility_option_id', 'responsibility_phrase');
// 		$sorted = $personTable->_fill_lookup($sorted, 'person_custom_1_option', 'person_custom_1_option_id', 'custom1_phrase');
// 		$sorted = $personTable->_fill_lookup($sorted, 'person_custom_2_option', 'person_custom_2_option_id', 'custom2_phrase');
// 		$sorted = $personTable->_fill_lookup($sorted, 'facility', 'facility_id', 'facility_name');
			
// 		//fill participants
// 		$select = $personTable->select()->from('person', array('id'))->setIntegrityCheck(false)
// 		->join(array('pt'=>'person_to_training'), "pt.person_id = person.id", array('training_id'))
// 		->join(array('t'=>'training'), "pt.training_id = t.id", array())
// 		->join(array('tt'=>'training_title_option'), "t.training_title_option_id = tt.id", array('training_title_phrase'));
			
			
// 		$rows = $personTable->fetchAll($select);

// 		foreach($rows as $row) {
// 			$pid = $row->id;
// 			$ra = $row->toArray();
// 			unset($ra['id']);
// 			$sorted[$pid]['courses'][] = $ra;
// 		}
			
// 		//fill trainers
			
// 		$select = $personTable->select()->from('trainer', array('person_id'))->setIntegrityCheck(false)
// 		->join(array('pt'=>'training_to_trainer'), "pt.trainer_id = trainer.person_id", array('training_id'))
// 		->join(array('t'=>'training'), "pt.training_id = t.id", array())
// 		->join(array('tt'=>'training_title_option'), "t.training_title_option_id = tt.id", array('training_title_phrase'));
// 		$rows = $personTable->fetchAll($select);

// 		foreach($rows as $row) {
// 			$pid = $row->person_id;
// 			$ra = $row->toArray();
// 			unset($ra['person_id']);
// 			$sorted[$pid]['trained'][] = $ra;
// 		}
			
// 		if ($this->getSanParam('outputType') == 'csv')
// 			$this->sendData ( $this->reportHeaders ( false, $sorted ) );

// 		$this->view->assign('data', $sorted);

// 	} catch (Exception $e) {
// 		echo $e->getMessage()."<br>".PHP_EOL;
// 	}

// }

// }

?>