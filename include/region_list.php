<?
$db_list_geo = CIBlockElement::GetList(Array('SORT' => 'ASC'), Array('IBLOCK_ID' => 9, 'ACTIVE' => 'Y'));
$i = 0;
$res_geo = array();
while ($country = $db_list_geo->GetNextElement()) {
$country = array_merge($country->GetFields(), $country->GetProperties());
$res_geo[$i]['country']['name'] = $country['NAME'];
$res_geo[$i]['country']['code'] = $country['CODE'];

$n = 0;

$db_city_list = CIBlockElement::GetList(Array('NAME' => 'ASC'), Array('IBLOCK_ID' => 7, 'ACTIVE' => 'Y', 'PROPERTY_country' => $country['ID']));

while ($city = $db_city_list->GetNextElement()) {
$city = array_merge($city->GetFields(), $city->GetProperties());
$res_geo[$i]['city'][$n]['name'] = $city['NAME'];
$res_geo[$i]['city'][$n]['id'] = $city['ID'];
$n++;
}
$i++;
}
//print_r($res_geo);
$c = 0;
?>
<!--noindex-->
<div id="dropdown-down">
    <div class="choose-reg-list" data-type="reg-list">
        <div class="content-wrapper">
            <div class="header-geo-choose-val" data-type="header-geo-choose">
                Текущий регион: <span data-value="<?=$loc['NAME']?>" data-type="curr-reg"><?=$loc['NAME']?></span>
            </div>
            <div class="reg-list-scroll" data-type="reg-list-scroll">

                <?/* desktop */?>
                <div class="e-reg-list-wrap desc">
                    <? foreach($res_geo as $arr) { ?>
                    <? if ($arr['country']['code'] == 'rossiya') {
                        //print_r($arr['country']['code']);
                        $qnt = count($arr['city']);
                        ?>
                        <div class="e-reg-list-column">
                        <div class="e-choose-country-name"><?=$arr['country']['name']?></div>
                        <?foreach($arr['city'] as $city) {
                            if( $city['id'] == 3109 && $my_city != 3109 && !$USER->IsAuthorized()
                                || $city['id'] == 3109 && $my_city != 3109 && $USER->IsAuthorized() && in_array('5',$user_groups) ) continue;
                            if($c == 33) {?>
                                </div>
                                <div class="e-reg-list-column e-reg-list-column-pad">
                                <?$c++;?>
                            <? } ?>
                            <? if($c % 34 == 0 && $c != 0 && $c != 33) {?>
                                </div>
                                <div class="e-reg-list-column e-reg-list-column-pad">
                            <? } ?>
                            <a class="e-choose-reg-name" data-value="<?=$city['id']?>" data-type="choose-reg"><?=$city['name']?></a>
                            <?
                            $c++;
                        } ?>
                        </div>
                    <?} else {
                    if ($arr['country']['code'] == 'respublika-abkhaziya') { ?>
                    <div class="e-reg-list-column column-diff-country">
                        <? } elseif ($arr['country']['code'] == 'georgian'){ ?>
                    </div>
                    <div class="e-reg-list-column column-diff-country">
                        <? } ?>
                        <div class="e-choose-country-name"><?= $arr['country']['name'] ?></div>
                        <? foreach ($arr['city'] as $city) { ?>
                            <a class="e-choose-reg-name" data-value="<?= $city['id'] ?>" data-type="choose-reg"><?= $city['name'] ?></a>
                        <? } ?>
                        <br>
                        <? }
                        }?>
                    </div>
                </div>

                <?/* mobile */?>
                <?
                //отсортируем массив по алфавиту
                $new_res_geo = Array();
                foreach($res_geo as $arr) {
                    if($arr['country']['code'] == 'rossiya') {
                        $rus_arr[] = $arr;
                        continue;
                    }
                    $new_res_geo[$arr['country']['name']] = $arr;
                }
                ksort($new_res_geo);
                $res_geo = array_merge($rus_arr,$new_res_geo);
                //print_r($res_geo);
                ?>
                <div class="e-reg-list-wrap-mob">
                    <? foreach($res_geo as $arr) { ?>
                        <div class="reg-list-country" data-type="reg-count-wrap">
                            <div class="reg-list-country-name" data-type="reg-count">
                                <?=$arr['country']['name']?>
                                <i class="icomoon icon-angle-down"></i>
                            </div>
                            <div class="reg-list-city" data-type="reg-city">
                                <div class="reg-list-city-column">
                                    <? foreach($arr['city'] as $city) {
                                    if( $city['id'] == 3109 && $my_city != 3109 && !$USER->IsAuthorized()
                                        || $city['id'] == 3109 && $my_city != 3109 && $USER->IsAuthorized() && in_array('5',$user_groups) ) continue;?>
                                    <a class="e-choose-reg-name" data-value="<?= $city['id'] ?>" data-type="choose-reg"><?= $city['name'] ?></a>
                                    <? } ?>
                                </div>
                            </div>
                        </div>
                    <? } ?>
                </div>
            </div>
        </div>
    </div>
</div>
<!--/noindex-->