<?php
require_once ('ReportFilterHelpers.php');
require_once ('models/table/OptionList.php');
require_once ('models/table/MultiAssignList.php');
require_once ('models/table/MultiOptionList.php');
require_once ('models/table/Location.php');
require_once ('views/helpers/FormHelper.php');
require_once ('views/helpers/DropDown.php');
require_once ('views/helpers/Location.php');
require_once ('views/helpers/CheckBoxes.php');
require_once ('views/helpers/TrainingViewHelper.php');
require_once ('models/table/Helper.php');

class PartnerController extends ReportFilterHelpers {
	public function init() {	}

	public function preDispatch() {
		parent::preDispatch ();

		if (! $this->isLoggedIn ())
			$this->doNoAccessError ();

		if (! $this->setting('module_employee_enabled'))
			$this->_redirect('select/select');
	}

	public function indexAction()
	{
		$this->_redirect('partner/search');
	}

	public function deleteAction() {
		if (! $this->hasACL ( 'employees_module' ) || !($this->hasACL("delete_partners"))) {
			$this->doNoAccessError ();
		}

		require_once('models/table/Partner.php');
		$status = ValidationContainer::instance ();
		$id = $this->getSanParam ( 'id' );

		if ($id) {
			$partner = new Partner ( );
			$rows = $partner->find ( $id );
			$row = $rows->current ();
			if ($row) {
				$partner->delete ( 'id = ' . $row->id );
			}
			$status->setStatusMessage ( t ( 'That partner was deleted.' ) );
		} else {
			$status->setStatusMessage ( t ( 'That partner could not be found.' ) );
		}

		//validate
		$this->view->assign ( 'status', $status );

	}


	public function addAction() {
		$this->view->assign ( 'mode', 'add' );
		return $this->editAction ();
	}

	public function editAction() {
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

        if (!array_key_exists($id, $p)) {
            $this->doNoAccessError();
        }

		if ($this->getRequest()->isPost())
		{
		    if (!$this->hasACL("edit_partners"))
		    {
		        $this->doNoAccessError();
		    }
		    else {
                //validate then save
                $status->checkRequired($this, 'partner', t('Partner'));
                $status->checkRequired($this, 'address1', t('Address 1'));
                $status->checkRequired($this, 'city', t('City'));
                $status->checkRequired($this, 'province_id', t('Region A (Province)'));
                $status->checkRequired($this, 'hr_contact_name', t('HR Contact Person Name'));
                $status->checkRequired($this, 'hr_contact_phone', t('HR Contact Office Phone'));
                $status->checkRequired($this, 'hr_contact_email', t('HR Contact Email'));

                $isValid = $status->isValidDateDDMMYYYY('capture_complete_date', t('Data Capture Completion Date'), $params['capture_complete_date']);
                if ($isValid) {
                    $d = DateTime::createFromFormat('d/m/Y', $params['capture_complete_date']);
                    $params['capture_complete_date'] = $d->format('Y-m-d');
                }

    			// location save stuff
    			$params['location_id'] = regionFiltersGetLastID(null, $params);
    			if ($params['city']) {
    				$params['location_id'] = Location::insertIfNotFound($params['city'], $params['location_id'],
                        $this->setting('num_location_tiers'));
    			}
    
    			if (!$status->hasError()) {
    				$id = $this->_findOrCreateSaveGeneric('partner', $params);
    
    				if(!$id) {
    					$status->setStatusMessage( t('That partner could not be saved.') );
    				} else {

    					$status->setStatusMessage( t('The partner was saved.') );
    					$this->_redirect("partner/edit/id/$id");
    				}
    			}		
		    }
		}
		
		else if ($id) { // read data from db

            $row = $db->fetchRow($db->select()->from('partner')->where('id = ?', $id));
			if (! $row) {
                $status->setStatusMessage(t('Error finding that record in the database.'));
            }
			else {
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

                $joinClause = $db->quoteInto('link_mechanism_partner.partner_id = subpartner.id AND link_mechanism_partner.partner_id != ?', $id);
                $select = $db->select()
                    ->from('mechanism_option', array('id', 'mechanism_phrase', 'end_date'))
                    ->joinInner('partner_funder_option', 'mechanism_option.funder_id = partner_funder_option.id', array('partner_funder_option.funder_phrase'))
                    ->joinInner('link_mechanism_partner', 'link_mechanism_partner.mechanism_option_id = mechanism_option.id', array())
                    ->joinLeft(array('subpartner' => 'partner'), $joinClause, array('subpartner' => 'subpartner.partner'))
                    ->joinInner('partner', 'mechanism_option.owner_id = partner.id', array('partner'))
                    ->where('mechanism_option.owner_id = ?', $id);

                if (!$this->hasACL('training_organizer_option_all')) {
                    $select->joinInner('user_to_organizer_access',
                        'partner.organizer_option_id = user_to_organizer_access.training_organizer_option_id', array())
                        ->where('user_to_organizer_access.user_id = ?', $uid);
                    $select->joinInner(array('user_to_organizer_access2' => 'user_to_organizer_access'),
                        'subpartner.organizer_option_id = user_to_organizer_access2.training_organizer_option_id', array())
                        ->where('user_to_organizer_access2.user_id = ?', $uid);
                }

                $rows = $db->fetchAll($select);

                $primeMechanisms = array();
                foreach ($rows as $r) {
                    if (!array_key_exists($r['id'], $primeMechanisms)) {
                        $primeMechanisms[$r['id']] = $r;
                        $primeMechanisms[$r['id']]['subpartners'] = array();
                    }
                    if ($r['subpartner']) {
                        $primeMechanisms[$r['id']]['subpartners'][] = $r['subpartner'];
                    }
                }

                $this->view->assign('primeMechanisms', $primeMechanisms);

                $select = $db->select()
                    ->from('link_mechanism_partner', array('link_mechanism_partner.end_date'))
                    ->joinInner('mechanism_option', 'link_mechanism_partner.mechanism_option_id = mechanism_option.id', array('mechanism_phrase'))
                    ->joinInner('partner', 'mechanism_option.owner_id = partner.id', array('partner.partner'))
                    ->where('owner_id != ?', $id)
                    ->where('partner_id = ?', $id);

                if (!$this->hasACL('training_organizer_option_all')) {
                    $select->joinInner('user_to_organizer_access',
                        'partner.organizer_option_id = user_to_organizer_access.training_organizer_option_id', array())
                        ->where('user_to_organizer_access.user_id = ?', $uid);
                }

                $secondaryMechanisms = $db->fetchAll($select);
                $this->view->assign('secondaryMechanisms', $secondaryMechanisms);
			}
		}

        if (!$this->hasACL("edit_partners"))
        {
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
					partner.id, partner.partner, partner.location_id, ".implode(',',$locationFlds)."
					,GROUP_CONCAT(sub.partner) as subPartner
					,GROUP_CONCAT(pfo.funder_phrase) as partnerFunder
					,GROUP_CONCAT(mo.mechanism_phrase) as mechanism
					,GROUP_CONCAT(mo.end_date) as funding_end_date
					FROM partner 
					LEFT JOIN ($locationsubquery) as l  ON l.id = partner.location_id
					LEFT JOIN link_mechanism_partner    ON partner.id = link_mechanism_partner.partner_id
					LEFT JOIN mechanism_option mo        		ON link_mechanism_partner.mechanism_option_id = mo.id
					LEFT JOIN partner_funder_option pfo         ON mo.funder_id = pfo.id
					LEFT JOIN partner sub                       ON sub.id = link_mechanism_partner.partner_id
					LEFT JOIN location parent_loc               ON parent_loc.id = partner.location_id";

			// restricted access?? only show partners by organizers that we have the ACL to view
			$org_allowed_ids = allowed_org_access_full_list($this); // doesnt have acl 'training_organizer_option_all'
			if($org_allowed_ids) {
				$where[] = " partner.organizer_option_id in ($org_allowed_ids) ";
			}
			// restricted access?? only show organizers that belong to this site if its a multi org site
			$site_orgs = allowed_organizer_in_this_site($this); // for sites to host multiple training organizers on one domain
			if ($site_orgs) {
				$where[] = " partner.organizer_option_id in ($site_orgs) ";
			}
			if ($locationWhere = $this->getLocationCriteriaWhereClause($criteria, '', '')) {
				$where[] = "($locationWhere OR parent_loc.parent_id = $location_id)"; #todo the subquery and parent_id is not working
			}

			if ($criteria['subpartner_id'])     $where[] = 'subpartners.subpartner_id = '.$criteria['subpartner_id'];
			if ($criteria['partner_id'])        $where[] = 'partner.id = '.$criteria['partner_id'];
			if ($criteria['start_date'])        $where[] = 'mo.end_date >= \''.$this->_euro_date_to_sql( $criteria['start_date'] ) .' 00:00:00\'';
			if ($criteria['end_date'])          $where[] = 'mo.end_date <= \''.$this->_euro_date_to_sql( $criteria['end_date'] ) .' 23:59:59\'';
			if ( count ($where)) {
				$sql .= ' WHERE ' . implode(' AND ', $where);
			}
			    
			$sql .= ' GROUP BY partner.id ';

			$db = $this->dbfunc();
			$rows = $db->fetchAll( $sql );

			$locations = Location::getAll (); // used in view also
			// hack #TODO - seems Region A -> ASDF, Region B-> *Multiple Province*, Region C->null Will not produce valid locations with Location::subquery
			foreach ($rows as $i => $row) {
				if ($row['province_id'] == ""){ // empty province
					$updatedRegions = Location::getCityandParentNames($row['location_id'], $locations, $this->setting('num_location_tiers'));
					$rows[$i] = array_merge($row, $updatedRegions);
				}
			}

			$this->viewAssignEscaped('results', $rows);
			$this->view->assign ('count', count($rows) );

			if ($criteria ['outputType']) {
				foreach($rows as $i => $row) {
					unset($rows[$i]['city_id']);
					unset($rows[$i]['location_id']);
					unset($rows[$i]['province_id']);
					unset($rows[$i]['district_id']);
					unset($rows[$i]['region_c_id']);
					unset($rows[$i]['region_d_id']);
					unset($rows[$i]['region_e_id']);
					unset($rows[$i]['region_f_id']);
					unset($rows[$i]['region_g_id']);
					unset($rows[$i]['region_h_id']);
					unset($rows[$i]['region_i_id']);
				}
				$this->sendData ( $this->reportHeaders ( false, $rows ) );
			}
		}
		// assign form drop downs
		$this->viewAssignEscaped ( 'criteria', $criteria );
		$this->viewAssignEscaped ( 'locations', $locations );
		$this->view->assign ( 'partners', DropDown::generateHtml ( 'partner', 'partner', $criteria['partner_id'], false, $this->view->viewonly, $this->getAvailablePartners() ) );
	}
}
