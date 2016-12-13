<?php

require_once ('app/controllers/sync/set/Simple.php');
require_once ('app/models/table/Person.php');
require_once ('app/models/table/Student.php');

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
		    //'timestamp_created',
		    //'timestamp_updated',
		    //'created_by',
		    //'modified_by',
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
	
	public function fetchFieldMatchOld($ld)
	{
	   //TA:50  print " fetchFieldMatch: " . @$ld->id . "; ";//TA:50
		//get uuid of facility
// 		$lfac = $this->fetchLeftItemById($ld->facility_id, 'facility');
// 		$rfac = $this->fetchRightItemByUuid($lfac->uuid, 'facility');
//         $rfac = $this->fetchRightItemById($lfac->id, 'facility');
		
// //TA:50		if ( !$rfac ) return null;
		
		$s = trim(strtolower((@$ld->first_name).(@$ld->middle_name).(@$ld->last_name)));
		$where = "(trim(lcase(CONCAT(IFNULL(first_name,''), IFNULL(middle_name,''), IFNULL(last_name,''))))=".$this->quote($s).")";
		if(@$ld->birthdate)
		  $where .= " AND birthdate = '". @$ld->birthdate . "' ";
		if(@$ld->facility_id)
	       $where .= " AND facility_id = ". @$ld->facility_id;
        
 		$row = $this->getRightTable()->fetchRow($where);
		if($row) {
			return $row;
		}
		
 		return null;
	}
	
	/*
	 * Match by five fields first_name, last_name, middle_name, datebirth, institutionid. If any of three are the same then return.
	 */
	public function fetchFieldMatchOld2($ld, $inst_id){
	    
// 	    TODO !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
// 	    ne pravil'no match. Nel'zya delat' match po:
// 	        birthdate, fisrt_name and inst_id, inache budet overwrite last_name
// 	        ili
// 	        birthdate, last_name and inst_id, inache budet overwrite first_name
	    
	    $row = null;
	    
	    $fn = " (trim(lcase(CONCAT(IFNULL(first_name,'')))))='".trim(strtolower((str_replace("'", "\'",@$ld->first_name)))) . "' ";
	    $ln = " (trim(lcase(CONCAT(IFNULL(last_name,'')))))='".trim(strtolower((str_replace("'", "\'", @$ld->last_name)))) . "' ";
	    $mn = " (trim(lcase(CONCAT(IFNULL(middle_name,'')))))='".trim(strtolower((str_replace("'", "\'", @$ld->middle_name)))) . "' ";
	    $dob = " birthdate = '". @$ld->birthdate . "' ";
	    $inst = " (
id in (select personid from student where institutionid=" . $inst_id . ")
or
id in (select personid from tutor where institutionid=" . $inst_id . ")) " ;
	    
	    if($inst_id){
	        $where = $fn . " and " . $ln . " and " . $inst . " and is_deleted=0";
	        $row = $this->getRightTable()->fetchRow($where);
	        if(!$row) {
	            $where = $fn . " and " . $mn . " and " . $inst . " and is_deleted=0";
	            $row = $this->getRightTable()->fetchRow($where);
	        }
	        if(!$row) {
	            $where = $ln . " and " . $mn . " and " . $inst . " and is_deleted=0";
	            $row = $this->getRightTable()->fetchRow($where);
	        }
	        if(!$row) {
	            $where = $fn . " and " . $dob . " and " . $inst . " and is_deleted=0";
	            $row = $this->getRightTable()->fetchRow($where);
	        }
	        if(!$row) {
	            $where = $ln . " and " . $dob . " and " . $inst . " and is_deleted=0";
	            $row = $this->getRightTable()->fetchRow($where);
	        }
	        if(!$row) {
	            $where = $mn . " and " . $dob . " and " . $inst . " and is_deleted=0";
	            $row = $this->getRightTable()->fetchRow($where);
	        }
	    }else{
	        if(!$row) {
	           $where = $fn . " and " . $ln . " and " . $mn . " and is_deleted=0";
	           $row = $this->getRightTable()->fetchRow($where);
	        }
	        if(!$row) {
	            $where = $fn . " and " . $ln . " and " . $dob . " and is_deleted=0";
	            $row = $this->getRightTable()->fetchRow($where);
	        }
	        if(!$row) {
	            $where = $fn . " and " . $mn . " and " . $dob . " and is_deleted=0";
	            $row = $this->getRightTable()->fetchRow($where);
	        }
	        if(!$row) {
	            $where = $ln . " and " . $mn . " and " . $dob . " and is_deleted=0";
	            $row = $this->getRightTable()->fetchRow($where);
	        }
	    }
	    
	    if($row) {
	        return $row;
	    }
	
	    return null;
	}
	
	/*
	 * TA:#303 Match by four fields first_name, last_name, middle_name, institutionid. 
	 */
	public function fetchFieldMatch($ld, $inst_id){
	     
	    $row = null;
	     
	    $fn = " (trim(lcase(CONCAT(IFNULL(first_name,'')))))='".trim(strtolower((str_replace("'", "\'",@$ld->first_name)))) . "' ";
	    $ln = " (trim(lcase(CONCAT(IFNULL(last_name,'')))))='".trim(strtolower((str_replace("'", "\'", @$ld->last_name)))) . "' ";
	    $mn = " (trim(lcase(CONCAT(IFNULL(middle_name,'')))))='".trim(strtolower((str_replace("'", "\'", @$ld->middle_name)))) . "' ";
	    $inst = "";
	    if($inst_id){
	    $inst = " and (
            id in (select personid from student where institutionid=" . $inst_id . ")
            or
            id in (select personid from tutor where institutionid=" . $inst_id . ")) " ;
	    }
	     
	    $where = $fn . " and " . $ln . " and " . $mn . $inst . " and is_deleted=0";
	    $row = $this->getRightTable()->fetchRow($where);
	     
	    if($row) {
	        return $row;
	    }
	
	    return null;
	}
	
	public function isDirtyOld($ld,$rd) {
	    foreach($this->getColumns() as $col) {
	        if ( $ld[$col] != $rd[$col]){
	           // print " Dirty id:" . $ld['id'] . "=" . $rd['id'] . "=>" . $col . ":". $ld[$col] . "=" . $rd[$col] . "; ";
	            return true;
	        }
	    }
	    return false;
	}
	
	//TA:#303 $rd = DB, $ld = sqlite
	public function isDirty($ld,$rd) {
	    foreach($this->getColumns() as $col) {
	        if(($col !== 'first_name' && $col !== 'last_name' && $col !== 'middle_name')
	            &&!(
	            ($rd[$col] === null && trim($ld[$col]) === '') ||
	            ($rd[$col] === null && trim($ld[$col]) === '0') ||
	            ($rd[$col] === 'na' && trim($ld[$col]) === '')
	        )
	            && trim($rd[$col]) !== trim($ld[$col])){
	            return true;
	        }   
	        }
	        return false;
	    }
	    
	    public function isConflictOld($ld, $rd){
	        $s = trim(strtolower((@$ld->first_name).(@$ld->middle_name).(@$ld->last_name)));
	        $where = "(trim(lcase(CONCAT(IFNULL(first_name,''), IFNULL(middle_name,''), IFNULL(last_name,''))))=".$this->quote($s).")";
	        if(@$ld->birthdate)
	            $where .= " AND birthdate = '". @$ld->birthdate . "' ";
	        if(@$ld->facility_id)
	            $where .= " AND facility_id = ". @$ld->facility_id;
	        if(@$ld->gender)//TA:1000
	            $where .= " AND gender = '". @$ld->gender. "' ";
	        $where .= " AND is_deleted = 0"; //TA:1000
	        $rows = $this->getRightTable()->fetchAll($where);
	        if($rows->toArray()) {
	            if(count($rows->toArray()) > 1){
	                $message = count($rows->toArray()) . " records are found for first_name=" . @$ld->first_name .
	                ", middle_name=" . @$ld->middle_name .
	                ", last_name=" . @$ld->last_name .
	                ", birthdate=" . @$ld->birthdate .
	                ", gender=" . @$ld->gender .
	                ", facility_id=" . @$ld->facility_id;
	                $this->log = $this->log . "CONFLICT: " . $message . "\n";
	                return $message;
	            }
	        }
	        return null;
	    }
	
	//TA:#303, TA:#315,  $rd = DB, $ld = sqlite, forse conflict do not allow to overwrite data
	public function isConflict($ld, $rd, $inst_id){
        $s = trim(strtolower((@$ld->first_name).(@$ld->middle_name).(@$ld->last_name)));
		$where = "(trim(lcase(CONCAT(IFNULL(first_name,''), IFNULL(middle_name,''), IFNULL(last_name,''))))=".$this->quote($s).")";
		if(@$ld->birthdate && trim(@$ld->birthdate) != '')
		    $where .= " AND birthdate = '". @$ld->birthdate . "' ";
// 		if(@$ld->facility_id && trim(@$ld->facility_id) !=='0')
// 	           $where .= " AND facility_id = ". @$ld->facility_id;
// 		if(@$ld->gender)//TA:1000
// 		    $where .= " AND gender = '". @$ld->gender. "' ";
		$where .= " AND is_deleted = 0"; //TA:1000
		$where .= " AND (person.id in (select personid from student where institutionid=$inst_id) or
person.id in (select personid from tutor where institutionid=$inst_id))";
 		$rows = $this->getRightTable()->fetchAll($where);
        if($rows->toArray()) {
            if(count($rows->toArray()) > 1){
                $message = count($rows->toArray()) . " records are found for first_name=" . @$ld->first_name . 
                ", middle_name=" . @$ld->middle_name .
                ", last_name=" . @$ld->last_name .
                ", birthdate=" . @$ld->birthdate .
               // ", gender=" . @$ld->gender .
               // ", facility_id=" . @$ld->facility_id;
                ", institution_id=" . $inst_id;
                //$this->log = $this->log . "CONFLICT: " . $message . "\n";
                $this->error = $this->error . "CONFLICT: " . $message . "\n";
                return $message;
            }
        }
	    return null;
	}
	
	public function updateMember($left_id, $right_id, $commit = false) {
	    $this->log = $this->log . "UPDATE: id:" . $right_id . ", ";
	    $lItem = $this->fetchLeftItemById($left_id, $this->tableName);
	    $rItem = $this->fetchRightItemById($right_id, $this->tableName);
	    $lTable = $this->getLeftTable();
	    $rTable = $this->getRightTable();
	    
	    $this->log = $this->log . "first_name:" . $rItem->first_name . ", middle_name:" . $rItem->middle_name . ", last_name:" . $rItem->last_name . ": ";
	     
	    foreach($this->getColumns() as $col) {
	        if(is_array($col)) {
	            list($fk, $type) = $col;
	            $rItem->$fk = $this->_map_fk($lItem, $fk, $type);
	        } else {
	            //update value except for first_name, last_name, middle_name
                //TA:#303 do not update if empty and null, empty and 0, null and 0
	            if(($col !== 'first_name' && $col !== 'last_name' && $col !== 'middle_name')
	                && !( 
	                ($rItem->$col === null && trim($lItem->$col) === '') ||
	                ($rItem->$col === null && trim($lItem->$col) === '0') ||
	                ($rItem->$col === 'na' && trim($lItem->$col) === '')
	                ) 
	                && trim($rItem->$col) !== trim($lItem->$col)){
	                   $this->log = $this->log . $col . ":" . $rItem->$col . "=>" . $lItem->$col . ", ";
	            }
	            $rItem->$col = trim($lItem->$col);
	        }
	    }
	    $this->log = $this->log . "\n";
	     
	    //undelete right side if necessary
	    if ( $lTable->has_is_deleted_col() && $rTable->has_is_deleted_col()) {
	        if ( $lItem->is_deleted == 0 )
	            $rItem->is_deleted = 0;
	    }
	     
	    if($commit){
	    $result = $rItem->save();
	    if(!$result) {
	        $this->log = $this->log .  "UPDATE ERROR: id=" . $lItem->id . ", first_name=" . $lItem->first_name .  ", middle_name=" . $lItem->middle_name . ", last_name=" . $lItem->last_name . "\n";
	        //throw new Exception("Update failed for table'" . $this->tableName . "':" . $left_id .'=>'. $right_id .' Could not update item.');
	    }
	     
	    return $result;
	    }
	    return null;
	}
	
	public function insertMember($left_id, $path, $field, $commit = false) {
	    //check for insert of deleted item
	    try {
	        $lItem = $this->fetchLeftItemById($left_id, $this->tableName);
	        $lTable = $this->getLeftTable();
	        $rTable = $this->getRightTable();
	        $rItem = $rTable->createRow();
	
	        foreach($this->getColumns() as $col) {
	            if(is_array($col)) {
	                list($fk, $type) = $col;
	
	                $rItem->$fk = $this->_map_fk($lItem, $fk, $type);
	            } else {
	                //update value
	                $rItem->$col = $lItem->$col;
	            }
	        }
	        if (! $commit) {
	            $new_person_id = $this->getNextId();
	        } else {
	        $new_person_id = $rItem->save();//TA:100 it seems that here did not take next 
	        }
	        $this->log = $this->log . "INSERT: id=" . $lItem->id . "=>" . $new_person_id . 
	        ", first_name=" .$lItem->first_name . ", middle_name=" . $lItem->middle_name . ", last_name=" .$lItem->last_name . "\n";
	        if(!$new_person_id) {
	            $this->log = $this->log . "INSERT ERROR: id=" . $lItem->id . "=>" . $new_person_id . 
	        ", first_name=" .$lItem->first_name . ", middle_name=" . $lItem->middle_name . ", last_name=" .$lItem->last_name . "\n";
	            throw new Exception('Insert fail. '. $left_id .' Could not insert member: person.');
	        }
	        
	        //upadte person id in person left table also (it needs for deleting data from links tables)
	        $lTable->getAdapter()->query("update person set id=" . $new_person_id . " where id=" . $lItem->id);
	         
	        //add link student details
	        $set_link_student = SyncSetFactory::create('student', SyncCompare::getDesktopConnectionParams('student',$path), $field);
	        //update in sqlite with new new_person_id
	        $left_table_student = $set_link_student->getLeftTable()->getAdapter();
	        $left_table_student->query("update student set personid =" . $new_person_id . " where personid=" . $lItem->id);
	        $left_table_student->query("update student set studentid =" . $new_person_id . " where studentid=" . $lItem->id);
	         
	        //add link tutor details
	        $set_link_tutor = SyncSetFactory::create('tutor', SyncCompare::getDesktopConnectionParams('tutor',$path), $field);
	        //update in sqlite with new new_person_id
	        $left_table_tutor = $set_link_tutor->getLeftTable()->getAdapter();
	        $left_table_tutor->query("update tutor set personid =" . $new_person_id . " where personid=" . $lItem->id);
	         

	    } catch(Exception $e) {
	        //if it's a unique constraint violation, then move on, most likely it's an acceptible duplicate
	        //from multiple imports of the same file
	        if ( strstr($e->getMessage(),'Integrity constraint violation: 1062 Duplicate entry') === false)
	            throw $e;
	    }
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





