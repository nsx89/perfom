<?
ini_set('display_errors', 1);
error_reporting(E_ALL);
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog.php");
if (!CModule::IncludeModule('iblock')) exit;
$APPLICATION->SetTitle("Заказ подтвержден");
$APPLICATION->SetPageProperty("description", "Европласт - производство полиуретановых изделий, лидер на российском рынке");
if (!CModule::IncludeModule('iblock') || !CModule::IncludeModule("catalog")) {
    exit;
}
require($_SERVER["DOCUMENT_ROOT"] . "/include/header.php");
$nmbr = $_REQUEST['nmbr'];
$db_list = CIBlockElement::GetList(Array('SORT' => 'ASC'), Array("IBLOCK_CODE"=>"keep_order","NAME"=>$nmbr));
$ob = $db_list->GetNextElement();
$order = array_merge($ob->GetFields(), $ob->GetProperties());

$arFilter = Array('IBLOCK_ID' => 7, 'ACTIVE' => 'Y', 'ID' => $order['CHOOSEN_REG']['VALUE']);
$db_list = CIBlockElement::GetList(Array('SORT' => 'ASC'), $arFilter);
$loc = $db_list->GetNextElement();
if (!$loc) {
    $arFilter = Array('IBLOCK_ID' => 7, 'ACTIVE' => 'Y', 'CODE' => 'moskva');
    $db_list = CIBlockElement::GetList(Array('SORT' => 'ASC'), $arFilter);
    $loc = $db_list->GetNextElement();
}
$loc = array_merge($loc->GetFields(), $loc->GetProperties());

//print_r($order);
?>

<div class="content-wrapper">
    <?if($_REQUEST['nmbr']) { ?>
        <div class="cont-success cont-success-order-off">
            
            <div class="succ-header">Дорогой покупатель, <br>спасибо за ваш заказ!</div> 

            <?if($order['PAYMENT']['VALUE'] == 'online' && $order['PAYMENT_STATUS']['VALUE'] != 'оплачено') { ?>
                <div class="succ-desc">Вы можете оплатить ваш заказ по&nbsp;ссылке: </div>
                <a href="/cart/pay.php?id=<?=$order['UUID']['VALUE']?>" class="succ-cat succ-payment">Перейти к&nbsp;оплате</a>
                <div class="succ-desc">Данная ссылка отправлена вам письмом <br>на указанный e-mail вместе с&nbsp;информацией о&nbsp;заказе.</div>
            <? } else { ?>
                <div class="succ-desc">Вся необходимая информация по заказу<br>
                    отправлена на указанный Вами адрес электронной почты.</div>
                <div class="succ-desc">Ожидайте звонка нашей службы доставки.</div>
            <? } ?>
            <div class="succ-desc">Наш менеджер свяжется с вами<br> в ближайшее время!</div>
            <div class="succ-nmbr">Заказ № <?=$nmbr?></div>
            <? if($order['CHOOSEN_REG']['VALUE'] != 3109) { ?>
                <div class="succ-support">По всем вопросам вы можете обратиться<br>
                    в&nbsp;службу поддержки потребителей:
                </div>
                <div class="succ-support-phone"><?=$order['PHONE_DEALER']['VALUE']?></div>
            <? } ?>
            <?//if($order['PAY_URL']['VALUE'] == '' ||  $order['PAY_URL']['VALUE'] != '' && $order['PAYMENT_STATUS']['VALUE'] == 'оплачено') { ?>
            <?if($order['PAYMENT']['VALUE'] == 'online' && $order['PAYMENT_STATUS']['VALUE'] != 'оплачено') { ?>
               <a href="/catalogue/" class="succ-cat">в каталог</a>
            <? } ?>
        </div>
    <? } ?>

</div>


<?
if($_COOKIE['basket']) {
$number_tr = $order['NAME']." - ".$loc['NAME'];
//электронная коммерция
$db_props = CIBlockElement::GetProperty(35,49179, array("sort" => "asc"), Array("CODE"=>"COUNT_VAL"));
if($ar_props = $db_props->Fetch()) {
    $d_number = IntVal($ar_props["VALUE"]);
    $value = $d_number + 1;
    CIBlockElement::SetPropertyValueCode(49179,"COUNT_VAL", $value);
}
// Передача счетчику товара
$cart = getObjectItems();
$cart = $cart['items'];
$all_price = 0;
$prod_arr= array();
foreach ($cart as $citem) {
    $citem['price'] = round($citem['price']);
    $all_price += $citem['price'] * $citem['COUNT'];
    $categories = ___get_product_sections($citem, true);
    //google
    $innercode = $citem['INNERCODE']['VALUE'];
    if($citem['prodId'] != '') $innercode = $citem['SAMPLE_CODE']['VALUE'];
    $prodName = __get_product_name($citem);
    if($citem['prodId'] != '') $prodName .= ' образец';
    $prod_arr[] = array('id' => $innercode, 'name' => $prodName, 'category' => $categories[0], 'price' => $citem['price'], 'quantity' => $citem['COUNT']);
}

/*передача в google*/
$ga_arr = array('transaction_id'=>$number_tr, 'affiliation'=>'Evroplast', 'value'=>$all_price, 'currency'=>'RUB', 'items'=>$prod_arr);
/*передача в yandex*/
$ya_cont = array(
    'ecommerce'=>array(
        'purchase'=>array(
            'actionField'=>array('id'=>$number_tr),
            'products'=>$prod_arr
        )
    )
);
?>
<script>
$(document).ready(function() {
    window.dataLayer.push(<?=json_encode($ya_cont)?>);
    gtag('event', 'purchase', <?=json_encode($ga_arr)?>);
})
</script>
<?
$http_host_temp = explode(":",$_SERVER['HTTP_HOST']);
$_SERVER['HTTP_HOST'] = $http_host_temp[0];
setcookie("basket",null,-1,'/', $_SERVER['HTTP_HOST']);
setcookie("mount",null,-1,'/', $_SERVER['HTTP_HOST']);
setcookie("calc", null,-1,'/',$_SERVER['HTTP_HOST']);
} ?>
<script>
    $(document).ready(function() {
      $('[data-type="header-cart-qty"]').hide();
    })
</script>

<?
require($_SERVER["DOCUMENT_ROOT"] . "/include/footer.php");
if(defined("B_PROLOG_INCLUDED") && B_PROLOG_INCLUDED===true)
{
    require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog.php");
}

