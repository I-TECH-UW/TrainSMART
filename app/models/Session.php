<?php
/*
 * Created on Mar 11, 2008
 * 
 *  Built for itechweb  
 *  Fuse IQ -- todd@fuseiq.com
 *  
 */
class Session {
	private static $_settings = array();

	public static function getCurrentUserId() {
        require_once('Zend/Auth.php');

    	$auth = Zend_Auth::getInstance();
		if ($auth and $auth->hasIdentity() and ($ident = $auth->getIdentity()))
        	return $auth->getIdentity()->id;
		
        return 0;
	}

	public static function setSettings($settings) {
		self::$_settings = $settings;
	}

	public static function getSetting($theSetting) {
		return self::$_settings[$theSetting];
	}
}