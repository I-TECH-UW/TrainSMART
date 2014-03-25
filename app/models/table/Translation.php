<?php
/*
 * Created on Feb 14, 2008
 *
 *  Built for web
 *  Fuse IQ -- todd@fuseiq.com
 *
 */

require_once('ITechTable.php');

/**
 * Not really a translation, but rather a keyword lookup from the database.
 *
 */
class Translation extends ITechTable
{
	protected $_primary = 'id';
  protected $_name = 'translation';

  public static function getAll()
  {
    $tTable = new Translation();
    $select = $tTable->select()->where("is_deleted = 0");
    $rows = $tTable->fetchAll($select);

  	$rowArray = $rows->toArray();
  	foreach($rowArray as $key => $row) {
	   if ( ITechTranslate::getLocale() == 'en_EN.UTF-8')
	    	$trans[$row['key_phrase']] = $row['phrase'];
	    else
  	   	$trans[$row['key_phrase']] = t($row['phrase']);
  	}

  	return $trans;
  }

  public static function translate($key_phrase)
  {
    $tTable = new Translation();
    $select = $tTable->select()->where("key_phrase = '$key_phrase'");

    $db_phrase = $tTable->fetchRow($select)->phrase;

    if ( ITechTranslate::getLocale() == 'en_EN.UTF-8')
    	return $db_phrase;
    else
    	return t($db_phrase);
  }

}
