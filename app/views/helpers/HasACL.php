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

class Zend_View_Helper_HasACL {
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
     * pass in an ACL value such as 'edit_people' 
     * 
	*/
    public function hasACL($acl) {
    	if (isset($this->view->identity) )
    		$ident = $this->view->identity;
    	else
    		return false;
		if (isset($ident->acls) and $ident->acls and (array_search($acl,$ident->acls) !== false) )
			return true;
    	
		return false;
    }
}