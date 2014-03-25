<?php
/*
 * Created on Feb 14, 2008
 * 
 *  Built for web  
 *  Fuse IQ -- todd@fuseiq.com
 *  
 */


class NOTLocationDistrict extends ITechTable
{
  protected $_primary = 'id';
  protected $_name = 'location_district';

  /**
   * Validates district is in the correct province.  If false, returns province_name (validateDistrict !== true)
   */ 
  public static function validateDistrict($district_id, $province_id) {
    $tableObj = new LocationDistrict();
    
    $select = $tableObj->select()
        ->from(array('d' => 'location_district'), array())
        ->setIntegrityCheck(false)
        ->join(array('p' => 'location_province'), "d.parent_province_id = p.id", array('id','province_name'))
        ->where("d.id = $district_id");
        
    try {
    
      $row = $tableObj->fetchRow($select);
    
    } catch(Zend_Exception $e) {
      error_log($e);
    }
   
    if($row->id == $province_id) {
      return true;
    } else {
      return $row->province_name;
    }
              
  }
	
}
 