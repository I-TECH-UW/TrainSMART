<?php
//TA:50 added 
require_once ('app/controllers/sync/set/Simple.php');
require_once ('app/models/table/Addresses.php');

class SyncSetAddresses extends SyncSetSimple
{

	function __construct($sourceDbParams, $syncfile_id)
	{
		parent::__construct($sourceDbParams, $syncfile_id, 'addresses');
	}
	
	
	protected function getColumns() 
	{
		return array( 
				'id_geog1', 
				'id_geog2', 
		          'address1',
				'address2', 
		      'city',
		    'postalcode',
			);
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
	
	protected function getTable($isLeft = true){
	    if($isLeft) {
			return new Addresses($this->sourceDbParams);
		}
		return new Addresses();
	}
	
	public function fetchFieldMatch($ld)
	{
	    //TA:50  print " fetchFieldMatch: " . @$ld->id_geog1 . "; ";//TA:50
		$row = $this->getRightTable()->fetchRow("(id_geog1=". @$ld->id_geog1. " and id_geog2=" .  @$ld->id_geog2 . ")");
		if($row) {
			return $row;
		}
		
		return null;
	}

	public function isReferenced($rd) {
		return Person::isReferenced($rd->id);
	}
	
	protected function _map_fk($lItem, $fk, $type) {
		return parent::_map_fk($lItem,$fk,$type);
	}
	
}





