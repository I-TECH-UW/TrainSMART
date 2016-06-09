<?php

require_once ('app/controllers/sync/set/Simple.php');
require_once ('app/models/table/Student.php');

class SyncSetStudent extends SyncSetSimple
{

	function __construct($sourceDbParams, $syncfile_id)
	{
		parent::__construct($sourceDbParams, $syncfile_id, 'student');
	}
	
	
	protected function getColumns() 
	{
		return array( 
		     'personid',
		    'studentid',
		    'institutionid',
		    'comments',
		    'postaddress1',
		    'postfacilityname',
		    'hscomldate',
		    'lastinstatt',
		    'schoolstartdate',
		    'lastunivatt',
		    'personincharge',
		    'emergcontact',
		    'geog1',
		    'geog2',
		    'isgraduated',
		    'studenttype',
		    'cadre',
		    'advisorid',
		    'equivalence',
		    'postgeo1',
		    'postgeo2'
			);
	}
	
	public function getTable($isLeft = true)
	{
		if($isLeft) {
			return new Student($this->sourceDbParams);
		}
		return new Student();
	}
	
	public function fetchFieldMatch($ld){
		$row = $this->getRightTable()->fetchRow("(personid='". @$ld->personid. "')");
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
        $rows = $this->getRightTable()->fetchAll("(personid='". @$ld->personid. "')");
        if($rows->toArray()) {
            if(count($rows->toArray()) > 1){
                $message =  count($rows->toArray()) . " records are found for personid=" . @$ld->personid;
                $this->log = $this->log . "CONFLICT: " . $message . "\n";
                return $message;
            }
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
	            $new_student_id = $this->getNextId();
	        } else {
	        $new_student_id = $rItem->save();
	        }
	        $this->log = $this->log . "INSERT: id=" . $lItem->id . "=>". $new_student_id.   ", personid=" . $lItem->personid . "\n";
	        if(!$new_student_id) {
	            $this->log = $this->log . "INSERT ERROR: id=" . $lItem->id . "=>". $new_student_id.   ", personid=" . $lItem->personid . "\n";
	            throw new Exception('Insert fail. '. $left_id .' Could not insert member: student.');
	        }
	        
	        //upadte student id in student left table also (it needs for deleting data from links tables)
	        $lTable->getAdapter()->query("update student set id=" . $new_student_id . " where id=" . $lItem->id);
	
	        //add link_student_cohort details
	        $set_link_cohort = SyncSetFactory::create('link_student_cohort', SyncCompare::getDesktopConnectionParams('link_student_cohort',$path), $field);
	        //update in sqlite with new student_id
	        $left_table_link_cohort = $set_link_cohort->getLeftTable()->getAdapter();
	        $left_table_link_cohort->query("update link_student_cohort set id_student=" . $new_student_id . " where id_student=" . $lItem->id);
	
	        //add link_student_licenses details
	        $set_link_licenses = SyncSetFactory::create('link_student_licenses', SyncCompare::getDesktopConnectionParams('link_student_licenses',$path), $field);
	        //update in sqlite with new student_id
	        $left_table_link_licenses = $set_link_licenses->getLeftTable()->getAdapter();
	        $left_table_link_licenses->query("update link_student_licenses set studentid=" . $new_student_id . " where studentid=" . $lItem->id);
	        
	        //add link_student_practicums details
	        $set_link_practicums = SyncSetFactory::create('link_student_practicums', SyncCompare::getDesktopConnectionParams('link_student_practicums',$path), $field);
	        //update in sqlite with new student_id
	        $left_table_link_practicums = $set_link_practicums->getLeftTable()->getAdapter();
	        $left_table_link_practicums->query("update link_student_practicums set studentid=" . $new_student_id . " where studentid=" . $lItem->id);
	        
	        //add link_student_funding details
	        $set_link_funding = SyncSetFactory::create('link_student_funding', SyncCompare::getDesktopConnectionParams('link_student_funding',$path), $field);
	        //update in sqlite with new student_id
	        $left_table_link_funding = $set_link_funding->getLeftTable()->getAdapter();
	        $left_table_link_funding->query("update link_student_funding set studentid=" . $new_student_id . " where studentid=" . $lItem->id);
	        
	        //add link_student_classes details
	        $set_link_classes = SyncSetFactory::create('link_student_classes', SyncCompare::getDesktopConnectionParams('link_student_classes',$path), $field);
	        //update in sqlite with new student_id
	        $left_table_link_classes = $set_link_classes->getLeftTable()->getAdapter();
	        $left_table_link_classes->query("update link_student_classes set studentid=" . $new_student_id . " where studentid=" . $lItem->id);
	        
	        //add link_student_addresses details
	        $set_link_addresses = SyncSetFactory::create('link_student_addresses', SyncCompare::getDesktopConnectionParams('link_student_addresses',$path), $field);
	        //update in sqlite with new student_id
	        $left_table_link_addresses = $set_link_addresses->getLeftTable()->getAdapter();
	        $left_table_link_addresses->query("update link_student_addresses set id_student=" . $new_student_id . " where id_student=" . $lItem->id);
	        
	    } catch(Exception $e) {
	        //if it's a unique constraint violation, then move on, most likely it's an acceptible duplicate
	        //from multiple imports of the same file
	        if ( strstr($e->getMessage(),'Integrity constraint violation: 1062 Duplicate entry') === false)
	            throw $e;
	    }
	}
	
	public function updateMember($left_id, $right_id, $commit = false) {
	   
	    $lItem = $this->fetchLeftItemById($left_id, $this->tableName);
	    $rItem = $this->fetchRightItemById($right_id, $this->tableName);
	    $lTable = $this->getLeftTable();
	    $rTable = $this->getRightTable();
	    
	    $this->log = $this->log . "UPDATE: id:" . $right_id . ", personid:" . $rItem->personid . ", ";
	
	    foreach($this->getColumns() as $col) {
	        if(is_array($col)) {
	            list($fk, $type) = $col;
	            $rItem->$fk = $this->_map_fk($lItem, $fk, $type);
	        } else {
	            //update value
	            //if($col == 'first_name' || $col == 'middle_name'|| $col == 'last_name' || $rItem->$col !== $lItem->$col){
	              // $this->log = $this->log . $col . ":" . $rItem->$col . "=>" . $lItem->$col . ", ";
	           // }
	           if($rItem->$col !== $lItem->$col){
	               $this->log = $this->log . $col . ":" . $rItem->$col . "=>" . $lItem->$col . ", ";
	               $rItem->$col = $lItem->$col;
	           }
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
	        $this->log = $this->log .  "UPDATE ERROR: id=" . $lItem->id . "\n";
	        //throw new Exception("Update failed for table'" . $this->tableName . "':" . $left_id .'=>'. $right_id .' Could not update item.');
	    }
	
	    return $result;
	    }
	    return null;
	}

	public function isReferenced($rd) {
		return Student::isReferenced($rd->id);
	}
	
	protected function _map_fk($lItem, $fk, $type) {
		
		return parent::_map_fk($lItem,$fk,$type);
	}
	
}





