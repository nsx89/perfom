<?
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");

CModule::IncludeModule('iblock');

$server = 'https://'.$_SERVER['SERVER_NAME'];

$content = '<yml_catalog date="'.date('Y-m-d H:i').'">
    <shop>
        <name>Перфом</name>
        <company>ООО "Декор"</company>
        <url>https://'.$_SERVER['SERVER_NAME'].'/</url>
        <currencies>
            <currency id="RUR" rate="1"/>
        </currencies>';

        //Категории
        //<categories></categories>

        /*if (!empty($_GET['all'])) {
            $CATALOG_FILTER = Array('IBLOCK_ID' => IB_CATALOGUE, "!TAGS" => "OFF");
        }
        else {
            $CATALOG_FILTER = Array('IBLOCK_ID' => IB_CATALOGUE, 'INCLUDE_SUBSECTIONS' => 'Y', "ACTIVE" => "Y", "ACTIVE_DATE" => "Y", "TAGS" => "Y");
        }

        $res = CIBlockElement::GetList(Array('PROPERTY_ARTICUL'=>'ASC'), $CATALOG_FILTER);
        $i = 0;
        while ($row = $res->GetNextElement()) {
            $item = $row->getFields();
            $prop = $row->getProperties();
            $ARTICUL = $prop['ARTICUL']['VALUE'];

            $PRICE = CPrice::GetBasePrice($item['ID'])['PRICE'];

            $SECTIONS = ___get_product_sections_all($item, true);
            $SECTIONS = array_reverse($SECTIONS);
            $IC_GROUP0 = $SECTIONS[0];
            $IC_GROUP1 = $SECTIONS[1];
            $IC_GROUP2 = $SECTIONS[2];

        }*/

    $content .= '</shop>
</yml_catalog>';


$dom_xml= new DomDocument();
$dom_xml->loadXML($content);
$dom_xml->save("yandex.xml");


function clear($value){
    $value = strip_tags($value, '<br>');
    $value = trim($value);
    $value = stripslashes($value);
    return $value;
}

//require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_after.php");
?>