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

class Zend_View_Helper_PrintAndExport {
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

    public function printAndExport($div_id_to_print) {
    	if ( strstr($_SERVER['REQUEST_URI'], '?') !== false )
    		$munged = (str_replace('?','/outputType/csv/?',$_SERVER['REQUEST_URI']));
  	    else if ( substr($_SERVER['REQUEST_URI'], strlen($_SERVER['REQUEST_URI']) - 1, 1) == '/' )
    		$munged = substr($_SERVER['REQUEST_URI'],0,strlen($_SERVER['REQUEST_URI'])-1).'/outputType/csv';
    	else
    		$munged = $_SERVER['REQUEST_URI'].'/outputType/csv';
        //TA:80 print only report
        if($div_id_to_print){
    	echo '<script type="text/javascript">
    	    function print_part(){
    	    var printContents = document.getElementById("' . $div_id_to_print. '").innerHTML;
     var originalContents = document.body.innerHTML;
     document.body.innerHTML = printContents;
     window.print();
     document.body.innerHTML = originalContents;
    	    }
    	</script>';
        }else{
            echo '<script type="text/javascript">
    	    function print_part(){
    	    
     window.print();
     
    	    }
    	</script>';
        }
       
            
	//	return '<span class="printAndExport"><a href="javascript:window.print();">'.t('Print').'</a>&nbsp;&nbsp;<a href="'.$munged.'">'.t('Export').'</a>&nbsp;&nbsp;<a  href="'.$munged.'"><img src="'.(Settings::$COUNTRY_BASE_URL).'/images/excel.jpg" /></a></span>';
    	return '<span class="printAndExport"><a href="javascript:print_part()">'.t('Print').'</a>&nbsp;&nbsp;<a href="'.$munged.'">'.t('Export').'</a>&nbsp;&nbsp;<a  href="'.$munged.'"><img src="'.(Settings::$COUNTRY_BASE_URL).'/images/excel.jpg" /></a></span>';
    }
}