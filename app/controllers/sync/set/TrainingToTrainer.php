<?php

require_once ('app/controllers/sync/set/Simple.php');
require_once ('app/models/table/MultiOptionList.php');

/**
 * Handles training_to_trainer
 *
 */
class SyncSetTrainingToTrainer extends SyncSetMultiOptionList
{
	
	function __construct($sourceDbParams, $syncfile_id)
	{
		parent::__construct($sourceDbParams, $syncfile_id, 'training_to_trainer', 
				array('training_id','training'), 
				array('trainer_id','trainer'), array('duration_days'));
	}
	
	public function fetchLeftPool()
	{
		$rows = $this->getLeftTable()->fetchAll('(timestamp_created > "' . SyncCompare::$lastSyncCompleted . '")');
		return $rows;
	}
	
	public function isReferenced($rd) {
		return false;//no references
	}
	
}



