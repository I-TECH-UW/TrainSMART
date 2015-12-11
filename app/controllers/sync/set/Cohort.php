<?php
//TA:50 added 
require_once ('app/controllers/sync/set/Simple.php');
require_once ('app/models/table/Cohort.php');

class SyncSetCohort extends SyncSetSimple
{

	function __construct($sourceDbParams, $syncfile_id)
	{
		parent::__construct($sourceDbParams, $syncfile_id, 'cohort');
	}
	
	
	protected function getColumns() 
	{
		return array( 
				'cohortname', 
				'startdate', 
				'graddate', 
				'degree', 
				//'institutionid', 
				'cadreid', 
			);
	}
	
	protected function getTable($isLeft = true){
	    if($isLeft) {
			return new Cohortedit($this->sourceDbParams);
		}
		return new Cohortedit();
	}
	
	public function fetchHardDeletes($path, $field){
	    return null;
	}
	
	public function updateMember($left_id, $right_id, $commit = false) {

	    $this->log = $this->log . "UPDATE: id:" . $right_id;
	    $lItem = $this->fetchLeftItemById($left_id, $this->tableName);
	    $rItem = $this->fetchRightItemById($right_id, $this->tableName);
	    $lTable = $this->getLeftTable();
	    $rTable = $this->getRightTable();
	    
	    $this->log = $this->log . ", cohortname:" . $rItem->cohortname . ", ";
	    foreach($this->getColumns() as $col) {
	        if(is_array($col)) {
	            list($fk, $type) = $col;
	            $rItem->$fk = $this->_map_fk($lItem, $fk, $type);
	        } else {
	            //update value
	            if($rItem->$col !== $lItem->$col){
	                $this->log = $this->log . $col . ":" . $rItem->$col . "=>" . $lItem->$col . ", ";
	            }
	            $rItem->$col = $lItem->$col;
	        }
	    }
	    $this->log = $this->log . "\n";
	    
	    //undelete right side if necessary
	    if ( $lTable->has_is_deleted_col() && $rTable->has_is_deleted_col()) {
	        if ( $lItem->is_deleted == 0 )
	            $rItem->is_deleted = 0;
	    }
	    
	    if($commit){
	    // update existing item
	    $result = $rItem->save();
	    if(!$result) {
	        $this->log = $this->log .  "UPDATE ERROR: id=" . $lItem->id . ", cohortname=" . $lItem->cohortname . "\n";
	        //throw new Exception("Update failed for table'" . $this->tableName . "':" . $left_id .'=>'. $right_id .' Could not update item.');
	    }
	    
	    return $result;
	    }return null;
	}
	
	public function fetchFieldMatch($ld)
	{
	    //TA:50 print " fetchFieldMatch: " . @$ld->cohortname . "; ";//TA:50
		$row = $this->getRightTable()->fetchRow("(cohortname='". str_replace("'", "\'", @$ld->cohortname) . "')");
		//$row = $this->getRightTable()->fetchRow("(id=". @$ld->id . ")");
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
        $rows = $this->getRightTable()->fetchAll("(cohortname='". str_replace("'", "\'", @$ld->cohortname). "')");
        if($rows->toArray()) {
            if(count($rows->toArray()) > 1){
                $message = count($rows->toArray()) . " records are found for cohortname=" . @$ld->cohortname;
                $this->log = $this->log . "CONFLICT: " . $message . "\n";
                return $message;
            }
        }
	    return false;
	}
	
	public function insertMember($left_id, $path, $field, $commit = false) {
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
	
	        //also copy uuid and timestamp_created
	        if ( $lTable->has_uuid_col() && $rTable->has_uuid_col() ) {
	            $rItem->uuid = $lItem->uuid;
	        } else if ( $rTable->has_uuid_col() ) {
	            $rItem->uuid = uniqid();
	        }
	        if($lTable->has_time_created_col() && $rTable->has_time_created_col()){//TA:50
	            $rItem->timestamp_created = $lItem->timestamp_created;
	        }
	        if ( $rTable->has_is_default_col() )
	            $rItem->is_default = 0;
	        
	        if (! $commit) {
	            $new_id = $this->getNextId();
	        } else {
	           $new_id = $rItem->save();
	        }
	        $this->log = $this->log . "INSERT:  id=" . $new_id . ", cohortname=" . $lItem->cohortname . "\n";
	        if(!$new_id) {
	            $this->log = $this->log .  "INSERT ERROR: cohortname=" . $lItem->cohortname . "\n";
	            throw new Exception('Insert fail. '. $left_id .' Could not insert member: cohort.');
	        }
	        
	        //upadte cohort id in cohort left table also (it needs for deleting data from links tables)
	        $lTable->getAdapter()->query("update cohort set id=" . $new_id . " where id=" . $lItem->id);
	         
            //upadte cohort id in link_cohorts_classes 
	        $set_link_classes = SyncSetFactory::create('link_cohorts_classes', SyncCompare::getDesktopConnectionParams('link_cohorts_classes',$path), $field);
	        //update in sqlite with new cohort_id
	        $left_table_link_classes = $set_link_classes->getLeftTable()->getAdapter();
	        $left_table_link_classes->query("update link_cohorts_classes set cohortid=" . $new_id . " where cohortid=" . $lItem->id);
	        
	        //upadte cohort id in practicum 
	        $set_link_practicum = SyncSetFactory::create('practicum', SyncCompare::getDesktopConnectionParams('practicum',$path), $field);
	        //update in sqlite with new cohort_id
	        $left_table_link_practicum = $set_link_practicum->getLeftTable()->getAdapter();
	        $left_table_link_practicum->query("update practicum set cohortid=" . $new_id . " where cohortid=" . $lItem->id);
	        
	        //upadte cohort id in licenses 
	        $set_link_licenses = SyncSetFactory::create('licenses', SyncCompare::getDesktopConnectionParams('licenses',$path), $field);
	        //update in sqlite with new cohort_id
	        $left_table_link_licenses = $set_link_licenses->getLeftTable()->getAdapter();
	        $left_table_link_licenses->query("update licenses set cohortid=" . $new_id . " where cohortid=" . $lItem->id);
	        
	        //aupadte cohort id in link_student_cohort 
	        $set_link_student = SyncSetFactory::create('link_student_cohort', SyncCompare::getDesktopConnectionParams('link_student_cohort',$path), $field);
	        //update in sqlite with new cohort_id
	        $left_table_link_student = $set_link_student->getLeftTable()->getAdapter();
	        $left_table_link_student->query("update link_student_cohort set id_cohort=" . $new_id . " where id_cohort=" . $lItem->id);
	        
	        //upadte cohort id in link_student_classes 
	        $set_link_classes_student = SyncSetFactory::create('link_student_classes', SyncCompare::getDesktopConnectionParams('link_student_classes',$path), $field);
	        //update in sqlite with new cohort_id
	        $left_table_link_classes_student = $set_link_classes_student->getLeftTable()->getAdapter();
	        $left_table_link_classes_student->query("update link_student_classes set cohortid=" . $new_id . " where cohortid=" . $lItem->id);
	        
	        //upadte cohort id in link_student_practicums 
	        $set_link_practicums_student = SyncSetFactory::create('link_student_practicums', SyncCompare::getDesktopConnectionParams('link_student_practicums',$path), $field);
	        //update in sqlite with new student_id
	        $left_table_link_practicums_student = $set_link_practicums_student->getLeftTable()->getAdapter();
	        $left_table_link_practicums_student->query("update link_student_practicums set cohortid=" . $new_id . " where cohortid=" . $lItem->id);
	        
	        //upadte cohort id in link_student_licenses 
	        $set_link_licenses_student = SyncSetFactory::create('link_student_licenses', SyncCompare::getDesktopConnectionParams('link_student_licenses',$path), $field);
	        //update in sqlite with new student_id
	        $left_table_link_licenses_student = $set_link_licenses_student->getLeftTable()->getAdapter();
	        $left_table_link_licenses_student->query("update link_student_licenses set cohortid=" . $new_id . " where cohortid=" . $lItem->id);
	
	
	    } catch(Exception $e) {
	        //if it's a unique constraint violation, then move on, most likely it's an acceptible duplicate
	        //from multiple imports of the same file
	        if ( strstr($e->getMessage(),'Integrity constraint violation: 1062 Duplicate entry') === false)
	            throw $e;
	    }
	}

	public function isReferenced($rd) {
		return Cohortedit::isReferenced($rd->id);
	}
	
	protected function _map_fk($lItem, $fk, $type) {
		return parent::_map_fk($lItem,$fk,$type);
	}
	
}





