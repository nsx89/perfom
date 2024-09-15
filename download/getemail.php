<?require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog.php");
if (!CModule::IncludeModule('iblock') || !CModule::IncludeModule("catalog")) {
    exit;
}

$email = null;
$e_item = false;
$e_model = false;
$fio = '';

if (isset($_GET['email'])) $email = $_GET['email'];

$resc_email = CIBlock::GetList(Array(), Array('CODE' => 'email_base'));
   while($arrc = $resc_email->Fetch()) 
   {
      $blockid_email = $arrc["ID"];
   }

$arFilter = Array('IBLOCK_ID' => $blockid_email, 'ACTIVE' => 'Y', 'NAME' => $email);
$db_list = CIBlockElement::GetList(Array(), $arFilter);
if ($fcontact = $db_list->GetNextElement()) {
	$fcontact = array_merge($fcontact->GetFields(), $fcontact->GetProperties());
	if ($fcontact['distribution']['VALUE'] == 'Y') $e_model = true;
	if ($fcontact['distribution_item']['VALUE'] == 'Y') $e_item = true;	
	$email = $fcontact['NAME'];
	$fio = $fcontact['FIO']['VALUE'];
}

print json_encode(array('email' => $email, 'item' => $e_item, 'model' => $e_model, 'fio' => $fio));

?>
