<?
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog.php");
if (!CModule::IncludeModule('iblock')) {
    exit;
}

$event = $_REQUEST['event'];
$id = $_REQUEST['id'];

//статус заказа
if($event == 'status') {
    $val = $_REQUEST['val'];
    $reason = $_REQUEST['reason'];
    $id = $_REQUEST['id'];
    $moderator = $_REQUEST['moderator'];

    $PROP = array();
    $PROP['STATUS'] = $val;
    $PROP['CANCEL_REASON'] = $reason;
    $PROP['MODERATOR'] = $moderator;

    CIBlockElement::SetPropertyValuesEx($id, false, $PROP);

    print 'ok';

}

//удалить (деактивировать) заказ
if($event == 'remove') {
    $el = new CIBlockElement;
    $arFields = Array(
        "MODIFIED_BY"    => $USER->GetID(), // элемент изменен текущим пользователем
        "ACTIVE"         => "N",            // активен
    );
    $res = $el->Update($id, $arFields);
    print 'ok';
    /*CIBlockElement::SetPropertyValueCode($id,"ACTIVE", "N");*/
}
?>