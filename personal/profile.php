<?// print_r($user)?>

<div class="profile-data-wrap">
    <div class="profile-data">

        <form class="profile-data-form">

            <div class="profile-data-part">
                <div class="prof-data-title">Личные данные</div>

              <input type="hidden" name="type" value="edit" oldval="edit">

                <input type="text" name="email" id="email" value="<?=$user['EMAIL']?>" readonly class="unchangeable" data-type="required" data-change="no" placeholder="email (логин)*" oldval="<?=$user['EMAIL']?>">

                <input type="text" name="name" id="name" value="<?=$user['NAME']?>" readonly data-type="required" placeholder="имя*" oldval="<?=$user['NAME']?>">

                <input type="text" name="lastname" id="lastname" value="<?=$user['LAST_NAME']?>" readonly data-type="required" placeholder="фамилия*" oldval="<?=$user['LAST_NAME']?>">

                <input type="text" name="phone" id="phone" value="<?=str_phone($user['PERSONAL_PHONE'])?>" readonly data-type="required" placeholder="номер телефона*" oldval="<?=str_phone($user['PERSONAL_PHONE'])?>">
            </div>

            <div class="profile-data-part">
                <div class="prof-data-title">Адрес доставки по умолчанию</div>

                <input type="text" name="city" id="city" readonly value="<?=$user['PERSONAL_CITY']?>" placeholder="город" oldval="<?=$user['PERSONAL_CITY']?>">

                <input type="text" name="street" id="street" readonly value="<?=$user['PERSONAL_ZIP']?>" placeholder="улица" oldval="<?=$user['PERSONAL_ZIP']?>">

                <input type="text" name="house" id="house" readonly value="<?=$user['PERSONAL_STREET']?>" placeholder="дом" oldval="<?=$user['PERSONAL_STREET']?>">

                <input type="text" name="apartment" id="apartment" readonly value="<?=$user['PERSONAL_MAILBOX']?>" placeholder="квартира" oldval="<?=$user['PERSONAL_MAILBOX']?>">
            </div>


            <div class="personal-data-form-success">Данные были успешно изменены</div>
            <div class="personal-data-form-error">Данные не изменялись</div>
            <div class="personal-data-form-btns">
                <button type="button" class="reset-btn" data-type="reset">Отмена</button>
                <button type="button" class="ok-btn" data-type="save">Сохранить</button>
            </div>

        </form>


    </div>
</div>
<div class="profile-change-pass">
    <div class="auth-popup" data-type="change-pass-popup">
        <form class="user-form profile-data-form" data-type="change-pass">
            <div class="profile-data-part">
                <div class="prof-data-title">Изменение пароля</div>
                <input type="hidden" name="type" value="change-pass">
                <input type="hidden" name="user_id" value="<?=$user['ID']?>">
                <input type="password" name="old_password" data-type="required" placeholder="старый пароль*">
                <input type="password" name="password" data-type="required" placeholder="новый пароль*">
                <input type="password" name="confirm_password" data-type="required" placeholder="подтверждение пароля*" class="last-input">
            </div>
            <span class="req-pass">Пароль должен содержать не&nbsp;менее 6&nbsp;символов</span>
            <span class="mandatory">* – пункты, обязательные для заполнения</span>

            <div class="personal-data-form-error" data-type="server-error"></div>
            <div class="personal-data-form-success">Пароль успешно изменен</div>

            <button type="button" class="ok-btn user-form-btn" data-type="ok-btn" data-act="change-pass" >Сохранить</button>
        </form>


    </div>
</div>


