<?
define('STOP_STATISTICS', true);
require_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php');
$APPLICATION->SetTitle("");
$GLOBALS['APPLICATION']->RestartBuffer();
?><?
//pre($_REQUEST);
if(!isset($_SESSION['flats-tabs-curent'])) $_SESSION['flats-tabs-curent'] = 'all';
if( $_REQUEST['jk_id'] != $_SESSION['old_jk_id'] ) {
	$_SESSION['old_jk_id'] = $_REQUEST['jk_id'];
	$_SESSION['flats-tabs-curent'] = 'all';
}

if( isset($_REQUEST['set_filter']) ) $_SESSION['flats-tabs-curent'] = $_REQUEST['set_filter'];

//$_REQUEST["SECTION_ID"] = 288;
$arAjrFilter["PROPERTY_AP_XML_BLOCK_ID"] = $_REQUEST['jk_id'];
//echo 'flats: ' . $_SESSION['flats-tabs-curent'];
//echo 'set_flats: ' . $_REQUEST['set_filter'];;
$arFlatsTypes = array();
if($_SESSION['flats-tabs-curent'] == '00')
	array_push($arFlatsTypes, 0); 
if($_SESSION['flats-tabs-curent'] == '1')
	array_push($arFlatsTypes, 1); 
if($_SESSION['flats-tabs-curent'] == '2')
	array_push($arFlatsTypes, 2, 22); 
if($_SESSION['flats-tabs-curent'] == '3')
	array_push($arFlatsTypes, 3, 23); 
if($_SESSION['flats-tabs-curent'] == '4')
	array_push($arFlatsTypes, 4, 21, 5, 32, 6, 7, 8); 

if($_SESSION['flats-tabs-curent'] != 'All')
	$arAjrFilter["?PROPERTY_AP_ROOMS_TYPE"] = $arFlatsTypes;
?>

<?$APPLICATION->IncludeComponent(
	"bitrix:catalog.section",
	"flats_ajax",
	Array(
		"ACTION_VARIABLE" => "action",
		"ADD_PICT_PROP" => "-",
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
		"COMPONENT_TEMPLATE" => "list",
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
		"FILTER_NAME" => "arAjrFilter",
		"IBLOCK_ID" => "2",
		"IBLOCK_TYPE" => "apartments",
		"INCLUDE_SUBSECTIONS" => "Y",
		"LABEL_PROP" => "-",
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
		"PAGER_TEMPLATE" => "flats_ajax",
		"PAGER_TITLE" => "Товары",
		"PAGE_ELEMENT_COUNT" => "10",
		"PARTIAL_PRODUCT_PROPERTIES" => "N",
		"PRICE_CODE" => array(),
		"PRICE_VAT_INCLUDE" => "Y",
		"PRODUCT_ID_VARIABLE" => "id",
		"PRODUCT_PROPERTIES" => array(),
		"PRODUCT_PROPS_VARIABLE" => "prop",
		"PRODUCT_QUANTITY_VARIABLE" => "",
		"PROPERTY_CODE" => array(
			0 => "AP_XML_ID",
			1 => "AP_ROOMS_TYPE",
			2 => "AP_STOTAL",
			3 => "AP_FLAT_PLAN",
			4 => "AP_COST_BASE",
			5 => "",
		),
		"SECTION_CODE" => $_REQUEST["SECTION_CODE"],
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
		"SHOW_ALL_WO_SECTION" => "Y",
		"SHOW_PRICE_COUNT" => "1",
		"TEMPLATE_THEME" => "blue",
		"USE_MAIN_ELEMENT_SECTION" => "N",
		"USE_PRICE_COUNT" => "N",
		"USE_PRODUCT_QUANTITY" => "N"
	)
);?>



<?die()?>