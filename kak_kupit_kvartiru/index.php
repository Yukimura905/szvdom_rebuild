<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Как купить квартиру");
?>

    <main class="main" role="main">
        <div class="container">
            <h2>Как купить квартиру в новостройке СПБ и ЛО</h2>
            <div class="head-text">
                <div class="row">
                    <div class="col-md-4">
                        <p>По Вашей заявке мы подберем самую выгодную кввартиру с максимально интересными условиями по оплате, дополнительным бонусам и другим необходимым опционалом. И всякий другой полезный текст про то,как нужно выбирать.</p>
                    </div>
                    <div class="col-md-4">
                        <p>По Вашей заявке мы подберем самую выгодную кввартиру с максимально интересными условиями по оплате, дополнительным бонусам и другим необходимым опционалом. И всякий другой полезный текст про то,как нужно выбирать.</p>
                    </div>
                    <div class="col-md-4">
                        <p>По Вашей заявке мы подберем самую выгодную кввартиру с максимально интересными условиями по оплате, дополнительным бонусам и другим необходимым опционалом. И всякий другой полезный текст про то,как нужно выбирать.</p>
                    </div>
                </div>
                <br>
                <a href="#form-best" class="btn btn-blue modal-form">Подобрать самую выгодную квартиру</a>
            </div>
            <div class="howbuy-info">
                <div class="row row-eq-height">
                    <div class="col-md-4">
                        <div class="item">
                            <h3>Вы уже знаете, какую квартиру хотите купить?</h3>
                            <p>
                                Вам нужно посмотреть квартиру, а лучше сразу несколько. Мы организуем просмотр сразу нескольких квартир в один день. Вам нужно посмотреть квартиру, а лучше сразу несколько. Мы организуем просмотр сразу нескольких квартир в один день.
                            </p>
                            <div class="bottom">
                                <a href="#form-show" class="btn modal-form">Записаться на просмотр квартиры</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="item">
                            <h3>Вам необходимо оформить ипотеку?</h3>
                            <p>
                                Для этого необходимо собрать пул документов, подойти во все банки, которые аккредитовали ЖК Самый лучший и подать на рассмотрение заявление, после одобрения ипотеки заключить договор. Или Вы можете предоставить все оформления в банках нашему ипотечному менеджеру.
                            </p>
                            <div class="bottom">
                                <a href="#form-ipoteka" class="btn modal-form">Записаться к ипотечному менеджеру</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="item">
                            <h3>Вам необходимо заключить договор?</h3>
                            <p>
                                Какую форму договора выбрать, что значит тот или другой пункт договора? Вы можете записаться к юристу или подойти на консультацию к нам, мы расскажем все максимально подробно и совершенно бесплатно. Есои необходимо провести оплату, арендовать ячейку, обеспечить юридическую безопасность проведения сделки - мы можем помочь Вам и в этом вопросе!
                            </p>
                            <div class="bottom">
                                <a href="#form-consult" class="btn modal-form">Записаться на консультацию</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <h2>Наши подарки всем покупателям!</h2>
            <div class="howbuy-icon">
                <div class="row">
                    <div class="col-md-4 col-sm-4">
                        <div class="item">
							<img src="<?echo SITE_TEMPLATE_PATH;?>/icon-howbuy-1.png" alt=""> Переезд за наш счет
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-4">
                        <div class="item">
                            <img src="<?echo SITE_TEMPLATE_PATH;?>/icon-howbuy-2.png" alt=""> Скидка 20% на дизайн-проект
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-4">
                        <div class="item">
                            <img src="<?echo SITE_TEMPLATE_PATH;?>/icon-howbuy-3.png" alt=""> Помощь в возрате 13% налога
                        </div>
                    </div>
                </div>
                <a  class="modal-form btn btn-blue" href="#form-consult">Заказать консультацию по покупке квартиры</a>
            </div>
        </div>
    </main>
<!-- Вызов формы Заказать ипотечного менеджера -->
<?$APPLICATION->IncludeComponent(
	"bitrix:main.include", 
	".default", 
	array(
		"AREA_FILE_SHOW" => "file",
		"AREA_FILE_SUFFIX" => "inc",
		"COMPONENT_TEMPLATE" => ".default",
		"EDIT_TEMPLATE" => "",
		"PATH" => "form_ipoteka.inc.php"
	),
	false
);?>
<!-- /Вызов формы Заказать ипотечного менеджера -->

<!-- Вызов формы Заказать просмотр квартиры -->
<?$APPLICATION->IncludeComponent(
	"bitrix:main.include", 
	".default", 
	array(
		"AREA_FILE_SHOW" => "file",
		"AREA_FILE_SUFFIX" => "inc",
		"COMPONENT_TEMPLATE" => ".default",
		"EDIT_TEMPLATE" => "",
		"PATH" => "form_show.inc.php"
	),
	false
);?>
<!-- /Вызов формы Заказать просмотр квартиры -->


<!-- Вызов формы Подобрать самую выгодную квартиру -->
<?$APPLICATION->IncludeComponent(
	"bitrix:main.include", 
	".default", 
	array(
		"AREA_FILE_SHOW" => "file",
		"AREA_FILE_SUFFIX" => "inc",
		"COMPONENT_TEMPLATE" => ".default",
		"EDIT_TEMPLATE" => "",
		"PATH" => "form_best.inc.php"
	),
	false
);?>
<!-- /Вызов формы Подобрать самую выгодную квартиру -->


<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>