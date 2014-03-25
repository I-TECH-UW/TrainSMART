<?php
/*
 * Created on Mar 24, 2008
 * 
 *  Built for itechweb  
 *  Fuse IQ -- todd@fuseiq.com
 *
 * Creates an editable YUI DataTable
 *  
 */

class Zend_View_Helper_IsLoggedIn {
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
    
    public function isLoggedIn() {
		$isLoggedIn = ( isset($this->view->identity->id) and $this->view->identity->id );
    	return $isLoggedIn;
    }
}