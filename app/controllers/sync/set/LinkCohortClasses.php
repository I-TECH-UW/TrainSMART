<?php
// TA:50 added new class
require_once ('app/controllers/sync/set/Simple.php');
require_once ('app/models/table/LinkCohortClasses.php');

class SyncSetLinkCohortClasses extends SyncSetSimple
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
        parent::__construct($sourceDbParams, $syncfile_id, 'link_cohorts_classes');
    }

    protected function getColumns()
    {
        return array(
            'classid',
            'cohortid',
            'status'
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
//         if($ld['id'] === $rd['id'] && !($ld['cohortid'] === $rd['cohortid'] && $ld['classid'] === $rd['classid'])){
//             // print "Conflict id ". $ld['id'] . "=" . $rd['id'] . ";";
//             return true;
//         }
        return false;
    }

    protected function getTable($isLeft = true)
    {
        if ($isLeft) {
            return new LinkCohortClasses($this->sourceDbParams);
        }
        return new LinkCohortClasses();
    }

    public function fetchFieldMatch($ld)
    {
        //TA:50 print " fetchFieldMatch(id cohort): " . @$ld->cohortid . "; "; // TA:50
//         $row = $this->getRightTable()->fetchRow("(cohortid=" . @$ld->cohortid . " AND classid=" . @$ld->classid . " AND id=" . @$ld->id . ")");
        $row = $this->getRightTable()->fetchRow("(cohortid=" . @$ld->cohortid . " AND classid=" . @$ld->classid .  ")");
        if ($row) {
            return $row;
        }
        return null;
    }

    public function fetchHardDeletes($path, $field){
      //  return null; //do not allow delete, beacuse it can be deleted data entered by other users
        $right_rows = $this->getRightTable()->fetchAll();
        $left_table = $this->getLeftTable()->getAdapter();
        $right_rows_to_delete = array();
		
		$sql = "SELECT * FROM ".$this->tableName;
		
		$left_rows = $left_table->query($sql)->fetchAll();
		foreach($right_rows as $rd) {
		    $found = false;
		    foreach($left_rows as $ld) {
		       if($ld['cohortid'] === $rd['cohortid'] && $ld['classid'] === $rd['classid']){
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
        if($commit){
            $rtable->delete($rtable->PK().' = '.$right_id);
        }
        $rItem = $rtable->fetchRow("id=". $right_id);
        $this->log = $this->log . "DELETE: cohortid=" . $rItem->cohortid . ", classid=" . $rItem->classid . "\n";
    }
    
    public function insertMember($left_id, $path=null, $field=null, $commit = false) {
        //check for insert of deleted item
        try {
            $lItem = $this->fetchLeftItemById($left_id, $this->tableName);
            $lTable = $this->getLeftTable();
            $rTable = $this->getRightTable();
    
            //do not insert if inserted already
            $rows = $rTable->fetchAll("cohortid=" . $lItem->cohortid . " and classid=" . $lItem->classid);
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
            $this->log = $this->log . "INSERT: cohortid=" . $lItem->cohortid .  ", classid=" . $rItem->classid . "\n";
            if(!$new_link) {
                $this->log = $this->log . "INSERT ERROR: cohortid=" . $lItem->cohortid .  ", classid=" . $rItem->classid . "\n";
                throw new Exception('Insert fail. '. $left_id .' Could not insert member:link_cohort_classes.');
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





