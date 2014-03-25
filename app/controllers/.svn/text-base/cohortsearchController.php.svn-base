<?php
require_once ('ITechController.php');
require_once ('models/table/Cohortsearch.php');

class CohortsearchController extends ITechController {
	public function __construct(Zend_Controller_Request_Abstract $request, Zend_Controller_Response_Abstract $response, array $invokeArgs = array()) {
		parent::__construct ( $request, $response, $invokeArgs );
	}
	
	public function init() {
	}
	
	public function cohortsearchAction()
	{
	
		$cohort = new CohortSearch();
		$cohorts = $cohort->SearchCohort($_POST);	
		
		$this->view->assign('title',$this->view->translation['Application Name']);

		$this->view->assign('getcohort',$cohorts);
	}
	
}
?>