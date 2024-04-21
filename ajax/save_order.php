<?require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog.php");
if (!CModule::IncludeModule('iblock') || !CModule::IncludeModule("catalog")) {
    exit;
}
//saved_orders
$order = '';
$id = '';

$order = $_REQUEST['order'];
$id = $_REQUEST['id'];

if($order == '' || $id == '') {
    print 'ok';
    die();
}

$el = new CIBlockElement;

$PROP = Array();
$PROP['ORDER_JSON'] = $order;

$resc = CIBlock::GetList(Array(), Array('CODE' => 'saved_orders'));

while($arrc = $resc->Fetch())
{
    $iblockid = $arrc["ID"];
}

$save_order = Array(
    "IBLOCK_SECTION_ID" => false,
    "IBLOCK_ID"      => $iblockid,
    "PROPERTY_VALUES"=> $PROP,
    "NAME"           => $id,
    "ACTIVE"         => "Y"
);
$new_id = $el->Add($save_order);

print 'ok';
