<?
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog.php");
if (!CModule::IncludeModule('iblock') || !CModule::IncludeModule("catalog")) {
    exit;
}
$APPLICATION->SetTitle("Регистрация на сайте");
$APPLICATION->SetPageProperty("description", "Европласт - производство полиуретановых изделий, лидер на российском рынке");
require($_SERVER["DOCUMENT_ROOT"] . "/include/header.php");

global $USER;
?>
<section class="personal-wrap">
<div class="content-wrapper">
    <div class="auth-wrapper">
        <div class="auth-img reg-img">
            <img src="/img/personal/reg.jpg" alt="регистрация">
        </div>
        <div class="user-form-wrapper authorization-cont reg-cont">
<? if($_REQUEST['confirm_registration'] == 'yes') { ?>

    <?
    $user_id = $_REQUEST['confirm_user_id'];

    $rsUser = CUser::GetByID($user_id);
    $arUser = $rsUser->Fetch();
    if($arUser) {
        //print_r($arUser);
        $user = new CUser;
        $fields = Array(
            "ACTIVE" => 'Y',
        );
        if($user->Update($user_id,$fields)) { ?>


                    <div class="confirm-reg-succ">
                        <div class="confirm-reg-succ-title">
                            <p>Вы успешно подтвердили регистрацию на&nbsp;сайте.</p>
                            <p>Для входа в&nbsp;личный кабинет введите&nbsp;пароль:</p>
                        </div>
                        <form class="user-form auth-form">
                            <p class="reg-login"><span>Логин: </span><?=$arUser['LOGIN']?></p>
                            <input type="hidden" name="type" value="enter">
                            <input type="hidden" name="login" value="<?=$arUser['LOGIN']?>">
                            <input type="password" name="password" data-type="required" placeholder="Пароль*" class="last-input">

                            <div class="error-message" data-type="server-error"></div>
                            <button type="button" class="ok-btn user-form-btn" data-type="ok-btn" data-act="reg-enter" data-val="reg">Войти</button>
                            <a href="/personal/forget_pass/" class="forget-pass">Забыли пароль?</a>
                        </form>
                    </div>
        <? }
        $str_error = $user->LAST_ERROR;
        print $str_error;
    } else { ?>
            <div class="confirm-reg-succ err">
                <div class="confirm-reg-succ-title">
                    <p>По данной ссылке не&nbsp;найден пользователь на&nbsp;сайте.</p>
                    <p>Пожалуйста, пройдите повторную регистрацию либо&nbsp;обратитесь в&nbsp;нашу службу поддержки.</p>
                </div>
                <form class="user-form auth-form">
                    <a href="/personal/registration/" class="link-btn user-form-btn">Регистрация</a>
                    <a data-type="q-popup-open"  class="ok-btn user-form-btn">Задать вопрос</a>
                    <a href="/" class="return-btn user-form-btn">Вернуться на главную</a>
                </form>
            </div>
   <? }
    ?>

<? } else { ?>


            <div class="user-form-title">Регистрация</div>
            <form class="user-form auth-form reg-form">
                <div>
                    <input type="hidden" name="type" value="registration">
                    <input type="text" name="name" data-type="required" placeholder="Имя*">
                    <input type="text" name="last_name" data-type="required" placeholder="Фамилия*">
                    <input type="text" name="email" data-type="required" placeholder="email*">
                    <div class="pers-data" data-type="user-pers-data">Я согласен на обработку персональных данных</div>
                </div>
                <div>
                    <input type="text" name="phone" data-type="required" placeholder="Номер телефона*">
                    <input type="password" name="password" data-type="required" placeholder="Пароль*" class="user-pass">
                    <input type="password" name="confirm_password" data-type="required" placeholder="Подтверждение пароля*" class="last-input">
                    <span class="req-pass">Пароль должен содержать не&nbsp;менее 6&nbsp;символов</span>
                </div>
                <div>
                    <button type="button" class="link-btn user-form-btn" data-type="ok-btn" data-act="reg">Регистрация</button>
                </div>
                <div>
                    <a href="/personal" class="ok-btn user-form-btn">Войти</a>
                </div>
                <div><span class="mandatory">* – пункты, обязательные для заполнения</span></div>
                <div class="error-message" data-type="server-error"></div>
            </form>



<? } ?>
        </div>
    </div>
</div>
</section>

<script src="/personal/personal.js?<?=$random?>"></script>

<?require($_SERVER["DOCUMENT_ROOT"] . "/include/footer.php");
if(defined("B_PROLOG_INCLUDED") && B_PROLOG_INCLUDED===true)
{
    require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog.php");
}
?>