<?php
/*
 * Created on Dec 7, 2009
 *
 *  Built for ITech
 *  Fuse IQ -- todd@fuseiq.com
 *
 */
require_once('ITechTable.php');
require_once('Zend/Mail.php');

class TrainingApprovalHistory extends ITechTable
{
	protected $_primary = 'id';
  	protected $_name = 'training_approval_history';
  	
  	public function insert(array $data)
    {
    	
     	$auth = Zend_Auth::getInstance();
        $user_id = $auth->getIdentity()->id;
    	$user_table = new User();
    	$user_row = $user_table->fetchRow('id = '.$user_id);
   		$data['created_by'] = $user_id;
		if ( !isset($data['approval_status']) or !$data['approval_status'])
			$data['approval_status'] = 'new';
    		
    	//get recipients
    	$training_id = $data['training_id'];
    	$select = $this->select()->setIntegrityCheck(false)->from($this->_name)
        	->join(array('u' => 'user'), "training_approval_history.created_by = u.id",array('email', 'first_name','last_name'))
    		->where("training_id = $training_id AND u.is_blocked = 0");
    	$previous_history_rows = $this->fetchAll($select);
 		
    	$recipients = array();
    	foreach($previous_history_rows as $rec ) {
    		$recipients[$rec->created_by] = array('email'=>$rec->email,'name'=>$rec->first_name.' '.$rec->last_name);
    	}
   		//send to anyone other than creator
    	unset($recipients[$user_id]);
    	
 		//insert the row
		$data['recipients'] = implode(',', array_keys($recipients));
		parent::insert($data);
		
		//send the mail	
		#echo print_r($recipients, true) . '//'.$data['approval_status'];
		#$recipients = array('name' => 'jgh23@uw.edu', 'email' => 'jgh23@uw.edu');
		if ($recipients && $data['approval_status']) {
	   		require_once('models/table/Training.php');
	    	$training = new Training();
	    	$training_name = $training->getCourseName($training_id);
	    	
			$view = new Zend_View();
			$view->setScriptPath(Globals::$BASE_PATH.'/app/views/scripts/email');
			$view->assign('creator', $user_row->first_name.' '.$user_row->last_name);
			$view->assign('training_name', $training_name);
			$view->assign('comments',$data['message']);
			$view->assign('link', Settings::$COUNTRY_BASE_URL.'/training/edit/id/'.$training_id);
			$mail = new Zend_Mail();
			
			switch($data['approval_status']) {
				case 'approved':
					$text = $view->render('text/approved.phtml');
					$html = $view->render('html/approved.phtml');
					$mail->setSubject(t('Training').' '.t('Approved'));
					break;
				case 'rejected':
					$text = $view->render('text/rejected.phtml');
					$html = $view->render('html/rejected.phtml');
					$mail->setSubject(t('Training').' '.t('Rejected'));
					break;
				case 'resubmitted':
					$text = $view->render('text/resubmitted.phtml');
					$html = $view->render('html/resubmitted.phtml');
					$mail->setSubject(t('Training').' '.t('Resubmitted'));
					break;
			}
			
			
			$mail->setBodyText($text);
			$mail->setBodyHtml($html);
			$mail->setFrom(Settings::$EMAIL_ADDRESS, Settings::$EMAIL_NAME);
			foreach($recipients as $guy) {
				$mail->addTo($guy['email'],  $guy['name']);
			}
			//$mail->send();
		}
    }
}

?>