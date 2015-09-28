<?php
// TA:50 added new class
require_once ('app/controllers/sync/set/Simple.php');
require_once ('app/models/table/LinkStudentAddresses.php');

class SyncSetLinkStudentAddresses extends SyncSetSimple
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
        parent::__construct($sourceDbParams, $syncfile_id, 'link_student_addresses');
    }

    protected function getColumns()
    {
        return array(
            'id_student',
               'id_address',
                
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
//         if($ld['id'] === $rd['id'] && !($ld['id_student'] === $rd['id_student'] && $ld['id_address'] === $rd['id_address'])){
//             // print "Conflict id ". $ld['id'] . "=" . $rd['id'] . ";";
//             return true;
//         }
        return false;
    }

    protected function getTable($isLeft = true)
    {
        if ($isLeft) {
            return new LinkStudentAddresses($this->sourceDbParams);
        }
        return new LinkStudentAddresses();
    }

    public function fetchFieldMatch($ld)
    {
        //TA:50 print " fetchFieldMatch(id address): " . @$ld->id_address . "; "; // TA:50
//         $row = $this->getRightTable()->fetchRow("(id_address=" . @$ld->id_address . " AND id=" . @$ld->id . " AND id_student=" . @$ld->id_student . ")");
        $row = $this->getRightTable()->fetchRow("(id_address=" . @$ld->id_address . " AND id_student=" . @$ld->id_student . ")");
        if ($row) {
            return $row;
        }
        return null;
    }
    
    public function deleteMember($right_id, $commit=false) {
        $rtable = $this->getRightTable();
        if($commit){
            $rtable->delete($rtable->PK().' = '.$right_id);
        }
        $rItem = $rtable->fetchRow("id=". $right_id);
        $this->log = $this->log . "DELETE: id_student=" . $rItem->id_student . ", id_address=" . $rItem->id_address .  "\n";
    }

    public function fetchHardDeletes($path, $field){
   //     return null;
        $right_rows = $this->getRightTable()->fetchAll();
        $left_table = $this->getLeftTable()->getAdapter();
        $right_rows_to_delete = array();
		
		$sql = "SELECT * FROM ".$this->tableName;
		
		$left_rows = $left_table->query($sql)->fetchAll();
		foreach($right_rows as $rd) {
		    $found = false;
		    foreach($left_rows as $ld) {
		        if($ld['id_address'] === $rd['id_address'] && $ld['id_student'] === $rd['id_student']){
		            $found = true;
		            break;
		        }
		        
		    }
		    if(!$found){
                // delete only if addresses exists in left table, otherwise link data might be entered by another user
                $set_add = SyncSetFactory::create('addresses', SyncCompare::getDesktopConnectionParams('addresses', $path), $field);
                $row_add = $set_add->getLeftTable()->fetchAll("id=" . $rd['id_address']);
                if ($row_add->toArray()) {
                    // delete only if student exists in left table, otherwise student data might be entered by another user
                    $set_student = SyncSetFactory::create('student', SyncCompare::getDesktopConnectionParams('student', $path), $field);
                    $row_student = $set_student->getLeftTable()->fetchAll("id=" . $rd['id_student']);
                    if ($row_student->toArray()) {
                        $right_rows_to_delete[] = $rd['id'];
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
            $rows = $rTable->fetchAll("id_address=" . $lItem->id_address . " and id_student=" . $lItem->id_student);
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
            $this->log = $this->log . "INSERT: id_student=" . $lItem->id_student . ", id_address=" . $lItem->id_address . "\n";
            if(!$new_student_link) {
                $this->log = $this->log . "INSERT ERROR: id_student=" . $lItem->id_student . ", id_address=" . $lItem->id_address . "\n";
                throw new Exception('Insert fail. '. $left_id .' Could not insert link_tutor_addresses.');
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





