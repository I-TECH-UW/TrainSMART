<?php

require_once ('app/controllers/sync/set/Simple.php');
require_once ('app/models/table/Location.php');

class SyncSetLocation extends SyncSetSimple
{
	
	
	
	function __construct($sourceDbParams, $syncfile_id)
	{
		parent::__construct($sourceDbParams, $syncfile_id, 'location');
	}
	
	
	protected function getTable($isLeft = true)
	{
		if($isLeft) {
			return new Location($this->sourceDbParams);
		}
		return new Location();
	}

	protected function getColumns() 
	{
		return array( 
			array('parent_id', 'location'), 
			'location_name', 
			'tier' 
			);
	}
	
	public function has_uuid_col()
	{
		return true; // bugfix, its saying no in location table for some reason
	}

	public function fetchLeftPool()
	{
		//get city tier
		$settings = System::getAll();
		$city_tier = 2 +  $settings['display_region_i'] + $settings['display_region_h'] + $settings['display_region_g'] +  $settings['display_region_f'] +  $settings['display_region_e'] +  $settings['display_region_d'] +  $settings['display_region_c'] + $settings['display_region_b'];
		
		//only return cities
		$rows = $this->getLeftTable()->fetchAll('(timestamp_updated > "' . SyncCompare::$lastSyncCompleted . '") AND tier = '.$city_tier);
		return $rows;
	}
	
	public function fetchFieldMatch($ld)
	{
		$s = trim(strtolower($ld->location_name));
		//we can match on parent id's here instead of uuids, since parent locations can't be added to the desktop
		$where = '(parent_id="'. $ld->parent_id .'" AND trim(lcase(location_name))='.$this->quote($s).')';
		$row = $this->getRightTable()->fetchRow($where);
		if($row) {
			return $row;
		}
		
		return null;
	}
}
