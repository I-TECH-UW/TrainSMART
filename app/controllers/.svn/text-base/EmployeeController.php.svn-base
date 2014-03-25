<?php
require_once ('ReportFilterHelpers.php');
require_once ('FacilityController.php');
require_once ('models/table/Person.php');
require_once ('models/table/Employee.php');
require_once ('models/table/Facility.php');
require_once ('models/table/OptionList.php');
require_once ('models/table/MultiOptionList.php');
require_once ('models/table/Location.php');
require_once ('models/table/MultiAssignList.php');
require_once ('views/helpers/FormHelper.php');
require_once ('views/helpers/DropDown.php');
require_once ('views/helpers/Location.php');
require_once ('views/helpers/CheckBoxes.php');
require_once ('views/helpers/TrainingViewHelper.php');
require_once ('models/table/Helper.php');

class EmployeeController extends ReportFilterHelpers {

	public function init() {	}

	public function preDispatch() {
		parent::preDispatch ();

		if (! $this->isLoggedIn ())
			$this->doNoAccessError ();

		if (! $this->setting('module_employee_enabled')){
			$_SESSION['status'] = t('The employee module is not enabled on this site.');
			$this->_redirect('select/select');
		}

		if (! $this->hasACL ( 'edit_employee' )) {
			$this->doNoAccessError ();
		}
	}

	public function indexAction() {

		if (! $this->hasACL ( 'edit_employee' )) {
			$this->doNoAccessError ();
		}

		require_once('models/table/dash-employee.php');
		$this->view->assign('title', $this->translation['Application Name'].space.t('Employee Tracking System'));

		// restricted access?? does this user only have acl to view some trainings or people
		// they dont want this, removing 5/01/13
		$org_allowed_ids = allowed_org_access_full_list($this); // doesnt have acl 'training_organizer_option_all'?
		$allowedWhereClause = $org_allowed_ids ? " partner.organizer_option_id in ($org_allowed_ids) " : "";
		// restricted access?? only show organizers that belong to this site if its a multi org site
		$site_orgs = allowed_organizer_in_this_site($this); // for sites to host multiple training organizers on one domain
		$allowedWhereClause .= $site_orgs ? "  partner.organizer_option_id in ($site_orgs) " : "";

		$partners = new DashviewEmployee();
		$details = $partners->fetchdetails($allowedWhereClause);
		$this->view->assign('getins',$details);

		/****************************************************************************************************************/
		/* Attached Files */
		require_once('views/helpers/FileUpload.php');

		$PARENT_COMPONENT = 'employee';

		FileUpload::displayFiles ( $this, $PARENT_COMPONENT, 1, $this->hasACL ( 'admin_files' ) );
		// File upload form
		if ( $this->hasACL ( 'admin_files' ) ) {
			$this->view->assign ( 'filesForm', FileUpload::displayUploadForm ( $PARENT_COMPONENT, 1, FileUpload::$FILETYPES ) );
		}
		/****************************************************************************************************************/
	}

	/**
	 * AJAX course add/delete/edit ... for employee_edit
	 *
	 * see: employee_course_table.phtml
	 */
	public function coursesAction()
	{
		try {
			if (! $this->hasACL ( 'edit_employee' )) {
				if($this->_getParam('outputType') == 'json') {
					$this->sendData(array('msg'=>'Not Authorized'));
					exit();
					return;
				}
				$this->doNoAccessError ();
			}

			$ret    = array();
			$params = $this->getAllParams();

			if ($params['mode'] == 'addedit') {
				// add or update a record based on $params[id]
				if( empty($params['id']) )
					unset( $params['id'] ); // unset ID (primary key) for Zend if 0 or '' (insert new record)
				$id = $this->_findOrCreateSaveGeneric('employee_to_course', $params); // wrapper for find or create
				$params['id'] = $id;
				if($id){
					// saved
					// reload the data
					$db = $this->dbfunc();
					$ret = $db->fetchRow("select * from employee_to_course where id = $id");
					$ret['msg'] = 'ok';
				} else {
					$ret['errored'] = true;
					$ret['msg']     = t('Error creating record.');
				}
			}
			else if($params['mode'] == 'delete' && $params['id']) {
				// delete a record
				try {
					$course_link_table = new ITechTable ( array ('name' => 'employee_to_course' ) );
					$num_rows = $course_link_table->delete('id = ' . $params['id']);
					if (! $num_rows )
						$ret['msg'] = t('Error finding that record in the database.');
					$ret['num_rows'] = $num_rows;
				} catch (Exception $e) {
					$ret['errored'] = true;
					$ret['msg']     = t('Error finding that record in the database.');
				}
			}

			if(strtolower($params['outputType']) == 'json'){
				$this->sendData($ret); // probably always json no need to check for output
			}

		}
		catch (Exception $e) {
			if($this->_getParam('outputType') == 'json') {
				$this->sendData(array('errored' => true, 'msg'=>'Error: ' . $e->getMessage()));
				return;
			} else {
				echo $e->getMessage();
			}
		}
	}

	private function getCourses($employee_id){
		if (!$employee_id)
			return;

		$db = $this->dbfunc();
		$sql = "SELECT * FROM employee_to_course WHERE employee_id = $employee_id";
		$rows = $db->fetchAll($sql);
		return $rows ? $rows : array();
	}

	public function addAction() {
		$this->view->assign('mode', 'add');
		$this->view->assign ( 'pageTitle', t ( 'Add New' ).' '.t( 'Employee' ) );
		return $this->editAction ();
	}

	public function deleteAction() {
		if (! $this->hasACL ( 'edit_employee' )) {
			$this->doNoAccessError ();
		}

		require_once('models/table/Employee.php');
		$status = ValidationContainer::instance ();
		$id = $this->getSanParam ( 'id' );

		if ($id) {
			$employee = new Employee ( );
			$rows = $employee->find ( $id );
			$row = $rows->current ();
			if ($row) {
				$employee->delete ( 'id = ' . $row->id );
			}
			$status->setStatusMessage ( t ( 'That employee was deleted.' ) );
		} else {
			$status->setStatusMessage ( t ( 'That employee could not be found.' ) );
		}

		//validate
		$this->view->assign ( 'status', $status );

	}

	public function editAction() {
		if (! $this->hasACL ( 'edit_employee' )) {
			$this->doNoAccessError ();
		}

		$db = $this->dbfunc();
		$status = ValidationContainer::instance ();
		$params = $this->getAllParams();
		$id = $params['id'];

		// restricted access?? only show partners by organizers that we have the ACL to view
		$org_allowed_ids = allowed_org_access_full_list($this);               // doesnt have acl 'training_organizer_option_all'
		if ($org_allowed_ids && $this->view->mode != 'add' && $id != "") {    // doesnt have acl & is edit mode.
			if ($partnerID = $db->fetchOne("SELECT partner_id FROM employee WHERE employee.id = ?", $id)) {
				$validID = $db->fetchCol("SELECT partner.id FROM partner WHERE partner.id = ? AND partner.organizer_option_id in ($org_allowed_ids)", $partnerID); // check for both
				if(empty($validID))
					$this->doNoAccessError ();
			}
		}

		if ( $this->getRequest()->isPost() )
		{
			//validate then save
			$params['location_id'] = regionFiltersGetLastID( '', $params );
			$params['dob']                      = $this->_euro_date_to_sql( $params['dob'] );
			$params['agreement_end_date']       = $this->_euro_date_to_sql( $params['agreement_end_date'] );
			$params['transition_date']          = $this->_euro_date_to_sql( $params['transition_date'] );
			$params['transition_complete_date'] = $this->_euro_date_to_sql( $params['transition_complete_date'] );
			$params['site_id']                  = $params['facilityInput'];
			$params['option_nationality_id']    = $params['lookup_nationalities_id'];
			$params['facility_type_option_id']  = $params['employee_site_type_option_id'];
			$params['race_option_id']           = $params['person_race_option_id'];

			$status->checkRequired ( $this, 'first_name', t ( 'Frist Name' ) );
			$status->checkRequired ( $this, 'last_name',  t ( 'Last Name' ) );
			$status->checkRequired ( $this, 'dob',        t ( 'Date of Birth' ) );
			if($this->setting('display_employee_nationality'))
				$status->checkRequired ( $this, 'lookup_nationalities_id', t ( 'Employee Nationality' ) );
			$status->checkRequired ( $this, 'employee_qualification_option_id', t ( 'Staff Cadre' ) );
			if($this->setting('display_gender'))
				$status->checkRequired ( $this, 'gender', t('Gender') );
			if($this->setting('display_employee_race'))
				$status->checkRequired ( $this, 'person_race_option_id', t('Race') );
			if($this->setting('display_employee_disability')) {
				$status->checkRequired ( $this, 'disability_option_id', t('Disability') );
				if ($params['disability_option_id'] == 1)
					$status->checkRequired ( $this, 'disability_comments', t('Nature of Disability') );
			}
			if($this->setting('display_employee_salary'))
				$status->checkRequired ( $this, 'salary', t('Salary') );
			if($this->setting('display_employee_benefits'))
				$status->checkRequired ( $this, 'benefits', t('Benefits') );
			if($this->setting('display_employee_additional_expenses'))
				$status->checkRequired ( $this, 'additional_expenses', t('Additional Expenses') );
			if($this->setting('display_employee_stipend'))
				$status->checkRequired ( $this, 'stipend', t('Stipend') );
			if ( $this->setting('display_employee_partner') )
				$status->checkRequired ( $this, 'partner_id', t ( 'Partner' ) );
			if($this->setting('display_employee_sub_partner'))
				$status->checkRequired ( $this, 'subpartner_id', t ( 'Sub Partner' ) );
			if($this->setting('display_employee_intended_transition'))
				$status->checkRequired ( $this, 'employee_transition_option_id', t ( 'Intended Transition' ) );
			if(($this->setting('display_employee_base') && !$params['employee_base_option_id']) || !$this->setting('display_employee_base')) // either one is OK, javascript disables regions if base is on & has a value choice
				$status->checkRequired ( $this, 'province_id', t ( 'Region A (Province)' ).space.t('or').space.t('Employee Based at') );
			if($this->setting('display_employee_base') && !$params['province_id'])
				$status->checkRequired ( $this, 'employee_base_option_id', t ( 'Employee Based at' ).space.t('or').space.t('Region A (Province)') );
			if($this->setting('display_employee_primary_role'))
				$status->checkRequired ( $this, 'employee_role_option_id', t ( 'Primary Role' ) );

			$status->checkRequired ( $this, 'funded_hours_per_week', t ( 'Funded hours per week' ) );
			if($this->setting['display_employee_contract_end_date'])
				$status->checkRequired ( $this, 'agreement_end_date', t ( 'Contract End Date' ) );

			// set partner specific unique employee number. (auto-increment ID for each employee, starting at 1, per-partner)
			if($id) { // reset if change partner_id
				$oldPartnerId = $db->fetchOne("SELECT partner_id FROM employee WHERE id = ?", $id);
				if ($params['partner_id'] != $oldPartnerId || $params['partner_id'] == "")
					$params['partner_employee_number'] = null;
			}
			if ($params['partner_id'] && $params['partner_employee_number'] == "") { // generate a new id
				$max = $db->fetchOne("SELECT MAX(partner_employee_number) FROM employee WHERE partner_id = ?", $params['partner_id']);
				$params['partner_employee_number'] = $max ? $max + 1 : 1; // max+1 or default to 1
			}

			// save
			if (! $status->hasError() ) {
				$id = $this->_findOrCreateSaveGeneric('employee', $params);
				if(!$id) {
					$status->setStatusMessage( t('That person could not be saved.') );
				} else {
					# converted to optionlist, link table not needed TODO. marking for removal.
					#MultiOptionList::updateOptions ( 'employee_to_role', 'employee_role_option', 'employee_id', $id, 'employee_role_option_id', $params['employee_role_option_id'] );
					
					$status->setStatusMessage( t('The person was saved.') );
					$this->_redirect("employee/edit/id/$id");
				}
			} else {
				$status->setStatusMessage( t('That person could not be saved.') );
			}
		}

		if ( $id && !$status->hasError() ) { // read data from db

			$sql = 'SELECT * FROM employee WHERE employee.id = '.$id;
			$row = $db->fetchRow( $sql );
			if ($row)
				$params = $row; // reassign form data
			else
				$status->setStatusMessage ( t('Error finding that record in the database.') );

			$region_ids = Location::getCityInfo($params['location_id'], $this->setting('num_location_tiers'));
			$region_ids = Location::regionsToHash($region_ids);
			$params = array_merge($params, $region_ids);
			#$params['roles'] = $db->fetchCol("SELECT employee_role_option_id FROM employee_to_role WHERE employee_id = $id");
		}

		// assign form drop downs
		$params['dob']                          = formhelperdate($params['dob']);
		$params['agreement_end_date']           = formhelperdate($params['agreement_end_date']);
		$params['transition_date']              = formhelperdate($params['transition_date']);
		$params['transition_complete_date']     = formhelperdate($params['transition_complete_date']);
		$params['courses']                      = $this->getCourses($id);
		$params['lookup_nationalities_id']      = $params['option_nationality_id'];
		$params['employee_site_type_option_id'] = $params['facility_type_option_id'];
		$params['person_race_option_id']        = $params['race_option_id'];
		$this->viewAssignEscaped ( 'employee', $params );
		$validCHWids = $db->fetchCol("select id from employee_qualification_option qual
										inner join (select id as success from employee_qualification_option where qualification_phrase in ('Community Based Worker','Community Health Worker','NC02 -Community health workers')) parentIDs
										on (parentIDs.success = qual.id)");
		$this->view->assign('validCHWids', $validCHWids);
		$this->view->assign('expandCHWFields', !(array_search($params['employee_qualification_option_id'],$validCHWids) === false)); // i.e $validCHWids.contains($employee[qualification])
		$this->view->assign('status', $status);
		$this->view->assign ( 'pageTitle', $this->view->mode == 'add' ? t ( 'Add Employee' ) : t( 'Edit Employee' ) );
		$this->viewAssignEscaped ( 'locations', Location::getAll () );
		$titlesArray = OptionList::suggestionList ( 'person_title_option', 'title_phrase', false, 9999);
		$this->view->assign ( 'titles',      DropDown::render('title_option_id', $this->translation['Title'], $titlesArray, 'title_phrase', 'id', $params['title_option_id'] ) );
		$this->view->assign ( 'partners',    DropDown::generateHtml   ( 'partner', 'partner', $params['partner_id'], false, $this->view->viewonly, false ) );
		$this->view->assign ( 'subpartners', DropDown::generateHtml   ( 'partner', 'partner', $params['subpartner_id'], false, $this->view->viewonly, false, false, array('name' => 'subpartner_id'), true ) );
		$this->view->assign ( 'bases',       DropDown::generateHtml   ( 'employee_base_option', 'base_phrase', $params['employee_base_option_id']) );
		$this->view->assign ( 'site_types',  DropDown::generateHtml   ( 'employee_site_type_option', 'site_type_phrase', $params['facility_type_option_id']) );
		$this->view->assign ( 'cadres',      DropDown::generateHtml   ( 'employee_qualification_option', 'qualification_phrase', $params['employee_qualification_option_id']) );
		$this->view->assign ( 'categories',  DropDown::generateHtml   ( 'employee_category_option', 'category_phrase', $params['employee_category_option_id'], false, $this->view->viewonly, false ) );
		$this->view->assign ( 'fulltime',    DropDown::generateHtml   ( 'employee_fulltime_option', 'fulltime_phrase', $params['employee_fulltime_option_id'], false, $this->view->viewonly, false ) );
		$this->view->assign ( 'roles',       DropDown::generateHtml   ( 'employee_role_option', 'role_phrase', $params['employee_role_option_id'], false, $this->view->viewonly, false ) );
		#$this->view->assign ( 'roles',       CheckBoxes::generateHtml ( 'employee_role_option', 'role_phrase', $this->view, $params['roles'] ) );
		$this->view->assign ( 'transitions', DropDown::generateHtml   ( 'employee_transition_option', 'transition_phrase', $params['employee_transition_option_id'], false, $this->view->viewonly, false ) );
		$this->view->assign ( 'transitions_complete', DropDown::generateHtml ( 'employee_transition_option', 'transition_phrase', $params['employee_transition_complete_option_id'], false, $this->view->viewonly, false, false, array('name' => 'employee_transition_complete_option_id'), true ) );
		$helper = new Helper();
		$this->viewAssignEscaped ( 'facilities', $helper->getFacilities() );
		$this->view->assign ( 'relationships', DropDown::generateHtml ( 'employee_relationship_option', 'relationship_phrase', $params['employee_relationship_option_id'], false, $this->view->viewonly, false ) );
		$this->view->assign ( 'referrals',     DropDown::generateHtml ( 'employee_referral_option', 'referral_phrase', $params['employee_referral_option_id'], false, $this->view->viewonly, false ) );
		$this->view->assign ( 'provided',      DropDown::generateHtml ( 'employee_training_provided_option', 'training_provided_phrase', $params['employee_training_provided_option_id'], false, $this->view->viewonly, false ) );
		$employees = OptionList::suggestionList ( 'employee', array ('first_name' ,'CONCAT(first_name, CONCAT(" ", last_name)) as name' ), false, 99999 );
		$this->view->assign ( 'supervisors',   DropDown::render('supervisor_id', $this->translation['Supervisor'], $employees, 'name', 'id', $params['supervisor_id'] ) );
		$this->view->assign ( 'nationality',   DropDown::generateHtml ( 'lookup_nationalities', 'nationality', $params['lookup_nationalities_id'], false, $this->view->viewonly, false ) );
		$this->view->assign ( 'race',          DropDown::generateHtml ( 'person_race_option', 'race_phrase', $params['race_option_id'], false, $this->view->viewonly, false ) );
	}

	public function searchAction()
	{
		$this->view->assign('pageTitle', 'Search Employees');
		if (! $this->hasACL ( 'edit_employee' )) {
			$this->doNoAccessError ();
		}

		$criteria = $this->getAllParams();

		if ($criteria['go'])
		{
			// process search
			$where = array();

			list($a, $location_tier, $location_id) = $this->getLocationCriteriaValues($criteria);
			list($locationFlds, $locationsubquery) = Location::subquery($this->setting('num_location_tiers'), $location_tier, $location_id, true);

			$sql = "SELECT DISTINCT
						employee.id,
						employee.first_name,
						employee.last_name,
						employee.gender,
						employee.national_id,
						employee.other_id,
						employee.location_id,
						".implode(',',$locationFlds)."
						,CONCAT(supervisor.first_name, CONCAT(' ', supervisor.last_name)) as supervisor,
						qual.qualification_phrase as staff_cadre,
						site.facility_name,
						category.category_phrase as staff_category
					FROM employee LEFT JOIN ($locationsubquery) as l ON l.id = employee.location_id
					LEFT JOIN employee supervisor ON supervisor.id = employee.supervisor_id
					LEFT JOIN facility site ON site.id = employee.site_id
					LEFT JOIN employee_qualification_option qual ON qual.id = employee.employee_qualification_option_id
					LEFT JOIN employee_category_option category on category.id = employee.employee_category_option_id
					LEFT JOIN partner ON partner.id = employee.partner_id
					";

			#if ($criteria['partner_id']) $sql    .= ' INNER JOIN partner_to_subpartner subp ON partner.id = ' . $criteria['partner_id'];

			// restricted access?? only show partners by organizers that we have the ACL to view
			$org_allowed_ids = allowed_org_access_full_list($this); // doesnt have acl 'training_organizer_option_all'
			if($org_allowed_ids)
				$where[] = " partner.organizer_option_id in ($org_allowed_ids) ";

			if ($locationWhere = $this->getLocationCriteriaWhereClause($criteria, '', '')) {
				$where[] = $locationWhere;
			}

			if ($criteria['first_name'])                        $where[] = "employee.first_name   = '{$criteria['first_name']}'";
			if ($criteria['last_name'])                         $where[] = "employee.last_name    = '{$criteria['last_name']}'";
			if ($criteria['partner_id'])                        $where[] = 'employee.partner_id   = '.$criteria['partner_id']; //todo
			if ($criteria['facilityInput'])                     $where[] = 'employee.site_id      = '.$criteria['facilityInput'];
			if ($criteria['employee_qualification_option_id'])  $where[] = 'employee.employee_qualification_option_id    = '.$criteria['employee_qualification_option_id'];
			if ($criteria['category_option_id'])                $where[] = 'employee.staff_category_id = '.$criteria['category_option_id'];

			if ( count ($where) )
				$sql .= ' WHERE ' . implode(' AND ', $where);

			$db = $this->dbfunc();
			$rows = $db->fetchAll( $sql );

			$locations = Location::getAll();
			// hack #TODO - seems Region A -> ASDF, Region B-> *Multiple Province*, Region C->null Will not produce valid locations with Location::subquery
			// the proper solution is to add "Default" districts under these subdistricts, not sure if i can at this point the table is 12000 rows, todo later
			foreach ($rows as $i => $row) {
				if ($row['province_id'] == "" && $row['location_id']){ // empty province
					$updatedRegions = Location::getCityandParentNames($row['location_id'], $locations, $this->setting('num_location_tiers'));
					$rows[$i] = array_merge($row, $updatedRegions);
				}
			}

			$this->viewAssignEscaped('results', $rows);
			$this->viewAssignEscaped('count', count($rows));

			if ($criteria ['outputType'] && $rows) {
				$this->sendData ( $this->reportHeaders ( false, $rows ) );
			}
		}
		// assign form drop downs
		$helper = new Helper();
		$this->view->assign('status', $status);
		$this->viewAssignEscaped ( 'criteria', $criteria );
		$this->viewAssignEscaped ( 'locations', $locations );
		$this->view->assign ( 'partners',    DropDown::generateHtml ( 'partner', 'partner', $criteria['partner_id'], false, $this->view->viewonly, false ) );
		$this->view->assign ( 'subpartners', DropDown::generateHtml ( 'partner', 'partner', $criteria['partner_id'], false, $this->view->viewonly, false, false, array('name' => 'subpartner_id'), true ) );
		$this->view->assign ( 'cadres',      DropDown::generateHtml ( 'employee_qualification_option', 'qualification_phrase', $criteria['employee_qualification_option_id'], false, $this->view->viewonly, false ) );
		$this->viewAssignEscaped ( 'sites', $helper->getFacilities() );
		$this->view->assign ( 'categories',  DropDown::generateHtml ( 'employee_category_option', 'category_phrase', $criteria['employee_category_option_id'], false, $this->view->viewonly, false ) );
	}
	
	/**
	 * Import an Employee
	 */
	public function importAction() {
	
		$this->view->assign('pageTitle', t( 'Import an employee' ));
		require_once('models/table/TrainingToTrainer.php');
	
		// template redirect
		if ( $this->getSanParam('download') )
			return $this->importTrainingTemplateAction();
	
		#if( ! $this->hasACL('import_person') )
			#$this->doNoAccessError ();
	
		//CSV STUFF
		$filename = ($_FILES['upload']['tmp_name']);
		if ( $filename )
		{
			$db = $this->dbfunc();
			$employeeObj = new Employee ();
			$errs = array();
			while ($row = $this->_csv_get_row($filename) )
			{
				$values = array();
				if (! is_array($row) )
					continue;		   // sanity?
				if (! isset($cols) ) { // set headers (field names)
					$cols = $row;	   // first row is headers (field names)
					continue;
				}
				$countValidFields = 0;
				if (! empty($row) ) {  // add
					foreach($row as $i=>$v){ // proccess each column
						if ( empty($v) && $v !== '0' )
							continue;
						if ( $v == 'n/a') // has to be able to process values from a data export
							$v = NULL;
						$countValidFields++;
						$delimiter = strpos($v, ','); // is this field a comma seperated list too (or array)?
						if ($delimiter && $v[$delimiter - 1] != '\\')	// handle arrays as field values(Export), and comma seperated values(import manual entry), and strings or int
							$values[$cols[$i]] = explode(',', $this->sanitize($v));
						else
							$values[$cols[$i]] = $this->sanitize($v);
					}
				}
				// done now all fields are named and in $values[my_field]
				if ( $countValidFields ) {
					//validate
					if ( isset($values['uuid']) ){ unset($values['uuid']); }
					if ( isset($values['id']) )  { unset($values['id']); }
					if ( isset($values['is_deleted']) ) { unset($values['is_deleted']); }
					if ( isset($values['created_by']) ) { unset($values['created_by']); }
					if ( isset($values['modified_by']) ){ unset($values['modified_by']); }
					if ( isset($values['timestamp_created']) ){ unset($values['timestamp_created']); }
					if ( isset($values['timestamp_updated']) ){ unset($values['timestamp_updated']); }
					if ( ! $this->hasACL('approve_trainings') ){ unset($values['approved']); }
					
					
// field map
$values['employee_qualification_option_id'] = $values['Occupational-Classification (Required field)'];
$values['employee_role_option_id'] = $values['Primary-Role (Required field)'];
$values['first_name'] = $values['First Name (Optional)'];
$values['middle_name'] = $values['Middle Name (Optional)'];
$values['last_name'] = $values['Surname (Required)'];
$values['title_option_id'] = $values['Title (Optional)'];
$values['employee_code'] = $values['EMPLOYEE CODE (Required)'];
$values['dob'] = $this->_date_to_sql($values['Date-of-BirthID Number']);
$values['disability_option_id'] = $values['Disability (Y/N) (Optional)'];
$values['disability_comments'] = $values['Nature-of-Disability'];
$values['partner_id'] = $values['Partner (Required)'];
$values[''] = $values['Based-at (Required)'];
$values[''] = $values['Province (Required)'];
$values[''] = $values['District (Required)'];
$values[''] = $values['Sub-District (Required)'];
$values['site_id'] = $values['Site-Name'];
$values['facility_type_option_id'] = $values['Site-Type'];
$values['funded_hours_per_week'] = $values['Hours-Worked-Per-Week'];
$values['salary'] = $values['Annual-Salary  (No spaces)Required'];
$values['benefits'] = $values['Annual-Benefits (required)'];
$values['additional_expenses'] = $values['Annual-Additional-Expenses (Required)'];
$values['stipend'] = $values['Annual-Stipend (Required)'];
$values['annual_cost'] = $values['Annual-Cost (Sum)'];
$values['external_funding_percent'] = $values['Percentage-Not-Paid-By-Partner'];
$values['agreement_end_date'] = $this->_date_to_sql($values['Contract-End-Date']);
$values['employee_transition_option_id'] = $values['Intended-Transition'];
$values['transition_complete_other'] = $values['Specify-Other-Transition'];
$values['transition_complete_date'] = $values['Intended-Transition-Date'];
$values['employee_transition_complete_option_id'] = $values['Actual-Transition-Outcome'] ? $values['Actual-Transition-Outcome'] : 0;
$values['transition_other'] = $values['Specify-Other-Transition'];
$values['transition_date'] = $values['Actual-Transition-Date'];


					//locations
/*
					$regionNames = array (t('Region A (Province)'), t('Region B (Health District)'), t('Region C (Local Region)'), t('Region D'), t('Region E'), t('Region F'), t('Region G'), t('Region H'), t('Region I') );
					$num_location_tiers = $this->setting('num_location_tiers');
					$bSuccess = true;
					$facility_id = null;
					$fac_location_id = null;
	
					if ( $values['facility_name'] ) { // something set for facility (name or id) (id is duplicated to name to support importing from a data export.... TODO clean this up now that both fields are supported in this function)
	
						if (! $values['facility_id']) { // get the id somehow
	
							if(is_array($values['facility_name']))
								$values['facility_id'] = $values['facility_name'][0]; //
							else if ( is_numeric($values['facility_name']) && !trim( $values[ t('Region A (Province)') ] ) ) // bugfix: numbers w/ no province = ID, numbers + location data = Fac Name all numbers... its in facility_name b/c of data export
								$values['facility_id'] = $values['facility_name']; // support export'ed values. (remap)
							else // lookup id
							{
								// verify location, do not allow insert
								$tier = 1;
								for ($i=0; $i <= $num_location_tiers; $i++) { // find locations
									$regionName = $regionNames[$i]; // first location field in csv row // could use this too: $values[t('Region A (Province)')]
									if ( empty($values[$regionName]) || $bSuccess == false )
										continue;
									$fac_location_id = $db->fetchOne(
											"select id FROM location WHERE location_name = '". $values[$regionName] . "'"
											. ($fac_location_id ? " AND parent_id = $fac_location_id " : '')
											. " LIMIT 1");
									if (! $fac_location_id) {
										$bSuccess = false;
										break;
									}
									$tier++;
								}
	
								// lookup facility
								if ($fac_location_id) {
									$facility_id = $db->fetchOne( "select id FROM facility WHERE location_id = $fac_location_id AND facility_name = '".$values['facility_name']."' LIMIT 1" );
									$values['facility_id'] = $facility_id ? $facility_id : 0;
								} else {
									$errs[] = t('Error locating region or city:').' '.$values[$regionName].' '.t('Facility').': '.$values['facility_name'].space.t("This person will have no assigned facility if the save is successful.");
								}
								if (! $values['facility_id'] && $bSuccess) { // found region(bSuccess) but not facility
									$errs[] = t('Error locating facility:').space.$values['facility_name'].space.t("This person will have no assigned facility if the save is successful.");
								}
							}
						}
					} else {
						if (! $values['facility_id'])
							$errs[] = t('Error locating facility:').$values['facility_name'].space.t("This person will have no assigned facility if the save is successful.");
					}
					
					
					*/
					$bSuccess = true; //reset, we allow saving with no facility.
	
					if(! $bSuccess)
						continue;
	
					//field mapping (Export vs import)
					#if ( isset($values["qualification_phrase"]) )            $values["primary_qualification_option_id"] = $values["qualification_phrase"];
					#if ( isset($values["primary_qualification_phrase"]) )    $values["primary_qualification_option_id"] = $values["primary_qualification_phrase"];
					#if ( isset($values["primary_responsibility_phrase"]) )   $values["primary_responsibility_option_id"] = $values["primary_responsibility_phrase"];
					#if ( isset($values["secondary_responsibility_phrase"]) ) $values["secondary_responsibility_option_id"] = $values["secondary_responsibility_phrase"];
					#if ( isset($values["highest_edu_level_phrase"]) )        $values["highest_edu_level_option_id"] = $values["highest_edu_level_phrase"];
					#if ( isset($values["attend_reason_phrase"]) )            $values["attend_reason_option_id"] = $values["attend_reason_phrase"];
					#if ( isset($values["custom_1"]) )                        $values["person_custom_1_option_id"] = $values["custom_1"];
					#if ( isset($values["custom_2"]) )                        $values["person_custom_2_option_id"] = $values["custom_2"];
					//save
					try {
						//$values['title_option_id']                    = $this->_importHelperFindOrCreate('person_title_option',           'title_phrase',           $values['title_option_id']); //title_option_id multiAssign (insert via helper)
						//$values['suffix_option_id']                   = $this->_importHelperFindOrCreate('person_suffix_option',          'suffix_phrase',          $values['suffix_option_id']);
						#$values['primary_qualification_option_id']    = $this->_importHelperFindOrCreate('person_qualification_option',   'qualification_phrase',   $values['primary_qualification_option_id']);
						#$values['primary_responsibility_option_id']   = $this->_importHelperFindOrCreate('person_responsibility_option',  'responsibility_phrase',  $values['primary_responsibility_option_id']);
						#$values['secondary_responsibility_option_id'] = $this->_importHelperFindOrCreate('person_secondary_responsibility_option',  'responsibility_phrase', $values['secondary_responsibility_option_id']);
						#$values['attend_reason_option_id']            = $this->_importHelperFindOrCreate('person_attend_reason_option',   'attend_reason_phrase',   $values['attend_reason_option_id']);
						#$values['person_custom_1_option_id']          = $this->_importHelperFindOrCreate('person_custom_1_option',        'custom1_phrase',         $values['person_custom_1_option_id']);
						#$values['person_custom_2_option_id']          = $this->_importHelperFindOrCreate('person_custom_2_option',        'custom2_phrase',         $values['person_custom_2_option_id']);
						#$values['highest_level_option_id']            = $this->_importHelperFindOrCreate('person_education_level_option', 'education_level_phrase', $values['highest_level_option_id']);
						//$values['courses']                            = $this->_importHelperFindOrCreate('???',         '?????', null, $values['courses']);
						
						
						$employeerow = $employeeObj->createRow();
						$employeerow = ITechController::fillFromArray($employeerow, $values);
						$row_id = $employeerow->save();
					} catch (Exception $e) {
						$errored = 1;
						$errs[]  = nl2br($e->getMessage()).' '.t ( 'ERROR: The employee could not be saved.' );
					}
					if(! $row_id){
						$errored = 1;
						$errs[] = t('That employee could not be saved.').space.t("Name").": ".$values['first_name'].space.$values['last_name'].space.$values['employee_code'];
					}
					//sucess - done
				}//loop
			}
			// done processing rows
			$_POST['redirect'] = null;
			if( empty($errored) && empty($errs) )
				$stat = t ('Your changes have been saved.');
			else
				$stat = t ('Error importing data. Some data may have been imported and some may not have.');
	
			foreach($errs as $errmsg)
				$stat .= '<br>'.'Error: '.htmlspecialchars($errmsg, ENT_QUOTES);
	
			$status = ValidationContainer::instance();
			$status->setStatusMessage($stat);
			$this->view->assign('status', $status);
		}
		// done with import
	}

	
	/**
	 * A template for importing a training
	 */
	public function importTrainingTemplateAction() {
		$sorted = array (
				array (
						"Occupational-Classification (Required field)" => '',
						"Primary-Role (Required field)" => '',
						"First Name (Optional)" => '',
						"Middle Name (Optional)" => '',
						"Surname (Required)" => '',
						"Title (Optional)" => '',
						"Employee Code (Required)" => '',
						"Date-of-BirthID Number" => '',
						"Disability (Y/N) (Optional)" => '',
						"Nature-of-Disability" => '',
						"Partner (Required)" => '',
						"Based-at (Required)" => '',
						"Province (Required)" => '',
						"District (Required)" => '',
						"Sub-District (Required)" => '',
						"Site-Name" => '',
						"Site-Type" => '',
						"Hours-Worked-Per-Week" => '',
						"Annual-Salary  (No spaces)Required" => '',
						"Annual-Benefits (required)" => '',
						"Annual-Additional-Expenses (Required)" => '',
						"Annual-Stipend (Required)" => '',
						"Annual-Cost (Sum)" => '',
						"Percentage-Not-Paid-By-Partner" => '',
						"Contract-End-Date" => '',
						"Intended-Transition" => '',
						"Specify-Other-Transition" => '',
						"Intended-Transition-Date" => '',
						"Actual-Transition-Outcome" => '',
						"Specify-Other-Transition" => '',
						"Actual-Transition-Date" => ''
						
			
						
						/*
  "id"							=> '',
  "uuid"						=> '',
  "partner_id"					=> '',
  "partner_employee_number"		=> '',
  "subpartner_id"				=> '',
  "employee_base_option_id"		=> '',
  "location_id"					=> '',
  "title_option_id"				=> '',
  "first_name"					=> '',
  "middle_name"					=> '',
  "last_name"					=> '',
  "employee_code"				=> '',
  "national_id"					=> '',
  "other_id"					=> '',
  "gender"						=> '',
  "race_option_id"				=> '',
  "option_nationality_id"		=> '',
  "dob"							=> '',
  "disability_comments"			=> '',
  "disability_option_id"		=> '',
  "site_id"						=> '',
  "facility_type_option_id"		=> '',
  "address1"					=> '',
  "address2"					=> '',
  "primary_phone"				=> '',
  "secondary_phone"				=> '',
  "email"						=> '',
  "employee_qualification_option_id"		=> '',
  "employee_category_option_id"	=> '',
  "employee_role_option_id"		=> '',
  "employee_fulltime_option_id"	=> '',
  "funded_hours_per_week"		=> '',
  "annual_cost"					=> '',
  "external_funding_percent"	=> '',
  "agreement_end_date"			=> '',
  "supervisor_id"				=> '',
  "employee_transition_option_id"		=> '',
  "transition_other"			=> '',
  "employee_transition_complete_option_id"	=> '',
  "transition_complete_other"	=> '',
  "transition_complete_date"	=> '',
  "transition_confirmed"		=> '',
  "transition_date"				=> '',
  "employee_training_provided_option_id"	=> '',
  "comments"					=> '',
  "created_by"					=> '',
  "timestamp_created"			=> '',
  "registration_number"			=> '',
  "salary"						=> '',
  "benefits"					=> '',
  "additional_expenses"			=> '',
  "stipend"						=> ''
  */
						
				));
	
	
		//done, output a csv
		if( $this->getSanParam('outputType') == 'csv' )
			$this->sendData ( $this->reportHeaders ( false, $sorted ) );
	}
	
	
}

?>