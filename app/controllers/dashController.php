<?php

require_once ('ITechController.php');
require_once ('models/table/dash.php');

class DashController extends ITechController {
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
	
	public function indexAction(){
		return $this->_redirect ( 'dash/dash' );
	}
	
	public function dashAction()
	{
	$this->view->assign('title',$this->view->translation['Application Name']);
        
        $institute = new Dashview();
        $details=$institute->fetchdetails();
        $this->view->assign('getins',$details);
        
	}
	
}
?>