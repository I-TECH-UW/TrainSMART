<?php

require_once ('ITechController.php');
require_once ('models/table/Helper.php');

class PeopleController extends ITechController {
	public function __construct(Zend_Controller_Request_Abstract $request, Zend_Controller_Response_Abstract $response, array $invokeArgs = array()) {
		parent::__construct ( $request, $response, $invokeArgs );
	}
	
	public function init() {
	}
	
	public function peopleAction(){
		
		$helper = new Helper();
		
		$cohort = $helper->getCohorts();
		$cadre = $helper->getCadres();
		$institution = $helper->getInstitutions(false);
		$facility = $helper->getFacilities();
				
		$this->view->assign('title',$this->view->translation['Application Name']);
		$this->view->assign('cohort',$cohort);
		$this->view->assign('cadre',$cadre);
		$this->view->assign('institution',$institution);
		$this->view->assign('facility',$facility);
	}

	public function indexAction() {
		$this->_redirect ( 'people/people' );
	}
	
}
?>