<?php
/*
 * Created on sep 4, 2012
 *
 *  TrainingPartnerController.php
 *  
 * note: acl requirements are kind of messed up you only need edit_course to do most of this stuff,
 * and training_organizer acl also.. not sure if we can link the two together, imo its fine
 */
require_once ('ReportFilterHelpers.php');
require_once ('models/table/TrainingPartner.php');
require_once ('models/ValidationContainer.php');
require_once ('models/table/OptionList.php');
require_once ('views/helpers/DropDown.php');

class TrainingPartnerController extends ReportFilterHelpers {
	
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
		$this->_redirect ( 'training-partner/search' );
	}


	function searchAction() {
		$criteria = array ();
		$criteria ['outputType'] = $this->getSanParam ( 'outputType' );
		$criteria ['partner_name'] = $this->getSanParam ( 'partner_name' );
		$criteria ['partner_funder'] = $this->getSanParam ( 'partner_funder' );
		$criteria ['limit'] = $this->getSanParam ( 'limit' );
		$criteria ['go'] = t('Preview');
		$this->viewAssignEscaped ( 'criteria', $criteria );

		$status = $status = ValidationContainer::instance ();

		// fill the form w/ data
		$db = Zend_Db_Table_Abstract::getDefaultAdapter ();
		$sql = 'SELECT training_organizer_phrase  as organizer_id
		from organizer_partners
		join  training_organizer_option on training_organizer_option.id = organizer_partners.organizer_id
		where organizer_partners.is_deleted = 0  
		order by training_organizer_phrase ASC
		';
		$partners = $db->fetchAll($sql);
		if( $partners === false )
			$partners = array();
		$this->viewAssignEscaped ( 'partners', $partners );

		$sql = 'SELECT DISTINCT funder_name FROM organizer_partners
		WHERE is_deleted = 0 AND funder_name != ""
		ORDER BY funder_name ASC
		';
		$funders = $db->fetchAll($sql);
		if( $funders === false )
			$funders = array();
		$this->viewAssignEscaped ( 'funders', $funders );

		// handle form submit
		$request = $this->getRequest();
		if ( $criteria['go'] ) {

			$where_clause = array();
 			$sql = '
				SELECT
				o.id as id,
				training_organizer_phrase as organizer_id,
				subpartner,
				mechanism_id,
				funder_name,
				funder_id
				FROM  organizer_partners AS o
				LEFT JOIN  training_organizer_option AS p ON (p.id = o.organizer_id and p.is_deleted = 0)
			';
			
			if ( $criteria['partner_name'] ) $where_clause [] = '  training_organizer_phrase = "'.$criteria['partner_name'].'"';
			if ( $criteria['partner_funder'] ) $where_clause [] = ' funder_name = "'.$criteria['partner_funder'].'"';
			$where_clause [] = ' o.is_deleted = 0 '; 
			$sql .= ' WHERE '.implode(' and ', $where_clause);
			$sql .= ' ORDER BY  organizer_id ASC';
			$results = $db->fetchAll($sql);

			if ($criteria ['outputType']) {
				$this->sendData ( $results );
			}

			$this->viewAssignEscaped ( 'results', $results );
			$this->view->assign ( 'count', count($results) );
		}
	}


	public function addAction() {
		$this->view->assign ('mode', 'add');
		return $this->editAction();
	}


	public function editAction() {
		if (! $this->hasACL ( 'edit_course' )) {
			$this->doNoAccessError ();
		}

		$db = Zend_Db_Table_Abstract::getDefaultAdapter ();
		$request = $this->getRequest ();
		$validateOnly = $request->isXmlHttpRequest ();
		$status = ValidationContainer::instance ();
		
		if ( $validateOnly )
			$this->setNoRenderer ();

		// populate form
		$allowIds = false; // todo test
		if (! $this->hasACL ( 'training_organizer_option_all' )) {
			$allowIds = array ();
			$user_id = $this->isLoggedIn ();
			$training_organizer_array = MultiOptionList::choicesList ( 'user_to_organizer_access', 'user_id', $user_id, 'training_organizer_option', 'training_organizer_phrase', false, false );
			foreach ( $training_organizer_array as $orgOption ) {
				if ($orgOption ['user_id'])
					$allowIds [] = $orgOption ['id'];
			}
		}
		$id = $this->getSanParam('id');
		if ( $id && !empty($id) ) {
			$sql = 'select * from organizer_partners where id = '.$id;
			$partner_row = $db->fetchRow($sql);
			if ( $partner_row !== false )
				$this->view->assign ( 'partner', $partner_row );
			else {
				$this->view->assign ( 'mode', 'add' );
				$status->setStatusMessage ( t ( 'That partner could not be found.' ) );
			}
		} else {
			$this->view->assign ( 'mode', 'add' );
		}

		$this->view->assign ( 'dropDownOrg', DropDown::generateHtml ( 'training_organizer_option', 'training_organizer_phrase', (isset($partner_row) ? $partner_row['organizer_id'] : ''), ($this->hasACL ( 'training_organizer_option_all' ) ? 'training/insert-table' : false), $this->view->viewonly, ($this->view->viewonly ? false : $allowIds) ) );


		// form submit?
		if ($request->isPost ()) {

			$obj = new TrainingPartner ( );
			if ($this->view->mode == 'add') {
				$obj_id = $this->validateAndSave ( $obj->createRow(), false );
			} else {
				$partnerRow = $obj->fetchRow ( 'id = ' . $id );
				$obj_id = $this->validateAndSave ( $partnerRow, false );
			}

			if ($obj_id) { // success
				$status->setObjectId ( $obj_id );
				$id = $obj_id;
				$status->setStatusMessage ( t ( 'The partner was saved.' ) );
				if ( $this->view->mode == 'add' )
					$status->redirect = Settings::$COUNTRY_BASE_URL.'/training-partner/edit/id/'.$id;
				if (!$validateOnly || $this->view->mode == 'add') // refreshing also
				$_SESSION['status'] = t ( 'The partner was saved.' );
			}

			if ( !$obj_id ) { // fail
				$status->setStatusMessage ( t ( 'ERROR: The partner could not be saved.' ) );
			}

			if ($validateOnly) {
				$this->sendData ( $status );
			} else {
				$this->view->assign ( 'status', $status );
				$this->_redirect('training-partner/edit/id/'.$id);
			}
		}
	}

	protected function validateAndSave($partnerRow, $checkName = true) {
		
		//check for required fields
		$status = ValidationContainer::instance ();
		$status->checkRequired ( $this, 'training_organizer_option_id', '' );
		if ($status->hasError ())
			return false;
		//save
		$partnerRow->organizer_id = $this->getSanParam("training_organizer_option_id");
		$partnerRow->subpartner = $this->getSanParam("partner_subpartner");
		$partnerRow->mechanism_id = $this->getSanParam("partner_mechanism_id");
		$partnerRow->funder_name = $this->getSanParam("funder_name");
		$partnerRow->funder_id = $this->getSanParam("funder_id");

		$obj_id = $partnerRow->save ();
		if ($obj_id) {
			return $obj_id;
		} else {
			$status->setStatusMessage ( t ( 'ERROR: The partner could not be saved.' ) );
		}
		return false;
	}

	/** not used or even visible. **/
/*
	public function listAction() {
		require_once ('models/table/TrainingPartner.php');
		$rowArray = TrainingPartner::suggestionList ( $this->getParam ( 'query' ) );//TODO
		
		$this->sendData ( $rowArray );
	}
	
	public function listwithunknownAction() {
		$this->listAction ();
	}
*/

	public function deleteAction() {
		if (! $this->hasACL ( 'edit_course' )) {
			$this->doNoAccessError ();
		}
		$status = ValidationContainer::instance ();
		$id = $this->getSanParam ( 'id' );

		if ($id) {
			$tp = new TrainingPartner ( );
			$rows = $tp->find ( $id );
			$row = $rows->current ();
			if ($row) {
				$tp->delete ( 'id = ' . $row->id );
			}
			$status->setStatusMessage ( t ( 'That partner was deleted.' ) );
		} else if (! $id) {
			$status->setStatusMessage ( t ( 'That partner could not be found.' ) );
		} else { 
			$status->setStatusMessage ( t ( 'That partner is in use and could not be deleted.' ) );
		}
		
		//validate
		$this->view->assign ( 'status', $status );

	}


	public function viewAction() {
		$db = Zend_Db_Table_Abstract::getDefaultAdapter ();
		require_once ('models/table/OptionList.php');

		$sql = 'SELECT
		organizer_partners.id,
		ifnull(organizer_id,training_organizer_phrase) as organizer_id,
		p.id as organizer_id
		from organizer_partners
		right outer JOIN training_organizer_option AS p ON p.id = organizer_id
		where p.is_deleted = 0 and organizer_partners.is_deleted = 0
		order by organizer_id ASC
		';
		$partners = $db->fetchAll($sql);

		if( $partners === false )
			$partners = array();
		$this->viewAssignEscaped ( 'partners', $partners );

		if ($id = $this->getSanParam ( 'id' )) {
			if ($this->hasACL ( 'edit_course' )) {
				//redirect to edit mode
				//$this->_redirect ( str_replace ( 'view', 'edit', 'http://' . $_SERVER ['SERVER_NAME'] . $_SERVER ['REQUEST_URI'] ) );
			}
			
			$partner = new TrainingPartner ( );
			$partnerRow = $partner->fetchRow ( 'id = ' . $id );
			$partnerArray = $partnerRow->toArray ();
			// and link to training_organizer_option
			if ($partnerArray['organizer_id']) {
				$sql = 'SELECT training_organizer_phrase from training_organizer_option where id = ' .$partnerArray['organizer_id'].' and is_deleted = 0';
				$partnerArray['training_organizer_phrase'] = $db->fetchOne($sql);
			}
		} else {
			$partnerArray = array ();
			$partnerArray ['id'] = null;
		}
		
		$this->viewAssignEscaped ( 'partner', $partnerArray);
	}

}