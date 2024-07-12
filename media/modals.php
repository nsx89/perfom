<div class="overlay" data-type="complain-overlay"></div>
<div class="complain-popup" data-type="complain-popup">
    <div class="complain-form">
        <i class="icon-close" data-type="complain-popup-close"></i>
        <div class="complain-form-title">Оставить жалобу</div>
        <form class="aqs-main-form complain-main-form" data-type="complain-main-form" id="complainMainForm">
            <div class="complain-form-column">
                <input type="text" name="name" placeholder="имя*">
                <div class="input-wrap input-wrap-no-margin" data-type="tel-wrap">
                    <input type="tel" name="phone" id="complain-tel" data-tel="yes" data-mask="+7 (XXX) XXX-XX-XX" placeholder="телефон">
                </div>
                <input type="text" name="email" placeholder="email*">
                <textarea name="text" placeholder="ваша жалоба*"></textarea>
                <input id="complain_policy" type="checkbox" name="complain_policy" class="q-check" required="required">
                <label for="complain_policy" class="complain_policy_label">
                    Я согласен <span>на обработку персональных данных</span>
                </label>
                <div class="e-complain-form-loader"><img src="/img/preloader.gif" alt="wait..."></div>
                <div class="complain-alert"></div>
                <button type="button" class="e-complain-form-button js-complain-submit">Отправить</button>
                <p class="e-aqs-field-req e-complain-field-req">* – пункты, обязательные для заполнения</p>
            </div>
        </form>
    </div>
</div>