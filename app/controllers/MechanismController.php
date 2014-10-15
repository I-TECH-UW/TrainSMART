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

class MechanismController extends ReportFilterHelpers {
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
	
	public function deleteFunderAction() {
		if (! $this->hasACL ( 'employees_module' )) {
			$this->doNoAccessError ();
		}
	
		require_once('models/table/Partner.php');
		require_once('views/helpers/Location.php'); // funder stuff
	
		$db     = $this->dbfunc();
		$status = ValidationContainer::instance ();
		$params = $this->getAllParams();
		
		//file_put_contents('c:\wamp\logs\php_debug.log', 'mechCont 44>'.PHP_EOL, FILE_APPEND | LOCK_EX);	ob_start();
		//var_dump($params);
		//$result = ob_get_clean(); file_put_contents('c:\wamp\logs\php_debug.log', $result .PHP_EOL, FILE_APPEND | LOCK_EX);
			
		if ($params['id']) {
			$recArr = explode('_', $params['id']);

			//find in psfm, should not find to delete
			$sql = 'SELECT * FROM partner_to_subpartner_to_funder_to_mechanism  WHERE '; // .$id.space.$orgWhere;
			$where = "subpartner_id = $recArr[0] and partner_funder_option_id = $recArr[1] and mechanism_option_id = $recArr[2] and is_deleted = false";
			$sql .= $where;
				
			$row = $db->fetchRow( $sql );
			if ($row){
				$status->setStatusMessage ( t('That record is in use.') );
				//file_put_contents('c:\wamp\logs\php_debug.log', 'That record is in use.'.PHP_EOL, FILE_APPEND | LOCK_EX);
			}
			else { // not in use
	
				//find in psfm, should find to delete
				$sql = 'SELECT * FROM subpartner_to_funder_to_mechanism  WHERE '; // .$id.space.$orgWhere;
				$where = "subpartner_id = $recArr[0] and partner_funder_option_id = $recArr[1] and mechanism_option_id = $recArr[2] and is_deleted = false";
				$sql .= $where;
					
				$row = $db->fetchRow( $sql );
				if (! $row){
					$status->setStatusMessage ( t('Cannot find that record in the database.') );
					//file_put_contents('c:\wamp\logs\php_debug.log', 'That record could not be found.'.PHP_EOL, FILE_APPEND | LOCK_EX);
				}
					
				else { // found, safe to delete
	
					//file_put_contents('c:\wamp\logs\php_debug.log', 'Ready to delete '.$row['id'].PHP_EOL, FILE_APPEND | LOCK_EX);
					$update_result = $db->update('subpartner_to_funder_to_mechanism', array('is_deleted' => 1), 'id = '.$row['id']);
					var_dump($update_result);
	
					if($update_result){
						$status->setStatusMessage ( t ( 'That mechanism was deleted.' ) );
						//file_put_contents('c:\wamp\logs\php_debug.log', 'That record was deleted.'.PHP_EOL, FILE_APPEND | LOCK_EX);
					}
					else{
						$status->setStatusMessage ( t ( 'That mechanism was not deleted.' ) );
						//file_put_contents('c:\wamp\logs\php_debug.log', 'That record was not deleted.'.PHP_EOL, FILE_APPEND | LOCK_EX);
					}
				}
			}
		}
		$this->_redirect("mechanism/edit/id/" . 0);
	}

	public function addAction() {
		$this->view->assign ( 'mode', 'add' );
		return $this->editAction ();
	}

	public function editAction() {

		$db     = $this->dbfunc();
		$status = ValidationContainer::instance ();
		$params = $this->getAllParams();
		$id     = $params['id'];
		
		//file_put_contents('c:\wamp\logs\php_debug.log', 'mechCont 106> isPost'.PHP_EOL, FILE_APPEND | LOCK_EX);	ob_start();
		//var_dump($params);
		//$result = ob_get_clean(); file_put_contents('c:\wamp\logs\php_debug.log', $result .PHP_EOL, FILE_APPEND | LOCK_EX);

		if ( $this->getRequest()->isPost() )
		{
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
      		  $this->_redirect("admin/employee-build_funding");
	        }
		}
		
		$helper = new Helper();
		
		$sfm = $helper->getSfm($id);
		$this->viewAssignEscaped ( 'sfm', $sfm );
		
		if($sfm) {
		  $subPartner = $helper->getSubPartner($sfm[0]['subpartner_id']);
		  $subPartner[0]['subpartner_id'] = $sfm[0]['subpartner_id'];
		  $this->viewAssignEscaped ( 'subPartner', $subPartner );
		  
		  $partnerFunder = $helper->getFunder($sfm[0]['partner_funder_option_id']);
		  $partnerFunder[0]['subpartner_id'] = $sfm[0]['subpartner_id'];
		  $partnerFunder[0]['partner_funder_option_id'] = $sfm[0]['partner_funder_option_id'];
		  $this->viewAssignEscaped ( 'partnerFunder', $partnerFunder );
		  
		  $mechanism = $helper->getMechanism($sfm[0]['mechanism_option_id']);
		  $mechanism[0]['subpartner_id'] = $sfm[0]['subpartner_id'];
		  $mechanism[0]['partner_funder_option_id'] = $sfm[0]['partner_funder_option_id'];
		  $mechanism[0]['mechanism_option_id'] = $sfm[0]['mechanism_option_id'];
		  $this->viewAssignEscaped ( 'mechanism', $mechanism );
		}
		
		
		
		//file_put_contents('c:\wamp\logs\php_debug.log', 'mechCont 130>'.PHP_EOL, FILE_APPEND | LOCK_EX);	ob_start();
		//var_dump($id);
		//var_dump($sfm);
		//var_dump($subPartner);
		//$result = ob_get_clean(); file_put_contents('c:\wamp\logs\php_debug.log', $result .PHP_EOL, FILE_APPEND | LOCK_EX);
		
	
		/*
		$subPartner = $helper->getAllSubPartners();
		$this->viewAssignEscaped ( 'subPartner', $subPartner );
		
		$partnerFunder = $helper->getAllFunders();
		$this->viewAssignEscaped ( 'partnerFunder', $partnerFunder );
		
		$mechanism = $helper->getAllMechanisms();
		$this->viewAssignEscaped ( 'mechanism', $mechanism );
		*/
		
		//file_put_contents('c:\wamp\logs\php_debug.log', 'mechCont 140>'.PHP_EOL, FILE_APPEND | LOCK_EX);	ob_start();
		//var_dump($subPartner);
		//$result = ob_get_clean(); file_put_contents('c:\wamp\logs\php_debug.log', $result .PHP_EOL, FILE_APPEND | LOCK_EX);
		
			
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
				#$where[] = $locationWhere;
				$where[] = "($locationWhere OR parent_loc.parent_id = $location_id)"; #todo the subquery and parent_id is not working
			}

#			if ($location_id && $alsoCheckMultiRegions = Location::southAfrica_get_multi_region($location_id)) //#SAONLY - check if they are using the *Multiple Regions* items
#				$where[] = " partner.location_id in ($alsoCheckMultiRegions)";

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