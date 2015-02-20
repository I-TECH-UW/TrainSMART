<?php
require_once ('ITechController.php');
require_once ('models/table/Peopleadd.php');
require_once ('models/table/Helper.php');

class PeopleaddController extends ITechController {
	public function __construct(Zend_Controller_Request_Abstract $request, Zend_Controller_Response_Abstract $response, array $invokeArgs = array()) {
		parent::__construct ($request, $response, $invokeArgs );
	}

	public function init() {
	}

	public function preDispatch() {
		parent::preDispatch ();

		if (! $this->isLoggedIn ())
			$this->doNoAccessError ();

	}
	

	public function peopleaddAction(){
		$params = $this->getAllParams();
		$status = ValidationContainer::instance();
		if ( $this->getRequest()->isPost() ){
			if (isset ($params['addpeople'])){
				$peopleadd = new PeopleAdd();	# TRIGGERS ADDING PERSON
				$tutorid = $peopleadd->addTutor($params);

				if ($tutorid) {// sucess
					$status->setStatusMessage ( t ( 'The person was saved.' ) );
					$_SESSION['status'] = t ( 'The person was saved.' );
				}


				switch ($params['type']){
					case "key":
					case "tutor":
						$this->_redirect(Settings::$COUNTRY_BASE_URL . "/tutoredit/tutoredit/id/" . $tutorid);
					break;
				}
			}
			exit;
		}
		$this->viewAssignEscaped ('locations', Location::getAll () );
		$this->view->assign('action','../studentedit/studentedit/');
		$this->view->assign('title', $this->view->translation['Application Name']);

		$persontitle = new Peopleadd();
		$result = $persontitle->Peopletitle($fetchtitle);

		$this->view->assign('fetchtitle',$result);

		// For Facility
		$faclilityttitle = new Peopleadd();
		$result = $faclilityttitle->PeopleFacility($fetchfacility);

		$this->view->assign('fetchfacility',$result);

		$citylist = new Peopleadd();
		$result = $citylist->PeopleCity($citylist);

		$this->view->assign('fetchcity',$result);

		# CREATING HELPER
		$helper = new Helper();
		$this->view->assign('institutions',$helper->getInstitutions(false));
	}

    public function skillsmartChwAddAction() {
        
    }
    
}
?>