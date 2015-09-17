<?php
// TA:50 added new class
require_once ('app/controllers/sync/set/Simple.php');
require_once ('app/models/table/LinkStudentClasses.php');

class SyncSetLinkStudentClasses extends SyncSetSimple
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
        parent::__construct($sourceDbParams, $syncfile_id, 'link_student_classes');
    }

    protected function getColumns()
    {
        return array(
            'studentid',
            'cohortid',
                'classid',
                         'grade',
            'credits',
            'exammark',
            'camark',
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
            return new LinkStudentClasses($this->sourceDbParams);
        }
        return new LinkStudentClasses();
    }

    public function fetchFieldMatch($ld)
    {
        //TA:50 print " fetchFieldMatch(id class): " . @$ld->classid . "; "; // TA:50
        $row = $this->getRightTable()->fetchRow("(classid=" . @$ld->classid . " AND studentid=" . @$ld->studentid . ")");
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
		        if($ld['classid'] === $rd['classid'] && $ld['studentid'] === $rd['studentid']){
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





