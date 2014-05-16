<?php
/*
 * Created on Feb 27, 2008
 *
 *  Built for web
 *  Fuse IQ -- todd@fuseiq.com
 *
 */
require_once ('ReportFilterHelpers.php');
require_once ('models/table/Facility.php');
require_once ('models/ValidationContainer.php');
require_once ('models/table/Location.php');
require_once ('models/table/OptionList.php');

class PsfacilityController extends ReportFilterHelpers {
	
	public function __construct(Zend_Controller_Request_Abstract $request, Zend_Controller_Response_Abstract $response, array $invokeArgs = array()) {
		parent::__construct ( $request, $response, $invokeArgs );
	}
	
	public function init() {
	}
	
	public function preDispatch() {
		parent::preDispatch ();
		
		if (! $this->isLoggedIn ())
			$this->doNoAccessError ();
	
	}
	
	public function indexAction() {
		$this->_redirect ( 'psfacility/search' );
	}
	
	public function cityListAction() {
		require_once ('models/table/Location.php');
		
		$rowArray = Location::suggestionQuery ( $this->_getParam ( 'query' ), $this->setting ( 'num_location_tiers' ) );
		
		$this->sendData ( $rowArray );
	}
	
	public function addAction() {
		if (! $this->hasACL ( 'edit_people' )) {
			$this->doNoAccessError ();
		}
		
		$request = $this->getRequest ();
		$validateOnly = $request->isXmlHttpRequest ();
		
		if ($validateOnly)
			$this->setNoRenderer ();
		
		if ($request->isPost ()) {
			$facilityObj = new Facility ( );
			$obj_id = $this->validateAndSave ( $facilityObj->createRow (), false );
			
			//validate
			$status = ValidationContainer::instance ();
			if ($obj_id) {
				$status->setObjectId ( $obj_id );
			}
			if ($validateOnly) {
				$this->sendData ( $status );
			} else {
				$this->view->assign ( 'status', $status );
			}
		}
		
		//locations
		$this->viewAssignEscaped ( 'locations', Location::getAll () );
		
		//facility types
		$typesArray = OptionList::suggestionList ( 'facility_type_option', 'facility_type_phrase', false, false );
		$this->viewAssignEscaped ( 'facility_types', $typesArray );
		//sponsor types
		$sponsorsArray = OptionList::suggestionList ( 'facility_sponsor_option', 'facility_sponsor_phrase', false, false );
		$this->viewAssignEscaped ( 'facility_sponsors', $sponsorsArray );
	
	}
	
	protected function validateAndSave($facilityRow, $checkName = true) {
		$districtText = $this->tr ( 'Region B (Health District)' );
		$provinceText = $this->tr ( 'Region A (Province)' );
		$localRegionText = $this->tr ( 'Region C (Local Region)' );
		
		//validate
		$status = ValidationContainer::instance ();
		
		//check for required fields
		if ($checkName) {
			$status->checkRequired ( $this, 'facility_name', 'Facility name' );
			//check for unique
			if ($this->_getParam ( 'facility_name' ) and ! Facility::isUnique ( $this->_getParam ( 'facility_name' ), $this->_getParam ( 'id' ) )) {
				$status->addError ( 'facility_name', t ( 'That name already exists.' ) );
			}
		}
		// validate lat & long
		require_once 'Zend/Validate/Float.php';
		require_once 'Zend/Validate/Between.php';
		$lat = $this->getSanParam('facility_latitude');
		$long = $this->getSanParam('facility_longitude');
		$validator = new Zend_Validate_Float();
		$validbetween = new Zend_Validate_Between('-180','180');
		if ($lat && (!$validator->isValid($lat) || !$validbetween->isValid($lat))){
			$status->addError( 'facility_latitude', t('That latitude and longitude does not appear to be valid.') );
		}
		if ($long && (!$validator->isValid($long) || !$validbetween->isValid($long))){
			$status->addError( 'facility_longitude', t('That latitude and longitude does not appear to be valid.') );
		}
		
		$status->checkRequired ( $this, 'facility_type_id', t ( 'Facility type' ) );
		$status->checkRequired ( $this, 'facility_province_id', $provinceText );
		if ($this->setting ( 'display_region_b' ))
			$status->checkRequired ( $this, 'facility_district_id', $districtText );
		if ($this->setting ( 'display_region_c' ))
			$status->checkRequired ( $this, 'facility_region_c_id', $localRegionText );
		//$status->checkRequired ( $this, 'facility_city', t ( "City is required." ) );
		
		list ( $location_params, $facility_location_tier, $facility_location_id ) = $this->getLocationCriteriaValues ( array (), 'facility' );
		
		$city_id = false;
		if ( $this->getSanParam('facility_city') && ! $this->getSanParam ( 'is_new_city' )) {
			$city_id = Location::verifyHierarchy ( $location_params ['facility_city'], $location_params ['facility_city_parent_id'], $this->setting ( 'num_location_tiers' ) );
			if ($city_id === false) {
				$status->addError ( 'facility_city', t ( "That city does not appear to be located in the chosen region. If you want to create a new city, check the new city box." ) );
			}
		}
		
		$sponsor_date_array     = $this->getSanParam('sponsor_start_date');// may or may not be array
		$sponsor_end_date_array = $this->getSanParam('sponsor_end_date');
		$sponsor_id = ($this->getSanParam ( 'facility_sponsor_id' ) ? $this->getSanParam ( 'facility_sponsor_id' ) : null);
		if(is_array($sponsor_id)) {
			$sponsor_array = $sponsor_id;
			$sponsor_id = $sponsor_id[0];
		}
		// todo case where multip array and no_allow_multi
		if( @$this->setting('require_sponsor_dates') ) {
			$status->checkRequired ( $this, 'sponsor_option_id', t('Sponsor dates are required.')."\n" );
			if( $this->setting('allow_multi_sponsors') ){ // and multiple sponsors option
				if(! is_array($this->getSanParam('sponsor_option_id')) ) { 
					$status->addError('sponsor_end_date', t('Sponsor dates are required.')."\n" );
				}
				foreach($sponsor_array as $i => $val ){
					if(empty($sponsor_date_array[$i]) || !empty($val))
						$status->addError('sponsor_start_date', t('Sponsor dates are required.')."\n" );
					if(empty($sponsor_end_date_array[$i]) || !empty($val))
						$status->addError('sponsor_end_date', t('Sponsor dates are required.')."\n" );
				}
			}
		}

		// end validation
		if ($status->hasError ()) {
			$status->setStatusMessage ( t ( 'The facility could not be saved.' ) );
		} else {
			$location_id = null;
			if (($city_id === false) && $this->getSanParam ( 'is_new_city' )) {
				$location_id = Location::insertIfNotFound ( $location_params ['facility_city'], $location_params ['facility_city_parent_id'], $this->setting ( 'num_location_tiers' ) );
				if ($location_id === false)
					$status->addError ( 'facility_city', t ( 'Could not save that city.' ) );
			} else {
					if ( $city_id ) {
						$location_id = $city_id;
					} else if ($this->setting ( 'display_region_c' )) {
						$location_id = $this->getSanParam('facility_region_c_id');
					} else if ($this->setting ( 'display_region_b' )) {
						$location_id = $this->getSanParam('facility_district_id' );
					} else {
						$location_id = $this->getSanParam('facility_province_id' );
					}

					if ( strstr($location_id,'_') ) {
						$parts = explode('_',$location_id);
						$location_id = $parts[count($parts) - 1];
					}
			}
			// save row
			if ($location_id) {
				//map db field names to FORM field names
					$facilityRow->facility_name = $this->getSanParam ( 'facility_name' );
				$facilityRow->location_id = $location_id;
				$facilityRow->type_option_id = ($this->getSanParam ( 'facility_type_id' ) ? $this->getSanParam ( 'facility_type_id' ) : null);
				$facilityRow->facility_comments = $this->_getParam ( 'facility_comments' );
				$facilityRow->address_1 = $this->getSanParam ( 'facility_address1' );
				$facilityRow->address_2 = $this->getSanParam ( 'facility_address2' );
				$facilityRow->lat         = $lat;
				$facilityRow->long        = $long;
				$facilityRow->postal_code = $this->getSanParam ( 'facility_postal_code' );
				$facilityRow->phone = $this->getSanParam ( 'facility_phone' );
				$facilityRow->fax = $this->getSanParam ( 'facility_fax' );
				$facilityRow->sponsor_option_id = $sponsor_id;
				
				//dupecheck
				$dupe = new Facility();
				$select = $dupe->select()->where('location_id =' . $facilityRow->location_id . ' and facility_name = "' . $facilityRow->facility_name . '"');
				if(!$facilityRow->id && $dupe->fetchRow($select)) {
					$status->status = '';
					$status->setStatusMessage( t ( 'The facility could not be saved. A facility with this name already exists in that location.' ) );
					return false;
				}
				
				$obj_id = $facilityRow->save ();
				$_SESSION['status'] =  t ( 'The facility was saved.' );
				if ($obj_id) {
					if(! Facility::saveSponsors ( $obj_id, $sponsor_array, $sponsor_date_array, $sponsor_end_date_array )) {
						$status->setStatusMessage( t ( 'There was an error saving sponsor data though.' ) );
						return false;
					}
					$status->setStatusMessage ( t ( 'The facility was saved.' ) );
					$status->setRedirect ( '/facility/view/id/' . $obj_id );
					return $obj_id;
				} else {
					unset($_SESSION['status']);
					$status->setStatusMessage ( t ( 'ERROR: The facility could not be saved.' ) );
				}
			}
		
		}
		
		return false;
	}
	
	public function listAction() {
		require_once ('models/table/Facility.php');
		$rowArray = Facility::suggestionList ( $this->_getParam ( 'query' ) );
		
		$this->sendData ( $rowArray );
	}
	
	public function listwithunknownAction() {
		$this->listAction ();
	}
	
	public function editAction() {
		if (! $this->hasACL ( 'edit_people' )) {
			$this->doNoAccessError ();
		}
		
		require_once ('models/table/OptionList.php');
		require_once ('views/helpers/TrainingViewHelper.php');
		
		if ($id = $this->getSanParam ( 'id' )) {
			$facility = new Facility ( );
			$facilityRow = $facility->fetchRow ( 'id = ' . $id );
			if ($facilityRow) {
			$facilityArray = $facilityRow->toArray ();
		} else {
				$facilityArray = array();
				$facilityArray ['id'] = null;
			}
		} else {
			$facilityArray = array ();
			$facilityArray ['id'] = null;
		}
		
		$request = $this->getRequest ();
		$validateOnly = $request->isXmlHttpRequest ();
		
		if ($validateOnly)
			$this->setNoRenderer ();
		
		if ($request->isPost ()) {
			$rslt = $this->validateAndSave ( $facilityRow, false );
			//$rslt = $this->validateAndSave ( $facilityRow, (($this->getSanParam ( 'facility_name' ) != $facilityRow->facility_name) ? true : false) ); // checkName from _request, we dont need this anymore [bugfix/feature request]
			
			//validate
			$status = ValidationContainer::instance ();
			if ($validateOnly) {
				if ($rslt) {
					$status->setRedirect ( '/psfacility/view/id/' . $id );
				}
				$this->sendData ( $status );
			} else {
				$this->view->assign ( 'status', $status );
			}
		}
		
		//facilities list
		$rowArray = OptionList::suggestionList ( 'facility', array ('facility_name', 'id' ), false, 9999 );
		$facilitiesArray = array ();
		foreach ( $rowArray as $key => $val ) {
			if ($val ['id'] != 0)
				$facilitiesArray [] = $val;
		}
		$this->viewAssignEscaped ( 'facilities', $facilitiesArray );
		
		//locations
		$locations = Location::getAll ();
		$this->viewAssignEscaped ( 'locations', $locations );
		list ( $cname, $prov, $dist, $regc ) = Location::getCityInfo ( $facilityRow->location_id, $this->setting ( 'num_location_tiers' ) );
		$facilityArray ['facility_city'] = $cname;
		$facilityArray ['region_c_id'] = $regc;
		$facilityArray ['district_id'] = $dist;
		$facilityArray ['province_id'] = $prov;
		
		//facility types
		$typesArray = OptionList::suggestionList ( 'facility_type_option', 'facility_type_phrase', false, false );
		$this->viewAssignEscaped ( 'facility_types', $typesArray );
		//sponsor types
		$sponsorsArray = OptionList::suggestionList ( 'facility_sponsor_option', 'facility_sponsor_phrase', false, false );
		$this->viewAssignEscaped ( 'facility_sponsors', $sponsorsArray );
		
		//see if it is referenced anywhere
		$this->view->assign ( 'okToDelete', ((! $id) or (! Facility::isReferenced ( $id ))) );
		
		$this->viewAssignEscaped ( 'facility', $facilityArray );
	
		//sponsors
		$sTable      = new ITechTable ( array ('name' => 'facility_sponsors' ) );
		$select      = $sTable->select()->where( 'is_deleted = 0 and facility_id = '.$id );
		$sponsorRows = $sTable->fetchAll($select);
		$this->viewAssignEscaped ( 'sponsor_data', count($sponsorRows) ? $sponsorRows->toArray() : array( array()) ); // sponsor rows or an empty row for the template to work
	}
	
	public function deleteAction() {
		if (! $this->hasACL ( 'edit_people' )) {
			$this->doNoAccessError ();
		}
		$status = ValidationContainer::instance ();
		$id = $this->getSanParam ( 'id' );
		
		if ($id and ! Facility::isReferenced ( $id )) {
			$fac = new Facility ( );
			$rows = $fac->find ( $id );
			$row = $rows->current ();
			if ($row) {
				$fac->delete ( 'id = ' . $row->id );
			}
			$status->setStatusMessage ( t ( 'That facility was deleted.' ) );
		} else if (! $id) {
			$status->setStatusMessage ( t ( 'That facility could not be found.' ) );
		} else {
			$status->setStatusMessage ( t ( 'That facility is in use and could not be deleted.' ) );
		}
		
		//validate
		$this->view->assign ( 'status', $status );
	
	}
	
	public function deleteLocationAction() {
		if (! $this->hasACL ( 'edit_course' )) {
			$this->doNoAccessError ();
		}
		
		require_once 'models/table/TrainingLocation.php';
		$status = ValidationContainer::instance ();
		$id = $this->getSanParam ( 'id' );
		
		if ($id and ! TrainingLocation::isReferenced ( $id )) {
			$loc = new TrainingLocation ( );
			$rows = $loc->find ( $id );
			$row = $rows->current ();
			if ($row) {
				$loc->delete ( 'id = ' . $row->id );
			}
			$status->setStatusMessage ( t ( 'That location was deleted.' ) );
		} else if (! $id) {
			$status->setStatusMessage ( t ( 'That location could not be found.' ) );
		} else {
			$status->setStatusMessage ( t ( 'That location is in use and could not be deleted.' ) );
		}
		
		//validate
		$this->view->assign ( 'status', $status );
	
	}
	
	public function searchLocationAction() {
		
		require_once ('models/table/OptionList.php');
		
		//location list
		$criteria = array ();
		list ( $criteria, $location_tier, $location_id ) = $this->getLocationCriteriaValues ( $criteria );
		$criteria ['training_location_name'] = $this->getSanParam ( 'training_location_name' );
		
		$criteria ['outputType'] = $this->getSanParam ( 'outputType' );
		
		$criteria ['go'] = $this->getSanParam ( 'go' );
		
		if ($criteria ['go']) {
			$db = Zend_Db_Table_Abstract::getDefaultAdapter ();
			
			$num_locs = $this->setting ( 'num_location_tiers' );
			list ( $field_name, $location_sub_query ) = Location::subquery ( $num_locs, $location_tier, $location_id, true);
			
			$sql = 'SELECT 
								training_location.training_location_name,
								training_location.id , ' . implode ( ',', $field_name ) . '
							FROM training_location LEFT JOIN (' . $location_sub_query . ') as l ON training_location.location_id = l.id';
			
			$where = array ('training_location.is_deleted = 0' );
			$locationWhere = $this->getLocationCriteriaWhereClause($criteria, '', '');
			if ($locationWhere) {
				$where[] = $locationWhere;
			}
			if ($criteria ['training_location_name']) {
				$where [] = " training_location_name='" . mysql_escape_string ( $criteria ['training_location_name'] ) . "'";
			}
			
			if ($where)
				$sql .= ' WHERE ' . implode ( ' AND ', $where );
			
			$sql .= " ORDER BY training_location_name ASC ";
			
			$rowArray = $db->fetchAll ( $sql );
			
			if ($criteria ['outputType']) {
				
				$this->sendData ( $rowArray );
			}
			
			$this->viewAssignEscaped ( 'results', $rowArray );
			$this->view->assign ( 'count', count ( $rowArray ) );
		}
		
		$this->view->assign ( 'criteria', $criteria );
		//location name
		$nameArray = OptionList::suggestionListValues ( 'training_location', 'training_location_name', false, false, false );
		$this->viewAssignEscaped ( 'location_names', $nameArray );
		//locations
		$this->viewAssignEscaped ( 'locations', Location::getAll () );
	
	}
	
	public function searchAction() {
		
		require_once ('models/table/OptionList.php');
		
		//facilities list
		$criteria = array ();
		list ( $criteria, $location_tier, $location_id ) = $this->getLocationCriteriaValues ( $criteria );
		$criteria ['facility_name'] = $this->getSanParam ( 'facility_name' );
		
		$criteria ['type_id'] = $this->getSanParam ( 'type_id' );
		$criteria ['sponsor_id'] = $this->getSanParam ( 'sponsor_id' );
		$criteria ['outputType'] = $this->getSanParam ( 'outputType' );
		
		$criteria ['go'] = $this->getSanParam ( 'go' );
		
		if ($criteria ['go']) {
			$db = Zend_Db_Table_Abstract::getDefaultAdapter ();
			
			$num_locs = $this->setting ( 'num_location_tiers' );
			list ( $field_name, $location_sub_query ) = Location::subquery ( $num_locs, $location_tier, $location_id, true);
			
			$sql = 'SELECT facility_sponsor_option.facility_sponsor_phrase, facility.location_id,
                facility_type_option.facility_type_phrase,
                facility.facility_name,
                facility.id , ' . implode ( ',', $field_name ) . '
              FROM facility LEFT JOIN (' . $location_sub_query . ') as l ON facility.location_id = l.id
              LEFT OUTER JOIN facility_sponsor_option ON facility.sponsor_option_id = facility_sponsor_option.id
              LEFT OUTER JOIN facility_type_option ON facility.type_option_id = facility_type_option.id ';
			
			$where = array ();
			$where [] = ' facility.is_deleted = 0 ';

			$locationWhere = $this->getLocationCriteriaWhereClause($criteria, '', '');
			if ($locationWhere) {
				$where[] = $locationWhere;
			}
			
			if ($criteria ['type_id'] or $criteria ['type_id'] === '0') {
				$where [] = ' type_option_id = "' . $criteria ['type_id'] . '"';
			}
			
			if ($criteria ['sponsor_id'] or $criteria ['sponsor_id'] === '0') {
				$where [] = ' sponsor_option_id = ' . ($criteria ['sponsor_id']) . ' ';
			}
			
			if ($criteria ['facility_name']) {
				$where [] = " facility_name = '" . mysql_escape_string ( $criteria ['facility_name'] ) . "'";
			}
			
			if ($where)
				$sql .= ' WHERE ' . implode ( ' AND ', $where );
			
			$sql .= " ORDER BY " . " facility_name ASC ";
			
			$rowArray = $db->fetchAll ( $sql );
			
			if ($criteria ['outputType']) {
				
				$this->sendData ( $rowArray );
			}
			
			$this->viewAssignEscaped ( 'results', $rowArray );
			$this->view->assign ( 'count', count ( $rowArray ) );
		}
		
		$this->view->assign ( 'criteria', $criteria );
		//facility name
		$nameArray = OptionList::suggestionListValues ( 'facility', 'facility_name', false, false, false );
		$this->viewAssignEscaped ( 'facility_names', $nameArray );
		//locations
		$this->viewAssignEscaped ( 'locations', Location::getAll () );
		//facility types
		$typesArray = OptionList::suggestionList ( 'facility_type_option', 'facility_type_phrase', false, false );
		$this->viewAssignEscaped ( 'facility_types', $typesArray );
		//sponsor types
		$sponsorsArray = OptionList::suggestionList ( 'facility_sponsor_option', 'facility_sponsor_phrase', false, false );
		$this->viewAssignEscaped ( 'facility_sponsors', $sponsorsArray );
	
	}
	
	public function viewAction() {
		
		require_once ('models/table/OptionList.php');
		
		if ($id = $this->getSanParam ( 'id' )) {
			if ($this->hasACL ( 'edit_people' )) {
				//redirect to edit mode
				$this->_redirect ( str_replace ( 'view', 'edit', 'http://' . $_SERVER ['SERVER_NAME'] . $_SERVER ['REQUEST_URI'] ) );
			}
			
			$facility = new Facility ( );
			$facilityRow = $facility->fetchRow ( 'id = ' . $id );
			$facilityArray = $facilityRow->toArray ();
		} else {
			$facilityArray = array ();
			$facilityArray ['id'] = null;
		}
		
		//facilities list
		$rowArray = OptionList::suggestionList ( 'facility', array ('facility_name', 'id' ), false, 9999 );
		$facilitiesArray = array ();
		foreach ( $rowArray as $key => $val ) {
			if ($val ['id'] != 0)
				$facilitiesArray [] = $val;
		}
		$this->viewAssignEscaped ( 'facilities', $facilitiesArray );
		//locations
		$this->viewAssignEscaped ( 'locations', Location::getAll () );
		//facility types
		$typesArray = OptionList::suggestionList ( 'facility_type_option', 'facility_type_phrase', false, false );
		$this->viewAssignEscaped ( 'facility_types', $typesArray );
		//sponsor types
		$sponsorsArray = OptionList::suggestionList ( 'facility_sponsor_option', 'facility_sponsor_phrase', false, false );
		$this->viewAssignEscaped ( 'facility_sponsors', $sponsorsArray );
		//sponsors
		$sponsorsArray = OptionList::suggestionList ( 'facility_sponsors', 'facility_sponsor_phrase_id', false, false, true, 'id = '.$id );
		$stable = new ITechTable ( array ('name' => 'facility_sponsors' ) );
		$select = $stable->select()->where( 'facility_id = '.$id );
		$rows = $stable->fetchAll($select);
		if ($rows)
			$this->viewAssignEscaped ( 'sponsor_data', $rows->toArray() );
		
		list ( $cname, $prov, $dist, $regc ) = Location::getCityInfo ( $facilityRow->location_id, $this->setting ( 'num_location_tiers' ) );
		$facilityArray ['facility_city'] = $cname;
		$facilityArray ['region_c_id'] = $regc;
		$facilityArray ['district_id'] = $dist;
		$facilityArray ['province_id'] = $prov;
		
		$this->viewAssignEscaped ( 'facility', $facilityArray );
	
	}
	
	function addlocationAction() {
		require_once 'views/helpers/DropDown.php';
		
		//locations
		$this->viewAssignEscaped ( 'locations', Location::getAll () );
	
	}
	
	function viewlocationAction() {
		if (! $this->hasACL ( 'edit_course' )) {
			$this->view->assign ( 'viewonly', 'disabled="disabled"' );
		}
		
		require_once 'models/table/TrainingLocation.php';
		
		$this->view->assign ( 'id', $this->_getParam ( 'id' ) );
		
		if ($this->_getParam ( 'id' )) {
			require_once 'views/helpers/DropDown.php';
			
			$rowLocation = TrainingLocation::selectLocation ( $this->_getParam ( 'id' ) )->toArray ();
			
			//locations
			list ( $cname, $prov, $dist, $regc ) = Location::getCityInfo ( $rowLocation ['location_id'], $this->setting ( 'num_location_tiers' ) );
			$rowLocation ['city_name'] = $cname;
			$rowLocation ['region_c_id'] = $regc;
			$rowLocation ['district_id'] = $dist;
			$rowLocation ['province_id'] = $prov;
			
			$this->viewAssignEscaped ( 'rowLocation', $rowLocation );
			
			//see if it is referenced anywhere
			$this->view->assign ( 'okToDelete', (! TrainingLocation::isReferenced ( $this->_getParam ( 'id' ) )) );
		
		}
		
		// location drop-down
		$locations = TrainingLocation::selectAllLocations ( $this->setting ( 'num_location_tiers' ) );
		$this->viewAssignEscaped ( 'tlocations', $locations );
	
	}

}
