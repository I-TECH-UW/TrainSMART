<?php
/*
* Created on Feb 11, 2008
*
*  Built for web
*  Fuse IQ -- todd@fuseiq.com
*
*/
require_once('UserController.php');
require_once('models/table/OptionList.php');
require_once('models/table/Helper.php');
require_once('EditTableController.php');
require_once ('views/helpers/DropDown.php');
require_once ('views/helpers/CheckBoxes.php');


class AdminController extends UserController
{

	private $_csvHandle = null;

	public function init()
	{
		$this->view->assign('pageTitle', t('Administration'));
	}

	public function preDispatch()
	{
		$rtn =	parent::preDispatch();

		if ( !$this->isLoggedIn() )
			$this->doNoAccessError();

		if ( ! $this->hasEditorACL() && ! $this->hasACL('edit_country_options') )
			$this->doNoAccessError();

		return $rtn;

	}

	/************************************************************************************
	 * Country
	 */

	public function indexAction()
	{
		header("Location: " . Settings::$COUNTRY_BASE_URL . "/admin/country-settings");
		exit;
	}

	private function getSetting($field)
	{
		require_once('models/table/System.php');
		$sysTable = new System();
		return $sysTable->getSetting($field);
	}

	private function putSetting($field, $value)
	{
		require_once('models/table/System.php');
		$sysTable = new System();
		return $sysTable->putSetting($field, $value);
	}

	/*
	 * TA:17:11: 10/22/2014
	*/
	public function countryMonthlyEmailReportsAction(){

		require_once('models/table/System.php');
		$sysTable = new System();

		// _system settings
		$checkboxFields = array(
			'display_email_report_1'           => 'display_email_report_1',
			'display_email_report_2'      => 'display_email_report_2',
			'display_email_report_3'     => 'display_email_report_3',
		);

		// For "Labels"
		require_once('models/table/Translation.php');
		$labelNames = array( // input name => key_phrase
			'label_email_report_1'          => 'Label Email Report Level 1',
			'label_email_report_2'          => 'Label Email Report Level 2',
			'label_email_report_3'          => 'Label Email Report Level 3',
			'email_report_federal'          => 'Emails Report Level 1',
			'email_report_state'          => 'Emails Report Level 2',
			'email_report_lga'          => 'Emails Report Level 3',
		);

		if($this->getRequest()->isPost()) { // Update db
			// update translation labels
			$tranTable = new Translation();
			foreach($labelNames as $input_key => $db_key) {

				if ( $this->getParam($input_key) ) {
					try {
						$tranTable->update(
							array('phrase' => $this->getParam($input_key)),
							"key_phrase = '$db_key'"
						);
						$this->viewAssignEscaped($input_key, $this->getParam($input_key));
					} catch(Zend_Exception $e) {
						error_log($e);
					}
				}
			}

			// update _system (checkboxes)
			foreach($checkboxFields as $input_key => $db_field) {
				$value = ($this->getParam($input_key) == NULL) ? 0 : 1;
				$updateData[$db_field] = $value;
				$this->view->assign($input_key, $value);
				$sysTable->update($updateData, '');
			}
		}else{//view
			// labels
			$t = Translation::getAll();
			foreach($labelNames as $input_key => $db_key) {
				$this->viewAssignEscaped($input_key, $t[$db_key]);
			}

			// checkboxes
			$sysRows = $sysTable->fetchRow($sysTable->select()->limit(1));
			foreach($checkboxFields as $input_key => $field_key) {
				$this->view->assign($input_key, $sysRows->$field_key);
			}
		}
	}

	public function countrySettingsAction()
	{

		require_once('models/table/System.php');
		$sysTable = new System();

		// For "Labels"
		require_once('models/table/Translation.php');
		$labelNames = array( // input name => key_phrase
			'label_country'          => 'Country',
			'label_regiona'          => 'Region A (Province)',
			'label_regionb'          => 'Region B (Health District)',
			'label_regionc'          => 'Region C (Local Region)',
			'label_regiond'          => 'Region D',
			'label_regione'          => 'Region E',
			'label_regionf'          => 'Region F',
			'label_regiong'          => 'Region G',
			'label_regionh'          => 'Region H',
			'label_regioni'          => 'Region I',
			'label_citytown'         => 'City or Town',
			'label_application_name' => 'Application Name',
			'label_training'         => 'Training',
			'label_trainings'        => 'Trainings',
			'label_trainer'          => 'Trainer',
			'label_trainers'         => 'Trainers',
			'label_training_center'  => 'Training Center',
			'label_participant'      => 'Participant',
			'label_participants'     => 'Participants',
		);

		// _system settings
		$checkboxFields = array( // input name => db field
			'check_mod_eval'           => 'module_evaluation_enabled',
			'check_mod_approvals'      => 'module_approvals_enabled',
			'check_mod_historical'     => 'module_historical_data_enabled',
			'check_mod_unknown'        => 'module_unknown_participants_enabled',
			'check_mod_attendance'     => 'module_attendance_enabled',
			'display_training_partner' => 'display_training_partner',
			'display_mod_skillsmart'   => 'display_mod_skillsmart',
			'fiscal_year_start'        => 'fiscal_year_start',
			'check_mod_employee'       => 'module_employee_enabled',
			'check_country_reports' => 'display_country_reports',//TA:17: 9/11/2014
			'display_use_offline_app' => 'display_use_offline_app' //TA:50:1
		);


		if($this->getRequest()->isPost()) { // Update db
			$updateData = array();
			$country = $this->getParam('country');
			$this->putSetting('country', $country);

			// update translation labels
			$tranTable = new Translation();
			foreach($labelNames as $input_key => $db_key) {

				if ( $this->getParam($input_key) ) {
					try {
						$tranTable->update(
							array('phrase' => $this->getParam($input_key)),
							"key_phrase = '$db_key'"
						);
						$this->viewAssignEscaped($input_key, $this->getParam($input_key));
					} catch(Zend_Exception $e) {
						error_log($e);
					}
				}
			}

			// save locale
			$updateData['locale_enabled'] = implode(',', $this->getParam('locales'));
			if ( $this->getParam('locale_default') )
				$updateData['locale'] = $this->getParam('locale_default');

			// update _system (checkboxes)
			foreach($checkboxFields as $input_key => $db_field) {
				$value = ($this->getParam($input_key) == NULL) ? 0 : 1;
				$updateData[$db_field] = $value;
				$this->view->assign($input_key, $value);
			}
			$updateData['fiscal_year_start'] = $this->getParam('fiscal_year_start');
			$sysTable->update($updateData, '');

		} else { // view

			// labels
			$t = Translation::getAll();
			foreach($labelNames as $input_key => $db_key) {
				$this->viewAssignEscaped($input_key, $t[$db_key]);
			}

			// checkboxes
			$sysRows = $sysTable->fetchRow($sysTable->select()->limit(1));
			foreach($checkboxFields as $input_key => $field_key) {
				$this->view->assign($input_key, $sysRows->$field_key);
			}
		}

		// country
		$country = $this->getSetting('country');
		$this->view->assign('country', htmlspecialchars($country));

		// locale
		$this->view->assign('languages', ITechTranslate::getLanguages());
		$this->view->assign('locale', $this->getSetting('locale'));
		$this->view->assign('locale_enabled', ITechTranslate::getLocaleEnabled());

		// redirect to next page
		if($this->getParam('saveonly')) {
			$status = ValidationContainer::instance();
			$status->setStatusMessage(t('Your settings have been updated.'));
			//reload the page
			$this->_redirect('admin/country-settings');

		} else if($this->getParam('redirect')) {
			header("Location: " . $this->getParam('redirect'));
			exit;
		}

	}

	public function addRegionTopAction() {
		if ( $this->setting('num_location_tiers') < 9) {
			Location::addTier(1);
			require_once('models/table/System.php');
			$sysTable = new System();
			$upd = array();
			//add one region
			if (!$this->setting('display_region_b')) $upd = array('display_region_b' => 1);
			if ($this->setting('display_region_b')) $upd = array('display_region_c' => 1);
			if ($this->setting('display_region_c')) $upd = array('display_region_d' => 1);
			if ($this->setting('display_region_d')) $upd = array('display_region_e' => 1);
			if ($this->setting('display_region_e')) $upd = array('display_region_f' => 1);
			if ($this->setting('display_region_f')) $upd = array('display_region_g' => 1);
			if ($this->setting('display_region_g')) $upd = array('display_region_h' => 1);
			if ($this->setting('display_region_h')) $upd = array('display_region_i' => 1);
			if (! empty($upd))
				$sysTable->update($upd, '');
		}

		$this->_redirect('admin/country-settings');

	}

	public function addRegionBottomAction() {
		if ( $this->setting('num_location_tiers') < 9) {
			Location::addTier($this->setting('num_location_tiers'));
			require_once('models/table/System.php');
			$sysTable = new System();
			$upd = array();
			// add one region
			if (!$this->setting('display_region_b')) $upd = array('display_region_b' => 1);
			if ($this->setting('display_region_b')) $upd = array('display_region_c' => 1);
			if ($this->setting('display_region_c')) $upd = array('display_region_d' => 1);
			if ($this->setting('display_region_d')) $upd = array('display_region_e' => 1);
			if ($this->setting('display_region_e')) $upd = array('display_region_f' => 1);
			if ($this->setting('display_region_f')) $upd = array('display_region_g' => 1);
			if ($this->setting('display_region_g')) $upd = array('display_region_h' => 1);
			if ($this->setting('display_region_h')) $upd = array('display_region_i' => 1);
			if (! empty($upd))
				$sysTable->update($upd, '');
		}

		$this->_redirect('admin/country-settings');

	}

	public function deleteRegionAction() {
		if ( $this->setting('num_location_tiers') > 2) {
			Location::collapseTier($this->setting('num_location_tiers') - 1);
			require_once('models/table/System.php');
			$sysTable = new System();
			// turn off last region
			if ($this->setting('display_region_a')) $upd = array('display_region_a' => 0);
			if ($this->setting('display_region_b')) $upd = array('display_region_b' => 0);
			if ($this->setting('display_region_c')) $upd = array('display_region_c' => 0);
			if ($this->setting('display_region_d')) $upd = array('display_region_d' => 0);
			if ($this->setting('display_region_e')) $upd = array('display_region_e' => 0);
			if ($this->setting('display_region_f')) $upd = array('display_region_f' => 0);
			if ($this->setting('display_region_g')) $upd = array('display_region_g' => 0);
			if ($this->setting('display_region_h')) $upd = array('display_region_h' => 0);
			$sysTable->update($upd, '');
		}
		$this->_redirect('admin/country-settings');
	}
	public function countryDataShareAction()
	{
		$db = $this->dbfunc();
		$sites = new ITechTable(array('name' => 'datashare_sites'));
		$sitesArray = $sites->fetchAll();

		// form post - update data
		if ($this->getRequest()->isPost()) {
			// determine site
			$parts = explode('.', $_SERVER['SERVER_NAME']); // same style as globals.php
			$this_site = $GLOBALS->$COUNTRY ? $GLOBALS->$COUNTRY : $parts[0];

			$newPass = $this->getSanParam('site_pass');
			if ($newPass and $this->hasACL('edit_country_options')) { // new password for site - the theory behind pw is sites will only be able to add your site as a child or sibling site if they know your password
				$sites->update(array('site_password' => $newPass), array('db_name' => $this_site)); // $data, $where
			}
		}
		// assign form data
		$this->view->assign('sites', $sitesArray ? $sitesArray->toArray() : array());
	}

	public function trainingSettingsAction()
	{

		require_once('models/table/System.php');
		$sysTable = new System();

		// For "Labels"
		require_once('models/table/Translation.php');
		$labelNames = array( // input name => key_phrase
			'label_training_category' => 'Training Category',
			'label_training_topic'    => 'Training Topic',
			'label_training_name'     => 'Training Name',
			'label_training_org'      => 'Training Organizer',
			'label_training_level'    => 'Training Level',
			'label_training_got_curric' => 'GOT Curriculum',
			'label_training_got_comment' => 'GOT Comment',
			'label_training_refresher'  => 'Refresher Course',
			'label_training_comments'   => 'Training Comments',
			'label_pepfar'            => 'PEPFAR Category',
			'label_training_trainers' => 'Training of Trainers',
			'label_course_objectives' => 'Course Objectives',
			'label_training_pre'      => 'Pre Test Score',
			'label_training_post'     => 'Post Test Score',
			'label_training_custom1'  => 'Training Custom 1',
			'label_training_custom2'  => 'Training Custom 2',
			'label_training_custom3'     => 'Training Custom 3',
			'label_training_custom4'     => 'Training Custom 4',
			'label_training_method'  => 'Training Method',
			'label_training_funding_amt' => 'Funding Amount',
			'label_primary_language' => 'Primary Language',
			'label_secondary_language' => 'Secondary Language',
			'label_award'                => 'Award',
			'label_viewing_location'     => 'Viewing Location',
			'label_budget_code'          => 'Budget Code'
		);

		// _system settings
		$checkboxFields = array( // input name => db field
			'check_training_topic' => 'display_training_topic',
			'check_training_trainers' => 'display_training_trainers',
			'check_training_got_curric'  => 'display_training_got_curric',
			'check_training_got_comment' => 'display_training_got_comment',
			'check_training_pepfar'      => 'display_training_pepfar',
			'check_training_refresher'   => 'display_training_refresher',
			'check_multi_refresher'      => 'multi_opt_refresher_course',
			'check_course_objectives' => 'display_course_objectives',
			'check_training_pre'      => 'display_training_pre_test',
			'check_training_post'     => 'display_training_post_test',
		    'check_training_final_mark'     => 'display_training_final_mark',//TA:#313
			'check_training_custom1'  => 'display_training_custom1',
			'check_training_custom2'  => 'display_training_custom2',
			'check_training_custom3'     => 'display_training_custom3',
			'check_training_custom4'     => 'display_training_custom4',
			'check_training_method'  => 'display_training_method',
			'check_training_primary_language'  => 'display_primary_language',
			'check_training_secondary_language'  => 'display_secondary_language',
			'check_training_end_date' => 'display_end_date',
			'check_training_funding_options' => 'display_funding_options',
			'check_training_funding_amounts'     => 'display_funding_amounts',
			'check_display_viewing_location'     => 'display_viewing_location',
			'check_display_budget_code'          => 'display_budget_code',
			'check_training_category'          => 'display_training_category', //TA:17: 8/27/2014
			'check_training_start_date'          => 'display_training_start_date', //TA:17: 9/02/2014
			'check_training_length'          => 'display_training_length', //TA:17: 9/03/2014
			'check_training_level'          => 'display_training_level', //TA:17: 9/03/2014
			'check_training_comments'          => 'display_training_comments', //TA:17: 9/03/2014
			'check_facilitator_info' => 'display_facilitator_info',//TA:17: 9/03/2014
			'check_training_score' => 'display_training_score',//TA:17: 9/03/2014
			'check_training_location'          => 'display_training_location', //TA:17:14 11/20/2014
		    'check_training_pt_pass' => 'display_training_pt_pass',//TA:#271
		);

		if($this->getRequest()->isPost()) { // Update db
			$updateData = array();

			// update translation labels
			$tranTable = new Translation();
			foreach($labelNames as $input_key => $db_key) {

				if ( $this->getParam($input_key) ) {
					try {
						$tranTable->update(
							array('phrase' => $this->getParam($input_key)),
							"key_phrase = '$db_key'"
						);
						$this->viewAssignEscaped($input_key, $this->getParam($input_key));
					} catch(Zend_Exception $e) {
						error_log($e);
					}
				}
			}

			// update _system (checkboxes)
			foreach($checkboxFields as $input_key => $db_field) {
				$value = ($this->getParam($input_key) == NULL) ? 0 : 1;
				$updateData[$db_field] = $value;
				$this->view->assign($input_key, $value);
			}
			$sysTable->update($updateData, '');

		} else { // view

			// labels
			$t = Translation::getAll();
			foreach($labelNames as $input_key => $db_key) {
				$this->viewAssignEscaped($input_key, $t[$db_key]);
			}

			// checkboxes
			$sysRows = $sysTable->fetchRow($sysTable->select()->limit(1));
			foreach($checkboxFields as $input_key => $field_key) {
				$this->view->assign($input_key, $sysRows->$field_key);
			}
		}

		// redirect to next page
		if($this->getParam('redirect')) {
			header("Location: " . $this->getParam('redirect'));
			exit;
		} else if($this->getParam('saveonly')) {
			$status = ValidationContainer::instance();
			$status->setStatusMessage(t('Your settings have been updated.'));
		}

	}

	public function countryRegionAction()
	{
		require_once('models/table/Location.php');

		//CSV STUFF

		if(@$_FILES['import']['tmp_name']) {
			$filename = ($_FILES['import']['tmp_name']);
			if ( $filename ) {
				$location_obj = new ITechTable(array('name' => 'location'));
				while ($row = $this->_csv_get_row($filename) ) {
				    //TA:#213
			    //INFORCE user to create files only in UTF-8 encoded:
			    //Option 1: Excel:Save as Unicode Text -> Notepad: replace tabs with commas, save as csv UTF-8
			    //Option 2: OpenOffice
			    //It is not required for english, but absolutelly required for special characteristics.
			    //If files saved in UTF-8 encoded, so we do not need this line
			    // $row = array_map("utf8_encode", $row); 
					if ( is_array($row) ) {
						//add province
						if ( isset($row[0] ) ) {
							$prov_id = $location_obj->insertUnique('location_name',$row[0], true, 'tier',1);
						}
						//add city (basically offset all our if(display_region) by 1, because city does not have a display_city setting
						if ( isset($row[1] ) ) {
							$dist_id = $location_obj->insertUnique('location_name',$row[1],true,'parent_id',$prov_id, 'tier',2);
						}
						//add district
						if ( isset($row[2] ) && $this->setting('display_region_b') ) {
							$dist_id = $location_obj->insertUnique('location_name',$row[2],true,'parent_id',$dist_id, 'tier',3);
						}
						//add region c
						if ( isset($row[3] ) && $this->setting('display_region_c')  ) {
							$dist_id = $location_obj->insertUnique('location_name',$row[3],true,'parent_id',$dist_id, 'tier',4);
						}
						if ( isset($row[4] ) && $this->setting('display_region_d')  ) {
							$dist_id = $location_obj->insertUnique('location_name',$row[4],true,'parent_id',$dist_id, 'tier',5);
						}
						if ( isset($row[5] ) && $this->setting('display_region_e')  ) {
							$dist_id = $location_obj->insertUnique('location_name',$row[5],true,'parent_id',$dist_id, 'tier',6);
						}
						if ( isset($row[6] ) && $this->setting('display_region_f')  ) {
							$dist_id = $location_obj->insertUnique('location_name',$row[6],true,'parent_id',$dist_id, 'tier',7);
						}
						if ( isset($row[7] ) && $this->setting('display_region_g')  ) {
							$dist_id = $location_obj->insertUnique('location_name',$row[7],true,'parent_id',$dist_id, 'tier',8);
						}
						if ( isset($row[8] ) && $this->setting('display_region_h')  ) {
							$dist_id = $location_obj->insertUnique('location_name',$row[8],true,'parent_id',$dist_id, 'tier',9);
						}
						if ( isset($row[9] ) && $this->setting('display_region_i')  ) {
							$dist_id = $location_obj->insertUnique('location_name',$row[9],true,'parent_id',$dist_id, 'tier',10);
						}
					}
				}
				if( isset($dist_id) || isset($prov_id) )
					$_SESSION['status'] = t ('Your changes have been saved.');
			}
			//kinda ugly, but $this->_setParam doesn't do it
			$_POST['redirect'] = null;

		}


		$location_id = $this->getSanParam('location');
		$tier = $this->getSanParam('tier');
		if (!$tier) $tier = 1;
		$this->view->assign('tier', $tier);
		if ( $tier == 1 ) {
			$location_id = null;
		}

		$locations = Location::getAll();
		$this->viewAssignEscaped('locations',$locations);
		//assign district and region filters
		$loc_parts = explode('_',$location_id);
		$location_id = $loc_parts[count($loc_parts) - 1];

		//defaults

		if ( $location_id ) {
			// viewing a location/children
			if ( count($loc_parts) ) {
				$this->view->assign('province_id', $loc_parts[0]);
				$this->view->assign('district_id', $loc_parts[1]);
				$this->view->assign('region_c_id', $loc_parts[2]);
				$this->view->assign('region_d_id', $loc_parts[3]);
				$this->view->assign('region_e_id', $loc_parts[4]);
				$this->view->assign('region_f_id', $loc_parts[5]);
				$this->view->assign('region_g_id', $loc_parts[6]);
				$this->view->assign('region_h_id', $loc_parts[7]);
				$this->view->assign('region_i_id', $loc_parts[8]);
				$this->view->assign('city_id', $loc_parts[9]);
			}
			else {
				$loc_parts = null;
			}
		}

		// handy fields for translation... tr( $name[$tier] )
		$name = array(''); // prepend 1 for readability, tiers start at 1
		$flds = array(''); // always show // this form has to be dynamic becasue city tier is num_tiers - 1

		$name[] = 'Region A (Province)';
		$flds[] = 'province_id';
		if($this->setting('display_region_b')){ $name[] = 'Region B (Health District)'; $flds[] = 'district_id'; }
		if($this->setting('display_region_c')){ $name[] = 'Region C (Local Region)';    $flds[] = 'region_c_id'; }
		if($this->setting('display_region_d')){ $name[] = 'Region D'; $flds[] = 'region_d_id'; }
		if($this->setting('display_region_e')){ $name[] = 'Region E'; $flds[] = 'region_e_id'; }
		if($this->setting('display_region_f')){ $name[] = 'Region F'; $flds[] = 'region_f_id'; }
		if($this->setting('display_region_g')){ $name[] = 'Region G'; $flds[] = 'region_g_id'; }
		if($this->setting('display_region_h')){ $name[] = 'Region H'; $flds[] = 'region_h_id'; }
		if($this->setting('display_region_i')){ $name[] = 'Region I'; $flds[] = 'region_i_id'; }
		$name[] = 'City or Town';  // always show
		$flds[] = 'city_id';
		$flds[] = '';

		// make the edit table
		if ( ($tier == 1) OR ($location_id && ($locations[$location_id]['tier'] + 1 == $tier))) {
		    
			$controller = &$this;  //gnr, Call-time pass-by-reference has been removed
	        $editTable = new EditTableController($controller->getRequest(), $controller->getResponse());
	        $editTable->setParentController($controller);
	        
			$editTable->table   = 'location';
			$editTable->fields  = array('location_name' => $this->tr($name[$tier]));
			$editTable->label   = $this->tr($name[$tier]);
			$editTable->dependencies = array(
				'parent_id' => 'self',
				'location_id' => 'training_location',
				'home_location_id' => 'person',
				'location_id' => 'facility',
			);
			$editTable->where = 'tier = '.$tier.($location_id?' AND parent_id = '.$location_id:' ');
			if ( $location_id )
				$editTable->insertExtra = array('parent_id'=>$location_id, 'tier'=>$tier);
			else
				$editTable->insertExtra = array('tier'=>1);

			$editTable->allowDefault = true;
			$editTable->noEdit  = false;
			$editTable->execute($controller->getRequest());
		}
	}

	public function countryRegionMoveAction()
	{
		require_once('models/table/Location.php');

		//  $this->viewAssignEscaped('criteria',$criteria);
		$location_id = $this->getSanParam('location');
		$tier = $this->getSanParam('tier');
		if (!$tier) $tier = 1;
		$this->view->assign('tier', $tier);
		if ( $tier == 1 ) {
			$location_id = null;
		}

		$locations = Location::getAll();

		//assign district and region filters
		//strip off leading ids
		$loc_parts = explode('_',$location_id);
		$location_id = $loc_parts[count($loc_parts) - 1];
		$this->view->assign('location_id',$location_id);
		$this->view->assign('pageTitle', t('Move Regions'));

		if ( $location_id ) {
			if ( count($loc_parts) ) {
				$this->view->assign('province_id', $loc_parts[0]);
				$this->view->assign('district_id', $loc_parts[1]);
				$this->view->assign('region_c_id', $loc_parts[2]);
				$this->view->assign('region_d_id', $loc_parts[3]);
				$this->view->assign('region_e_id', $loc_parts[4]);
				$this->view->assign('region_f_id', $loc_parts[5]);
				$this->view->assign('region_g_id', $loc_parts[6]);
				$this->view->assign('region_h_id', $loc_parts[7]);
				$this->view->assign('region_i_id', $loc_parts[8]);
				$this->view->assign('city_id', $loc_parts[9]);
			}
			else {
				$loc_parts = null;
			}

			if ( $this->getRequest()->isPost() && $this->getSanParam('move')) {
				$target = null;
				switch($tier) {
					case 2:
						$target = $this->getSanParam('target_province_id');
						break;
					case 3:
						$target = $this->getSanParam('target_district_id');
						break;
					case 4:
						$target = $this->getSanParam('target_region_c_id');
						break;
					case 5:
						$target = $this->getSanParam('target_region_d_id');
						break;
					case 6:
						$target = $this->getSanParam('target_region_e_id');
						break;
					case 7:
						$target = $this->getSanParam('target_region_f_id');
						break;
					case 8:
						$target = $this->getSanParam('target_region_g_id');
						break;
					case 9:
						$target = $this->getSanParam('target_region_h_id');
						break;
					case 10:
						$target = $this->getSanParam('target_region_i_id');
						break;
				}
				$target_parts = explode('_',$target);
				$target_id = $target_parts[count($target_parts) - 1];

				if ( $target_id && ($target_id != $location_id)) {
					foreach($this->getSanParam('move') as $loc) {
						Location::moveLocation($loc, $target_id);
					}
				}

				//reload locations
				$locations = Location::getAll();

			}

			//get locations
			$candidates = array();
			foreach($locations as $l) {
				if ( $l['parent_id'] == $location_id )
					$candidates []= $l;
			}

			$this->viewAssignEscaped('candidates', $candidates);
		}

		$this->viewAssignEscaped('locations',$locations);

	}

	public function facilitiesSettingsAction()
	{

		require_once('models/table/System.php');
		$sysTable = new System();

		// For "Labels"
		require_once('models/table/Translation.php');
		$labelNames = array( // input name => key_phrase
			'label_facility'         => 'Facility',
			'label_comments'         => 'Facility Comments',
			'label_sponsor_date'     => 'Sponsor Date',
			'label_facility_custom1'    => 'Facility Custom 1',
			//TA:17: 10/02/2014
			'label_facility_commodity_table_col_name'         => 'Facility Commodity Column Table Commodity Name',
			'label_facility_commodity_table_col_date'         => 'Facility Commodity Column Table Date',
			'label_facility_commodity_table_col_consumption'         => 'Facility Commodity Column Table Consumption',
			'label_facility_commodity_table_col_outofstock'         => 'Facility Commodity Column Table Out of Stock',
			///
		);
		$checkboxFields = array(
			'check_approval_mod'     => 'module_facility_approval',
		    'check_education_mod'     => 'module_people_education',//TA:#331.1
			'check_multi_sponsors'   => 'allow_multi_sponsors',
			'check_display_dates'    => 'display_sponsor_dates',
			'check_require_dates'    => 'require_sponsor_dates',
			'check_display_lat_long' => 'display_facility_lat_long',
			'check_display_postal'   => 'display_facility_postal_code',
			'check_display_sponsor'  => 'display_facility_sponsor',
			'check_facility_custom1'  => 'display_facility_custom1',
			'check_facility_address' => 'display_facility_address', //TA:17: 9/03/2014
			'check_facility_phone' => 'display_facility_phone', //TA:17: 9/03/2014
			'check_facility_fax' => 'display_facility_fax', //TA:17: 9/03/2014
			'check_facility_comments' => 'display_facility_comments', //TA:17: 9/03/2014
			'check_facility_type' => 'display_facility_type', //TA:17: 9/03/2014
			'check_facility_city' => 'display_facility_city', //TA:17: 9/04/2014
			'check_facility_commodity' => 'display_facility_commodity', //TA:17: 10/02/2014
		);

		if($this->getRequest()->isPost()) { // Update db
			$updateData = array();

			// update translation labels
			$tranTable = new Translation();
			foreach($labelNames as $input_key => $db_key) {

				if ( $this->getParam($input_key) ) {
					try {
						$tranTable->update(
							array('phrase' => $this->getParam($input_key)),
							"key_phrase = '$db_key'"
						);
						$this->viewAssignEscaped($input_key, $this->getParam($input_key));
					} catch(Zend_Exception $e) {
						error_log($e);
					}
				}
			}
			// update _system (checkboxes)
			foreach($checkboxFields as $input_key => $db_field) {
				$value = ($this->getParam($input_key) == NULL) ? 0 : 1;
				$updateData[$db_field] = $value;
				$this->view->assign($input_key, $value);
			}
			if($updateData)
				$sysTable->update($updateData, '');

		} else { // view
			// checkboxes
			$sysRows = $sysTable->fetchRow($sysTable->select()->limit(1));
			foreach($checkboxFields as $input_key => $field_key) {
				if ( isset($sysRows->$field_key) )
					$this->view->assign($input_key, $sysRows->$field_key);
			}
			// labels
			$t = Translation::getAll();
			foreach($labelNames as $input_key => $db_key) {
				$this->viewAssignEscaped($input_key, $t[$db_key]);
			}

		}

		// redirect to next page
		if($this->getParam('redirect')) {
			header("Location: " . $this->getParam('redirect'));
			exit;
		} else if($this->getParam('saveonly')) {
			$status = ValidationContainer::instance();
			$status->setStatusMessage(t('Your settings have been updated.'));
		}
	}

	public function peopleSettingsAction()
	{

		require_once('models/table/System.php');
		$sysTable = new System();

		// For "Labels"
		require_once('models/table/Translation.php');
		$labelNames = array( // input name => key_phrase
			'label_people_active'   => 'Is Active',
			'label_people_title'   => 'Title',
			'label_people_first'   => 'First Name',
			'label_people_middle'   => 'Middle Name',
			'label_people_last'   => 'Last Name',
			'label_people_suffix'   => 'Suffix',
			'label_people_national'   => 'National ID',
			'label_people_file_num'    => 'File Number',
			'label_people_age'   => 'Age',
			'label_people_gender'   => 'Gender',
			'label_people_custom1'    => 'People Custom 1',
			'label_people_custom2'    => 'People Custom 2',
			'label_people_custom3'            => 'People Custom 3',
			'label_people_custom4'            => 'People Custom 4',
			'label_people_custom5'            => 'People Custom 5',
			'label_responsibility_me'    => 'M&E Responsibility',
			'label_highest_ed_level'    => 'Highest Education Level',
			'label_attend_reason'    => 'Reason Attending',
			'label_primary_responsibility'    => 'Primary Responsibility',
			'label_secondary_responsibility'    => 'Secondary Responsibility',
			'label_comments'    => 'Qualification Comments',
			'label_address1'		=> 'Address 1',
			'label_address2'		=> 'Address 2',
			'label_home_phone'  => 'Home phone',
		    'label_education_history'  => 'Education History',//TA:#331.1
		    'label_type_of_education'  => 'Type of Education',//TA:#331.1
		    'label_official_school_name'  => 'Official School Name',//TA:#331.1
		    'label_education_country'  => 'Education Country',//TA:#331.1
		    'label_year_graduation'  => 'Year of Graduation/Completion',//TA:#331.1
		);

		// _system settings
		$checkboxFields = array( // input name => db field
			'check_people_title'   => 'display_people_title',
			'check_people_active'   => 'display_people_active',
			'check_people_suffix'   => 'display_people_suffix',
			'check_people_national'   => 'display_national_id',
			'check_people_middle' => 'display_middle_name',
			'check_middle_last'   => 'display_middle_name_last',
			'check_people_gender'     => 'display_gender',
			'check_people_custom1'    => 'display_people_custom1',
			'check_people_custom2'    => 'display_people_custom2',
			'check_people_custom3'    => 'display_people_custom3',
			'check_people_custom4'    => 'display_people_custom4',
			'check_people_custom5'    => 'display_people_custom5',
			//      'check_regionb'     => 'display_region_b',
			'check_people_file_num'    => 'display_people_file_num',
			'check_people_age'    => 'display_people_age',
			'check_people_home_address'    => 'display_people_home_address',
			'check_people_home_phone'  => 'display_people_home_phone',
			'check_people_second_email'  => 'display_people_second_email',
			'check_people_fax'         => 'display_people_fax',
			'check_trainer_affiliations' => 'display_trainer_affiliations',
			'check_responsibility_me'    => 'display_responsibility_me',
			'check_highest_ed_level'    => 'display_highest_ed_level',
			'check_attend_reason'    => 'display_attend_reason',
			'check_external_classes'  => 'display_external_classes',
			'check_primary_responsibility'  => 'display_primary_responsibility',
			'check_secondary_responsibility'  => 'display_secondary_responsibility',
			'check_approval_mod'              => 'module_person_approval',
		    'check_education_mod'              => 'module_people_education',//TA:#331.1
			'check_people_comments'	=> 'display_people_comments', //TA:17: 09/09/2014
			'check_people_facilitator' => 'display_people_facilitator', //TA:17: 09/09/2014
			'check_people_birthdate' => 'display_people_birthdate', //TA:17: 09/10/2014
		);

		if($this->getRequest()->isPost()) { // Update db
			$updateData = array();

			// update translation labels
			$tranTable = new Translation();
			foreach($labelNames as $input_key => $db_key) {
			    if ( $this->getParam($input_key) ) {
					try {
						$tranTable->update(
							array('phrase' => $this->getParam($input_key)),
							"key_phrase = '$db_key'"
						);
						$this->viewAssignEscaped($input_key, $this->getParam($input_key));
					} catch(Zend_Exception $e) {
						error_log($e);
					}
				}
			}

			// update _system (checkboxes)
			foreach($checkboxFields as $input_key => $db_field) {
				$value = ($this->getParam($input_key) == NULL) ? 0 : 1;
				$updateData[$db_field] = $value;
				$this->view->assign($input_key, $value);
			}
			$sysTable->update($updateData, '');

		} else { // view

			// labels
			$t = Translation::getAll();
			foreach($labelNames as $input_key => $db_key) {
				$this->viewAssignEscaped($input_key, $t[$db_key]);
			}

			// checkboxes
			$sysRows = $sysTable->fetchRow($sysTable->select()->limit(1));
			foreach($checkboxFields as $input_key => $field_key) {
				$this->view->assign($input_key, $sysRows->$field_key);
			}
		}

		// redirect to next page
		if($this->getParam('redirect')) {
			header("Location: " . $this->getParam('redirect'));
			exit;
		} else if($this->getParam('saveonly')) {
			$status = ValidationContainer::instance();
			$status->setStatusMessage(t('Your settings have been updated.'));
		}

	}
	/************************************************************************************
	 * Training
	 */

	public function trainingCategoryAction()
	{
		$controller = &$this;  //gnr, Call-time pass-by-reference has been removed and EditTableController constructor signature must match Zend_Controller_Action_Interface
		$editTable = new EditTableController($controller->getRequest(), $controller->getResponse());  
		$editTable->setParentController($controller);
		$editTable->table   = 'training_category_option';
		$editTable->fields  = array('training_category_phrase' => t('Training Category'));
		$editTable->label   = t('Training Category');
		$editTable->execute($controller->getRequest());
	}

	public function trainingTitleAction()
	{
		$controller = &$this;
		$editTable = new EditTableController($controller->getRequest(), $controller->getResponse());  
		$editTable->setParentController($controller);
		$editTable->table   = 'training_title_option';
		$editTable->fields  = array('training_title_phrase' => t('Training Title'));
		$editTable->label   = t('Training Title');
		$editTable->allowMerge = true;
		$editTable->dependencies = array('training');
		$editTable->execute($controller->getRequest());
	}

	public function trainingOrganizerAction()
	{
		$controller = &$this;
		$editTable = new EditTableController($controller->getRequest(), $controller->getResponse());  
		$editTable->setParentController($controller);
		$editTable->table   = 'training_organizer_option';
		$editTable->fields  = array('training_organizer_phrase' => t('Training Organizer'));
		$editTable->label   = t('Training Organizer');
		$editTable->dependencies = array('training');
		$editTable->allowDefault = true;
		$editTable->execute($controller->getRequest());
	}

	public function trainingLevelAction()
	{
		$controller = &$this;
		$editTable = new EditTableController($controller->getRequest(), $controller->getResponse());  
		$editTable->setParentController($controller);
		$editTable->table   = 'training_level_option';
		$editTable->fields  = array('training_level_phrase' => t('Training Level'));
		$editTable->label   = t('Training Level');
		$editTable->dependencies = array('training');
		$editTable->allowDefault = true;
		$editTable->execute($controller->getRequest());
	}

	public function trainingTopicAction()
	{
		/* checkbox */
		$fieldSystem = 'allow_multi_topic';

		if($this->getRequest()->isPost() && !$this->getParam("id")) { // Update db
			$this->putSetting($fieldSystem, $this->getParam($fieldSystem));
		}

		$checkbox = array(
			'name'  => $fieldSystem,
			'label' => t('Allow multiple Training topics'),
			'value' => $this->getSetting($fieldSystem),
		);
		$this->view->assign('checkbox', $checkbox);

		$controller = &$this;
		$editTable = new EditTableController($controller->getRequest(), $controller->getResponse());  
		$editTable->setParentController($controller);
		$editTable->table   = 'training_topic_option';
		$editTable->fields  = array('training_topic_phrase' => t('Training Topic'));
		$editTable->label   = t('Training Topic');
		$editTable->dependencies = array('training_to_training_topic_option');
		$editTable->allowDefault = true;
		$editTable->execute($controller->getRequest());
	}

	public function trainingPepfarAction()
	{
		/* checkbox */
		$fieldSystem = 'allow_multi_pepfar';

		if($this->getRequest()->isPost() && !$this->getParam("id")) { // Update db
			$this->putSetting($fieldSystem, $this->getParam($fieldSystem));
		}

		$checkbox = array(
			'name'  => $fieldSystem,
			'label' => t('Allow multiple PEPFAR categories'),
			'value' => $this->getSetting($fieldSystem),
		);
		$this->view->assign('checkbox', $checkbox);

		/* edit table */
		$controller = &$this;
		$editTable = new EditTableController($controller->getRequest(), $controller->getResponse());  
		$editTable->setParentController($controller);
		$editTable->table   = 'training_pepfar_categories_option';
		$editTable->fields  = array('pepfar_category_phrase' => t('PEPFAR Category'));
		$editTable->label   = t('PEPFAR Category');
		$editTable->dependencies = array('training_to_training_pepfar_categories_option');
		$editTable->allowDefault = true;
		$editTable->execute($controller->getRequest());
	}

	public function trainingFundingAction()
	{
		/* checkbox */
		$fieldSystem = 'display_funding_options';

		if($this->getRequest()->isPost() && !$this->getParam("id")) { // Update db
			$this->putSetting($fieldSystem, $this->getParam($fieldSystem));
		}

		/* edit table */
		$controller = &$this;
		$editTable = new EditTableController($controller->getRequest(), $controller->getResponse());  
		$editTable->setParentController($controller);
		$editTable->table   = 'training_funding_option';
		$editTable->fields  = array('funding_phrase' => t('Funding'));
		$editTable->label   = t('Funding');
		$editTable->dependencies = array('training_to_training_funding_option');
		$editTable->allowDefault = true;
		$editTable->execute($controller->getRequest());
	}

	public function trainingRefreshercourseAction()
	{
		$controller = &$this;
		$editTable = new EditTableController($controller->getRequest(), $controller->getResponse());  
		$editTable->setParentController($controller);
		$editTable->table   = 'training_refresher_option';
		$editTable->fields  = array('refresher_phrase_option' => t('Refresher Course'));
		$editTable->label   = t('Refresher Course');
		$editTable->dependencies = array('training');
		$editTable->execute($controller->getRequest());
	}

	public function trainingGotcurriculumAction()
	{
		/* edit table */
		$controller = &$this;
		$editTable = new EditTableController($controller->getRequest(), $controller->getResponse());  
		$editTable->setParentController($controller);
		$editTable->table   = 'training_got_curriculum_option';
		$editTable->fields  = array('training_got_curriculum_phrase' => t('National Curriculum'));
		$editTable->label   = t('National Curriculum');
		$editTable->dependencies = array('training');
		$editTable->execute($controller->getRequest());
	}

	public function trainingMethodAction()
	{
		/* edit table */
		$controller = &$this;
		$editTable = new EditTableController($controller->getRequest(), $controller->getResponse());  
		$editTable->setParentController($controller);
		$editTable->table   = 'training_method_option';
		$editTable->fields  = array('training_method_phrase' => t('Method'));
		$editTable->label   = t('Training Methods');
		$editTable->dependencies = array('training_to_training_pepfar_categories_option');
		$editTable->execute($controller->getRequest());
	}

	public function trainingRecommendAction()
	{
		$NUM_TOPICS = 20;
		$this->view->assign('NUM_TOPICS', $NUM_TOPICS);

		/* checkbox */
		$fieldSystem = 'display_training_recommend';

		if($this->getRequest()->isPost() && !$this->getParam("id")) { // Update db
			$this->putSetting($fieldSystem, $this->getParam($fieldSystem));
		}

		$checkbox = array(
			'name'  => $fieldSystem,
			'label' => t('Display recommended trainings per individual'),
			'value' => $this->getSetting($fieldSystem),
		);
		$this->view->assign('checkbox', $checkbox);

		require_once('models/table/TrainingRecommend.php');

		// Save POST
		if($this->getRequest()->isPost()) { // Update db
			if(is_numeric($this->getParam('person_qualification_option_id'))) {
				TrainingRecommend::saveRecommendations(
					$this->getParam('person_qualification_option_id'),
					$this->getParam('training_topic_option_id')
				);

				// Remove current, then redirect to clean page
				if($this->getParam('edit') && $this->getParam('edit') != $this->getParam('person_qualification_option_id')) {
					TrainingRecommend::saveRecommendations($this->getParam('edit'), array());
					header("Location: " . Settings::$COUNTRY_BASE_URL . '/admin/training-recommend');
					exit;
				}
			}

			// redirect to next page
			if($this->getParam('redirect')) {
				header("Location: " . $this->getParam('redirect'));
				exit;
			} else if($this->getParam('saveonly')) {
				$status = ValidationContainer::instance();
				$status->setStatusMessage('Your recommended trainings have been saved.');
			}
		}

		// Edting
		if($this->getParam('edit') || $this->getParam('edit') === '0' ) {
			$qualId = $this->getParam('edit');
			$topicId = array_fill(1, $NUM_TOPICS, '');
			$topics = TrainingRecommend::getRecommendations($this->getParam('edit'));
			$pos = 0;
			foreach($topics->ToArray() as $row) {
				$topicId[++$pos] = $row['training_topic_option_id'];
			}
		} else { // New
			$qualId = 0;
			$topicId = array_fill(1, $NUM_TOPICS, '');
		}

		// Delete
		if($delete = $this->getParam('delete')) {
			TrainingRecommend::saveRecommendations($this->getParam('delete'), array());
		}

		require_once 'views/helpers/DropDown.php';
		require_once 'models/table/OptionList.php';
		//$allowIds = TrainingRecommend::getQualificationIds(); // primary qualifications only
		//$this->view->assign('dropDownQuals', DropDown::generateHtml('person_qualification_option','qualification_phrase',$qualId, false, false, $allowIds));

		$qualificationsArray = OptionList::suggestionListHierarchical('person_qualification_option','qualification_phrase',false,false);
		// remove children qualifications and unknown as an option
		foreach($qualificationsArray as $k => $qualArray) {
			if  ($qualArray['id'] || $qualArray['parent_phrase'] == 'unknown') {
				unset($qualificationsArray[$k]);
			}
		}
		$this->viewAssignEscaped('qualifications',$qualificationsArray);
		$this->viewAssignEscaped('qualId',$qualId);

		for($j = 1; $j <= $NUM_TOPICS; $j++) {
			$this->view->assign('dropDownTopic' . $j, DropDown::generateHtml('training_topic_option','training_topic_phrase',$topicId[$j], false, false, false, true));
		}

	}

	public function trainingApproversAction()
	{
		// ajax handler
		if($this->getRequest()->isPost() && $this->getSanParam('ajax') ) { // Update db
			$table = new ITechTable(array('name' => 'user_to_acl'));
			$msg = '';
			$success = false;
			$proceed = true;

			$id = $this->getSanParam('id');
			if (! trim($id) || ! is_numeric($id) )
				$proceed = false;

			if($this->getSanParam('ajaxAction') == 'elevate' && $proceed)
			{
				$user_acl = $table->createRow();
				$user_acl->acl_id  = 'master_approver';
				$user_acl->user_id = $id;
				$user_acl = $user_acl->save();
				$msg = ($user_acl) ? t('That user is now a master approver') : t('Unable to make that user a master approver');
				if ($user_acl) $success = true;
			}
			if($this->getSanParam('ajaxAction') == 'deelevate' && $proceed)
			{
				$user_acl = $table->delete( "acl_id = 'master_approver' and user_id = $id" );
				$msg = ($user_acl) ? t('That user is now a regular approver') : t('Unable to remove that user as a master approver');
				if ($user_acl) $success = true;
			}
			if($this->getSanParam('ajaxAction') == 'remove' && $proceed)
			{
				$user_acl = $table->delete( "acl_id = 'approve_trainings' and user_id = $id" );
				$msg = ($user_acl) ? t('That user is no longer an approver') : t('Unable to delete that approver');
				if ($user_acl) $success = true;
			}
			// done
			$_SESSION['status'] = $msg;
			$this->setNoRenderer ();
			$output = array( 'success'=> $success, 'msg'=> $msg );
			echo json_encode($output);
			exit(); // no view now
		}

		require_once('models/table/System.php');
		require_once('models/table/Translation.php');
		$sysTable = new System();
		$labelNames = array(); // input name => key_phrase (changes translation table)
		$checkboxFields = array('master_approver' =>   'allow_multi_approvers'); // field => key phrase (changes _system table)

		// edit table & data
		require_once('views/helpers/EditTableHelper.php');
		$db = Zend_Db_Table_Abstract::getDefaultAdapter();
		$hideMasterLinks = false;
		$noDelete = array();
		$fieldDefs = array('fullname' => t('Name'));
		$fieldDefs['approver'] = t('Approver');
		if( $this->getSanParam('master_approver') || $this->setting('allow_multi_approvers') ) {
			$fieldDefs['master_approver'] = t('Master Approver');
			$hideMasterLinks = true;
		}
		$fieldDefs['lnks'] = t('Actions');

		$rows = $db->fetchAll ("select *,
			CONCAT(first_name, CONCAT(' ', last_name)) as fullname, '".t('Yes')."' as approver, m1.id as master_approver, user.id as id
			from user
			inner join user_to_acl acl on (acl.user_id = user.id and acl.acl_id = 'approve_trainings')
			left join  user_to_acl m1 on (m1.user_id = user.id and m1.acl_id = 'master_approver')
			where user.is_blocked = 0 limit 100");
		foreach ($rows as $i => $row){ // lets add some data to the resultset to show in the EditTable
			$noDelete[] = $row['id'];  // add to nodelete array
			$rows[$i]['fullname'] = htmlspecialchars( ucwords($rows[$i]['fullname']), ENT_QUOTES ); // format name
			if( empty($rows[$i]['master_approver']) ) {
				$rows[$i]['master_approver'] = t('No');  // master approver?
				$rows[$i]['lnks'] = "<a href='#' onclick='ajaxApprover(\"remove\", {$row['id']});return false'>".t('Remove')."</a>"; // links
				if($hideMasterLinks) $rows[$i]['lnks'] = " <a href='#' onclick='ajaxApprover(\"elevate\", {$row['id']});return false'>".t('Make Master').'</a>';
			}else {
				$rows[$i]['master_approver'] = t('Yes');	// is approver?
				if($hideMasterLinks) $rows[$i]['lnks'] = "<a href='#' onclick='ajaxApprover(\"deelevate\", {$row['id']});return false'>".t('Make Low Level Approver').'</a>';
				else $rows[$i]['lnks'] = "<a href='#' onclick='ajaxApprover(\"remove\", {$row['id']});return false'>".t('Remove')."</a>"; // same as first 'remove' link above
			}
		}
		// print a edit table
		$html = EditTableHelper::generateHtml('Approvers', $rows, $fieldDefs, array(), $noDelete, true); // array(1) and select 1 as id = bugfix: remove delete col
		$this->view->assign('editTable', $html);
		// done


		// process form (copied from other pages)
		if($this->getRequest()->isPost()) { // Update db
			$updateData = array();

			// update translation labels
			$tranTable = new Translation();
			foreach($labelNames as $input_key => $db_key) {

				if ( $this->getParam($input_key) ) {
					try {
						$tranTable->update(
							array('phrase' => $this->getParam($input_key)),
							"key_phrase = '$db_key'"
						);
						$this->viewAssignEscaped($input_key, $this->getParam($input_key));
					} catch(Zend_Exception $e) {
						error_log($e);
					}
				}
			}
			// update _system (checkboxes)
			foreach($checkboxFields as $input_key => $db_field) {
				$value = ($this->getParam($input_key) == NULL) ? 0 : 1;
				$updateData[$db_field] = $value;
				$this->view->assign($input_key, $value);
			}
			$sysTable->update($updateData, '');

		} else { // view
			// checkboxes
			$sysRows = $sysTable->fetchRow($sysTable->select()->limit(1));
			foreach($checkboxFields as $input_key => $field_key) {
				if ( isset($sysRows->$field_key) )
					$this->view->assign($input_key, $sysRows->$field_key);
			}
			// labels
			$t = Translation::getAll();
			foreach($labelNames as $input_key => $db_key) {
				$this->viewAssignEscaped($input_key, $t[$db_key]);
			}

		}

		// redirect to next page
		if($this->getParam('redirect')) {
			header("Location: " . $this->getParam('redirect'));
			exit;
		} else if($this->getParam('saveonly')) {
			$status = ValidationContainer::instance();
			$status->setStatusMessage(t('Your settings have been updated.'));
		}

	}

	public function trainingAssignTitleAction()
	{

		require_once('views/helpers/MultiAssign.php');

		$multiAssign = new multiAssign();
		$multiAssign->table = 'training_category_option_to_training_title_option';

		$multiAssign->option_table = 'training_title_option';
		$multiAssign->option_field = array('training_title_phrase' => t('Title'));

		$multiAssign->parent_table = 'training_category_option';
		$multiAssign->parent_field = array('training_category_phrase' => t('Training Category'));

		$output = $multiAssign->init($this);
		if(is_array($output)) { // json
			$this->sendData($output);
		} else {
			$this->view->assign('multiAssign', $output);
		}

		if($this->getRequest()->isPost()) { // Redirect
			// redirect to next page
			if($this->getParam('redirect')) {
				header("Location: " . $this->getParam('redirect'));
				exit;
			} else if($this->getParam('saveonly')) {
				$status = ValidationContainer::instance();
				$status->setStatusMessage('Your assigned categories have been saved.');
			}
		}

		return;

		$NUM_TOPICS = 20;
		$this->view->assign('NUM_TOPICS', $NUM_TOPICS);

		/* checkbox */
		$fieldSystem = 'display_training_recommend';

		if($this->getRequest()->isPost() && !$this->getParam("id")) { // Update db
			$this->putSetting($fieldSystem, $this->getParam($fieldSystem));
		}

		$checkbox = array(
			'name'  => $fieldSystem,
			'label' => t('Display recommended trainings per individual'),
			'value' => $this->getSetting($fieldSystem),
		);
		$this->view->assign('checkbox', $checkbox);

		require_once('models/table/TrainingRecommend.php');

		// Save POST
		if($this->getRequest()->isPost()) { // Update db
			if(is_numeric($this->getParam('person_qualification_option_id'))) {
				TrainingRecommend::saveRecommendations(
					$this->getParam('person_qualification_option_id'),
					$this->getParam('training_topic_option_id')
				);

				// Remove current, then redirect to clean page
				if($this->getParam('edit') && $this->getParam('edit') != $this->getParam('person_qualification_option_id')) {
					TrainingRecommend::saveRecommendations($this->getParam('edit'), array());
					header("Location: " . Settings::$COUNTRY_BASE_URL . '/admin/training-recommend');
					exit;
				}

			}

			// redirect to next page
			if($this->getParam('redirect')) {
				header("Location: " . $this->getParam('redirect'));
				exit;
			} else if($this->getParam('saveonly')) {
				$status = ValidationContainer::instance();
				$status->setStatusMessage('Your recommended trainings have been saved.');
			}
		}

		// Edting
		if($this->getParam('edit') || $this->getParam('edit') === '0' ) {
			$qualId = $this->getParam('edit');
			$topicId = array_fill(1, $NUM_TOPICS, '');
			$topics = TrainingRecommend::getRecommendations($this->getParam('edit'));
			$pos = 0;
			foreach($topics->ToArray() as $row) {
				$topicId[++$pos] = $row['training_topic_option_id'];
			}
		} else { // New
			$qualId = 0;
			$topicId = array_fill(1, $NUM_TOPICS, '');
		}

		// Delete
		if($delete = $this->getParam('delete')) {
			TrainingRecommend::saveRecommendations($this->getParam('delete'), array());
		}

		require_once 'views/helpers/DropDown.php';
		require_once 'models/table/OptionList.php';
		//$allowIds = TrainingRecommend::getQualificationIds(); // primary qualifications only
		//$this->view->assign('dropDownQuals', DropDown::generateHtml('person_qualification_option','qualification_phrase',$qualId, false, false, $allowIds));

		$qualificationsArray = OptionList::suggestionListHierarchical('person_qualification_option','qualification_phrase',false,false);
		// remove children qualifications and unknown as an option
		foreach($qualificationsArray as $k => $qualArray) {
			if  ($qualArray['id'] || $qualArray['parent_phrase'] == 'unknown') {
				unset($qualificationsArray[$k]);
			}
		}
		$this->viewAssignEscaped('qualifications',$qualificationsArray);
		$this->viewAssignEscaped('qualId',$qualId);

		for($j = 1; $j <= $NUM_TOPICS; $j++) {
			$this->view->assign('dropDownTopic' . $j, DropDown::generateHtml('training_topic_option','training_topic_phrase',$topicId[$j], false, false, false, true));
		}

	}

	public function listByRecommendAction() {
		require_once('models/table/TrainingRecommend.php');
		$rowArray = TrainingRecommend::getRecommendedAdmin();
		foreach($rowArray as $key => $row) {
			$rowArray[$key]['edit'] = '<a href="' . Settings::$COUNTRY_BASE_URL . '/admin/training-recommend/edit/'. $row['person_qualification_option_id'] . '#edit">' . t('edit') . '</a>&nbsp;' .
				'<a href="' . Settings::$COUNTRY_BASE_URL . '/admin/training-recommend/delete/'. $row['person_qualification_option_id'] . '" onclick="return confirm(\'' . t('Are you sure you wish to remove these recommendations?') . '\')">' . t('delete') . '</a>';
		}
		$this->sendData($rowArray);
	}

	public function trainingCompletionAction()
	{

		/* edit table */
		$controller = &$this;
		$editTable = new EditTableController($controller->getRequest(), $controller->getResponse());
		$editTable->setParentController($controller);
		$editTable->table   = 'person_to_training_award_option';
		$editTable->fields  = array('award_phrase' => t('Training Completion'));
		$editTable->label   = t('Complete Status');
		$editTable->dependencies = array('award_id' => 'person_to_training');
		$editTable->execute($controller->getRequest());
	}

	public function trainingViewingLocationAction()
	{

		/* edit table */
		$controller = &$this;
		$editTable = new EditTableController($controller->getRequest(), $controller->getResponse());
		$editTable->setParentController($controller);
		$editTable->table   = 'person_to_training_viewing_loc_option';
		$editTable->fields  = array('location_phrase' => t('Location'));
		$editTable->label   = t('Location');
		$editTable->dependencies = array('viewing_location_option_id' => 'person_to_training');
		$editTable->execute($controller->getRequest());
	}

	public function trainingBudgetCodeAction()
	{

		/* edit table */
		$controller = &$this;
		$editTable = new EditTableController($controller->getRequest(), $controller->getResponse());
		$editTable->setParentController($controller);
		$editTable->table   = 'person_to_training_budget_option';
		$editTable->fields  = array('budget_code_phrase' => t('Budget Code'));
		$editTable->label   = t('Budget Code');
		$editTable->dependencies = array('budget_code_option_id' => 'person_to_training');
		$editTable->execute($controller->getRequest());
	}

	/************************************************************************************
	 * People (Person) / Trainer
	 */

	public function peopleNewPeopleAction(){
		$db = Zend_Db_Table_Abstract::getDefaultAdapter ();
		$rows = $db->fetchAll('select
			person.*,qualification_phrase,facility.facility_name,location.location_name,user.username as created_by from person
			left join person_qualification_option on person_qualification_option.id = primary_qualification_option_id
			left join facility on facility_id = facility.id
			left join location on facility.location_id = location.id
			left join user on user.id=person.created_by
			where person.approved is null and person.is_deleted = 0');
		$this->viewAssignEscaped('primary_results', $rows);

		$go = $this->getSanParam('go');
		if($go){
			require_once('PersonController.php');
			$c = new PersonController($this->getRequest(), $this->getResponse());
			$c->searchAction();
			$this->viewAssignEscaped('primary_results', $rows);
		}

		// fill form dropdowns
		$this->viewAssignEscaped ( 'locations', Location::getAll() );

		//training titles
		require_once ('models/table/TrainingTitleOption.php');
		$titleArray = TrainingTitleOption::suggestionList ( false, 10000 );
		$this->viewAssignEscaped ( 'courses', $titleArray );
		//types
		$qualificationsArray = OptionList::suggestionListHierarchical ( 'person_qualification_option', 'qualification_phrase', false, false );
		$this->viewAssignEscaped ( 'qualifications', $qualificationsArray );

		//facilities list
		$rowArray = OptionList::suggestionList ( 'facility', array ('facility_name', 'id' ), false, 9999 );
		$facilitiesArray = array ();
		foreach ( $rowArray as $key => $val ) {
			if ($val ['id'] != 0)
				$facilitiesArray [] = $val;
		}
		$this->viewAssignEscaped ( 'facilities', $facilitiesArray );

	}

	protected function peopleMerge($mergeFromID, $mergeToID)
	{
		$status = ValidationContainer::instance();
		$db = $this->dbfunc();
		try {
			$table = 'comp';
			$affectedIDs = implode( $db->fetchCol ( 'SELECT id FROM comp WHERE person = ?', $mergeFromID ) );
			$db->query ("UPDATE comp SET person = $mergeToID WHERE person = $mergeFromID");

			$table = 'competencies_answers';
			$affectedIDs = implode( $db->fetchCol ( 'SELECT id FROM competencies_answers WHERE personid = ?', $mergeFromID ) );
			$db->query ("UPDATE competencies_answers SET personid = $mergeToID WHERE personid = $mergeFromID");

			$table = 'compres';
			$affectedIDs = implode( $db->fetchCol ( 'SELECT SNo FROM compres WHERE person = ?', $mergeFromID ) );
			$db->query ("UPDATE compres SET person = $mergeToID WHERE person = $mergeFromID");

			$table = 'evaluation_response';
			$affectedIDs = implode( $db->fetchCol ( 'SELECT id FROM evaluation_response WHERE person_id = ?', $mergeFromID ) );
			$db->query ("UPDATE evaluation_response SET person_id = $mergeToID WHERE person_id = $mergeFromID");

			$table = 'external_course';
			$affectedIDs = implode( $db->fetchCol ( 'SELECT id FROM external_course WHERE person_id = ?', $mergeFromID ) );
			$db->query ("UPDATE external_course SET person_id = $mergeToID WHERE person_id = $mergeFromID");

			$table = 'facs';
			$affectedIDs = implode( $db->fetchCol ( 'SELECT sno FROM facs WHERE person = ?', $mergeFromID ) );
			$db->query ("UPDATE facs SET person = $mergeToID WHERE person = $mergeFromID");

			$table = 'link_person_training';
			$affectedIDs = implode( $db->fetchCol ( 'SELECT id FROM link_person_training WHERE personid = ?', $mergeFromID ) );
			$db->query ("UPDATE link_person_training SET personid = $mergeToID WHERE personid = $mergeFromID");

			$table = 'person_history';
			$affectedIDs = implode( $db->fetchCol ( 'SELECT vid FROM person_history WHERE person_id = ?', $mergeFromID ) );
			$db->query ("UPDATE person_history SET person_id = $mergeToID WHERE person_id = $mergeFromID");

			$table = 'person_to_training';
			$affectedIDs = implode( $db->fetchCol ( 'SELECT id FROM person_to_training WHERE person_id = ?', $mergeFromID ) );
			//TA:21: 09/30/2014
			$from_person_training = $db->fetchCol ( 'SELECT training_id FROM person_to_training WHERE person_id=?', $mergeFromID);
			$to_person_training = $db->fetchCol ( 'SELECT training_id FROM person_to_training WHERE person_id=?', $mergeToID);
			$arr = array();
			for($i=0; $i<count($from_person_training); $i++){
				if(!in_array($from_person_training[$i], $to_person_training)){
					array_push($arr, $from_person_training[$i]);
				}
			}
			for($i=0; $i<count($arr); $i++){// training ids list to update
				$db->query ("UPDATE person_to_training SET person_id = $mergeToID WHERE person_id = $mergeFromID and training_id=$arr[$i]");
			}
			///

			$table = 'person_to_training_topic_option';
			$affectedIDs = implode( $db->fetchCol ( 'SELECT id FROM person_to_training_topic_option WHERE person_id = ?', $mergeFromID ) );
			$db->query ("UPDATE person_to_training_topic_option SET person_id = $mergeToID WHERE person_id = $mergeFromID");

			$table = 'student';
			$affectedIDs = implode( $db->fetchCol ( 'SELECT id FROM student WHERE personid = ?', $mergeFromID ) );
			$db->query ("UPDATE student SET personid = $mergeToID WHERE personid = $mergeFromID");

			$table = 'trainer';
			$affectedIDs = implode( $db->fetchCol ( 'SELECT uuid FROM trainer WHERE person_id = ?', $mergeFromID ) );
			if(!$db->fetchCol ( 'SELECT person_id FROM trainer WHERE person_id=?', $mergeToID)){ //TA:21: 09/26/2014
				$db->query ("UPDATE trainer SET person_id = $mergeToID WHERE person_id = $mergeFromID");
			}
			//TA:52 10/06/2015
			$db->query ("UPDATE trainer SET is_deleted=1 WHERE person_id = $mergeFromID");
			
			//TA:52 10/06/2015
			$table = 'training_to_trainer';
 			$from_trainer_training = $db->fetchCol ( 'SELECT training_id FROM training_to_trainer WHERE trainer_id=?', $mergeFromID);
 			$to_trainer_training = $db->fetchCol ( 'SELECT training_id FROM training_to_trainer WHERE trainer_id=?', $mergeToID);
 			$arr = array();
			for($i=0; $i<count($from_trainer_training); $i++){
				if(!in_array($from_trainer_training[$i], $to_trainer_training)){
					array_push($arr, $from_trainer_training[$i]);
				}
			}
			for($i=0; $i<count($arr); $i++){// training ids list to update
				$db->query ("UPDATE training_to_trainer SET trainer_id = $mergeToID WHERE trainer_id = $mergeFromID and training_id=$arr[$i]");
			}
			$db->query ("DELETE from training_to_trainer WHERE trainer_id = $mergeFromID");

			$table = 'trainer_history';
			$affectedIDs = implode( $db->fetchCol ( 'SELECT vid FROM trainer_history WHERE person_id = ?', $mergeFromID ) );
			$db->query ("UPDATE trainer_history SET person_id = $mergeToID WHERE person_id = $mergeFromID");

			$table = 'trans';
			$affectedIDs = implode( $db->fetchCol ( 'SELECT id FROM trans WHERE person = ?', $mergeFromID ) );
			$db->query ("UPDATE trans SET person = $mergeToID WHERE person = $mergeFromID");

			$table = 'tutor';
			$affectedIDs = implode( $db->fetchCol ( 'SELECT id FROM tutor WHERE personid = ?', $mergeFromID ) );
			$db->query ("UPDATE tutor SET personid = $mergeToID WHERE personid = $mergeFromID");

			$table = 'user';
			$affectedIDs = implode( $db->fetchCol ( 'SELECT id FROM user WHERE person_id = ?', $mergeFromID ) );
			$db->query ("UPDATE user SET person_id = $mergeToID WHERE person_id = $mergeFromID");

			$table = 'person';
			$db->query ("UPDATE person SET is_deleted = 1 WHERE id = $mergeFromID");

		} catch (Exception $e) {
			$status->addError( null, t('Error updating people. Table:').space.$table );
			return;
		}

		$_SESSION['status'] = t( 'The person was saved.' );
		$status->setStatusMessage( t( 'The person was saved.' ) );
	}

	public function peopleMergeAction(){
		$db = Zend_Db_Table_Abstract::getDefaultAdapter ();
		$this->view->assign('showMerge', true);
		$status = ValidationContainer::instance();

		if($this->getSanParam('submitted') ){
			$fromID = $this->getSanParam('person_from_id');
			$toID = $this->getSanParam('person_to_id');
			if (!$fromID or !$toID or $fromID == $toID){
				$status->addError( null, t('You must select a valid person to merge') );
			} else {
				$this->peopleMerge($fromID, $toID);
			}
		}
		if($this->getSanParam('go')){
			require_once('PersonController.php');
			$c = new PersonController($this->getRequest(), $this->getResponse());
			$c->searchAction();
			$this->viewAssignEscaped('primary_results', $rows);
		}

		// fill form dropdowns
		$this->viewAssignEscaped ( 'locations', Location::getAll() );
		$this->view->assign ('pageTitle', t('Person Merge'));
		$this->view->assign ('status', $status);

		//training titles
		require_once ('models/table/TrainingTitleOption.php');
		$titleArray = TrainingTitleOption::suggestionList ( false, 10000 );
		$this->viewAssignEscaped ( 'courses', $titleArray );
		//types
		$qualificationsArray = OptionList::suggestionListHierarchical ( 'person_qualification_option', 'qualification_phrase', false, false );
		$this->viewAssignEscaped ( 'qualifications', $qualificationsArray );

		//facilities list
		$rowArray = OptionList::suggestionList ( 'facility', array ('facility_name', 'id' ), false, 9999 );
		$facilitiesArray = array ();
		foreach ( $rowArray as $key => $val ) {
			if ($val ['id'] != 0)
				$facilitiesArray [] = $val;
		}
		$this->viewAssignEscaped ( 'facilities', $facilitiesArray );

	}


	public function peopleQualAction()
	{
		$parent = $this->getSanParam('parent');

		if ( $parent or $this->getSanParam('redirect') ) {
			$controller = &$this;
		    $editTable = new EditTableController($controller->getRequest(), $controller->getResponse());  
		    $editTable->setParentController($controller);
			$editTable->table   = 'person_qualification_option';
			$editTable->fields  = array('qualification_phrase' => t('Qualification'));
			$editTable->label   = t('Person Qualification');
			$editTable->dependencies = array('primary_qualification_option_id' => 'person');
			$editTable->where = 'parent_id = '.$parent;
			$editTable->insertExtra = array('parent_id'=>$parent);
			$editTable->allowDefault = true;
			$editTable->execute($controller->getRequest());
		}

		$parentArray = OptionList::suggestionList('person_qualification_option','qualification_phrase',false,false, true, 'parent_id IS NULL');
		$this->viewAssignEscaped('parents',$parentArray);
		$this->view->assign('parent', $parent);

	}

	public function peopleResponsibilityAction()
	{
		return $this->peoplePrimaryrespAction();
	}

	public function peoplePrimaryrespAction() // was peopleResponsibilityAction
	{
		$controller = &$this;
        $editTable = new EditTableController($controller->getRequest(), $controller->getResponse());  
		$editTable->setParentController($controller);
		$editTable->table   = 'person_primary_responsibility_option';
		$editTable->fields  = array('responsibility_phrase' => t('Primary Responsibility'));
		$editTable->label   = t('Primary Responsibility');
		$editTable->dependencies = array('primary_responsibility_option_id' => 'person');
		$editTable->execute($controller->getRequest());
	}

	public function peopleSecondaryrespAction()
	{
		$controller = &$this;
        $editTable = new EditTableController($controller->getRequest(), $controller->getResponse());  
		$editTable->setParentController($controller);
		$editTable->table   = 'person_secondary_responsibility_option';
		$editTable->fields  = array('responsibility_phrase' => t('Secondary Responsibility'));
		$editTable->label   = t('Secondary Responsibility');
		$editTable->dependencies = array('secondary_responsibility_option_id' => 'person');
		$editTable->execute($controller->getRequest());
	}

	public function peopleTypesAction()
	{
		$controller = &$this;
        $editTable = new EditTableController($controller->getRequest(), $controller->getResponse());  
		$editTable->setParentController($controller);
		$editTable->table   = 'trainer_type_option';
		$editTable->fields  = array('trainer_type_phrase' => t('Type'));
		$editTable->label   = t('Trainer Type');
		$editTable->dependencies = array('type_option_id' => 'trainer');
		$editTable->execute($controller->getRequest());
	}

	public function peopleSkillsAction()
	{

		if($this->getRequest()->isPost()) {
			// form submit
			$updateData = array();
			$require_trainer_skill = $this->getParam('require_trainer_skill');
			if (empty($require_trainer_skill)) { $require_trainer_skill = 0; }
			$this->putSetting('require_trainer_skill', $require_trainer_skill);
		}

		// populate form
		$checkbox = array(
			'name'  => 'require_trainer_skill',
			'label' => t('Require at least one trainer skill per trainer'),
			'value' => $this->getSetting('require_trainer_skill'),
		);
		$this->view->assign('checkbox', $checkbox);

		$controller = &$this;
        $editTable = new EditTableController($controller->getRequest(), $controller->getResponse());  
		$editTable->setParentController($controller);
		$editTable->table   = 'trainer_skill_option';
		$editTable->fields  = array('trainer_skill_phrase' => t('Trainer Skill'));
		$editTable->label   = t('Trainer Skill');
		$editTable->dependencies = array('trainer_to_trainer_skill_option');
		$editTable->execute($controller->getRequest());
	}

	public function peopleLanguagesAction()
	{

		$controller = &$this;
        $editTable = new EditTableController($controller->getRequest(), $controller->getResponse());  
		$editTable->setParentController($controller);
		$editTable->table   = 'trainer_language_option';
		$editTable->fields  = array('language_phrase' => t('Language'));
		$editTable->label   = t('Language');
		$editTable->dependencies = array('trainer_to_trainer_language_option');
		$editTable->execute($controller->getRequest());
	}

	public function peopleAffiliationsAction()
	{

		/* edit table */

		$controller = &$this;
        $editTable = new EditTableController($controller->getRequest(), $controller->getResponse());  
		$editTable->setParentController($controller);
		$editTable->table   = 'trainer_affiliation_option';
		$editTable->fields  = array('trainer_affiliation_phrase' => t('Affiliation'));
		$editTable->label   = t('Affiliation');
		$editTable->dependencies = array('affiliation_option_id' => 'trainer');
		$editTable->execute($controller->getRequest());
	}

	public function peopleTitleAction()
	{

		/* edit table */
		$controller = &$this;
        $editTable = new EditTableController($controller->getRequest(), $controller->getResponse());  
		$editTable->setParentController($controller);
		$editTable->table   = 'person_title_option';
		$editTable->fields  = array('title_phrase' => t('Title'));
		$editTable->label   = t('Title');
		$editTable->dependencies = array('title_option_id' => 'person');
		$editTable->execute($controller->getRequest());

	}

	//TA: added 7/24/2014
	public function tutorspecialtyAction()
	{
		$controller = &$this;
        $editTable = new EditTableController($controller->getRequest(), $controller->getResponse());  
		$editTable->setParentController($controller);
		$editTable->table   = 'tutor_specialty_option';
		$editTable->fields  = array('specialty_phrase' => t('Specialty'));
		$editTable->label   = t('Specialty');
		$editTable->dependencies = array('specialty' => 'tutor');
		$editTable->execute($controller->getRequest());	
	}

	//TA: added 7/24/2014
	public function tutorcontractAction()
	{
		$controller = &$this;
        $editTable = new EditTableController($controller->getRequest(), $controller->getResponse());  
		$editTable->setParentController($controller);
		$editTable->table   = 'tutor_contract_option';
		$editTable->fields  = array('contract_phrase' => t('Contract Type'));
		$editTable->label   = t('Contract Type');
		$editTable->dependencies = array('contract_type' => 'tutor');
		$editTable->execute($controller->getRequest());
	}

	//TA:17:12: added 9/19/2014
	public function commoditynameAction(){

		$controller = &$this;
        $editTable = new EditTableController($controller->getRequest(), $controller->getResponse());  
		$editTable->setParentController($controller);
		$editTable->fields  = array('commodity_name' => t('Commodity Name'));
		$editTable->table   = 'commodity_name_option';
		$editTable->label   = t('Commodity Name');
		$editTable->dependencies = array('name_id' => 'commodity');
		$editTable->execute($controller->getRequest());
	}


	//TA:17:12: added 10/03/2014
	public function commoditytypeAction()
	{
		$controller = &$this;
        $editTable = new EditTableController($controller->getRequest(), $controller->getResponse());  
		$editTable->setParentController($controller);
		$editTable->table   = 'commodity_type_option';
		$editTable->fields  = array('commodity_type' => t('Commodity Type'));
		$editTable->label   = t('Commodity Type');
		$editTable->dependencies = array('type_id' => 'commodity');
		$editTable->execute($controller->getRequest());
	}

	public function peopleSuffixAction()
	{

		/* edit table */
		$controller = &$this;
        $editTable = new EditTableController($controller->getRequest(), $controller->getResponse());  
		$editTable->setParentController($controller);
		$editTable->table   = 'person_suffix_option';
		$editTable->fields  = array('suffix_phrase' => t('Suffix'));
		$editTable->label   = t('Suffix');
		$editTable->dependencies = array('suffix_option_id' => 'person');
		$editTable->execute($controller->getRequest());
	}

	public function peopleActiveTrainerAction()
	{

		/* edit table */
		$controller = &$this;
        $editTable = new EditTableController($controller->getRequest(), $controller->getResponse());  
		$editTable->setParentController($controller);
		$editTable->table   = 'person_active_trainer_option';
		$editTable->fields  = array('active_trainer_phrase' => t('Active Trainer'));
		$editTable->label   = t('Active Trainer');
		$editTable->dependencies = array('active_trainer_option_id' => 'trainer');
		$editTable->execute($controller->getRequest());
	}

	public function peopleHighestedulevelAction()
	{

		/* edit table */
		$controller = &$this;
        $editTable = new EditTableController($controller->getRequest(), $controller->getResponse());  
		$editTable->setParentController($controller);
		$editTable->table   = 'person_education_level_option';
		$editTable->fields  = array('education_level_phrase' => t('Highest Education Level'));
		$editTable->label   = t('Highest Education Level');
		$editTable->dependencies = array('highest_edu_level_option_id' => 'person');
		$editTable->execute($controller->getRequest());
	}

	public function peopleAttendreasonAction()
	{

		/* edit table */
		$controller = &$this;
        $editTable = new EditTableController($controller->getRequest(), $controller->getResponse());  
		$editTable->setParentController($controller);
		$editTable->table   = 'person_attend_reason_option';
		$editTable->fields  = array('attend_reason_phrase' => t('Reason Attending'));
		$editTable->label   = t('Reason Attending');
		$editTable->dependencies = array('attend_reason_option_id' => 'person');
		$editTable->execute($controller->getRequest());
	}

	/************************************************************************************
	 * Facilities
	 */

	public function facilitiesTypesAction()
	{
	    $controller = &$this;  // gnr, Call-time pass-by-reference has been removed
        $editTable = new EditTableController($controller->getRequest(), $controller->getResponse());
        $editTable->setParentController($controller);
		$editTable->table   = 'facility_type_option';
		$editTable->fields  = array('facility_type_phrase' => t('Facility Type'));
		$editTable->label   = t('Facility Type');
		$editTable->dependencies = array('type_option_id' => 'facility');
		$editTable->execute($controller->getRequest());
	}

	public function facilitiesSponsorsAction()
	{
	    $controller = &$this;  //gnr, Call-time pass-by-reference has been removed
	    $editTable = new EditTableController($controller->getRequest(), $controller->getResponse());
	    $editTable->setParentController($controller);
		$editTable->table   = 'facility_sponsor_option';
		$editTable->fields  = array('facility_sponsor_phrase' => t('Facility Sponsor'));
		$editTable->label   = t('Facility Sponsor');
		$editTable->dependencies = array('sponsor_option_id' => 'facility');
		$editTable->execute($controller->getRequest());
	}

	public function facilitiesNewFacilitiesAction()
	{
		require_once('views/helpers/Location.php');
		$db = Zend_Db_Table_Abstract::getDefaultAdapter ();
		$criteria = array();
		list($criteria, $location_tier, $location_id) = $this->getLocationCriteriaValues($criteria);
		$num_locs = $this->setting('num_location_tiers');
		list($field_name,$location_sub_query) = Location::subquery($num_locs, $location_tier, $location_id);
		$rows = $db->fetchAll("
			select loc.*,facility.*,types.facility_type_phrase,sponsors.facility_sponsor_phrase,facility.id as id
			from facility
			left join ($location_sub_query)    as loc   on loc.id = location_id
			left join facility_type_option     as types on types.id = type_option_id
			left join facility_sponsor_option  as sponsors on sponsors.id = sponsor_option_id
			where facility.approved is null and facility.is_deleted = 0
			order by facility_name");

		$go = $this->getSanParam('go');
		if($go){
			require_once('FacilityController.php');
			$c = new FacilityController($this->getRequest(), $this->getResponse());
			$c->searchAction();
		}

		// fill form dropdowns
		$this->viewAssignEscaped('primary_results', $rows);
		// facility name
		$nameArray = OptionList::suggestionListValues ( 'facility', 'facility_name', false, false, false );
		$this->viewAssignEscaped ( 'facility_names', $nameArray );
		// locations
		$this->viewAssignEscaped ( 'locations', Location::getAll () );
		// facility types
		$typesArray = OptionList::suggestionList ( 'facility_type_option', 'facility_type_phrase', false, false );
		$this->viewAssignEscaped ( 'facility_types', $typesArray );
		// sponsor types
		$sponsorsArray = OptionList::suggestionList ( 'facility_sponsor_option', 'facility_sponsor_phrase', false, false );
		$this->viewAssignEscaped ( 'facility_sponsors', $sponsorsArray );
	}

	
	protected function facilityMerge($mergeFromID, $mergeToID)
	{
		$status = ValidationContainer::instance();
		$db = $this->dbfunc();
		try {

			$table = 'facs';
			$affectedIDs = implode( $db->fetchCol ( 'SELECT sno FROM facs WHERE facility = ?', $mergeFromID ) );
			$db->query ("UPDATE facs SET facility = $mergeToID WHERE facility = $mergeFromID");

			$table = 'person';
			$affectedIDs = implode( $db->fetchCol ( 'SELECT id FROM person WHERE facility_id = ?', $mergeFromID ) );
			$db->query ("UPDATE person SET facility_id = $mergeToID WHERE facility_id = $mergeFromID");

			$table = 'person_history';
			$affectedIDs = implode( $db->fetchCol ( 'SELECT vid FROM person_history WHERE facility_id = ?', $mergeFromID ) );
			$db->query ("UPDATE person_history SET facility_id = $mergeToID WHERE facility_id = $mergeFromID");

			$table = 'practicum';
			$affectedIDs = implode( $db->fetchCol ( 'SELECT id FROM practicum WHERE facilityid = ?', $mergeFromID ) );
			$db->query ("UPDATE practicum SET facilityid = $mergeToID WHERE facilityid = $mergeFromID");

			$table = 'tutor';
			$affectedIDs = implode( $db->fetchCol ( 'SELECT id FROM tutor WHERE facilityid = ?', $mergeFromID ) );
			$db->query ("UPDATE tutor SET facilityid = $mergeToID WHERE facilityid = $mergeFromID");

			$table = 'employee';
			$affectedIDs = implode( $db->fetchCol ( 'SELECT id FROM employee WHERE site_id = ?', $mergeFromID ) );
			$db->query ("UPDATE employee SET site_id = $mergeToID WHERE site_id = $mergeFromID");

			$table = 'facility';
			$db->query ("UPDATE facility SET is_deleted = 1 WHERE id = $mergeFromID");
			
			//TA:54 10/06/2015
			$table = 'facility_sponsors';
			$db->query ("UPDATE facility_sponsors SET facility_id = $mergeToID WHERE facility_id = $mergeFromID");
			//TA:54 10/06/2015
			$table = 'commodity';
			$db->query ("UPDATE commodity SET facility_id = $mergeToID WHERE facility_id = $mergeFromID");
			//TA:54 10/06/2015
			$table = 'link_facility_addresses';
			$db->query ("UPDATE link_facility_addresses SET id_facility = $mergeToID WHERE id_facility = $mergeFromID");

		} catch (Exception $e) {
			$status->addError( null, t('Error updating facilities. Table:').space.$table );
			return;
		}

		$_SESSION['status'] = t( 'The facility was saved.' );
		$status->setStatusMessage( t( 'The facility was saved.' ) );
	}

	public function facilitiesMergeAction()
	{
		require_once('views/helpers/Location.php');

		$this->view->assign('showMerge', true);
		$status = ValidationContainer::instance();

		if($this->getSanParam('go')){
			require_once('FacilityController.php');
			$c = new FacilityController($this->getRequest(), $this->getResponse());
			$c->searchAction();
		}
		if($this->getSanParam('submitted') ){
			$fromID = $this->getSanParam('facility_from_id');
			$toID = $this->getSanParam('facility_to_id');
			if (!$fromID or !$toID or $fromID == $toID){
				$status->addError( null, t('You must select a valid facility to merge') );
			} else {
				$this->facilityMerge($fromID, $toID);
			}
		}

		// fill form dropdowns
		$this->view->assign('pageTitle', t('Facilities Merge'));
		// facility name
		$nameArray = OptionList::suggestionListValues ( 'facility', 'facility_name', false, false, false );
		$this->viewAssignEscaped ( 'facility_names', $nameArray );
		// locations
		$this->viewAssignEscaped ( 'locations', Location::getAll () );
		// facility types
		$typesArray = OptionList::suggestionList ( 'facility_type_option', 'facility_type_phrase', false, false );
		$this->viewAssignEscaped ( 'facility_types', $typesArray );
		// sponsor types
		$sponsorsArray = OptionList::suggestionList ( 'facility_sponsor_option', 'facility_sponsor_phrase', false, false );
		$this->viewAssignEscaped ( 'facility_sponsors', $sponsorsArray );
	}
	/************************************************************************************
	 * Internal
	 */

	protected function _csv_get_row($filepath, $reset = FALSE) {
		ini_set('auto_detect_line_endings',true);

		if ($filepath == '') {
			$this->_csvHandle = null;
			return FALSE;
		}

		if (!$this->_csvHandle || $reset) {
			if ($this->_csvHandle) {
				fclose($this->_csvHandle);
			}
			$this->_csvHandle = fopen($filepath, 'r');
		}

		return fgetcsv($this->_csvHandle, 10000, ',');
	}

	public function usersAddAction() {

		return parent::addAction();
	}

	public function usersSearchAction() {

		$this->_redirect('user/search');
	}

	/**
	 * this controller's view relies on the indexes of the resulting array from fetchAssoc to be the db ids,
	 * which relies, in turn, on id being the first column selected
	 */

	public function preserviceClassesAction(){
		$helper = new Helper();

		if ($this->getRequest()->isPost()) {
			$params = $this->getAllParams();

			$map = array(
				'_startdate' => 'startdate',
				'_enddate' => 'enddate',
				'_instructorid' => 'instructorid',
				'_coursetypeid' => 'coursetypeid',
				'_coursetopic' => 'coursetopic',
				'_classname' => 'classname',
				'_id' => 'id',
				'_class_modules_id' => 'class_modules_id',
				'_custom_1' => 'custom_1',
				'_credits' => 'maxcredits',
				'_custom_2' => 'custom_2',
			);


			// filter out empty parameters and translate to database column names

			// drop all keys with 0 length
			$params = array_filter($params, strlen);

			// TODO: What would it take to just use column names in the form? That would eliminate this processing step and the map
			// translate form fields to database column names
			$data = array();
			foreach ($params as $k => $v) {
				if (array_key_exists($k, $map)) {
					$data[$map[$k]] = $v;
				}
			}

			switch($params['_action']) {
				case "addnew":
					$helper->addClasses($data);
					break;
				case "update":
					$helper->updateClasses($data);
					break;
			}
			$this->_redirect ( 'admin/preservice-classes' );
		}

		$db = $this->dbfunc();
		$q = "select id, external_id, title from class_modules ORDER BY title ASC";
		// this view relies on the indexes of the resulting array from fetchAssoc to be the db ids
		// which relies, in turn, on id being the first column selected
		$modules = $db->fetchAssoc($q);

		$list = $helper->AdminClasses();

		$q = "select id, coursetype from lookup_coursetype ORDER BY coursetype ASC";
		$coursetypes = $db->fetchAssoc($q);

		$tutors = $helper->getAllTutors();
		$this->view->assign("lookup", $list);
		$this->view->assign("coursetypes", $coursetypes);
		$this->view->assign("tutors", $tutors);
		$this->view->assign("header",t("Classes"));
		$this->view->assign("modules", $modules);
	}

	/**
	 * this controller relies on view form fields having the same name as the database columns
	 *
	 * this view relies on the indexes of the resulting array from fetchAssoc to be the db ids,
	 * which relies, in turn, on id being the first column selected
	 *
	 */

	public function preserviceClassModulesAction()
	{
		// this function relies on form fields having the same names as their database column

		if ($this->getRequest()->isPost()) {
			$params = $this->getAllParams();

			// drop all keys with 0 length
			$params = array_filter($params, strlen);

			$params['id'] = $params['currentid'];

			$dbcols = array (
				'id',
				'external_id',
				'title',
				'lookup_coursetype_id',
				'custom_1'
			);

			// filter out keys that don't have database columns
			$db_data = array_intersect_key($params, array_flip($dbcols));

			switch($params['data_action']) {
				case "addnew":
				{
					$this->dbfunc()->insert('class_modules', $db_data);
					break;
				}
				case "update":
				{
					$this->dbfunc()->update('class_modules', $db_data, 'id = '.$db_data['id']);
					break;
				}
				case "delete":
				{
					$this->dbfunc()->delete('class_modules', 'id = '.$db_data['id']);
					break;
				}
			}
			$this->_redirect('admin/preservice-class-modules');
		}

		// this view relies on the indexes of the resulting array from fetchAssoc to be the db ids,
		// which relies, in turn, on id being the first column selected
		$this->view->assign('modules', $this->dbfunc()->fetchAssoc(
			$this->dbfunc()->select()->from("class_modules")
		));

		$this->view->assign('coursetypes', $this->dbfunc()->fetchAssoc(
			$this->dbfunc()->select()->from("lookup_coursetype")
		));
	}

	//TA: changed on 7/21/2014
	public function preserviceLabelsAction(){
		require_once('models/table/System.php');
		$sysTable = new System();

		// For "Labels"
		require_once('models/table/Translation.php');
		$labelNames = array( // input name => key_phrase
			'label_ps_institution'   => 'ps institution',
			'label_ps_tutor' => 'ps tutor',
			'label_ps_zip_code' => 'ps zip code',
			'label_ps_clinical_allocation' => 'ps clinical allocation',
			'label_ps_local_address' => 'ps local address',
			'label_ps_lic_reg' => 'ps license and registration',
			'label_ps_permanent_address' => 'ps permanent address',
			'label_ps_religious_denomination' => 'ps religious denomination',
			'label_ps_program_enrolled' => 'ps program enrolled in',
			'label_ps_nationality' => 'ps nationality',
			'label_inst_compl_date'   => 'ps high school completion date',
			'label_last_school_att' => 'ps last school attended',
			'label_schhol_start_date' => 'ps school start date',
			'label_equivalence' => 'ps equivalence',
			'label_last_univ_att' => 'ps last university attended',
			'label_person_charge' => 'ps person in charge',
			'label_ps_custom_field1' => 'ps custom field 1',
			'label_ps_custom_field2' => 'ps custom field 2',
			'label_ps_custom_field3' => 'ps custom field 3',
			'label_ps_marital_status' => 'ps marital status',
			'label_ps_spouse_name' => 'ps spouse name',
			'label_ps_specialty' => 'ps specialty',
			'label_ps_contract_type' => 'ps contract type',
			'label_ps_exam_mark' => 'ps exam mark',
			'label_ps_ca_mark' => 'ps ca mark',
			'label_ps_credits' => 'ps credits',
			'label_ps_class_modules_custom_1' => 'ps class modules custom 1',
		    'label_ps_inst_sponsor' => 'ps inst sponsor',//TA:88

		);

		// _system settings
		$checkboxFields = array( // input name => db field _system table
			'check_display_inst_compl_date'   => 'ps_display_inst_compl_date',
			'check_display_last_inst_attended' => 'ps_display_last_inst_attended',
			'check_display_start_school_date' => 'ps_display_start_school_date',
			'check_display_equivalence' => 'ps_display_equivalence',
			'check_display_last_univ_attended' => 'ps_display_last_univ_attended',
			'check_display_person_charge' => 'ps_display_person_charge',
			'check_display_custom_field1' => 'ps_display_custom_field1',
			'check_display_custom_field2' => 'ps_display_custom_field2',
			'check_display_custom_field3' => 'ps_display_custom_field3',
			'check_display_marital_status' => 'ps_display_marital_status',
			'check_display_spouse_name' => 'ps_display_spouse_name',
			'check_display_specialty' => 'ps_display_specialty',
			'check_display_contract_type' => 'ps_display_contract_type',
			'check_display_local_address' => 'ps_display_local_address',
			'check_display_permanent_address' => 'ps_display_permanent_address',
			'check_display_religious_denomin' => 'ps_display_religious_denomin',
			'check_display_nationality' => 'ps_display_nationality',
			'check_display_exam_mark' => 'ps_display_exam_mark',
			'check_display_ca_mark' => 'ps_display_ca_mark',
			'check_display_credits' => 'ps_display_credits',
		);

		if($this->getRequest()->isPost()) { // Update db
			$updateData = array();

			// update translation labels
			$tranTable = new Translation();
			foreach($labelNames as $input_key => $db_key) {
				if ( $this->getParam($input_key) ) {
					try {
						$tranTable->update(
							array('phrase' => $this->getParam($input_key)),
							"key_phrase = '$db_key'"
						);
						$this->viewAssignEscaped($input_key, $this->getParam($input_key));
					} catch(Zend_Exception $e) {
						error_log($e);
					}
				}
			}

			// update _system (checkboxes)
			foreach($checkboxFields as $input_key => $db_field) {
				$value = ($this->getParam($input_key) == NULL) ? 0 : 1;
				$updateData[$db_field] = $value;
				$this->view->assign($input_key, $value);
			}
			$sysTable->update($updateData, '');

		} else { // view

			// labels
			$t = Translation::getAll();
			foreach($labelNames as $input_key => $db_key) {
				$this->viewAssignEscaped($input_key, $t[$db_key]);
			}

			// checkboxes
			$sysRows = $sysTable->fetchRow($sysTable->select()->limit(1));
			foreach($checkboxFields as $input_key => $field_key) {
				$this->view->assign($input_key, $sysRows->$field_key);
			}
		}

		// redirect to next page
		if($this->getParam('redirect')) {
			header("Location: " . $this->getParam('redirect'));
			exit;
		} else if($this->getParam('saveonly')) {
			$status = ValidationContainer::instance();
			$status->setStatusMessage(t('Your settings have been updated.'));
		}

		$this->view->assign("header",t("Field labels"));
	}

	public function preserviceCadresAction(){
		$helper = new Helper();

		if (isset ($_POST['_action'])){
			switch ($_POST['_action']){
				case "addnew":
					$helper->addCadres($_POST);
					break;
				case "update":
					$helper->updateCadres($_POST);
					break;
			}
			$this->_redirect ( 'admin/preservice-cadres' );
		}

		$list = $helper->AdminCadres();
		$this->view->assign("lookup", $list);
		$this->view->assign("header",t("Cadres"));
	}

	//TA:35 able to delete a degree
	public function preserviceDegreesAction(){
		$helper = new Helper();

		if (isset ($_POST['_action'])){
			print_r($_POST);
			switch ($_POST['_action']){
				case "addnew":
					$helper->addDegrees($_POST);
					break;
				case "update":
					$helper->updateDegrees($_POST);
					break;
				case "delete":
						$helper->deleteDegrees($_POST);
						break;
			}
			$this->_redirect ( 'admin/preservice-degrees' );
		}

		$list = $helper->AdminDegrees();
		$this->view->assign("lookup", $list);
		$this->view->assign("header",t("Degrees"));

	}

	public function preserviceCoursetypesAction(){
		$helper = new Helper();

		if (isset ($_POST['_action'])){
			switch ($_POST['_action']){
				case "addnew":
					$helper->addCoursetypes($_POST);
					break;
				case "update":
					$helper->updateCoursetypes($_POST);
					break;
			}
			$this->_redirect ( 'admin/preservice-coursetypes' );
		}

		$list = $helper->AdminCoursetypes();
		$this->view->assign("lookup", $list);
		$this->view->assign("header",t("Course types"));
	}

	public function preserviceFundingAction(){
		$helper = new Helper();

		if (isset ($_POST['_action'])){
			switch ($_POST['_action']){
				case "addnew":
					$helper->addFunding($_POST);
					break;
				case "update":
					$helper->updateFunding($_POST);
					break;
			}
			$this->_redirect ( 'admin/preservice-funding' );
		}

		$list = $helper->AdminFunding();
		$this->view->assign("lookup", $list);
		$this->view->assign("header",t("Funding sources"));
	}

	public function preserviceInstitutiontypesAction(){
		$helper = new Helper();

		if (isset ($_POST['_action'])){
			switch ($_POST['_action']){
				case "addnew":
					$helper->addInstitutiontypes($_POST);
					break;
				case "update":
					$helper->updateInstitutiontypes($_POST);
					break;
			}
			$this->_redirect ( 'admin/preservice-institutiontypes' );
		}

		$list = $helper->AdminInstitutionTypes();
		$this->view->assign("lookup", $list);
		$this->view->assign("header",t("Institution types"));
	}

	public function preserviceLanguagesAction(){
		$helper = new Helper();

		if (isset ($_POST['_action'])){
			switch ($_POST['_action']){
				case "addnew":
					$helper->addLanguages($_POST);
					break;
				case "update":
					$helper->updateLanguages($_POST);
					break;
			}
			$this->_redirect ( 'admin/preservice-languages' );
		}

		$list = $helper->AdminLanguages();
		$this->view->assign("lookup", $list);
		$this->view->assign("header",t("Languages"));
	}

	public function preserviceNationalitiesAction(){
		$helper = new Helper();

		if (isset ($_POST['_action'])){
			switch ($_POST['_action']){
				case "addnew":
					$helper->addNationalities($_POST);
					break;
				case "update":
					$helper->updateNationalities($_POST);
					break;
			}
			$this->_redirect ( 'admin/preservice-nationalities' );
		}

		$list = $helper->AdminNationalities();
		$this->view->assign("lookup", $list);
		$this->view->assign("header",t("Nationalities"));
	}

	public function priorLearningAction() {

	}

	public function qualificationsAction() {

	}

	public function preserviceJoindropreasonsAction(){
		$helper = new Helper();

		if (isset ($_POST['_action'])){
			switch ($_POST['_action']){
				case "addnew":
					$helper->addJoindropreasons($_POST);
					break;
				case "update":
					$helper->updateJoindropreasons($_POST);
					break;
			}
			$this->_redirect ( 'admin/preservice-joindropreasons' );
		}

		$list = $helper->AdminJoinDropReasons();
		$this->view->assign("lookup", $list);
		$this->view->assign("header",t("Join & drop reasons"));
	}

	public function preserviceSponsorsAction(){
		$helper = new Helper();

		if (isset ($_POST['_action'])){
			switch ($_POST['_action']){
				case "addnew":
					$helper->addSponsors($_POST);
					break;
				case "update":
					$helper->updateSponsors($_POST);
					break;
			}
			$this->_redirect ( 'admin/preservice-sponsors' );
		}

		$list = $helper->AdminSponsors();
		$this->view->assign("lookup", $list);
		$this->view->assign("header",t("Sponsors"));
	}

	public function preserviceStudenttypesAction(){
		$helper = new Helper();

		if (isset ($_POST['_action'])){
			switch ($_POST['_action']){
				case "addnew":
					$helper->addStudenttypes($_POST);
					break;
				case "update":
					$helper->updateStudenttypes($_POST);
					break;
			}
			$this->_redirect ( 'admin/preservice-studenttypes' );
		}

		$list = $helper->AdminStudenttypes();
		$this->view->assign("lookup", $list);
		$this->view->assign("header",t("Student types"));
	}

	public function preserviceTutortypesAction(){
		$helper = new Helper();

		if (isset ($_POST['_action'])){
			switch ($_POST['_action']){
				case "addnew":
					$helper->addTutortypes($_POST);
					break;
				case "update":
					$helper->updateTutortypes($_POST);
					break;
			}
			$this->_redirect ( 'admin/preservice-tutortypes' );
		}

		$list = $helper->AdminTutortypes();
		$this->view->assign("lookup", $list);
		$this->view->assign("header",t("Tutor types"));
	}

	public function preserviceReligionAction(){
		$helper = new Helper();

		if (isset ($_POST['_action'])){
			switch ($_POST['_action']){
				case "addnew":
					$helper->addReligion($_POST);
					break;
				case "update":
					$helper->updateReligion($_POST);
					break;
			}
			$this->_redirect ( 'admin/preservice-religion' );
		}

		$list = $helper->AdminStudenttypes();
		$this->view->assign("lookup", $list);
		$this->view->assign("header",t("Religious denominations"));
	}

	public function skillsmartSettingsAction()
	{

		require_once('models/table/System.php');
		$sysTable = new System();

		// For "Labels"
		require_once('models/table/Translation.php');
		$labelNames = array(
			'label_occupational_category'	=> 'Occupational category',
			'label_government_employee'		=> 'Government employee',
			'label_professional_bodies'		=> 'Professional bodies',
			'label_race'					=> 'Race',
			'label_disability'				=> 'Disability',
			'label_nurse_trainer_type'		=> 'Nurse trainer type',
			'label_provider_start'			=> 'Year you started providing care',
			'label_rank_groups'				=> 'Rank patient groups based on time',
			'label_supervised'				=> 'Supervised',
			'label_training_received'		=> 'Indicate the training you received',
			'label_facility_department'		=> 'Facility department',
		);

		// _system settings
		$checkboxFields = array( // input name => db field
			'check_occupational_category'	=> 'display_occupational_category',
			'check_government_employee'		=> 'display_government_employee',
			'check_professional_bodies'		=> 'display_professional_bodies',
			'check_race'					=> 'display_race',
			'check_disability'				=> 'display_disability',
			'check_nurse_trainer_type'		=> 'display_nurse_trainer_type',
			'check_provider_start'			=> 'display_provider_start',
			'check_rank_groups'				=> 'display_rank_groups',
			'check_supervised'				=> 'display_supervised',
			'check_training_received'		=> 'display_training_received',
			'check_facility_department'		=> 'display_facility_department',
		);



		if($this->getRequest()->isPost()) { // Update db
			$updateData = array();

			// update translation labels
			$tranTable = new Translation();
			foreach($labelNames as $input_key => $db_key) {

				if ( $this->getParam($input_key) ) {
					try {
						$tranTable->update(
							array('phrase' => $this->getParam($input_key)),
							"key_phrase = '$db_key'"
						);
						$this->viewAssignEscaped($input_key, $this->getParam($input_key));
					} catch(Zend_Exception $e) {
						error_log($e);
					}
				}
			}

			// update _system (checkboxes)
			foreach($checkboxFields as $input_key => $db_field) {
				$value = ($this->getParam($input_key) == NULL) ? 0 : 1;
				$updateData[$db_field] = $value;
				$this->view->assign($input_key, $value);
			}
			$sysTable->update($updateData, '');

		} else { // view

			// labels
			$t = Translation::getAll();
			foreach($labelNames as $input_key => $db_key) {
				$this->viewAssignEscaped($input_key, $t[$db_key]);
			}

			// checkboxes
			$sysRows = $sysTable->fetchRow($sysTable->select()->limit(1));
			foreach($checkboxFields as $input_key => $field_key) {
				$this->view->assign($input_key, $sysRows->$field_key);
			}
		}

		// redirect to next page
		if($this->getParam('redirect')) {
			header("Location: " . $this->getParam('redirect'));
			exit;
		} else if($this->getParam('saveonly')) {
			$status = ValidationContainer::instance();
			$status->setStatusMessage(t('Your settings have been updated.'));
		}

	}

	public function skillsmartRaceAction(){
		$helper = new Helper();

		if (isset ($_POST['_action'])){
			switch ($_POST['_action']){
				case "addnew":
					$helper->addSkillsmartLookup($_POST,"race");
					break;
				case "update":
					$helper->updateSkillsmartLookup($_POST);
					break;
			}
			$this->_redirect ( 'admin/skillsmart-race' );
		}

		$list = $helper->getSkillSmartLookups();
		$dump = var_export($list,true);
		$this->view->assign("lookup", $list['race']);
		$this->view->assign("header",t("Race"));
	}

	public function skillsmartDisabilityAction(){
		$helper = new Helper();

		if (isset ($_POST['_action'])){
			switch ($_POST['_action']){
				case "addnew":
					$helper->addSkillsmartLookup($_POST,"disability");
					break;
				case "update":
					$helper->updateSkillsmartLookup($_POST);
					break;
			}
			$this->_redirect ( 'admin/skillsmart-disability' );
		}

		$list = $helper->getSkillSmartLookups();
		$this->view->assign("lookup", $list['disability']);
		$this->view->assign("header",t("Disability"));
	}

	public function skillsmartProfessionalbodiesAction(){
		$helper = new Helper();

		if (isset ($_POST['_action'])){
			switch ($_POST['_action']){
				case "addnew":
					$helper->addSkillsmartLookup($_POST,"professionalbodies");
					break;
				case "update":
					$helper->updateSkillsmartLookup($_POST);
					break;
			}
			$this->_redirect ( 'admin/skillsmart-professionalbodies' );
		}

		$list = $helper->getSkillSmartLookups();
		$this->view->assign("lookup", $list['professionalbodies']);
		$this->view->assign("header",t("Professional Bodies"));
	}

	public function skillsmartSupervisedAction(){
		$helper = new Helper();

		if (isset ($_POST['_action'])){
			switch ($_POST['_action']){
				case "addnew":
					$helper->addSkillsmartLookup($_POST,"supervised");
					break;
				case "update":
					$helper->updateSkillsmartLookup($_POST);
					break;
			}
			$this->_redirect ( 'admin/skillsmart-supervised' );
		}

		$list = $helper->getSkillSmartLookups();
		$this->view->assign("lookup", $list['supervised']);
		$this->view->assign("header",t("Supervised"));
	}

	public function skillsmartSupervisedfrequencyAction(){
		$helper = new Helper();

		if (isset ($_POST['_action'])){
			switch ($_POST['_action']){
				case "addnew":
					$helper->addSkillsmartLookup($_POST,"supervisedfrequency");
					break;
				case "update":
					$helper->updateSkillsmartLookup($_POST);
					break;
			}
			$this->_redirect ( 'admin/skillsmart-supervisedfrequency' );
		}

		$list = $helper->getSkillSmartLookups();
		$this->view->assign("lookup", $list['supervisedfrequency']);
		$this->view->assign("header",t("Supervision Frequency"));
	}

	public function skillsmartTrainingAction(){
		$helper = new Helper();

		if (isset ($_POST['_action'])){
			switch ($_POST['_action']){
				case "addnew":
					$helper->addSkillsmartLookup($_POST,"training");
					break;
				case "update":
					$helper->updateSkillsmartLookup($_POST);
					break;
			}
			$this->_redirect ( 'admin/skillsmart-training' );
		}

		$list = $helper->getSkillSmartLookups();
		$this->view->assign("lookup", $list['training']);
		$this->view->assign("header",t("Training received"));
	}

	public function skillsmartFacilitydepartmentAction(){
		$helper = new Helper();

		if (isset ($_POST['_action'])){
			switch ($_POST['_action']){
				case "addnew":
					$helper->addSkillsmartLookup($_POST,"facilitydepartment");
					break;
				case "update":
					$helper->updateSkillsmartLookup($_POST);
					break;
			}
			$this->_redirect ( 'admin/skillsmart-facilitydepartment' );
		}

		$list = $helper->getSkillSmartLookups();
		$this->view->assign("lookup", $list['facilitydepartment']);
		$this->view->assign("header",t("Facility Departments"));
	}

	public function skillsmartCompetencyAction(){
		$helper = new Helper();

		$compid = $this->getSanParam('comp');
		$comp = false;
		if (is_numeric($compid)){
			$comp = true;
		}

		if (isset ($_POST['_action'])){
			// UPDATING COMPETENCY NAME
			switch ($_POST['_action']){
				case "addnew":
					$helper->addSkillsmartCompetency($_POST);
					break;
				case "update":
					$helper->updateSkillsmartCompetency($_POST);
					break;
			}
			$this->_redirect ( 'admin/skillsmart-competency' );
		} elseif (isset ($_POST['_actiondetail'])){
			switch ($_POST['_actiondetail']){
				case "addnew":
					$helper->addSkillsmartCompetencyQuestion($_POST,$compid);
					break;
				case "update":
					$helper->updateSkillsmartCompetencyQuestion($_POST,$compid);
					break;
			}
			$this->_redirect ( 'admin/skillsmart-competency/comp/' . $compid );
		} elseif (isset ($_POST['actionqual'])){
			$helper->skillsmartLinkQualComp($_POST);
			$this->_redirect ( 'admin/skillsmart-competency/comp/' . $compid );
//			var_dump ($_POST['qual']);
//			exit;
		}

		$this->view->assign("showcomp",$comp);
		if (!$comp){
			// GENERAL OVERVIEW OF ALL COMPETENCY NAMES
			$list = $helper->getSkillSmartCompetencies();
			$this->view->assign("lookup", $list);
			$this->view->assign("header",t("Competencies"));
		} else {
			// COMPETENCY SPECIFIC OUTPUT

			$competency = $helper->getSkillSmartCompetencies($compid);
			$questions = $helper->getSkillSmartCompetenciesQuestions($compid);

			$this->view->assign("header","Update competency '" . $competency['label'] . "'");
			$this->view->assign("questions",$questions);

			// GETTING QUALIFICATIONS
			$quals = $helper->skillsmartGetQualifications($compid);
			$this->view->assign("quals",$quals);
			$this->view->assign("compid",$compid);
			$this->view->assign("currentlinks",$helper->skillsmartGetCompetencyLinks($compid));
		}
	}


	public function skillsmartOccupationalCatsAction(){
		$parent = $this->getSanParam('parent');

		if ( $parent or $this->getSanParam('redirect') ) {
			$controller = &$this;
		    $editTable = new EditTableController($controller->getRequest(), $controller->getResponse());
		    $editTable->setParentController($controller);
			$editTable->table   = 'occupational_categories';
			$editTable->fields  = array('category_phrase' => t('Category'));
			$editTable->label   = t('Occupational Category');
			$editTable->dependencies = array('occupational_category_id' => 'person');
			$editTable->where = 'parent_id = '.$parent;
			$editTable->insertExtra = array('parent_id'=>$parent);
			$editTable->allowDefault = true;
			$editTable->execute($controller->getRequest());
		}

		$parentArray = OptionList::suggestionList('occupational_categories', 'category_phrase', false, false, true, 'parent_id IS NULL');
		$this->viewAssignEscaped('parents', $parentArray);
		$this->view->assign('parent', $parent);
	}

	public function skillsmartChwSettingsAction() {

		$labelOrder = array(
			'label_last_name',
			'label_first_name',
			'.label_ps_nationality.',
			'label_ps_national_id',
			'.label_ps_specialty.',
			'label_age',
			'label_gender',
			'label_ps_spouse_name',
		    'label_ps_inst_sponsor',//TA:88
			'.label_highest_qualification_achieved.',
			'label_ps_local_address',
			'label_province',
			'label_phone',
			'label_email',
			'label_ps_marital_status',
			'.label_list_of_modules.',
            'label_ps_class_modules_custom_1',
			'-Workplace-',
			'label_workplace_name',
			'label_workplace_address_1',
            'label_workplace_address_2',
			'label_ps_person_in_charge',
			'label_commencing_date_for_workplace',
			'label_end_date_for_workplace',
			'label_name_of_employer',
			'label_employer_address_1',
            'label_employer_address_2',
			'label_telephone_number',
			'label_name_of_contact_person',
			'label_email_address',
			'-Qualification-',
			'.label_reason_for_enrollment.',
			'label_ps_custom_field_2',
			'label_ps_custom_field_3',
			'.label_reason_for_separation.',
			'label_date_of_enrollment',
			'label_date_of_separation',
			'label_date_of_final_integrated_external_assessment',
			'.label_ps_program_enrolled_in.',
			'label_ps_last_university_attended',
			'label_contact_number_of_assessment_centre_site',
			'.label_ps_religious_denomination.',
			'-Qualified Learners-',
			'label_date_certificate_was_received_from_the_aqp',
			'label_certificate_number',
			'label_date_learner_received_certificate',
			'-Certificate Issuers-',
			'label_certificate_issuer',
			'label_certificate_issuer_name',
			'label_certificate_issuer_phone',
			'label_certificate_issuer_email',
			'label_certificate_issuer_logo',
		);

		$dropdownLinks = array(
			'.label_ps_nationality.'                 => '/admin/preservice-nationalities',
			'.label_ps_specialty.'                   => '/admin/tutorspecialty',
			'.label_highest_qualification_achieved.' => '/admin/qualifications',
			'.label_list_of_modules.'                => '/admin/prior-learning',
			'.label_reason_for_enrollment.'          => '/admin/preservice-joindropreasons',
			'.label_reason_for_separation.'          => '/admin/preservice-joindropreasons',
			'.label_ps_program_enrolled_in.'         => '/admin/preservice-cadres',
			'.label_ps_religious_denomination.'      => '/admin/preservice-religion',
		);

		$labelNames = array(
			'label_last_name'                                    => 'Last Name',
			'label_first_name'                                   => 'First Name',
			'label_ps_nationality'                               => 'ps nationality',
			'label_ps_national_id'                               => 'ps national id',
			'label_ps_specialty'                                 => 'ps specialty',
			'label_age'                                          => 'Age',
			'label_gender'                                       => 'Gender',
			'label_ps_spouse_name'                               => 'ps spouse name',
		    'label_ps_inst_sponsor'                              => 'ps inst sponsor',//TA:88
			'label_highest_qualification_achieved'               => 'Highest Qualification Achieved',
			'label_ps_local_address'                             => 'ps local address',
			'label_province'                                     => 'Province',
			'label_phone'                                        => 'Phone',
			'label_email'                                        => 'Email',
			'label_ps_marital_status'                            => 'ps marital status',
			'label_list_of_modules'                              => 'List of Modules',
            'label_ps_class_modules_custom_1'                    => 'ps class modules custom 1',

            'label_workplace_name'                               => 'Workplace Name',
            'label_workplace_address_1'                          => 'Workplace Address 1',
            'label_workplace_address_2'                          => 'Workplace Address 2',
            'label_ps_person_in_charge'                          => 'ps person in charge',
			'label_commencing_date_for_workplace'                => 'Commencing Date for Workplace',
			'label_end_date_for_workplace'                       => 'End Date for Workplace',
			'label_name_of_employer'                             => 'Name of Employer',
            'label_employer_address_1'                           => 'Address of Employer 1',
            'label_employer_address_2'                           => 'Address of Employer 2',
			'label_telephone_number'                             => 'Telephone Number',
			'label_name_of_contact_person'                       => 'Name of Contact Person',
			'label_email_address'                                => 'Email Address',

			'label_reason_for_enrollment'                        => 'Reason for Enrollment',
			'label_ps_custom_field_2'                            => 'ps custom field 2',
			'label_ps_custom_field_3'                            => 'ps custom field 3',
			'label_reason_for_separation'                        => 'Reason for Separation',
			'label_date_of_enrollment'                           => 'Date of Enrollment',
			'label_date_of_separation'                           => 'Date of Separation',
			'label_date_of_final_integrated_external_assessment' => 'Date of Final Integrated External Assessment',
			'label_ps_program_enrolled_in'                       => 'ps program enrolled in',
			'label_ps_last_university_attended'                  => 'ps last university attended',
			'label_contact_number_of_assessment_centre_site'     => 'Contact Number of Assessment Centre/Site',
			'label_ps_religious_denomination'                    => 'ps religious denomination',

            'label_date_certificate_was_received_from_the_aqp'   => 'Date Certificate was Received From the AQP',
			'label_certificate_number'                           => 'Certificate Number',
			'label_date_learner_received_certificate'            => 'Date Learner Received Certificate',

			'label_certificate_issuer'                           => 'Certificate Issuer',
			'label_certificate_issuer_name'                      => 'Certificate Issuer Name',
			'label_certificate_issuer_phone'                     => 'Certificate Issuer Phone Number',
			'label_certificate_issuer_email'                     => 'Certificate Issuer Email',
			'label_certificate_issuer_logo'                      => 'Certificate Issuer Logo',

		);


		# TODO: add Certificate Issuer translation
		if ($this->getRequest()->isPost()) {
			$params = $this->getAllParams();

			if (isset($params['operation']) && $params['operation']) {
				$db = $this->dbfunc();

				$op = null;
				if ($params['operation'] === "save") {
					if ($params['issuer_id'] === "0") {
						# TODO: update database column id to be auto-increment
						$db->insert('certificate_issuers',
							array('issuer_name' => $params['issuer_name'],
								'issuer_email' => $params['issuer_email'],
								'issuer_phone_number' => $params['issuer_phone_number'],
								# TODO: change database column name to issuer_logo_id
								'issuer_logo_file_id' => $params['issuer_logo']
							)
						);
					}
					else {
						$db->update('certificate_issuers',
							array('issuer_name' => $params['issuer_name'],
								'issuer_email' => $params['issuer_email'],
								'issuer_phone_number' => $params['issuer_phone_number'],
								# TODO: change database column name to issuer_logo_id
								'issuer_logo_file_id' => $params['issuer_logo']
							),
							array('id = ?' => $params['issuer_id'])
						);
					}
				}
				else if ($params['operation'] === "delete") {
					$db->delete('certificate_issuers', array('id = ?' => $params['issuer_id']));
				}
			} 
			else {
				require_once('models/table/Translation.php');

				$tranTable = new Translation();
				$translations = $tranTable->getAll();

				foreach ($params as $k => $v) {
					if (strpos($k, 'label_') === 0) {
						try {
							if (!array_key_exists($labelNames[$k], $translations)) {
								// This key_phrase is not in the translation table, so add it
								$tranTable->insert(array('phrase' => $v, 'key_phrase' => $labelNames[$k]));
							} else {
								$tranTable->update(array('phrase' => $v), "key_phrase = '{$labelNames[$k]}'");
							}
						} catch (Zend_Exception $e) {
							error_log($e);
						}
					}
				}
				$this->updateTranslations();
			}
		}

		$db = $this->dbfunc();
		$s = $db->select()
			->from('certificate_issuers');
		$issuers = array('0' => array('id' => "0", 'issuer_name' => t('New') . ' ' . t('Certificate Issuer'))) + $db->fetchAssoc($s);


		$s = $db->select()
			->from('file', array('id', 'filename'))
			->where("parent_table = 'certificate_issuers' AND filemime IN ('image/png', 'image/jpeg', 'image/gif')");

		$o = $s->__toString();
		$images = array('0' => '--' . t('choose') . '--') + $db->fetchPairs($s);

		$this->view->assign('issuers', $issuers);
		$this->view->assign('issuer_images', $images);
		$this->view->assign('labelOrder', $labelOrder);
		$this->view->assign('labelNames', $labelNames);
		$this->view->assign('dropdownLinks', $dropdownLinks);
	}

	public function employeeSettingsAction()
	{

		require_once('models/table/System.php');
		$sysTable = new System();

		// For "Labels"
		// same logic as other Settings pages - except the employee_header setting below
		require_once('models/table/Translation.php');
		$labelNames = array( // input name => key_phrase
			'label_employee'                 => 'Employee',
			'label_employees'                => 'Employees',
			'label_employer'                 => 'Employer',
			'label_employee_code'            => 'Employee Code',
			'label_partner'                  => 'Partner',
			'label_sub_partner'              => 'Sub Partner',
			'label_type'                     => 'Type of Partner',
			'label_funder'                   => 'Funder',
			'label_full_time'                => 'Full Time',
			'label_base'                     => 'Employee Based at',
			'label_funded_hours_per_week'    => 'Funded hours per week',
			'label_cadre'                    => 'Staff Cadre',
			'label_staff_category'           => 'Staff Category',
			'label_annual_cost'              => 'Annual Cost',
			'label_primary_role'             => 'Primary Role',
			'label_importance'               => 'Importance',
			'label_intended_transition'      => 'Intended Transition',
			'label_incoming_partner'         => 'Incoming partner',
			'label_relationship'             => 'Relationship',
			'label_referral_mechanism'       => 'Referral Mechanism',
			'label_chw_supervisor'           => 'CHW Supervisor',
			'label_trainings_provided'       => 'Trainings provided',
			'label_courses_completed'        => 'Courses Completed',
			'label_other_id'                 => 'Other ID',
			'label_disability'               => 'Disability',
			'label_disability_comments'      => 'Disability Comments',
			'label_nationality'              => 'Employee Nationality',
			'label_race'                     => 'Race',
			'label_date_of_birth'            => 'Date of Birth',
			'label_registration_number'      => 'Registration Number',
			'label_salary'                   => 'Salary',
			'label_benefits'                 => 'Benefits',
			'label_additional_expenses'      => 'Additional Expenses',
			'label_stipend'                  => 'Stipend',
			'label_currency'                 => 'Employee Local Currency',
			'label_hours_per_mechanism'      => 'Hours per Mechanism',
			'label_annual_cost_to_mechanism' => 'Annual Cost to Mechanism',
		);
		$checkboxFields = array(
			'check_partner'                  => 'display_employee_partner',
			'check_sub_partner'              => 'display_employee_sub_partner',
			'check_type'                     => 'display_partner_type',
			'check_funder'                   => 'display_employee_funder',
			'check_full_time'                => 'display_employee_full_time',
			'check_base'                     => 'display_employee_base',
			'check_site_type'                => 'display_employee_site_type',
			'check_funded_hours_per_week'    => 'display_employee_funded_hours_per_week',
			'check_staff_category'           => 'display_employee_staff_category',
			'check_annual_cost'              => 'display_employee_annual_cost',
			'check_primary_role'             => 'display_employee_primary_role',
			'check_importance'               => 'display_employee_importance',
			'check_contract_end_date'        => 'display_employee_contract_end_date',
			'check_agreement_end_date'       => 'display_employee_agreement_end_date',
			'check_intended_transition'      => 'display_employee_intended_transition',
			'check_transition_confirmed'     => 'display_employee_transition_confirmed',
			'check_transition_complete'      => 'display_employee_complete_transition',
			'check_transition_complete_date' => 'display_employee_actual_transition_date',
			'check_incoming_partner'         => 'display_employee_incoming_partner',
			'check_relationship'             => 'display_employee_relationship',
			'check_referral_mechanism'       => 'display_employee_referral_mechanism',
			'check_chw_supervisor'           => 'display_employee_chw_supervisor',
			'check_trainings_provided'       => 'display_employee_trainings_provided',
			'check_courses_completed'        => 'display_employee_courses_completed',
			'check_site_name'                => 'display_employee_site_name',
			'check_employee_header'          => 'display_employee_employee_header',
			'check_other_id'                 => 'display_employee_other_id',
			'check_disability'               => 'display_employee_disability',
			'check_nationality'              => 'display_employee_nationality',
			'check_race'                     => 'display_employee_race',
			'check_date_of_birth'            => 'display_employee_dob',
			'check_registration_number'      => 'display_employee_registration_number',
			'check_salary'                   => 'display_employee_salary',
			'check_benefits'                 => 'display_employee_benefits',
			'check_additional_expenses'      => 'display_employee_additional_expenses',
			'check_stipend'                  => 'display_employee_stipend',
			'check_hours_per_mechanism'      => 'display_hours_per_mechanism',
			'check_annual_cost_to_mechanism' => 'display_annual_cost_to_mechanism',
		);

		if($this->getRequest()->isPost()) { // Update db
            $updateData = array();

            $params = $this->getAllParams();
            // update translation labels
            if (isset($params['reset_capture_dates']) && $params['reset_capture_dates']) {
                $db = $this->dbfunc();
                $db->update('partner', array('capture_complete_date' => null));
            } else {
                $tranTable = new Translation();
                foreach ($labelNames as $input_key => $db_key) {

                    if (isset($params[$input_key]) && $params[$input_key]) {
                        try {
                            $tranTable->update(
                                array('phrase' => $params[$input_key]),
                                "key_phrase = '$db_key'"
                            );
                            $this->viewAssignEscaped($input_key, $params[$input_key]);
                        } catch (Zend_Exception $e) {
                            error_log($e);
                        }
                    }
                }
                // update _system (checkboxes)
                foreach ($checkboxFields as $input_key => $db_field) {
                    $value = ($params[$input_key] == NULL) ? 0 : 1;
                    $updateData[$db_field] = $value;
                    $this->view->assign($input_key, $value);
                }
                $updateData['employee_header'] = $params['employee_header'];
                $this->view->assign('employee_header', $params['employee_header'] ? $params['employee_header'] : '');
                $sysTable->update($updateData, '');
            }
        }

		// redirect to next page
		if($this->getParam('redirect')) {
			header("Location: " . $this->getParam('redirect'));
			exit;
		} else if($this->getParam('saveonly')) {
			$status = ValidationContainer::instance();
			$status->setStatusMessage(t('Your settings have been updated.'));
		    $this->view->assign('status', $status);
		}

        // checkboxes
        $sysRows = $sysTable->fetchRow($sysTable->select()->limit(1));
        $this->view->assign('employee_header', isset($sysRows->employee_header) ? $sysRows->employee_header : '');
        foreach($checkboxFields as $input_key => $field_key) {
            if ( isset($sysRows->$field_key) )
                $this->view->assign($input_key, $sysRows->$field_key);
        }
        // labels
        $t = Translation::getAll();
        foreach($labelNames as $input_key => $db_key) {
            $this->viewAssignEscaped($input_key, $t[$db_key]);
        }
	}

	public function employeePartnerTypeAction()
	{

		/* edit table */
		$controller = &$this;
        $editTable = new EditTableController($controller->getRequest(), $controller->getResponse());
        $editTable->setParentController($controller);
		$editTable->table   = 'partner_type_option';
		$editTable->fields  = array('type_phrase' => t('Partner Type'));
		$editTable->label   = t('Partner Type');
		$editTable->dependencies = array('partner_type_option_id' => 'partner');
		$editTable->execute($controller->getRequest());
	}

	public function employeeCategoryAction()
	{

		/* edit table */
		$controller = &$this;
        $editTable = new EditTableController($controller->getRequest(), $controller->getResponse());
        $editTable->setParentController($controller);
		$editTable->table   = 'employee_category_option';
		$editTable->fields  = array('category_phrase' => t('Staff Category'));
		$editTable->label   = t('Staff Category');
		$editTable->dependencies = array('employee_category_option_id' => 'employee');
		$editTable->execute($controller->getRequest());
	}

	public function employeeBaseAction()
	{

		/* edit table */
		$controller = &$this;
        $editTable = new EditTableController($controller->getRequest(), $controller->getResponse());
        $editTable->setParentController($controller);
		$editTable->table   = 'employee_base_option';
		$editTable->fields  = array('base_phrase' => t('Base'));
		$editTable->label   = t('Base');
		$editTable->dependencies = array('employee_base_option_id' => 'employee');
		$editTable->execute($controller->getRequest());
	}

	public function employeeSiteTypeAction()
	{

		/* edit table */
		$controller = &$this;
        $editTable = new EditTableController($controller->getRequest(), $controller->getResponse());
        $editTable->setParentController($controller);
		$editTable->table   = 'employee_site_type_option';
		$editTable->fields  = array('site_type_phrase' => t('Type'));
		$editTable->label   = t('Type');
		$editTable->dependencies = array('facility_type_option_id' => 'employee');
		$editTable->execute($controller->getRequest());
	}

	public function employeeFullTimeAction()
	{

		/* edit table */
		$controller = &$this;
        $editTable = new EditTableController($controller->getRequest(), $controller->getResponse());
        $editTable->setParentController($controller);
		$editTable->table   = 'employee_fulltime_option';
		$editTable->fields  = array('fulltime_phrase' => t('Full Time'));
		$editTable->label   = t('Status');
		$editTable->dependencies = array('employee_fulltime_option_id' => 'employee');
		$editTable->execute($controller->getRequest());
	}

	public function peopleRaceAction()
	{

		/* edit table */
		$controller = &$this;
        $editTable = new EditTableController($controller->getRequest(), $controller->getResponse());
        $editTable->setParentController($controller);
		$editTable->table   = 'person_race_option';
		$editTable->fields  = array('race_phrase' => t('Race'));
		$editTable->label   = t('Race');
		$editTable->dependencies = array('race_option_id' => 'employee');
		$editTable->execute($controller->getRequest());
	}

	public function employeeQualificationAction()
	{

		/* edit table */
		$controller = &$this;
        $editTable = new EditTableController($controller->getRequest(), $controller->getResponse());
        $editTable->setParentController($controller);
		$editTable->table   = 'employee_qualification_option';
		$editTable->fields  = array('qualification_phrase' => t('Qualification'));
		$editTable->label   = t('Qualification');
		$editTable->dependencies = array('employee_qualification_option_id' => 'employee');
		$editTable->execute($controller->getRequest());
	}

	public function employeeRoleAction()
	{

		/* edit table */
		$controller = &$this;
        $editTable = new EditTableController($controller->getRequest(), $controller->getResponse());
        $editTable->setParentController($controller);
		$editTable->table   = 'employee_role_option';
		$editTable->fields  = array('role_phrase' => t('Primary Role'));
		$editTable->label   = t('Primary Roles for employees');
		$editTable->dependencies = array('employee_role_option_id' => 'employee');
		$editTable->execute($controller->getRequest());
	}

	public function employeeTransitionAction()
	{

		/* edit table */
		$controller = &$this;
        $editTable = new EditTableController($controller->getRequest(), $controller->getResponse());
        $editTable->setParentController($controller);
		$editTable->table   = 'employee_transition_option';
		$editTable->fields  = array('transition_phrase' => t('Intended Transition'));
		$editTable->label   = t('Intended Transitions');
		$editTable->dependencies = array('employee_transition_option_id' => 'employee');
		$editTable->execute($controller->getRequest());
	}

	public function employeeRelationshipAction()
	{

		/* edit table */
		$controller = &$this;
        $editTable = new EditTableController($controller->getRequest(), $controller->getResponse());
        $editTable->setParentController($controller);
		$editTable->table   = 'employee_relationship_option';
		$editTable->fields  = array('relationship_phrase' => t('Relationship'));
		$editTable->label   = t('Relationship');
		$editTable->dependencies = array('relationship_option_id' => 'employee_to_relationship');
		$editTable->execute($controller->getRequest());
	}

	public function employeeReferralAction()
	{

		/* edit table */
		$controller = &$this;
        $editTable = new EditTableController($controller->getRequest(), $controller->getResponse());
        $editTable->setParentController($controller);
		$editTable->table   = 'employee_referral_option';
		$editTable->fields  = array('referral_phrase' => t('Referral Mechanism'));
		$editTable->label   = t('Mechanism');
		$editTable->dependencies = array('referral_option_id' => 'employee_to_referral');
		$editTable->execute($controller->getRequest());
	}

	public function employeeTrainingProvidedAction()
	{

		/* edit table */
		$controller = &$this;
        $editTable = new EditTableController($controller->getRequest(), $controller->getResponse());
        $editTable->setParentController($controller);
		$editTable->table   = 'employee_training_provided_option';
		$editTable->fields  = array('training_provided_phrase' => t('Training Provided'));
		$editTable->label   = t('Training Provided');
		$editTable->dependencies = array('employee_training_provided_option_id' => 'employee');
		$editTable->execute($controller->getRequest());
	}

	public function employeePartnerFunderAction()
	{

		/* edit table */
		$controller = &$this;
        $editTable = new EditTableController($controller->getRequest(), $controller->getResponse());
        $editTable->setParentController($controller);
		$editTable->table   = 'partner_funder_option';
		$editTable->fields  = array('funder_phrase' => t('Funder'));
		$editTable->label   = t('Funder');
		$editTable->dependencies = array('partner_funder_option_id' => 'subpartner_to_funder_to_mechanism');
		$editTable->execute($controller->getRequest());
	}

	public function employeePartnerImportanceAction()
	{

		/* edit table */
		$controller = &$this;
        $editTable = new EditTableController($controller->getRequest(), $controller->getResponse());
        $editTable->setParentController($controller);
		$editTable->table   = 'partner_importance_option';
		$editTable->fields  = array('importance_phrase' => t('Importance'));
		$editTable->label   = t('Importance');
		$editTable->dependencies = array('partner_importance_option_id' => 'partner');
		$editTable->execute($controller->getRequest());
	}

	public function employeeAgencyAction()
	{

		/* edit table */
		$controller = &$this;
        $editTable = new EditTableController($controller->getRequest(), $controller->getResponse());
        $editTable->setParentController($controller);
		$editTable->table   = 'agency_option';
		$editTable->fields  = array('agency_phrase' => t('Agency'));
		$editTable->label   = t('Agency');
		// $editTable->dependencies = array('partner_importance_option_id' => 'partner');
		$editTable->execute($controller->getRequest());
	}

	public function employeeMechanismAction()
	{
        $db = $this->dbfunc();
        $status = ValidationContainer::instance();

        if ($this->getRequest()->isPost()) {
            $params = $this->getAllParams();

            if (isset($params['mechanism_operation']) && $params['mechanism_operation']) {
                if ($params['mechanism_operation'] === 'new') {

                    if ($status->isValidDateDDMMYYYY('mechanism_end_date', t('Funding End Date'), $params['mechanism_end_date'])) {
                        $d = DateTime::createFromFormat('d/m/Y', $params['mechanism_end_date']);
                        $params['mechanism_end_date'] = $d->format('Y-m-d');

                        $db->insert('mechanism_option',
                            array('mechanism_phrase' => $params['mechanism_phrase'],
                                'owner_id' => $params['partner_id'],
                                'funder_id' => $params['funder_id'],
                                'external_id' => $params['external_id'],
                                'end_date' => $params['mechanism_end_date']
                            ));
                        $id = $db->lastInsertId();
                        $db->insert('link_mechanism_partner', array('partner_id' => $params['partner_id'],
                            'mechanism_option_id' => $id));
                    }
                }
                elseif ($params['mechanism_operation'] === 'update') {
                    $status = ValidationContainer::instance();
                    if ($status->isValidDateDDMMYYYY('mechanism_end_date', t('Funding End Date'), $params['mechanism_end_date'])) {

                        if (isset($params['partner_id']) && $params['partner_id']) {
                            $id = $db->fetchOne($db->select()->from('mechanism_option', array('owner_id'))->where('mechanism_option.id = ?', $params['mechanism_id']));

                            if ($id != $params['partner_id']) {
                                $db->update('link_mechanism_partner', array('partner_id' => $params['partner_id']),
                                    "link_mechanism_partner.mechanism_option_id = {$params['mechanism_id']}");
                            }
                        }

                        $d = DateTime::createFromFormat('d/m/Y', $params['mechanism_end_date']);
                        $params['mechanism_end_date'] = $d->format('Y-m-d');

                        $db->update('mechanism_option',
                            array('mechanism_phrase' => $params['mechanism_phrase'],
                                'owner_id' => $params['partner_id'],
                                'funder_id' => $params['funder_id'],
                                'external_id' => $params['external_id'],
                                'end_date' => $params['mechanism_end_date']
                            ),
                            "mechanism_option.id = {$params['mechanism_id']}"
                        );
                    }
                }
                elseif ($params['mechanism_operation'] === 'delete') {
                    $db->delete('mechanism_option', "mechanism_option.id = {$params['mechanism_id']}");
                    $db->delete('link_mechanism_partner', "link_mechanism_partner.mechanism_option_id = {$params['mechanism_id']}");
                    $status->setStatusMessage(t('Mechanism') . ' ' . t('Deleted') . ': ' . $params['mechanism_phrase']);
                }
            }
        }

        $choose = array("0" => '--' . t("choose") . '--');

        $partners = $choose + $db->fetchPairs($db->select()
                ->from('partner', array('id', 'partner'))
                ->order('partner ASC')
            );

        $funders = $choose + $db->fetchPairs($db->select()
                ->from('partner_funder_option', array('id', 'funder_phrase'))
                ->order('funder_phrase ASC')
            );


        $select = $db->select()
            ->from('mechanism_option', array())
            ->joinLeft('partner', 'mechanism_option.owner_id = partner.id', array())
            ->joinLeft('partner_funder_option', 'partner_funder_option.id = mechanism_option.funder_id', array())
            ->order('mechanism_phrase ASC');

        $select->columns(array('mechanism_option.id', 'mechanism_phrase', 'partner.partner',
            'partner_funder_option.funder_phrase', 'external_id', 'end_date' => new Zend_Db_Expr("DATE_FORMAT(mechanism_option.end_date, '%d/%m/%Y')")));

        $s = $select->__toString();

        $headers = array(t('ID'), t('Mechanism'), t('Prime') . ' ' . t('Partner'), t('Funder'), t('Mechanism') . ' ' . t('ID'), t('Funding End Date'));
        $output = $db->fetchAll($select);

        $select->columns(array('mechanism_option.funder_id', 'mechanism_option.owner_id'));
        $mechanisms = $db->fetchAssoc($select);

        $this->view->assign('headers', $headers);
        $this->view->assign('output', $output);
        $this->view->assign('mechanisms', $mechanisms);
        $this->view->assign('partners', $partners);
        $this->view->assign('funders', $funders);
        $this->view->assign('status', $status);
        $this->view->assign('pageTitle', t('Mechanism'));

	}

	public function employeeMechanismToSubpartnerAction()
    {
        $db = $this->dbfunc();

        if ($this->getRequest()->isPost()) {
            $params = $this->getAllParams();

            if ($params['outputType'] == 'json') {
                $id = $this->getSanParam('id');
                $ret = array();

                if (!$id) {
                    $ret['error'] = "No record id %s found.";
                }
                elseif ($this->getSanParam('delete') == 1) {
                    $rv = $db->delete('link_mechanism_partner', "id = $id");
                    if (!$rv) {
                        $ret['error'] = "Could not delete %s";
                    } else {
                        $ret['msg'] = 'ok';
                    }
                }
                $this->sendData($ret);
            }
        }

        // fill in null values in the partner column so the edittable visibly updates when a user selects a
        // value for a field that came in null - probably a YUI DataTable beta quirk
        $sql = 'SELECT
            link_mechanism_partner.id,
            mechanism_option.mechanism_phrase,
            partner.partner,
            link_mechanism_partner.end_date
            FROM
            link_mechanism_partner
            INNER JOIN mechanism_option ON link_mechanism_partner.mechanism_option_id = mechanism_option.id
            INNER JOIN partner ON link_mechanism_partner.partner_id = partner.id
            WHERE mechanism_option.owner_id != link_mechanism_partner.partner_id
            ORDER BY mechanism_phrase ASC;
            ';
        $tableRows = $db->fetchAll($sql);

        require_once 'views/helpers/EditTableHelper.php';

        $customColDefs = array(
            'mechanism_phrase' => "sortable:true",
            'partner'          => "sortable:true",
            // formatDate is defined in the view file
            'end_date'         => "sortable:true, formatter:formatDate"
        );

        $this->view->assign('pageTitle', t('Sub-Partner').space.t('Mechanism'));
        $columnNames = array('mechanism_phrase' => t('Mechanism'), 'partner' => t('Sub-Partner'),
            'end_date' => t('Funding End Date'));
        $this->view->assign('editTable', EditTableHelper::generateHtml('SubPartner', $tableRows, $columnNames,
            $customColDefs, array(), true));

    }

	public function employeeSubpartnerToFunderToMechanismAction()
	{

		/* edit table */
		$controller = &$this;
        $editTable = new EditTableController($controller->getRequest(), $controller->getResponse());
        $editTable->setParentController($controller);
		$editTable->table   = 'subpartner_to_funder_to_mechanism';
		$editTable->fields  = array('id' => t('ID'), 'subpartner_id' => t('Subpartner'), 'partner_funder_option_id' => t('Funder'), 'mechanism_option_id' => t('Mechanism'), 'funding_end_date' => t('Funding End Date'));
		$editTable->label   = t('Funding  Mechanisms');
		$editTable->dependencies = array('id' => 'partner_to_subpartner_to_funder_to_mechanism');
		$editTable->execute($controller->getRequest());

	}


    public function employeeBuildFundingAction()
    {

        require_once('models/table/Partner.php');

        if ( $this->getRequest()->isPost() ) {
			$db     = $this->dbfunc();
            $status = ValidationContainer::instance();
            $params = $this->getAllParams();

			// test for all values
            if (!($params['subPartner'] && $params['mechanism'] && $params['end_date'])) {
                $status->addError('', t('All fields') . space . t('are required'));
            }
            else {
                // prepare date for database
                $params['end_date'] = $this->_euro_date_to_sql($params['end_date']);

                $subpartnerMechanismTable = new ITechTable(array('name' => 'link_mechanism_partner'));

                foreach ($params['subPartner'] as $value) {

                    $query = $subpartnerMechanismTable->select()
                        ->where('partner_id = ?', $value)
                        ->where('mechanism_option_id = ?', $params['mechanism']);

                    $row = $subpartnerMechanismTable->fetchRow($query);
                    if ($row) {
                        $data = array('end_date' => $params['end_date']);
                        $subpartnerMechanismTable->update($data, "id = $row->id");

                    }
                    else {
                        $data = array(
                            'partner_id' => $value,
                            'mechanism_option_id' => $params['mechanism'],
                            'end_date' => $params['end_date']
                        );
                        $result = $subpartnerMechanismTable->insert($data);

                        if (!$result) {
                            $status->addError('', t('Unable to store all subpartners.'));
                            break;
                        }
                    }
                }

                if ( $status->hasError() ) {
                    $status->setStatusMessage(t('That funding mechanism could not be saved.'));
                } else {
                    $status->setStatusMessage(t('Mechanism data saved.'));
                    $this->_redirect("admin/employee-build-funding");
                }
            }
        }

        $helper = new Helper();

        $subPartner = $helper->getAllSubPartners();
        $this->viewAssignEscaped ( 'subPartner', $subPartner );

        $mechanism = $helper->getAllMechanisms();
        $this->viewAssignEscaped ( 'mechanism', $mechanism );

    } //employeeBuildFundingAction

	public function employeeFunderFilterAction()
	{

		require_once('views/helpers/Location.php'); // funder stuff
		require_once('models/table/Partner.php');

		if ( $this->getRequest()->isPost() ) {
			$db     = $this->dbfunc();
			$status = ValidationContainer::instance ();
			$params = $this->getAllParams();

			// prepare date for database
			$params['funding_end_date'] = $this->_array_me($params['funding_end_date']);

			foreach ($params['funding_end_date'] as $i => $value)
				$params['funding_end_date'][$i] = $this->_euro_date_to_sql($value);

			// test for all values
			if(!($params['subPartner'] && $params['partnerFunder'] && $params['mechanism'] && $params['funding_end_date'][0]))
				$status->addError('', t ( 'All fields' ) . space . t('are required'));

			// test for existing record
			$id = false;
			if ($id) {
				$status->addError('', t('Record exists'));
			}


			if ( $status->hasError() )
				$status->setStatusMessage( t('That funding mechanism could not be saved.') );

			else {
				//save
				$sfm = new ITechTable(array('name' => 'subpartner_to_funder_to_mechanism'));


				$data = array(
					'subpartner_id'  => $params['subPartner'],
					'partner_funder_option_id' => $params['partnerFunder'],
					'mechanism_option_id' => $params['mechanism'],
					'funding_end_date' => $params['funding_end_date'][0],
				);

				$insert_result = $sfm->insert($data);
				$status->setStatusMessage( t('The funding mechanism was saved.') );
				$this->_redirect("admin/employee-build-funding");
			}
		}

		$helper = new Helper();

		$subPartner = $helper->getAllSubPartners();
		$this->viewAssignEscaped ( 'subPartner', $subPartner );



		$partnerFunder = $helper->getFunder();
		$this->viewAssignEscaped ( 'partnerFunder', $partnerFunder );

		$mechanism = $helper->getMechanism();
		$this->viewAssignEscaped ( 'mechanism', $mechanism );


	}

	public function employeeSfmFilterAction()
	{

		require_once('views/helpers/Location.php'); // funder stuff
		require_once('models/table/Partner.php');

		if ( $this->getRequest()->isPost() ) {
			$db     = $this->dbfunc();
			$status = ValidationContainer::instance ();
			$params = $this->getAllParams();

			// prepare date for database
			$params['funding_end_date'] = $this->_array_me($params['funding_end_date']);

			foreach ($params['funding_end_date'] as $i => $value)
				$params['funding_end_date'][$i] = $this->_euro_date_to_sql($value);

			// test for all values
			if(!($params['subPartner'] && $params['partnerFunder'] && $params['mechanism'] && $params['funding_end_date'][0]))
				$status->addError('', t ( 'All fields' ) . space . t('are required'));

			// test for existing record
			//$id = $this->_findOrCreateSaveGeneric('partner_to_funder_to_mechanism', $params);
			$id = false;
			if ($id) {
				$status->addError('', t('Record exists'));
			}


			if ( $status->hasError() )
				$status->setStatusMessage( t('That funding mechanism could not be saved.') );

			else {
				//save
				$sfm = new ITechTable(array('name' => 'subpartner_to_funder_to_mechanism'));


				$data = array(
					'subpartner_id'  => $params['subPartner'],
					'partner_funder_option_id' => $params['partnerFunder'],
					'mechanism_option_id' => $params['mechanism'],
					'funding_end_date' => $params['funding_end_date'][0],
				);

				$insert_result = $sfm->insert($data);
				$status->setStatusMessage( t('The funding mechanism was saved.') );
				$this->_redirect("admin/employee-build-funding");
			}
		}

		$helper = new Helper();

		$subPartner = $helper->getSfmSubPartner();
		$this->viewAssignEscaped ( 'subPartner', $subPartner );

		$partnerFunder = $helper->getSfmFunder();
		$this->viewAssignEscaped ( 'partnerFunder', $partnerFunder );

		$mechanism = $helper->getSfmMechanism();
		$this->viewAssignEscaped ( 'mechanism', $mechanism );

	}

	public function employeePsfmFilterAction()
	{

		require_once('views/helpers/Location.php'); // funder stuff
		require_once('models/table/Partner.php');

		if ( $this->getRequest()->isPost() ) {
			$db     = $this->dbfunc();
			$status = ValidationContainer::instance ();
			$params = $this->getAllParams();

			// prepare date for database
			$params['funding_end_date'] = $this->_array_me($params['funding_end_date']);

			foreach ($params['funding_end_date'] as $i => $value)
				$params['funding_end_date'][$i] = $this->_euro_date_to_sql($value);

			// test for all values
			if(!($params['subPartner'] && $params['partnerFunder'] && $params['mechanism'] && $params['funding_end_date'][0]))
				$status->addError('', t ( 'All fields' ) . space . t('are required'));

			// test for existing record
			//$id = $this->_findOrCreateSaveGeneric('partner_to_funder_to_mechanism', $params);
			$id = false;
			if ($id) {
				$status->addError('', t('Record exists'));
			}


			if ( $status->hasError() )
				$status->setStatusMessage( t('That funding mechanism could not be saved.') );

			else {
				//save
				$sfm = new ITechTable(array('name' => 'subpartner_to_funder_to_mechanism'));


				$data = array(
					'subpartner_id'  => $params['subPartner'],
					'partner_funder_option_id' => $params['partnerFunder'],
					'mechanism_option_id' => $params['mechanism'],
					'funding_end_date' => $params['funding_end_date'][0],
				);

				$insert_result = $sfm->insert($data);
				$status->setStatusMessage( t('The funding mechanism was saved.') );
				$this->_redirect("admin/employee-build-funding");
			}
		}

		$helper = new Helper();

		$partner = $helper->getPsfmPartner();
		$this->viewAssignEscaped ( 'partner', $partner );

		$subPartner = $helper->getPsfmSubPartner();
		$this->viewAssignEscaped ( 'subPartner', $subPartner );

		$partnerFunder = $helper->getPsfmFunder();
		$this->viewAssignEscaped ( 'partnerFunder', $partnerFunder );


		$mechanism = $helper->getPsfmMechanism();
		$this->viewAssignEscaped ( 'mechanism', $mechanism );

	}

	public function employeeEpsfmFilterAction()
	{

		require_once('views/helpers/Location.php'); // funder stuff
		require_once('models/table/Partner.php');

		if ( $this->getRequest()->isPost() ) {
			$db     = $this->dbfunc();
			$status = ValidationContainer::instance ();
			$params = $this->getAllParams();

			// prepare date for database
			$params['funding_end_date'] = $this->_array_me($params['funding_end_date']);

			foreach ($params['funding_end_date'] as $i => $value)
				$params['funding_end_date'][$i] = $this->_euro_date_to_sql($value);

			// test for all values
			if(!($params['subPartner'] && $params['partnerFunder'] && $params['mechanism'] && $params['funding_end_date'][0]))
				$status->addError('', t ( 'All fields' ) . space . t('are required'));

			// test for existing record
			//$id = $this->_findOrCreateSaveGeneric('partner_to_funder_to_mechanism', $params);
			$id = false;
			if ($id) {
				$status->addError('', t('Record exists'));
			}


			if ( $status->hasError() )
				$status->setStatusMessage( t('That funding mechanism could not be saved.') );

			else {
				//save
				$sfm = new ITechTable(array('name' => 'subpartner_to_funder_to_mechanism'));


				$data = array(
					'subpartner_id'  => $params['subPartner'],
					'partner_funder_option_id' => $params['partnerFunder'],
					'mechanism_option_id' => $params['mechanism'],
					'funding_end_date' => $params['funding_end_date'][0],
				);

				$insert_result = $sfm->insert($data);
				$status->setStatusMessage( t('The funding mechanism was saved.') );
				$this->_redirect("admin/employee-build-funding");
			}
		}

		$helper = new Helper();

		$employee = $helper->getEpsfmEmployee();
		$this->viewAssignEscaped ( 'employee', $employee );

		$partner = $helper->getEpsfmPartner();
		$this->viewAssignEscaped ( 'partner', $partner );

		$subPartner = $helper->getEpsfmSubPartner();
		$this->viewAssignEscaped ( 'subPartner', $subPartner );

		$partnerFunder = $helper->getEpsfmFunder();
		$this->viewAssignEscaped ( 'partnerFunder', $partnerFunder );


		$mechanism = $helper->getEpsfmMechanism();
		$this->viewAssignEscaped ( 'mechanism', $mechanism );

	}


	public function hasEditorACL(){
		// return hasACL() based on admin page viewing
		$validACLEditPages = array(
			'training_title_option_all'   => 'training_title_option_all',
			'training-category'           => 'acl_editor_training_category',
			'training-assign-title'       => 'acl_editor_training_category',
			'people-qual'                 => 'acl_editor_people_qualifications',
			'people-responsibility'       => 'acl_editor_people_responsibility',
			'people-types'                => 'acl_editor_people_trainer',
			'people-title'                => 'acl_editor_people_titles',
			'people-skills'               => 'acl_editor_people_trainer_skills',
			'people-languages'            => 'acl_editor_people_languages',
			'people-affiliations'         => 'acl_editor_people_affiliations',
			'people-suffix'               => 'acl_editor_people_suffix',
			'people-active'               => 'acl_editor_people_active_trainer',
			'training-organizer'          => 'acl_editor_training_organizer',
			'training-topic'              => 'acl_editor_training_topic',
			'training-level'              => 'acl_editor_training_level',
			'training-pepfar'             => 'acl_editor_pepfar_category',
			'training-refreshercourse'    => 'acl_editor_refresher_course',
			'training-funding'            => 'acl_editor_funding',
			'training-recommend'          => 'acl_editor_recommended_topic',
			'training-gotcurriculum'      => 'acl_editor_nationalcurriculum',
			'training-method'             => 'acl_editor_method',
			'training-viewing-location'   => 'acl_admin_training',
			'training-budget-code'        => 'acl_admin_training',
			'facilities-types'            => 'acl_editor_facility_types',
			'facilities-sponsors'         => 'acl_editor_facility_sponsors',
			'preservice-classes'          => 'acl_editor_ps_classes',
			'preservice-cadres'           => 'acl_editor_ps_cadres',
			'preservice-degrees'          => 'acl_editor_ps_degrees',
			'preservice-funding'          => 'acl_editor_ps_funding',
			'preservice-institutiontypes' => 'acl_editor_ps_institutions',
			'preservice-languages'        => 'acl_editor_ps_languages',
			'preservice-nationalities'    => 'acl_editor_ps_nationalities',
			'preservice-joindropreasons'  => 'acl_editor_ps_joindropreasons',
			'preservice-sponsors'         => 'acl_editor_ps_sponsors',
			'preservice-tutortypes'       => 'acl_editor_ps_tutortypes',
			'preservice-coursetypes'      => 'acl_editor_ps_coursetypes',
			'preservice-religion'         => 'acl_editor_ps_religions',
			'users-add'                   => 'add_edit_users',
			'training-settings'           => 'acl_admin_training',
			'people-settings'             => 'acl_admin_people',
			'facilities-settings'         => 'acl_admin_facilities',
			'people-new'                  => 'facility_and_person_approver',
			'facilities-new-facilities'   => 'facility_and_person_approver',
			'employee-category'           => 'employees_module',
			'employee-role'               => 'employees_module',
			'employee-transition'         => 'employees_module',
			'employee-relationship'       => 'employees_module',
			'employee-referral'           => 'employees_module',
			'employee-training-provided'  => 'employees_module',
			'tutorspecialty'                => 'acl_editor_tutor_specialty', //TA: added 7/22/2014
			'tutorcontract'                => 'acl_editor_tutor_contract', //TA: added 7/24/2014
			'commodityname'                => 'acl_editor_commodityname', //TA:17: added 9/19/2014
			'commoditytype'                => 'acl_editor_commoditytype', //TA:17:12: added 10/03/2014
		);


		return $this->hasACL($validACLEditPages[$this->getRequest()->action]);
	}

	public function hiddenSettingsAction() {

		$style_id = $this->setting('site_style_id');

		if ($this->getRequest()->isPost()) {
			$db = $this->dbfunc();

			$id = $this->getSanParam('site_style_option');
			$q = "UPDATE _system set site_style_id = $id";
			$style_id = $id;
			$db->query($q);
		}

		$this->view->assign('siteStyleDropdown',
			DropDown::generateSelectionFromQuery(
				'SELECT id, site_style_name AS val FROM site_styles ORDER BY val ASC',
				array('name' => 'site_style_option'),
				$style_id, false
			)
		);
	}
	
	//TA:#331.1
	public function peopleEducationtypeAction(){
	    if($this->getRequest()->isPost()) {
			// form submit
			$updateData = array();
			$require_trainer_skill = $this->getParam('require_trainer_skill');
			if (empty($require_trainer_skill)) { $require_trainer_skill = 0; }
			$this->putSetting('require_trainer_skill', $require_trainer_skill);
		}

		$controller = &$this;
        $editTable = new EditTableController($controller->getRequest(), $controller->getResponse());  
		$editTable->setParentController($controller);
		$editTable->table   = 'education_type_option';
		$editTable->fields  = array('education_type_phrase' => t('Type Of Education'));
		$editTable->label   = t('Type Of Education');
		$editTable->execute($controller->getRequest());
	
	}
	
	//TA:#331.1
	public function peopleSchoolnameAction(){
	    if($this->getRequest()->isPost()) {
	        // form submit
	        $updateData = array();
	        $require_trainer_skill = $this->getParam('require_trainer_skill');
	        if (empty($require_trainer_skill)) { $require_trainer_skill = 0; }
	        $this->putSetting('require_trainer_skill', $require_trainer_skill);
	    }
	
	    $controller = &$this;
	    $editTable = new EditTableController($controller->getRequest(), $controller->getResponse());
	    $editTable->setParentController($controller);
	    $editTable->table   = 'education_school_name_option';
	    $editTable->fields  = array('school_name_phrase' => t('Official School Name'));
	    $editTable->label   = t('Official School Name');
	    $editTable->execute($controller->getRequest());
	
	}
	
	//TA:#331.1
	public function peopleEducationcountryAction(){
	    if($this->getRequest()->isPost()) {
	        // form submit
	        $updateData = array();
	        $require_trainer_skill = $this->getParam('require_trainer_skill');
	        if (empty($require_trainer_skill)) { $require_trainer_skill = 0; }
	        $this->putSetting('require_trainer_skill', $require_trainer_skill);
	    }
	
	    $controller = &$this;
	    $editTable = new EditTableController($controller->getRequest(), $controller->getResponse());
	    $editTable->setParentController($controller);
	    $editTable->table   = 'education_country_option';
	    $editTable->fields  = array('education_country_phrase' => t('Country'));
	    $editTable->label   = t('Country');
	    $editTable->execute($controller->getRequest());
	
	}
}


?>