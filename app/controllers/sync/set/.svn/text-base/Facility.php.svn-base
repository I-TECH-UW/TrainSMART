<?php

require_once ('app/controllers/sync/set/Simple.php');
require_once ('app/models/table/Facility.php');

class SyncSetFacility extends SyncSetSimple
{
	
	function __construct($sourceDbParams, $syncfile_id)
	{
		parent::__construct($sourceDbParams, $syncfile_id, 'facility');
	}
	
	
	protected function getColumns() 
	{
		return array(
			'facility_name', 
			'address_1', 
			'address_2', 
			array('location_id', 'location'), 
			'postal_code', 
			'phone', 
			'fax', 
			array('sponsor_option_id', 'facility_sponsor_option'), 
			array('type_option_id', 'facility_type_option'), 
			'facility_comments'
		);
	}
	
	protected function getTable($isLeft = true)
	{
		if($isLeft) {
			return new Facility($this->sourceDbParams);
		}
		return new Facility();
	}
	
	public function fetchFieldMatch($ld)
	{
		//get uuid of location
		$lloc = $this->fetchLeftItemById($ld->location_id, 'location');
		$rloc = $this->fetchRightItemByUuid($lloc->uuid, 'location');
		
		if ( !$rloc ) return null;
		$quoted_fn = $this->quote($ld->facility_name);
		$where = '(facility_name='.$quoted_fn.' AND location_id="'. $rloc->id .'")';
		$row = $this->getRightTable()->fetchRow($where);
		if($row) {
			return $row;
		}
		
		return null;
	}

	public function isReferenced($rd) {
		return Facility::isReferenced($rd->id);
	}
}



