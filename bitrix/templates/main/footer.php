    <footer class="footer" role="contentinfo">
        <div class="container">
            <div class="row">
                <div class="col-md-3 col-sm-5 col-xs-12">
					<a href="/" class="logo logo-footer">
                        Home<b>hunter</b><span>Центр<br />новострек</span>
                    </a>
                    <div class="social-buttons">
                        <a href="#"><img src="<?echo SITE_TEMPLATE_PATH;?>/icon-facebook.png" alt="Facebook" /></a>
                        <a href="#"><img src="<?echo SITE_TEMPLATE_PATH;?>/icon-twitter.png" alt="Twitter" /></a>
                        <a href="http://vk.com/homehunter_spb"><img src="<?echo SITE_TEMPLATE_PATH;?>/icon-vk.png" alt="VK" /></a>
                        <a href="#"><img src="<?echo SITE_TEMPLATE_PATH;?>/icon-google.png" alt="Google+" /></a>
                    </div>
                </div>
                <div class="col-md-5 col-sm-7 text-center">
                     <a  class="modal-form btn border-white" href="#form-consult">Заказать косультацию по покупке квартиры</a>
                    <div class="phone-footer">
                        <a href="tel:+88124261990">8 (812) 426-19-90</a>
                        <a href="tel:+88005007140">8 (800) 500-71-40</a>
                    </div>
                </div>
                <div class="col-md-4 col-sm-12">
                    <ul class="nav-footer">
                        <li>
							<a href="/partnership/">Сотрудничество</a>
                        </li>
                        <li>
							<a href="/carieer/">Карьера</a>
                        </li>
                        <li>
							<a href="/contacts/">Контакты</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </footer>

<!-- Вызов формы Заказать обратный звонок -->
<?$APPLICATION->IncludeComponent(
	"bitrix:main.include", 
	".default", 
	array(
		"AREA_FILE_SHOW" => "file",
		"AREA_FILE_SUFFIX" => "inc",
		"COMPONENT_TEMPLATE" => ".default",
		"EDIT_TEMPLATE" => "",
		"PATH" => "form_callback.inc.php"
	),
	false
);?>
<!-- /Вызов формы Заказать обратный звонок -->

<!-- Вызов формы Заказать консультацию -->
<?$APPLICATION->IncludeComponent(
	"bitrix:main.include", 
	".default", 
	array(
		"AREA_FILE_SHOW" => "file",
		"AREA_FILE_SUFFIX" => "inc",
		"COMPONENT_TEMPLATE" => ".default",
		"EDIT_TEMPLATE" => "",
		"PATH" => "form_consult.inc.php"
	),
	false
);?>
<!-- /Вызов формы Заказать консультацию -->

</body>

</html>
