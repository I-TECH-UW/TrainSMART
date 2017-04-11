<?php
/*
 * Created Oct 22, 2010
 *
 *  Built for ITech
 *  Fuse IQ -- nick@fuseiq.com todd@fuseiq.com

We're using this grid to find and act on changes. The right-uuid is the item acted on. We find 
a right uuid by searching the alias table (and act on what the alias points to), a uuid search 
(if from the desktop export or inserted from the left earlier), or a fuzzy search. If there's 
a difference between left and right items we update or insert.  

Finding Differences.
We use the uuid to load the left and right item (or search and match) then compare only the 
specified member fields. For refrence columns, we load the left and right by id and check uuids 
match. If fields mismatch, we log it for update. 

Job Results Logged.
          left-uuid right-uuid  is-skipped  done-time
  1. insert     #     _               
  2. update     #     #               
  3. update     #     #(aliased)              
  4. soft delete    #     #               
  5. hard delete    _     #               
  6. both-changed   #     #               (no updates made) 
  7. mis-match    _     #               (we don't check for this) 


Making Updates.
Order of operation is important creating items, so refrence columns point to an existing id 
on the right. We run items in this order: 
  see $compareTypes

Handling Failures. 
Log items are done with a timestamp_completed. If an update fails, we quit the job.......


 * 
 */

require_once ('app/models/table/ITechTable.php');
require_once ('app/models/table/SyncFile.php');
require_once ('app/models/table/SyncLog.php');
require_once ('app/controllers/sync/set/SyncSetFactory.php');
require_once ('app/controllers/sync/set/Simple.php');


class SyncCompare
{
  
  // Desktop sqlite file id and path for sourcing data
  protected $desktopFileId = false;
  protected $desktopFilePath = false;
  protected $desktopFilePathCopy = false;
  protected $syncLog = null;
  protected $syncFile = null;
  
  // Last sync time to compare since 
  static public  $lastSyncCompleted = null;
  
  // Our items to work on, // assumed to be modified (21 tables)
  public static $compareTypes = array(
        'institution',
        'link_cadre_institution', // after institution
        'link_institution_degrees', // after institution
        'cohort',
        'licenses', // after cohort
        'practicum', // after cohort
        'link_cohorts_classes', // after cohort
        'person',
          'student', // after person
        'link_student_cohort', // after student and cohort
        'link_student_funding', // after student
        'link_student_classes', // after student and cohort
        'link_student_practicums', // after cohort, student and practicum
        'link_student_licenses', // after cohort, student and licenses
        'addresses',
        'link_student_addresses', // after student and addresses
        'tutor', // after person
        'link_tutor_addresses', // after tutor and addresses
        'link_tutor_languages', // after tutor
        'link_tutor_tutortype', // after tutor
        'link_tutor_institution'// after tutor and institution
    ) 
;
  
  // Our items to work on 
  //TA:50 old version does not work
  static public $compareTypesOld = array(
    'location',
    'facility_sponsor_option',
    'facility_type_option',
    'facility',
    'person_title_option',
    'person_suffix_option',
    'person_primary_responsibility_option', // was person_responsibility_option
    'person_secondary_responsibility_option', // new table added 

    'person_qualification_option',
    'person_custom_1_option',
    'person_custom_2_option',
    'person', 
    
    'trainer_affiliation_option',
    'trainer_skill_option',
    'trainer_type_option',
    'trainer_language_option',
  
    'trainer',
    'trainer_to_trainer_skill_option',
    'trainer_to_trainer_language_option',
    'training_location',
    'training_custom_1_option',
    'training_custom_2_option',
    'training_level_option',
    'training_method_option',
    'training_organizer_option',
    'training_pepfar_categories_option',
    'training_funding_option',
    'training_got_curriculum_option',
    'training_category_option',
    'training_topic_option',
    'training_title_option',
    'training_category_option_to_training_title_option', //not assignable in desktop version
    'age_range_option',
    'training',
      
    'training_to_training_topic_option',
    'training_to_training_funding_option',
    'training_to_training_pepfar_categories_option',
    'training_to_person_qualification_option',
  
    'person_to_training',
    'training_to_trainer',
    'person_history',
    'trainer_history'
    );
  
  function __construct($desktopFileId = null)
  {
        
    // logs for storing changes 
    $this->syncLog = new SyncLog($desktopFileId); //items 
    $this->syncFile = new SyncFile(); //the job

    // set params for source data and log file
    if($desktopFileId && $row = $this->syncFile->fetchRow('id="'.$desktopFileId .'"')) {
      $this->desktopFileId = $row->id;
      $this->desktopFilePath = $row->filepath.$row->filename;
      $this->desktopFilePathCopy = $row->filepath.$row->filename . '.copy';
      // last update to limit searches 
      $last_sync = $row->timestamp_last_sync;
      SyncCompare::$lastSyncCompleted = $last_sync; 
    }
  }
  
  /*
   * @return connection options to use creating client-source objects
   * create connectino for SQLite db
   */
  static public function getDesktopConnectionParams($tableName, $databaseFile)
  {
    $connection = array();
    
    if(!file_exists($databaseFile)) {
      throw new Exception('Error. Syncfile missing. '. $databaseFile); #@TODO fix fatal error with bad file   
    }
    // connect to file 
    $db = Zend_Db::factory('PDO_SQLITE', array('dbname' => $databaseFile));
    $connection = array_merge($connection, array(Zend_Db_Table_Abstract::ADAPTER => $db)); 
    
    // add table model
    $connection = array_merge($connection, array('name' => $tableName ? $tableName : null));
    return $connection;
  }
      
  function sanityCheck() {
    //make sure num location tiers is the same on the right vs. the left
    $_app = new ITechTable(SyncCompare::getDesktopConnectionParams('location',$this->desktopFilePath));
    $tiers = $_app->getAdapter()->query('SELECT COUNT(DISTINCT tier) as "cnt" FROM location');
    $rows = $tiers->fetchAll();
    $settings = System::getAll();
    $city_tier = 2 +  $settings['display_region_i'] + $settings['display_region_h'] + $settings['display_region_g'] +  $settings['display_region_f'] +  $settings['display_region_e'] +  $settings['display_region_d'] +  $settings['display_region_c'] + $settings['display_region_b'];
    if ( $rows[0]['cnt'] != $city_tier) {
      //return 'Could not import data: Offline database contains '.$rows[0]['cnt'].' regional levels, while the web database contains '.$city_tier.'.';
    }
    
    return false;
  }
  
  //TA:50 Search for left db and right db differences
//   function doSyncProcessOld($sqlite_file, $commit=false){
  
//       $errors = array();
//       $log_text = "";
//       $this->syncLog->truncate(); // delete unfinished items for db file
//       foreach (self::$compareTypes as $tableType) {
//           $log_text = $log_text . "\n" . $tableType . "=>[\n";
//           $set = SyncSetFactory::create($tableType, SyncCompare::getDesktopConnectionParams($tableType, $sqlite_file), $this->desktopFileId);
//           if ($set) {
//               $leftItems = $set->fetchLeftPool();
//               foreach ($leftItems as $ld) {
//                   $actions = array();
//                   $userMessage = '';
//                   try {
//                       $rItem = $set->fetchFieldMatch($ld);
//                       if ($rItem) {
//                           if ($set->isDirty($ld, $rItem)) {
//                               $isConflict = $set->isConflict($ld, $rItem);
//                               if ($isConflict) {
//                                   // write message and skip action
//                                   //if(!$commit){
//                                   $userMessage = $isConflict;
//                                   $actions[] = 'conflict';
//                                   // }
//                               } else {
//                                   // print "ID:" . $ld->id . "=" . $rItem->id . ", personid:" . $ld->personid . "=" . $rItem->personid . "\n";
//                                   $set->updateMember($ld->id, $rItem->id, $commit);
//                                   $userMessage = 'Update';
//                                   $actions[] = 'update';
//                               }
//                           }
//                       } else{
//                           $set->insertMember($ld->id, $sqlite_file, $this->desktopFileId, $commit);
//                           $userMessage = 'Insert';
//                           $actions[] = 'insert';
//                       }
                       
//                       foreach($actions as $action) {
//                           if($commit){
//                               //to keep for log
//                               //we need this line
//                               $lid=null;
//                               $ldata = null;
//                               if($ld){
//                                   $lid = $ld->id;
//                                   $ldata = $ld->toArray();
//                               }
//                               $rid=null;
//                               $rdata = null;
//                               if($rItem){
//                                   $rid = $rItem->id;
//                                   $rdata = $rItem->toArray();
//                               }
//                               $this->syncLog->add($tableType, $lid, $rid, $action . "-done", $userMessage,$ldata, $rdata,
//                                   $this->syncLog->now_expr());
//                           }else{
//                               //to shaw user to review before commit
//                               $this->syncLog->add($tableType, null, null, $action, $userMessage,null, null);
//                           }
//                       }
  
//                   } catch (Exception $e) {
//                       $errors[] = print_r($e->getMessage(), true);
//                   }
//               }
  
//               try {
//                   //TODO: now it returns array of ids, may be we can return array of items, then we could save in log file full info about deleted item
//                   // like $this->syncLog->add($tableType, null, $d,"delete-done", null, null, $d); where $d - row item , noyt just id.
//                   $deletables = $set->fetchHardDeletes($sqlite_file, $this->desktopFileId);
//                   if ($deletables) {
//                       foreach ($deletables as $d) {
//                           $set->deleteMember($d, $commit);
//                           if($commit){
//                               //to keep for log
//                               $this->syncLog->add($tableType, null, $d,"delete-done", "Delete", null, null, $this->syncLog->now_expr());
//                           }else{
//                               //$pk = $d->getTable()->PK();
//                               //to shaw user to review before commit
//                               $this->syncLog->add($tableType, null, $d, 'delete', "Delete");
//                           }
//                       }
//                   }
//               } catch (Exception $e) {
//                   $errors[] = print_r($e->getMessage(), true);
//               }
  
//               $log_text = $log_text . $set->getLog();
//           }
//           // do not remove this line (to show that process is done)
//           $this->syncLog->addTableCompleteMessage($tableType);
//           //$save = array('fid' => $this->_fid, 'item_type'=> $table, 'action' => 'table-diff-complete');
//           //$result = $this->insert($save);
  
//           // TA:50
//           $log_text = $log_text . "];\n";
//       }
//       if(!$commit)
//           print $log_text;
  
//       //clean up synclog table after commit
//       if($commit){
//           $this->syncLog->delete("action='table-diff-complete' and fid=" . $this->desktopFileId);
//       }
//       return $errors;
//   }
  
  //TA:99 get value from reference table
  function getRefrenceTableValue($sqlite_file, $ref_table, $key){
      $log_text = "";
          $set_ref = SyncSetFactory::create($ref_table, SyncCompare::getDesktopConnectionParams($ref_table, $sqlite_file), $this->desktopFileId);
          if ($set_ref) {
              $leftItems = $set_ref->fetchLeftPool();
              foreach ($leftItems as $ld) {
                  $log_text = $log_text . @$ld->personid . "**";
              }
          }
          return $log_text;
  }
    
    //TA:50 Search for left db and right db differences
    function doSyncProcess($sqlite_file, $commit=false){
        
        $errors = array();
        $log_text = "";
        $error_text = ""; //TA:#315
        //TA:#303 $this->syncLog->truncate(); // delete unfinished items for db file
        
        //TA:99 to match person we have to take insitutionid from 'student' or 'tutor' table
         $set_link_student = SyncSetFactory::create('student', SyncCompare::getDesktopConnectionParams('student',$sqlite_file), $this->desktopFileId);
         $left_table_link_student = $set_link_student->getTable()->getAdapter();
         $set_link_tutor = SyncSetFactory::create('tutor', SyncCompare::getDesktopConnectionParams('tutor',$sqlite_file), $this->desktopFileId);
         $left_table_link_tutor = $set_link_student->getTable()->getAdapter();
        
        
        foreach (self::$compareTypes as $tableType) {
            
            $log_text = $log_text . "\n" . $tableType . "=>[\n";
            $set = SyncSetFactory::create($tableType, SyncCompare::getDesktopConnectionParams($tableType, $sqlite_file), $this->desktopFileId); 
            if ($set) {
                $leftItems = $set->fetchLeftPool();
                foreach ($leftItems as $ld) {
              
                    $actions = array();
                    $userMessage = '';
                    
                    //TA:99 to match person we have to take insitutionid from 'student' or 'tutor' table
                    if($tableType === 'person'){
                        $inst_id = $left_table_link_student->fetchRow("select institutionid from student where personid=" . $ld->id)['institutionid'];
                        if(!$inst_id){
                            $inst_id = $left_table_link_tutor->fetchRow("select institutionid from tutor where personid=" . $ld->id)['institutionid'];
                        }
                    }
                    
                    try {
                        $rItem = $set->fetchFieldMatch($ld, $inst_id);
                        if ($rItem) {
                            if ($set->isDirty($ld, $rItem)) {
                                //TA:#303, TA:#315
                                if($tableType === 'person'){
                                    $isConflict = $set->isConflict($ld, $rItem, $inst_id);
                                }else{
                                    $isConflict = $set->isConflict($ld, $rItem);
                                }
                                if ($isConflict) {
                                    // write message and skip action
                                    //if(!$commit){
                                        $userMessage = $isConflict;
                                        $actions[] = 'conflict';
                                   // }
                                } else {
                                    // print "ID:" . $ld->id . "=" . $rItem->id . ", personid:" . $ld->personid . "=" . $rItem->personid . "\n";
                                    $set->updateMember($ld->id, $rItem->id, $commit);
                                    $userMessage = 'Update';
                                        $actions[] = 'update';
                                }
                            }
                        } else{
                            $set->insertMember($ld->id, $sqlite_file, $this->desktopFileId, $commit);
                                $userMessage = 'Insert';
                                $actions[] = 'insert';
                         }
                         
                         foreach($actions as $action) {
                             if($commit){
                                 //to keep for log
                                 //we need this line 
                                 $lid=null;
                                 $ldata = null;
                                 if($ld){
                                     $lid = $ld->id;
                                     $ldata = $ld->toArray();
                                 }
                                 $rid=null;
                                 $rdata = null;
                                 if($rItem){
                                     $rid = $rItem->id;
                                     $rdata = $rItem->toArray();
                                 }
                                 //TA:#303 $this->syncLog->add($tableType, $lid, $rid, $action . "-done", $userMessage,$ldata, $rdata, $this->syncLog->now_expr());
                                 }else{
                                 //to shaw user to review before commit
                                 //TA:#303 $this->syncLog->add($tableType, null, null, $action, $userMessage,null, null);
                                                     }
                         }
                        
                    } catch (Exception $e) {
                        $errors[] = print_r($e->getMessage(), true);
                    }
                }
    
                try {
                    //TODO: now it returns array of ids, may be we can return array of items, then we could save in log file full info about deleted item
                    // like $this->syncLog->add($tableType, null, $d,"delete-done", null, null, $d); where $d - row item , noyt just id.
                   //TA:#303 temp do not delete any records for now 
                   //$deletables = $set->fetchHardDeletes($sqlite_file, $this->desktopFileId);
                    $deletables = null;
                    if ($deletables) {
                        foreach ($deletables as $d) {
                            $set->deleteMember($d, $commit);
                            if($commit){
                                //to keep for log
                                //TA:#303 $this->syncLog->add($tableType, null, $d,"delete-done", "Delete", null, null, $this->syncLog->now_expr());
                            }else{
                                //$pk = $d->getTable()->PK();
                                //to shaw user to review before commit
                                //TA:#303 $this->syncLog->add($tableType, null, $d, 'delete', "Delete");
                            }
                        }
                    }
                } catch (Exception $e) {
                    $errors[] = print_r($e->getMessage(), true);
                }
    
                $log_text = $log_text . $set->getLog();
                $error_text = $error_text . $set->getError();
            }
            // do not remove this line (to show that process is done)
            //TA:#303 $this->syncLog->addTableCompleteMessage($tableType);
            //$save = array('fid' => $this->_fid, 'item_type'=> $table, 'action' => 'table-diff-complete');
            //$result = $this->insert($save);
    
            // TA:50
            $log_text = $log_text . "];\n";
        }
        if(!$commit){
            //TA:#315
            if($error_text !== ""){
                $error_text = "The following conflicts are found in data:\n" . $error_text . "\n\nWe recommend to fix conflicts before continue with the next step and rerun sync process again. 
\nOr you can continue with the next step, but please note, conflict data will not be committed to database.\n";
            }
            print $error_text . "\n\nUploading process is completed with folowing database tables:\n\n" . $log_text;
        }
        
        //clean up synclog table after commit
        if($commit){
            //TA:#303 $this->syncLog->delete("action='table-diff-complete' and fid=" . $this->desktopFileId);
        }
        return $errors;
    }
    
    /*
     * //TA:50 
     * Search for left db and right db differences, do not commit to database, show result to user for review
     * @return
     */
    function findDifferencesProcess(){
    // create copy sqlite file to use it later during update (commit) process
        if (! copy ($this->desktopFilePath, $this->desktopFilePathCopy) )
            throw new Exception('PHP copy function did not succeed');
        return $this->doSyncProcess($this->desktopFilePath, false);
    
    }
    
    /*
     * //TA:50
     * If user pushe commit, Search for left db and right db differences, commit to database, show result to user
     */
    function doUpdatesProcess(){
        return $this->doSyncProcess($this->desktopFilePathCopy, true);
    }
  
  /*
   * Search for left db and right db differences
   * @return
   */
//   function findDifferencesProcessOld($arr)
//   {
  
//       $errors = array();
  
//       $this->syncLog->truncate(); // delete unfinished items for db file
//       foreach(self::$compareTypes as $tableType)
//       {
//           //TA:50
//           print "" . $tableType . "=>[ ";
//           $set = SyncSetFactory::create($tableType, SyncCompare::getDesktopConnectionParams($tableType,$this->desktopFilePath), $this->desktopFileId);
//           //TA:50 print "=0;";
//           if ( $set ) {
//               //TA:50 print "=11;";
//               $leftItems = $set->fetchLeftPool(); //desktop db
//               $meta_done = false;
//               $pk = 'id';
//               foreach($leftItems as $ld) {
//                   print  $ld->id . "( ";
//                   $rItem = $actions = array();
//                   try {
//                       $ldata = null;
//                       $rdata = null;
//                       if ( !$meta_done ) {
//                           $has_uuid = $ld->getTable()->has_uuid_col();
//                           $meta_done = true;
//                           $pk = $ld->getTable()->PK();
//                       }
  
//                       $userMessage = '';
  
//                       //TA:50 print "=2;";
  
//                       if ( $has_uuid && $ld->uuid )
//                           $rItem = $set->fetchRightItemByUuid($ld->uuid);
  
//                       if ($rItem) { //exact uuid match on item
//                           if ( $set->isDirty($ld, $rItem) ) {
//                               if ($set->isConflict($ld, $rItem)) { // true if conflict
//                                   $userMessage = 'Update (with conflict)';
//                                   $actions[] = 'update-conflict';
  
//                                   $ldata = $ld->toArray();
//                                   $rdata = $rItem->toArray();
  
//                               } else {
//                                   $userMessage = 'Update';
//                                   $actions[] = 'update';
//                               }
//                           }
//                       } else { //no exact uuid match on item, look for field level match
//                           //TA:50print "=3;";
//                           // if($set::$used_ids->contains($ld->id)) //TA:50
//                           //   return; //TA:50
//                           $rItem = $set->fetchFieldMatch($ld);
//                           //TA:50print "=4;";
  
//                           if ($rItem) {
//                               //TA:50
//                               print "MATCH,";
//                               if ($set->usesAlias())
//                                   $actions[] = 'add-alias';
//                               if ( $set->isDirty($ld, $rItem) ) {
//                                   print "DIRTY,";
//                                   if ( $set->isConflict($ld, $rItem) ) {
//                                       print "CONFLICT,";
//                                       $userMessage = "Update (with conflict)";
//                                       $actions[] = 'update-conflict';
  
//                                       $ldata = $ld->toArray();
//                                       $rdata = $rItem->toArray();
//                                   } else {
//                                       print "UPDATE,";
//                                       $userMessage = 'Update';
//                                       $actions[] = 'update';
//                                   }
//                               }
//                           } else { //no right match
//                               print "INSERT,";
//                               //do we insert deleted rows? no
//                               $has_soft_delete = $ld->getTable()->has_is_deleted_col();
//                               if ( !$has_soft_delete OR ($has_soft_delete && !$ld->is_deleted)) {
//                                   $actions[] = 'insert';
//                                   $rItem = new stdClass();
//                                   $rItem->uuid = null;
//                                   $rItem->$pk = null;
//                                   $userMessage = 'New item';
//                               }
//                           }
//                       }
  
//                       if ( ((!$actions) OR ($actions[0] != 'insert')) &&
//                           $rItem && $rItem->getTable()->has_is_deleted_col() ) {
//                               if ( ($rItem->is_deleted && $ld->is_deleted) || ($ld->is_deleted && $set->isReferenced($rItem)) ) {
//                                   //if they're both deleted then skip
//                                   $actions = array();//skip
//                                   print "SKIP,";
//                               } else if ( $ld->is_deleted ) {
//                                   print "DELETE,";
//                                   $actions = array('delete');
//                               }
//                           }
  
//                           //$this->syncLog->add($tableType, $ld->uuid, $rItem->uuid, null, null); // log no-action. for counts
//                           foreach($actions as $action) {
//                               $this->syncLog->add($tableType, $ld->$pk, $rItem->$pk, $action, $userMessage,$ldata, $rdata);
//                           }
//                   }
//                   catch (Exception $e) {
//                       $this->syncLog->add(array($e->getMessage(), $tableType, $ld->$pk, $rItem->$pk, @$actions[0]));
//                       $errors []= print_r($e->getMessage(),true);
//                   }
  
//                   print  ") ";
  
//               }
  
  
//               try {
//                   $deletables = $set->fetchHardDeletes();
//                   if ( $deletables ) {
//                       foreach($deletables as $d) {
//                           //TA:50
//                           print "DELETE: " . $tableType . ":" . $d->$pk . ";";//TA:50
//                           $pk = $d->getTable()->PK();
  //                         $this->syncLog->add($tableType, null, $d->$pk, 'delete');
//                       }
//                   }
//               } catch (Exception $e) {
//                   $this->syncLog->add($tableType, null, null, 'error');
//                   $errors []= print_r($e->getMessage(),true);
//               }
//           }
//           $this->syncLog->addTableCompleteMessage($tableType);
  
//           //TA:50
//           print "];\n";
//       }
//       return $errors;
//   }
  
  /*
   * Run through log for changes and operate on each 
   * @return 
   */
//   function doUpdatesProcessOld(){
//     $errors = array();
    
    
//      // do the queued items by table order 
//     foreach(self::$compareTypes as $tableType) {
//       $set = SyncSetFactory::create($tableType, SyncCompare::getDesktopConnectionParams($tableType,$this->desktopFilePath), $this->desktopFileId);
//       $list = $this->syncLog->pendingList($tableType);
//       foreach( $list as $logData) { 
//         try {
//             if ( !$logData->is_skipped AND !$logData->timestamp_completed ) {
            
//               switch($logData->action) {
//                 case 'insert':
//                 case 'update':
//                 case 'update-conflict':
//                   //verify any referenced items that we're not inserting already exist
//                   $set->verifyFKs($logData->left_id,$this->syncLog);
//                   break;
//               } 
//             }
            
//             //$this->syncLog->markDone($logData->id);
            
//         } catch (Exception $e) {
//           $this->syncLog->add(array($e->getMessage(), $tableType, $logData->left_id, '?', $logData));
//           $errors []= print_r($e, true);
//         }
        
//        }
//     }
      
//     if ( $errors ) return $errors;
    
//     // do the queued items by table order 
//     foreach(self::$compareTypes as $tableType) {
//       $set = SyncSetFactory::create($tableType, SyncCompare::getDesktopConnectionParams($tableType,$this->desktopFilePath), $this->desktopFileId);
//       $list = $this->syncLog->pendingList($tableType);
//       foreach( $list as $logData) { 
//         try {
//             if ( !$logData->is_skipped AND !$logData->timestamp_completed ) {
            
//               switch($logData->action) {
//                 case 'insert':
//                   $set->insertMember($logData->left_id, $this->desktopFilePath, $this->desktopFileId);
//                   break;
//                 case 'update':
//                 case 'update-conflict':
//                   $set->updateMember($logData->left_id, $logData->right_id);
//                   break;
//                 case 'delete':
//                   $set->deleteMember($logData->right_id);
//                   break;
//                 case 'add-alias':
//                   $set->addAliasMember($logData->left_id, $logData->right_id);
//                   break;
//               } 
//             }
            
//             $this->syncLog->markDone($logData->id);
            
//         }
//         catch (Exception $e) {
//           $errors []= print_r($e, true);
//         }
      
//       }
//     }
    
//     // mark job as done 
//     $time = $this->syncFile->now_expr();
//     $this->syncFile->update(array('timestamp_completed' => $time), '(id="'. $this->desktopFileId .'")');
//     return $errors;
//   }

//   }}

  }