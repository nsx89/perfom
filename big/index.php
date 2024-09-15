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
<div class="main-slider-wrap">
    <div class="main-slider-preloader"><img src="/img/preloader.gif" alt="Подождите..."></div>
    <div class="main-slider" data-type="main-slider">
        <div class="main-slide">
            <div class="main-slide-caption">стиль, <br>отточенный <br>временем</div>
            <img src="/img/main-slider/01.jpg" alt="стиль, отточенный временем">
        </div>
        <div class="main-slide">
            <div class="main-slide-caption">для&nbsp;красоты <br>стен и&nbsp;потолков</div>
            <img src="/img/main-slider/02.jpg" alt="для красоты стен и потолков">
        </div>
        <div class="main-slide">
            <div class="main-slide-caption">элегантность <br>в&nbsp;каждой детали</div>
            <img src="/img/main-slider/03.jpg" alt="элегантность в каждой детали">
        </div>
        <div class="main-slide">
            <div class="main-slide-caption">для воплощения <br>ваших идей</div>
            <img src="/img/main-slider/05.jpg" alt="для воплощения ваших идей">
        </div>
        <div class="main-slide">
            <div class="main-slide-caption">классика <br>в современном <br>дизайне</div>
            <img src="/img/main-slider/06.jpg" alt="классика в современном дизайне">
        </div>
    </div>
</div>

<section class="main-pref">
    <div class="content-wrapper">
        <div class="main-pref-txt">
            <h2>Европласт</h2>
            <p class="main-pref-annotation">
                Европласт&nbsp;— бренд лепнины и <br>архитектурного декора
                из пенополиуретана. <br>На&nbsp;протяжении многих лет марка&nbsp;—
                cиноним <br>высокого качества и оригинального дизайна.
            </p>
            <p>
                На сегодняшний день лепнина и архитектурный декор под<br>
                брендом "Европласт" установлены во многих квартирах Росcии.
            </p>
            <p>
                Приобрести лепнину «Европласт» можно в розничных точках<br>
                в каждом городе России, СНГ и стран Балтии. Также купить<br>
                декор из пенополиуретана можно в интернет&#8209;магазине.
            </p>
        </div>
        <div class="main-pref-slider" data-type="main-pref-slider">
            <div class="main-pref-slide">
                <i class="icon-brilliant"></i>
                повышенная <br>прочность
            </div>
            <div class="main-pref-slide">
                <i class="icon-thumb"></i>
                простой <br>монтаж
            </div>
            <div class="main-pref-slide">
                <i class="icon-snowflake"></i>
                выдающаяся <br>белизна
            </div>
            <div class="main-pref-slide">
                <i class="icon-paint"></i>
                красится <br>в любой цвет
            </div>
            <div class="main-pref-slide">
                <i class="icon-umbrella"></i>
                уникальная <br>влагостойкость
            </div>
            <div class="main-pref-slide">
                <i class="icon-pattern"></i>
                самый четкий <br>рисунок
            </div>
        </div>

    </div>
</section>

<section class="main-gallery">
    <div class="content-wrapper">
        <div class="main-gallery-title-block">
            <h2 class="main-blocks-title">Сделайте интерьер лучше</h2>
            <a href="/karnizy/" class="main-blocks-link"><span>Перейти к&nbsp;каталогу</span> <i class="icon-long-arrow"></i></a>
        </div>
        <div class="main-gallery-slider" data-type="main-gallery-slider">
            <div class="main-gallery-slide main-gallery-slide-1 main-gallery-slide-vertical">
                <div class="main-gallery-item-vertical-row">
                    <div class="main-gallery-item show-materials">
                        <a href="#"></a>
                        <img src="/img/gallery/7.png?v=1" alt="Проект 7">
                        <div class="show-materials-item" style="left:0.7%;top:16%;" data-type="product-item" data-id="4825" data-name="карниз 1.50.153" data-code="ВР001021966" data-price="3748.00" data-currency="RUB" data-cat="1542" data-cat-name="карнизы" data-iscomp="0">
                            <div class="show-materials-point"></div>
                            <div class="show-materials-popup">
                                <div class="show-materials-popup-img">
                                    <a href="#"></a>
                                    <img src="/img/gallery/1.50.153.20.png" alt="1.50.153">
                                </div>
                                <div class="show-materials-popup-title">
                                    <a href="#">карниз 1.50.153</a>
                                </div>
                                <div class="show-materials-popup-bottom">
                                    <div class="show-materials-popup-price">3 799 руб.</div>
                                    <div class="show-materials-popup-btns">
                                        <div class="show-materials-popup-favorite" data-type="favorite" data-user="no-user">
                                            <i class="icon-star"></i>
                                        </div>
                                        <div class="show-materials-popup-add" data-type="cart-add">
                                            <i class="icon-plus"></i>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="show-materials-item" style="left:16%;top:15%;" data-type="product-item" data-id="4825" data-name="карниз 1.50.153" data-code="ВР001021966" data-price="3748.00" data-currency="RUB" data-cat="1542" data-cat-name="карнизы" data-iscomp="0">
                            <div class="show-materials-point"></div>
                            <div class="show-materials-popup">
                                <div class="show-materials-popup-img">
                                    <a href="#"></a>
                                    <img src="/img/gallery/1.50.153.20.png" alt="1.50.153">
                                </div>
                                <div class="show-materials-popup-title">
                                    <a href="#">карниз 1.50.153</a>
                                </div>
                                <div class="show-materials-popup-bottom">
                                    <div class="show-materials-popup-price">3 799 руб.</div>
                                    <div class="show-materials-popup-btns">
                                        <div class="show-materials-popup-favorite" data-type="favorite" data-user="no-user">
                                            <i class="icon-star"></i>
                                        </div>
                                        <div class="show-materials-popup-add" data-type="cart-add">
                                            <i class="icon-plus"></i>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="show-materials-item" style="left:61%;top:10%;" data-type="product-item" data-id="4825" data-name="карниз 1.50.153" data-code="ВР001021966" data-price="3748.00" data-currency="RUB" data-cat="1542" data-cat-name="карнизы" data-iscomp="0">
                            <div class="show-materials-point"></div>
                            <div class="show-materials-popup">
                                <div class="show-materials-popup-img">
                                    <a href="#"></a>
                                    <img src="/img/gallery/1.50.153.20.png" alt="1.50.153">
                                </div>
                                <div class="show-materials-popup-title">
                                    <a href="#">карниз 1.50.153</a>
                                </div>
                                <div class="show-materials-popup-bottom">
                                    <div class="show-materials-popup-price">3 799 руб.</div>
                                    <div class="show-materials-popup-btns">
                                        <div class="show-materials-popup-favorite" data-type="favorite" data-user="no-user">
                                            <i class="icon-star"></i>
                                        </div>
                                        <div class="show-materials-popup-add" data-type="cart-add">
                                            <i class="icon-plus"></i>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="show-materials-item" style="left:18%;top:51%;" data-type="product-item" data-id="4825" data-name="карниз 1.50.153" data-code="ВР001021966" data-price="3748.00" data-currency="RUB" data-cat="1542" data-cat-name="карнизы" data-iscomp="0">
                            <div class="show-materials-point"></div>
                            <div class="show-materials-popup">
                                <div class="show-materials-popup-img">
                                    <a href="#"></a>
                                    <img src="/img/gallery/1.50.153.20.png" alt="1.50.153">
                                </div>
                                <div class="show-materials-popup-title">
                                    <a href="#">карниз 1.50.153</a>
                                </div>
                                <div class="show-materials-popup-bottom">
                                    <div class="show-materials-popup-price">3 799 руб.</div>
                                    <div class="show-materials-popup-btns">
                                        <div class="show-materials-popup-favorite" data-type="favorite" data-user="no-user">
                                            <i class="icon-star"></i>
                                        </div>
                                        <div class="show-materials-popup-add" data-type="cart-add">
                                            <i class="icon-plus"></i>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="show-materials-item" style="left:41%;top:47%;" data-type="product-item" data-id="4825" data-name="карниз 1.50.153" data-code="ВР001021966" data-price="3748.00" data-currency="RUB" data-cat="1542" data-cat-name="карнизы" data-iscomp="0">
                            <div class="show-materials-point"></div>
                            <div class="show-materials-popup">
                                <div class="show-materials-popup-img">
                                    <a href="#"></a>
                                    <img src="/img/gallery/1.50.153.20.png" alt="1.50.153">
                                </div>
                                <div class="show-materials-popup-title">
                                    <a href="#">карниз 1.50.153</a>
                                </div>
                                <div class="show-materials-popup-bottom">
                                    <div class="show-materials-popup-price">3 799 руб.</div>
                                    <div class="show-materials-popup-btns">
                                        <div class="show-materials-popup-favorite" data-type="favorite" data-user="no-user">
                                            <i class="icon-star"></i>
                                        </div>
                                        <div class="show-materials-popup-add" data-type="cart-add">
                                            <i class="icon-plus"></i>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="show-materials-item" style="left:59%;top:56%;" data-type="product-item" data-id="4825" data-name="карниз 1.50.153" data-code="ВР001021966" data-price="3748.00" data-currency="RUB" data-cat="1542" data-cat-name="карнизы" data-iscomp="0">
                            <div class="show-materials-point"></div>
                            <div class="show-materials-popup">
                                <div class="show-materials-popup-img">
                                    <a href="#"></a>
                                    <img src="/img/gallery/1.50.153.20.png" alt="1.50.153">
                                </div>
                                <div class="show-materials-popup-title">
                                    <a href="#">карниз 1.50.153</a>
                                </div>
                                <div class="show-materials-popup-bottom">
                                    <div class="show-materials-popup-price">3 799 руб.</div>
                                    <div class="show-materials-popup-btns">
                                        <div class="show-materials-popup-favorite" data-type="favorite" data-user="no-user">
                                            <i class="icon-star"></i>
                                        </div>
                                        <div class="show-materials-popup-add" data-type="cart-add">
                                            <i class="icon-plus"></i>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="show-materials-item" style="left:87%;top:63%;" data-type="product-item" data-id="4825" data-name="карниз 1.50.153" data-code="ВР001021966" data-price="3748.00" data-currency="RUB" data-cat="1542" data-cat-name="карнизы" data-iscomp="0">
                            <div class="show-materials-point"></div>
                            <div class="show-materials-popup">
                                <div class="show-materials-popup-img">
                                    <a href="#"></a>
                                    <img src="/img/gallery/1.50.153.20.png" alt="1.50.153">
                                </div>
                                <div class="show-materials-popup-title">
                                    <a href="#">карниз 1.50.153</a>
                                </div>
                                <div class="show-materials-popup-bottom">
                                    <div class="show-materials-popup-price">3 799 руб.</div>
                                    <div class="show-materials-popup-btns">
                                        <div class="show-materials-popup-favorite" data-type="favorite" data-user="no-user">
                                            <i class="icon-star"></i>
                                        </div>
                                        <div class="show-materials-popup-add" data-type="cart-add">
                                            <i class="icon-plus"></i>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="main-gallery-item resize-bg" style="background-image: url('/img/gallery/2.png?v=2')">
                        <a href="#"></a>
                        <img src="/img/gallery/gallery-bg-2.png?v=1" alt="Проект 2">
                    </div>
                </div>
                <div class="main-gallery-item resize-bg vertical-centered" style="background-image: url('/img/gallery/3.png?v=1')">
                    <a href="#"></a>
                    <img src="/img/gallery/gallery-bg-3.png?v=1" alt="Проект 3">
                </div>
            </div>
            <div class="main-gallery-slide main-gallery-slide-2 main-gallery-slide-horizontal">
                <div class="main-gallery-item show-materials">
                    <a href="#"></a>
                    <img src="/img/gallery/4.png?v=1" alt="Проект 4">
                    <div class="show-materials-item" style="left:64%;top:52%;" data-type="product-item" data-id="4825" data-name="карниз 1.50.153" data-code="ВР001021966" data-price="3748.00" data-currency="RUB" data-cat="1542" data-cat-name="карнизы" data-iscomp="0">
                        <div class="show-materials-point"></div>
                        <div class="show-materials-popup">
                            <div class="show-materials-popup-img">
                                <a href="#"></a>
                                <img src="/img/gallery/1.50.153.20.png" alt="1.50.153">
                            </div>
                            <div class="show-materials-popup-title">
                                <a href="#">карниз 1.50.153</a>
                            </div>
                            <div class="show-materials-popup-bottom">
                                <div class="show-materials-popup-price">3 799 руб.</div>
                                <div class="show-materials-popup-btns">
                                    <div class="show-materials-popup-favorite" data-type="favorite" data-user="no-user">
                                        <i class="icon-star"></i>
                                    </div>
                                    <div class="show-materials-popup-add" data-type="cart-add">
                                        <i class="icon-plus"></i>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="show-materials-item" style="left:59%;top:86%;" data-type="product-item" data-id="4825" data-name="карниз 1.50.153" data-code="ВР001021966" data-price="3748.00" data-currency="RUB" data-cat="1542" data-cat-name="карнизы" data-iscomp="0">
                        <div class="show-materials-point"></div>
                        <div class="show-materials-popup">
                            <div class="show-materials-popup-img">
                                <a href="#"></a>
                                <img src="/img/gallery/1.50.153.20.png" alt="1.50.153">
                            </div>
                            <div class="show-materials-popup-title">
                                <a href="#">карниз 1.50.153</a>
                            </div>
                            <div class="show-materials-popup-bottom">
                                <div class="show-materials-popup-price">3 799 руб.</div>
                                <div class="show-materials-popup-btns">
                                    <div class="show-materials-popup-favorite" data-type="favorite" data-user="no-user">
                                        <i class="icon-star"></i>
                                    </div>
                                    <div class="show-materials-popup-add" data-type="cart-add">
                                        <i class="icon-plus"></i>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="main-gallery-item-horizontal-row">
                    <div class="main-gallery-item resize-bg" style="background-image: url('/img/gallery/5.png?v=1')">
                        <a href="#"></a>
                        <img src="/img/gallery/gallery-bg-2.png?v=1" alt="Проект 5">
                    </div>
                    <div class="main-gallery-item show-materials">
                        <a href="#"></a>
                        <img src="/img/gallery/6.png?v=1" alt="Проект 6">
                        <div class="show-materials-item" style="left:43%;top:8%;" data-type="product-item" data-id="4825" data-name="карниз 1.50.153" data-code="ВР001021966" data-price="3748.00" data-currency="RUB" data-cat="1542" data-cat-name="карнизы" data-iscomp="0">
                            <div class="show-materials-point"></div>
                            <div class="show-materials-popup">
                                <div class="show-materials-popup-img">
                                    <a href="#"></a>
                                    <img src="/img/gallery/1.50.153.20.png" alt="1.50.153">
                                </div>
                                <div class="show-materials-popup-title">
                                    <a href="#">карниз 1.50.153</a>
                                </div>
                                <div class="show-materials-popup-bottom">
                                    <div class="show-materials-popup-price">3 799 руб.</div>
                                    <div class="show-materials-popup-btns">
                                        <div class="show-materials-popup-favorite" data-type="favorite" data-user="no-user">
                                            <i class="icon-star"></i>
                                        </div>
                                        <div class="show-materials-popup-add" data-type="cart-add">
                                            <i class="icon-plus"></i>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="show-materials-item" style="left:83%;top:33%;" data-type="product-item" data-id="4825" data-name="карниз 1.50.153" data-code="ВР001021966" data-price="3748.00" data-currency="RUB" data-cat="1542" data-cat-name="карнизы" data-iscomp="0">
                            <div class="show-materials-point"></div>
                            <div class="show-materials-popup">
                                <div class="show-materials-popup-img">
                                    <a href="#"></a>
                                    <img src="/img/gallery/1.50.153.20.png" alt="1.50.153">
                                </div>
                                <div class="show-materials-popup-title">
                                    <a href="#">карниз 1.50.153</a>
                                </div>
                                <div class="show-materials-popup-bottom">
                                    <div class="show-materials-popup-price">3 799 руб.</div>
                                    <div class="show-materials-popup-btns">
                                        <div class="show-materials-popup-favorite" data-type="favorite" data-user="no-user">
                                            <i class="icon-star"></i>
                                        </div>
                                        <div class="show-materials-popup-add" data-type="cart-add">
                                            <i class="icon-plus"></i>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="main-gallery-slide main-gallery-slide-3 main-gallery-slide-vertical">
                <div class="main-gallery-item-vertical-row">
                    <div class="main-gallery-item resize-bg vertical-centered" style="background-image: url('/img/gallery/1.png?v=2')">
                        <a href="#"></a>
                        <img src="/img/gallery/gallery-bg-1.png?v=1" alt="Проект 1">
                    </div>
                    <div class="main-gallery-item show-materials">
                        <a href="#"></a>
                        <img src="/img/gallery/8.png?v=1" alt="Проект 8">
                        <div class="show-materials-item" style="left:47%;top:28%;" data-type="product-item" data-id="4825" data-name="карниз 1.50.153" data-code="ВР001021966" data-price="3748.00" data-currency="RUB" data-cat="1542" data-cat-name="карнизы" data-iscomp="0">
                            <div class="show-materials-point"></div>
                            <div class="show-materials-popup">
                                <div class="show-materials-popup-img">
                                    <a href="#"></a>
                                    <img src="/img/gallery/1.50.153.20.png" alt="1.50.153">
                                </div>
                                <div class="show-materials-popup-title">
                                    <a href="#">карниз 1.50.153</a>
                                </div>
                                <div class="show-materials-popup-bottom">
                                    <div class="show-materials-popup-price">3 799 руб.</div>
                                    <div class="show-materials-popup-btns">
                                        <div class="show-materials-popup-favorite" data-type="favorite" data-user="no-user">
                                            <i class="icon-star"></i>
                                        </div>
                                        <div class="show-materials-popup-add" data-type="cart-add">
                                            <i class="icon-plus"></i>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="show-materials-item" style="left:58%;top:43%;" data-type="product-item" data-id="4825" data-name="карниз 1.50.153" data-code="ВР001021966" data-price="3748.00" data-currency="RUB" data-cat="1542" data-cat-name="карнизы" data-iscomp="0">
                            <div class="show-materials-point"></div>
                            <div class="show-materials-popup">
                                <div class="show-materials-popup-img">
                                    <a href="#"></a>
                                    <img src="/img/gallery/1.50.153.20.png" alt="1.50.153">
                                </div>
                                <div class="show-materials-popup-title">
                                    <a href="#">карниз 1.50.153</a>
                                </div>
                                <div class="show-materials-popup-bottom">
                                    <div class="show-materials-popup-price">3 799 руб.</div>
                                    <div class="show-materials-popup-btns">
                                        <div class="show-materials-popup-favorite" data-type="favorite" data-user="no-user">
                                            <i class="icon-star"></i>
                                        </div>
                                        <div class="show-materials-popup-add" data-type="cart-add">
                                            <i class="icon-plus"></i>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="main-gallery-item resize-bg vertical-centered" style="background-image: url('/img/gallery/9.png?v=1')">
                    <a href="#"></a>
                    <img src="/img/gallery/gallery-bg-3.png?v=1" alt="Проект 3">
                </div>
            </div>
        </div>
    </div>
</section>

<div class="main-events-catalogues main-section">
    <div class="content-wrapper">
        <section class="main-events">
            <h2 class="main-blocks-title">события</h2>
            <div class="main-events-wrap">
                <div class="main-events-slider" data-type="main-events-slider">
                    <div class="main-events-slide">
                        <article>
                            <a href="#"></a>
                            <span>16 февраля</span>
                            <img src="/img/event-1.png" alt="ТВ-проект. «Фазенда Лайф». Амстердам">
                            <h2>ТВ-проект. «Фазенда Лайф». Амстердам</h2>
                        </article>
                    </div>
                    <div class="main-events-slide">
                        <article>
                            <a href="#"></a>
                            <span>19 февраля</span>
                            <img src="/img/event-2.png" alt="ТВ-проект. «Фазенда Лайф». Амстердам">
                            <h2>ТВ-проект. «Фазенда Лайф». Амстердам</h2>
                        </article>
                    </div>
                </div>
                <a href="#" class="main-events-catalogues-link"><span>Смотреть еще</span></a>
            </div>
        </section>

        <section class="main-catalogues">
            <div>
                <h2 class="main-blocks-title">каталоги</h2>
                <a href="/download/" class="main-catalogues-link main-blocks-link"><span>Смотреть все&nbsp;каталоги</span> <i class="icon-long-arrow"></i></a>
            </div>
            <div class="main-catalogue-slider" data-type="main-catalogue-slider">
                <div class="main-catalogue-slide">
                    <article>
                        <img src="/img/interior.png" alt="Интерьерный каталог">
                        <div class="article-txt">
                            <h2>Интерьерный каталог</h2>
                            <p>Карнизы ручной работы <br>– для вас</p>
                            <a href="#" class="main-events-catalogues-link main-catalogue-link-1">Скачать</a>
                        </div>
                    </article>
                </div>
                <div class="main-catalogue-slide">
                    <article>
                        <img src="/img/interior.png" alt="Каталог Мавритания">
                        <div class="article-txt">
                            <h2>Mauritania</h2>
                            <p>Карнизы ручной работы <br>– для вас</p>
                            <a href="#" class="main-events-catalogues-link main-catalogue-link-2">Скачать</a>
                        </div>
                    </article>
                </div>
                <div class="main-catalogue-slide">
                    <article>
                        <img src="/img/lines.png" alt="Каталог Lines">
                        <div class="article-txt">
                            <h2>Lines</h2>
                            <p>Карнизы ручной работы <br>– для вас</p>
                            <a href="#" class="main-events-catalogues-link main-catalogue-link-3">Скачать</a>
                        </div>
                    </article>
                </div>
            </div>
        </section>
    </div>
</div>

<section class="main-news main-section">
    <div class="content-wrapper">
        <h2 class="main-blocks-title">новости</h2>
        <a href="/mag/#all" class="main-blocks-link"><span>Смотреть все&nbsp;новости</span> <i class="icon-long-arrow"></i></a>
        <div class="main-news-slider" data-type="main-news-slider">
            <div class="main-news-slide main-news-slide-1">
                <article>
                    <a href="#"></a>
                    <img src="/img/news-1.png" alt="Презентация новой линейки широких молдингов">
                    <h3>Презентация новой линейки широких молдингов</h3>
                    <span>15.10.2018</span>
                </article>
            </div>
            <div class="main-news-slide main-news-slide-2">
                <article>
                    <a href="#"></a>
                    <img src="/img/news-2.png" alt="Лекция Виктора Дембовского в Хабаровске">
                    <h3>Лекция Виктора Дембовского в Хабаровске</h3>
                    <span>25.05.2022</span>
                </article>
            </div>
            <div class="main-news-slide main-news-slide-3">
                <article>
                    <a href="#"></a>
                    <img src="/img/news-3.png" alt="Лекция Виктора Дембовского в Благовещенске">
                    <h3>Лекция Виктора Дембовского в Благовещенске</h3>
                    <span>20.05.2022</span>
                </article>
            </div>
            <div class="main-news-slide main-news-slide-4">
                <article>
                    <a href="#"></a>
                    <img src="/img/news-4.png" alt="Презентация новой линейки широких молдингов">
                    <h3>Презентация новой линейки широких молдингов</h3>
                    <span>15.10.2018</span>
                </article>
            </div>
        </div>
    </div>
</section>

<section class="main-articles main-section">
        <div class="content-wrapper">
            <h2 class="main-blocks-title">Статьи</h2>
            <a href="/mag/#styles" class="main-blocks-link"><span>Смотреть все&nbsp;статьи</span> <i class="icon-long-arrow"></i></a>
            <div class="main-articles-slider" data-type="main-articles-slider">
                <div class="main-articles-slide main-articles-slide-1">
                    <article>
                        <a href="#"></a>
                        <img src="/img/Ecostyle.jpg" alt="Экостиль в интерьере: единение с природой">
                        <div>
                            <h3>Экостиль в&nbsp;интерьере: единение с&nbsp;природой</h3>
                            <p>Все больше людей задумываются об&nbsp;экологии и&nbsp;осознанном потреблении. И&nbsp;это&nbsp;уже не&nbsp;дань моде, а необходимость сохранить природные ресурсы.</p>
                            <span>Подробнее</span>
                        </div>
                    </article>
                </div>
                <div class="main-articles-slide main-articles-slide-2">
                    <article style="background-image: url('/img/Blagoveshensk.jpg')">
                        <a href="#"></a>
                        <img src="/img/article-2-base.png" alt="Лекция Виктора Дембовского в Благовещенске">
                        <div>
                            <h3>Лекция Виктора&nbsp;Дембовского в&nbsp;Благовещенске</h3>
                            <p>Все больше людей задумываются об&nbsp;экологии и&nbsp;осознанном потреблении. И&nbsp;это&nbsp;уже не&nbsp;дань моде, а необходимость сохранить природные ресурсы.</p>
                            <span>Подробнее</span>
                        </div>
                    </article>
                    <article style="background-image: url('/img/Ufa.jpg')">
                        <a href="#"></a>
                        <img src="/img/article-3-base.png" alt="Форум дизайнеров и архитекторов в Уфе">
                        <div>
                            <h3>Форум дизайнеров и&nbsp;архитекторов в&nbsp;Уфе</h3>
                            <p>Все больше людей задумываются об&nbsp;экологии и&nbsp;осознанном потреблении. И&nbsp;это&nbsp;уже не&nbsp;дань моде, а необходимость сохранить природные ресурсы.</p>
                            <span>Подробнее</span>
                        </div>
                    </article>
                </div>
                <div class="main-articles-slide main-articles-slide-3">
                    <article style="background-image: url('/img/Ufa.jpg')">
                        <a href="#"></a>
                        <img src="/img/article-3-base.png" alt="Форум дизайнеров и архитекторов в Уфе">
                        <div>
                            <h3>Форум дизайнеров и&nbsp;архитекторов в&nbsp;Уфе</h3>
                            <p>Все больше людей задумываются об&nbsp;экологии и&nbsp;осознанном потреблении. И&nbsp;это&nbsp;уже не&nbsp;дань моде, а необходимость сохранить природные ресурсы.</p>
                            <span>Подробнее</span>
                        </div>
                    </article>
                </div>
                <div class="main-articles-slide main-articles-slide-4">
                    <article style="background-image: url('/img/Khabarovsk.jpg')">
                        <a href="#"></a>
                        <img class="article-base-vert" src="/img/article-4-base.png" alt="Лекция Виктора Дембовского в Хабаровске">
                        <img class="article-base-hor" src="/img/article-1-base.png" alt="Лекция Виктора Дембовского в Хабаровске">
                        <div>
                            <h3>Лекция Виктора&nbsp;Дембовского в&nbsp;Хабаровске</h3>
                            <p>Все больше людей задумываются об&nbsp;экологии и&nbsp;осознанном потреблении. И&nbsp;это&nbsp;уже не&nbsp;дань моде, а необходимость сохранить природные ресурсы.</p>
                            <span>Подробнее</span>
                        </div>
                    </article>
                </div>
            </div>
        </div>
    </section>
    <script src="/big/js/main.js"></script>

<?
require($_SERVER["DOCUMENT_ROOT"] . "/include/footer.php");
if(defined("B_PROLOG_INCLUDED") && B_PROLOG_INCLUDED===true) {
    require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog.php");
}