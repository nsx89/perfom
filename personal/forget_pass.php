<?
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog.php");
if (!CModule::IncludeModule('iblock') || !CModule::IncludeModule("catalog")) {
    exit;
}
$APPLICATION->SetTitle("Восстановление пароля");
$APPLICATION->SetPageProperty("description", "Европласт - производство полиуретановых изделий, лидер на российском рынке");
require($_SERVER["DOCUMENT_ROOT"] . "/include/header.php");

global $USER;
?>
<section class="personal-wrap">
    <div class="content-wrapper">

  <? if($_REQUEST['change_password'] == 'yes') { ?>

      <div class="auth-wrapper">
          <div class="auth-img">
              <img src="/img/personal/auth.jpg" alt="авторизация">
          </div>
          <div class="user-form-wrapper forget-change-pass-wrapper">
              <div class="user-form-title">Смена пароля</div>
              <form class="user-form auth-form">
                  <input type="hidden" name="type" value="forget-change">
                  <div class="input-block">
                      <input type="text" name="email" data-type="required" placeholder="email*" value="<?=$_REQUEST['user_email']?>" readonly>
                  </div>
                  <div class="input-block">
                      <input type="text" name="checkword" data-type="required" placeholder="Контрольная строка*" value="<?=$_REQUEST['user_checkword']?>" readonly>
                  </div>
                  <div class="input-block">
                      <input type="password" name="password" data-type="required" placeholder="Новый пароль*" class="user-pass">
                  </div>
                  <div class="input-block">
                      <input type="password" name="confirm_password" data-type="required" placeholder="Подтверждение пароля*" class="last-input">
                  </div>
                  <span class="req-pass">Пароль должен содержать не&nbsp;менее&nbsp;6&nbsp;символов</span>
                  <button type="button" class="ok-btn user-form-btn" data-type="ok-btn" data-act="forget-change">Изменить пароль</button>
                  <a href="/personal/" class="link-btn user-form-btn">Авторизация</a>
                  <span class="mandatory">* – пункты, обязательные для заполнения</span>
                  <div class="error-message" data-type="server-error"></div>
              </form>
              <div class="success-message" data-type="succ-mess">
                  <div class="succ-content"></div>
                  <a href="/" class="link-btn user-form-btn">Вернуться на главную</a>
              </div>
          </div>
      </div>

  <? } else { ?>

      <div class="auth-wrapper">
          <div class="auth-img">
              <img src="/img/personal/auth.jpg" alt="авторизация">
          </div>
          <div class="user-form-wrapper forget-pass-wrapper">
              <div class="user-form-title">Запрос пароля</div>
              <div class="confirm-reg-succ-title">
                  <p>Если вы забыли пароль, введите свой&nbsp;email.</p>
                  <p>Контрольная строка для&nbsp;смены пароля, а&nbsp;также ваши регистрационные данные, будут высланы вам по&nbsp;email.</p>
              </div>
              <form class="user-form auth-form">
                  <input type="hidden" name="type" value="forget">
                  <div class="input-block">
                      <input type="text" name="email" data-type="required" placeholder="email*">
                  </div>
                  <button type="button" class="ok-btn user-form-btn" data-type="ok-btn" data-act="forget">Выслать</button>
                  <a href="/personal/" class="link-btn user-form-btn">Авторизация</a>
                  <span class="mandatory">* – пункты, обязательные для заполнения</span>
                  <div class="error-message" data-type="server-error"></div>
              </form>
              <div class="success-message" data-type="succ-mess">
                  <div class="succ-content"></div>
                  <a href="/" class="link-btn user-form-btn">Вернуться на главную</a>
              </div>
          </div>
      </div>

  <? } ?>

</div>
</section>

<script src="/personal/personal.js?<?=$random?>"></script>

<?require($_SERVER["DOCUMENT_ROOT"] . "/include/footer.php");
if(defined("B_PROLOG_INCLUDED") && B_PROLOG_INCLUDED===true)
{
    require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog.php");
}
?>
