<?
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog.php");
if (!CModule::IncludeModule('iblock')) exit;
$APPLICATION->SetTitle("Оформление заказа");
$APPLICATION->SetPageProperty("description", "Европласт - производство полиуретановых изделий, лидер на российском рынке");
require($_SERVER["DOCUMENT_ROOT"] . "/include/header.php");
?>
    <div class="main-banner company-banner">
        <div class="main-slide-caption white">
            Оформление <br>заказа
        </div>
        <img src="/img/online-store/order_placement.jpg" alt="Оформление заказа">
    </div>
    <section class="dealers-main-txt online-store-txt checkout-txt">
        <div class="content-wrapper">
                <h1 class="e-pol-title">Оформление заказа</h1>
                <div class="terms-point">
                    <div class="terms-point-number">1.</div>
                    <div class="terms-point-cont">Выберете нужный раздел каталога с&nbsp;товарами, далее выберите нужный товар/ы, укажите количество и нажмите кнопку <span>«Добавить в&nbsp;корзину»</span>.
                    </div>
                </div>
                <div class="terms-point">
                    <div class="terms-point-number">2.</div>
                    <div class="terms-point-cont">После завершения подбора товаров, для&nbsp;оформления заказа перейдите в&nbsp;корзину нажав соответствующую кнопку в&nbsp;правом верхнем углу&nbsp;экрана.
                    </div>
                </div>
                <div class="terms-point">
                    <div class="terms-point-number">3.</div>
                    <div class="terms-point-cont">В корзине вы можете отредактировать количество необходимого товара. В&nbsp;разделе <span>«Монтажные материалы»</span> по&nbsp;нажатию кнопки <span>«Рассчитать количество»</span> система автоматически рассчитает необходимое количество <span>клея для&nbsp;монтажа</span>.
                    </div>
                </div>
                <div class="terms-point">
                    <div class="terms-point-number">4.</div>
                    <div class="terms-point-cont">
                        В разделе <span>«Где и&nbsp;как вы хотите получить заказ»</span> выберете один из&nbsp;предложенных вариантов получения:
                        <div class="terms-point-pictures">
                            <div class="left-pic">
                                <div class="wrap">
                                    <div class="cart-form-rbtn active">Доставка</div>
                                    <div class="terms-option-desc">При выборе этой опции, Вам станут доступны условия
                                        доставки и&nbsp;тарифы. Укажите наименование города, улицы, номер дома и&nbsp;удаленность
                                        от&nbsp;МКАД при&nbsp;необходимости.
                                    </div>
                                    <div class="terms-option-desc-add">
                                        *Стоимость доставки рассчитывается автоматически исходя из&nbsp;стоимости заказа и&nbsp;удаленности от&nbsp;МКАД.
                                    </div>
                                </div>

                            </div>
                            <div class="right-pic">
                                <div class="wrap">
                                    <div class="cart-form-rbtn active">Самовывоз</div>
                                    <div class="terms-option-desc">
                                        При выборе этой опции, Вам будет предложено выбрать подходящий адрес магазина для&nbsp;осуществления самовывоза.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="terms-point">
                    <div class="terms-point-number">5.</div>
                    <div class="terms-point-cont">
                        В разделе <span>«Как вам будет удобнее оплатить заказ»</span> выберете один из&nbsp;доступных
                        способов оплаты&nbsp;заказа:
                        <div class="terms-point-pictures">
                            <div class="left-pic">
                                <div class="wrap">
                                    <div class="cart-form-rbtn active">При получении</div>
                                    <div class="terms-option-desc">
                                        При выборе этой опции доступны способы оплаты наличными или&nbsp;картой.
                                    </div>
                                </div>
                            </div>
                            <div class="right-pic">
                                <div class="wrap">
                                    <div class="cart-form-rbtn active">Онлайн</div>
                                    <div class="terms-option-desc">
                                        При выборе этой опции, вы&nbsp;сможете оплатить заказ на&nbsp;сайте после внесения контактных данных.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="terms-point">
                    <div class="terms-point-number">6.</div>
                    <div class="terms-point-cont">
                        После выбора всех опций вы можете оформить заказ нажав кнопку <span>«Оформить заказ»</span> или&nbsp;сохранить заказ в&nbsp;PDF нажав на&nbsp;соответствующий значок. При&nbsp;нажатии кнопки <span>«Оформить заказ»</span> в&nbsp;открывшемся окне Вам необходимо заполнить следующие&nbsp;поля:
                        <div class="terms-point-pictures">
                            <div class="wrap">
                                <div class="terms-point-pictures-title"><span>6.1.</span>Персональные данные:</div>
                                <img src="/img/online-store/personal-data.png" alt="Персональные данные">
                                <div class="terms-point-pictures-title"><span>6.2.</span><p>В поле <span>«Комментарий»</span> Вы&nbsp;можете оставить любое сообщение. Например, о&nbsp;лице, принимающим товар, или&nbsp;для&nbsp;въезда необходимо заказать&nbsp;пропуск:</p></div>
                                <img src="/img/online-store/comment.png" alt="Комментарий">
                                <div class="terms-point-pictures-title"><span>6.3.</span>Дать согласие на&nbsp;обработку персональных данных путем установки&nbsp;флажка:</div>
                                <img src="/img/online-store/agreement.png" alt="Согласие" style="margin-bottom:0;">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="terms-point">
                    <div class="terms-point-number">7.</div>
                    <div class="terms-point-cont">Для отправки заказа и&nbsp;перехода на&nbsp;страницу онлайн оплаты нажмите кнопку <span>«Оформить заказ»</span>.
                    </div>
                </div>
                <div class="terms-point">
                    <div class="terms-point-number">8.</div>
                    <div class="terms-point-cont">После отправки заказа, его обрабатывает менеджер, после чего связывается с&nbsp;Вами для&nbsp;уточнения параметров заказа. Обо&nbsp;всех изменениях в&nbsp;заказе Вы&nbsp;получаете письмо на&nbsp;указанный Вами&nbsp;e&#8209;mail.
                    </div>
                </div>
        </div>
    </section>
<?
require($_SERVER["DOCUMENT_ROOT"] . "/include/footer.php");
if(defined("B_PROLOG_INCLUDED") && B_PROLOG_INCLUDED===true)
{
    require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog.php");
}
