<?php
/**
 * Created by PhpStorm.
 * User: nadida
 * Date: 25.02.2020
 * Time: 14:11
 */
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog.php");
if (!CModule::IncludeModule('iblock') || !CModule::IncludeModule("catalog")) {
    exit;
}

$data = $_REQUEST;

/**
 * сохранить
 */
if($data['type'] == 'save') {

    $id = '';
    $arr = '';

    if($data['id'])     $id = $data['id'];
    if($data['arr'])    $arr = $data['arr'];

    if ($id == '' || $arr == '') {
        print '{"code":0,"errors":""}';
        die();
    }

    //записываем в БД
    $el = new CIBlockElement;

    $PROP = array();
    $PROP['USER_ID'] = $id;

    $resc = CIBlock::GetList(Array(), Array('CODE' => 'corners'));
    while($arrc = $resc->Fetch())
    {
        $blockid = $arrc["ID"];
    }

    //номер проекта
    $resc = CIBlock::GetList(Array(), Array('CODE' => 'mounting_counters'));
    while($arrc = $resc->Fetch()) $counter_block_id = $arrc["ID"];

    $resc = CIBlockElement::GetList(Array(), Array('IBLOCK_ID'=>$counter_block_id, 'NAME' => 'Углы'));
    while($arrc = $resc->Fetch()) $counter_id = $arrc["ID"];

    $db_props = CIBlockElement::GetProperty($counter_block_id, $counter_id, array("sort" => "asc"), Array("CODE"=>"NUMBER"));
    if($ar_props = $db_props->Fetch()) {
        $d_number = IntVal($ar_props["VALUE"]);
        $number = $d_number + 1;
        CIBlockElement::SetPropertyValueCode($counter_id,"NUMBER", $number);
    }

    $arLoadProductArray = Array(
        "IBLOCK_SECTION_ID" => false,
        "IBLOCK_ID"      => $blockid,
        "PROPERTY_VALUES"=> $PROP,
        "NAME"           => $number,
        "DETAIL_TEXT"    => json_encode($arr),
        "ACTIVE"         => "Y"
    );

    if($PRODUCT_ID = $el->Add($arLoadProductArray)) {
        print '{"code":0,"errors":[],"number":"'.$number.'","date":"'.date('d.m.Y').'","time":"'.date('H:i').'"}';
    } else {
        print '{"code":1,"errors":"'.$el->LAST_ERROR.'"}';
    }

    //print '{"code":0,"errors":[],"number":"125","date":"'.date('d.m.Y').'","time":"'.date('H:i').'"}';


}