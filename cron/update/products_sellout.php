<?
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog.php");
if (!CModule::IncludeModule('iblock') || !CModule::IncludeModule("catalog")) {
    exit;
}

/*
$file = file('sellout/sellout.txt');
foreach ($file AS $item) {
    $arr = explode('=', $item);
    $ARTICUL = $arr[0];
    $NAME = $arr[1];
    $OLD_PRICE = $arr[2];
    $PRICE = $arr[3];
    $BYN = $arr[4];
    $KZH = $arr[5];
    $UAH = $arr[6];
    $MDL = $arr[7];
    $AMD = $arr[8];
    $EUR1 = $arr[9];
    $EUR2 = $arr[10];
    $GEL = $arr[11];
    $UZS = $arr[12];
    $KGS = $arr[13];

    echo $ARTICUL.'='.$NAME.'='.$OLD_PRICE.'='.$PRICE;
    echo '='.$BYN;
    echo '='.$KZH;
    echo '='.$UAH;
    echo '='.$MDL;
    echo '='.$AMD;
    echo '='.$EUR1;
    echo '='.$EUR2;
    echo '='.$GEL;
    echo '='.$UZS;
    echo '='.$KGS;
    echo '<br>';

    $arFilter = Array('IBLOCK_ID' => IB_CATALOGUE, 'TAGS' => 'OFF', 'PROPERTY_ARTICUL' => $ARTICUL, 'NAME' => $NAME);
    $res = CIBlockElement::GetList(Array("PROPERTY_ARTICUL"=>"ASC"), $arFilter);
    while ($row = $res->GetNextElement()) {

        $item = $row->getFields();
        $prop = $row->getProperties();

        CIBlockElement::SetPropertyValueCode($item['ID'], "OLD_PRICE", $OLD_PRICE);

        $db_res = CPrice::GetList(
            array(),
            array("PRODUCT_ID" => $item['ID'])
        );
        $ar_res = $db_res->Fetch();
        $PRICE_ID = $ar_res['ID'];
        if (!empty($PRICE_ID) && !empty($PRICE)) {
            CPrice::Update($PRICE_ID, Array("PRICE" => $PRICE));
        }

        CIBlockElement::SetPropertyValueCode($item['ID'], "BYN", $BYN);   
        CIBlockElement::SetPropertyValueCode($item['ID'], "KZH", $KZH);   
        CIBlockElement::SetPropertyValueCode($item['ID'], "UAH", $UAH);   
        CIBlockElement::SetPropertyValueCode($item['ID'], "MDL", $MDL);   
        CIBlockElement::SetPropertyValueCode($item['ID'], "AMD", $AMD);   
        CIBlockElement::SetPropertyValueCode($item['ID'], "EUR1", $EUR1);   
        CIBlockElement::SetPropertyValueCode($item['ID'], "EUR2", $EUR2);   
        CIBlockElement::SetPropertyValueCode($item['ID'], "GEL", $GEL);   
        CIBlockElement::SetPropertyValueCode($item['ID'], "UZS", $UZS);   
        CIBlockElement::SetPropertyValueCode($item['ID'], "KGS", $KGS);   

        CIBlockElement::SetPropertyValueCode($item['ID'], "SELLOUT", 'Y');   

        $el = new CIBlockElement;
        $el->Update($item['ID'], Array(
            "TAGS" => '',   
        ));

        //exit;
    }
}
 

?>