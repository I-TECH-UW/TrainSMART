<?php
/*
 * Created on Mar 11, 2008
 * 
 *  Built for itechweb  
 *  Fuse IQ -- todd@fuseiq.com
 *  
 */
class Session {

	public static function getCurrentUserId() {
        require_once('Zend/Auth.php');

    	$auth = Zend_Auth::getInstance();
		if ($auth and $auth->hasIdentity() and ($ident = $auth->getIdentity()))
        	return $auth->getIdentity()->id;
		
        return 0;
	}
}