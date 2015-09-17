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
        $row = $this->getRightTable()->fetchRow("(id_tutor=" . @$ld->id_tutor . " AND id_address=" . @$ld->id_address . ")");
        if ($row) {
            return $row;
        }
        return null;
    }

    public function fetchHardDeletes(){
        $right_rows = $this->getRightTable()->fetchAll();
        $left_table = $this->getLeftTable()->getAdapter();
        $right_rows_to_delete = array();
		
		$sql = "SELECT * FROM ".$this->tableName;
		
		$left_rows = $left_table->query($sql)->fetchAll();
		foreach($right_rows as $rd) {
		    $found = false;
		    foreach($left_rows as $ld) {
		        if($ld['id_tutor'] === $rd['id_tutor'] && $ld['id_address'] === $rd['id_address']){
		            $found = true;
		            break;
		        }
		        
		    }
		    if(!$found)
		        $right_rows_to_delete []= $rd;	
        }
        return $right_rows_to_delete;
    }

    protected function _map_fk($lItem, $fk, $type)
    {
        return parent::_map_fk($lItem, $fk, $type);
    }
}





