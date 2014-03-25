<?php

require_once ('app/controllers/sync/set/Simple.php');
require_once ('app/models/table/Training.php');

class SyncSetTraining extends SyncSetSimple
{
	
	function __construct($sourceDbParams, $syncfile_id)
	{
		parent::__construct($sourceDbParams, $syncfile_id, 'training');
	}
	
	
	protected function getColumns() 
	{
		return array(
			array('training_title_option_id','training_title_option'), 
			'has_known_participants', 
			'training_start_date', 
			'training_end_date', 
			'training_length_value', 
			'training_length_interval', 
			array('training_organizer_option_id','training_organizer_option'), 
			array('training_location_id', 'training_location'), 
			array('training_level_option_id', 'training_level_option'), 
			array('training_method_option_id','training_method_option'), 
			array('training_custom_1_option_id','training_custom_1_option'), 
			array('training_custom_2_option_id','training_custom_2_option'), 
			array('training_got_curriculum_option_id','training_got_curriculum_option'), 
			array('training_primary_language_option_id', 'trainer_language_option'), 
			array('training_secondary_language_option_id', 'trainer_language_option'), 
			'comments', 
			'got_comments', 
			'objectives', 
			'is_approved', 
			'is_tot', 
			'is_refresher', 
			'pre', 
			'post'
		); 
	}
	
	protected function getTable($isLeft = true)
	{
		if($isLeft) {
			return new Training($this->sourceDbParams);
		}
		return new Training();
	}
	
	public function fetchFieldMatch($ld)
	{
		//look at training_location
		$ltl = $this->fetchLeftItemById($ld->training_location_id, 'training_location');
		$rtl = $this->fetchRightItemByUuid($ltl->uuid, 'training_location');
		
		if ( !$rtl ) return null;
		
		//look at training_title
		$ltt = $this->fetchLeftItemById($ld->training_title_option_id, 'training_title_option');
		$rtt = $this->fetchRightItemByUuid($ltt->uuid, 'training_title_option');
		
		if ( !$rtt ) return null;
		
		//strip off time part if it's there
		$datetime = explode(' ', $ld->training_start_date);
		$date = $datetime[0];
		
		$where = " (training_start_date = '". $date ."' )";
				
		$where .= " AND training_title_option_id = ".$rtt->id;
		$where .= " AND training_location_id = ".$rtl->id;
		$row = $this->getRightTable()->fetchRow($where);
		if($row) {
			return $row;
		}
		
		return null;
	}


}


