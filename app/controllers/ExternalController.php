<?php
/*
 * Created on Dec 3, 2009
 *
 *  Built for web
 *  Fuse IQ -- todd@fuseiq.com
 *
 */
  require_once('ITechController.php');
  require_once('models/table/ExternalCourse.php');
   require_once('models/table/Person.php');
 
  /**
   * Handles external classes
   *
   */
class ExternalController extends ITechController
{
   public function __construct(Zend_Controller_Request_Abstract $request, Zend_Controller_Response_Abstract $response, array $invokeArgs = array())
    {
        parent::__construct($request, $response, $invokeArgs);
    }

    public function init()
    {

    }

   public function listByParticipantAction() {
			//class info
			$courseObj = new ExternalCourse();
			$rowArray = $courseObj->findFromParticipant($this->_getParam('id'));
      		$this->sendData($rowArray);
	  }

	public function addAction() {
		$person_id = $this->_getParam('id');
		
    	$request = $this->getRequest();
		if ( $request->isPost() ) {
	    	//validate
	    	$status = ValidationContainer::instance();
			$status->checkRequired($this, 'title',$this->tr('Title'));
			$training_start_date = (@$this->getSanParam('start-year')).'-'.(@$this->getSanParam('start-month')).'-'.(@$this->getSanParam('start-day'));
			if ( $training_start_date !== '--' and $training_start_date !== '0000-00-00')
				$status->isValidDate($this, 'start-day' , t('Training').' '.t('start'), $training_start_date );
	 		
			if ( $status->hasError() ) {
     			$status->setStatusMessage(t('The person could not be saved.'));
    		} else {
    			$ecourseObj = new ExternalCourse();
	 			$ecourseRow = $ecourseObj->createRow();
				$ecourseRow->person_id = $person_id;
			
  				$ecourseRow->title = $this->getSanParam('title');
  				$ecourseRow->training_funder = $this->getSanParam('training_funder');
  				$ecourseRow->training_location = $this->getSanParam('training_location');
 				$ecourseRow->training_start_date = $training_start_date;
  				$ecourseRow->training_length_value = $this->getSanParam('training_length_value');
  				
   				if ( $id = $ecourseRow->save() ) {
    				$status->setStatusMessage('The new course was created.');
    				$this->_redirect('person/edit/id/'.$person_id);
    				} else {
     					$status->setStatusMessage(t('The external course could not be saved.'));
   				}
			
    		}
   	}
    	
    	$person = new Person();
    	$personRow = $person->fetchRow('id = '.$person_id); 
    	$this->view->assign('person', $personRow->toArray());  	
	}
}
