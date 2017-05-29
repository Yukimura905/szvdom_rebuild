#!/usr/bin/php
<?
$_SERVER["DOCUMENT_ROOT"] = realpath(dirname(__FILE__)."/../../../..");
$DOCUMENT_ROOT = $_SERVER["DOCUMENT_ROOT"];

define("NO_KEEP_STATISTIC", true);
define("NOT_CHECK_PERMISSIONS",true); 
define('CHK_EVENT', true);

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

@set_time_limit(0);
@ignore_user_abort(true);


$parser2 = new abcParser2;

$parser2->buildOldSectionArray();
$parser2->buildNewSectionArray();
$parser2->updateIBObjects();
flush();

$parser2->buildNewApptArray();
$parser2->buildOldApptArray();
$parser2->updateIBFlats();

//$parser2->parseObjects();


unset($parser2);


?>
