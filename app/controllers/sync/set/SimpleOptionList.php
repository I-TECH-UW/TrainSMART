<?php

require_once ('app/controllers/sync/set/Simple.php');
require_once ('app/models/table/OptionList.php');

class SyncSetSimpleOptionList extends SyncSetSimple
{
	
	public $type = 'optionlist';
	private $value_col = null;
	
	function __construct($sourceDbParams, $syncfile_id, $tableName, $value_col)
	{
		$this->value_col = $value_col;
		parent::__construct($sourceDbParams, $syncfile_id, $tableName);
	}
	
	protected function getColumns() 
	{
		return array(
			$this->value_col
		);
	}
	
	protected function getTable($isLeft = true)
	{
		if($isLeft) {
			return new OptionList($this->sourceDbParams);
		}
		return new OptionList(array('name'=>$this->tableName));
	}

	public function isDirty($ld,$rd) {
		return false; //if we found any sort of matching objects, then we don't need to update anything
	}
	
	/**
	 * Only return new stuff. We don't update or delete option rows
	 *
	 * @return unknown
	 */
	public function fetchLeftPool()
	{
		//bug fix: we need to check old records in the db now, sometimes titles/options are deleted and used by the app.
		//$rows = $this->getLeftTable()->fetchAll('(timestamp_updated > "' . SyncCompare::$lastSyncCompleted . '" AND is_created_offline = 1)');
		$rows = $this->getLeftTable()->fetchAll();
		return $rows;
	}
	
	/**
	 * Probably don't need this since we're only inserting option rows
	 *
	 * @param unknown_type $ld
	 * @return unknown
	 */
	public function fetchFieldMatch($ld)
	{
		//compare text values
		$val_col = trim(strtolower($this->value_col));
		$where = "(trim(lcase(".$val_col.")) = ". $this->quote($ld->$val_col) .")";
		$row = $this->getRightTable()->fetchRow($where);
		if($row) {
			return $row;
		}
		
		return null;
	}


}



