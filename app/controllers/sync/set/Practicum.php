<?php
// TA:50 added new class
require_once ('app/controllers/sync/set/Simple.php');
require_once ('app/models/table/Practicum.php');

class SyncSetPracticum extends SyncSetSimple
{

    public function fetchLeftPool()
    {
        $rows = $this->getLeftTable()->fetchAll();
        return $rows;
    }

    public function isReferenced($rd)
    {
        return false; // no references
    }

    function __construct($sourceDbParams, $syncfile_id)
    {
        parent::__construct($sourceDbParams, $syncfile_id, 'practicum');
    }

    protected function getColumns()
    {
        return array(
            'practicumname',
            'practicumdate',
            'cohortid',
            'facilityid',
            'advisorid',
            'hoursrequired',
        );
    }
    
    public function isDirty($ld,$rd) {
        foreach($this->getColumns() as $col) {
            if ( $ld[$col] != $rd[$col])
                return true;
        }
        return false;
    }
    
    public function isConflict($ld, $rd){
        $rows = $this->getRightTable()->fetchAll("(practicumname='". str_replace("'", "\'", @$ld->practicumname).
            "' AND cohortid=" . @$ld->cohortid . 
            " AND facilityid=" . @$ld->facilityid .
            " AND practicumdate='" . @$ld->practicumdate .
            "')");
        if($rows->toArray()) {
            if(count($rows->toArray()) > 1){
                $message = count($rows->toArray()) . " records are found for cohortid=" . @$ld->cohortid . 
                ", practicumname=" . @$ld->practicumname .
                ", facilityid=" . @$ld->facilityid .
                ", practicumdate=" . @$ld->practicumdate;
                $this->log = $this->log . "CONFLICT: " . $message . "\n";
                return $message;
            }
        }
        return false;
    }

    protected function getTable($isLeft = true)
    {
        if ($isLeft) {
            return new Practicum($this->sourceDbParams);
        }
        return new Practicum();
    }

    public function fetchFieldMatch($ld)
    {
        //TA:50 print " fetchFieldMatch(cohort id): " . @$ld->cohortid . "; "; // TA:50
//          $row = $this->getRightTable()->fetchRow("(cohortid=" . @$ld->cohortid . " AND id=" . @$ld->id . " AND practicumname='" . str_replace("'", "\'", @$ld->practicumname) . "')");
        $row = $this->getRightTable()->fetchRow("(cohortid=" . @$ld->cohortid .  
            " AND practicumname='" . str_replace("'", "\'", @$ld->practicumname) .
            "' AND facilityid=" . @$ld->facilityid .
            " AND practicumdate='" . @$ld->practicumdate .
            "')");
        if ($row) {
            return $row;
        }
        return null;
    }

    public function fetchHardDeletes($path, $field){
       // return null; //do not allow delete, beacuse it can be deleted data entered by other users
        $right_rows = $this->getRightTable()->fetchAll();
        $left_table = $this->getLeftTable()->getAdapter();
        $right_rows_to_delete = array();
		
		$sql = "SELECT * FROM ".$this->tableName;
		
		$left_rows = $left_table->query($sql)->fetchAll();
		foreach($right_rows as $rd) {
		    $found = false;
		    foreach($left_rows as $ld) {
 		        if($ld['cohortid'] == $rd['cohortid'] && $ld['practicumname'] == $rd['practicumname']
 		            && $ld['facilityid'] == $rd['facilityid'] && $ld['practicumdate'] == $rd['practicumdate']){
		            $found = true;
		            break;
		        }
		        
		    }
		    if(!$found){
		        // delete only if cohort exists in left table, otherwise link data might be entered by another user
		        $set_cohort = SyncSetFactory::create('cohort', SyncCompare::getDesktopConnectionParams('cohort',$path), $field);
		        $row_cohort = $set_cohort->getLeftTable()->fetchAll("id=" . $rd['cohortid']);
		        if($row_cohort->toArray()){
		            $right_rows_to_delete []= $rd['id'];
		        }
		    }	
        }
        return $right_rows_to_delete;
    }
    
    public function deleteMember($right_id, $commit = false) {
        $rtable = $this->getRightTable();
        if ($commit) {
            $rtable->delete($rtable->PK().' = '.$right_id);
        }
        $rItem = $rtable->fetchRow("id=". $right_id);
        $this->log = $this->log . "DELETE: cohortid=" . $rItem->cohortid . ", practicumname=" . $rItem->practicumname . "\n";
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
                $new_practicum_id = $this->getNextId();
            } else {
                $new_practicum_id = $rItem->save();
            }
            $this->log = $this->log . "INSERT: cohortid=" . $lItem->cohortid . ",practicumname=" . $lItem->practicumname . 
             ", facilityid=" . $lItem->facilityid . ", practicumdate=" . $lItem->practicumdate ."\n";
            if(!$new_practicum_id) {
                $this->log = $this->log . "INSERT ERROR: cohortid=" . $lItem->cohortid . ", practicumname=" . $lItem->practicumname . 
                ", facilityid=" . $lItem->facilityid . ", practicumdate=" . $lItem->practicumdate ."\n";
                throw new Exception('Insert fail. '. $left_id .' Could not insert member: practicum.');
            }
            
            //upadte practicum id in practicum left table also (it needs for deleting data from links tables)
            $lTable->getAdapter()->query("update practicum set id=" . $new_practicum_id . " where id=" . $lItem->id);
    
            //add link_student_practicums details
	        $set_link_student = SyncSetFactory::create('link_student_practicums', SyncCompare::getDesktopConnectionParams('link_student_practicums',$path), $field);
	        //update in sqlite with new practicum_id
	        $left_table_link_student = $set_link_student->getLeftTable()->getAdapter();
	        $left_table_link_student->query("update link_student_practicums set practicumid=" . $new_practicum_id . " where practicumid=" . $lItem->id);
             
             
        } catch(Exception $e) {
            if ( strstr($e->getMessage(),'Integrity constraint violation: 1062 Duplicate entry') === false)
                throw $e;
        }
    }

    protected function _map_fk($lItem, $fk, $type)
    {
        return parent::_map_fk($lItem, $fk, $type);
    }
}





