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


$parser = new abcParser;
$parser->getCurentObjecktsList();
//$parser->clearAll();
$parser->insertObjekts();
$parser->showBlockStat();
unset($parser);


?>
<?
//phpinfo();
/*
$name = "Текст*89";
$arParams = array("replace_space"=>"-","replace_other"=>"-");
$trans = Cutil::translit($name,"ru",$arParams);
echo '<pre>';
var_dump($trans);
echo '</pre>';
*/
?>

