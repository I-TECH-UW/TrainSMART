<?php

require_once ('app/controllers/ITechController.php');

class ReportFilterHelpers extends ITechController {

	public function __construct(Zend_Controller_Request_Abstract $request, Zend_Controller_Response_Abstract $response, array $invokeArgs = array()) {
		parent::__construct ( $request, $response, $invokeArgs = array () );

	}

	/**
	 * Get the location filter values
	 *
	 * @param unknown_type $criteria
	 * @param unknown_type $prefix
	 *
	 * return $criteria, location tier, location id, city, and city_parent_id
	 */
	protected function getLocationCriteriaValues($criteria = array(), $prefix = '') {
	if ( $prefix != '' ) $prefix .= '_';

		$criteria [$prefix.'city'] = $this->getSanParam ( $prefix.'city' ); // set city
		$rgns = array('province_id', 'district_id','region_c_id','region_d_id','region_e_id','region_f_id','region_g_id','region_h_id','region_i_id');
		// get value from each region sent by form
		foreach($rgns as $rgn_name) {
			$tmp = $this->getSanParam($prefix.$rgn_name);
			if (is_array ( $tmp ) ) {
				if ( $tmp [0] === "") { // "All"
					$criteria [$prefix.$rgn_name] = array ();
			} else {
					foreach($tmp as $key => $val) {
						if (strstr ( $val, '_' ) !== false) {
							$parts = explode ( '_', $val );
							#$tmp [$key] = $parts [count($parts)-1];
							$tmp [$key] = array_pop($parts);
						}
					}
					$criteria [$prefix.$rgn_name] = $tmp;
				}
			} else {
				if (strstr ( $tmp, '_' ) !== false) {
					$parts = explode ( '_', $tmp );
					$tmp = array_pop($parts);
				}
				$criteria [$prefix.$rgn_name] = $tmp;
			}
		}

		$city_parent_id = 0; // todo: small bug here, on receiving array input for region_ids, city_parent_id returns an array of ids, possibly even wrong ids -- probably ok - its not used in reports anyway...
		if ( $this->setting ( 'display_region_i' ) ) {
			$city_parent_id = $criteria[$prefix.'region_i_id'];
		} else if ( $this->setting ( 'display_region_h' ) ) {
			$city_parent_id = $criteria[$prefix.'region_h_id'];
		} else if ( $this->setting ( 'display_region_g' ) ) {
			$city_parent_id = $criteria[$prefix.'region_g_id'];
		} else if ( $this->setting ( 'display_region_f' ) ) {
			$city_parent_id = $criteria[$prefix.'region_f_id'];
		} else if ( $this->setting ( 'display_region_e' ) ) {
			$city_parent_id = $criteria[$prefix.'region_e_id'];
		} else if ( $this->setting ( 'display_region_d' ) ) {
			$city_parent_id = $criteria[$prefix.'region_d_id'];
		} else if ( $this->setting ( 'display_region_c' ) ) {
			$city_parent_id = $criteria[$prefix.'region_c_id'];
    } else if ( $this->setting ( 'display_region_b' ) ) {
			$city_parent_id = $criteria[$prefix.'region_b_id'];
    } else {
			$city_parent_id = $criteria['_id'];
    }
    $criteria [$prefix.'city_parent_id'] = $city_parent_id;


    $location_tier = 1;
    $location_id = $criteria [$prefix.'province_id'];
    if ( $criteria [$prefix.'district_id'] ) {
      $location_id = $criteria [$prefix.'district_id'];
      $location_tier = 2;
    }
    if ( $criteria [$prefix.'region_c_id'] ) {
      $location_id = $criteria [$prefix.'region_c_id'];
      $location_tier = 3;
    }
		if ( $criteria [$prefix.'region_d_id'] ) {
			$location_id = $criteria [$prefix.'region_d_id'];
			$location_tier = 4;
		}
		if ( $criteria [$prefix.'region_e_id'] ) {
			$location_id = $criteria [$prefix.'region_e_id'];
			$location_tier = 5;
		}
		if ( $criteria [$prefix.'region_f_id'] ) {
			$location_id = $criteria [$prefix.'region_f_id'];
			$location_tier = 6;
		}
		if ( $criteria [$prefix.'region_g_id'] ) {
			$location_id = $criteria [$prefix.'region_g_id'];
			$location_tier = 7;
		}
		if ( $criteria [$prefix.'region_h_id'] ) {
			$location_id = $criteria [$prefix.'region_h_id'];
			$location_tier = 8;
		}
		if ( $criteria [$prefix.'region_i_id'] ) {
			$location_id = $criteria [$prefix.'region_i_id'];
			$location_tier = 9;
		}

    return array($criteria, $location_tier, $location_id);
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