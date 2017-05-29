<?
class bitParser2 {
	private $fStartTime;
	private $startMemory;
	private $xml;
	private $status = Array();
	private $arSectionInIB = Array();
	private $arSectionInXML = Array();
	private $arElemetsInIB = Array();
	private $arElemetsInXML = Array();
	private $iAppartamentsIBID = 2;	
	private $sXmlFile = "http://dev.hh-an.ru/include/xml/xml/SiteData.xml";
	private $pidFile = 'abcParser2.lock';
	private $logFile = 'abcParser2.log';
	
	public function __construct() {
		$this->log("");
		$this->log("abcParser2 start...");		
		if($this->isLock()){
			$this->log("Other parser process running - exiting");		
			die();
		}
		$this->fStartTime = microtime(true);
		$this->startMemory = memory_get_usage();
		if( !($this->xml = simplexml_load_file($this->sXmlFile)) ){
			$this->log("Error: Cannot create SimpleXML object");
			die();
		}
		if (!CModule::IncludeModule("iblock")){
			$this->log("Error: Cannot load module");
			die();
			}
		$this->lock();
	}

	public function __destruct() {
		$this->log( "work time: " . number_format( microtime(true) - $this->fStartTime, 10 ) . " sec" );
		$this->log( "used: ".number_format(memory_get_usage() - $this->startMemory) . " bytes" );
		//$this->log("-------------------------------------");
		$this->unLock();
		
	}
	
	/**
	* Строит массив сеций на основе данных из инфоблока
	* Формат [xml-id секции] => array('SECTION_ID'=>'ИД секции в инфоблоке', 'NAME' => 'Название секции');
	* @return
	*/
	public function buildOldSectionArray(){
		$arOrder = Array("SORT"=>"ASC");
		$arFilter = Array("IBLOCK_ID"=>$this->iAppartamentsIBID,"ACTIVE"=>"Y");
		$arSelect = Array("ID","IBLOCK_ID","IBLOCK_SECTION_ID","NAME","XML_ID");
		$SectList = CIBlockSection::GetList($arOrder, $arFilter ,false, $arSelect);
        while ($arSection = $SectList->GetNext())
        {
        	//echo'<pre>';print_r($arSection);echo'</pre>';
            $this->arSectionInIB[ $arSection["XML_ID"] ]["SECTION_ID"] = $arSection["ID"];
            $this->arSectionInIB[ $arSection["XML_ID"] ]["NAME"] = $arSection["NAME"];
        } 
        //echo'---><pre>';print_r($this->arSectionInIB);echo'</pre>';
	}
	/**
	* Строит массив секций на основе данных из XML
	* 
	* @return
	*/
	
	function buildNewSectionArray(){
		$arBlocks = Array();
		$sxoBlocks = $this->xml->xpath("/Ads/Blocks/Block");
		//echo'---><pre>';print_r($sxoBlocks);echo'</pre>';
		foreach ($sxoBlocks as $Block) {
		    $blockID = (string)$Block[0]['id'];
		    $blockNAME = (string)$Block[0]['title'];
		    $arBlocks[$blockID]['NAME'] = $blockNAME;
		    //$arBlock[$blockID]['SECTION_ID'] = 0;
		}
		$this->arSectionInXML = $arBlocks;
	}
	
	/**
	* Добавляет из XML Жилые комплексы в инфоблок, в виде секций.
	* Несуществующий в инфоблоке добавляет
	* Существующие в инфоблоке обновляет
	* Отсутствующие в XML удаляет
	* @return
	*/
	function updateIBObjects(){
		$arNewObjects = Array();
		$arRemObjects = Array();
		$bs = new CIBlockSection;
		global $DB;
		
		$arNewObjects = array_diff_key( $this->arSectionInXML, $this->arSectionInIB);
		$arRemObjects = array_diff_key( $this->arSectionInIB, $this->arSectionInXML);
		
		//echo'new: <pre>';print_r($arNewObjects);echo'</pre>';
		//echo'rem: <pre>';print_r($arRemObjects);echo'</pre>';
		$this->log('Objects deleted: ' . count($arRemObjects) );
		$this->log('Objects added: ' . count($arNewObjects) );
		//echo'Objectss added:';echo count($arNewObjects);echo"\n";
		//@ob_flush();
		//flush();
		
		// добавляем новые комплексы
		foreach($arNewObjects as $xml_id=>$arCurrentBlock){
			$arFields = Array(
			  "ACTIVE" => 'Y',
			  "IBLOCK_SECTION_ID" => false,
			  "IBLOCK_ID" => $this->iAppartamentsIBID,
			  "NAME" => $arCurrentBlock['NAME'],
			  "SORT" => $xml_id*10,
			  "XML_ID" => $xml_id
			  );
			  // актуализируем массив с секциями
			  $this->arSectionInIB[$xml_id]['SECTION_ID'] = $bs->Add($arFields, false, false);
			  $this->arSectionInIB[$xml_id]['NAME'] = $arCurrentBlock['NAME'];
			  //echo $xml_id." adeded" . PHP_EOL;
		}
		// Удаляем отсутствующие в XML
		foreach($arRemObjects as $xml_id=>$arCurrentBlock){
			$DB->StartTransaction();
			CIBlockSection::Delete($arCurrentBlock['SECTION_ID']);
			$DB->Commit();
			// актуализируем массив с секциями
			unset( $this->arSectionInIB[$xml_id] );
			//echo $xml_id." deleted" . PHP_EOL;
		}
		//echo'rem: <pre>';print_r($this->arSectionInIB);echo'</pre>';
		CIBlockSection::Resort($this->iAppartamentsIBID);
	}
	

	/**
	* Строит массив квартир на основе данных из XML
	* 
	* @return
	*/	
	function buildNewApptArray(){
		$arAppts = Array();
		$sxoBlocks = $this->xml->xpath("/Ads/Apartments/Apartment");
		$cnt = 1000;// ограничение количеств добавляемых квартир на этапе тестирования
		//echo'---><pre>';print_r($sxoBlocks);echo'</pre>';
		foreach ($sxoBlocks as $Block) {
			//$cnt--; if($cnt == 0) break; // ограничение количеств добавляемых квартир на этапе тестирования
		    $apptID = (string)$Block[0]['id'];
		    $apptBlockID = (string)$Block[0]['blockid'];
		    $arAppts[$apptID]['BlockID'] = $apptBlockID;
		    //$arBlock[$apptID]['ELEMENT_ID'] = 0;
		}
		$this->arElemetsInXML = $arAppts;
		//echo 'size of appts_new: ' . sizeof($this->arElemetsInXML) . '<br>';
		//echo'><pre>';print_r($this->arElemetsInXML);echo'</pre>';
	}
	
	/**
	* Строит массив квартир на основе данных из инфоблока
	* Формат [xml-id секции] => array('SECTION_ID'=>'ИД секции в инфоблоке', 'NAME' => 'Название секции');
	* @return
	*/
	public function buildOldApptArray(){
		$arOrder = Array("SORT"=>"ASC");
		$arFilter = Array("IBLOCK_ID"=>$this->iAppartamentsIBID,"ACTIVE"=>"Y");
		$arSelect = Array("ID","IBLOCK_ID","IBLOCK_SECTION_ID","NAME","CODE");
		$res = CIBlockElement::GetList($arOrder, $arFilter ,false, false, $arSelect);
        while ($ob = $res->GetNextElement())
        {
        	$arFields = $ob->GetFields();
        	//echo'<pre>';print_r($arFields);echo'</pre>';
            $this->arElemetsInIB[ $arFields["CODE"] ]["ELEMENT_ID"] = $arFields["ID"];
            $this->arElemetsInIB[ $arFields["CODE"] ]["SECTION_ID"] = $arFields["IBLOCK_SECTION_ID"];
        } 
    	//echo 'size of appts_ib: ' . sizeof($this->arElemetsInIB) . '<br>';
		//echo'<pre>';print_r($this->arElemetsInIB);echo'</pre>';
	}
	
	/**
	* Добавляет из XML Жилые комплексы в инфоблок, в виде секций.
	* Несуществующий в инфоблоке добавляет
	* Существующие в инфоблоке обновляет
	* Отсутствующие в XML удаляет
	* @return
	*/
	function updateIBFlats(){
		$arNewFlats = Array();
		$arRemFlats = Array();
		$el = new CIBlockElement;
		global $DB;
		$cnt = 3;// ограничение количеств добавляемых квартир на этапе тестирования
		
		$arNewFlats = array_diff_key( $this->arElemetsInXML, $this->arElemetsInIB);
		$arRemFlats = array_diff_key( $this->arElemetsInIB, $this->arElemetsInXML);
		
		//echo'rem: <pre>';print_r($arRemFlats);echo'</pre>';
		//echo'new: <pre>';print_r($arNewFlats);echo'</pre>';
		//echo'Appartaments deleted: ';echo count($arRemFlats);echo"\n";
		//echo'Appartaments added: ';echo count($arNewFlats);echo"\n";
		$this->log('Appartaments deleted: ' . count($arRemFlats) );
		$this->log('Appartaments added: ' . count($arNewFlats) );
		
		
		//echo'<pre>';print_r($this->arElemetsInIB);echo'</pre>';
		//return; // остановимся пока
		// добавляем новые комплексы
		foreach($arNewFlats as $xml_id=>$arCurrentAppt){
			//$cnt--; if($cnt == 0) break; // ограничение количеств добавляемых квартир на этапе тестирования
			$block_id = $arCurrentAppt['BlockID'];
			$section_id = $this->arSectionInIB[ $block_id ]['SECTION_ID'];
			// заполняе свойства			
			$sxoApp = $this->xml->xpath("/Ads/Apartments/Apartment[@id=".$xml_id."]");
			//echo'sxo: <pre>';print_r($sxoApp);echo'</pre>';
			//$blockID = (string)$Block[0]['id'];	
			$PROP = array();
			$PROP['25'] = (int)$sxoApp[0]['id'];
			$PROP['26'] = (int)$sxoApp[0]['blockid'];
			$PROP['27'] = (int)$sxoApp[0]['buildingid'];
			$PROP['28'] = (int)$sxoApp[0]['section'];
			$PROP['29'] = (int)$sxoApp[0]['roomtypeid'];
			$PROP['30'] = (float)$sxoApp[0]['stotal'];
			$PROP['31'] = (string)$sxoApp[0]['sroom'];
			$PROP['32'] = (string)$sxoApp[0]['skitchen'];
			$PROP['33'] = (string)$sxoApp[0]['sbalcony'];
			$PROP['34'] = (string)$sxoApp[0]['scorridor'];			
			$PROP['35'] = (string)$sxoApp[0]['swatercloset'];
			$PROP['36'] = (string)$sxoApp[0]['height'];
			$PROP['37'] = (int)$sxoApp[0]['flattypeid'];
			$PROP['38'] = (string)$sxoApp[0]['decoration'];
			$PROP['39'] = (int)$sxoApp[0]['subsidy'];
			$PROP['40'] = (int)$sxoApp[0]['creditend'];
			$PROP['41'] = (int)$sxoApp[0]['flatcostwithdiscounts'];
			$PROP['42'] = (int)$sxoApp[0]['flatcostbase'];
			$PROP['43'] = (int)$sxoApp[0]['flatfloor'];
			$PROP['44'] = (string)$sxoApp[0]['flatplan'];			
			$arFields = Array(
			  "ACTIVE" => 'Y',
			  "IBLOCK_SECTION_ID" => $section_id,
			  "IBLOCK_ID" => $this->iAppartamentsIBID,
			  "PROPERTY_VALUES"=> $PROP,
			  "NAME" => $xml_id,
			  "SORT" => $xml_id,
			  "CODE" => $xml_id
			  );
			  // актуализируем массив с секциями
			  $this->arElemetsInIB[$xml_id]['ELEMENT_ID'] = $el->Add($arFields, false, false);
			  $this->arElemetsInIB[$xml_id]['SECTION_ID'] = $section_id;//$arCurrentBlock['NAME'];
			  //echo $xml_id.' adeded<br>';
		}
		//return; // остановимся пока
		// Удаляем отсутствующие в XML
		foreach($arRemFlats as $xml_id=>$arCurrentAppt){
			$DB->StartTransaction();
			CIBlockElement::Delete($arCurrentAppt['ELEMENT_ID']);
			$DB->Commit();
			// актуализируем массив с секциями
			unset( $this->arElemetsInIB[$xml_id] );
			//echo $xml_id.' deleted<br>';
		}
		//echo'updated: <pre>';print_r($this->arElemetsInIB);echo'</pre>';
	}
	public function isLock()
    {
		return file_exists($this->pidFile);
    }
	public function lock()
    {
        //echo 'lock pid', PHP_EOL;
        file_put_contents($this->pidFile, getmypid());
    }

	public function unLock()
    {
        unlink($this->pidFile);
        $this->stop_server = true;
       // echo 'Unlocked', PHP_EOL;
    }
    private function log($text){
    	if($text != "")
    		$dt = date("[m.d.y H:i:s] ");
    		else
    		$dt = "-------------------------------";
		file_put_contents($this->logFile, $dt.$text.PHP_EOL, FILE_APPEND);
		echo $text . PHP_EOL;
	}
}
?>