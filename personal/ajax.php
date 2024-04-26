<?
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog.php");
if (!CModule::IncludeModule('iblock') || !CModule::IncludeModule("catalog")) {
    exit;
}
require($_SERVER["DOCUMENT_ROOT"] . "/include/top-current-location.php");
global $loc;

global $USER;

$type = '';
$name = '';
$last_name = '';
$email = '';
$phone = '';
$login = '';
$rememb = '';
$checkword = '';
$city = '';
$street = '';
$house = '';
$apartment = '';
$password = '';
$old_password = '';
$confirm_password = '';
$user_id = '';
$product_id = '';

$type = $_REQUEST['type'];

//REGISTRATION
if($type == 'registration') {

    $name = $_REQUEST['name'];
    $last_name = $_REQUEST['last_name'];
    $email = $_REQUEST['email'];
    $phone = $_REQUEST['phone'];
    $password = $_REQUEST['password'];
    $confirm_password = $_REQUEST['confirm_password'];

    //check spam
    if(
        $type == ''
        || $name == ''
        || $last_name == ''
        || $email == ''
        || $phone == ''
        || $password == ''
        || $confirm_password == ''
    ) {
        $answ['type'] = 'OK';
    }

    //user registration

    $arResult = $USER->Register(
        $email,
        $name,
        $last_name,
        $password,
        $password,
        $email
    );

    //save user phone
    if($arResult['TYPE'] == 'OK') {

        $user = new CUser;
        $fields = Array(
            "PERSONAL_PHONE" => $phone,
        );
        $user->Update($arResult['ID'], $fields);

        $answ['type'] = 'OK';

    } else {
        $answ['type'] = 'ERROR';
        $answ['mess'] = $arResult['MESSAGE'];
    }

    echo json_encode($answ);

}

//CONFIRM REGISTRATION
if($type == 'enter') {
    $login = $_REQUEST['login'];
    $password = $_REQUEST['password'];
    $rememb = $_REQUEST['rememb'];

    $remember = $rememb == 'yes' ? 'Y' : 'N';

    //check spam
    if(
        $type == ''
        || $login == ''
        || $password == ''
    ) {
        $answ['type'] = 'OK';
    }

    //user authorization
    if (!is_object($USER)) $USER = new CUser;
    $arAuthResult = $USER->Login($login, $password, $remember);
    $APPLICATION->arAuthResult = $arAuthResult;

    //print_r($arAuthResult);

    if($arAuthResult === true) {

        $answ['type'] = 'OK';

    } else {
        $answ['type'] = 'ERROR';
        $answ['mess'] = $arAuthResult['MESSAGE'];
    }

    echo json_encode($answ);

}

//FORGET PASSWORD
if($type == 'forget') {

    $email = $_REQUEST['email'];

    //check spam
    if(
        $type == ''
        || $email == ''
    ) {
        $answ['type'] = 'OK';
    }

    $arResult = $USER->SendPassword($email, $email);
    //print_r($arResult);
    if($arResult["TYPE"] == "OK") {
        $answ['type'] = 'OK';
        $answ['mess'] = $arResult['MESSAGE'];
    } else {
        $answ['type'] = 'ERROR';
        $answ['mess'] = $arResult['MESSAGE'];
    }
    echo json_encode($answ);
}

//CHANGE FORGET PASSWORD
if($type == 'forget-change') {

    $email = $_REQUEST['email'];
    $checkword = $_REQUEST['checkword'];
    $password = $_REQUEST['password'];
    $confirm_password = $_REQUEST['confirm_password'];

    //check spam
    if(
        $type == ''
        || $email == ''
        || $checkword == ''
        || $password == ''
        || $confirm_password == ''
    ) {
        $answ['type'] = 'OK';
    }

    $arResult = $USER->ChangePassword($email, $checkword, $password, $confirm_password);
    //print_r($arResult);
    if($arResult["TYPE"] == "OK") {
        $answ['type'] = 'OK';
        $answ['mess'] = $arResult['MESSAGE'];
    } else {
        $answ['type'] = 'ERROR';
        $answ['mess'] = $arResult['MESSAGE'];
    }
    echo json_encode($answ);
}

//edit personal data
if($type == 'edit') {

    $name = $_REQUEST['name'];
    $last_name = $_REQUEST['lastname'];
    $email = $_REQUEST['email'];
    $phone = $_REQUEST['phone'];
    $city = $_REQUEST['city'];
    $street = $_REQUEST['street'];
    $house = $_REQUEST['house'];
    $apartment = $_REQUEST['apartment'];

    //check spam
    if(
        $type == ''
        || $name == ''
        || $last_name == ''
        || $email == ''
        || $phone == ''
    ) {
        $answ['type'] = 'OK';
    }

    $rsUser = CUser::GetByLogin($email);
    $arUser = $rsUser->Fetch();
    $user_id = $arUser['ID'];

    $user = new CUser;
    $fields = Array(
        "NAME"              => $name,
        "LAST_NAME"         => $last_name,
        "PERSONAL_PHONE"    => $phone,
        "PERSONAL_CITY"     => $city,
        "PERSONAL_ZIP"      => $street,
        "PERSONAL_STREET"   => $house,
        "PERSONAL_MAILBOX"  => $apartment,

    );
    if($user->Update($user_id, $fields)) {
        $answ['type'] = 'OK';
    } else {
        $answ['type'] = 'ERROR';
        $answ['mess'] = $user->LAST_ERROR;
    }

    echo json_encode($answ);

}

//change password

if($type == 'change-pass') {

    $old_password = $_REQUEST['old_password'];
    $password = $_REQUEST['password'];
    $confirm_password = $_REQUEST['confirm_password'];
    $user_id = $_REQUEST['user_id'];

    //check spam
    if(
        $type == ''
        || $old_password == ''
        || $password == ''
        || $confirm_password == ''
        || $user_id == ''
    ) {
        $answ['type'] = 'OK';
    }

    $rsUser = CUser::GetByID($user_id);
    $arUser = $rsUser->Fetch();
    $arAuthResult = $USER->Login($arUser['LOGIN'], $old_password, 'N', 'Y');
    $APPLICATION->arAuthResult = $arAuthResult;

    if($arAuthResult !== true) {
        $answ['type'] = 'ERROR';
        $answ['mess'] = "Введен неверный текущий пароль!";
    } else {

        $user = new CUser;
        $fields = Array(
            "PASSWORD"          => $password,
            "CONFIRM_PASSWORD"  => $confirm_password,

        );
        if($user->Update($user_id, $fields)) {
            $answ['type'] = 'OK';
            $fields = array('EMAIL'=>$arUser['EMAIL'],'NAME'=>$arUser['NAME'],'LAST_NAME'=>$arUser['LAST_NAME'],'STATUS'=>"активен",'LOGIN'=>$arUser['LOGIN']);
            CEvent::SendImmediate("USER_INFO", s1, $fields, "N");
        } else {
            $answ['type'] = 'ERROR';
            $answ['mess'] = $user->LAST_ERROR;
        }

    }
    echo json_encode($answ);

}

if($type == 'favorite') {

    $product_id = $_REQUEST['product_id'];
    $user_id = $_REQUEST['user_id'];

    if(
        $type == ''
        || $product_id == ''
        || $user_id == ''
    ) {
        $answ['type'] = 'OK';
    }

    $rsUser = CUser::GetByID($user_id);
    $arUser = $rsUser->Fetch();

    $wishlist = json_decode($arUser['PERSONAL_NOTES']);

    if(in_array($product_id,$wishlist)) {
        $key = array_search($product_id,$wishlist);
        unset($wishlist[$key]);
        if(!empty($wishlist)) {
            $wishlist = explode("|", implode("|", $wishlist));
        }
    } else {
        $wishlist[] = $product_id;
    }

    $wishlist = json_encode($wishlist);

    $user = new CUser;
    $fields = Array(
        "PERSONAL_NOTES"  => $wishlist,

    );
    $user->Update($user_id, $fields);

    echo $wishlist;
}

if($type == 'get_favorite') {
    $user_id = $USER->GetID();
    $user_group_arr = [];
    $res = CUser::GetUserGroupList($user_id);
    while ($arGroup = $res->Fetch()) {
        $user_group_arr[] = $arGroup['GROUP_ID'];
    }

    if ($USER->IsAuthorized() && in_array(5,$user_group_arr)) {
        $favorite = $user['PERSONAL_NOTES'];
    } elseif($_COOKIE['favorite']) {
        $favorite = $_COOKIE['favorite'];
    } else {
        $favorite = json_encode(Array());
    }
    print $favorite;
}

if($type == 'favorite_clear') {

    $user_id = $_REQUEST['user_id'];

    if(
        $type == ''
        || $user_id == ''
    ) {
        $answ['type'] = 'OK';
    }

    $rsUser = CUser::GetByID($user_id);
    $arUser = $rsUser->Fetch();

    $user = new CUser;
    $fields = Array(
        "PERSONAL_NOTES"  => '',

    );
    $user->Update($user_id, $fields);

    $answ['type'] = 'OK';

    echo json_encode($answ);
}

if($type == 'next_wish') {

    $user_id = $_REQUEST['user_id'];
    $page = $_REQUEST['page'];

    $user_group_arr = [];
    $res = CUser::GetUserGroupList($user_id);
    while ($arGroup = $res->Fetch()) {
        $user_group_arr[] = $arGroup['GROUP_ID'];
    }

    if($user_id != '' && in_array(5,$user_group_arr)) {
        $rsUser = CUser::GetByID($user_id);
        $arUser = $rsUser->Fetch();
        $wishlist = json_decode($arUser['PERSONAL_NOTES']);
    } else {
        $wishlist = json_decode($_COOKIE['favorite']);
    }

    $data_onpage = 10;

    $prev = ($page-1)*$data_onpage;
    $next = $page*$data_onpage;

    if($_REQUEST['all']) {
        $prev = 0;
        $next = count($wishlist);
    }

    ob_start();

    foreach($wishlist as $k => $item) {
        if($k+1 > $prev && $k+1 <= $next){
            //$ar_res = CIBlockElement::GetList(Array(),Array("IBLOCK_ID"=>IB_CATALOGUE,"ID"=>$item,"ACTIVE"=>"Y"),false, Array(), Array());
            $ar_res = CIBlockElement::GetList(Array(),Array("IBLOCK_ID"=>IB_CATALOGUE,"ID"=>$item),false, Array(), Array());
            while ( $ob = $ar_res->GetNextElement() ) {
                $product = array_merge($ob->GetFields(), $ob->GetProperties());
                echo get_product_preview($product);
            }

        }
    }
    $html = ob_get_clean();

    print json_encode($html);

}

if($type == 'remove_saved') {
    $id = $_REQUEST['id'];
    CIBlockElement::Delete($id);
    print 'ok';
}

if($type == 'send_pay_link') {

    $id = $_REQUEST['id'];
    $mail = $_REQUEST['mail'];

    $resc = CIBlock::GetList(Array(), Array('CODE' => 'keep_order'));
    while($arrc = $resc->Fetch())
    {
        $blockid = $arrc["ID"];
    }

    $res = CIBlockElement::GetList(Array(), Array('IBLOCK_ID'=>$blockid, 'ID'=>$id), false, Array(), Array());
    while($ob = $res->GetNextElement())
    {
        $item = array_merge($ob->GetFields(), $ob->GetProperties());
    }

    $payment_link = '/cart/pay.php?id='.$item['UUID']['VALUE'];

    $from = "";
    $from = '<div style="padding-left:25px;margin-top:20px;line-height:20px;width:695px;margin-bottom:20px;">';
    $from .= '<div style="font-size:20px;">Здравствуйте!</div>';

    $date = explode(" ",$item['DATE']['VALUE']);
    $date = $date[0];
    $from .= '<p style="margin-bottom:10px;">Вы можете оплатить онлайн заказ № <b>'.$item['NAME'].'</b> от '.$date.' на сайте <a href="http://perfom-decor.ru" target="_blank" style="color:#000;">perfom-decor.ru</a> по ссылке: </p>';
    $from .= '<a href="https://'.$_SERVER['HTTP_HOST'].$payment_link.'" style="background:##849795; color:#fff;text-decoration:none;display:block;width:185px;height:32px;line-height:32px;text-align:center;" target="_blank">Оплатить заказ</a>';
    $from .= '<p style="margin-top:20px;">При возникновении вопросов Вы можете обратиться по контактам:</p>';
    $from .= '<p><b>Телефон: </b>'.$item['PHONE_DEALER']['VALUE'].'<br>';
    $from .= '<b>Email: </b>'.$item['MAIL_DEALER']['VALUE'].'</p>';
    $from .= '<p style="margin-top:20px;margin-bottom: 5px;">Все детали заказа Вы можете посмотреть по ссылке:</p>';
    $from .= '<a href="https://'.$_SERVER['HTTP_HOST'].'/personal/show_order?number='.$item['NAME'].'" style="color:##849795;text-decoration:underline;" target="_blank">Посмотреть заказ</a><br>';
    $from .= '</div><br>';

    $title = 'Ссылка на онлайн-оплату заказа №'.$item['NAME'].' на сайте perfom-decor.ru';

    $email_arr  = array(
        $mail
    );

    foreach($email_arr as $email) {
        $fields = array('EMAIL'=>$email, 'EMAIL_D'=>$item['MAIL_DEALER']['VALUE'], 'TITLE'=>$title, 'CLIENT'=>$from);
        CEvent::SendImmediate("ORDER_NEW", s1, $fields, "N");
    }

    print 'ok';
}