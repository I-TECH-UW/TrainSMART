<?php
require_once ('ITechController.php');
require_once ('models/table/Institution.php');
require_once ('models/table/Helper.php');

class InstitutionController extends ITechController
{
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
		$this->_redirect ( 'institution/institution' );
	}


	public function institutionAction() {

		# CREATING HELPER
		$helper = new Helper();

		# GETTING INSTITUTION TYPES
		$types = $helper->getInstitutionTypes();
		$this->view->assign('institutiontypes',$types);
		$this->viewAssignEscaped ('locations', Location::getAll () );
		$this->view->assign('sponsors',$helper->getSponsors());
	}

	public function institutionaddAction(){
		require_once ('models/table/Tutoredit.php');

		if(isset($_POST['submit'])){
			$institute = new Institution();
			$newid = $institute->Addinstitution($_POST);

			$helper = new Helper();
			$helper->addUserInstitutionRights($helper->myid,$newid);

#			$instituteid = ($_POST[edittable])?$_POST['editid']:$institute->Addinstitution($_POST);
			$this->_redirect("institution/institutionedit/id/" . $newid);
#			echo ("<script language=\"JavaScript\">location.replace('institutionedit/id/" . $instituteid . "');</script>\n");
#			exit;
		}

		# CREATING HELPER
		$helper = new Helper();

		# GETTING CADRES
		$listcadre = $helper->getCadres();
		$this->view->assign('getcadre',$listcadre);

		# GETTING INSTITUTION TYPES
		$types = $helper->getInstitutionTypes();
		$this->view->assign('institutiontypes',$types);

		$this->viewAssignEscaped ('locations', Location::getAll() );
		$this->view->assign('action','');

		$sponsors = $helper->getSponsors();
		$this->view->assign('lookupsponsors',$sponsors);

		# GETTING LOOKUPS
		$this->view->assign('lookupdegrees',$helper->getDegrees());
	}

	public function institutioneditAction(){
	    if(! $this->hasACL('edit_studenttutorinst')){
	       $instid = $this->getSanParam('id');
	       $this->_redirect("institution/institutionview/id/" . $instid);
	    }
	    
	    
		require_once ('models/table/Tutoredit.php');

		$this->viewAssignEscaped ('locations', Location::getAll() );

		if(isset($_POST['update'])){
			$instituteupdate = new Institution();
			$details=$instituteupdate->Updateinstitute($_POST);
		}

		# GETTING ID IF NOT ALREADY SET
		$request = $this->getRequest();
		if (!isset ($instituteid)){
			$instituteid = $request->getParam('id');
		}

		# CREATING INSTITUTION OBJECT
		$ins = new Institution();

		# PULLING STAFF LISTING FOR THIS INSTITUTION
		$staff = $ins->listStaff($instituteid);

		$this->view->assign('fetchins',$staff);

		# CREATING HELPER
		$helper = new Helper();

		# DISPLAYING
		$institutedit = new Institution();
		$details=$institutedit->Editinstitute($instituteid);

		$this->view->assign('insid',$institudeid);


		# PRESENTING MULTI-SELECT OPTIONS
		$this->view->assign('instypes',$helper->getInstitutionTypes());

		$this->view->assign('cadres',$helper->getCadres());
		$cadselect = $helper->getInstitutionCadres($request->getParam('id'));
		$_cs = array();
		foreach ($cadselect as $cad){
			$_cs[] = $cad['id_cadre'];
		}
		$this->view->assign('cadresselected',$_cs);
		$this->view->assign('id',$instituteid);
		$this->view->assign('name',$details['institutionname']);
		$this->view->assign('address1',$details['address1']);
		$this->view->assign('address2',$details['address2']);
		$this->view->assign('city',$details['city']);
		$this->view->assign('geo1',$details['geography1']);
		$this->view->assign('geo2',$details['geography2']);
		$this->view->assign('geo3',$details['geography3']);
		$this->view->assign('zip',$details['postalcode']);
		$this->view->assign('phone',$details['phone']);
		$this->view->assign('fax',$details['fax']);
		$this->view->assign('degree',$details['degrees']);
		$this->view->assign('type',$details['type']);
		$this->view->assign('hasdormitories',$details['hasdormitories']);
		$this->view->assign('dormcount',$details['dormcount']);
		$this->view->assign('tutorhousing',$details['tutorhousing']);
		$this->view->assign('tutorhouses',$details['tutorhouses']);
		$this->view->assign('sponsor',$details['sponsor']);
		$this->view->assign('computers',$details['computercount']);
		$this->view->assign('tutorhouses',$details['tutorhouses']);
		$this->view->assign('studbeds',$details['bedcount']);
		$this->view->assign('comments',$details['comments']);
		$this->view->assign('year',$details['yearfounded']);

		$degreeselect = $helper->getInstitutionDegrees($request->getParam('id'));

		$_ds = array();
		foreach ($degreeselect as $deg){
			$_ds[] = $deg['id'];
		}
		$this->view->assign('degree',$_ds);

		$sponsors = $helper->getSponsors();
		$this->view->assign('lookupsponsors',$sponsors);


		if (($details['tutorcount'] != 0) && (is_numeric($details['tutorcount'])) && ($details['studentcount'] != 0) && (is_numeric($details['studentcount']))){
			$this->view->assign('tutorratio',"1 : " . round(($details['studentcount'] / $details['tutorcount']),2));
		} else {
			$this->view->assign('tutorratio',"N/A");
		}


		# GETTING LOOKUPS
		$this->view->assign('lookupdegrees',$helper->getDegrees());

#		$degrees = $helper->getInstitutionDegrees($instituteid);
#		$_deg = array();
#		foreach ($degrees as $degree){
#			$_deg[] = $degree['id'];
#		}
#		$this->view->assign('degreesselected',$_deg);

		$studentcount = $institutedit->getStudentCount($instituteid);
		$tutorcount = $institutedit->getTutorCount($instituteid);

		$this->view->assign('tutor',$tutorcount);
		$this->view->assign('students',$studentcount);
	}

	public function institutionviewAction(){
	
	    require_once ('models/table/Tutoredit.php');
	
	    $this->viewAssignEscaped ('locations', Location::getAll() );
	
	    if(isset($_POST['update'])){
	        $instituteupdate = new Institution();
	        $details=$instituteupdate->Updateinstitute($_POST);
	    }
	
	    # GETTING ID IF NOT ALREADY SET
	    $request = $this->getRequest();
	    if (!isset ($instituteid)){
	    $instituteid = $request->getParam('id');
	}
	
	# CREATING INSTITUTION OBJECT
	$ins = new Institution();
	
	# PULLING STAFF LISTING FOR THIS INSTITUTION
	$staff = $ins->listStaff($instituteid);
	
	$this->view->assign('fetchins',$staff);
	
	# CREATING HELPER
	$helper = new Helper();
	
	# DISPLAYING
	$institutedit = new Institution();
	$details=$institutedit->Editinstitute($instituteid);
	
	$this->view->assign('insid',$institudeid);
	
	
	# PRESENTING MULTI-SELECT OPTIONS
	$this->view->assign('instypes',$helper->getInstitutionTypes());
	
	$this->view->assign('cadres',$helper->getCadres());
	$cadselect = $helper->getInstitutionCadres($request->getParam('id'));
	$_cs = array();
	foreach ($cadselect as $cad){
	$_cs[] = $cad['id_cadre'];
	}
	$this->view->assign('cadresselected',$_cs);
	$this->view->assign('id',$instituteid);
	$this->view->assign('name',$details['institutionname']);
		$this->view->assign('address1',$details['address1']);
			$this->view->assign('address2',$details['address2']);
			$this->view->assign('city',$details['city']);
			$this->view->assign('geo1',$details['geography1']);
			$this->view->assign('geo2',$details['geography2']);
			$this->view->assign('geo3',$details['geography3']);
			$this->view->assign('zip',$details['postalcode']);
			$this->view->assign('phone',$details['phone']);
			$this->view->assign('fax',$details['fax']);
			$this->view->assign('degree',$details['degrees']);
			$this->view->assign('type',$details['type']);
			$this->view->assign('hasdormitories',$details['hasdormitories']);
		$this->view->assign('dormcount',$details['dormcount']);
		$this->view->assign('tutorhousing',$details['tutorhousing']);
		$this->view->assign('tutorhouses',$details['tutorhouses']);
		$this->view->assign('sponsor',$details['sponsor']);
			$this->view->assign('computers',$details['computercount']);
		$this->view->assign('tutorhouses',$details['tutorhouses']);
		$this->view->assign('studbeds',$details['bedcount']);
			$this->view->assign('comments',$details['comments']);
			$this->view->assign('year',$details['yearfounded']);
	
			$degreeselect = $helper->getInstitutionDegrees($request->getParam('id'));
	
			$_ds = array();
			foreach ($degreeselect as $deg){
	$_ds[] = $deg['id'];
	}
	$this->view->assign('degree',$_ds);
	
	$sponsors = $helper->getSponsors();
	$this->view->assign('lookupsponsors',$sponsors);
	
	
	if (($details['tutorcount'] != 0) && (is_numeric($details['tutorcount'])) && ($details['studentcount'] != 0) && (is_numeric($details['studentcount']))){
	    $this->view->assign('tutorratio',"1 : " . round(($details['studentcount'] / $details['tutorcount']),2));
	} else {
			$this->view->assign('tutorratio',"N/A");
	}
	
	
	# GETTING LOOKUPS
	$this->view->assign('lookupdegrees',$helper->getDegrees());
	
	#		$degrees = $helper->getInstitutionDegrees($instituteid);
	#		$_deg = array();
	#		foreach ($degrees as $degree){
	#			$_deg[] = $degree['id'];
	#		}
	#		$this->view->assign('degreesselected',$_deg);
	
	$studentcount = $institutedit->getStudentCount($instituteid);
	$tutorcount = $institutedit->getTutorCount($instituteid);
	
	$this->view->assign('tutor',$tutorcount);
	$this->view->assign('students',$studentcount);
	}
	
	
	public function institutionsearchAction(){

		$ins = new Institution();
		$criteria = $this->sanitize($_POST);
		$inssearch = $ins->InstitutionSearch($criteria);

		$this->view->assign('institute',$inssearch);
		$this->view->assign('geo1', $criteria['geo1']);
		$this->view->assign('geo2', $criteria['geo2']);
		$this->view->assign('geo3', $criteria['geo3']);
		$this->view->assign('geo4', $criteria['geo4']);
		$this->view->assign('geo5', $criteria['geo5']);
		$this->view->assign('geo6', $criteria['geo6']);
		$this->view->assign('geo7', $criteria['geo7']);
		$this->view->assign('geo8', $criteria['geo8']);
		$this->view->assign('geo9', $criteria['geo9']);

		# CREATING HELPER
		$helper = new Helper();

		# GETTING INSTITUTION TYPES
		$types = $helper->getInstitutionTypes();
		$this->view->assign('institutiontypes',$types);

		# GETTING LOCATIONS
		$this->viewAssignEscaped ('locations', Location::getAll () );

		# GETTING SPONSORS
		$this->view->assign('sponsors',$helper->getSponsors());

	}

	public function addstaffAction(){
		$institute = new Institution();
		$helper = new Helper();
		$request = $this->getRequest();
		if (isset ($_POST['update'])){
			$institute->updateStaff();
			$this->_redirect("institution/institutionedit/id/" . $_POST['updateid']);
		}

		$instituteid = $request->getParam('id');

		# PULLING STAFF LISTING FOR THIS INSTITUTION
		$staff = $helper->getAllTutors();

		$this->view->assign('tutors',$staff);
		$this->view->assign('id',$instituteid);

		# PULLING STAFF LISTING FOR THIS INSTITUTION
		$staff = $institute->listStaff($instituteid);

		$selected = array();
		foreach ($staff as $s){
			$selected[] = $s['tutorid'];
		}
		$this->view->assign('selectedstaff',$selected);
	}

}
?>