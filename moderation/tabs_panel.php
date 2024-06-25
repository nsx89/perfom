<?if($USER->IsAuthorized() && in_array($user_stat,array('mod','admin'))) { ?>
            <section class="pacc-choose-reg-wrap">
                <div class="e-new-choose-reg-list" data-type="filt-reg-list">
                  <div class="e-new-header-geo-choose-val">
                    Текущий регион: <span data-value="<?=$filr_reg?>" data-type="curr-reg"><?=$filr_reg?></span>
                  </div>
                  <i class="new-icomoon icon-close geo-close" data-type="filt-geo-close"></i>
                  <div class="e-new-reg-list-wrap">
                      <?require($_SERVER["DOCUMENT_ROOT"] . "/include/top-current-location.php");?>
                  </div>
                </div>
              </section>
        <? } ?>

<?if($USER->IsAuthorized() && in_array($user_stat,array('mod','admin'))) { ?>
          <div class="tab-collection">
            <a href="#etc1" data-ed="tabs-switcher" data-target="etc1">Интернет-магазин</a>
            <a href="#etc3" data-ed="tabs-switcher" data-target="etc3">Отчёты</a>
            <a href="#etc2" data-ed="tabs-switcher" data-target="etc2">Регистрация <br>на мероприятия</a>
            <a href="/order_managment" target="_blank">Администрирование заказов</a>
            <?if($USER->IsAuthorized() && in_array($user_stat_dealer,array('admin','moddealer','specdealer','userdealer'))) { ?>
                <a href="#etc4" data-ed="tabs-switcher" data-target="etc4">Контрагенты</a>
            <? } ?>
          </div>
        <? } ?>
