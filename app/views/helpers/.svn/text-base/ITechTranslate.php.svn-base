<?php
require_once('Zend/Translate.php');
require_once('controllers/ITechController.php');
/**
 * Translate
 *
 * @param str $string
 * @return str
 */
function t($string) {
	//first check our label list for the phrase
	$t = ITechController::translations();

	//see if the label has been modified in country settings
	$label_change = null;
	if ( $t && isset($t[$string]))
	 $label_change = $t[$string];

	//translate using the original label
	$translated = "";
	if ( ITechTranslate::$instance )
		$translated = ITechTranslate::$instance->translate($string);

	if (!$translated)
		return $string;

	//if we didn't translate anything then return the label
	if ( ($translated == $string) && $label_change)
	 return $label_change;

	//otherwise return translated
	if ( $translated )
	 return $translated;

	return $string;
}

/**
 * Print translated string
 *
 * @param str $string
 */
function tp($string) {
	print t($string);
}

class ITechTranslate extends Zend_Translate {

	public static $instance = null;

	private $locale = null;

	function __construct() {
		parent::__construct('gettext',Globals::$BASE_PATH.'/translations/en_EN.UTF-8/LC_MESSAGES/itech.mo','en_EN.UTF-8');
	}

	static function init($locale) {

		self::$instance = new ITechTranslate();

		self::$instance->addTranslation(Globals::$BASE_PATH.'/translations/'.$locale.'/LC_MESSAGES/itech.mo',$locale);

		self::$instance->setLocale($locale);
		setlocale(LC_TIME, str_replace(".UTF-8","",$locale) ); //hack to get date conversion to work. Use fr_FR without the .UTF-8
		self::$instance->locale = $locale;


	}


	static function getLocale() {
		if (!isset(ITechTranslate::$instance)) return null;
		return ITechTranslate::$instance->locale;
	}

  /**
   * Returns array of locale codes available to site instance
   */
	static function getLocaleEnabled() {
    require_once('models/table/System.php');
    $sysTable = new System();
		return explode(',', $sysTable->getSetting('locale_enabled'));
	}


	/**
	 * Returns a list of enabled languages
	 *
	 * @param unknown_type $all
	 * @return unknown
	 */
	static function getLanguages($all = false) {

		return array(
			'en_EN.UTF-8' => 'English',
			'nl_NL.UTF-8' => 'Dutch',
			'fr_FR.UTF-8' => 'French',
			'ru_RU.UTF-8' => 'Russian',
			'uk_UK.UTF-8' => 'Ukrainian',
			'es_ES.UTF-8' => 'Spanish',
			'pt_PT.UTF-8' => 'Portuguese'
		);
	}

	/**
	 * Translate a database field name to a readable word or phrase
	 *
	 * @param unknown_type $db_field_name
	 */
	static function db_tr($db_field_name) {
		switch($db_field_name) {
  			case 'first_name':
  				return t('First Name');
  				break;
			case 'middle_name':
  				return t('Middle Name');
  				break;
			case 'last_name':
  				return t('Last Name');
  				break;
			case 'national_id':
  				return t('National ID');
  				break;
			case 'file_number':
  				return t('File Number');
  				break;
			case 'facility_name':
  				return t('Facility');
  				break;
 			case 'birthdate':
  				return t('Birthdate');
  				break;
 			case 'gender':
  				return t('Gender');
  				break;
			case 'phone_work':
  				return t('Phone (work)');
  				break;
			case 'phone_home':
  				return t('Phone (home)');
  				break;
			case 'phone_mobile':
  				return t('Phone (mobile)');
  				break;
			case 'fax':
  				return t('Fax');
  				break;
 			case 'email':
 			case 'email_secondary':
  				return t('Email');
  				break;
 			case 'comments':
  				return t('Comments');
  				break;
  			case 'timestamp_created':
  				return t('Date');
  				break;
			case 'is_deleted':
  				return t('Deleted');
  				break;
			case 'active':
  				return t('Active');
  				break;
  		case 'responsibility_phrase':
 				return t('Responsibility');
  				break;
 			case 'qualification_phrase':
 				return t('Qualification');
  				break;
  			case 'home_address_1':
 			case 'home_address_2':
 			case 'home_postal_code':
  				return t('Address');
  				break;
			case 'title_option_id':
				return t('Title');
				break;
			case 'suffix_option_id':
				return t('Suffix');
				break;
			case 'person_custom_1_option_id':
				return t('People Custom 1');
				break;
			case 'person_custom_2_option_id':
				return t('People Custom 2');
				break;
			case 'home_location_id':
				return t('Home Location');
				break;
			case 'active':
				return t('Active');
				break;
			case 'me_responsibility':
				return t('M&E Responsibility');
				break;
			case 'highest_edu_level_option_id':
				return t('Highest Education Level');
				break;
			case 'attend_reason_option_id':
				return t('Reason Attending');
				break;
			case 'attend_reason_other':
				return t('Reason Attending');
				break;
			case 'highest_level_option_id':
				return t('Highest Education Level');
				break;
			case 'approved':
				return t('Approved');
				break;
			case 'custom_3':
				return t('People Custom 3');
				break;
			case 'custom_4':
				return t('People Custom 4');
				break;
			case 'custom_5':
				return t('People Custom 5');
				break;
  		}

  		return $db_field_name;
	}

}

?>