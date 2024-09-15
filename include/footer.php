    </div><!-- end of content-->
    <?/*<a class="zchbLink" href="https://zachestnyibiznes.ru/company/ul/1067760097822_7707609512_OOO-DEKOR?w=1" target="_blank" rel="nofollow">
        <div class="zchbWidget-wrap">
            <div class="zchbWidgetIcon2">
                <div class="zchbHead">ЗА</div>
                <div class="zchbLogoText">ЧЕСТНЫЙ БИЗНЕС</div>
            </div>
        </div>
    </a>*/
    ?>
    <footer>
        <div class="content-wrapper">
            <div class="logo-column">
                <a href="/">
                    <i class="icon-perfom-logo"></i>
                </a>
            </div>
            <ul class="footer-menu">
                <li>
                    <span>Компания</span>
                    <ul>
                        <?$active_arr = array_diff(explode('/',$active), array(''));?>
                        <li><a href="/company/brand/" <? if(strpos($active, 'brand')) echo 'class="active"'?>>Наш бренд</a></li>
                        <li><a href="/company/" <? if(strpos($active, 'company') && count($active_arr) == 1) echo 'class="active"'?>>Компания</a></li>
                        <li><a href="/company/tech/" <? if(strpos($active, 'tech')) echo 'class="active"'?>>Технологии</a></li>
                        <!--<li><a href="/company/certificates/" <? if(strpos($active, 'cert')) echo 'class="active"'?>>Сертификаты</a></li>-->
                        <!--<li><a href="/company/local_acts/" <? if(strpos($active, 'local_acts')) echo 'class="active"'?>>Локальные акты</a></li>-->
                    </ul>
                </li>
                <li>
                    <span>Каталог</span>
                    <ul>
                        <li><a href="/karnizy/"<?if($main_section == 'interernyj-dekor') echo ' class="active"'?>>Интерьер</a></li>
                        <!--<li><a href="/antablementy/karnizi/"<?if($main_section == 'fasadnyj-dekor') echo ' class="active"'?>>Фасад</a></li>-->
                        <li><a href="/adhesive/"<?if(strpos($_SERVER['REQUEST_URI'], 'adhesive')||strpos($_SERVER['REQUEST_URI'], 'klei-90')) echo ' class="active"'?>>Клей</a></li>
                        <li><a href="/collection/"<?if(strpos($_SERVER['REQUEST_URI'], 'collection')) echo ' class="active"'?>>Коллекции</a></li>
                    </ul>
                </li>
                <li>
                    <span>Разделы сайта</span>
                    <ul>
                        <li><a href="/designer/" <? if(strpos($active, 'designer')) echo'class="active"'?>>Дизайнерам</a></li>
                        <li><a href="/professional/" <? if(strpos($active, 'professional')&&!strpos($tmp, 'question_service')) echo'class="active"'?>>Строителям</a></li>
                        <li><a href="<?= MEDIA_FOLDER ?>/" <? if(strpos($active, 'mag')||strpos($active, 'news')||strpos($active, 'media')) echo 'class="active"'?>><?= MEDIA_NAME ?></a></li>
                        <li><a href="/install/" <? if(strpos($active, 'install')) echo 'class="active"'?>>Монтаж</a></li>
                        <li><a href="/gallery/" <? if(strpos($active, 'gallery')) echo 'class="active"'?>>Проекты</a></li>

                        <li><a href="/download/" <? if(strpos($active, 'download')) echo 'class="active"'?>>загрузки</a></li>
                        <li><a href="/factory/">Производство</a></li>
                        <li><a href="/wheretobuy/" <? if(strpos($active, 'wheretobuy')) echo 'class="active"'?>>где&nbsp;купить</a></li>
						<li><a href="/contact/" <? if(strpos($active, 'contact')) echo'class="active"'?>>Контакты</a></li>
                    </ul>
                </li>
                <?if($my_city == '3109') { ?>
                    <li>
                        <span>Интернет&#8209;магазин</span>
                        <ul>
                            <li><a href="/online-store/checkout/" <? if(strpos($active, 'checkout')) echo'class="active"'?>>Оформление заказа</a></li>
                            <li><a href="/online-store/payment/" <? if(strpos($active, 'payment')) echo'class="active"'?>>Оплата</a></li>
                            <li><a href="/online-store/delivery/" <? if(strpos($active, 'delivery')) echo'class="active"'?>>Доставка</a></li>
                            <li><a href="/online-store/return/" <? if(strpos($active, 'return')) echo'class="active"'?>>Условия возврата</a></li>
                            <li><a href="/online-store/service/" <? if(strpos($active, 'service')) echo'class="active"'?>>Сервис</a></li>
                            <li><a href="/online-store/details/" <? if(strpos($active, 'details')) echo'class="active"'?>>Реквизиты</a></li>
                        </ul>
                    </li>
                <? } ?>
                <li>
                    <span>Информация</span>
                    <ul>
                        <li><a href="/publishers-imprint/" <? if(strpos($active, 'publishers-imprint')) echo'class="active"'?>>Выходные данные</a></li>
                        <li><a href="/disclaimer/" <? if(strpos($active, 'disclaimer')) echo'class="active"'?>>Дисклеймер</a></li>
                        <li><a href="/company/policies/#personal_data" <? if(strpos($active, 'personal_data')) echo'class="active"'?>>Обработка данных</a></li>
                        <li><a href="/company/policies/" <? if(strpos($active, 'policies')) echo'class="active"'?>>Конфиденциальность</a></li>
                    </ul>
                </li>
            </ul>
            <div class="footer-contacts">
                <?if($phone) { ?>
                    <a class="footer-phone" href="tel:<?=$link_phone?>"><?=$phone?></a>
                <? } ?>
                <div class="footer-social">
                    <a href="/personal/" target="_blank" rel="nofollow"><i class="icon-footer-personal"></i></a>
                    <? /*
                    <a href="https://t.me/evroplastru" target="_blank" rel="nofollow"><i class="icon-t"></i></a>
                    <a href="https://www.youtube.com/channel/UCq8KPRl92_QkNw-vVhwHytA" target="_blank" rel="nofollow"><i class="icon-y"></i></a>
                    <a href="https://vk.com/evroplast" target="_blank" rel="nofollow"><i class="icon-v"></i></a>
                    <a href="#" target="_blank" rel="nofollow"><i class="icon-p"></i></a>
                    */ ?>
                </div>
                <div class="footer-banks">
                    <i class="icon-mir"></i>
                    <i class="icon-visa"></i>
                    <i class="icon-mastercard"></i>
                    <i class="icon-sbp"></i>
                </div>
            </div>
            <? /*
            <div class="footer-copyright">&#169; ООО «Декор» <?=date('Y')?></div>
            */ ?>
            <div class="footer-made"><a href="https://kuznets.agency/" target="_blank" rel="nofollow">разработан в Kuznets.agency</a></div>
        </div>
    </footer>
</div><!-- end of wrapper-->

<!-- Schema Organization -->
<div itemscope itemtype="http://schema.org/Organization" style="display: none;">
  <span itemprop="name">ООО «Перфом»</span>
  Контакты:
  <div itemprop="address" itemscope itemtype="http://schema.org/PostalAddress">
    Адрес:
    <span itemprop="streetAddress">1-й Дорожный проезд, д. 6, стр. 4</span>
    <span itemprop="postalCode"> 117545</span>
    <span itemprop="addressLocality">Москва, Россия</span>,
  </div>
  Телефон:<span itemprop="telephone">+7 495 315 30 40</span>,
  Факс:<span itemprop="faxNumber">+7 495 315 30 40</span>,
  Электронная почта: <span itemprop="email">decor@decor-evroplast.ru</span>
</div>
<!-- // -->

<? require($_SERVER["DOCUMENT_ROOT"] . "/include/end.php");