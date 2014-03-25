<?php

require_once ('app/controllers/sync/set/Simple.php');
require_once ('app/models/table/Person.php');

class SyncSetPerson extends SyncSetSimple
{

	function __construct($sourceDbParams, $syncfile_id)
	{
		parent::__construct($sourceDbParams, $syncfile_id, 'person');
	}
	
	
	protected function getColumns() 
	{
		return array( 
				array('title_option_id', 'person_title_option'), 
				'first_name', 
				'middle_name', 
				'last_name', 
				array('suffix_option_id', 'person_suffix_option'), 
				'national_id', 
				'file_number', 
				'birthdate', 
				'gender', 
				array('facility_id', 'facility'), 
				'phone_work', 
				'phone_mobile', 
				'fax', 
				'phone_home', 
				'email', 
				'email_secondary', 
				array('primary_qualification_option_id', 'person_qualification_option'),
				array('primary_responsibility_option_id', 'person_primary_responsibility_option'), 
				array('secondary_responsibility_option_id', 'person_secondary_responsibility_option'), 
				'comments', 
				array('person_custom_1_option_id', 'person_custom_1_option'),
				array('person_custom_2_option_id', 'person_custom_2_option'),
				'home_address_1', 
				'home_address_2', 
				array('home_location_id', 'location'), 
				'home_postal_code', 
				'active', 
			);
	}
	
	protected function getTable($isLeft = true)
	{
		if($isLeft) {
			return new Person($this->sourceDbParams);
		}
		return new Person();
	}
	
	public function fetchFieldMatch($ld)
	{
		//get uuid of facility
		$lfac = $this->fetchLeftItemById($ld->facility_id, 'facility');
		$rfac = $this->fetchRightItemByUuid($lfac->uuid, 'facility');
		
		if ( !$rfac ) return null;
		
		
		$s = trim(strtolower((@$ld->first_name).(@$ld->middle_name).(@$ld->last_name)));
		$where = "(trim(lcase(CONCAT(IFNULL(first_name,''), IFNULL(middle_name,''), IFNULL(last_name,''))))=".$this->quote($s).")";
		$where .= " AND facility_id = ".$rfac->id;
		$row = $this->getRightTable()->fetchRow($where);
		if($row) {
			return $row;
		}
		
		return null;
	}

	public function isReferenced($rd) {
		return Person::isReferenced($rd->id);
	}
	
	protected function _map_fk($lItem, $fk, $type) {
		//Location has no 0 "undefined" value
		if ( ($fk == 'home_location_id') && ($lItem->$fk < 1) ) return null;

		return parent::_map_fk($lItem,$fk,$type);
	}
	
}





