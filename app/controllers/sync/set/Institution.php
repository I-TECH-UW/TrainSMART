<?php
// TA:50 added
require_once ('app/controllers/sync/set/Simple.php');
require_once ('app/models/table/Institution.php');

class SyncSetInstitution extends SyncSetSimple
{

    function __construct($sourceDbParams, $syncfile_id)
    {
        parent::__construct($sourceDbParams, $syncfile_id, 'institution');
    }

    protected function getColumns()
    {
        return array(
            'institutionname',
            'address1',
            'address2',
            'city',
            'postalcode',
            'phone',
            'fax',
            'type',
            'sponsor',
            'geography1',
            'geography2',
            'computercount',
            'dormcount',
            'bedcount',
            'hasdormitories',
            'tutorhousing',
            'tutorhouses',
            'yearfounded',
            'comments',
            'customfield1',
            'customfield2',
            'degrees'
        );
    }

    protected function getTable($isLeft = true)
    {
        if ($isLeft) {
            return new Institution($this->sourceDbParams);
        }
        return new Institution();
    }

    public function fetchFieldMatch($ld)
    {
        // TA:50 print " fetchFieldMatch: " . @$ld->institutionname . "; ";
        $row = $this->getRightTable()->fetchRow("(institutionname='" . str_replace("'", "\'", @$ld->institutionname) . "')");
        if ($row) {
            return $row;
        }
        
        return null;
    }
    
    // first:
    public function isDirty($ld, $rd)
    {
        foreach ($this->getColumns() as $col) {
            if ($ld[$col] != $rd[$col])
                return true;
        }
        return false;
    }

    public function isConflict($ld, $rd){
        $rows = $this->getRightTable()->fetchAll("(institutionname='" . str_replace("'", "\'", @$ld->institutionname) . "')");
        if ($rows->toArray()) {
            if (count($rows->toArray()) > 1) {
                $message = count($rows->toArray()) . " records are found for institutionname=" . @$ld->institutionname;
                //$this->log = $this->log . "CONFLICT: " . $message . "\n"; //TA:#315
                $this->error = $this->error . "CONFLICT: " . $message . "\n";
                return $message;
            }
        }
        return null;
    }

    public function fetchHardDeletes($path, $field)
    {
        return null;
    }

    public function updateMember($left_id, $right_id, $commit = false)
    {
        $this->log = $this->log . "UPDATE: id:" . $right_id . ", ";
        $lItem = $this->fetchLeftItemById($left_id, $this->tableName);
        $rItem = $this->fetchRightItemById($right_id, $this->tableName);
        $lTable = $this->getLeftTable();
        $rTable = $this->getRightTable();
        
        $this->log = $this->log . "institutionname:" . $rItem->institutionname . ", ";
        
        foreach ($this->getColumns() as $col) {
            if (is_array($col)) {
                list ($fk, $type) = $col;
                $rItem->$fk = $this->_map_fk($lItem, $fk, $type);
            } else {
                // update value
                if ($rItem->$col !== $lItem->$col) {
                    $this->log = $this->log . $col . ":" . $rItem->$col . "=>" . $lItem->$col . ", ";
                }
                $rItem->$col = $lItem->$col;
            }
        }
        $this->log = $this->log . "\n";
        
        // undelete right side if necessary
        if ($lTable->has_is_deleted_col() && $rTable->has_is_deleted_col()) {
            if ($lItem->is_deleted == 0)
                $rItem->is_deleted = 0;
        }
        
        if($commit){
        // update existing item
        $result = $rItem->save();
        if (! $result) {
            $this->log = $this->log . "UPDATE ERROR: id=" . $lItem->id . ", institutionname=" . $lItem->institutionname . "\n";
            // throw new Exception("Update failed for table'" . $this->tableName . "':" . $left_id .'=>'. $right_id .' Could not update item.');
        }
        return $result;
        } 
        return null;
    }

    public function insertMember($left_id, $path, $field, $commit = false){
        // check for insert of deleted item
        try {
            $lItem = $this->fetchLeftItemById($left_id, $this->tableName);
            $lTable = $this->getLeftTable();
            $rTable = $this->getRightTable();
            $rItem = $rTable->createRow();
            
            foreach ($this->getColumns() as $col) {
                if (is_array($col)) {
                    list ($fk, $type) = $col;
            
                    $rItem->$fk = $this->_map_fk($lItem, $fk, $type);
                } else {
                    // update value
                    $rItem->$col = $lItem->$col;
                }
            }
            
            if (! $commit) {
                 $new_id = $this->getNextId();
            } else {
                $new_id = $rItem->save();
            }
            $this->log = $this->log . "INSERT: id=" . $new_id . ", institutionname=" . $lItem->institutionname . "\n";
            if (! $new_id) {
                $this->log = $this->log . "INSERT ERROR: institutionname=" . $lItem->institutionname . "\n";
                throw new Exception('Insert fail. ' . $left_id . ' Could not insert member:institution.');
            }
            
            // upadte institution id in institution left table also (it needs for deleting data from links tables)
            $lTable->getAdapter()->query("update institution set id=" . $new_id . " where id=" . $lItem->id);
            
            // upadte institution id in link_cadre_institution
            $set_link_cadre = SyncSetFactory::create('link_cadre_institution', SyncCompare::getDesktopConnectionParams('link_cadre_institution', $path), $field);
            // update in sqlite with new institution_id
            $left_table_cadre = $set_link_cadre->getLeftTable()->getAdapter();
            $left_table_cadre->query("update link_cadre_institution set id_institution=" . $new_id . " where id_institution=" . $lItem->id);
            
            // upadte institution id in link_institution_degrees
            $set_link_degress = SyncSetFactory::create('link_institution_degrees', SyncCompare::getDesktopConnectionParams('link_institution_degrees', $path), $field);
            // DEPRICATED: $set_link_degrees->insertAllMembers($lItem->id, $new_id);
            // update in sqlite with new institution_id
            $left_table_degrees = $set_link_degress->getLeftTable()->getAdapter();
            $left_table_degrees->query("update link_institution_degrees set id_institution=" . $new_id . " where id_institution=" . $lItem->id);
            
            // upadte institution id in add link_tutor_institution
            $set_link_tutor = SyncSetFactory::create('link_tutor_institution', SyncCompare::getDesktopConnectionParams('link_tutor_institution', $path), $field);
            // update in sqlite with new tutor_id
            $left_table_link_tutor = $set_link_tutor->getLeftTable()->getAdapter();
            $left_table_link_tutor->query("update link_tutor_institution set id_institution=" . $new_id . " where id_institution=" . $lItem->id);
        } catch (Exception $e) {
            if (strstr($e->getMessage(), 'Integrity constraint violation: 1062 Duplicate entry') === false)
                throw $e;
        }
    }

    public function isReferenced($rd)
    {
        return Institution::isReferenced($rd->id);
    }

    protected function _map_fk($lItem, $fk, $type)
    {
        return parent::_map_fk($lItem, $fk, $type);
    }
}





