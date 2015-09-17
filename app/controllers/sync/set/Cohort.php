<?php
//TA:50 added 
require_once ('app/controllers/sync/set/Simple.php');
require_once ('app/models/table/Cohort.php');

class SyncSetCohort extends SyncSetSimple
{

	function __construct($sourceDbParams, $syncfile_id)
	{
		parent::__construct($sourceDbParams, $syncfile_id, 'cohort');
	}
	
	
	protected function getColumns() 
	{
		return array( 
				'cohortname', 
				'startdate', 
				'graddate', 
				'degree', 
				'institutionid', 
				'cadreid', 
			);
	}
	
	protected function getTable($isLeft = true){
	    if($isLeft) {
			return new Cohortedit($this->sourceDbParams);
		}
		return new Cohortedit();
	}
	
	public function fetchFieldMatch($ld)
	{
	    //TA:50 print " fetchFieldMatch: " . @$ld->cohortname . "; ";//TA:50
		$row = $this->getRightTable()->fetchRow("(cohortname='". @$ld->cohortname. "')");
		if($row) {
			return $row;
		}
		
		return null;
	}
	
	public function isDirty($ld,$rd) {
	    foreach($this->getColumns() as $col) {
	        if ( $ld[$col] != $rd[$col])
	            return true;
	    }
	    return false;
	}
	
	public function isConflict($ld, $rd){
	    return false;
	}
	
	public function fetchHardDeletes(){
	    $right_rows = $this->getRightTable()->fetchAll();
	    $left_table = $this->getLeftTable()->getAdapter();
	    $right_rows_to_delete = array();
	
	    $sql = "SELECT * FROM ".$this->tableName;
	
	    $left_rows = $left_table->query($sql)->fetchAll();
	    foreach($right_rows as $rd) {
	        $found = false;
	        foreach($left_rows as $ld) {
	            if($ld['cohortname'] === $rd['cohortname']){
	                $found = true;
	                break;
	            }
	
	        }
	        if(!$found)
	            $right_rows_to_delete []= $rd;
	    }
	    return $right_rows_to_delete;
	}

	public function isReferenced($rd) {
		return Person::isReferenced($rd->id);
	}
	
	protected function _map_fk($lItem, $fk, $type) {
		return parent::_map_fk($lItem,$fk,$type);
	}
	
}





