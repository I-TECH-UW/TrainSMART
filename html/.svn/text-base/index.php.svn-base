<?php
/**
 * Main bootstrap file for ITech app
 * Fuse IQ
 * 
 */
require_once('../sites/globals.php');

// FIX FOR apache_request_headers() function missing

if (!function_exists('apache_request_headers')) { 
    eval(' 
        function apache_request_headers() { 
            foreach($_SERVER as $key=>$value) { 
                if (substr($key,0,5)=="HTTP_") { 
                    $key=str_replace(" ","-",ucwords(strtolower(str_replace("_"," ",substr($key,5))))); 
                    $out[$key]=$value; 
                } 
            } 
            return $out; 
        } 
    '); 
}

try {
    Zend_Loader::loadClass('Zend_Controller_Front');
    $frontController = Zend_Controller_Front::getInstance(); 
    //$frontController->throwExceptions(true);
    $frontController->setControllerDirectory(Globals::$BASE_PATH.'app/controllers/');
    
    $frontController->setDefaultControllerName('index');
    $frontController->setDefaultAction('index');
    
 	$frontController->returnResponse(true);
    $response = $frontController->dispatch();
    $response->sendHeaders();
    $response->outputBody();
} catch (exception $e) {
	ob_start();
	var_export($e);
	error_log(ob_get_clean());
}
?>
