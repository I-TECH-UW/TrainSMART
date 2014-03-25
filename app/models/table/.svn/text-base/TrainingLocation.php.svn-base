<?php
/*
 * Created on Feb 14, 2008
 * 
 *  Built for web  
 *  Fuse IQ -- todd@fuseiq.com
 *  
 */

require_once('ITechTable.php');

class TrainingLocation extends ITechTable
{
    protected $_name = 'training_location';
	protected $_primary = 'id';

public static function isReferenced($id) {
		require_once('Training.php');

		$training = new Training();
		$select = $training->select();
       $select->where("training_location_id = ?",$id);
      	$select->where("is_deleted = 0");
     	if ( $training->fetchRow($select) )
     		return true;
     		
     	return false;
	}
	
	/**
	 * Selects training locations along with facilities
	 *
	 * @param unknown_type $num_tiers
	 * @return unknown
	 */
	public static function selectAllLocations($num_tiers = 4) {
    $db = Zend_Db_Table_Abstract::getDefaultAdapter ();
		
    list($field_name,$location_sub_query) = Location::subquery($num_tiers, false, false);
           
      $sql = 'SELECT training_location.id, training_location.training_location_name, '.implode(',',$field_name).'
              FROM training_location LEFT JOIN ('.$location_sub_query.') as l ON training_location.location_id = l.id 
              WHERE training_location.is_deleted = 0 ORDER BY province_name'.($num_tiers > 2?', district_name':'').', training_location_name';
    
    return $db->fetchAll($sql);  
  }
  
  public static function selectLocation($id) {
    $tableObj = new TrainingLocation();
    
    $select = $tableObj->select()
        ->from(array('tl' => $tableObj->_name), array('id', 'training_location_name', 'location_id'))
        ->setIntegrityCheck(false)
         ->where("tl.id = $id");   
    
    return $tableObj->fetchRow($select);
  }
  
	/**
   * Insert new training location and return id if not found.  Return id if found.
   */  	
  public static function insertIfNotFound($name, $location_id) {
    $tableObj = new TrainingLocation();
    
    $whereSql = "training_location_name = ? AND location_id = $location_id";
    
    $select = $tableObj->select()
                ->from(array('tl' => $tableObj->_name), 'id')
                ->where($whereSql, $name);
                
    $row = $tableObj->fetchRow($select);
    
    if($row) {
      
      return $row->id;
      
    } else { // insert                           

      $data['training_location_name'] = $name;
      $data['location_id'] = $location_id;      
      
      return $tableObj->insert($data);
    
    }        

    
  }
  
  /*
	public static function suggestionList($match = false, $limit = 100) {
     	$rows = self::suggestionQuery($match,$limit);
    	$rowArray = $rows->toArray();
    		//	unset 'unknown'
    	foreach($rowArray as $key => $row) {
    		if ( $row['training_location_name'] == 'unknown' )
    			unset($rowArray[$key]);
    	}
    	
    	return $rowArray;
	}
	
	protected static function suggestionQuery($match = false, $limit = 100) {
   		require_once('models/table/OptionList.php');
   		$facilityTable = new OptionList(array('name' => 'training_location'));
    		
    		$select = $facilityTable->select()->from('training_location',array('id','training_location_name','d.district_name','p.province_name', 'location_district_id','location_province_id'))->setIntegrityCheck(false);
  		$select->joinLeft(array('d' => 'location_district'),
       						'training_location.location_district_id = d.id', 'd.district_name');
  		$select->joinLeft(array('p' => 'location_province'),
       						'training_location.location_province_id = p.id', 'p.province_name');
    	
    	//look for char start
    	if ( $match ) {
    		$select->where('training_location_name LIKE ? ', $match.'%');
     	}
     	$select->where('training_location.is_deleted = 0');
      	$select->order('training_location_name')->order('province_name')->order('district_name');
     	
     	if ( $limit )
    		$select->limit($limit,0);
    	
     	$rows = $facilityTable->fetchAll($select);
    	
    	return $rows;
	}
*/
}
 