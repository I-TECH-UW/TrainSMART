<?php
/*
 * Created on Feb 11, 2008
 *
 *  Built for web
 *  Fuse IQ -- todd@fuseiq.com
 *
 */
require_once ('ITechController.php');

class IndexController extends ITechController {
	public function __construct(Zend_Controller_Request_Abstract $request, Zend_Controller_Response_Abstract $response, array $invokeArgs = array()) {
		parent::__construct ( $request, $response, $invokeArgs );
	}

	public function init() {	}

	public function indexAction() {

		if($this->hasACL('edit_employee') && $this->setting('module_employee_enabled')){
			if($this->hasACL('in_service') == false && $this->hasACL('pre_service') == false) {
				$this->_redirect('employee');
				exit();
			}
		}

		if (strstr($_SERVER['REQUEST_URI'],'index/index') === false) {
			if ($this->hasACL ( 'in_service' )) {
				$this->_redirect('index/index');
				exit();
			} elseif ($this->hasACL ( 'pre_service' )) {
				$this->_redirect('dash/dash');
				exit();
			}

			$this->_redirect('index/index');
			exit();
		}

		$db = Zend_Db_Table_Abstract::getDefaultAdapter ();
		$sql = "SELECT q2.qualification_phrase, COUNT(q2.qualification_phrase) CNT FROM person p INNER JOIN person_qualification_option q1 ON p.primary_qualification_option_id = q1.id INNER JOIN person_qualification_option q2 ON q1.parent_id = q2.id WHERE p.is_deleted = 0 GROUP BY q2.qualification_phrase;";
		$rowyArray1 = $db->fetchAll ( $sql );
		$sql = "SELECT q3.qualification_phrase, 0 CNT FROM person_qualification_option q3 WHERE q3.id NOT IN (SELECT q2.id CNT FROM person p INNER JOIN person_qualification_option q1 ON p.primary_qualification_option_id = q1.id INNER JOIN person_qualification_option q2 ON q1.parent_id = q2.id WHERE p.is_deleted = 0) AND q3.parent_id IS NULL;";
		$rowyArray2 = $db->fetchAll ( $sql );
		$rowyArray = array_merge($rowyArray1, $rowyArray2);
		$this->viewAssignEscaped ( 'rowy', $rowyArray );
		$sql = "SELECT p.comments FROM person p INNER JOIN person_qualification_option q1 ON p.primary_qualification_option_id = q1.id INNER JOIN person_qualification_option q2 ON q1.parent_id = q2.id WHERE p.is_deleted = 0 AND q2.id = 8";
		$rowsArray = $db->fetchAll ( $sql );
		$NIMART = 0;
		foreach($rowsArray as $key => $row) {
			$NIMARTsplit=split("§",$rowsArray[$key]['comments']);
			if(strlen($NIMARTsplit[21])>0) {
				if($NIMARTsplit[21] = "Nurse Initiating ART") {
					$NIMART = $NIMART + 1;
				}
			}
		}
		$this->viewAssignEscaped ( 'NIMART', $NIMART );

		// retrieve list of incomplete courses created by user
		if ($this->hasACL ( 'edit_course' )) {
			require_once 'models/table/Training.php';
			require_once 'models/Session.php';
			require_once 'views/helpers/EditTableHelper.php';
			require_once('views/helpers/TrainingViewHelper.php');

			$uid = Session::getCurrentUserId ();

			// Find incomplete training and future trainings
			$trainingFields = array ('id' => t('ID'),'training_title' => t ( 'Course Name' ), 'training_start_date' => t ( 'Start Date' ), 'training_location_name' => t ( 'Training Center' ), 'budget_code' => t('Budget Code'),'creator' => t ( 'Created By' ) );
			if(!$this->setting('display_budget_code')) {
				unset($trainingFields['budget_code']);
			}

			foreach ( array_keys ( $trainingFields ) as $key ) {
				$colCustom [$key] = 'sortable:true';
			}

			$colStatic = array_keys ( $trainingFields ); // all


			$editLinkInfo ['disabled'] = 1;

			$lkeys = array_keys ( $trainingFields );
			unset($lkeys[0]); // cant link id, it breaks html (its already defined twice)
			$linkInfo = array (  // add links to datatable fields
				'linkFields' => $lkeys, // all
				'linkId' => 'id', // use ths value in link
				'linkUrl' => Settings::$COUNTRY_BASE_URL . '/training/edit/id/%id%' );

			// restricted access?? only show trainings we have the ACL to view // add this to every query
			$org_allowed_ids = allowed_org_access_full_list($this); // doesnt have acl 'training_organizer_option_all'
			$allowedWhereClause = $org_allowed_ids ? " AND training_organizer_option_id in ($org_allowed_ids) " : "";

			// restricted access?? only show organizers that belong to this site if its a multi org site
			$site_orgs = allowed_organizer_in_this_site($this); // for sites to host multiple training organizers on one domain
			$allowedWhereClause .= $site_orgs ? " AND training_organizer_option_id in ($site_orgs) " : "";

			// Incomplete
			$tableObj = new Training ( );
			$rowsPast = $tableObj->getIncompleteTraining ( $uid, 'training_start_date < NOW() '.$allowedWhereClause )->toArray();
			if ($rowsPast) {
				$html = EditTableHelper::generateHtmlTraining ( 'TrainingPast', $rowsPast, $trainingFields, $colStatic, $linkInfo, $editLinkInfo, $colCustom );
				$this->view->assign ( 'tableTrainingPast', $html );
			}

			// Future
			$tableObj = new Training ( );
			$rowsFuture = $tableObj->getIncompleteTraining ( $uid, 'training_start_date >= NOW()'.$allowedWhereClause, '' )->toArray();
			if ($rowsFuture) {
				$html = EditTableHelper::generateHtmlTraining ( 'TrainingFuture', $rowsFuture, $trainingFields, $colStatic, $linkInfo, $editLinkInfo, $colCustom );
				$this->view->assign ( 'tableTrainingFuture', $html );
			}

			// Unapproved
			if ($this->setting ( 'module_approvals_enabled' )) {
				$tableObj = new Training ( );
				$unapproved = $tableObj->getUnapprovedTraining ("1".$allowedWhereClause); // everything
				if ($unapproved) {
					$linkInfoUnapprov = $linkInfo;
					if (! $this->hasACL ( 'approve_trainings' )) {
						$linkInfoUnapprov ['linkFields'] = array ('training_title');
					}

					$trainingFieldsUnapprov = $trainingFields;
					unset($trainingFieldsUnapprov['province_name']); // optional - but they not be built into the query
					unset($trainingFieldsUnapprov['budget_code']);
					$trainingFieldsUnapprov['message'] = t('Message');
					$colStatic['message'] = 'message';

					$html = EditTableHelper::generateHtmlTraining ( 'unapproved', $unapproved, $trainingFieldsUnapprov, $colStatic, $linkInfoUnapprov, $editLinkInfo, $colCustom );
					$this->view->assign ( 'tableUnapproved', $html );
				}
			}

			//YTD, start at April 1
			if ( $ytdStart = $this->setting('fiscal_year_start') ) {
				$ytdStart = date('Y-n-j', strtotime($ytdStart));
				$this->view->assign ( 'ytdStart', $ytdStart );
				//get total unique participants
				$db = Zend_Db_Table_Abstract::getDefaultAdapter ();
				$sql = "SELECT COUNT(DISTINCT person_id) as \"unique_p\" from person_to_training left join training on (training.id = training_id and training.is_deleted = 0) where training_start_date > ".$ytdStart.$allowedWhereClause;
				$rowArray = $db->fetchRow ( $sql );
				$this->view->assign ( 'unique_participants', $rowArray ['unique_p'] );
			} else {
			$ytdStart = (date ( 'Y' ) - ((date ( 'n' ) < 4) ? 1 : 0)) . '-04-01';
			$this->view->assign ( 'ytdStart', $ytdStart );
			//get total unique participants
			$db = Zend_Db_Table_Abstract::getDefaultAdapter ();
				$sql = "SELECT COUNT(DISTINCT person_id) as \"unique_p\" from person_to_training left join training on (training.id = training_id and training.is_deleted = 0) where 1 ".$allowedWhereClause;
			$rowArray = $db->fetchRow ( $sql );
			$this->view->assign ( 'unique_participants', $rowArray ['unique_p'] );
			}

			$allowedOrgJoin = ($allowedWhereClause ? ' LEFT JOIN training ON training.id = training_id WHERE training.is_deleted = 0 '.$allowedWhereClause: ''); // only show trainings we're allowed to see by access level

			//get participants total and by YTD
			$db = Zend_Db_Table_Abstract::getDefaultAdapter ();
			$sql = "SELECT COUNT(person_id) as \"attendees\" from person_to_training".$allowedOrgJoin;
			$rowArray = $db->fetchRow ( $sql );
			$this->view->assign ( 'attendees', $rowArray ['attendees'] );

			$sql = "SELECT COUNT(person_id) as  \"attendees\" FROM training, person_to_training as pt WHERE pt.training_id = training.id AND training_start_date >= '$ytdStart'".$allowedWhereClause;
			$rowArray = $db->fetchRow ( $sql );
			$this->view->assign ( 'attendees_ytd', $rowArray ['attendees'] );

			//get total unique trainers
			if ($allowedOrgJoin) {
				$sql = "SELECT COUNT(DISTINCT trainer_id) as \"unique_t\" FROM training_to_trainer".$allowedOrgJoin; // trainers in viewable trainings
			} else {
			$sql = "SELECT COUNT(person_id) as \"unique_t\" from trainer";
			}
			$rowArray = $db->fetchRow ( $sql );
			$this->view->assign ( 'unique_trainers', $rowArray ['unique_t'] );

			//get total trainers and by YTD
			$sql = "SELECT COUNT(trainer_id) as \"trainers\" FROM training_to_trainer".$allowedOrgJoin;
			$rowArray = $db->fetchRow ( $sql );
			$this->view->assign ( 'trainers', $rowArray ['trainers'] );

			$sql = "SELECT COUNT(tt.trainer_id) as \"trainers\" FROM training, training_to_trainer as tt WHERE tt.training_id = training.id AND training_start_date >= '$ytdStart'".$allowedWhereClause;
			$rowArray = $db->fetchRow ( $sql );
			$this->view->assign ( 'trainers_ytd', $rowArray ['trainers'] );

			//get trainings
			//    total and YTD
			$sql = "SELECT COUNT(id) as \"trainings\", MIN(training_start_date) as \"min_date\",MAX(training_start_date) as \"max_date\"  from training WHERE is_deleted = 0".$allowedWhereClause;
			$rowArray = $db->fetchRow ( $sql );
			$this->view->assign ( 'trainings', $rowArray ['trainings'] );
			$this->view->assign ( 'min_date', $rowArray ['min_date'] );
			$this->view->assign ( 'max_date', $rowArray ['max_date'] );

			$sql = "SELECT COUNT(id) as \"trainings\"  from training WHERE training_start_date >= '$ytdStart' AND is_deleted = 0".$allowedWhereClause;
			$rowArray = $db->fetchRow ( $sql );
			$this->view->assign ( 'trainings_ytd', $rowArray ['trainings'] );

		}

    /****************************************************************************************************************/
    /* Attached Files */
    require_once 'views/helpers/FileUpload.php';

    $PARENT_COMPONENT = 'home';

    FileUpload::displayFiles ( $this, $PARENT_COMPONENT, 1, $this->hasACL ( 'admin_files' ) );
     // File upload form
     if ( $this->hasACL ( 'admin_files' ) ) {
        $this->view->assign ( 'filesForm', FileUpload::displayUploadForm ( $PARENT_COMPONENT, 1, FileUpload::$FILETYPES ) );
     }
    /****************************************************************************************************************/
	}

	public function testAction() {
	}

	public function languageAction() {
		require_once ('models/Session.php');
		require_once ('models/table/User.php');
		if ($this->isLoggedIn () and array_key_exists ( $this->getSanParam ( 'opt' ), ITechTranslate::getLanguages () )) {
			$user = new User ( );
			$userRow = $user->find ( Session::getCurrentUserId () )->current ();
			$user->updateLocale ( $this->getSanParam ( 'opt' ), Session::getCurrentUserId () );

			$auth = Zend_Auth::getInstance ();
			$identity = $auth->getIdentity ();
			$identity->locale = $this->getSanParam ( 'opt' );
			$auth->getStorage ()->write ( $identity );
			setcookie ( 'locale', $this->getSanParam ( 'opt' ), null, Globals::$BASE_PATH );
		}

		$this->_redirect ( $_SERVER ['HTTP_REFERER'] );

	}

	public function jsAggregateAction() {
		#$headers = apache_request_headers ();

		// Checking if the client is validating his cache and if it is current.
		/*
	    if (isset($headers['If-Modified-Since']) && (strtotime($headers['If-Modified-Since']) > time() - 60*60*24)) {
	        // Client's cache IS current, so we just respond '304 Not Modified'.
	        header('Last-Modified: '.gmdate('D, d M Y H:i:s',  time()).' GMT', true, 304);
			$this->setNoRenderer();
	    }
		#echo Globals::$BASE_PATH.Globals::$WEB_FOLDER.$file;
		#exit;
		*/

		$response = $this->getResponse ();
		$response->clearHeaders ();

		//allow cache
		#$response->setHeader ( 'Expires', gmdate ( 'D, d M Y H:i:s', time () + 60 * 60 * 30 ) . ' GMT', true );
		#$response->setHeader ( 'Cache-Control', 'max-age=7200, public', true );
		#$response->setHeader ( 'Last-Modified', '', true );
		#$response->setHeader ( 'Cache-Control',  "public, must-revalidate, max-age=".(60*60*24*7), true ); // new ver TS new JS file
		$response->setHeader ( 'Cache-Control',  "must-revalidate, max-age=".(60*60*24*7), true ); // new ver TS new JS file
		#$response->setHeader ( 'Pragma', 'public', true );
		$response->setHeader ( 'Last-Modified',''.date('D, d M Y H:i:s', strtotime('18 March 2013 19:20')).' GMT', true ); // todo update this when thers a new javascript file to force re dl
		$response->setHeader ( 'Content-type', 'application/javascript' ); // should fix inspector warnings (was text/html)

	}

}
?>