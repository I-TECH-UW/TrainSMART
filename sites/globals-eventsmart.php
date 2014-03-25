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
 	public static $BASE_PATH = '/home/eventsma/';
 	public static $WEB_FOLDER = 'html';
	public static $COUNTRY = null;
	public static $SITE_TITLE = 'EventSMART';

 	public function __construct() {
		//set country
		try {

		$parts = explode('.', $_SERVER['HTTP_HOST']);
		if ( @$_SERVER['SERVER_NAME'] == 'localhost' ) {
			self::$COUNTRY = 'namibia';
		} else {
			//lookup subdomain from subdomain
			self::$COUNTRY = $parts[0];
		}

		//load country settings
		require_once('settings.php');
		$countryLoaded = false;

		if($parts[0] == 'www'){
			// try and sanely guess the domain, they want WWW to be a working redirect
			if(in_array('eventsmart', $parts)) {
				unset($parts[0]);
				header('Location: http://'.implode('.', $parts));
				exit();
			}
		} else if ( $parts[1] == 'eventsmart' ) {
			Settings::$DB_DATABASE = 'itechweb_eventsmart_'.$parts[0];
			self::$COUNTRY = $parts[0];
			Settings::$COUNTRY_BASE_URL = 'http://'.$parts[0].'.eventsmart.info';
			$countryLoaded = true;
		} else if ( $parts[0] == 'eventsmart' ) {
			Settings::$DB_DATABASE = 'itechweb_eventsmart';
			Settings::$COUNTRY_BASE_URL = 'http://eventsmart.info';
			$countryLoaded = true;
		}


		if ( !$countryLoaded or ($parts[0] == 'trainingdata') or ($parts[0] == 'www') ) {
			header( "Location: http://www.trainingdata.org/home.html" );
			exit();
		}
		if ( @$_SERVER['SERVER_NAME'] == 'localhost' ) {
			Settings::$COUNTRY_BASE_URL = 'http://localhost/itech/web/html';
		}

		error_reporting( E_ALL
		// | E_STRICT
		);

		// PATH_SEPARATOR =  ; for windows, : for *nix
		 	$iReturn = ini_set( 'include_path',
					(Globals::$BASE_PATH).PATH_SEPARATOR.
					(Globals::$BASE_PATH).'app'.PATH_SEPARATOR.
					(Globals::$BASE_PATH.'ZendFramework'.DIRECTORY_SEPARATOR.'library').PATH_SEPARATOR.
						ini_get('include_path'));
		require_once('Zend/Loader.php');

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
		} catch (Exception $e) {
			echo $e->getMessage();
		}
 	}
 }
try {

 new Globals();
		} catch (Exception $e) {
			echo $e->getMessage();
		}
