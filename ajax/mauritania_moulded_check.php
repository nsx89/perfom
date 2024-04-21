<?
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog.php");

if (!CModule::IncludeModule('iblock') || !CModule::IncludeModule("catalog")) {
    exit;
}
$http_host_temp = explode(":",$_SERVER['HTTP_HOST']);
$_SERVER['HTTP_HOST'] = '.'.$http_host_temp[0];
//print($_SERVER['HTTP_HOST']);

$basket = json_decode($_COOKIE['basket']);
$cart = getObjectItems();
$cart = $cart['items'];

$moulded_arr = array();
$elems_arr = array();
$elems_count = 0;
$moulded_count = 0;
$panel_count = 0;

//переменные ответа
$mess = '';
$err = 0;

if(count($cart) == 0) {
    $db_list = CIBlockElement::GetList(Array(),Array('IBLOCK_ID' => IB_CATALOGUE,'ID' => $_REQUEST['id'], 'ACTIVE' => 'Y'));
    while($ob = $db_list->GetNextElement()) {
        $item = array_merge($ob->GetFields(), $ob->GetProperties());
        $item['COUNT'] = $_REQUEST['qty'];
    }
    $cart[] = $item;
}

$in_cart = false;
foreach($cart as $item) {
    if($_REQUEST['id'] == $item['ID']) $in_cart = true;
    if($item['MAURITANIA_SPECIAL']['VALUE'] == 'Y') {
        $elems_arr[] = $item;
        $elems_count += $item['COUNT'];
        if($_REQUEST['id'] == $item['ID']) $elems_count += $_REQUEST['qty'];
    }
    if($item['MAURITANIA']['VALUE'] == 'Y') {
        if($item['NAME'] == 'карниз' || $item['NAME'] == 'молдинг' || $item['NAME'] == 'плинтус') {
            $moulded_count += $item['COUNT'];
            if($_REQUEST['id'] == $item['ID']) $moulded_count += $_REQUEST['qty'];
        }
        if($item['ARTICUL']['VALUE'] == '1.59.503') {
            $panel_count += $item['COUNT'];
            if($_REQUEST['id'] == $item['ID']) $panel_count += $_REQUEST['qty'];
        }
    }
}

if(!$in_cart) {
    $db_list = CIBlockElement::GetList(Array(),Array('IBLOCK_ID' => IB_CATALOGUE,'ID' => $_REQUEST['id'], 'ACTIVE' => 'Y'));
    while($ob = $db_list->GetNextElement()) {
        $item = array_merge($ob->GetFields(), $ob->GetProperties());
        if($item['MAURITANIA_SPECIAL']['VALUE'] == 'Y') $elems_count += $_REQUEST['qty'];
        //print_r($elems_count);
        if($item['MAURITANIA']['VALUE'] == 'Y') {
            if($item['NAME'] == 'карниз' || $item['NAME'] == 'молдинг' || $item['NAME'] == 'плинтус') {
                $moulded_count += $_REQUEST['qty'];
            }
            if($item['ARTICUL']['VALUE'] == '1.59.503') {
                $panel_count += $item['COUNT'];
                if($_REQUEST['id'] == $item['ID']) $panel_count += $_REQUEST['qty'];
            }
        }

    }
}

if($moulded_count*2 + floor($panel_count/4) < $elems_count) {
    if($moulded_count == 0 && $panel_count == 0) {
        $mess = '<p>Данный товар невозможно добавить в&nbsp;корзину, <br>т.к.&nbsp;в&nbsp;корзине нет соответствующх ему изделий <br>из&nbsp;коллекции <span>MAURITANIA</span>. </p><p>Для&nbsp;уточнения вопроса обратитесь к&nbsp;менеджеру.</p>';
    } else {
        $mess = '<p>Данный товар невозможно добавить в&nbsp;корзину, <br>т.к.&nbsp;в&nbsp;корзине недостаточно соответствующх ему изделий <br>из&nbsp;коллекции <span>MAURITANIA</span>. </p><p>Для&nbsp;уточнения вопроса обратитесь к&nbsp;менеджеру.</p>';
    }
    $err++;
}


print json_encode(array('err' => $err,'mess' => $mess));
