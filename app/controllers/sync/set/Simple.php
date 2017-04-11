<?php

/*
 * Base class for sync items
 *
 */


require_once ('app/models/table/SyncAlias.php');

class SyncSetSimple
{
	
	protected $sourceDbParams = null;
	protected static $aliasTable = null;
	
	public $tableName = null;
	public $syncfile_id = null;
	
	public $log = ""; //TA:50
	public $error = ""; //TA:#313
	public $last_id = null;
	
	//public static $used_ids = array();//TA:50
	
	/**
	 * Use getLeftTable or getRightTable to access these 
	 */
	private $lRefTable = null; //used for temporary lookups
	private $rRefTable = null; //used for temporary lookups
	private $lTable = null; //initialized once for members of this set
	private $rTable = null; //initialized once for members of this set
	
	
	function __construct($sourceDbParams, $syncfile_id, $tableName)
	{
		$this->tableName = $tableName;
		$this->sourceDbParams = $sourceDbParams;
		$this->syncfile_id = $syncfile_id;
		// make sure the db connection has the table name set
		$this->sourceDbParams['name'] = $this->tableName;
		
		if(self::$aliasTable == null)
			self::$aliasTable = new SyncAlias();
		
			
	}
	
   function quote($s){
   	  return $this->getRightTable()->getDefaultAdapter()->quote($s);
   }
	
	/*
	 * Column names of importable fields. Not including ids and meta info
	 * use array elements for foreign key relationships
	 * Array example: array(column1, column2, array(column3_id, reference_table), column4)
	 * @retrun (array) 
	 */
	protected function getColumns() 
	{
		throw new Exception ('columns not defined for table: '.$this->tableName);
	}
	
	/*
	 * Get a left or right table object
	 * Subclasses need to override to return a specialized table
	 * @param unknown_type $isLeft
	 */
	protected function getTable($isLeft = true)
	{
		if($isLeft) {
			return new ITechTable($this->sourceDbParams);
		}
		return new ITechTable(array('name' => $this->tableName));
	}
	
	
	/**
	 * Use this to get a (cached) version of a table to work with
	 * returns desktop sqlite table to either the main table of this set or a reference table
	 */
	protected function getLeftTable($tableName = null)
	{
		if ( !$tableName || ($tableName == $this->tableName) ) {
			if ( !$this->lTable )
				$this->lTable = $this->getTable(true);
			return $this->lTable;
		}
		
		//reference table or something else
		if ( !$this->lRefTable || ($this->lRefTable->Name() != $tableName) ) {
			$params = array_merge($this->sourceDbParams, array('name' => $tableName));
			$this->lRefTable = new ITechTable($params);
		}
		
		return $this->lRefTable;
	}
	
	/**
	 * Use this to get a (cached) version of a table to work with
	 * returns web mysql table to either the main table of this set or a reference table
	 */
	protected function getRightTable($tableName = null)
	{
		if ( !$tableName || ($tableName == $this->tableName) ) {
			if ( !$this->rTable )
				$this->rTable = $this->getTable(false);
			return $this->rTable;
		}
		
		//reference table or something else
		if ( !$this->rRefTable || ($this->rRefTable->Name() != $tableName) ) {
			$this->rRefTable = new ITechTable(array('name' => $tableName));
			
		}
		
		return $this->rRefTable;
		
	}
	
	
	/*
	 * Get the left item pool
	 * @return row collection 
	 */
	public function fetchLeftPool()
	{
	    //TA:50 remove condition by 'timestamp_updated'
		//$rows = $this->getLeftTable()->fetchAll('(timestamp_updated > "' . SyncCompare::$lastSyncCompleted . '")');
		$rows = $this->getLeftTable()->fetchAll();
		return $rows;
	}
	
	/**
	 * return a row collection from the right side
	 *
	 */
	public function fetchHardDeletes()
	{
		return null;
	}
	
	/**
	 * Check for an active object reference prior to delete
	 *
	 * @param row $rd
	 * @return boolean
	 */
	public function isReferenced($rd) {
		return true; //forbid deletions by default
	}

	/**
	 * Determines if this object type supports aliases or if they are always match by fields
	 *
	 * @return boolean
	 */
	public function usesAlias() {
		return true;
	}
	
	/*
	 * Retreive object from right db using a uuid from the right or left
	 * Checks for an alias if uuid not found in the target table
	 * @param (string) uuid, either a right uuid or left uuid alias
	 * @param (string) table name
	 * @return null or row object  
	 */
	function fetchRightItemByUuid($uuid, $refTableName = null)
	{
		if ( !$uuid ) return null;
		if (strlen($uuid) == 0 ) return null;
		
		// use the current table or search another  
		$table = $this->getRightTable($refTableName);
		
		// we have a uuid, return the unique match 
		$row = $table->fetchRow( " uuid = '" . $uuid ."' OR uuid IN (SELECT right_uuid FROM syncalias WHERE left_uuid  = '".$uuid."') ");
		
		if ( $row ) return $row;
		
		return null;
	}
	
		/*
	 * Retreive object from right db and table
	 * @param (int) id value 
	 * @param (string) table name
	 * @return null or row object  
	 */
	function fetchRightItemById($id, $refTableName = null)
	{
		$table = $this->getRightTable($refTableName);
				
		$table_PK = $table->PK(); //TA:50 this part is not working
		if(!$table_PK) //TA:50 do trick to fix it, by default usually primary key id 'id'
		    $table_PK = "id";
		
		// we have a uuid, return the unique match 
		$row = $table->fetchRow( $table_PK ." = ".$id);
		
		if ( $row ) return $row;
		
		return null;
			
	}
	
	
	/*
	 * Retreive object from left db and return row 
	 * @param (string) uuid value 
	 * @param (string) table name
	 * @return null or row object  
	 */
	function fetchLeftItemByUuid($uuid, $refTableName = null)
	{
		$table = $this->getLeftTable($refTableName);
				
		if($uuid) {
			return $table->fetchRow('(uuid="' . $uuid . '")');
		}
		
		return null;
	}
	
	//TA:50 
	function fetchLeftItemById($id, $refTableName = null){
	    
	    $table = $this->getLeftTable($refTableName);
	
	    $table_PK = $table->PK(); //TA:50 this part is not working 
	    if(!$table_PK) //TA:50 do trick to fix it, by default usually primary key id 'id'
	        $table_PK = "id";
	    
	    if($id) {
	        return $table->fetchRow('(' . $table_PK . '=' . $id . ')');
	    }
	
	    return null;
	}

	function fetchLeftItemByIdOld($id, $refTableName = null)
	{
		$table = $this->getLeftTable($refTableName);
				
		if($id) {
			return $table->fetchRow('('.$table->PK().'="' . $id . '")');
		}

		return null;
	}
	
	/*
	 * Do fuzzy search and match on primary fields
	 * @param (object) $ld left object
	 * @return (object) row-item matched || null if no match 
	 */
	public function fetchFieldMatch($ld)
	{
		return null;
	}
	
	/**
	 * Return whether the objects don't match or not
	 * For performance. If they do match, then we don't need to update.
	 *
	 * The two param objects have corresponding uuids or matching field values
	 * 
	 * @param unknown_type $ld
	 * @param unknown_type $rd
	 */
	public function isDirty($ld,$rd) {
		return true; //assume we need to do an update
	}
	
	/*
	 * Compare dirty objects for simultaneous changes.
	 * @param (object) $ld left object
	 * @param (object) $rd right object
	 * @return false or array($left_data, $right_data)
	 */
	public function isConflict($ld, $rd)
	{
		if ( !array_key_exists('timestamp_updated',$ld->toArray()) || !array_key_exists('timestamp_updated',$rd->toArray()))
			return true;
		
		// quick check on time only
		if(SyncCompare::$lastSyncCompleted) {
			if(($ld->timestamp_updated > SyncCompare::$lastSyncCompleted) && ($rd->timestamp_updated > SyncCompare::$lastSyncCompleted)) {
				return array($ld->toArray(), $rd->toArray());
			}
		}
		/* Not sure why we need to do field level checks - ToddW
		 * 
		 * 
		// quick check on delete
		if(isset($ld->is_deleted) && $ld->is_deleted != $rd->is_deleted) {
			return true;
		}

		// check special columns if defined 
		$columns = $this->getColumns();
		if(!empty($columns)) 
		{
			// first search the string fields 
			foreach($columns as $col) {
				if(is_string($col) && isset($ld->{$col})) {
					if($ld->{$col} != $rd->{$col}) {
SyncCompare::scratchData('is conflict match @'. $ld->{$col} .' @'. $rd->{$col});
						return 'At least one field did not match: '. $ld->{$col} .' - '. $rd->{$col};
					}
				}
			}
			// check refrence columns point to matching item on right 
			foreach($columns as $col) {
				if(is_array($col)) {
					list($id, $type) = array($ld->{$col[0]}, $col[1]);
					$rightMatch = $this->fetchRightItem($id, $type);
					$leftMatch = $this->fetchLeftItem($id, $type);
					if(!$rightMatch || $rightMatch->uuid != $leftMatch->uuid) {
						return 'At least one column pointed to different items: '. $type;
					}
				}
			}
		}
*/
		// default no conflict
		return false;
	}
	
	/*
	 * Make updates to insert, update or delete a item on the right, based on the left 
	 * @param (string) $leftUuid left-uuid from log table 
	 * @param (string) $rightUuid right-uuid from log table 
	 * @return results of an insert or update OR, false if failed 
	 */
	public function updateMember($left_id, $right_id){
	    $this->log = $this->log . "UPDATE: id:" . $right_id . ", ";
		$lItem = $this->fetchLeftItemById($left_id, $this->tableName);
		$rItem = $this->fetchRightItemById($right_id, $this->tableName);
		$lTable = $this->getLeftTable();
		$rTable = $this->getRightTable();
		
		foreach($this->getColumns() as $col) {
			if(is_array($col)) {
			    list($fk, $type) = $col;
				$rItem->$fk = $this->_map_fk($lItem, $fk, $type);
			} else {
				//update value
				if($rItem->$col !== $lItem->$col){
	                $this->log = $this->log . $col . ":" . $rItem->$col . "=>" . $lItem->$col . ", ";
	            }
				$rItem->$col = $lItem->$col;
			}
		}
		
		$this->log = $this->log . "\n";
		
		//undelete right side if necessary
		if ( $lTable->has_is_deleted_col() && $rTable->has_is_deleted_col()) {
			if ( $lItem->is_deleted == 0 )
				$rItem->is_deleted = 0;
		}
		
		// update existing item 
		$result = $rItem->save();
		if(!$result) {
		    $this->log = $this->log .  "UPDATE ERROR: id=" . $lItem->id . "\n";
			//throw new Exception("Update failed for table'" . $this->tableName . "':" . $left_id .'=>'. $right_id .' Could not update item.');
		}
		
		return $result;
	}

	/**
	 * return the same right side id for a left side id
	 *
	 * @param unknown_type $obj
	 * @param unknown_type $col //column mapping entry for a fk relationship
	 */
	protected function _map_fk($lItem, $fk, $type) {
		//1-look up uuid on the left
		//2-find uuid or alias on the right
		//3-find id on the right
		
		if ( $lItem->$fk < 1) return 0;
		if ( $lItem->$fk === null) return null;
				
		$lref = $this->fetchLeftItemById($lItem->$fk, $type);
		
		if ( !$lref ) {
			throw new Exception('Bad table mapping: '.$this->tableName.':'.$fk);
		}
		
		try {
			//special case for Location
			if ( ($type == 'location') && !intval($lref->is_created_offline)) {
        $rref = $this->fetchRightItemById($lref->id, $type);
			} else {
 			  //TA:50 do not take by UUID, only by id $rref = $this->fetchRightItemByUuid($lref->uuid, $type);
			    $rref = $this->fetchRightItemById($lref->id, $type);
			}
 			if(!$rref) {
				throw new Exception('FK lookup failed. Could not find required reference item. '. $type . ':'.$lref->uuid.' It is possible that the referenced item was deleted from the website.');
			}
		} catch(Exception $e) {
			throw $e;
		}
		
		$rtable = $this->getRightTable($type);
		$pk = $rtable->PK();
		
		return $rref->$pk;
	}
	
	public function deleteMember($right_id, $commit=false) {
	    if($commit){
		  $rtable = $this->getRightTable();
		  $rtable->delete($rtable->PK().' = '.$right_id);
	    }
	}
	
	//TA:50
	public function getNextId(){
	    if(!$this->last_id){
	        $db_opt = Zend_Db_Table_Abstract::getDefaultAdapter();
	        $this->last_id = $db_opt->fetchRow("select max(id) from " . $this->tableName)['max(id)'];
	    }
	    $this->last_id = $this->last_id +1;
	    return $this->last_id;
	}
	
	/**
	 * Create an uuid alias for an item on the left to another item on the right
	 *
	 * @param unknown_type $left_id
	 * @param unknown_type $right_id
	 */
	public function addAliasMember($left_id, $right_id) {
		
		$lItem = $this->fetchLeftItemById($left_id, $this->tableName);
		$rItem = $this->fetchRightItemById($right_id, $this->tableName);
		
// TA:50 we do not use uuid		
//if ( !$lItem OR !$rItem OR !$lItem->uuid OR !$rItem->uuid )
		if ( !$lItem OR !$rItem)
			throw new Exception('Alias failed @'. $left_id .' @'. $right_id .' Could not alias item.');
		
// TA:50 we do not use uuid		
//if ( $lItem->uuid == $rItem->uuid ) {
// 			throw new Exception('Alias not needed. Uuids match @'. $lItem->uuid);
// 		}
				
//TA:50 we do not use uuid 		$alias = array('syncfile_id'=>$this->syncfile_id, 'right_id'=> $right_id, 'right_uuid'=>$rItem->uuid, 'item_type'=> $this->tableName, 'left_uuid'=> $lItem->uuid, 'left_id'=>$left_id); 
		$alias = array('syncfile_id'=>$this->syncfile_id, 'right_id'=> $right_id, 'right_uuid'=>'', 'item_type'=> $this->tableName, 'left_uuid'=> '', 'left_id'=>$left_id);
		if(!self::$aliasTable->insert($alias)) {
			throw new Exception('Alias failed @'. $left_id .' @'. $right_id .' Could not alias item.');
		}
	}
	
	public function verifyFKs($left_id, $log) {
    
   //check for insert of deleted item
	  //check that foreign keys exist or we have a pending fk insert
    try {
      $lItem = $this->fetchLeftItemById($left_id, $this->tableName);
       
      foreach($this->getColumns() as $col) {
        if(is_array($col)) {
          list($fk, $type) = $col;
          
          //could probably be optimized
          $list = $log->pendingList($type);
          $is_pending_insert = false;
          foreach($list as $log_item) {
          	if ( (($log_item->action == 'insert') || ($log_item->action == 'add-alias')) &&  ($log_item->left_id == $lItem->$fk) ) {
          		$is_pending_insert = true;
          	}
          }
          
          if ( !$is_pending_insert )
            $this->_map_fk($lItem, $fk, $type);
        }
      }
      
    } catch(Exception $e) {
       throw new Exception('Foreign key verification failed. The '.$this->tableName.' reference to '.$fk.' '.$lItem->$fk.' does not exist.' . $e->getTraceAsString());
    }		
	}
	
	
	public function insertMember($left_id, $path= null, $field=null, $commit= false) {

		//check for insert of deleted item
		try {
			$lItem = $this->fetchLeftItemById($left_id, $this->tableName);
			$lTable = $this->getLeftTable();
			$rTable = $this->getRightTable();
			$rItem = $rTable->createRow();
			
			foreach($this->getColumns() as $col) {
				if(is_array($col)) {
					list($fk, $type) = $col;
					
					$rItem->$fk = $this->_map_fk($lItem, $fk, $type);
				} else {
					//update value
					$rItem->$col = $lItem->$col;
				}
			}
			
// 			//TA:50 also copy id
// 			$rItem->id = $lItem->id;
			
			//also copy uuid and timestamp_created
			if ( $lTable->has_uuid_col() && $rTable->has_uuid_col() ) {
				$rItem->uuid = $lItem->uuid;
			} else if ( $rTable->has_uuid_col() ) {
				$rItem->uuid = uniqid();
			}
			if($lTable->has_time_created_col() && $rTable->has_time_created_col()){//TA:50
			     $rItem->timestamp_created = $lItem->timestamp_created;
			}
			if ( $rTable->has_is_default_col() )
				$rItem->is_default = 0;
					
			$result = $rItem->save();
			if(!$result) {
				throw new Exception('Insert fail. '. $left_id .' Could not insert member.');
		}
		} catch(Exception $e) {
			//if it's a unique constraint violation, then move on, most likely it's an acceptible duplicate
			//from multiple imports of the same file
			if ( strstr($e->getMessage(),'Integrity constraint violation: 1062 Duplicate entry') === false)
			 throw $e;
		}
	}
	
	//TA:50
	public function getLog() {
	    return $this->log;
	}
	
	//TA:#315
	public function getError() {
	    return $this->error;
	}
	
}









