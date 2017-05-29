<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
$arResult = array();

// restore saved filter stat
//pre($_SESSION);
//echo 'work';
// reset filter paramets
if( $_REQUEST['arrFilReset'] == 1){
	unset($_SESSION['arrFilter']);
	unset($_SESSION['arrFilterArr']);
}

if( count($_SESSION['arrFilterArr']) and !isset($_REQUEST['set_filter']) and !isset($_REQUEST['tag']) )
	$_REQUEST = array_merge($_REQUEST, $_SESSION['arrFilterArr']);


if( isset($_REQUEST['tag']) ) $_SESSION['tag'] = $_REQUEST['tag'];
if( isset($_REQUEST['set_filter']) or $_REQUEST['arrFilReset'] == 1 ) unset( $_SESSION['tag'] );




if (!CModule::IncludeModule("iblock")) die("Error: Cannot load module");
$property_enums = CIBlockPropertyEnum::GetList(Array("DEF"=>"DESC", "SORT"=>"ASC"), Array("IBLOCK_ID"=>1, "CODE"=>"APART_TYPES"));
while($enum_fields = $property_enums->GetNext())
{
	//  echo $enum_fields["ID"]." - ".$enum_fields["VALUE"]."<br>";
	$prop[$enum_fields["XML_ID"]] = $enum_fields["VALUE"];
}
//pre($prop);
$arResult['MIN_PRICE'] = 100000000;
$arResult['MAX_PRICE'] = 0;
$rsResult=CIBlockElement::GetList( array(), array("IBLOCK_ID"=>1, "ACTIVE"=>"Y"), false, false, array("PROPERTY_APART_PRICE_MAX", "PROPERTY_APART_PRICE_MIN") );
while($arData=$rsResult->Fetch()){
	//echo "<pre>".print_r($arData,true)."</pre>";
	if($arResult['MAX_PRICE'] < $arData["PROPERTY_APART_PRICE_MAX_VALUE"]) $arResult['MAX_PRICE'] = $arData["PROPERTY_APART_PRICE_MAX_VALUE"];
	if($arResult['MIN_PRICE'] > $arData["PROPERTY_APART_PRICE_MIN_VALUE"]) $arResult['MIN_PRICE'] = $arData["PROPERTY_APART_PRICE_MIN_VALUE"];
	//pre($arData);
}
$arResult['MIN_PRICE'] = (int)($arResult['MIN_PRICE']/1000);
$arResult['MAX_PRICE'] = (int)($arResult['MAX_PRICE']/1000);

//pre($_REQUEST);
$arResult["FORM"]["SearchLine"] = $_REQUEST['arrFilter_SearchLine'];
$arResult["FORM"]["Price_MIN"] = is_numeric($_REQUEST['arrFilter_Price_MIN'])?$_REQUEST['arrFilter_Price_MIN'] : $arResult['MIN_PRICE'];
$arResult["FORM"]["Price_MAX"] = is_numeric($_REQUEST['arrFilter_Price_MAX'])?$_REQUEST['arrFilter_Price_MAX'] : $arResult['MAX_PRICE'];
$arResult["FORM"]["FlatsType_F1"] = $_REQUEST['arrFilter_FlatsType_F1'];
$arResult["FORM"]["FlatsType_F2"] = $_REQUEST['arrFilter_FlatsType_F2'];
$arResult["FORM"]["FlatsType_F3"] = $_REQUEST['arrFilter_FlatsType_F3'];
$arResult["FORM"]["FlatsType_F4"] = $_REQUEST['arrFilter_FlatsType_F4'];
$arResult["FORM"]["FlatsType_F5"] = $_REQUEST['arrFilter_FlatsType_F5'];
$arResult["FORM"]["Year"] = $_REQUEST['arrFilter_Year'];
//$arResult["FORM"]["SearchLine"] = $_REQUEST['arrFilter_SearchLine'];

/*
Array
(
    [arrFilter_SearchLine] => 656565
    [arrFilter_Price_MIN] => 196000
    [arrFilter_Price_MAX] => 201780049
    [arrFilter_FlatsType_F1] => on
    [arrFilter_FlatsType_F2] => on
    [arrFilter_FlatsType_F3] => on
    [arrFilter_FlatsType_F4] => on
    [arrFilter_FlatsType_F5] => on
    [arrFilter_Year] => 2018
)
*/
//pre($arResult);

if($this->StartResultCache())
{
 $this->IncludeComponentTemplate();
}
?>