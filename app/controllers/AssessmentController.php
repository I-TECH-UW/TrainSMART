<?php

require_once ('ITechController.php');
require_once ('models/table/Assessment.php');
require_once ('models/table/PersonToAssessment.php');
require_once ('models/table/Helper.php');
class AssessmentController extends ITechController {
	public function __construct(Zend_Controller_Request_Abstract $request, Zend_Controller_Response_Abstract $response, array $invokeArgs = array()) {
		parent::__construct ( $request, $response, $invokeArgs );
	}

	public function init() {
	}
	public function preDispatch() {
		parent::preDispatch ();
		
		if (! $this->isLoggedIn ())
			$this->doNoAccessError ();
			
			// we extend these controllers, lets redirect to their URL
		if (strstr ( $_SERVER ['HTTP_REFERER'], '/site/' ) && strstr ( $_SERVER ['REQUEST_URI'], '/assessment' ))
			$this->_redirect ( str_replace ( '/assessment/', '/site/', '//' . $_SERVER ['SERVER_NAME'] . $_SERVER ['REQUEST_URI'] ) );
	}
	
	public function indexAction() {
		$this->_redirect ( 'assessment/search' );
	}

	
	public function editAction() {
	    //if (! $this->hasACL ( 'edit_assessment' )) {
	      //  $this->doNoAccessError ();
	    //}
	
	    $this->view->assign ( 'mode', 'edit' );
	    $rtn = $this->doEditView ();
	    return $rtn;
	}

    protected function validateAndSave($paRow, $checkName = true)
    {
        file_put_contents('/vagrant/vagrant/logs/php_debug.log', 'assessmentController validateAndSave >' . PHP_EOL, FILE_APPEND | LOCK_EX); ob_start();
        $toss = ob_get_clean(); file_put_contents('/vagrant/vagrant/logs/php_debug.log', $toss . PHP_EOL, FILE_APPEND | LOCK_EX);
        
        try {
        $status = ValidationContainer::instance();
        $status->checkRequired($this, 'first_name', $this->tr('First Name'));
        $status->checkRequired($this, 'last_name', $this->tr('Last Name'));
        $status->checkRequired($this, 'assessment_type_id', $this->tr('Assessment Type'));
        
        if($status->hasError()) {
            $status->addError(null, 'Enter required fields.');
            throw new Exception('Error');
        }
        
        $criteria = array();
        $criteria['first_name'] = $this->getSanParam('first_name');
        $criteria['last_name'] = $this->getSanParam('last_name');
        $criteria['assessment_type_id'] = $this->getSanParam('assessment_type_id');
        $criteria['add-day'] = $this->getSanParam('add-day');
        $criteria['add-month'] = $this->getSanParam('add-month');
        $criteria['add-year'] = $this->getSanParam('add-year');
        
        file_put_contents('/vagrant/vagrant/logs/php_debug.log', 'assessmentController save0 >' . PHP_EOL, FILE_APPEND | LOCK_EX); ob_start();
        var_dump("criteria=", $criteria, "END");
        $toss = ob_get_clean(); file_put_contents('/vagrant/vagrant/logs/php_debug.log', $toss . PHP_EOL, FILE_APPEND | LOCK_EX);
        
        $db = Zend_Db_Table_Abstract::getDefaultAdapter();
        $link = mysqli_connect(Settings::$DB_SERVER, Settings::$DB_USERNAME, Settings::$DB_PWD, "");
        
        /* check connection */
        if (mysqli_connect_errno()) {
            printf("Connect failed: %s\n", mysqli_connect_error());
            $status->addError(null, mysqli_connect_error());
            throw new Exception('Error');
            //exit();
        }
        
        // get assessment_id from $criteria('assessment_type_id');
        $sql = '
select
a.id as assessment_id
from assessments a
where ';
        $where = array();
        // $where [] = ' assessment.is_deleted = 0 ';
        $where[] = ' 0 = 0 ';
        
        if ($criteria['assessment_type_id']) {
            $where[] = " assessment_type_id = " . mysqli_real_escape_string($link, $criteria['assessment_type_id']);
        }
        
        if ($where)
            $sql .= implode(' AND ', $where);
        
        $rowArray = $db->fetchAll($sql);
        
        if (count($rowArray) == 0 ) {
            $status->addError(null, 'Assessment type not found.');
            throw new Exception('Error');
        }
        
        $row = $rowArray[0];
        $paRow->assessment_id = $row['assessment_id'];
                
        // validate first_name, last_name, get facility_id
        $sql = '
select
p.id as person_id,
p.first_name,
p.last_name,
p.facility_id,
f.facility_name
from person p
join facility f on p.facility_id = f.id 
where ';
        $where = array();
        // $where [] = ' assessment.is_deleted = 0 ';
        $where[] = ' 0 = 0 ';
        

        
        if ($criteria['first_name']) {
            $where[] = " first_name = '" . mysqli_real_escape_string($link, $criteria['first_name']) . "'";
        }
        
        if ($criteria['last_name']) {
            $where[] = " last_name = '" . mysqli_real_escape_string($link, $criteria['last_name']) . "'";
        }
        
        if ($where)
            $sql .= implode(' AND ', $where);
        
        $rowArray = $db->fetchAll($sql);
        
        if (count($rowArray) == 0 ) {
            $status->addError(null, 'Person not found.');
            throw new Exception('Error');
        }
        
        if (count($rowArray) > 1) {
            $status->addError(null, 'Duplicate person records found.');
            throw new Exception('Error');
        }
        
        // gather pa key including assessment_id from previous query, dupecheck
        $row = $rowArray[0];
        $paRow->person_id = $row['person_id'];
        $paRow->facility_id = $row['facility_id'];
        $paRow->date_created = "'" . $criteria['add-year'] . '-' . $criteria['add-month'] . '-' . $criteria['add-day'] . "'";
        $date_created_without_quotes = $criteria['add-year'] . '-' . $criteria['add-month'] . '-' . $criteria['add-day'];
        
        
        file_put_contents('/vagrant/vagrant/logs/php_debug.log', 'assessmentController save1 >' . PHP_EOL, FILE_APPEND | LOCK_EX); ob_start();
        //var_dump("paRow=", $paRow, "END");
        var_dump("paRow->id=", $paRow->id, "END");
        var_dump("paRow->person_id=", $paRow->person_id, "END");
        var_dump("paRow->facility_id=", $paRow->facility_id, "END");
        var_dump("paRow->assessment_id=", $paRow->assessment_id, "END");
        var_dump("paRow->date_created=", $paRow->date_created, "END");
        var_dump("paRow->user_id=", $paRow->user_id, "END");
        $toss = ob_get_clean(); file_put_contents('/vagrant/vagrant/logs/php_debug.log', $toss . PHP_EOL, FILE_APPEND | LOCK_EX);
        
        $dupe = new PersonToAssessments();
        
        $select = $dupe->select()->where( 
            'person_id = ' . $paRow->person_id . 
            ' and facility_id = ' . $paRow->facility_id .
            ' and assessment_id = ' . $paRow->assessment_id .
            ' and date_created = ' . $paRow->date_created  );
        
        
        file_put_contents('/vagrant/vagrant/logs/php_debug.log', 'assessmentController save1a >' . PHP_EOL, FILE_APPEND | LOCK_EX); ob_start();
        //var_dump("select=", $select, "END");
        $toss = ob_get_clean(); file_put_contents('/vagrant/vagrant/logs/php_debug.log', $toss . PHP_EOL, FILE_APPEND | LOCK_EX);
        
        if($dupe->fetchRow($select)) {
            $status->addError(null, 'Duplicate assessment record found.');
            throw new Exception('Error');
        }
        
        // save new pa
        $paRow->date_created = $date_created_without_quotes;
        $helper = new Helper;
        $paRow->user_id = $helper->myid();
        $obj_id = $paRow->save();
        
        throw new Exception('Saved');
        
        } catch (Exception $e) {
            
            if ( $e != 'Error') {
                 $status->setStatusMessage ( t ( 'Assessment') . ' ' . $obj_id . ' ' . t('saved' ) . '.' );
            }            
        } // try/catch
    }
	
	

	public function viewAction() {
	
	     
	    //file_put_contents('/vagrant/vagrant/logs/php_debug.log', 'person cont viewAction 99 >'.PHP_EOL, FILE_APPEND | LOCK_EX);	ob_start();
	    //var_dump("this->base_url=", $this->base_url,"END");
	    //var_dump("_SERVER ['SERVER_NAME']=", $_SERVER ['SERVER_NAME'] . $_SERVER ['REQUEST_URI'],"END");
	    //echo "zend_version=" . zend_version() . PHP_EOL;
	    //echo "phpversion=" . phpversion() . PHP_EOL;
	    //var_dump ($this->serverUrl());
	    //var_dump("facility_data=",$this->facility_data[0],"END");
	    //$toss = ob_get_clean(); file_put_contents('/vagrant/vagrant/logs/php_debug.log', $toss .PHP_EOL, FILE_APPEND | LOCK_EX);
	
	     
	    //if (! $this->hasACL ( 'view_assessment' ) and ! $this->hasACL ( 'edit_assessment' ) ) {
	      //  $this->doNoAccessError ();
	    //}
	
	    if ($this->hasACL ( 'edit_assessment' ) or true ) {
	        //redirect to edit mode
	        $this->_redirect ( str_replace ( 'view', 'edit', '//' . $_SERVER ['SERVER_NAME'] . ':' . $_SERVER ['SERVER_PORT'] . $_SERVER ['REQUEST_URI'] ) );
	    }
	
	    $this->view->assign ( 'mode', 'view' );
	    $rtn = $this->doAddEditView ();
	    return $rtn;
	}
	
	public function searchAction(){
	    require_once ('models/table/OptionList.php');
	    
	    // assessemnts list
	    $criteria = array ();
	    $criteria ['first_name'] = $this->getSanParam ( 'first_name' );
	    $criteria ['last_name'] = $this->getSanParam ( 'last_name' );
	    $criteria ['facility_id'] = $this->getSanParam ( 'facility_id' );
	    $criteria ['assessment_type_id'] = $this->getSanParam ( 'assessment_type_id' );
	    
	    $criteria['start-day'] = $this->getSanParam ( 'start-day' );
        $criteria['start-month'] = $this->getSanParam ( 'start-month' );
        $criteria['start-year'] = $this->getSanParam ( 'start-year' );
        $criteria['end-day'] = $this->getSanParam ( 'end-day' );
        $criteria['end-month'] = $this->getSanParam ( 'end-month' );
        $criteria['end-year'] = $this->getSanParam ( 'end-year' );

        $criteria ['start_date'] = $this->getSanParam ( 'start-year' ) . '-' . $this->getSanParam ( 'start-month' ) . '-' . $this->getSanParam ( 'start-day' );
        $criteria ['end_date'] = $this->getSanParam ( 'end-year' ) . '-' . $this->getSanParam ( 'end-month' ) . '-' . $this->getSanParam ( 'end-day' );
        
	    $criteria ['outputType'] = $this->getSanParam ( 'outputType' );
	    $criteria ['go'] = $this->getSanParam ( 'go' );

	    file_put_contents('/vagrant/vagrant/logs/php_debug.log', 'assessmentController searchAction >'.PHP_EOL, FILE_APPEND | LOCK_EX);	ob_start();
	    var_dump("criteria=", $criteria, "END");
	    $toss = ob_get_clean(); file_put_contents('/vagrant/vagrant/logs/php_debug.log', $toss .PHP_EOL, FILE_APPEND | LOCK_EX);
	    
	    if ($criteria ['go']) {
	        $db = Zend_Db_Table_Abstract::getDefaultAdapter ();
	        	
	        $sql = 
'select
pa.id,	            
p.first_name,
p.last_name,
f.facility_name,
pa.date_created,
lat.assessment_type
from person p
join facility f on p.facility_id = f.id
join person_to_assessments pa on p.id = pa.person_id
join assessments a on pa.assessment_id = a.id
join lookup_assessment_types lat on a.assessment_type_id = lat.id
where';
	        	    
	        $where = array ();
	        //$where [] = ' assessment.is_deleted = 0 ';
	        $where [] = ' 0 = 0 ';
	        
	        $link = mysqli_connect(Settings::$DB_SERVER, Settings::$DB_USERNAME, Settings::$DB_PWD, "");
	        
	        /* check connection */
	        if (mysqli_connect_errno()) {
	            printf("Connect failed: %s\n", mysqli_connect_error());
	            exit();
	        }
	        
	        if ($criteria ['first_name']) {
	            $where [] = " first_name LIKE '%" . mysqli_real_escape_string ( $link, $criteria ['first_name'] ) . "%'";
	        }

	        if ($criteria ['last_name']) {
	            $where [] = " last_name LIKE '%" . mysqli_real_escape_string ($link,  $criteria ['last_name'] ) . "%'";
	        }
	        	
	        if ($criteria ['assessment_type_id']) {
	            $where [] = ' a.assessment_type_id = ' . $criteria ['assessment_type_id'];
	        }
	        	
	        if ($criteria ['facility_id']) {
	            $where[] = ' p.facility_id = ' . $criteria['facility_id'];
	        }
	        
	        if ($criteria ['start_date'] && $criteria ['start_date'] != '--') {
	            $where [] = ' date_created  >= ' . "'" . $criteria['start_date'] . "'";
	        }
	        
	        if ($criteria ['end_date'] && $criteria ['end_date'] != '--') {
	            $where [] = ' date_created  <= ' . "'" . $criteria['end_date'] . "'";
	        }
	        	       	
	        if ($where)
	            $sql .= implode ( ' AND ', $where );
	        	
	        //$sql .= " GROUP BY facility.id "; // bugfixes dual (depricated) column "sponsor_option_id" and linked lookup table "facility_sponsors", todo: OK to remove this when above TODO is fixed
	        	
	        $sql .= " ORDER BY " . " pa.id ASC ";
	        	
	        file_put_contents('/vagrant/vagrant/logs/php_debug.log', 'assessmentController searchAction >'.PHP_EOL, FILE_APPEND | LOCK_EX);	ob_start();
	        var_dump("sql=", $sql, "END");
	        $toss = ob_get_clean(); file_put_contents('/vagrant/vagrant/logs/php_debug.log', $toss .PHP_EOL, FILE_APPEND | LOCK_EX);
	        
	        $rowArray = $db->fetchAll ( $sql );
	        
	        if ($criteria ['outputType']) {
	    
	            $this->sendData ( $rowArray );
	        }
	        	
	        $this->viewAssignEscaped ( 'results', $rowArray );
	        $this->view->assign ( 'count', count ( $rowArray ) );
	    }
	    
	    $helper = new Helper();

	    $facilities = $helper->getFacilities();
	    $assessment_types = $helper->getAssessmentTypes();
	
	    $this->view->assign('title',$this->view->translation['Application Name']);
	    $this->view->assign('facilities',$facilities);
	    $this->view->assign('assessment_types',$assessment_types);
	    
	    $current_year = date("Y");
	    $current_month = date("m");
	    $current_day = date("d");
	    
	    $start_date = date_create(date("Y-m-d"));
	    date_sub($start_date, date_interval_create_from_date_string("6 weeks"));
	    
	    $start_year = date_format($start_date, "Y");
	    $start_month = date_format($start_date, "m");
	    $start_day = date_format($start_date, "d");
	    
	    if ( !$criteria['start-year'] ) { $criteria['start-year'] = $start_year; } 
	    if ( !$criteria['start-month'] ) { $criteria['start-month'] = $start_month; }
	    if ( !$criteria['start-day'] ) { $criteria['start-day'] = $start_day; }
	   
	    if( !$criteria['end-year'] ) { $criteria['end-year'] = $current_year; }
	    if( !$criteria['end-month'] ) { $criteria['end-month'] = $current_month; }
	    if( !$criteria['end-day'] ) { $criteria['end-day'] = $current_day; }
	    
	    $this->view->assign ( 'criteria', $criteria );
	    
	}
	
	public function testaddAction() {
	    require_once ('models/table/OptionList.php');
	    //if ( ! $this->hasACL ( 'edit_assessment' )) { 
			//$this->doNoAccessError ();
		//}
		
		file_put_contents('/vagrant/vagrant/logs/php_debug.log', 'assessmentController testaddAction >'.PHP_EOL, FILE_APPEND | LOCK_EX);	ob_start();
		$toss = ob_get_clean(); file_put_contents('/vagrant/vagrant/logs/php_debug.log', $toss .PHP_EOL, FILE_APPEND | LOCK_EX);
		
		$criteria = array ();
		$criteria ['first_name'] = $this->getSanParam ( 'first_name' );
		$criteria ['last_name'] = $this->getSanParam ( 'last_name' );
		$criteria ['facility_id'] = $this->getSanParam ( 'facility_id' );
		$criteria ['assessment_type_id'] = $this->getSanParam ( 'assessment_type_id' );
		
		$criteria['add-day'] = $this->getSanParam ( 'add-day' );
		$criteria['add-month'] = $this->getSanParam ( 'add-month' );
		$criteria['add-year'] = $this->getSanParam ( 'add-year' );
		
		$request = $this->getRequest ();
		$validateOnly = $request->isXmlHttpRequest ();
		
		if ($validateOnly)
			$this->setNoRenderer ();
		
		if ($request->isPost ()) {
		    
			$paObj = new PersonToAssessments ();
			$obj_id = $this->validateAndSave ( $paObj->createRow (), false );
				
			// validate
			$status = ValidationContainer::instance ();
			if ($obj_id) {
				$status->setObjectId ( $obj_id );
			}
			
			if ($validateOnly) {
			    $this->sendData ( $status );
			} else {
			    $this->view->assign ( 'status', $status );
			}
		}
		
		$helper = new Helper();
		
		$facilities = $helper->getFacilities();
		$assessment_types = $helper->getAssessmentTypes();
		
		$this->view->assign('title',$this->view->translation['Application Name']);
		$this->view->assign('facilities',$facilities);
		$this->view->assign('assessment_types',$assessment_types);
		
		$current_year = date("Y");
		$current_month = date("m");
		$current_day = date("d");
		
		if( !$criteria['add-year'] ) { $criteria['add-year'] = $current_year; }
		if( !$criteria['add-month'] ) { $criteria['add-month'] = $current_month; }
		if( !$criteria['add-day'] ) { $criteria['add-day'] = $current_day; }
		
		$this->view->assign ( 'criteria', $criteria );
		
	}
	
	public function addAction() {
	    require_once ('models/table/OptionList.php');
	    //if ( ! $this->hasACL ( 'edit_assessment' )) { 
			//$this->doNoAccessError ();
		//}
		
		file_put_contents('/vagrant/vagrant/logs/php_debug.log', 'assessmentController testaddAction >'.PHP_EOL, FILE_APPEND | LOCK_EX);	ob_start();
		$toss = ob_get_clean(); file_put_contents('/vagrant/vagrant/logs/php_debug.log', $toss .PHP_EOL, FILE_APPEND | LOCK_EX);
		
		$criteria = array ();
		$criteria ['first_name'] = $this->getSanParam ( 'first_name' );
		$criteria ['last_name'] = $this->getSanParam ( 'last_name' );
		$criteria ['facility_id'] = $this->getSanParam ( 'facility_id' );
		$criteria ['assessment_type_id'] = $this->getSanParam ( 'assessment_type_id' );
		
		$criteria['add-day'] = $this->getSanParam ( 'add-day' );
		$criteria['add-month'] = $this->getSanParam ( 'add-month' );
		$criteria['add-year'] = $this->getSanParam ( 'add-year' );
		
		$request = $this->getRequest ();
		$validateOnly = $request->isXmlHttpRequest ();
		
		if ($validateOnly)
			$this->setNoRenderer ();
		
		if ($request->isPost ()) {
		    
			$paObj = new PersonToAssessments ();
			$obj_id = $this->validateAndSave ( $paObj->createRow (), false );
				
			// validate
			$status = ValidationContainer::instance ();
			if ($obj_id) {
				$status->setObjectId ( $obj_id );
			}
			
			if ($validateOnly) {
			    $this->sendData ( $status );
			} else {
			    $this->view->assign ( 'status', $status );
			}
		}
		
		$helper = new Helper();
		
		$facilities = $helper->getFacilities();
		$assessment_types = $helper->getAssessmentTypes();
		
		$this->view->assign('title',$this->view->translation['Application Name']);
		$this->view->assign('facilities',$facilities);
		$this->view->assign('assessment_types',$assessment_types);
		
		$current_year = date("Y");
		$current_month = date("m");
		$current_day = date("d");
		
		if( !$criteria['add-year'] ) { $criteria['add-year'] = $current_year; }
		if( !$criteria['add-month'] ) { $criteria['add-month'] = $current_month; }
		if( !$criteria['add-day'] ) { $criteria['add-day'] = $current_day; }
		
		$this->view->assign ( 'criteria', $criteria );
	     
	}
	
	public function doAddView(){
	     
	    $person_to_assessment_id = $this->getSanParam ( 'id' );
	     
	    //file_put_contents('/vagrant/vagrant/logs/php_debug.log', 'doAddEditView >'.PHP_EOL, FILE_APPEND | LOCK_EX);	ob_start();
	    //var_dump("pa.id=", $person_to_assessment_id, "END");
	    //$toss = ob_get_clean(); file_put_contents('/vagrant/vagrant/logs/php_debug.log', $toss .PHP_EOL, FILE_APPEND | LOCK_EX);
	     
	    $helper		= new Helper();
	    $request	= $this->getRequest ();
	    if ($request->isPost ()) {
	        $helper->saveAssessmentAnswers($this->getSanParam ('question'), $person_to_assessment_id);
	        $this->_redirect (  'assessment/edit/id/' . $person_to_assessment_id );
	    }
	
	    $person_to_assessment_id = $this->getSanParam ( 'id' );
	    $this->view->assign ( 'person_to_assessemnt_id', $person_to_assessment_id );
	
	    $assessments = $helper->getPersonAssessmentsDetailed($person_to_assessment_id);
	    $this->view->assign ( 'assessments', $assessments );
	}
	
	
	
	public function doEditView(){
	    
	    $person_to_assessment_id = $this->getSanParam ( 'id' );
	    
	    //file_put_contents('/vagrant/vagrant/logs/php_debug.log', 'doAddEditView >'.PHP_EOL, FILE_APPEND | LOCK_EX);	ob_start();
	    //var_dump("pa.id=", $person_to_assessment_id, "END");
	    //$toss = ob_get_clean(); file_put_contents('/vagrant/vagrant/logs/php_debug.log', $toss .PHP_EOL, FILE_APPEND | LOCK_EX);
	    
	    $helper		= new Helper();
	    $request	= $this->getRequest ();
	    if ($request->isPost ()) {
	        $helper->saveAssessmentAnswers($this->getSanParam ('question'), $person_to_assessment_id);
	        $this->_redirect (  'assessment/edit/id/' . $person_to_assessment_id );
	    }
	
	    $person_to_assessment_id = $this->getSanParam ( 'id' );
	    $this->view->assign ( 'person_to_assessemnt_id', $person_to_assessment_id );
	
	    $assessments = $helper->getPersonAssessmentsDetailed($person_to_assessment_id);
	    $this->view->assign ( 'assessments', $assessments );
	}
	
	public function viewassessmentAction(){
	    $person_id = $this->getSanParam ( 'id' );
	    $this->view->assign ( 'person_id', $person_id );
	
	    $helper = new Helper();
	    $assessments = $helper->getPersonAssessmentsDetailed($person_id);
	    $this->view->assign ( 'assessments', $assessments );
	}

	public function assessmentfindAction(){
		$people = new Peoplefind();

		$converted = false;
		if( !empty($_GET) ){
			$_POST = $_GET;
			$converted = true;
		}
		if(!$converted && (!empty($_POST) || !empty($_GET))){
			$params_query = http_build_query($_POST);
			header("Location:http://{$_SERVER['HTTP_HOST']}/peoplefind/peoplefind?{$params_query}");
		}

		$param = $_GET;
		if( empty($_GET) ){ $param = $_POST; }
		
		$search = $people->peoplesearch($param);

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

		$this->view->assign('getpeople',$search);

	}

}
?>