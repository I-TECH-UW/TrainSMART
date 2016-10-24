<?php
/*
* Created on Feb 27, 2008
*
*  Built for web
*  Fuse IQ -- todd@fuseiq.com
*
*/
require_once ('ReportFilterHelpers.php');
require_once ('models/table/Training.php');
require_once ('models/table/TrainingToTrainer.php');
require_once ('models/table/PersonToTraining.php');
require_once ('models/table/Trainer.php');
require_once ('models/table/Person.php');
require_once ('models/table/Translation.php');
require_once ('models/table/OptionList.php');
require_once ('models/table/MultiAssignList.php');
//require_once ('models/table/Course.php');
require_once ('views/helpers/TrainingViewHelper.php');


class TrainingController extends ReportFilterHelpers {

	private $NUM_PEPFAR = 5; // number of pepfars when "multiple" is allowed


	public function __construct(Zend_Controller_Request_Abstract $request, Zend_Controller_Response_Abstract $response, array $invokeArgs = array()) {
		$this->_countrySettings = array();
		$this->_countrySettings = System::getAll();

		$this->_countrySettings['num_location_tiers'] = 2 + $this->_countrySettings['display_region_c'] + $this->_countrySettings['display_region_b'] + $this->_countrySettings['display_region_d'] + $this->_countrySettings['display_region_e'] + $this->_countrySettings['display_region_f'] + $this->_countrySettings['display_region_g'] + $this->_countrySettings['display_region_h'] + $this->_countrySettings['display_region_i']; //including city // todo i want to mark this for removal

		parent::__construct ( $request, $response, $invokeArgs );
	}

	public function init() {
	}

	public function preDispatch() {
		parent::preDispatch ();

		if (! $this->isLoggedIn ())
		$this->doNoAccessError ();
	}

	public function addAction() {
		if (! $this->hasACL ( 'edit_course' )) {
			$this->doNoAccessError ();
		}

		$this->view->assign ( 'pageTitle', t ( 'Add New' ).' '.t( 'Training' ) );
		return $this->doAddEditView ();
	}

	public function editAction() {
		if (! $this->hasACL ( 'edit_course' )) {
			$this->doNoAccessError ();
		}

		$this->view->assign ( 'pageTitle', t ( 'View/Edit' ).' '.t( 'Training' ) );
		return $this->doAddEditView ();
	}

	public function viewAction() {
		$this->view->assign ( 'pageTitle', t ( 'View/Edit' ).' '.t( 'Training' ) );
		return $this->doAddEditView ();
	}

	private function doAddEditView() {
		
		if (! $this->hasACL ( 'edit_course' )) {
			$this->view->assign ( 'viewonly', 'disabled="disabled"' );
			$this->view->assign ( 'pageTitle', t ( 'View' ).' '.t( 'Training' ) );
		}

		// edittable ajax (remove/update/etc)
		if ($this->getParam ( 'edittable' )) {
			$this->ajaxEditTable ();
			return;
		}

		require_once 'models/table/MultiOptionList.php';
		require_once 'models/table/TrainingLocation.php';
		require_once 'models/table/Location.php';
		require_once 'models/table/System.php';
		require_once 'views/helpers/EditTableHelper.php';
		require_once 'views/helpers/DropDown.php';
		require_once 'views/helpers/FileUpload.php';

		// allow multiple pepfars?
		if (! $this->setting ( 'allow_multi_pepfar' )) {
			$this->NUM_PEPFAR = 1;
		}

		// get translation labels
		// $this->view->assign('labels', Translation::getAll());


		//validate
		$status = ValidationContainer::instance ();

		$request = $this->getRequest ();
		$validateOnly = $request->isXmlHttpRequest ();
		$training_id = $this->getSanParam ( 'id' );
		$is_new = ($this->getSanParam ( 'new' ) || ! $training_id); // new training -- use defaults
		$this->view->assign ( 'is_new', $is_new );

		$trainingObj = new Training ( );
		$row = $trainingObj->findOrCreate ( $training_id );
		$rowRay = @$row->toArray ();

		//filter training orgs by user access
		$allowIds = false;
		if (! $this->hasACL ( 'training_organizer_option_all' )) {
			$allowIds = array ();
			$user_id = $this->isLoggedIn ();
			$training_organizer_array = MultiOptionList::choicesList ( 'user_to_organizer_access', 'user_id', $user_id, 'training_organizer_option', 'training_organizer_phrase', false, false );
			foreach ( $training_organizer_array as $orgOption ) {
				if ($orgOption ['user_id'])
				$allowIds [] = $orgOption ['id'];
			}
		}

		if (($this->getParam ( 'action' ) != 'add') and ! $this->hasACL ( 'training_organizer_option_all' ) and ((! $allowIds) or (array_search ( $rowRay ['training_organizer_option_id'], $allowIds ) === false))) {
			$this->view->assign ( 'viewonly', 'disabled="disabled"' );
			$this->view->assign ( 'pageTitle', t ( 'View' ).' '.t( 'Training' )); 
		}
		

		if ($row->is_deleted) {
			$this->_redirect ( 'training/deleted' );
			return;
		}

		$courseRow = $trainingObj->getCourseInfo ( $training_id );
		$rowRay ['training_title'] = ($courseRow) ? $courseRow->training_title_phrase : '';
		$rowRay ['training_title_option_id'] = ($courseRow) ? $courseRow->training_title_option_id : 0;

		// does not exist
		if (! $row->id && $this->getParam ( 'action' ) != 'add') {
			$this->_redirect ( 'training/index' );
		}

		if ($validateOnly)
		$this->setNoRenderer ();

		$age_opts = OptionList::suggestionList('age_range_option',array('id','age_range_phrase'), false, 100, false);

		if ($request->isPost () && ! $this->getSanParam ( 'edittabledelete' )) {

			//$status->checkRequired($this, 'training_title_option_id',t('Training Name'));
			$status->checkRequired($this, 'training_category_and_title_option_id',t('Training Name')); //TA:53 10/06/2015 
			//$status->checkRequired ( $this, 'training_length_value', t ( 'Training' ).' '.t( 'Length' ) );//TA:17: 09/3/2014
			//$status->checkRequired ( $this, 'training_length_interval', t ( 'Training' ).' '.t( 'Interval' ) ); //TA:17: 09/3/2014
			//$status->checkRequired($this, 'training_organizer_option_id',t('Training Organizer'));
			//$status->checkRequired($this, 'training_level_option_id',t('Training Level'));
			//$status->checkRequired($this, 'training_location_id',t('Training Location'));
			//$status->checkRequired($this, 'training_topic_option_id','Training Topic');


			// May be "0" value
			//TA:17: 9/2/2014 can be NULL
			/*if (! $this->getSanParam ( 'training_length_value' )) {
				$status->addError ( 'training_length_value', t ( 'Training length is required.' ) );
			}*/

			// validate score averages values
			if ($score = trim ( $this->getSanParam ( 'pre' ) )) {
				if (! is_numeric ( $score )) {
					$status->addError ( 'pre', $this->view->translation ['Pre Test Score'] . ' ' . t ( 'must be numeric.' ) );
				} elseif ($score < 0 || $score > 100) {
					$status->addError ( 'pre', $this->view->translation ['Pre Test Score'] . ' ' . t ( 'must be between 1-100.' ) );
				}
			}
			if ($score = trim ( $this->getSanParam ( 'post' ) )) {
				if (! is_numeric ( $score )) {
					$status->addError ( 'post', $this->view->translation ['Post Test Score'] . ' ' . t ( 'must be numeric.' ) );
				} elseif ($score < 0 || $score > 100) {
					$status->addError ( 'post', $this->view->translation ['Post Test Score'] . ' ' . t ( 'must be between 1-100.' ) );
				}
			}

			//TA:17: 9/2/2014 Start date is not required
			//if ( $this->getSanParam('start-year') == ""  || $this->getSanParam('start-month') == "" || $this->getSanParam('start-day') == "" )
				//$status->addError( 'start-day', t('Start date is required.') );
			$training_start_date = (@$this->getSanParam ( 'start-year' )) . '-' . (@$this->getSanParam ( 'start-month' )) . '-' . (@$this->getSanParam ( 'start-day' ));
			if ($training_start_date !== '--' and $training_start_date !== '0000-00-00')
			$status->isValidDate ( $this, 'start-day', t ( 'Training' ).' '.t( 'start' ), $training_start_date );
			if ($this->setting ( 'display_end_date' )) {
				$training_end_date = (@$this->getSanParam ( 'end-year' )) . '-' . (@$this->getSanParam ( 'end-month' )) . '-' . (@$this->getSanParam ( 'end-day' ));
				if ($training_end_date !== '--' and $training_end_date !== '0000-00-00') {
					$status->isValidDate ( $this, 'end-day', t ( 'Training' ).' '.t( 'end' ), $training_end_date );
				}

				if ($training_end_date != '--') {
					if (strtotime ( $training_end_date ) < strtotime ( $training_start_date )) {
						$status->addError ( 'end-day', t ( 'End date must be after start date.' ) );
					}
				}
			}

			$pepfarEnabled = @$this->setting('display_training_pepfar');

			if ($training_id) {

				$pepfarCount = 0;
				$pepfar_array = $this->getSanParam ( 'training_pepfar_categories_option_id' );
				if ($pepfar_array) {
					foreach ( $pepfar_array as $p ) {
						if ($p)
						$pepfarCount ++;
					}
				}

				//          if (!$pepfarCount) {
				//            $status->addError('training_pepfar_categories_option',t('PEPFAR is required.'));
				//         }


				// pepfar (multiple days)
				if ($this->getSanParam ( 'pepfar_days' ) && $pepfarEnabled) {
					$pepfarTotal = 0;
					foreach ( $this->getSanParam ( 'pepfar_days' ) as $key => $value ) {
						if (! is_numeric ( $value ))
						$value = preg_replace ( "/\/^[.0-9]/", "", $value );
						//$daysRay [$pepfar_array[$key]] = $value; //set the days key to  the pepfar id
						$daysRay [$key] = $value; //set the days key to  the pepfar id
						$pepfarTotal += $value;

						if ($pepfarCount > 1 && ! $value  && $pepfarEnabled) {
							$status->addError ( 'training_pepfar_categories_option', t ( 'Number of days is required.' ) );
						}

						if ($pepfarCount == $key + 1) {
							break;
						}

					}

					// calculate days
					switch ($this->getSanParam ( 'training_length_interval' )) {
						case 'week' :
						$days = $this->getSanParam ( 'training_length_value' ) * 7;
						break;
						case 'day' :
						$days = $this->getSanParam ( 'training_length_value' ); // start day counts as a day?
						break;
						default :
						$days = 0.5;
						break;
					}

					// do days add up to match training length?
					if ($days != $pepfarTotal && $pepfarCount > 1 && $pepfarEnabled) {
						$status->addError ( 'training_pepfar_categories_option', sprintf ( t("Total").' '.t('Training').' '.t("length is %s, but PEPFAR category total is %d days. " ), (($days == 1) ? $days . ' ' . t ( 'day' ) : $days . ' ' . t ( 'days' )), $pepfarTotal ) );
					}

				}

				// custom fields
				if ($this->getSanParam ( 'custom1_phrase' )) {
					$tableCustom = new ITechTable ( array ('name' => 'training_custom_1_option' ) );
					$row->training_custom_1_option_id = $tableCustom->insertUnique ( 'custom1_phrase', $this->getSanParam ( 'custom1_phrase' ), true );
				}
				if ($this->getSanParam ( 'custom2_phrase' )) {
					$tableCustom = new ITechTable ( array ('name' => 'training_custom_2_option' ) );
					$row->training_custom_2_option_id = $tableCustom->insertUnique ( 'custom2_phrase', $this->getSanParam ( 'custom2_phrase' ), true );
				}
				$custom3 = $this->getSanParam ( 'custom3_phrase' );
				$row->custom_3 = ($custom3) ? $custom3 : '';
				$custom4 = $this->getSanParam ( 'custom4_phrase' );
				$row->custom_4 = ($custom4) ? $custom4 : '';

				// checkbox
				if (! $this->getSanParam ( 'is_tot' )) {
					$row->is_tot = 0;
				}
				if (! $this->getSanParam ( 'is_refresher' )) {
					$row->is_refresher = 0;
				}

				$training_refresher_option_id =  $this->getSanParam ( 'training_refresher_option_id' );
				if (! empty($training_refresher_option_id )) {
					$row->is_refresher = 1;
				}

			}

			if (($this->getParam ( 'action' ) == 'add') && $this->_countrySettings ['module_unknown_participants_enabled'] && (! $this->getSanParam ( 'has_known_participants' ))) {
				$row->has_known_participants = 0;
			} else if ($this->getParam ( 'action' ) == 'add') {
				$row->has_known_participants = 1;
			}

			//approve by default if the approvals modules is not enabled
			if (($this->getParam ( 'action' ) == 'add') && $this->_countrySettings ['module_approvals_enabled'] && ! $this->hasACL ( 'approve_trainings' )) {
				$row->is_approved = 0;
			} else if ($this->getParam ( 'action' ) == 'add') {
				$row->is_approved = 1;
			}

			// delete training
			if ($this->getSanParam ( 'specialAction' ) == 'delete') {
				$partys = PersonToTraining::getParticipants ( $training_id )->toArray ();
				$tranys = TrainingToTrainer::getTrainers ( $training_id )->toArray ();
				if (! $partys && ! $tranys) {
					$row->is_deleted = 1;
					$trainingObj->delete ( 'id = ' . $row->id );
				} else {
					$status->setStatusMessage ( t ( 'This' ).' '.t( 'Training' ).' '.t( 'session could not be deleted. Some participants or trainers may still be attached.' ) );

				}
			}

			if ($status->hasError () && ! $row->is_deleted) {
				$status->setStatusMessage ( t ( 'This' ).' '.t( 'Training' ).' '.t( 'session could not be saved.' ) );
			} else {
				$row = self::fillFromArray ( $row, $this->getAllParams () );

				// format: categoryid_titleid
				$ct_ids = $this->getSanParam ( 'training_category_and_title_option_id' );

				// remove category id and underscore (unless dynamic title insert, which is numeric)
				$training_title_option_id = (! is_numeric ( $ct_ids )) ? substr ( $ct_ids, strpos ( $ct_ids, '_' ) + 1 ) : $ct_ids;
				$row->training_title_option_id = $training_title_option_id;

				$row->training_start_date = (@$this->getSanParam ( 'start-year' )) . '-' . (@$this->getSanParam ( 'start-month' )) . '-' . (@$this->getSanParam ( 'start-day' ));
				if ($this->setting ( 'display_end_date' )) {
					$row->training_end_date = (@$this->getSanParam ( 'end-year' )) . '-' . (@$this->getSanParam ( 'end-month' )) . '-' . (@$this->getSanParam ( 'end-day' ));
				}

				// cannot be null ... set defaults
				if (! $row->comments)
				$row->comments = '';
				if (! $row->got_comments)
				$row->got_comments = '';
				if (! $row->objectives)
				$row->objectives = '';
				if (! $row->is_tot)
				$row->is_tot = 0;
				if (! $row->is_refresher)
				$row->is_refresher = 0;

				// update related tables
				if ($training_id) {

					// funding
					$amount_extra_col = '';
					$amount_extra_vals = array ();
					$amount_extra_col = 'funding_amount';
					if ($this->getSanParam ( 'funding_id' )) {
						foreach ( $this->getSanParam ( 'funding_id' ) as $funding_id ) {
							$amount_extra_vals [] = $this->getSanParam ( 'funding_id_amount_' . $funding_id );
						}
					}

					MultiOptionList::updateOptions ( 'training_to_training_funding_option', 'training_funding_option', 'training_id', $training_id, 'training_funding_option_id', $this->getSanParam ( 'funding_id' ), $amount_extra_col, $amount_extra_vals );

					// pepfar
					if ($pepfarEnabled)
					MultiOptionList::updateOptions ( 'training_to_training_pepfar_categories_option', 'training_pepfar_categories_option', 'training_id', $training_id, 'training_pepfar_categories_option_id', $this->getSanParam ( 'training_pepfar_categories_option_id' ), 'duration_days', (isset ( $daysRay ) ? $daysRay : false) );

					// method
					if ($this->setting ( 'display_training_method' )) {
						$row->training_method_option_id = $this->getSanParam ( 'training_method_option_id' );
					}

					// topics
					if (! $this->setting ( 'allow_multi_topic' )) {
						// drop-down -- set up faux checkbox array (since table schema uses multiple choices)
						$_GET ['topic_id'] [] = $this->getSanParam ( 'training_topic_option_id' );
					}
					MultiOptionList::updateOptions ( 'training_to_training_topic_option', 'training_topic_option', 'training_id', $training_id, 'training_topic_option_id', $this->getSanParam ( 'topic_id' ) );

					// refresher course (if dropdownlist)
					if ($this->setting ( 'multi_opt_refresher_course')) {
						MultiOptionList::updateOptions ('training_to_training_refresher_option', 'training_refresher_option', 'training_id', $training_id, 'training_refresher_option_id', $this->getSanParam ( 'training_refresher_option_id' ));
					}
					
					//Qualifications for unknown participants
					if (! $row->has_known_participants) {
						
						//check for duplicates
						///oooh, compound key = qual + age

						//DELETE EVERYTHING FOR THIS TRAINING
						//START OVER

						$quals = $this->getSanParam ( 'person_qualification_option_id' );
						$quantities_na = $this->getSanParam ( 'qualification_quantity_na' );
						$quantities_male = $this->getSanParam ( 'qualification_quantity_male' );
						$quantities_female = $this->getSanParam ( 'qualification_quantity_female' );
						$age_ranges = $this->getSanParam ( 'age_range_option_id' );

						$qualPlusAgeArray = array();

						//make array of qualifications + age range
						$qualRows = OptionList::suggestionListHierarchical ( 'person_qualification_option', 'qualification_phrase', false, false );
						foreach($qualRows as $qRow) {
							foreach(array_keys($age_opts) as $age_opt) {
								$qualPlusAgeArray [$qRow['id']][$age_opt] = array('na'=>0,'male'=>0,'female'=>0);
							}
						}

						foreach($quals as $ix => $item) {
							if ( $item) {
								$qualPlusAgeArray[$quals[$ix]][$age_ranges[$ix]]['na'] = $qualPlusAgeArray[$quals[$ix]][$age_ranges[$ix]]['na'] + $quantities_na[$ix];
								$qualPlusAgeArray[$quals[$ix]][$age_ranges[$ix]]['male'] = $qualPlusAgeArray[$quals[$ix]][$age_ranges[$ix]]['male'] + $quantities_male[$ix];
								$qualPlusAgeArray[$quals[$ix]][$age_ranges[$ix]]['female'] = $qualPlusAgeArray[$quals[$ix]][$age_ranges[$ix]]['female'] + $quantities_female[$ix];
							}
						}

						$deleteTable = new ITechTable(array ('name' => 'training_to_person_qualification_option' ));
						$deleteTable->delete('training_id = '.$training_id, true);

						foreach($qualPlusAgeArray as $qkey => $ageRay) {
							foreach($ageRay as $akey => $counts) {
								if ( $counts['na'] || $counts['male'] || $counts['female'] ) {
									MultiOptionList::insertOption('training_to_person_qualification_option', 'training_id', $training_id, 'person_qualification_option_id', $qkey,
									array ('age_range_option_id','person_count_na', 'person_count_male', 'person_count_female' ),
									array('age_range_option_id' => $akey, 'person_count_na' => $counts['na'], 'person_count_male' => $counts['male'], 'person_count_female' => $counts['female']));
								}
							}
						}
					}
				}

				//mark approval status
				$do_save_approval_history = false;
				if ($this->setting ( 'module_approvals_enabled' )) {
					if ($this->getSanParam ( 'approval_status' ) == 'approved') {
						$row->is_approved = 1;
						if($this->setting ( 'allow_multi_approvers' ) && !$this->hasACL ( 'master_approver' ) ) {
							$row->is_approved = 2; // approved, but not approved by master approver, only that user can make this a 1 and have it display aproved!
						}
						$rowRay ['is_approved'] = 1;
						$do_save_approval_history = true;
					} else if ($this->getSanParam ( 'approval_status' ) == 'rejected') {
						$row->is_approved = 0;
						$rowRay ['is_approved'] = 0;
						if($this->setting ( 'allow_multi_approvers' ) && !$this->hasACL ( 'master_approver' ) ) {
							$row->is_approved = 1; // approved, but not approved by master approver, only that user can make this a 1 and have it display aproved!
							$rowRay['is_approved'] = 1;
						}
						$do_save_approval_history = true;
					}
					if (($this->getParam ( 'action' ) == 'add') or (! $this->hasACL ( 'approve_trainings' ))) {
						$do_save_approval_history = true;
					}

				}

				if ($this->getParam ( 'action' ) == 'add') {
					$do_save_approval_history = true;
				}
				$row->training_refresher_option_id = 0; // refresher / bugfix - this col isnt used anymore

				if ($row->save ()) {

					//save approval history
					if ($this->getSanParam ( 'approval_comments' ) || $do_save_approval_history) {
						require_once ('models/table/TrainingApprovalHistory.php');
						$history_table = new TrainingApprovalHistory ( );
						$approval_status = ($this->_countrySettings ['module_approvals_enabled'] ? $this->getSanParam ( 'approval_status' ) : 'approved');

						if (! $this->hasACL ( 'approve_trainings' ))
						$approval_status = 'resubmitted';
						$history_data = array ('training_id' => $row->id, 'approval_status' => $approval_status, 'message' => $this->getSanParam ( 'approval_comments' ) );
						$history_table->insert ( $history_data );
					}

					// redirects
					if ($this->getParam ( 'action' ) == 'add') {
						$status->redirect = Settings::$COUNTRY_BASE_URL . '/training/edit/id/' . $row->id . '/new/1';
					}
					if ($this->getParam ( 'redirectUrl' )) {
						$status->redirect = $this->getParam ( 'redirectUrl' );
					}

					// duplicate training
					if ($this->getSanParam ( 'specialAction' ) == 'duplicate') {
						if ($this->hasACL('duplicate_training')) {
							$dupId = $trainingObj->duplicateTraining ( $row->id );
							$status->redirect = Settings::$COUNTRY_BASE_URL . '/training/edit/id/' . $dupId . '/msg/duplicate';
						}
					}

					if (! $status->redirect) {
						$status->setStatusMessage ( t ( 'This' ).' '.t( 'Training' ).' '.t( 'session has been saved.' ) );
					}

				} else {
					error_log ( "Couldn't save training $training_id" );
				}

			}

			if ($validateOnly) {
				$this->sendData ( $status );
			} else {
				$this->view->assign ( 'status', $status );
			}

		}

		//
		// Init view
		//

		$this->view->assign ('custom3_phrase', $row->custom_3);
		$this->view->assign ('custom4_phrase', $row->custom_4);

		//split start date fields
		if (! $row->training_start_date)
		$row->training_start_date = '--'; // empty
		$parts = explode ( ' ', $row->training_start_date );
		$parts = explode ( '-', $parts [0] );
		$rowRay ['start-year'] = $parts [0];
		$rowRay ['start-month'] = $parts [1];
		$rowRay ['start-day'] = $parts [2];

		//split end date fields
		if (! $row->training_end_date)
		$row->training_end_date = '--'; // empty
		$parts = explode ( ' ', $row->training_end_date );
		$parts = explode ( '-', $parts [0] );
		$rowRay ['end-year'] = $parts [0];
		$rowRay ['end-month'] = $parts [1];
		$rowRay ['end-day'] = $parts [2];

		// Drop downs
		//$this->view->assign('dropDownTitle', DropDown::generateHtml('training_title_option','training_title_phrase',$rowRay['training_title_option_id'],($this->hasACL('training_title_option_all')?'training/insert-table':false), $this->view->viewonly2,false)); 
		$this->view->assign ( 'dropDownOrg', DropDown::generateHtml ( 'training_organizer_option', 'training_organizer_phrase', $rowRay ['training_organizer_option_id'], ($this->hasACL ( 'training_organizer_option_all' ) ? 'training/insert-table' : false), $this->view->viewonly, ($this->view->viewonly ? false : $allowIds) ) );
		$this->view->assign ( 'dropDownLevel', DropDown::generateHtml ( 'training_level_option', 'training_level_phrase', $rowRay ['training_level_option_id'], 'training/insert-table', $this->view->viewonly ) );
		$this->view->assign ( 'dropDownGotCir', DropDown::generateHtml ( 'training_got_curriculum_option', 'training_got_curriculum_phrase', $rowRay ['training_got_curriculum_option_id'], 'training/insert-table', $this->view->viewonly ) );
		$this->view->assign ( 'dropDownMethod', DropDown::generateHtml ( 'training_method_option', 'training_method_phrase', $rowRay ['training_method_option_id'], 'training/insert-table', $this->view->viewonly ) );
		$this->view->assign ( 'dropDownPrimaryLanguage', DropDown::generateHtml ( 'trainer_language_option', 'language_phrase', $rowRay ['training_primary_language_option_id'], false, $this->view->viewonly, false, false, array ('name' => 'training_primary_language_option_id' ) ) );
		$this->view->assign ( 'dropDownSecondaryLanguage', DropDown::generateHtml ( 'trainer_language_option', 'language_phrase', $rowRay ['training_secondary_language_option_id'], false, $this->view->viewonly, false, false, array ('name' => 'training_secondary_language_option_id' ) ) );

		//$catTitleArray = OptionList::suggestionList('location_district',array('district_name','parent_province_id'),false,false);

		$this->view->assign( 'age_options', $age_opts);

		// training categories & titles
		$categoryTitle = MultiAssignList::getOptions ( 'training_title_option', 'training_title_phrase', 'training_category_option_to_training_title_option', 'training_category_option' );
		$this->view->assign ( 'categoryTitle', $categoryTitle);
		// add title link
		if ($this->hasACL ( 'training_title_option_all' )) {
			//TA:68 fixed bug : did not shaw up for Ukranian translation 
			//$this->view->assign ( 'titleInsertLink', " <a href=\"#\" onclick=\"addToSelect('" . str_replace ( "'", "\\" . "'", t ( 'Please enter your new' ) ) . " " . strtolower ( $this->view->translation ['Training'] . t('Name') ) . ":', 'select_training_title_option', '" . Settings::$COUNTRY_BASE_URL . "/training/insert-table/table/training_title_option/column/training_title_phrase/outputType/json'); return false;\">" . t ( 'Insert new' ) . "</a>" );
		    $this->view->assign ( 'titleInsertLink', 
		        " <a href=\"#\" onclick=\"addToSelect('" . str_replace ( "'", "\\" . "'", t ( 'Please enter your new' )  . " " . 
		        strtolower ( $this->view->translation ['Training'] . t('Name') )) . ":', 'select_training_title_option', '" . Settings::$COUNTRY_BASE_URL . "/training/insert-table/table/training_title_option/column/training_title_phrase/outputType/json'); return false;\">" . t ( 'Insert new' ) . "</a>" );
		    //$this->view->assign ( 'pageTitle', t ( 'View/Edit' ).' '.t( 'Training' )); //TA:11:
		}

		//get assigned evaluation
		$ev_id = null;
		$ev_to_t_id = null;
		if ($training_id) {
			$evtableObj = new ITechTable ( array ('name' => 'evaluation_to_training' ) );
			$evRow = $evtableObj->fetchRow ( $evtableObj->select ( array ('id', 'evaluation_id' ) )->where ( 'training_id = ' . $training_id ) );
			if ($evRow) {
				$ev_id = $evRow->evaluation_id;
				$ev_to_t_id = $evRow->id;
			}
			$this->view->assign ( 'evaluation_id', $ev_id );
			$this->view->assign ( 'evaluation_to_training_id', $ev_to_t_id );
		}

		//Qualifications for unknown participants
		if (! $row->has_known_participants) {
			//count primary qualifications.
			//add a dropdown for each
			$tableObj = new ITechTable ( array ('name' => 'person_qualification_option' ) );
			$qualRows = OptionList::suggestionListHierarchical ( 'person_qualification_option', 'qualification_phrase', false, false );
			//get values for this training
			$selectedObj = new ITechTable ( array ('name' => 'training_to_person_qualification_option' ) );
			$selectedRows = null;
			if ($training_id) {
				$selectedRows = $selectedObj->fetchAll ( $selectedObj->select ( array ('person_qualification_option_id', 'id', 'person_count_na', 'person_count_male', 'person_count_female', 'age_range_option_id' ) )->where ( 'training_id = ' . $training_id ) );

				$unknownQualDropDowns = array ();



				$qual_row_count = 0;
				foreach ( $selectedRows as $selectedRow ) {
					$selector = array ();

					$selector ['select'] = $this->_generate_hierarchical('select_person_qualification_option_'.$qual_row_count, $qualRows, 'qualification_phrase', $selectedRow->person_qualification_option_id) ;
					$selector ['age_range_option_id'] = $selectedRow->age_range_option_id;
					$selector ['quantity_na'] = $selectedRow->person_count_na;
					$selector ['quantity_male'] = $selectedRow->person_count_male;
					$selector ['quantity_female'] = $selectedRow->person_count_female;
					$unknownQualDropDowns [] = $selector;
					$qual_row_count ++;
				}

				$max_rows = count($qualRows)*3;//should be about 30

				for($i = $selectedRows->count (); $i < $max_rows; $i ++) {
					$selector = array ();
					$selector ['select'] = $this->_generate_hierarchical('select_person_qualification_option_'.$qual_row_count, $qualRows, 'qualification_phrase', -1);
					$selector ['age_range_option_id'] = 1;
					$selector ['quantity_na'] = 0;
					$selector ['quantity_male'] = 0;
					$selector ['quantity_female'] = 0;
					$unknownQualDropDowns [] = $selector;
					$qual_row_count ++;
				}

				$this->view->assign ( 'unknownQualDropDowns', $unknownQualDropDowns );
			}

		}

		// find category based on title
		$catId = 0;
		if ($courseRow && $courseRow->training_title_option_id) {
			foreach ( $categoryTitle as $r ) {
				if ($r ['id'] == $courseRow->training_title_option_id && $r['training_category_option_id'] != 0) {
					$catId = $r ['training_category_option_id'];
					break;
				}
			}
		}

		$this->view->assign ( 'dropDownCategory', DropDown::generateHtml ( 'training_category_option', 'training_category_phrase', $catId, false, $this->view->viewonly, false ) );

		//echo '<pre>';
		//print_r($catTitleArray); exit;


		//      $this->view->assign('dropDownProvince', DropDown::generateHtml('location_province','province_name',false,false,$this->view->viewonly));
		//      $this->view->assign('dropDownDistrict', DropDown::generateHtml('location_district','district_name',false,false,$this->view->viewonly));
		$this->viewAssignEscaped ( 'locations', Location::getAll () );

		// Topic drop-down
		if ($training_id) {
			if (! $this->setting ( 'allow_multi_topic' )) {
				$training_topic_id = $trainingObj->getTrainingSingleTopic ( $training_id );
				if ($is_new)
				$training_topic_id = false; // use default
				$this->view->assign ( 'dropDownTopic', DropDown::generateHtml ( 'training_topic_option', 'training_topic_phrase', $training_topic_id, 'training/insert-table', $this->view->viewonly ) );
			} else { // topic checkboxes
				$topicArray = MultiOptionList::choicesList ( 'training_to_training_topic_option', 'training_id', $training_id, 'training_topic_option', 'training_topic_phrase' );
				$this->view->assign ( 'topicArray', $topicArray );
				$this->view->assign ( 'topicJsonUrl', Settings::$COUNTRY_BASE_URL . '/training/insert-table/table/training_topic_option/column/training_topic_phrase/outputType/json' );
			}

		}
		if ($this->hasACL ( 'acl_editor_training_topic' )) {
			$this->view->assign ( "topicInsertLink", ' <a href="#" onclick="addCheckbox(\''.t('Please enter the name your new topic item') .     '\', \'topic_id\', \'topicContainer\', \''.$this->view->topicJsonUrl.'\'); return false;">'.t('Insert New').'</a>' );
		}

		// get custom phrases (custom1_phrase, custom2_phrase)
		if ($training_id) {
			$rowRay = array_merge ( $rowRay, $trainingObj->getCustom ( $training_id )->toArray () );
		}

		// location drop-down
		$tlocations = TrainingLocation::selectAllLocations ( $this->setting ( 'num_location_tiers' ) );
		$this->viewAssignEscaped ( 'tlocations', $tlocations );
		if ($this->hasACL ( 'edit_facility' )) {
			$this->view->assign ( "insertLocationLink", '<a href="#" onclick="return false;" id="show">'. t(str_replace(' ','&nbsp;',t('Insert new'))) . '</a>' );
		}

		// pepfar durations
		$pepfarEnabled = @$this->setting('display_training_pepfar');
		if ($training_id && $pepfarEnabled) {
			$pepfarArray = MultiOptionList::choicesList ( 'training_to_training_pepfar_categories_option', 'training_id', $training_id, 'training_pepfar_categories_option', 'pepfar_category_phrase', 'duration_days' );
			foreach ( $pepfarArray as $item ) {
				if (isset ( $item ['training_id'] ) && $item ['training_id']) {
					$pepfars [] = array ('id' => $item ['id'], 'duration' => $item ['duration_days'] );
				}
			}
		}

		// pepfar
		$this->view->assign ( 'NUM_PEPFAR', $this->NUM_PEPFAR ); // number of Pepfar drop-downs to display
		for($j = 0; $j < $this->NUM_PEPFAR; $j ++) {
			$pepfarid = (isset ( $pepfars [$j] ['id'] ) ? $pepfars [$j] ['id'] : '');
			if ($is_new)
			$pepfarid = false; // use default
			$pepfarHtml = DropDown::generateHtml ( 'training_pepfar_categories_option', 'pepfar_category_phrase', $pepfarid, false, $this->view->viewonly, false, false, array (), ($j == 0) );
			$pepfarHtml = str_replace ( 'training_pepfar_categories_option_id', 'training_pepfar_categories_option_id[]', $pepfarHtml ); // use array
			$dropDownPepfars [] = $pepfarHtml;
			//$pepfarDurations[] = (isset($pepfars[$j]['duration']) && $pepfars[$j]['duration'] ? $pepfars[$j]['duration'] . ' day' . (($pepfars[$j]['duration'] <= 1) ? '' : 's') : '');
			$pepfarDurations [] = (isset ( $pepfars [$j] ['duration'] ) && $pepfars [$j] ['duration']) ? $pepfars [$j] ['duration'] : '';
		}
		$this->view->assign ( 'dropDownPepfars', $dropDownPepfars );
		$this->view->assign ( 'pepfarDurations', $pepfarDurations );

		// checkboxes
		$fundingArray = MultiOptionList::choicesList ( 'training_to_training_funding_option', 'training_id', $training_id, 'training_funding_option', array ('funding_phrase', 'is_default' ) );
		if (/*$this->setting ( 'display_funding_amount' ) &&*/ $training_id) {
			//lame to do another query, but it's easy
			$tableObj = new ITechTable ( array ('name' => 'training_to_training_funding_option' ) );
			$amountRows = $tableObj->fetchAll ( $tableObj->select ( array ('training_funding_option_id', 'id', 'funding_amount' ) )->where ( 'training_id = ' . $training_id ) );
			foreach ( $amountRows as $amt_row ) {
				foreach ( $fundingArray as $k => $funding_row ) {
					if ($funding_row ['id'] == $amt_row->training_funding_option_id) {
						$fundingArray [$k] ['funding_amount'] = $amt_row->funding_amount;
					}
				}
			}
		}
		$this->view->assign ( 'fundingArray', $fundingArray );
		if ($this->hasACL ( 'acl_editor_funding' )) {
		$this->view->assign ( 'fundingJsonUrl', Settings::$COUNTRY_BASE_URL . '/training/insert-table/table/training_funding_option/column/funding_phrase/outputType/json' );
			$this->view->assign ( "fundingInsertLink", ' <a href="#" onclick="addCheckbox(\''.t('Please enter the name your new funding item:') .  '\', \'funding_id\', \'fundingContainer\', \''.$this->view->fundingJsonUrl.'\'); return false;">'.t('Insert New').'</a>' );
		}

		// refresher (if multi)
		if ($training_id) {
			if ($this->setting ( 'multi_opt_refresher_course' )) {
				$training_refresher_id = $row->training_refresher_option_id;
				if ($is_new)
				$training_refresher_id = false; // use default
				#$this->view->assign ( 'dropDownRefresher', DropDown::generateHtml ( 'training_refresher_option', 'refresher_phrase_option', $training_refresher_id, 'training/insert-table', $this->view->viewonly ) );
				$this->view->assign ( 'refresherArray', MultiOptionList::choicesList ( 'training_to_training_refresher_option', 'training_id', $training_id, 'training_refresher_option', 'refresher_phrase_option', false, false ) );
				if ($this->hasACL ( 'acl_editor_refresher_course' )) {
				$this->view->assign ( 'refresherJsonUrl', Settings::$COUNTRY_BASE_URL . '/training/insert-table/table/training_refresher_option/column/refresher_phrase_option/outputType/json' );
					$this->view->assign ( "refresherInsertLink", ' <a href="#" onclick="addCheckbox(\''.t('Please enter the name your new refresher item') . '\', \'training_refresher_option_id\', \'refresherContainer\', \''.$this->view->refresherJsonUrl.'\'); return false;">'.t('Insert New').'</a>' );
				}
			}
		}



		/****************************************************************************************************************
		* Trainers */
		$locations = Location::getAll ();
		if ($training_id) {
			$trainers = TrainingToTrainer::getTrainers ( $training_id )->toArray ();
			
			//TA:107
			foreach ( $trainers as $pid => $p ) {
			    $region_ids = Location::getCityInfo ( $p ['location_id'], $this->setting ( 'num_location_tiers' ) ); // todo expensive call, getcityinfo loads all locations each time??
			    $trainers [$pid] ['province_name'] = ($region_ids[1] ? $locations [$region_ids['1']] ['name'] : 'unknown');
			    if ($region_ids[2])
			        $trainers [$pid] ['district_name'] = $locations [$region_ids[2]] ['name'];
			    else
			        $trainers [$pid] ['district_name'] = 'unknown';
			
			    if ($region_ids[3])
			        $trainers [$pid] ['region_c_name'] = $locations [$region_ids[3]] ['name'];
			    else
			        $trainers [$pid] ['region_c_name'] = 'unknown';
			
			    if ($region_ids[4])
			        $trainers [$pid] ['region_d_name'] = $locations [$region_ids[4]] ['name'];
			    else
			        $trainers [$pid] ['region_d_name'] = 'unknown';
			
			    if ($region_ids[5])
			        $trainers [$pid] ['region_e_name'] = $locations [$region_ids[5]] ['name'];
			    else
			        $trainers [$pid] ['region_e_name'] = 'unknown';
			
			    if ($region_ids[6])
			        $trainers [$pid] ['region_f_name'] = $locations [$region_ids[6]] ['name'];
			    else
			        $trainers [$pid] ['region_f_name'] = 'unknown';
			
			    if ($region_ids[7])
			        $trainers [$pid] ['region_g_name'] = $locations [$region_ids[7]] ['name'];
			    else
			        $trainers [$pid] ['region_g_name'] = 'unknown';
			
			    if ($region_ids[8])
			        $trainers [$pid] ['region_h_name'] = $locations [$region_ids[8]] ['name'];
			    else
			        $trainers [$pid] ['region_h_name'] = 'unknown';
			
			    if ($region_ids[9])
			        $trainers [$pid] ['region_i_name'] = $locations [$region_ids[9]] ['name'];
			    else
			        $trainers [$pid] ['region_i_name'] = 'unknown';
			}
			/////
		} else {
			$trainers = array ();
		}

		if (! $this->setting ( 'display_middle_name' )) {

			$trainerFields = array ('first_name' => $this->tr ( 'First Name' ), 'last_name' => $this->tr ( 'Last Name' ) );

			$colStatic = array ('first_name', 'last_name' );

		} else if ($this->setting ( 'display_middle_name_last' )) {

			$trainerFields = array ('first_name' => $this->tr ( 'First Name' ), 'last_name' => $this->tr ( 'Last Name' ), 'middle_name' => $this->tr ( 'Middle Name' ) );

			$colStatic = array ('first_name', 'last_name', 'middle_name' );

		} else {

			$trainerFields = array ('first_name' => $this->tr ( 'First Name' ), 'middle_name' => $this->tr ( 'Middle Name' ), 'last_name' => $this->tr ( 'Last Name' ) );

			$colStatic = array ('first_name', 'middle_name', 'last_name' );
		}
		
		//TA:107
		$trainerFields ['birthdate'] = $this->tr ( 'Date of Birth' );
		$trainerFields ['facility_name'] = $this->tr ( 'Facility Name' );
		///
		
		//TA:107
		if ( $this->setting ( 'display_region_i' )) {
		    $trainerFields ['region_i_name'] = $this->tr ( 'Region I' );
		}
		else if ( $this->setting ( 'display_region_h' )) {
		    $trainerFields ['region_h_name'] = $this->tr ( 'Region H' );
		}
		else if ( $this->setting ( 'display_region_g' )) {
		    $trainerFields ['region_g_name'] = $this->tr ( 'Region G' );
		}
		else if ( $this->setting ( 'display_region_f' )) {
		    $trainerFields ['region_f_name'] = $this->tr ( 'Region F' );
		}
		else if ( $this->setting ( 'display_region_e' )) {
		    $trainerFields ['region_e_name'] = $this->tr ( 'Region E' );
		}
		else if ( $this->setting ( 'display_region_d' )) {
		    $trainerFields ['region_d_name'] = $this->tr ( 'Region D' );
		}
		else if ( $this->setting ( 'display_region_c' )) {
		    $trainerFields ['region_c_name'] = $this->tr ( 'Region C (Local Region)' );
		}
		else if ($this->setting ( 'display_region_b' )) {
		    $trainerFields ['district_name'] = $this->tr ( 'Region B (Health District)' );
		}
		else {
		    $trainerFields ['province_name'] = $this->tr ( 'Region A (Province)' );
		}
		//////

		if ($this->view->viewonly) {
			$editLinkInfo ['disabled'] = 1;
			$linkInfo = array ();
			$colStatic = array_keys ( $trainerFields );
		} else {
			$linkInfo = array (// add links to datatable fields
			'linkFields' => $colStatic, 'linkId' => 'trainer_id', // use ths value in link
			'linkUrl' => Settings::$COUNTRY_BASE_URL . '/person/edit/id/%trainer_id%' );
			$linkInfo ['linkUrl'] = "javascript:submitThenRedirect('{$linkInfo['linkUrl']}/trainingredirect/$training_id');";
			$editLinkInfo = array ();
		}

		$html = EditTableHelper::generateHtmlTraining ( 'Trainer', $trainers, $trainerFields, $colStatic, $linkInfo, $editLinkInfo );
		$this->view->assign ( 'tableTrainers', $html ); 

		/****************************************************************************************************************
		* Participants */

		$locations = Location::getAll ();
		$customColDefs = array();
		//TA:#271
		$pass = 0;

		if ($training_id) {
			$persons = PersonToTraining::getParticipants ( $training_id )->toArray ();

			foreach ( $persons as $pid => $p ) {
			    //TA:#271
			    if($persons [$pid] ['pass_fail'] === 'pass'){
			        $pass++;
			    }
				$region_ids = Location::getCityInfo ( $p ['location_id'], $this->setting ( 'num_location_tiers' ) ); // todo expensive call, getcityinfo loads all locations each time??
				$persons [$pid] ['province_name'] = ($region_ids[1] ? $locations [$region_ids['1']] ['name'] : 'unknown');
				if ($region_ids[2])
					$persons [$pid] ['district_name'] = $locations [$region_ids[2]] ['name'];
				else
					$persons [$pid] ['district_name'] = 'unknown';

				if ($region_ids[3])
					$persons [$pid] ['region_c_name'] = $locations [$region_ids[3]] ['name'];
				else
					$persons [$pid] ['region_c_name'] = 'unknown';

				if ($region_ids[4])
					$persons [$pid] ['region_d_name'] = $locations [$region_ids[4]] ['name'];
				else
					$persons [$pid] ['region_d_name'] = 'unknown';

				if ($region_ids[5])
					$persons [$pid] ['region_e_name'] = $locations [$region_ids[5]] ['name'];
				else
					$persons [$pid] ['region_e_name'] = 'unknown';

				if ($region_ids[6])
					$persons [$pid] ['region_f_name'] = $locations [$region_ids[6]] ['name'];
				else
					$persons [$pid] ['region_f_name'] = 'unknown';

				if ($region_ids[7])
					$persons [$pid] ['region_g_name'] = $locations [$region_ids[7]] ['name'];
				else
					$persons [$pid] ['region_g_name'] = 'unknown';

				if ($region_ids[8])
					$persons [$pid] ['region_h_name'] = $locations [$region_ids[8]] ['name'];
				else
					$persons [$pid] ['region_h_name'] = 'unknown';

				if ($region_ids[9])
					$persons [$pid] ['region_i_name'] = $locations [$region_ids[9]] ['name'];
				else
					$persons [$pid] ['region_i_name'] = 'unknown';
			}
		} else {
			$persons = array ();
		}


		if (! $this->setting ( 'display_middle_name' )) {
			$personsFields = array ('first_name' => $this->tr ( 'First Name' ), 'last_name' => $this->tr ( 'Last Name' ), 'birthdate' => t ( 'Date of Birth' ), 'facility_name' => t ( 'Facility' ) );
		} else if ($this->setting ( 'display_middle_name_last' )) {
			$personsFields = array ('first_name' => $this->tr ( 'First Name' ), 'last_name' => $this->tr ( 'Last Name' ), 'middle_name' => "..." . $this->tr ( 'Middle Name' ), 'birthdate' => t ( 'Date of Birth' ), 'facility_name' => t ( 'Facility' ) );
		} else {
			$personsFields = array ('first_name' => $this->tr ( 'First Name' ), 'middle_name' => "..." . $this->tr ( 'Middle Name' ), 'last_name' => $this->tr ( 'Last Name' ), 'birthdate' => t ( 'Date of Birth' ), 'facility_name' => t ( 'Facility' ) );
		}
		if ( $this->setting ( 'module_attendance_enabled' )) {
			$personsFields['duration_days'] = t($this->tr ( 'Days Attended' ));
			$personsFields['award_phrase']  = t($this->tr ( 'Complete' ));

			$rowArray = OptionList::suggestionList('person_to_training_award_option', array('id', 'award_phrase'), false, 9999, false, false);
			$elements = array(0 => array('text' => ' ', 'value' => 0));
			foreach ($rowArray as $i => $tablerow) {
				$elements[$i+1]['text']  = $tablerow['award_phrase'];
				$elements[$i+1]['value'] = $tablerow['id'];
			}
			$elements = json_encode($elements); // yui data table will enjoy spending time with a json encoded array

			$customColDefs['award_phrase']       = "editor:'dropdown', editorOptions: {dropdownOptions: $elements }";
		}
		if ( $this->setting ( 'display_viewing_location' ) ) {
			$personsFields['location_phrase']    = $this->tr ( 'Viewing Location' );

			$vLocDropDown = OptionList::suggestionList('person_to_training_viewing_loc_option', array('id', 'location_phrase'), false, 9999, false, false);
			$elements = array(0 => array('text' => '', 'value' => 0));
			foreach ($vLocDropDown as $i => $tablerow) {
				$elements[$i+1]['text'] = $tablerow['location_phrase'];
				$elements[$i+1]['value'] = $tablerow['id'];
			}
			$elements = json_encode($elements);

			$customColDefs['location_phrase']    = "editor:'dropdown', editorOptions: {dropdownOptions: $elements }";

		}
		if ( $this->setting ( 'display_budget_code' ) ) {
			$personsFields['budget_code_phrase'] = $this->tr ( 'Budget Code' );

			$budgetDropDown = OptionList::suggestionList('person_to_training_budget_option', array('id', 'budget_code_phrase'), false, 9999, false, false);
			$elements = array( 0 => array('text' => '', 'value' => 0));
			foreach ($budgetDropDown as $i => $tablerow) {
				$elements[$i+1]['text'] = $tablerow['budget_code_phrase'];
				$elements[$i+1]['value'] = $tablerow['id'];
			}
			$elements = json_encode($elements);
			$customColDefs['budget_code_phrase'] = "editor: 'dropdown' , editorOptions:{dropdownOptions: $elements} ";
		}



		if ( $this->setting ( 'display_region_i' )) {
			$personsFields ['region_i_name'] = $this->tr ( 'Region I' );
		}
		else if ( $this->setting ( 'display_region_h' )) {
			$personsFields ['region_h_name'] = $this->tr ( 'Region H' );
		}
		else if ( $this->setting ( 'display_region_g' )) {
			$personsFields ['region_g_name'] = $this->tr ( 'Region G' );
		}
		else if ( $this->setting ( 'display_region_f' )) {
			$personsFields ['region_f_name'] = $this->tr ( 'Region F' );
		}
		else if ( $this->setting ( 'display_region_e' )) {
			$personsFields ['region_e_name'] = $this->tr ( 'Region E' );
		}
		else if ( $this->setting ( 'display_region_d' )) {
			$personsFields ['region_d_name'] = $this->tr ( 'Region D' );
		}
		else if ( $this->setting ( 'display_region_c' )) {
			$personsFields ['region_c_name'] = $this->tr ( 'Region C (Local Region)' );
		}
		else if ($this->setting ( 'display_region_b' )) {
			$personsFields ['district_name'] = $this->tr ( 'Region B (Health District)' );
		}
		else {
			$personsFields ['province_name'] = $this->tr ( 'Region A (Province)' );
		}


		$colStatic = array_keys ( $personsFields ); // static calumns (From field keys)
		if ( $this->setting ( 'module_attendance_enabled' ) || $this->setting ( 'display_viewing_location' ) || $this->setting( 'display_budget_code' ) ) {
			foreach( $colStatic as $i => $v )
				if( $v == 'duration_days' || $v == 'award_phrase' || $v == 'budget_code_phrase' || $v == 'location_phrase')
					unset( $colStatic[$i] ); // remove 1 so we can edit the field
		}

		if ($this->view->viewonly) {
			$editLinkInfo ['disabled'] = 1;
			$linkInfo = array ();
		} else {

			$linkInfo = array (// add links to datatable fields
			'linkFields' => $colStatic, 'linkId' => 'person_id', // use ths value in link
			'linkUrl' => Settings::$COUNTRY_BASE_URL . '/person/edit/id/%person_id%' );
			$linkInfo ['linkUrl'] = "javascript:submitThenRedirect('{$linkInfo['linkUrl']}/trainingredirect/$training_id');";

			$editLinkInfo = array ();// add link next to "Remove"
			if ($this->setting('display_training_pre_test')) {
				$editLinkInfo[] = array ('linkName' => t ( 'Pre-Test' ), 'linkId' => 'id', // use this value in link
				'linkUrl' => "javascript:updateScore('Pre-Test', %id%, '" . Settings::$COUNTRY_BASE_URL . "/training/scores-update', '%score_pre%');" ); // do not translate label/key
			}

			if ($this->setting('display_training_post_test')) {
				$editLinkInfo[] = array ('linkName' => t ( 'Post-Test' ), 'linkId' => 'id', // use this value in link
				'linkUrl' => "javascript:updateScore('Post-Test', %id%, '" . Settings::$COUNTRY_BASE_URL . "/training/scores-update', '%score_post%');" ); // do not translate label/key
			}

			//TA:17: 09/03/2014
			if ($this->setting('display_training_score')) {
			$editLinkInfo[] = array ('linkName' => t ( 'Scores' ), 'linkId' => 'id', // use this value in link
				'linkUrl' => "javascript:submitThenRedirect('" . Settings::$COUNTRY_BASE_URL . "/training/scores/ptt_id/%id%');" );
			}
			// old
			//'linkUrl' => Settings::$COUNTRY_BASE_URL."/training/scores/training/$training_id/person/%person_id%",
			//$editLinkInfo['linkUrl'] = "javascript:submitThenRedirect('{$editLinkInfo['linkUrl']}');";


		}

		$html = EditTableHelper::generateHtmlTraining ( 'Persons', $persons, $personsFields, $colStatic, $linkInfo, $editLinkInfo, $customColDefs);
		$this->view->assign ( 'tablePersons', $html );

		/****************************************************************************************************************/
		/* Attached Files */

		FileUpload::displayFiles ( $this, 'training', $row->id, (! $this->view->viewonly) );

		//$this->view->assign('files', 'x' . FileUpload::displayFiles($this, 'training', $row->id));


		// File upload form
		if (! $this->view->viewonly) {
			$this->view->assign ( 'filesForm', FileUpload::displayUploadForm ( 'training', $row->id, FileUpload::$FILETYPES ) );
		}

		/****************************************************************************************************************/
		/* Approval status */

		if ( $this->setting('module_approvals_enabled') )
		{
			$canApprove = ($this->hasACL('master_approver') && $row->is_approved == 2) || ($this->hasACL('approve_trainings') && ! $row->is_approved);
			$this->view->assign('can_approve', $canApprove);

			if ($canApprove)
				$this->view->assign('approve_val', '');
			else
				$this->view->assign('approve_val', $row->is_approved);

			// disable control
			if (!$canApprove or !$this->hasACL('approve_trainings')) {
				$this->view->assign('approve_disable_str', 'disabled');
			} else {
				$this->view->assign('approve_disable_str', '');
			}
		}

		/****************************************************************************************************************/
		/* Attached Files */


		// mode
		$this->view->assign ( 'mode', $this->getParam ( 'action' ) );

		switch ($this->getParam ( 'msg' )) {
			case 'duplicate' :
			$this->view->assign ( 'msg', t ( 'Training' ).' '.t( 'session has been duplicated.<br>You can edit the duplicate session below.' ) );
			break;
			default :
			break;
		}

		// edit variables
		if ($this->getParam ( 'action' ) != 'add') {
			//audit history
			$creatorObj = new User ( );
			$updaterObj = new User ( );
			$creatorrow = $creatorObj->findOrCreate ( $row->created_by );
			$rowRay ['creator'] = ($creatorrow->first_name) . ' ' . ($creatorrow->last_name);
			$updaterrow = $updaterObj->findOrCreate ( $row->modified_by );
			$rowRay ['updater'] = ($updaterrow->first_name) . ' ' . ($updaterrow->last_name);
		}

		if (empty ( $trainers ) || empty ( $persons )) {
			$this->view->assign ( 'isIncomplete', true );
		}

		// default start date?
		if($this->getSanParam('start-date')){
			$parts = explode('/', $this->getSanParam('start-date'));
			if(count($parts) == 3) {
				$rowRay['start-day'] = $parts[0];
				$rowRay['start-month'] = $parts[1];
				$rowRay['start-year'] = $parts[2];
			}
		}

		// row values
		$this->view->assign ( 'row', $rowRay );
		
		//TA:#271  calcualte pass/fail
		if($this->setting('display_training_pt_pass')){
		  $this->view->assign ( 'pass', ($pass/count($persons))*100 . "%" );
		}
	}

	/**
	* JSON request to insert a new value into an option table (used for dynamic drop-downs and adding a dynamic checkbox)
	*/
	public function insertTableAction() {
		$request = $this->getRequest ();
		if ($request->isXmlHttpRequest ()) {

			$table = $this->getSanParam ( 'table' );
			$column = $this->getSanParam ( 'column' );
			$value = $this->getSanParam ( 'value' );

			if ($table && $column && $value) {
				$tableObj = new ITechTable ( array ('name' => $table ) );

				$undelete = $this->getSanParam ( 'undelete' );
				if ($undelete) {
					try {

						$row = $tableObj->undelete ( $column, $value );
						$sendRay ['insert'] = $row->id;
						$this->sendData ( $sendRay );

					} catch ( Zend_Exception $e ) {
						$this->sendData ( array ("insert" => 0, 'error' => $e->getMessage () ) );
					}
				} else {

					// Attempt to insert new


					try {
						$insert = $tableObj->insertUnique ( $column, $value );
						$sendRay ['insert'] = "$insert";
						$orgid = $last_id = $tableObj->getAdapter()->lastInsertId();

						if ($insert == - 1)
						$sendRay ['error'] = '"%s" ' . t ( 'already exists' ) . '.';
						if ($insert == - 2)
						$sendRay ['error'] = '"%s" ' . ('already exists, but was deleted.  Would you like to undelete?');

						// associate new training title with training category
						if ($table == 'training_title_option' && ! isset ( $sendRay ['error'] ) && $cat_id = $this->getSanParam ( 'cat_id' )) {
							$tableCatObj = new ITechTable ( array ('name' => 'training_category_option_to_training_title_option' ) );
							$tableCatObj->insert ( array ('training_category_option_id' => $cat_id, 'training_title_option_id' => $insert ) );
						}
						// give person organizer_access if they are creating the organizer.
						if ($table == 'training_organizer_option' && ! isset ( $sendRay ['error'] ) && $orgid) {
							$auth = Zend_Auth::getInstance ();
							$identity = $auth->getIdentity ();
							$user_id = $identity->id;
							$orgtable = new ITechTable ( array ('name' => 'user_to_organizer_access') );
							$data = array(
								'training_organizer_option_id' => $last_id,
								'user_id' => $user_id);
							$orgtable->insert($data);
						}

						// send json data to front end
						$this->sendData ( $sendRay );

					} catch ( Zend_Exception $e ) {
						$this->sendData ( array ("insert" => 0, 'error' => $e->getMessage () ) );
						error_log ( $e );
					}

				}

			}

		}
	}

	/**
	* editTable ajax
	*/
	private function ajaxEditTable() {

		if (! $this->hasACL ( 'edit_course' )) {
			$this->doNoAccessError ();
		}

		$training_id = $this->getParam ( 'id' );
		$do = $this->getParam ( 'edittable' );
		
		if (! $training_id) { // user is adding a new session (which does not have an id yet)
			$this->sendData ( array ('0' => 0 ) );
			return;
		}

		$action = $this->getParam ( 'a' );
		$row_id = $this->getParam ( 'row_id' );
		
		if ($do == 'trainer') { // update trainer table
			require_once ('models/table/Training.php');

			if ($action == 'add') {

				$days = $this->getParam ( 'days' );
				$result = TrainingToTrainer::addTrainerToTraining ( $row_id, $training_id, $days );
				$sendRay ['insert'] = $result;
				if ($result == - 1) {
					$sendRay ['error'] = t ( 'This' ).' '.t( 'trainer' ).' '.t( 'is already in this training session.' );
				}
				$this->sendData ( $sendRay );

			} elseif ($action == 'del') {
				$tableObj = new TrainingToTrainer ( );
				$result = $tableObj->delete ( "id=$row_id", true );

				$this->sendData ( array ('delete' => $result ) );

			} else { // update a row?


				$days = $this->getParam ( 'duration_days' );
				if ($days) {
					$tableObj = new TrainingToTrainer ( );
					$result = $tableObj->update ( array ("duration_days" => $days ), "id=$row_id" );
					$sendRay ['update'] = $result;

					if (! $result) {
						$sendRay ['error'] = t ( 'Could not update this record.' );
					}

					$this->sendData ( $sendRay );
				}

			}

		} else if ($do == 'persons') { // update person table
			require_once ('models/table/PersonToTraining.php');
			$tableObj = new PersonToTraining ( );

			if ($action == 'add') {

				$result = $tableObj->addPersonToTraining ( $row_id, $training_id );
				$sendRay ['insert'] = $result;
				if ($result == - 1) {
					$sendRay ['error'] = t ( 'This' ).' '.t( 'participant' ).' '.t( 'is already in this training session.' );
				}
				$this->sendData ( $sendRay );

			} elseif ($action == 'del') {
				$result = $tableObj->delete ( "id=$row_id", true );
				$this->sendData ( array ('delete' => $result ) );
			} else { // update a row?

				$days = $this->getSanParam ( 'duration_days' );
				if ($days !== "") {
					$tableObj = new PersonToTraining ( );
					$result = $tableObj->update ( array ("duration_days" => $days ), "id=$row_id" );
					$sendRay ['update'] = $result;

					if (! $result) {
						$sendRay ['error'] = t ( 'Could not update this record.' );
					}

					$this->sendData ( $sendRay );
				}

				$award_id = $this->getSanParam ( 'award_phrase' );
				if ($award_id !== "") {
					$tableObj = new PersonToTraining ( );
					$result = $tableObj->update ( array ("award_id" => $award_id ), "id=$row_id" );
					$sendRay ['update'] = $result;
					if (! $result) {
						$sendRay ['error'] = t ( 'Could not update this record.' );
					}

					$this->sendData ( $sendRay );
				}

				$viewing_loc_option_id = $this->getSanParam ( 'location_phrase' );
				if ($viewing_loc_option_id !== "") {
					$tableObj = new PersonToTraining ( );
					$result = $tableObj->update ( array ("viewing_location_option_id" => $viewing_loc_option_id ), "id=$row_id" );
					$sendRay ['update'] = $result;
					if (! $result) {
						$sendRay ['error'] = t ( 'Could not update this record.' );
					}

					$this->sendData ( $sendRay );
				}

				$budget_code_option_id = $this->getSanParam ( 'budget_code_phrase' );
				if ($budget_code_option_id !== "") {
					$tableObj = new PersonToTraining ( );
					$result = $tableObj->update ( array ("budget_code_option_id" => $budget_code_option_id ), "id=$row_id" );
					$sendRay ['update'] = $result;
					if (! $result) {
						$sendRay ['error'] = t ( 'Could not update this record.' );
					}

					$this->sendData ( $sendRay );
				}

			}

		}

		if (!isset($sendRay['error'])) {
			// update "modified_by" field in training table
			$tableObj = new Training ();
			$tableObj->update(array(), "id = $training_id");
		}
	}

	/**
	 * TA:17: 10/13/2014
	* Import a training
	*/
	
	public function importActionExcel() {
		$errs = array();
		$this->view->assign('pageTitle', t( 'Import a training' ));
		
		// template redirect
		if ( $this->getSanParam('download') )
			return $this->importTrainingTemplateAction();
		
		if( ! $this->hasACL('import_training') )
			$this->doNoAccessError ();
		
		$filename = ($_FILES['upload']['tmp_name']);
		if ( $filename ){
			
			require_once('models/table/TrainingLocation.php');
			require_once('models/table/Person.php');
			require_once('models/table/TrainingToTrainer.php');
			require_once('models/table/PersonToTraining.php');
			
			$trainingObj = new Training ();
			$personToTraining = new PersonToTraining();
			
			$rows = $this->_excel_parser($filename);
			
			$values = array();
			
			//get training info
			$values['training_organizer_phrase'] = $rows[0][2];
			$values['training_start_date'] = $rows[1][3];
			$values['training_end_date'] = $rows[2][3];
			$values['training_title_option_id'] = '';
			for($i=4; $i<15; $i++){
				if(! empty($rows[$i][3])){
					$values['training_title_option_id'] = $rows[$i][2];
					break;
				}
			}
			
			try{
				if (isset($values['training_title_option_id'])){$values['training_title_option_id']= $this->_importHelperFindOrCreate('training_title_option','training_title_phrase',$values['training_title_option_id']); }
				if ($values['training_start_date']){$values['training_start_date'] = $this->_date_to_sql($values['training_start_date']); }
				if ($values['training_end_date']) {$values['training_end_date'] = $this->_date_to_sql($values['training_end_date']); }
				if (isset($values['training_organizer_phrase'])){$values['training_organizer_option_id']= $this->_importHelperFindOrCreate('training_organizer_option','training_organizer_phrase',$values['training_organizer_phrase']); }
				//default values
				$values['has_known_participants'] = '1';
				$values['comments'] = '';
				$values['got_comments'] = '';
				$values['objectives'] = '';
				$values['is_approved'] = '1';
				$values['is_tot'] = '0';
				$values['is_refresher'] = '1';

				$tableObj = $trainingObj->createRow();
				$tableObj = ITechController::fillFromArray($tableObj, $values);
				$training_id = $tableObj->save();
				if ($training_id > 0) {
					$db = $this->dbfunc();
					$personObj = new Person (); //in case if we will need to add new persons
					for($i=17; $i< sizeof($rows); $i++){
							if(!empty($rows[$i][1]) || !empty($rows[$i][2])  || !empty($rows[$i][3]) ){
								//first, middle, last
								$trainer_id = Person::tryFind(trim($rows[$i][1]), trim($rows[$i][2]), trim($rows[$i][3]));
								if ( !$trainer_id ) { //add new person to Person
									if(!trim($rows[$i][1])){
										$errs[] = t("Could not add person to training. First name is undefined.").space.t('Training')." #$training_id: " . $rows[$i][1] . " " . $rows[$i][2] . " " . $rows[$i][3];
										continue;
									}
									if(!trim($rows[$i][3])){
										$errs[] = t("Could not add person to training. Last name is undefined.").space.t('Training')." #$training_id: " . $rows[$i][1] . " " . $rows[$i][2] . " " . $rows[$i][3];
										continue;
									}
									if(!trim($rows[$i][4])){
										$errs[] = t("Could not add person to training. Gender is undefined.").space.t('Training')." #$training_id: " . $rows[$i][1] . " " . $rows[$i][2] . " " . $rows[$i][3];
										continue;
									}
									
									$values_person = array();
									
									//optional fields
									$values_person['phone_home'] = trim($rows[$i][9]);
									$values_person['email'] = trim($rows[$i][10]);
									$values_person['middle_name'] = trim($rows[$i][2]);
									
									//required fields
									$values_person['first_name'] = trim($rows[$i][1]);
									$values_person['last_name'] = trim($rows[$i][3]);
									$values_person['gender'] = trim($rows[$i][4]);
									$values_person['primary_qualification_option_id'] = '0';
									if(trim($rows[$i][5])){
										$cadre_id = $db->fetchOne ( "SELECT id FROM person_qualification_option WHERE qualification_phrase = '" . trim($rows[$i][5]) . "' LIMIT 1" );
										if($cadre_id){
											$values_person['primary_qualification_option_id'] = $cadre_id;
										}
									}
									
									//if facility id not found then allow to add person with empty facility id
									$facility_name = trim($rows[$i][6]);
									$lga_name = trim($rows[$i][7]);
									$state_name = trim($rows[$i][8]); 
									$values_person['facility_id'] = '0';
									
									if ($facility_name) { 
										//find facility id only if state id and lga id are defined
 										if (!empty ( $state_name )){
 											$state_id = $db->fetchOne ( "SELECT id FROM location WHERE location_name = '" . $state_name . "' LIMIT 1" );
											if($state_id != null && !empty ( $lga_name )){
												$lga_id = $db->fetchOne (  "SELECT id FROM location WHERE location_name = '" . $lga_name . "' AND parent_id = $state_id  LIMIT 1" );
												if($lga_id != null && !empty ( $facility_name )){
													$values_person['facility_id'] = $db->fetchOne (  "SELECT id FROM facility WHERE facility_name = '" . $facility_name . "' AND location_id = $lga_id  LIMIT 1" );
												}
											}
 										}
									}
									
									$mes_facility = '';
									if($facility_name) $mes_facility .= $facility_name;
									if($lga_name){
										if($mes_facility) $mes_facility .= ", ";
										$mes_facility .= $lga_name;
									}
									if($state_name){
										if($mes_facility) $mes_facility .= ", ";
										$mes_facility .= $state_name;
									}
									$mes_person = '';
									if($rows[$i][1]) $mes_person .= $rows[$i][1];
									if($rows[$i][2]){
										if($mes_person) $mes_person .= ", ";
										$mes_person .= $rows[$i][2];
									}
									if($rows[$i][3]){
										if($mes_person) $mes_person .= ", ";
										$mes_person .= $rows[$i][3];
									}
									
									if($values_person['facility_id'] == '0'){				
										$errs [] = "Error locating facility: '" . $mes_facility . "', Person: '" . $mes_person . "' will have no assigned facility.";
									}
									
									$personrow = $personObj->createTableRow();
									$personrow = ITechController::fillFromArray($personrow, $values_person);
									$trainer_id = $personrow->save();
									if(!$trainer_id){
										$errs[] = t("Could not add person to training.").space.t('Training')." #$training_id, Person: '" . $mes_person . "'";
										continue;
 									}
								}
								//	this not working 
								//TrainingToTrainer::addTrainerToTraining($trainer_id, $training_id, 0); 
								$personToTraining->addPersonToTraining($trainer_id, $training_id);
							
						}
					}
				}
			} catch (Exception $e) {
				$errored = 1;
				$errs[]  = nl2br($e->getMessage()).' '.t ( 'ERROR: The training data could not be saved.').space.($training_id ? t('Training').space."#$training_id".space.t('Warning: Some data imported.').space.t('Check Funding, PEPFAR, Topic, Refresher options and Participants and Trainers Data; or delete the training and try again.') : '') ;
			}
			
			// done processing rows
			$_POST['redirect'] = null;
			$status = ValidationContainer::instance();
			if( empty($errored) && empty($errs) )
				$stat = t ('Your changes have been saved.');
			else
				$stat = t ('Error importing data. Some data may have been imported and some may not have.');
			
			foreach ($success as $errmsg)
				$stat .= '<br>'.$errmsg;
			foreach($errs as $errmsg)
				$stat .= '<br>'.'Error: '.htmlspecialchars($errmsg, ENT_QUOTES);
			
			$stat .='<br><a href='. Settings::$COUNTRY_BASE_URL . '/training/view/id/' . $training_id . '>View new training</a>';
			
			$status->setStatusMessage($stat);
			$this->view->assign('status',$status);
			
		}	
	}
	//TA:66:1 return CSV import
	public function importAction() {
	
		//ini_set('max_execution_time','300');
		$errs = array();
		$this->view->assign('pageTitle', t( 'Import a training' ));
	
		// template redirect
		if ( $this->getSanParam('download') )
			return $this->importTrainingTemplateAction();
	
		if( ! $this->hasACL('import_training') )
			$this->doNoAccessError ();
	
		//CSV STUFF
		$filename = ($_FILES['upload']['tmp_name']);
		if ( $filename ){
			require_once('models/table/TrainingLocation.php');
			require_once('models/table/Person.php');
			require_once('models/table/TrainingToTrainer.php');
			require_once('models/table/PersonToTraining.php');
	
			$trainingObj = new Training ();
			$personToTraining = new PersonToTraining();
			while ($row = $this->_csv_get_row($filename) ){
			    //TA:#213
			    //INFORCE user to create files only in UTF-8 encoded:
			    //Option 1: Excel:Save as Unicode Text -> Notepad: replace tabs with commas, save as csv UTF-8
			    //Option 2: OpenOffice
			    //It is not required for english, but absolutelly required for special characteristics.
			    //If files saved in UTF-8 encoded, so we do not need this line
			    // $row = array_map("utf8_encode", $row); 
				$values = array();
				if (! is_array($row) )
					continue;
				if (! isset($cols) ) {
					$cols = $row;	// first row is headers (fieldnames)
					continue;
				}
				if (! empty($row) ) { // add
					$countValidFields = 0;
					foreach($row as $i=>$v){
						if ( empty($v) && $v !== '0' )
							continue;
						if ( $v == 'n/a') // has to be able to process values from a data export
							$v = NULL;
						$countValidFields++;
						if (strpos($v, "\n")){ // explode by newline then comma to give us arrays
							$v = explode("\n", $v);
							foreach ($v as $key => $value) {
								$delimiter = strpos($value, ',');
								if ($delimiter && $value[$delimiter - 1] != '\\') // todo this doesnt really work, explode will still break it (supposed to be a \, handler (escaped comma), so you can use commas in fields)
									$values[$cols[$i]][] = explode(',', $this->sanitize($value)); // trimmed later
								else
									$values[$cols[$i]][] = $this->sanitize($value);
							}
						} else {
							$delimiter = strpos($v, ',');
							if ($delimiter && $v[$delimiter - 1] != '\\')
								$values[$cols[$i]] = explode(',', $this->sanitize($v));
							else
								$values[$cols[$i]] = $this->sanitize($v);
						}
	
					}
				} // all values are now in a hash ex: $values['training_id']
				if ( $countValidFields ) {
					//validate
					if (isset($values['uuid']))   {     unset($values['uuid']);         }
					if (isset($values['id']))     {     unset($values['id']);           }
					if (isset($values['is_deleted'])) { unset($values['is_deleted']);   }
					if (isset($values['created_by'])) { unset($values['created_by']);   }
					if (isset($values['modified_by'])){ unset($values['modified_by']);  }
					if (isset($values['timestamp_created'])){ unset($values['timestamp_created']); }
					if (isset($values['timestamp_updated'])){ unset($values['timestamp_updated']); }
					if (!$this->hasACL('approve_trainings')){ $values['is_approved'] = 0; }
					if (!$this->setting('module_approvals_enabled') == 0) { $values['is_approved'] = 1; }
					if ($values['training_start_date']){                $values['training_start_date'] = $this->_date_to_sql($values['training_start_date']); }
					if ($values['training_end_date']) {                 $values['training_end_date'] = $this->_date_to_sql($values['training_end_date']); }
					if ($values['training_length_interval'] == 'days')  $values['training_length_interval'] = 'day';
					if ($values['training_length_interval'] == 'weeks') $values['training_length_interval'] = 'week';
					if ($values['training_length_interval'] == 'hours') $values['training_length_interval'] = 'hour';
					// remap fields (field names differ vs actual column names on export/template for readibility thanks to some function in iTechTranslate)
					if ($values['training_title_phrase'])               $values['training_title_option_id'] = $values['training_title_phrase'];
					if ($values['training_title'])                      $values['training_title_option_id'] = $values['training_title'];
					if ($values['custom3_phrase'])                      $values['custom_3'] = $values['custom3_phrase'];
					if ($values['custom4_phrase'])                      $values['custom_4'] = $values['custom4_phrase'];
					if ($values['custom5_phrase'])                      $values['custom_5'] = $values['custom5_phrase'];
					if ($values['refresher_phrase_option'])             $values['training_refresher_phrase'] = $values['refresher_phrase_option'];
					if ($values['training_refresher_option_id'])        $values['training_refresher_phrase'] = $values['training_refresher_option_id'];
					if ($values['language_phrase'])                     $values['training_primary_language_option_id'] = $values['language_phrase'];
					// required fields
					if( ! $values['training_title_option_id'] )         $values['training_title_option_id'] = 0;
					if( ! $values['comments'] )                         $values['comments'] = '';
					if( ! $values['got_comments'] )                     $values['got_comments'] = '';
					if( ! $values['objectives'] )                       $values['objectives'] = '';
	
					// training location
					$num_location_tiers = $this->setting('num_location_tiers');
					$bSuccess = true;
					$errored = 0;
	
					if (! isset($regionNames) )
						$regionNames = array ('', t('Region A (Province)'), t('Region B (Health District)'), t('Region C (Local Region)'), t('Region D'), t('Region E'), t('Region F'), t('Region G'), t('Region H'), t('Region I'), t('City') );
	
					$location_id = 0;
					$training_location_id = $values['training_location_id'] ? $values['training_location_id'] : 0; // training_location_id and a default
	
					// validity check location name
					if ($training_location_id && $values['training_location_name']) {
						$dupe = new TrainingLocation();
						$select = $dupe->select()->where('id =' . $training_location_id . ' and training_location_name = "' . $values['training_location_name'] . '"');
						$training_location_id = ($a = $dupe->fetchRow($select)) ? $a->id : 0; // valid or doesnt match lets reset it and insert a new one this record was imported from another site
					}
					// insert new location
					$tier = 1;
					if (!$training_location_id && $values['training_location_name'] ) // we need to search for loc or insert a new one
					{
						for ($i=1; $i < $num_location_tiers + 1; $i++) { // insert/find locations
							$location_name = ($i == $num_location_tiers) ? @$values[t('City')] : @$values[$regionNames[$i]]; // last one?
	
							if ( $regionNames[$i] != t('City') && ( empty($location_name) || $bSuccess == false ) ) {
								$bSuccess = false;
								continue;
							}
							else { // insert
								$location_id = Location::insertIfNotFound ( $location_name, $location_id, $tier );
								if (! $location_id) {
									$bSuccess = false;
									$errs[] = t('Error locating/creating region or city:').' '.$location_name.' '.t('Training Location').': '.$values['training_location_name'];
									break;
								}
							}
							$tier++;
						}
	
						if ($bSuccess && $location_id){
							// we have a region id (location id), now save training_location
							// dupecheck
							$dupe = new TrainingLocation();
							$select = $dupe->select()->where('location_id =' . $location_id . ' and is_deleted = 0 and training_location_name = "' . $values['training_location_name'] . '"');
							if( $a = $dupe->fetchRow($select) ) {
								$training_location_id = $a->id;
							}
							else {
								// save
								try {
									$trainingLocModel = new TrainingLocation();
									$trainingLocationObj = $trainingLocModel->createRow();
									$trainingLocationObj->training_location_name = $values['training_location_name'];
									$trainingLocationObj->location_id = $location_id;
									$training_location_id = $trainingLocationObj->save();
								} catch (Exception $e) {
									$errored = 1;
									$errs[]  = nl2br($e->getMessage()).space.t ( 'ERROR: The training location could not be saved.' ).space.'"'.$values['training_location_name'].'"';
								}
								if(! $training_location_id)
									$errored = 1;
							}
						}
						if ( $errored || ! $bSuccess ) { // couldn't save location
							$errs[] = t('Error locating/creating').space.t('Region').space.': '.$location_name.' '.t('Training Location').': '.$values['training_location_name'];
							$errored = 0;
							$bSuccess = true;
						}
					}
					if ($training_location_id)
						$values['training_location_id'] = $training_location_id;
					// done, saved training location.
					// save
	
					try {
						// option tables / lookup tables // insert or get id, and remap col names from csv template name to actual column name, asdf_phrase to asdf_option_id
						// the logic is: if ($values['title_phrase']) $training->title_option_id = findOrCreate('training_title', $values['title_phrase']) );
						if (isset($values['training_title_option_id'])){             $values['training_title_option_id']             = $this->_importHelperFindOrCreate('training_title_option',          'training_title_phrase',           $values['training_title_option_id']); }
						if (isset($values['training_got_curriculum_phrase'])){       $values['training_got_curriculum_option_id']    = $this->_importHelperFindOrCreate('training_got_curriculum_option', 'training_got_curriculum_phrase',  $values['training_got_curriculum_phrase']); }
						if (isset($values['training_level_phrase'])){                $values['training_level_option_id']             = $this->_importHelperFindOrCreate('training_level_option',          'training_level_phrase',           $values['training_level_phrase']); }
						if (isset($values['custom1_phrase'])){                       $values['training_custom_1_option_id']          = $this->_importHelperFindOrCreate('training_custom_1_option',       'custom1_phrase',                  $values['custom1_phrase']); }
						if (isset($values['custom2_phrase'])){                       $values['training_custom_2_option_id']          = $this->_importHelperFindOrCreate('training_custom_2_option',       'custom2_phrase',                  $values['custom2_phrase']); }
						if (isset($values['training_organizer_phrase'])){            $values['training_organizer_option_id']         = $this->_importHelperFindOrCreate('training_organizer_option',      'training_organizer_phrase',       $values['training_organizer_phrase']); }
						if (isset($values['training_method_phrase'])){               $values['training_method_option_id']            = $this->_importHelperFindOrCreate('training_method_option',         'training_method_phrase',          $values['training_method_phrase']); }
						if (isset($values['training_primary_language_option_id'])){  $values['training_primary_language_option_id']  = $this->_importHelperFindOrCreate('trainer_language_option',        'language_phrase',                 $values['training_primary_language_option_id']); }
						if (isset($values['training_refresher_phrase']) && !is_array($values['training_refresher_phrase'])){ $values['training_refresher_option_id'] = $this->_importHelperFindOrCreate('training_refresher_option', 'refresher_phrase_option', $values['training_refresher_phrase']); }
						// participants, trainers
						$tableObj = $trainingObj->createRow();
						$tableObj = ITechController::fillFromArray($tableObj, $values);
						$row_id = $tableObj->save();
						// done save - we have a training id now
						// linked tables
						if ($row_id > 0) {
							$training_id = $row_id;
							$success[] = t('Successfully imported training #').space
							.'<a href="'. Settings::$COUNTRY_BASE_URL . '/training/view/id/'.$training_id.'">'.$training_id.'</a>';
							// multiOptionList tables
							if ( $values['funding_phrase'] ) {
								$bSuccess = $this->_importHelperFindOrCreateMOLT('training_funding_option',   'funding_phrase',                 $values['funding_phrase'],            'funding',   $training_id , $values['funding_amount'] );
								if ( ! $bSuccess ) $errs[] = t('Training').space."#$training_id ".t('Some Data not imported')." (funding)";
							}
							if ( $values['pepfar_category_phrase'] ) {
								$bSuccess = $this->_importHelperFindOrCreateMOLT('training_pepfar_categories_option', 'pepfar_category_phrase', $values['pepfar_category_phrase'],    'pepfar',    $training_id, $values['pepfar_duration_days'] );
								if ( ! $bSuccess ) $errs[] = t('Training').space."#$training_id ".t('Some Data not imported')." (PEPFAR)";
							}
							if ( $values['training_refresher_phrase'] ) {
								$bSuccess = $this->_importHelperFindOrCreateMOLT('training_refresher_option', 'refresher_phrase_option',        $values['training_refresher_phrase'], 'refresher', $training_id );
								if ( ! $bSuccess ) $errs[] = t('Training').space."#$training_id ".t('Some Data not imported')." (refresher course)";
							}
							if ( $values['training_topic_phrase'] ) {
								$bSuccess = $this->_importHelperFindOrCreateMOLT('training_topic_option',     'training_topic_phrase',          $values['training_topic_phrase'],     'topic',     $training_id );
								if ( ! $bSuccess ) $errs[] = t('Training').space."#$training_id ".t('Some Data not imported')." (training topic)";
							}
							//unknown participants
							if (!empty($values['unknown participants']))
							{
								$upTable = new ITechTable(array('name' => 'training_to_person_qualification_option'));
								foreach ($values['unknown participants'] as $i => $data) {
	
									if (empty($data) || $data == 'n/a')
										continue;
	
									try {
										# (this row is $data) row format: array( "2(na)"," 2(male)"," 2(female)"," "QualificationPhrase")
	
										$qual_id = $data[count($data)-1];
										$qual_id = trim($qual_id);
										if (!is_int($qual_id) && strpos($qual_id, '(na)') === false && strpos($qual_id, '(female)') === false && strpos($qual_id, '(male)') === false ) {
											$qual_id = $this->_importHelperFindOrCreate('person_qualification_option',   'qualification_phrase',   $qual_id);
										} else {
											$errs[] = 'Training #'.$row_id.space.t('You did not set a qualification for a group of unknown participants.');
											$qual_id = 0;
										}
	
										$tablerow = $upTable->createRow();
										$tablerow->id = null;
										$tablerow->training_id = $row_id;
										$tablerow->person_qualification_option_id = $qual_id ? $qual_id : 0;
										$tablerow->age_range_option_id = 0; // todo age_range_option_id
										#reference: $age_opts = OptionList::suggestionList('age_range_option',array('id','age_range_phrase'), false, 100, false);
										$tablerow->person_count_na = 0;
										$tablerow->person_count_male = 0;
										$tablerow->person_count_female = 0;
										foreach ($data as $v) {
											if ( strpos($v, '(na)') )     $tablerow->person_count_na     = trim(str_replace('(na)',     '', $v));
											if ( strpos($v, '(male)') )   $tablerow->person_count_male   = trim(str_replace('(male)',   '', $v));
											if ( strpos($v, '(female)') ) $tablerow->person_count_female = trim(str_replace('(female)', '', $v));
										}
										if (! $tablerow->person_count_na && ! $tablerow->person_count_male && ! $tablerow->person_count_female)
											continue; //empty
	
										$tablerow->save();
	
									} catch (Exception $e) {
										$errs[] = t('Error saving unknown participant information.').space.'Training #'.$row_id.space.'Error: '.$e->getMessage();
									}
								}
							}
	
							//training_to_trainer
	
							foreach (array( $values['trainers'], $values['participants'] ) as $imode => $personArr) {
								if ( empty($personArr) )
									continue;
								if (!is_array($personArr))
									$personArr = array($personArr);
								foreach ($personArr as $tRow) {
									if (!is_array($tRow))
										$tRow = trim( $tRow );
									if ($tRow == '' || $tRow == 'n/a')
										continue;
	
									#echo '<br>'.PHP_EOL.'trainer [t|p]'.$imode.' row @ training id #training_id: '.print_r($tRow,true)."<Br>".PHP_EOL;
									if( is_array($tRow) ) {
									// accept formats: (middle name always optional)
									// first last, first last
									// first last \n first last
									// id, first, mid, last \n #first, mid, last
									if(count($tRow) == 4 && is_numeric($tRow[0])) { // some handling if it has a middle name or ID # attached or exported from training search
										list($trainer_id, $trainer_first, $trainer_middle, $trainer_last) = $tRow;
									}
									else if(count($tRow) == 3 && $this->setting('display_middle_name') == 0 && is_numeric($tRow[0])) {
									list($trainer_id, $trainer_first, $trainer_last) = $tRow;
									}
									else
										list($trainer_first, $trainer_middle, $trainer_last) = $tRow;   // expects comma seperated list of names...
									} else {
									list($trainer_first, $trainer_middle, $trainer_last) = explode(' ', $tRow);
									}
									if ($trainer_middle && ! $trainer_last) {
									$trainer_last = $trainer_middle;
										$trainer_middle = '';
									}
									#echo "trainer_first, trainer_middle, trainer_last = $trainer_first, $trainer_middle, $trainer_last";
	
									//trim values in case
									$trainer_first  = trim($trainer_first);
									$trainer_middle = trim($trainer_middle);
									$trainer_last   = trim($trainer_last);
	
									#if (! $trainer_id) // id comes with exported data - todo if @search() matches Id ->
									$trainer_id = Person::tryFind($trainer_first, $trainer_middle, $trainer_last);
									#echo " and trainer_id = $trainer_id <br>\n";
									if ( !$trainer_id ) {
									$errs[] = t("Could not add user to training because they were not found in the system.").space.t('Training')." #$training_id: '$trainer_first $trainer_middle $trainer_last'";
									continue;
									}
									#echo " and trainer found, addTrainerToTraining($trainer_id, $training_id,0)<br>\n";
									// add them to training
									if ($imode == 0) TrainingToTrainer::addTrainerToTraining($trainer_id, $training_id, 0); // todo days
									elseif ($imode == 1) $personToTraining->addPersonToTraining($trainer_id, $training_id);
								}
								}
								}
								} catch (Exception $e) {
								$errored = 1;
								$errs[]  = nl2br($e->getMessage()).' '.t ( 'ERROR: The training data could not be saved.').space.($training_id ? t('Training').space."#$training_id".space.t('Warning: Some data imported.').space.t('Check Funding, PEPFAR, Topic, Refresher options and Participants and Trainers Data; or delete the training and try again.') : '') ;
								}
								if(! $row_id)
									$errored = 1;
								}
								}
								// done processing rows
								$_POST['redirect'] = null;
								$status = ValidationContainer::instance();
										if( empty($errored) && empty($errs) )
											$stat = t ('Your changes have been saved.');
											else
												$stat = t ('Error importing data. Some data may have been imported and some may not have.');
	
					foreach ($success as $errmsg)
														$stat .= '<br>'.$errmsg;
														foreach($errs as $errmsg)
								$stat .= '<br>'.'Error: '.htmlspecialchars($errmsg, ENT_QUOTES);
	
								$status->setStatusMessage($stat);
								$this->view->assign('status',$status);
		}
			// done with import
	}

	/**
	 * A template for importing a training
	 */
	public function importTrainingTemplateAction() {
		$sorted = array (
				array (
						'training_title_phrase' => '',
						'has_known_participants' => '1',
						'training_start_date' => '',
						'training_end_date' => '',
						'training_length_value' => '',
						'training_length_interval' => t('hours').space.t('or').space.t('days').space.t('or').space.t('weeks'),
						'training_location_id' => '',
						'training_location_name' => ''
				));
		// add some regions
		$num_location_tiers = $this->setting('num_location_tiers');
		$regionNames = array ('', t('Region A (Province)'), t('Region B (Health District)'), t('Region C (Local Region)'), t('Region D'), t('Region E'), t('Region F'), t('Region G'), t('Region H'), t('Region I') );
		for ($i=1; $i < $num_location_tiers; $i++) {
			//add regions
			$sorted[0][$regionNames[$i]] = '';
		}
		// add city region
		$sorted[0][t('City')] = '';
		$sorted[0] = array_merge($sorted[0], array('comments' => '',
				'got_comments' => '',
				'objectives' => '',
				'is_approved' => '1',
				'is_tot' => '0',
				'is_refresher' => '1',
				'pre' => '',
				'post' => '',
				'course_id' => '',
				'training_refresher_phrase' => '',
				'training_got_curriculum_phrase' => '',
				'training_level_phrase' => '',
				'training_method_phrase' => '',
				'training_primary_language_phrase' => '',
				'training_secondary_language_phrase' => '',
				'funding_phrase' => '',
				'funding_amount' => '',
				'pepfar_category_phrase' => '',
				'pepfar_duration_days' => '',
				'training_topic_phrase' => '',
				'custom1_phrase' => '',
				'custom2_phrase' => '',
				'custom_3' => '',
				'custom_4' => '',
				'custom_5' => '',
				'training_organizer_phrase' => '',
				'participants' => '',
				'trainers' => '',
				'unknown participants' => ''));
		//done, output a csv
		if( $this->getSanParam('outputType') == 'csv' ){
			$this->sendData ( $this->reportHeaders ( false, $sorted ) );
		}else if($this->getSanParam('outputType') == 'ImportTrainingTemplate.xlsx'){//TA:17: 10/14/2014
 			header('Content-Type: application/application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
 			header('Content-Disposition: attachment; filename="ImportTrainingTemplate.xlsx"');
 			header("Content-Type: application/force-download");
 			readfile(Globals::$BASE_PATH . '/html/templates/ImportTrainingTemplate.xlsx');
  			$this->view->layout()->disableLayout();
         	$this->_helper->viewRenderer->setNoRender(true);
		}
	}

	/**
	* New training location
	*/
	public function locationAddAction() {
		require_once 'models/table/TrainingLocation.php';
		require_once 'models/table/Location.php';

		$request = $this->getRequest ();
		$validateOnly = $request->isXmlHttpRequest ();

		if ($validateOnly)
		$this->setNoRenderer ();

		if ($request->isPost ()) {

			$tableObj = new TrainingLocation ( );

			$location = $this->getParam ( 'training_location_name' );

			list ( $location_params, $location_tier, $location_id ) = $this->getLocationCriteriaValues ( array () );
			//validate
			$status = ValidationContainer::instance ();

			$districtText = $this->tr ( 'Region B (Health District)' );
			$provinceText = $this->tr ( 'Region A (Province)' );
			$localRegionText = $this->tr ( 'Region C (Local Region)' );
			$regionDText = $this->tr ( 'Region D' );
			$regionEText = $this->tr ( 'Region E' );
			$regionFText = $this->tr ( 'Region F' );
			$regionGText = $this->tr ( 'Region G' );
			$regionHText = $this->tr ( 'Region H' );
			$regionIText = $this->tr ( 'Region I' );

			$status->checkRequired ( $this, 'province_id', $provinceText );
			if ($this->setting ( 'display_region_b' )) $status->checkRequired ( $this, 'district_id', $districtText );
			if ($this->setting ( 'display_region_c' )) $status->checkRequired ( $this, 'region_c_id', $localRegionText );
			if ($this->setting ( 'display_region_d' )) $status->checkRequired ( $this, 'region_d_id', $regionDText );
			if ($this->setting ( 'display_region_e' )) $status->checkRequired ( $this, 'region_e_id', $regionEText );
			if ($this->setting ( 'display_region_f' )) $status->checkRequired ( $this, 'region_f_id', $regionFText );
			if ($this->setting ( 'display_region_g' )) $status->checkRequired ( $this, 'region_g_id', $regionGText );
			if ($this->setting ( 'display_region_h' )) $status->checkRequired ( $this, 'region_h_id', $regionHText );
			if ($this->setting ( 'display_region_i' )) $status->checkRequired ( $this, 'region_i_id', $regionIText );
			//$status->checkRequired ( $this, 'city', t ( "City is required." ) );

			$city_id = false;
			if ($this->getSanParam('city') && ! $this->getSanParam ( 'is_new_city' )) {
				$city_id = Location::verifyHierarchy ( $location_params ['city'], $location_params ['city_parent_id'], $this->setting ( 'num_location_tiers' ) );
				if ($city_id === false) {
					$status->addError ( 'city', t ( "That city does not appear to be located in the chosen region. If you want to create a new city, check the new city box." ) );
				}
			}
			// save
			if (! $status->hasError ()) {
				$location_id = null;
				if (($city_id === false) && $this->getSanParam ( 'is_new_city' )) {
					$location_id = Location::insertIfNotFound ( $location_params ['city'], $location_params ['city_parent_id'], $this->setting ( 'num_location_tiers' ) );
					if ($location_id === false)
					$status->addError ( 'city', t ( 'Could not save that city.' ) );
				} else {
					if ( $city_id ) {
						$location_id = $city_id;
					} else if ($this->setting ( 'display_region_i' )) {
						$location_id = $this->getSanParam('region_i_id');
					} else if ($this->setting ( 'display_region_h' )) {
						$location_id = $this->getSanParam('region_h_id');
					} else if ($this->setting ( 'display_region_g' )) {
						$location_id = $this->getSanParam('region_g_id');
					} else if ($this->setting ( 'display_region_f' )) {
						$location_id = $this->getSanParam('region_f_id');
					} else if ($this->setting ( 'display_region_e' )) {
						$location_id = $this->getSanParam('region_e_id');
					} else if ($this->setting ( 'display_region_d' )) {
						$location_id = $this->getSanParam('region_d_id');
					} else if ($this->setting ( 'display_region_c' )) {
						$location_id = $this->getSanParam('region_c_id');
					} else if ($this->setting ( 'display_region_b' )) {
						$location_id = $this->getSanParam('district_id' );
					} else {
						$location_id = $this->getSanParam('province_id' );
					}

					if ( strstr($location_id,'_') ) {
						$parts = explode('_',$location_id);
						$location_id = $parts[count($parts) - 1];
					}

				}

				if ($location_id) {
					// update or insert?
					if ($this->getParam ( 'update' )) {
						$data = array ();
						$data ['location_id'] = $location_id;
						$data ['training_location_name'] = $location;

						$tableObj = new TrainingLocation ( );
						$tableObj->update ( $data, "id = " . $this->getParam ( 'update' ) );

						$status->setStatusMessage ( t ( 'The' ).' '.t( 'Training Center' ).' '.t( 'has been updated.' ) );
						$_SESSION['status'] = t ( 'The' ).' '.t( 'Training Center' ).' '.t( 'has been updated.' );

						//refresh the page, so the picker dropdown is refreshed as well
						$status->setRedirect ( '/facility/view-location/id/' . $this->getParam ( 'update' ) );
					} else {
						$id = TrainingLocation::insertIfNotFound ( $location, $location_id );
						
						if ($this->getParam ( 'info' ) == 'extra') {
							$status->setStatusMessage ( t ( 'The' ).' '.t( 'Training Center' ).' '.t( 'has been saved.' ) );
							$_SESSION['status'] = t ( 'The' ).' '.t( 'Training Center' ).' '.t( 'has been saved.' );
							$status->setRedirect ( '/facility/view-location/id/' . $id );
						}
					}

					if ($this->getParam ( 'info' ) == 'extra') {
						$this->sendData ( $status );
					} else {
						$this->sendData ( array ('location_id' => $id ) );
					}
				}
			} else {
				$status->setStatusMessage ( t ( 'The' ).' '.t( 'Training Center' ).' '.t( 'could not be saved.' ) );
				$this->sendData ( $status );
			}

		}
	}

	private function _attach_locations($rowArray) {
		if ($rowArray) {
			$num_tiers = $this->setting ( 'num_location_tiers' );
			$locations = Location::getAll ();
			foreach ( $rowArray as $id => $row ) {
				$regions = Location::getCityInfo ( $row ['location_id'], $num_tiers );
				$regions = Location::cityInfotoHash($regions);
				$rowArray [$id] ['province_name'] = $locations [$regions['province_id']] ['name'];
				$rowArray [$id] ['district_name'] = isset( $locations[$regions['district_id']]['name'] ) ? $locations [$regions['district_id']] ['name'] : "";
				$rowArray [$id] ['region_c_name'] = isset( $locations[$regions['region_c_id']]['name'] ) ? $locations [$regions['region_c_id']] ['name'] : "";
				$rowArray [$id] ['region_d_name'] = isset( $locations[$regions['region_d_id']]['name'] ) ? $locations [$regions['region_d_id']] ['name'] : "";
				$rowArray [$id] ['region_e_name'] = isset( $locations[$regions['region_e_id']]['name'] ) ? $locations [$regions['region_e_id']] ['name'] : "";
				$rowArray [$id] ['region_f_name'] = isset( $locations[$regions['region_f_id']]['name'] ) ? $locations [$regions['region_f_id']] ['name'] : "";
				$rowArray [$id] ['region_g_name'] = isset( $locations[$regions['region_g_id']]['name'] ) ? $locations [$regions['region_g_id']] ['name'] : "";
				$rowArray [$id] ['region_h_name'] = isset( $locations[$regions['region_h_id']]['name'] ) ? $locations [$regions['region_h_id']] ['name'] : "";
				$rowArray [$id] ['region_i_name'] = isset( $locations[$regions['region_i_id']]['name'] ) ? $locations [$regions['region_i_id']] ['name'] : "";
			}
		}

		return $rowArray;
	}

	/**
	* autocomplete ajax (trainer)
	*/
	public function trainerLastListAction() {
		require_once ('models/table/Trainer.php');
		$rowArray = Trainer::suggestionList ( $this->getParam ( 'query' ), 100, $this->setting ( 'display_middle_name_last' ) );
		$rowArray = $this->_attach_locations ( $rowArray );
		$this->sendData ( $rowArray );
	}
	/**
	* autocomplete ajax (trainer)
	*/
	public function trainerMiddleListAction() {
		require_once ('models/table/Trainer.php');
		$rowArray = Trainer::suggestionListByMiddleName ( $this->getParam ( 'query' ), 100, $this->setting ( 'display_middle_name_last' ) );
		$rowArray = $this->_attach_locations ( $rowArray );
		$this->sendData ( $rowArray );
	}

	/**
	* autocomplete ajax (trainer)
	*/
	public function trainerFirstListAction() {
		require_once ('models/table/Trainer.php');
		$rowArray = Trainer::suggestionListByFirstName ( $this->getParam ( 'query' ), 100, $this->setting ( 'display_middle_name_last' ) );
		$rowArray = $this->_attach_locations ( $rowArray );
		$this->sendData ( $rowArray );
	}

	/**
	* autocomplete ajax (person)
	*/
	public function personLastListAction() {
		require_once ('models/table/Person.php');
		$rowArray = Person::suggestionList ( $this->getParam ( 'query' ), 100, $this->setting ( 'display_middle_name_last' ) );
		$rowArray = $this->_attach_locations ( $rowArray );
		$this->sendData ( $rowArray );
	}

	/**
	* autocomplete ajax (person)
	*/
	public function personMiddleListAction() {
		require_once ('models/table/Person.php');
		$rowArray = Person::suggestionListByMiddleName ( $this->getParam ( 'query' ), 100, $this->setting ( 'display_middle_name_last' ) );
		$rowArray = $this->_attach_locations ( $rowArray );
		$this->sendData ( $rowArray );
	}
	/**
	* autocomplete ajax (person)
	*/
	public function personFirstListAction() {
		require_once ('models/table/Person.php');
		$rowArray = Person::suggestionListByFirstName ( $this->getParam ( 'query' ), 100, $this->setting ( 'display_middle_name_last' ) );
		$rowArray = $this->_attach_locations ( $rowArray );
		$this->sendData ( $rowArray );
	}

	/**
	* autocomplete ajax (course name via people search)
	*/
	public function courseListAction() {
		require_once ('models/table/TrainingTitleOption.php');
		$rowArray = suggestionList::suggestionList ( $this->getParam ( 'query' ) );
		$this->sendData ( $rowArray );
	}

	/**
	* Edit test scores
	*/
	public function scoresAction() {
		require_once ('models/table/Person.php');
		require_once ('models/table/PersonToTraining.php');

		$pttObj = new PersonToTraining ( );
		$personTrainingRow = $pttObj->findOrCreate ( $this->getParam ( 'ptt_id' ) );

		$trainingObj = new Training ( );
		$personObj = new Person ( );

		$this->viewAssignEscaped ( 'courseName', $trainingObj->getCourseName ( $personTrainingRow->training_id ) );
		$this->viewAssignEscaped ( 'personRow', $personObj->getPersonName ( $personTrainingRow->person_id ) );
		$this->view->assign ( 'training_id', $personTrainingRow->training_id );

		require_once ('EditTableController.php');
		$editTable = new EditTableController ($this->getRequest(), $this->getResponse());
		$editTable->setParentController($this);
		$editTable->table = 'score';
		if ($this->setting('display_training_pt_pass') === '0') {
		    $editTable->fields = array ('score_label' => t ( 'Label' ), 'score_value' => t ( 'Score' )); // TODO: Label translations
		    $editTable->customColDef = array('score_value' => 'formatter:fickle');/*Todo rename this*/
		}else{
		  //TA:#271 add pass/fail options
		  $editTable->fields = array ('score_label' => t ( 'Label' ), 'score_value' => t ( 'Score' ), 'pass_fail'=>t('Pass/Fail')); // TODO: Label translations
		  $elements = array(array('text' => ' ', 'value' => ' '), array('text' => 'pass', 'value' => 'pass'), array('text' => 'fail', 'value' => 'fail'));
		  $elements = json_encode($elements);
		  $editTable->customColDef = array('score_value' => 'formatter:fickle', 'pass_fail' => "editor:'dropdown', editorOptions: {dropdownOptions: $elements }");/*Todo rename this*/
		}
		$editTable->label = t('Score'); //TA:66
		$editTable->where = "person_to_training_id = {$personTrainingRow->id}";
		$editTable->insertExtra = array ('person_to_training_id' => $personTrainingRow->id );
		//$editTable->customColDef = array('training_date' => 'formatter:YAHOO.widget.DataTable.formatDate, editor:"date"');
		//$editTable->customColDef = array('training_date' => 'width:120');
		$editTable->execute ($this->getRequest());
	}

	/**
	* Update test scores via Ajax
	*/
	public function scoresUpdateAction() {
		require_once ('models/table/Person.php');
		require_once ('models/table/PersonToTraining.php');

		$ptt_id = $this->getSanParam ( 'ptt_id' );
		$label = $this->getSanParam ( 'label' );
		$value = $this->getSanParam ( 'value' );

		Training::updateScore ( $ptt_id, $label, $value );
		$status = ValidationContainer::instance ();
		$this->sendData('');
	}

	public function scoresImportAction() {
		require_once ('models/table/Person.php');
		require_once ('models/table/PersonToTraining.php');

		//labels
		$id = $this->getSanParam ( 'training' );
		$db = Zend_Db_Table_Abstract::getDefaultAdapter ();
		$status = ValidationContainer::instance ();
		$trainingObj = new Training ( );
		$this->viewAssignEscaped ( 'courseName', $trainingObj->getCourseName ( $id ) );
		$this->view->assign ( 'training_id', $id );

		//CSV import -- post
		if(@$_FILES['import']['tmp_name']) {
			$filename = ($_FILES['import']['tmp_name']);
			if ( $filename ) {
				// we need a table to compare names to
				$table = new ITechTable(array('name' => 'score'));
				$persons = new ITechTable(array('name' => 'person'));
				$sql = 'select distinct person_to_training.id as pid,person.first_name,person.last_name from person_to_training
					   left join person on person.id = person_id
					   where person_to_training.training_id = '.$id;
				$ppl = $db->fetchAll($sql);

				while ($row = $this->_csv_get_row($filename) ) {
				    //TA:#213
			    //INFORCE user to create files only in UTF-8 encoded:
			    //Option 1: Excel:Save as Unicode Text -> Notepad: replace tabs with commas, save as csv UTF-8
			    //Option 2: OpenOffice
			    //It is not required for english, but absolutelly required for special characteristics.
			    //If files saved in UTF-8 encoded, so we do not need this line
			    // $row = array_map("utf8_encode", $row); 
					if ( is_array($row) ) {
						if ( isset($row[0]) && isset($row[4]) && !empty($row[0]) && !empty($row[4]) ) {

							// find person
							$row[0] = trim($row[0]);
							$row[1] = trim($row[1]);
							$pid = null;
							foreach($ppl as $v){
								if ($v['first_name'] == $row[0] && $v['last_name'] == $row[1]){
									$pid = $v['pid'];
									break;
								}

							}
							if($pid){
								$new_row = $table->createRow ( );
								$new_row->person_to_training_id = $pid;
								$new_row->training_date = $row[2];
								$new_row->score_label = $row[3];
								$new_row->score_value = $row[4];
								$new_row->save();
							}
							else { // err
								if (! isset($notfound))
									$notfound = array();
								if ($row[0] != t('First Name'))
									$notfound [] = $row[0].' '.$row[1].'<br>';
							}
						}
					}
				}
			}
			$_POST['redirect'] = null;
			if($notfound){
				$status->setStatusMessage( t('The following users could not be found while importing, perhaps they were not adding to the training:<br>') );
				foreach($notfound as $v)
					$status->setStatusMessage($v);
			}
		// done
		}

		// score view (edit table)
		require_once ('views/helpers/EditTableHelper.php');
		$label = 'Score';
		$fields = array ('name' => t('Name'), 'score_label' => t ( 'Label' ), 'score_value' => t ( 'Score' ) );
		$rowRay = $db->fetchAll("select score.*,CONCAT(person.first_name, CONCAT(' ', person.last_name)) as name from person_to_training
						inner join score on score.person_to_training_id = person_to_training.id
						left join person on person.id = person_id
						where person_to_training.training_id = $id
						");
		$this->view->assign('editTable', EditTableHelper::generateHtml($label, $rowRay, $fields, array(), array(), true));


	}

	public function scoresTemplateAction() {
		// gimme a csv template for training scores for this training
		$id = $this->getSanParam('training');
		$db = Zend_Db_Table_Abstract::getDefaultAdapter();
		$rowRay = $db->fetchAll("select person.first_name, person.last_name from person_to_training
						left join person on person.id = person_id
						where person_to_training.training_id = $id");

		$sorted = array();
		if ( count( $rowRay ) ){ // either print names attached to training or 3 generic ones
			foreach( $rowRay as $row ){

				$sorted[] = array(
				t('First Name')    => $row['first_name'],
				t('Last Name')     => $row['last_name'],
				t('Training Date') => '',
				t('Score Label')   => '',
				t('Score Value')   => '');
			}
		} else {
			$sorted = array (
				array (
					t('First Name')    => 'Charles',
					t('Last Name')     => 'Goo',
					t('Training Date') => '2012-05-30',
					t('Score Label')   => 'Pre-Test',
					t('Score Value')   => '99'),
				array (
					t('First Name')    => 'Charles',
					t('Last Name')     => 'Goo',
					t('Training Date') => '2012-05-30',
					t('Score Label')   => 'Post-Test',
					t('Score Value')   => '100'),
				array (
					t('First Name')    => 'Charles',
					t('Last Name')     => 'Goo',
					t('Training Date') => '2012-05-30',
					t('Score Label')   => 'Custom 1 (Extra Test)',
					t('Score Value')   => '100')
				);
		}
		//done, output a csv
		if( $this->getSanParam('outputType') == 'csv' )
			$this->sendData ( $this->reportHeaders ( false, $sorted ) );
	}

	public function indexAction() {
		return $this->_redirect ( 'training/search' );
	}

	public function searchAction() {
		$this->forward ( 'training-search', 'reports' );
	}

	public function deletedAction() {

	}

	/**
	* Training Roster
	*/
	public function rosterAction() {
		$training_id = $this->getParam ( 'id' );
		$this->view->assign ( 'url', Settings::$COUNTRY_BASE_URL . "/training/roster/id/$training_id" );

		$tableObj = new Training ( );

		$rowRay = $tableObj->getTrainingInfo ( $training_id );

		// calculate end date
		switch ($rowRay ['training_length_interval']) {
			case 'week' :
			$days = $rowRay ['training_length_value'] * 7;
			break;
			case 'day' :
			$days = $rowRay ['training_length_value'] - 1; // start day counts as a day?
			break;
			default :
			$days = false;
			break;
		}

		if ($days) {
			$rowRay ['training_end_date'] = strtotime ( "+$days day", strtotime ( $rowRay ['training_start_date'] ) );
			$rowRay ['training_end_date'] = date ( 'Y-m-d', $rowRay ['training_end_date'] );
		} else {
			$rowRay ['training_end_date'] = $rowRay ['training_start_date'];
		}

		$rowRay ['duration'] = $rowRay ['training_length_value'] . ' ' . $rowRay ['training_length_interval'] . (($rowRay ['training_length_value'] == 1) ? "" : "s");

		$this->viewAssignEscaped ( 'row', $rowRay );

		// trainer/person tables
		require_once 'views/helpers/EditTableHelper.php';

		/* Trainers */
		$trainers = TrainingToTrainer::getTrainers ( $training_id )->toArray ();

		$trainerFields = array ('last_name' => $this->tr ( 'Last Name' ), 'first_name' => $this->tr ( 'First Name' ), 'duration_days' => t ( 'Days' ) );
		$colStatic = array_keys ( $trainerFields ); // all
		$editLinkInfo = array ('disabled' => 1 ); // no edit/remove links
		$html = EditTableHelper::generateHtmlTraining ( 'Trainer', $trainers, $trainerFields, $colStatic, array (), $editLinkInfo );
		$this->view->assign ( 'tableTrainers', $html );

		/* Participants */
		$persons = PersonToTraining::getParticipants ( $training_id )->toArray ();
		$personsFields = array ('last_name' => $this->tr ( 'Last Name' ), 'first_name' => $this->tr ( 'First Name' ));

		if ( $this->setting('module_attendance_enabled') ) {
			if( strtotime( $rowRay ['training_start_date'] ) < time() ) {
			    //TA:106
				$personsFields = array_merge($personsFields, array ( 'duration_days' => t ( 'Duration' ) )); // already had class(es) - show the days attended
			}
			$personsFields['award_phrase']  = $this->tr ( 'Complete' );
		}
		$personsFields = array_merge($personsFields, array ('birthdate' => t ( 'Date of Birth' ), 'facility_name' => t ( 'Facility' )));

		if ( $this->setting('display_viewing_location') ) {
			$personsFields['location_phrase'] = $this->tr ( 'Viewing Location' );
		}
		if ( $this->setting('display_budget_code') ) {
			$personsFields['budget_code_phrase'] = $this->tr ( 'Budget Code' );
		}


		//if ($this->setting ( 'display_region_b' ))
		$personsFields ['location_name'] = t ( 'Location' );
		//add location
		$locations = Location::getAll ();
		foreach ( $persons as $pid => $person ) {
			$region_ids = Location::getCityInfo ( $person ['location_id'], $this->setting ( 'num_location_tiers' ) );
			$ordered_l = array( $region_ids['cityname'] );

			foreach ($region_ids as $key => $value) {
				if( !empty ($value) && isset($locations[$value]['name']))
					$ordered_l [] = $locations [$value] ['name'];
				else
					break;
			}
			$persons [$pid] ['location_name'] = implode ( ', ', $ordered_l );
		}

		$colStatic = array_keys ( $personsFields ); // all
		$editLinkInfo = array ('disabled' => 1 ); // no edit/remove links
		$html = EditTableHelper::generateHtmlTraining ( 'Persons', $persons, $personsFields, $colStatic, array (), $editLinkInfo );
		$this->view->assign ( 'tablePersons', $html );

		if ($this->getParam ( 'outputType' ) && $this->getParam ( 'trainers' ))
		$this->sendData ( $trainers );
		if ($this->getParam ( 'outputType' ) && $this->getParam ( 'persons' ))
		$this->sendData ( $persons );

	}

	public function topicListAction() {
		$rowArray = OptionList::suggestionList ( 'training_topic_option', 'training_topic_phrase', $this->getParam ( 'query' ) );
		$this->sendData ( $rowArray );
	}

	public function listByTrainerAction() {
		//training info
		$trainingObj = new Training ( );
		$rowArray = $trainingObj->findFromTrainer ( $this->getParam ( 'id' ) );
		$this->sendData ( $rowArray );
	}

	public function listByTrainingRecommendAction() {
		// recommended classes based on primary qualification
		require_once 'models/table/TrainingRecommend.php';
		$trainingRecObj = new TrainingRecommend ( );
		$rowArray = $trainingRecObj->getRecommendedClasses ( $this->getParam ( 'id' ) ); // qualification id
		$this->sendData ( $rowArray );
	}

	public function listByTrainingRecommendPersonAction() {
		// recommended classes based on person id
        // TODO: this looks unused BS 20150706
		require_once 'models/table/TrainingRecommend.php';
		$trainingRecObj = new TrainingRecommend ( );
		$rowArray = $trainingRecObj->getRecommendedClassesforPerson ( $this->getParam ( 'id' ) ); // person id
		$this->sendData ( $rowArray );
	}

	public function listByParticipantAction() {
		//organizer info
        // TODO: this looks unused BS 20150706
        $orgAccessListAllowed = allowed_org_access_full_list($this) . "," . allowed_organizer_in_this_site($this);
		if ($orgAccessListAllowed == ",")
			$orgAccessListAllowed = "";
		//class info
		$trainingObj = new Training ( );
		$rowArray = $trainingObj->findFromParticipant ( $this->getParam ( 'id' ) , $orgAccessListAllowed);
		$this->sendData ( $rowArray );
	}

	public function organizerListAction() {
		require_once ('models/table/OptionList.php');
		$rowArray = OptionList::suggestionList ( 'training_organizer_option', 'training_organizer_phrase', $this->getParam ( 'query' ) );
		$this->sendData ( $rowArray );
	}

	//ugly helper; TODO refactor
	private function _generate_hierarchical($id, $data, $phrase_col, $selected) {
		$output = '<select id="'.$id.'" name="person_qualification_option_id[]">';
		$output .= '<option value="">-- '.t("select").' --</option>';
		$lastParent = null;
		foreach ($data as $vals ) {
			if ( !$vals['id'] ) {
				$lastParent = ($vals['parent_phrase']);
				$output .='<option value="'.$vals['parent_id'].'" '.($selected == $vals['parent_id'] ?'selected="selected"':'').'>'.htmlspecialchars($vals['parent_phrase']).'</option>';
			} else {
				$output .= '<option value="'.$vals['id'].'" '.($selected == $vals['id'] ?'selected="selected"':'').'>&nbsp;&nbsp;'. htmlspecialchars($vals[$phrase_col]).'</option>';
			}
		}

		$output .= '</select>';
		return $output;

	}

	public function hasACLEdit($requireACL, $linkURL)
	{
		//$helpr = new Zend_View_Helper_HasACL ();
		//$helpr->setView($this);
		if(! ($this->hasACL($requireACL) || $this->hasACL('edit_country_options')))
			return null;

		return ' &nbsp; &nbsp; <a href="'.$linkURL.'" onclick="submitThenRedirect(\''.$linkURL.'\');return false;">'.t('Edit').'</a>';
	}
}
?>
