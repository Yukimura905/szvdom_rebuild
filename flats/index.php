<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Title");

?><?$APPLICATION->IncludeComponent(
	"sakura:filter", 
	"flats-filter", 
	array(
		"CACHE_GROUPS" => "Y",
		"CACHE_TIME" => "36000000",
		"CACHE_TYPE" => "A",
		"COMPONENT_TEMPLATE" => "flats-filter",
		"COMPOSITE_FRAME_MODE" => "A",
		"COMPOSITE_FRAME_TYPE" => "AUTO",
		"FILTER_NAME" => "arrFilter",
		"IBLOCK_ID" => "1",
		"IBLOCK_TYPE" => "objects",
		"PAGER_PARAMS_NAME" => "arrPager",
		"SAVE_IN_SESSION" => "N",
		"SECTION_CODE" => "",
		"SECTION_ID" => $_REQUEST["SECTION_ID"],
		"SEF_MODE" => "N"
	),
	false
);?>
<?
// sorted by fields
if( isset($_REQUEST["sortField"]) ){
	$_SESSION['sortMode'] = $_REQUEST["sortField"];
	if( isset($_REQUEST["sortField"]) != $_REQUEST["sortField"])
		$_SESSION['orderMode'] = 'asc';
	else
		$_SESSION['orderMode'] = $_SESSION['orderMode'] == 'asc' ? 'desc':'asc';
}

switch ( $_SESSION['sortMode'] ) {
    case 'name':
		$sortField = 'name';
        break;
    case 'shows':
		$sortField = 'SHOW_COUNTER_START';
        break;
    case 'end':
		$sortField = 'PROPERTY_SER_ENDINGPERIOD';
        break;
    case 'price':
		if($_SESSION['orderMode'] == 'asc')
			$sortField = 'PROPERTY_APART_PRICE_MIN';
			else
			$sortField = 'PROPERTY_APART_PRICE_MAX';
        break;
	default:
		$sortField = 'sort';
        break;
}
$orderField = $_SESSION['orderMode'];
//unset($_SESSION['sortMode']);
//echo $_SESSION['sortMode'];
//echo $_SESSION['orderMode'];
//pre($_SESSION);
//echo $sortField;
?>
<?
// arrFilter
// price
if(isset($_REQUEST['arrFilter_Price_MIN']))
	$arrFilter[">=PROPERTY_AP_COST_DISC"] = $_REQUEST['arrFilter_Price_MIN']*1000;
if(isset($_REQUEST['arrFilter_Price_MAX']))
	$arrFilter["<=PROPERTY_AP_COST_DISC"] = $_REQUEST['arrFilter_Price_MAX']*1000;
// area
if(isset($_REQUEST['arrFilter_Area_MIN']))
	$arrFilter[">=PROPERTY_AP_STOTAL"] = $_REQUEST['arrFilter_Area_MIN'];
if(isset($_REQUEST['arrFilter_Area_MAX']))
	$arrFilter["<=PROPERTY_AP_STOTAL"] = $_REQUEST['arrFilter_Area_MAX'];
// floor
if(isset($_REQUEST['arrFilter_Floor_MIN']))
	$arrFilter[">=PROPERTY_AP_FLAT_FLOOR"] = $_REQUEST['arrFilter_Floor_MIN'];
if(isset($_REQUEST['arrFilter_Floor_MAX']))
	$arrFilter["<=PROPERTY_AP_FLAT_FLOOR"] = $_REQUEST['arrFilter_Floor_MAX'];

//year
if(isset($_REQUEST['arrFilter_Year']) AND is_numeric($_REQUEST['arrFilter_Year']))
	$arrFilter["PROPERTY_SER_ENDINGPERIOD"] = $_REQUEST['arrFilter_Year'];

$arFlatsTypes = array();
if(isset($_REQUEST['arrFilter_FlatsType_F1']))
	array_push($arFlatsTypes, 0); 
if(isset($_REQUEST['arrFilter_FlatsType_F2']))
	array_push($arFlatsTypes, 1); 
if(isset($_REQUEST['arrFilter_FlatsType_F3']))
	array_push($arFlatsTypes, 2, 22); 
if(isset($_REQUEST['arrFilter_FlatsType_F4']))
	array_push($arFlatsTypes, 3, 23); 
if(isset($_REQUEST['arrFilter_FlatsType_F5']))
	array_push($arFlatsTypes, 4, 21, 5, 32, 6, 7, 8); 

if(true)
	$arrFilter["?PROPERTY_AP_ROOMS_TYPE"] = $arFlatsTypes;

//by name
if(isset($_REQUEST['arrFilter_SearchLine']))
	$arrFilter["%NAME"] = $_REQUEST['arrFilter_SearchLine'];

//$_REQUEST['arrFilter_BlockId'] = 76;
//by block id
if(isset($_REQUEST['arrFilter_BlockId']))
	$arrFilter["PROPERTY_AP_XML_BLOCK_ID"] = $_REQUEST['arrFilter_BlockId'];
//by builder
//if(isset($_REQUEST['arrFilter_SearchLine']))
//	$arrFilter["PROPERTY_HF_BUILDER_VALUE"] = array($_REQUEST['arrFilter_SearchLine']);

//$arrFilter["SEARCHABLE_CONTENT"] = ToUpper("%".$_REQUEST['arrFilter_SearchLine']."%");//$_REQUEST['arrFilter_SearchLine'];


if(isset($_REQUEST['tag'])){
	//$arLenOblast = array("Бокситогорский р-н", "Волосовский р-н", "Волховский р-н", "Всеволожский р-н", "Гатчинский р-н", "Даниловский р-н", "Западный р-н", "Истринский р-н", "Кингисеппский р-н", "Киришский р-н", "Колпинский р-н");
		$arPiter = array("Адмиралтейский р-н", "Василеостровский р-н", "Выборгский р-н", "Выборгский р-н", "Калининский р-н", "Кировский р-н", "Красногвардейский р-н", "Московский р-н", "Невский р-н", "Петроградский р-н", "Петродворцовый р-н", "Приморский р-н", "Сланцевский р-н", "Фрунзенский р-н", "Центральный р-н");
		switch($_REQUEST['tag']){
			case 'spb': //GEO_REGION
				$arrFilter["PROPERTY_GEO_REGION_VALUE"] = $arPiter;
			break;
			case 'lo':
				$arrFilter["!PROPERTY_GEO_REGION_VALUE"] = $arPiter;
			break;
			case 'metro':
				$arrFilter["!PROPERTY_GEO_METRO_DEST"] = "%Пешком%";
			break;
			case 'ready':
			//echo date("Y");
				$arrFilter["<=PROPERTY_SER_ENDINGPERIOD"] = date("Y");//
			break;
		}
	}
?>
<?$APPLICATION->IncludeComponent(
	"bitrix:catalog.section", 
	"list", 
	array(
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
		"ELEMENT_SORT_FIELD" => $sortField,
		"ELEMENT_SORT_FIELD2" => "",
		"ELEMENT_SORT_ORDER" => $orderField,
		"ELEMENT_SORT_ORDER2" => "",
		"FILTER_NAME" => "arrFilter",
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
		"PAGER_TEMPLATE" => ".default",
		"PAGER_TITLE" => "Комплексы",
		"PAGE_ELEMENT_COUNT" => "30",
		"PARTIAL_PRODUCT_PROPERTIES" => "N",
		"PRICE_CODE" => array(
		),
		"PRICE_VAT_INCLUDE" => "Y",
		"PRODUCT_ID_VARIABLE" => "id",
		"PRODUCT_PROPERTIES" => array(
		),
		"PRODUCT_PROPS_VARIABLE" => "prop",
		"PRODUCT_QUANTITY_VARIABLE" => "",
		"PROPERTY_CODE" => array(
			0 => "AP_XML_BLOCK_ID",
			1 => "AP_ROOMS_TYPE",
			2 => "AP_STOTAL",
			3 => "AP_COST_BASE",
			4 => "AP_FLAT_FLOOR",
			5 => "AP_FLAT_PLAN",
			6 => "",
		),
		"SECTION_CODE" => "",
		"SECTION_ID" => $_REQUEST["SECTION_ID"],
		"SECTION_ID_VARIABLE" => "SECTION_ID",
		"SECTION_URL" => "",
		"SECTION_USER_FIELDS" => array(
			0 => "",
			1 => "",
		),
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
	),
	false
);?><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>