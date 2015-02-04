<?php
require_once('Dashboard.php');
require_once('Helper.php');

class DashboardCHAI extends Dashboard
{
	protected $_primary = 'id';

	public function fetchConsumptionDetails($dataName = null, $id, $where = null, $group = null, $useName = null) {
	    $db = Zend_Db_Table_Abstract::getDefaultAdapter();
	    $output = array();
	    
	    $subSelect = new Zend_Db_Expr("(select facility_id, month(max(date)) as C_monthDate from commodity group by facility_id)");

		switch ($dataName) {
		    case 'geo':
		        $select = $db->select()
		        ->from('location');
		        if ($where) // comma seperated string for sql
		            $select = $select->where($where)
		            ->order('location_name');
		        $result = $db->fetchAll($select);
		        
		        foreach ($result as $row){
		        
		          $output[] = array(
		            "id" => $row['id'],
		            "name" => $row['location_name'],
		            "tier" => $row['tier'],
		            "parent_id" => $row['parent_id'],
		            "consumption" => 0,
		            "link" => Settings::$COUNTRY_BASE_URL . "/dashboard/dash3/id/" . $row['id'],
		            "type" => 1
		           );
		        }
		    break;
		    case 'location':
		        $select = $db->select()
		        ->from(array('f' => 'facility'), 
		          array(
		          'f.id as F_id', 
		          'f.facility_name as F_facility_name', 
		          'f.location_id as F_location_id', 
		          'l1.id as L1_id',
		          'l1.location_name as L1_location_name', 
		          'l2.id as L2_id', 
		          'l2.location_name as L2_location_name', 
		          'l2.parent_id as L2_parent_id', 
		          'l3.location_name as L3_location_name', 
		          'cno.commodity_name as CNO_commodity_name',
		          'ifnull(sum(c.consumption),0) as C_consumption' ))
		        ->joinLeft(array('l1' => "location"), 'f.location_id = l1.id')
		        ->joinLeft(array('l2' => "location"), 'l1.parent_id = l2.id')
		        ->joinLeft(array('l3' => "location"), 'l2.parent_id = l3.id')
		        ->joinLeft(array("c" => "commodity"), 'f.id = c.facility_id')
		        ->joinLeft(array("cno" => "commodity_name_option"), 'c.name_id = cno.id')
		        ->joinInner(array('mc' => $subSelect), 'f.id = mc.facility_id and month(c.date) = C_monthDate')
		        ->where($where)
		        ->group(array($group))
                ->order(array('L3_location_name', 'L2_location_name', 'L1_location_name', 'F_facility_name'));
		        
		        $result = $db->fetchAll($select);
		        
		      foreach ($result as $row){
		          
		        $_child_name = ($id ==  "") ? $row['L2_location_name'] : $row['L1_location_name'] ;
		        
		 	    $output[] = array(
		 	        "id" => $row[$group],
		 	        "name" => $row[$useName],
		 	        "tier" => $row['tier'],
		 	        "parent_id" => $row['L2_parent_id'],
		 	        "child_name" => $_child_name,
		 	        "commodity_name" => $row[CNO_commodity_name],
		 	        "consumption" => $row['C_consumption'],
		 	        "link" => Settings::$COUNTRY_BASE_URL . "/dashboard/dash3/id/" . $row['l2.parent_id'],
		 	        "type" => 1
		 	     );
		 	    
		      }
		    break;
		    case 'facility':
		          $select = $db->select()
		          ->from(array('f' => 'facility'), 
		          array(
		          'f.id as F_id', 
		          'f.facility_name as F_facility_name', 
		          'f.location_id as F_location_id', 
		          'l1.id as L1_id',
		          'l1.location_name as L1_location_name', 
		          'l2.id as L2_id', 
		          'l2.location_name as L2_location_name', 
		          'l2.parent_id as L2_parent_id', 
		          'l3.location_name as L3_location_name', 
		          'cno.commodity_name as CNO_commodity_name',
		          'ifnull(c.consumption,0) as C_consumption' ))
		        ->joinLeft(array('l1' => "location"), 'f.location_id = l1.id')
		        ->joinLeft(array('l2' => "location"), 'l1.parent_id = l2.id')
		        ->joinLeft(array('l3' => "location"), 'l2.parent_id = l3.id')
		        ->joinLeft(array('c' => "commodity"), 'f.id = c.facility_id')
		        ->joinLeft(array("cno" => "commodity_name_option"), 'c.name_id = cno.id')
		        ->joinInner(array('mc' => $subSelect), 'f.id = mc.facility_id and month(c.date) = C_monthDate')
		        ->where($where)
                ->order(array('L3_location_name', 'L2_location_name', 'L1_location_name', 'F_facility_name'));
		        
		        $result = $db->fetchAll($select);
		        
		      foreach ($result as $row){
		 	    $output[] = array(
		 	        "id" => $row[$group],
		 	        "name" => $row[$useName],
		 	        "tier" => $row['tier'],
		 	        "parent_id" => $row['L3_parent_id'],
		 	        "child_name" => $row['F_facility_name'],
		 	        "commodity_name" => $row[CNO_commodity_name],
		 	        "consumption" => $row['C_consumption'],
		 	        "link" => Settings::$COUNTRY_BASE_URL . "/dashboard/dash3/id/" . $row['l2.parent_id'],
		 	        "type" => 1
		 	     );
		      }
		        break;		    
		}
		return $output;
	}
	
	public function fetchTier( $id ) {
	    $db = Zend_Db_Table_Abstract::getDefaultAdapter();
	    $output = array();
	    
	    $select = $db->select()
	    ->from('location')
        ->where('id = ' . $id);
	    
	    $result = $db->fetchRow($select);
	    
	    return $result['tier'];
	}
	
	public function fetchTitleData() {
	    $db = Zend_Db_Table_Abstract::getDefaultAdapter();
	    $output = array();
	     
	    $select = $db->select()
	    ->from(array('l' => 'lc_view'),
	        array( 
	            'monthName(l.C_date) as month_name',
	            'year(l.C_date) as year'
	        ));
	     
	    $result = $db->fetchRow($select);
	     
	    return $result;
	    
	}
	

	
	public function fetchTitleDate() {
	    $db = Zend_Db_Table_Abstract::getDefaultAdapter();
	
	    $select = $db->select()
	    ->from(array('c' => 'commodity'),
	        array(new Zend_Db_Expr('monthName(max(c.date)) as month_name, year(max(c.date)) as year' )));
	
	    $result = $db->fetchRow($select);

	    return $result;
	}
	
	public function fetchTitleMethod($method) {
	    $db = Zend_Db_Table_Abstract::getDefaultAdapter();
	    $output = array();
	    
	    $method = "'".$method."'";
	    $select = $db->select()
	    ->from(array('cno' => 'commodity_name_option'),
	           array( 'commodity_name' ))
	    ->where("external_id =  $method ");
	
	    $result = $db->fetchRow($select);
	
	    return $result;
	     
	}

	//TA:17:17 Coverage Summary chart
	public function fetchCSDetails($date) {
	
	    $db = Zend_Db_Table_Abstract::getDefaultAdapter();
	    $output = array();
	
	    // get last date where data were uploaded from DHIS2
	    if($date === null || empty($date)){
	        $result = $db->fetchAll("select max(date) as date from facility_report_rate");
	        $date = $result[0]['date'];
	    }
	
	    $output['last_date'] = $date;
	
	    $select = $db->select()
	    -> from(array('facility_report_rate' => 'facility_report_rate'),
	        array('count(*) as count'));
	
	            $result = $db->fetchAll($select);
	            $output['total_facility_count'] = $result[0]['count'];
	
	            $select = $db->select()
	            -> from(array('facility_report_rate' => 'facility_report_rate'),
	            array('count(*) as count'))
	            ->where("date='" . $date . "'");
	
	            $result = $db->fetchAll($select);
	            $output['total_facility_count_month'] = $result[0]['count'];
	
	        $select = $db->select()
	       -> from(array('facility' => 'facility'), array('count(distinct facility.id) as count'))
           ->joinLeft(array('facility_report_rate' => "facility_report_rate"), 'facility.external_id = facility_report_rate.facility_external_id')
	       ->joinLeft(array('person' => "person"), 'facility.id = person.facility_id')
	       ->joinLeft(array('person_to_training' => "person_to_training"), 'person.id = person_to_training.person_id')
           ->joinLeft(array('training' => "training"), 'training.id = person_to_training.training_id')
	       ->where('training.training_title_option_id = 1');
	        
	        $sql = $select->__toString();
	        $sql = str_replace('AS `count`,', 'AS `count`', $sql);
	        $sql = str_replace('`facility_report_rate`.*,', '', $sql);
	        $sql = str_replace('`person`.*,', '', $sql);
	        $sql = str_replace('`person_to_training`.*,', '', $sql);
	        $sql = str_replace('`training`.*', '', $sql);
	
         $result = $db->fetchAll($sql);
	     $output['larc_facility_count'] = $result[0]['count'];
	
	        $select = $db->select()
           -> from(array('facility' => 'facility'), array('count(distinct facility.id) as count'))
           ->joinLeft(array('facility_report_rate' => "facility_report_rate"), 'facility.external_id = facility_report_rate.facility_external_id')
           ->joinLeft(array('person' => "person"), 'facility.id = person.facility_id')
           ->joinLeft(array('person_to_training' => "person_to_training"), 'person.id = person_to_training.person_id')
           ->joinLeft(array('training' => "training"), 'training.id = person_to_training.training_id')
	               ->where('training.training_title_option_id = 2');
	        
	        $sql = $select->__toString();
	        $sql = str_replace('AS `count`,', 'AS `count`', $sql);
	        $sql = str_replace('`facility_report_rate`.*,', '', $sql);
	        $sql = str_replace('`person`.*,', '', $sql);
	        $sql = str_replace('`person_to_training`.*,', '', $sql);
	        $sql = str_replace('`training`.*', '', $sql);
	
         $result = $db->fetchAll($sql);
	     $output['fp_facility_count'] = $result[0]['count'];
	
	
	
	     $select = $db->select()
	     -> from(array('facility' => 'facility'), array('count(distinct facility.id) as count'))
	         ->joinLeft(array('facility_report_rate' => "facility_report_rate"), 'facility.external_id = facility_report_rate.facility_external_id')
	         ->joinLeft(array('person' => "person"), 'facility.id = person.facility_id')
	         ->joinLeft(array('person_to_training' => "person_to_training"), 'person.id = person_to_training.person_id')
	         ->joinLeft(array('training' => "training"), 'training.id = person_to_training.training_id')
	         ->joinLeft(array('commodity' => "commodity"), 'facility.id = commodity.facility_id')
	         ->joinLeft(array('commodity_name_option' => "commodity_name_option"), 'commodity.name_id = commodity_name_option.id')
	         ->where('training.training_title_option_id = 1')
             ->where("commodity_name_option.external_id='DiXDJRmPwfh'");
	     
	     $sql = $select->__toString();
	     $sql = str_replace('AS `count`,', 'AS `count`', $sql);
	     $sql = str_replace('`facility_report_rate`.*,', '', $sql);
	     $sql = str_replace('`person`.*,', '', $sql);
	     $sql = str_replace('`person_to_training`.*,', '', $sql);
	     $sql = str_replace('`training`.*,', '', $sql);
	     $sql = str_replace('`commodity`.*,', '', $sql);
	     $sql = str_replace('`commodity_name_option`.*', '', $sql);
	     
     $result = $db->fetchAll($sql);
	 $output['larc_consumption_facility_count'] = $result[0]['count'];
	
     $select = $db->select()
     -> from(array('facility' => 'facility'), array('count(distinct facility.id) as count'))
	         ->joinLeft(array('facility_report_rate' => "facility_report_rate"), 'facility.external_id = facility_report_rate.facility_external_id')
	         ->joinLeft(array('person' => "person"), 'facility.id = person.facility_id')
             ->joinLeft(array('person_to_training' => "person_to_training"), 'person.id = person_to_training.person_id')
	         ->joinLeft(array('training' => "training"), 'training.id = person_to_training.training_id')
	         ->joinLeft(array('commodity' => "commodity"), 'facility.id = commodity.facility_id')
	         ->joinLeft(array('commodity_name_option' => "commodity_name_option"), 'commodity.name_id = commodity_name_option.id')
	         ->where('training.training_title_option_id = 2')
	         ->where("commodity_name_option.external_id='ibHR9NQ0bKL'");
     
     $sql = $select->__toString();
     $sql = str_replace('AS `count`,', 'AS `count`', $sql);
     $sql = str_replace('`facility_report_rate`.*,', '', $sql);
     $sql = str_replace('`person`.*,', '', $sql);
     $sql = str_replace('`person_to_training`.*,', '', $sql);
     $sql = str_replace('`training`.*,', '', $sql);
     $sql = str_replace('`commodity`.*,', '', $sql);
     $sql = str_replace('`commodity_name_option`.*', '', $sql);
      
	 $result = $db->fetchAll($sql);
	 $output['fp_consumption_facility_count'] = $result[0]['count'];
	
	 $select = $db->select()
	 -> from(array('facility' => 'facility'), array('count(distinct facility.id) as count'))
	 ->joinLeft(array('facility_report_rate' => "facility_report_rate"), 'facility.external_id = facility_report_rate.facility_external_id')
	 ->joinLeft(array('person' => "person"), 'facility.id = person.facility_id')
	 ->joinLeft(array('person_to_training' => "person_to_training"), 'person.id = person_to_training.person_id')
	 ->joinLeft(array('training' => "training"), 'training.id = person_to_training.training_id')
	 ->joinLeft(array('commodity' => "commodity"), 'facility.id = commodity.facility_id')
	 ->joinLeft(array('commodity_name_option' => "commodity_name_option"), 'commodity.name_id = commodity_name_option.id')
	 ->where('training.training_title_option_id = 1')
	 ->where("commodity_name_option.external_id='DiXDJRmPwfh'")
     ->where("stock_out='Y'");
	 
	 $sql = $select->__toString();
	 $sql = str_replace('AS `count`,', 'AS `count`', $sql);
	 $sql = str_replace('`facility_report_rate`.*,', '', $sql);
	 $sql = str_replace('`person`.*,', '', $sql);
	 $sql = str_replace('`person_to_training`.*,', '', $sql);
	 $sql = str_replace('`training`.*,', '', $sql);
	 $sql = str_replace('`commodity`.*,', '', $sql);
	 $sql = str_replace('`commodity_name_option`.*', '', $sql);
	
	 $result = $db->fetchAll($sql);
     $output['larc_stock_out_facility_count'] = $result[0]['count'];
	
	 $select = $db->select()
     -> from(array('facility' => 'facility'), array('count(distinct facility.id) as count'))
	 ->joinLeft(array('facility_report_rate' => "facility_report_rate"), 'facility.external_id = facility_report_rate.facility_external_id')
	 ->joinLeft(array('person' => "person"), 'facility.id = person.facility_id')
	 ->joinLeft(array('person_to_training' => "person_to_training"), 'person.id = person_to_training.person_id')
	 ->joinLeft(array('training' => "training"), 'training.id = person_to_training.training_id')
	 ->joinLeft(array('commodity' => "commodity"), 'facility.id = commodity.facility_id')
	 ->joinLeft(array('commodity_name_option' => "commodity_name_option"), 'commodity.name_id = commodity_name_option.id')
	 ->where('training.training_title_option_id = 2')
	 ->where("commodity_name_option.external_id='JyiR2cQ6DZT'");
	 
	 $sql = $select->__toString();
	 $sql = str_replace('AS `count`,', 'AS `count`', $sql);
	 $sql = str_replace('`facility_report_rate`.*,', '', $sql);
	 $sql = str_replace('`person`.*,', '', $sql);
	 $sql = str_replace('`person_to_training`.*,', '', $sql);
	 $sql = str_replace('`training`.*,', '', $sql);
	 $sql = str_replace('`commodity`.*,', '', $sql);
	 $sql = str_replace('`commodity_name_option`.*', '', $sql);
	
	 $result = $db->fetchAll($sql);
	 $output['fp_stock_out_facility_count'] = $result[0]['count'];
	
	return $output;
	
    }
	
	
	public function fetchCLNDetails($dataName = null, $id = null, $where = null, $group = null, $useName = null) {
	    
	    //file_put_contents('c:\wamp\logs\php_debug.log', 'fetchCLNDetails >'.PHP_EOL, FILE_APPEND | LOCK_EX);	ob_start();
	    //var_dump('all= ', $dataName, $where, $group, $useName, 'END');
	    //$toss = ob_get_clean(); file_put_contents('c:\wamp\logs\php_debug.log', $toss .PHP_EOL, FILE_APPEND | LOCK_EX);
	    
	    $db = Zend_Db_Table_Abstract::getDefaultAdapter();
	    $output = array();
	
	    switch ($dataName) {
	        case 'geo':
	            $select = $db->select()
	            ->from('location');
	            if ($where) // comma seperated string for sql
	                $select = $select->where($where)
	                ->order('location_name');
	            $result = $db->fetch($select);
	
	            foreach ($result as $row){
	
	                $output[] = array(
	                    "id" => $row['id'],
	                    "name" => $row['location_name'],
	                    "tier" => $row['tier'],
	                    "parent_id" => $row['parent_id'],
	                    "consumption" => 0,
	                    "link" => Settings::$COUNTRY_BASE_URL . "/dashboard/dash3/id/" . $row['id'],
	                    "type" => 1
	                );
	            }
	            break;
	        case 'location':
	            
                //file_put_contents('c:\wamp\logs\php_debug.log', 'Dashboard-CHAI 171>'.PHP_EOL, FILE_APPEND | LOCK_EX);	ob_start();
	            //var_dump('all=', $dataName, $id, $where, $group, $useName, "END");
	            //$toss = ob_get_clean(); file_put_contents('c:\wamp\logs\php_debug.log', $toss .PHP_EOL, FILE_APPEND | LOCK_EX);
	            
	    	    $create_view = $db->select()
	           ->from(array('f' => 'facility'),
	           array(
	            'f.id as F_id',
	            'f.facility_name as F_facility_name',
	            //"replace(f.facility_name, '\'', '\\\'') as F_facility_name',",
	            'f.location_id as F_location_id',
	            'l1.id as L1_id',
	            'l1.location_name as L1_location_name',
	            'l2.id as L2_id',
	            'l2.location_name as L2_location_name',
	            'l2.parent_id as L2_parent_id',
	            'l3.location_name as L3_location_name',
	            'cno.id as CNO_id',
	            'cno.external_id as CNO_external_id',
	            'cno.commodity_name as CNO_commodity_name',
	            'c.date as C_date',
	         	'ifnull(sum(c.consumption),0) as C_consumption' ))
	         	->joinLeft(array('l1' => "location"), 'f.location_id = l1.id')
	    	    ->joinLeft(array('l2' => "location"), 'l1.parent_id = l2.id')
	    	    ->joinLeft(array('l3' => "location"), 'l2.parent_id = l3.id')
	    	    ->joinLeft(array("c" => "commodity"), 'f.id = c.facility_id')
	    	    ->joinLeft(array("cno" => "commodity_name_option"), 'c.name_id = cno.id')
	    	    ->joinInner(array('mc' => 'lc_view_subselect'), 'f.id = mc.C_facility_id and month(c.date) = month(mc.C_date) and year(c.date) = year(mc.C_date)')
	    	    ->where($where)
	    	    ->group(array($group))
	    	    ->order(array('L3_location_name', 'L2_location_name', 'L1_location_name', 'F_facility_name'));
	    
	    $sql = $create_view->__toString();
	    $sql = str_replace('`C_consumption`,', '`C_consumption`', $sql);
	    $sql = str_replace('`l1`.*,', '', $sql);
	    $sql = str_replace('`l2`.*,', '', $sql);
	    $sql = str_replace('`l3`.*,', '', $sql);
	    $sql = str_replace('`c`.*,', '', $sql);
	    $sql = str_replace('`cno`.*,', '', $sql);
	    $sql = str_replace('`mc`.*', '', $sql);
	    
	    try{
	        $sql='create or replace view lc_view as ('.$sql.')';
	        $db->fetchOne( $sql );
	    }
	    catch (Exception $e) {
	        //echo $e->getMessage();
	        //var_dump('error', $e->getMessage());
	        
	    }

	    $select = $db->select()
	    ->from(array('cv' => 'lc_view_extended_pivot_non_null'),
	        array(
	            'L1_location_name',
	            'L2_location_name',
	            'L3_location_name',
	            'ifnull(sum(cv.consumption1),0) as consumption1',
	            'ifnull(sum(cv.consumption2),0) as consumption2',
	            'ifnull(sum(cv.consumption3),0) as consumption3',
	            'ifnull(sum(cv.consumption4),0) as consumption4',
	            'ifnull(sum(cv.consumption5),0) as consumption5',
	            'ifnull(sum(cv.consumption6),0) as consumption6',
	            'ifnull(sum(cv.consumption7),0) as consumption7', ))
	    	->group(array($useName))
	    	->order(array('L3_location_name', 'L2_location_name', 'L1_location_name'));
	    
	    $result = $db->fetchAll($select);
	        
	    foreach ($result as $row){
	    
	        $output[] = array(
	            "L1_location_name" => $row['L1_location_name'],
	            "L2_location_name" => $row['L2_location_name'],
	            "L3_location_name" => $row['L3_location_name'],
	           // "location_name" => str_replace(' ', '', $row[$useName]),
	           // "location_name" => substr($row[$useName], 0, 16),
	            "location_name" => $row[$useName],
	            "consumption1" => $row['consumption1'],
	            "consumption2" => $row['consumption2'],
	            "consumption3" => $row['consumption3'],
	            "consumption4" => $row['consumption4'],
	            "consumption5" => $row['consumption5'],
	            "consumption6" => $row['consumption6'],
	            "consumption7" => $row['consumption7'],
	            "type" => 1
	        );
	    }
	            break;
	            
	        case 'facility':
	            
	            //file_put_contents('c:\wamp\logs\php_debug.log', 'Dashboard-CHAI 231>'.PHP_EOL, FILE_APPEND | LOCK_EX);	ob_start();
	            //var_dump('all=', $dataName, $id, $where, $group, $useName, "END");
	            //$toss = ob_get_clean(); file_put_contents('c:\wamp\logs\php_debug.log', $toss .PHP_EOL, FILE_APPEND | LOCK_EX);
	            
	            $create_view = $db->select()
	            ->from(array('f' => 'facility'),
	                array(
	                    'f.id as F_id',
	                    'f.facility_name as F_facility_name',
	                    //"replace(f.facility_name, '\'', '\\\'') as F_facility_name',",
	                    'f.location_id as F_location_id',
	                    'l1.id as L1_id',
	                    'l1.location_name as L1_location_name',
	                    'l2.id as L2_id',
	                    'l2.location_name as L2_location_name',
	                    'l2.parent_id as L2_parent_id',
	                    'l3.location_name as L3_location_name',
	                    'cno.id as CNO_id',
	                    'c.date as C_date',
                        'ifnull(sum(c.consumption),0) as C_consumption' ))	            	         	
                        ->joinLeft(array('l1' => "location"), 'f.location_id = l1.id')
	            	    ->joinLeft(array('l2' => "location"), 'l1.parent_id = l2.id')
	            	    ->joinLeft(array('l3' => "location"), 'l2.parent_id = l3.id')
	            	    ->joinLeft(array("c" => "commodity"), 'f.id = c.facility_id')
	            	    ->joinLeft(array("cno" => "commodity_name_option"), 'c.name_id = cno.id')
	            	    ->joinInner(array('mc' => 'lc_view_subselect'), 'f.id = mc.facility_id and month(c.date) = C_monthDate')
	            	    ->where($where)
	            	    ->group(array($group))
	            	    ->order(array('L3_location_name', 'L2_location_name', 'L1_location_name', 'F_facility_name'));
	            	    
	            	    $sql = $create_view->__toString();
	            	    $sql = str_replace('`C_consumption`,', '`C_consumption`', $sql);
	            	    $sql = str_replace('`l1`.*,', '', $sql);
	            	    $sql = str_replace('`l2`.*,', '', $sql);
	            	    $sql = str_replace('`l3`.*,', '', $sql);
	            	    $sql = str_replace('`c`.*,', '', $sql);
	            	    $sql = str_replace('`cno`.*,', '', $sql);
	            	    $sql = str_replace('`mc`.*', '', $sql);
	            	    
	            	    try{
	            	        $sql='create or replace view lc_view as ('.$sql.')';
	            	        $db->fetchOne( $sql );
	            	    }
	            	    catch (Exception $e) {
	            	        //echo $e->getMessage();
	            	    }
	            	    
	            	    
	                   if($useName == 'F_facility_name'){
	                       $select = $db->select()
	                       ->from(array('cv' => 'lc_view_extended_pivot_non_null'),
	                           array(
	                               'F_facility_name',
	                               'L1_location_name',
	                               'L2_location_name',
	                               'L3_location_name',
	                               'consumption1',
	                               'consumption2',
	                               'consumption3',
	                               'consumption4', 
	                               'consumption5',
	                               'consumption6',
	                               'consumption7',
	                           ));
	                   }
	                   else {
	            	    $select = $db->select()
	            	    ->from(array('cv' => 'lc_view_extended_pivot_non_null'),
	            	        array(
	            	            'F_facility_name',
	            	            'L1_location_name',
	            	            'L2_location_name',
	            	            'L3_location_name',
                        'ifnull(sum(cv.consumption1),0) as consumption1',
                        'ifnull(sum(cv.consumption1),0) as consumption2',
                        'ifnull(sum(cv.consumption2),0) as consumption3',
                        'ifnull(sum(cv.consumption3),0) as consumption4',
                        'ifnull(sum(cv.consumption4),0) as consumption5',
                        'ifnull(sum(cv.consumption5),0) as consumption6',
                        'ifnull(sum(cv.consumption6),0) as consumption7', ))
	            	    	->group(array('L1_location_name'))
	            	    	->order(array('L3_location_name', 'L2_location_name', 'L1_location_name'));
	                   }
	            	    
	            	    $result = $db->fetchAll($select);
	            	    
	            	    foreach ($result as $row){
	            	    
	            	        $output[] = array(
	            	            "L1_location_name" => $row['L1_location_name'],
	            	            "L2_location_name" => $row['L2_location_name'],
	            	            "L3_location_name" => $row['L3_location_name'],
	            	            "location_name" => $row[$useName], 0, 16,
	            	    "consumption1" => $row['consumption1'],
                        "consumption2" => $row['consumption2'],
                        "consumption3" => $row['consumption3'],
                        "consumption4" => $row['consumption4'],
                        "consumption5" => $row['consumption5'],
                        "consumption6" => $row['consumption6'],
                        "consumption7" => $row['consumption7'],
	            	            "type" => 1
	            	        );
	            	    }
	            break;
	    }
	    return $output;
	}
	
	public function fetchAMCDetails($where = null) {
	    $db = Zend_Db_Table_Abstract::getDefaultAdapter();
	    
	    $create_view = $db->select()
	    ->from(array('c' => 'commodity'),
	        array(
	            'cno.id as CNO_id',
	            'cno.external_id as CNO_external_id',
	            'cto.id as CTO_id',
	            'c.date as date',
	            'f.id as F_id',
	            'f.facility_name as F_facility_name',
	            'f.location_id as F_location_id',
	            'l1.id as L1_id',
	            'l1.location_name as L1_location_name',
	            'l2.id as L2_id',
	            'l2.location_name as L2_location_name',
	            'l2.parent_id as L2_parent_id',
	            'l3.location_name as L3_location_name',
	            'ifnull(sum(c.consumption),0) as C_consumption' )) 
        	    ->joinLeft(array('f' => "facility"), 'f.id = c.facility_id')
        	    ->joinLeft(array('l1' => "location"), 'f.location_id = l1.id')
	    	    ->joinLeft(array('l2' => "location"), 'l1.parent_id = l2.id')
	    	    ->joinLeft(array('l3' => "location"), 'l2.parent_id = l3.id')
	    	    ->joinLeft(array("cno" => "commodity_name_option"), 'c.name_id = cno.id')
	    	    ->joinLeft(array("cto" => "commodity_type_option"), 'c.type_id = cto.id')
	    	    ->where($where)
	    	    ->group(array('CNO_external_id', 'c.date'))
	    	    ->order(array('L3_location_name', 'L2_location_name', 'L1_location_name', 'F_facility_name'));
	    	    
	    	   $sql = $create_view->__toString();
	    	   $sql = str_replace('`C_consumption`,', '`C_consumption`', $sql);
	    	   $sql = str_replace('`f`.*,', '', $sql);
	    	   $sql = str_replace('`l1`.*,', '', $sql);
	    	   $sql = str_replace('`l2`.*,', '', $sql);
	    	   $sql = str_replace('`l3`.*,', '', $sql);
	    	   $sql = str_replace('`cno`.*,', '', $sql);
	    	   $sql = str_replace('`cto`.*', '', $sql);
	    	    
	    	    try{
	    	        $sql='create or replace view amc_view as ('.$sql.')';
	    	        $db->fetchOne( $sql );
	    	    }
	    	    catch (Exception $e) {
	    	        //echo $e->getMessage();
	    	        //var_dump('error', $e->getMessage());
	    	        
	    	    }
	    
	    $output = array();
	    
	    $orderClause = new Zend_Db_Expr("`c`.`date` desc limit 12");
	    
	    $select = $db->select()
	    ->from(array('cv' => 'amc_view_extended_pivot_non_null'),
	        array(
	            'monthname(cv.date) as month',
	            'cv.consumption1 as consumption1',
	            'cv.consumption2 as consumption2',
	            'cv.consumption3 as consumption3',
	            'cv.consumption4 as consumption4',
	            'cv.consumption5 as consumption5',
	            'cv.consumption6 as consumption6',
	            'cv.consumption7 as consumption7'))	            
	            ->order(array('cv.date desc'))
	            ->limit('12');
	    
	    
	    /*
	    select monthname(date) as month, sum(implant_consumption) as implant_consumption, sum(injectable_consumption) as injectable_consumption
	    from amc_view_extended_pivot_non_null
	    group by monthname(date)
	    order by date;
	    */
	    
	            
	    $result = $db->fetchAll($select);
	    
	    foreach ($result as $row){
	    
	        $output[] = array(
	            "commodity_name" => $row['commodity_name'],
	            "month" => $row['month'],
	            "consumption1" => $row['consumption1'],
	            "consumption2" => $row['consumption2'],
	            "consumption3" => $row['consumption3'],
	            "consumption4" => $row['consumption4'],
	            "consumption5" => $row['consumption5'],
	            "consumption6" => $row['consumption6'],
	            "consumption7" => $row['consumption7']
	        );
	    }
	
	    return array_reverse($output, true);
	}
	
	public function fetchHCWTDetails($where = null) {
	    $db = Zend_Db_Table_Abstract::getDefaultAdapter();
	    $output = array();
	     
	    $select = $db->select()
	    ->from(array('hv' => 'hcwt_view_extended_pivot_non_null'),
	        array(
	            'month(hv.date) as month',
	            'hv.fp_trained as fp_trained',
                'hv.larc_trained as larc_trained'))
                ->group(array('month(hv.date)'))
		        ->order(array('hv.date desc'))
		        ->limit(array('12') );
		    
		    /*
select year(date) as year, sum(fp_trained) as fp_trained, sum(larc_trained) as larc_trained
from hcwt_view_extended_pivot_non_null 
group by year(date)
order by date;

		    */
		            
		    $result = $db->fetchAll($select);
		    
		    foreach ($result as $row){
		    
		        $output[] = array(
		            "month" => $row['month'],
		            "fp_trained" => $row['fp_trained'],
		            "larc_trained" => $row['larc_trained']
		        );
		    }
		
		    return $output;
		}
		
		public function fetchPercentFacHWProvidingStockOutDetails($cnoConsumptionWhere = null, $cnoStockOutWhere = null, $geoWhere = null, $group = null, $useName = null ) {
		    $db = Zend_Db_Table_Abstract::getDefaultAdapter();
		    $numer = array();
		    
		    // provided last 6 months in stock_out
		    
		    $stock_out_sql = $db->select()
		    ->from(array('c' => 'commodity'),
		        array("frr.facility_external_id as fei" ))
		        ->joinLeft(array('cno' => "commodity_name_option"), 'c.name_id = cno.id')
		        ->joinLeft(array('cto' => "commodity_type_option"), 'c.type_id = cto.id')
		        ->joinLeft(array('f' => "facility"), 'c.facility_id = f.id')
		        ->joinInner(array('frr' => "facility_report_rate"), 'frr.facility_external_id = f.external_id')
		        ->joinLeft(array('l1' => "location"), 'f.location_id = l1.id')
		        ->joinLeft(array('l2' => "location"), 'l1.parent_id = l2.id')
		        ->joinLeft(array('l3' => "location"), 'l2.parent_id = l3.id')
		        ->where($cnoStockOutWhere)  // s.b.  cno.external_id in ('ibHR9NQ0bKL') and c.stock_out = 'Y' and c.date = (select max(date) from commodity)
 		        ->where($geoWhere);

		    $sql = $stock_out_sql->__toString();
		    $sql = str_replace('`frr`.`facility_external_id` AS `fei`,','`frr`.`facility_external_id` AS `fei`', $sql);
		    $sql = str_replace('`frr`.*,', '', $sql);
		    $sql = str_replace('`cno`.*,', '', $sql);
		    $sql = str_replace('`cto`.*,', '', $sql);
		    $sql = str_replace('`f`.*,', '', $sql);
		    $sql = str_replace('`frr`.*,', '', $sql);
		    $sql = str_replace('`l1`.*,', '', $sql);
		    $sql = str_replace('`l2`.*,', '', $sql);
		    $sql = str_replace('`l3`.*', '', $sql);
		    
		    $six_month_sql = $db->select()
		    ->from(array('c' => 'commodity'),
		        array(
		            'l1.location_name as L1_location_name', 
		            'l2.location_name as L2_location_name', 
		            'l3.location_name as L3_location_name', 
 		            'frr.facility_external_id as fei' ))
		        ->joinLeft(array('cno' => "commodity_name_option"), 'c.name_id = cno.id')
		        ->joinLeft(array('cto' => "commodity_type_option"), 'c.type_id = cto.id')
		        ->joinLeft(array('f' => "facility"), 'c.facility_id = f.id')
		        ->joinInner(array('frr' => "facility_report_rate"), 'frr.facility_external_id = f.external_id')
		        ->joinLeft(array('l1' => "location"), 'f.location_id = l1.id')
		        ->joinLeft(array('l2' => "location"), 'l1.parent_id = l2.id')
		        ->joinLeft(array('l3' => "location"), 'l2.parent_id = l3.id')
		        ->where($cnoConsumptionWhere) // s.b. cno.external_id in ('ibHR9NQ0bKL') and c.consumption > 0 and c.date between date_sub(now(), interval 182 day) and now()
		        ->where(" f.external_id in ( $sql ) " )
		        // ->where($geoWhere)
		        ->group(array( $useName ));
		    
		    $sql = $six_month_sql->__toString();
		    $sql = str_replace('`frr`.`facility_external_id` AS `fei`,','`frr`.`facility_external_id` AS `fei`', $sql);
		    $sql = str_replace('`frr`.*,', '', $sql);
		    $sql = str_replace('`cno`.*,', '', $sql);
		    $sql = str_replace('`cto`.*,', '', $sql);
		    $sql = str_replace('`f`.*,', '', $sql);
		    $sql = str_replace('`frr`.*,', '', $sql);
		    $sql = str_replace('`l1`.*,', '', $sql);
		    $sql = str_replace('`l2`.*,', '', $sql);
		    $sql = str_replace('`l3`.*', '', $sql);
		    
		   $final_count_select = 'select L1_location_name, L2_location_name, L3_location_name, count(distinct(fei)) as cnt from ( ' . 
		  		   $sql . ')t group by ' . $useName;
		    
		    $result = $db->fetchAll($final_count_select);
		
		    foreach ($result as $row){
		        $numer[] = array(
		            "location" => $row[$useName],
		            "cnt" => $row['cnt'],
		            "color" => 'blue',
		        );
		    }
		    
		    $select = $db->select()
		    ->from(array('c' => 'commodity'),
		        array(
		            'l1.location_name as L1_location_name',
		            'l2.location_name as L2_location_name',
		            'l3.location_name as L3_location_name',
		            'count(distinct(c.facility_id)) as cnt' ))
		        ->joinLeft(array('cno' => "commodity_name_option"), 'c.name_id = cno.id')
		        ->joinLeft(array('cto' => "commodity_type_option"), 'c.type_id = cto.id')
		        ->joinLeft(array('f' => "facility"), 'c.facility_id = f.id')
		        ->joinLeft(array('l1' => "location"), 'f.location_id = l1.id')
		        ->joinLeft(array('l2' => "location"), 'l1.parent_id = l2.id')
		        ->joinLeft(array('l3' => "location"), 'l2.parent_id = l3.id')
		        ->where($cnoConsumptionWhere)
		        ->where($geoWhere)
		        ->group(array( $useName ));
		
		    $result = $db->fetchAll($select);
		
		    foreach ($result as $row){
		        $denom[] = array(
		            "location" => $row[$useName],
		            "cnt" => $row['cnt'],
		            "color" => 'blue',
		        );
		    }
		     
		    foreach($denom as $i => $row){
		         
		        $output[] = array('location' => $row['location'], 'percent' => $numer[$i]['cnt']/ $row['cnt'], 'color' => $row['color']);
		
		    }
		    
		    file_put_contents('c:\wamp\logs\php_debug.log', 'Dashboard-CHAI fetchPercentFacHWProvidingStockOutDetails  >'.PHP_EOL, FILE_APPEND | LOCK_EX);	ob_start();
		    var_dump('$numer= ', $numer, 'END');
		    var_dump('$denom= ', $denom, 'END');
		    var_dump('$output= ', $output, 'END');
		    //var_dump('$ouput= ', $output, 'END');
		    $toss = ob_get_clean(); file_put_contents('c:\wamp\logs\php_debug.log', $toss .PHP_EOL, FILE_APPEND | LOCK_EX);
		
		    return $output;
		}
		
		public function fetchPercentFacHWTrainedStockOutDetails($trainingWhere = null, $stockOutWhere = null, $sixMonthWhere = null, $geoWhere = null, $group = null, $useName = null ) {
		    $db = Zend_Db_Table_Abstract::getDefaultAdapter();
		    $numer = array();
		    
		    // provided last 6 months in stock_out
		    
		    $stock_out_sql = $db->select()
		    ->from(array('c' => 'commodity'),
		        array("frr.facility_external_id as fei" ))
		        ->joinLeft(array('cno' => "commodity_name_option"), 'c.name_id = cno.id')
		        ->joinLeft(array('f' => "facility"), 'c.facility_id = f.id')
		        ->joinInner(array('frr' => "facility_report_rate"), 'frr.facility_external_id = f.external_id')
		        ->joinLeft(array('l1' => "location"), 'f.location_id = l1.id')
		        ->joinLeft(array('l2' => "location"), 'l1.parent_id = l2.id')
		        ->joinLeft(array('l3' => "location"), 'l2.parent_id = l3.id')
		        ->where($stockOutWhere)  // s.b.  cno.external_id in ('JyiR2cQ6DZT') and c.date = (select max(date) from commodity) ) 
 		        ->where($geoWhere);

		    $sql = $stock_out_sql->__toString();
		    $sql = str_replace('`frr`.`facility_external_id` AS `fei`,','`frr`.`facility_external_id` AS `fei`', $sql);
		    $sql = str_replace('`frr`.*,', '', $sql);
		    $sql = str_replace('`cno`.*,', '', $sql);
		    $sql = str_replace('`cto`.*,', '', $sql);
		    $sql = str_replace('`f`.*,', '', $sql);
		    $sql = str_replace('`frr`.*,', '', $sql);
		    $sql = str_replace('`l1`.*,', '', $sql);
		    $sql = str_replace('`l2`.*,', '', $sql);
		    $sql = str_replace('`l3`.*', '', $sql);
		    
		    $training_sql = $db->select()
		    ->from(array('pt' => 'person_to_training'),
		        array(
		            'l1.location_name as L1_location_name', 
		            'l2.location_name as L2_location_name', 
		            'l3.location_name as L3_location_name', 
 		            'frr.facility_external_id as fei' ))
		        ->joinLeft(array('p' => "person"), 'pt.person_id = p.id')
		        ->joinLeft(array('f' => "facility"), 'p.facility_id = f.id')
		        ->joinInner(array('frr' => "facility_report_rate"), 'frr.facility_external_id = f.external_id')
		        ->joinLeft(array('l1' => "location"), 'f.location_id = l1.id')
		        ->joinLeft(array('l2' => "location"), 'l1.parent_id = l2.id')
		        ->joinLeft(array('l3' => "location"), 'l2.parent_id = l3.id')
		        ->joinLeft(array('t' => "training"), 'pt.training_id = t.id')
		        ->joinInner(array('tto' => "training_title_option"), 't.training_title_option_id = tto.id')
		        ->where($trainingWhere) // s.b. t.training_title_option_id = 1  
		        ->where($geoWhere)
		        ->where(" frr.facility_external_id in ( $sql ) " )
		        ->group(array( $useName ));
		    
		    $sql = $training_sql->__toString();
		    $sql = str_replace('`frr`.`facility_external_id` AS `fei`,','`frr`.`facility_external_id` AS `fei`', $sql);
		    $sql = str_replace('`p`.*,', '', $sql);
		    $sql = str_replace('`f`.*,', '', $sql);
		    $sql = str_replace('`frr`.*,', '', $sql);
		    $sql = str_replace('`l1`.*,', '', $sql);
		    $sql = str_replace('`l2`.*,', '', $sql);
		    $sql = str_replace('`l3`.*,', '', $sql);
		    $sql = str_replace('`t`.*,', '', $sql);
		    $sql = str_replace('`tto`.*', '', $sql);
		    
		   $final_count_select = 'select L1_location_name, L2_location_name, L3_location_name, count(distinct(fei)) as cnt from ( ' . 
		  		   $sql . ')t group by ' . $useName;
		    
		    $result = $db->fetchAll($final_count_select);
		
		    foreach ($result as $row){
		        $numer[] = array(
		            "location" => $row[$useName],
		            "cnt" => $row['cnt'],
		            "color" => 'blue',
		        );
		    }
		    
		    $select = $db->select()
		    ->from(array('c' => 'commodity'),
		        array(
		            'l1.location_name as L1_location_name',
		            'l2.location_name as L2_location_name',
		            'l3.location_name as L3_location_name',
		            'count(distinct(c.facility_id)) as cnt' ))
		        ->joinLeft(array('cno' => "commodity_name_option"), 'c.name_id = cno.id')
		        ->joinLeft(array('cto' => "commodity_type_option"), 'c.type_id = cto.id')
		        ->joinLeft(array('f' => "facility"), 'c.facility_id = f.id')
		        ->joinLeft(array('l1' => "location"), 'f.location_id = l1.id')
		        ->joinLeft(array('l2' => "location"), 'l1.parent_id = l2.id')
		        ->joinLeft(array('l3' => "location"), 'l2.parent_id = l3.id')
		        ->where($sixMonthWhere) // s.b. cno.external_id in ('w92UxLIRNTl', 'H8A8xQ9gJ5b', 'ibHR9NQ0bKL', 'yJSLjbC9Gnr', 'vDnxlrIQWUo', 'krVqq8Vk5Kw') or cno.external_id = 'DiXDJRmPwfh', LARC
		        ->where($geoWhere)
		        ->group(array( $useName ));
		
		    $result = $db->fetchAll($select);
		
		    foreach ($result as $row){
		        $denom[] = array(
		            "location" => $row[$useName],
		            "cnt" => $row['cnt'],
		            "color" => 'blue',
		        );
		    }
		     
		    foreach($denom as $i => $row){
		         
		        $output[] = array('location' => $row['location'], 'percent' => $numer[$i]['cnt']/ $row['cnt'], 'color' => $row['color']);
		
		    }
		    
		    //file_put_contents('c:\wamp\logs\php_debug.log', 'Dashboard-CHAI fetchPercentFacHWProvidingStockOutDetails  >'.PHP_EOL, FILE_APPEND | LOCK_EX);	ob_start();
		    //var_dump('$numer= ', $numer, 'END');
		    //var_dump('$denom= ', $denom, 'END');
		    //var_dump('$output= ', $output, 'END');
		    //var_dump('$ouput= ', $output, 'END');
		    //$toss = ob_get_clean(); file_put_contents('c:\wamp\logs\php_debug.log', $toss .PHP_EOL, FILE_APPEND | LOCK_EX);
		
		    return $output;
				}
				

		public function fetchPercentFacHWTrainedProvidingDetails($trainingWhere = null, $cnoWhere = null, $geoWhere = null, $group = null, $useName = null ) {
		    $db = Zend_Db_Table_Abstract::getDefaultAdapter();
		    $trained = array();
		  
		     
		    $select = $db->select()
		    ->from(array('pt' => 'person_to_training'),
		        array(
		            "count(*) as cnt" ))
		
		            ->joinLeft(array('p' => "person"), 'pt.person_id = p.id')
		            ->joinLeft(array('f' => "facility"), 'p.facility_id = f.id')
		            ->joinLeft(array('l1' => "location"), 'f.location_id = l1.id')
		            ->joinLeft(array('l2' => "location"), 'l1.parent_id = l2.id')
		            ->joinLeft(array('l3' => "location"), 'l2.parent_id = l3.id')
		             
		            ->joinLeft(array("t" => "training"), 'pt.training_id = t.id')
		            ->joinInner(array('tto' => training_title_option), 't.training_title_option_id = tto.id')
		            ->where($trainingWhere);
		     
		    $result = $db->fetchAll($select);
		     
		    foreach ($result as $row){
		        $trained[] = array(
		            "location" => 'National',
		            "cnt" => $row['cnt'],
		            "color" => 'black',
		        );
		    }
		     
		    $select = $db->select()
		    ->from(array('pt' => 'person_to_training'),
		        array(
		            'l1.location_name as L1_location_name',
		            'l2.location_name as L2_location_name',
		            'l3.location_name as L3_location_name',
		            "count(*) as cnt" ))
		             
		            ->joinLeft(array('p' => "person"), 'pt.person_id = p.id')
		            ->joinLeft(array('f' => "facility"), 'p.facility_id = f.id')
		            ->joinLeft(array('l1' => "location"), 'f.location_id = l1.id')
		            ->joinLeft(array('l2' => "location"), 'l1.parent_id = l2.id')
		            ->joinLeft(array('l3' => "location"), 'l2.parent_id = l3.id')
		
		            ->joinLeft(array("t" => "training"), 'pt.training_id = t.id')
		            ->joinInner(array('tto' => training_title_option), 't.training_title_option_id = tto.id')
		            ->where($geoWhere)
		            ->where($trainingWhere)
		            ->group(array($useName))
		            ->order(array($useName));
		
		    $result = $db->fetchAll($select);
		
		    foreach ($result as $row){
		        $color = 'blue' ;
		
		        $trained[] = array(
		            "location" => $row[$useName],
		            "cnt" => $row['cnt'],
		            "color" => $color,
		        );
		    }
		
		    //file_put_contents('c:\wamp\logs\php_debug.log', 'Dashboard-CHAI 243 >'.PHP_EOL, FILE_APPEND | LOCK_EX);	ob_start();
		    //var_dump($output,"END");
		    //var_dump('id=', $id);
		    //$result = ob_get_clean(); file_put_contents('c:\wamp\logs\php_debug.log', $result .PHP_EOL, FILE_APPEND | LOCK_EX);
		
		$providing = array();
        
        $select = $db->select()
        ->from(array('c' => 'commodity'),
            array("count(distinct(c.facility_id)) as cnt" ))
             
            ->joinLeft(array('cno' => "commodity_name_option"), 'c.name_id = cno.id')
            ->joinLeft(array('cto' => "commodity_type_option"), 'c.type_id = cto.id')
            ->joinLeft(array('f' => "facility"), 'c.facility_id = f.id')
            ->joinLeft(array('l1' => "location"), 'f.location_id = l1.id')
            ->joinLeft(array('l2' => "location"), 'l1.parent_id = l2.id')
            ->joinLeft(array('l3' => "location"), 'l2.parent_id = l3.id')
            ->where($cnoWhere);
        
        $result = $db->fetchAll($select);
        
        foreach ($result as $row){
            $providing[] = array(
                "location" => 'National',
                "cnt" => $row['cnt'],
                "color" => 'black',
            );
        }
         
	        $select = $db->select()
	        ->from(array('c' => 'commodity'),
	            array(
	                'l1.location_name as L1_location_name',
	                'l2.location_name as L2_location_name',
	                'l3.location_name as L3_location_name',
	                "count(distinct(c.facility_id)) as cnt" ))
        
	                ->joinLeft(array('cno' => "commodity_name_option"), 'c.name_id = cno.id')
	                ->joinLeft(array('cto' => "commodity_type_option"), 'c.type_id = cto.id')
	                ->joinLeft(array('f' => "facility"), 'c.facility_id = f.id')
	                ->joinLeft(array('l1' => "location"), 'f.location_id = l1.id')
	                ->joinLeft(array('l2' => "location"), 'l1.parent_id = l2.id')
	                ->joinLeft(array('l3' => "location"), 'l2.parent_id = l3.id')
	                ->where($geoWhere)
	                ->where($cnoWhere)
	                ->group(array( $useName ))
	                ->order(array( $useName ));
	    
	                    $result = $db->fetchAll($select);
	    
	                    foreach ($result as $row){
	                        $color = 'blue' ;
	    
	                        $providing[] = array(
	                           "location" => $row[$useName],
	                           "cnt" => $row['cnt'],
	                           "color" => $color,
	                            );
	                    }
	                    
	                    foreach($providing as $i => $row){
	                        
                            $output[] = array('location' => $row['location'], 'percent' => $trained[$i]['cnt']/ $row['cnt'], 'color' => $row['color']);                      

	                    }
	    
	    		//file_put_contents('c:\wamp\logs\php_debug.log', 'Dashboard-CHAI fetchPercentFacHWTrainedProvidingDetails  >'.PHP_EOL, FILE_APPEND | LOCK_EX);	ob_start();
	    		//var_dump('$trained= ', $trained, 'END');
	    		//var_dump('$providing= ', $providing, 'END');
	    		//var_dump('$ouput= ', $output, 'END');
	    		//$toss = ob_get_clean(); file_put_contents('c:\wamp\logs\php_debug.log', $toss .PHP_EOL, FILE_APPEND | LOCK_EX);	    
		    
		    return $output;
		}
		
		
		
		
    public function fetchPercentFacHWTrainedDetails($trainingWhere = null, $geoWhere = null, $group = null, $useName = null ) {
	    $db = Zend_Db_Table_Abstract::getDefaultAdapter();
	    $output = array();
	    
	    //national
	    $create_view = $db->select()
	    ->from(array('f' => 'facility'),
	        array(
	             	"count(distinct(frr.facility_external_id)) as denom"))
	             	->joinLeft(array('l1' => "location"), 'f.location_id = l1.id')
	                ->joinLeft(array('l2' => "location"), 'l1.parent_id = l2.id')
	                ->joinLeft(array('l3' => "location"), 'l2.parent_id = l3.id')
	                ->joinInner(array('frr' => "facility_report_rate"), 'f.external_id = frr.facility_external_id');
	            
	            $sql = $create_view->__toString();
	            $sql = str_replace('AS `denom`,', 'AS `denom`', $sql);
	            $sql = str_replace('`l1`.*,', '', $sql);
	            $sql = str_replace('`l2`.*,', '', $sql);
	            $sql = str_replace('`l3`.*,', '', $sql);
	            $sql = str_replace('`frr`.*', '', $sql);
	            
	            try{
	                $sql = 'create or replace view pft_denom_view as ('.$sql.')';
	                $db->fetchOne($sql);
	            }
	            catch (Exception $e) { // normal operation throws "General Error"
	                //echo $e->getMessage();
	                //file_put_contents('c:\wamp\logs\php_debug.log', 'Dashboard-CHAI >'.PHP_EOL, FILE_APPEND | LOCK_EX);	ob_start();
	                //var_dump('ERROR= ', $e->getMessage(), "END");
	                //$result = ob_get_clean(); file_put_contents('c:\wamp\logs\php_debug.log', $result .PHP_EOL, FILE_APPEND | LOCK_EX);
	            }
	            
	            $subSelect = new Zend_Db_Expr("( select denom from pft_denom_view )"); 
	    
	    $select = $db->select()
	    ->from(array('pt' => 'person_to_training'),
	        array(
	            "count(*) / $subSelect as percent" ))
	             
	            ->joinLeft(array('p' => "person"), 'pt.person_id = p.id')
	            ->joinLeft(array('f' => "facility"), 'p.facility_id = f.id')
	            ->joinInner(array('frr' => "facility_report_rate"), 'f.external_id = frr.facility_external_id')
	            ->joinLeft(array('l1' => "location"), 'f.location_id = l1.id')
	            ->joinLeft(array('l2' => "location"), 'l1.parent_id = l2.id')
	            ->joinLeft(array('l3' => "location"), 'l2.parent_id = l3.id')
	            ->joinLeft(array("t" => "training"), 'pt.training_id = t.id')
	            ->joinInner(array('tto' => training_title_option), 't.training_title_option_id = tto.id')
	            ->where($trainingWhere);
	    
	    $sql = $select->__toString();
	    $sql = str_replace('AS `percent`,', 'AS `percent`', $sql);
	    $sql = str_replace('`p`.*,', '', $sql);
	    $sql = str_replace('`f`.*,', '', $sql);
	    $sql = str_replace('`frr`.*,', '', $sql);
	    $sql = str_replace('`l1`.*,', '', $sql);
	    $sql = str_replace('`l2`.*,', '', $sql);
	    $sql = str_replace('`l3`.*,', '', $sql);
	    $sql = str_replace('`t`.*,', '', $sql);
	    $sql = str_replace('`tto`.*', '', $sql);
	    
	    $result = $db->fetchAll($sql);
	    
	    foreach ($result as $row){
	        $output[] = array(
	            "Location" => 'National',
	            "percent" => $row['percent'],
	            "color" => 'black',
	        );
	    }
	    
    //geo	    
	    $create_view = $db->select()
	    ->from(array('f' => 'facility'),
	        array(
	            "$group as Location_id", // s.b. l1.id, l2.id, or l3.id
	            
	            "count(distinct(frr.facility_external_id)) as denom"))
	            ->joinLeft(array('l1' => "location"), 'f.location_id = l1.id')
	            ->joinLeft(array('l2' => "location"), 'l1.parent_id = l2.id')
	            ->joinLeft(array('l3' => "location"), 'l2.parent_id = l3.id')
	            ->joinInner(array('frr' => "facility_report_rate"), 'f.external_id = frr.facility_external_id')
	            ->where($geoWhere)
	            ->group(array( $group ));
	        
	        $sql = $create_view->__toString();
	        $sql = str_replace('AS `denom`,', 'AS `denom`', $sql);
	        $sql = str_replace('`l1`.*,', '', $sql);
	        $sql = str_replace('`l2`.*,', '', $sql);
	        $sql = str_replace('`l3`.*,', '', $sql);
	        $sql = str_replace('`frr`.*', '', $sql);
	        
	        try{
	            $sql = 'create or replace view pft_denom_view as ('.$sql.')'; // could reuse identical pfp_denom_view but building pft... for concurrency
	            $db->fetchOne($sql);
	        }
	        catch (Exception $e) { // normal operation throws "General Error"
	            //echo $e->getMessage();
	            //file_put_contents('c:\wamp\logs\php_debug.log', 'Dashboard-CHAI >'.PHP_EOL, FILE_APPEND | LOCK_EX);	ob_start();
	            //var_dump('ERROR= ', $e->getMessage(), "END");
	            //$result = ob_get_clean(); file_put_contents('c:\wamp\logs\php_debug.log', $result .PHP_EOL, FILE_APPEND | LOCK_EX);
	        }
	        
	        $subSelect = new Zend_Db_Expr("( select denom from pft_denom_view where $group = Location_id )"); //corraelated
		
		        $select = $db->select()
		        ->from(array('pt' => 'person_to_training'), 
		          array(
		          
		          'l1.location_name as L1_location_name', 
		          'l2.location_name as L2_location_name',
		          'l3.location_name as L3_location_name',
		          "count(*) / $subSelect as percent" ))
		         
		        ->joinLeft(array('p' => "person"), 'pt.person_id = p.id')
		        ->joinLeft(array('f' => "facility"), 'p.facility_id = f.id')
		        ->joinInner(array('frr' => "facility_report_rate"), 'f.external_id = frr.facility_external_id')
		        ->joinLeft(array('l1' => "location"), 'f.location_id = l1.id')
		        ->joinLeft(array('l2' => "location"), 'l1.parent_id = l2.id')
		        ->joinLeft(array('l3' => "location"), 'l2.parent_id = l3.id')
		        
		        ->joinLeft(array("t" => "training"), 'pt.training_id = t.id')
		        ->joinInner(array('tto' => training_title_option), 't.training_title_option_id = tto.id')
		        ->where($geoWhere)
		        ->where($trainingWhere)
		        ->group(array($useName))
                ->order(array('percent'));
		        
		        $sql = $select->__toString();
		        $sql = str_replace('AS `percent`,', 'AS `percent`', $sql);
		        $sql = str_replace('`p`.*,', '', $sql);
		        $sql = str_replace('`f`.*,', '', $sql);
		        $sql = str_replace('`frr`.*,', '', $sql);
		        $sql = str_replace('`l1`.*,', '', $sql);
		        $sql = str_replace('`l2`.*,', '', $sql);
		        $sql = str_replace('`l3`.*,', '', $sql);
		        $sql = str_replace('`t`.*,', '', $sql);
		        $sql = str_replace('`tto`.*', '', $sql);

		        $result = $db->fetchAll($sql);
		        
		        //file_put_contents('c:\wamp\logs\php_debug.log', 'Dashboard-CHAI 243 >'.PHP_EOL, FILE_APPEND | LOCK_EX);	ob_start();
		        //var_dump('$sql=', $sql,"END");
		        //var_dump('$result=', $result,"END");
		        //var_dump('id=', $id);
		        //$toss = ob_get_clean(); file_put_contents('c:\wamp\logs\php_debug.log', $toss .PHP_EOL, FILE_APPEND | LOCK_EX);

		      $cnt = 0;
		      $length = sizeof($result);
		      
		      foreach ($result as $row){
		        $color = 'blue' ;

		        $output[] = array(
		 	    "location" => $row[$useName],
		 	    "percent" => $row['percent'],
		 	    "color" => $color,
		 	      );
		      }
		      

		
		//file_put_contents('c:\wamp\logs\php_debug.log', 'Dashboard-CHAI 243 >'.PHP_EOL, FILE_APPEND | LOCK_EX);	ob_start();
		//var_dump($output,"END");
		//var_dump('id=', $id);
		//$result = ob_get_clean(); file_put_contents('c:\wamp\logs\php_debug.log', $result .PHP_EOL, FILE_APPEND | LOCK_EX);

		return $output;
	}
				    
				    /*
	select 
l2.location_name as outer_state,

count(*) /
(select count(*) as cnt
  from facility f
  left join location l1 ON f.location_id = l1.id
  left join location l2 ON l1.parent_id = l2.id
  left join location l3 ON l2.parent_id = l3.id
  where l2.location_name = outer_state
  group by l2.location_name ) as percentage,

tto.training_title_phrase as title_phrase
-- pt.timestamp_created as date
from person_to_training pt
left join person p on pt.person_id = p.id
left join facility f on p.facility_id = f.id
left join location l1 ON f.location_id = l1.id
left join location l2 ON l1.parent_id = l2.id
left join location l3 ON l2.parent_id = l3.id
left join training t on pt.training_id = t.id
left join training_title_option tto on t.training_title_option_id = tto.id
where 1=1
and t.training_title_option_id in (5) 
and pt.award_id in (1,2)
group by outer_state, title_phrase
-- group by f.facility_name
order by percentage 
;

				    */
	
public function fetchPFTPDetails( $ttoWhere = null, $geoWhere = null, $dateWhere = null, $group = null, $useName = null ) {
    $db = Zend_Db_Table_Abstract::getDefaultAdapter();
    $output = array();
    
    /*
     * fetch numer from pfp_view, 
     *   select C_date, numer from pfp_view where CNO_external_id = 'DiXDJRmPwfh' order by C_date desc limit 12; -- implant
     *   
     * 0) fetch denom from sql below, 
     * 1) fetch total from begin-of-time to 1 year ago, 
     * 2) fetch previous year by month, 
     * 3) fill missing months with zero, 
     * 4) calc running total, 
     * 5) use as denom
      
SELECT
 t.training_end_date, count(1)
FROM `person_to_training` AS `pt`
 LEFT JOIN `person` AS `p` ON pt.person_id = p.id
 LEFT JOIN `facility` AS `f` ON p.facility_id = f.id
 LEFT JOIN `location` AS `l1` ON f.location_id = l1.id
 LEFT JOIN `location` AS `l2` ON l1.parent_id = l2.id
 LEFT JOIN `location` AS `l3` ON l2.parent_id = l3.id
 LEFT JOIN `training` AS `t` ON pt.training_id = t.id
 INNER JOIN `training_title_option` AS `tto` ON t.training_title_option_id = tto.id
WHERE 1=1
and (t.training_title_option_id = 1 ) -- LARC
-- and (t.training_title_option_id = 2 ) -- FP Tech

-- and (t.training_end_date <= date_sub(now(), interval 365 day))
and (t.training_end_date between date_sub(now(), interval 365 day) and now() )

GROUP BY t.training_end_date
order by t.training_end_date desc
;
     */
    
    //providing, numer
    $select = $db->select()
    ->from(array('cv' => 'pfp_view'),
        array(
            'C_date',
            'numer' ))
            ->order(array('C_date desc'))
            ->limit('12');
        
        $numer = $db->fetchAll($select);
    
    // fetch total from begin-of-time to 1 year ago
    $select = $db->select()
    ->from(array('pt' => 'person_to_training'),
        array(
            'sum(1) as start_denom_total' ))
            ->joinLeft(array('p' => "person"), 'pt.person_id = p.id')
            ->joinLeft(array('f' => "facility"), 'p.facility_id = f.id')
            ->joinLeft(array('l1' => "location"), 'f.location_id = l1.id')
    	    ->joinLeft(array('l2' => "location"), 'l1.parent_id = l2.id')
    	    ->joinLeft(array('l3' => "location"), 'l2.parent_id = l3.id')
            ->joinLeft(array("t" => "training"), 'pt.training_id = t.id')
            ->joinInner(array('tto' => training_title_option), 't.training_title_option_id = tto.id')
            ->where($ttoWhere)
            ->where($geoWhere)
            ->where('t.training_end_date <= date_sub(now(), interval 365 day)')
            ->order(array('t.training_end_date desc'));
    
            $sql = $select->__toString();
            $sql = str_replace('AS `start_denom_total`,', 'AS `start_denom_total`', $sql);
            $sql = str_replace('`p`.*,', '', $sql);
            $sql = str_replace('`f`.*,', '', $sql);
            $sql = str_replace('`l1`.*,', '', $sql);
            $sql = str_replace('`l2`.*,', '', $sql);
            $sql = str_replace('`l3`.*', '', $sql);
            $sql = str_replace('`t`.*,', '', $sql);
            $sql = str_replace('`tto`.*', '', $sql);
    
            $start_denom_total = $db->fetchOne( $sql );
            
    //fetch previous year            
    $select = $db->select()
    ->from(array('pt' => 'person_to_training'),
        array(
            't.training_end_date',
            'count(1) as added' ))
            ->joinLeft(array('p' => "person"), 'pt.person_id = p.id')
            ->joinLeft(array('f' => "facility"), 'p.facility_id = f.id')
            ->joinLeft(array('l1' => "location"), 'f.location_id = l1.id')
            ->joinLeft(array('l2' => "location"), 'l1.parent_id = l2.id')
            ->joinLeft(array('l3' => "location"), 'l2.parent_id = l3.id')
            ->joinLeft(array("t" => "training"), 'pt.training_id = t.id')
            ->joinInner(array('tto' => training_title_option), 't.training_title_option_id = tto.id')
            ->where($where)
            ->where('t.training_end_date between date_sub(now(), interval 365 day) and now() ')
            ->group('t.training_end_date')
            ->order(array('t.training_end_date asc'));
    
    $sql = $select->__toString();
    $sql = str_replace('AS `added`,', 'AS `added`', $sql);
    $sql = str_replace('`p`.*,', '', $sql);
    $sql = str_replace('`f`.*,', '', $sql);
    $sql = str_replace('`l1`.*,', '', $sql);
    $sql = str_replace('`l2`.*,', '', $sql);
    $sql = str_replace('`l3`.*', '', $sql);
    $sql = str_replace('`t`.*,', '', $sql);
    $sql = str_replace('`tto`.*', '', $sql);
        
    $prev_year_raw = $db->fetchAll( $sql );
    
    // fill missing months with zero,
    foreach ($numer as $nrow){
        $found = false;
        foreach ($prev_year_raw as $prow){
            if($prow['training_end_date'] == $nrow['C_date']){
                $found = true;
            }
        }
        if($found == false){
            $new_rows[] = array($nrow['C_date'], '0');
        }
    }
    
    foreach ($prev_year_raw as $row){
        $prev_year[] = array('training_end_date' => $row['training_end_date'], 'added' => $row['added']);
    }
    $prev_year = array_merge($prev_year, $new_rows);
    
    // fetch number of facilities reporting for use in tt calc, select sum(cnt) from facilities_reporting_by_state_view;
    $select = $db->select()
    ->from(array('fr' => 'facilities_reporting_by_state_view'),
        array(
            'sum(cnt) as facilities_reporting' ));
    
    $facilities_reporting = $db->fetchOne( $select );
    
    // calc running total and use as denom
    foreach ($numer as $nrow){
        $denom = $start_denom_total;
        foreach ($prev_year as $prow){
            
            if($nrow['C_date'] >= $prow['training_end_date']){
                $denom = $denom + $prow['added'];
            }
        }
        $tmp[] = array($nrow['C_date'], $nrow['numer'], $denom, $prow['added'], $facilities_reporting );
        
        $date = strtotime($nrow['C_date']);
        $monthName = date('F', $date);
        $year = date('Y', $date);
        
        //$output[] = array($nrow['C_date'], $nrow['numer']/$denom);
        $output[] = array('month' => $monthName, 'year' => $year, 'tp_percent' => $nrow['numer']/$denom, 'tt_percent' => $denom/$facilities_reporting);
    }
   
    
    //file_put_contents('c:\wamp\logs\php_debug.log', 'Dashboard-CHAI PFTP >'.PHP_EOL, FILE_APPEND | LOCK_EX);	ob_start();
    //var_dump('$numer= ', $numer,"END");
    //var_dump('$tmp= ', $tmp,"END");
    //var_dump('$facilities_reporting= ', $facilities_reporting,"END");
    //var_dump('$output= ', $output,"END");
    //var_dump('$new_rows= ', $new_rows,"END");
    //var_dump('$prev_year= ', $prev_year,"END");
    //var_dump('$start_denom_total= ', $start_denom_total,"END");
    //var_dump('$month= ', $month,"END");
    //$toss = ob_get_clean(); file_put_contents('c:\wamp\logs\php_debug.log', $toss .PHP_EOL, FILE_APPEND | LOCK_EX);
    
    return $output;
}	


	
public function fetchPFPDetails( $cnoWhere = null, $geoWhere = null, $dateWhere = null, $group = null, $useName = null ) {
    $db = Zend_Db_Table_Abstract::getDefaultAdapter();
    $output = array();
    
    $create_view = $db->select()
    ->from(array('c' => 'commodity'),
        array(
            'c.date as C_date',
            'monthname(c.date) as C_monthName',
            'year(c.date) as C_year',
            'count(distinct(c.facility_id)) as numer'))
            ->joinLeft(array('cno' => "commodity_name_option"), 'c.name_id = cno.id')
            ->joinLeft(array('f' => "facility"), 'c.facility_id = f.id')
    	    ->joinLeft(array('l1' => "location"), 'f.location_id = l1.id')
    	    ->joinLeft(array('l2' => "location"), 'l1.parent_id = l2.id')
    	    ->joinLeft(array('l3' => "location"), 'l2.parent_id = l3.id')
            ->where($cnoWhere)
            ->where($geoWhere)
            ->group(array('C_date'))
            ->order(array('C_date'));
    
    $sql = $create_view->__toString();
    $sql = str_replace('AS `percent`,', 'AS `percent`', $sql);
    $sql = str_replace('`c`.*,', '', $sql);
    $sql = str_replace('`cno`.*,', '', $sql);
    $sql = str_replace('`f`.*,', '', $sql);
    $sql = str_replace('`l1`.*,', '', $sql);
    $sql = str_replace('`l2`.*,', '', $sql);
    $sql = str_replace('`l3`.*', '', $sql);
    
    try{
        $sql='create or replace view pfp_view as ('.$sql.')';
        $db->fetchOne( $sql );
    }
    catch (Exception $e) {
        //echo $e->getMessage();
        //var_dump('error', $e->getMessage());
    }
    
    $select = $db->select()
    ->from(array('cv' => 'pfp_view'),
        array(
            'C_monthName',
            'C_year',
            'numer'))
            ->order(array('C_date desc'))
            ->limit('12');
    
    $result = $db->fetchAll($select);
                
    foreach ($result as $row){
        $output[] = array(
            "month" => $row['C_monthName'],
            "year" => $row['C_year'],
            "numer" => $row['numer'],
        );
    }
    
    //file_put_contents('c:\wamp\logs\php_debug.log', 'Dashboard-CHAI fetchpfpdetails >'.PHP_EOL, FILE_APPEND | LOCK_EX);	ob_start();
    //var_dump($output,"END");
    //var_dump('id=', $id);
    //$result = ob_get_clean(); file_put_contents('c:\wamp\logs\php_debug.log', $result .PHP_EOL, FILE_APPEND | LOCK_EX);
    
    //return $output;
    return array_reverse($output, true);
    
    
}	

public function fetchPFSODetails($where = null) {
    $db = Zend_Db_Table_Abstract::getDefaultAdapter();
    $output = array();

    /*
     *
     *

     select
     c.date,
     monthName(c.date) C_month,
     year(c.date) C_year,
     cno.commodity_name,
     cno.external_id,
     count(distinct(c.facility_id)) numer,
     (select sum(cnt) from facilities_reporting_by_state_view) denom,
     count(facility_id) / (select sum(cnt) from facilities_reporting_by_state_view) * 100 percentage
     from commodity  c
     join commodity_name_option cno on c.name_id = cno.id
     where 1=1
     and (cno.external_id in ( 'DiXDJRmPwfh') and stock_out = 'Y') or  (cno.external_id in ( 'JyiR2cQ6DZT'))
     group by c.date, cno.external_id
     order by c.date, cno.external_id
     ;

     *
     *
    */

    $create_view = $db->select()
    ->from(array('c' => 'commodity'),
        array(
            'c.date as C_date',
            'monthname(c.date) as C_monthName',
            'year(c.date) as C_year',
            'cno.commodity_name as CNO_commodity_name',
            'cno.external_id as CNO_external_id',
            'count(distinct(c.facility_id)) as numer',
            '(select sum(cnt) from facilities_reporting_by_state_view) as denom',
            'count(facility_id) / (select sum(cnt) from facilities_reporting_by_state_view) as percent'))
            ->joinLeft(array('cno' => "commodity_name_option"), 'c.name_id = cno.id')
            ->where($where)
            ->group(array('C_date', 'CNO_external_id'))
            ->order(array('C_date', 'CNO_external_id'));
    
    $sql = $create_view->__toString();
    $sql = str_replace('`c`.*,', '', $sql);
    $sql = str_replace('`cno`.*,', '', $sql);
    
    try{
        $sql='create or replace view pfso_view as ('.$sql.')';
        $db->fetchOne( $sql );
    }
    catch (Exception $e) {
        //echo $e->getMessage();
        //var_dump('error', $e->getMessage());
    }
    
    $select = $db->select()
    ->from(array('cv' => 'pfso_view_extended_pivot_non_null'),
        array(
            'C_monthName',
            'C_year',
            'percent4',
            'percent8' ))
            ->order(array('C_date desc'))
            ->limit('12');
    
    $result = $db->fetchAll($select);
                
    foreach ($result as $row){
        $output[] = array(
            "month" => $row['C_monthName'],
            "year" => $row['C_year'],
            "implant_percent" => $row['percent4'], // implant
            "seven_days_percent" => $row['percent8'], // stock out 7 days
        );
    }
    
    //file_put_contents('c:\wamp\logs\php_debug.log', 'Dashboard-CHAI fetchpfpdetails >'.PHP_EOL, FILE_APPEND | LOCK_EX);	ob_start();
    //var_dump($output,"END");
    //var_dump('id=', $id);
    //$result = ob_get_clean(); file_put_contents('c:\wamp\logs\php_debug.log', $result .PHP_EOL, FILE_APPEND | LOCK_EX);
    
    //return $output;
    return array_reverse($output, true);
    
}	
	
public function fetchPercentProvidingDetails($cnoWhere = null, $geoWhere = null, $dateWhere = null, $group = null, $useName = null) {
    $db = Zend_Db_Table_Abstract::getDefaultAdapter();
    $output = array();
    
    // national
    $create_view = $db->select()
    ->from(array('f' => 'facility'),
        array(
             "count(distinct(frr.facility_external_id)) as denom"))            
            ->joinLeft(array('l1' => "location"), 'f.location_id = l1.id')
            ->joinLeft(array('l2' => "location"), 'l1.parent_id = l2.id')
            ->joinLeft(array('l3' => "location"), 'l2.parent_id = l3.id')
            ->joinInner(array('frr' => "facility_report_rate"), 'f.external_id = frr.facility_external_id');
        
        $sql = $create_view->__toString();
        $sql = str_replace('AS `denom`,', 'AS `denom`', $sql);
        $sql = str_replace('`l1`.*,', '', $sql);
        $sql = str_replace('`l2`.*,', '', $sql);
        $sql = str_replace('`l3`.*,', '', $sql);
        $sql = str_replace('`frr`.*', '', $sql);
        
        try{
            $sql = 'create or replace view pfp_denom_view as ('.$sql.')';
            $db->fetchOne($sql);
        }
        catch (Exception $e) { // normal operation throws "General Error"
            //echo $e->getMessage();
            //file_put_contents('c:\wamp\logs\php_debug.log', 'Dashboard-CHAI >'.PHP_EOL, FILE_APPEND | LOCK_EX);	ob_start();
            //var_dump('ERROR= ', $e->getMessage(), "END");
            //$result = ob_get_clean(); file_put_contents('c:\wamp\logs\php_debug.log', $result .PHP_EOL, FILE_APPEND | LOCK_EX);
        }
        
        $subSelect = new Zend_Db_Expr("( select denom from pfp_denom_view )"); 
        
    	$select = $db->select()
    	->from(array('c' => 'commodity'),
    	   array(
    	       "count(distinct(c.facility_id)) as numer",
    	       "$subSelect  as denom"))
    	       ->joinLeft(array('cno' => "commodity_name_option"), 'c.name_id = cno.id')
    	       ->joinLeft(array('f' => "facility"), 'c.facility_id = f.id')
    	       ->joinLeft(array('l1' => "location"), 'f.location_id = l1.id')
    	       ->joinLeft(array('l2' => "location"), 'l1.parent_id = l2.id')
    	       ->joinLeft(array('l3' => "location"), 'l2.parent_id = l3.id')
    	       ->where($dateWhere)
    	       ->where($cnoWhere);
    	
    	$sql = $select->__toString();
    	$sql = str_replace('AS `denom`,', 'AS `denom`', $sql);
    	$sql = str_replace('`cno`.*,', '', $sql);
    	$sql = str_replace('`f`.*,', '', $sql);
    	$sql = str_replace('`l1`.*,', '', $sql);
    	$sql = str_replace('`l2`.*,', '', $sql);
    	$sql = str_replace('`l3`.*', '', $sql);
    	
    	$result = $db->fetchAll($sql);
    	foreach ($result as $row){
    	    $color = 'black' ;
    	
    	    $output[] = array(
    	       "location" => 'National',
    	       "percent" => $row['numer'] / $row['denom'],
    	       "color" => $color,
    	        );
    	}
    
    
    // geo
    $create_view = $db->select()
    ->from(array('f' => 'facility'),
        array(
            "$group as Location_id", // s.b. l1.id, l2.id, or l3.id
            "count(distinct(frr.facility_external_id)) as denom"))
        ->joinLeft(array('l1' => "location"), 'f.location_id = l1.id')
        ->joinLeft(array('l2' => "location"), 'l1.parent_id = l2.id')
        ->joinLeft(array('l3' => "location"), 'l2.parent_id = l3.id')
        ->joinInner(array('frr' => "facility_report_rate"), 'f.external_id = frr.facility_external_id')
        ->where($geoWhere)
        ->group(array( $group ));
    
    $sql = $create_view->__toString();
    $sql = str_replace('AS `denom`,', 'AS `denom`', $sql);
    $sql = str_replace('`l1`.*,', '', $sql);
    $sql = str_replace('`l2`.*,', '', $sql);
    $sql = str_replace('`l3`.*,', '', $sql);
    $sql = str_replace('`frr`.*', '', $sql);
    
    try{
        $sql = 'create or replace view pfp_denom_view as ('.$sql.')';
        $db->fetchOne($sql);
    }
    catch (Exception $e) { // normal operation throws "General Error"
        //echo $e->getMessage();
        //file_put_contents('c:\wamp\logs\php_debug.log', 'Dashboard-CHAI >'.PHP_EOL, FILE_APPEND | LOCK_EX);	ob_start();
        //var_dump('ERROR= ', $e->getMessage(), "END");
        //$result = ob_get_clean(); file_put_contents('c:\wamp\logs\php_debug.log', $result .PHP_EOL, FILE_APPEND | LOCK_EX);
    }
    
    $subSelect = new Zend_Db_Expr("( select denom from pfp_denom_view where $group = Location_id )"); //corraelated
    
	$select = $db->select()
	->from(array('c' => 'commodity'),
	   array(
	       "$useName as Location_name", // s.b. l1.location_name, l2.lo....
	       "count(distinct(c.facility_id)) as numer",
	       "$subSelect  as denom"))
	       ->joinLeft(array('cno' => "commodity_name_option"), 'c.name_id = cno.id')
	       ->joinLeft(array('f' => "facility"), 'c.facility_id = f.id')
	       ->joinLeft(array('l1' => "location"), 'f.location_id = l1.id')
	       ->joinLeft(array('l2' => "location"), 'l1.parent_id = l2.id')
	       ->joinLeft(array('l3' => "location"), 'l2.parent_id = l3.id')
	       ->where($geoWhere)
	       ->where($dateWhere)
	       ->where($cnoWhere)
	       ->group(array( $group ));
	
	$sql = $select->__toString();
	$sql = str_replace('AS `denom`,', 'AS `denom`', $sql);
	$sql = str_replace('`cno`.*,', '', $sql);
	$sql = str_replace('`f`.*,', '', $sql);
	$sql = str_replace('`l1`.*,', '', $sql);
	$sql = str_replace('`l2`.*,', '', $sql);
	$sql = str_replace('`l3`.*', '', $sql);
	
	$result = $db->fetchAll($sql);
	foreach ($result as $row){
	    $color = 'blue' ;
	
	    $output[] = array(
	       "location" => $row['Location_name'],
	       "percent" => $row['numer'] / $row['denom'],
	       "color" => $color,
	        );
	}
	
	//file_put_contents('c:\wamp\logs\php_debug.log', 'Dashboard-CHAI 243 >'.PHP_EOL, FILE_APPEND | LOCK_EX);	ob_start();
	//var_dump($output,"END");
	//var_dump('id=', $id);
	//$result = ob_get_clean(); file_put_contents('c:\wamp\logs\php_debug.log', $result .PHP_EOL, FILE_APPEND | LOCK_EX);
	
	return $output;
	
}	
		
		/*
		 * TA:17:17: 01/15/2015
		 * get trained persons details
		 DB query to take number of HW trained in “LARC’ in 2014
		
		 select count(distinct person_to_training.person_id) from person_to_training
		 left join training on training.id = person_to_training.training_id
		 where training.training_title_option_id=1 and training.training_end_date like '2014%';
		 */
		public function fetchTPDetails($year, $year_amount) {
		    $db = Zend_Db_Table_Abstract::getDefaultAdapter ();
		    $output = array ();
		
		    for($i = $year_amount; $i > 0; $i--) {
		        $data = array ();
				
		        $select = $db->select ()->from ( array ('person_to_training' => 'person_to_training' ), array ('count(person_to_training.person_id) as count' ) )
		        ->joinLeft ( array ('training' => "training" ), 'training.id = person_to_training.training_id' )
		        ->where ( 'training.training_title_option_id=1' )->where ( "training.training_end_date like '" . $year . "%'" );
		        $result = $db->fetchAll ( $select );
		        $data ['tp_larc'] = $result [0] ['count'];
		
		        $select = $db->select ()->from ( array ('person_to_training' => 'person_to_training' ), array ('count(person_to_training.person_id) as count' ) )
		        ->joinLeft ( array ('training' => "training" ), 'training.id = person_to_training.training_id' )
		        ->where ( 'training.training_title_option_id=2' )->where ( "training.training_end_date like '" . $year . "%'" );
		        $result = $db->fetchAll ( $select );
		        $data ['tp_fp'] = $result [0] ['count'];
		
		        $output [$year] = $data;
		        $year --;
		    }
		    ksort($output);
		    //accamulate data: add previous years to the current year
		    foreach ($output as $i => $value){
		    	$output[$i]['tp_larc'] = $output[$i]['tp_larc'] + $output[$i-1]['tp_larc'];
		    	$output[$i]['tp_fp'] = $output[$i]['tp_fp'] + $output[$i-1]['tp_fp'];
		    }
		    return $output;
		}
		
		
		public function fetchDashboardData($chart = null) {
		    $db = Zend_Db_Table_Abstract::getDefaultAdapter();
		    $output = array();
		    /*
		    select * from dashboard_refresh
		    where chart = 'percent_facilities_hw_trained_larc'
		    and datetime = (select max(datetime) from dashboard_refresh where chart = 'percent_facilities_hw_trained_larc')
		    ;
		    */

		    $bad_chars = array("'", ",");
		    $chart = str_replace($bad_chars, "", $chart);
		    
		    $where = "chart = '$chart' and datetime = (select max(datetime) from dashboard_refresh where chart = '$chart')";
		    $subSelect = new Zend_Db_Expr("(select max(datetime) from dashboard_refresh where chart = $chart)");
		
		    $select = $db->select()
		    ->from(array('dr' => 'dashboard_refresh'),
		        array(
		            'id',
		            'datetime',
		            'chart',
		            'data0','data1','data2','data3','data4','data5','data6','data7','data8','data9',
		        ))
		            ->where($where)
		            ->order(array('id'));
		
		    $result = $db->fetchAll($select);
		    
            switch ($chart) {
                case 'percent_facilities_hw_trained_larc':
                case 'percent_facilities_hw_trained_fp':
                case 'percent_facilities_providing_larc':
                case 'percent_facilities_providing_fp':
                    
                    foreach ($result as $row) {
                        $output[] = array(
                            "state" => $row['data0'],
                            "percentage" => $row['data1'],
                            "color" => $row['data2']
                        );
                    }
                    break;
                    
                  case 'national_consumptionw92UxLIRNTl':
                  case 'national_consumptionH8A8xQ9gJ5b':
                  case 'national_consumptionibHR9NQ0bKL':
                  case 'national_consumptionDiXDJRmPwfh':
                  case 'national_consumptionyJSLjbC9Gnr':
                  case 'national_consumptionvDnxlrIQWUo':
                  case 'national_consumptionkrVqq8Vk5Kw':
                
                    foreach($result as $row){
                        $output[] = array(
                            "location" => $row['data0'],
                            "consumption" => $row['data1'],
                        );
                    }
                    break;
                
                  case 'national_average_monthly_consumptionw92UxLIRNTl':
                  case 'national_average_monthly_consumptionH8A8xQ9gJ5b':
                  case 'national_average_monthly_consumptionibHR9NQ0bKL':
                  case 'national_average_monthly_consumptionDiXDJRmPwfh':
                  case 'national_average_monthly_consumptionyJSLjbC9Gnr':
                  case 'national_average_monthly_consumptionvDnxlrIQWUo':
                  case 'national_average_monthly_consumptionkrVqq8Vk5Kw':
                
                    foreach($result as $row){
                        $output[] = array(
                            "month" => $row['data0'],
                            "consumption" => $row['data1'],
                        );
                    }
                    break;
                
                  case 'national_total_consumptionw92UxLIRNTl':
                  case 'national_total_consumptionH8A8xQ9gJ5b':
                  case 'national_total_consumptionibHR9NQ0bKL':
                  case 'national_total_consumptionDiXDJRmPwfh':
                  case 'national_total_consumptionyJSLjbC9Gnr':
                  case 'national_total_consumptionvDnxlrIQWUo':
                  case 'national_total_consumptionkrVqq8Vk5Kw':
                
                    foreach($result as $row){
                        $output[] = array(
                            "location" => $row['data0'],
                            "consumption" => $row['data1'],
                        );
                    }
                    break;
                    
                case 'average_monthly_consumption':
                
                    foreach ($result as $row) {
                        $output[] = array(
                            "month" => $row['data0'],
                            "injectable_consumption" => $row['data1'],
                            "implant_consumption" => $row['data2']
                        );
                    }
                    break;
                    
                case 'national_consumption_by_method':
                    
                     foreach ($result as $row) {
                         $output[] = array(
                             "method" => $row['data0'],
                             "consumption" => $row['data1']
                         );
                     }
                     break;
                     
                case 'national_percent_facilities_providing':
                
                    foreach ($result as $row) {
                        $output[] = array(
                            "month" => $row['data0'],
                            "year" => $row['data1'],
                            "fp_percent" => $row['data2'],
                            "larc_percent" => $row['data3']
                        );
                    }
                    break;
                    
                case 'national_percent_facilities_stock_out':
                
                    foreach ($result as $row) {
                        $output[] = array(
                            "month" => $row['data0'],
                            "year" => $row['data1'],
                            "implant_percent" => $row['data2'],
                            "seven_days_percent" => $row['data3']
                        );
                    }
                    break;
                    
                case 'national_average_monthly_consumption_all':
                
                    foreach ($result as $row) {
                        $output[] = array(
                            "month" => $row['data0'],
                            "consumption1" => $row['data1'],
                            "consumption2" => $row['data2'],
                            "consumption3" => $row['data3'],
                            "consumption4" => $row['data4'],
                            "consumption5" => $row['data5'],
                            "consumption6" => $row['data6'],
                            "consumption7" => $row['data7'],
                            
                        );
                    }
                    break;
                case 'national_coverage_summary':
                   
                    if (count($result == 1)) {
                            $output["last_date"] =                       $result[0]['data0'];
                            $output["total_facility_count"] =            $result[0]['data1'];
                            $output["total_facility_count_month"] =      $result[0]['data2'];
                            $output["larc_facility_count"] =             $result[0]['data3'];
                            $output["fp_facility_count"] =               $result[0]['data4'];
                            $output["larc_consumption_facility_count"] = $result[0]['data5'];
                            $output["fp_consumption_facility_count"] =   $result[0]['data6'];
                            $output["larc_stock_out_facility_count"] =   $result[0]['data7'];
                            $output["fp_stock_out_facility_count"] =     $result[0]['data8'];
                   }
                   break;
                case 'PercentFacHWTrainedStockOutLarc':
                case 'PercentFacHWTrainedStockOutFP':
                     
                    foreach ($result as $row) {
                        $output[] = array(
                            "location" => $row['data0'],
                            "percent" => $row['data1'],
                            "color" => $row['data2'],
                            
                        );
                    }
                    break; 
                 case 'PercentFacHWProvidingStockOutLarc':
                 case 'PercentFacHWProvidingStockOutFP':
                      
                     foreach ($result as $row) {
                         $output[] = array(
                             "location" => $row['data0'],
                             "percent" => $row['data1'],
                             "color" => $row['data2'],
                 
                         );
                     }
                     break;
                  case 'PercentFacHWTrainedProvidingLarc':
                  case 'PercentFacHWTrainedProvidingFP':
                  
                      foreach ($result as $row) {
                          $output[] = array(
                              "location" => $row['data0'],
                              "percent" => $row['data1'],
                              "color" => $row['data2'],
                               
                          );
                      }
                      break;
                  
            }
            
            
		    //file_put_contents('c:\wamp\logs\php_debug.log', 'Dashboard-CHAI 243 >'.PHP_EOL, FILE_APPEND | LOCK_EX);	ob_start();
		    //var_dump($output,"END");
		    //var_dump('id=', $id);
		    //$result = ob_get_clean(); file_put_contents('c:\wamp\logs\php_debug.log', $result .PHP_EOL, FILE_APPEND | LOCK_EX);
		
		    return $output;
		}
	
		public function insertDashboardData($details, $chart) {
		    //current_datetime = now()
		    //INSERT INTO `itechweb_chainigeria`.`dashboard_refresh`
		    //( `datetime`, `chart`, `data0`, `data1`, `data2`)
		    //VALUES (current_datetime, 'percent_facilities_hw_trained_larc', 'Plateau', '0.0025', 'red');
		    
		    /*save
		    $sfm = new ITechTable(array('name' => 'subpartner_to_funder_to_mechanism'));
		     
		    $data = array(
		        'subpartner_id'  => $params['subPartner'],
		        'partner_funder_option_id' => $params['partnerFunder'],
		        'mechanism_option_id' => $params['mechanism'],
		        'funding_end_date' => $params['funding_end_date'][0],
		    );
		    
		    $insert_result = $sfm->insert($data);
		    */
		    
		    $bad_chars = array("'", ",");
		    $chart = str_replace($bad_chars, "", $chart);
		    
		    $dateTime = date("Y-m-d H:i:s");
		    $dashboard_refresh = new ITechTable(array('name' => 'dashboard_refresh'));
		    
		    switch ($chart) {
		        case 'percent_facilities_hw_trained_larc':
		        case 'percent_facilities_hw_trained_fp':
		        case 'percent_facilities_providing_larc':
		        case 'percent_facilities_providing_fp':
		  		    
        		    foreach($details as $row){
        		        $data = array(
        		            'datetime'  => $dateTime,
        		            'chart'  => $chart,
        		            'data0'  => $row['state'],
        		            'data1'  => $row['percentage'],
        		            'data2'  => $row['color'],
        		        );
        		        
        		        $insert_result = $dashboard_refresh->insert($data);
        		    }
		          break;
		          
                  case 'national_consumptionw92UxLIRNTl':
                  case 'national_consumptionH8A8xQ9gJ5b':
                  case 'national_consumptionibHR9NQ0bKL':
                  case 'national_consumptionDiXDJRmPwfh':
                  case 'national_consumptionyJSLjbC9Gnr':
                  case 'national_consumptionvDnxlrIQWUo':
                  case 'national_consumptionkrVqq8Vk5Kw':
		              
		              foreach($details as $row){
		                  $data = array(
		                      'datetime'  => $dateTime,
		                      'chart'  => $chart,
		                      'data0'  => $row['location'],
		                      'data1'  => $row['consumption'],
		                  );
		          
		                  $insert_result = $dashboard_refresh->insert($data);
		              }
		              break;
		              
                  case 'national_average_monthly_consumptionw92UxLIRNTl':
                  case 'national_average_monthly_consumptionH8A8xQ9gJ5b':
                  case 'national_average_monthly_consumptionibHR9NQ0bKL':
                  case 'national_average_monthly_consumptionDiXDJRmPwfh':
                  case 'national_average_monthly_consumptionyJSLjbC9Gnr':
                  case 'national_average_monthly_consumptionvDnxlrIQWUo':
                  case 'national_average_monthly_consumptionkrVqq8Vk5Kw':
		          
		              foreach($details as $row){
		                  $data = array(
		                      'datetime'  => $dateTime,
		                      'chart'  => $chart,
		                      'data0'  => $row['month'],
		                      'data1'  => $row['consumption'],
		                  );
		          
		                  $insert_result = $dashboard_refresh->insert($data);
		              }
		              break;
		              
                  case 'national_total_consumptionw92UxLIRNTl':
                  case 'national_total_consumptionH8A8xQ9gJ5b':
                  case 'national_total_consumptionibHR9NQ0bKL':
                  case 'national_total_consumptionDiXDJRmPwfh':
                  case 'national_total_consumptionyJSLjbC9Gnr':
                  case 'national_total_consumptionvDnxlrIQWUo':
                  case 'national_total_consumptionkrVqq8Vk5Kw':
		          
		              foreach($details as $row){
		                  $data = array(
		                      'datetime'  => $dateTime,
		                      'chart'  => $chart,
		                      'data0'  => $row['location'],
		                      'data1'  => $row['consumption'],
		                  );
		          
		                  $insert_result = $dashboard_refresh->insert($data);
		              }
		              break;
		          
		        case 'average_monthly_consumption':
		            
		            foreach($details as $row){
		                $data = array(
		                    'datetime'  => $dateTime,
		                    'chart'  => $chart,
		                    'data0'  => $row['month'],
		                    'data1'  => $row['injectable_consumption'],
		                    'data2'  => $row['implant_consumption'],
		                );
		            
		                $insert_result = $dashboard_refresh->insert($data);
		            }
		            break;
		            
		        case 'national_consumption_by_method':
		        
		            foreach($details as $row){
		                $data = array(
		                    'datetime'  => $dateTime,
		                    'chart'  => $chart,
		                    'data0'  => $row['method'],
		                    'data1'  => $row['consumption'],
		                );
		        
		                $insert_result = $dashboard_refresh->insert($data);
		            }
		            break;
		            
		        case 'national_percent_facilities_providing':
		        
		            foreach($details as $row){
		                $data = array(
		                    'datetime'  => $dateTime,
		                    'chart'  => $chart,
		                    'data0'  => $row['month'],
		                    'data1'  => $row['year'],
		                    'data2'  => $row['fp_percent'],
		                    'data3'  => $row['larc_percent'],
		                );
		        
		                $insert_result = $dashboard_refresh->insert($data);
		            }
		            break;
		        case 'national_percent_facilities_stock_out':
		        
		            foreach($details as $row){
		                $data = array(
		                    'datetime'  => $dateTime,
		                    'chart'  => $chart,
		                    'data0'  => $row['month'],
		                    'data1'  => $row['year'],
		                    'data2'  => $row['implant_percent'],
		                    'data3'  => $row['seven_days_percent'],
		                );
		            
		                $insert_result = $dashboard_refresh->insert($data);
		            }
		            break;
		            
		        case 'national_average_monthly_consumption_all':
		        
		            foreach($details as $row){
		                $data = array(
		                    'datetime'  => $dateTime,
		                    'chart'  => $chart,
		                    'data0'  => $row['month'],
		                    'data1'  => $row['consumption1'],
		                    'data2'  => $row['consumption2'],
		                    'data3'  => $row['consumption3'],
		                    'data4'  => $row['consumption4'],
		                    'data5'  => $row['consumption5'],
		                    'data6'  => $row['consumption6'],
		                    'data7'  => $row['consumption7'],
		                    
		                );
		        
		                $insert_result = $dashboard_refresh->insert($data);
		            }
		            break;
		            
		        case 'national_coverage_summary':
		        
		                $data = array(
		                    'datetime'  => $dateTime,
		                    'chart'  => $chart,
		                    'data0'  => $details['last_date'],
		                    'data1'  => $details['total_facility_count'],
		                    'data2'  => $details['total_facility_count_month'],
		                    'data3'  => $details['larc_facility_count'],
		                    'data4'  => $details['fp_facility_count'],
		                    'data5'  => $details['larc_consumption_facility_count'],
		                    'data6'  => $details['fp_consumption_facility_count'],
		                    'data7'  => $details['larc_stock_out_facility_count'],
		                    'data8'  => $details['fp_stock_out_facility_count'],
		                );
		        
		                $insert_result = $dashboard_refresh->insert($data);
		            break;
		            
		        case 'PercentFacHWTrainedStockOutLarc':
		        case 'PercentFacHWTrainedStockOutFP':
		        
		            foreach($details as $row){
		                $data = array(
		                    'datetime'  => $dateTime,
		                    'chart'  => $chart,
		                    'data0'  => $row['location'],
		                    'data1'  => $row['percent'],
		                    'data2'  => $row['color'],
		        
		                );
		        
		                $insert_result = $dashboard_refresh->insert($data);
		            }
		            break;
		            
		         case 'PercentFacHWProvidingStockOutLarc':
		         case 'PercentFacHWProvidingStockOutFP':
		         
		             foreach($details as $row){
		                 $data = array(
		                     'datetime'  => $dateTime,
		                     'chart'  => $chart,
		                     'data0'  => $row['location'],
		                     'data1'  => $row['percent'],
		                     'data2'  => $row['color'],
		         
		                 );
		         
		                 $insert_result = $dashboard_refresh->insert($data);
		             }
		             break;
		             
		          case 'PercentFacHWTrainedProvidingLarc':
		          case 'PercentFacHWTrainedProvidingFP':
		               
		              foreach($details as $row){
		                  $data = array(
		                      'datetime'  => $dateTime,
		                      'chart'  => $chart,
		                      'data0'  => $row['location'],
		                      'data1'  => $row['percent'],
		                      'data2'  => $row['color'],
		                       
		                  );
		                   
		                  $insert_result = $dashboard_refresh->insert($data);
		              }
		              break;

		    }
		    
		    //file_put_contents('c:\wamp\logs\php_debug.log', 'Dashboard-CHAI 697>'.PHP_EOL, FILE_APPEND | LOCK_EX);	ob_start();
		    //var_dump('id=', $id);
		    //var_dump('dateTime=', $dateTime);
		    //$result = ob_get_clean(); file_put_contents('c:\wamp\logs\php_debug.log', $result .PHP_EOL, FILE_APPEND | LOCK_EX);
		    
		}



public function fetchCMDetails($where = null, $group = null, $useName = null) {

    $db = Zend_Db_Table_Abstract::getDefaultAdapter();
    $output = array();


    //file_put_contents('c:\wamp\logs\php_debug.log', 'Dashboard-CHAI 171>'.PHP_EOL, FILE_APPEND | LOCK_EX);ob_start();
    //var_dump('all=', $where, $group, $useName, "END");
    //$toss = ob_get_clean(); file_put_contents('c:\wamp\logs\php_debug.log', $toss .PHP_EOL, FILE_APPEND | LOCK_EX);

    $create_view = $db->select()
    ->from(array('f' => 'facility'),
        array(
            'f.id as F_id',
            'f.facility_name as F_facility_name',
            //"replace(f.facility_name, '\'', '\\\'') as F_facility_name',",
            'f.location_id as F_location_id',
            'l1.id as L1_id',
            'l1.location_name as L1_location_name',
            'l2.id as L2_id',
            'l2.location_name as L2_location_name',
            'l2.parent_id as L2_parent_id',
            'l3.location_name as L3_location_name',
            'cno.id as CNO_id',
            'cno.commodity_name as CNO_commodity_name',
            'c.date as C_date',
            'ifnull(sum(c.consumption),0) as C_consumption' ))
         	->joinLeft(array('l1' => "location"), 'f.location_id = l1.id')
    	    ->joinLeft(array('l2' => "location"), 'l1.parent_id = l2.id')
    	    ->joinLeft(array('l3' => "location"), 'l2.parent_id = l3.id')
    	    ->joinLeft(array("c" => "commodity"), 'f.id = c.facility_id')
    	    ->joinLeft(array("cno" => "commodity_name_option"), 'c.name_id = cno.id')
    	    ->joinInner(array('mc' => 'lc_view_subselect'), 'f.id = mc.facility_id and month(c.date) = C_monthDate')
    	    ->where($where)
    	    ->group(array($group))
    	    ->order(array('CNO_id'));
    
    $sql = $create_view->__toString();
    $sql = str_replace('`C_consumption`,', '`C_consumption`', $sql);
    $sql = str_replace('`l1`.*,', '', $sql);
    $sql = str_replace('`l2`.*,', '', $sql);
    $sql = str_replace('`l3`.*,', '', $sql);
    $sql = str_replace('`c`.*,', '', $sql);
    $sql = str_replace('`cno`.*,', '', $sql);
    $sql = str_replace('`mc`.*', '', $sql);
    
    try{
        $sql='create or replace view lc_view as ('.$sql.')';
        $db->fetchOne( $sql );
    }
    catch (Exception $e) {
        //echo $e->getMessage();
        //var_dump('error', $e->getMessage());
        
    }

    $select = $db->select()
    ->from(array('cv' => 'lc_view'),
        array(
            'CNO_commodity_name',
            'ifnull(C_consumption,0) as C_consumption' ))
    	->order(array('CNO_commodity_name'));
    
    $result = $db->fetchAll($select);
    
    foreach ($result as $row){
    
        $output[] = array(
            "CNO_commodity_name" => $row['CNO_commodity_name'],
            "C_consumption" => $row['C_consumption'],
        );
    }
    
    return $output;
}

}


?>
