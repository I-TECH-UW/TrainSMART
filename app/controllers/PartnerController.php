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
		if (! $this->hasACL ( 'employees_module' )) {
			$this->doNoAccessError ();
		}

		$db     = $this->dbfunc();
		$status = ValidationContainer::instance ();
		$params = $this->getAllParams();
		$id     = $params['id'];

		// restricted access?? only show partners by organizers that we have the ACL to view // - removed 5/1/13, they dont want this, its used by site-rollup (datashare), and user-restrict by org.
		$org_allowed_ids = allowed_org_access_full_list($this); // doesnt have acl 'training_organizer_option_all'
		$site_orgs = allowed_organizer_in_this_site($this); // for sites to host multiple training organizers on one domain
		$siteOrgsClause = $site_orgs ? " AND partner.organizer_option_id IN ($site_orgs)" : "";
		if ($org_allowed_ids && $this->view->mode != 'add') {
			$validID = $db->fetchCol("SELECT partner.id FROM partner WHERE partner.id = $id AND partner.organizer_option_id in ($org_allowed_ids) $siteOrgsClause");
			if(empty($validID))
				$this->doNoAccessError ();
		}

		if ( $this->getRequest()->isPost() )
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


    			//location save stuff
    			$params['location_id'] = regionFiltersGetLastID(null, $params); // formprefix, criteria
    			if ( $params['city'] ) {
    				$params['location_id'] = Location::insertIfNotFound ( $params['city'], $params['location_id'], $this->setting ( 'num_location_tiers' ) );
    			}
    
    			if (! $status->hasError() ) {
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
		
		if ($id && ! $status->hasError()) { // read data from db

			// restricted access?? only show partners by organizers that we have the ACL to view
			$org_allowed_ids = allowed_org_access_full_list($this); // doesnt have acl 'training_organizer_option_all'
			$orgWhere = ($org_allowed_ids) ? " AND partner.organizer_option_id in ($org_allowed_ids) " : "";
			// restricted access?? only show organizers that belong to this site if its a multi org site
			$site_orgs = allowed_organizer_in_this_site($this); // for sites to host multiple training organizers on one domain
			$allowedWhereClause = $site_orgs ? " AND partner.organizer_option_id in ($site_orgs) " : "";

			// continue reading data
			$sql = 'SELECT * FROM partner WHERE id = '.$id.space.$orgWhere;
			$row = $db->fetchRow( $sql );
			if (! $row)
				$status->setStatusMessage ( t('Error finding that record in the database.') );
			else
			{
				$params = $row; // reassign form data

				$region_ids = Location::getCityInfo($params['location_id'], $this->setting('num_location_tiers'));
				$params['city'] = $region_ids[0];
				$region_ids = Location::regionsToHash($region_ids);
				$params = array_merge($params, $region_ids);

                require_once 'views/helpers/EditTableHelper.php';

                $sql = "SELECT mechanism_option.id, mechanism_option.mechanism_phrase, mechanism_option.end_date, partner_funder_option.funder_phrase
                        FROM mechanism_option
                        INNER JOIN partner_funder_option ON mechanism_option.funder_id = partner_funder_option.id
                        WHERE mechanism_option.owner_id = $id";
                $primeMechanisms = $db->fetchAll($sql);

                $this->view->assign('primeMechanisms', $primeMechanisms);

                $sql = "SELECT link_mechanism_partner.id, mechanism_option.mechanism_phrase, partner_funder_option.funder_phrase, link_mechanism_partner.end_date
                        FROM link_mechanism_partner
                        INNER JOIN mechanism_option ON link_mechanism_partner.mechanism_option_id = mechanism_option.id
                        INNER JOIN partner_funder_option ON mechanism_option.funder_id = partner_funder_option.id
                        WHERE owner_id != $id AND partner_id = $id";

                $secondaryMechanisms = $db->fetchAll($sql);
                $this->view->assign('secondaryMechanisms', $secondaryMechanisms);
			}
		}

		// make sure form data is valid for display
		if (empty($params['subpartner']))
			$params['subpartner'] = array(array());
		if (empty($params['funder']))
			$params['funder'] = array(array());
		if (empty($params['mechanism_option_id']))
			$params['mechanism_option_id'] = array(array());
		
        if (!$this->hasACL("edit_partners"))
        {
            $this->view->viewonly = true;
        }
		// assign form drop downs
		$this->view->assign( 'status', $status );
		$this->view->assign ( 'pageTitle', $this->view->mode == 'add' ? t ( 'Add Partner' ) : t( 'View Partner' ) );
		$this->viewAssignEscaped ( 'partner', $params );
		$this->viewAssignEscaped ( 'locations', Location::getAll () );
		$this->view->assign ( 'partners',    DropDown::generateHtml ( 'partner', 'partner', $params['partner_type_option_id'], false, $this->view->viewonly, false ) ); //table, col, selected_value
		$this->view->assign ( 'subpartners', DropDown::generateHtml ( 'partner', 'partner', 0, false, $this->view->viewonly, false, true, array('name' => 'subpartner_id[]'), true ) );
		$this->view->assign ( 'types',       DropDown::generateHtml ( 'partner_type_option', 'type_phrase', $params['partner_type_option_id'], false, $this->view->viewonly, false ) );
		$this->view->assign ( 'importance',  DropDown::generateHtml ( 'partner_importance_option', 'importance_phrase', $params['partner_importance_option_id'], false, $this->view->viewonly, false ) );
		$this->view->assign ( 'transitions', DropDown::generateHtml ( 'employee_transition_option', 'transition_phrase', $params['employee_transition_option_id'], false, $this->view->viewonly, false ) );
		$this->view->assign ( 'incomingPartners', DropDown::generateHtml ( 'partner', 'partner', $params['incoming_partner'], false, $this->view->viewonly, false, true, array('name' => 'incoming_partner'), true ) );
		$this->view->assign ( 'organizers',  DropDown::generateHtml ( 'training_organizer_option', 'training_organizer_phrase', $params['organizer_option_id'], false, $this->view->viewonly, false, true, array('name' => 'organizer_option_id'), true ) );
		$helper = new Helper();
		$this->viewAssignEscaped ( 'facilities', $helper->getFacilities() );
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
					,GROUP_CONCAT(psfm.funding_end_date) as funding_end_date
					FROM partner 
					LEFT JOIN ($locationsubquery) as l  ON l.id = partner.location_id
					LEFT JOIN partner_to_subpartner_to_funder_to_mechanism psfm  ON partner.id = psfm.partner_id
					LEFT JOIN partner_funder_option pfo         ON psfm.partner_funder_option_id = pfo.id
					LEFT JOIN mechanism_option mo        		ON psfm.mechanism_option_id = mo.id
					LEFT JOIN partner sub                       ON sub.id = psfm.subpartner_id 
					LEFT JOIN location parent_loc               ON parent_loc.id = partner.location_id";

			// restricted access?? only show partners by organizers that we have the ACL to view
			$org_allowed_ids = allowed_org_access_full_list($this); // doesnt have acl 'training_organizer_option_all'
			if($org_allowed_ids)
				$where[] = " partner.organizer_option_id in ($org_allowed_ids) ";
			// restricted access?? only show organizers that belong to this site if its a multi org site
			$site_orgs = allowed_organizer_in_this_site($this); // for sites to host multiple training organizers on one domain
			if ($site_orgs)
				$where[] = " partner.organizer_option_id in ($site_orgs) ";

			if ($locationWhere = $this->getLocationCriteriaWhereClause($criteria, '', '')) {
				$where[] = "($locationWhere OR parent_loc.parent_id = $location_id)"; #todo the subquery and parent_id is not working
			}

			if ($criteria['subpartner_id'])     $where[] = 'subpartners.subpartner_id = '.$criteria['subpartner_id'];
			if ($criteria['partner_id'])        $where[] = 'partner.id = '.$criteria['partner_id'];
			if ($criteria['start_date'])        $where[] = 'funding_end_date >= \''.$this->_euro_date_to_sql( $criteria['start_date'] ) .' 00:00:00\'';
			if ($criteria['end_date'])          $where[] = 'funding_end_date <= \''.$this->_euro_date_to_sql( $criteria['end_date'] ) .' 23:59:59\'';
			if ( count ($where))
  			  $sql .= ' WHERE ' . implode(' AND ', $where);
			
			    
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
		$this->view->assign('status', $status);
		$this->viewAssignEscaped ( 'criteria', $criteria );
		$this->viewAssignEscaped ( 'locations', $locations );
		$this->view->assign ( 'partners',    DropDown::generateHtml ( 'partner', 'partner', $criteria['partner_id'], false, $this->view->viewonly, false ) );
		$this->view->assign ( 'subpartners', DropDown::generateHtml ( 'partner', 'partner', $criteria['subpartner_id'], false, $this->view->viewonly, false, true, array('name' => 'subpartner_id'), true ) );
	}
}

?>