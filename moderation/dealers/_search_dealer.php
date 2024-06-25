<?
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog.php");
if (!CModule::IncludeModule('iblock') || !CModule::IncludeModule("catalog") || !CModule::IncludeModule("search")) {
    exit;
}
require_once($_SERVER["DOCUMENT_ROOT"] . "/include/phone.php");
$str_query = mb_convert_case($_REQUEST['q'], MB_CASE_LOWER, "UTF-8");
$str_query=preg_replace('/(\S+)/', '"\\1"',$str_query);
$obSearch = new CSearch;
$obSearch->Search(array('QUERY' => $str_query, 'SITE_ID' => LANG, 'MODULE_ID' => 'iblock', 'PARAM1'=>'dealers', 'PARAM2'=>6), array("NAME"=>"ASC"));
$res = array();

$result = [];
$i = 1;

function highlight_found($str,$str_query) {
    $str = mb_convert_encoding($str, "UTF-8");
    $res = str_ireplace($str_query, "<b>".$str_query."</b>", $str);
    return $res;
}

$id_arr = Array();

while ($arResult = $obSearch->GetNext()) {

    if ($arResult['PARAM2'] == 6) {
        $id_arr[] = $arResult['ITEM_ID'];
    }
}

ob_start();

if(empty($id_arr)) { ?>
    <div class="md-search-no-results">Ничего не найдено.</div>
<? } else {
    $reg = $_REQUEST['reg'];
    $reg_dealer = Array();
    if($reg) {
        $arFilter = Array("IBLOCK_ID"=>7, "ID"=>$reg, 'ACTIVE'=>'Y');
        $res = CIBlockElement::GetList(Array("sort"=>'ASC'), $arFilter, false, Array(), Array());
        while($ob = $res->GetNextElement()) {
            $city = array_merge($ob->GetFields(), $ob->GetProperties());
        }
        if($city) {
            if($city['reg_dealers']['VALUE'] != '') {
                $reg_dealer = $city['reg_dealers']['VALUE'];
            }
        }
    }
    $res = CIBlockElement::GetList(Array(), Array('ID'=>$id_arr), false, Array(), Array());
    if(intval($res->SelectedRowsCount()) > 0) { ?>
        <div class="md-search-total">Всего найдено: <span><?=intval($res->SelectedRowsCount())?></span></div>

        <table class="md-list-table">
        <thead>
        <tr>
            <th class="md-list-table-name">Наименование</th>
            <th class="md-list-table-reg-dealer">Сделать дилером региона</th>
            <th class="md-list-table-url">Web-сайт</th>
            <th class="md-list-table-addr">Адрес и контакты</th>
            <th class="md-list-table-type">Тип точки</th>
            <th class="md-list-table-time">Время работы</th>
        </tr>
        </thead>
        <tbody>
        <?
        while($ob = $res->GetNextElement()) {
            $item = array_merge($arFields = $ob->GetFields(),$arFields = $ob->GetProperties()); ?>
            <tr>
                <td>
                    <div class="md-list-name">
                        <div class="md-list-name-top">
                            <span><?=$i++?>.</span>
                            <?if($item['trade_point']['~VALUE']!='') {
                                echo $item['trade_point']['~VALUE'].'<br>';
                            }?>
                            <?=highlight_found($item['organization']['~VALUE'],$str_query)?>

                        </div>
                        <div class="md-list-name-bottom">
                            <?
                            //TODO: статусы
                            if($item['ACTIVE'] == 'Y') {
                                $stat = 'Опубликовано';
                                $statClass = 'rel';
                            }
                            if($item['ACTIVE'] == 'N') {
                                $stat = 'Не опубликовано';
                                $statClass = 'no-rel';
                            }
                            ?>
                            <div class="dealer-status <?=$statClass?>"><?=$stat?></div>
                        </div>
                    </div>
                </td>
                <td class="md-list-table-reg-dealer">
                    <div class="reg-dealer-wrap">
                       <input type="checkbox" id="reg-dealer-<?=$item['ID']?>" name="reg-dealer" data-val="<?=$item['ID']?>"<?if(in_array($item['ID'],$reg_dealer)) echo ' checked'?>>
                        <label for="reg-dealer-<?=$item['ID']?>"></label>
                    </div>
                </td>
                <td>
                    <?if($item['href']['~VALUE'] != '') {?>
                        <a href="<?='http://'.$item['href']['~VALUE']?>" target="_blank"><?=$item['href']['~VALUE']?></a>
                    <? } ?>
                </td>
                <td>
                    <?
                    $city_res = CIBlockElement::GetList(Array(), Array('ID'=>$item['city']), false, Array(), Array());
                    while($city_ob = $city_res->GetNextElement()) $city_item = $city_ob->GetFields();
                    if($city_item) { ?>
                        <p class="dealer-list-addr"><span>Регион:</span> <?=$city_item['~NAME']?></p>
                    <? } ?>
                    <?if($item['address']['~VALUE'] != '') {?>
                        <p class="dealer-list-addr"><?=highlight_found($item['address']['~VALUE'],$str_query)?></p>
                    <? } ?>
                    <?if($item['phones']['~VALUE'] != '') {?>
                        <p class="dealer-list-addr"><?=highlight_found(str_phone($item['phones']['~VALUE']),$str_query)?></p>
                    <? } ?>
                    <?if($item['email']['~VALUE'] != '') {?>
                        <p class="dealer-list-addr"><?=highlight_found($item['email']['~VALUE'],$str_query)?></p>
                    <? } ?>
                </td>
                <?
                $point_type = " - ";
                if($item['point_type']['~VALUE'] == 'retail') $point_type = "Собственная розница";
                if($item['point_type']['~VALUE'] == 'subdealer') {
                    $point_type = "Субдилерская сеть";
                    if($item['contractor']['~VALUE'] != '') {
                        $point_type .= '<br><br>';
                        $point_type .= 'Предприятие контрагента:<br>';
                        $point_type .= $item['contractor']['~VALUE'];
                    }
                }
                ?>
                <td><?=$point_type?></td>
                <td>
                    <div class="md-list-time">
                        <div class="md-list-time-top">
                            <div class="md-list-time-item">
                                <?if($item['workday']['~VALUE'] != '') {?>
                                    <div class="md-list-time-item-hours"><?=$item['workday']['~VALUE']?></div>
                                    <div class="md-list-time-item-day">Будни</div>
                                <? } ?>

                            </div>
                            <div class="md-list-time-item">
                                <?if($item['saturday']['~VALUE'] != '') {?>
                                    <div class="md-list-time-item-hours"><?=$item['saturday']['~VALUE']?></div>
                                    <div class="md-list-time-item-day">Суббота</div>
                                <? } ?>
                            </div>
                            <div class="md-list-time-item">
                                <?if($item['sunday']['~VALUE'] != '') {?>
                                    <div class="md-list-time-item-hours"><?=$item['sunday']['~VALUE']?></div>
                                    <div class="md-list-time-item-day">Воскресенье</div>
                                <? } ?>
                            </div>
                            <div class="md-list-time-item">
                                <?if($item['weekend']['~VALUE'] != '') {?>
                                    <div class="md-list-time-item-hours"><?=$item['weekend']['~VALUE']?></div>
                                    <div class="md-list-time-item-day">Доп. выходные</div>
                                <? } ?>
                            </div>
                            <?if($item['without']['VALUE'] != '') {?>
                                <div class="md-list-time-item">
                                    <div class="md-list-time-item-hours"></div>
                                    <div class="md-list-time-item-day">Без выходных</div>
                                </div>
                            <? } ?>
                        </div>
                        <div class="md-list-time-bottom">
                            <div data-type="dealer-show" data-id="<?=$item['ID']?>" class="dealer-item-see">Перейти <i class="new-icomoon icon-Angle-right"></i></div>
                        </div>
                    </div>
                </td>
            </tr>
        <? }
        ?>
        </tbody>
        </table>

   <? } else { ?>
        <div class="md-search-no-results">Ничего не найдено.</div>
    <? }
}
?>
<? $result['cont'] = ob_get_clean();

print json_encode($result);