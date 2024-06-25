<?
$id = $_GET['ID'];
$type = $_GET['type'];

?>
<link rel="stylesheet" href="/js/autocomplete/autocomplete.min.css">
<link rel="stylesheet" href="/moderation/dealers/dealers.css?<?=$random?>">
<script src="https://api-maps.yandex.ru/2.1/?apikey=0e24f952-da4e-4266-9ab4-66b2b263e914&lang=ru_RU" type="text/javascript"></script>
<div class="md-content<?if(in_array($user_stat_dealer,array('userdealer'))) echo ' userdealer'?>">
    <div class="md-content-dealer active" data-val="cont" data-type="dealer">
<?
$back = '/moderation/#etc4';
?>
<style>
    .main-sec-wrap-tabs {
        display: none;
    }
    .mod-tabs-cont:before {
        display: none!important;
    }
    .main-sec-wrap>.pers-acc-top {
        display: none;
    }
    .mod-tabs-cont {
        width: 100%;
        padding: 0;
    }
    .pers-acc-top {
        height: 100%;
        font-family: Nekst, sans-serif;
        font-size: 16px;
    }
    @media screen and (max-width: 1000px) {
        .mod-tabs-cont {
            width: calc(100% + 40px);
        }
</style>
<a href="<?=$back?>" class="pacc-back" data-type="pacc-back" data-val="first-back">
    <i class="icon-arrow-left"></i>
</a>


<div class="md-new-form">

<div class="md-point-preloader">
    <img src="/img/preloader.gif" alt="wait...">
</div>

<form class="md-edit" data-type="mod" style="display: none;">
<div class="md-edit-wrap">
<div class="md-edit-contact" data-type="main-form-data">
<div class="md-edit-contact-top">
    <div class="md-edit-contact-top-left">
        <div class="md-edit-not-found"><p></p></div>
        <div class="md-edit-contact-top-left-cont" data-val="md-edit-cont">
            <div class="md-edit-act">
                <p></p>
            </div>
            <div class="md-edit-title">
                <a href="<?=$back?>" class="pacc-back md-edit-back-mob" data-type="pacc-back">
                    <i class="icon-arrow-left"></i>
                </a>
                1. Контактные данные
            </div>
            <input type="hidden" id=md-id" name="md-id" data-type="form-data">
            <div class="md-edit-main-cont">
                <div class="md-edit-cont-top-btns">
                    <div class="md-edit-cont-stat">
                        <div class="md-edit-input-wrap md-edit-input-wrap-publish md-edit-input-wrap-main">
                            <input type="checkbox" id="md-main" name="md-main" data-type="form-data">
                            <label for="md-main">Главный дилер</label>
                        </div>
                        <div class="md-edit-input-wrap md-edit-input-wrap-publish md-edit-input-wrap-order">
                            <input type="checkbox" id="md-order" name="md-order" data-type="form-data">
                            <label for="md-order">Контакт для заказа</label>
                        </div>
                    </div>
                    <div class="md-edit-input-wrap md-edit-input-wrap-publish">
                        <input type="checkbox" id="ed-publish" name="md-publish" data-type="form-data">
                        <label for="ed-publish">Опубликовать</label>
                    </div>
                </div>
                <div class="md-edit-cont-add-fields">
                    <div class="md-edit-cont-add-fields-wrap">
                        <div class="md-edit-input-wrap">
                            <label for="mdTelOrdr">Телефон для заказа</label>
                            <input type="tel" name="md-tel-order" id="mdTelOrdr" data-type="form-data">
                        </div>
                        <div class="md-edit-input-wrap">
                            <label for="mdMailOrder">E-mail для заказа</label>
                            <input type="email" name="md-mail-order" id="mdMailOrder" data-type="form-data">
                        </div>
                        <div class="md-edit-input-wrap">
                            <label for="mdMailQs">E-mail для Справочной</label>
                            <div class="md-edit-add-phone md-edit-add-email-qs"><i class="icon-plus" data-type="add-phone"></i></div>
                            <input type="email" name="md-mail-qs" id="mdMailQs" data-type="form-data">
                        </div>
                    </div>
                    <div class="md-edit-input-wrap">
                        <p class="md-edit-subtit">Сделать дилером региона</p>
                        <div class="md-add-reg-inp-wrap">
                        </div>
                        <div class="md-add-reg" data-type="md-add-reg">Добавить регион</div>
                    </div>
                    <div class="md-edit-input-wrap md-edit-input-wrap-publish md-edit-input-wrap-order-reg">
                        <input type="checkbox" id="md-order-reg" name="md-only-reg" data-type="form-data">
                        <label for="md-order-reg">является контактом для заказа только для указанных регионов</label>
                    </div>

                    <div class="md-edit-comment">
                        1. Если Телефон и E-mail не заполнены, будут использованы основные телефон и e-mail.<br>
                        2. Если регионы добавлены, то в таких регионах будет показан <span>только</span> данный контакт.
                    </div>
                </div>

            </div>
            <div class="md-edit-input-wrap md-edit-input-wrap-main">
                <input type="text" name="md-org" placeholder="Организация*" required="required" data-type="form-data">
            </div>
            <div class="md-edit-input-wrap md-edit-input-wrap-main">
                <input type="text" name="md-point" placeholder="Точка продажи*" required="required" data-type="form-data">
            </div>
        </div>

    </div>
    <div class="md-edit-contact-top-right">
        <?include($_SERVER["DOCUMENT_ROOT"] . "/moderation/manager_panel.php");?>
    </div>

</div>
    <div class="md-edit-contact-left" data-val="md-edit-cont">
        <div class="md-edit-left-col-wrap">
            <div class="md-edit-left-col">
                <div class="md-edit-choose-reg md-edit-input-wrap" data-type="point-reg">
                    <input type="hidden" value="" name="md-reg" required="required" data-type="form-data">
                    <span class="choosed">выбрать город*</span>
                    <i class="icon-angle-down"></i>
                </div>
                <div class="md-edit-input-wrap md-edit-input-wrap-addr">
                    <label for="mdAddr">Адрес*</label>
                    <textarea name="md-addr" required="required" id="mdAddr" data-type="form-data" class="autogrow"></textarea>
                </div>
                <div class="md-edit-input-wrap">
                    <label for="mdTel">Телефон/факс*</label>
                    <div class="md-edit-add-phone"><i class="new-icomoon icon-plus" data-type="add-phone"></i></div>
                    <input type="tel" name="md-tel" required="required" id="mdTel" data-type="form-data">
                </div>
                <div class="md-edit-input-wrap">
                    <label for="mdMail">E-mail*</label>
                    <input type="email" name="md-mail" required="required" id="mdMail" data-type="form-data">
                </div>
                <div class="md-edit-input-wrap md-edit-input-wrap-url">
                    <label for="mdUrl">URL</label>
                    <input type="text" name="md-url" id="mdUrl" data-type="form-data">
                </div>
                <div class="md-edit-input-wrap md-edit-input-wrap-type">
                    <p class="md-edit-subtit">Тип точки*</p>
                    <input type="radio" name="md-pointtype" id="mdType1" value="retail" data-type="form-data">
                    <label for="mdType1">Собственная розница</label>
                    <input type="radio" name="md-pointtype" id="mdType2" value="subdealer" data-type="form-data">
                    <label for="mdType2">Субдилерская сеть</label>
                </div>
                <div class="md-edit-input-wrap md-edit-input-wrap-contractor">
                    <label for="mdContractor">Главный дилер*</label>
                    <input type="text" name="md-contractor" id="mdContractor" data-type="form-data">
                </div>
            </div>
            <div class="md-edit-right-col">
                <div class="md-edit-time-top-wrap">
                    <p class="md-edit-subtit">Время работы</p>
                    <div class="md-edit-input-wrap md-edit-input-wrap-without">
                        <input type="checkbox" name="md-without" id="mdWithout" data-type="form-data">
                        <label for="mdWithout">без выходных</label>
                    </div>
                </div>
                <div class="md-edit-input-wrap md-edit-input-wrap-time">
                    <input type="text" name="md-work" required="required" id="mdWork" data-type="form-data">
                    <label for="mdWork">будни*</label>
                </div>
                <div class="md-edit-input-wrap md-edit-input-wrap-time">
                    <input type="text" name="md-sat" id="mdSat" data-type="form-data">
                    <label for="mdSat">суббота</label>
                </div>
                <div class="md-edit-input-wrap md-edit-input-wrap-time">
                    <input type="text" name="md-sun" id="mdSun" data-type="form-data">
                    <label for="mdSun">воскресенье</label>
                </div>
                <div class="md-edit-input-wrap">
                    <label for="mdWeekEnd">дополнительные выходные</label>
                    <input type="text" name="md-weekend" id="mdWeekEnd" data-type="form-data">
                </div>
                <div class="md-edit-input-wrap md-edit-input-wrap-photo" data-type="drop-area">
                    <p class="md-edit-subtit">Добавить фотографии точки</p>
                    <div class="md-edit-photo-wrap" data-type="drop-area-wrap"></div>
                    <div class="md-edit-photo-err" data-type="drop-area-err"></div>
                    <label for="mdPhoto">Добавить фотографии</label>
                    <input type="file" name="md-photo" id="mdPhoto" multiple>
                    <input type="text" name="md-folder" data-type="form-data" class="md-edit-folder">
                    <input type="hidden" name="md-operations" value="0">
                </div>

            </div>
        </div>
    </div>
    <div class="md-edit-contact-right"  data-val="md-edit-cont">
        <div class="md-edit-map-wrap">
            <div id="YMapsPointAdd" class="md-edit-map"></div>
            <div class="md-edit-coords">
                <div class="md-edit-coords-inp">
                    <div class="md-edit-input-wrap">
                        <input type="text" name="md-lat" id="mdAddLat" required="required" data-type="form-data">
                        <label for="mdAddLat">широта*</label>
                    </div>
                    <div class="md-edit-input-wrap">
                        <input type="text" name="md-lon" id="mdAddLon" required="required" data-type="form-data">
                        <label for="mdAddLon">долгота*</label>
                    </div>
                </div>
                Для выбора координат точки установите метку на&nbsp;карте щелчком мыши. Координаты точки появятся <span>автоматически</span>. Для&nbsp;более удобного выбора необходимого адреса вы можете ввести его в&nbsp;поле поиска и&nbsp;нажать кнопку <span>«Найти»</span>
            </div>
        </div>
        <div class="md-edit-add-fields-wrap">
            <div class="md-edit-add-fields-desc">
                Вы можете внести дополнительную информацию о&nbsp;расположении торговой точки, для&nbsp;более конкретного ориентира для&nbsp;покупателей.
            </div>
            <div class="md-edit-add-fields">
                    <div class="md-edit-input-wrap">
                        <label for="md-mall">ТЦ, рынок и прочее</label>
                        <input type="text" name="md-mall" id="md-mall" data-type="form-data">
                    </div>
                    <div class="md-edit-input-wrap">
                        <label for="md-mark">Ориентир для поиска</label>
                        <input type="text" name="md-mark" id="md-mark" data-type="form-data">
                    </div>
                    <div class="md-edit-input-wrap">
                        <label for="md-add">Дополнительная информация</label>
                        <textarea name="md-add" id="md-add" data-type="form-data" class="autogrow"></textarea>
                    </div>
                    <div class="md-edit-input-wrap md-edit-input-wrap-serv-comm">
                        <label for="md-serv-comm">Служебный комментарий:</label>
                        <textarea name="md-serv-comm" id="md-serv-comm" data-type="form-data" class="autogrow"></textarea>
                    </div>
            </div>
        </div>
    </div>
</div>
                <div class="md-edit-others" data-type="main-form-data" data-val="md-edit-cont">
                    <div class="md-edit-others-left">
                        <div class="md-edit-range">
                            <div class="md-edit-title">2. Ассортимент на точке</div>
                            <div class="md-edit-input-wrap md-edit-input-wrap-assort">
                                <input type="checkbox" id="assort1" name="md-assort" value="interior" data-type="form-data">
                                <label for="assort1">интерьер ППУ</label>
                                <input type="checkbox" id="assort2" name="md-assort" value="composit" data-type="form-data">
                                <label for="assort2">композит</label>
                                <input type="checkbox" id="assort3" name="md-assort" value="facade" data-type="form-data">
                                <label for="assort3">фасад</label>
                            </div>
                            <div class="md-edit-input-wrap md-edit-input-wrap-equip">
                                <label for="md-equip">Торговое оборудование:</label>
                                <textarea name="md-equip" id="md-equip" data-type="form-data" class="autogrow"></textarea>
                            </div>
                        </div>
                        <div class="md-edit-serv">
                            <div class="md-edit-title">3. Услуги</div>
                            <div class="md-edit-input-wrap">
                                <input type="checkbox" id="serv1" name="md-serv" value="del" data-type="form-data">
                                <label for="serv1">доставка</label>
                                <input type="checkbox" id="serv2" name="md-serv" value="mount" data-type="form-data">
                                <label for="serv2">монтаж</label>
                                <input type="checkbox" id="serv3" name="md-serv" value="des" data-type="form-data">
                                <label for="serv3">дизайнер/выезд</label>
                                <input type="checkbox" id="serv4" name="md-serv" value="paint" data-type="form-data">
                                <label for="serv4">покраска</label>
                                <input type="checkbox" id="serv5" name="md-serv" value="store" data-type="form-data">
                                <label for="serv5">складская программа</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="md-edit-staff" data-val="md-edit-cont">
                    <div class="md-edit-title">4. Персонал</div>
                    <div class="md-edit-staff-item-add">Добавить персонал<i class="icon-plus"></i></div>
                    <div class="md-edit-staff-item">
                        <div class="md-edit-staff-main">
                            <div class="md-edit-input-wrap md-edit-input-wrap-fio">
                                <label for="mdFio">ФИО*</label>
                                <input type="text" name="md-fio" required="required" id="mdFio" data-type="form-data">
                            </div>
                            <div class="md-edit-input-wrap">
                                <label for="mdPos">Должность*</label>
                                <input type="taxt" name="md-pos" required="required" id="mdPos" data-type="form-data">
                            </div>
                            <div class="md-edit-input-wrap md-edit-input-wrap-staff-mail">
                                <label for="mdStaffEmail">E-mail*</label>
                                <input type="email" name="md-mail-staff" required="required" id="mdStaffEmail" data-type="form-data">
                            </div>
                            <div class="md-edit-input-wrap md-edit-input-wrap-staff-tel">
                                <label for="mdStaffTel">Телефон/факс*</label>
                                <input type="tel" name="md-tel-staff" required="required" id="mdStaffTel" data-type="form-data">
                                <div class="md-edit-add-phone"><i class="new-icomoon icon-plus" data-type="add-phone"></i></div>
                            </div>
                            <div class="md-edit-staff-item-remove" title="Удалить персонал" data-type="remove-staff"><i class="icon-delete"></i></div>
                        </div>
                        <?/*<div class="md-edit-input-wrap md-edit-input-wrap-type md-edit-input-wrap-access">
                            <p class="md-edit-subtit">Уровень доступа*:</p>
                            <input type="radio" name="md-access" id="md-access-1" value="read" checked="" data-type="form-data">
                            <label for="md-access-1">Чтение</label>
                            <input type="radio" name="md-access" id="md-access-2" value="write" data-type="form-data">
                            <label for="md-access-2">Чтение/запись</label>
                            <input type="radio" name="md-access" id="md-access-3" value="mod" data-type="form-data">
                            <label for="md-access-3">Модерация</label>
                        </div>
                        */?>
                    </div>

                </div>
            </div>
            <?/* Для модератора - принять/отклонить*/?>
            <? if($type == 'mod' && in_array($user_stat_dealer,array('admin','moddealer'))) { ?>
                <div class="md-edit-btns-wrap" data-val="md-edit-cont">
                    <div class="md-edit-mess md-mod-mess">
                        <p></p>
                    </div>
                    <div class="md-edit-err"></div>
                    <div class="md-edit-btns">
                        <button type="button" class="md-edit-no" data-type="md-mod-no">Отклонить</button>
                        <button type="button" class="md-edit-yes" data-type="md-mod-yes">Принять</button>
                    </div>
                </div>

            <?/* Для пользователя - работа с промежуточным сохранением*/?>
            <? } elseif($type == 'saved' && in_array($user_stat_dealer,array('specdealer'))) { ?>
                <div class="md-edit-btns-wrap" data-val="md-edit-cont">
                    <div class="md-edit-mess md-edit-mess-saved">
                        <p>Сохраненные изменения видны только вам.</p>
                        <p>Перед отправкой на&nbsp;модерацию нажмите кнопку "Сохранить", если у&nbsp;вас есть <span>несохраненные изменения</span>, которые вы хотите отправить на&nbsp;модерацию.</p>
                        <p>Изменения будут отображены на&nbsp;сайте после&nbsp;модерации.</p>
                    </div>
                    <div class="md-edit-err<?if($_REQUEST['type'] == 'saved') echo ' md-edit-err-saved'?>"></div>
                    <div class="md-edit-btns">
                        <button type="button"  class="md-edit-no" data-type="md-temp-remove">Удалить</button>
                        <button type="button"  class="md-edit-yes" data-type="md-temp-save">Сохранить</button>
                        <button type="button"  class="md-edit-mod" data-type="md-temp-mod">На модерацию</button>
                    </div>
                </div>

            <? } elseif($type == 'edit' && in_array($user_stat_dealer,array('admin','moddealer'))) { ?>

                <div class="md-edit-btns-wrap" data-val="md-edit-cont">
                    <div class="md-edit-err"></div>
                    <div class="md-edit-btns">
                        <button type="button" class="md-edit-no" onclick="history.back(); return false;">Отменить</button>
                        <button type="button"  class="md-edit-yes" data-type="md-edit-yes" data-val="edit-save">Сохранить</button>
                        <button type="button"  class="md-edit-remove" data-type="md-edit-rem" data-val="edit-remove">Удалить</button>
                        <button type="button"  class="md-edit-copy" data-type="md-edit-copy" data-val="edit-copy">Копировать</button>
                    </div>
                </div>
            <?  } elseif($type == 'edit' && in_array($user_stat_dealer,array('specdealer'))) {?>
                <div class="md-edit-btns-wrap" data-val="md-edit-cont">
                    <div class="md-edit-mess">
                        <p>Для <span>промежуточного сохранения</span> изменений, нажмите кнопку "Сохранить". <br>Изменения будут видны только вам, данные на сайте останутся прежними.<br>
                            Bсе промежуточно сохраненные точки появятся на вкладке <span>"Сохраненное"</span>.</p>
                        <p>Изменения будут отображены на сайте после модерации.</p>
                    </div>
                    <div class="md-edit-err"></div>
                    <div class="md-edit-btns">
                        <button type="button"  class="md-edit-no" data-type="md-rem-mod">Удалить</button>
                        <button type="button"  class="md-edit-yes" data-type="md-temp-save">Сохранить</button>
                        <button type="button"  class="md-edit-mod" data-type="md-edit-mod">На модерацию</button>
                        <button type="button"  class="md-edit-copy" data-type="md-edit-copy" data-val="edit-copy">Копировать</button>
                    </div>
                </div>
            <? } ?>
        </form>
        <?/*<div class="md-edit-not-found">
            <p>Дилер не найден.</p>
            <div class="md-edit-btns">
                <a href="<?=$back?>" class="md-edit-yes" data-type="pacc-back">Вернуться</a>
            </div>
        </div>*/?>
    </div>
</div>


    <div class="clear-all-window" data-type="md-popup">
        <i class="new-icomoon icon-close" data-type="close-md-popup"></i>
        <div class="md-popup-cont"></div>
        <div class="md-popup-preloader">
            <img src="/img/preloader.gif" alt="wait...">
        </div>
    </div>

    <div class="copy-window" data-type="copy-window">
        <i class="new-icomoon icon-close" data-type="close-copy-window"></i>
        <div class="copy-window-cont">
            <div class="copy-window-title">Укажите пункты, <br>которые будут скопированы:</div>
                <form class="copy-window-points">
                    <div class="copy-window-points-block">
                        <div class="copy-input-wrap">
                            <input type="checkbox" id="copyOrg" value="org" checked>
                            <label for="copyOrg">организация</label>
                        </div>
                        <div class="copy-input-wrap">
                            <input type="checkbox" id="copyPoint" value="point" checked>
                            <label for="copyPoint">точка продажи</label>
                        </div>
                        <div class="copy-input-wrap">
                            <input type="checkbox" id="copyReg" value="reg" checked>
                            <label for="copyReg">город</label>
                        </div>
                        <div class="copy-input-wrap">
                            <input type="checkbox" id="copyAddr" value="addr" checked>
                            <label for="copyAddr">адрес</label>
                        </div>
                    </div>
                    <div class="copy-window-points-block">
                        <div class="copy-input-wrap">
                            <input type="checkbox" id="copyMail" value="mail" checked>
                            <label for="copyMail">e-mail</label>
                        </div>
                        <div class="copy-input-wrap">
                            <input type="checkbox" id="copyUrl" value="url" checked>
                            <label for="copyUrl">url</label>
                        </div>
                        <div class="copy-input-wrap">
                            <input type="checkbox" id="copyTime" value="time" checked>
                            <label for="copyTime">время работы</label>
                        </div>
                        <div class="copy-input-wrap">
                            <input type="checkbox" id="copyStaff" value="staff" checked>
                            <label for="copyStaff">персонал</label>
                        </div>
                    </div>
                    <div class="copy-window-buttons">
                        <button type="button" data-type="copy-window-yes">Копировать</button>
                    </div>
                </form>
        </div>
    </div>
</div>


<script src="/js/autocomplete/autocomplete.js"></script>
<script src="/js/uploader-1.0.2/jquery.dm-uploader.min.js"></script>
<script src="/moderation/dealers/dealers.js?<?=$random?>"></script>