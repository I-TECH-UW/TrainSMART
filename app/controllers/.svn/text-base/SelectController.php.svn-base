<?php

require_once ('ITechController.php');
require_once ('models/table/System.php');
require_once ('models/Session.php');

class SelectController extends ITechController {
	public function __construct(Zend_Controller_Request_Abstract $request, Zend_Controller_Response_Abstract $response, array $invokeArgs = array()) {
		parent::__construct ( $request, $response, $invokeArgs );
	}

	public function init() {	}

	public function selectAction(){
		if($this->hasACL('edit_employee') && $this->setting('module_employee_enabled')){
			if($this->hasACL('in_service') == false && $this->hasACL('pre_service') == false) {
				$this->_redirect('employee');
				exit();
			}
		}

		if(! $this->hasACL('in_service')) {
			$this->_redirect('dash/dash');
			exit();
		}

		/*
        require_once('Zend/Session.php');
        $itechspace = new Zend_Session_Namespace('itech');
        if (!isset ($itechspace->dashaction)){
        	$itechspace->dashaction = null;
        }

		$request = $this->getRequest();
		$action = $request->getParam('dashaction');
		if (!is_null($action)){
			$itechspace->dashaction = $action;
		}

		$dashaction = $itechspace->dashaction;
		if (!is_null($dashaction)){
			switch ($dashaction){
				case "inservice":
					echo "<script language=\"JavaScript\">location.replace('" . Settings::$COUNTRY_BASE_URL . "/index/index');</script>";
				break;
				case "preservice":
					echo "<script language=\"JavaScript\">location.replace('" . Settings::$COUNTRY_BASE_URL . "/dash/dash');</script>";
				break;
			}
			exit;
		}

#		print_r ($_POST);
#		exit;
*/
		$this->view->assign('title','Select Service');

	}

}
?>