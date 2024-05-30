<?
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog.php");
if (!CModule::IncludeModule('iblock')) exit;
$APPLICATION->SetTitle("Дилерам");
$APPLICATION->SetPageProperty("description", "Европласт - производство полиуретановых изделий, лидер на российском рынке");
require($_SERVER["DOCUMENT_ROOT"] . "/include/header.php");
?>
<? /*
<div class="main-banner">
    <div class="main-slide-caption white">Контакты</div>
    <img src="/img/dealers/1.jpg" alt="Контакты">
</div>
<section class="dealers-main-txt">
    <div class="content-wrapper">
        <p>
            Компания "Декор" реализует продукцию в&nbsp;регионах присуствия
            через&nbsp;собственную дилерскую сеть. В&nbsp;каждом регионе присутствия
            дилер обеспечивает открытие офиса, розничных точек продаж
            (опционально), склада и&nbsp;развивает субдилерскую&nbsp;сеть.
        </p>
        <p>
            Сегодня дилерами компании являются 203&nbsp;партнера из&nbsp;России, СНГ
            и&nbsp;стран Балтии и&nbsp;58&nbsp;партнеров из&nbsp;стран&nbsp;ЕС.
        </p>
        <p>
            Чтобы стать дилером компании, необходимо соответствовать
            условиям, предъявляемым компанией "Декор" к&nbsp;будущим партнерам.
            Вы можете получить дополнительную информацию в&nbsp;разделе
            "Контакты"&nbsp;– Дилерам, а&nbsp;также отправить свои данные через форму
            "Задать&nbsp;вопрос".
        </p>
        <p>
            Если вы являетесь представителем розничной сети и&nbsp;планируете
            реализовывать продукцию под&nbsp;брендами "Европласт" и&nbsp;"GAUDI",
            обратитесь к&nbsp;официальному дилеру бренда в&nbsp;своем регионе.
            Найти информацию и&nbsp;контакты дилера можно на&nbsp;нашем&nbsp;сайте.
        </p>
    </div>
</section>

*/ 

/* --- Свои контакты (для каждого города) --- */

$is_moscow = false;
if ($my_city == 3109) $is_moscow = true;

global $dealer;
$address = $dealer['address']['~VALUE'] ?? null;
$email = $dealer['email']['VALUE'] ?? null;
$phones = $dealer['phones']['VALUE'] ?? null;
if (empty($phones)) $phones = $phone;

$map = $dealer['map']['VALUE'] ?? ''; //координаты
if ($is_moscow) $map = '';

/* --- Головной офис --- */

$main_head = 'Адрес головного офиса';
$main_address = '117342, Россия, г.&nbsp;Москва,<br>ул.&nbsp;Обручева, д.&nbsp;52, стр.&nbsp;3';
$main_email = 'dealer@decor-evroplast.ru';
$main_phone = '+7 495 315 30 40';

$main_email2 = 'prof@decor-evroplast.ru';
$main_email3 = 'design@decor-evroplast.ru';

if (!$is_moscow) {
    $main_head = 'Адрес';
    if (!empty($address)) $main_address = $address; 
    if (!empty($email)) $main_email = $email;
    if (!empty($phones)) $main_phone = $phones;

    if (!empty($email)) $main_email2 = $email;
    if (!empty($email)) $main_email3 = $email;

    list($d_lat, $d_lon) = explode(",", $dealer['map']['VALUE']);
    $d_zoom = 16;
    $mainDealerObj = [
        'id'        => $dealer['ID'],
        'org'       => $dealer['organization']['~VALUE'],
        'point'     => $dealer['trade_point']['~VALUE'],
        'city'      => $loc['NAME'],
        'addr'      => $dealer['address']['~VALUE'],
        'mall'      => $dealer['trading_center']['~VALUE'],
        'mark'      => $dealer['orientation']['~VALUE'],
        'lat'       => $d_lat,
        'lon'       => $d_lon,
        'zoom'      => $d_zoom,
        'phones'    => $dealer['phones']['~VALUE'],
        'email'     => $dealer['email']['~VALUE'],
        'url'       => $dealer['href']['~VALUE'],
        'saturday'  => $dealer['saturday']['~VALUE'],
        'sunday'    => $dealer['sunday']['~VALUE'],
        'weekdays'  => $dealer['workday']['~VALUE'],
        'weekend'   => $dealer['weekend']['~VALUE'],
        'without'   => $dealer['without']['VALUE'],
    ];
    ?>
    <script>
    var mainDealer= {
        'err':      {
            'qty':  0,
            'mess': '',
        },
        'dealers':    {
            'position': {
                'lat':  <?=$d_lat?>,
                'lon':  <?=$d_lon?>,
                'zoom': <?=$d_zoom?>,
            },
            'items':    [],
        },
    };
    mainDealer.dealers.items.push(Object.assign({}, <?=json_encode($mainDealerObj)?>));
    </script>
    <?
}

/* --- // --- */
?>
    <section class="contact-info contact-page">
        <div class="content-wrapper">
            <div class="contact-wrap <?= $is_moscow ? 'contact-wrap-moscow' : '' ?>" data-type="main-tab-cont">
                <div class="head-office-wrap">
                    <ul class="head-office-list" data-type="main-off">
                        <? if (!$is_moscow) { ?>
                            <li class="active" data-val="h-off-11" data-id='1' data-type="h-off-tab">Представитель в регионе</li>
                        <? } ?>
                        <li class="head-office-main <?= $is_moscow ? 'active' : '' ?>" data-val="h-off-1" data-id='0' data-type="h-off-tab">головной офис</li>
                        <li data-val="h-off-2" data-id='1' data-type="h-off-tab">Служба качества</li>
                        <li data-val="h-off-3" data-id='1' data-type="h-off-tab">Производство</li>
                        <li data-val="h-off-4" data-id='2' data-type="h-off-tab">Складской комплекс</li>
                        <li data-val="h-off-5" data-id='0' data-type="h-off-tab">Дилерам</li>
                        <li data-val="h-off-6" data-id='0' data-type="h-off-tab">Строителям</li>
                        <li data-val="h-off-7" data-id='0' data-type="h-off-tab">Дизайнерам и&nbsp;архитекторам</li>
                        <li data-val="h-off-8" data-id='0' data-type="h-off-tab">Пресс-служба</li>
                        <? if ($is_moscow) { ?>
                            <li data-val="h-off-9" data-id='1' data-type="h-off-tab">Отдел персонала</li>
                        <? } ?>
                        <li data-val="h-off-10" data-id='0' data-type="h-off-tab">Интернет-магазин</li>
                    </ul>
                    <div class="head-office-cont">

                        <div class="head-office-item <?= $is_moscow ? 'active' : '' ?>" data-val="h-off-1">
                            <div>
                                <span class="head-office-item-title">Адрес</span>
                                <p class="head-office-item-val">117342, Россия, г.&nbsp;Москва,<br>ул.&nbsp;Обручева, д.&nbsp;52, стр.&nbsp;3</p>
                            </div>

                            <div>
                                <span class="head-office-item-title">Телефон/факс</span>
                                <p class="head-office-item-val">+7 495 315 30 40</p>
                            </div>
                            <div>
                                <span class="head-office-item-title">почта</span>
                                <p class="head-office-item-val">decor@decor-evroplast.ru</p>
                            </div>
                            <div>
                                <span class="head-office-item-title">схема проезда</span>
                                <a href="/download/Европласт_схема_проезда_в_офис_2024.pdf" class="prod-info-dwnld-btn" download=""><i class="icon-download"></i> скачать pdf</a>
                            </div>
                        </div>

                        <div class="head-office-item" data-val="h-off-2">
                            <div>
                                <div>
                                    <span class="head-office-item-title">Адрес</span>
                                    <p class="head-office-item-val">142350, Россия, Московская&nbsp;обл., <br>Чеховский р-н, д.&nbsp;Ивачково, <br>ул.&nbsp;Лесная, владение&nbsp;12, строение&nbsp;7</p>
                                </div>
                            </div>
                            <div>
                                <div>
                                    <span class="head-office-item-title">Телефон/факс</span>
                                    <p class="head-office-item-val">+7 (495) 789 62 76</p>
                                </div>
                            </div>
                            <div>
                                <div>
                                    <span class="head-office-item-title">почта</span>
                                    <p class="head-office-item-val">quality@decor-evroplast.ru</p>
                                </div>
                            </div>
                        </div>

                        <div class="head-office-item" data-val="h-off-3">
                            <div>
                                <span class="head-office-item-title">Адрес</span>
                                <p class="head-office-item-val">142350, Россия, Московская&nbsp;обл., <br>Чеховский р-н, д. Ивачково, <br>ул.&nbsp;Лесная, владение&nbsp;12, строение&nbsp;7</p>
                            </div>
                            <div>
                                <span class="head-office-item-title">Телефон/факс</span>
                                <p class="head-office-item-val">+7 495 789-62-70</p>
                            </div>
                        </div>

                        <div class="head-office-item" data-val="h-off-4">
                            <div>
                                <span class="head-office-item-title">Адрес</span>
                                <? /*
                                <p class="head-office-item-val">Московская область, Ленинский&nbsp;район, <br>с/п&nbsp;Булатниковское, с.&nbsp;Булатниково, <br>ул.&nbsp;Центральная, дом&nbsp;1В, стр.&nbsp;10</p>
                                */ ?>
                                <p class="head-office-item-val">142350, Россия, Московская&nbsp;обл., <br>Чеховский р-н, д.&nbsp;Ивачково, <br>ул.&nbsp;Лесная, владение&nbsp;12, строение&nbsp;7</p>
                                <? /*
                                <div style="font-size:14px;">
                                    <br>
                                    <span class="head-office-item-title" style="color:#00416B;">адрес для навигатора</span>
                                    <p class="head-office-item-val attent-blue">Ленинский&nbsp;район, Московская&nbsp;область, <br>с.&nbsp;Булатниково, ул.&nbsp;Центральная, д.1Б
                                    </p>
                                </div>
                                */ ?>
                            </div>
                            <? /*
                            <div>
                                <span class="head-office-item-title">схема проезда</span>
                                <a href="/download/Европласт_схема_проезда_на_склад-офис.pdf?v=1" class="prod-info-dwnld-btn" download=""><i class="icon-download"></i> скачать pdf</a>
                            </div>
                            */ ?>
                        </div>

                        <div class="head-office-item" data-val="h-off-5">
                            <div>
                                <div>
                                    <span class="head-office-item-title"><?= $main_head ?></span>
                                    <p class="head-office-item-val head-office-item-val-address"><?= $main_address ?></p>
                                </div>
                            </div>
                            <div>
                                <div>
                                    <span class="head-office-item-title">Телефон/факс</span>
                                    <p class="head-office-item-val"><?= $main_phone ?></p>
                                </div>
                            </div>
                            <div>
                                <div>
                                    <span class="head-office-item-title">почта</span>
                                    <p class="head-office-item-val"><?= $main_email ?></p>
                                </div>
                            </div>
                        </div>

                        <div class="head-office-item" data-val="h-off-6">
                            <div>
                                <div>
                                    <span class="head-office-item-title"><?= $main_head ?></span>
                                    <p class="head-office-item-val"><?= $main_address ?></p>
                                </div>
                            </div>
                            <div>
                                <div>
                                    <span class="head-office-item-title">Телефон/факс</span>
                                    <p class="head-office-item-val"><?= $main_phone ?></p>
                                </div>
                            </div>
                            <div>
                                <div>
                                    <span class="head-office-item-title">почта</span>
                                    <p class="head-office-item-val"><?= $main_email2 ?></p>
                                </div>
                            </div>
                        </div>

                        <div class="head-office-item" data-val="h-off-7">
                            <div>
                                <div>
                                    <span class="head-office-item-title"><?= $main_head ?></span>
                                    <p class="head-office-item-val"><?= $main_address ?></p>
                                </div>
                            </div>
                            <div>
                                <div>
                                    <span class="head-office-item-title">Телефон/факс</span>
                                    <p class="head-office-item-val"><?= $main_phone ?></p>
                                </div>
                            </div>
                            <div>
                                <div>
                                    <span class="head-office-item-title">почта</span>
                                    <p class="head-office-item-val"><?= $main_email3 ?></p>
                                </div>
                            </div>
                        </div>

                        <div class="head-office-item" data-val="h-off-8">
                            <div>
                                <div>
                                    <span class="head-office-item-title">Адрес</span>
                                    <p class="head-office-item-val">117342, Россия, г.&nbsp;Москва,<br>ул.&nbsp;Обручева, д.&nbsp;52, стр.&nbsp;3</p>
                                </div>
                            </div>
                            <div>
                                <div>
                                    <span class="head-office-item-title">Телефон/факс</span>
                                    <p class="head-office-item-val">+7 495 315 30 40</p>
                                </div>
                            </div>
                            <div>
                                <div>
                                    <span class="head-office-item-title">почта</span>
                                    <p class="head-office-item-val">pr@decor-evroplast.ru</p>
                                </div>
                            </div>
                        </div>

                        <div class="head-office-item" data-val="h-off-9">
                            <div>
                                <div>
                                    <span class="head-office-item-title">Адрес</span>
                                    <p class="head-office-item-val">142350, Россия, Московская&nbsp;обл., <br>Чеховский&nbsp;р-н, д.&nbsp;Ивачково, <br>ул.&nbsp;Лесная, владение&nbsp;12, строение&nbsp;7</p>
                                </div>
                            </div>
                            <div>
                                <div>
                                    <span class="head-office-item-title">Телефон/факс</span>
                                    <p class="head-office-item-val">+7 (495) 789 62 70</p>
                                </div>
                            </div>
                            <div>
                                <div>
                                    <span class="head-office-item-title">почта</span>
                                    <p class="head-office-item-val">hr@decor-evroplast.ru</p>
                                </div>
                            </div>
                        </div>

                        <div class="head-office-item" data-val="h-off-10">
                            <div>
                                <div>
                                    <span class="head-office-item-title">Адрес</span>
                                    <p class="head-office-item-val">117342, Россия, г.&nbsp;Москва,<br>ул.&nbsp;Обручева, д.&nbsp;52, стр.&nbsp;3</p>

                                </div>
                            </div>
                            <div>
                                <div>
                                    <span class="head-office-item-title">Телефон/факс</span>
                                    <p class="head-office-item-val">+7 495 315 30 40</p>
                                </div>
                            </div>
                            <div>
                                <div>
                                    <span class="head-office-item-title">почта</span>
                                    <p class="head-office-item-val">marketing@decor-evroplast.ru</p>
                                </div>
                            </div>
                            <div>
                                <span class="head-office-item-title">схема проезда</span>
                                <a href="/download/Европласт_схема_проезда_в_офис_2024.pdf" class="prod-info-dwnld-btn" download=""><i class="icon-download"></i> скачать pdf</a>
                            </div>
                        </div>

                        <? if (!$is_moscow) { ?>
                            <div class="head-office-item active" data-val="h-off-11">
                                <? if (!empty($address)) { ?>
                                    <div>
                                        <div>
                                            <span class="head-office-item-title">Адрес</span>
                                            <p class="head-office-item-val" style><?= $address ?></p>
                                        </div>
                                    </div>
                                <? } ?>
                                <? if (!empty($phones)) { ?>
                                    <div>
                                        <div>
                                            <span class="head-office-item-title">Телефон/факс</span>
                                            <p class="head-office-item-val"><?= $phones ?></p>
                                        </div>
                                    </div>
                                <? } ?>
                                <? if (!empty($email)) { ?>
                                    <div>
                                        <div>
                                            <span class="head-office-item-title">почта</span>
                                            <p class="head-office-item-val"><?= $email ?></p>
                                        </div>
                                    </div>
                                <? } ?>
                            </div>
                        <? } ?>

                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="contact-map">
        <script src="https://api-maps.yandex.ru/2.1/?apikey=0e24f952-da4e-4266-9ab4-66b2b263e914&lang=ru_RU" type="text/javascript"></script>
        <div class="contact-map-wrap">
            <div class="main-dealer-map-container main-dealer-map-container-contact" data-type="designer-map" id="map" data-coords="<?= $map ?>"></div>
            <div class="main-dealer-list-container" data-type="list">
                <div class="content-wrapper">

                </div>
            </div>
            <div class="md-content-wait" data-type="wait-panel">
                <img src="/img/preloader.gif" alt="Wait...">
            </div>
        </div>
    </section>
    <script src="/wheretobuy/script.js?<?=$random?>"></script>
<?
require($_SERVER["DOCUMENT_ROOT"] . "/include/footer.php");
if(defined("B_PROLOG_INCLUDED") && B_PROLOG_INCLUDED===true)
{
    require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog.php");
}
