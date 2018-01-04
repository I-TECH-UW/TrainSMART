<?php
require_once('ReportFilterHelpers.php');
require_once('models/table/OptionList.php');
require_once('models/table/MultiAssignList.php');
require_once('models/table/MultiOptionList.php');
require_once('models/table/Location.php');
require_once('views/helpers/FormHelper.php');
require_once('views/helpers/DropDown.php');
require_once('views/helpers/Location.php');
require_once('views/helpers/CheckBoxes.php');
require_once('views/helpers/TrainingViewHelper.php');
require_once('models/table/Helper.php');

class PartnerController extends ReportFilterHelpers
{
    public function init()
    {
        $contextSwitch = $this->_helper->getHelper('contextSwitch');

        $contextSwitch->addContext('csv', array(
                'headers' => array('Content-Type' => 'text/csv'),
                'callbacks' => array(
                    'post' => array($this, 'postCsvCallback'),
                    'init' => array($this, 'preCsvCallback')
                )
            )
        );
        $contextSwitch->addActionContext('search', 'csv');
        $contextSwitch->initContext();

    }

    public function preDispatch()
    {
        parent::preDispatch();

        if (!$this->isLoggedIn())
            $this->doNoAccessError();

        if (!$this->setting('module_employee_enabled'))
            $this->_redirect('select/select');
    }

    protected function calculateCurrentQuarter() {
        $now = new DateTime();
        $thisYear = $now->format('Y');
        $quarterStarts = array(DateTime::createFromFormat('Y-m-d', $thisYear . '-01-01'),
            DateTime::createFromFormat('Y-m-d', $thisYear . '-04-01'),
            DateTime::createFromFormat('Y-m-d', $thisYear . '-07-01'),
            DateTime::createFromFormat('Y-m-d', $thisYear . '-10-01'),
        );
        $currentQuarterStartDate = $quarterStarts[0];
        $size = count($quarterStarts);
        for ($i = 0; $i < $size; $i++) {
            if (($i + 1) >= $size) {
                $currentQuarterStartDate = $quarterStarts[$i];
                break;
            }
            if ($quarterStarts[$i] < $now && $quarterStarts[$i + 1] > $now) {
                $currentQuarterStartDate = $quarterStarts[$i];
                break;
            }
        }
        return $currentQuarterStartDate;
    }

    public function indexAction()
    {
        $this->_redirect('partner/search');
    }

    public function deleteAction()
    {
        if (!$this->hasACL('employees_module') || !($this->hasACL("delete_partners"))) {
            $this->doNoAccessError();
        }

        require_once('models/table/Partner.php');
        $status = ValidationContainer::instance();
        $id = $this->getSanParam('id');

        if ($id) {
            $partner = new Partner ();
            $rows = $partner->find($id);
            $row = $rows->current();
            if ($row) {
                $partner->delete('id = ' . $row->id);
            }
            $status->setStatusMessage(t('That partner was deleted.'));
        } else {
            $status->setStatusMessage(t('That partner could not be found.'));
        }

        //validate
        $this->view->assign('status', $status);

    }


    public function addAction()
    {
        $this->view->assign('mode', 'add');
        return $this->editAction();
    }

    public function editAction()
    {
        if (!$this->hasACL('employees_module')) {
            $this->doNoAccessError();
        }

        $db = $this->dbfunc();
        $uid = $this->isLoggedIn();
        $params = $this->getAllParams();
        $id = $params['id'];
        $status = ValidationContainer::instance();

        if (!$uid) {
            $this->doNoAccessError();
        }

        $p = $this->getAvailablePartnersAssoc();

        if (!array_key_exists($id, $p) && !($this->view->mode == 'add')) {
            $this->doNoAccessError();
        }

        if ($this->getRequest()->isPost()) {
            if (!$this->hasACL("edit_partners")) {
                $this->doNoAccessError();
            } else {
                //validate then save
                $status->checkRequired($this, 'partner', t('Partner'));
                $status->checkRequired($this, 'organizer_option_id', t('Organizer'));//TA:#279
                $status->checkRequired($this, 'address1', t('Address 1'));
                $status->checkRequired($this, 'city', t('City'));
                $status->checkRequired($this, 'province_id', t('Region A (Province)'));
                $status->checkRequired($this, 'district_id', t('District'));//TA:#279
                $status->checkRequired($this, 'region_c_id', t('Sub-District'));//TA:#279
                $status->checkRequired($this, 'hr_contact_name', t('HR Contact Person Name'));
                $status->checkRequired($this, 'hr_contact_phone', t('HR Contact Office Phone'));
                $status->checkRequired($this, 'hr_contact_email', t('HR Contact Email'));
                //TA:#279
                if($params['capture_complete']){
                    $status->checkRequired($this, 'capture_complete_date', t('Submission Date')); //TA:#440
                }

                $status->isAcceptableSAPhoneNumber('hr_contact_phone', t('HR Contact Office Phone'), $params['hr_contact_phone']);

                if (isset($params['hr_contact_fax']) && $params['hr_contact_fax']) {
                    $status->isAcceptableSAPhoneNumber('hr_contact_fax', t('HR Contact Office Fax'), $params['hr_contact_fax']);
                }

                if (isset($params['hr_contact_email']) && $params['hr_contact_email'] &&
                    !(strstr($params['hr_contact_email'], '@'))
                ) {
                    $status->addError('hr_contact_email', t('HR Contact Email') . ' ' . t('invalid email address format.'));
                }

                $isValidDate = false;
                if (isset($params['capture_complete']) && $params['capture_complete']) {
                    //TA:#440
                    $isValidDate = $status->isValidDateDDMMYYYY('capture_complete_date', t('Submission Date'), $params['capture_complete_date']);
                    if ($isValidDate) {
                        $d = DateTime::createFromFormat('d/m/Y', $params['capture_complete_date']);
                        $now = new DateTime(null, new DateTimeZone('Africa/Johannesburg'));
                        if ($d > $now) {
                            $isValidDate = false;
                            //TA:#440
                            $status->addError('capture_complete_date', t('Submission Date') . ' must not be in the future.');
                        }
                    }
                }else{//TA:#279 write date as null
                    $params['capture_complete_date'] = null;
                }

                // location save stuff
                $params['location_id'] = regionFiltersGetLastID(null, $params);
                if ($params['city']) {
                    $params['location_id'] = Location::insertIfNotFound($params['city'], $params['location_id'],
                        $this->setting('num_location_tiers'));
                }

                if (!$status->hasError()) {
                    if ($isValidDate) {
                        $captureText = t('Partner Data Capture Complete');
                        $subjectText = $captureText . ': ' . $params['partner'];

                        $captureText .= ".\n\n";
                        $captureText .= t('Partner') . ': ' . $params['partner'] . "\n";
                        $captureText .= t('Date') . ': ' . $params['capture_complete_date'];

                        $d = DateTime::createFromFormat('d/m/Y', $params['capture_complete_date']);
                        $params['capture_complete_date'] = $d->format('Y-m-d');
                        $oldDate = DateTime::createFromFormat('d/m/Y', $params['previous_date']);

                        // only send email update if date has changed
                        if ($oldDate != $d) {
                            try {
                                require_once('Zend/Mail.php');

                                $mail = new Zend_Mail();
                                $mail->setBodyText($captureText);
                                $mail->setFrom(Settings::$EMAIL_ADDRESS, 'SkillSMART Administrator');
                                $mail->addTo('skillsmart@itech-southafrica.org');
                                $mail->setSubject($subjectText);
                                $mail->send();
                            } catch (Exception $e) {
                                $writer = new Zend_Log_Writer_Stream('php://stderr');
                                $logger = new Zend_Log($writer);
                                $logger->info('Email Notification Failure: ' . $e->getMessage());
                            }
                        }

                    }
                    $id = $this->_findOrCreateSaveGeneric('partner', $params);

                    if (!$id) {
                        $status->setStatusMessage(t('That partner could not be saved.'));
                    } else {

                        $status->setStatusMessage(t('The partner was saved.'));
                        $this->_redirect("partner/edit/id/$id");
                    }
                }
            }
        } else if ($id) { // read data from db

            $row = $db->fetchRow($db->select()->from('partner')->where('id = ?', $id));
            if (!$row) {
                $status->setStatusMessage(t('Error finding that record in the database.'));
            } else {
                $params = $row; // reassign form data

                $params['capture_complete'] = 0;
                if (isset($params['capture_complete_date']) && $params['capture_complete_date']) {
                    $d = DateTime::createFromFormat('Y-m-d', $params['capture_complete_date']);
                    $params['capture_complete_date'] = $d->format('d/m/Y');
                    $params['capture_complete'] = 1;
                }

                $region_ids = Location::getCityInfo($params['location_id'], $this->setting('num_location_tiers'));
                $params['city'] = $region_ids[0];
                $region_ids = Location::regionsToHash($region_ids);
                $params = array_merge($params, $region_ids);

                $currentQuarterStartDate = $this->calculateCurrentQuarter();

                $joinClause = $db->quoteInto('link_mechanism_partner.partner_id = subpartner.id AND link_mechanism_partner.partner_id != ?', $id);
                $select = $db->select()
                    ->from('mechanism_option', array())
                    ->joinLeft('partner_funder_option', 'mechanism_option.funder_id = partner_funder_option.id', array())
                    ->joinLeft('link_mechanism_partner', 'link_mechanism_partner.mechanism_option_id = mechanism_option.id', array())
                    ->joinLeft(array('subpartner' => 'partner'), $joinClause, array())
                    ->joinLeft('partner', 'mechanism_option.owner_id = partner.id', array())
                    ->where('mechanism_option.owner_id = ?', $id)
                   //TA:#454 ->where('mechanism_option.end_date >= ?', $currentQuarterStartDate->format('Y-m-d'))
                ->where('mechanism_option.end_date >= ?', '(MAKEDATE(YEAR(NOW()),1) + INTERVAL QUARTER(NOW())-2 QUARTER)')
                    ->group('mechanism_option.id');

                if (!$this->hasACL('training_organizer_option_all')) {
                    $select->joinInner('user_to_organizer_access',
                        'partner.organizer_option_id = user_to_organizer_access.training_organizer_option_id OR ' .
                        'subpartner.organizer_option_id = user_to_organizer_access.training_organizer_option_id', array())
                        ->where('user_to_organizer_access.user_id = ?', $uid);
                }

                $select->columns(array('mechanism_option.id', 'mechanism_option.mechanism_phrase', 'mechanism_option.end_date',
                    'partner_funder_option.funder_phrase', 'partner.partner'));

                $select->columns(array('subpartners' => "GROUP_CONCAT(DISTINCT subpartner.partner ORDER BY subpartner.partner ASC SEPARATOR ',,,')"));

                $primeMechanisms = $db->fetchAssoc($select);
               
                foreach ($primeMechanisms as $partner_id => &$row) {
                    if (strlen($row['subpartners'])) {
                        $row['subpartners'] = explode(',,,', $row['subpartners']);
                    }
                }

                $this->view->assign('primeMechanisms', $primeMechanisms);

                $select = $db->select()
                    ->from('link_mechanism_partner', array())
                    ->joinLeft('mechanism_option', 'link_mechanism_partner.mechanism_option_id = mechanism_option.id', array())
                    ->joinLeft('partner', 'partner.id = mechanism_option.owner_id')
                    ->where('mechanism_option.owner_id != ?', $id)
                    ->where('link_mechanism_partner.partner_id = ?', $id)
                    ->where('link_mechanism_partner.end_date >= ?', $currentQuarterStartDate->format('Y-m-d'));

                $select->columns(array('mechanism_option.mechanism_phrase', 'partner.partner', 'link_mechanism_partner.end_date'));

                $secondaryMechanisms = $db->fetchAll($select);
                $this->view->assign('secondaryMechanisms', $secondaryMechanisms);
            }
        }

        if (!$this->hasACL("edit_partners")) {
            $this->view->viewonly = true;
        }
        // assign form drop downs
        $this->view->assign('required_fields', array('partner', 'address1', 'city', 'province_id', 'hr_contact_name', 'hr_contact_phone', 'hr_contact_email', 'capture_complete_date'));
        $this->view->assign('status', $status);
        $this->view->assign('pageTitle', $this->view->mode == 'add' ? t('Add Partner') : t('View Partner'));
        $this->viewAssignEscaped('partner', $params);
        $this->viewAssignEscaped('locations', Location::getAll());
        $this->view->assign('organizers', DropDown::generateHtml('training_organizer_option', 'training_organizer_phrase', $params['organizer_option_id'], false, $this->view->viewonly, false, true, array('name' => 'organizer_option_id'), true));
    }

    public function searchAction()
    {
        if (!$this->hasACL('employees_module')) {
            $this->doNoAccessError();
        }

        $criteria = $this->getAllParams();
        $status = ValidationContainer::instance();
        $locations = Location::getAll(); // used in view also
        $db = $this->dbfunc();

        if ($criteria['go']) {

            // a less hacky version of this should probably be a helper function
            $tier_id = null;
            $location_id = null;
            if (isset($criteria['region_c_id']) && $criteria['region_c_id']) {
                $tmp = explode('_', $criteria['region_c_id']);
                $location_id = array_pop($tmp);
                $tier_id = 'region_c_id';
            }
            else if (isset($criteria['district_id']) && $criteria['district_id']) {
                $tmp = explode('_', $criteria['district_id']);
                $location_id = array_pop($tmp);
                $tier_id = 'district_id';

            }
            else if (isset($criteria['province_id']) && $criteria['province_id']) {
                $location_id = $criteria['province_id'];
                $tier_id = 'province_id';
            }

            $currentQuarterStartDate = $this->calculateCurrentQuarter();

            $select = $db->select()
                ->from('partner', array('id', 'partner'))
                ->joinLeft('mechanism_option', 'partner.id = mechanism_option.owner_id and mechanism_option.end_date >= ' . $currentQuarterStartDate->format('Y-m-d'), array())
                ->joinLeft('link_mechanism_partner', 'link_mechanism_partner.mechanism_option_id = mechanism_option.id', array())
                ->joinLeft(array('subpartner' => 'partner'), 'link_mechanism_partner.partner_id = subpartner.id AND link_mechanism_partner.partner_id <> mechanism_option.owner_id',
                    array())
                ->joinLeft('partner_funder_option', 'partner_funder_option.id = mechanism_option.funder_id', array())
                ->joinLeft(array('location' => new Zend_Db_Expr('(' . Location::fluentSubquery() . ')')), 'location.id = partner.location_id', array())
                ->group('partner.id');

            if (!$this->hasACL('training_organizer_option_all')) {
                $uid = $this->isLoggedIn();
                $select->joinInner('user_to_organizer_access',
                    'partner.organizer_option_id = user_to_organizer_access.training_organizer_option_id OR ' .
                    'subpartner.organizer_option_id = user_to_organizer_access.training_organizer_option_id', array())
                    ->where('user_to_organizer_access.user_id = ?', $uid);
            }

            $select->columns(array('location.province_name', 'location.district_name', 'location.region_c_name'));
            $select->columns(array('subpartners' => "GROUP_CONCAT(DISTINCT subpartner.partner ORDER BY subpartner.partner ASC SEPARATOR ', ')"));

            // selecting the funder phrase and end date with the mechanism so the output matches in each column
            $select->columns(array('mechanism_info' => "GROUP_CONCAT(DISTINCT mechanism_phrase, ',,,', funder_phrase, ',,,', mechanism_option.end_date ORDER BY mechanism_phrase ASC SEPARATOR ',,,')"));

            if ($location_id) {
                $select->where($tier_id . ' = ?', $location_id);
            }

            if (isset($criteria['partner_id']) && $criteria['partner_id']) {
                $select->where('partner.id = ?', $criteria['partner_id']);
            }

            //TA:#440
            if (isset($criteria['funding_start_date']) &&
                $status->isValidDateDDMMYYYY('funding_start_date', t('Submission Date'), $criteria['funding_start_date'])) {
                $d = DateTime::createFromFormat('d/m/Y', $criteria['funding_start_date']);
                $select->where('mechanism_option.end_date >= ?', $d->format('Y-m-d'));
            }
            //TA:#440
            if (isset($criteria['funding_end_date']) &&
                $status->isValidDateDDMMYYYY('funding_end_date', t('Submission Date'), $criteria['funding_end_date'])) {
                $d = DateTime::createFromFormat('d/m/Y', $criteria['funding_end_date']);
                $select->where('mechanism_option.end_date <= ?', $d->format('Y-m-d'));
            }

            $complete_start = null;
            //TA:#440
            if (isset($criteria['capture_complete_start_date']) && $status->isValidDateDDMMYYYY('capture_complete_start_date', t('Submission Date'), $criteria['capture_complete_start_date'])) {
                $complete_start = DateTime::createFromFormat('d/m/Y', $criteria['capture_complete_start_date']);
            }
            $complete_end = null;
            //TA:#440
            if (isset($criteria['capture_complete_end_date']) && $status->isValidDateDDMMYYYY('capture_complete_end_date', t('Submission Date'), $criteria['capture_complete_end_date'])) {
                $complete_end = DateTime::createFromFormat('d/m/Y', $criteria['capture_complete_end_date']);
            }

            if (isset($criteria['show_complete_captures']) && $criteria['show_complete_captures']) {
                //TA:#440
                if (!$complete_start && !$complete_end) {
                    $status->addError('capture_complete_start_date', t('Submission Date') . ' ' . t('is required.'));
                }
                if ($complete_start) {
                    $select->where('partner.capture_complete_date >= ?', $complete_start->format('Y-m-d'));
                }
                if ($complete_end) {
                    $select->where('partner.capture_complete_date <= ?', $complete_end->format('Y-m-d'));
                }
            }
            else {
                $completeclause = null;
                if ($complete_start && $complete_end) {
                    $completeclause = $db->quoteInto('((partner.capture_complete_date >= ? AND ', $complete_start->format('Y-m-d'));
                    $completeclause .= $db->quoteInto('partner.capture_complete_date <= ?) OR partner.capture_complete_date IS NULL)', $complete_end->format('Y-m-d'));
                }
                else if ($complete_start) {
                    $completeclause = $db->quoteInto('((partner.capture_complete_date >= ?) OR partner.capture_complete_date IS NULL)', $complete_start->format('Y-m-d'));
                }
                else if ($complete_end) {
                    $completeclause = $db->quoteInto('((partner.capture_complete_date <= ?) OR partner.capture_comlplete_date IS NULL)', $complete_end->format('Y-m-d'));
                }
                if ($completeclause !== null) {
                    $select->where($completeclause);
                }
            }

            if (!$status->hasError()) {
                $headers = array(t('ID'), t('Partner') . ' ' . t('Name'), t('Region A (Province)'),
                    t('Region B (Health District)'), t('Region C (Local Region)'), t('Sub Partner'),
                    t('Funder'), t('Mechanism'), t('Funder End Date'));
                $output = $db->fetchAll($select);

                // post-process the mechanism_info column into 3 columns
                foreach ($output as &$r) {
                    $mechanism_info = explode(',,,', $r['mechanism_info']);
                    $numitems = count($mechanism_info);
                    unset($r['mechanism_info']);
                    for ($i = 0; $i < $numitems; $i += 3) {
                        $r['funder_phrase'] .= $mechanism_info[$i + 1] . ', ';
                        $r['mechanisms'] .= $mechanism_info[$i] . ', ';
                        $r['end_date'] .= $mechanism_info[$i + 2] . ', ';
                    }
                    $r['funder_phrase'] = trim($r['funder_phrase'], ', ');
                    $r['mechanisms'] = trim($r['mechanisms'], ', ');
                    $r['end_date'] = trim($r['end_date'], ', ');

                }
                $this->viewAssignEscaped('output', $output);
                $this->viewAssignEscaped('headers', $headers);
            }
        }

        $this->viewAssignEscaped('criteria', $criteria);
        $this->viewAssignEscaped('locations', $locations);
        $this->view->assign('partners', DropDown::generateHtml('partner', 'partner', $criteria['partner_id'], false, $this->view->viewonly, $this->getAvailablePartners()));
    }
}

