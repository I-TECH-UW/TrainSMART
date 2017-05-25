<?php
/*
 * Created on Feb 14, 2008
 *
 *  Built for web
 *  ALL HAIL ANGRY CAT
 *
 */

require_once ('ITechTable.php');

class Employee extends ITechTable {
	protected $_name = 'employee';
	protected $_primary = 'id';

    /**
     * deletes mechanism associations from an employee
     * @param $employee_id     - employee id
     * @param $association_ids - the ids to remove
     * @return bool
     */
    public static function disassociateMechanismsFromEmployee($employee_id, $mechanism_ids)
	{
	    if ((!$mechanism_ids) || (!$employee_id) ||
            (!preg_match('/^\d+[,\d+]*$/', $mechanism_ids))) {
            return false;
        }
	    
	    $table = new ITechTable ( array ('name' => 'link_mechanism_employee' ) );
	    $rv = false;
	    try {
	        $rv = $table->delete("mechanism_option_id IN ($mechanism_ids) AND employee_id = $employee_id");
	    } catch(Exception $e) {
	        error_log($e);
	        return false;
	    }
	    return $rv;
	     
	}

    /**
     * associate mechanisms and percentages with an employee
     * @param int    $employee_id           - employee id
     * @param string $mechanism_ids         - comma-delimited string of ids to associate with the employee
     * @param string $mechanism_percentages - comma-delimited string of percentages to associate with each id in $mechanism_ids
     * @return bool
     */
	public static function saveMechanismAssociations ( $employee_id, $mechanism_ids, $mechanism_percentages)
	{

        $ids = explode(',', $mechanism_ids);
        $percentages = explode(',', $mechanism_percentages);

        if (count($ids) != count($percentages)) {
            return false;
        }

        $linkTable = new ITechTable ( array ('name' => 'link_mechanism_employee' ) );
	    foreach($ids as $i => $mechID) {
	        try {
                $row = $linkTable->createRow(array('employee_id' => $employee_id, 'mechanism_option_id' => $ids[$i], 'percentage' => $percentages[$i]));
                $row->save();

	        } catch(Exception $e) {
	            error_log($e);
	            return false;
	        }
	    }
	    return true;
	}
	
	//TA:#329
	public static function getAllEmployeeQualifications(){
	    $linkTable = new ITechTable ( array ('name' => 'employee_qualification_option' ));
	    $select = $linkTable->select()->order('qualification_phrase');
	    return $linkTable->fetchAll($select)->toArray();
	}
	
//TA:#224 TA:#416
	public static function getEmployeeSites($employee_id){
	    $tableObj = new Employee();
	    $db = $tableObj->dbfunc();
	    $query = "SELECT link_employee_facility.id as site_link_id, link_employee_facility.facility_id, facility.facility_name, 
facility.type_option_id, facility_type_option.facility_type_phrase, 
employee_dsdmodel_option.id as dsd_model_id, employee_dsdmodel_option.employee_dsdmodel_phrase as sds_model_name, 
employee_dsdteam_option.id as dsd_team_id,employee_dsdteam_option.employee_dsdteam_phrase as sds_team_name,
link_employee_facility.hiv_fte_related, 
 facility.location_id 
FROM link_employee_facility 
LEFT JOIN facility ON link_employee_facility.facility_id = facility.id
LEFT join  facility_type_option on facility_type_option.id=facility.type_option_id
LEFT join employee_dsdmodel_option on employee_dsdmodel_option.id=link_employee_facility.dsd_model_id
LEFT join employee_dsdteam_option on employee_dsdteam_option.id=link_employee_facility.dsd_team_id
WHERE (employee_id = $employee_id) order by link_employee_facility.id"; //#387
	    $select = $db->query($query);
	    return $select->fetchAll();
	}
	
	//TA:#224
	public static function removeSites($employee_id, $ids){
	    if($ids and $ids !== ''){
	$table = new ITechTable ( array ('name' => 'link_employee_facility' ) );
	    try {
		    $table->delete("employee_id=$employee_id and id in ($ids)");
		    } catch(Exception $e) {
		    error_log($e);
		        return false;
		    }
	    }
	    return true;
	}
	
//TA:#224, TA:#416
	public static function saveSites ( $employee_id, $site_id, $dsd_model_id, $dsd_team_id, $hiv_related_fte){
	Employee::removeSites($employee_id);
	$linkTable = new ITechTable ( array ('name' => 'link_employee_facility' ) );
	    try {
	    $row = $linkTable->createRow(array('employee_id' => $employee_id, 'facility_id' => $site_id, 'dsd_model_id' => $dsd_model_id, 'dsd_team_id' => $dsd_team_id, 'hiv_fte_related' => $hiv_related_fte));
	        $row->save();
	    } catch(Exception $e) {
	    error_log($e);
	    return false;
	    }
	    return true;
	    }
}
