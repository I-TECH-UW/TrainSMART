<?php
/*
 * Created on Feb 14, 2008
 * 
 *  Built for web  
 *  Fuse IQ -- todd@fuseiq.com
 *  
 */


class NOTLocationCity extends ITechTable
{
	protected $_primary = 'id';
    protected $_name = 'location_city';

	/**
	 * Returns false if the city is known to be in another district or province
	 * Returns false if the district is not in the province
	 * Returns an id if the city is unknown or is in the correct parent location
	 */
	public static function verifyCityHierarchy($cityName, $province_id, $district_id = false) {
		$rowset = self::suggestionQuery($cityName);
		$count = 0;
//		foreach ($rowset as $row) {
// no longer validating a city's district and province, since it was preventing duplicate city names.
//
//			if ( ($row->city_name == $cityName) and ($province_id == $row->parent_province_id) and ((!$district_id) or ($district_id == $row->parent_district_id)) ) {
//				return $row->id;
//			}
//			$count++;
//		}
		
		if ( $count == 0 ) {
			//for unknowns check that the district is in the province
	    	if ( $district_id ) {
		    	$district = new OptionList(array('name' => 'location_district'));
		    	$select = $district->select()->from('location_district',array('id'))->setIntegrityCheck(false);
		   		$select->join(array('p' => 'location_province'),
		       						'location_district.parent_province_id = p.id', 'p.province_name');
	    		$select->where('location_district.id = ? ', $district_id);
	    		$select->where('p.id = ? ', $province_id);
	     	 	$select->where('location_district.is_deleted = 0');
    			if ( !$district->fetchRow($select) )
    				return false;
	    	}
			return 'unknown'; //it's a new city
		}
		
		return false;
	}
	
	/**
   * Insert city if not found and has correct district, otherwise return id.  Return -1 for incorrect district
   */ 	
  public static function insertIfNotFound($cityName, $province_id, $district_id = null) {
      if(!$cityName) return false;
      
      if($district_id) { // verify district is in correct province
        require_once 'models/table/LocationDistrict.php';
        if(LocationDistrict::validateDistrict($district_id, $province_id) !== true) return -1;        
      }
      
      $cityTable = new LocationCity();
      $select = $cityTable->select()
                  ->from($cityTable->_name, "id")
                  ->where("city_name = '$cityName' AND parent_province_id = $province_id");
      
      if ( $district_id )
        $select->where("parent_district_id = $district_id");
                  
      $row = $cityTable->fetchRow($select);
      
      if($row) {             
        
        return $row->id;
       
      } else { // insert
        
        $data = array();
        $data['city_name'] = $cityName;
        $data['parent_district_id'] = $district_id;
        $data['parent_province_id'] = $province_id;
        
        return $cityTable->insert($data);
        
      }
            	  
	}
	
	public static function suggestionCityList($match = false, $limit = 100) {
     	$rows = self::suggestionQuery($match,$limit);
    	$rowArray = $rows->toArray();
    		//	unset 'unknown'
    	foreach($rowArray as $key => $row) {
    		if ( $row['city_name'] == 'unknown' )
    			unset($rowArray[$key]);
    	}
    	
    	return $rowArray;
	}

	protected static function suggestionQuery($match = false, $limit = 100) {
   		require_once('models/table/OptionList.php');
   		$topicTable = new OptionList(array('name' => 'location_city'));
    		
    	$select = $topicTable->select()->from('location_city',array('city_name','p.province_name','d.district_name','id', 'parent_district_id','parent_province_id'))->setIntegrityCheck(false);
  		$select->joinLeft(array('d' => 'location_district'),
       						'location_city.parent_district_id = d.id', 'd.district_name');
  		$select->joinLeft(array('p' => 'location_province'),
       						'location_city.parent_province_id = p.id', 'p.province_name');
    	
    	//look for char start
    	if ( $match ) {
    		$select->where('city_name LIKE ? ', $match.'%');
     	}
     	$select->where('location_city.is_deleted = 0');
      	$select->order('city_name ASC');
     	
     	if ( $limit )
    		$select->limit($limit,0);
    	
     	$rows = $topicTable->fetchAll($select);
    	
    	return $rows;
	}
	
}
 