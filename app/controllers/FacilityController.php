<?php
/*
 * Created on Feb 27, 2008 Built for web Fuse IQ -- todd@fuseiq.com
 */
require_once ('ReportFilterHelpers.php');
require_once ('models/table/Facility.php');
require_once ('models/ValidationContainer.php');
require_once ('models/table/Location.php');
require_once ('models/table/OptionList.php');
class FacilityController extends ReportFilterHelpers {
	public function __construct(Zend_Controller_Request_Abstract $request, Zend_Controller_Response_Abstract $response, array $invokeArgs = array()) {
		parent::__construct ( $request, $response, $invokeArgs );
	}
	public function init() {
	}
	public function preDispatch() {
		parent::preDispatch ();
		
		if (! $this->isLoggedIn ())
			$this->doNoAccessError ();
			
			// we extend these controllers, lets redirect to their URL
		if (strstr ( $_SERVER ['HTTP_REFERER'], '/site/' ) && strstr ( $_SERVER ['REQUEST_URI'], '/facility' ))
			$this->_redirect ( str_replace ( '/facility/', '/site/', 'http://' . $_SERVER ['SERVER_NAME'] . $_SERVER ['REQUEST_URI'] ) );
	}
	public function indexAction() {
		$this->_redirect ( 'facility/search' );
	}
	public function cityListAction() {
		require_once ('models/table/Location.php');
		
		$rowArray = Location::suggestionQuery ( $this->_getParam ( 'query' ), $this->setting ( 'num_location_tiers' ) );
		
		$this->sendData ( $rowArray );
	}
	public function approveAction() {
		if (! $this->hasACL ( 'facility_and_person_approver' ) && ! $this->hasACL ( 'edit_country_options' )) {
			$this->doNoAccessError ();
		}
		
		$id = $this->getSanParam ( 'id' );
		$status = ValidationContainer::instance ();
		$facility = new Facility ();
		$facility_row = $facility->find ( $id )->current ();
		if ($facility_row == null) {
			$status->setStatusMessage ( t ( 'Error approving facility: That record could not be found.' ) );
			$this->_redirect ( 'admin/facilities-new-facilities' );
			return;
		}
		
		$facility_row->approved = 1;
		$facility_row->save ();
		$status->setStatusMessage ( t ( 'That facility has been approved.' ) );
		$this->_redirect ( 'admin/facilities-new-facilities' );
	}
	public function addAction() {
		if (! $this->hasACL ( 'edit_people' ) && ! $this->hasACL ( 'edit_facility' )) { // TODO: edit_people is legacy ACL
			$this->doNoAccessError ();
		}
		
		$request = $this->getRequest ();
		$validateOnly = $request->isXmlHttpRequest ();
		
		if ($validateOnly)
			$this->setNoRenderer ();
		
		if ($request->isPost ()) {
			
			$facilityObj = new Facility ();
			$obj_id = $this->validateAndSave ( $facilityObj->createRow (), false );
			
			// validate
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
		
		// locations
		$this->viewAssignEscaped ( 'locations', Location::getAll () );
		$this->viewAssignEscaped ( 'facility_types', OptionList::suggestionList ( 'facility_type_option', 'facility_type_phrase', false, false ) );
		$this->viewAssignEscaped ( 'facility_sponsors', OptionList::suggestionList ( 'facility_sponsor_option', 'facility_sponsor_phrase', false, false ) );
	}
	
	protected function validateAndSave($facilityRow, $checkName = true) {
		$status = ValidationContainer::instance ();
		
		// check for required fields
		if ($checkName) {
			$status->checkRequired ( $this, 'facility_name', 'Facility name' );
			// check for unique
			if ($this->_getParam ( 'facility_name' ) and ! Facility::isUnique ( $this->_getParam ( 'facility_name' ), $this->_getParam ( 'id' ) )) {
				$status->addError ( 'facility_name', t ( 'That name already exists.' ) );
			}
		}
		
		// validate fields
		//$status->checkRequired ( $this, 'facility_type_id', t ( 'Facility type' ) ); //TA:17: 09/03/2014
		$status->checkRequired ( $this, 'facility_province_id', $this->tr ( 'Region A (Province)' ) );
		if ($this->setting ( 'display_region_b' ))
			$status->checkRequired ( $this, 'facility_district_id', $this->tr ( 'Region B (Health District)' ) );
		if ($this->setting ( 'display_region_c' ))
			$status->checkRequired ( $this, 'facility_region_c_id', $this->tr ( 'Region C (Local Region)' ) );
		if ($this->setting ( 'display_region_d' ))
			$status->checkRequired ( $this, 'facility_region_d_id', $this->tr ( 'Region D' ) );
		if ($this->setting ( 'display_region_e' ))
			$status->checkRequired ( $this, 'facility_region_e_id', $this->tr ( 'Region E' ) );
		if ($this->setting ( 'display_region_f' ))
			$status->checkRequired ( $this, 'facility_region_f_id', $this->tr ( 'Region F' ) );
		if ($this->setting ( 'display_region_g' ))
			$status->checkRequired ( $this, 'facility_region_g_id', $this->tr ( 'Region G' ) );
		if ($this->setting ( 'display_region_h' ))
			$status->checkRequired ( $this, 'facility_region_h_id', $this->tr ( 'Region H' ) );
		if ($this->setting ( 'display_region_i' ))
			$status->checkRequired ( $this, 'facility_region_i_id', $this->tr ( 'Region I' ) );
			// $status->checkRequired ( $this, 'facility_city', t ( "City is required." ) );
			// validate lat & long
		require_once 'Zend/Validate/Float.php';
		require_once 'Zend/Validate/Between.php';
		$lat = $this->getSanParam ( 'facility_latitude' );
		$long = $this->getSanParam ( 'facility_longitude' );
		$validator = new Zend_Validate_Float ();
		$validbetween = new Zend_Validate_Between ( '-180', '180' );
		if ($lat && (! $validator->isValid ( $lat ) || ! $validbetween->isValid ( $lat ))) {
			$status->addError ( 'facility_latitude', t ( 'That latitude and longitude does not appear to be valid.' ) );
		}
		if ($long && (! $validator->isValid ( $long ) || ! $validbetween->isValid ( $long ))) {
			$status->addError ( 'facility_longitude', t ( 'That latitude and longitude does not appear to be valid.' ) );
		}
		
		// validate locations
		$city_id = false;
		$values = $this->_getAllParams ();
		require_once 'views/helpers/Location.php';
		$facility_city_parent_id = regionFiltersGetLastID ( 'facility', $values );
		
		if ($values ['facility_city'] && ! $values ['is_new_city']) {
			$city_id = Location::verifyHierarchy ( $values ['facility_city'], $facility_city_parent_id, $this->setting ( 'num_location_tiers' ) );
			if ($city_id === false) {
				$status->addError ( 'facility_city', t ( "That city does not appear to be located in the chosen region. If you want to create a new city, check the new city box." ) );
			}
		}
		
		// validate sponsors
		$sponsor_date_array = $this->getSanParam ( 'sponsor_start_date' ); // may or may not be array
		$sponsor_end_date_array = $this->getSanParam ( 'sponsor_end_date' );
		$sponsor_id = ($this->getSanParam ( 'facility_sponsor_id' ) ? $this->getSanParam ( 'facility_sponsor_id' ) : null);
		if (is_array ( $sponsor_id )) { // fallback, need at least one value to test if requiring sponsor dates
			$sponsor_array = $sponsor_id;
			$sponsor_id = $sponsor_id [0];
		}
		
		if (@$this->setting ( 'require_sponsor_dates' )) {
			if ($this->setting ( 'allow_multi_sponsors' )) { // and multiple sponsors option
				if (! is_array ( $this->getSanParam ( 'facility_sponsor_id' ) )) {
					$status->addError ( 'sponsor_end_date', t ( 'Sponsor dates are required.' ) . "\n" );
				}
				foreach ( $sponsor_array as $i => $val ) {
					if (empty ( $sponsor_date_array [$i] ) && ! empty ( $val ))
						$status->addError ( 'sponsor_start_date', t ( 'Sponsor dates are required.' ) . "\n" );
					if (empty ( $sponsor_end_date_array [$i] ) && ! empty ( $val ))
						$status->addError ( 'sponsor_end_date', t ( 'Sponsor dates are required.' ) . "\n" );
				} // todo case where multiple array and no_allow_multi
			}
		}
		
		// end validation
		if ($status->hasError ()) {
			$status->setStatusMessage ( t ( 'The facility could not be saved.' ) );
		} else {
			$location_id = null; // save location
			if (($city_id === false) && $values ['is_new_city']) {
				$location_id = Location::insertIfNotFound ( $values ['facility_city'], $facility_city_parent_id, $this->setting ( 'num_location_tiers' ) );
				if ($location_id === false)
					$status->addError ( 'facility_city', t ( 'Could not save that city.' ) );
			} else {
				if ($city_id) {
					$location_id = $city_id;
				} else {
					$location_id = regionFiltersGetLastID ( 'facility', $values );
				}
				
				if (strstr ( $location_id, '_' )) {
					$parts = explode ( '_', $location_id );
					$location_id = $parts [count ( $parts ) - 1];
				}
			}
			// save row
			if ($location_id) {
				// map db field names to FORM field names
				$facilityRow->facility_name = $this->getSanParam ( 'facility_name' );
				$facilityRow->location_id = $location_id;
				$facilityRow->type_option_id = ($this->getSanParam ( 'facility_type_id' ) ? $this->getSanParam ( 'facility_type_id' ) : null);
				$facilityRow->facility_comments = $this->_getParam ( 'facility_comments' );
				$facilityRow->address_1 = $this->getSanParam ( 'facility_address1' );
				$facilityRow->address_2 = $this->getSanParam ( 'facility_address2' );
				$facilityRow->lat = $lat;
				$facilityRow->long = $long;
				$facilityRow->postal_code = $this->getSanParam ( 'facility_postal_code' );
				$facilityRow->phone = $this->getSanParam ( 'facility_phone' );
				$facilityRow->fax = $this->getSanParam ( 'facility_fax' );
				$facilityRow->custom_1 = $this->getSanParam ( 'facility_custom1' );
				$facilityRow->sponsor_option_id = $sponsor_id;
				
				// dupecheck
				$dupe = new Facility ();
				$select = $dupe->select ()->where ( 'location_id =' . $facilityRow->location_id . ' and is_deleted = 0 and facility_name = "' . $facilityRow->facility_name . '"' );
				if (! $facilityRow->id && $dupe->fetchRow ( $select )) {
					$status->status = '';
					$status->setStatusMessage ( t ( 'The facility could not be saved. A facility with this name already exists in that location.' ) );
					return false;
				}
				
				$obj_id = $facilityRow->save ();
				$_SESSION ['status'] = t ( 'The facility was saved.' );
				if ($obj_id) {
					if ($this->setting ( 'display_facility_sponsor' ) && ! Facility::saveSponsors ( $obj_id, $sponsor_array, $sponsor_date_array, $sponsor_end_date_array )) {
						$status->setStatusMessage ( t ( 'There was an error saving sponsor data though.' ) );
						return false;
					}
					
					//TA:17: 09/08/2014
					$new_commodity_data = $this->_getParam ( 'commodity_new_data' );
					if($new_commodity_data){
						$data_to_add = json_decode($new_commodity_data, true);
						if (! Facility::saveCommodities ( $obj_id, $data_to_add['data'])) {
							$status->setStatusMessage ( t ( 'There was an error saving commodity data though.' ) );
							return false;
						}
					}
					$delete_commodity_data = $this->_getParam ( 'commodity_delete_data' );
					if($delete_commodity_data){	
						if (! Facility::deleteCommodities ( $delete_commodity_data)) {
							$status->setStatusMessage ( t ( 'There was an error saving commodity data though.' ) );
							return false;
						}
					}
					
					
					$status->setStatusMessage ( t ( 'The facility was saved.' ) );
					$status->setRedirect ( '/facility/view/id/' . $obj_id );
					return $obj_id;
				} else {
					unset ( $_SESSION ['status'] );
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
			$facility = new Facility ();
			$facilityRow = $facility->fetchRow ( 'id = ' . $id );
			if ($facilityRow) {
				$facilityArray = $facilityRow->toArray ();
			} else {
				$facilityArray = array ();
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
			// $rslt = $this->validateAndSave ( $facilityRow, (($this->getSanParam ( 'facility_name' ) != $facilityRow->facility_name) ? true : false) ); // checkName from _request, we dont need this anymore [bugfix/feature request]
			
			// validate
			$status = ValidationContainer::instance ();
			if ($validateOnly) {
				if ($rslt) {
					$status->setRedirect ( '/facility/view/id/' . $id );
				}
				$this->sendData ( $status );
			} else {
				$this->view->assign ( 'status', $status );
			}
		}
		
		// facilities list
		$rowArray = OptionList::suggestionList ( 'facility', array (
				'facility_name',
				'id' 
		), false, 9999 );
		$facilitiesArray = array ();
		foreach ( $rowArray as $key => $val ) {
			if ($val ['id'] != 0)
				$facilitiesArray [] = $val;
		}
		$this->viewAssignEscaped ( 'facilities', $facilitiesArray );
		
		// locations
		$locations = Location::getAll ();
		$this->viewAssignEscaped ( 'locations', $locations );
		require_once 'views/helpers/Location.php';
		$regions = Location::getCityInfo ( $facilityRow->location_id, $this->setting ( 'num_location_tiers' ) );
		$facilityArray ['facility_city'] = $regions [0];
		$regions = Location::regionsToHash ( $regions, 'facility' ); // (vals, prefix)
		$facilityArray = array_merge ( $facilityArray, $regions ); // stash hash values in there so we can set our parent tier ids
		                                                        
		// facility types
		$typesArray = OptionList::suggestionList ( 'facility_type_option', 'facility_type_phrase', false, false );
		$this->viewAssignEscaped ( 'facility_types', $typesArray );
		// sponsor types
		$sponsorsArray = OptionList::suggestionList ( 'facility_sponsor_option', 'facility_sponsor_phrase', false, false );
		$this->viewAssignEscaped ( 'facility_sponsors', $sponsorsArray );
		
		// see if it is referenced anywhere
		$this->view->assign ( 'okToDelete', ((! $id) or (! Facility::isReferenced ( $id ))) );
		
		$this->viewAssignEscaped ( 'facility', $facilityArray );
		
		// sponsors
		$sTable = new ITechTable ( array (
				'name' => 'facility_sponsors' 
		) );
		$select = $sTable->select ()->where ( 'is_deleted = 0 and facility_id = ' . $id );
		$sponsorRows = $sTable->fetchAll ( $select );
		$this->viewAssignEscaped ( 'sponsor_data', count ( $sponsorRows ) ? $sponsorRows->toArray () : array (
				array () 
		) ); // sponsor rows or an empty row for the template to work
		
		//TA:17: 09/04/2013 
		require_once('views/helpers/EditTableHelper.php');
		$db = Zend_Db_Table_Abstract::getDefaultAdapter();
		//$rows = $db->fetchAll ("SELECT id, name, DATE_FORMAT(date, '%m/%y') as date, consumption, stock_out FROM (SELECT *  from commodity where facility_id=". $id . " order by id desc) as temp group by name");
		// display the 2 most recent commodities based on name
// 		$rows = $db->fetchAll ("select temp3.id, commodity_name_option.commodity_name as name, DATE_FORMAT(date, '%m/%y') as date, consumption, stock_out, temp3.created_by, temp3.modified_by from " .
//  		"(SELECT * FROM (SELECT *  from commodity where facility_id=". $id . " order by id desc) as temp group by name " .
// 		"union " .
//  		"SELECT * FROM (SELECT *  from commodity where facility_id= ". $id . " and id not in " .
//  		"(SELECT id FROM (SELECT *  from commodity where facility_id=". $id . " order by id desc) as temp2 group by name) " .
//  		"order by id desc) as temp group by name) as temp3 INNER JOIN commodity_name_option
// 				ON commodity_name_option.id=temp3.name order by temp3.name");
		$rows = $db->fetchAll ("select temp3.id, commodity_name_option.commodity_name as name, DATE_FORMAT(date, '%m/%y') as date, consumption, stock_out, temp3.created_by, temp3.modified_by from " .
				"(SELECT * FROM (SELECT *  from commodity where facility_id=". $id . " order by id desc) as temp group by name_id " .
				"union " .
				"SELECT * FROM (SELECT *  from commodity where facility_id= ". $id . " and id not in " .
				"(SELECT id FROM (SELECT *  from commodity where facility_id=". $id . " order by id desc) as temp2 group by name_id) " .
				"order by id desc) as temp group by name_id) as temp3 INNER JOIN commodity_name_option
				ON commodity_name_option.id=temp3.name_id order by temp3.name_id");
		$noDelete = array();
		$customColDefs = array();
		foreach ($rows as $i => $row){ // lets add some data to the resultset to show in the EditTable
			if($row['created_by'] === '0' || $row['modified_by'] === '0'){
				$noDelete[] = $row['id'];  // add to nodelete array
			}
		}
		require_once('models/table/Translation.php');
		$translation = Translation::getAll();
 		$fieldDefs = array('name' => $translation['Facility Commodity Column Table Commodity Name'], 
 				'date' => $translation['Facility Commodity Column Table Date'] . " (MM/YY)", 
 				'consumption' => $translation['Facility Commodity Column Table Consumption'], 
 				'stock_out' => $translation['Facility Commodity Column Table Out of Stock'] . " (Y/N)");
// 		$customColDefs['consumption'] = "editor:'textbox'";
// 		$elements = array(array('text' => 'N', 'value' => 'N'), array('text' => 'Y', 'value' => 'Y'));
// 		$elements = json_encode($elements); // yui data table will enjoy spending time with a json encoded array
// 		$customColDefs['stock_out'] = "editor:'dropdown', editorOptions: {dropdownOptions: $elements }";
		//use "commodity" here, but in phtml use in javascript ITECH.commodityTable.addRow(...) and html <div id="commodityTable"></div>
		$html = EditTableHelper::generateHtml('commodity', $rows, $fieldDefs, $customColDefs, $noDelete, true);
		$this->view->assign ( 'tableCommodities', $html );
		$this->view->assign ( 'totalCommodities',  sizeof($rows));
		
		$this->view->assign('commodity_names',$facility->ListCommodityNames());
		//TA:17: 09/12/2013		
		
	}
	
	public function deleteAction() {
		if (! $this->hasACL ( 'edit_people' )) {
			$this->doNoAccessError ();
		}
		$status = ValidationContainer::instance ();
		$id = $this->getSanParam ( 'id' );
		
		if ($id and ! Facility::isReferenced ( $id )) {
			$fac = new Facility ();
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
		
		// validate
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
			$loc = new TrainingLocation ();
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
		
		// validate
		$this->view->assign ( 'status', $status );
	}
	public function searchLocationAction() {
		require_once ('models/table/OptionList.php');
		
		// location list
		$criteria = array ();
		list ( $criteria, $location_tier, $location_id ) = $this->getLocationCriteriaValues ( $criteria );
		$criteria ['training_location_name'] = $this->getSanParam ( 'training_location_name' );
		
		$criteria ['outputType'] = $this->getSanParam ( 'outputType' );
		
		$criteria ['go'] = $this->getSanParam ( 'go' );
		
		if ($criteria ['go']) {
			$db = Zend_Db_Table_Abstract::getDefaultAdapter ();
			
			$num_locs = $this->setting ( 'num_location_tiers' );
			list ( $field_name, $location_sub_query ) = Location::subquery ( $num_locs, $location_tier, $location_id, true );
			
			$sql = 'SELECT
								training_location.training_location_name,
								training_location.id , ' . implode ( ',', $field_name ) . '
							FROM training_location LEFT JOIN (' . $location_sub_query . ') as l ON training_location.location_id = l.id';
			
			$where = array (
					'training_location.is_deleted = 0' 
			);
			if ($criteria ['training_location_name']) {
				$where [] = " training_location_name='" . mysql_escape_string ( $criteria ['training_location_name'] ) . "'";
			}
			$locationWhere = $this->getLocationCriteriaWhereClause ( $criteria, '', '' );
			if ($locationWhere) {
				$where [] = $locationWhere;
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
		// location name
		$nameArray = OptionList::suggestionListValues ( 'training_location', 'training_location_name', false, false, false );
		$this->viewAssignEscaped ( 'location_names', $nameArray );
		// locations
		$this->viewAssignEscaped ( 'locations', Location::getAll () );
	}
	public function searchAction() {
		require_once ('models/table/OptionList.php');
		
		// facilities list
		$criteria = array ();
		list ( $criteria, $location_tier, $location_id ) = $this->getLocationCriteriaValues ( $criteria );
		$criteria ['facility_name'] = $this->getSanParam ( 'facility_name' );
		$criteria ['facility_name_text'] = $this->getSanParam ( 'facility_name_text' );
		$criteria ['type_id'] = $this->getSanParam ( 'type_id' );
		$criteria ['sponsor_id'] = $this->getSanParam ( 'sponsor_id' );
		$criteria ['outputType'] = $this->getSanParam ( 'outputType' );
		
		$criteria ['go'] = $this->getSanParam ( 'go' );
		
		if ($criteria ['go']) {
			$db = Zend_Db_Table_Abstract::getDefaultAdapter ();
			
			$num_locs = $this->setting ( 'num_location_tiers' );
			list ( $field_name, $location_sub_query ) = Location::subquery ( $num_locs, $location_tier, $location_id, true );

			
			// gnr: replaced facility_sponsor_option.facility_sponsor_phrase
			
			$sql = 'SELECT 
					facility.id,
					facility.facility_name,
					facility.location_id,
					facility.address_1,
					facility.address_2,
					facility.postal_code,
					facility.phone,
					facility.fax,
					facility.type_option_id,
	                facility_type_option.facility_type_phrase,
					facility.facility_comments,
										
					( SELECT group_concat(facility_sponsor_option.facility_sponsor_phrase 
  order by facility_sponsor_option.facility_sponsor_phrase asc separator ", ") as _list
  from facility inner_facility
  left outer join facility_sponsors 
  on inner_facility.id = facility_sponsors.facility_id
  left outer join facility_sponsor_option 
  on facility_sponsors.facility_sponsor_phrase_id = facility_sponsor_option.id
  left outer join facility_type_option 
  on inner_facility.type_option_id = facility_type_option.id 
  where  facility.is_deleted = 0  
  and inner_facility.id = facility.id ) as facility_sponsor_phrase , 
			    
                ' . implode ( ',', $field_name ) . '

              FROM facility LEFT JOIN (' . $location_sub_query . ') as l ON facility.location_id = l.id
              LEFT OUTER JOIN facility_type_option ON facility.type_option_id = facility_type_option.id
              LEFT JOIN facility_sponsors ON facility_sponsors.facility_id = facility.id
              LEFT OUTER JOIN facility_sponsor_option ON facility.sponsor_option_id = facility_sponsor_option.id OR facility_sponsors.facility_sponsor_phrase_id = facility_sponsor_option.id';

			$where = array ();
			$where [] = ' facility.is_deleted = 0 ';
			
			$locationWhere = $this->getLocationCriteriaWhereClause ( $criteria, '', '' );
			if ($locationWhere) {
				$where [] = $locationWhere;
			}
			
			if ($criteria ['type_id'] or $criteria ['type_id'] === '0') {
				$where [] = ' type_option_id = "' . $criteria ['type_id'] . '"';
			}
			
			if ($criteria ['sponsor_id'] or $criteria ['sponsor_id'] === '0') {
				$where [] = ' (sponsor_option_id = ' . $criteria ['sponsor_id'] . ' or facility_sponsors.facility_sponsor_phrase_id = ' . $criteria ['sponsor_id'] . ')'; // facility.sponsor_option_id is now deprecated, todo: remove it
			}
			
			if ($criteria ['facility_name']) {
				$where [] = " facility_name = '" . mysql_escape_string ( $criteria ['facility_name'] ) . "'";
			}
			
			if ($criteria ['facility_name_text']) {
				$where [] = " facility_name LIKE '%" . mysql_escape_string ( $criteria ['facility_name_text'] ) . "%'";
			}
			
			if ($where)
				$sql .= ' WHERE ' . implode ( ' AND ', $where );
			
			$sql .= " GROUP BY facility.id "; // bugfixes dual (depricated) column "sponsor_option_id" and linked lookup table "facility_sponsors", todo: OK to remove this when above TODO is fixed
			
			$sql .= " ORDER BY " . " facility_name ASC ";
			
			$rowArray = $db->fetchAll ( $sql );
			
			if ($criteria ['outputType']) {
				
				$this->sendData ( $rowArray );
			}
			
			$this->viewAssignEscaped ( 'results', $rowArray );
			$this->view->assign ( 'count', count ( $rowArray ) );
		}
		
		$this->view->assign ( 'criteria', $criteria );
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
	public function viewAction() {
		require_once ('models/table/OptionList.php');
		
		if ($id = $this->getSanParam ( 'id' )) {
			if ($this->hasACL ( 'edit_people' )) {
				// redirect to edit mode
				$this->_redirect ( str_replace ( 'view', 'edit', 'http://' . $_SERVER ['SERVER_NAME'] . $_SERVER ['REQUEST_URI'] ) );
			}
			
			$facility = new Facility ();
			$facilityRow = $facility->fetchRow ( 'id = ' . $id );
			$facilityArray = $facilityRow->toArray ();
		} else {
			$facilityArray = array ();
			$facilityArray ['id'] = null;
		}
		
		// facilities list
		$rowArray = OptionList::suggestionList ( 'facility', array (
				'facility_name',
				'id' 
		), false, 9999 );
		$facilitiesArray = array ();
		foreach ( $rowArray as $key => $val ) {
			if ($val ['id'] != 0)
				$facilitiesArray [] = $val;
		}
		$this->viewAssignEscaped ( 'facilities', $facilitiesArray );
		// locations
		$this->viewAssignEscaped ( 'locations', Location::getAll () );
		// facility types
		$typesArray = OptionList::suggestionList ( 'facility_type_option', 'facility_type_phrase', false, false );
		$this->viewAssignEscaped ( 'facility_types', $typesArray );
		// sponsor types
		$sponsorsArray = OptionList::suggestionList ( 'facility_sponsor_option', 'facility_sponsor_phrase', false, false );
		$this->viewAssignEscaped ( 'facility_sponsors', $sponsorsArray );
		// sponsors
		$sponsorsArray = OptionList::suggestionList ( 'facility_sponsors', 'facility_sponsor_phrase_id', false, false, true, 'id = ' . $id );
		$stable = new ITechTable ( array (
				'name' => 'facility_sponsors' 
		) );
		$select = $stable->select ()->where ( 'facility_id = ' . $id );
		$rows = $stable->fetchAll ( $select );
		if ($rows)
			$this->viewAssignEscaped ( 'sponsor_data', $rows->toArray () );
		
		$region_ids = Location::getCityInfo ( $facilityRow->location_id, $this->setting ( 'num_location_tiers' ) );
		$region_ids = Location::regionsToHash ( $region_ids );
		$facilityArray ['facility_city'] = $region_ids ['cityname'];
		$facilityArray = array_merge ( $facilityArray, $region_ids );
		
		$this->viewAssignEscaped ( 'facility', $facilityArray );
		
	}
	function addlocationAction() {
		require_once 'views/helpers/DropDown.php';
		
		// locations
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
			
			// locations
			$this->viewAssignEscaped ( 'locations', Location::getAll () );
			$region_ids = Location::getCityInfo ( $rowLocation ['location_id'], $this->setting ( 'num_location_tiers' ) );
			$rowLocation ['city_name'] = $region_ids [0];
			$region_ids = Location::regionsToHash ( $region_ids );
			$rowLocation = array_merge ( $rowLocation, $region_ids );
			
			$this->viewAssignEscaped ( 'rowLocation', $rowLocation );
			
			// see if it is referenced anywhere
			$this->view->assign ( 'okToDelete', (! TrainingLocation::isReferenced ( $this->_getParam ( 'id' ) )) );
		}
		
		// location drop-down
		$locations = TrainingLocation::selectAllLocations ( $this->setting ( 'num_location_tiers' ) );
		$this->viewAssignEscaped ( 'tlocations', $locations );
	}
	
	/**
	 * Import a facility
	 */
	public function importAction() {
		$this->view->assign ( 'pageTitle', t ( 'Import a facility' ) );
		require_once ('models/table/Location.php');
		require_once ('models/table/Facility.php');
		
		// template redirect
		if ($this->getSanParam ( 'download' ))
			return $this->importFacilityTemplateAction ();
		
		if (! $this->hasACL ( 'import_facility' ))
			$this->doNoAccessError ();
			
			// CSV STUFF
		$filename = ($_FILES ['upload'] ['tmp_name']);
		if ($filename) {
			$facilityObj = new Facility ();
			$errs = array ();
			while ( $row = $this->_csv_get_row ( $filename ) ) {
				$values = array ();
				if (! is_array ( $row ))
					continue; // sanity?
				if (! isset ( $cols )) { // set headers (field names)
					$cols = $row; // first row is headers (field names)
					continue;
				}
				$countValidFields = 0;
				if (! empty ( $row )) { // add
					foreach ( $row as $i => $v ) { // proccess each column
						if (empty ( $v ) && $v !== '0')
							continue;
						if ($v == 'n/a') // has to be able to process values from a data export
							$v = NULL;
						$countValidFields ++;
						$delimiter = strpos ( $v, ',' ); // is this field a comma seperated list too (or array)?
						if ($delimiter && $v [$delimiter - 1] != '\\') // handle arrays as field values(Export), and comma seperated values(import manual entry), and strings or int
							$values [$cols [$i]] = explode ( ',', $this->sanitize ( $v ) );
						else
							$values [$cols [$i]] = $this->sanitize ( $v );
					}
				}
				// done now all fields are named and in $values[my_field]
				if ($countValidFields) {
					// validate
					if (isset ( $values ['uuid'] )) {
						unset ( $values ['uuid'] );
					}
					if (isset ( $values ['id'] )) {
						unset ( $values ['id'] );
					}
					if (isset ( $values ['is_deleted'] )) {
						unset ( $values ['is_deleted'] );
					}
					if (isset ( $values ['created_by'] )) {
						unset ( $values ['created_by'] );
					}
					if (isset ( $values ['modified_by'] )) {
						unset ( $values ['modified_by'] );
					}
					if (isset ( $values ['timestamp_created'] )) {
						unset ( $values ['timestamp_created'] );
					}
					if (isset ( $values ['timestamp_updated'] )) {
						unset ( $values ['timestamp_updated'] );
					}
					if (! $this->hasACL ( 'approve_trainings' )) {
						unset ( $values ['approved'] );
					}
					if ($values ['sponsor_option_id']) {
						$sponsors = $this->_array_me ( $values ['sponsor_option_id'] ); // could be an array, we dont want one
						$values ['sponsor_option_id'] = $sponsors [0];
					}
					// required
					if (empty ( $values ['facility_name'] )) {
						$errs [] = t ( 'Error adding facility, Facility name cannot be empty.' );
						continue;
					}
					if (empty ( $values ['address_1'] )) {
						$values ['address_1'] = '';
					}
					if (empty ( $values ['address_2'] )) {
						$values ['address_2'] = '';
					}
					// locations
					$num_location_tiers = $this->setting ( 'num_location_tiers' );
					$bSuccess = true;
					$location_id = null;
					if ($values ['location_id'])
						$location_id = $values ['location_id'];
					$tier = 1;
					if (! $location_id) {
						for($i = 0; $i <= $num_location_tiers; $i ++) { // insert/find locations
							$r = 13 + $i; // first location field in csv row // could use this too: $values[t('Region A (Province)')]
							if (empty ( $row [$r] ) || $bSuccess == false)
								continue;
							$location_id = Location::insertIfNotFound ( $row [$r], $location_id, $tier );
							if (! $location_id) {
								$bSuccess = false;
								break;
							}
							$tier ++;
						}
					}
					if (! $bSuccess) {
						$errs [] = t ( 'Error locating/creating region or city:' ) . ' ' . $row [$r] . ' ' . t ( 'Facility' ) . ': ' . $values ['facility_name'];
						continue; // couldnt save location
					}
					$values ['location_id'] = $location_id;
					// dupecheck
					if ($location_id) {
						$dupe = new Facility ();
						$select = $dupe->select ()->where ( 'location_id =' . $location_id . ' and is_deleted = 0 and facility_name = "' . $values ['facility_name'] . '"' );
						if ($dupe->fetchRow ( $select )) {
							$errs [] = t ( 'The facility could not be saved. A facility with this name already exists in that location.' ) . ' ' . t ( 'Facility' ) . ': ' . $values ['facility_name'];
							$bSuccess = false;
						}
					} else {
						$location_id = null;
					}
					// save
					if ($bSuccess) {
						try {

							$tableObj = $facilityObj->createRow ();
							$tableObj = ITechController::fillFromArray ( $tableObj, $values );
							$tableObj->type_option_id = $this->_importHelperFindOrCreate ( 'facility_type_option', 'id', $tableObj->type_option_id );
							if ($values ['type_option_id'] && ! $tableObj->type_option_id) {
								$errs [] = t ( "Couldn't save facility type for facility:" ) . ' ' . $tableObj->facility_name;
							}
							$row_id = $tableObj->save ();
						} catch ( Exception $e ) {

							$errored = 1;
							$errs [] = nl2br ( $e->getMessage () ) . ' ' . t ( 'ERROR: The facility could not be saved.' );
						}
						if (! $row_id)
							$errored = 1;
							
							// save linked tables
						if ($row_id) {
							if ($sponsors || $values ['sponsor_start_date'] || $values ['sponsor_end_date']) {
								$dates1 = $this->_array_me ( $values ['sponsor_start_date'] );
								$dates2 = $this->_array_me ( $values ['sponsor_end_date'] );
								foreach ( $sponsors as $i => $phrase ) // insert or get id
									$sponsors [$i] = $this->_importHelperFindOrCreate ( 'facility_sponsor_option', 'facility_sponsor_phrase', $phrase ); // facility_type_option_id multiAssign (insert via helper)
								if (! Facility::saveSponsors ( $row_id, $sponsors, $dates1, $dates2 )) { // save
									$errs [] = t ( 'There was an error saving sponsor data though.' ) . ' ' . t ( 'Facility' ) . ': ' . $tableObj->facility_name;
								}
							}
						}
					}
					// sucess - done
				} // loop
			}
			// done processing rows
			
			$_POST ['redirect'] = null;
			$status = ValidationContainer::instance ();
			if (empty ( $errored ) && empty ( $errs ))
				$stat = t ( 'Your changes have been saved.' );
			else
				$stat = t ( 'Error importing data. Some data may have been imported and some may not have.' );
			
			foreach ( $errs as $errmsg )
				$stat .= '<br>' . 'Error: ' . htmlspecialchars ( $errmsg, ENT_QUOTES );
			
			$status->setStatusMessage ( $stat );
			$this->view->assign ( 'status', $status );
		}
		// done with import
	}
	
	/**
	 * A template for importing a Facility
	 */
	public function importFacilityTemplateAction() {
		// gimme a csv template for an example Facility
		$sorted = array (

				array (
						'id' => '',
						'facility_name' => '',
						'location_id' => '',
						'address_1' => '',
						'address_2' => '',
						'postal_code' => '',
						'phone' => '',
						'fax' => '',
						'type_option_id' => '',
						'facility_type_phrase' => '',
						'facility_comments' => '',
						'custom_1' => '',
						'sponsor_option_id' => '', 
				) 
		);
		// add some regions
		$num_location_tiers = $this->setting ( 'num_location_tiers' );
		$regionNames = array (
				'',
				t ( 'Region A (Province)' ),
				t ( 'Region B (Health District)' ),
				t ( 'Region C (Local Region)' ),
				t ( 'Region D' ),
				t ( 'Region E' ),
				t ( 'Region F' ),
				t ( 'Region G' ),
				t ( 'Region H' ),
				t ( 'Region I' ) 
		);
		for($i = 1; $i < $num_location_tiers; $i ++) {
			// add regions
			$sorted [0] [$regionNames [$i]] = '';
		}
		// add city region
		$sorted [0] [t ( 'City' )] = '';
		
		// done, output a csv
		if ($this->getSanParam ( 'outputType' ) == 'csv')

			$this->sendData ( $this->reportHeaders ( false, $sorted ) );
	}
	
	/**
	 * Import a training location
	 */
	public function importLocationAction() {
		$this->view->assign ( 'pageTitle', t ( 'Import a training location' ) );
		require_once ('models/table/Location.php');
		require_once ('models/table/TrainingLocation.php');
		
		// template redirect
		if ($this->getSanParam ( 'download' ))
			return $this->importLocationTemplateAction ();
		
		if (! $this->hasACL ( 'import_training_location' ))
			$this->doNoAccessError ();
			
			// CSV STUFF
		$filename = ($_FILES ['upload'] ['tmp_name']);
		if ($filename) {
			$trainingLocationObj = new TrainingLocation ();
			$errs = array ();
			while ( $row = $this->_csv_get_row ( $filename ) ) {
				$values = array ();
				if (! is_array ( $row ))
					continue; // sanity?
				if (! isset ( $cols )) { // set headers (field names)
					$cols = $row; // first row is headers (field names)
					continue;
				}
				$countValidFields = 0;
				if (! empty ( $row )) { // add
					foreach ( $row as $i => $v ) { // proccess each column
						if (empty ( $v ) && $v !== '0')
							continue;
						if ($v == 'n/a') // has to be able to process values from a data export
							$v = NULL;
						$countValidFields ++;
						$delimiter = strpos ( $v, ',' ); // is this field a comma seperated list too (or array)?
						if ($delimiter && $v [$delimiter - 1] != '\\') // handle arrays as field values(Export), and comma seperated values(import manual entry), and strings or int
							$values [$cols [$i]] = explode ( ',', $this->sanitize ( $v ) );
						else
							$values [$cols [$i]] = $this->sanitize ( $v );
					}
				}
				// done now all fields are named and in $values['my_field']
				if ($countValidFields) {
					// validate
					if (isset ( $values ['uuid'] )) {
						unset ( $values ['uuid'] );
					}
					if (isset ( $values ['id'] )) {
						unset ( $values ['id'] );
					}
					if (isset ( $values ['is_deleted'] )) {
						unset ( $values ['is_deleted'] );
					}
					if (isset ( $values ['created_by'] )) {
						unset ( $values ['created_by'] );
					}
					if (isset ( $values ['modified_by'] )) {
						unset ( $values ['modified_by'] );
					}
					if (isset ( $values ['timestamp_created'] )) {
						unset ( $values ['timestamp_created'] );
					}
					if (isset ( $values ['timestamp_updated'] )) {
						unset ( $values ['timestamp_updated'] );
					}
					// required
					if (empty ( $values ['training_location_name'] )) {
						$errs [] = t ( 'Error adding training location, training location name cannot be empty.' );
					}			
					// locations
					$num_location_tiers = $this->setting ( 'num_location_tiers' );
					$bSuccess = true;
					$location_id = null;
					if ($values ['location_id'])
						$location_id = $values ['location_id'];
					$tier = 1;
					if (! $location_id) {
						for($i = 0; $i <= $num_location_tiers; $i ++) { // insert/find locations
							$r = 1 + $i; // first location field in csv row // could use this too: $values[t('Region A (Province)')]
							if (empty ( $row [$r] ) || $bSuccess == false) 	
								continue;
							$location_id = Location::insertIfNotFound ( $row [$r], $location_id, $tier );
							if (! $location_id) {
								$bSuccess = false;
								break;
							}
							$tier ++;
						}
					}
					if (! $bSuccess || ! $location_id) {
						$errs [] = t ( 'Error locating/creating region or city:' ) . ' ' . $row [$r] . ' ' . t ( 'Training Location' ) . ': ' . $values ['training_location_name'];
						continue; // couldnt save location
					}
					$values ['location_id'] = $location_id;				
					// dupecheck
					$dupe = new TrainingLocation ();
					$select = $dupe->select ()->where ( 'location_id =' . $location_id . ' and is_deleted = 0 and training_location_name = "' . $values ['training_location_name'] . '"' );
					if ($dupe->fetchRow ( $select )) {
						$errs [] = t ( 'The training location could not be saved. A training location with this name already exists in that location.' ) . ' ' . t ( 'training location' ) . ': ' . $values ['training_location_name'];
						$bSuccess = false;
					}
					if (! $bSuccess)
						continue;
						// save
					try {
						$tableObj = $trainingLocationObj->createRow ();
						$tableObj->training_location_name = $values ['training_location_name'];
						$tableObj->location_id = $location_id;
						$row_id = $tableObj->save ();
					} catch ( Exception $e ) {
						$errored = 1;
						$errs [] = nl2br ( $e->getMessage () ) . ' ' . t ( 'ERROR: The training location could not be saved.' );
					}
					if (! $row_id)
						$errored = 1;
					// sucess - done
					
				} // loop
			}
			// done processing rows
			$_POST ['redirect'] = null;
			$status = ValidationContainer::instance ();
			if (empty ( $errored ) && empty ( $errs ))
				$stat = t ( 'Your changes have been saved.' );
			else
				$stat = t ( 'Error importing data. Some data may have been imported and some may not have.' );
			
			foreach ( $errs as $errmsg )
				$stat .= '<br>' . 'Error: ' . htmlspecialchars ( $errmsg, ENT_QUOTES );
			
			$status->setStatusMessage ( $stat );
			$this->view->assign ( 'status', $status );
		}
		// done with import
	}
	
	/**
	 * A template for importing a training_location
	 */
	public function importLocationTemplateAction() {
		// gimme a csv template for an example training location
		$sorted = array (
				array (
						'training_location_name' => 'Sample Location' 
				) 
		);
		// add on a few regions equal to the number the site is using... examples!
		$num_location_tiers = $this->setting ( 'num_location_tiers' );
		$regionNames = array (
				'',
				t ( 'Region A (Province)' ),
				t ( 'Region B (Health District)' ),
				t ( 'Region C (Local Region)' ),
				t ( 'Region D' ),
				t ( 'Region E' ),
				t ( 'Region F' ),
				t ( 'Region G' ),
				t ( 'Region H' ),
				t ( 'Region I' ) 
		);
		for($i = 1; $i < $num_location_tiers; $i ++) {
			$sorted [0] [$regionNames [$i]] = '';
		}
		// add city region
		$sorted [0] [t ( 'City' )] = t ( 'City' );
		// done, output a csv
		if ($this->getSanParam ( 'outputType' ) == 'csv')
			$this->sendData ( $this->reportHeaders ( false, $sorted ) );
	}
}
