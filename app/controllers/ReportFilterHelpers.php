<?php

require_once ('app/controllers/ITechController.php');

class ReportFilterHelpers extends ITechController {

	public function __construct($request, $response, $invokeArgs = array()) {
		parent::__construct ( $request, $response, $invokeArgs = array () );

	}

	/**
	 * Get the location filter values
	 *
	 * @param array $criteria = array() - extra criteria to pass through, matching keys get overwritten
	 * @param string $prefix = '' - prefix of html element parameter names
	 *
     * @return array - $criteria(with city, city_parent_id), location tier, location id
	 */
    protected function getLocationCriteriaValues($criteria = array(), $prefix = '') {
        $params = $this->getAllParams();
        require_once('views/helpers/Location.php');

        return getCriteriaValues($params, $this->_countrySettings, $criteria, $prefix);
    }

	// helper to generate a where clause based on 9 available levels of regions in $criteria
	// return value: " `$tablePrefix`.region_X_id IN (val, val, val) ";
	protected function getLocationCriteriaWhereClause(&$criteria, $prefix = '', $tablePrefix = '') {

		$tableCols = array('', 'province_id', 'district_id', 'region_c_id','region_d_id','region_e_id','region_f_id','region_g_id','region_h_id','region_i_id');
		list($crit, $tier, $location_id) = $this->getLocationCriteriaValues($criteria, $prefix, $tablePrefix);

		if ($prefix)
			$prefix = $prefix . "_";

		$tableString = $tablePrefix ? "$tablePrefix." : '';

		if ($location_id)
			$location_id = $this->_array_me($location_id); // sometimes $location_id is a string because it comes from a form input[]

		if ($tier && !empty ( $location_id ))
			return " " . $tableString . $tableCols[$tier] . " IN ( " . implode(',', $location_id) . " ) ";

		return false;
	}

	//_is_filter_all
	// is string (pass) or array with out "" (pass)
	// "" is used for --ALL-- in drop downs, now we are supporting multi-value <selects>
	protected function _is_filter_all($arrOrStr)
	{
		if (is_array($arrOrStr)) {
			if (!in_array("", $arrOrStr))
				return false;
			else
				return true;
		}else if ($arrOrStr || $arrOrStr === '0') {  //not really used, should be safe though
			return false;
		}else if ($arrOrStr == "") {
			return true;
		}
		return false;
	}

	protected function _is_not_filter_all($arrOrStr)
	{
		return (! $this->_is_filter_all($arrOrStr));
	}
}

?>