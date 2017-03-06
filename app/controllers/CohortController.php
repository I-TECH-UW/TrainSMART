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

		if ($request->isPost()) {

            $params = $this->getAllParams();

            // filter out keys from the frontend that aren't in the database
            $frontend_keys = array('action', 'controller', 'formaction', 'module');
            if (isset($params['formaction'])) {

                switch ($params['formaction']) {
                    case 'addclass': {
                        $cohort_id = $params['id'];
                        array_push($frontend_keys, 'currentid', 'id');
                        $columns = array_diff_key($params, array_flip($frontend_keys));

                        $class_id = $helper->addClasses($columns);
                        $helper->updateCohortClasses($cohort_id, array('class' => array($class_id => array('id' => $class_id))), false);
                        break;
                    }
                    case 'editclass': {
                        $params['id'] = $params['currentid'];
                        array_push($frontend_keys, 'currentid');
                        $columns = array_diff_key($params, array_flip($frontend_keys));
                        $helper->updateClasses($columns);
                        break;
                    }
                    case 'students': {
                        $helper->updateCohortStudents($params['id'], $params);
                        $this->_redirect('cohort/cohortedit/id/' . $params['id']);
                        break;
                    }
                    default: {
                        break;
                    }
                }
            }

            if (isset ($_POST['licenseaction'])) {
                $helper->updateCohortLicense($request->getparam('id'), $_POST);
                $this->_redirect('cohort/cohortedit/id/' . $request->getparam('id'));
            }

            if (isset ($_POST['classaction'])) {
                $helper->updateCohortClasses($request->getparam('id'), $_POST);
                $this->_redirect('cohort/cohortedit/id/' . $request->getparam('id'));
            }

            if (isset ($_POST['practicumaction'])) {
                $helper->updateCohortPracticums($request->getparam('id'), $_POST);
                $this->_redirect('cohort/cohortedit/id/' . $request->getparam('id'));
            }

            if (isset ($_POST['delpracticum'])) {
                $helper->deleteCohortPracticum($request->getparam('id'), $_POST);
                $this->_redirect('cohort/cohortedit/id/' . $request->getparam('id'));
            }

            if (isset ($_POST['dellicense'])) {
                $helper->deleteCohortLicense($request->getparam('id'), $_POST);
                $this->_redirect('cohort/cohortedit/id/' . $request->getparam('id'));
            }

            if (isset($_POST['update'])) {
                # UPDATING COHORT
                $cohort = new Cohortedit();
                $cohortid = $cohort->updateCohort($_POST);
            }

            // Cohort Update Query
            if (isset($_POST['updatecohort'])) {
                $cohortupdate = new Cohortedit();
                $update = $cohortupdate->UpdateCohort($_POST);
            }

            // delete cohort ?
            if (isset($_POST['deletecohort'])) {
                $cohortupdate = new Cohortedit();

                if ($cohortupdate->DeleteCohort($_POST)) {
                    header("Location:/cohort/");
                    exit;
                }
            }
        }

		$cohortlist = new Cohortedit();
		$result = $cohortlist->Listcohort();
		  
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
		$this->view->assign('allStudents',$cohort->getAllStudents($cohortid, true, $details['institutionid']));
		$this->view->assign('cohortStudents',$cohort->getAllStudents($cohortid));
		$this->view->assign('licenses',$cohort->getLicenses($cohortid));
		
		$this->view->assign('facilities',$helper->getFacilities());
		$this->view->assign('advisors',$helper->getAllTutors());

		$result = $helper->ListCurrentPracticum($cohortid);	
		$this->view->assign('practicumfetch',$result);

				
		$result = $cohort->ListExams();
		$this->view->assign('fetchexams',$result);

		$result = $cohort->ListClasses($this->setting('site_style'));
		$this->view->assign('fetchclasses',$result);

		$result = $helper->ListCurrentClasses($cohortid);	
		$this->view->assign('fetchcurrentclasses',$result);

		$this->view->assign('lookupdegrees',$helper->getDegrees());
		$this->view->assign('coursetypes', $helper->getCourseTypes());

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
	
	//TA:#362
	public function transcriptAction(){
	    $output = '';
	    $request = $this->getRequest();
	    $cohortid = $request->getparam('id');
	    $cohortedit = new Cohortedit();
		$details=$cohortedit->EditCohort($cohortid);
		
	    $output .= $cohortid;
		
		$this->view->assign('output', $output);
	    
// 	    require_once('models/table/Studentedit.php');
// 	    $request = $this->getRequest();
// 	    $helper = new Helper();
	
// 	    $this->viewAssignEscaped('locations', Location::getAll());
// 	    $params = $this->getAllParams();
// 	    if (isset($params['licenseaction'])) {
// 	        $helper->updateStudentLicense($request->getparam('id'), $params);
// 	        $this->_redirect(Settings::$COUNTRY_BASE_URL . '/studentedit/personview/id/' . $params['id']);
// 	    }
// 	    if (isset($params['classaction'])) {
// 	        $helper->updateStudentClass($request->getparam('id'), $params);
// 	        $this->_redirect(Settings::$COUNTRY_BASE_URL . '/studentedit/personview/id/' . $params['id']);
// 	    }
// 	    if (isset($params['practicumaction'])) {
// 	        $helper->updateStudentPracticum($request->getparam('id'), $params);
// 	        $this->_redirect(Settings::$COUNTRY_BASE_URL . '/studentedit/personview/id/' . $params['id']);
// 	    }
	
	
// 	    // UPDATING FIRST, SO WE WILL LATER RETRIEVE UPDATED VALUES
// 	    if (isset($params['personupdate'])) {
// 	        require_once('models/table/Studentedit.php');
	
// 	        $studentupdate = new Studentedit();
// 	        $update = $studentupdate->UpdatePerson($params);                        # STORING PERSON RECORD
// 	        $update = $studentupdate->UpdateStudent($params);                    # STORING STUDENT RECORD
// 	        $update = $studentupdate->UpdateStudentCohort($params);                # STORING COHORT LINK
// 	        $update = $studentupdate->UpdatePermanentAddress($params);            # STORING PERMANENT ADDRESS
// 	        $update = $studentupdate->UpdateFunding($params);                    # STORING FUNDING
// 	    }
	
	
// 	    $pupiladd = $request->getParam('id');
	
// 	    $this->view->assign('id', $pupiladd);
	
// 	    $this->viewAssignEscaped('locations', Location::getAll());
// 	    $studentedit = new Studentedit();
// 	    $details = $studentedit->EditStudent($pupiladd);
// 	    $sid = $details['student'][0]['id']; //TA:#319
	
// 	    #print_r ($details);
	
// 	    $date = $details['person'][0]['birthdate'];
// 	    $dob = date("d-m-Y", strtotime($date));
	
// 	    $this->view->assign('id', $pupiladd);
// 	    $this->view->assign('selid', $details['person'][0]['title_option_id']);
// 	    $this->view->assign('firstname', $details['person'][0]['first_name']);
// 	    $this->view->assign('middlename', $details['person'][0]['middle_name']);
// 	    $this->view->assign('lastname', $details['person'][0]['last_name']);
// 	    $this->view->assign('gender', $details['person'][0]['gender']);
// 	    $this->view->assign('dob', $dob);
// 	    $this->view->assign('localgeo1', $details['student'][0]['geog1']);
// 	    $this->view->assign('localgeo2', $details['student'][0]['geog2']);
// 	    $this->view->assign('localgeo3', $details['student'][0]['geog3']);
// 	    $this->view->assign('address1', $details['person'][0]['home_address_1']);
// 	    $this->view->assign('address2', $details['person'][0]['home_address_2']);
// 	    $this->view->assign('city', $details['person'][0]['home_city']);
// 	    $this->view->assign('zip', $details['person'][0]['home_postal_code']);
// 	    $this->view->assign('enroll', $details['person'][0]['home_address_2']);
// 	    $this->view->assign('email', $details['person'][0]['email']);
// 	    $this->view->assign('email2', $details['person'][0]['email_secondary']);
// 	    $this->view->assign('titid', $details['person'][0]['title_option_id']);
// 	    $this->view->assign('phone', $details['person'][0]['phone_work']);
// 	    $this->view->assign('cell', $details['person'][0]['phone_mobile']);
// 	    $this->view->assign('nationalid', $details['person'][0]['national_id']);
// 	    $this->view->assign('graduated', $details['student'][0]['isgraduated']);
// 	    $this->view->assign('cohortid', $details['link_cohort'][0]['id_cohort']);
	
// 	    $this->view->assign('facilities', $helper->getFacilities());
	
// 	    if ($details['link_cohort'][0]['id_cohort'] != 0) {
// 	        $this->view->assign('allclasses', $helper->listcurrentclasses($details['link_cohort'][0]['id_cohort']));
// 	        $this->view->assign('allpracticum', $helper->ListCurrentPracticum($details['link_cohort'][0]['id_cohort']));
// 	        $this->view->assign('alllicenses', $helper->ListCurrentLicenses($details['link_cohort'][0]['id_cohort']));
// 	        //TA:#319 use $sid not $details['person'][0]['id']
// 	        $this->view->assign('currentclasses', $helper->listcurrentclasses($details['link_cohort'][0]['id_cohort'], $sid));
// 	        $this->view->assign('currentpracticum', $helper->ListCurrentPracticum($details['link_cohort'][0]['id_cohort'], $sid));
// 	        $this->view->assign('currentlicenses', $helper->ListCurrentLicenses($details['link_cohort'][0]['id_cohort'], $sid));
// 	    } else {
// 	        $this->view->assign('currentclasses', array());
// 	        $this->view->assign('currentpracticum', array());
// 	        $this->view->assign('currentlicenses', array());
// 	    }
	
	
// 	    # PERMANENT ADDRESS
// 	    $this->view->assign('permanentgeo1', $details['permanent_address'][0]['id_geog1']);
// 	    $this->view->assign('permanentgeo2', $details['permanent_address'][0]['id_geog2']);
// 	    $this->view->assign('permanentgeo3', $details['permanent_address'][0]['id_geog3']);
// 	    $this->view->assign('permanentaddress1', $details['permanent_address'][0]['address1']);
// 	    $this->view->assign('permanentaddress2', $details['permanent_address'][0]['address2']);
// 	    $this->view->assign('permanentcity', $details['permanent_address'][0]['city']);
// 	    $this->view->assign('permanentzip', $details['permanent_address'][0]['postalcode']);
	
// 	    # STUDENT VIEW
// 	    $this->view->assign('studentid', $details['student'][0]['studentid']);
// 	    $this->view->assign('studenttype', $details['student'][0]['studenttype']);
// 	    $this->view->assign('comments', $details['student'][0]['comments']);
	
// 		if (count($details['link_cohort']) == 0) {
// 	    $joindate = "";
// 	    $dropdate = "";
// 	    $joinreason = 0;
// 	    $dropreason = 0;
// 	    } else {
// 			$joindate = $details['link_cohort'][0]['joindate'] == "0000-00-00" ? "" : date("m/d/Y", strtotime($details['link_cohort'][0]['joindate']));
// 			$dropdate = $details['link_cohort'][0]['dropdate'] == "0000-00-00" ? "" : date("m/d/Y", strtotime($details['link_cohort'][0]['dropdate']));
// 			$joinreason = $details['link_cohort'][0]['joinreason'];
// 			$dropreason = $details['link_cohort'][0]['dropreason'];
// 		}
// 		$this->view->assign('enrollmentdate', $joindate);
// 		$this->view->assign('enrollmentreason', $joinreason);
// 		$this->view->assign('separationdate', $dropdate);
// 		$this->view->assign('separationreason', $dropreason);
	
// 			    $this->view->assign('cadre', $details['student'][0]['cadre']);
// 			        $this->view->assign('advisor', $details['student'][0]['advisorid']);
	
// 			            $this->view->assign('fundingsource', $details['funding'][0]['fundingsource']);
// 			                $this->view->assign('fundingamount', $details['funding'][0]['fundingamount']);
	
// 			                // POST GRADUATION FIELDS
// 			                $this->view->assign('postgeo1', $details['student'][0]['postgeo1']);
// 			                $this->view->assign('postgeo2', $details['student'][0]['postgeo2']);
// 			                $this->view->assign('postgeo3', $details['student'][0]['postgeo3']);
// 		$this->view->assign('postaddress1', $details['student'][0]['postaddress1']);
// 		$this->view->assign('postfacilityname', $details['student'][0]['postfacilityname']);
	
// 			$helper = new Helper();
	
// 			                $this->view->assign('joinreasons', $helper->getReasons('join'));
// 			                $this->view->assign('dropreasons', $helper->getReasons('drop'));
	
	
// 			                // For Title List
// 			                $listtitle = $studentedit->ListTitle();
// 			                $this->view->assign('gettitle', $listtitle);
	
// 			                # GETTING COHORTS
// 			                $listcohort = $studentedit->ListCohort();
// 			                $this->view->assign('getcohorts', $listcohort);
	
// 			                # GETTING CADRES
// 			                $listcadre = $studentedit->listCadre();
// 			                $this->view->assign('getcadres', $listcadre);
	
// 			                # GETTING TUTORS
// 			                $listtutors = $studentedit->ListTutors();
// 			                $this->view->assign('gettutors', $listtutors);
	
// 			                # NATIONALITY INFO
// 			                $helper = new Helper();
// 			                $this->view->assign('lookupnationalities', $helper->getNationalities());
// 			                $this->view->assign('nationalityid', $details['student'][0]['nationalityid']);
	
// 		if (count($details['link_cohort']) > 0) {
// 			$this->view->assign('hascohort', true);
	
// 			#$cstartdate = $details['cohort'][0]['startdate'];
// 			$cstartdate = $joindate;
// 			                #echo '<pre>';var_dump($details);die();
// 			                if ($cstartdate) {
// 			                $joindate = $details['link_cohort'][0]['joindate'] == "0000-00-00" ? "" : date("m/d/Y", strtotime($details['link_cohort'][0]['joindate']));
// 			                $dropdate = $details['link_cohort'][0]['dropdate'] == "0000-00-00" ? "" : date("m/d/Y", strtotime($details['link_cohort'][0]['dropdate']));
	
// 			                $lastclass = $dropdate ? $dropdate : date("m/d/Y");  // seperate date or today
// 				$yearofstudy = intval((strtotime($lastclass) - strtotime($cstartdate)) / 31536000 + 1); // time spent in class / seconds in a year + 1 year to show 1 year if 6mo of class or whatever
// 	}
	
// 			                $this->view->assign('yearofstudy', $cstartdate ? $yearofstudy : "");
	
// 			                if (count($details['cadre']) > 0) {
// 			                $cadre = $details['cadre'][0]['id'];
// 				} else {
// 					$cadre = 0;
// 				}
	
	
// 			} else {
// 				$this->view->assign('hascohort', false);
// 				$this->view->assign('yearofstudy', "");
// 				$cadre = 0;
// 			}
	
// 			$this->view->assign('lookupstudenttypes', $helper->getStudentTypes());
// 			$this->view->assign('cadre', $cadre);
	
	
// 			$this->view->assign('lookupfunding', $helper->getFunding());
// 			$this->view->assign('studentfunding', $studentedit->getStudentFunding($details['student'][0]['id']));
	
		}

}
?>
