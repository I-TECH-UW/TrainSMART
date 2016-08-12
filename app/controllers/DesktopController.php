<?php
/*
 * Created on Feb 11, 2008
 *
 *  Built for web
 *  Fuse IQ -- todd@fuseiq.com
 *
 */
require_once ('models/table/OptionList.php');
require_once ('controllers/ITechController.php');
require_once('models/table/Helper.php');

class DesktopController extends ITechController {
	
	private $desktop_db = null;
	
	private $zip_name = 'trainsmart.zip';
	
	private	$package_dir = "";
	
	private $error_message = "";
	
	private $sqlite_db = "trainsmart.active.sqlite";
	
	public function __construct(Zend_Controller_Request_Abstract $request, Zend_Controller_Response_Abstract $response, array $invokeArgs = array()) {
		parent::__construct ( $request, $response, $invokeArgs );
		
	}
	
	public function init() {
		// Site specific files go here (/app/desktop/distro/data/settings.xml + copy of emtpy sqlite db file)
		$this->package_dir = Globals::$BASE_PATH.'sites/'.str_replace(' ', '_', Globals::$COUNTRY).'/desktop';
		
		//TA:81 fix bug for each user generate own [user_id]_trainsmart.active.sqlite and [user_id]_trainsmart.zip
		// sites/[county]/desktop/[user_id]_trainsmart.zip is overwritten by new user zip file
		// sites/[county]/desktop/data/[user_id]_trainsmart.active.sqlite is overwritten by new user sql file
		//
		// in folder sites/[county]/desktop/data we can see who and when last time downloaded data
		// in future if we will want to save all copies of downloaded data we can add date to the beginning of the file name like 
		//sites/[county]/desktop/[user_id]_[date]_trainsmart.zip OR sites/[county]/desktop/data/[user_id]_[date]_trainsmart.active.sqlite
		$helper = new Helper();
		$this->sqlite_db = $helper->myid() . "_trainsmart.active.sqlite";
		$this->zip_name = $helper->myid() . "_trainsmart.zip";
	}
	
	public function preDispatch() {
		$rtn = parent::preDispatch ();
		
		return $rtn;
	
	}
	
	public function downloadDotNetAction() {
		$this->init();
		$go = $this->getSanParam('go');
		if ($go == 1) {
			header("Location: " . Settings::$COUNTRY_BASE_URL . "/dotnetfx.zip");
			exit;
		}
	}

	// Sean Smith 10/20/11: Package and download actions split out to separate "actions"
	// for slow net link users. Server set up to cron this action every hour, so that users
	// only have to download the app.
	public function createAction() {
		//ini_set ( "memory_limit", "256M" );
		
		// Sean Smith: 10/26/2011 - Reworked app packaging to handle multiple 
		// sites calling this function at once

		$this->init();

		// Check for existing site directory, copy package files from code 
		// source location (/app/desktop/distro) to site dir
		if (!$this->_prepareSiteDirectory()) {
			// If dir structure not right, or a file copy failed, 
			// bail and render error page (download.phtml)
			$this->view->assign ( 'error_message', 'Could not create package. The error was: '.$this->error_message );
			return; 
		}

		// Populate sqlite db from mysql db
		$this->dbAction ();

		//zip up
		require_once('app/desktop/Zip.php');
		$file_collection = array();
		
		$zipNameLen = strlen($this->zip_name);

		unlink($this->package_dir.'/'.$this->zip_name);
		$archive = new Archive_Zip($this->package_dir.'/'.$this->zip_name);

		// Gather up all files in distro directory
		// initialize an iterator pass it the directory to be processed
		$DISTRO_PATH = '/app/desktop/distro';//TA:50
		
		/*TA:50 RecursiveIteratorIterator DOES NOT WORK on the server, we have to use hard coded file names
		$iterator = new RecursiveIteratorIterator ( new RecursiveDirectoryIterator ( Globals::$BASE_PATH.$DISTRO_PATH ) );
		// iterate over the directory add each file found to the archive
		foreach ( $iterator as $key => $value ) {
			// Exclude the zip file itself (if exists from a previous creation)
			if (substr($key, $zipNameLen * -1, $zipNameLen) != $this->zip_name)
				$core_file_collection []= realpath ( $key );
		}
		
		// Gather up the site specific files (data/settings.xml and data/sqlite.db)
		$iterator = new RecursiveIteratorIterator ( new RecursiveDirectoryIterator ( $this->package_dir ) );
		foreach ( $iterator as $key => $value ) {
			// Exclude the zip file itself
			if (substr($key, $zipNameLen * -1, $zipNameLen) != $this->zip_name){
				$site_file_collection []= realpath ( $key ); 
			}
		}

		// Files added in two stages because there are two paths to remove 
		// (app/desktop/distro and package_dir) but zip procs will only take one path to remove per call
		$archive->create($site_file_collection,array('remove_path'=>$this->package_dir, 'add_path'=>'TS'));	
		$archive->add($core_file_collection,array('remove_path'=>Globals::$BASE_PATH.$DISTRO_PATH, 'add_path'=>'TS'));
		*/
		//TA:50 file names are hard coded for now
                 $site_file_collection []= realpath ( $this->package_dir . '/data/' . $this->sqlite_db );
                $core_file_collection [] = realpath (Globals::$BASE_PATH.$DISTRO_PATH . '/_READ_ME.txt' );
                 $core_file_collection [] = realpath (Globals::$BASE_PATH.$DISTRO_PATH . '/_trainsmart.jar' );
$archive->create($site_file_collection,array('remove_path'=>$this->package_dir, 'add_path'=>'TS'));
$archive->add($core_file_collection,array('remove_path'=>Globals::$BASE_PATH.$DISTRO_PATH, 'add_path'=>'TS'));	
	}
	
	private function _prepareSiteDirectory() {
		// Make sure site has it's own directory of files to zip, so no 
		// collisions with other sites creating app package at the same time
		try {
			// Make sure directory structure exists. All files in this tree, plus /app/desktop/distro tree will be zipped together
			$old = umask(0); 
			if (!file_exists($this->package_dir)) {
				if (! mkdir($this->package_dir, 0777, true)) throw new Exception('Could not create site directory'); // Make recursive dir structure (make all dirs)
				chmod($this->package_dir,0777);
			}
			if (!file_exists($this->package_dir.'/data')) {
				if (! mkdir($this->package_dir.'/data',0777)) throw new Exception('Could not create site data directory'); 
			}
			umask($old); 
		} catch (Exception $e) {
			$this->error_message = $e->getMessage();
			$this->view->assign ( 'error_message', $this->error_message );
			return false;
		}
			
		
		// Copy site specific settings file and a blank copy of the database to the site's 
		// desktop dir. The database will be populated later, before zip is created
		try {
			// Create settings file in the site's desktop directory (/sites/<countryName>/data)
			//copy (Globals::$BASE_PATH.Globals::$WEB_FOLDER.'/Settings.xml', $dp.'data/Settings.xml') 
			//$curFile = 'settings';
			//$this->settingsAction ($this->package_dir.'/data/');	

			// Always start with a fresh blank datbase file
			$curFile = 'sqlite';
			if (! copy (Globals::$BASE_PATH.'/app/desktop/trainsmart.template.sqlite', $this->package_dir.'/data/' . $this->sqlite_db) ) throw new Exception('PHP copy function did not succeed');
		
		} catch (Exception $e) {
			$this->error_message = 'Failure copying '.$curFile.' file. The exact error was '.$e->getMessage();
			$this->view->assign ( 'error_message', $this->error_message );
			return false;
		}
						
		
		if (!file_exists($this->package_dir.'/data')) {
			$this->view->assign ( 'error_message', 'Could not initialize site directory.' );
			return false;
		}
		
		return true;
	}

	
	
	// Sean Smith 10/20/11: This function is deprecated. 
	// Create and download functions have been split into two separate procedures
	public function distroAction() {
		if (! $this->isLoggedIn ())
			$this->doNoAccessError ();
		
		if (! $this->hasACL ( 'edit_country_options' ) && ! $this->hasACL ( 'use_offline_app' )) {
			$this->doNoAccessError ();
		}
		
		$this->createAction(); // Package the sqlite db and offline app into a zip file
		
    $this->_pushZip();

	}
	
	public function dbAction() {
		set_time_limit ( 300 );

		require_once 'Zend/Db.php';
		//set a default database adaptor
		//$db = Zend_Db::factory ( 'PDO_SQLITE', array ('dbname' => Globals::$BASE_PATH . 'app/desktop/trainsmart.active.sqlite' ) );
		$db = Zend_Db::factory ( 'PDO_SQLITE', array ('dbname' => $this->package_dir .'/data/' . $this->sqlite_db ) );
		$GLOBALS['debug'] = $this->package_dir .'/data/' . $this->sqlite_db;
		
		require_once 'Zend/Db/Adapter/Abstract.php';
		if (! $db instanceof Zend_Db_Adapter_Abstract) {
			require_once 'Zend/Db/Table/Exception.php';
			throw new Zend_Db_Table_Exception ( 'Could not create sqlite adaptor' );
		}
		$this->desktop_db = $db;
		$litedb = $this->desktop_db;
		
		require_once('models/table/Helper.php');
		$helper = new Helper();
		
		$litedb->update('_app', array('user_id' => $helper->myid()), 'id = 0');
		
		//$liteSysTable = new System(array( Zend_Db_Table_Abstract::ADAPTER => $litedb));
		//$liteSysTable->select('*');
		
		//TA:50 needed as a lookup tables and configuration, not assumed to be modified by app (18 tables)
		$optionTypes = array(
		    'lookup_degrees',
		    'cadres',
		    'lookup_coursetype',
		    'classes',
		    'lookup_institutiontype',
		    'lookup_sponsors',
		    'lookup_languages',
		    'lookup_tutortype',
		    'location',
		    'translation',
		    'person_qualification_option',
		    'person_title_option',
		    'lookup_reasons',
		    'lookup_studenttype',
		    'lookup_nationalities',
		    'lookup_fundingsources',
		    'facility_sponsor_option',
		    'facility'
		);
		
		require_once 'sync/SyncCompare.php';
		$option_tables = array_merge(SyncCompare::$compareTypes,$optionTypes);
		
		// require_once('models/table/System.php');
		foreach ( $option_tables as $opt ) {
			//$GLOBALS['debug'] = $opt;
			try {
				$step = 1;
			$optTable = new OptionList ( array ('name' => $opt ) );
				$step = 2;
			$liteTable = new OptionList ( array ('name' => $opt, Zend_Db_Table_Abstract::ADAPTER => $litedb ) );
			} catch (Exception $e) {
				echo "--- err matching/finding tables $opt, step $step [".($step==1?"db":"sqlite")."] --- <BR>";
				echo $e->getMessage();
			}
			
			
		//TA:81 filter institution, cohort, and people only for login user
// 			if($opt === 'institution'){
// 			    $institutions = $helper->getUserInstitutions($helper->myid(), false);
// 			    if ((is_array($institutions)) && (count($institutions) > 0)) {
// 			        $insids = implode(",", $institutions);
// 			        $optTable->select('*');
// 			        $rowset = $optTable->fetchAll('id IN (' . $insids . ')');
// 			    }else{
// 			        $optTable->select('*');
// 			        $rowset = $optTable->fetchAll();
// 			    }
// 			}
			
			if($opt === 'institution' || $opt === 'cohort' || $opt === 'person' || $opt === 'practicum' || $opt === 'link_student_cohort'
			    || $opt === 'link_student_funding' || $opt === 'link_student_classes' || $opt ==='link_student_practicums' || $opt === 'link_student_licenses'
			    || $opt === 'link_student_addresses' || $opt ==='link_tutor_addresses' || $opt === 'link_tutor_languages' 
			    || $opt === 'link_tutor_tutortype' || $opt ==='link_tutor_institution' || $opt === 'link_cohorts_classes'
			    || $opt === 'link_institution_degrees' || $opt === 'link_cadre_institution' || $opt === 'student' || $opt === 'tutor'){
			    $institutions = $helper->getUserInstitutions($helper->myid(), false);
			    if ((is_array($institutions)) && (count($institutions) > 0)){
			        $insids = implode(",", $institutions);
			        $optTable->select('*');
			        if($opt === 'institution'){
			             $rowset = $optTable->fetchAll('id IN (' . $insids . ')');
 //			             print "<br><br>========================= " . $opt . " =============================<br>";
// 			             print_r($rowset);
			        }else if($opt === 'cohort'){
			            //$rowset = $optTable->fetchAll('institutionid IN (' . $insids . ') and graddate>now()');//download only active cohorts
			            $rowset = $optTable->fetchAll('institutionid IN (' . $insids . ')');
 			           // print "<br><br>========================= " . $opt . "+" . $insids . " =============================<br>";
 			           //  print_r($rowset);
			        }else if($opt === 'person'){
			            
// 			            $rowset = $optTable->fetchAll(' is_deleted=0 AND (id in (
// select personid from tutor where id in 
// (select id_tutor from link_tutor_institution where id_institution in 
// (' . $insids . ')))
// or 
// id in (
// select personid from student where id in(
// select id_student from link_student_cohort where id_cohort in (
// select id from cohort where institutionid in 
// (' . $insids . '))))
// or (
// id in (
// select personid from tutor where personid not in 
// (select id_tutor from link_tutor_institution))
// or
// id in (
// select personid from student where id not in 
// (select id_student from link_student_cohort))))');

			            
// 			            $rowset = $optTable->fetchAll(
// 			                ' is_deleted=0 and (
// id in (select personid from tutor where institutionid in (' . $insids . ')) 
// or 
// id in (select personid from student where institutionid in (' . $insids . ')))');

//TA:100 filter students by cohort institution access as well - it works???????
// 			            $rowset = $optTable->fetchAll(
// 			                ' is_deleted=0 and (
// id in (select personid from tutor where institutionid in (' . $insids . '))
// or
// id in (
// SELECT person.id FROM person
// INNER JOIN student ON student.personid = person.id 
// LEFT JOIN institution ON institution.id = student.institutionid 
// LEFT JOIN link_student_cohort ON link_student_cohort.id_student = student.id 
// LEFT JOIN cohort ON cohort.id = link_student_cohort.id_cohort 
//  where cohort.institutionid in (' . $insids . ') and student.institutionid in (' . $insids . ')
// ))');
//TA:100 filter students not by cohort institution access but institutionid only		            
			            $rowset = $optTable->fetchAll(
			                ' is_deleted=0 and (
id in (select personid from tutor where institutionid in (' . $insids . '))
or
id in (
SELECT person.id FROM person
INNER JOIN student ON student.personid = person.id
LEFT JOIN institution ON institution.id = student.institutionid
 where student.institutionid in (' . $insids . ')
))');
//  			            print "<br><br>========================= " . $opt . " =============================<br>";
//  			             print_r($rowset);
			        }else if($opt === 'link_student_cohort'){
			            //download only students belonging to the user's institution
			            //wrong result
// 			            $rowset = $optTable->fetchAll(' id_student in (select student.id from student 
// 			                JOIN institution ON institution.id = student.institutionid where institution.id in (' . $insids . '))');
                        //more correct result??
			            $rowset = $optTable->fetchAll(' id_cohort in (select id from cohort where institutionid in (' . $insids . '))');
			        }else if($opt === 'link_student_funding'){
			            $rowset = $optTable->fetchAll(' studentid in (select student.id from student 
			                JOIN institution ON institution.id = student.institutionid where institution.id in (' . $insids . '))');
			        }else if($opt === 'link_student_classes'){
			            $rowset = $optTable->fetchAll(' studentid in (select student.id from student 
			                JOIN institution ON institution.id = student.institutionid where institution.id in (' . $insids . '))');
			        }else if($opt === 'link_student_practicums'){
			            $rowset = $optTable->fetchAll(' studentid in (select student.id from student 
			                JOIN institution ON institution.id = student.institutionid where institution.id in (' . $insids . '))');
			        }else if($opt === 'link_student_licenses'){
			            $rowset = $optTable->fetchAll(' studentid in (select student.id from student 
			                JOIN institution ON institution.id = student.institutionid where institution.id in (' . $insids . '))');
			        }else if($opt === 'link_student_addresses'){
			            $rowset = $optTable->fetchAll(' id_student in (select student.id from student 
			                JOIN institution ON institution.id = student.institutionid where institution.id in (' . $insids . '))');
			        }else if($opt === 'link_tutor_addresses'){
			            $rowset = $optTable->fetchAll(' id_tutor in (select tutor.id from tutor 
			                JOIN institution ON institution.id = tutor.institutionid where institution.id in (' . $insids . '))');
			        }else if($opt === 'link_tutor_languages'){
			            $rowset = $optTable->fetchAll(' id_tutor in (select tutor.id from tutor 
			                JOIN institution ON institution.id = tutor.institutionid where institution.id in (' . $insids . '))');
			        }else if($opt === 'link_tutor_tutortype'){
			            $rowset = $optTable->fetchAll(' id_tutor in (select tutor.id from tutor 
			                JOIN institution ON institution.id = tutor.institutionid where institution.id in (' . $insids . '))');
			        }else if($opt === 'link_tutor_institution'){
			            $rowset = $optTable->fetchAll(' id_tutor in (select tutor.id from tutor 
			                JOIN institution ON institution.id = tutor.institutionid where institution.id in (' . $insids . '))');
			        }else if($opt === 'link_cohorts_classes'){
			            $rowset = $optTable->fetchAll(' cohortid in (select cohort.id from cohort 
			                JOIN institution ON institution.id = cohort.institutionid where institution.id in (' . $insids . '))');
			        }else if($opt === 'link_institution_degrees'){
			            $rowset = $optTable->fetchAll(' id_institution in (' . $insids . ')');
			        }else if($opt === 'link_cadre_institution'){
			            $rowset = $optTable->fetchAll(' id_institution in (' . $insids . ')');
			        }else if($opt === 'student'){
			            $rowset = $optTable->fetchAll(' institutionid in (' . $insids . ')');
			        }else if($opt === 'tutor'){
			            $rowset = $optTable->fetchAll(' institutionid in (' . $insids . ')');
			        }else if($opt === 'practicum'){
			            $rowset = $optTable->fetchAll(' cohortid in (select id from cohort where institutionid in (' . $insids . '))');
// 			            print "<br><br>========================= " . $opt . " =============================<br>";
// 			             print_r($rowset);
			        }
			    }else{
			        $optTable->select('*');
			        $rowset = $optTable->fetchAll();
// 			        print "<br><br>========================= " . $opt . " =============================<br>";
// 			        print_r($rowset);
			    }  
			}else{
			    $optTable->select ( '*' );
			    $rowset = $optTable->fetchAll ();
			}

			$liteKeys = $liteTable->createRow ()->toArray ();
#			if ($opt == 'age_range_option') echo "!!keys:: ".print_r($liteKeys, true);
#			if ($opt == 'age_range_option') echo "!!data:: ".count($rowset);

			foreach ( $rowset as $optRow ) {
				$data = $optRow->toArray ();
				
				foreach ( array_keys ( $data ) as $k ) {
					// if ($opt == 'age_range_option') echo "@@: $k";
					if (! array_key_exists ( $k, $liteKeys ))
						unset ( $data [$k] );
				}
				try {
					if(isset($data['training_id']) && $data['training_id'] == null){
						$data['training_id'] = 0; //bugfix - FK training_id not accepting nulls. production db has "bad" data
					}
					#debug
#					if ($opt == 'age_range_option') {
#						echo " adding row @ '$opt': ".print_r($data,true).PHP_EOL;
#					}
					
				//TA:82 it was inserted wrongly $liteTable->insert ( $data );
			     $liteTable->insertAllAsItIS( $data );
				} catch (Exception $e) {
				    //TA:82 uncoment later and understand why this Ecxeption occurs
// 					echo "############## skipping data ($opt)";
// 					echo '<br><pre>'.$e->getMessage()."\n";
// 					print_r($data);
				}	
			}
		}
		
// 		//TA:50 add virtual primary id start for each download user
// 		/*
// 		 *_app_start_ids table to keep start primary id for some tables when desktop user inserts new data. 
// 		 *Max primary key for those tables is int(10)=1000000000. If we give each download 10,000 ids range it will allow 100,000 downloads. 
// 		 *Every time when user downloads app start_id +10,000. It will help avoid overlapping of the ids when users will upload data to TS.
// 		 */
// 		$db_opt = Zend_Db_Table_Abstract::getDefaultAdapter();
// 		foreach ( SyncCompare::$compareTypes as $opt ) {
// 		    $template_liteTable = new OptionList ( array ('name' => '_vpk', Zend_Db_Table_Abstract::ADAPTER =>
// 		        Zend_Db::factory ( 'PDO_SQLITE', array ('dbname' => Globals::$BASE_PATH.'/app/desktop/trainsmart.template.sqlite' )))) ;
//  			$row = $template_liteTable->fetchRow("table_name = '" . $opt . "'");
//  			$last_id = $row['vpk'];
//  			if($last_id == 0){
//  			    $optTable = new OptionList ( array ('name' =>'" . $opt . "' ) );
//  			   $next_vpk = $db_opt->fetchRow("select max(id) from " . $opt)['max(id)'];
//  			}else{
//  			    $next_vpk = $last_id + 10000;
//  			}
//  			$next_vpk = $next_vpk+1;
//  			//write to template sqlite
//  		    $template_liteTable->update(array('vpk'=>$next_vpk),"table_name = '" . $opt . "'");
// 		    //write to user sqlite
// 		    $liteTable = new OptionList ( array ('name' => '_vpk', Zend_Db_Table_Abstract::ADAPTER => $litedb ) );
// 		    $liteTable->update(array('vpk'=>$next_vpk),"table_name = '" . $opt . "'");
// 		}
		
	}

	public function downloadAction() {
		$this->init();
		
		// Sean Smith 10/20/11: Moved security check to distro action and to download action
		// This way a text based browser (Lynx) can package the application without
		// having to log in.
		if (! $this->isLoggedIn ())
			$this->doNoAccessError ();
		
		if (! $this->hasACL ( 'edit_country_options' ) && ! $this->hasACL ( 'use_offline_app' )) {
			$this->doNoAccessError ();
		}
		
		// If no zip file, then Create App never performed (do this first)
		//TA:50 create new (overwrite if exist) every time 	
	//	if (! file_exists($this->package_dir.'/'.$this->zip_name)) {
			$this->createAction(); 
	//	}
	
		// Assumes createAction called every hour by cron job
		// Allows slow net link users to only download a prepackaged zip file
		// instead of having to wait for the entire package event and then the
		// entire download (combined time was breaking in remote locations)
		$this->_pushZip(); 
	}
	
	//TA:TEST DO NOT REMOVE: use for debug
// 	public function downloadAction() {
// 	    $this->init();
// 	    if (! $this->isLoggedIn ())
// 	        $this->doNoAccessError ();
	
// 	    if (! $this->hasACL ( 'edit_country_options' ) && ! $this->hasACL ( 'use_offline_app' )) {
// 	        $this->doNoAccessError ();
// 	    }
// 	    $this->dbAction (); 
// 	}
		
	
	function _pushZip() {
		$this->init();
		
		try {
      if ($fd = @fopen($this->package_dir.'/'.$this->zip_name, 'rb')) {
		    ob_end_clean();
		    ob_start(); 
		    $buffer = "";
		
		    header('Content-Type:'); // application/zip
		    header('Content-Disposition: inline; filename="' . $this->zip_name.'"');
		    header('Cache-Control: private, max-age=0, must-revalidate');
		    header('Pragma: public');

        while (!feof($fd)) {
        	$buffer = fread($fd, 1024); // Next two lines was simply:  print fread($fd, 1024);
	        echo $buffer;
	        ob_flush(); 
	        flush();         	
        }
        fclose($fd);
      } else {
      	$this->view->assign ( 'error_message', 'Could not open file '.$this->package_dir.'/'.$this->zip_name .'. ' );
      	return;
      }
      exit(); 
    } catch (Exception $e) {
    	$this->view->assign ( 'error_message', 'Could not open file '.$this->package_dir.'/'.$this->zip_name.'. The error was '.$e->getMessage() );
    } 
	}
	
	public function settingsAction($path) {
		require_once ('models/table/System.php');
		$sysTable = new System ( );
		
		@date_default_timezone_set ( "GMT" );
		$settingsWriter = new XMLWriter ( );
		
		// Output directly to the user 
		

		$settingsWriter->openURI ( $path.'Settings.xml' );
		$settingsWriter->startDocument ( '1.0' );
		
		$settingsWriter->setIndent ( 4 );
		$settingsWriter->startElement ( 'system' );
		$settingsWriter->writeAttribute ( 'version', '1.0' );
		$settingsWriter->writeAttribute ( 'password', 'mango' );
		$sysTable->select ( '*' );
		$row = $sysTable->fetchRow ();
		foreach ( $row->toArray () as $k => $v ) {
			$settingsWriter->writeAttribute ( $k, $v );
		}
		
		$option_tables = array ('translation' );
		
		foreach ( $option_tables as $opt ) {
			$settingsWriter->startElement ( str_replace ( '_option', '', $opt ) );
			$optTable = new OptionList ( array ('name' => $opt ) );
			$optTable->select ( '*' );
			foreach ( $optTable->fetchAll () as $row ) {
				$settingsWriter->startElement ( 'add' );
				foreach ( $row->toArray () as $k => $v ) {
					if ($k == 'id') {
						$settingsWriter->writeAttribute ( 'key', $v );
					} else if (strpos ( $k, '_phrase' )) {
						$settingsWriter->writeAttribute ( 'value', $v );
					} else {
						$settingsWriter->writeAttribute ( $k, $v );
					}
				}
				$settingsWriter->endElement ();
			}
			
			$settingsWriter->endElement ();
		}
		
		// End Item 
		$settingsWriter->endElement ();
	
	}
}
?>