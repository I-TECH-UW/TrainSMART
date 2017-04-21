<?php
require_once('ITechTable.php');


class Assessment extends ITechTable
{
    protected $_name = 'assessment';
    protected $_primary = 'id';
    
    public function createTableRow( array $data = array() ) {
        $row = parent::createRow($data);
        if ( !isset($data['active']) ) {
            $row->active = 'active';
        }
    
        return $row;
    }
    
    public function find($id = false) {
    }
    
    
    public function add($id = false) {
        
    }
    
}