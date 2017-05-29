#!/usr/bin/php
<?
$_SERVER["DOCUMENT_ROOT"] = realpath(dirname(__FILE__)."/..");
$DOCUMENT_ROOT = $_SERVER["DOCUMENT_ROOT"];

define("NO_KEEP_STATISTIC", true);
define("NOT_CHECK_PERMISSIONS",true); 
define('CHK_EVENT', true);

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

@set_time_limit(0);
@ignore_user_abort(true);

?>
<?
// db connect
$dblocation = "localhost"; // Имя сервера
$dbuser = "root";          // Имя пользователя
$dbpasswd = "aef0Oogh5oezeGha";            // Пароль
$dbname = "imp_data";

$dbcnx = @mysql_connect($dblocation,$dbuser,$dbpasswd);
if (!$dbcnx) // Если дескриптор равен 0 соединение не установлено
{
  echo("<P>В настоящий момент сервер базы данных не доступен, поэтому 
           корректное отображение страницы невозможно.</P>");
  exit();
}
if (!@mysql_select_db($dbname, $dbcnx)) 
{
  echo( "<P>В настоящий момент база данных не доступна, поэтому
            корректное отображение страницы невозможно.</P>" );
  exit();
}
    mysql_query('SET NAMES cp1251', $dbcnx);          
    mysql_query('SET CHARACTER SET utf8', $dbcnx);  
    mysql_query('SET COLLATION_CONNECTION="utf8_general_ci"', $dbcnx);


function get_text($hh_blocks){
	$arText = array();
	$ath = mysql_query("select * from mp_themes_pages where index_id = 40 AND hh_blocks = ".$hh_blocks);
	while($article = mysql_fetch_array($ath)){
		//pre($article);
		$arText[] = Array('name' => $article['name'], 'text' => $article['text'], 'id' => $article['id']);
		//		echo $article['name'].'<br>';
	}
	return $arText;
}
function get_img($hh_blocks){
	$arText = array();
	$ath = mysql_query("select * from mp_hh_blocks_slider where  blocks_id = ".$hh_blocks);
	while($article = mysql_fetch_array($ath)){
		//pre($article);
		$arText[] = Array('alt' => $article['alt'], 'img' => $article['img'], 'id' => $article['id']);
		//		echo $article['name'].'<br>';
	}
	return $arText;
}
?>
<?
//$arTest = CFile::MakeFileArray("http://hh-an.ru/hh:img/128/tn:blocks_slider/fn:img/w:1100/h:826/c:1/null/img.jpg");
//pre($arTest);

?>
<?
mylog();
CModule::IncludeModule('iblock');
//http://hh-an.ru/hh:img/125/tn:blocks_slider/fn:img/w:1100/h:826/null/img.jpg
$src = "http://hh-an.ru/hh:img/127/tn:blocks_slider/fn:img/w:1100/h:826/c:1/null/img.jpg";
//addImg($arImg, 197507, 95 );

$arFilter = Array(
 "IBLOCK_ID"=>1, 
 "ACTIVE"=>"Y"
 );
$count = 50000000;
$res = CIBlockElement::GetList(Array(), $arFilter, false, Array(), $arSelect);
while($ob = $res->GetNextElement()){ 
	$count--; if($count <= 0) break;
	$arFields = $ob->GetFields();  
	//pre($arFields);
 	$arProps = $ob->GetProperties();
	//pre($arProps);

	$arTexts = get_text($arProps['SER_XML_ID']['VALUE']);
	//pre($arTexts);
	foreach($arTexts as $arText)
		addtext($arText, $arProps['SER_XML_ID']['VALUE']);
	}

	//$arImages = get_img($arProps['SER_XML_ID']['VALUE']);
	//echo $arFields['ID'].'<br>';
	//pre($arImages);
	//addImg($arImages, $arFields['ID']);
//}

function addImg($arImages, $el_id){
	//pre($arImages);
	foreach($arImages as $arImg){
	 	$src = "http://hh-an.ru/hh:img/".$arImg['id']."/tn:blocks_slider/fn:img/w:550/h:413/c:1/null/img.jpg";
		$rnd = rand(1000,9999);
		$loaded = 'load'.$rnd.'.jpg';
		$resized = 'resz'.$rnd.'.jpg';
		copy($src, $loaded);
	 $rif = CFile::ResizeImageFile( // уменьшение картинки для превью
		$sourceFile = $loaded,
		$destinationFile =  $resized,
		$arSize = array('width'=>550, 'height'=>413),
		$resizeType = BX_RESIZE_IMAGE_PROPORTIONAL,
		$arWaterMark = array(),
		$jpgQuality=45
	);

		$arReadyFile = CFile::MakeFileArray($resized);
		//      unlink($resized);
				unlink($loaded);

		$arFiles[] = array("VALUE" => $arReadyFile,"DESCRIPTION"=>$arImg['alt']);
	}
	//	pre($arFiles);
	mylog($el_id . ' : ' . count($arFiles) );

	CIBlockElement::SetPropertyValueCode($el_id, 'MORE_IMG', $arFiles);

	foreach($arFiles as $arFile)
		unlink($arFile['VALUE']['name']);

}

function addtext($arText, $block_id){
	//pre($arText);
	$el = new CIBlockElement;

	$PROP = array();
	$PROP['BLOCK_ID'] = $block_id;
	$PROP['OLD_REC_ID'] = $arText['id'];

	$arLoadProductArray = Array(
		//"MODIFIED_BY"    => $USER->GetID(), // элемент изменен текущим пользователем
	  "IBLOCK_SECTION_ID" => false,          // элемент лежит в корне раздела
	  "IBLOCK_ID"      => 6,
	  "PROPERTY_VALUES"=> $PROP,
		"NAME"           =>  $arText['name'] == '' ? 'noname':$arText['name'],
	  "ACTIVE"         => "Y",            // активен
	  "PREVIEW_TEXT"   => "***",
		"DETAIL_TEXT"    =>  $arText['text'],
	 "DETAIL_TEXT_TYPE" => "HTML"
		//"DETAIL_PICTURE" => CFile::MakeFileArray($_SERVER["DOCUMENT_ROOT"]."/image.gif")
	  );
	//return;
	if($PRODUCT_ID = $el->Add($arLoadProductArray))
	  echo "New ID: ".$PRODUCT_ID;
	else
	  echo "Error: ".$el->LAST_ERROR;
	echo '<br>';
}
function mylog($text){
    	if($text != "")
    		$dt = date("[m.d.y H:i:s] ");
    		else
    		$dt = "-------------------------------";
		file_put_contents('import.log', $dt.$text.PHP_EOL, FILE_APPEND);
		echo $text . PHP_EOL;
	}
?>
