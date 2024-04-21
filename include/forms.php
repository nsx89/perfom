<!--noindex-->
<div class="overlay" data-type="overlay"></div>
<div class="overlay" data-type="q-overlay"></div>
<div class="question-popup" data-type="q-popup">
    <div class="question-form">
        <i class="icon-close" data-type="q-popup-close"></i>
        <div class="q-form-title">Задать вопрос</div>
        <form class="aqs-main-form" data-type="aqs-main-form" id="aqsMainForm">
            <div class="left-form-column">
                <input type="hidden" name="aqs-city" value="<?=$my_city?>">
                <input type="hidden" name="aqs-page" value="" id="e-aqs-input-page">
                <input type="text" name="aqs-name" id="aqs-name" placeholder="имя*">
                <div class="input-wrap input-wrap-no-margin" data-type="tel-wrap">
                    <input type="tel" name="aqs-tel" id="aqs-tel" data-tel="yes" data-mask="<?=get_phone_mask($loc['country']['VALUE'])?>" placeholder="телефон">
                </div>
                <div class="input-wrap input-wrap-format">
                    <input type="checkbox" id="aqs_format" name="online_format" class="q-check" data-type="online-format">
                    <label for="aqs_format">У меня другой формат номера телефона</label>
                </div>
                <input type="text" name="aqs-email" id="aqs-email" placeholder="email*">
                <input type="text" name="aqs-loc" id="aqs-loc" placeholder="город*">
                <input type="checkbox" id="aqs_policy" name="aqs_policy" class="q-check" required="required">
                <label for="aqs_policy" class="aqs_policy_label">Я согласен <span>на обработку персональных данных</span></label>
                <button type="button" class="e-aqs-form-button">Отправить</button>
                <div class="e-aqs-form-loader"><img src="/img/preloader.gif" alt="wait..."></div>
            </div>
            <div class="right-form-column">
                <div class="e-aqs-file-wrapper">
                    <label>
                        <i class="icon-add"></i>
                        <span class="e-aqs-file-lbl">прикрепить файл</span>
                        <span class="e-aqs-file-name" data-type="add-file"></span>
                        <input type="file" name="aqs-file" accept="image/jpeg,image/png,.zip,.rar,.pdf">
                    </label>
                </div>
                <p class="e-aqs-file-exmpl">
                    Разрешены к отправке файлы: изображения JPEG, PNG, PDF,
                    архивы RAR, ZIP, размер файла не должен превышать 10 МБ
                </p>
                <textarea name="aqs-qst" id="aqs-qst" placeholder="ваш вопрос*"></textarea>
                <div class="e-aqs-select-wrap">
                    <select name="aqs-subj" data-placeholder="тема вопроса*" data-type="q-form-select">
                        <option value="" disabled selected style="display:none;">тема вопроса*</option>
                        <option value="2">Монтаж изделий</option>
                        <option value="3">Свойства изделий</option>
                        <option value="4">Претензии и&nbsp;вопросы по&nbsp;заказам и&nbsp;сервису</option>
                        <option value="1">Ассортимент и&nbsp;уточнение по&nbsp;размерам</option>
                        <option value="5">Гарантийные обязательства</option>
                        <option value="6">Работа магазинов</option>
                        <option value="7">Другое</option>
                    </select>
                </div>
                <p class="e-aqs-field-req">* – пункты, обязательные для заполнения</p>
            </div>
        </form>

    </div>
</div>
<div class="question-result-popup question-popup" data-type="q-popup-res">
    <div class="question-form">
        <i class="icon-close" data-type="q-popup-res-close"></i>
        <div data-type="aqs-rqst">
            <div class="q-form-title">вопрос <br>отправлен</div>
            <div class="q-form-res">
                Ваш вопрос под номером <span>№476421</span> <br>на&nbsp;модерации, ожидайте ответа <br>на&nbsp;почте.
            </div>
        </div>
    </div>
</div>
<div class="maur-item-popup" data-type="maur-pop">
    <i class="new-icomoon icon-close" data-type="maur-pop-close"></i>
    <div class="maur-pop-wrap">
        <div class="maur-pop-title">Внимание!</div>
        <div class="maur-pop-content" data-type="maur-pop-content">
            <p>Данный товар невозможно добавить в&nbsp;корзину, <br>т.к.&nbsp;в&nbsp;корзине нет соответствующх ему <br>изделий из&nbsp;коллекции <span>MAURITANIA</span>. </p><p>Для&nbsp;уточнения вопроса обратитесь к&nbsp;менеджеру.</p>
        </div>
    </div>
</div>
<div class="maur-item-popup mess-popup" data-type="popup-mess"></div>
<!--/noindex-->
