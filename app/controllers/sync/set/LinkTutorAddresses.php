<?php
// TA:50 added new class
require_once ('app/controllers/sync/set/Simple.php');
require_once ('app/models/table/LinkTutorAddresses.php');

class SyncSetLinkTutorAddresses extends SyncSetSimple
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
        parent::__construct($sourceDbParams, $syncfile_id, 'link_tutor_addresses');
    }

    protected function getColumns()
    {
        return array(
            'id_tutor',
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
//         if($ld['id'] === $rd['id'] && !($ld['id_tutor'] === $rd['id_tutor'] && $ld['id_address'] === $rd['id_address'])){
//             // print "Conflict id ". $ld['id'] . "=" . $rd['id'] . ";";
//             return true;
//         }
        return false;
    }

    protected function getTable($isLeft = true)
    {
        if ($isLeft) {
            return new LinkTutorAddresses($this->sourceDbParams);
        }
        return new LinkTutorAddresses();
    }

    public function fetchFieldMatch($ld)
    {
        //TA:50 print " fetchFieldMatch(id tutor): " . @$ld->id_tutor . "; "; // TA:50
//         $row = $this->getRightTable()->fetchRow("(id_tutor=" . @$ld->id_tutor . " AND id=" . @$ld->id . " AND id_address=" . @$ld->id_address . ")");
        $row = $this->getRightTable()->fetchRow("(id_tutor=" . @$ld->id_tutor . " AND id_address=" . @$ld->id_address . ")");
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
        $this->log = $this->log . "DELETE: id_tutor=" . $rItem->id_tutor . ", id_address=" . $rItem->id_address .  "\n";
        }

    public function fetchHardDeletes($path, $field){
        $right_rows = $this->getRightTable()->fetchAll();
        $left_table = $this->getLeftTable()->getAdapter();
        $right_rows_to_delete = array();
		
		$sql = "SELECT * FROM ".$this->tableName;
		
		$left_rows = $left_table->query($sql)->fetchAll();
		foreach($right_rows as $rd) {
		    $found = false;
		    foreach($left_rows as $ld) {
		        if($ld['id_address'] === $rd['id_address'] && $ld['id_tutor'] === $rd['id_tutor']){
		            $found = true;
		            break;
		        }
		        
		    }
		    if(!$found){
                // delete only if addresses exists in left table, otherwise link data might be entered by another user
                $set_add = SyncSetFactory::create('addresses', SyncCompare::getDesktopConnectionParams('addresses', $path), $field);
                $row_add = $set_add->getLeftTable()->fetchAll("id=" . $rd['id_address']);
                if ($row_add->toArray()) {
                    // delete only if tutor exists in left table, otherwise tutor data might be entered by another user
                    $set_tutor = SyncSetFactory::create('tutor', SyncCompare::getDesktopConnectionParams('tutor', $path), $field);
                    $row_tutor = $set_tutor->getLeftTable()->fetchAll("id=" . $rd['id_tutor']);
                    if ($row_tutor->toArray()) {
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
            $rows = $rTable->fetchAll("id_address=" . $lItem->id_address . " and id_tutor=" . $lItem->id_tutor);
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
                $new_id = $this->getNextId();
            } else {
            $new_id = $rItem->save();
            }
            $this->log = $this->log . "INSERT: id_tutor=" . $lItem->id_tutor . ", id_address=" . $lItem->id_address . "\n";
            if(!$new_id) {
                $this->log = $this->log . "INSERT ERROR: id_tutor=" . $lItem->id_tutor . ", id_address=" . $lItem->id_address . "\n";
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





