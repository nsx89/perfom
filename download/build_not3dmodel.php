<?
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog.php");

if (!CModule::IncludeModule('iblock') || !CModule::IncludeModule("catalog")) {
    exit;
}

    $iblockid = 12;

    $models_path = $_SERVER["DOCUMENT_ROOT"]."/cron/catalog/data/models";
    $models_web_path = "/cron/catalog/data/models";
    
    $download3dName = '/download/3d_not.csv';    


    set_time_limit(72000);

   if (!$fp = @fopen($_SERVER["DOCUMENT_ROOT"].$download3dName, "wb")) {echo 'не возможно открытие фала'; exit; }

	fwrite($fp, "No;Articul;max;gsm;dwg;3ds;obj"."\n");
	$count = 1;
	$arFilter = Array('IBLOCK_ID'=>$iblockid, 'ACTIVE'=>'Y');	
	$_items = CIBlockElement::GetList(array("PROPERTY_ARTICUL" => "ASC"), $arFilter, false); //false, array("nTopCount" => 120));
	while ($item = $_items->GetNextElement()) { // ->
		$item = array_merge($item->GetFields(), $item->GetProperties());

		$model_not = false;
		$model =$count.';'.$item['ARTICUL']['VALUE'].';';
        	foreach (array('max','gsm','dwg','3ds','obj') as $m_pre) {
           	$path = $models_path . "/" . $m_pre . "/" . $item['ARTICUL']['VALUE'] . '.'.$m_pre;
            	$web_path = $item['ARTICUL']['VALUE'] . '.'.$m_pre;
            		if (!file_exists($path)) {
                	$model_not = true;
			$model .= ' - ;'; 
            		} else $model .= ' Yes ;'; 
		
        	}

		if ($model_not) {
			fwrite($fp, $model."\n");
			$count +=1;
		}
	}
    
  fclose($fp);
  echo 'файл данных сформирован ...'.'<br>';
  echo '<a href="'.$download3dName.'">загрузить</a>';
?>
