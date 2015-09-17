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
				'title_option_id', 
				'first_name', 
				'middle_name', 
				'last_name', 
				//TA:50 array('suffix_option_id', 'person_suffix_option'), 
				'national_id', 
				'file_number', 
				'birthdate', 
				'gender', 
				'facility_id', 
				'phone_work', 
				'phone_mobile', 
				'fax', 
				'phone_home', 
				'email', 
				'email_secondary', 
				'primary_qualification_option_id',
				'primary_responsibility_option_id', 
				'secondary_responsibility_option_id', 
				'comments', 
				'person_custom_1_option_id',
				'person_custom_2_option_id',
				'home_address_1', 
				'home_address_2',
				'home_location_id', 
				'home_postal_code', 
				'active', 
		    //TA:50 all below added
		    'home_city',   
		         'marital_status',
		    'spouse_name', 
		    'home_is_residential',
		    'timestamp_created',
		    'timestamp_updated',
		    'created_by',
		    'modified_by',
		    'highest_edu_level_option_id',
		    'attend_reason_option_id',
		    'attend_reason_other',
		    'highest_level_option_id',
		    'nationality_id',
		    'custom_field1',
		    'custom_field2',
		    'custom_field3'
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
	   //TA:50  print " fetchFieldMatch: " . @$ld->first_name . "; ";//TA:50
		//get uuid of facility
		////TA:50$lfac = $this->fetchLeftItemById($ld->facility_id, 'facility');
		//TA:50 by ID only $rfac = $this->fetchRightItemByUuid($lfac->uuid, 'facility');
////TA:50 		$rfac = $this->fetchRightItemById($lfac->id, 'facility');
		
// //TA:50		if ( !$rfac ) return null;
		
		$s = trim(strtolower((@$ld->first_name).(@$ld->middle_name).(@$ld->last_name)));
		$where = "(trim(lcase(CONCAT(IFNULL(first_name,''), IFNULL(middle_name,''), IFNULL(last_name,''))))=".$this->quote($s).")";
	////TA:50	$where .= " AND facility_id = ".$rfac->id;
		$row = $this->getRightTable()->fetchRow($where);
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
		//Location has no 0 "undefined" value
		if ( ($fk == 'home_location_id') && ($lItem->$fk < 1) ) return null;

		return parent::_map_fk($lItem,$fk,$type);
	}
	
}





