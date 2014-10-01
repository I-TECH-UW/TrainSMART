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
  protected $syncLog = null;
  protected $syncFile = null;
  
  // Last sync time to compare since 
  static public  $lastSyncCompleted = null;
  
  // Our items to work on 
  static public $compareTypes = array(
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

  /*
   * Search for left db and right db differences
   * @return  
   */
  function findDifferencesProcess()
  {
    
    $errors = array();
    
    $this->syncLog->truncate(); // delete unfinished items for db file 
    foreach(self::$compareTypes as $tableType) 
    {
      
      $set = SyncSetFactory::create($tableType, SyncCompare::getDesktopConnectionParams($tableType,$this->desktopFilePath), $this->desktopFileId);
      if ( $set ) {
        $leftItems = $set->fetchLeftPool(); //desktop db
        $meta_done = false;
        $pk = 'id';
        foreach($leftItems as $ld) 
        { 
          $rItem = $actions = array();
          try {
            $ldata = null;
            $rdata = null;
            if ( !$meta_done ) {
              $has_uuid = $ld->getTable()->has_uuid_col();
              $meta_done = true;
              $pk = $ld->getTable()->PK();
            }     
            
              $userMessage = '';
              
              if ( $has_uuid && $ld->uuid )
                $rItem = $set->fetchRightItemByUuid($ld->uuid);
              
              if ($rItem) { //exact uuid match on item
                if ( $set->isDirty($ld, $rItem) ) {           
                  if ($set->isConflict($ld, $rItem)) { // true if conflict
                    $userMessage = 'Update (with conflict)';
                    $actions[] = 'update-conflict';
                    
                    $ldata = $ld->toArray();
                    $rdata = $rItem->toArray();
                    
                  } else {
                    $userMessage = 'Update';
                    $actions[] = 'update';
                  }
                }
              } else { //no exact uuid match on item, look for field level match
                $rItem = $set->fetchFieldMatch($ld);
                if ($rItem) {
                  if ($set->usesAlias())
                    $actions[] = 'add-alias';
                  if ( $set->isDirty($ld, $rItem) ) {           
                    if ( $set->isConflict($ld, $rItem) ) {
                      $userMessage = "Update (with conflict)";
                      $actions[] = 'update-conflict';
                  
                      $ldata = $ld->toArray();
                      $rdata = $rItem->toArray();
                    } else {
                      $userMessage = 'Update';
                      $actions[] = 'update';
                    }
                  }
                } else { //no right match 
                  
                    //do we insert deleted rows? no
                    $has_soft_delete = $ld->getTable()->has_is_deleted_col();
                    if ( !$has_soft_delete OR ($has_soft_delete && !$ld->is_deleted)) {
                      $actions[] = 'insert';
                      $rItem = new stdClass(); 
                      $rItem->uuid = null;
                      $rItem->$pk = null;
                      $userMessage = 'New item';
                    }
                }
              }
              
              if ( ((!$actions) OR ($actions[0] != 'insert')) && 
                  $rItem && $rItem->getTable()->has_is_deleted_col() ) {
                if ( ($rItem->is_deleted && $ld->is_deleted) || ($ld->is_deleted && $set->isReferenced($rItem)) ) {
                  //if they're both deleted then skip
                  $actions = array();//skip
                } else if ( $ld->is_deleted ) {
                  $actions = array('delete');
                }
              }
              
              //$this->syncLog->add($tableType, $ld->uuid, $rItem->uuid, null, null); // log no-action. for counts 
              foreach($actions as $action) {
                $this->syncLog->add($tableType, $ld->$pk, $rItem->$pk, $action, $userMessage,$ldata, $rdata);
              }
          }
          catch (Exception $e) {
            $this->syncLog->add(array($e->getMessage(), $tableType, $ld->$pk, $rItem->$pk, @$actions[0])); 
            $errors []= print_r($e->getMessage(),true);
          }
          
        }
        
        try {
          $deletables = $set->fetchHardDeletes(); 
          if ( $deletables ) {
            foreach($deletables as $d) {
              $pk = $d->getTable()->PK();
              $this->syncLog->add($tableType, null, $d->$pk, 'delete'); 
            }
          }
        } catch (Exception $e) {
            $this->syncLog->add($tableType, null, null, 'error'); 
            $errors []= print_r($e->getMessage(),true);
        }
      }
      $this->syncLog->addTableCompleteMessage($tableType);
      
    } 
    return $errors;
  }
  
  /*
   * Run through log for changes and operate on each 
   * @return 
   */
  function doUpdatesProcess()
  {
    $errors = array();
    
    
     // do the queued items by table order 
    foreach(self::$compareTypes as $tableType) {
      $set = SyncSetFactory::create($tableType, SyncCompare::getDesktopConnectionParams($tableType,$this->desktopFilePath), $this->desktopFileId);
      $list = $this->syncLog->pendingList($tableType);
      foreach( $list as $logData) { 
        try {
            if ( !$logData->is_skipped AND !$logData->timestamp_completed ) {
            
              switch($logData->action) {
                case 'insert':
                case 'update':
                case 'update-conflict':
                  //verify any referenced items that we're not inserting already exist
                  $set->verifyFKs($logData->left_id,$this->syncLog);
                  break;
              } 
            }
            
            //$this->syncLog->markDone($logData->id);
            
        } catch (Exception $e) {
          $this->syncLog->add(array($e->getMessage(), $tableType, $logData->left_id, '?', $logData));
          $errors []= print_r($e, true);
        }
        
       }
    }
      
    if ( $errors ) return $errors;
    
    // do the queued items by table order 
    foreach(self::$compareTypes as $tableType) {
      $set = SyncSetFactory::create($tableType, SyncCompare::getDesktopConnectionParams($tableType,$this->desktopFilePath), $this->desktopFileId);
      $list = $this->syncLog->pendingList($tableType);
      foreach( $list as $logData) { 
        try {
            if ( !$logData->is_skipped AND !$logData->timestamp_completed ) {
            
              switch($logData->action) {
                case 'insert':
                  $set->insertMember($logData->left_id);
                  break;
                case 'update':
                case 'update-conflict':
                  $set->updateMember($logData->left_id, $logData->right_id);
                  break;
                case 'delete':
                  $set->deleteMember($logData->right_id);
                  break;
                case 'add-alias':
                  $set->addAliasMember($logData->left_id, $logData->right_id);
                  break;
              } 
            }
            
            $this->syncLog->markDone($logData->id);
            
        }
        catch (Exception $e) {
          $errors []= print_r($e, true);
        }
      
      }
    }
    
    // mark job as done 
    $time = $this->syncFile->now_expr();
    $this->syncFile->update(array('timestamp_completed' => $time), '(id="'. $this->desktopFileId .'")');
    return $errors;
  }

  /*
   * Dump data in a file. Includes execution time and mem usage. File truncated on first run. 
   */
  /*
  public static function scratchData()
  {
    static $startTime = 0;
    $scratchFile = '/syncscratch.txt';
    $scratchData = func_get_args();
    $memoryUse = memory_get_usage();
    
    if(!$startTime) {
      file_put_contents($scratchFile, '');
      $startTime = (float)array_sum(explode(' ', microtime()));
    }
    
    foreach((array)$scratchData as $k => $v) {
      $scratchData[$k] = serialize($v) ? serialize($v) : $v;
    }
    
    if($memoryUse < 1024) {
      $memoryUse = $memoryUse .'bytes';
    }
    elseif($memoryUse < 1048576) {
      $memoryUse = round($memoryUse / 1024, 2) .'kb';
    }
    else {
      $memoryUse = round($memoryUse / 1048576, 2) .'mb';
    }
    
    $endTime = ((float)array_sum(explode(' ', microtime())) - $startTime);
    $scratchData = "\n\r" . implode('|', array_merge(array(date('j/n/Y H:i:s'), $endTime, $memoryUse), $scratchData));
    file_put_contents($scratchFile, $scratchData, FILE_APPEND | LOCK_EX);
    return $endTime;
  }
*/
}

