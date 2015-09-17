<?php
//TA:50 added 
require_once ('app/controllers/sync/set/Simple.php');
require_once ('app/models/table/Institution.php');

class SyncSetInstitution extends SyncSetSimple
{

	function __construct($sourceDbParams, $syncfile_id)
	{
		parent::__construct($sourceDbParams, $syncfile_id, 'institution');
	}
	
	
	protected function getColumns() 
	{
		return array( 
				'institutionname', 
				'address1', 
		          'address2',
				'city', 
		      'postalcode',
		    'phone',
		    'fax',
				'type', 
				'sponsor', 
            'geography1',
		    'geography2',
		    'computercount',
		    'dormcount', 
		    'bedcount', 
		    'hasdormitories', 
		    'tutorhousing',
		    'tutorhouses', 
		    'yearfounded', 
		    'comments', 
		    'customfield1', 
		    'customfield2',
		    'degrees'
			);
	}
	
	protected function getTable($isLeft = true){
	    if($isLeft) {
			return new Institution($this->sourceDbParams);
		}
		return new Institution();
	}
	
	public function fetchFieldMatch($ld)
	{
	    //TA:50 print " fetchFieldMatch: " . @$ld->institutionname . "; ";
		$row = $this->getRightTable()->fetchRow("(institutionname='". @$ld->institutionname. "')");
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
		return Person::isReferenced($rd->id);
	}
	
	protected function _map_fk($lItem, $fk, $type) {
		return parent::_map_fk($lItem,$fk,$type);
	}
	
}





