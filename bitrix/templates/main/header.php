<!DOCTYPE html>
<html>

<head>
<script src="<?echo SITE_TEMPLATE_PATH;?>/jquery.min.js"></script>

<?$APPLICATION->ShowHead()?>
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
	<link rel="icon" type="image/x-icon" href="<?echo SITE_TEMPLATE_PATH;?>/favicon.ico" />
<title><?$APPLICATION->ShowTitle()?></title>
	<link rel="shortcut icon" type="image/png" href="/bitrix/templates/.default/images/icons/logo.png" />
<link rel="shortcut icon" type="image/x-icon" href="/bitrix/templates/.default/images/icons/favicon.ico">




    <!--[if lt IE 9]>
<script src="<?echo SITE_TEMPLATE_PATH;?>/html5shiv.min.js"></script>
<script src="<?echo SITE_TEMPLATE_PATH;?>/respond.min.js"></script>




    <![endif]-->

	<!-- Add mousewheel plugin (this is optional) -->
	<script type="text/javascript" src="<?echo SITE_TEMPLATE_PATH;?>/jquery.mousewheel-3.0.6.pack.js" defer></script>

	<!-- Add fancyBox main JS and CSS files -->
	<script type="text/javascript" src="<?echo SITE_TEMPLATE_PATH;?>/jquery.fancybox.js?v=2.1.5" defer></script>
	<link rel="stylesheet" type="text/css" href="<?echo SITE_TEMPLATE_PATH;?>/jquery.fancybox.css?v=2.1.5" media="screen" />

	<!-- Add Button helper (this is optional) -->
	<link rel="stylesheet" type="text/css" href="<?echo SITE_TEMPLATE_PATH;?>/jquery.fancybox-buttons.css?v=1.0.5" />
	<script type="text/javascript" src="<?echo SITE_TEMPLATE_PATH;?>/jquery.fancybox-buttons.js?v=1.0.5" defer></script>

	<!-- Add Thumbnail helper (this is optional) -->
	<link rel="stylesheet" type="text/css" href="<?echo SITE_TEMPLATE_PATH;?>/jquery.fancybox-thumbs.css?v=1.0.7" />
	<script type="text/javascript" src="<?echo SITE_TEMPLATE_PATH;?>/jquery.fancybox-thumbs.js?v=1.0.7" defer></script>

	<!-- Add Media helper (this is optional) -->
	<script type="text/javascript" src="<?echo SITE_TEMPLATE_PATH;?>/jquery.fancybox-media.js?v=1.0.6" defer></script>

<link rel="stylesheet" type="text/css" href="https://owlcarousel2.github.io/OwlCarousel2/assets/owlcarousel/assets/owl.theme.default.min.css" />
    <script src="<?echo SITE_TEMPLATE_PATH;?>/jquery.magnific-popup.min.js" defer></script>

   <script>
    $(document).ready(function() {
        $('.modal-form').magnificPopup({
            type: 'inline',
            preloader: false
        });
    });
    </script>

</head>





<body>
<?$APPLICATION->ShowPanel();?>
    <header class="header">
        <div class="container">
            <div class="top">
                <div class="row">
                    <div class="col-md-4 col-sm-4 col-xs-12">
						<a href="/" class="logo" role="banner">
                            Homehunter<span>Центр<br />новостроек</span>
                        </a>
                    </div>
                    <div class="col-md-8 col-sm-8 col-xs-12">
                        <ul class="phone">
                            <li>
                                <a href="tel:+88124261990">8 (812) 426-19-90</a>
                            </li>
                            <li>
                                <a href="tel:+88005007140">8 (800) 500-71-40</a>
                            </li>
                        </ul>
                        <a  class="modal-form btn" href="#form-callback">Заказать обратный звонок</a>
                    </div>
                </div>
            </div>
        </div>
    </header>
<?$APPLICATION->IncludeComponent(
	"sakura:filter", 
	"template1", 
	array(
		"CACHE_GROUPS" => "Y",
		"CACHE_TIME" => "36000000",
		"CACHE_TYPE" => "A",
		"COMPOSITE_FRAME_MODE" => "A",
		"COMPOSITE_FRAME_TYPE" => "AUTO",
		"FILTER_NAME" => "arrFilter",
		"IBLOCK_ID" => "1",
		"IBLOCK_TYPE" => "objects",
		"PAGER_PARAMS_NAME" => "arrPager",
		"SAVE_IN_SESSION" => "N",
		"SECTION_CODE" => "",
		"SECTION_ID" => $_REQUEST["SECTION_ID"],
		"SEF_MODE" => "N",
		"COMPONENT_TEMPLATE" => "template1"
	),
	false
);?>

    <!-- ПОИСК КОМПОНЕНТ -->