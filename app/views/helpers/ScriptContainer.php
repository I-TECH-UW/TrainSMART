<?php
/*
 * Created on Mar 7, 2008
 *
 *  Built for itechweb
 *  Fuse IQ -- todd@fuseiq.com
 *
 */
require_once('ITechTranslate.php');

class ScriptContainer {

	public static $jsfiles = array();

    public static $jsincludes = array();
	public static $cssincludes = array();

	public static $debug = true;

	public static $instance = null;

	public function __construct() {
		if ( self::$instance == null ) {
			self::$instance = $this;

			# CSS YUI
			$this->addCSSLink('/js/yui/build/reset-fonts-grids/reset-fonts-grids.css');
			$this->addCSSLink('/js/yui/build/resize/assets/skins/sam/resize.css');
			$this->addCSSLink('/js/yui/build/button/assets/skins/sam/button.css');
			$this->addCSSLink('/js/yui/build/autocomplete/assets/skins/sam/autocomplete.css');
			$this->addCSSLink('/js/yui/build/calendar/assets/skins/sam/calendar.css');
			$this->addCSSLink('/js/yui/build/container/assets/skins/sam/container.css');
			$this->addCSSLink('/js/yui/build/datatable/assets/skins/sam/datatable.css');
			
			#CSS 
			$this->addCSSLink('/css/style.css');
			$this->addCSSLink('/css/calendar.css');
			$this->addCSSLink('/css/media/demo_page.css');
			$this->addCSSLink('/css/media/demo_table_jui.css');
			$this->addCSSLink('/css/media/jquery-ui-1.8.17.custom.css');
			$this->addCSSLink('/css/jquery.tabledrag.css');

			require_once('models/Session.php');
			if (Session::getSetting('site_style') === 'eventsmart') {
				$this->addCSSLink('/css/style-engender.css');
			}

			# TransLations
			$locale = ITechTranslate::getLocale();
			if ( $locale !== null ) {
				$this->addJSLink('/js/translation-'.$locale.'.js');
			}

			# Javascript - AUTOMATICALLY appends -min
			$this->addJSLink('/js/itech-namespace.js');

			# jQuery
			$this->addJSLink('/js/scripts/jquery-1.7.1-min.js');
			$this->addJSLink('/js/scripts/jquery.dataTables.min.js');
			$this->addJSLink('/js/scripts/jquery-ui-1.8.17.custom-min.js');	

			# Javascript - PRESERVICE
			$this->addJSLink('/js/scripts/validate/jquery.validate.js');
			$this->addJSLink('/js/scripts/jquery.comboedit.js');
			$this->addJSLink('/js/scripts/preservice.js');

			# Javascript - YUI
			$this->addJSLink('/js/yui/build/yahoo-dom-event/yahoo-dom-event.js');
			$this->addJSLink('/js/yui/build/connection/connection.js');
			$this->addJSLink('/js/yui/build/animation/animation.js');
			$this->addJSLink('/js/yui/build/autocomplete/autocomplete.js');
			$this->addJSLink('/js/yui/build/utilities/utilities.js');
			$this->addJSLink('/js/yui/build/datasource/datasource-beta.js');
			$this->addJSLink('/js/yui/build/datatable/datatable-beta.js');
			$this->addJSLink('/js/yui/build/dragdrop/dragdrop.js');
			$this->addJSLink('/js/yui/build/button/button.js');
			$this->addJSLink('/js/yui/build/calendar/calendar.js');
			$this->addJSLink('/js/yui/build/element/element-beta.js');
			$this->addJSLink('/js/yui/build/json/json.js');

			# Javascript - TrainSMART
			$this->addJSLink('/js/flydown.js');
			$this->addJSLink('/js/yui/build/container/container.js'); //add me last
			$this->addJSLink('/js/statusbox.js');
			$this->addJSLink('/js/ajaxsubmit.js');
			$this->addJSLink('/js/autocomplete.js');
			$this->addJSLink('/js/calendar.js');
			$this->addJSLink('/js/datatable.js');
			$this->addJSLink('/js/edittable.js');
			$this->addJSLink('/js/edittable-training.js');
			$this->addJSLink('/js/fileupload.js');
			$this->addJSLink('/js/itech.js');
			$this->addJSLink('/js/dropdown.js');
			$this->addJSLink('/js/jquery.tabledrag.js');

			$burl = Settings::$COUNTRY_BASE_URL;

			if (substr($burl, -1) != '/' && substr($burl, -1) != '\\')
				$burl = $burl . '/';

			self::$jsincludes []= '<script src="'. $burl . 'index/js-aggregate" type="text/javascript"></script>';
		}
	}

	public function addJSLink($filename) {
		$test = (strstr($filename, 'translation-') || strstr($filename, '/scripts/'));
		if ($test===false)
		{
			self::$jsfiles []= str_replace('.js','-min.js',$filename);
		}
		else 
		{
			self::$jsfiles []= $filename;
		}
	}

	public function addCSSLink($filename) {
		$localized = '<link rel="stylesheet" type="text/css" href="'.(Settings::$COUNTRY_BASE_URL).$filename.'" />';

		if ( array_search($localized, self::$jsincludes) === false )
			self::$jsincludes []= $localized;
	}

	public function renderJSHead() {
		return implode("\n",self::$jsincludes);
	}

	public function renderCSSHead() {
		return implode("\n",self::$cssincludes);
	}
}

new ScriptContainer();