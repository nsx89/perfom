<?
/*ini_set('display_errors', 1);
error_reporting(E_ALL);*/

require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog.php");
if (!CModule::IncludeModule('iblock') || !CModule::IncludeModule("catalog")) {
    exit;
}
$APPLICATION->SetTitle("Европласт - производство полиуретановых изделий, лидер на российском рынке");
$APPLICATION->SetPageProperty("description", "Европласт - производство полиуретановых изделий, лидер на российском рынке");

require($_SERVER["DOCUMENT_ROOT"] . "/include/header.php");
?>
    <link href="/big/css/main.css?v=1" type="text/css"  rel="stylesheet"/>
    <link href="/big/css/responsive.css" type="text/css"  rel="stylesheet"/>
<div class="content-wrapper product">
    <section class="prod-info-wrap">
        <div class="prod-info-prev">
            <div class="prod-preload" data-type="prod-preload">
                <img src="/img/preloader.gif" alt="Ожидайте">
            </div>
            <div id="prodSlider" class="slider-prod">
                <div class="sp-slides">
                    <div class="sp-slide">
                        <a href="https://evroplast.ru/cron/catalog/data/images/big/100/1.50.100.100-b.png" data-lightbox="big" class="sp-big" data-title="карниз 1.50.100" tabindex="0">Увеличить</a>





                        <img class="sp-image" src="https://evroplast.ru/upload/resize_cache/cron_responsive/catalog/data/images/100/525_525_1/1.50.100.100.png" data-val="100">
                        <img class="sp-thumbnail" src="https://evroplast.ru/upload/resize_cache/cron_responsive/catalog/data/images/100/106_106_1/1.50.100.100.png">
                    </div>
                    <div class="sp-slide">
                        <img class="sp-image" src="https://evroplast.ru/cron/catalog/data/images/20/1.50.100.20.png" data-val="200">
                        <img class="sp-thumbnail" src="https://evroplast.ru/upload/resize_cache/cron/catalog/data/images/20/106_106_1/1.50.100.20.png">
                    </div>
                    <div class="sp-slide">
                        <img class="sp-image" src="https://evroplast.ru/cron/catalog/data/images/30/1.50.100.30.png" data-val="100">
                        <img class="sp-thumbnail" src="https://evroplast.ru/upload/resize_cache/cron/catalog/data/images/30/106_106_1/1.50.100.30.png">
                    </div>
                    <div class="sp-slide">
                        <div class="big-section">
                            <div class="big-section-note">мм, справочный размер</div>
                            <div id="item_spec_big">
                                <div id="cut_img_big" style="position: absolute; display: block; left:137px; top:0px;">
                                    <div style="padding-top: 10px; text-align: center; color: #000; font-size: 150%">55</div>
                                    <div style="position: absolute; margin-top: 192px; margin-left: -74px; width: 60px; text-align: center; color: #000; font-size: 150%; -webkit-transform: rotate(270deg); -moz-transform: rotate(270deg); -ms-transform: rotate(270deg); -o-transform: rotate(270deg); transform: rotate(270deg);">64</div>
                                    <div style="margin-left: -7px; margin-right: -7px; margin-top: -2px; padding-bottom: 5px;">
                                        <div style="display: block; height: 30px; background: url('https://evroplast.ru/images/cut_left_big.png');">
                                            <span style="display: block; width : 15px; height: 100%; float:right; background: url('https://evroplast.ru/images/cut_right_big.png');"/>
                                        </div>
                                    </div>
                                    <div id="sechenie_img_big">
                                        <img style="height: 340px; width : 210px;" src="https://evroplast.ru/cron/catalog/data/images/40/1.50.100.40.png" alt=""/>
                                        <div style="margin-left: -35px; margin-top: -350px;">
                                            <div style="position: absolute; display: block; height: 353px; width : 30px; background: url('https://evroplast.ru/images/cut_top_big.png');">
                                                <span style="position: absolute; bottom: 0px; display: block; width : 100%; height: 15px; background: url('https://evroplast.ru/images/cut_bottom_big.png');"/>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="sp-thumbnail sp-thumbnail-section">
                            <div class="section-wrap" <?/*style="width:122px;height:156px;"*/?>>
                                <?/*
                                height = $img_cur_h + 30 (paddings) + 17 (axis) + 19 (number);
                                width = $img_cur_w + 30 (paddings) + 14 (axis) + 19 (number);
                            */?>
                                <div id="cut_img" style="position: absolute; display: block; left: 75px; top: 0;">
                                    <div style="text-align: center; color: #000; font-size: 100%">55</div>
                                    <div style="position: absolute; margin-top: 51px; margin-left: -48px; width: 50px; text-align: center; color: #000; font-size: 100%; -webkit-transform: rotate(270deg); -moz-transform: rotate(270deg); -ms-transform: rotate(270deg); -o-transform: rotate(270deg); transform: rotate(270deg);">64</div>
                                    <div style="margin-left: -4px; margin-right: -4px; margin-top: -2px; padding-bottom: 3px;">
                                        <div style="display: block; height: 14px; background: url('https://evroplast.ru/images/cut_left_small.png');">
                                            <span style="display: block; width : 14px; height: 100%; float:right; background: url('https://evroplast.ru/images/cut_right_small.png');"/>
                                        </div>
                                    </div>
                                    <div id="sechenie_img">
                                        <img style="height:80px; width:49px;" src="/cron/catalog/data/images/40/1.50.100.40.png" alt="">
                                    </div>
                                    <div style="margin-left: -17px; margin-top: -100px;">
                                        <div style="position: absolute; display: block; height: 100px; width : 14px; background: url('https://evroplast.ru/images/cut_top_small.png');">
                                            <span style="position: absolute; bottom: 0px; display: block; width : 100%; height: 14px; background: url('https://evroplast.ru/images/cut_bottom_small.png');"/>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="prod-info" data-type="prod-info" data-id="4783" data-cat="1542" data-name="карниз 1.50.100" data-code="ВР000001042" data-price="58.52" data-currency="BYN" data-cat-name="карнизы" data-iscomp="0" data-qty="1">
            <div class="prod-info-top prod-info-line">
                <h1>карниз 1.50.100</h1>
                <div class="prod-info-main">
                    <div class="prod-info-price">10 091.00 RUB</div>
                    <div class="prod-info-btns">
                        <div class="prod-buy">
                            <div class="prod-add-cart" data-type="prod-page-add">в корзину</div>
                            <div class="prod-qty" data-inbasket="">
                                <div class="prod-minus prod-qty-btn" data-type="prod-page-minus"><i class="icon-minus"></i></div>
                                <input type="text" value="1" min="1" data-type="prod-page-qty">
                                <div class="prod-plus prod-qty-btn" data-type="prod-page-plus"><i class="icon-plus-squared"></i></div>
                            </div>
                        </div>
                        <a href="/cart/" class="prod-buy-link" style="display: none;">перейти в корзину <span>3</span></a>
                        <div class="prod-compare-btn" data-type="compare">сравнить</div>
                        <i class="icon-favorite" data-type="favorite" data-user="no-user" title="Добавить в избранное"></i>
                        <div class="icon-share-wrap">
                            <i class="icon-share" data-type="share" title="Поделиться"></i>
                            <div class="add-social-wrap" data-type="share-wrap">
                                <script src="//yastatic.net/es5-shims/0.0.2/es5-shims.min.js"></script>
                                <script src="//yastatic.net/share2/share.js"></script>
                                <script>
                                    var myShare = document.getElementById('my-share');
                                    var share = Ya.share2('my-share', {
                                        content: {
                                            url: 'https://yandex.com',
                                            title: 'Yandex',
                                            description: 'All about Yandex',
                                            image: 'https://yastatic.net/morda-logo/i/logo.svg'
                                        }
                                    });
                                </script>
                                <div class="ya-share2" id="icon-share" data-services="vkontakte,odnoklassniki,twitter"></div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <div class="prod-info-sample prod-info-line">
                <div class="prod-add-cart sample-add-cart" data-type="buy-sample-page">Купить образец</div>
                <div class="sample-add-wrap">
                    <div class="sample-add-option"><span>Длина:</span> 250 мм</div>
                    <div class="sample-add-option"><span>Цена:</span> 100 RUB</div>
                </div>
            </div>
            <div class="prod-info-type-wrap prod-info-line">
                <div class="prod-info-type">
                    <div class="prod-info-type-name">тип</div>
                    <div class="prod-info-type-options">
                        <a class="prod-info-type-option active">жесткий</a>
                        <a class="prod-info-type-option" href="#" title="Перейти">гибкий</a>
                    </div>
                </div>
                <div class="prod-info-dwnld">
                    <div class="prod-info-dwnld-name">инструкция по монтажу</div>
                    <a href="https://evroplast.ru/download/manual_karnizy.pdf" class="prod-info-dwnld-btn" download><i class="icon-download"></i> скачать pdf</a>
                </div>
            </div>
            <div class="prod-info-params prod-info-line">
                <div class="prod-info-main-params">
                    <div>
                        <div class="prod-info-param-item">
                            <div class="prod-info-param-name">Длина детали, мм</div>
                            <div class="prod-info-param-val">2000</div>
                        </div>
                        <div class="prod-info-param-item">
                            <div class="prod-info-param-name">Ширина по потолку, мм</div>
                            <div class="prod-info-param-val">55</div>
                        </div>
                        <div class="prod-info-param-item">
                            <div class="prod-info-param-name">Высота по стене, мм</div>
                            <div class="prod-info-param-val">64</div>
                        </div>
                    </div>
                    <div>
                        <div class="prod-info-flex-params-title">Радиусы гибких аналогов</div>
                        <div class="prod-info-param-item">
                            <div class="prod-info-param-name">Радиус изгиба выпуклый, мм</div>
                            <div class="prod-info-param-val">3000</div>
                        </div>
                        <div class="prod-info-param-item">
                            <div class="prod-info-param-name">Радиус изгиба вогнутый, мм</div>
                            <div class="prod-info-param-val">2500</div>
                        </div>
                    </div>
                </div>
                <div class="prod-info-ref">*Размеры справочные</div>
                <div class="prod-info-param-material">
                    <i class="icon-material"></i>
                    <div class="prod-info-material-wrap">
                        <div class="prod-info-param-name">Материал</div>
                        <div class="prod-info-param-val">пенополиуретан</div>
                        <?/*<div class="prod-info-param-val">вспененный композиционный полимер высокой плотности на основе полистирола, изготовлено методом экструзии</div>*/?>
                    </div>
                </div>
            </div>
            <div class="prod-info-models prod-info-line">
                <div class="prod-info-models-wrap">
                    <div class="prod-info-models-title">3d модель</div>
                    <div class="prod-info-links">
                        <a href="#" class="prod-info-link"><i class="icon-download"></i> скачать max</a>
                        <a href="#" class="prod-info-link"><i class="icon-download"></i> скачать gsm</a>
                        <a href="#" class="prod-info-link"><i class="icon-download"></i> скачать obj</a>
                        <a href="#" class="prod-info-link"><i class="icon-download"></i> скачать 3ds</a>
                        <a href="#" class="prod-info-link"><i class="icon-download"></i> скачать dwg</a>
                    </div>
                </div>
                <div class="prod-info-models-wrap">
                    <div class="prod-info-models-title">2d модель</div>
                    <div class="prod-info-links">
                        <a href="#" class="prod-info-link"><i class="icon-download"></i> скачать dwg</a>
                        <a href="#" class="prod-info-link"><i class="icon-download"></i> скачать pdf</a>
                    </div>
                </div>

            </div>
        </div>
    </section>
    <section class="prod-adhesive">
        <div class="prod-flex-block">
            <div class="prod-adhesive-title prod-related-title">Гибкий аналог</div>
            <div class="prod-prev prod-prev-h" data-type="prod-prev" data-id="4783" data-name="карниз 1.50.100" data-code="ВР000001042" data-price="58.52" data-currency="BYN" data-cat="1542" data-cat-name="карнизы" data-iscomp="0">
                <a href="/karnizy/1-50-100/"></a>
                <div class="prod-prev-top">
                    <div class="prod-prev-title">
                        <span class="prod-prev-name">карниз гибкий</span>
                        <span class="prod-prev-article">1.50.100</span>
                    </div>
                    <div class="prod-prev-img">
                        <img src="https://evroplast.ru/upload/resize_cache/cron_responsive/catalog/data/images/600/287_287_1/1.50.100.600.png" alt="карниз 1.50.100">
                    </div>
                    <div class="prod-prev-btns">
                        <div class="prod-prev-icons">
                            <i class="icon-favorite" data-type="favorite" data-user="no-user" title="Добавить в избранное"></i>
                            <i class="icon-cart" data-type="cart-add" title="Добавить в корзину"></i>
                        </div>
                        <div class="prod-prev-one-click" data-type="one-click">купить в&nbsp;1&nbsp;клик</div>
                        <div class="prod-prev-cart-mess prod-prev-mess">Товар добавлен в&nbsp;корзину</div>
                        <div class="prod-prev-favorite-mess prod-prev-mess">Товар добавлен в&nbsp;избранное</div>
                    </div>
                </div>
                <div class="prod-prev-bottom">
                    <div class="prod-prev-price">2 182.00 RUB</div>
                    <div class="prod-prev-params">
                        <div class="prod-prev-param">
                            <span>Радиус изгиба выпуклый</span>
                            <span>3000 мм</span>
                        </div>
                        <div class="prod-prev-param">
                            <span>Радиус изгиба вогнутый</span>
                            <span>2500 мм</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="prod-adh-block">
            <div class="prod-adhesive-title prod-related-title" data-type="adh-title">Клей для монтажа</div>
            <div class="prod-adh-slider prod-prev-slider" data-type="adh-slider">
                <div class="prod-prev" data-type="prod-prev" data-id="4783" data-name="карниз 1.50.100" data-code="ВР000001042" data-price="58.52" data-currency="BYN" data-cat="1542" data-cat-name="карнизы" data-iscomp="0">
                    <a href="/karnizy/1-50-100/"></a>
                    <div class="prod-prev-top">
                        <div class="prod-prev-title">
                            <span class="prod-prev-name">E01.M.290 / Клей Европласт Монтажный 290 мл.</span>
                            <span class="prod-prev-article"></span>
                        </div>
                        <div class="prod-prev-img">
                            <img src="https://evroplast.ru/upload/resize_cache/cron_responsive/catalog/data/images/100/287_287_1/E01.M.290.100.png" alt="карниз 1.50.100">
                        </div>
                        <div class="prod-prev-btns">
                            <div class="prod-prev-icons">
                                <i class="icon-favorite" data-type="favorite" data-user="no-user" title="Добавить в избранное"></i>
                                <i class="icon-cart" data-type="cart-add" title="Добавить в корзину"></i>
                            </div>
                            <div class="prod-prev-one-click" data-type="one-click">купить в&nbsp;1&nbsp;клик</div>
                            <div class="prod-prev-cart-mess prod-prev-mess">Товар добавлен в&nbsp;корзину</div>
                            <div class="prod-prev-favorite-mess prod-prev-mess">Товар добавлен в&nbsp;избранное</div>
                        </div>
                    </div>
                    <div class="prod-prev-bottom">
                        <div class="prod-prev-price">498.00 RUB</div>
                    </div>
                </div>
                <div class="prod-prev" data-type="prod-prev" data-id="4783" data-name="карниз 1.50.100" data-code="ВР000001042" data-price="58.52" data-currency="BYN" data-cat="1542" data-cat-name="карнизы" data-iscomp="0">
                    <a href="/karnizy/1-50-100/"></a>
                    <div class="prod-prev-top">
                        <div class="prod-prev-title">
                            <span class="prod-prev-name">Клей монтажный Европласт 290 мл.</span>
                            <span class="prod-prev-article"></span>
                        </div>
                        <div class="prod-prev-img">
                            <img src="https://evroplast.ru/upload/resize_cache/cron_responsive/catalog/data/images/100/287_287_1/EB05.M.290.100.png" alt="карниз 1.50.100">
                        </div>
                        <div class="prod-prev-btns">
                            <div class="prod-prev-icons">
                                <i class="icon-favorite" data-type="favorite" data-user="no-user" title="Добавить в избранное"></i>
                                <i class="icon-cart" data-type="cart-add" title="Добавить в корзину"></i>
                            </div>
                            <div class="prod-prev-one-click" data-type="one-click">купить в&nbsp;1&nbsp;клик</div>
                            <div class="prod-prev-cart-mess prod-prev-mess">Товар добавлен в&nbsp;корзину</div>
                            <div class="prod-prev-favorite-mess prod-prev-mess">Товар добавлен в&nbsp;избранное</div>
                        </div>
                    </div>
                    <div class="prod-prev-bottom">
                        <div class="prod-prev-price">1 299.00 RUB</div>
                    </div>
                </div>
                <div class="prod-prev" data-type="prod-prev" data-id="4783" data-name="карниз 1.50.100" data-code="ВР000001042" data-price="58.52" data-currency="BYN" data-cat="1542" data-cat-name="карнизы" data-iscomp="0">
                    <a href="/karnizy/1-50-100/"></a>
                    <div class="prod-prev-top">
                        <div class="prod-prev-title">
                            <span class="prod-prev-name">E13.S.60 / Клей Европласт стыковочный PU 60 мл.</span>
                            <span class="prod-prev-article"></span>
                        </div>
                        <div class="prod-prev-img">
                            <img src="https://evroplast.ru/upload/resize_cache/cron_responsive/catalog/data/images/100/287_287_1/E13.S.60.100.png" alt="карниз 1.50.100">
                        </div>
                        <div class="prod-prev-btns">
                            <div class="prod-prev-icons">
                                <i class="icon-favorite" data-type="favorite" data-user="no-user" title="Добавить в избранное"></i>
                                <i class="icon-cart" data-type="cart-add" title="Добавить в корзину"></i>
                            </div>
                            <div class="prod-prev-one-click" data-type="one-click">купить в&nbsp;1&nbsp;клик</div>
                            <div class="prod-prev-cart-mess prod-prev-mess">Товар добавлен в&nbsp;корзину</div>
                            <div class="prod-prev-favorite-mess prod-prev-mess">Товар добавлен в&nbsp;избранное</div>
                        </div>
                    </div>
                    <div class="prod-prev-bottom">
                        <div class="prod-prev-price">520.00 RUB</div>
                    </div>
                </div>
                <div class="prod-prev" data-type="prod-prev" data-id="4783" data-name="карниз 1.50.100" data-code="ВР000001042" data-price="58.52" data-currency="BYN" data-cat="1542" data-cat-name="карнизы" data-iscomp="0">
                    <a href="/karnizy/1-50-100/"></a>
                    <div class="prod-prev-top">
                        <div class="prod-prev-title">
                            <span class="prod-prev-name">E12.S.290 / Клей Европласт стыковочный PU 290 мл.</span>
                            <span class="prod-prev-article"></span>
                        </div>
                        <div class="prod-prev-img">
                            <img src="https://evroplast.ru/upload/resize_cache/cron_responsive/catalog/data/images/100/287_287_1/E12.S.290.100.png" alt="карниз 1.50.100">
                        </div>
                        <div class="prod-prev-btns">
                            <div class="prod-prev-icons">
                                <i class="icon-favorite" data-type="favorite" data-user="no-user" title="Добавить в избранное"></i>
                                <i class="icon-cart" data-type="cart-add" title="Добавить в корзину"></i>
                            </div>
                            <div class="prod-prev-one-click" data-type="one-click">купить в&nbsp;1&nbsp;клик</div>
                            <div class="prod-prev-cart-mess prod-prev-mess">Товар добавлен в&nbsp;корзину</div>
                            <div class="prod-prev-favorite-mess prod-prev-mess">Товар добавлен в&nbsp;избранное</div>
                        </div>
                    </div>
                    <div class="prod-prev-bottom">
                        <div class="prod-prev-price">1 399.00 RUB</div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="prod-similar">
        <div class="prod-similar-title prod-related-title">Схожие по стилю</div>
        <div class="prod-similar-slider prod-prev-slider" data-type="similar-slider">
            <div class="prod-prev prod-prev-h" data-type="prod-prev" data-id="4783" data-name="карниз 1.50.100" data-code="ВР000001042" data-price="58.52" data-currency="BYN" data-cat="1542" data-cat-name="карнизы" data-iscomp="0">
                <a href="/karnizy/1-50-100/"></a>
                <div class="prod-prev-top">
                    <div class="prod-prev-title">
                        <span class="prod-prev-name">карниз</span>
                        <span class="prod-prev-article">1.50.100</span>
                    </div>
                    <div class="prod-prev-img">
                        <img src="https://evroplast.ru/upload/resize_cache/cron_responsive/catalog/data/images/100/287_287_1/1.50.100.100.png" alt="карниз 1.50.100">
                    </div>
                    <div class="prod-prev-btns">
                        <div class="prod-prev-icons">
                            <i class="icon-favorite" data-type="favorite" data-user="no-user" title="Добавить в избранное"></i>
                            <i class="icon-cart" data-type="cart-add" title="Добавить в корзину"></i>
                        </div>
                        <div class="prod-prev-one-click" data-type="one-click">купить в&nbsp;1&nbsp;клик</div>
                        <div class="prod-prev-cart-mess prod-prev-mess">Товар добавлен в&nbsp;корзину</div>
                        <div class="prod-prev-favorite-mess prod-prev-mess">Товар добавлен в&nbsp;избранное</div>
                    </div>
                </div>
                <div class="prod-prev-bottom">
                    <div class="prod-prev-price">1 091.00 RUB</div>
                    <div class="prod-prev-params">
                        <div class="prod-prev-param">
                            <span>Длина детали</span>
                            <span>2000 мм</span>
                        </div>
                        <div class="prod-prev-param">
                            <span>Ширина по потолку</span>
                            <span>55 мм</span>
                        </div>
                        <div class="prod-prev-param">
                            <span>Высота по стене</span>
                            <span>64 мм</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="prod-prev prod-prev-h" data-type="prod-prev" data-id="4783" data-name="карниз 1.50.100" data-code="ВР000001042" data-price="58.52" data-currency="BYN" data-cat="1542" data-cat-name="карнизы" data-iscomp="0">
                <a href="/karnizy/1-50-100/"></a>
                <div class="prod-prev-top">
                    <div class="prod-prev-title">
                        <span class="prod-prev-name">карниз</span>
                        <span class="prod-prev-article">1.50.100</span>
                    </div>
                    <div class="prod-prev-img">
                        <img src="https://evroplast.ru/upload/resize_cache/cron_responsive/catalog/data/images/100/287_287_1/1.50.100.100.png" alt="карниз 1.50.100">
                    </div>
                    <div class="prod-prev-btns">
                        <div class="prod-prev-icons">
                            <i class="icon-favorite" data-type="favorite" data-user="no-user" title="Добавить в избранное"></i>
                            <i class="icon-cart" data-type="cart-add" title="Добавить в корзину"></i>
                        </div>
                        <div class="prod-prev-one-click" data-type="one-click">купить в&nbsp;1&nbsp;клик</div>
                        <div class="prod-prev-cart-mess prod-prev-mess">Товар добавлен в&nbsp;корзину</div>
                        <div class="prod-prev-favorite-mess prod-prev-mess">Товар добавлен в&nbsp;избранное</div>
                    </div>
                </div>
                <div class="prod-prev-bottom">
                    <div class="prod-prev-price">1 091.00 RUB</div>
                    <div class="prod-prev-params">
                        <div class="prod-prev-param">
                            <span>Длина детали</span>
                            <span>2000 мм</span>
                        </div>
                        <div class="prod-prev-param">
                            <span>Ширина по потолку</span>
                            <span>55 мм</span>
                        </div>
                        <div class="prod-prev-param">
                            <span>Высота по стене</span>
                            <span>64 мм</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="prod-prev prod-prev-h" data-type="prod-prev" data-id="4783" data-name="карниз 1.50.100" data-code="ВР000001042" data-price="58.52" data-currency="BYN" data-cat="1542" data-cat-name="карнизы" data-iscomp="0">
                <a href="/karnizy/1-50-100/"></a>
                <div class="prod-prev-top">
                    <div class="prod-prev-title">
                        <span class="prod-prev-name">карниз</span>
                        <span class="prod-prev-article">1.50.100</span>
                    </div>
                    <div class="prod-prev-img">
                        <img src="https://evroplast.ru/upload/resize_cache/cron_responsive/catalog/data/images/100/287_287_1/1.50.100.100.png" alt="карниз 1.50.100">
                    </div>
                    <div class="prod-prev-btns">
                        <div class="prod-prev-icons">
                            <i class="icon-favorite" data-type="favorite" data-user="no-user" title="Добавить в избранное"></i>
                            <i class="icon-cart" data-type="cart-add" title="Добавить в корзину"></i>
                        </div>
                        <div class="prod-prev-one-click" data-type="one-click">купить в&nbsp;1&nbsp;клик</div>
                        <div class="prod-prev-cart-mess prod-prev-mess">Товар добавлен в&nbsp;корзину</div>
                        <div class="prod-prev-favorite-mess prod-prev-mess">Товар добавлен в&nbsp;избранное</div>
                    </div>
                </div>
                <div class="prod-prev-bottom">
                    <div class="prod-prev-price">1 091.00 RUB</div>
                    <div class="prod-prev-params">
                        <div class="prod-prev-param">
                            <span>Длина детали</span>
                            <span>2000 мм</span>
                        </div>
                        <div class="prod-prev-param">
                            <span>Ширина по потолку</span>
                            <span>55 мм</span>
                        </div>
                        <div class="prod-prev-param">
                            <span>Высота по стене</span>
                            <span>64 мм</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="prod-prev prod-prev-h" data-type="prod-prev" data-id="4783" data-name="карниз 1.50.100" data-code="ВР000001042" data-price="58.52" data-currency="BYN" data-cat="1542" data-cat-name="карнизы" data-iscomp="0">
                <a href="/karnizy/1-50-100/"></a>
                <div class="prod-prev-top">
                    <div class="prod-prev-title">
                        <span class="prod-prev-name">карниз</span>
                        <span class="prod-prev-article">1.50.100</span>
                    </div>
                    <div class="prod-prev-img">
                        <img src="https://evroplast.ru/upload/resize_cache/cron_responsive/catalog/data/images/100/287_287_1/1.50.100.100.png" alt="карниз 1.50.100">
                    </div>
                    <div class="prod-prev-btns">
                        <div class="prod-prev-icons">
                            <i class="icon-favorite" data-type="favorite" data-user="no-user" title="Добавить в избранное"></i>
                            <i class="icon-cart" data-type="cart-add" title="Добавить в корзину"></i>
                        </div>
                        <div class="prod-prev-one-click" data-type="one-click">купить в&nbsp;1&nbsp;клик</div>
                        <div class="prod-prev-cart-mess prod-prev-mess">Товар добавлен в&nbsp;корзину</div>
                        <div class="prod-prev-favorite-mess prod-prev-mess">Товар добавлен в&nbsp;избранное</div>
                    </div>
                </div>
                <div class="prod-prev-bottom">
                    <div class="prod-prev-price">1 091.00 RUB</div>
                    <div class="prod-prev-params">
                        <div class="prod-prev-param">
                            <span>Длина детали</span>
                            <span>2000 мм</span>
                        </div>
                        <div class="prod-prev-param">
                            <span>Ширина по потолку</span>
                            <span>55 мм</span>
                        </div>
                        <div class="prod-prev-param">
                            <span>Высота по стене</span>
                            <span>64 мм</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <a href="/catalogue/" class="prod-back-cat">В каталог</a>
</div>

    <script src="/big/js/main.js"></script>
    <script src="/catalogue/catalogue.js?v=3"></script>

<? require($_SERVER["DOCUMENT_ROOT"] . "/include/footer.php");
if (defined("B_PROLOG_INCLUDED") && B_PROLOG_INCLUDED === true) {
    require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/epilog.php");
}