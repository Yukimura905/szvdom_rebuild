<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Акциии и скидки");
?>

<!-- form -->
<?
if(isset($_REQUEST['district']) and $_REQUEST['district'] != 'All')
	$arrFilter["PROPERTY_5_VALUE"] = $_REQUEST['district'];
if(isset($_REQUEST['metro']) and $_REQUEST['metro'] != 'All')
	$arrFilter["PROPERTY_7_VALUE"] = $_REQUEST['metro'];
//if(isset($_REQUEST['fuckYou3']) and $_REQUEST['fuckYou3'] != 'All')
//	$arrFilter["PROPERTY_10_VALUE"] = $_REQUEST['fuckYou3'];
if(isset($_REQUEST['price-from']))
	$arrFilter[">=PROPERTY_APART_PRICE_MAX"] = $_REQUEST['price-from'];

$arMarksTypes = array();
//array_push($arMarksTypes, 'Участвует'); 
array_push($arMarksTypes, 'Да'); 
//$arrFilter["PROPERTY_MARK_ACTION_VALUE"] = $arMarksTypes;
$arrFilter["PROPERTY_OPT_SHOW_ON_OFFER_VALUE"] = $arMarksTypes;

?>
<?
//$arrFilter=Array("PROPERTY_MARK_ACTION_VALUE"=>"Да");
?>
<?$APPLICATION->IncludeComponent(
	"bitrix:catalog.section",
	"list-special",
	Array(
		"ACTION_VARIABLE" => "action",
		"ADD_PROPERTIES_TO_BASKET" => "Y",
		"ADD_SECTIONS_CHAIN" => "N",
		"AJAX_MODE" => "N",
		"AJAX_OPTION_ADDITIONAL" => "",
		"AJAX_OPTION_HISTORY" => "N",
		"AJAX_OPTION_JUMP" => "N",
		"AJAX_OPTION_STYLE" => "Y",
		"BACKGROUND_IMAGE" => "-",
		"BASKET_URL" => "/personal/basket.php",
		"BROWSER_TITLE" => "-",
		"CACHE_FILTER" => "N",
		"CACHE_GROUPS" => "Y",
		"CACHE_TIME" => "36000000",
		"CACHE_TYPE" => "A",
		"COMPONENT_TEMPLATE" => "list1",
		"COMPOSITE_FRAME_MODE" => "A",
		"COMPOSITE_FRAME_TYPE" => "AUTO",
		"DETAIL_URL" => "",
		"DISABLE_INIT_JS_IN_COMPONENT" => "N",
		"DISPLAY_BOTTOM_PAGER" => "Y",
		"DISPLAY_TOP_PAGER" => "N",
		"ELEMENT_SORT_FIELD" => "sort",
		"ELEMENT_SORT_FIELD2" => "id",
		"ELEMENT_SORT_ORDER" => "asc",
		"ELEMENT_SORT_ORDER2" => "desc",
		"FILTER_NAME" => "arrFilter",
		"IBLOCK_ID" => "1",
		"IBLOCK_TYPE" => "objects",
		"INCLUDE_SUBSECTIONS" => "Y",
		"LINE_ELEMENT_COUNT" => "3",
		"MESSAGE_404" => "",
		"MESS_BTN_ADD_TO_BASKET" => "В корзину",
		"MESS_BTN_BUY" => "Купить",
		"MESS_BTN_DETAIL" => "Подробнее",
		"MESS_BTN_SUBSCRIBE" => "Подписаться",
		"MESS_NOT_AVAILABLE" => "Нет в наличии",
		"META_DESCRIPTION" => "-",
		"META_KEYWORDS" => "-",
		"OFFERS_LIMIT" => "5",
		"PAGER_BASE_LINK_ENABLE" => "N",
		"PAGER_DESC_NUMBERING" => "N",
		"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
		"PAGER_SHOW_ALL" => "N",
		"PAGER_SHOW_ALWAYS" => "N",
		"PAGER_TEMPLATE" => ".default",
		"PAGER_TITLE" => "Акции",
		"PAGE_ELEMENT_COUNT" => "30",
		"PARTIAL_PRODUCT_PROPERTIES" => "N",
		"PRICE_CODE" => array(),
		"PRICE_VAT_INCLUDE" => "Y",
		"PRODUCT_ID_VARIABLE" => "id",
		"PRODUCT_PROPERTIES" => array(),
		"PRODUCT_PROPS_VARIABLE" => "prop",
		"PRODUCT_QUANTITY_VARIABLE" => "",
		"PROPERTY_CODE" => array(0=>"SER_XML_ID",1=>"SER_ENDINGPERIOD",2=>"SER_UPDATE_AUTO",3=>"SER_HIDDEN",4=>"GEO_REGION",5=>"GEO_ADRESS",6=>"GEO_METRO",7=>"GEO_METRO_DEST",8=>"GEO_GPS",9=>"APART_TYPES",10=>"APART_COUNT",11=>"APART_COUNT_TOTAL",12=>"APART_PRICE_DIAPASON",13=>"APART_PRICE_MIN",14=>"APART_PRICE_MAX",15=>"APART_AREA_DIAPASON",16=>"APART_AREA_MIN",17=>"APART_AREA_MAX",18=>"APART_OTDELKA",19=>"HF_BUILD_TYPE",20=>"HF_FLORS",21=>"HF_BUILDER",22=>"HF_LINES",23=>"FIN_BUY_TYPE",24=>"FIN_BANKS",25=>"FIN_FLAT_TYPE",26=>"OPT_SHOW_ON_MAIN",27=>"OPT_SHOW_ON_OFFER",28=>"IMG_AVATAR",29=>"MAN_DECOR",30=>"MAN_LIKE",31=>"MAN_ACTION",32=>"MAN_GDESCRIBE",33=>"MARK_ACTION",34=>"",),
		"SECTION_CODE" => "",
		"SECTION_ID" => $_REQUEST["SECTION_ID"],
		"SECTION_ID_VARIABLE" => "SECTION_ID",
		"SECTION_URL" => "",
		"SECTION_USER_FIELDS" => array(0=>"",1=>"",),
		"SEF_MODE" => "N",
		"SET_BROWSER_TITLE" => "Y",
		"SET_LAST_MODIFIED" => "N",
		"SET_META_DESCRIPTION" => "Y",
		"SET_META_KEYWORDS" => "Y",
		"SET_STATUS_404" => "N",
		"SET_TITLE" => "Y",
		"SHOW_404" => "N",
		"SHOW_ALL_WO_SECTION" => "N",
		"SHOW_PRICE_COUNT" => "1",
		"TEMPLATE_THEME" => "blue",
		"USE_MAIN_ELEMENT_SECTION" => "N",
		"USE_PRICE_COUNT" => "N",
		"USE_PRODUCT_QUANTITY" => "N"
	)
);?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>