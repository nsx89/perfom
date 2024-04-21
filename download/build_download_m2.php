<?require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog.php");
if (!CModule::IncludeModule('iblock') || !CModule::IncludeModule("catalog")) {
    exit;
}
?>
<?
//echo json_encode($_POST);

$json = array();
if (isset($_POST['email'])) {
	if (!empty($_POST['email'])) {
    if(!preg_match("|^[-0-9a-z_\.]+@[-0-9a-z_^\.]+\.[a-z]{2,6}$|i", $_POST['email'])) { // проверим email на валидность
        $json['error'] = 'Не верный формат email!';
        echo json_encode($json); 
        die(); // убийство :(
    }

if (isset($_POST['fio'])) {
	if (empty($_POST['fio'])) {
 	$json['error'] = 'Поле "Ваше имя" обязательно к заполнению';
	echo json_encode($json);
	die(); // убийство :(
	} else { /*
	   if(!preg_match("/^[а-яА-Яё \t]+$/iu", $_POST['fio'])) { // проверим fio на валидность русских букв  
       	 	$json['error'] = 'Поле "Ваше имя" - Разрешено вводить только русские буквы!';
		echo json_encode($json); 
		die(); // убийство :(
	   }
	*/}

    }
$json = array();
if ($_POST['privatePolicy']=='N') {
    $json['error'] = 'Укажите, что Вы согласны на обработку персональных данных!';
    echo json_encode($json); 
    die(); // убийство :(
}

require($_SERVER["DOCUMENT_ROOT"] . '/bitrix/modules/main/include/prolog_before.php');
if (!CModule::IncludeModule('iblock')) exit;

$models_path = $_SERVER["DOCUMENT_ROOT"] . "/cron/catalog/data/models2";
$models_web_path = "/cron/catalog/data/models2";

set_time_limit(6000);

//$name = serialize($_POST);
//$name = md5($name);

$name = ''.date("m.d.y").'_('.rand(10000, 99999).')';
$request_array ='';

$zipName = $_SERVER["DOCUMENT_ROOT"] . '/upload/archive/perfom_' . $name . '.zip';
$downloadName = '/upload/archive/perfom_' . $name . '.zip';

$link = 'https://'.$_SERVER['HTTP_HOST'].$downloadName;

$cat_arr = Array(1542,1544);
$link_all = '';
function create_link_all($ext) {
    $link_all = '';

    //if($_POST['cat_id'] != 1542 && $_POST['cat_id'] != 1544) return $link_all; //карнизы, молдинги

    $arFilter = Array("IBLOCK_ID"=>12, "ACTIVE"=>"Y", "ID"=>$_POST['cat_id']);
    $db_list = CIBlockSection::GetList(Array(), $arFilter, true, Array('CODE'));
    $ar_result = $db_list->GetNext();
    $sec_code = $ar_result['CODE'];
    //$cat_path = $_SERVER["DOCUMENT_ROOT"] . '/upload/archive_full/perfom_'. $sec_code . '_' . $ext . '.zip';
    //$cat_path_dwnld = "https://".$_SERVER['HTTP_HOST'] . '/upload/archive_full/perfom_'. $sec_code . '_' . $ext . '.zip';
    $cat_path_dwnld = "https://".$_SERVER['HTTP_HOST'] . '/download/2d_'.$ext.'.zip';

    $link_all .= 'Скачать пакет выбранных 2D моделей в формате <b>'.$ext.'</b> вы можете по ссылке: <br>';
    $link_all .= '<br>&nbsp; &nbsp; &nbsp;<a href="'.$cat_path_dwnld.'">2D модели в формате '.$ext.'</a><br><br>';
    $link_all .= '&nbsp;Объем данного пакета составляет <b>'.round(filesize($cat_path)/1048576,2).'</b> MB<br><br>';
    return $link_all;
}

if (!file_exists($zipName)) {
    $zip = new ZipArchive();
    $handle = $zip->open($zipName, ZipArchive::CREATE | ZipArchive::OVERWRITE | ZipArchive::CM_STORE);
    if (is_array($_POST['d2pdf'])) {
        foreach ($_POST['d2pdf'] as $fname => $on) {
            $request_array .= $fname . ';';
            if($fname == 'all' && in_array($_POST['cat_id'],$cat_arr)) {
                $link_all = create_link_all('PDF');
                $link = $link_all;
                break;
            }
            $file = $models_path . "/pdf/" . $fname;
            $zip->addFile($file, $fname);
        }
    }
    if (is_array($_POST['d2dwg'])) {
        foreach ($_POST['d2dwg'] as $fname => $on) {
            $request_array .= $fname . ';';
            if($fname == 'all' && in_array($_POST['cat_id'],$cat_arr)) {
                $link_all = create_link_all('DWG');
                $link = $link_all;
                break;
            }
            $file = $models_path . "/dwg/" . $fname;
            $zip->addFile($file, $fname);
        }
    }
    $zip->close();


 // создание записи
   $resc = CIBlock::GetList(Array(), Array('CODE' => '2d_email'));
   while($arrc = $resc->Fetch()) 
   {
      $blockid = $arrc["ID"];
   }

   $arFieldsAdd = array(
    
   	"IBLOCK_ID" => $blockid,
   	"NAME" => "2d запрос",
   	"PROPERTY_VALUES" => array(
   	"EMAIL" => strtolower($_POST['email']),
   	"distribution" => $_POST['update2D'],
        "distribution_item" => $_POST['updateItem'], 
   	"FIO" => $_POST['fio'], 
   	"request_array" => $request_array,
	"region" => $_POST['region'],
    "link" => $link,
   	)
   );

	$oElement = new CIBlockElement();
	$idElement = $oElement->Add($arFieldsAdd, false, false, false);
 
	// Создание записи по базе e-mail
   $resc_email = CIBlock::GetList(Array(), Array('CODE' => 'email_base'));
   while($arrc = $resc_email->Fetch()) 
   {
      $blockid_email = $arrc["ID"];
   }

		
		$email = $_POST['email'];
		$distribution = $_POST['update2D'];
		$distribution_item = $_POST['updateItem'];
		$arFilter = Array('IBLOCK_ID' => $blockid_email, 'ACTIVE' => 'Y', 'NAME' => $email);
		$db_list_email = CIBlockElement::GetList(Array(), $arFilter);
		if ($fcontact_email = $db_list_email->GetNextElement()) { // есть е-маил
			$fcontact_email = array_merge($fcontact_email->GetFields(), $fcontact_email->GetProperties());

			if (($fcontact_email['distribution']['VALUE'] == 'N') && ($distribution == 'Y')) {
				CIBlockElement::SetPropertyValueCode($fcontact_email['ID'], 'distribution', 'Y');
			}

			if (($fcontact_email['distribution_item']['VALUE'] == 'N') && ($distribution_item == 'Y')) {
				CIBlockElement::SetPropertyValueCode($fcontact_email['ID'], 'distribution_item', 'Y');
			}

			CIBlockElement::SetPropertyValueCode($fcontact_email['ID'], 'fio', $_POST['fio']);
			CIBlockElement::SetPropertyValueCode($fcontact_email['ID'], 'region', $_POST['region']);
			
		
		} else { // новое мыло
		$arFieldsAdd = array(
    
   			"IBLOCK_ID" => $blockid_email,
   			"NAME" => $email,
   			"PROPERTY_VALUES" => array(
   			"distribution" => $distribution,
        		"distribution_item" => $distribution_item, 
   			"FIO" => $_POST['fio'], 
			"region" => $_POST['region'],
   			)
   			);

		$oElement = new CIBlockElement();
		$idElement = $oElement->Add($arFieldsAdd, false, false, false);
			
		}

 $zip_size = '<b>'.round(filesize($zipName)/1048576,2).'</b> MB';

$link_other = '';
if(file_exists($zipName)) {
    if($link_all != '') {
        $link_other .= 'Скачать пакет выбранных 2D моделей в остальных форматах вы можете по ссылке: <br>';
    } else {
        $link_other .= 'Скачать пакет выбранных 2D моделей вы можете по ссылке: <br>';
    }
    $link_other .= '<br>&nbsp; &nbsp; &nbsp;<a href="https://'.$_SERVER['HTTP_HOST'].$downloadName.'">2D модели</a><br><br>';
    $link_other .= '&nbsp;Объем данного пакета составляет '.$zip_size.'<br><br>';
}


 $fields = array('EMAIL'=>$_POST['email'],'LINK_ALL' => $link_all,'LINK_OTHER'=>$link_other);
 CEvent::SendImmediate("EUROPLAST_DOWNLOAD_2D", s1, $fields, "N");
}

} else {
	
	$json['error'] = 'Поле "Email" обязательно к заполнению';
	echo json_encode($json);
        die();
}

print '{"code":0,"id":null,"errors":[]}';

}

?>
