<?
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog.php");
if (!CModule::IncludeModule('iblock')) exit;
$APPLICATION->SetTitle("Контакты");
$APPLICATION->SetPageProperty("description", "Европласт - производство полиуретановых изделий, лидер на российском рынке");
require($_SERVER["DOCUMENT_ROOT"] . "/include/header.php");
?>
<div class="content-wrapper contact">
    <div class="cat-top not-fixed">
        <div class="cat-sections cat-sections-catalogue">
            <?if($my_city != 3109) { ?>
            <a href="#region" class="cat-sections-item" data-type="main-tab">
                Представитель в&nbsp;регионе
            </a>
            <?/*<a href="#head" class="cat-sections-item" data-type="main-tab">
                Головной офис
            </a>*/?>
            <a href="#outlets" class="cat-sections-item" data-type="main-tab">
                Точки продаж
            </a>
            <? } ?>
        </div>
    </div>
</div>
<?/**
 * $dealer - представитель в регионе - берется из /include/top-current-location.php
*/?>
<?if($my_city != 3109) { ?>
<section class="contact-info">
    <div class="content-wrapper">
        <?if($my_city != 3109) { ?>
            <div class="contact-wrap" id="region" data-type="main-tab-cont">
            <?
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
            <div class="main-contact-info">
                <div class="e-cctb-title">
                    <span>
                        <?
                        if($dealer['trade_point']['~VALUE'] != '') {
                            echo $dealer['trade_point']['~VALUE'];
                        }
                        if($dealer['trade_point']['~VALUE'] != '' && $dealer['organization']['~VALUE'] && $dealer['trade_point']['~VALUE'] != $dealer['organization']['~VALUE']) {
                            echo '<br>';
                        }
                        if($dealer['organization']['~VALUE'] && $dealer['trade_point']['~VALUE'] != $dealer['organization']['~VALUE']) {
                            echo '<span>'.$dealer['organization']['~VALUE'].'</span>';
                        }
                        ?>
                    </span>
                </div>
                <div class="e-cctb-content-2col">
                    <?/*<div>*/?>
                        <?if($dealer['address']['~VALUE'] != '') { ?>
                            <div class="e-cctb-item">
                                <span class="e-cctb-name">адрес</span>
                                <p><?=$dealer['address']['~VALUE']?></p>
                                <?if($dealer['orientation']['~VALUE']!= '') { ?>
                                    <p><?=$dealer['orientation']['~VALUE']?></p>
                                <? } ?>
                            </div>
                        <? } ?>
                        <?
                        $phones = $dealer['order_phone']['VALUE'] != '' ? $dealer['order_phone']['VALUE'] : $dealer['phones']['VALUE'];
                        if($phones != '') {
                            $phones = explode(';', $phones);
                            ?>
                            <div class="e-cctb-item">
                                <span class="e-cctb-name">телефон/факс</span>
                                <p>
                                    <?foreach ($phones as $p=>$d_phone) {
                                        echo $d_phone;
                                        if($p < count($phones)-1) echo ', <br>';
                                    } ?>
                                </p>
                            </div>
                        <? } ?>
                        <?if($dealer['email']['~VALUE'] != '') { ?>
                            <div class="e-cctb-item">
                                <span class="e-cctb-name">E-mail</span>
                                <p><?=$dealer['email']['~VALUE']?></p>
                            </div>
                        <? } ?>
                        <?if($dealer['href']['~VALUE'] != '') { ?>
                            <div class="e-cctb-item">
                                <span class="e-cctb-name">сайт</span>
                                <p>
                                    <a target="_blank" href="http://<?=$dealer['href']['~VALUE']?>"><?=$dealer['href']['~VALUE']?></a>
                                </p>
                            </div>
                        <? } ?>
                    <?/*</div>*/?>
                    <?if($dealer['saturday']['~VALUE']!= '' || $dealer['sunday']['~VALUE']!= '' || $dealer['workday']['~VALUE'] != '' || $dealer['weekend']['~VALUE'] != '' || $dealer['without']['VALUE'] == 'Y') { ?>
                    <?/*<div>*/?>
                    <div class="e-cctb-item e-cctb-item-time">
                        <span class="e-cctb-name">Время работы</span>
                        <?if($dealer['workday']['~VALUE'] != '') { ?>
                            <div class="e-ccb-wtw"><p>Будни</p><p><?=$dealer['workday']['~VALUE']?></p></div>
                        <? } ?>
                        <?if($dealer['saturday']['~VALUE'] != '') { ?>
                            <div class="e-ccb-wtw"><p>Суббота</p><p><?=$dealer['saturday']['~VALUE']?></p></div>
                        <? } ?>
                        <?if($dealer['sunday']['~VALUE'] != '') { ?>
                            <div class="e-ccb-wtw"><p>Воскресенье</p><p><?=$dealer['sunday']['~VALUE']?></p></div>
                        <? } ?>
                        <?if($dealer['weekend']['~VALUE'] != '') { ?>
                            <div class="e-ccb-wtw"><p>Выходные</p><p><?=$dealer['weekend']['~VALUE']?></p></div>
                        <? } ?>
                        <?if($dealer['without']['VALUE'] == 'Y') { ?>
                            <div class="e-ccb-wtw"><p>Без выходных</p><p></p></div>
                        <? } ?>
                    </div>
                    <?/*</div>*/?>
                    <? } ?>
                </div>
            </div>
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
        </div>
        <? } ?>
        <?/*<div class="contact-wrap<?if($my_city == 3109) echo ' contact-wrap-moscow'?>" id="head" data-type="main-tab-cont">
            <div class="head-office-wrap">
                <ul class="head-office-list" data-type="main-off">
                    <li class="active" data-val="h-off-1" data-id='0' data-type="h-off-tab">головной офис</li>
                    <li data-val="h-off-2" data-id='1' data-type="h-off-tab">Служба качества</li>
                    <li data-val="h-off-3" data-id='1' data-type="h-off-tab">Производство</li>
                    <li data-val="h-off-4" data-id='2' data-type="h-off-tab">Складской комплекс</li>
                    <li data-val="h-off-5" data-id='0' data-type="h-off-tab">Дилерам</li>
                    <li data-val="h-off-6" data-id='0' data-type="h-off-tab">Строителям</li>
                    <li data-val="h-off-7" data-id='0' data-type="h-off-tab">Дизайнерам и&nbsp;архитекторам</li>
                    <li data-val="h-off-8" data-id='0' data-type="h-off-tab">Пресс-служба</li>
                    <li data-val="h-off-9" data-id='1' data-type="h-off-tab">Отдел персонала</li>
                    <li data-val="h-off-10" data-id='0' data-type="h-off-tab">Интернет-магазин</li>
                </ul>
                <div class="head-office-cont">

                    <div class="head-office-item active" data-val="h-off-1">
                        <div>
                            <span class="head-office-item-title">Адрес</span>
                            <p class="head-office-item-val">117545, Россия, г.&nbsp;Москва,<br>1-й Дорожный проезд, д.&nbsp;6, стр.&nbsp;4</p>
                        </div>
                        <div>
                            <span class="head-office-item-title">Телефон/факс</span>
                            <p class="head-office-item-val">+7 495 315 30 40</p>
                        </div>
                        <div>
                            <span class="head-office-item-title">почта</span>
                            <p class="head-office-item-val">decor@decor-evroplast.ru</p>
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
                            <p class="head-office-item-val">Московская область, Ленинский&nbsp;район, <br>с/п&nbsp;Булатниковское, с.&nbsp;Булатниково, <br>ул.&nbsp;Центральная, дом&nbsp;1В, стр.&nbsp;10</p>
                        </div>
                        <div>
                            <span class="head-office-item-title">схема проезда</span>
                            <a href="/download/location_map.pdf" class="prod-info-dwnld-btn" download=""><i class="icon-download"></i> скачать pdf</a>
                        </div>
                    </div>

                    <div class="head-office-item" data-val="h-off-5">
                        <div>
                            <div>
                                <span class="head-office-item-title">Адрес</span>
                                <p class="head-office-item-val">117545, Россия, г.&nbsp;Москва,<br>1-й&nbsp;Дорожный&nbsp;проезд, д.&nbsp;6, стр.&nbsp;4</p>
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
                                <p class="head-office-item-val">dealer@decor-evroplast.ru</p>
                            </div>
                        </div>
                    </div>

                    <div class="head-office-item" data-val="h-off-6">
                        <div>
                            <div>
                                <span class="head-office-item-title">Адрес</span>
                                <p class="head-office-item-val">117545, Россия, г.&nbsp;Москва,<br>1-й&nbsp;Дорожный&nbsp;проезд, д.&nbsp;6, стр.&nbsp;4</p>
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
                                <span class="head-office-item-val">почта</span>
                                <p class="head-office-item-val">prof@decor-evroplast.ru</p>
                            </div>
                        </div>
                    </div>

                    <div class="head-office-item" data-val="h-off-7">
                        <div>
                            <div>
                                <span class="head-office-item-title">Адрес</span>
                                <p class="head-office-item-val">117545, Россия, г.&nbsp;Москва,<br>1-й&nbsp;Дорожный&nbsp;проезд, д.&nbsp;6, стр.&nbsp;4</p>
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
                                <p class="head-office-item-val">design@decor-evroplast.ru</p>
                            </div>
                        </div>
                    </div>

                    <div class="head-office-item" data-val="h-off-8">
                        <div>
                            <div>
                                <span class="head-office-item-title">Адрес</span>
                                <p class="head-office-item-val">117545, Россия, г.&nbsp;Москва,<br>1-й&nbsp;Дорожный&nbsp;проезд, д.&nbsp;6, стр.&nbsp;4</p>
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
                                <p class="head-office-item-val">117545, Россия, г.&nbsp;Москва,<br>1-й&nbsp;Дорожный&nbsp;проезд, д.&nbsp;6, стр.&nbsp;4</p>
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
                    </div>

                </div>
            </div>
        </div>*/?>
        <div class="contact-wrap<?if($my_city == 3109) echo ' contact-wrap-moscow'?>" id="outlets" data-type="main-tab-cont">
            <?/*if($my_city != 3109) { ?>
                <div class="outlets-main-dealer">
                <div class="outlet-item">
                    <div class="e-cctb-title">
                        <span>
                            <?
                            if($dealer['trade_point']['~VALUE'] != '') {
                                echo $dealer['trade_point']['~VALUE'];
                            }
                            if($dealer['trade_point']['~VALUE'] != '' && $dealer['organization']['~VALUE'] && $dealer['trade_point']['~VALUE'] != $dealer['organization']['~VALUE']) {
                                echo '<br>';
                            }
                            if($dealer['organization']['~VALUE'] && $dealer['trade_point']['~VALUE'] != $dealer['organization']['~VALUE']) {
                                echo '<text>'.$dealer['organization']['~VALUE'].'</text>';
                            }
                            ?>
                        </span>
                    </div>
                    <div class="e-cctb-content-2col">
                        <?if($dealer['address']['~VALUE'] != '') { ?>
                            <div class="e-cctb-item">
                                <span class="e-cctb-name">адрес</span>
                                <p><?=$dealer['address']['~VALUE']?></p>
                                <?if($dealer['orientation']['~VALUE']!= '') { ?>
                                    <p><?=$dealer['orientation']['~VALUE']?></p>
                                <? } ?>
                            </div>
                        <? } ?>
                        <?if($dealer['href']['~VALUE'] != '') { ?>
                            <div class="e-cctb-item">
                                <span class="e-cctb-name">сайт</span>
                                <p>
                                    <a target="_blank" href="http://<?=$dealer['href']['~VALUE']?>"><?=$dealer['href']['~VALUE']?></a>
                                </p>
                            </div>
                        <? } ?>
                        <?
                        $phones = $dealer['order_phone']['VALUE'] != '' ? $dealer['order_phone']['VALUE'] : $dealer['phones']['VALUE'];
                        if($phones != '') {
                            $phones = explode(';', $phones);
                            ?>
                            <div class="e-cctb-item">
                                <span class="e-cctb-name">телефон/факс</span>
                                <p>
                                    <?foreach ($phones as $p=>$d_phone) {
                                        echo $d_phone;
                                        if($p < count($phones)-1) echo ', <br>';
                                    } ?>
                                </p>
                            </div>
                        <? } ?>
                        <?if($dealer['email']['~VALUE'] != '') { ?>
                            <div class="e-cctb-item">
                                <span class="e-cctb-name">почта</span>
                                <p><?=$dealer['email']['~VALUE']?></p>
                            </div>
                        <? } ?>
                    </div>
                </div>
            </div>
            <? } */?>
            <?/*<div class="outlets-tabs">
                <div class="outlets-tabs-item active" data-type="outlet-tab" data-val="map">Карта</div>
                <div class="outlets-tabs-item" data-type="outlet-tab" data-val="list">Список</div>
            </div> */?>
        </div>
    </div>
</section>
<? } ?>
<section class="contact-map">
    <script src="https://api-maps.yandex.ru/2.1/?apikey=0e24f952-da4e-4266-9ab4-66b2b263e914&lang=ru_RU" type="text/javascript"></script>
    <div class="contact-map-wrap">
        <div class="main-dealer-map-container main-dealer-map-container-contact" data-type="designer-map" id="map"></div>
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

