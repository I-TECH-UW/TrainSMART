<?php

/* 
 * 1. read 'commodity-names-ids' file with list of commodity ids (from Akinsola file 'DHIS2-commodities-ids.xlsx'  - first column)
 * 2. read web service with facility names and commodity data (or 'commodity-data-json' file)
 * all names: commodity names, facility names, states, zones, LGAs
	[UID]=>[name]
	hierarchy: [facility UID]=>/[skip UID]/[state UID]/[LGA UID]/[skip UID]
	
	Zones and states are hardcoded in database and never updated (no stored UIDs).
	UID are stored only for LGAs, facilities, commodities names.
	UIDs are store for identification.
	
	commodity data: [facility type - we do not need],[commodity name],[facility name],[consumption]
*/

$DB_NAME = 'dev_test';

//constants
$PERIOD_LAST_MONTH_MODE = false;
$PERIOD_HISTORICAL_MODE = false;
//DO NOT CHANGE these values
$UPDATE_FACILITY_MODE = true;
$UPDATE_COMMODITY_NAMES_MODE = true;
$UPDATE_COMMODITY_DATA_MODE = true;

$DEBUG_MODE = false;

$PERIOD_LAST_MONTH = 'LAST_MONTH';
$PERIOD_HISTORICAL = "201101;201102;201103;201104;201105;201106;201107;201108;201109;201110;201111;201112;201201;201202;201203;201204;201205;201206;201207;201208;201209;201210;201211;201212;201301;201302;201303;201304;201305;201306;201307;201308;201309;201310;201311;201312;201401;201402;201403;201404;201405;201406;201407;201408;201409;201410";

$DATA_URL_START = "https://dhis2nigeria.org.ng/api/analytics.json?dimension=GvScnquL6Se:ornTXR1cYqs;rtZY2mEY1Sp&dimension=dx:DiXDJRmPwfh;EIHpURrBm7K;G5mKWErswJ0;GWxFtWFcEW1;H8A8xQ9gJ5b;J08AReLicRG;JyiR2cQ6DZT;OhtYDDvpHW8;QlroxgXpWTL;VrSopGk6j9I;c7T9iG1BVbo;eChiJMwaOqm;elD5WAUTvQ2;ibHR9NQ0bKL;krVqq8Vk5Kw;mvBO08ctlWw;oI4V3jXAWdB;pYhpegHDt4x;qQcxmSkuWsL;twQ4H0sl8lz;vDnxlrIQWUo;w92UxLIRNTl;wNT8GGBpXKL;yJSLjbC9Gnr&dimension=ou:LEVEL-3;LEVEL-5;s5DPBsdoE8b&filter=pe:";
$DATA_URL_END = "&hierarchyMeta=true&ignoreLimit=true";
$USERNAME = "afadeyi";
$PASSWORD = "CHAI100F";
//format: [commodity UID];[name];[out of stock UID]
$COMMODITY_NAMES_IDS_FILE = 'commodity-names-ids';

//Set stock out indicators: if consumption is 0 then stock out 'N', otherwise 'Y'
// separated by comma like: "'JyiR2cQ6DZT', 'jhgyg67cjd'"
$STOCK_OUT_INDICATORS = "'JyiR2cQ6DZT'";

//stock out commodities external id
$STOCK_OUT_COMMODITIES = "'w92UxLIRNTl','DiXDJRmPwfh'";

//get program input arguments
$options = getopt("m::p::h");
if(sizeof($options) === 0){
	help();
}else{
	if(array_key_exists('h', $options)){
		help();
		exit;
	}else{
		if(array_key_exists('d', $options)){
			$DEBUG_MODE = true;
		}
		if(array_key_exists('m', $options)){
			$PERIOD_LAST_MONTH_MODE = true;
		}
		if(array_key_exists('p', $options)){
			$PERIOD_HISTORICAL_MODE = true;
			$per = $options['p'];
			if(!empty($per)){
				$PERIOD_HISTORICAL = $per;
			}
		}
		if(!$PERIOD_LAST_MONTH_MODE && !$PERIOD_HISTORICAL_MODE){
			help();
			exit;
		}
	}
}

//to run on local PC
  require_once 'globals.php';
  $db = Zend_Db_Table_Abstract::getDefaultAdapter();
 
  //to run on server
//  $db = getDB($DB_NAME);
// print "USE DATABASE: " . $DB_NAME . "\n\n";

 $all_errors = '';
 $date = '';
 
 // array with out of stock info [out of stock UID]=>[commodity UID]
 $commodity_names_out_of_stock_arr = array();
 
 echo date(DATE_RFC2822);
 if($PERIOD_HISTORICAL_MODE){
 	$periods = explode(";", $PERIOD_HISTORICAL);
 	for($i=0; $i<sizeof($periods); $i++){
 		print "\n\n ===> UPLOAD PERIOD: " . $periods[$i] . " START\n\n";
 		$DATA_URL = $DATA_URL_START . $periods[$i] . $DATA_URL_END;
 		upload($DATA_URL, $USERNAME, $PASSWORD, $UPDATE_FACILITY_MODE, $UPDATE_COMMODITY_NAMES_MODE, $UPDATE_COMMODITY_DATA_MODE, $COMMODITY_NAMES_IDS_FILE, $db, $commodity_names_out_of_stock_arr);
 		print "\n\n ===> UPLOAD PERIOD: " . $periods[$i] . " END\n####################################################################################\n\n";
 	}
 }
 if($PERIOD_LAST_MONTH_MODE){
 	print "\n\n ===> UPLOAD PERIOD: " . $PERIOD_LAST_MONTH . " START\n\n";
 	$DATA_URL = $DATA_URL_START . $PERIOD_LAST_MONTH . $DATA_URL_END;
 	upload($DATA_URL, $USERNAME, $PASSWORD, $UPDATE_FACILITY_MODE, $UPDATE_COMMODITY_NAMES_MODE, $UPDATE_COMMODITY_DATA_MODE, $COMMODITY_NAMES_IDS_FILE, $db, $commodity_names_out_of_stock_arr);
 	print "\n\n ===> UPLOAD PERIOD: " . $PERIOD_LAST_MONTH . " END\n\n";
 }
 echo date(DATE_RFC2822);
 
 if(!empty($all_errors)){
 	$file = fopen("DHIS2Upload-FacilityCommodity-". $date . ".errors","w");
 	echo fwrite($file,$all_errors);
 	fclose($file);
 }
 
 exit;
 
 /**
  * Upload data
  */
function upload($DATA_URL, $USERNAME, $PASSWORD, $UPDATE_FACILITY_MODE, $UPDATE_COMMODITY_NAMES_MODE, $UPDATE_COMMODITY_DATA_MODE, $COMMODITY_NAMES_IDS_FILE, $db) {
	
	global $commodity_names_out_of_stock_arr;
	global $date;
	
	// ******************* LOAD DATA FROM DHIS2 WEB SERVICE ***************************
	
	// read web service with facility and commodity data
	print "Load data: " . $DATA_URL . "\n\n";
	$data_json = getWebServiceResult($DATA_URL, $USERNAME, $PASSWORD);
	
// 	$data_json = file_get_contents ( 'DHIS2-data-json' ); // REMOVE: for test only
	                                                     
	$data_json_arr = json_decode($data_json, true);
	
	unset($data_json_arr["metaData"]["ou"]); // remove this huge object (2Mb of size)

	$date = $data_json_arr ["metaData"] ["pe"] [0];
	$date_year = substr ( $date, 0, 4 );
	$date_month = substr ( $date, - 2 );
	print "Data period: " . $date_year . "-" . $date_month . "-01\n\n";
	
	//save json output to file
	$file = fopen("DHIS2Upload-FacilityCommodity-". $date . ".json","w");
	echo fwrite($file,$data_json);
	fclose($file);
	
	// commodity data: [facility type - we do not need],[commodity name],[facility name],[consumption]
	if (sizeof ( $data_json_arr ["rows"] ) == 0) {
		global $all_errors;
		$all_errors = $all_errors . "ERROR: Commodity data is empty in WS.\n";
		exit ();
	}
	
	// ******************* UPDATE FACILITIES NAMES ***********************************************
	if ($UPDATE_FACILITY_MODE) {
		// get DB facility info BEFORE update
		// hash: key - external id, value - array(id, external_id, facility_name)
		$db_facility_info = getDBFacilitiesInfo ( $db );
		
		//hash:  key - [name], value - [id]
		$db_state_info = getDBStateInfo($db);
		
		updateFacilities ( $data_json_arr ["metaData"] ["ouHierarchy"], $db_facility_info, $data_json_arr ["metaData"] ["names"], $db, $date_year . "-" . $date_month . "-01", $db_state_info);
	}
	
	// get DB facility info AFTER update
	// hash: key - external id, value - array(id, external_id, facility_name)
	$db_facility_info = getDBFacilitiesInfo ( $db );
	
	// ******************* UPDATE COMMODITIES NAMES ***********************************************
	if ($UPDATE_COMMODITY_NAMES_MODE) {
		// read commodity names ids file - format [id1]\n[id2]\n...
		print "=> Get commodities name ids from file\n\n";
		//format: [commodity UID];[name];[out of stock UID]
		$commodity_names_ids = array_filter ( preg_split ( "/[\r\n]+/", file_get_contents ( $COMMODITY_NAMES_IDS_FILE ) ) );
		
		// get DB commodities names info BEFORE update
		// hash: key - external id, value - array(id, external_id, facility_name)
		$db_commodity_info = getDBCommodityNamesInfo ( $db );
		
		// take files with commodity names id and check if in DB, if not add new commodity name to DB 'commodity_name_option' table
		updateCommoditiesNames ( $data_json_arr ["metaData"] ["names"], $commodity_names_ids, $db, $db_commodity_info, $date_year, $date_month );

	}
	
	// get DB commodities names info AFTER update
	// hash: key - external id, value - array(id, external_id, facility_name)
	$db_commodity_info = getDBCommodityNamesInfo ( $db );
	
	// ******************* UPDATE COMMODITIES DATA ***********************************************
	if ($UPDATE_COMMODITY_DATA_MODE) {
		// get DB commodity data info BEFORE update
		// hash: key - facility id, key - name id, value - id
		$db_commodity_data_info = getDBCommoditiesDataInfo ( $db, $date_year . "-" . $date_month . "-01" );
		
		updateCommoditiesData ( $data_json_arr ["rows"], $db_commodity_info, $db_facility_info, $db_commodity_data_info, $db, $date_year . "-" . $date_month . "-01" );
	}
}

// take files with commodity names id and check if in DB, if not add new commodity name to DB 'commodity_name_option' table
function updateCommoditiesNames($names, $commodity_name_ids, $db, $db_commodity_info, $date_year, $date_month){
	global $commodity_names_out_of_stock_arr;
	global $DEBUG_MODE;
	$error = '';
	if($DEBUG_MODE)
		print "=> UPDATE COMMODITIES NAMES START ...\n\n";
	$count = 0;
	foreach ( $commodity_name_ids as $commodity_info) {
		$count++;
		//format: [commodity UID];[name];[out of stock UID]
		$commodity_info_arr = explode(";", $commodity_info);
		$commodity_external_id = $commodity_info_arr[0];
		//find commodity name by external_id from WS 'names'
		if(array_key_exists($commodity_external_id, $names)){
			$commodity_name = trim($names[trim($commodity_external_id)]);
		}else{
			$commodity_name = $commodity_info_arr[1];
		}
		if($commodity_name){
			//add/update commodity name in DB
			if($db_commodity_info && array_key_exists($commodity_external_id, $db_commodity_info)){
				// if commodity name are different then update
				if($commodity_name !== $db_commodity_info[$commodity_external_id]['commodity_name']){
					try{
						$db->query("UPDATE commodity_name_option SET commodity_name='" . $commodity_name . "' WHERE external_id='" . $commodity_external_id . "'");
						if($DEBUG_MODE)
							print "EDIT COMMODITY: " . $commodity_external_id . "=>" . $commodity_name ."\n\n";
					}catch(Exception $e){
						$error = $error . "ERROR: " . $commodity_external_id . " (" . $e->getMessage() . ")\n";
					}
				}
			}else{
				//add new commodity_name
				try{
					$bind = array(
						'external_id'			=>	$commodity_external_id,
						'commodity_name'		=>	$commodity_name,
						'timestamp_created' => $date_year . "-" . $date_month . "-01",
					);
					//all value automatically will be removed white spaces at the END during insertion to DB
					$db->insert("commodity_name_option", $bind);
					$commodity_id=$db->lastInsertId();
					if($DEBUG_MODE)
						print "ADD COMMODITY: " . $commodity_external_id . "=>" . $commodity_name ."\n\n";
				}catch(Exception $e){
					$error = $error . "ERROR: " . $commodity_external_id . " (" . $e->getMessage() . ")\n";
				}
			}
		}else{
			$error = $error . "ERROR: " . $commodity_external_id . " not found in 'names' WS.\n";
		}
		// check this commodity has out of stock info
		if(!empty($commodity_info_arr[2])){
			// array with out of stock info [out of stock UID]=>[commodity UID]
			$commodity_names_out_of_stock_arr[$commodity_info_arr[2]] = $commodity_external_id;
		}
	}
	if(!empty($error)){
		global $all_errors;
		$all_errors = $all_errors . "\n=> UPDATE COMMODITIES NAMES:\n" . $error . "\n\n";
	}
	print "\n=> UPDATE COMMODITIES NAMES END:\n" .  $count . " commodities names ids have been processed.\n";

	//validate process
	$db_commodity_names_info_count = $db->fetchAll ("select count(*) as count from commodity_name_option");
	print $db_commodity_names_info_count[0]['count'] . " commodities names in database.\n\n";
}

// add commodity data to facilities - 'commodities' table
function updateCommoditiesData($commodity_data, $db_commodity_info, $db_facility_info, $db_commodity_data_info, $db, $date){
	global $commodity_names_out_of_stock_arr;
	global $DEBUG_MODE;
	global $STOCK_OUT_INDICATORS;
	global $STOCK_OUT_COMMODITIES;

	
	// get commodity names ids which has out of stock info
	$db_commodity_names_ids_with_has_out_off_stock = array();
	$query_arr = array_values($commodity_names_out_of_stock_arr);
	foreach ($query_arr as &$value)
		$value = "'" .$value . "'";
	$res = $db->fetchAll ("select id from commodity_name_option where external_id in (" . implode(", ", $query_arr) . ")");
	foreach ($res as $value)
		array_push($db_commodity_names_ids_with_has_out_off_stock, $value['id']);

	//means that out of stock 'Y'
	//format: [facility external id]=> array of commodity externals id for which out of stock 'Y'
	$commodity_names_out_of_stock_arr_to_update = array();
	$error = '';
	if($DEBUG_MODE)
		print "=> UPDATE COMMODITIES DATA START ...\n\n";
	$count = 0;
	foreach ( $commodity_data as $commodity) {
		$commodity_external_id = $commodity[1];
		//if this is out of stock data
		if($commodity_names_out_of_stock_arr && array_key_exists($commodity_external_id, $commodity_names_out_of_stock_arr)){
			//add facility and out of stock info
			$consumption = $commodity[3];
			//if($consumption > 0){
				$data_arr = array();
				$facility_external_id = $commodity[2];
				if(array_key_exists($facility_external_id, $commodity_names_out_of_stock_arr_to_update)){
					$data_arr = $commodity_names_out_of_stock_arr_to_update[$facility_external_id];
				}
				array_push($data_arr, $commodity_names_out_of_stock_arr[$commodity_external_id] . "=" . $consumption);
				$commodity_names_out_of_stock_arr_to_update[$facility_external_id] = $data_arr; 
		//	}
			continue;
		}
		
		//if commodity name in the list of needed commodities
		if($db_commodity_info && array_key_exists($commodity_external_id, $db_commodity_info)){
			$commodity_id = $db_commodity_info[$commodity_external_id]['id'];
			$facility_external_id = $commodity[2];
			// facility in the database
			if($db_facility_info && array_key_exists($facility_external_id, $db_facility_info)){
				$count++;
				$facility_id = $db_facility_info[$facility_external_id]['id'];	
				$consumption = $commodity[3];
				try{
					//if exist in database update
					if(array_key_exists($facility_id, $db_commodity_data_info)){
						$db_commodity_facility = $db_commodity_data_info[$facility_id];
						if(array_key_exists($commodity_id, $db_commodity_facility)){
							$id = $db_commodity_facility[$commodity_id];

// 							set out of stock 'N' as default only for those commodity_id which has out of stock info from  $commodity_names_out_of_stock_arr;
// 							below code where we will update to 'Y' if WS has any info about out of stock;
//							update out of stock to Y/N only for stock info from  $commodity_names_out_of_stock_arr, for others keep how was set by human via GUI
							if(in_array($commodity_id, $db_commodity_names_ids_with_has_out_off_stock)){
								$db->query("UPDATE commodity SET stock_out='N', consumption='" . $consumption . "', modified_by='' WHERE id='" . $id . "' and date='" . $date ."'");
								if($DEBUG_MODE)
									print "EDIT COMMODITY DATA: " . $commodity_external_id . " to facility " . $facility_external_id . "=" . $consumption . "\n";
							}else{
								$db->query("UPDATE commodity SET consumption='" . $consumption . "', modified_by='' WHERE id='" . $id . "' and date='" . $date ."'");
								if($DEBUG_MODE)
									print "EDIT COMMODITY DATA: " . $commodity_external_id . " to facility " . $facility_external_id . "=" . $consumption . "\n";
							}
							continue;
						}
					}
						// add new data
					$bind = array(
						'name_id'			=>	$commodity_id,
						'facility_id'=>	$facility_id,
						'date' => $date,
						'consumption' => $consumption,
						'timestamp_created' => $date,
						'modified_by' => '0',
					);
				
					//all value automatically will be removed white spaces at the END during insertion to DB
					$db->insert("commodity", $bind);
					if($DEBUG_MODE)
						print "ADD COMMODITY DATA: " . $commodity_external_id . " to facility " . $facility_external_id . "=" . $consumption . "\n";
					
				}catch(Exception $e){
					$error = $error . "ERROR ADD COMMODITY DATA: " . $commodity_external_id . "=>" . $facility_external_id . "=" . $consumption . " (" . $e->getMessage() . ")\n";
				}
			}
		}
	}
	print "\n=> UPDATE COMMODITIES DATA END:\n" .  $count . " commodities data have been processed.\n";

	$db_commodity_info_count = $db->fetchAll ("select count(*) as count from commodity where date='" . $date . "'");
	print $db_commodity_info_count[0]['count'] . " commodities data in database.\n\n";
	
	//get all current commodities data
	$db_commodity_data_info = getDBCommoditiesDataInfo ( $db, $date);
	
	// update out of stock info
 	// update 'out_of_stock" = "Y" if this is in $commodity_names_out_of_stock_arr_to_update
 	//otherwise update to 'N'
	
	$all_add = array();
	$count_out_of_stock = 0;
	if($DEBUG_MODE)
		print "\n=> UPDATE COMMODITIES DATA out of stock...\n\n";
	// $commodity_names_out_of_stock_arr_to_update - arr [facility external id]=> arr (commodity external ids which have stock outs):
	foreach ( $commodity_names_out_of_stock_arr_to_update as $facility_names_out_of_stock_external_id=>$commodity_names_out_of_stock_external_id_arr) {
		// get facility name id
		if($db_facility_info && array_key_exists($facility_names_out_of_stock_external_id, $db_facility_info)){
			$facility_id = $db_facility_info[$facility_names_out_of_stock_external_id]['id'];
		}
		if($facility_id){
			foreach ( $commodity_names_out_of_stock_external_id_arr as $commodity_names_out_of_stock_external_ids_consumption_arr){
				$commodity_names_out_of_stock_arr = explode("=", $commodity_names_out_of_stock_external_ids_consumption_arr);
                $commodity_names_out_of_stock_external_ids = $commodity_names_out_of_stock_arr[0];
				$commodity_names_out_of_stock_external_ids_consumption = $commodity_names_out_of_stock_arr[1];
				$stock_out = 'N';
				if($commodity_names_out_of_stock_external_ids_consumption > 0){
					$stock_out = 'Y';
				}
				$count_out_of_stock++;
					// get commodity name id
				if($db_commodity_info && array_key_exists($commodity_names_out_of_stock_external_ids, $db_commodity_info)){
					$commodity_id = $db_commodity_info[$commodity_names_out_of_stock_external_ids]['id'];
					//if commodity does not exist for this period in DHIS2 (no in DB), but its out of stock exists in DHIS2, then add this info to DB
					if(array_key_exists($facility_id, $db_commodity_data_info) && array_key_exists($commodity_id, $db_commodity_data_info[$facility_id])){
						$db->query("UPDATE commodity SET stock_out='" . $stock_out . "', modified_by='' WHERE name_id='" . $commodity_id . "' and facility_id='" . $facility_id . "' and date='" . $date ."'");
						if($DEBUG_MODE)
							print "EDIT COMMODITY DATA out of stock : " . $commodity_names_out_of_stock_external_ids . " to facility " . $facility_names_out_of_stock_external_id . "\n";
					}else{
						// add new data
						$bind = array(
							'name_id'			=>	$commodity_id,
							'facility_id'=>	$facility_id,
							'stock_out'=>	$stock_out,
							'date' => $date,
							 'consumption' => '0',
							'timestamp_created' => $date,
							'modified_by' => '0',
						);
						
						//all value automatically will be removed white spaces at the END during insertion to DB
						$db->insert("commodity", $bind);
						if($DEBUG_MODE)
							print "ADD COMMODITY DATA out of stock: " . $commodity_names_out_of_stock_external_ids . " to facility " . $facility_names_out_of_stock_external_id . "\n";
					}
				}else{
					$error = $error . "ERROR: out of stock update: commodity name " . $commodity_names_out_of_stock_external_ids . " does not exist in database.\n";
				}
			}	
		}
	}
	
	//Remove commodity duplicated if found for commodity which can have stock out:
// 	select id,name_id, facility_id, date, count(*) from commodity
// 	group by name_id, facility_id, date having count(*) > 1;
	try{
		$db->query("delete T1 from commodity T1, commodity T2 where T1.name_id = T2.name_id and T1.facility_id = T2.facility_id and T1.date = T2.date and T2.date='" . $date . "' and T1.id < T2.id " .
						"and T1.name_id in (select id from commodity_name_option where external_id in(" . $STOCK_OUT_COMMODITIES . "));");
		print "\n=> Remove commodity duplicated if found.\n\n";
	}catch(Exception $e){
		$error = $error . "ERROR: Remove commodity duplicated if found\n";
	}

	
	//Set stock out indicators if consumption 1 then stock out 'Y'
	try{
	$db->query("update commodity set commodity.stock_out='Y', commodity.consumption=0 where 
			commodity.name_id = (select id from commodity_name_option where external_id in (" . $STOCK_OUT_INDICATORS . ") and consumption=1 and date='" . $date ."')");
	print "\n=> UPDATE COMMODITIES DATA for stock out indicators: " . $STOCK_OUT_INDICATORS . "\n\n";
	}catch(Exception $e){
		$error = $error . "ERROR: UPDATE COMMODITY DATA for stock out indicators: " . $STOCK_OUT_INDICATORS . "\n";
	}
	
	//clean dashboard_refresh table
	try{
		$db->query("delete from dashboard_refresh");
		print "\n=> Clean dashboard_refresh table\n\n";
	}catch(Exception $e){
		$error = $error . "ERROR: Clean dashboard_refresh table\n";
	}
	
	print "\n=> UPDATE COMMODITIES DATA: " .  $count_out_of_stock . " commodities out of stock data have been processed.\n\n";
	if(!empty($error)){
		global $all_errors;
		$all_errors = $all_errors . "\n=> UPDATE COMMODITIES DATA:\n" . $error . "\n\n";
	}
	
}

/*
 * DHIS2 WS does not have information about 'zone' in 'hierarchy' tag: "[facility UID]":"/ [skip] / [state UID] / [LGA UId] / [skip]”
 * we have hard coded in DB Location table zones and states with no external UID, but LGA and facility we will store with external UID.
 *  
 */
function updateFacilities($hierarchy, $db_facility_info, $names, $db, $date, $db_state_info){
	global $DEBUG_MODE;
	$error = '';
	if($DEBUG_MODE)
		print "=> UPDATE FACILITIES START ...\n\n";
	$count = 0;
	foreach ( $hierarchy as $facility_external_id=>$location_path ) {
		if(!$location_path || empty($location_path)){
			$error = $error . "ERROR: " . $facility_external_id . " (facility location is empty)\n";
			continue;
		}
		$location = explode("/", $location_path);
		// parse only for facilities.
		//Path should be [facility UID]=>/[UID - skip]/[state UID]/[LGA UID]/[UID - skip]
		if(count($location) != 5){
			continue;
		}
		$count++;
		$state_external_id =  $location[2];
		$lga_external_id =  $location[3];
		//remove prefix before name, like 'Kd Radiance clinic' - remove 'Kd'
		$facility_name = substr(strstr(trim($names[$facility_external_id])," "), 1);
		$state_name = substr(strstr(trim($names[$state_external_id])," "), 1);
		$lga_name = substr(strstr(trim($names[$lga_external_id])," "), 1);
		// remove 'Local Government Area' from LGA names
		/*or do this: UPDATE location SET location_name = REPLACE(location_name, 'Local Government Area', '') where tier=3 and location_name like '%Local Government Area';*/
		$lga_name = trim(str_replace("Local Government Area","",$lga_name));
		// remove ' from names for LGA like "Jama'are"
		$lga_name = trim(str_replace("'","",$lga_name));
		if($DEBUG_MODE)
			print "\nProcessing facility " . $count . ": " . $facility_name . "/" . $state_name . "/" . $lga_name . " (" . $facility_external_id . "=>" . $location_path .")\n";
		$facility_name = trim($facility_name);		
		if(empty($facility_name)){
			$error = $error . "ERROR: " . $facility_external_id . " (facility name is empty)\n";
			continue;
		}
		if($db_facility_info && array_key_exists($facility_external_id, $db_facility_info)){
			// if facility name are different then update
			if($facility_name !== $db_facility_info[$facility_external_id]['facility_name']){
				try{
					$db->query("UPDATE facility SET facility_name='" . $facility_name . "' WHERE external_id='" . $facility_external_id . "'");
					if($DEBUG_MODE)
						print "EDIT FACILITY: " . $facility_external_id . "=>" . $facility_name ."\n\n";
				}catch(Exception $e){
					$error = $error . "ERROR: EDIT FACILITY: " . $facility_external_id . " (" . $e->getMessage() . ")\n";
				}
			}
		}else{
			//ADD NEW FACILITY
				// remove '-' from state name
				$state_name = str_replace('-', ' ', trim($state_name));
				// remove 'state' word from $state_name
				$state_name = trim(ucwords(str_replace('state', '', strtolower($state_name))));
				//find state in database (hardcoded)
				if($db_state_info && array_key_exists($state_name, $db_state_info)){
					$state_id = $db_state_info[$state_name];
					if($state_id){
						$lga_id = isLocationExist($lga_external_id, $db);
						if($lga_id === NULL){
							$lga_id = addLocation($lga_external_id, $lga_name, 3, $state_id, $db, $date);
						}
						$bind = array(
								'external_id'			=>	$facility_external_id,
								'facility_name'		=>	$facility_name,
								'location_id'=>	$lga_id,
								'timestamp_created' => $date,
						);
						try{
							//all value automatically will be removed white spaces at the END during insertion to DB
							$db->insert("facility", $bind);
							$facility_id=$db->lastInsertId();
							if($DEBUG_MODE)
								print "ADD FACILITY: " . $facility_external_id . "=>" . $facility_name ."\n\n";
						}catch(Exception $e){
							$error = $error . "ERROR: ADD FACILITY: " . $facility_external_id . " does not have prefix (" . $e->getMessage() . ")\n";
						}
					}else{
						$error = $error . "ERROR: ADD FACILITY: cannot add new facility '" . $facility_name . "': state '" . $state_name . "' does not exist in database.\n";
					}
				}else{
					$error = $error . "ERROR: ADD FACILITY: cannot add new facility '" . $facility_name . "': state '" . $state_name . "' does not exist in database.\n";
				}
		}
	}
	if(!empty($error)){
		global $all_errors;
		$all_errors = $all_errors . "\n=> UPDATE FACILITIES:\n" . $error . "\n\n";
	}
	print "\n=> UPDATE FACILITIES END:\n" .  $count . " facilities have been processed.\n";

	//validate process
	$db_facility_info_count = $db->fetchAll ("select count(*) as count from facility");
	print $db_facility_info_count[0]['count'] . " facilities in database.\n\n";
}

//returns location id
function isLocationExist($external_location_id, $db){
	$db_location_info = $db->fetchAll ("select id, location_name from location where external_id = '" . $external_location_id . "'");
	if($db_location_info){
		return $db_location_info[0]['id'];
	}
	return NULL; // not found
}

function addLocation($external_location_id, $location_name, $location_tier, $location_parent_id, $db, $date){
	global $DEBUG_MODE;
	$bind = array(
			'external_id'			=>	$external_location_id,
			'location_name'		=>	$location_name,
			'tier'=>	$location_tier,
			'parent_id'=>	$location_parent_id,
			'timestamp_created' => $date,
	);
	try{
	 $db->insert("location", $bind);
	 $location_id=$db->lastInsertId();
	 if($DEBUG_MODE)
		print "ADD LOCATION: " . $external_location_id . "=>" . $location_name . "\n";
	 return $location_id;
	}catch(Exception $e){
		throw new Exception("ERROR: " . $external_location_id . " (" . $e->getMessage() . ")\n");
	}
}

function getDBCommoditiesDataInfo($db, $date){
	echo "\n=>Get commodity data info from database...\n\n";
	$db_commodity_data_info = $db->fetchAll ("select id, name_id, facility_id from commodity where date = '" . $date . "'");
	$db_commodity_data_info_hash = array();
	foreach ( $db_commodity_data_info as $db_commodity_data_row ) {
		$db_commodity_data_info_hash[$db_commodity_data_row['facility_id']][$db_commodity_data_row['name_id']] = $db_commodity_data_row['id'];
	}
	return $db_commodity_data_info_hash;
}

//hash: key - external id, value - array(id, external_id, facility_name)
function getDBFacilitiesInfo($db){
	echo "\n=>Get facility info from database...\n\n";
	 $db_facility_info = $db->fetchAll ("select id, external_id, facility_name from facility");
	 $db_facility_info_hash = array();
	 foreach ( $db_facility_info as $db_facility_row ) {
	 	$db_facility_info_hash[$db_facility_row['external_id']] = $db_facility_row;
	 }
	 return $db_facility_info_hash;
}

//hash: key - external id, value - array(id, external_id, commodity_name)
function getDBCommodityNamesInfo($db){
	echo "\n=>Get commodity names info from database...\n\n";
	$db_commodity_info = $db->fetchAll ("select id, external_id, commodity_name from commodity_name_option");
	$db_commodity_info_hash = array();
	foreach ( $db_commodity_info as $db_commodity_row ) {
		$db_commodity_info_hash[$db_commodity_row['external_id']] = $db_commodity_row;
	}
	return $db_commodity_info_hash;
}

//hash:  key - [name], value - [id]
function getDBStateInfo($db){
	echo "\n=>Get states info from database...\n\n";
	$db_state_info = $db->fetchAll ("select id, location_name, parent_id from location where tier=2");
	$db_state_info_hash = array();
	foreach ( $db_state_info as $db_state_row ) {
		$db_state_info_hash[$db_state_row['location_name']] = $db_state_row['id'];
	}
	return $db_state_info_hash;
}

//print help how to run script
function help(){
	print "This script uploads data from DHIS2\n\nUSAGE: php DHIS2Upload-FacilityCommodity.php [options] > out\nOptions:\n";
	print "\t-m - upload data for the last month period. USAGE: -m\n";
	print "\t-p - upload data for period [YYYYMM;YYYYMM], if -p is empty then upload periods [201101-201410]. USAGE:-p[YYYYMM;YYYYMM] or -p\n";
	print "\t-d - print debug output\n";
	print "\t-h - help\n";
}

	/**
	 * Read web service and return output
	 */
	function getWebServiceResult($commodity_data_url, $username, $password){
		if (!function_exists('curl_init')){
			die('Sorry cURL is not installed!');
		}
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $commodity_data_url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_USERPWD, "$username:$password");
		curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // comment later, it is for Windows only
		curl_setopt($ch, CURLOPT_TIMEOUT, 0);
		
		$output = curl_exec($ch);
		if($output === false){
			echo 'ERROR: ' . curl_error($ch);
		}
// 		else{
// 			return $output;
// 		}
		curl_close($ch);
		return $output;
	}

/**
 * Read 'commodity-names' file to array
 */
function csv_to_array($filename = '', $delimiter = ',') {
	if (! file_exists ( $filename ) || ! is_readable ( $filename ))
		return FALSE;
	$header = NULL;
	$data = array ();
	if (($handle = fopen ( $filename, 'r' )) !== FALSE) {
		while ( ($row = fgetcsv ( $handle, 1000, $delimiter )) !== FALSE ) {
			if (! $header)
				$header = $row;
			else
				$data [] = array_combine ( $header, $row );
		}
		fclose ( $handle );
	}
	return $data;
}

function getDB($db_name){
	require_once 'settings.php';
	require_once 'Zend/Db.php';

	//set a default database adaptor
	$db = Zend_Db::factory('PDO_MYSQL', array(
			'host'     => Settings::$DB_SERVER,
			'username' => Settings::$DB_USERNAME,
			'password' => Settings::$DB_PWD,
			'dbname'   => $db_name
	));

	require_once 'Zend/Db/Table/Abstract.php';
	Zend_Db_Table_Abstract::setDefaultAdapter($db);
	$db = Zend_Db_Table_Abstract::getDefaultAdapter();
	return $db;

}


?>