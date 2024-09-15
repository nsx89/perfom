<?
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog.php");
if (!CModule::IncludeModule('iblock') || !CModule::IncludeModule("catalog")) {
    exit;
}
$iblock_res = CIBlock::GetList(Array(), Array('CODE' => 'faq'));
while($iblock_res_arr = $iblock_res->Fetch()) $iblock_id_faq = $iblock_res_arr["ID"];
$res = CIBlockElement::GetList(Array('SORT'=>'ASC'), Array("IBLOCK_ID" => $iblock_id_faq), false, Array(), Array());
while($ob = $res->GetNextElement()){
    $arFields = $ob->GetFields();
    print_r($arFields['NAME']);
    echo '<br>';

    $nmbr = rand(0,500);


    /*$el = new CIBlockElement;
    $arLoadProductArray = Array(
        "SORT"=> $nmbr,
    );
    $PRODUCT_ID = $arFields['ID'];
    $ress = $el->Update($PRODUCT_ID, $arLoadProductArray);*/


}
