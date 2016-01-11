<?php
require_once ('ReportFilterHelpers.php');
require_once ('FacilityController.php');
require_once ('models/table/Person.php');
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
require_once ('models/table/Partner.php');

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

		if (! $this->hasACL ( 'employees_module' )) {
			$this->doNoAccessError ();
		}
	}

	public function indexAction() {

		if (! $this->hasACL ( 'employees_module' )) {
			$this->doNoAccessError ();
		}

		require_once('models/table/dash-employee.php');
		$this->view->assign('title', $this->translation['Application Name'].space.t('Employee').space.t('Tracking System'));

		// restricted access?? does this user only have acl to view some trainings or people
		// they dont want this, removing 5/01/13
		$org_allowed_ids = allowed_org_access_full_list($this); // doesnt have acl 'training_organizer_option_all'?
		$allowedWhereClause = $org_allowed_ids ? " partner.organizer_option_id in ($org_allowed_ids) " : "";
		// restricted access?? only show organizers that belong to this site if its a multi org site
		$site_orgs = allowed_organizer_in_this_site($this); // for sites to host multiple training organizers on one domain
		$allowedWhereClause .= $site_orgs ? " AND partner.organizer_option_id in ($site_orgs) " : "";

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
			if (! $this->hasACL ( 'employees_module' )) {
				if($this->getParam('outputType') == 'json') {
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
			if($this->getParam('outputType') == 'json') {
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
		if (! $this->hasACL ( 'employees_module' )) {
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
            $db     = $this->dbfunc();
            $db->delete("link_mechanism_employee", "employee_id = $id");

			$status->setStatusMessage ( t ( 'That employee was deleted.' ) );
		} else {
			$status->setStatusMessage ( t ( 'That employee could not be found.' ) );
		}

		//validate
		$this->view->assign ( 'status', $status );

	}

    /**
     * get the mechanism data available to the logged in user
     * @return array
     */
    public function getAvailableMechanisms() {
        $db = $this->dbfunc();

        $sql = 'SELECT mechanism_option.id, mechanism_option.mechanism_phrase, mechanism_option.owner_id
                FROM mechanism_option
                LEFT JOIN link_mechanism_partner ON mechanism_option.id = link_mechanism_partner.mechanism_option_id
                WHERE is_deleted = 0';

        if (!$this->hasACL('training_organizer_option_all')) {
            $partners = $this->getAvailablePartners();
            if (count($partners)) {
                $plist = implode(',', $partners);
                $sql .= " AND (link_mechanism_partner.partner_id in ($plist) OR
                mechanism_option.owner_id in ($plist))";
            }
        }

        $sql .= ' GROUP BY mechanism_option.id ORDER BY owner_id ASC, mechanism_phrase ASC';

        $partnerMechanisms = $db->fetchAll($sql);

        foreach ($partnerMechanisms as &$mech) {
            $sql = "SELECT partner_id from link_mechanism_partner where mechanism_option_id = {$mech['id']}";
            $mech['partners'] = $db->fetchCol($sql);
        }

        return $partnerMechanisms;
    }

    /**
     * gets mechanisms associated with employee
     * @param int|string      - $employee_id
     * @return array
     */
    public function getEmployeeMechanisms($employee_id) {
        if (!$employee_id) {
            return array();
        }
        $db = $this->dbfunc();
        $sql = "SELECT link_mechanism_employee.id, link_mechanism_employee.percentage,
                link_mechanism_employee.mechanism_option_id, mechanism_option.mechanism_phrase
                FROM link_mechanism_employee INNER JOIN mechanism_option ON link_mechanism_employee.mechanism_option_id = mechanism_option.id
                WHERE employee_id = $employee_id ORDER BY link_mechanism_employee.percentage DESC";
        $employeeMechanisms = $db->fetchAll($sql);
        foreach ($employeeMechanisms as &$mech) {
            $sql = "SELECT partner_id from link_mechanism_partner where mechanism_option_id = {$mech['id']}";
            $mech['partners'] = $db->fetchCol($sql);
        }
        return $employeeMechanisms;
    }

    /**
     * return whether the logged-in user has access to this employee's information, depends on the user having
     * access to one of the partners on a mechanism associated with the employee
     *
     * @param int|string $partner_id
     * @param array $employeeMechanisms - return value from getEmployeeMechanisms
     * @param array $extraMechanismIDs - array of further mechanisms to check for partner association
     * @return bool
     */
    private function userHasAccess($partner_id, $employeeMechanisms, $extraMechanismIDs = array()) {
        if (!$this->hasACL('training_organizer_option_all')) {

            $partners = $this->getAvailablePartners();
			if (!count($partners)) {
				return false;
			}
            if (array_search($partner_id, $partners) === false) {

                // employee not employed by a partner that the logged in user has access to,
                // see if they're employed by a subpartner on a mechanism that an accessible partner owns

                $foundPartner = false;
                foreach ($employeeMechanisms as $mech) {
                    if (array_search($partner_id, $mech["partners"]) !== false) {
                        $foundPartner = true;
                        break;
                    }
                }

                if (!$foundPartner) {
                    $db = $this->dbfunc();
                    foreach ($extraMechanismIDs as $extraID) {
                        $sql = "SELECT partner_id from link_mechanism_partner where mechanism_option_id = $extraID";
                        $partners = $db->fetchCol($sql);
                        if (array_search($partner_id, $partners) !== false) {
                            $foundPartner = true;
                            break;
                        }
                    }
                }
                return $foundPartner;
            }
			else {
				return true;
			}
        }
        return false;
    }

	public function editAction() {
		if (! $this->hasACL ( 'employees_module' )) {
            $this->doNoAccessError ();
		}

        $params = $this->getAllParams();
        $id = $params['id'];

        if ($id == '' && $this->view->mode != 'add') {
            $this->doNoAccessError ();
        }

        $status = ValidationContainer::instance();
        $db = $this->dbfunc();

        // ignore when $params['edittabledelete'] is true so that edit table deletions can be done as a batch via
        // form post when save button is pressed, rather than AJAX
		if ( $this->getRequest()->isPost() && !$params['edittabledelete'] )
		{

            if (!$this->hasACL('edit_employee'))
		    {
		        $this->doNoAccessError();
		    }
            else 
            {
                if ($id && (!$this->hasACL('training_organizer_option_all'))) {

                    // ensure that we have permissions to edit this employee via mechanism partners and subpartners,
                    // including newly posted form data
                    $newMechanismIDs = array();
                    $newMechData = json_decode($params['employeeFunding_new_data'], true);
                    foreach ($newMechData as $newData) {
                        array_push($newMechanismIDs, $newData['id']);
                    }
                    if (!$this->userHasAccess($params['partner_id'], $this->getEmployeeMechanisms($id), $newMechanismIDs)) {
                        $this->doNoAccessError();
                    }
                }

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
    
    			// $status->checkRequired ( $this, 'first_name', t ( 'Frist Name' ) );
    			// $status->checkRequired ( $this, 'last_name',  t ( 'Last Name' ) );
    			
    			$status->checkRequired ( $this, 'employee_code', t('Employee Code'));
    			
    			//$status->checkRequired ( $this, 'dob', t ( 'Date of Birth' ) );//TA:18: 08/28/2014 (DOB field is not required)
    			
    			if($this->setting('display_employee_nationality'))
    				$status->checkRequired ( $this, 'lookup_nationalities_id', t('Employee Nationality'));
    			
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
    			//if($this->setting('display_employee_sub_partner'))
    				//$status->checkRequired ( $this, 'subpartner_id', t ( 'Sub Partner' ) );
    			if($this->setting('display_employee_intended_transition'))
    				$status->checkRequired ( $this, 'employee_transition_option_id', t ( 'Intended Transition' ) );
    			if(($this->setting('display_employee_base') && !$params['employee_base_option_id']) || !$this->setting('display_employee_base')) // either one is OK, javascript disables regions if base is on & has a value choice
    				$status->checkRequired ( $this, 'province_id', t ( 'Region A (Province)' ).space.t('or').space.t('Employee Based at') );
    			if($this->setting('display_employee_base') && !$params['province_id'])
    				$status->checkRequired ( $this, 'employee_base_option_id', t('Employee Based at').space.t('or').space.t('Region A (Province)') );
    			if($this->setting('display_employee_primary_role'))
    				$status->checkRequired ( $this, 'employee_role_option_id', t ( 'Primary Role' ) );
    
    			$status->checkRequired ( $this, 'funded_hours_per_week', t ( 'Funded hours per week' ) );
    			if($this->setting['display_employee_contract_end_date'])
    				$status->checkRequired ( $this, 'agreement_end_date', t ( 'Contract End Date' ) );
    			
                // TODO: used?
    			$params['subPartner'] = $this->_array_me($params['subPartner']);
    			$params['partnerFunder'] = $this->_array_me($params['partnerFunder']);
    			$params['mechanism'] = $this->_array_me($params['mechanism']);
    			
    			$total_percent = 0;
    			foreach($params['percentage'] as $i => $val){
    				$total_percent = $total_percent + $params['percentage'][$i];
    			}
    			if ($total_percent > 100) $status->setStatusMessage ( t(' Warn: Total Funded Percentage > 100 ') );
    		
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
    			    require_once('models/table/Employee.php');
    				$id = $this->_findOrCreateSaveGeneric('employee', $params);

    				if(!$id) {
    					$status->setStatusMessage( t('That person could not be saved.') );
    				} else {
                        if ($params['disassociateMechanisms'])
                        {
                            if (!Employee::disassociateMechanismsFromEmployee($id, $params['disassociateMechanisms'])) {
                                $status->setStatusMessage(t('Error removing mechanism association.'));
                            }
                        }

                        if ($params['associateMechanisms'])
                        {
                            if (!Employee::saveMechanismAssociations($id, $params['associateMechanisms'], $params['mechanismPercentages']))
                            {
                                $status->setStatusMessage(t('Error saving mechanism association.'));
                            }
                        }
    					$status->setStatusMessage( t('The person was saved.') );
                        $this->_redirect("employee/edit/id/$id");
    				}
    			}
    		} // else we have edit_employee acl
		} // if isPost()

        if ($this->getRequest()->isPost() && $params['edittabledelete']) {
            // tell edit table that the ajax request succeeded, we'll actually process it when the
            // Save button is clicked
            $this->sendData(array('msg' => 'ok'));
        }

        // this data is used both for populating mechanisms and checking for subpartner permissions to edit employees
        $employeeMechanisms = $this->getEmployeeMechanisms($id);
        $mechanisms = $this->getAvailableMechanisms($id);
        $mechanismData = array('available' => $mechanisms);
        $mechanismData['employee_mechanism_ids'] = array();
        $mechanismData['employee_association_ids'] = array();
        foreach($employeeMechanisms as $mech) {
            array_push($mechanismData['employee_mechanism_ids'], $mech['mechanism_option_id']);
            $mechanismData['employee_association_ids'][$mech['id']] = $mech['mechanism_option_id'];
        }

        if ( $id && !$status->hasError() )  // read data from db
		{
			$sql = 'SELECT * FROM employee WHERE employee.id = '.$id;
			$row = $db->fetchRow( $sql );
			if ( ! $row)
			{
				$status->setStatusMessage ( t('Error finding that record in the database.') );
			}
			else 
        	{
            	$params = $row; // reassign form data
            	
            	$region_ids = Location::getCityInfo($params['location_id'], $this->setting('num_location_tiers'));
            	$region_ids = Location::regionsToHash($region_ids);
            	$params = array_merge($params, $region_ids);
			}

            if ((!$this->hasACL('training_organizer_option_all')) &&
                (!$params['partner_id'] || !$this->userHasAccess($params['partner_id'], $employeeMechanisms))) {
                $this->doNoAccessError();
            }
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
		$this->view->assign ( 'pageTitle', $this->view->mode == 'add' ? t ('Add').space.t('Employee' ) : t( 'Edit').space.t('Employee' ) );
		$this->viewAssignEscaped ( 'locations', Location::getAll () );
		$titlesArray = OptionList::suggestionList ( 'person_title_option', 'title_phrase', false, 9999);
		$this->view->assign ( 'titles',      DropDown::render('title_option_id', $this->translation['Title'], $titlesArray, 'title_phrase', 'id', $params['title_option_id'] ) );
		
		$this->view->assign ( 'partners',    DropDown::generateHtml   ( 'partner', 'partner', $params['partner_id'], false, !$this->hasACL("edit_employee"), $this->getAvailablePartners(), false, array("onchange" => "availableMechanisms();") ) );
		
		$this->view->assign ( 'bases',       DropDown::generateHtml   ( 'employee_base_option', 'base_phrase', $params['employee_base_option_id'], false, !$this->hasACL("edit_employee")) );
		$this->view->assign ( 'site_types',  DropDown::generateHtml   ( 'employee_site_type_option', 'site_type_phrase', $params['facility_type_option_id'], false, !$this->hasACL("edit_employee")) );
		$this->view->assign ( 'cadres',      DropDown::generateHtml   ( 'employee_qualification_option', 'qualification_phrase', $params['employee_qualification_option_id'], false, !$this->hasACL("edit_employee")) );
		$this->view->assign ( 'categories',  DropDown::generateHtml   ( 'employee_category_option', 'category_phrase', $params['employee_category_option_id'], false, !$this->hasACL("edit_employee"), false ) );
		$this->view->assign ( 'fulltime',    DropDown::generateHtml   ( 'employee_fulltime_option', 'fulltime_phrase', $params['employee_fulltime_option_id'], false, !$this->hasACL("edit_employee"), false ) );
		$this->view->assign ( 'roles',       DropDown::generateHtml   ( 'employee_role_option', 'role_phrase', $params['employee_role_option_id'], false, !$this->hasACL("edit_employee"), false ) );
		#$this->view->assign ( 'roles',       CheckBoxes::generateHtml ( 'employee_role_option', 'role_phrase', $this->view, $params['roles'] ) );
		$this->view->assign ( 'transitions', DropDown::generateHtml   ( 'employee_transition_option', 'transition_phrase', $params['employee_transition_option_id'], false, !$this->hasACL("edit_employee"), false ) );
		$this->view->assign ( 'transitions_complete', DropDown::generateHtml ( 'employee_transition_option', 'transition_phrase', $params['employee_transition_complete_option_id'], false, !$this->hasACL("edit_employee"), false, false, array('name' => 'employee_transition_complete_option_id'), true ) );
		$helper = new Helper();
		$this->viewAssignEscaped ( 'facilities', $helper->getFacilities() );
		$this->view->assign ( 'relationships', DropDown::generateHtml ( 'employee_relationship_option', 'relationship_phrase', $params['employee_relationship_option_id'], false, !$this->hasACL("edit_employee"), false ) );
		$this->view->assign ( 'referrals',     DropDown::generateHtml ( 'employee_referral_option', 'referral_phrase', $params['employee_referral_option_id'], false, !$this->hasACL("edit_employee"), false ) );
		$this->view->assign ( 'provided',      DropDown::generateHtml ( 'employee_training_provided_option', 'training_provided_phrase', $params['employee_training_provided_option_id'], false, !$this->hasACL("edit_employee"), false ) );
		$employees = OptionList::suggestionList ( 'employee', array ('first_name' ,'CONCAT(first_name, CONCAT(" ", last_name)) as name' ), false, 99999 );
		$this->view->assign ( 'supervisors',   DropDown::render('supervisor_id', $this->translation['Supervisor'], $employees, 'name', 'id', $params['supervisor_id'] ) );
		$this->view->assign ( 'nationality',   DropDown::generateHtml ( 'lookup_nationalities', 'nationality', $params['lookup_nationalities_id'], false, !$this->hasACL("edit_employee"), false ) );
		$this->view->assign ( 'race',          DropDown::generateHtml ( 'person_race_option', 'race_phrase', $params['race_option_id'], false, !$this->hasACL("edit_employee"), false ) );

		$this->view->assign('mechanismData', $mechanismData);
		$this->view->assign('employeeMechanisms', $employeeMechanisms);
	}

	public function searchAction()
	{
		$this->view->assign('pageTitle', 'Search Employees');
		if (! $this->hasACL ( 'employees_module' )) {
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
    employee.employee_code,
    employee.gender,
    employee.national_id,
    employee.other_id,
    employee.location_id,
    ".implode(',',$locationFlds).",
    CONCAT(supervisor.first_name,
    CONCAT(' ', supervisor.last_name)) as supervisor,
    qual.qualification_phrase as staff_cadre,
    site.facility_name,
    category.category_phrase as staff_category,
GROUP_CONCAT(subpartner.partner) as subPartner,
GROUP_CONCAT( partner_funder_option.funder_phrase) as partnerFunder,
GROUP_CONCAT(mechanism_option.mechanism_phrase) as mechanism,
GROUP_CONCAT(link_mechanism_employee.percentage) as percentage
FROM    employee
LEFT JOIN    ($locationsubquery) as l ON l.id = employee.location_id
LEFT JOIN   employee supervisor ON supervisor.id = employee.supervisor_id
LEFT JOIN   facility site ON site.id = employee.site_id
LEFT JOIN   employee_qualification_option qual ON qual.id = employee.employee_qualification_option_id
LEFT JOIN   employee_category_option category ON category.id = employee.employee_category_option_id
LEFT JOIN   partner ON partner.id = employee.partner_id
LEFT JOIN   link_mechanism_employee on link_mechanism_employee.employee_id = employee.id
LEFT JOIN   mechanism_option on mechanism_option.id = link_mechanism_employee.mechanism_option_id
LEFT JOIN 	partner_funder_option on mechanism_option.funder_id = partner_funder_option.id
LEFT JOIN   link_mechanism_partner on link_mechanism_partner.mechanism_option_id = mechanism_option.id
LEFT JOIN   partner subpartner on subpartner.id = link_mechanism_partner.partner_id
";
			#if ($criteria['partner_id']) $sql    .= ' INNER JOIN partner_to_subpartner subp ON partner.id = ' . $criteria['partner_id'];

			// restricted access?? only show partners by organizers that we have the ACL to view
			$org_allowed_ids = allowed_org_access_full_list($this); // doesnt have acl 'training_organizer_option_all'
			if($org_allowed_ids)
				$where[] = " partner.organizer_option_id in ($org_allowed_ids) ";

			if ($locationWhere = $this->getLocationCriteriaWhereClause($criteria, '', '')) {
				$where[] = $locationWhere;
			}

			// if ($criteria['first_name'])                        $where[] = "employee.first_name   = '{$criteria['first_name']}'";
			// if ($criteria['last_name'])                         $where[] = "employee.last_name    = '{$criteria['last_name']}'";
			if ($criteria['employee_code'])                        $where[] = "employee.employee_code    like '%{$criteria['employee_code']}%'";
			
			if ($criteria['partner_id'])                        $where[] = 'employee.partner_id   = '.$criteria['partner_id']; //todo
			
			if ($criteria['facilityInput'])                     $where[] = 'employee.site_id      = '.$criteria['facilityInput'];
			if ($criteria['employee_qualification_option_id'])  $where[] = 'employee.employee_qualification_option_id    = '.$criteria['employee_qualification_option_id'];
			if ($criteria['category_option_id'])                $where[] = 'employee.staff_category_id = '.$criteria['category_option_id'];

			if ( count ($where) )
				$sql .= ' WHERE ' . implode(' AND ', $where);
			
			$sql .= ' GROUP BY employee.id ';
			
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

		$this->viewAssignEscaped ( 'criteria', $criteria );
		$this->viewAssignEscaped ( 'locations', $locations );
		$this->view->assign ( 'partners',    DropDown::generateHtml ( 'partner', 'partner', $criteria['partner_id'], false, $this->view->viewonly, $this->getAvailablePartners() ) );
		
		$this->view->assign ( 'cadres',      DropDown::generateHtml ( 'employee_qualification_option', 'qualification_phrase', $criteria['employee_qualification_option_id'], false, $this->view->viewonly, false ) );
		$this->viewAssignEscaped ( 'sites', $helper->getFacilities() );
		$this->view->assign ( 'categories',  DropDown::generateHtml ( 'employee_category_option', 'category_phrase', $criteria['employee_category_option_id'], false, $this->view->viewonly, false ) );
	}
}

?>