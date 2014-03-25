<?php

require_once ('app/controllers/sync/set/Simple.php');
require_once ('app/models/table/Trainer.php');

class SyncSetTrainer extends SyncSetSimple
{
	
	function __construct($sourceDbParams, $syncfile_id)
	{
				
		parent::__construct($sourceDbParams, $syncfile_id, 'trainer');
	}
	
	//TODO: trainer history
	protected function getColumns() 
	{
		return array(
			array('person_id', 'person'), 
			array('type_option_id', 'trainer_type_option'),
			array('active_trainer_option_id', 'person_active_trainer_option'),
			array('affiliation_option_id', 'trainer_affiliation_option'), 
			'is_active', 
		);
	}
	
	public function addAliasMember($left_id, $right_id) {
		return parent::addAliasMember($left_id,$right_id);
	}
	
	protected function getTable($isLeft = true)
	{
		if($isLeft) {
			return new Trainer($this->sourceDbParams);
		}
		return new Trainer();
	}
	
	public function fetchFieldMatch($ld)
	{
		//match on same person
		$lp = $this->fetchLeftItemById($ld->person_id, 'person');
		$rp = $this->fetchRightItemByUuid($lp->uuid, 'person');
		
		if ( !$rp ) return null;
		
		$where = '(person_id = '.$rp->id.')';
		$row = $this->getRightTable()->fetchRow($where);
		if($row) {
			return $row;
		}
		
		return null;
	}


}



