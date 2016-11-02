<?php
/*
 * Created on Mar 7, 2008
 *
 *  Built for itechweb
 *  Fuse IQ -- todd@fuseiq.com
 *
 */

/**
 * Serialized to JSON or displayed in a view
 */
class ValidationContainer {
	public $status = null;
	public $messages = array();
	public $redirect = null;
	public $obj_id = null;

	protected static $instance = null;

	public function __construct() {
		self::$instance = $this;
	}

	static public function instance() {
		if ( self::$instance )
			return self::$instance;

		return new ValidationContainer();
	}

	public function addError($fieldname, $msg) {
		$this->messages[$fieldname] = ' ' . $msg;
	}

	public function checkRequired($controller, $name, $textName) {
		$val = $controller->getRequest()->getParam($name);
		if ( ($val === null) or ($val == '') ) {
    			//$this->addError($name,' (required)');
          		$this->addError($name, $textName.' ('.t('required').')');
			return false;
		}
		return true;
	}
	
	public function checkPercentage($controller, $val, $textName) {		
		if ( $val > 100 ) {
			$this->addError($val, $textName.' ('.t(' > 100').')');
			return false;
		}
		return true;
	}

	public function isValidDate($controller, $fieldname, $textName, $dateString) {
		require_once('Zend/Date.php');

		$rtn = true;

		$parts = explode('-',$dateString);
		if ( intval($parts[1]) > 12 )
			$rtn = false;

		$parts = explode('-',$dateString);
		if ( intval($parts[2]) > 31 )
			$rtn = false;

		$parts = explode('-',$dateString);
		if ( intval($parts[0]) > 2200 )
			$rtn = false;

		$parts = explode('-',$dateString);
		if ( intval($parts[0]) < 1900 )
			$rtn = false;

		$rtn = $rtn and Zend_Date::isDate($dateString, 'Y-m-d');

		if ( !$rtn )
   			$this->addError($fieldname, $textName.' '.t('is not a valid date').'.');

		return $rtn;
	}

	public function isValidDateDDMMYYYY($fieldName, $textName, $dateString) {
        $errorMessage = null;
        try {
            $date = DateTime::createFromFormat('d/m/Y', $dateString);
        }
        catch (Exception $e) {
            $errorMessage = $textName . ' ' . t('is not a valid date');
        }

        // But wait, there's more! DateTime can interpret the date into a different day without
        // throwing an exception.
        if (!$errorMessage) {
            $errors = DateTime::getLastErrors();
            if (count($errors) && ((isset($errors['warning_count']) && $errors['warning_count'] > 0) ||
                    (isset($errors['error_count']) && $errors['error_count']) > 0)) {
                $errorMessage = $textName . ' ' . t('is not a valid date');
            }
        }

        if ($errorMessage) {
            $this->addError($fieldName, $errorMessage);
            return false;
        }
        return true;
    }

	public function hasError() {
		return count($this->messages);
	}

	public function setStatusMessage($msg) {
		$this->status .= $msg;
	}

	public function setRedirect($location, $addBasePath = true) {
		$this->redirect = ($addBasePath ? Settings::$COUNTRY_BASE_URL :'' ).$location;
	}

	public function hasRedirect() {
		return $this->redirect;
	}

	public function setObjectId($id) {
		$this->obj_id = $id;
	}
}