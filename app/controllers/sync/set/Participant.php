<?php

require_once ('app/controllers/sync/set/Simple.php');
require_once ('MultiOptionList.php');

/**
 * Handles person_to_training
 *
 */
class SyncSetParticipant extends SyncSetMultiOptionList
{
	
	function __construct($sourceDbParams, $syncfile_id)
	{
		parent::__construct($sourceDbParams, $syncfile_id, 'person_to_training', array('training_id','training'), array('person_id','person'));
	}
	
	/**
	 * Only return stuff where the parent object has been modified
	 *
	 * @return rowset
	 */
	public function fetchLeftPool()
	{
		$where = '(timestamp_created > "' . SyncCompare::$lastSyncCompleted . '")';
		$rows = $this->getLeftTable()->fetchAll($where);
		return $rows;
	}
	
	public function isReferenced($rd) {
		return false;//no references
	}
}



