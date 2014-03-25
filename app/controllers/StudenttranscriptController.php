<?php
require_once ('ITechController.php');
require_once ('models/table/Studenttranscript.php');

class StudenttranscriptController extends ITechController {
	public function __construct(Zend_Controller_Request_Abstract $request, Zend_Controller_Response_Abstract $response, array $invokeArgs = array()) {
		parent::__construct ($request, $response, $invokeArgs );
	}
	
	public function init() {
	}
	
	public function studenttranscriptAction()
	{
		$id = $this->getRequest()->getParam('id');
		$studenttrans = new Studenttranscript();
		$transstud=$studenttrans->EditStudenttranscript($id);
		$this->view->assign('id',$id);
       	$this->view->assign('title',$this->view->translation['Application Name']);
		$this->view->assign('firstname',$transstud['firstname']);
		$this->view->assign('cadre',$transstud['cadre']);
		$this->view->assign('dateofenroll',$transstud['enrollmentdate']);
		$this->view->assign('gender',$transstud['gendername']);
		$this->view->assign('dob',$transstud['birthdate']);
		$this->view->assign('sepration',$transstud['separationdate']);
		$this->view->assign('address1',$transstud['address1']);
	
	}
	
}
?>