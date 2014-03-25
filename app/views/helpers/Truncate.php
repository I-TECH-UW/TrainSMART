<?php
/*
 * Created on Mar 24, 2008
 * 
 *  Built for itechweb  
 *  Fuse IQ -- todd@fuseiq.com
 *
 * Shorten a string
 *  
 */

class Zend_View_Helper_Truncate {
    /**
     * @var Zend_View_Interface
     */  
    public $view;  

        /**
     * Set view
     * 
     * @param  Zend_View_Interface $view 
     * @return void
     */  
    public function setView(Zend_View_Interface $view)  
    {  
        $this->view = $view;  
    }  
    
    /*
     * pass in a string and a character limit
     * 
	*/
    public static function truncate($str, $limit = 55) {
    	if ( is_string($str) ) {
    		$sub = substr($str,0,$limit);
    		if ( strlen($sub) < strlen($str) and strlen($sub) == $limit )
    			return $sub.'&hellip;';
    		else
    			return $str;
    	}
    	
    	return $str;
    }
}