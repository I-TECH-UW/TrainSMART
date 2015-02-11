<?php

/* 
 1. Read DHIS2 web service
 2.
 From:  Row  e.g. ["lyVV9bPLlVy","201411","agAcarLl8in","100,0"]


                          ["Not Needed","DATE","facility","reported"]


Put in database 'facility_report_rate': id, facility_external_id, date.
*/

$DB_NAME = 'dev_test';

//constants
$PERIOD_LAST_MONTH_MODE = false;
$PERIOD_HISTORICAL_MODE = false;

$PERIOD_LAST_MONTH = 'LAST_MONTH';
$PERIOD_HISTORICAL = "201101;201102;201103;201104;201105;201106;201107;201108;201109;201110;201111;201112;201201;201202;201203;201204;201205;201206;201207;201208;201209;201210;201211;201212;201301;201302;201303;201304;201305;201306;201307;201308;201309;201310;201311;201312;201401;201402;201403;201404;201405;201406;201407;201408;201409;201410";

$DATA_URL_START = "https://dhis2nigeria.org.ng/api/analytics.json?dimension=dx:lyVV9bPLlVy&dimension=pe:";
$DATA_URL_END = "&dimension=ou:LEVEL-5;TFY8aaVkCtV;BmWTbiMgEai;Gq37IyyjUfj;jXngIDniC8t;Ym1fEhWFWYI;FmH4buccgqx;fBInDsbaQHO;r3IK5qdHsZ6;hfNPq5F4mjr;yx3QJHm86vWH2ZhSMudlMI;gzLOszDWdqM;RYEnw3sMDyE;tjLatcokcel;M689V9w3Gs3;cTIw3RXOLCQ;S7Vs7ifJKlh;uKlacgs9ykR;jReUW6NCPkL;HYCMnXqLDPV;bSfaEpPFa9Y;FmOhtDnhdwU;MJVVi73YayJ;tjLatcokcel;M689V9w3Gs3;cTIw3RXOLCQ;S7Vs7ifJKlh;m0rZG06GdPe;xWSEoKmrbBW;aMQcvAoEFh0;iilma7EajGc;Quac4RHRtaZ;HYCMnXqLDPV;bSfaEpPFa9Y;FmOhtDnhdwU;Nko8QFDmYmq;FHlOerryBjk;OgjFloqKoqk;qLiKWoddwFu;ziJ3yxfgb3m;MXrZyuS9E7A;RLySnRCE1Gy;ns3vF75Y0bF;caG44DzHu6F&displayProperty=NAME";

$USERNAME = "afadeyi";
$PASSWORD = "CHAI100F";

//get program input arguments
$options = getopt("m::p::h");
if(sizeof($options) === 0){
	help();
}else{
	if(array_key_exists('h', $options)){
		help();
		exit;
	}else{
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

 require_once 'globals.php';
 $db = Zend_Db_Table_Abstract::getDefaultAdapter();
 
 //  $db = getDB($DB_NAME);
 // print "USE DATABASE: " . $DB_NAME . "\n\n";
 
 $all_errors = '';
 $date = '';
 
 echo date(DATE_RFC2822);
 if($PERIOD_HISTORICAL_MODE){
 	$periods = explode(";", $PERIOD_HISTORICAL);
 	for($i=0; $i<sizeof($periods); $i++){
 		print "\n\n ===> UPLOAD PERIOD: " . $periods[$i] . " START\n\n";
 		$DATA_URL = $DATA_URL_START . $periods[$i] . $DATA_URL_END;
 		upload($DATA_URL, $USERNAME, $PASSWORD, $db);
 		print "\n===> UPLOAD PERIOD: " . $periods[$i] . " END\n####################################################################################\n\n";
 	}
 }
 if($PERIOD_LAST_MONTH_MODE){
 	print "\n\n ===> UPLOAD PERIOD: " . $PERIOD_LAST_MONTH . " START\n\n";
 	$DATA_URL = $DATA_URL_START . $PERIOD_LAST_MONTH . $DATA_URL_END;
 	upload($DATA_URL, $USERNAME, $PASSWORD, $db);
 	print "\n===> UPLOAD PERIOD: " . $PERIOD_LAST_MONTH . " END\n\n";
 }
 echo date(DATE_RFC2822);
 
 exit;
 
 /**
  * Upload data
  */
function upload($DATA_URL, $USERNAME, $PASSWORD, $db) {
	
	$error = '';
	global $date;
	
	// ******************* LOAD DATA FROM DHIS2 WEB SERVICE ***************************
	
	// read web service 
	print "Load data: " . $DATA_URL . "\n\n";
	$data_json = getWebServiceResult($DATA_URL, $USERNAME, $PASSWORD);
// 	$data_json = file_get_contents ( 'DHIS2-data-json' ); // REMOVE: for test only
	                                                    
	$data_json_arr = json_decode($data_json, true);
	
	$date = $data_json_arr ["metaData"] ["pe"] [0];
	$date_year = substr ( $date, 0, 4 );
	$date_month = substr ( $date, - 2 );
	print "Data period: " . $date_year . "-" . $date_month . "-01\n\n";
	
	unset($data_json_arr["metaData"]); // remove this huge object
	
	// check if these date already loaded to database
	$db_data_info_count = $db->fetchAll ("select count(*) as count from facility_report_rate where date='" . $date_year . "-" . $date_month . "-01'");
	if($db_data_info_count[0]['count'] > 0){
		print "Data for this period were loaded in database earlier.\n\n";
		return;
	}
	
	// ******************* PARSING DATA ***************************
	
	$count = 0;
	foreach ( $data_json_arr ["rows"] as $row) {
		$facility_external_id = $row[2];
		$report = $row[3];
		if($report !== '100.0'){
			$error = $error . "ERROR: " . $facility_external_id . " has value " . $report . "\n";
		}
		$count++;
		$bind = array(
				'facility_external_id'			=>	$facility_external_id,
				'date' => $date_year . "-" . $date_month . "-01"
		);
		try{
			$db->insert("facility_report_rate", $bind);
		}catch(Exception $e){
			$error = $error . "ERROR ADD DATA: " . $facility_external_id . " (" . $e->getMessage() . ")\n";
		}
		
		
	}
	
	print "\n=> REPORT RATE LOAD:\n" .  $count . " facilities have been processed.\n\n";
	
	//validate process
	$db_data_info_count = $db->fetchAll ("select count(*) as count from facility_report_rate where date='" . $date_year . "-" . $date_month . "-01'");
	print $db_data_info_count[0]['count'] . " facilities  in database.\n\n";
	
 if(!empty($error)){
 	$file = fopen("DHIS2Upload-FacilityReportRate-". $date . ".errors","w");
 	fwrite($file,$error);
 	fclose($file);
 }
}

//print help how to run script
function help(){
	print "This script uploads data from DHIS2\n\nUSAGE: php DHIS2Upload-FacilityReportRate.php [options] > out\nOptions:\n";
	print "\t-m - upload data for the last month period. USAGE: -m\n";
	print "\t-p - upload data for period [YYYYMM;YYYYMM], if -p is empty then upload periods [201101-201410]. USAGE:-p[YYYYMM;YYYYMM] or -p\n";
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
	
	function getReportedFacilities(){
// 		select facility.external_id, facility.facility_name from facility
// 		inner join facility_report_rate on facility.external_id=facility_report_rate.facility_external_id
// 		where facility_report_rate.date='2014-11-01';
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