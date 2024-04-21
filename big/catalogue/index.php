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
<div class="content-wrapper catalogue">
    <div class="cat-top">
        <div class="cat-sections">
            <a href="/karnizy/" class="cat-sections-item active">
                Интерьерная лепнина
            </a>
            <a href="/antablementy/karnizi/" class="cat-sections-item">
                Фасадная лепнина
            </a>
            <a href="/adhesive/" class="cat-sections-item">
                Клей
            </a>
        </div>
        <div class="cat-filters-mob"><i class="icon-sort"></i>Применить фильтры</div>
    </div>

    <div class="cat-wrap">
        <div class="cat-filters">
            <div class="cat-filt-item active" data-type="filt-item">
                <div class="cat-filt-item-title" data-type="filt-title">Элементы лепнины <i class="icon-angle-down-2"></i></div>
                <div class="cat-filt-item-cont category" data-type="filt-cont">
                    <ul>
                        <li class="active"><a href="/karnizy">карнизы</a></li>
                        <li><a href="/moldingi">молдинги</a></li>
                        <li><a href="/plintusy">плинтусы</a></li>
                        <li><a href="/arkhitravy">архитравы</a></li>
                        <li><a href="/uglovye-jelementy">угловые элементы</a></li>
                        <li><a href="/rozetki">розетки</a></li>
                        <li><a href="/potolochnye-paneli">потолочные панели</a></li>
                        <li><a href="/piljastry">пилястры</a></li>
                        <li><a href="/kolonny">колонны</a></li>
                        <li><a href="/polukolonny">полуколонны</a></li>
                        <li><a href="/obramlenie-arok">обрамление арок</a></li>
                        <li><a href="/obramlenie-dverej">обрамление дверей</a></li>
                        <li><a href="/sandriki">сандрики</a></li>
                        <li><a href="/kaminy">декоративные камины</a>
                        <li><a href="/nishi">ниши</a></li>
                        <li><a href="/kessony-i-kupola">кессоны и купола</a></li>
                        <li><a href="/dekorativnii-paneli">декоративные панели</a></li>
                        <li><a href="/kronshtejny">кронштейны</a></li>
                        <li><a href="/ornamenty">орнаменты</a></li>
                        <li><a href="/sostavnye-elementy">составные элементы</a></li>
                        <li><a href="/elementy-kamina">элементы камина</a></li>
                        <li><a href="/arochnyy-element">арочный элемент</a></li>
                        <li><a href="/dopolnitelnye-elementy">дополнительные элементы</a></li>
                    </ul>
                </div>
            </div>
            <div class="cat-filters-wrap">
                <div class="cat-filt-item active" data-type="filt-item">
                    <div class="cat-filt-item-title" data-type="filt-title">Класс <i class="icon-angle-down-2"></i></div>
                    <div class="cat-filt-item-cont" data-type="filt-cont">
                        <ul>
                            <li class="active" data-type="filter-class"><a data-val="class_02">с орнаментом</a></li>
                            <li data-type="filter-class"><a data-val="class_03">гладкие</a></li>
                            <li data-type="filter-class"><a data-val="class_01">для скрытого освещения</a></li>
                            <li data-type="filter-class"><a data-val="class_20">для натяжного потолка</a></li>
                        </ul>
                    </div>
                </div>
                <div class="cat-filt-item active" data-type="filt-item">
                    <div class="cat-filt-item-title" data-type="filt-title">Стиль <i class="icon-angle-down-2"></i></div>
                    <div class="cat-filt-item-cont" data-type="filt-cont">
                        <ul>
                            <li class="active" data-type="filter-style"><a data-val="class_04">классика</a></li>
                            <li data-type="filter-style"><a data-val="class_05">барокко</a></li>
                            <li data-type="filter-style"><a data-val="class_06">неоклассика</a></li>
                            <li data-type="filter-style"><a data-val="class_07">ар деко</a></li>
                            <li data-type="filter-style"><a data-val="class_08">контемпорари</a></li>
                            <li data-type="filter-style"><a data-val="class_09">лофт</a></li>
                            <li data-type="filter-style"><a data-val="class_10">прованс</a></li>
                            <li data-type="filter-style"><a data-val="class_11">минимализм</a></li>
                        </ul>
                    </div>
                </div>
                <div class="cat-filt-item params active" data-type="filt-item">
                    <div class="cat-filt-item-title" data-type="filt-title">Ширина по потолку, мм <i class="icon-angle-down-2"></i></div>
                    <div class="cat-filt-item-cont" data-type="filt-cont">
                        <div class="category-filter-wrap">
                            <div class="category-filter"
                                 data-name="category-filter"
                                 data-type="S1"
                                 data-fmin="16"
                                 data-fmax="262"
                                 data-from="16" <?//значение from по фильтру, если нет - минимум?>
                                 data-to="262"><?//значение to по фильтру, если нет - максимум?>
                            </div>
                        </div>
                        <div class="category-filter-values">
                            <div class="category-filter-from">от <span data-type="from"></span></div>
                            <div class="category-filter-to">до <span data-type="to"></span></div>
                        </div>
                    </div>
                </div>
                <div class="cat-filt-item params active" data-type="filt-item">
                    <div class="cat-filt-item-title" data-type="filt-title">Высота по стене, мм <i class="icon-angle-down-2"></i></div>
                    <div class="cat-filt-item-cont" data-type="filt-cont">
                        <div class="category-filter-wrap">
                            <div class="category-filter"
                                 data-name="category-filter"
                                 data-type="S3"
                                 data-fmin="13"
                                 data-fmax="280"
                                 data-from="13" <?//значение from по фильтру, если нет - минимум?>
                                 data-to="280"><?//значение to по фильтру, если нет - максимум?>
                            </div>
                        </div>
                        <div class="category-filter-values">
                            <div class="category-filter-from">от <span data-type="from"></span></div>
                            <div class="category-filter-to">до <span data-type="to"></span></div>
                        </div>
                    </div>
                </div>
                <button class="cat-reset-filters">Сбросить все фильтры</button>
            </div>

        </div>
        <div class="cat-products">
            <div class="cat-sort">
                <div class="cat-sort-title" data-type="e-sort" data-main-param="1">Сортировать по</div>
                <div class="cat-sort-items">
                    <div class="cat-sort-item">
                        <div class="cat-sort-item-title" data-val="S1" data-type="sort-param" data-sort="asc">ширина по потолку</div>
                        <i class="cat-sort-arrow icon-arrow-up" data-type="sort-param" data-val="S1" data-sort="asc" title="по возрастанию"></i>
                        <i class="cat-sort-arrow icon-arrow-down" data-type="sort-param" data-val="S1" data-sort="desc" title="по убыванию"></i>
                    </div>
                    <div class="cat-sort-item">
                        <div class="cat-sort-item-title" data-val="S3" data-type="sort-param" data-sort="asc">высота по стене</div>
                        <i class="cat-sort-arrow icon-arrow-up" data-type="sort-param" data-val="S3" data-sort="asc" title="по возрастанию"></i>
                        <i class="cat-sort-arrow icon-arrow-down" data-type="sort-param" data-val="S3" data-sort="desc" title="по убыванию"></i>
                    </div>
                    <div class="cat-sort-item">
                        <div class="cat-sort-item-title" data-val="1" data-type="sort-param" data-sort="asc">цене</div>
                        <i class="cat-sort-arrow icon-arrow-up" data-type="sort-param" data-val="1" data-sort="asc" title="по возрастанию"></i>
                        <i class="cat-sort-arrow icon-arrow-down" data-type="sort-param" data-val="2" data-sort="desc" title="по убыванию"></i>
                    </div>
                    <div class="cat-sort-item active">
                        <div class="cat-sort-item-title" data-val="4" data-type="sort-param" data-sort="asc">артикулу</div>
                        <i class="cat-sort-arrow icon-arrow-up active" data-type="sort-param" data-val="3" data-sort="asc" title="по возрастанию"></i>
                        <i class="cat-sort-arrow icon-arrow-down" data-type="sort-param" data-val="4" data-sort="desc" title="по убыванию"></i>
                    </div>
                </div>
            </div>
            <div class="cat-items" data-type="items-list" id="1542" data-val="catalogue">
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
                                <i class="icon-favorite active" data-type="favorite" data-user="no-user" title="Добавить в избранное"></i>
                                <i class="icon-cart active" data-type="cart-add" title="Добавить в корзину"></i>
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
                <div class="prod-prev prod-prev-v" data-type="prod-prev" data-id="4783" data-name="карниз 1.50.100" data-code="ВР000001042" data-price="58.52" data-currency="BYN" data-cat="1542" data-cat-name="карнизы" data-iscomp="0">
                    <a href="/karnizy/1-50-100/"></a>
                    <div class="prod-prev-top">
                        <div class="prod-prev-title">
                            <span class="prod-prev-name">колонна</span>
                            <span class="prod-prev-article"></span>
                        </div>
                        <div class="prod-prev-img">
                            <img src="https://evroplast.ru/upload/resize_cache/cron/catalog/data/images/10/287_287_1/1.30.105.10.png" alt="карниз 1.50.100">
                        </div>
                        <div class="prod-prev-btns">
                            <div class="prod-prev-icons">
                                <i class="icon-favorite" data-type="favorite" data-user="no-user" title="Добавить в избранное"></i>
                                <i class="icon-cart" data-type="cart-add" title="Добавить в корзину"></i>
                            </div>
                            <div class="prod-prev-one-click" data-type="one-click">купить в&nbsp;1&nbsp;клик</div>
                        </div>
                    </div>
                    <div class="prod-prev-bottom">
                        <div class="prod-prev-price">30 959.00 RUB</div>
                        <div class="prod-prev-params">
                            <div class="prod-prev-param">
                                <span>Ширина</span>
                                <span>318 мм</span>
                            </div>
                            <div class="prod-prev-param">
                                <span>Высота</span>
                                <span>2692 мм</span>
                            </div>
                            <div class="prod-prev-param">
                                <span>Глубина</span>
                                <span>318 мм</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="prod-prev" data-type="prod-prev" data-id="4783" data-name="карниз 1.50.100" data-code="ВР000001042" data-price="58.52" data-currency="BYN" data-cat="1542" data-cat-name="карнизы" data-iscomp="0">
                    <a href="/karnizy/1-50-100/"></a>
                    <div class="prod-prev-top">
                        <div class="prod-prev-title">
                            <span class="prod-prev-name">угловой элемент</span>
                            <span class="prod-prev-article">1.52.279</span>
                        </div>
                        <div class="prod-prev-img">
                            <img src="https://evroplast.ru/upload/resize_cache/cron_responsive/catalog/data/images/100/287_287_1/1.52.279.100.png" alt="карниз 1.50.100">
                        </div>
                        <div class="prod-prev-btns">
                            <div class="prod-prev-icons">
                                <i class="icon-favorite" data-type="favorite" data-user="no-user" title="Добавить в избранное"></i>
                                <i class="icon-cart" data-type="cart-add" title="Добавить в корзину"></i>
                            </div>
                            <div class="prod-prev-one-click" data-type="one-click">купить в&nbsp;1&nbsp;клик</div>
                        </div>
                    </div>
                    <div class="prod-prev-bottom">
                        <div class="prod-prev-price">956.00 RUB</div>
                        <div class="prod-prev-params">
                            <div class="prod-prev-param">
                                <span>Ширина</span>
                                <span>358 мм</span>
                            </div>
                            <div class="prod-prev-param">
                                <span>Высота</span>
                                <span>323 мм</span>
                            </div>
                            <div class="prod-prev-param">
                                <span>Толщина</span>
                                <span>22 мм</span>
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
            <div class="pagination">
                <div class="pag-title">Смотреть ещё</div>
                <div class="pag-wrap">
                    <div class="pag" data-items="1000" data-onpage="15" data-type="pag" data-current="1"></div>
                    <div class="show-wait">
                        <img src="/img/preloader.gif" alt="loading">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
    <script src="/big/js/main.js"></script>
<script src="/catalogue/catalogue.js?v=3"></script>
<? require($_SERVER["DOCUMENT_ROOT"] . "/include/footer.php");
if (defined("B_PROLOG_INCLUDED") && B_PROLOG_INCLUDED === true) {
    require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/epilog.php");
}