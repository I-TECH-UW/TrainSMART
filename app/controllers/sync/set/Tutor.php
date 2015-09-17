<?php

require_once ('app/controllers/sync/set/Simple.php');
require_once ('app/models/table/Tutor.php');

class SyncSetTutor extends SyncSetSimple
{

	function __construct($sourceDbParams, $syncfile_id)
	{
		parent::__construct($sourceDbParams, $syncfile_id, 'tutor');
	}
	
	
	protected function getColumns() 
	{
		return array( 
		     'personid',
		    'degree',
		     'positionsheld',
		    'comments',
		    'specialty',
		    'contract_type',
		    'degreeinst',
		    'languagesspoken',
		    'positionsheld',
		    'specialty',
		    'contract_type',
		    'facilityid',
		    'institutionid',
		    'tutorsince',
		    'tutortimehere',
		    'degreeyear'
			);
	}
	
	protected function getTable($isLeft = true)
	{
		if($isLeft) {
			return new Tutor($this->sourceDbParams);
		}
		return new Tutor();
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
	
	public function fetchFieldMatch($ld)
	{
	   //TA:50  print " fetchFieldMatch: " . @$ld->personid . "; ";//TA:50
		
		$row = $this->getRightTable()->fetchRow("(personid='". @$ld->personid. "')");
		if($row) {
			return $row;
		}
		
		return null;
	}

	public function isReferenced($rd) {
		return Student::isReferenced($rd->id);
	}
	
	protected function _map_fk($lItem, $fk, $type) {
		
		return parent::_map_fk($lItem,$fk,$type);
	}
	
}





