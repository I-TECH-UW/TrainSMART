<?php
require_once('ReportFilterHelpers.php');
require_once('FacilityController.php');
require_once('models/table/Person.php');
require_once('models/table/Facility.php');
require_once('models/table/OptionList.php');
require_once('models/table/MultiOptionList.php');
require_once('models/table/Location.php');
require_once('models/table/MultiAssignList.php');
require_once('views/helpers/FormHelper.php');
require_once('views/helpers/DropDown.php');
require_once('views/helpers/Location.php');
require_once('views/helpers/CheckBoxes.php');
require_once('views/helpers/TrainingViewHelper.php');
require_once('models/table/Helper.php');
require_once('models/table/Partner.php');

class EmployeeController extends ReportFilterHelpers
{

    public function init()
    {
    }

    public function preDispatch()
    {
        parent::preDispatch();

        if (!$this->isLoggedIn())
            $this->doNoAccessError();

        if (!$this->setting('module_employee_enabled')) {
            $_SESSION['status'] = t('The employee module is not enabled on this site.');
            $this->_redirect('select/select');
        }

        if (!$this->hasACL('employees_module')) {
            $this->doNoAccessError();
        }
    }

    public function indexAction()
    {

        if (!$this->hasACL('employees_module')) {
            $this->doNoAccessError();
        }

        require_once('models/table/dash-employee.php');
        $this->view->assign('title', $this->translation['Application Name'] . space . t('Employee') . space . t('Tracking System'));

        // restricted access?? does this user only have acl to view some trainings or people
        // they dont want this, removing 5/01/13
        $org_allowed_ids = allowed_org_access_full_list($this); // doesnt have acl 'training_organizer_option_all'?
        $allowedWhereClause = $org_allowed_ids ? " partner.organizer_option_id in ($org_allowed_ids) " : "";
        // restricted access?? only show organizers that belong to this site if its a multi org site
        $site_orgs = allowed_organizer_in_this_site($this); // for sites to host multiple training organizers on one domain
        $allowedWhereClause .= $site_orgs ? " AND partner.organizer_option_id in ($site_orgs) " : "";

        $partners = new DashviewEmployee();
        $details = $partners->fetchdetails($allowedWhereClause);
        $this->view->assign('getins', $details);
        
        //TA:#412
        $db = $this->dbfunc(); 
        $select = "SELECT employee.id, partner.partner, employee.employee_code,
    SUBSTRING_INDEX(employee.agreement_end_date, ' ', 1) as agreement_end_date,
    SUBSTRING_INDEX(employee.transition_date, ' ', 1) as transition_date, 
    SUBSTRING_INDEX(employee.transition_complete_date, ' ', 1) as transition_complete_date,
    mechanism_option.end_date as mechanism_end_date
FROM employee
LEFT JOIN partner ON partner.id = employee.partner_id
LEFT JOIN link_mechanism_employee ON employee.id = link_mechanism_employee.employee_id
LEFT JOIN mechanism_option ON link_mechanism_employee.mechanism_option_id = mechanism_option.id
WHERE (is_active = 1) AND (partner.organizer_option_id in (select training_organizer_option_id from user_to_organizer_access where user_id = " . $this->isLoggedIn() . "))
AND (employee.agreement_end_date < SUBSTRING_INDEX(now(), ' ', 1) OR transition_date < SUBSTRING_INDEX(now(), ' ', 1)
     OR mechanism_option.end_date < SUBSTRING_INDEX(now(), ' ', 1) OR  transition_complete_date > SUBSTRING_INDEX(now(), ' ', 1))";
        $position_updates = $db->fetchAll($select);
         $this->view->assign('position_updates', $position_updates);
        ///

        /****************************************************************************************************************/
        /* Attached Files */
        require_once('views/helpers/FileUpload.php');

        $PARENT_COMPONENT = 'employee';

        FileUpload::displayFiles($this, $PARENT_COMPONENT, 1, $this->hasACL('admin_files'));
        // File upload form
        if ($this->hasACL('admin_files')) {
            $this->view->assign('filesForm', FileUpload::displayUploadForm($PARENT_COMPONENT, 1, FileUpload::$FILETYPES));
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
            if (!$this->hasACL('employees_module')) {
                if ($this->getParam('outputType') == 'json') {
                    $this->sendData(array('msg' => 'Not Authorized'));
                    exit();
                    return;
                }
                $this->doNoAccessError();
            }

            $ret = array();
            $params = $this->getAllParams();

            if ($params['mode'] == 'addedit') {
                // add or update a record based on $params[id]
                if (empty($params['id']))
                    unset($params['id']); // unset ID (primary key) for Zend if 0 or '' (insert new record)
                $id = $this->_findOrCreateSaveGeneric('employee_to_course', $params); // wrapper for find or create
                $params['id'] = $id;
                if ($id) {
                    // saved
                    // reload the data
                    $db = $this->dbfunc();
                    $ret = $db->fetchRow("select * from employee_to_course where id = $id");
                    $ret['msg'] = 'ok';
                } else {
                    $ret['errored'] = true;
                    $ret['msg'] = t('Error creating record.');
                }
            } else if ($params['mode'] == 'delete' && $params['id']) {
                // delete a record
                try {
                    $course_link_table = new ITechTable (array('name' => 'employee_to_course'));
                    $num_rows = $course_link_table->delete('id = ' . $params['id']);
                    if (!$num_rows)
                        $ret['msg'] = t('Error finding that record in the database.');
                    $ret['num_rows'] = $num_rows;
                } catch (Exception $e) {
                    $ret['errored'] = true;
                    $ret['msg'] = t('Error finding that record in the database.');
                }
            }

            if (strtolower($params['outputType']) == 'json') {
                $this->sendData($ret); // probably always json no need to check for output
            }

        } catch (Exception $e) {
            if ($this->getParam('outputType') == 'json') {
                $this->sendData(array('errored' => true, 'msg' => 'Error: ' . $e->getMessage()));
                return;
            } else {
                echo $e->getMessage();
            }
        }
    }

    private function getCourses($employee_id)
    {
        if (!$employee_id)
            return;

        $db = $this->dbfunc();
        $sql = "SELECT * FROM employee_to_course WHERE employee_id = $employee_id";
        $rows = $db->fetchAll($sql);
        return $rows ? $rows : array();
    }

    public function addAction()
    {
        $this->view->assign('mode', 'add');
        $this->view->assign('pageTitle', t('Add New') . ' ' . t('Employee'));
        return $this->editAction();
    }

    public function deleteAction()
    {
        if (!$this->hasACL('employees_module')) {
            $this->doNoAccessError();
        }
        
        //TA:#421
        if (!$this->hasACL('position_access')) {
            $this->doNoAccessError();
        }
        
        require_once('models/table/Employee.php');
        $status = ValidationContainer::instance();
        $id = $this->getSanParam('id');

        if ($id) {
            $employee = new Employee ();
            $rows = $employee->find($id);
            $row = $rows->current();
            if ($row) {
                $employee->delete('id = ' . $row->id);
            }
            $db = $this->dbfunc();
            $db->delete("link_mechanism_employee", "employee_id = $id");

            $status->setStatusMessage(t('That employee was deleted.'));
        } else {
            $status->setStatusMessage(t('That employee could not be found.'));
        }

        //validate
        $this->view->assign('status', $status);

    }

    /**
     * get the mechanism data available to the logged in user
     * @return array
     */
    public function getAvailableMechanisms()
    {
        $db = $this->dbfunc();
        $user_id = $this->isLoggedIn();

        $res = array();

        // only get mechanisms that are not expired or that expired this quarter
        $currentQuarterStartDate = $this->getCurrentQuarterStartDate();

        $select = $db->select()
            ->from('mechanism_option', array())
            ->joinLeft(array('mechanism_partners' => 'link_mechanism_partner'),
                'mechanism_partners.mechanism_option_id = mechanism_option.id', array())
            ->where('is_deleted = 0')
            ->where('mechanism_option.end_date >= ?', $currentQuarterStartDate->format('Y-m-d'))
            ->group('mechanism_option.id')
            ->order('mechanism_phrase ASC');

        // this allows us to set the order of columns in the returned results. Having the id be the first result is
        // important for $db->fetchAssoc(), since it uses the first column as array keys
        $select->columns(array('mechanism_option.id', 'mechanism_option.mechanism_phrase', 'mechanism_option.owner_id',
            'partners' => "GROUP_CONCAT(DISTINCT mechanism_partners.partner_id SEPARATOR ',,,')"));

        if (!$this->hasACL('training_organizer_option_all')) {
            $select->joinInner('link_mechanism_partner', 'mechanism_option.id = link_mechanism_partner.mechanism_option_id', array())
                ->joinInner('partner', 'link_mechanism_partner.partner_id = partner.id', array('partner_id' => 'partner.id'))
                ->joinInner('user_to_organizer_access',
                    'user_to_organizer_access.training_organizer_option_id = partner.organizer_option_id', array())
                ->where('user_to_organizer_access.user_id = ?', $user_id);
        }

        $res['byMechanism'] = $db->fetchAssoc($select);
        $mechanisms = &$res['byMechanism'];
        $res['byPartner'] = array();
        $partners = &$res['byPartner'];

        foreach ($mechanisms as $mechID => &$mechData) {
            $mechData['partners'] = explode(',,,', $mechData['partners']);
            $p = $mechData['partners'];
            foreach ($p as $partnerID) {
                if (!array_key_exists($partnerID, $partners)) {
                    $partners[$partnerID] = array();
                }
                array_push($partners[$partnerID], $mechData['id']);
            }
        }

        return $res;
    }

    public function editAction()
    {
        
        require_once('models/table/Employee.php');//TA:#293 
        if (!$this->hasACL('employees_module')) {
            $this->doNoAccessError();
        }
        
        //TA:#421
        if (!$this->hasACL('position_access')) {
            $this->doNoAccessError();
        }

        $params = $this->getAllParams();
        $id = $params['id'];

        $db = $this->dbfunc();
        $choose = array("" => '--' . t("choose") . '--');
        $bases = $choose + $db->fetchPairs(
            $db->select()
                ->from('employee_base_option', array('id', 'base_phrase'))
                ->order('base_phrase ASC')
        );

        $employeeMechanisms = array();
        if (!$id && $this->view->mode != 'add') {
            $this->doNoAccessError();
        } else if ($this->view->mode !== 'add') {
            // get current employee mechanisms
            $select = $db->select()
                ->from('link_mechanism_employee', array('mechanism_option_id', 'percentage'))
                ->joinLeft('mechanism_option', 'link_mechanism_employee.mechanism_option_id = mechanism_option.id',
                    array('mechanism_phrase'))
                ->where('employee_id = ?', $id)
                ->order('percentage DESC');

            $employeeMechanisms = $db->fetchAssoc($select);
        }

        $status = ValidationContainer::instance();

        if ($this->getRequest()->isPost()) {

            if (!$this->hasACL('edit_employee')) {
                $this->doNoAccessError();
            }
            else {

                //validate then save
                $params['dob'] = $this->_euro_date_to_sql($params['dob']);
                $params['agreement_start_date'] = $this->_euro_date_to_sql($params['agreement_start_date']);//TA:#374
                $params['agreement_end_date'] = $this->_euro_date_to_sql($params['agreement_end_date']);
                $params['transition_date'] = $this->_euro_date_to_sql($params['transition_date']);
                $params['transition_complete_date'] = $this->_euro_date_to_sql($params['transition_complete_date']);
                $params['site_id'] = $params['facilityInput'];
                $params['option_nationality_id'] = $params['lookup_nationalities_id'];
                $params['facility_type_option_id'] = $params['employee_site_type_option_id'];
                $params['is_active'] = $params['is_active']?'1':'0';//TA:#309
                $params['employee_transition_complete_option_id'] = $params['employee_transition_complete_option_id']?$params['employee_transition_complete_option_id']:'0';

                //TA:#329
                $status->checkRequired($this, 'employee_qualification_option_id', t('Staff Cadre'));
                $params['employee_qualification_option_id'] = explode("_", $params['employee_qualification_option_id'])[1];

                if ($this->setting('display_employee_primary_role')) {
                    $status->checkRequired($this, 'employee_role_option_id', t('Primary Role'));
                }

                $status->checkRequired($this, 'employee_code', t('Employee Code'));

                if ($this->setting('display_employee_disability')) {
                    $status->checkRequired($this, 'disability_option_id', t('Disability'));
                    if ($params['disability_option_id'] == 1)
                        $status->checkRequired($this, 'disability_comments', t('Nature of Disability'));
                }

                // set partner specific unique employee number. (auto-increment ID for each employee, starting at 1, per-partner)
                if ($id) { // reset if change partner_id
                    $oldPartnerId = $db->fetchOne("SELECT partner_id FROM employee WHERE id = ?", $id);
                    if ($params['partner_id'] != $oldPartnerId || $params['partner_id'] == "")
                        $params['partner_employee_number'] = null;
                }
                if ($params['partner_id'] && (!isset($params['partner_employee_number']) || $params['partner_employee_number'] == "")) { // generate a new id
                    $max = $db->fetchOne("SELECT MAX(partner_employee_number) FROM employee WHERE partner_id = ?", $params['partner_id']);
                    $params['partner_employee_number'] = $max ? $max + 1 : 1; // max+1 or default to 1
                }

                if ($this->setting('display_employee_partner')) {
                    $status->checkRequired($this, 'partner_id', t('Partner'));
                }
                    
                if ($this->setting('display_employee_base')) {
                    $hasBase = $status->checkRequired($this, 'employee_base_option_id', t('Employee Based at'));

                    if ($hasBase) {
                        // this functionality relies on an entry in the employee_base_option table to have the 'base_phrase'
                        // the same as the translated version of the string 'Other' in the translation table
                        $b = array_flip($bases);
                        $other_id = $b[t('Other')];
                        if ($other_id && $params['employee_base_option_id'] == $other_id) {
                            $status->checkRequired($this, 'based_at_other', t('Employee Based at') . ' ' . t('Other, Specify'));
                            if (strlen($params['based_at_other'] > 50)) {
                                $status->addError('based_at_other', t('Based at Other, Specify must be 50 characters or fewer'));
                            }
                        }
                    }
                }
                
                //TA:#224
                if($params['multi_sites_table_data_count'] == 0){
                    $status->checkRequired($this, 'multi_sites_table_data_count_0', t('Site Name'));
                }

                $status->checkRequired($this, 'agreement_start_date', t('Contract Start Date'));//TA:#374
                
                if ($this->setting('display_employee_contract_end_date')) {
                    $status->checkRequired($this, 'agreement_end_date', t('Contract End Date'));
                }
                
                //TA:#374
                if($params['agreement_start_date'] && $params['agreement_end_date']){
                    if(strtotime($params['agreement_start_date']) > strtotime($params['agreement_end_date'])){
                        $status->addError('agreement_dates_wrong', t('Contract Start Date must be less than Contract End Date'));
                    }
                }
                

                if ($this->setting('display_employee_intended_transition')) {
                    $status->checkRequired($this, 'employee_transition_option_id', t('Intended Transition'));
                    //TA:#279.2
                    if($params['employee_transition_option_id'] == 8){
                        $status->checkRequired($this, 'transition_other', t('Intended Transition Other'));
                    }
                }
                
                //TA:#279.2
                if($params['employee_transition_complete_option_id'] == 8){
                    $status->checkRequired($this, 'transition_complete_other', t('Actual Transition Outcome Other'));
                } 

                //TA:#383
                $status->checkRequired($this, 'transition_date', t('Intended Transition Date'));

                $status->checkRequired($this, 'funded_hours_per_week', t('Funded hours per week'));
                //TA:#447
                if ($params['funded_hours_per_week'] < 1 || $params['funded_hours_per_week'] > 45) {
                    $status->addError('funded_hours_per_week',  t('Funded hours per week') . ' should be between 1 and 45');
                }
                
                $costaccum = 0;
                if ($this->setting('display_employee_salary')) {
                    $status->checkRequired($this, 'salary', t('Salary'));
                    $costaccum += $params['salary'];
                }
                if ($this->setting('display_employee_benefits')) {
                    $status->checkRequired($this, 'benefits', t('Benefits'));
                    $costaccum += $params['benefits'];
                }
                if ($this->setting('display_employee_additional_expenses')) {
                    $status->checkRequired($this, 'additional_expenses', t('Additional Expenses'));
                    $costaccum += $params['additional_expenses'];
                }
                if ($this->setting('display_employee_stipend')) {
                    $status->checkRequired($this, 'stipend', t('Stipend'));
                    $costaccum += $params['stipend'];
                }
                if ($costaccum > 2000000) {
                    $status->addError('annual_cost', t('Total') . ' ' . t('Annual Cost') . ' ' . t("can not be more than 2,000,000."));
                }

                $total_percent = 0;
                foreach ($params['percentage'] as $i => $val) {
                    $total_percent = $total_percent + $params['percentage'][$i];
                }

                if ($total_percent > 100) {
                    $status->setStatusMessage(t(' Warn: Total Funded Percentage > 100 '));
                }

                // save
                if (!$status->hasError()) {
                    require_once('models/table/Employee.php');
                    
                    //TA:#408 trim strings
                    preg_replace('/\s+/', '', $params['transition_other']);
                    preg_replace('/\s+/', '', $params['transition_complete_other']);
                                     
                    $id = $this->_findOrCreateSaveGeneric('employee', $params);

                    if (!$id) {
                        //TA:#171 add more details to error message and redirect to employee edit page for edit or stay on the same page
                        $status->setStatusMessage(t('That position could not be saved. Employee code for this partner exists.'));
                    } else {
                        if (isset($params['disassociateMechanisms']) && $params['disassociateMechanisms']) {
                            if (!Employee::disassociateMechanismsFromEmployee($id, $params['disassociateMechanisms'])) {
                                $status->setStatusMessage(t('Error removing mechanism association.'));
                            }
                        }

                        if (isset($params['associateMechanisms']) && $params['associateMechanisms']) {
                            if (!Employee::saveMechanismAssociations($id, $params['associateMechanisms'], $params['mechanismPercentages'])) {
                                $status->setStatusMessage(t('Error saving mechanism association.'));
                            }
                        }
                        
                        //TA:#438
                        if(!Employee::removeAllSites($id)){
                            $status->setStatusMessage(t('Error saving employee sites.'));    
                        }
                        if($params['multi_sites_table_data']){
                            $sites_to_add = explode(";",$params['multi_sites_table_data']);
                            foreach($sites_to_add as $i => $loc) {
                                $site_to_add = explode(",",$loc);
                                if(!Employee::saveSites($id, $site_to_add[0], $site_to_add[1],$site_to_add[2],$site_to_add[3])){//TA:#416
                                    $status->setStatusMessage(t('Error saving employee sites.'));
                                }
                            }
                        }
                        //////
                        
                        $status->setStatusMessage(t('The position was saved.'));
                        $this->_redirect("employee/edit/id/$id");
                    }
                }
            } // else we have edit_employee acl
        } // if isPost()

        // this data is used both for populating mechanisms and checking for subpartner permissions to edit employees
        $mechanismData = $this->getAvailableMechanisms();
        $mechanismData['assigned_mechanisms'] = $employeeMechanisms;


        if ($id){
            $select = $db->select()->from('employee')->where('id = ?', $id);
            $row = $db->fetchRow($select);
            if (!$row) {
                $status->setStatusMessage(t('Error finding that record in the database.'));
            } else {
                $params = $row; // reassign form data
            //TA:#224
            $sites_info = Employee::getEmployeeSites($id);
            
                $result_sites = array();
                $helper = new Helper();
                    foreach($sites_info as $i => $loc) {
                        if($sites_info[$i]['facility_id'] && $sites_info[$i]['location_id']){//TA:#393
                        $result_site = $helper->getFacility3TiersLocationsParentInfo($sites_info[$i]['facility_id'], $sites_info[$i]['location_id']);
                        $result_site['site_link_id'] = $sites_info[$i]['site_link_id'];
                        $result_site['hiv_fte_related'] = $sites_info[$i]['hiv_fte_related'];
                        $result_site['non_hiv_fte_related'] = $sites_info[$i]['non_hiv_fte_related'];
                        $result_site['type_option_id'] = $sites_info[$i]['type_option_id'];
                        $result_site['facility_type_phrase'] = $sites_info[$i]['facility_type_phrase'];
                        $result_site['facility_id'] = $sites_info[$i]['facility_id'];
                        $result_site['facility_name'] = $sites_info[$i]['facility_name'];
                        $result_site['sds_model_name'] = $sites_info[$i]['sds_model_name'];//TA:#416
                        $result_site['sds_team_name'] = $sites_info[$i]['sds_team_name'];//TA:#416
                        $result_site['dsd_model_id'] = $sites_info[$i]['dsd_model_id'];//TA:#438
                        $result_site['dsd_team_id'] = $sites_info[$i]['dsd_team_id'];//TA:#438
                        array_push($result_sites,$result_site);
                    }
                    $params['sites'] = $result_sites;
                }
            }
        }

        // assign form drop downs
        $params['dob'] = formhelperdate($params['dob']);
        $params['agreement_start_date'] = formhelperdate($params['agreement_start_date']);//TA:#374
        $params['agreement_end_date'] = formhelperdate($params['agreement_end_date']);
        $params['transition_date'] = formhelperdate($params['transition_date']);
        $params['transition_complete_date'] = formhelperdate($params['transition_complete_date']);
        $params['courses'] = $this->getCourses($id);
        $params['lookup_nationalities_id'] = $params['option_nationality_id'];
        $params['employee_site_type_option_id'] = $params['facility_type_option_id'];
        $params['person_race_option_id'] = $params['race_option_id'];
        $this->viewAssignEscaped('employee', $params);
        $validCHWids = $db->fetchCol("select id from employee_qualification_option qual
										inner join (select id as success from employee_qualification_option where qualification_phrase in ('Community Based Worker','Community Health Worker','NC02 -Community health workers')) parentIDs
										on (parentIDs.success = qual.id)");
        $this->view->assign('validCHWids', $validCHWids);
        $this->view->assign('expandCHWFields', !(array_search($params['employee_qualification_option_id'], $validCHWids) === false)); // i.e $validCHWids.contains($employee[qualification])
        $this->view->assign('status', $status);
        $this->view->assign('pageTitle', $this->view->mode == 'add' ? t('Add') . space . t('Employee') : t('Edit') . space . t('Employee'));
        $this->viewAssignEscaped('locations', Location::getAll());
        $titlesArray = OptionList::suggestionList('person_title_option', 'title_phrase', false, 9999);
        $this->view->assign('titles', DropDown::render('title_option_id', $this->translation['Title'], $titlesArray, 'title_phrase', 'id', $params['title_option_id']));
        $this->view->assign('partners', DropDown::generateHtml('partner', 'partner', $params['partner_id'], false, !$this->hasACL("edit_employee"), array_keys($this->getAvailablePartnersAssoc()), false, array("onchange" => "availableMechanisms();")));
        $this->view->assign('bases', $bases);
        $this->view->assign('cadres', DropDown::generateHtml('employee_qualification_option', 'qualification_phrase', $params['employee_qualification_option_id'], false, !$this->hasACL("edit_employee")));
        $this->view->assign('categories', DropDown::generateHtml('employee_category_option', 'category_phrase', $params['employee_category_option_id'], false, !$this->hasACL("edit_employee"), false));
        $this->view->assign('fulltime', DropDown::generateHtml('employee_fulltime_option', 'fulltime_phrase', $params['employee_fulltime_option_id'], false, !$this->hasACL("edit_employee"), false));
        $this->view->assign('roles', DropDown::generateHtml('employee_role_option', 'role_phrase', $params['employee_role_option_id'], false, !$this->hasACL("edit_employee"), false));
        $this->view->assign('cadres_data',Employee::getAllEmployeeQualifications()); //TA:#329
        $this->view->assign('transitions', DropDown::generateHtml('employee_transition_option', 'transition_phrase', $params['employee_transition_option_id'], false, !$this->hasACL("edit_employee"), false));
        //TA:#422 $this->view->assign('transitions_complete', DropDown::generateHtml('employee_transition_option', 'transition_phrase', $params['employee_transition_complete_option_id'], false, !$this->hasACL("edit_employee"), false, false, array('name' => 'employee_transition_complete_option_id'), true));
        $this->view->assign('transitions_complete', DropDown::generateHtml('employee_transition_complete_option', 'transition_complete_phrase', $params['employee_transition_complete_option_id'], false, !$this->hasACL("edit_employee"), false, false, array('name' => 'employee_transition_complete_option_id'), true));
        $helper = new Helper();
        $this->viewAssignEscaped('facilities', $helper->getFacilities());
        $this->viewAssignEscaped('site_types', $helper->getFacilityTypes()); //TA:#224
        $this->viewAssignEscaped('dsd_model', $helper->getEmployeeDSDModels()); //TA:#416
        $this->viewAssignEscaped('dsd_team', $helper->getEmployeeDSDTeams()); //TA:#416
        $this->view->assign('relationships', DropDown::generateHtml('employee_relationship_option', 'relationship_phrase', $params['employee_relationship_option_id'], false, !$this->hasACL("edit_employee"), false));
        $this->view->assign('referrals', DropDown::generateHtml('employee_referral_option', 'referral_phrase', $params['employee_referral_option_id'], false, !$this->hasACL("edit_employee"), false));
        $this->view->assign('provided', DropDown::generateHtml('employee_training_provided_option', 'training_provided_phrase', $params['employee_training_provided_option_id'], false, !$this->hasACL("edit_employee"), false));
        $employees = OptionList::suggestionList('employee', array('first_name', 'CONCAT(first_name, CONCAT(" ", last_name)) as name'), false, 99999);
        $this->view->assign('supervisors', DropDown::render('supervisor_id', $this->translation['Supervisor'], $employees, 'name', 'id', $params['supervisor_id']));
        $this->view->assign('nationality', DropDown::generateHtml('lookup_nationalities', 'nationality', $params['lookup_nationalities_id'], false, !$this->hasACL("edit_employee"), false));
        $this->view->assign('race', DropDown::generateHtml('person_race_option', 'race_phrase', $params['race_option_id'], false, !$this->hasACL("edit_employee"), false));

        $this->view->assign('mechanismData', $mechanismData);
        $this->view->assign('employeeMechanisms', $employeeMechanisms);
       
        //TA:#256
        $dc = strtotime($params['timestamp_created']);
        $dateCreated = $dc != '' && $dc > 0 ? date("d-m-Y H:i:s",$dc) : t("N/A");
        $this->view->assign('dateCreated', $dateCreated);
        $dm = strtotime($params['timestamp_updated']);
        $dateModified = $dm != '' && $dm >0 ? date("d-m-Y H:i:s",$dm): t("N/A");
        $this->view->assign('dateModified', $dateModified);
        require_once('models/table/User.php');
        $userObj = new User ();
        $created_by = $params['created_by'] ? $userObj->getUserFullName($params['created_by']) : t("N/A");
        $this->viewAssignEscaped('creator', $created_by);
        $update_by = $params['modified_by'] ? $userObj->getUserFullName($params['modified_by']) : t("N/A");
        $this->viewAssignEscaped('updater', $update_by);
    }

    public function searchAction()
    {
        $this->view->assign('pageTitle', 'Search Employees');
        if (!$this->hasACL('employees_module')) {
            $this->doNoAccessError();
        }
        //TA:#421
        if (!$this->hasACL('position_access')) {
            $this->doNoAccessError();
        }

        $db = $this->dbfunc();

        $criteria = $this->getAllParams();

        $locations = Location::getAll();

        if ($criteria['go']) {

            $select = $db->select()
                ->from('employee', array());

            $select->joinLeft('link_employee_facility', 'employee.id = link_employee_facility.employee_id', array());//TA:#224
            $select->joinLeft('facility', 'facility.id = link_employee_facility.facility_id', array());//TA:#224
            $select->joinLeft(array('location' => new Zend_Db_Expr('(' . Location::fluentSubquery() . ')')),
                'location.id = facility.location_id', array());//TA:#224
            $select->joinLeft('partner', 'partner.id = employee.partner_id', array());
            $select->joinLeft('employee_qualification_option',
                'employee_qualification_option.id = employee.employee_qualification_option_id', array());

            $select->columns(array('employee.id', 'employee.employee_code',
                'province_name' => "GROUP_CONCAT(DISTINCT location.province_name ORDER BY location.province_name ASC SEPARATOR ', ')",
                'district_name' => "GROUP_CONCAT(DISTINCT location.district_name ORDER BY location.district_name ASC SEPARATOR ', ')",
                'region_c_name' => "GROUP_CONCAT(DISTINCT location.region_c_name ORDER BY location.region_c_name ASC SEPARATOR ', ')",
                'partner.partner', 'facility.facility_name', 'employee_qualification_option.qualification_phrase'));

            $select->group('employee.id');

            if (!$this->hasACL('training_organizer_option_all')) {
                $user_id = $this->isLoggedIn();

                $select->joinInner('user_to_organizer_access',
                        'user_to_organizer_access.training_organizer_option_id = partner.organizer_option_id', array())
                    ->where('user_to_organizer_access.user_id = ?', $user_id);
            }

            if (isset($criteria['employee_code']) && $criteria['employee_code']) {
                $select->where('employee.employee_code like ?', "%" . $criteria['employee_code'] . "%");//TA:#364 never use %?% it will generate wrong sql string %'1'%
            }
            
            if (isset($criteria['partner_id']) && $criteria['partner_id']) {
                $select->where('employee.partner_id = ?', $criteria['partner_id']);
            }

            if (isset($criteria['facilityInput']) && $criteria['facilityInput']) {
                $select->where('employee.site_id = ?', $criteria['facilityInput']);
            }

            if (isset($criteria['employee_qualification_option_id']) && $criteria['employee_qualification_option_id']) {
                $select->where('employee.employee_qualification_option_id = ?', $criteria['employee_qualification_option_id']);
            }

            if (isset($criteria['category_option_id']) && $criteria['category_option_id']) {
                $select->where('employee.staff_category_id = ?', $criteria['category_option_id']);
            }

            if ($locationWhere = $this->getLocationCriteriaWhereClause($criteria, '', '')) {
                $select->where($locationWhere);
            }

            $this->view->assign('headers', array(t('ID'), t('Employee Code'), t('Region A (Province)'),
                t('Region B (Health District)'), t('Region C (Local Region)'), t('Partner'),
                t('Facility'), t('Staff Cadre')));

            // don't get column names in this fetch so they don't have to be filtered out of json output in the view
            $oldFetchMode = $db->getFetchMode();
            $db->setFetchMode(Zend_Db::FETCH_NUM);
            
            $this->view->assign('output', $db->fetchAll($select));

            $db->setFetchMode($oldFetchMode);
        }

        // assign form drop downs
        $helper = new Helper();

        //TA:#293 set location multiple selection
        $criteria['district_id'] = regionFiltersGetDistrictIDMultiple($criteria);
        $criteria['region_c_id'] = regionFiltersGetLastIDMultiple('', $criteria);
        
        $this->viewAssignEscaped('criteria', $criteria);
        $this->viewAssignEscaped('locations', $locations);
        $this->view->assign('partners', DropDown::generateHtml('partner', 'partner', $criteria['partner_id'], false, $this->view->viewonly, array_keys($this->getAvailablePartnersAssoc())));

        $this->view->assign('cadres', DropDown::generateHtml('employee_qualification_option', 'qualification_phrase', $criteria['employee_qualification_option_id'], false, $this->view->viewonly, false));
        $this->viewAssignEscaped('sites', $helper->getFacilities());
        $this->view->assign('categories', DropDown::generateHtml('employee_category_option', 'category_phrase', $criteria['employee_category_option_id'], false, $this->view->viewonly, false));
    }
}
