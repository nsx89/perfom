<?
/**
 *  подключается в дизайнерам и архитекторам, строителям
 *
 * $dealer берется из /include/top-current-location.php
 */
?>
<section class="main-dealer-map">
    <? 
    if (!empty($_GET['test'])) {
        $my_city = 3109;
    }

    if($my_city == 3109 && empty($_GET['spb'])) {
        //$d_address = '117545, Россия, г.&nbsp;Москва,<br>1-й&nbsp;Дорожный проезд, д.&nbsp;6, стр.&nbsp;4';
        $d_address = '117246, Россия, г.&nbsp;Москва,<br>ул.&nbsp;Обручева, д.&nbsp;52, стр.&nbsp;3';
        /*$d_address .= '<p class="head-office-item-val attent"><br>новый офис начнет работать <br>с&nbsp;15&nbsp;февраля <br><br>до&nbsp;15 февраля 2024 временный офис <br>будет находиться: <br>
                                    142718, Россия, Московская&nbsp;обл., <br>Ленинский&nbsp;р-н, с/п Булатниково, <br>ул.&nbsp;Центральная, влд.&nbsp;1В, стр.&nbsp;10,
                                </p>
                                <div style="font-size:14px;">
                                    <br>
                                    <span class="head-office-item-title" style="color:#00416B;">адрес для навигатора</span>
                                    <p class="head-office-item-val attent-blue">Ленинский&nbsp;район, Московская&nbsp;область, <br>с.&nbsp;Булатниково, ул.&nbsp;Центральная, д.1Б
                                    </p>
                                </div>';*/
        //$d_loc_map = '/download/Европласт_схема_проезда_на_склад-офис.pdf';
        $d_loc_map = '/download/Европласт_схема_проезда_в_офис_2024.pdf';
        $d_email = 'design@decor-evroplast.ru';
        //$d_lat = '55.56821656917941';
        //$d_lon = '37.66324249999998';
        $d_lat = '55.648341';
        $d_lon = '37.561069';
        $d_zoom = 16;
        $d_phone = $phone;
        $d_class = ' mos_dealer';
        if($page == 'professional') {
            $d_email = 'prof@decor-evroplast.ru';
            $d_phone = '+7 495 315 30 40 доб. 4';
        }
    } else {
        //print_r($dealer);
        $d_address = $dealer['address']['VALUE'];
        if ((isset($dealer['orientation']['VALUE']))&&($dealer['orientation']['VALUE'] != '')) {
            $d_address .= '<br>'.$dealer['orientation']['VALUE'];
        }
        if($dealer['order_phone']['VALUE'] || $dealer['phones']['VALUE']) {
            $phones = $dealer['order_phone']['VALUE'] != '' ? $dealer['order_phone']['VALUE'] : $dealer['phones']['VALUE'];
            $phones = explode(';', $phones);
            foreach($phones as $k => $v) {
                if($k > 0) $d_phone .= '<br>';
                $d_phone .= substr_replace (phone($v), '<span style="display: none;">no skype!</span>', 6, 0);
            }
        }
        if ((isset($dealer['email']['VALUE']))&&($dealer['email']['VALUE'] != '')) {
            $d_email = $dealer['email']['VALUE'];
        }
        list($d_lat, $d_lon) = explode(",", $dealer['map']['VALUE']);
        $d_zoom = 11;
        $d_class = '';

    } ?>
    <script src="https://api-maps.yandex.ru/2.1/?apikey=0e24f952-da4e-4266-9ab4-66b2b263e914&lang=ru_RU" type="text/javascript"></script>
    <script type="text/javascript">
        ymaps.ready(init);
        function init(){
            var lat = <?=$d_lat?>,
                lon = <?=$d_lon?>,
                zoom = <?=$d_zoom?>;
            // Создание карты.
            var myMap = new ymaps.Map("map", {
                center: [lat, lon],
                zoom: zoom,
                scroll: false,
            });
            var dPoint = new ymaps.Placemark([lat,lon], {
                hintContent: "Центральный офис Европласт",
                //balloonContentHeader: "",
                balloonContentBody: "<div style='padding: 7px 12px 7px;'>Центральный офис Европласт</div>",
                //balloonContentFooter: "",
            }, 
            {
                balloonMaxWidth: 300,
                iconLayout: 'default#image',
                iconImageSize: [28, 42],
                iconImageOffset: [-14, -8],
                iconImageHref: "/img/e-mark.svg?v=1",
            });
            myMap.geoObjects.add(dPoint);
            myMap.behaviors.disable('scrollZoom');
            //if(supportsTouch === true) {
                //myMap.behaviors.disable('drag');
            //}
        }
    </script>
    <div class="main-dealer-map-container<?=$d_class?>" data-type="designer-map" id="map"></div>
    <div class="main-dealer-map-desc">
        <div class="content-wrapper">
            <?if($d_address) { ?>
                <div class="main-dealer-map-desc-item">
                    <div class="main-dealer-map-desc-title">адрес</div>
                    <div class="main-dealer-map-desc-info main-dealer-map-desc-info-addr"><?=$d_address?></div>
                </div>
            <? } ?>
            <?if($d_loc_map && file_exists($_SERVER["DOCUMENT_ROOT"].$d_loc_map)) { ?>
                <div class="main-dealer-map-desc-item">
                    <div class="main-dealer-map-desc-title">схема проезда</div>
                    <a href="<?=$d_loc_map?>" class="prod-info-dwnld-btn" download=""><i class="icon-download"></i> скачать pdf</a>
                </div>
            <? } ?>
            <?if($d_phone) { ?>
                <div class="main-dealer-map-desc-item">
                    <div class="main-dealer-map-desc-title">телефон</div>
                    <div class="main-dealer-map-desc-info main-dealer-map-desc-info-phone"><?=$d_phone?></div>
                </div>
            <? } ?>
            <?if($d_email) { ?>
                <div class="main-dealer-map-desc-item">
                    <div class="main-dealer-map-desc-title">почта</div>
                    <div class="main-dealer-map-desc-info main-dealer-map-desc-info-email"><a href="mailto:<?=$d_email?>"><?=$d_email?></a></div>
                </div>
            <? } ?>
        </div>
    </div>

</section>