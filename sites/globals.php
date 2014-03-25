<?php
/*
 * Created on Feb 11, 2008
 *
 *  Built for web
 *  Fuse IQ -- todd@fuseiq.com
 *
 */

ini_set('max_execution_time','300');
ini_set('memory_limit', '1024M');

define('space',  " ");

 class Globals {
 	public static $BASE_PATH = '/home/training/';
 	public static $WEB_FOLDER = 'html';
	public static $COUNTRY = null;
	public static $SITE_TITLE = 'TrainSMART';

 	public function __construct() {
		//set country
		//lookup subdomain from subdomain
		$parts = explode('.', $_SERVER['HTTP_HOST']);
		self::$COUNTRY = $parts[0];

		/*
		// load country settings for multiple subdomains
		$countryFile = self::$BASE_PATH.'sites/'.self::$COUNTRY.'/settings.php';
		$countryLoaded = false;
		if (file_exists($countryFile) and self::$COUNTRY) {
			require_once($countryFile);
			$countryLoaded = true;
		}
		*/

		require_once('settings.php');
		$countryLoaded = false;

		// our site specific settings by domain, subdomain
		if ( $parts[1] == 'trainingdata' ) {
			Settings::$DB_DATABASE = 'itechweb_'.$parts[0];
			self::$COUNTRY = $parts[0];
			Settings::$COUNTRY_BASE_URL = 'http://'.$parts[0].'.trainingdata.org';
			$countryLoaded = true;
		}
		else if ( $_SERVER['HTTP_HOST'] == 'jhpiego.ethiopia.trainingdata.org') {
			Settings::$DB_DATABASE      = 'itechweb_jhpiego_ethiopia';
			Settings::$COUNTRY_BASE_URL = 'http://jhpiego.ethiopia.trainingdata.org';
			$countryLoaded = true;
		}
		else if ( $_SERVER['HTTP_HOST'] == 'jhpiego.southsudan.trainingdata.org') {
			Settings::$DB_DATABASE      = 'itechweb_jhpiego_southsudan';
			Settings::$COUNTRY_BASE_URL = 'http://jhpiego.southsudan.trainingdata.org';
			$countryLoaded = true;
		}
		else if ( $_SERVER['HTTP_HOST'] == 'jhpiego.angola.trainingdata.org') {
			Settings::$DB_DATABASE      = 'itechweb_jhpiego_angola';
			Settings::$COUNTRY_BASE_URL = 'http://jhpiego.angola.trainingdata.org';
			$countryLoaded = true;
		}
		else if ( $_SERVER['HTTP_HOST'] == 'jhpiego.tanzania.trainingdata.org') {
			Settings::$DB_DATABASE      = 'itechweb_jhpiego_tanzania';
			Settings::$COUNTRY_BASE_URL = 'http://jhpiego.tanzania.trainingdata.org';
			$countryLoaded = true;
		}
		else if ( $_SERVER['HTTP_HOST'] == 'jhpiego.gates-implants.trainingdata.org') {
			Settings::$DB_DATABASE      = 'itechweb_jhpiego_gates-implants';
			Settings::$COUNTRY_BASE_URL = 'http://jhpiego.gates-implants.trainingdata.org';
			$countryLoaded = true;
		}
		else if ( $_SERVER['HTTP_HOST'] == 'jhpiego.pakistan.trainingdata.org') {
			Settings::$DB_DATABASE      = 'itechweb_jhpiego_pakistan';
			Settings::$COUNTRY_BASE_URL = 'http://jhpiego.pakistan.trainingdata.org';
			$countryLoaded = true;
		}

		// drupal site redirect
		if ( !$countryLoaded or ($parts[0] == 'trainingdata') or ($parts[0] == 'www') ) {
			header( "Location: http://www.trainingdata.org/home/" );
			exit();
		}
		// development environment
		if ( @$_SERVER['SERVER_NAME'] == 'localhost' ) {
			Settings::$COUNTRY_BASE_URL = 'http://localhost/itech/web/html';
		}

		// globals
		error_reporting( E_ALL ^ E_NOTICE
			// | E_STRICT
		);

		// PATH_SEPARATOR =  ; for windows, : for *nix
	 	$iReturn = ini_set( 'include_path',
					(Globals::$BASE_PATH).PATH_SEPARATOR.
					(Globals::$BASE_PATH).'app'.PATH_SEPARATOR.
					(Globals::$BASE_PATH.'ZendFramework'.DIRECTORY_SEPARATOR.'library').PATH_SEPARATOR.
						ini_get('include_path'));

		require_once 'Zend/Loader.php';

 		if ( $countryLoaded ) {
			require_once 'Zend/Db.php';

			//set a default database adaptor
			$db = Zend_Db::factory('PDO_MYSQL', array(
				'host'     => Settings::$DB_SERVER,
				'username' => Settings::$DB_USERNAME,
				'password' => Settings::$DB_PWD,
				'dbname'   => Settings::$DB_DATABASE
			));

			 require_once 'Zend/Db/Table/Abstract.php';
			Zend_Db_Table_Abstract::setDefaultAdapter($db);
		}
 	}
 }

 new Globals();
