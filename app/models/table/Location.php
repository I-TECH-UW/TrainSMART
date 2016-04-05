<?php
/*
 * Created on Sept 14, 2010
 *
 *  Built for web
 *  Fuse IQ -- todd@fuseiq.com
 *
 */


class Location extends ITechTable
{
	protected $_primary = 'id';
	protected $_name = 'location';

	// cache for lookups and processed results.
	static protected $_locationDataStructure = null;
	static protected $_locationHierarchy = null;
	static protected $_locations = null;
	static protected $_locationsByTier = null;
	static protected $_numLocationTiers = null;

    /**
     * returns an array of all non-deleted locations
     *
     * @return array|null
     */
    public static function getAll() {

	    if (self::$_locations) return self::$_locations;

	    $tableObj = new Location();

	    $select = $tableObj->select()
		    ->from(array('l' => 'location'))->where('is_deleted = 0')->order('location_name');

	    $output = array();
	    try {
		    $rows = $tableObj->fetchAll($select);
		    // reindex with id
		    $indexed = array();
		    while ($rows->current()) {
			    $indexed [$rows->current()->id] = $rows->current()->toArray();
			    $rows->next();
		    }

		    $num_tiers = 1;
		    foreach ($indexed as $row) {

			    // check that the hierarchy works
			    // if the parent is more than one tier higher, then no good unless the middle region is off
			    $is_good = true;
			    $parent_tier = (!$row['parent_id'] ? 0 : $indexed[$row['parent_id']]['tier']);
			    if ($row['tier'] > 1 && !$parent_tier) {
				    $is_good = false;
			    } else if ((($parent_tier + 1) != $row['tier'])) {
				    $is_good = false;
			    }

			    $output[$row['id']] = array('id' => $row['id'],
				    'uuid'       => $row['uuid'],
				    'name'       => $row['location_name'],
				    'parent_id'  => ($row['parent_id'] ? $row['parent_id'] : 0),
				    'tier'       => $row['tier'],
				    'is_default' => $row['is_default'],
				    'is_good'    => $is_good
			    );
			    if ($row['tier'] > $num_tiers) {
				    $num_tiers = $row['tier'];
			    }
		    }

		    // check for null parents and add 'unknown' option
		    $has_parents = array();
		    for ($t = 2; $t <= $num_tiers; $t++) {
			    $has_parents [$t] = true;
		    }
		    for ($t = 2; $t <= $num_tiers; $t++) {
			    foreach ($output as $l) {
				    if (!$l['parent_id']) {
					    $has_parents[$t] = false;
				    }
			    }
		    }

		    self::$_locations = $output;
		    return self::$_locations;

	    } catch (Zend_Exception $e) {
		    error_log($e);
	    }

	    return null;
    }

	/**
	 * resets the location caches so that location information will be updated from the database in the event
	 * of changes to the data in the location table.
	 */
	public static function flushCachedLocations() {
		self::$_locationDataStructure = null;
		self::$_locationHierarchy = null;
		self::$_locations = null;
		self::$_locationsByTier = null;
		self::$_numLocationTiers = null;
	}

	/**
	 * gets an array of locations with valid parent locations ordered by hierarchical location tier
	 * also populates the cache variable of the same data
	 *
	 * @return array|null
	 * @throws Zend_Db_Select_Exception
	 */
	public static function getValidLocationsByTier() {
		if (self::$_locationsByTier) {
			return self::$_locationsByTier;
		}

		$tableObj = new Location();

		$provinceSelect = $tableObj->select()
			->from(array('location'))
			->where('location.tier = 1')
			->where('location.parent_id IS NULL')
			->where('is_deleted = 0')
		;

		$subRegionsSelect = $tableObj->select()
			->from(array('location'))
			->join(array('parent_location' => 'location'), 'location.parent_id = parent_location.id', array())
			->where('location.tier > 0')
			->where('parent_location.tier = location.tier - 1')
			->where('location.is_deleted = 0')
			->where('parent_location.is_deleted = 0')
		;

		$locationSelect = $tableObj->select()
			->union(array($provinceSelect, $subRegionsSelect))
			->order('tier')
			->order('location_name');

		try {
			self::$_locationsByTier  = $tableObj->fetchAll($locationSelect)->toArray();
			return self::$_locationsByTier;
		} catch (Zend_Exception $e) {
			error_log($e);
		}

		return null;
	}

	/**
	 * get the number of user-activated location tiers, plus the top tier and the bottom "city" tier
	 * 
	 * @return int
	 */
	public static function getNumLocationTiers() {
		if (self::$_numLocationTiers) {
			return self::$_numLocationTiers;
		}

		$q = 'select 
				case when display_region_b = 1 then 1 else 0 end +
				case when display_region_c = 1 then 1 else 0 end +
				case when display_region_d = 1 then 1 else 0 end +
				case when display_region_e = 1 then 1 else 0 end +
				case when display_region_f = 1 then 1 else 0 end +
				case when display_region_g = 1 then 1 else 0 end +
				case when display_region_h = 1 then 1 else 0 end +
				case when display_region_i = 1 then 1 else 0 end +
				2 as num_location_tiers from _system';

		$db = Zend_Db_Table_Abstract::getDefaultAdapter ();
		self::$_numLocationTiers = $db->fetchOne($q);
		return self::$_numLocationTiers;
	}

	/**
	 * processes locations into an array of arrays by tier for easier front-end use
	 * 
	 * @return array|null
	 */
	public static function getLocationDataStructure() {
		if (self::$_locationDataStructure) {
			return self::$_locationDataStructure;
		}

		if (!self::$_locationsByTier) {
			self::getValidLocationsByTier();
		}

		self::$_locationDataStructure = array();

		$ds = &self::$_locationDataStructure;
		$locations = &self::$_locationsByTier;
		foreach ($locations as $location) {
			$t = $location['tier'];
			if (!array_key_exists($t, $ds)) {
				$ds[$t] = array();
			}
			$ds[$t][] = $location;
		}
		return self::$_locationDataStructure;
	}

	/**
	 * get a tree of all validly linked locations
	 *
	 * @return null
	 */
	public static function getLocationHierarchy() {
		if (self::$_locationHierarchy) {
			return self::$_locationHierarchy;
		}

		if (!self::$_locationsByTier) {
			self::getValidLocationsByTier();
		}

		$locs = &self::$_locationsByTier;
		self::$_locationHierarchy = array();
		$h = &self::$_locationHierarchy;

		// make a temporary lookup array to link children with parents
		$temp = array();
		foreach ($locs as $loc) {

			$temp[$loc['id']] = array('data' => $loc, 'children' => array());
			if ($loc['parent_id']) {
				$temp[$loc['parent_id']]['children'][$loc['id']] = &$temp[$loc['id']];
			}
		}

		// grab the top tier out of the lookup array, children are linked in appropriately
		// locations are sorted by tier and this logic depends on it
		foreach ($locs as $loc) {
			if ($loc['tier'] > 1) {
				break;
			}
			$h[$loc['id']] = &$temp[$loc['id']];
		}

		return self::$_locationHierarchy;
	}

	/**
	 * gets an array of all of the locations that contain data with missing parents or invalid tiers
	 * 
	 * @return array|null
	 */
	public static function getInvalidLocations() {
		$db = Zend_Db_Table_Abstract::getDefaultAdapter ();

		$q =
			'SELECT
				`location`.*
			FROM
				`location`
			INNER JOIN `location` AS `parent_location` ON location.parent_id = parent_location.id
			WHERE
				(location.tier > 0)
			AND (
				parent_location.tier != location.tier - 1
			)
			AND (location.is_deleted = 0)
			UNION
				SELECT
					*
				FROM
					location
				WHERE
					parent_id IS NOT NULL
				AND tier < 1
				AND (location.is_deleted = 0)
			UNION
				SELECT
					*
				FROM
					location
				WHERE
					tier > 1
				AND parent_id IS NULL
				AND (location.is_deleted = 0)
			UNION
				SELECT
					*
				FROM
					location
				WHERE
					parent_id NOT IN (SELECT id FROM location)
				AND (location.is_deleted = 0)
			UNION
				SELECT
					*
				FROM
					location
				WHERE
					tier > ?
				AND (location.is_deleted = 0)
			ORDER BY
				`tier` ASC,
				`location_name` ASC';
		try {
			return $db->fetchAll($q, self::getNumLocationTiers());
		} catch (Zend_Exception $e) {
			error_log($e);
		}

		return null;
	}

	public static function moveLocation($location_id, $new_parent) {
		//caller should make sure it's in the correct tier
		$db = Zend_Db_Table_Abstract::getDefaultAdapter ();

		$sql = "UPDATE location
		SET parent_id = ".$new_parent." WHERE id = ".$location_id;
		$db->query($sql);

		//clear the locations cache
		self::$_locations = null;
	}

	public static function addTier($tier) {
		$db = Zend_Db_Table_Abstract::getDefaultAdapter ();

		error_log('add tier = '.$tier);

		#make 3s into 4s
		$sql = "UPDATE location
		SET tier = tier + 1
		WHERE tier >= $tier";
		$db->query($sql);

		#insert 'default' 3
		if ( $tier == 1) {
			$sql = "INSERT location (location_name, parent_id, tier)
			VALUES('default', NULL , 1)";
		} else {
			$sql = "INSERT location (location_name, parent_id, tier)
			SELECT 'default', id , ".($tier)." FROM location
			WHERE tier = ".($tier-1);
		}
		$db->query($sql);

		#update 4s parents to the new 'default' 3s
		if ( $tier == 1) {
			$sql = "UPDATE location l, location pl
			SET l.parent_id = pl.id
			WHERE l.tier = 2 AND pl.location_name = 'default' AND pl.tier = 1";

		} else {
			$sql = "UPDATE location l, location pl
			SET l.parent_id = pl.id
			WHERE l.tier = ".($tier+1)." AND l.parent_id = pl.parent_id AND pl.tier = ".$tier;
		}
		$db->query($sql);

		//update referencing tables to new parents
		$sql = "UPDATE facility f, location l, location dl
		SET f.location_id = dl.id
		WHERE f.location_id = l.id AND l.tier < $tier AND dl.parent_id = l.id";
		$db->query($sql);

		$sql = "UPDATE training_location f, location l, location dl
		SET f.location_id = dl.id
		WHERE f.location_id = l.id AND l.tier < $tier AND dl.parent_id = l.id";
		$db->query($sql);

		$sql = "UPDATE person f, location l, location dl
		SET f.home_location_id = dl.id
		WHERE f.home_location_id = l.id AND l.tier < $tier AND dl.parent_id = l.id";
		$db->query($sql);

		//     $sql = "UPDATE location SET tier = tier + 1 WHERE tier > $tier";
		//     $db->query($sql);

	}

	public static function collapseTier($tier) {
		$db = Zend_Db_Table_Abstract::getDefaultAdapter ();

		error_log('collapse tier = '.$tier);

		$sql = "UPDATE facility f, location l
		SET f.location_id = l.parent_id
		WHERE l.tier = $tier AND f.location_id = l.id ";
		$db->query($sql);

		#training loc
		$sql = "UPDATE training_location f, location l
		SET f.location_id = l.parent_id
		WHERE l.tier = $tier AND f.location_id = l.id ";
		$db->query($sql);

		#home address
		$sql = "UPDATE person f, location l
		SET f.home_location_id = l.parent_id
		WHERE l.tier = $tier AND f.home_location_id = l.id ";
		$db->query($sql);

		#remove locations; skip parent of removed tier for all
		$sql = "UPDATE location l, location pl
		SET l.parent_id = pl.parent_id
		WHERE pl.tier = $tier AND l.parent_id = pl.id ";
		$db->query($sql);

		$sql = "DELETE FROM location WHERE tier = $tier ";
		$db->query($sql);
		$sql = "UPDATE location SET tier = tier - 1 WHERE tier > $tier";
		$db->query($sql);
	}


	/**
	* Return a all regions in this region or below
	* eg. input a district id, return district id + all region_c ids + all cities underneath
	*
	* @param unknown_type $location_id
	*/
	public static function findChildren($location_id, $tier = false) {
		$output = array();
		$locations = self::getAll();
		self::_innerChild($locations, $locations[$location_id], $tier, $output);

		return $output;

	}

	public static function _innerChild($locations, $location, $tier = false, &$output) {
		if ( !$location['id'] ) return;

		if ( (!$tier) || ($location['tier'] == $tier))
		$output []= $location['id'];

		foreach($locations as $loc) {
			if ( $loc['parent_id'] == $location['id']) {
				self::_innerChild($locations, $loc, $tier, $output);
			}
		}

	}

	/**
	* returns rows as [name,tier1,(tier2),(tier3),id,tier1_id,(tier2_id),(tier3_id)]
	*/
	public static function suggestionQuery($match = false, $tier = 4, $limit = 100) {
		$locations = self::getAll();

		$output = array();
		$tmatch = trim($match);
		$lenmatch = strlen($tmatch);
		if ( !$lenmatch ) return $output;

		foreach($locations as $loc) {
			if ( $limit && ($limit < count($output)))
			return $output;

			$doit = false;
			if ( $loc['tier'] == $tier ) {
				$sub = strtolower(substr($loc['name'], 0, $lenmatch));
				if ( !$match ) {
					$doit = true;
				} else if ( $sub == strtolower($tmatch) ) {
					$doit = true;
				}
			}

			if ($doit) {
				//get a list of parent names
				$parents = array();
				$curp = $loc['parent_id'];
				for($i = 1; $i < $tier; $i++) {
					if ( $curp ) {
						$parents[$i] = array($locations[$curp]['name'],$locations[$curp]['id']);
						$curp = $locations[$curp]['parent_id'];
					} else {
						$parents[$i] = array('unknown',0);
					}
				}

				//tack on parent names then parent ids
				$loc_info = array($loc['name']);
				foreach($parents as $p) {
					$loc_info []= $p[0];
				}
				$loc_info []= $loc['id'];
				foreach($parents as $p) {
					$loc_info []= $p[1];
				}
				$loc_info []= $loc['is_default'];


				$output []= $loc_info;
			}
		}

		return $output;
	}

	public static function getFieldName($suffix = 'id', $tier, $num_tiers = 4) {
		$field_name = 'province_'.$suffix;
  	    $tiernames = array('', 'province', 'district' ,'region_c' ,'region_d' ,'region_e' ,'region_f' ,'region_g' ,'region_h' ,'region_i');

        if ($num_tiers > 1)
        {
        	if ($tier < $num_tiers)
        		$field_name = $tiernames[$tier] . '_' . $suffix;
        	else
        		$field_name = 'city_'.$suffix;
		}

		return $field_name;

	}

	/**
	* Return name and parents always in the same 4 tier format:
	* (city_name, prov_id, dist_id, regc_id)
	*
	* @param int $loc_id
	*/
	public static function getCityInfo($loc_id, $num_tiers, &$locations = null) {

  	$output = array(0=>null, 1=>null, 2=>null, 3=>null, 4=>null, 5=>null, 6=>null, 7=>null, 8=>null, 9=>null);
		if ( $loc_id ) {
			if ($locations == null)
				$locations = self::getAll();

			$loc = $locations[$loc_id];

			if ( $loc['tier'] == $num_tiers) {
				$output[0] = $loc['name'];
				$parent_id = $loc['parent_id'];
			} else {
				$parent_id = $loc_id;
			}

			while($parent_id) {
				$output[$locations[$parent_id]['tier']]= $parent_id;
				$parent_id = $locations[$parent_id]['parent_id'];
			}
		}

		// bugfix - lets give a city name even when the system bug of region merging gives us 19 tiers, and $num_tiers is invalid
		if ($output[0] == null && $locations[$loc_id]['tier'] > $num_tiers){ // bugfix: And when tiers is rediculous, or it breaks when their is no city saved
			$output[0] = $locations[$loc_id]['name'];
		}
		// end bugfix

		foreach ($output as $i => $value) {
			if($value == null)
				unset($output[$i]); // remove items from array if empty instead of having a bunch of: if(display_region_d) dostuff
		}

		return $output;
	}

	/**
   * Return a hash from getCityInfo()s values - a little more sane with 9 max location tiers
   * WARN: That function PUTS regions in reverse order sometimes, that breaks this function if regions delivered in sane order by that function, sometimes it outputs: 1,4,5,6,9,8  as its output no city name, dont use this with that
   * WARN: updated info: getCityInfo() apparently returns an array[tier] => id format, this two next functions assume it returns only a parent_id,parent_id,parent_id func, they work, as long as the # of results from getcityInfo returns a number of location ids equal to the number of tiers
   * see comments in getCityandParentNames() for a explination, basically the next 2 func are created to handle a bug in region tier setup and they will almost always give good data, but im paranoid about the bug where we can have 20 tiers (sometimes) when the sys only supports a fixed # of teirs for every city
   */
  public static function cityInfotoHash($vals, $prefix = '') {
	if($prefix)
		$prefix .= '_';

	if ( empty($vals) || !is_array($vals) )
		return array();

	$hash = array('cityname'=>$vals[0]);
	unset($vals[0]);				// first item is name
	$vals = array_reverse($vals);   // array is ordered by parent, lets reverse it
	$region_names = array ('province_id', 'district_id', 'region_c_id', 'region_d_id', 'region_e_id', 'region_f_id', 'region_g_id', 'region_h_id', 'region_i_id');
	for ($i=0; $i < count($vals); $i++)
		$hash[$prefix.$region_names[$i]] = $vals[$i];

	return $hash;
  }

  /**
   * Return a hash of 'province_id' => id, district_id => id style array
   * Assumes getCityInfo returns an ordered array (usually does... but actually it returns array[tier]=>id)
   */
  public static function regionsToHash($vals, $prefix = '')
  {
	if($prefix)
		$prefix .= '_';

	if ( empty($vals) || !is_array($vals) )
		return array();

	$hash = array();
	$region_names = array ('cityname', 'province_id', 'district_id', 'region_c_id', 'region_d_id', 'region_e_id', 'region_f_id', 'region_g_id', 'region_h_id', 'region_i_id');

	for ($i=1; $i <= count($vals); $i++) // array now starts at 1 for some reason, getcityinfo(), sometimes cityname isnt set
		$hash[$prefix.$region_names[$i]] = $vals[$i];

	return $hash;
  }

  /**
   * getCityandParentNames
   *
   * Returns a hash of cityname,province_name, district_name, etc
   */
  public function getCityandParentNames($loc_id, &$locations, $num_location_tiers, $prefix = '')
  {
  	if ($prefix)
  		$prefix .= '_';

  	// assumes there is a bug in region merge (todo remove this line)
  	// stops counting at # of tiers the system is set at
  	// ideally this first for loop will be removed (todo) when the region merge doesnt allow all these bogus tiers to be made
		$o = array();
		$newHash = array(); // rewriting func to output hash, todo
		if (! $loc_id )
			return $o;
		$city_id = $loc_id; // save for later
		while ($loc_id) {
			$parent_id = $locations[$loc_id]['parent_id'];
			$o[]= $locations[$loc_id]['name']; // will reverse later
			$loc_id = $parent_id;
		}

		$hash = array();
		$region_names = array ('province_id', 'district_id', 'region_c_id', 'region_d_id', 'region_e_id', 'region_f_id', 'region_g_id', 'region_h_id', 'region_i_id');
		//ok heres where the bug is, after creating new region tiers, say we have 5 tiers
		// sometimes theres a location with 20 parent tiers, so technically we want
		// to return the first 5 tiers as region_a, region_b, etc
		// i guess ill do that so at least it has 'some data' and its *valid* by application standards,
		// even though at this point its not correct due to a bug in (region_Create or region_merge)
		// that causes some data to have 20 tiers, hopefuly this wont ever happen but at least it exports / imports cleanly now
		#$hash[$prefix.'city_name'] = $o[0]; // u will probably want to add city_name back into the above array and loop through them all
		#unset($o[0]);

		// end bugfix -- todo later, take out 2 above lines^^^ (and maybe first code block in this func (while loop))

		#for ($i=0; $i < $max; $i++) #todo: not sure what i was doing here, seems to not be working, will revist
			#$hash[$prefix.$region_names[$i]] = $o[$i];
		#echo "<pre>";print_r(array_reverse($o)); echo "!!";print_r($hash); echo "\n!!!!";
		#$hash = array_merge($hash, array_reverse($o));


		#$hash = array_merge($hash, array_reverse($o)); // SORRY REWRITING THIS.
		$keys = array(
			$prefix.'province_name',
			$prefix.'district_name',
			$prefix.'region_c_name',
			$prefix.'region_d_name',
			$prefix.'region_e_name',
			$prefix.'region_f_name',
			$prefix.'region_g_name',
			$prefix.'region_h_name',
			$prefix.'region_i_name',
			$prefix.'city_name');
		$o = array_reverse($o);
		foreach($o as $i => $row)
			#$newHash[$keys[$i]] = $o[$i]; //hmm sommetimes region b is showing up in city_name - bugfix
			$newHash[$keys[$i]] = $o[$i];

		#$newHash[$prefix.'city_name'] = $hash[$prefix.'city_name']; #-bugfix
		return $newHash;

  }

  /**
	* Returns false if the city is known to be in another parent region
	* Returns false if the city is unknown
	* Returns an id if the city is known
	*/
	public static function verifyHierarchy($region_name, $parent_id, $tier) {
		$locations = self::getAll();

		foreach($locations as $loc) {
			if ( ($loc['tier'] == $tier) &&
			(strtolower($loc['name']) == strtolower($region_name)) &&
			($loc['parent_id'] == $parent_id))
			return $loc['id'];
		}

		return false;
	}

	/**
	* Insert city if not found and return id
	* otherwise just return actual city id
	*/
	public static function insertIfNotFound($region_name, $parent_id, $tier) {
		if(!$region_name) return false;

		$city_id = self::verifyHierarchy($region_name, $parent_id, $tier);
		if (!$city_id) {
			$cityTable = new Location();
			$data = array();
			$data['location_name'] = $region_name;
			$data['parent_id'] = $parent_id;
			$data['tier'] = $tier;

			$city_id = $cityTable->insert($data);

		}

		return $city_id;
	}

	/**
	* Create a location subquery for a report/search filter
	* A facility or training location can be joined to a city or next region above, so we need a union of two queries.
	*
	* @param int $num_locs, number of tiers on the site
	* @param int $where_tier
	* @param int or array $where_location_id
	* @return array of select names, and the subquery sql
	*/
	public static function subquery($num_locs, $where_tier = false, $where_location_id = false, $include_ids = false) {
		if ( $where_location_id && !is_array($where_location_id)) {
			$where_location_id = array($where_location_id);
		}

		$output_field_name = array();
		$field_name = array();
		for($l = 1; $l <= $num_locs; $l++) {
			$field_name [$l]= self::getFieldName('',$l, $num_locs);
			$output_field_name []= self::getFieldName('name',$l, $num_locs);
			if ($include_ids ) $output_field_name []= self::getFieldName('id',$l, $num_locs);
		}

		$select_cols1 = array(); $select_cols2 = array();
		$joins1 = array();$joins2 = array();
		for($l = $num_locs; $l > 0; $l--) {
			$tier = $l;
			$select_cols1 []= 'l'.$tier.'.location_name as '.$field_name[$l].'name';
			if ($include_ids ) $select_cols1 []= 'l'.$tier.'.id as '.$field_name[$l].'id';
			if ( $l < $num_locs) {
				$joins1 []= " LEFT JOIN location l$tier ON l".($tier+1).".parent_id = l$tier.id  AND l$tier.tier = $tier ".($where_location_id && $where_tier == $tier?" AND l$tier.id IN (".implode(',',$where_location_id).')':'');
				$select_cols2 []= 'l'.$tier.'.location_name as '.$field_name[$l].'name';
				if ($include_ids ) $select_cols2 []= 'l'.$tier.'.id as '.$field_name[$l].'id';
				if ( $l < $num_locs-1) {
					$joins2 []= " LEFT JOIN location l$tier ON l".($tier+1).".parent_id = l$tier.id  AND l$tier.tier = $tier ".($where_location_id && $where_tier == $tier?" AND l$tier.id IN (".implode(',',$where_location_id).')':'');
				}
			}
		}

		$location_sub_query = "SELECT DISTINCT l$num_locs.id as id, ".implode(',',$select_cols1)."
		FROM location l$num_locs
		".implode(" \n",$joins1)." WHERE l$num_locs.tier = $num_locs
		UNION
		SELECT DISTINCT l".($num_locs-1).".id as id, 'unknown' as ".$field_name[$num_locs].'name '.($include_ids?", 'unknown' as ".$field_name[$num_locs].'id':'' ). ", ".implode(',',$select_cols2)."
		FROM location l".($num_locs - 1)."
		".implode(" \n",$joins2)."  WHERE l".($num_locs-1).".tier = ".($num_locs-1).($where_location_id && $where_tier == ($num_locs - 1)?" AND l".($num_locs - 1).".id IN (".implode(',',$where_location_id).')':'');


		return array($output_field_name, $location_sub_query);
	}

  /*
   * find multiple province regions - for southAfrica only #SAONLY
   *
   * if passed an ID will return NULL if that ID is not one of the multiple region entries (return null if not in list)
   */
  public static function southAfrica_get_multi_region($idToCheck = null)
  {
	if ( $_SERVER['HTTP_HOST'] != "localhost" && $_SERVER['HTTP_HOST'] != 'pepfarskillsmart.trainingdata.org')
		return null;
    $db = self::dbfunc();
    $rgns = '"*Multiple SD*","*Multiple Districts*","*Multiple Provinces*"';
    $rowRay = $db->fetchCol("select id from location where location_name in ($rgns)");
    $results = @implode(',', $rowRay);
    
    if ($idToCheck) {
    	if (in_array($idToCheck, $rowRay))
    		return $results ? $results : null;
    	else
    		return null;
    }
    
    return $results ? $results : null;
  }

}

