<?php
require_once('ITechTable.php');


class PersonToAssessments extends ITechTable
{
    protected $_name = 'person_to_assessments';
    protected $_primary = 'id';
    
    public function createTableRow( array $data = array() ) {
        
        file_put_contents('/vagrant/vagrant/logs/php_debug.log', 'PersonToAssessment->createTableRow >'.PHP_EOL, FILE_APPEND | LOCK_EX);	ob_start();
        $toss = ob_get_clean(); file_put_contents('/vagrant/vagrant/logs/php_debug.log', $toss .PHP_EOL, FILE_APPEND | LOCK_EX);
        
        $row = parent::createRow($data);
        if ( !isset($data['active']) ) {
            $row->active = 'active';
        }
    
        return $row;
    }
    
    public function find($id = false) {
        file_put_contents('/vagrant/vagrant/logs/php_debug.log', 'assessment->find >'.PHP_EOL, FILE_APPEND | LOCK_EX);	ob_start();
        $toss = ob_get_clean(); file_put_contents('/vagrant/vagrant/logs/php_debug.log', $toss .PHP_EOL, FILE_APPEND | LOCK_EX);
    }
    
    public function add($id = false) {
        
        file_put_contents('/vagrant/vagrant/logs/php_debug.log', 'assessment->add >'.PHP_EOL, FILE_APPEND | LOCK_EX);	ob_start();
        $toss = ob_get_clean(); file_put_contents('/vagrant/vagrant/logs/php_debug.log', $toss .PHP_EOL, FILE_APPEND | LOCK_EX);
        
    }
    
}