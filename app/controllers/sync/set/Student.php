<?php

require_once ('app/controllers/sync/set/Simple.php');
require_once ('app/models/table/Student.php');

class SyncSetStudent extends SyncSetSimple
{

	function __construct($sourceDbParams, $syncfile_id)
	{
		parent::__construct($sourceDbParams, $syncfile_id, 'student');
	}
	
	
	protected function getColumns() 
	{
		return array( 
		     'personid',
		    'studentid',
		     'institutionid',
		    'comments',
		    'postaddress1',
		    'postfacilityname',
		    'hscomldate',
		    'lastinstatt',
		    'schoolstartdate',
		    'lastunivatt',
		    'personincharge',
		    'emergcontact',
		    'geog1',
		    'geog2',
		    'isgraduated',
		    'studenttype',
		    'cadre',
		    'advisorid',
		    'equivalence',
		    'postgeo1',
		    'postgeo2'
			);
	}
	
	protected function getTable($isLeft = true)
	{
		if($isLeft) {
			return new Student($this->sourceDbParams);
		}
		return new Student();
	}
	
	public function fetchFieldMatch($ld)
	{
	  //TA:50   print " fetchFieldMatch: " . @$ld->personid . "; ";//TA:50
		
		$row = $this->getRightTable()->fetchRow("(personid='". @$ld->personid. "')");
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

	public function isReferenced($rd) {
		return Student::isReferenced($rd->id);
	}
	
	protected function _map_fk($lItem, $fk, $type) {
		
		return parent::_map_fk($lItem,$fk,$type);
	}
	
}





