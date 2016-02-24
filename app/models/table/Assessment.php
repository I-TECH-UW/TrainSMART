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
        file_put_contents('/vagrant/vagrant/logs/php_debug.log', 'assessment->find >'.PHP_EOL, FILE_APPEND | LOCK_EX);	ob_start();
        //var_dump("pa.id=", $person_to_assessment_id, "END");
        $toss = ob_get_clean(); file_put_contents('/vagrant/vagrant/logs/php_debug.log', $toss .PHP_EOL, FILE_APPEND | LOCK_EX);
    }
    
    
    public function add($id = false) {
        
        file_put_contents('/vagrant/vagrant/logs/php_debug.log', 'assessment->add >'.PHP_EOL, FILE_APPEND | LOCK_EX);	ob_start();
        var_dump("first_name=", $first_name, "END");
        $toss = ob_get_clean(); file_put_contents('/vagrant/vagrant/logs/php_debug.log', $toss .PHP_EOL, FILE_APPEND | LOCK_EX);
        
    }
    
}