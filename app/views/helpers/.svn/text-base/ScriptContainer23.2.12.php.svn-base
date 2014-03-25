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

			$this->addCSSLink('/js/yui/build/reset-fonts-grids/reset-fonts-grids.css');
			$this->addCSSLink('/js/yui/build/resize/assets/skins/sam/resize.css');
			$this->addCSSLink('/js/yui/build/button/assets/skins/sam/button.css');
			$this->addCSSLink('/js/yui/build/autocomplete/assets/skins/sam/autocomplete.css');
			$this->addCSSLink('/js/yui/build/calendar/assets/skins/sam/calendar.css');
			$this->addCSSLink('/js/yui/build/container/assets/skins/sam/container.css');
			$this->addCSSLink('/js/yui/build/datatable/assets/skins/sam/datatable.css');
      $this->addCSSLink('/css/style.css');
      $this->addCSSLink('/css/calendar.css');

      $url_parts = explode('.', $_SERVER['HTTP_HOST']);
      if ( @$url_parts[0] == 'eventsmart' OR (isset($url_parts[1]) && (@$url_parts[1] == 'eventsmart')) ) {
        $this->addCSSLink('/css/style-engender.css');
      }
      
      $local = ITechTranslate::getLocale();
      $local_empty = ($local===null);
      if (!$local_empty) {
      	$this->addJSLink('/js/translation-'.$local.'.js');
      }
      	
      $this->addJSLink('/js/itech-namespace.js');
      
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

			$burl = Settings::$COUNTRY_BASE_URL;

			if (substr($burl, -1) != '/' && substr($burl, -1) != '\\')
				$burl = $burl . '/';

				
			self::$jsincludes []= '<script src="'. $burl . 'index/js-aggregate" type="text/javascript"></script>';
		}
	}

	public function addJSLink($filename) {
		$test = strstr($filename, 'translation-');
		if ($test===false)
		{
			//self::$jsfiles []= (self::$debug?str_replace('.js','-min.js',$filename):$filename); //.'?_yuiversion=2.5.0';
			self::$jsfiles []= str_replace('.js','-min.js',$filename);
		}
		else 
		{
			self::$jsfiles []= $filename;
		}
		//if ( $test !== false )
		//	self::$jsfiles []= $filename;
		//else
		//	self::$jsfiles []= (self::$debug?str_replace('.js','-min.js',$filename):$filename); //.'?_yuiversion=2.5.0';

    /*
    $localized = '<script type="text/javascript" src="'.(Settings::$COUNTRY_BASE_URL).(self::$debug?str_replace('.js','-min.js',$filename):$filename).'?_yuiversion=2.5.0"></script>';

		if ( array_search($localized, self::$jsincludes) === false )
			self::$jsincludes []= $localized;
		//*/

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