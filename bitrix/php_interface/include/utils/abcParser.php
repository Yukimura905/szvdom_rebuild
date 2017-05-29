<?
class abcParser {
	private $fStartTime;
	private $xml;
	private $arCurObjects = Array();
	private $atatus = Array();
	// Appartaments complecses block id
	private $iAppCompIBID = 1;
	private $iAppartamentsIBID = 1;
	// Appartaments block id
	//private $iAppIBID = 2;
	//private $sXmlFile = "http://szvdom.ru/include/xml/SiteData.xml";
	private $sXmlFile = "../../../../include/xml/xml/SiteData.xml";


	public function __construct() {
		//echo 'load - ok!<br>';
		$this->fStartTime = microtime(true);
		$this->xml = simplexml_load_file($this->sXmlFile) or die("Error: Cannot create object");
		
		if (!CModule::IncludeModule("iblock")) die("Error: Cannot load module");
		$this->getCurentObjecktsList();		
		
	}
	public function __destruct() {
		echo "work time: " . number_format( microtime(true) - $this->fStartTime, 10 ) . ' sec<br>';
	}
	public function showBlockStat(){		
		echo "---------------------------------------------------------<br>";
		echo "total parsed: " . $this->status['total_parsed'] . "<br>";
		echo "total base objects: " . $this->status['obj_base_count'] . "<br>";
		echo "added: " . $this->status['new_objects_added'] . '<br>';
		echo "updated: " . $this->status['objects_updated'] . '<br>';
		echo "deactivated: " . $this->status['deactivated_objects'] . '<br>';
		echo "autoupdate bloked: " . $this->status['upd_blk'] . '<br>';
		echo "---------------------------------------------------------<br>";		
	}


    function getCurentObjecktsList(){
		$arFilter = array("IBLOCK_ID" => $this->iAppCompIBID);//, "ACTIVE"=>"Y");
		$arSelectFields = array("ID", "IBLOCK_ID", "NAME", "PROPERTY_*");
		$res = CIBlockElement::GetList(array(), $arFilter, false, false, $arSelectFields);
		while($ob = $res->GetNextElement()) {
			$arFields = $ob->GetFields();
			$arProps = $ob->GetProperties();
			//echo'<pre>';print_r($arFields);echo'</pre>';
			$this->arCurObjects[ $arProps['SER_XML_ID']['VALUE'] ]['OB_ID'] = $arFields['ID'];
			$this->arCurObjects[ $arProps['SER_XML_ID']['VALUE'] ]['UPDATE_AUTO'] = $arProps['SER_UPDATE_AUTO']['VALUE'];
			$this->arCurObjects[ $arProps['SER_XML_ID']['VALUE'] ]['FIND'] = 0;
			//$this->arCurObjects[ $arProps['SER_XML_ID']['VALUE'] ]['PROPS'] = $arProps;
			//$this->arCurObjects[ $arProps['SER_XML_ID']['VALUE'] ]['FIELDS'] = $arFields;
		}
		$this->status['obj_base_count'] = sizeof( $this->arCurObjects );
		//echo'<pre>';print_r($this->arCurObjects);echo'</pre>';
	}
    
    
    function clearAll(){    	
		$result = CIBlockElement::GetList(array("ID"=>"ASC"),array('IBLOCK_ID'=>$this->iAppCompIBID, 'SECTION_ID'=>0,'INCLUDE_SUBSECTIONS'=>'N'));
		while($element = $result->Fetch())
			CIBlockElement::Delete($element['ID']);					
		}

	function insertObjekts(){
		$arObjekts = $this->makeArray();
		 $this->status['total_parsed'] = sizeof( $arObjekts );
		//echo'<pre>';print_r($arObjekts);echo'</pre>';
		$el = new CIBlockElement;
		$cnt=2;

		//pre($arObjekts);
		
		
		foreach ($arObjekts as $key => $objekt){
			//echo $objekt['TITLE'];echo'<br>';
			//$cnt--; if($cnt == 0) break; //echo $cnt.'<br>';
			
			
			if( array_key_exists($key, $this->arCurObjects) ){
				//echo $key.' - found<br>';
				$this->arCurObjects[$key]['FIND'] = 1;
				if( $this->arCurObjects[$key]['UPDATE_AUTO'] != 'Да' ){					
					$this->status['upd_blk']++;
					continue;
					}
				$UPD_EL_ID = $this->arCurObjects[$key]['OB_ID'];
			}
			else{
				$UPD_EL_ID = 0;
				//echo $key.' - not found<br>';			
			}
				


			if (false AND $objekt['BUILDINGS']['endingperiod']['MAX'] < date("Y")){
			    $dateEnd = "Сдан";
			}else{
			    $dateEnd = array($objekt['BUILDINGS']['endingperiod']['MIN'], $objekt['BUILDINGS']['endingperiod']['MAX']);
			}

		foreach ($objekt['BUILDINGS']['countlinecorp'] as $jjj => $value){

            if (count($objekt['BUILDINGS']['countline'][$jjj]) > 1){
                foreach ($objekt['BUILDINGS']['countline'][$jjj] as $j => $end){
                    $yearAr = explode('.',$end);
                    if (false AND $yearAr[2] < date('Y')){
                        $dataLine = " сдан";
                    }else{
                        $dataLine = $end;
                    }
                    if ($dataLine != " сдан"){
                        $lines[] = $jjj." оч (".$objekt['BUILDINGS']['countlinecorp'][$jjj][$j].") - ".$dataLine." г." ;
                    }else{
                        $lines[] = $jjj." оч (".$objekt['BUILDINGS']['countlinecorp'][$jjj][$j].") - ".$dataLine ;
                    }
                }
            }else{
                $yearAr = explode('.',$objekt['BUILDINGS']['countline'][$jjj][0]);
                if (false AND $yearAr[2] < date('Y')){
                    $dataLine = " сдан";
                }else{
                    $dataLine = $objekt['BUILDINGS']['countline'][$jjj][0];
                }
                if ($dataLine != " сдан"){
                    $lines[] = $jjj." оч - ".$dataLine." г." ;
                }else{
                    $lines[] = $jjj." оч - ".$dataLine ;
                }
            }
        }
        asort($lines);



			$PROP = array();
			$PROP['SER_XML_ID'] = $key;
			//$PROP['SER_UPDATE_AUTO'] = $this->getPropertyID(array('ID_YES'), "SER_UPDATE_AUTO", false);
			$PROP['GEO_REGION'] = $this->getPropertyID(array($objekt['REGION_ID']), "GEO_REGION", false);			
			$PROP['GEO_METRO'] = $this->getPropertyID($objekt['SUBWAYS'], "GEO_METRO", true);
			$PROP['GEO_METRO_DEST'] = $objekt['DISTANCE'];;
			$PROP['GEO_ADRESS'] = $objekt['ADDRESS'];
			$PROP['GEO_GPS'] = array($objekt['LATITUDE'],$objekt['LONGITUDE']);
			$PROP['APART_TYPES'] = $this->getPropertyID($objekt['APARTMENTS']['rooms'], "APART_TYPES", true);;
			$PROP['APART_PRICE_DIAPASON'] = $objekt['APARTMENTS']['flatcost'];
			$PROP['APART_PRICE_MIN'] = $objekt['APARTMENTS']['cost']['MIN'];
			$PROP['APART_PRICE_MAX'] = $objekt['APARTMENTS']['cost']['MAX'];
			$PROP['HF_BUILD_TYPE'] = $this->getPropertyID($objekt['BUILDINGS']['buildingtype'],"HF_BUILD_TYPE",false);
			$PROP['APART_AREA_DIAPASON'] = $objekt['APARTMENTS']['stotal'];
			$PROP['APART_AREA_MIN'] = $objekt['APARTMENTS']['area']['MIN'];;
			$PROP['APART_AREA_MAX'] = $objekt['APARTMENTS']['area']['MAX'];;
			$PROP['HF_FLORS'] = $objekt['BUILDINGS']['max_floors'];
			//$PROP['SER_HIDDEN'] = $this->getPropertyID(array('ID_NO'), "SER_HIDDEN", false);
			$PROP['FIN_BUY_TYPE'] = $this->getPropertyID(array($objekt['BUILDINGS']['mortgage'], $objekt['APARTMENTS']['creditend'], $objekt['APARTMENTS']['subsidy']), "FIN_BUY_TYPE", false);
			$PROP['FIN_BANKS'] = $this->getPropertyID($objekt['BANKS'],"FIN_BANKS",true);
			//$PROP['OPT_SHOW_ON_MAIN'] = $this->getPropertyID(array('ID_NO'), "OPT_SHOW_ON_MAIN", false);
			//$PROP['OPT_SHOW_ON_OFFER'] = $this->getPropertyID(array('ID_NO'), "OPT_SHOW_ON_OFFER", false);
			$PROP['HF_BUILDER'] = $this->getPropertyID(array($objekt['BUILDER']),"HF_BUILDER",false);
			$PROP['APART_COUNT'] = $objekt['APARTMENTS']['countbytype'];
			$PROP['APART_COUNT_TOTAL'] = $objekt['APARTMENTS']['count'];
			$PROP['FIN_FLAT_TYPE'] = $this->getPropertyID($objekt['APARTMENTS']['flattypeid'],"FIN_FLAT_TYPE",true);
			$PROP['APART_OTDELKA'] = $this->getPropertyID($objekt['APARTMENTS']['decoration'] ,"APART_OTDELKA",true);
			$PROP['SER_ENDINGPERIOD'] = $dateEnd;
			$PROP['IMG_AVATAR'] = $objekt['AVATAR'];			
			$PROP['HF_LINES'] = $lines;
			unset($lines);

			//echo'****************<br><pre>';print_r($PROP['11']);echo'</pre>';
			$params = Array(
				"max_len" => "100", // обрезает символьный код до 100 символов
				"change_case" => "L", // буквы преобразуются к нижнему регистру
				"replace_space" => "_", // меняем пробелы на нижнее подчеркивание
				"replace_other" => "_", // меняем левые символы на нижнее подчеркивание
				"delete_repeat_replace" => "true", // удаляем повторяющиеся нижние подчеркивания
				"use_google" => "false", // отключаем использование google
			);

			if($objekt['APARTMENTS']['cost']['MIN'] == 0)
				$sActive = "N";
				else
				$sActive = "Y";

			$arLoadProductArray = Array(
			  "IBLOCK_ID"      => $this->iAppCompIBID,
			  "PROPERTY_VALUES"=> $PROP,
			  "NAME"           => $objekt['TITLE'],
			  'PREVIEW_TEXT' => $objekt['NOTE'],
			  "CODE" => CUtil::translit($objekt['TITLE'], "ru" , $params),
			  "ACTIVE"         => $sActive
			  );
			
			if($UPD_EL_ID == 0){
				// defaul values set;
				$PROP['2'] = $this->getPropertyID(array('ID_YES'), "SER_UPDATE_AUTO", false);
				$PROP['13'] = $this->getPropertyID(array('ID_NO'), "SER_HIDDEN", false);
				$PROP['16'] = $this->getPropertyID(array('ID_NO'), "OPT_SHOW_ON_MAIN", false);			
				$PROP['17'] = $this->getPropertyID(array('ID_NO'), "OPT_SHOW_ON_OFFER", false);
				$arLoadProductArray["PROPERTY_VALUES"] = $PROP;
				$el->Add($arLoadProductArray);//, false, false, false);
				$this->status['new_objects_added']++;
			}
			else{
				unset( $arLoadProductArray["PROPERTY_VALUES"] );
				//$arLoadProductArray["NAME"] = 'du';
				$el->Update($UPD_EL_ID, $arLoadProductArray);
				CIBlockElement::SetPropertyValuesEx($UPD_EL_ID, false, $PROP );
				$this->status['objects_updated']++;
			}	
			

		}
		// deactivate missed objectsParser		
		foreach($this->arCurObjects as $key=>$objekt){
			if( $objekt['FIND'] == 0 ){
				$this->status['deactivated_objects']++;
				// ...deactivate object code ....
				$arLoadProductArray = Array(
				  "ACTIVE"         => "N"
				  );
				$el->Update( $objekt['OB_ID'] , $arLoadProductArray);
			}
		}
		//echo'<pre>';print_r( $this->arCurObjects);echo'</pre>';	
	}




	function getPropertyID($array, $code, $k) {
		$result = array();
		$dummy = array();
		//echo "--> ".$code."\n";
		//echo '===><pre>';print_r($array);echo'</pre>';
		
		$property = CIBlockPropertyEnum::GetList(Array("DEF"=>"DESC", "SORT"=>"ASC"), Array("IBLOCK_ID"=>$this->iAppCompIBID, "CODE"=>$code));
		while($fields = $property->GetNext()) {
			$xmlID = $fields["XML_ID"];
			$dummy[$xmlID] = array("ID" => $fields["ID"], "XML_ID" => $fields["XML_ID"], "VALUE" => $fields["VALUE"]);
		}
		//echo'---><pre>';print_r($dummy);echo'</pre>';
		
		foreach($array as $key => $val) {
			if ($k) $result[] = $dummy[$key]['ID'];
			else $result[] = $dummy[$val]['ID'];
		}
		//echo'++++><pre>';print_r($result);echo'</pre>';
		
		return $result;
	}



    function makeObjectXml($value){
        $OBJECTS = array();
        $Blocks = $this->xml->xpath("/Ads/Blocks/Block[@id='" . $value . "']");

        foreach ($Blocks as $Block) {

            $blockID = (string)$Block[0]['id'];
            $region = $this->xml->xpath("/Ads/Regions/Region[@id='" . (string)$Block[0]['region'] . "']");
            $builder = $this->xml->xpath("/Ads/Builders/Builder[@id='" . (string)$Block[0]['builderid'] . "']");
            $blocksubways = $this->xml->xpath("/Ads/BlockSubways/BlockSubway[@blockid='" . $blockID . "']");
            $banks = $this->xml->xpath("/Ads/Mortgages/Mortgage[@blockid='" . $blockID . "']");
            $buildings = $this->xml->xpath("/Ads/Buildings/Building[@blockid='" . $blockID . "']");
            $apartments = $this->xml->xpath("/Ads/Apartments/Apartment[@blockid='" . $blockID . "']");

            $toArraySubways = $this->toArraySubways($blocksubways);
            $toArrayBanks = $this->toArrayBanks($banks);
            $toArrayBuildings = $this->toArrayBuildings($buildings);
            $toArrayApartments = $this->toArrayApartments($apartments);

			pre($toArrayApartments);

            $OBJECTS[$blockID]['ID'] = $blockID;
            $OBJECTS[$blockID]['TITLE'] = (string)$Block[0]['title'];
            $OBJECTS[$blockID]['ADDRESS'] = (string)$Block[0]['address'];
            $OBJECTS[$blockID]['LATITUDE'] = (string)$Block[0]['latitude'];
            $OBJECTS[$blockID]['LONGITUDE'] = (string)$Block[0]['longitude'];
            $OBJECTS[$blockID]['AVATAR'] = (string)$Block[0]['avatar'];
            $OBJECTS[$blockID]['REGION_ID'] = (string)$region[0]['id'];
            $OBJECTS[$blockID]['REGION'] = (string)$region[0]['name'];
            $OBJECTS[$blockID]['BUILDER'] = (string)$builder[0]['id'];
            $OBJECTS[$blockID]['SUBWAYS'] = $toArraySubways;
            $OBJECTS[$blockID]['BANKS'] = $toArrayBanks;
            $OBJECTS[$blockID]['BUILDINGS'] = $toArrayBuildings;
            $OBJECTS[$blockID]['APARTMENTS'] = $toArrayApartments;
            $OBJECTS[$blockID]['EXTRAS'] = array($toArrayBuildings['mortgage'], $toArrayApartments['creditend'], $toArrayApartments['decoration']);

        }

        return $OBJECTS;
    }


	function makeArray() {
	
		$OBJECTS = array();
		$Blocks = $this->xml->xpath("/Ads/Blocks/Block");
		$cnt = 3;
		foreach ($Blocks as $Block) {
			//$cnt--; if($cnt == 0) break;
			$blockID = (string)$Block[0]['id'];			
			$region = $this->xml->xpath("/Ads/Regions/Region[@id='" . (string)$Block[0]['region'] . "']");
			$builder = $this->xml->xpath("/Ads/Builders/Builder[@id='" . (string)$Block[0]['builderid'] . "']");
			$blocksubways = $this->xml->xpath("/Ads/BlockSubways/BlockSubway[@blockid='" . $blockID . "']");
			$banks = $this->xml->xpath("/Ads/Mortgages/Mortgage[@blockid='" . $blockID . "']");
			$buildings = $this->xml->xpath("/Ads/Buildings/Building[@blockid='" . $blockID . "']");
			$apartments = $this->xml->xpath("/Ads/Apartments/Apartment[@blockid='" . $blockID . "']");
			
			
			$toArraySubways = $this->toArraySubways($blocksubways);
			$toArrayBanks = $this->toArrayBanks($banks);
			$toArrayBuildings = $this->toArrayBuildings($buildings);
			$toArrayApartments = $this->toArrayApartments($apartments);
			$toArrayDisatance = $this->toArrayDisatance($blocksubways);

			$OBJECTS[$blockID]['ID'] = $blockID;
			$OBJECTS[$blockID]['TITLE'] = (string)$Block[0]['title'];
			$OBJECTS[$blockID]['NOTE'] = (string)$Block[0]['note'];
			$OBJECTS[$blockID]['ADDRESS'] = (string)$Block[0]['address'];
			$OBJECTS[$blockID]['LATITUDE'] = (string)$Block[0]['latitude'];
			$OBJECTS[$blockID]['LONGITUDE'] = (string)$Block[0]['longitude'];
			$OBJECTS[$blockID]['AVATAR'] = (string)$Block[0]['avatar'];
			$OBJECTS[$blockID]['REGION_ID'] = (string)$region[0]['id'];
			$OBJECTS[$blockID]['REGION'] = (string)$region[0]['name'];
			$OBJECTS[$blockID]['BUILDER'] = (string)$builder[0]['id'];
			$OBJECTS[$blockID]['SUBWAYS'] = $toArraySubways;
			$OBJECTS[$blockID]['BANKS'] = $toArrayBanks;
			$OBJECTS[$blockID]['BUILDINGS'] = $toArrayBuildings;
			$OBJECTS[$blockID]['APARTMENTS'] = $toArrayApartments;
			$OBJECTS[$blockID]['EXTRAS'] = array($toArrayBuildings['mortgage'], $toArrayApartments['creditend'], $toArrayApartments['decoration']);
			$OBJECTS[$blockID]['DISTANCE'] = $toArrayDisatance;

			
		}
		
		return $OBJECTS;
		
	}

	function toArrayDisatance($array) {
		//	echo'<pre>';print_r($array);echo'</pre>';		
		$result = array();

		foreach ($array as $item) {

			$itemID = (string)$item[0]['blockid'];
			$swID = (string)$item[0]['subwayid'];
			$subway = $this->xml->xpath("/Ads/BlockSubways/BlockSubway[@subwayid='" . $swID . "' and @blockid='" . $itemID . "']");
			$subwayname = $this->xml->xpath("/Ads/Subways/Subway[@id='" . $swID . "']");

			$result[$swID] = (string)$subway[0]['distance'] . ' от метро ' . (string)$subwayname[0]['name'];

		}
		//echo '--------------------<br>';echo'<pre>';print_r($result);echo'</pre>';		
		return $result;

	}


	function toArraySubways($array) {
		
		$result = array();
		
		foreach ($array as $item) {
		
			$itemID = (string)$item[0]['subwayid'];
			$subway = $this->xml->xpath("/Ads/Subways/Subway[@id='" . $itemID . "']");
			
			$result[$itemID] = (string)$subway[0]['name'];
			
		}
		
		return $result;
		
	}
	
	function toArrayBanks($array) {
		
		$result = array();
		
		foreach ($array as $item) {
		
			$itemID = (string)$item[0]['bankid'];
			$bank = $this->xml->xpath("/Ads/Banks/Bank[@id='" . $itemID . "']");
			
			$result[$itemID] = (string)$bank[0]['name'];
			
		}
		
		return $result;

	}
	
	function toArrayBuildings($array) {
		//echo'<pre>';print_r($array);echo'</pre>';
		$result = array();
		$result['mortgage'] = NULL;
		$result['max_floors'] = 0;
		
		foreach ($array as $item) {
			$endingPeriod = (int)substr((string)$item[0]['endingperiod'], -4);
			if ((string)$item[0]['mortgage'])
				$result['mortgage'] = "IPO";
				
			if ((int)$item[0]['floors'] > $result['max_floors']) 
				$result['max_floors'] = (int)$item[0]['floors'];

            //$buildTypeTrue = $this->xml->xpath("/Ads/BuildingTypes/BuildingType[@id='" . (string)$item[0]['buildingtype'] . "']");
			if (in_array((string)$buildTypeTrue[0]['name'], $result['buildingtype'])) {} else {

				//$result['buildingtype'][] = (string)$buildTypeTrue[0]['name'];
                $result['buildingtype'][] = (string)$item[0]['buildingtype'];
			}
			
			if (!isset($result['endingperiod']['MIN']) || !isset($result['endingperiod']['MAX'])) {
				$result['endingperiod']['MIN'] = $endingPeriod;
				$result['endingperiod']['MAX'] = $endingPeriod;
			}
			if (isset($result['endingperiod']['MIN']) && $result['endingperiod']['MIN'] > $endingPeriod)
				$result['endingperiod']['MIN'] = $endingPeriod;
			if (isset($result['endingperiod']['MAX']) && $result['endingperiod']['MAX'] < $endingPeriod)
				$result['endingperiod']['MAX'] = $endingPeriod;

            $result["countline"][(string)$item["line"]][] = (string)$item["endingperiod"];
            $result["countlinecorp"][(string)$item["line"]][] = (string)$item["corp"];

		}
		return $result;
		
	}

	function toArrayApartments($array) {
		
		$result = array();
		$result['creditend'] = NULL;
		$result['decoration'] = NULL;
		$roomIDsOut = array(25,26,28,29,30);
		$roomIDsSkip = array(25,30);
        $arRoomsType = $this->getDict();
        //echo'<pre>';print_r($arRoomsType);echo'</pre>';
        $arTemp = Array();
			
		$result['count'] = 0;
		$result['cost']['MIN'] = 0;
		$result['cost']['MAX'] = 0;
		foreach($arRoomsType as $id=>$name){
			$result['flatcost'][$id]['MIN'] = 0;
			$result['flatcost'][$id]['MAX'] = 0;
			$result['countbytype'][$id] = 0;
		}		
		
		$result['area']['MIN'] = 0;
		$result['area']['MAX'] = 0;
		foreach($arRoomsType as $id=>$name){
			$result['stotal'][$id]['MIN'] = 0;
			$result['stotal'][$id]['MAX'] = 0;			
		}


		foreach ($array as $item) {
			
			$flatCost = (int)$item[0]['flatcostwithdiscounts'];
			$stotal = (float)$item[0]['stotal'];
			$roomID = (string)$item[0]['roomtypeid'];
			$ftID = (string)$item[0]['flattypeid'];
			
			
			$result['countbytype'][$roomID]++;
			$result['count']++;
			//if( (int)$item[0]['blockid'] == 30 ) echo (int)$item[0]['rooms'].'***<br>';

            //if ($roomID == "25") { continue; }
        

            

/*            if ( !isset($result['flatcost']['total']['MIN']) ) {
				$result['flatcost']['total']['MIN'] = $flatCost;
				$result['flatcost']['total']['MAX'] = $flatCost;
			}

*/			


/*            if ( !isset($result['flatcost'][$roomID]['MIN']) ) {
				$result['flatcost'][$roomID]['MIN'] = $flatCost;
				$result['flatcost'][$roomID]['MAX'] = $flatCost;
			}
*/		
			
			if ($result['flatcost'][$roomID]['MIN'] > $flatCost or $result['flatcost'][$roomID]['MIN'] == 0)
				$result['flatcost'][$roomID]['MIN'] = $flatCost;
			if ($result['flatcost'][$roomID]['MAX'] < $flatCost)
				$result['flatcost'][$roomID]['MAX'] = $flatCost;
		if( !in_array($roomID, $roomIDsSkip) ):
			if ($result['cost']['MIN'] > $flatCost or $result['cost']['MIN'] == 0)
				$result['cost']['MIN'] = $flatCost;
			if ($result['cost']['MAX'] < $flatCost)
				$result['cost']['MAX'] = $flatCost;
		endif;





/*			if ( !isset($result['stotal']['total']['MIN']) ) {
				$result['stotal']['total']['MIN'] = $stotal;
				$result['stotal']['total']['MAX'] = $stotal;
			}
*/			
/*			if ( !isset($result['stotal'][$roomID]['MIN']) ) {
				$result['stotal'][$roomID]['MIN'] = $stotal;
				$result['stotal'][$roomID]['MAX'] = $stotal;

			}*/
			
			if ($result['stotal'][$roomID]['MIN'] > $stotal or $result['stotal'][$roomID]['MIN'] == 0)
				$result['stotal'][$roomID]['MIN'] = $stotal;
			if ($result['stotal'][$roomID]['MAX'] < $stotal)
				$result['stotal'][$roomID]['MAX'] = $stotal;
		if( !in_array($roomID, $roomIDsSkip) ):
			if ($result['area']['MIN'] > $stotal or $result['area']['MIN'] == 0)
				$result['area']['MIN'] = $stotal;
			if ($result['area']['MAX'] < $stotal)
				$result['area']['MAX'] = $stotal;
		endif;
			//ksort($result['stotal']);
				
			if ((string)$item[0]['creditend'])
				$result['creditend'] = "RAS";
				
//			if ((string)$item[0]['decoration'] != "Без отделки")//Подчистовая//Чистовая//Без отделки
//				$result['decoration'] = "OTD";
/*			if ((string)$item[0]['decoration'] == "Без отделки")
				$result['decoration']["BO"] = "BO";
			if ((string)$item[0]['decoration'] == "Подчистовая")
				$result['decoration']["PCH"] = "PCH";
			if ((string)$item[0]['decoration'] == "Чистовая")
				$result['decoration']["CH"] = "CH";
*/
			$sDecoration = (string)$item[0]['decoration'];
			if (!in_array($sDecoration, $result['decoration'])){				
				$result['decoration'][ $sDecoration ] = $sDecoration;
			}
			//			pre($result['decoration']);
			//pre($sDecoration);

			
			if ((string)$item[0]['subsidy'] == "1")
				$result['subsidy'] = "SUB";	
				

/*
			if (!in_array($roomID, $roomIDsOut)) {
				
				if ($roomID === "0") $result['rooms']['S'] = "Ст";					
				if ($roomID === "1") $result['rooms']['1'] = "1";					
				if (in_array($roomID, array(2,22))) $result['rooms']['2'] = "2";				
				if (in_array($roomID, array(3,23))) $result['rooms']['3'] = "3";
				if (in_array($roomID, array(4,21)) || ($roomID > "4" && $roomID < "10")) 
					$result['rooms']['M'] = "4+";
			
			}*/
			if($roomID == 0) $roomID = '00';
			if (!in_array($roomID, $result['rooms'])){				
				$result['rooms'][$roomID] = $roomID;
			}
			if($ftID == 0) $ftID = '00';
			if (!in_array($roomID, $result['flattypeid'])){				
				$result['flattypeid'][$ftID] = $ftID;
			}
			
			
			
		}

		$arTemp = Array();
		foreach($result['stotal'] as $key=>$arTotal)
			$arTemp[] = $arTotal['MIN'].'-'.$arTotal['MAX'];
		$result['stotal'] = $arTemp;
		
		$arTemp = Array();
		foreach($result['flatcost'] as $key=>$arTotal)
			$arTemp[] = $arTotal['MIN'].'-'.$arTotal['MAX'];
		$result['flatcost'] = $arTemp;			
			
		
		/*$flatcostMIN = $result['flatcost']['MIN'] * 0.2;
		$flatcostMAX = $result['flatcost']['MAX'] * 0.2;
		$result['flatcost']['MIN'] = $result['flatcost']['MIN'] - (int)$flatcostMIN;
		$result['flatcost']['MAX'] = $result['flatcost']['MAX'] + (int)$flatcostMAX;*/
        $flatcostMIN = $result['flatcost']['MIN'] * 0.2;
        $flatcostMAX = $result['flatcost']['MAX'] * 0.2;
        $result['flatcost_visual']['MIN'] = $result['flatcost']['MIN'] - (int)$flatcostMIN;
        $result['flatcost_visual']['MAX'] = $result['flatcost']['MAX'] + (int)$flatcostMAX;
        if ($result['flatcost']['MAX'] > 36000000){
            $result['flatcost']['MAX'] = 36000000;
        }
        if ($result['flatcost_visual']['MAX'] > 36000000){
            $result['flatcost_visual']['MAX'] = 36000000;
        }
		return $result;
		
	}
  
  function getDict($path = "/Ads/RoomTypes/RoomType"){
  	$array = $this->xml->xpath($path);
  	$arDict = Array();
  	
	foreach ($array as $item){
		$itemID = (string)$item['id'];
		$itemNAME = (string)$item['name'];
		$arDict[$itemID] = $itemNAME;		
	}
	asort($arDict);
	return $arDict;
  }

  function updateDict(){
    $array = $this->xml->xpath("/Ads/Subways/Subway");
    $propID = 7;
	$this->updateEnum($array, $propID);

    $array = $this->xml->xpath("/Ads/Banks/Bank");
    $propID = 20;
	$this->updateEnum($array, $propID);

    $array = $this->xml->xpath("/Ads/Builders/Builder");
    $propID = 17;
	$this->updateEnum($array, $propID); 

    //Regions   
    $array = $this->xml->xpath("/Ads/Regions/Region");
    $propID = 5;
	 $this->updateEnum($array, $propID);

    //BuildingTypes
    $array = $this->xml->xpath("/Ads/BuildingTypes/BuildingType");
    $propID = 15;
	$this->updateEnum($array, $propID);

    //RoomTypes
	  $array = $this->xml->xpath("/Ads/RoomTypes/RoomType");//!!!!!!!!!!
    $propID = 10;
	$this->updateEnum($array, $propID);

    //FlatTypes
    $array = $this->xml->xpath("/Ads/FlatTypes/FlatType");
    $propID = 21;
	$this->updateEnum($array, $propID);

    //Decorations
    $array = $this->xml->xpath("/Ads/Decorations/Decoration");
    $propID = 14;
	$this->updateEnum($array, $propID);

  }

  function updateEnum($array, $propID){

    $arEnumProperty = Array();
    $arTemp = Array();
    $cnt = 0;
    

    foreach ($array as $item){
      $itemID = (string)$item['id'];
		$itemNAME = (string)$item['name'];// .'0xFF';
		if( isset($item['iscityregion']) and $item['iscityregion'] != 0) $itemNAME =$itemNAME.' (ЛО)';
      $arTemp[$itemID] = $itemNAME;
      //$arEnumProperty[$itemID] = Array('SORT'=>$itemID*10, 'VALUE'=>$itemNAME); 
		//pre($item);
    }
    asort($arTemp);
	  //return;
    foreach ($arTemp as $id=>$name){
      $cnt++;
      if($id == 0) $id = '00';
		$arEnumProperty[$id] = Array('SORT'=>$cnt*10, 'VALUE'=>$name, 'XML_ID'=>$id);//[$id]
    }
   
	  //	  $CIBlockProp = new CIBlockPropertyEnum;//CIBlockProperty;
	  $CIBlockProp = new CIBlockProperty;//$CIBlockProp->UpdateEnum($propID, $arEnumProperty);
	  if ( $CIBlockProp->UpdateEnum($propID, $arEnumProperty) )
			echo 'Property ' .$propID." updatet Ok\n";
		else
			echo 'Property ' .$propID." updatet Error\n";
		pre($arEnumProperty);
	  //$CIBlockProp->Add($arEnumProperty);

	  //    echo'<pre>';print_r($arEnumProperty);echo'</pre>';
  }

}
?>