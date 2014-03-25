<?php require_once('ITechTable.php');

class Peopleview extends ITechTable
{
	protected $_primary = 'id';
	protected $_name = 'student';
	protected $_person = 'person';
	protected $_title = 'person_title_option';
	protected $_city = 'location_city';
	protected $_location = 'location';
	protected $_cadres = 'cadres';	
	
     
       

     public function ViewPeople($getid){
	
	$select = $this->dbfunc()->select()
	->from('person')
	->where('id =?',$getid);
	$result = $this->dbfunc()->fetchAll($select);
	
           		return $result;
	 }
	
	 
	
 }
  
?>