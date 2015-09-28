<?php
// TA:50 added new class
require_once ('app/controllers/sync/set/Simple.php');
require_once ('app/models/table/LinkStudentFunding.php');

class SyncSetLinkStudentFunding extends SyncSetSimple
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
        parent::__construct($sourceDbParams, $syncfile_id, 'link_student_funding');
    }

    protected function getColumns()
    {
        return array(
            'studentid',
                'fundingsource',
                 'fundingamount',
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
//         if($ld['id'] === $rd['id'] && !($ld['studentid'] === $rd['studentid'] && $ld['fundingsource'] === $rd['fundingsource'])){
//             // print "Conflict id ". $ld['id'] . "=" . $rd['id'] . ";";
//             return true;
//         }
        return false;
    }

    protected function getTable($isLeft = true)
    {
        if ($isLeft) {
            return new LinkStudentFunding($this->sourceDbParams);
        }
        return new LinkStudentFunding();
    }

    public function fetchFieldMatch($ld)
    {
       //TA:50  print " fetchFieldMatch(id cohort): " . @$ld->id_cohort . "; "; // TA:50
        $row = $this->getRightTable()->fetchRow("(fundingsource=" . @$ld->fundingsource . " AND id=" . @$ld->id . " AND studentid=" . @$ld->studentid . ")");
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
        $this->log = $this->log . "DELETE: studentid=" . $rItem->studentid . ", fundingsource=" . $rItem->fundingsource . "\n";
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
		        if($ld['studentid'] === $rd['studentid'] && $ld['fundingsource'] === $rd['fundingsource']){
		            $found = true;
		            break;
		        }
		        
		    }
		    if(!$found){
		        // delete only if student exists in left table, otherwise student data might be entered by another user
		        $set_student = SyncSetFactory::create('student', SyncCompare::getDesktopConnectionParams('student',$path), $field);
		        $row_student = $set_student->getLeftTable()->fetchAll("id=" . $rd['studentid']);
		        if($row_student->toArray()){
		            $right_rows_to_delete []= $rd['id'];
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
            $rows = $rTable->fetchAll("studentid=" . $lItem->studentid . " and fundingsource=" . $lItem->fundingsource);
            //print_r($rows->toArray());
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
                $new_student_link = $this->getNextId();
            } else {
            
            $new_student_link = $rItem->save();
            }
            $this->log = $this->log . "INSERT: studentid=" . $lItem->studentid . ", fundingsource=" . $lItem->fundingsource . "\n";
            if(!$new_student_link) {
                $this->log = $this->log . "INSERT ERROR: studentid=" . $lItem->studentid . ", fundingsource=" . $lItem->fundingsource . "\n";
                throw new Exception('Insert fail. '. $left_id .' Could not insert link_student_funding.');
            }
    
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





