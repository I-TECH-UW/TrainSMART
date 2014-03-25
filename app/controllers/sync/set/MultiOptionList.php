<?php

require_once ('app/controllers/sync/set/Simple.php');
require_once ('app/models/table/OptionList.php');

/**
 * Handles multi-to-mutli relationships such as trainer_to_trainer_skill_option, etc.
 *
 */
class SyncSetMultiOptionList extends SyncSetSimple
{
	
	protected $owner_col = null; //training or person. array of (fk,table); e.g. array('trainer_id', 'trainer');
	protected $option_col = null; //array of (fk, table); e.g. array('trainer_skill_option_id', 'trainer_skill_option')
	protected $extra_cols = null; //additional data cols

	
	function __construct($sourceDbParams, $syncfile_id, $tableName, $owner_col, $option_col, $extra_cols = array())
	{
		$this->owner_col = $owner_col;
		$this->option_col = $option_col;
		$this->extra_cols = $extra_cols;
		parent::__construct($sourceDbParams, $syncfile_id, $tableName);
	}
	
	
	protected function getColumns() 
	{
		$cols = $this->extra_cols;
		$cols []= $this->owner_col;
		$cols []= $this->option_col;
		return $cols;
	}
	
	public function addAliasMember($left_id, $right_id)
	{
		//no aliases for these	
	}
	
	/**
	 * Determines if this object type supports aliases or if they are always match by fields
	 *
	 * @return boolean
	 */
	public function usesAlias() {
		return false;
	}
	
	public function isDirty($ld,$rd) {
		if ( !$this->extra_cols )
			return false;
		
		foreach($this->extra_cols as $col) {
			if ( is_array($col) ) {
				return true; //need to do a lookup on a fk ref, so assume it's dirty
			}
			
			if ( $ld->$col != $rd->$col)
				return true;
		}
		
		return false; //if we found any sort of matching objects, then we don't need to update anything unless there are extra cols
	}
	
	
	/**
	 * Only return stuff where the parent object has been modified
	 *
	 * @return rowset
	 */
	public function fetchLeftPool()
	{
		$parentTable = $this->getLeftTable($this->owner_col[1]);
		
		$rows = $this->getLeftTable()->fetchAll( $this->owner_col[0].' IN (SELECT '.$parentTable->PK().' FROM '.$parentTable->Name().' WHERE timestamp_updated > "' . SyncCompare::$lastSyncCompleted . '" )');
		return $rows;
	}
	
	public function fetchHardDeletes()
	{
		$delTableDB = $this->getLeftTable()->getAdapter();
		
		$sql = "SELECT d.* FROM ".$this->tableName.'_deleted'." d ".
				" LEFT JOIN ".$this->tableName." t ON d.".$this->owner_col[0]." = t.".$this->owner_col[0]." AND d.".$this->option_col[0]." = t.".$this->option_col[0].
				"	WHERE t.id IS NULL AND d.timestamp_created >  '" . SyncCompare::$lastSyncCompleted . "'";
		
		$results = $delTableDB->query($sql);
		if ( $results ) {
			//find right side
			$right_rows = array();
			foreach($results->fetchAll() as $ld) {
				$tmp = new stdClass();
				$owner_col = $this->owner_col[0];
				$option_col = $this->option_col[0];
				$tmp->$owner_col = $ld[$owner_col];
				$tmp->$option_col = $ld[$option_col];
				$rrow = $this->fetchFieldMatch($tmp);
				if ( $rrow )
					$right_rows []= $rrow;
			}
			return $right_rows;
		}
		
		return null;
	}
	
	
	/**
	 * Probably don't need this since we're only inserting option rows
	 *
	 * @param unknown_type $ld
	 * @return unknown
	 */
	public function fetchFieldMatch($ld)
	{
		//maybe we can optimize this if we know the owner object is an insert
		
		//match on same parent and same option
		$owner_key = $this->owner_col[0];
		$owner_table = $this->owner_col[1];
		
		$lp = $this->fetchLeftItemById($ld->$owner_key, $owner_table);
		$rp = $this->fetchRightItemByUuid($lp->uuid, $owner_table);
		if (!$rp) return null;
		
		$rtable = $this->getRightTable($owner_table);
		$owner_pk = $rtable->PK();
		
		$opt_key = $this->option_col[0];
		$opt_table = $this->option_col[1];

		$lopt = $this->fetchLeftItemById($ld->$opt_key, $opt_table);
		$ropt = null;
		if ( is_object($lopt) ) {
			$ropt = $this->fetchRightItemByUuid($lopt->uuid, $opt_table);
		}
		if ( !$ropt ) return null;
		
		$rtable = $this->getRightTable($opt_table);
		$opt_pk = $rtable->PK();
		
		
		$where = "(".$owner_key."=". $this->quote($rp->$owner_pk) .")"." AND (".$opt_key."=". $this->quote($ropt->$opt_pk) .")";
		$row = $this->getRightTable()->fetchRow($where);
		if($row) {
			return $row;
		}
		
		return null;
	}


}



