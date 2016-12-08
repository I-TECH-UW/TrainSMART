<?php
/*
 * Created Oct 22, 2010
 *
 *  Built for web
 *  Fuse IQ -- nick@fuseiq.com
 *
 */

//require_once ('ZendFramework/library/Zend/Auth.php');
//require_once ('app/models/Session.php');


require_once ('app/models/table/ITechTable.php');
require_once ('app/models/table/SyncFile.php');
require_once ('app/models/table/SyncLog.php');

require_once ('app/controllers/ITechController.php');
require_once ('app/controllers/ReportFilterHelpers.php');
require_once ('app/controllers/sync/SyncCompare.php');

class SyncController extends ReportFilterHelpers
{
	private $status = null;// ValidationContainer
	
	public function __construct(Zend_Controller_Request_Abstract $request, Zend_Controller_Response_Abstract $response, array $invokeArgs = array())
	{
//		ini_set('max_execution_time',120);
//		set_time_limit(120);
		set_time_limit(300);
		parent::__construct($request, $response, $invokeArgs);
	}
	
	public function preDispatch()
	{
		$return = parent::preDispatch();
		if(!$this->isLoggedIn()) {
			$this->doNoAccessError();
		}
		if(!$this->hasACL('edit_country_options')) {
			$this->doNoAccessError();
		}
		
		
		$this->status = ValidationContainer::instance();
		
		return $return;
	}
	
	public function indexAction()
	{
		$fid = $this->getSanParam('fid');
		$this->view->assign('uploadUrl', $this->view->base_url . '/sync/upload/');
		$this->view->assign('searchUrl', $this->view->base_url . '/sync/search/fid/' . $fid);
		$this->view->assign('reviewUrl', $this->view->base_url . '/sync/review/fid/' . $fid);
		$this->view->assign('commitUrl', $this->view->base_url . '/sync/commit/fid/' . $fid);
	}
	

	
	/*
	 * Upload a new sync *.sqlite file and update server db  
	 */
	public function uploadAction()
	{
		$user = Zend_Auth::getInstance()->getIdentity();
		
		$result = false;
		// move upload file 
		if($this->getRequest()->isPost() && !empty($_FILES)) {
			// check extension 
			$name = $_FILES['upload']['name'];
			if(strpos(strrev($name), strrev('.sqlite')) !== 0) {
				$this->status->setStatusMessage(t('There was a problem with file types <code>' . $name . '</code>'));
				return;
			}
			
			$name = implode('.', array($user->id, $user->last_name, $user->first_name, date('Y-m-d'), $_FILES['upload']['name'])); 
			$path = rtrim(Globals::$BASE_PATH, '/') . '/files_sync/';
			if(!file_exists($path) && !mkdir($path, 0777, true)) { // make storage dir 
				$this->status->setStatusMessage(t('There was a problem creating <code>' . $path . '</code>'));
				return;
			}
			$result = move_uploaded_file($_FILES['upload']['tmp_name'], $path . $name);
			if(!$result) {
				$this->status->setStatusMessage(t('There was a problem moving files <code>' . $path . $name . '</code>'));
				return;
			}
		} else if ($this->getRequest()->isPost()) { // empty _$files, user has exceeded upload size on server
			$max_post_size = trim ( ini_get('post_max_size') );
			$last = strtolower($max_post_size[strlen($max_post_size)-1]);
			switch($last) {
				case 'g':
					$max_post_size *= 1024;
				case 'm':
					$max_post_size *= 1024;
				case 'k':
					$max_post_size *= 1024;
			}
			if ($_SERVER['CONTENT_LENGTH'] > $max_post_size) {
				$this->status->setStatusMessage(t('The file uploaded to the server was too large, maximum file size:').space.ini_get('post_max_size'));
			} else {
				$this->status->setStatusMessage(t('There was a problem uploading the file to the server'));
			}
			return; // exit and error
		}
		// create entry for file  
		if($result) {
			// source info 
			$db = SyncCompare::getDesktopConnectionParams('_app',$path . $name);
			try {
				$_app = new ITechTable($db);
	
				$_app = $_app->fetchAll()->current();
				
				//look for previous sync time
				$previous_files = new SyncFile();
				$previous = $previous_files->getAdapter()->query("SELECT * FROM syncfile WHERE application_id = '".($_app->app_id)."' AND timestamp_completed IS NOT NULL ORDER BY timestamp_completed DESC LIMIT 1");
				
				$previous_timestamp = $_app->init_timestamp; //first time
				if ( $previous ) {
					$previous = $previous->fetchAll();
					if ( $previous )
						$previous_timestamp = $previous[0]['timestamp_completed'];
				}
				
				$save = array(
					'filename' => $name, //update this later, need to put fid in the file name
					'filepath' => $path,
					'application_id' => $_app->app_id,
					'application_version' => 1, //$_app->app_version,
					'timestamp_last_sync' => $previous_timestamp
				);
				// dest info 
				$syncFile = new SyncFile();
				$result = $syncFile->insert($save);
				
				if ( $result ) {
					$newname = $result.'.'.$name;
					$good = rename($path.$name, $path.$newname);
					if ( $good ) {
						$syncFile->update(array('filename' => $newname), 'id = '.$result);
					}
				}
				
				if(!$result) {
					$msg = "Could not save file.";
					$this->status->setStatusMessage($msg);
				} else {
					$this->status->setStatusMessage(t('Sync database file uploaded.'));
					$this->_redirect('sync/search/fid/' . $result); // .'/startjob/1/outputType/text' search for db changes   
					
				}
			} catch( Exception $e) {
				$msg = t('Invalid desktop database file');
			}
		}		

	}
	
	/*
	 * Search server db for differences
	 */
	public function searchAction(){
		$fid = $this->getSanParam('fid');
		
		// check for file 
		if(empty($fid)) {
			$this->status->setStatusMessage(t('Please upload a file to continue'));
			$this->_redirect('sync/upload');
		}
		// status dump for async 
		if($this->getSanParam('statuscheck')) {
		    //TA:#303 uncomment // comment for debuging otherwise local MySQL is crushed
//			$syncLog = new SyncLog($fid);
//			$remaining = count(SyncCompare::$compareTypes) - $syncLog->getDiffStatus();
//			$this->sendData ( array($remaining) );
		    $this->sendData ( array(count(SyncCompare::$compareTypes)) ); //TA:#303 use for debiging
			return;
		} else if($this->getSanParam('startjob')) {// thread start for async 
			try {
				$syncCompare = new SyncCompare($fid);
				$msg = $syncCompare->sanityCheck();
				if ( !$msg ) {
					$has_errors = $syncCompare->findDifferencesProcess();
				} else {
					$has_errors = $msg;
				}
			} catch (Exception  $e) {
				$has_errors = $e->getMessage();
			}
			
			if($has_errors) {
				$this->sendData ( array("Uploading process is completed with errors \n".print_r($has_errors,true)) );
				
			} else {
				$this->sendData ( array('Uploading process is completed with folowing database tables:') );
			}
			return;
		}
		
		$this->view->assign('fid', $fid);
	}
	
	/*
	 * Present list with pending changes. 
	 * Update list if form submit, or click to commitAction()
	 */
	public function reviewAction()
	{
		$fid = $this->getSanParam('fid');
		// check for file 
		if(empty($fid)) {
			$this->status->setStatusMessage(t('Please upload a file to continue'));
			$this->_redirect('sync/upload');
		}
		
		$syncLog = new SyncLog($fid);
		
		if ($this->getRequest()->isPost()) {
			//check for skipped items
			$id_array = $this->getSanParam('id');
			if ( $id_array ) {
				foreach($id_array as $id => $option) {
					if ( $option == 'skip' ) {
						$syncLog->markSkip($id,1);
					} else {
						$syncLog->markSkip($id,0);
					}
				}
			}
			
			$this->_redirect('sync/commit/fid/'.$fid);
		}
		
		// show the pending changes and allow click to commitAction()
		$totals = $syncLog->pendingTotals();
		$list = $syncLog->pendingList();
		
		//index by type and action
		$stats = array();
		foreach($totals as $t) {
			if ( !isset($stats[$t['item_type']]))
				$stats[$t['item_type']] = array();
				
			$stats[$t['item_type']] [$t['action']] = $t['cnt'];
		}
		
		$this->view->assign('stats', $stats);
		
		//index by type and action
		$groupedlist = array();
		foreach($list as $t) {
			
			$type = $t->item_type;
			$act = $t->action;
			if ( !isset($groupedlist[$type]))
				$groupedlist[$type] = array();
			
			if ( !isset($groupedlist[$type][$act]))
				$groupedlist[$type][$act] = array();
			
			$data = $t->toArray();
			
			$groupedlist[$type][$act] []= $data;
		}
		
		$this->view->assign('items', $groupedlist);
		
		
	}
	
	/*
	 * Run selected changes and display what's done 
	 */
	public function commitAction()
	{
		$fid = $this->getSanParam('fid');
		$sc = new SyncCompare($fid);
		$errors = $sc->doUpdatesProcess();
		if ( $errors )
			$this->view->assign('errors',count($errors).' errors :'.print_r($errors,true));
		else
			$this->view->assign('errors', t('Done'));
	}

}