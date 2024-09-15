<?
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog.php");
if (!CModule::IncludeModule('iblock')) exit;
$APPLICATION->SetTitle("Загрузки");
$APPLICATION->SetPageProperty("description", "Европласт - производство полиуретановых изделий, лидер на российском рынке");
require($_SERVER["DOCUMENT_ROOT"] . "/include/header.php");
?>
<div class="dwnld-tab-panel">
    <div class="content-wrapper">
        <div class="dwnld-tabs" data-type="main-dwnld">
            <a href="#cat" data-type="main-tab">Каталоги</a>
            <? /*<a href="#price" data-type="main-tab">Прайс-листы</a>*/ ?>
            <a href="#3d" data-type="main-tab">3d модели</a>
            <a href="#2d" data-type="main-tab">2d модели</a>
            <a href="#manuals" data-type="main-tab">Инструкции</a>
            <a href="#cert" data-type="main-tab">Сертификаты</a>
        </div>
    </div>
</div>
<div class="dwnld-cont-wrap">
    <div class="content-wrapper">
        <div class="dwnld-cont dwnld-cont-cat" data-type="main-tab-cont" id="cat">

        <h1 class="hidden">Файлы для скачивания</h1>

         <? require($_SERVER["DOCUMENT_ROOT"] . "/download/catalogs.php");?>
			
        </div>
        <div class="dwnld-cont prices" data-type="main-tab-cont" id="price">
            <ul class="dwnld-pricelist-tab-nav">
                <li class="active" data-val="pricelist-int" data-type="pricelist-tab">Интерьерная лепнина</li>
                <!--<li data-val="pricelist-fac" data-type="pricelist-tab">Фасадная лепнина</li>-->
            </ul>
            <div class="dwnld-pricelist-tab-wrap">
                <div class="dwnld-pricelist-preloader" data-type="dwnld-preloader"><img src="/img/preloader.gif" alt="..."></div>
                <div class="dwnld-pricelist-mess">
                    <img src="/img/preloader.gif" alt="...">
                    <p><span>Внимание!</span> Формирование прайса <br>может занять некоторое время</p>
                </div>
                <section class="dwnld-pricelist-tab-int active" id="pricelist-int" data-type="pricelist-tab-item"></section>
                <section class="dwnld-pricelist-tab-int" id="pricelist-fac" data-type="pricelist-tab-item"></section>
            </div>
        </div>
        <div class="dwnld-cont three-d" data-type="main-tab-cont" id="3d">
            <? /*
            <div class="dwnld-models-tabs">
                <i class="icomoon icon-close" data-type="close-mod-filters"></i>
                <div class="dwnld-models-tab active" data-type="3d-tab" data-val="3d-int">Интерьерная лепнина</div>
                <div class="dwnld-models-tab" data-type="3d-tab" data-val="3d-fac">Фасадная лепнина</div>
            </div>
            */ ?>
            <div class="dwnld-models-rules">
                <div class="dwnld-models-rules-title">Порядок скачивания 3d моделей</div>
                <div class="dwnld-models-rules-cont">
                    <div class="dwnld-mod-rule">
                        <div class="dwnld-rule-nmbr">1</div>
                        <div class="dwnld-rule-txt">выберите интересующую вас категорию</div>
                    </div>
                    <div class="dwnld-mod-rule">
                        <div class="dwnld-rule-nmbr">2</div>
                        <div class="dwnld-rule-txt">в таблице укажите необходимый формат</div>
                    </div>
                    <div class="dwnld-mod-rule">
                        <div class="dwnld-rule-nmbr">3</div>
                        <div class="dwnld-rule-txt">нажмите кнопку «скачать модели»</div>
                    </div>
                    <div class="dwnld-mod-rule">
                        <div class="dwnld-rule-nmbr">4</div>
                        <div class="dwnld-rule-txt">заполните Запрос и&nbsp;нажмите кнопку «отправить запрос»</div>
                    </div>
                    <div class="dwnld-mod-rule">
                        <div class="dwnld-rule-nmbr">5</div>
                        <div class="dwnld-rule-txt">выбранные модели будут доступны через наш файлообменный сервер</div>
                    </div>
                </div>
            </div>
            <div class="dwnl-models-cont">
                <div class="dwnld-models-left-column">
                    <div class="dwnld-models-filt-wrap active" id="3d-int" data-type="3d-tab-item">
                        <? /*<div class="dwnld-models-filt-title" data-type="dwnld-filt">элементы интерьера <i class="icomoon icon-angle-down-2"></i></div>*/ ?>
                        <div class="dwnld-models-filt-cont dwnld-models-filt-cont1">
                            <?=build_drop_categories(false);?>
                        </div>
                    </div>
                    <? /*
                    <div class="dwnld-models-filt-wrap" id="3d-fac" data-type="3d-tab-item">
                        <div class="dwnld-models-filt-title" data-type="dwnld-filt">элементы фасада <i class="icomoon icon-angle-down"></i></div>
                        <div class="dwnld-models-filt-cont dwnld-models-filt-cont1">
                            <?=build_drop_categories('fasadnyj-dekor');?>
                        </div>
                    </div>
                    */ ?>
                </div>
                <div class="dwnld-models-right-column">
                    <div class="dwnld-models-btns">
                        <div class="dwnld-models-mob-filt" data-type="open-filt">
                            <i class="icon-filters"></i> фильтры
                        </div>
                        <div class="dwnld-search">
                            <input type="text" id="dwnld-search" placeholder="поиск по артикулу">
                            <label for="dwnld-search"><i class="icomoon icon-search-2"></i></label>
                        </div>
                        <button class="dwnld-models-btn" data-type="dwnld-3d"><i class="icon-download"></i>скачать модели</button>
                        <div class="dwnld-3d-err" data-type="dform-err">необходимо выбрать 3d&nbsp;модели для&nbsp;скачивания</div>
                    </div>
                    <div class="dwnld-3d-table dwnld-3d-table1">
                        <table>
                            <thead>
                            <tr>
                                <th>Все форматы</th>
                                <th>Артикул</th>
                                <th>max <span data-type="choose-all" data-val="d3max"></span></th>
                                <th>3ds <span data-type="choose-all" data-val="d33ds"></span></th>
                                <th>gsm <span data-type="choose-all" data-val="d3gsm"></span></th>
                                <th>dwg <span data-type="choose-all" data-val="d3dwg"></span></th>
                                <th>obj <span data-type="choose-all" data-val="d3obj"></span></th>
                            </tr>
                            </thead>
                            <tbody data-type="ajax-wrap"></tbody>
                        </table>


                    </div>
                </div>
            </div>
        </div>
        <div class="dwnld-cont two-d" data-type="main-tab-cont" id="2d">

            <? /*
            <div class="dwnld-cat-item">
                <div class="dwnld-cat-item-img">
                    <img class="img-load" src="/img/1x1.png" data-src="/download/images/2d-pdf.png" alt="2d pdf">
                </div>
                <div class="dwnld-cat-item-bottom">
                    <div class="dwnld-cat-item-title">
                        2d&nbsp;модели в&nbsp;формате&nbsp;.pdf <br>
                        для&nbsp;ваших&nbsp;проектов
                    </div>
                    <a class="cat-item-dwnld" href="/download/2d_PDF.zip" title="Скачать 2d модели">
                        <i class="icon-download"></i> скачать pdf
                    </a>
                </div>
            </div>

            <div class="dwnld-cat-item">
                <div class="dwnld-cat-item-img">
                    <img class="img-load" src="/img/1x1.png" data-src="/download/images/2d-dwg.png" alt="2d dwg">
                </div>
                <div class="dwnld-cat-item-bottom">
                    <div class="dwnld-cat-item-title">
                        2d&nbsp;модели в&nbsp;формате&nbsp;.dwg <br>
                        для&nbsp;ваших&nbsp;проектов
                    </div>
                    <a class="cat-item-dwnld cat-item-dwnld-dwg" href="/download/2d_DWG.zip" title="Скачать 2d модели">
                        <i class="icon-download"></i> скачать pdf</a>
                </div>
            </div>
            */ ?>

            
                <div class="dwnld-models-rules">
                    <div class="dwnld-models-rules-title">Порядок скачивания 2d моделей</div>
                    <div class="dwnld-models-rules-cont">
                        <div class="dwnld-mod-rule">
                            <div class="dwnld-rule-nmbr">1</div>
                            <div class="dwnld-rule-txt">выберите интересующую вас категорию</div>
                        </div>
                        <div class="dwnld-mod-rule">
                            <div class="dwnld-rule-nmbr">2</div>
                            <div class="dwnld-rule-txt">в таблице укажите необходимый формат</div>
                        </div>
                        <div class="dwnld-mod-rule">
                            <div class="dwnld-rule-nmbr">3</div>
                            <div class="dwnld-rule-txt">нажмите кнопку «скачать модели»</div>
                        </div>
                        <div class="dwnld-mod-rule">
                            <div class="dwnld-rule-nmbr">4</div>
                            <div class="dwnld-rule-txt">заполните Запрос и&nbsp;нажмите кнопку «отправить запрос»</div>
                        </div>
                        <div class="dwnld-mod-rule">
                            <div class="dwnld-rule-nmbr">5</div>
                            <div class="dwnld-rule-txt">выбранные модели будут доступны через наш файлообменный сервер</div>
                        </div>
                    </div>
                </div>

                <div class="dwnl-models-cont">
                    <div class="dwnld-models-left-column">
                        <div class="dwnld-models-filt-wrap active" id="2d-int" data-type="2d-tab-item">
                            <div class="dwnld-models-filt-cont dwnld-models-filt-cont2">
                                <?=build_drop_categories(false);?>
                            </div>
                        </div>
                    </div>
                    <div class="dwnld-models-right-column">
                        <div class="dwnld-models-btns">
                            <? /*
                            <div class="dwnld-models-mob-filt" data-type="open-filt">
                                <i class="icon-filters"></i> фильтры
                            </div>
                            <div class="dwnld-search">
                                <input type="text" id="dwnld-search" placeholder="поиск по артикулу">
                                <label for="dwnld-search"><i class="icomoon icon-search-2"></i></label>
                            </div>
                            */ ?>
                            <button class="dwnld-models-btn" data-type="dwnld-2d"><i class="icon-download"></i>скачать модели</button>
                            <div class="dwnld-3d-err" data-type="dform-err2">необходимо выбрать 2d&nbsp;модели для&nbsp;скачивания</div>
                        </div>
                        <div class="dwnld-3d-table dwnld-3d-table2">
                            <table>
                                <thead>
                                <tr>
                                    <th>Все форматы</th>
                                    <th>Артикул</th>
                                    <th>pdf <span data-type="choose-all2" data-val="d2pdf"></span></th>
                                    <th>dwg <span data-type="choose-all2" data-val="d2dwg"></span></th>
                                </tr>
                                </thead>
                                <tbody data-type="ajax-wrap2"></tbody>
                            </table>
                        </div>
                    </div>
                </div>

        </div>
        <div class="dwnld-cont manual" data-type="main-tab-cont" id="manuals">

            <? /*
            <div class="dwnld-instr-item">
                <a href="/download/manual_baljustrady.pdf" target="_blank" title="Скачать инструкцию по монтажу"></a>
                <span class="dwnld-instr-item-title">Монтаж балюстрады <br>декоративной</span>
                <div class="dwnld-instr-item-img">
                    <img class="img-load" src="/img/1x1.png" data-src="/download/images/instr-1.png" alt="Монтаж балюстрады декоративной">
                </div>
                <div class="dwnld-instr-item-btn" data-type="dwnld-instr">
                    <i class="icon-download" data-type="save-pdf"></i>
                    Скачать pdf
                </div>
            </div>
            */ ?>

            <div class="dwnld-instr-item">
                <a href="/download/files/INSTALL_skirting_apr2024_web.pdf" target="_blank" title="Скачать инструкцию по монтажу"></a>
                <span class="dwnld-instr-item-title">Монтаж плинтуса <br>напольного</span>
                <div class="dwnld-instr-item-img end">
                    <img class="img-load" src="/img/1x1.png" data-src="/download/images/instr-2.png" alt="Монтаж плинтуса напольного">
                </div>
                <div class="dwnld-instr-item-btn" data-type="dwnld-instr">
                    <i class="icon-download" data-type="save-pdf"></i>
                    Скачать pdf
                </div>
            </div>

            <div class="dwnld-instr-item">
                <a href="/download/files/INSTALL_light-cornice_apr2024_web.pdf" target="_blank" title="Скачать инструкцию по монтажу"></a>
                <span class="dwnld-instr-item-title">Монтаж карниза <br>для скрытого освещения</span>
                <div class="dwnld-instr-item-img end">
                    <img class="img-load" src="/img/1x1.png" data-src="/download/images/instr-3.png" alt="Монтаж карниза для скрытого освещения">
                </div>
                <div class="dwnld-instr-item-btn" data-type="dwnld-instr">
                    <i class="icon-download" data-type="save-pdf"></i>
                    Скачать pdf
                </div>
            </div>

            <div class="dwnld-instr-item">
                <a href="/download/files/INSTALL_cornice_apr2024_web.pdf" target="_blank" title="Скачать инструкцию по монтажу"></a>
                <span class="dwnld-instr-item-title">Монтаж карниза <br>потолочного</span>
                <div class="dwnld-instr-item-img end">
                    <img class="img-load" src="/img/1x1.png" data-src="/download/images/instr-4_new.png" alt="Монтаж карниза потолочного">
                </div>
                <div class="dwnld-instr-item-btn" data-type="dwnld-instr">
                    <i class="icon-download" data-type="save-pdf"></i>
                    Скачать pdf
                </div>
            </div>

            <? /*
            <div class="dwnld-instr-item">
                <a href="/download/manual_karnizi.pdf" target="_blank" title="Скачать инструкцию по монтажу"></a>
                <span class="dwnld-instr-item-title">Монтаж карниза <br>под кровлю</span>
                <div class="dwnld-instr-item-img end">
                    <img class="img-load" src="/img/1x1.png" data-src="/download/images/instr-5.png" alt="Монтаж карниза под кровлю">
                </div>
                <div class="dwnld-instr-item-btn" data-type="dwnld-instr">
                    <i class="icon-download" data-type="save-pdf"></i>
                    Скачать pdf
                </div>
            </div>
            */ ?>

            <div class="dwnld-instr-item">
                <a href="/download/files/INSTALL_moulding_aprt2024_web.pdf" target="_blank" title="Скачать инструкцию по монтажу"></a>
                <span class="dwnld-instr-item-title">Монтаж молдинга <br>настенного</span>
                <div class="dwnld-instr-item-img end">
                    <img class="img-load" src="/img/1x1.png" data-src="/download/images/instr-8_new.png" alt="Монтаж молдинга настенного">
                </div>
                <div class="dwnld-instr-item-btn">
                    <i class="icon-download" data-type="save-pdf"></i>
                    Скачать pdf
                </div>
            </div>

            <div class="dwnld-instr-item">
                <a href="/download/files/INSTALL_rozetka_apr24_web.pdf" target="_blank" title="Скачать инструкцию по монтажу"></a>
                <span class="dwnld-instr-item-title">Монтаж розетки <br>потолочной</span>
                <div class="dwnld-instr-item-img">
                    <img class="img-load" src="/img/1x1.png" data-src="/download/images/instr-7.png" alt="Монтаж розетки потолочной">
                </div>
                <div class="dwnld-instr-item-btn" data-type="dwnld-instr">
                    <i class="icon-download" data-type="save-pdf"></i>
                    Скачать pdf
                </div>
            </div>


            <div class="dwnld-instr-item">
                <a href="/download/files/INSTALL_cornice_NAILS_apr2024_web.pdf" target="_blank" title="Скачать инструкцию по монтажу"></a>
                <span class="dwnld-instr-item-title">Монтаж лепнины <br>с применением <br>финишных гвоздей</span>
                <div class="dwnld-instr-item-img end">
                    <img class="img-load" src="/img/1x1.png" data-src="/download/images/instr-6_new.png" alt="Монтаж лепнины с применением финишных гвоздей">
                </div>
                <div class="dwnld-instr-item-btn">
                    <i class="icon-download" data-type="save-pdf"></i>
                    Скачать pdf
                </div>
            </div>

        </div>
        <div class="dwnld-cont cert" data-type="main-tab-cont" id="cert">
            <? include_once($_SERVER["DOCUMENT_ROOT"] . "/company/certificates.php");?>
        </div>
    </div>
</div>


<div class="dform" data-type="dform">
    <i class="icomoon icon-close" data-type="dform-close"></i>
    <div class="dform-title">Запрос на получение 3D моделей</div>
    <div class="dform-lead">Автоматическая система* отправит на&nbsp;адрес электронной почты ссылку <br>для&nbsp;скачивания пакета с&nbsp;выбранными вами&nbsp;моделями</div>
    <div class="dform-main">
        <p>Для получения ссылки для&nbsp;скачивания на&nbsp;Ваш е&#8209;mail заполните форму:</p>
        <input type="hidden" id="reg" name="reg" value="Минск">
        <input type="text" id="email" name="email" placeholder="email*">
        <input type="text" id="fio" name="fio" placeholder="имя*">
        <input type="checkbox" id="dform_policy" name="dform_policy" class="q-check">
        <label for="dform_policy" class="dform_policy_label">Я согласен(на) <span>на <a href="/company/policies" target="_blank">обработку персональных данных</a></span></label>
        <div class="dform-details dform-details-first">
            <span>*</span> Мы не&nbsp;храним адреса электронной почты<br>
            и&nbsp;не&nbsp;занимаемся несанкционированной рассылкой сообщений.
        </div>
        <div class="dform-details">
            <span>**</span> Если вы хотите получать от&nbsp;нас информацию<br>
            об&nbsp;обновлении ассортимента и&nbsp;моделей&nbsp;- поставьте галочку&nbsp;согласия.
        </div>
    </div>
    <div class="dform-radio-wrap">
        <div class="dform-radio-btns-wrap">
            <div class="dform-radio" data-type="d-form-new">
                <p>Согласны ли вы получать информацию о&nbsp;новинках ассортимента?</p>
                <div class="dform-radio-btns">
                    <div data-type="radio-new" data-val="Y">да</div>
                    <div data-type="radio-new" class="active" data-val="N">нет</div>
                </div>
            </div>
            <div class="dform-radio-y" data-type="d-form-new-y">
                <i class="icomoon icon-checked"></i>Вы подписаны на&nbsp;новостную рассылку о&nbsp;новинках ассортимента
            </div>
            <div class="dform-radio" data-type="d-form-3d">
                <p>Согласны ли&nbsp;вы получать информацию об&nbsp;обновлениях библиотеки 3D&nbsp;моделей?</p>
                <div class="dform-radio-btns">
                    <div data-type="radio-updt" data-val="Y">да</div>
                    <div data-type="radio-updt" class="active" data-val="N">нет</div>
                </div>
            </div>
            <div class="dform-radio-y" data-type="d-form-3d-y">
                Вы подписаны на&nbsp;новостную рассылку об&nbsp;обновления библиотеки 3D&nbsp;моделей
            </div>
        </div>
        <div class="dform-wait">
            <img src="/img/preloader.gif" alt="wait...">
            <div class="dform-wait-mess">Ожидайте формирование пакета</div>
        </div>
        <div class="dform-result" style="display:none;">
            <div class="dform-result-mess"></div>
        </div>
    </div>
    <div class="dform-btns">
        <button class="dform-cansel" data-type="dform-cansel">Отмена</button>
        <button class="dform-submit" data-type="dform-submit">Отправить запрос</button>
        <button class="dform-close" data-type="dform-close">Закрыть форму</button>
    </div>
    <div class="dform-warn">
        <span>Предупреждение</span><br>
        Использование данной функции приводит к&nbsp;получению большого объема данных,
        обратите внимание на&nbsp;этот&nbsp;факт, если &nbsp;Вы используете мобильное подключение,
        находитесь в&nbsp;роуминге или&nbsp;используете платное подключение.
    </div>
    <div class="dform-explain">* - пункты, обязательные для&nbsp;заполнения</div>
</div>

<div class="dform" data-type="dform2">
    <i class="icomoon icon-close" data-type="dform-close2"></i>
    <div class="dform-title">Запрос на получение 2D моделей</div>
    <div class="dform-lead">Автоматическая система* отправит на&nbsp;адрес электронной почты ссылку <br>для&nbsp;скачивания пакета с&nbsp;выбранными вами&nbsp;моделями</div>
    <div class="dform-main">
        <p>Для получения ссылки для&nbsp;скачивания на&nbsp;Ваш е&#8209;mail заполните форму:</p>
        <input type="hidden" id="reg2" name="reg" value="Минск">
        <input type="text" id="email2" name="email" placeholder="email*">
        <input type="text" id="fio2" name="fio" placeholder="имя*">
        <input type="checkbox" id="dform_policy2" name="dform_policy" class="q-check">
        <label for="dform_policy2" class="dform_policy_label">Я согласен(на) <span>на <a href="/company/policies" target="_blank">обработку персональных данных</a></span></label>
        <div class="dform-details dform-details-first">
            <span>*</span> Мы не&nbsp;храним адреса электронной почты<br>
            и&nbsp;не&nbsp;занимаемся несанкционированной рассылкой сообщений.
        </div>
        <div class="dform-details">
            <span>**</span> Если вы хотите получать от&nbsp;нас информацию<br>
            об&nbsp;обновлении ассортимента и&nbsp;моделей&nbsp;- поставьте галочку&nbsp;согласия.
        </div>
    </div>
    <div class="dform-radio-wrap">
        <div class="dform-radio-btns-wrap">
            <div class="dform-radio" data-type="d-form-new2">
                <p>Согласны ли вы получать информацию о&nbsp;новинках ассортимента?</p>
                <div class="dform-radio-btns">
                    <div data-type="radio-new2" data-val="Y">да</div>
                    <div data-type="radio-new2" class="active" data-val="N">нет</div>
                </div>
            </div>
            <div class="dform-radio-y" data-type="d-form-new-y2">
                <i class="icomoon icon-checked"></i>Вы подписаны на&nbsp;новостную рассылку о&nbsp;новинках ассортимента
            </div>
            <div class="dform-radio" data-type="d-form-2d">
                <p>Согласны ли&nbsp;вы получать информацию об&nbsp;обновлениях библиотеки 2D&nbsp;моделей?</p>
                <div class="dform-radio-btns">
                    <div data-type="radio-updt2" data-val="Y">да</div>
                    <div data-type="radio-updt2" class="active" data-val="N">нет</div>
                </div>
            </div>
            <div class="dform-radio-y" data-type="d-form-2d-y">
                Вы подписаны на&nbsp;новостную рассылку об&nbsp;обновления библиотеки 2D&nbsp;моделей
            </div>
        </div>
        <div class="dform-wait">
            <img src="/img/preloader.gif" alt="wait...">
            <div class="dform-wait-mess">Ожидайте формирование пакета</div>
        </div>
        <div class="dform-result" style="display:none;">
            <div class="dform-result-mess"></div>
        </div>
    </div>
    <div class="dform-btns">
        <button class="dform-cansel" data-type="dform-cansel2">Отмена</button>
        <button class="dform-submit" data-type="dform-submit2">Отправить запрос</button>
        <button class="dform-close" data-type="dform-close2">Закрыть форму</button>
    </div>
    <div class="dform-warn">
        <span>Предупреждение</span><br>
        Использование данной функции приводит к&nbsp;получению большого объема данных,
        обратите внимание на&nbsp;этот&nbsp;факт, если &nbsp;Вы используете мобильное подключение,
        находитесь в&nbsp;роуминге или&nbsp;используете платное подключение.
    </div>
    <div class="dform-explain">* - пункты, обязательные для&nbsp;заполнения</div>
</div>


<script defer src="/download/download.js?<?=$random?>"></script>
<?
require($_SERVER["DOCUMENT_ROOT"] . "/include/footer.php");
if(defined("B_PROLOG_INCLUDED") && B_PROLOG_INCLUDED===true)
{
    require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog.php");
}