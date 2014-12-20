<?php

/**
 * @param $requireACL
 * @param $linkURL
 * @param $view
 * @return null|string
 */
function hasACLEdit($requireACL, $linkURL, &$view)
{
  if(! $view->hasACL('edit_country_options') && ! $view->hasACL($requireACL))
    return null;

  return '<a href="'.$view->base_url.'/'.$linkURL.'" onclick="submitThenRedirect(\''.$view->base_url.'/'.$linkURL.'\');return false;">'.t('Edit').'</a>';
}

/**
 * @param $criteria
 * @return string
 */
function implode_criteria_to_url (&$criteria)
{
	if ( empty($criteria) )
		return '';
	$o = '';
	foreach($criteria as $k => $v){
		if ( empty($v) )
			continue;
		if ( is_array($v) ) {
			foreach($v as $item)
				$o .= $k."[]=$item&"; 
		} else {
			$o .= "$k=$v&"; 
		}
	}
	return $o;
}

/**
 * get a list of ids from a result set, and return them in url form ids/1,2,3,4&
 * @param $results
 * @return string
 */
function implode_ids (&$results)
{
	if ( empty($results) )
		return '';
	$o = '';
	foreach($results as $row){
		if ($row['id'])
			$o .= $row['id'].',';
	}
	$len = strlen($o);
	if( $len )
		return 'ids/'.substr( $o, 0, $len-1 );
	else
		return '';
}

/**
 * what organizers' trainings can we see (by user)
 * @param $itechthis
 * @return array|bool
 */
function allowed_organizer_access (&$itechthis)
{
	require_once('models/table/MultiOptionList.php');

	$allowIds = false;
	if (! $itechthis->hasACL ( 'training_organizer_option_all' )) {
		$allowIds = array();
		$user_id = $itechthis->isLoggedIn();
		$training_organizer_array = MultiOptionList::choicesList ( 'user_to_organizer_access', 'user_id', $user_id, 'training_organizer_option', 'training_organizer_phrase', false, false );
		foreach ( $training_organizer_array as $orgOption ) {
			if ($orgOption ['user_id'])
				$allowIds [] = $orgOption ['id'];
		}
	}

	return $allowIds;
}

/**
 * get allowed_organizer_access() as array then return as a comma separated list
 * @param $itechthis
 * @return bool|string
 */
function allowed_org_access_full_list (&$itechthis) {
	$orgs = allowed_organizer_access($itechthis);
	return $orgs ? implode(',', $orgs) : false;
}



/**
 * return a comma separated list of organizers allowed in this site (site rollup feature)
 * @param $itechthis
 * @return bool|string
 */
function allowed_organizer_in_this_site(&$itechthis)
{
	// determine site
	$parts = explode('.', $_SERVER['SERVER_NAME']); // same style as globals.php
	$me = $GLOBALS->$COUNTRY ? $GLOBALS->$COUNTRY : $parts[0];

	// get child sites
	$db = Zend_Db_Table_Abstract::getDefaultAdapter ();
	$orgs = $db->fetchOne("select organizer_access from datashare_sites where db_name='$me' limit 1"); //todo
	$orgs = trim($orgs);
	#return $orgs ? implode(',', $orgs) : false;
	return $orgs ? $orgs : false;

}