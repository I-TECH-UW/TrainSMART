<?php
// TA:50 added new class
require_once ('app/controllers/sync/set/Simple.php');
require_once ('app/models/table/LinkTutorInstitution.php');

class SyncSetLinkTutorInstitution extends SyncSetSimple
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
        parent::__construct($sourceDbParams, $syncfile_id, 'link_tutor_institution');
    }

    protected function getColumns()
    {
        return array(
            'id_tutor',
                'id_institution',
                
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
//         if($ld['id'] === $rd['id'] && !($ld['id_tutor'] === $rd['id_tutor'] && $ld['id_institution'] === $rd['id_institution'])){
//             // print "Conflict id ". $ld['id'] . "=" . $rd['id'] . ";";
//             return true;
//         }
        return false;
    }

    protected function getTable($isLeft = true)
    {
        if ($isLeft) {
            return new LinkTutorInstitution($this->sourceDbParams);
        }
        return new LinkTutorInstitution();
    }

    public function fetchFieldMatch($ld)
    {
        //TA:50 print " fetchFieldMatch(id tutor): " . @$ld->id_tutor . "; "; // TA:50
        $row = $this->getRightTable()->fetchRow("(id_tutor=" . @$ld->id_tutor . " AND id=" . @$ld->id . " AND id_institution=" . @$ld->id_institution . ")");
        if ($row) {
            return $row;
        }
        return null;
    }

    public function fetchHardDeletes($path, $field){
        //return null; //do not allow delete, beacuse it can be deleted data entered by other users
        $right_rows = $this->getRightTable()->fetchAll();
        $left_table = $this->getLeftTable()->getAdapter();
        $right_rows_to_delete = array();
		
		$sql = "SELECT * FROM ".$this->tableName;
		
		$left_rows = $left_table->query($sql)->fetchAll();
		foreach($right_rows as $rd) {
		    $found = false;
		    foreach($left_rows as $ld) {
		        if($ld['id_tutor'] === $rd['id_tutor'] && $ld['id_institution'] === $rd['id_institution']){
		            $found = true;
		            break;
		        }
		        
		    }
		    if(!$found){ 
		        // delete only if institution exists in left table, otherwise institution data might be entered by another user
		        $set_institution = SyncSetFactory::create('institution', SyncCompare::getDesktopConnectionParams('institution',$path), $field);
		        $row = $set_institution->getLeftTable()->fetchAll("id=" . $rd['id_institution']);
		        if($row->toArray()){
		            // delete only if tutor exists in left table, otherwise tutor data might be entered by another user
		            $set_tutor = SyncSetFactory::create('tutor', SyncCompare::getDesktopConnectionParams('tutor',$path), $field);
		            $row_tutor = $set_tutor->getLeftTable()->fetchAll("id=" . $rd['id_tutor']);
		            if($row_tutor->toArray()){
		              $right_rows_to_delete []= $rd['id'];
		            }
		        }
		    }
        }
        return $right_rows_to_delete;
    }
    
    public function deleteMember($right_id, $commit=false) {
        $rtable = $this->getRightTable();
        if($commit){
            $rtable->delete($rtable->PK().' = '.$right_id);
        }
        $rItem = $rtable->fetchRow("id=". $right_id);
        $this->log = $this->log . "DELETE: id_tutor=" . $rItem->id_tutor . ", id_institution=" . $rItem->id_institution . "\n";
    }
    
    public function insertMember($left_id, $path=null, $field=null, $commit = false) {
        //check for insert of deleted item
        try {
            $lItem = $this->fetchLeftItemById($left_id, $this->tableName);
            $lTable = $this->getLeftTable();
            $rTable = $this->getRightTable();
    
            //do not insert if inserted already
            $rows = $rTable->fetchAll("id_tutor=" . $lItem->id_tutor . " and id_institution=" . $lItem->id_institution);
            //print_r($rows->toArray());
            if($rows->toArray())
                return;
            
            //delete previous link
            $rTable->delete("id_tutor=" . $lItem->id_tutor);
    
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
                $new_tutor_link = $this->getNextId();
            } else {
            $new_tutor_link = $rItem->save();
            }
            $this->log = $this->log . "INSERT: id_tutor=" . $lItem->id_tutor . ", id_institution=" . $lItem->id_institution . "\n";
            if(!$new_tutor_link) {
                $this->log = $this->log . "INSERT ERROR: id_tutor=" . $lItem->id_tutor . ", id_institution=" . $lItem->id_institution . "\n";
                throw new Exception('Insert fail. '. $left_id .' Could not insert link_tutor_institution.');
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





