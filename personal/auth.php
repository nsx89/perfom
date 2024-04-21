<section class="personal-wrap">
    <div class="content-wrapper">
        <div class="auth-wrapper">
            <div class="auth-img">
                <img src="/img/personal/auth.jpg" alt="авторизация">
            </div>
            <div class="user-form-wrapper authorization-cont">
                <div class="user-form-title">Авторизация</div>
                <form class="user-form auth-form">
                    <input type="hidden" name="type" value="enter">
                    <div class="input-block">
                        <input type="text" name="login" data-type="required" placeholder="Логин или email*" id="userLogin">
                    </div>
                    <div class="input-block">
                        <input type="password" name="password" data-type="required" placeholder="Пароль*" class="last-input">
                    </div>
                    <button type="button" class="ok-btn user-form-btn" data-type="ok-btn" data-act="reg-enter">Войти</button>
                    <a href="/personal/registration" class="link-btn user-form-btn">Регистрация</a>
                    <a href="/personal/forget_pass/" class="forget-pass">Забыли пароль?</a>
                    <span class="mandatory">* – пункты, обязательные для заполнения</span>
                    <div class="error-message" data-type="server-error"></div>
                </form>
            </div>
        </div>
    </div>
</section>