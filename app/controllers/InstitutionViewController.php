<?php

require_once ('ITechController.php');

class InstitutioneditController extends ITechController {
	public function __construct(Zend_Controller_Request_Abstract $request, Zend_Controller_Response_Abstract $response, array $invokeArgs = array()) {
		parent::__construct ( $request, $response, $invokeArgs );
	}
	
	public function init() {
	}
	
	public function institutionViewAction()
	{
		$this->view->assign('title',$this->view->translation['Application Name']);
	}
	
}
?>