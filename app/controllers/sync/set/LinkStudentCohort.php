<?php
// TA:50 added new class
require_once ('app/controllers/sync/set/Simple.php');
require_once ('app/models/table/LinkStudentCohort.php');

class SyncSetLinkStudentCohort extends SyncSetSimple
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
        parent::__construct($sourceDbParams, $syncfile_id, 'link_student_cohort');
    }

    protected function getColumns()
    {
        return array(
            'id_student',
            'id_cohort',
            'joindate',
            'joinreason',
            'dropdate',
            'dropreason',
            'isgraduated'
        );
    }
    
    //TA:#303, TA:#315,  $rd = DB, $ld = sqlite
	public function isDirty($ld,$rd) {
	    foreach($this->getColumns() as $col) {
	        if ( trim($ld[$col]) != trim($rd[$col])){
	            if(
	                ($col === 'dropdate' && !($rd[$col] === '0000-00-00' && trim($ld[$col]) === ''))
	                
	             ){
	             return true;
	           }
	       }
	    }
	    return false;
	}
    
    public function isConflict($ld, $rd){
//         if($ld['id'] === $rd['id'] && !($ld['id_cohort'] === $rd['id_cohort'] && $ld['id_student'] === $rd['id_student'])){
//             // print "Conflict id ". $ld['id'] . "=" . $rd['id'] . ";";
//             return true;
//         }
        return false;
    }

    protected function getTable($isLeft = true)
    {
        if ($isLeft) {
            return new LinkStudentCohort($this->sourceDbParams);
        }
        return new LinkStudentCohort();
    }

    public function fetchFieldMatch($ld)
    {
        //TA:50 print " fetchFieldMatch(id cohort): " . @$ld->id_cohort . "; "; // TA:50
         $row = $this->getRightTable()->fetchRow("(id_cohort=" . @$ld->id_cohort . " AND id_student=" . @$ld->id_student . " AND id=" . @$ld->id . ")");
        if ($row) {
            return $row;
        }
        return null;
    }
    
    public function deleteMember($right_id, $commit = false) {
        $rtable = $this->getRightTable();
        if($commit){
            $rtable->delete($rtable->PK().' = '.$right_id);
        }
        $rItem = $rtable->fetchRow("id=". $right_id);
        $this->log = $this->log . "DELETE: id_cohort=" . $rItem->id_cohort . ", id_student=" . $rItem->id_student . "\n";
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
		        if($ld['id_cohort'] === $rd['id_cohort'] && $ld['id_student'] === $rd['id_student']){
		            $found = true;
		            break;
		        }
		        
		    }
		    if(!$found)
		    {
		        // delete only if cohort exists in left table, otherwise link data might be entered by another user
		        $set_cohort = SyncSetFactory::create('cohort', SyncCompare::getDesktopConnectionParams('cohort',$path), $field);
		        $row_cohort = $set_cohort->getLeftTable()->fetchAll("id=" . $rd['id_cohort']);
		        if($row_cohort->toArray()){
		        // delete only if student exists in left table, otherwise student data might be entered by another user
		            $set_student = SyncSetFactory::create('student', SyncCompare::getDesktopConnectionParams('student',$path), $field);
		            $row_student = $set_student->getLeftTable()->fetchAll("id=" . $rd['id_student']);
		            if($row_student->toArray()){
		              $right_rows_to_delete []= $rd['id'];
		            }
		        }
		    }
        }
        return $right_rows_to_delete;
    }
    
    public function insertMember($left_id, $path=null, $field=null, $commit = false) {
        //check for insert of deleted item
        try {
            $lItem = $this->fetchLeftItemById($left_id, $this->tableName);
            $lTable = $this->getLeftTable();
            $rTable = $this->getRightTable();
    
            //do not insert if inserted already
            $rows = $rTable->fetchAll("id_cohort=" . $lItem->id_cohort . " and id_student=" . $lItem->id_student);
            // print_r($rows->toArray());
            if($rows->toArray())
                return;
    
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
                $new_link = $this->getNextId();
            } else {
    
            $new_link = $rItem->save();
            }
            $this->log = $this->log . "INSERT: id_cohort=" . $lItem->id_cohort .  ", id_student=" . $rItem->id_student . "\n";
            if(!$new_link) {
                $this->log = $this->log . "INSERT ERROR: id_cohort=" . $lItem->id_cohort .  ", id_student=" . $rItem->id_student . "\n";
                throw new Exception('Insert fail. '. $left_id .' Could not insert member:link_student_cohort.');
            }
    
        } catch(Exception $e) {
            //if it's a unique constraint violation, then move on, most likely it's an acceptible duplicate
            //from multiple imports of the same file
            if ( strstr($e->getMessage(),'Integrity constraint violation: 1062 Duplicate entry') === false)
                throw $e;
        }
    }

    protected function _map_fk($lItem, $fk, $type)
    {
        return parent::_map_fk($lItem, $fk, $type);
    }
}





