<?
session_start();
CModule::AddAutoloadClasses(
        '', // не указываем имя модуля
        array(
           // ключ - имя класса, значение - путь относительно корня сайта к файлу с классом
			'abcParser' => '/bitrix/php_interface/include/utils/abcParser.php',
			'abcParser2' => '/bitrix/php_interface/include/utils/abcParser2.php',
			'testClass' => '/bitrix/php_interface/include/utils/testClass.php',
			'bitParser' => '/bitrix/php_interface/include/parser/bitParser.php',
			'bitParser2' => '/bitrix/php_interface/include/parser/bitParser2.php',
        )
);
?>
<?
function pre($toPrint){
	global $USER;
	if (!$USER->IsAdmin()) return;
	echo'<pre>';
	print_r($toPrint);
	//var_dump($toPrint);
	echo'</pre>';
}
?>
<?
function get_tmb_img($sImgSrc, $sSrcPath=''){
	$sTmbPath='/include/tmb/';
	$ImgTmbFullPath = $sTmbPath.$sImgSrc;
	$ImgSrcFullPath = $sSrcPath.$sImgSrc;
	//$ImgSrcFullPath = 'https://im2-tub-ru.yandex.net/i?id=a95a15527a059095b78a0b1b730125be-l&n=13';
	if (!file_exists($_SERVER["DOCUMENT_ROOT"].$ImgTmbFullPath)){
		//echo 'Создать: '.$ImgTmbFullPath.'<br>';
		//echo '.';
		$st = CFile::ResizeImageFile(
			$sourceFile = $_SERVER["DOCUMENT_ROOT"].$ImgSrcFullPath,
			$destinationFile = $_SERVER["DOCUMENT_ROOT"].$ImgTmbFullPath,
			$arSize = array('width'=>280, 'height'=>400),
			$resizeType = BX_RESIZE_IMAGE_PROPORTIONAL,
			$arWaterMark = array(),
			$jpgQuality=45,
			$arFilters =false
		);
		if($st == false ){
			//echo'*';
			return $ImgSrcFullPath;
		}
		return $ImgTmbFullPath;//$ImgSrcFullPath;//$ImgTmbFullPath;
	}
	return $ImgTmbFullPath;
	}
?>