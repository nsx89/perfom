<div class="md-content<?if(in_array($user_stat_dealer,array('userdealer'))) echo ' userdealer'?>">
    <?/** НА КАРТЕ */?>
    <div class="md-content-map" data-val="cont" data-type="map">
        <div class="map-wrap">

            <div class="map-panel">
                <div class="map-panel-wrap">
                    <div class="scrollbar">
                        <div class="track">
                            <div class="thumb">
                                <div class="end"></div>
                            </div>
                        </div>
                    </div>
                    <div class="viewport">
                        <div class="overview" id="overview" data-type="dealer-list">
                        </div>
                    </div>
                </div>
            </div>
            <script src="https://api-maps.yandex.ru/2.1/?apikey=0e24f952-da4e-4266-9ab4-66b2b263e914&lang=ru_RU" type="text/javascript"></script>
            <div id="mdMap"></div>
            <div class="map-size" data-type="map-size"><i class="icon-expand"></i></div>
        </div>

        <div class="map-slider" data-type="map-slider"></div>

    </div>

<?/** СПИСКОМ */?>
<div class="md-content-list" data-val="cont" data-type="list">
    <div class="orders-list-table-wrapper">
        <table class="md-list-table">
            <thead>
            <tr>
                <th class="md-list-table-name">Наименование</th>
                <?/*<th class="md-list-table-reg-dealer">Сделать дилером региона</th>*/?>
                <th class="md-list-table-url">Web-сайт</th>
                <th class="md-list-table-addr">Адрес и контакты</th>
                <th class="md-list-table-type">Тип точки</th>
                <th class="md-list-table-time">Время работы</th>
            </tr>
            </thead>
            <tbody data-type="contacts-table-cont"></tbody>
        </table>
    </div>
</div>

<?/** МОДЕРАЦИЯ */?>
<?if(in_array($user_stat_dealer,array('specdealer'))) { ?>
<div class="md-content-moder" data-val="cont" data-type="moder-spec">
    <div class="orders-list-table-wrapper">
        <table class="md-list-table">
            <thead>
            <tr>
                <th class="md-list-table-name">Наименование</th>
                <th class="md-list-table-act" style="text-align:center">Действие</th>
                <th class="md-list-table-url">Web-сайт</th>
                <th class="md-list-table-addr">Адрес и контакты</th>
                <th class="md-list-table-type">Тип точки</th>
                <th class="md-list-table-time">Время работы</th>
            </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
</div>
<?  } ?>

<?/** ПРЕЛОАДЕР */?>
<div class="md-content-wait" data-type="wait-panel">
    <img src="/img/preloader.gif" alt="Wait...">
</div>

<?/** НА МОДЕРАЦИИ */?>
    <?if(in_array($user_stat_dealer,array('mod','admin','moddealer'))) { ?>
<div class="md-content-moder" data-val="cont" data-type="moder">
    <? if($mod_qty > 0) { ?>
    <div class="orders-list-table-wrapper">
        <table class="md-list-table">
        <thead>
        <tr>
            <th class="md-list-table-name">Наименование</th>
            <th class="md-list-table-act">Действие и&nbsp;пользователь</th>
            <th class="md-list-table-url">Web-сайт</th>
            <th class="md-list-table-addr">Адрес и контакты</th>
            <th class="md-list-table-type">Тип точки</th>
            <th class="md-list-table-time">Время работы</th>
        </tr>
        </thead>
        <tbody>
        <?
        $i = 1;
        while($mod_ob = $mod_res->GetNextElement()) {
        $mod = array_merge($mod_ob->GetFields(), $mod_ob->GetProperties());
        //print_r($mod)?>
        <tr>
            <td>
                <div class="md-list-name">
                    <div class="md-list-name-top">
                        <span><?=$i++?>.</span>
                        <? if($mod['trade_point']['~VALUE']!='') {
                        echo $mod['trade_point']['~VALUE'].'<br>';
                        } ?>
                                    <?=$mod['organization']['~VALUE']?>
                    </div>
                    <div class="md-list-name-bottom">
                        <div class="dealer-status mod">На модерации</div>
                    </div>
                </div>
            </td>
            <td>
                <?
                $rsUser = CUser::GetByID($mod['dealer_id']['VALUE']);
                $arUser = $rsUser->Fetch();
                $user_name  = $arUser['NAME'] != '' ? $arUser['NAME'] : $arUser['LOGIN'];
                ?>
                            <?
                switch ($mod['mod_act']['VALUE']) {
                case 'new':
                $act = 'Создание';
                break;
                case 'rem':
                $act = 'Удаление';
                break;
                default:
                $act = "Изменение";
                }
                echo $act.'<br>';
                echo '<span class="mod-date">'.ConvertTimeStamp($mod['DATE_CREATE_UNIX'], "SHORT").'</span>';
                echo '<span class="mod-name">'.$user_name.'</span>';
                ?>
            </td>
            <td>
                <?if($mod['href']['~VALUE'] != '') {?>
                <a href="<?='http://'.$mod['href']['~VALUE']?>" target="_blank"><?=$mod['href']['~VALUE']?></a>
                <? } ?>
            </td>
            <?
            $arFilterCity = Array("IBLOCK_ID"=>7, "ID"=>$mod['city']['VALUE'], 'ACTIVE'=>'Y');
            $resCity = CIBlockElement::GetList(Array(), $arFilterCity, false, Array(), Array("NAME"));
            while($obCity = $resCity->GetNextElement()) {
            $itemCity = array_merge($arFields = $obCity->GetFields(),$arFields = $obCity->GetProperties());
            }?>
            <td>
                <?if($mod['address']['~VALUE'] != '') {?>
                <p class="dealer-list-addr">
                    <?if($itemCity) {
                    echo 'г. '.$itemCity['NAME'].'<br>';
                    } ?>
                                    <?=$mod['address']['~VALUE']?>
                </p>
                <? } ?>
                            <?if($mod['phones']['~VALUE'] != '') {?>
                <p class="dealer-list-addr"><?=$mod['phones']['~VALUE']?></p>
                <? } ?>
                            <?if($mod['email']['~VALUE'] != '') {?>
                <p class="dealer-list-addr"><?=$mod['email']['~VALUE']?></p>
                <? } ?>
            </td>
            <?
            //print_r($mod);
            $point_type = " - ";
            if($mod['point_type']['~VALUE'] == 'retail') $point_type = "Собственная розница";
            if($mod['point_type']['~VALUE'] == 'subdealer') {
            $point_type = "Субдилерская сеть";
            if($mod['contractor']['~VALUE'] != '') {
            $point_type .= '<div class="table-subdealer"><span>Главный дилер:</span><br>';
            $point_type .= $mod['contractor']['~VALUE'].'</div>';
            }
            }
            ?>
            <td><?=$point_type?></td>
            <td>
                <div class="md-list-time">
                    <div class="md-list-time-top">
                        <div class="md-list-time-item">
                            <?if($mod['workday']['~VALUE'] != '') {?>
                            <div class="md-list-time-item-hours"><?=$mod['workday']['~VALUE']?></div>
                            <div class="md-list-time-item-day">Будни</div>
                            <? } ?>
                        </div>
                        <div class="md-list-time-item">
                            <?if($mod['saturday']['~VALUE'] != '') {?>
                            <div class="md-list-time-item-hours"><?=$mod['saturday']['~VALUE']?></div>
                            <div class="md-list-time-item-day">Суббота</div>
                            <? } ?>
                        </div>
                        <div class="md-list-time-item">
                            <?if($mod['sunday']['~VALUE'] != '') {?>
                            <div class="md-list-time-item-hours"><?=$mod['sunday']['~VALUE']?></div>
                            <div class="md-list-time-item-day">Воскресенье</div>
                            <? } ?>
                        </div>
                        <div class="md-list-time-item">
                            <?if($mod['weekend']['~VALUE'] != '') {?>
                            <div class="md-list-time-item-hours"><?=$mod['weekend']['~VALUE']?></div>
                            <div class="md-list-time-item-day">Доп. выходные</div>
                            <? } ?>
                        </div>
                        <?if($mod['without']['VALUE'] != '') {?>
                            <div class="md-list-time-item">
                                <div class="md-list-time-item-hours"></div>
                                <div class="md-list-time-item-day">Без выходных</div>
                            </div>
                        <? } ?>
                    </div>
                    <div class="md-list-time-bottom">
                        <a href="/moderation/?id=<?=$mod['ID']?>&type=mod#etc4" class="dealer-item-see" data-id="<?=$mod['ID']?>">Перейти <i class="new-icomoon icon-Angle-right"></i></a>
                    </div>
                </div>
            </td>
        </tr>


        <? } ?>
        </tbody>
    </table>
    </div>
    <? } else { ?>
        <div class="md-data-not-found">
            <p>Данные не найдены.</p>
        </div>
    <?  } ?>
</div>
<?  } ?>



    <?/** СОХРАНЕНО */?>
    <? if(in_array($user_stat_dealer,array('specdealer'))) { ?>
<div class="md-content-moder" data-val="cont" data-type="saved">
    <?if($saved_qty > 0) {?>
    <div class="orders-list-table-wrapper">
        <table class="md-list-table">
        <thead>
        <tr>
            <th class="md-list-table-name">Наименование</th>
            <th class="md-list-table-url">Web-сайт</th>
            <th class="md-list-table-addr">Адрес и контакты</th>
            <th class="md-list-table-type">Тип точки</th>
            <th class="md-list-table-time">Время работы</th>
        </tr>
        </thead>
        <tbody>
        <?
        $i = 1;
        while($saved_ob = $saved_res->GetNextElement()) {
        $saved = array_merge($saved_ob->GetFields(), $saved_ob->GetProperties());
        //print_r($mod)?>
        <tr>
            <td>
                <div class="md-list-name">
                    <div class="md-list-name-top">
                        <span><?=$i++?>.</span>
                        <? if($saved['trade_point']['~VALUE']!='') {
                        echo $saved['trade_point']['~VALUE'].'<br>';
                        } ?>
                                        <?=$saved['organization']['~VALUE']?>
                    </div>
                    <div class="md-list-name-bottom">
                        <div class="dealer-status saved">Промежуточное сохранение</div>
                    </div>
                </div>
            </td>
            <td>
                <?if($saved['href']['~VALUE'] != '') {?>
                <a href="<?='http://'.$saved['href']['~VALUE']?>" target="_blank"><?=$saved['href']['~VALUE']?></a>
                <? } ?>
            </td>
            <?
            $arFilterCity = Array("IBLOCK_ID"=>7, "ID"=>$saved['city']['VALUE'], 'ACTIVE'=>'Y');
            $resCity = CIBlockElement::GetList(Array(), $arFilterCity, false, Array(), Array("NAME"));
            while($obCity = $resCity->GetNextElement()) {
            $itemCity = array_merge($arFields = $obCity->GetFields(),$arFields = $obCity->GetProperties());
            }?>
            <td>
                <?if($saved['address']['~VALUE'] != '') {?>
                <p class="dealer-list-addr">
                    <? if($itemCity) {
                    echo 'г. '.$itemCity['NAME'].'<br>';
                    } ?>
                                        <?=$saved['address']['~VALUE']?>
                </p>
                <? } ?>
                                <?if($saved['phones']['~VALUE'] != '') {?>
                <p class="dealer-list-addr"><?=$saved['phones']['~VALUE']?></p>
                <? } ?>
                                <?if($saved['email']['~VALUE'] != '') {?>
                <p class="dealer-list-addr"><?=$saved['email']['~VALUE']?></p>
                <? } ?>
            </td>
            <?
            $point_type = " - ";
            if($saved['point_type']['~VALUE'] == 'retail') $point_type = "Собственная розница";
            if($saved['point_type']['~VALUE'] == 'subdealer') {
            $point_type = "Субдилерская сеть";
            if($saved['contractor']['~VALUE'] != '') {
            if($mod['contractor']['~VALUE'] != '') {
            $point_type .= '<div class="table-subdealer"><span>Главный дилер:</span><br>';
            $point_type .= $mod['contractor']['~VALUE'].'</div>';
            }
            }
            }
            ?>
            <td><?=$point_type?></td>
            <td>
                <div class="md-list-time">
                    <div class="md-list-time-top">
                        <div class="md-list-time-item">
                            <?if($saved['workday']['~VALUE'] != '') {?>
                            <div class="md-list-time-item-hours"><?=$saved['workday']['~VALUE']?></div>
                            <div class="md-list-time-item-day">Будни</div>
                            <? } ?>
                        </div>
                        <div class="md-list-time-item">
                            <?if($saved['saturday']['~VALUE'] != '') {?>
                            <div class="md-list-time-item-hours"><?=$saved['saturday']['~VALUE']?></div>
                            <div class="md-list-time-item-day">Суббота</div>
                            <? } ?>
                        </div>
                        <div class="md-list-time-item">
                            <?if($saved['sunday']['~VALUE'] != '') {?>
                            <div class="md-list-time-item-hours"><?=$saved['sunday']['~VALUE']?></div>
                            <div class="md-list-time-item-day">Воскресенье</div>
                            <? } ?>
                        </div>
                        <div class="md-list-time-item">
                            <?if($saved['weekend']['~VALUE'] != '') {?>
                            <div class="md-list-time-item-hours"><?=$saved['weekend']['~VALUE']?></div>
                            <div class="md-list-time-item-day">Доп. выходные</div>
                            <? } ?>
                        </div>
                        <?if($saved['without']['VALUE'] != '') {?>
                            <div class="md-list-time-item">
                                <div class="md-list-time-item-hours"></div>
                                <div class="md-list-time-item-day">Без выходных</div>
                            </div>
                        <? } ?>
                    </div>
                    <div class="md-list-time-bottom">
                        <a href="/moderation/?id=<?=$saved['ID']?>&type=saved#etc4" class="dealer-item-see" data-id="<?=$saved['ID']?>">Перейти <i class="new-icomoon icon-Angle-right"></i></a>
                    </div>
                </div>
            </td>
        </tr>
        <? } ?>
        </tbody>
    </table>
    </div>
    <? } else { ?>
        <div class="md-data-not-found">
            <p>Данные не найдены.</p>
        </div>
    <?  } ?>
</div>
<?  } ?>
</div>