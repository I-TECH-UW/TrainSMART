<?php
require_once ('ITechController.php');
//require_once ('models/table/Cohortadd.php');
require_once ('models/table/Cohort.php');
#require_once ('models/table/Studentedit.php');	# REMOVED AND REPLACED WITH GENERIC HELPER
require_once ('models/table/Helper.php');



class CohortController extends ITechController {
	public function __construct(Zend_Controller_Request_Abstract $request, Zend_Controller_Response_Abstract $response, array $invokeArgs = array()) {
		parent::__construct ( $request, $response, $invokeArgs );
	}
	
	public function init() {
	}
	
	public function preDispatch() {
		parent::preDispatch ();
		
		if (! $this->isLoggedIn ())
			$this->doNoAccessError ();
	

		if (empty($this->view->title))
			$this->view->assign('title', $this->view->translation['Application Name']);
	}
	
	public function indexAction() {
		$this->_redirect ('cohort/cohort');
	}

	public function cohortaddAction(){
		if (isset ($_POST['update'])){
			$cohort = new Cohortedit();
			$newid = $cohort->addCohort($_POST);
			$this->_redirect(Settings::$COUNTRY_BASE_URL . '/cohort/cohortedit/id/' . $newid);
		}

		$this->view->assign('action','../cohort/cohortedit/');

		# CREATING HELPER
		$helper = new Helper();
		
		# GETTING ALL COHORT NAMES
		$allcohorts = $helper->getCohorts();
		$cohortarray = array();
		foreach ($allcohorts as $co){
			$cohortarray[] = addslashes($co['cohortname']);
		}
		$this->view->assign('allcohorts',"'" . implode("','", $cohortarray) . "'");

		# GETTING CADRES
		$listcadre = $helper->getCadres();
		$this->view->assign('getcadres',$listcadre);

		# GETTING INSTITUTIONS
		$institutions = $helper->getInstitutions();
		$this->view->assign('institutions',$institutions);

		# GETTING DEGREES
		$this->view->assign('degrees',$helper->getDegrees());

	}
	
	public function cohorteditAction(){
		$request = $this->getRequest();
		
		$helper = new Helper();
		
		if ((isset ($_POST['action'])) && ($_POST['action'] == "students")){
			$helper->updateCohortStudents($request->getparam('id'), $_POST);
			$this->_redirect ('cohort/cohortedit/id/' . $request->getparam('id'));
		}

		if (isset ($_POST['licenseaction'])){
			$helper->updateCohortLicense($request->getparam('id'), $_POST);
			$this->_redirect ('cohort/cohortedit/id/' . $request->getparam('id'));
		}

		if (isset ($_POST['classaction'])){
			$helper->updateCohortClasses($request->getparam('id'), $_POST);
			$this->_redirect ('cohort/cohortedit/id/' . $request->getparam('id'));
		}

		if (isset ($_POST['practicumaction'])){
			$helper->updateCohortPracticums($request->getparam('id'), $_POST);
			$this->_redirect ('cohort/cohortedit/id/' . $request->getparam('id'));
		}
		
		if (isset ($_POST['delpracticum'])){
			$helper->deleteCohortPracticum($request->getparam('id'), $_POST);
			$this->_redirect ('cohort/cohortedit/id/' . $request->getparam('id'));
		}

		if (isset ($_POST['dellicense'])){
			$helper->deleteCohortLicense($request->getparam('id'), $_POST);
			$this->_redirect ('cohort/cohortedit/id/' . $request->getparam('id'));
		}

		if(isset($_POST['update'])){
			# UPDATING COHORT
			$cohort = new Cohortedit();
			$cohortid = $cohort->updateCohort($_POST);	
		}
		
		// Cohort Update Query
	    if(isset($_POST['updatecohort'])){
			$cohortupdate = new Cohortedit();
			$update = $cohortupdate->UpdateCohort($_POST);	
		}
		
		// delete cohort ?
		if(isset($_POST['deletecohort'])){
			$cohortupdate = new Cohortedit();
			
	    	if( $cohortupdate->DeleteCohort($_POST) ){
	    		header("Location:/cohort/");
	    		exit;
	    	}
		}
		
		$cohortlist = new Cohortedit();
		$result = $cohortlist->Listcohort($fetchlist);	
		  
		$this->view->assign('cohortfetch',$result);
		  
		$cohortid = $request->getparam('id');	

		// Cohort Listing Page 
		$cohortedit = new Cohortedit();
		$details=$cohortedit->EditCohort($cohortid);
	
		$this->view->assign('id',$cohortid);	
		$this->view->assign('cohortid',$cohortid);	
		$this->view->assign('cohortname',$details['cohortname']);	
		$this->view->assign('degree',$details['degree']);	
		$this->view->assign('institution',$details['institutionid']);
		$this->view->assign('startname',($details['startdate'] != "0000-00-00" ? date("m/d/Y", strtotime($details['startdate'])) : ""));	
		$this->view->assign('graddate',($details['graddate'] != "0000-00-00" ? date("m/d/Y", strtotime($details['graddate'])) : ""));
		
		# GETTING INSTITUTIONS
		$institutions = $helper->getInstitutions();
		$this->view->assign('institutions',$institutions);

		# cadres
		$institution_cadres = $helper->getInstitutionCadres($details['institutionid']);
		$this->view->assign('institution_cadre_id', $details['cadreid']);
		$this->view->assign('institution_cadres', $institution_cadres);
		
		$cohort = new Cohortedit();
		$this->view->assign('studentsatstart',count($helper->getCohortStudents($cohortid)));
		$this->view->assign('studentsseparated',count($helper->getCohortStudents($cohortid,"dropped")));
		$this->view->assign('studentsgraduating',count($helper->getCohortStudents($cohortid,"graduating")));

		//TA:#304 Let's take all students from this institution only
		//$this->view->assign('allStudents',$cohort->getAllStudents($cohortid, true));
		$this->view->assign('allStudents',$cohort->getAllStudents($cohortid, true, $details['institutionid']));
		$this->view->assign('cohortStudents',$cohort->getAllStudents($cohortid));
		$this->view->assign('licenses',$cohort->getLicenses($cohortid));
		
		$this->view->assign('facilities',$helper->getFacilities());
		$this->view->assign('advisors',$helper->getAllTutors());
		

		  // For Graduation Requirements
		  
#		$requirementlist = new Cohortedit();
#		$result = $requirementlist->ListRequirement($fetchrequire);	
#		$this->view->assign('graduationfetch',$result);
#		//$this->view->assign('action','../cohort/cohortgraduationedit');
		
		
		$result = $helper->ListCurrentPracticum($cohortid);	
		$this->view->assign('practicumfetch',$result);
		//$this->view->assign('action','../cohort/cohortpracticumedit');
		
				
		$result = $cohort->ListExams($fetchexams);	
		$this->view->assign('fetchexams',$result);
		//$this->view->assign('action','../cohort/cohortexamedit');
		
		$result = $cohort->ListClasses($this->setting('site_style'));
		$this->view->assign('fetchclasses',$result);

		$result = $helper->ListCurrentClasses($cohortid);	
		$this->view->assign('fetchcurrentclasses',$result);
		//$this->view->assign('action','../cohort/cohortclassedit');


		$this->view->assign('lookupdegrees',$helper->getDegrees());
		
		//TA:97
 		$dc = strtotime($details['timestamp_created']);
 		$dateCreated = $dc != '' && $dc > 0 ? date("d-m-Y",$dc) : t("N/A");
 		$this->view->assign('dateCreated', $dateCreated);
 		$dm = strtotime($details['timestamp_updated']);
 		$dateModified = $dm != '' && $dm >0 ? date("d-m-Y",$dm): t("N/A");
 		$this->view->assign('dateModified', $dateModified);
 		require_once('models/table/User.php');
 		$userObj = new User ();
  		$created_by = $details['created_by'] ? $userObj->getUserFullName($details['created_by']) : t("N/A");
  		$this->viewAssignEscaped('creator', $created_by);
  		$update_by = $details['modified_by'] ? $userObj->getUserFullName($details['modified_by']) : t("N/A");
 		$this->viewAssignEscaped('updater', $update_by);

	}
	
	
	public function cohortstudentAction(){
	}

	public function cohortpracticumAction(){
		$request = $this->getRequest();
		$cohortid = $request->getparam('cohortid');	
		$this->view->assign('id',$cohortid);
	

		if(isset($_POST[add])){
			$request = $this->getRequest();
			$cohortid = $request->getparam('cohortid');	
			$cohort = new Cohortedit();
			$cohortid = $cohort->AddRequirement($cohortid,$_POST);	
		}
	
	}
	
	public function cohortgraduationeditAction(){
	
		$request = $this->getRequest();
		$requireid = $request->getparam('grduateid');	
	
		$requireview = new Cohortedit();
		$details=$requireview->ViewRequirement($requireid);
	
		$this->view->assign('req',$details['requirement']);	
		$this->view->assign('year',$details['requirementyear']);	
	 
	
		if(isset($_POST[updatereq])){
			$cohortupdate = new Cohortedit();
			$update=$cohortupdate->UpdateRequirement($requireid,$_POST);	
			
			$this->view->assign('id',$_POST[id]);
			$this->view->assign('req',$update['requirement']);	
			$this->view->assign('year',$update['requirementyear']);	
			
			$this->view->assign('success','Update succesfully');
		}
	
	}
	
	public function cohortclassAction(){
		
		$request = $this->getRequest();
		$cohortid = $request->getparam('cohortid');	
		$this->view->assign('id',$cohortid);
	
		if(isset($_POST[add])){
			$request = $this->getRequest();
			$cohortid = $request->getparam('cohortid');	
			$cohort = new Cohortedit();
			$cohortid = $cohort->AddClasses($cohortid,$_POST);	
		}
	}
	
	public function cohortclasseditAction(){
	
		$request = $this->getRequest();
		$classid = $request->getparam('classid');	
		
		$classview = new Cohortedit();
		$details=$classview->ViewClasses($classid);
		$this->view->assign('name',$details['classname']);
		$this->view->assign('classdate',$details['startdate']);	
	
		if(isset($_POST[updateclass])){
			$classupate = new Cohortedit();
			$update=$classupate->UpdateClasses($classid,$_POST);	
			
			$this->view->assign('id',$_POST[id]);
			$this->view->assign('name',$update['classname']);
			$this->view->assign('classdate',$update['startdate']);	
		
			
		
			$this->view->assign('success','Update succesfully');
		}
	}
	
	public function cohortasspracticumAction(){
		
		$request = $this->getRequest();
		$cohortid = $request->getparam('cohortid');	
		$this->view->assign('id',$cohortid);
	
		if(isset($_POST[add])){
			$request = $this->getRequest();
			$cohortid = $request->getparam('cohortid');	
			$cohort = new Cohortedit();
			$cohortid = $cohort->AddPracticum($cohortid,$_POST);	
		}
	}
	
	public function cohortpracticumeditAction(){
	
		$request = $this->getRequest();
		$pracid = $request->getparam('pracid');	
		
		$pracview = new Cohortedit();
		$details=$pracview->ViewPracticum($pracid);
		$this->view->assign('name',$details['practicumname']);
		$this->view->assign('hc',$details['hourscompleted']);	
		$this->view->assign('hq',$details['hoursrequired']);	
		 
		
		if(isset($_POST[updateprac])){
			$pracupdate = new Cohortedit();
			$update=$pracupdate->UpdatePracticum($pracid,$_POST);	
			
			$this->view->assign('id',$_POST[id]);
			$this->view->assign('name',$update['practicumname']);
			$this->view->assign('hc',$update['hourscompleted']);	
			$this->view->assign('hq',$update['hoursrequired']);	
			
			$this->view->assign('success','Update succesfully');
		}
	}
	
	public function cohortexamaddAction(){
		
		$request = $this->getRequest();
		$cohortid = $request->getparam('cohortid');	
		$this->view->assign('id',$cohortid);
	
		if(isset($_POST[add])){
			$request = $this->getRequest();
			$cohortid = $request->getparam('cohortid');	
			$cohort = new Cohortedit();
			$cohortid = $cohort->AddExams($cohortid,$_POST);	
		}
	}
	
	public function cohortexameditAction(){
	
		$request = $this->getRequest();
		$examid = $request->getparam('examid');	
		
		$examview = new Cohortedit();
		$details=$examview->ViewExams($examid);
		$this->view->assign('name',$details['examname']);
		$this->view->assign('examdate',$details['examdate']);	
		$this->view->assign('grade',$details['grade']);	
	 
	
		if(isset($_POST[updateexam])){
			$examupate = new Cohortedit();
			$update=$examupate->UpdateExams($examid,$_POST);	
			
			$this->view->assign('id',$_POST[id]);
			$this->view->assign('name',$update['examname']);
			$this->view->assign('examdate',$update['examdate']);	
			$this->view->assign('grade',$update['grade']);	
			
		
			$this->view->assign('success','Update succesfully');
		}
	}
	
	
	public function cohortAction(){
	    if (! $this->isLoggedIn ())
	        $this->doNoAccessError ();
	     
	    if (! $user_id = $this->isLoggedIn()) {
	        $this->doNoAccessError();
	    }
	     
	    if ($this->view->mode == 'edit') {
	        $user_id = $this->getSanParam('id');
	    }

/*
		$cohort = new Cohortedit();
		$cohorts = $cohort->Cohortsearch($_POST);
*/
		
		#$this->view->assign('cohort',$cohorts);
		$this->view->assign('action','../cohort/cohortsearch');

		$helper = new Helper();
		$this->view->assign('lookup_institutions', $helper->getUserAllowedInstitutionNames($user_id));
		$this->view->assign('lookup_cadres', $helper->getUserAllowedCadreNames($user_id));
	}
	
	public function cohortsearchAction(){
	    if (! $this->isLoggedIn ())
	        $this->doNoAccessError ();
	    
	    if (! $user_id = $this->isLoggedIn()) {
	        $this->doNoAccessError();
	    }
	    
	    if ($this->view->mode == 'edit') {
	        $user_id = $this->getSanParam('id');
	    }
	    
	    $cohorts = array();
		#print_r ($_POST);
		
		$converted = false;
		if( !empty($_GET) ){
			$_POST = $_GET;
			$converted = true;
		}
		if (isset ($_POST['update'])){
			$cohort = new Cohortedit();
			$cohorts = $cohort->Cohortsearch($_POST);
			
			if(!$converted){
				$params_query = http_build_query($_POST);
				header("Location://{$_SERVER['HTTP_HOST']}/cohort/cohortsearch?{$params_query}");
			}
		}

		$this->view->assign('cohort',$cohorts);

		$helper = new Helper();
		$this->view->assign('lookup_institutions',$helper->getUserAllowedInstitutionNames($user_id));
		$this->view->assign('lookup_cadres', $helper->getUserAllowedCadreNames($user_id));
		
	}

}
?>