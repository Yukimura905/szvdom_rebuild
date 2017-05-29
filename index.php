<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("");
?><?
/* $APPLICATION->IncludeComponent(
	"bitrix:menu", 
	"vertical_multilevel", 
	array(
		"ALLOW_MULTI_SELECT" => "N",
		"CHILD_MENU_TYPE" => "left",
		"DELAY" => "N",
		"MAX_LEVEL" => "1",
		"MENU_CACHE_GET_VARS" => array(
		),
		"MENU_CACHE_TIME" => "3600",
		"MENU_CACHE_TYPE" => "N",
		"MENU_CACHE_USE_GROUPS" => "Y",
		"ROOT_MENU_TYPE" => "left",
		"USE_EXT" => "N",
		"COMPONENT_TEMPLATE" => "vertical_multilevel",
		"MENU_THEME" => "site"
	),
	false
);*/?> <?
$arrFilter=Array("PROPERTY_OPT_SHOW_ON_MAIN_VALUE"=>"Да");
?> <section class="special">
<?$APPLICATION->IncludeComponent(
	"bitrix:catalog.section",
	"list-owl1",
	array(
	"ACTION_VARIABLE" => "action",	// Название переменной, в которой передается действие
		"ADD_PICT_PROP" => "-",
		"ADD_PROPERTIES_TO_BASKET" => "Y",	// Добавлять в корзину свойства товаров и предложений
		"ADD_SECTIONS_CHAIN" => "N",	// Включать раздел в цепочку навигации
		"AJAX_MODE" => "N",	// Включить режим AJAX
		"AJAX_OPTION_ADDITIONAL" => "",	// Дополнительный идентификатор
		"AJAX_OPTION_HISTORY" => "N",	// Включить эмуляцию навигации браузера
		"AJAX_OPTION_JUMP" => "N",	// Включить прокрутку к началу компонента
		"AJAX_OPTION_STYLE" => "Y",	// Включить подгрузку стилей
		"BACKGROUND_IMAGE" => "-",	// Установить фоновую картинку для шаблона из свойства
		"BASKET_URL" => "/personal/basket.php",	// URL, ведущий на страницу с корзиной покупателя
		"BROWSER_TITLE" => "-",	// Установить заголовок окна браузера из свойства
		"CACHE_FILTER" => "N",	// Кешировать при установленном фильтре
		"CACHE_GROUPS" => "Y",	// Учитывать права доступа
		"CACHE_TIME" => "36000000",	// Время кеширования (сек.)
		"CACHE_TYPE" => "A",	// Тип кеширования
		"COMPONENT_TEMPLATE" => "list-owl",
		"COMPOSITE_FRAME_MODE" => "A",	// Голосование шаблона компонента по умолчанию
		"COMPOSITE_FRAME_TYPE" => "AUTO",	// Содержимое компонента
		"DETAIL_URL" => "",	// URL, ведущий на страницу с содержимым элемента раздела
		"DISABLE_INIT_JS_IN_COMPONENT" => "N",	// Не подключать js-библиотеки в компоненте
		"DISPLAY_BOTTOM_PAGER" => "Y",	// Выводить под списком
		"DISPLAY_TOP_PAGER" => "N",	// Выводить над списком
		"ELEMENT_SORT_FIELD" => "sort",	// По какому полю сортируем элементы
		"ELEMENT_SORT_FIELD2" => "",	// Поле для второй сортировки элементов
		"ELEMENT_SORT_ORDER" => "asc",	// Порядок сортировки элементов
		"ELEMENT_SORT_ORDER2" => "",	// Порядок второй сортировки элементов
		"FILTER_NAME" => "arrFilter",	// Имя массива со значениями фильтра для фильтрации элементов
		"IBLOCK_ID" => "1",	// Инфоблок
		"IBLOCK_TYPE" => "objects",	// Тип инфоблока
		"INCLUDE_SUBSECTIONS" => "Y",	// Показывать элементы подразделов раздела
		"LABEL_PROP" => "-",
		"LINE_ELEMENT_COUNT" => "3",	// Количество элементов выводимых в одной строке таблицы
		"MESSAGE_404" => "",	// Сообщение для показа (по умолчанию из компонента)
		"MESS_BTN_ADD_TO_BASKET" => "В корзину",
		"MESS_BTN_BUY" => "Купить",
		"MESS_BTN_DETAIL" => "Подробнее",
		"MESS_BTN_SUBSCRIBE" => "Подписаться",
		"MESS_NOT_AVAILABLE" => "Нет в наличии",
		"META_DESCRIPTION" => "-",	// Установить описание страницы из свойства
		"META_KEYWORDS" => "-",	// Установить ключевые слова страницы из свойства
		"OFFERS_LIMIT" => "5",	// Максимальное количество предложений для показа (0 - все)
		"PAGER_BASE_LINK_ENABLE" => "N",	// Включить обработку ссылок
		"PAGER_DESC_NUMBERING" => "N",	// Использовать обратную навигацию
		"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",	// Время кеширования страниц для обратной навигации
		"PAGER_SHOW_ALL" => "N",	// Показывать ссылку "Все"
		"PAGER_SHOW_ALWAYS" => "N",	// Выводить всегда
		"PAGER_TEMPLATE" => ".default",	// Шаблон постраничной навигации
		"PAGER_TITLE" => "Комплексы",	// Название категорий
		"PAGE_ELEMENT_COUNT" => "20",	// Количество элементов на странице
		"PARTIAL_PRODUCT_PROPERTIES" => "N",	// Разрешить добавлять в корзину товары, у которых заполнены не все характеристики
		"PRICE_CODE" => "",	// Тип цены
		"PRICE_VAT_INCLUDE" => "Y",	// Включать НДС в цену
		"PRODUCT_ID_VARIABLE" => "id",	// Название переменной, в которой передается код товара для покупки
		"PRODUCT_PROPERTIES" => "",	// Характеристики товара
		"PRODUCT_PROPS_VARIABLE" => "prop",	// Название переменной, в которой передаются характеристики товара
		"PRODUCT_QUANTITY_VARIABLE" => "",	// Название переменной, в которой передается количество товара
		"PROPERTY_CODE" => array(	// Свойства
			0 => "SER_ENDINGPERIOD",
			1 => "GEO_REGION",
			2 => "GEO_METRO",
			3 => "APART_TYPES",
			4 => "",
		),
		"SECTION_CODE" => "",	// Код раздела
		"SECTION_ID" => $_REQUEST["SECTION_ID"],	// ID раздела
		"SECTION_ID_VARIABLE" => "SECTION_ID",	// Название переменной, в которой передается код группы
		"SECTION_URL" => "",	// URL, ведущий на страницу с содержимым раздела
		"SECTION_USER_FIELDS" => array(	// Свойства раздела
			0 => "",
			1 => "",
		),
		"SEF_MODE" => "N",	// Включить поддержку ЧПУ
		"SET_BROWSER_TITLE" => "Y",	// Устанавливать заголовок окна браузера
		"SET_LAST_MODIFIED" => "N",	// Устанавливать в заголовках ответа время модификации страницы
		"SET_META_DESCRIPTION" => "Y",	// Устанавливать описание страницы
		"SET_META_KEYWORDS" => "Y",	// Устанавливать ключевые слова страницы
		"SET_STATUS_404" => "N",	// Устанавливать статус 404
		"SET_TITLE" => "Y",	// Устанавливать заголовок страницы
		"SHOW_404" => "N",	// Показ специальной страницы
		"SHOW_ALL_WO_SECTION" => "N",	// Показывать все элементы, если не указан раздел
		"SHOW_PRICE_COUNT" => "1",	// Выводить цены для количества
		"TEMPLATE_THEME" => "blue",
		"USE_MAIN_ELEMENT_SECTION" => "N",	// Использовать основной раздел для показа элемента
		"USE_PRICE_COUNT" => "N",	// Использовать вывод цен с диапазонами
		"USE_PRODUCT_QUANTITY" => "N",	// Разрешить указание количества товара
	)
);?> </section> <section class="map">
<div id="map" class="map-windows">
	<?
// Берём все значения инфоблока с ID равным 10. Вам нужно подставить свой ID.
$mas = CIBlockElement::GetList(array(), array( "IBLOCK_ID" => 1, "ACTIVE"=>"Y" )); 

while ( $oElement = $mas->GetNextElement() ) {
  $aElement['PROPERTIES'] = $oElement->GetProperties(); // Берём значения элемента инфоблока
	//pre($aElement['PROPERTIES']);
	//print_r($oElement);
$mElement = $oElement->GetFields();
	//pre ($mElement);
  // Координаты хранятся в переменной MAP (подставьте вашу переменную) через запятую, разделим их
	//$arTmp = explode(',', $aElement['PROPERTIES']['MAP']['VALUE']); 
  //Подготовка карты
  $arResult['POSITION']['yandex_scale'] = "10"; // Подбираем размер карты, чтобы поместились все маркеры
  // В yandex_lat и yandex_lon заносим координаты центральной точки карты
  $arResult['POSITION']['yandex_lat'] = $aElement['PROPERTIES']["GEO_GPS"]["VALUE"][0]; // В нашем случае координаты первого элемента инфоблока
  $arResult['POSITION']['yandex_lon'] = $aElement['PROPERTIES']["GEO_GPS"]["VALUE"][1];
  //Собираем маркеры
  $arResult['POSITION']['PLACEMARKS'][] = array(
    'LON' => $aElement['PROPERTIES']["GEO_GPS"]["VALUE"][1], // LON и LAT - координаты маркера
    'LAT' => $aElement['PROPERTIES']["GEO_GPS"]["VALUE"][0],
	  'TEXT' => 'ЖК "' . $mElement['NAME'] . '"',
  );
}
// Выводим карту с метками через компонент Битрикса для карт Яндекса (Яндекс.Карты: настраиваемая карта)
$APPLICATION->IncludeComponent(
  "bitrix:map.yandex.view",
  "",
  Array(
    "INIT_MAP_TYPE" => "MAP",
    "MAP_DATA" => serialize($arResult['POSITION']),
    "MAP_WIDTH" => "100%", // Ширина карты
    "MAP_HEIGHT" => "400", // Высота карты
    "CONTROLS" => array("ZOOM", "TYPECONTROL", "SCALELINE"),
    "OPTIONS" => array("DISABLE_SCROLL_ZOOM", "ENABLE_DBLCLICK_ZOOM", "ENABLE_DRAGGING"),
  )
);

?>
</div>
<div class="container">
	<div class="btn btn-lg border-white btn-show-map">
		Новостройки на карте
	</div>
</div>
 </section> <section class="preset">
<div class="container">
	<h2>Готовые подборки новостроек в СПБ и ЛО</h2>
	<div class="row">
		<div class="col-md-4 col-sm-4">
			<ul>
				<li> <a href="/obekty/?arrFilter_SearchLine=&arrFilter_Price_MIN=196&arrFilter_Price_MAX=201780&Price_MIN=196&Price_MAX=201780&arrFilter_FlatsType_F2=on&arrFilter_Year=не+выбран&set_filter=Показать+обьекты">1-комнатные квартиры в новостройках</a> </li>
				<li> <a href="/obekty/?arrFilter_SearchLine=&arrFilter_Price_MIN=196&arrFilter_Price_MAX=201780&Price_MIN=196&Price_MAX=201780&arrFilter_FlatsType_F3=on&arrFilter_Year=не+выбран&set_filter=Показать+обьекты">2-комнатные квартиры в новостройках</a> </li>
			</ul>
		</div>
		<div class="col-md-4 col-sm-4">
			<ul>
				<li> <a href="/obekty/?arrFilter_SearchLine=Невский&arrFilter_Price_MIN=196&arrFilter_Price_MAX=201780&Price_MIN=196&Price_MAX=201780&arrFilter_Year=не+выбран&set_filter=Показать+обьекты">Новостройки невский район</a> </li>
				<li> <a href="/obekty/?arrFilter_SearchLine=Девяткино&arrFilter_Price_MIN=196&arrFilter_Price_MAX=201780&Price_MIN=196&Price_MAX=201780&arrFilter_Year=не+выбран&set_filter=Показать+обьекты">Новостройки в девяткино</a> </li>
			</ul>
		</div>
		<div class="col-md-4 col-sm-4">
			<ul>
				<li> <a href="/obekty/?arrFilter_SearchLine=&arrFilter_Price_MIN=196&arrFilter_Price_MAX=4000&Price_MIN=196&Price_MAX=201780&arrFilter_Year=не+выбран&set_filter=Показать+обьекты">Новостройки до 4 млн рублей</a> </li>
				<li> <a href="/obekty/?arrFilter_SearchLine=&arrFilter_Price_MIN=196&arrFilter_Price_MAX=2000&Price_MIN=196&Price_MAX=201780&arrFilter_Year=не+выбран&set_filter=Показать+обьекты">Новостройки до 2 млн рублей</a> </li>
			</ul>
		</div>
	</div>
</div>
 </section> <section class="partners">
<div class="container">
	<h2>Агентство недвижимости "HomeHunter" в Санкт-Петербурге</h2>
	<div class="row">
	        <div class="col-md-5 col-sm-6">
<h2>Зачем нужно обращаться в агентство?</h2>
<p>Если вы задумались о покупке жилья в новостройке СПб или Ленинградской области, обращайтесь в компанию «Home Hunter» и мы решим все ваши проблемы. Почему стоит это сделать:
</p>
<p>Наша компания располагает огромной базой данных, в которой собраны квартиры разного уровня комфортности в новых жилых комплексах Санкт-Петербурга и помещения коммерческого назначения. Обозначьте нам приоритетные параметры, и мы в считанные минуты подберем самые подходящие варианты. Аналогичного результата самостоятельно вы бы достигли спустя недели и месяцы.
</p>
<p>Гарантия безопасности. В числе наших деловых партнеров девелоперы с безупречной репутацией. Продажа квартир от надежных застройщиков – одно из главных направлений нашей деятельности. Поэтому у вас всегда будет актуальная и достоверная информация о сроках сдачи объектов, о реальных ценах и технических характеристиках зданий. Покупая жилплощадь вместе с нами, вы будете застрахованы от неприятных неожиданностей. Юридический отдел тщательно следит за грамотным оформлением всех документов.
</p>
<p>Перспективные инвестиции. Специалисты отдела аналитики ежедневно следят за конъюнктурой рынка. Поэтому у нас вы всегда узнаете, в каких районах СПб повысились продажи квартир, где самые низкие цены на жилье, в каких новостройках прогнозируется скачок стоимости квадратного метра. Мы поможем вам сделать выгодное приобретение, которое с каждым годом будет только возрастать в цене.
</p>
<p>Посчитав все плюсы сотрудничества с компанией «Home Hunter», вы можете сделать собственные выводы о том, что услуги профессионалов, которые помогут вам сэкономить, купить лучшее, приумножить со временем свои капиталы, – все эти услуги абсолютно бесплатны!
</p>
<p>Только факты! По данным статистики в 2015 году больше 60% сделок с недвижимым имуществом в Москве и Санкт-Петербурге проведено при помощи профессиональных риэлторов.
</p>
		</div>
		<div class="col-md-4 col-sm-6">
<h2>Почему лучше покупать квадратные метры в строящихся комплексах?</h2>
<p>Уровень продаж квартир в новостройках значительно превышает количество операций с недвижимым имуществом на вторичном рынке. Почему это происходит:
</p>
<p>Новые дома отличаются более высокими техническими параметрами. При строительстве корпусов используются передовые технологии и современные материалы. Квартира в строящемся комплексе – это актуальный дизайн, инновационные инженерно-технические решения, эффективные средства связи и охраны.
</p>
<p>Покупая жилплощадь в новостройке, вам не придется беспокоиться о ее «юридической чистоте». Термин широко вошел в обиход риэлторов из-за большого объема сомнительных сделок и мошеннических операций на вторичном рынке.
</p>
<p>Застройщик «ставит» минимальные цены за квадратный метр, потому что продать быстрее для него выгодно. В связи с этим регулярно проводятся акции, разрабатываются специальные предложения, которые дают возможность значительно сэкономить при покупке жилплощади.
</p>
<p>Преимущества сотрудничества с нашим агентством выделим отдельно: это опытные и компетентные сотрудники, готовые ответить на все ваши вопросы, весь спектр услуг, связанных с приобретением, продажей, арендой недвижимости в Санкт-Петербурге, профессиональная юридическая и консалтинговая поддержка.
</p>
		</div>
		<div class="col-md-3 col-sm-12">
			<div class="amount-partners">
				<span>28</span> компаний успешно сотрудничают с нами
			</div>
		</div>

		
	</div>
	<div class="row brand-partners">
		<div class="col-md-2 col-sm-4 col-xs-6">
			<img src="<?echo SITE_TEMPLATE_PATH;?>/images/partners/4.png" alt="" >
		</div>
		<div class="col-md-2 col-sm-4 col-xs-6">
			<img src="<?echo SITE_TEMPLATE_PATH;?>/images/partners/3.png" alt="" >
		</div>
		<div class="col-md-2 col-sm-4 col-xs-6">
			<img src="<?echo SITE_TEMPLATE_PATH;?>/images/partners/1.png" alt="" >
		</div>
		<div class="col-md-2 col-sm-4 col-xs-6">
			<img src="<?echo SITE_TEMPLATE_PATH;?>/images/partners/4.png" alt="" >
		</div>
		<div class="col-md-2 col-sm-4 col-xs-6">
			<img src="<?echo SITE_TEMPLATE_PATH;?>/images/partners/3.png" alt="" >
		</div>
		<div class="col-md-2 col-sm-4 col-xs-6">
			<img src="<?echo SITE_TEMPLATE_PATH;?>/images/partners/1.png" alt="" >
		</div>
		<div class="col-md-2 col-sm-4 col-xs-6">
			<img src="<?echo SITE_TEMPLATE_PATH;?>/images/partners/5.png" alt="" >
		</div>
		<div class="col-md-2 col-sm-4 col-xs-6">
			<img src="<?echo SITE_TEMPLATE_PATH;?>/images/partners/2.png" alt="" >
		</div>
		<div class="col-md-2 col-sm-4 col-xs-6">
			<img src="<?echo SITE_TEMPLATE_PATH;?>/images/partners/3.png" alt="" >
		</div>
		<div class="col-md-2 col-sm-4 col-xs-6">
			<img src="<?echo SITE_TEMPLATE_PATH;?>/images/partners/5.png" alt="" >
		</div>
		<div class="col-md-2 col-sm-4 col-xs-6">
			<img src="<?echo SITE_TEMPLATE_PATH;?>/images/partners/2.png" alt="" >
		</div>
		<div class="col-md-2 col-sm-4 col-xs-6">
			<img src="<?echo SITE_TEMPLATE_PATH;?>/images/partners/3.png" alt="" >
		</div>
	</div>
</div>
 </section><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>