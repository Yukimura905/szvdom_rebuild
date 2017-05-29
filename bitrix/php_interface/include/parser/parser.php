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

$debug = true;
$sIniFileName = 'parser.ini.php';
$arIni = parse_ini_file(getcwd().'/'.$sIniFileName);

$logFile = $arIni['logFile'];//'parser.log';
rotateLog($arIni['log_rotation_size']);
$inputFile = $arIni['dataPath'].$arIni['xmlFile'];
$fullName = $_SERVER["DOCUMENT_ROOT"].$inputFile;

$arCurr = array();
$arCurr['time'] = time();
?>
<?
/// parse
plog();
plog('parser start');
plog('testing file:"'.$fullName.'"');
if (!file_exists($fullName)) {plog('file not found. exit(1)'); exit(1);}
$arCurr['iFileSize'] = filesize($fullName);
plog( 'file size is: ' . $arCurr['iFileSize'] .' Bytes' );
$arCurr['iFileTime'] = filemtime($fullName);
$arCurr['iAge'] =  time() - $arCurr['iFileTime']; 
plog( 'file time is: ' . date("m.d.y H:i:s ", $arCurr['iFileTime']) );
if ($arCurr['iAge'] - 7200 < 0) {plog('file too new. exit(2)'); exit(2);}
plog( 'file age is: ~' . round($arCurr['iAge'] / 3600).' Hours' );
$arCurr['iHash'] = hash_file('md5', $fullName);
plog( 'file hash: ' . $arCurr['iHash'].'' );

// load last processed file information
plog('loadind previous update session...');
$arLast = load();
if( false == $arLast){
	plog('saved session date not found. save curent session');
	save($arCurr);
}
pre($arLast);
if($arLast['iHash'] != $arCurr['iHash']){
	plog('found new date, run update...');
	save($arCurr);
}
else{
	plog('hash matches, file matches no need to update. exit (0)');
}

?>
<?
function plog($text){
		GLOBAL $logFile;
    	if($text != "")
    		$dt = date("[m.d.y H:i:s] ");
    		else
    		$dt = "-------------------------------";
		file_put_contents($logFile, $dt.$text.PHP_EOL, FILE_APPEND);
	//	echo $logFile;
	echo $text . PHP_EOL;
	}
function save($arDat, $file = 'upd.dat'){
		$strItemsDat = serialize($arDat);
		$status = file_put_contents($file, $strItemsDat);
		return $status;
	}
function load($file = 'upd.dat'){
		$strItemsDat = file_get_contents($file);
		if( false == $strItemsDat) return false;
		$arDat = unserialize($strItemsDat);
		return $arDat;
	}
function rotateLog($max_log_size){
	GLOBAL $logFile;
	$iLogSize = filesize($logFile);

	if($iLogSize < $max_log_size * 1024) return 0;
	unlink($logFile.'.old');
	rename($logFile, $logFile.'.old');
	return 1;
}
?>