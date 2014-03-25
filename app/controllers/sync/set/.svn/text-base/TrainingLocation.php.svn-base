<?php

require_once ('app/controllers/sync/set/Simple.php');
require_once ('app/models/table/TrainingLocation.php');

class SyncSetTrainingLocation extends SyncSetSimple
{
	
	function __construct($sourceDbParams, $syncfile_id)
	{
		parent::__construct($sourceDbParams, $syncfile_id, 'training_location');
	}
	
	
	protected function getTable($isLeft = true)
	{
		if($isLeft) {
			return new TrainingLocation($this->sourceDbParams);
		}
		return new TrainingLocation();
	}

	protected function getColumns() 
	{
		return array( 
			'training_location_name', 
			array('location_id', 'location'), 
		);
	}
	
	public function fetchFieldMatch($ld)
	{
		//get uuid of location
		$lloc = $this->fetchLeftItemById($ld->location_id, 'location');
		$rloc = $this->fetchRightItemByUuid($lloc->uuid, 'location');
		
		if ( !$rloc ) return null;
		
		$where = '(training_location_name='. $this->quote($ld->training_location_name) .' AND location_id='. $this->quote($rloc->id) .')';
		$row = $this->getRightTable()->fetchRow($where);
		if($row) {
			return $row;
		}
		
		return null;
	}
	
	

}










