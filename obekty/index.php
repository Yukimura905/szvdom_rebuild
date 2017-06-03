<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Новостройки");


echo "!";


?>
<?
// sorted by fields
/**if( isset($_REQUEST["sortField"]) ){
	$_SESSION['sortMode'] = $_REQUEST["sortField"];
	if( isset($_REQUEST["sortField"]) != $_REQUEST["sortField"])
		$_SESSION['orderMode'] = 'asc';
	else
		$_SESSION['orderMode'] = $_SESSION['orderMode'] == 'asc' ? 'desc':'asc';
}*/

if( !isset($_SESSION['sortField'])) $_SESSION['sortField'] = 'name';


if( isset($_REQUEST['sortField'])){
	 $_SESSION['sortField'] = str_replace('-','',$_REQUEST['sortField']);
	$_SESSION['sortOrder'] = $_REQUEST['sortField'] == str_replace('-','',$_REQUEST['sortField'])?'asc':'desc';
}




/*
if(isset($_REQUEST["sortField"]) )
if( $_SESSION['sortMode'] == $_REQUEST["sortField"] )
	$_SESSION['orderMode'] = $_SESSION['orderMode'] == 'asc' ? 'desc':'asc';
else	{
	$_SESSION['sortMode'] = $_REQUEST["sortField"];
	$_SESSION['orderMode'] = 'asc';
	}
*/

switch ( $_SESSION['sortField'] ) {
    case 'name':
		$sortField = 'name';
        break;
    case 'shows':
		$sortField = 'show_counter';
        break;
    case 'end':
		$sortField = 'PROPERTY_SER_ENDINGPERIOD';
        break;
    case 'price':
		if($_SESSION['sortOrder'] == 'asc')
			$sortField = 'PROPERTY_APART_PRICE_MIN';
			else
			$sortField = 'PROPERTY_APART_PRICE_MAX';
        break;
	default:
		$sortField = 'sort';
        break;
}
$orderField = $_SESSION['sortOrder'];


//echo "--->" . $orderField .'<br>';;
//echo "--->" . $sortField .'<br>';;
//unset($_SESSION['sortMode']);
//echo $_SESSION['sortMode'];
//echo $_SESSION['orderMode'];
//pre($_SESSION);
//echo $sortField;
?>
<?
// arrFilter
if(isset($_REQUEST['arrFilter_Price_MIN']))
	$arrFilter[">=PROPERTY_APART_PRICE_MAX"] = $_REQUEST['arrFilter_Price_MIN']*1000;
if(isset($_REQUEST['arrFilter_Price_MAX']))
	$arrFilter["<=PROPERTY_APART_PRICE_MIN"] = $_REQUEST['arrFilter_Price_MAX']*1000;
if(isset($_REQUEST['arrFilter_Year']) AND is_numeric($_REQUEST['arrFilter_Year']))
//	$arrFilter["PROPERTY_SER_ENDINGPERIOD"] = $_REQUEST['arrFilter_Year'];
	if($_REQUEST['arrFilter_Year'] != 1000)
		$arrFilter["%PROPERTY_HF_LINES"] = $_REQUEST['arrFilter_Year'];
		else
			$arrFilter["<PROPERTY_SER_ENDINGPERIOD"] = date("Y");//$_REQUEST['arrFilter_Year'];



$arFlatsTypes = array();
if(isset($_REQUEST['arrFilter_FlatsType_F1']))
	array_push($arFlatsTypes, 'Студия'); 
if(isset($_REQUEST['arrFilter_FlatsType_F2']))
	array_push($arFlatsTypes, '1 ккв'); 
if(isset($_REQUEST['arrFilter_FlatsType_F3']))
	array_push($arFlatsTypes, '2 ккв', '2 ккв (Евро)'); 
if(isset($_REQUEST['arrFilter_FlatsType_F4']))
	array_push($arFlatsTypes, '3 ккв','3 ккв (Евро)'); 
if(isset($_REQUEST['arrFilter_FlatsType_F5']))
	array_push($arFlatsTypes, '4 ккв', '4 ккв (Евро)', '5 ккв', '5 ккв (Евро)', '6 ккв', '7 ккв', '8 ккв'); 

if(count($arFlatsTypes))
	$arrFilter["PROPERTY_APART_TYPES_VALUE"] = $arFlatsTypes;

//by name
if(isset($_REQUEST['arrFilter_SearchLine']) and strlen($_REQUEST['arrFilter_SearchLine']) > 3){

	$req = $_REQUEST['arrFilter_SearchLine'];


	$arrFilter = array(
		array(
				"LOGIC" => "AND",
				array(
					"LOGIC" => "OR",
					array("%NAME"=>$req),
					array("ID"=>$req),
					array("%PROPERTY_GEO_METRO_VALUE"=>array($req)),
					array("%PROPERTY_GEO_REGION_VALUE"=>array($req)),
					array("%PROPERTY_HF_BUILDER_VALUE"=>array($req))
					),
				$arrFilter
			)
	);
}
else{
	//echo'************************';
	//$_REQUEST['arrFilter_SearchLine'] = '';
}


// reset filter paramets
if( $_REQUEST['arrFilReset'] == 1 or isset($_REQUEST['set_filter']) or isset($_REQUEST['tag']) ){
	unset($_SESSION['arrFilter']);
	unset($_SESSION['arrFilterArr']);
}


// save filter parametrs
if(count($arrFilter)){
	$_SESSION['arrFilter'] = $arrFilter;
	// save last filter state
	foreach ($_REQUEST as $key => $value){
		if( substr($key, 0 , 10) == 'arrFilter_' and $value != '' ) $_SESSION['arrFilterArr'][$key] = $value;
	}

}
//pre($_REQUEST);

//by builder
//if(isset($_REQUEST['arrFilter_SearchLine']))
//	$arrFilter["PROPERTY_HF_BUILDER_VALUE"] = array($_REQUEST['arrFilter_SearchLine']);

//$arrFilter["SEARCHABLE_CONTENT"] = ToUpper("%".$_REQUEST['arrFilter_SearchLine']."%");//$_REQUEST['arrFilter_SearchLine'];


if(isset($_SESSION['tag'])){
	//$arLenOblast = array("Бокситогорский р-н", "Волосовский р-н", "Волховский р-н", "Всеволожский р-н", "Гатчинский р-н", "Даниловский р-н", "Западный р-н", "Истринский р-н", "Кингисеппский р-н", "Киришский р-н", "Колпинский р-н");
		$arPiter = array("Адмиралтейский р-н", "Василеостровский р-н", "Выборгский р-н", "Выборгский р-н", "Калининский р-н", "Кировский р-н", "Красногвардейский р-н", "Московский р-н", "Невский р-н", "Петроградский р-н", "Петродворцовый р-н", "Приморский р-н", "Сланцевский р-н", "Фрунзенский р-н", "Центральный р-н");
		switch($_SESSION['tag']){
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
// pagination
	// Количество объектов на странице
	if( !isset($_SESSION['OPP_NUM']) ) $_SESSION['OPP_NUM'] = 10;
	if( isset($_REQUEST['opp'])) $_SESSION['OPP_NUM'] = $_REQUEST['opp'];
		$opp = $_SESSION['OPP_NUM'];
	// Формируем переменную с количеством элеменов на страницу

	//$GLOBALS['opp'] = $arResult['OPP_NUM'];
	//pre($GLOBALS);


?>
<?$APPLICATION->IncludeComponent(
	"bitrix:catalog.section", 
	"list3", 
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
		"COMPONENT_TEMPLATE" => "list3",
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
		"IBLOCK_ID" => "1",
		"IBLOCK_TYPE" => "objects",
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
		"PAGE_ELEMENT_COUNT" => $opp,
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
			0 => "SER_ENDINGPERIOD",
			1 => "GEO_REGION",
			2 => "GEO_METRO",
			3 => "APART_TYPES",
			4 => "",
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
		"SET_STATUS_404" => "Y",
		"SET_TITLE" => "Y",
		"SHOW_404" => "Y",
		"SHOW_ALL_WO_SECTION" => "N",
		"SHOW_PRICE_COUNT" => "1",
		"TEMPLATE_THEME" => "blue",
		"USE_MAIN_ELEMENT_SECTION" => "N",
		"USE_PRICE_COUNT" => "N",
		"USE_PRODUCT_QUANTITY" => "N",
		"FILE_404" => ""
	),
	false
);?><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>