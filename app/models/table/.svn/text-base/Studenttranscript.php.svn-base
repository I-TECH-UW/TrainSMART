<?php require_once('ITechTable.php');

class Studenttranscript extends ITechTable
{
	protected $_primary = 'id';
	protected $_name = 'student';
	//protected $_address = 'addresses';
	//protected $_tutor = 'tutor';

  public function EditStudenttranscript($pupilid) {

	 $db = Zend_Db_Table_Abstract::getDefaultAdapter(); 
 	 $select=$db->query("select * from student s, lookup_gender lg, addresses ad where s.id = '$pupilid' and s.id = lg.id and s.id= ad.id");
	 $row = $select->fetch();
	 return $row;	
 	 }
	
 }
  
?>