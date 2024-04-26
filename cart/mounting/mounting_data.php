<?
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog.php");
if (!CModule::IncludeModule('iblock')) exit;
//для js
if($_REQUEST['type'] == 'getData') {
    $arr = getMountingData();
    print json_encode($arr);
}

if($_REQUEST['type'] == 'getMountList') {
    $arr = getMountList($_COOKIE['mount']);
    print json_encode($arr);
}

function getMountingData() {
    $arr = Array(
        'one-side' => 100, // карниз: монтаж на 1 грань (м/п)
        'two-sides' => 0, // карниз: монтаж стандарт (м/п)
        'corner' => 100, // погонаж: стоимость 1 угла (шт)
        'corner-standart' => 6, // погонаж: стандартное количество углов
        'corners' => Array(4,6,8,12), // погонаж: углы в выборке
        'height-2500' => 0, // погонаж: высота от 2,5 до 3,5 (м/п)
        'height-3500' => 200, // погонаж: высота от 3,5 до 4,5 (м/п)
        'frame' => 100, // молдинг: монтаж рамок (м/п)
        'wall' => 0, // молдинг: монтаж на стену (м/п)
        'ceiling' => 150, // молдинг: монтаж на потолке (м/п)
        'plintus-standart' => 0, // плинтус: стандартная установка плинтуса (м/п)
        'plintus-light' => 0, // плинтус: установка плинтуса с подсветкой (м/п)
        'plintus-end-1' => 100, // плинтус: окончание плинтуса - поворот в стену (шт)
        'plintus-end-2' => 100, // плинтус: окончание плинтуса - поворот в пол (шт),
        'add-materials' => 1.07, // поправочный коэффициент стоимости с учетом расходных материалов
    );
    return $arr;
}

function getOptionValues($opt,$val,$qty=0) {
    $res = Array();
    $data = getMountingData();
    switch ($opt) {
        /* карнизы */
        case '1' :
            $res['opt'] = "Способ установки карниза";
            switch($val) {
                case '1' :
                    $res['val'] = "Стандарт";
                    $res['factor'] = $data['two-sides'];
                    break;
                case '2' :
                    $res['val'] = "Для натяжного потолка";
                    $res['factor'] = $data['one-side'];
                    break;
                case '3' :
                    $res['val'] = "Для скрытой подсветки";
                    $res['factor'] = $data['one-side'];
                    break;
            }
            break;
        /* молдинги */
        case '2' :
            $res['opt'] = "Тип монтажа";
            switch($val) {
                case '1' :
                    $res['val'] = "Рамки";
                    $res['factor'] = $data['frame'];
                    break;
                case '2' :
                    $res['val'] = "Вдоль стен";
                    $res['factor'] = $data['wall'];
                    break;
                case '3' :
                    $res['val'] = "По периметру потолка";
                    $res['factor'] = $data['ceiling'];
                    break;
            }
            break;
        /* плинтусы */
        case '3' :
            $res['opt'] = "Способ установки плинтуса";
            switch($val) {
                case '1' :
                    $res['val'] = "Стандарт";
                    $res['factor'] = $data['plintus-standart'];
                    break;
                case '2' :
                    $res['val'] = "Скрытая подсветка";
                    $res['factor'] = $data['plintus-light'];
                    break;
            }
            break;
        case '4' :
            $res['opt'] = "Вид окончания";
            switch($val) {
                case '1' :
                    $res['val'] = "Поворот в стену";
                    $res['factor'] = $data['plintus-end-1'];
                    $res['qty'] = $qty;
                    break;
                case '2' :
                    $res['val'] = "Поворот в пол";
                    $res['factor'] = $data['plintus-end-2'];
                    $res['qty'] = $qty;
                    break;
            }
            break;
        case '5' :
            $res['opt'] = "Количество окончаний";
            $res['factor'] = 0;
            $res['val'] = $val;
            break;
        /* общее */
        case '6' :
            $res['opt'] = "Тип помещения (количество углов)";
            $res['val'] = $val;
            $res['factor'] = $data['corner'];
            $res['qty'] = $val > $data['corner-standart'] ? $val - $data['corner-standart'] : 0;
            break;
        case '7' :
            $res['opt'] = "Высота потолков, мм";
            switch($val) {
                case '1' :
                    $res['val'] = "2 500 - 3 500";
                    $res['factor'] = $data['height-2500'];
                    break;
                case '2' :
                    $res['val'] = "3 500 - 4 500";
                    $res['factor'] = $data['height-3500'];
                    break;
            }
            break;
    }
    return $res;
}

function getMountList($list) {
    $m_arr = Array('1542','1543','1544','1545','1546','1547');
    $res = Array();
    $total = 0;
    $list = json_decode($list);
    $mountingData = getMountingData();
    foreach($list as $item) {
        $res_item = Array();
        $res_item['id'] = $item->id;
        $res_item['qty'] = $item->qty;
        $arFilter = Array("IBLOCK_ID"=>12, "ACTIVE"=>"Y","ID"=>$item->id);
        $db_res = CIBlockElement::GetList(Array(), $arFilter, false, Array(), Array());
        while($ob = $db_res->GetNextElement()) {
            $db_item = array_merge($arFields = $ob->GetFields(),$arFields = $ob->GetProperties());
        }
        $res_item['name'] = __get_product_name($db_item);
        $res_item['mount_cost'] = $db_item['MOUNT_COST']['VALUE'];
        $res_item['section_id'] = $db_item['IBLOCK_SECTION_ID'];
        $res_item['length'] = $db_item['S2']['VALUE'] / 1000;
        $res_item['options'] = Array();
        $total_item = $item->qty*$res_item['length']*$res_item['mount_cost'];
        if(!in_array($res_item['section_id'],$m_arr)) $total_item = $item->qty*$res_item['mount_cost'];
        foreach($item as $k=>$v) {
            if ($k == 'id' || $k == 'qty' || $k == 'bqty') continue;
            $qty = 0;
            if($k == 4) $qty = isset($item->{'5'}) ? $item->{'5'} : 0; /*для окончания плинтуса*/
            $opt = getOptionValues($k,$v,$qty);
            $res_item['options'][] = $opt;
            $total_item += isset($opt['qty']) ? $opt['factor']*$opt['qty'] : $opt['factor']*$item->qty*$res_item['length'];
        }
        /*если не заполенены свойства по метражным*/
        if(in_array($res_item['section_id'],$m_arr) && empty($res_item['options'])) $total_item = 0;
        $total_item *= $mountingData['add-materials'];
        $res_item['total_item'] = $total_item;
        $res['items'][] = $res_item;
        $total += $total_item;
    }
    $res['total'] = $total;

    return $res;
}

function getMountListItem($id) {
    $list = json_decode($_COOKIE['mount']);
    foreach($list as $item) {
        if($item->id == $id) return $item;
    }
    return false;
}

function renderMountItem($item,$mountItem,$n,$i=0) {
    $m_arr = Array('1542','1543','1544','1545','1546','1547');
    $isCalc = false;
    if($mountItem && $mountItem->bqty == $item['COUNT'] || $mountItem && $mountItem->qty == $item['COUNT']) $isCalc = true;

    $molded = in_array($item['IBLOCK_SECTION_ID'],$m_arr) ? true : false;
    $signTmp = '';
    $res = CIBlockSection::GetByID($item["IBLOCK_SECTION_ID"]);
    if($arRes = $res->Fetch())
    {
        $section_id = $arRes["ID"];
        $arFilter = Array('IBLOCK_ID'=>12, 'ACTIVE'=>'Y', 'ID'=>$section_id);
        $db_list = CIBlockSection::GetList(Array(), $arFilter, false, array('UF_*'));
        $last_section = $db_list->GetNext();
    }
    if ($last_section['UF_H'] == 1) {
        $signTmp = ' cart-item-img-h';
    } else {
        $signTmp = '';
    }
    if ($item['FLEX']['VALUE'] == "Y") {
        $signTmp = ' cart-item-img-flex';
    }
    $factor_arr = getMountingData();
    $isCalc = false;
    if($mountItem && $mountItem->bqty == $item['COUNT'] || $mountItem && $mountItem->qty == $item['COUNT']) $isCalc = true;
    ob_start();
    $pref = $i > 0 ? '-'.$i : '';
    ?>
    <div class="mount-item<?if($n == 0) echo ' active'?><?if(!$molded) echo ' not-molded'?>"
         data-type="mount-item"
         data-id="<?=$item['ID'].$pref?>"
         data-prod-id="<?=$item['ID']?>"
         data-qty="<?=$isCalc ? $mountItem->qty : $item['COUNT']?>"
         data-bqty="<?=$item['COUNT']?>"
         data-measure="<?=$molded ? 'm' : 'i'?>"
         data-completed="<?=$molded && !$isCalc  ? 'n' : 'y'?>"
         data-price="<?=$item['MOUNT_COST']['VALUE']?>"
         data-length="<?=$item['S2']['VALUE']?>"
    >
        <div class="mount-item-top cart-item">
            <div class="cart-item-img">
                <img src="<?=$item['FILES_IMAGES'][0]?>" alt="<?=__get_product_name($item)?>">
            </div>
            <div class="cart-item-info">
                <div class="cart-item-info-left">
                    <h2>
                        <span><?=__get_product_name($item,true,false,false)?></span>
                        <span class="cart-item-articul"><?=$item['ARTICUL']['VALUE']?></span>
                    </h2>
                    <? if($molded) { ?>
                    <div class="mount-item-duplicate" data-type="duplicate">Дублировать</div>
                <? } ?>
                </div>


                <div class="cart-item-btns">
                    <div class="prod-price">
                        <div class="mount-item-cost-tit">Стоимость монтажа:</div>
                        <?if(!$molded) { ?>
                            <span class="mount-item-cost-number-full prod-total"><span><?=$isCalc ? $mountItem->qty : $item['COUNT']?></span> шт. * <?=__cost_format($item['MOUNT_COST']['VALUE'])?></span>

                        <? } else { ?>
                        <span class="mount-item-cost-number prod-total">0.00 RUB</span>
                        <? } ?>
                    </div>
                    <div class="cart-item-info-qty">
                        <span class="cart-item-btn-tit">Количество:</span>
                        <div class="prod-qty">
                            <div class="prod-minus" data-type="mount-item-minus"><i class="icon-minus"></i></div>
                            <input type="text" value="<?=$isCalc ? $mountItem->qty : $item['COUNT']?>" min="1" data-type="mount-item-qty">
                            <div class="prod-plus" data-type="mount-item-plus"><i class="icon-plus"></i></div>
                        </div>
                    </div>
                    <? if($molded) { ?>
                        <?/*<button type="button" class="mount-item-calc-act" data-type="count">Рассчитать</button>*/?>
                        <button type="button" class="mount-item-calc-act" data-type="open-param">Выбрать параметры <i class="icon-angle-down"></i></button>
                    <? } else { ?>
                            <div class="prod-price not-moulded-total">
                                <div class="cart-item-btn-tit">Итого:</div>
                                <span class="mount-item-cost-number prod-total">0.00 RUB</span>
                            </div>
                    <? } ?>
                </div>
            </div>
            <i class="icon-close" data-type="remove-mount-item" title="Сбросить параметры"></i>
        </div>

        <? if($molded) { ?>
        <div class="mount-item-bottom">
            <div class="mount-item-calc-subheadline">Выберите нужные параметры:</div>

            <? /* карнизы */ ?>
            <? if($item['IBLOCK_SECTION_ID'] == '1542' || $item['IBLOCK_SECTION_ID'] == '1543') { ?>

                <div class="mount-item-calc-step" data-type="step" data-opt="radio" data-val="1">
                    <div class="mount-item-calc-step-headline">Шаг 1. Способ установки карниза</div>
                    <div class="mount-item-calc-step-wrap">
                        <div class="mount-item-calc-step-param mount-cornice<?=checkActive($isCalc,$mountItem,1,1)?>" data-type="option" data-val="1" data-factor="<?=$factor_arr['two-sides']?>">
                            <i class="m-icon-cornice-standart"></i>
                            <div class="mount-item-calc-step-param-btn">стандарт</div>
                        </div>
                        <div class="mount-item-calc-step-param mount-cornice<?=checkActive($isCalc,$mountItem,1,2)?>" data-type="option" data-val="2" data-factor="<?=$factor_arr['one-side']?>">
                            <i class="m-icon-cornice-stretch"></i>
                            <div class="mount-item-calc-step-param-btn">натяжной</div>
                        </div>
                        <div class="mount-item-calc-step-param mount-cornice<?=checkActive($isCalc,$mountItem,1,3)?>" data-type="option" data-val="3" data-factor="<?=$factor_arr['one-side']?>">
                            <i class="m-icon-cornice-light"></i>
                            <div class="mount-item-calc-step-param-btn">скрытая <br>подсветка</div>
                        </div>
                    </div>
                </div>
                <div class="mount-item-calc-step" data-type="step" data-opt="radio" data-val="6">
                    <div class="mount-item-calc-step-headline">Шаг 2. Тип помещения (количество углов)</div>
                    <div class="mount-item-calc-step-wrap corners">
                        <?
                        foreach($factor_arr['corners'] as $corner) {
                            $payForCorner = 0;
                            if($corner > $factor_arr['corner-standart']) $payForCorner = $corner - $factor_arr['corner-standart'];
                            ?>
                            <div class="mount-item-calc-step-param<?=checkActive($isCalc,$mountItem,6,$corner)?>" data-type="option" data-val="<?=$corner?>" data-factor="<?=$factor_arr['corner']?>" data-qty="<?=$payForCorner?>">
                                <i class="m-icon-<?=$corner?>-corners"></i>
                                <div class="mount-item-calc-step-param-btn"><?=$corner?></div>
                            </div>
                        <? } ?>
                    </div>
                </div>
                <div class="mount-item-calc-step" data-type="step" data-opt="radio" data-val="7">
                    <div class="mount-item-calc-step-headline">Шаг 3. Высота потолков, мм</div>
                    <div class="mount-item-calc-step-wrap height">
                        <div class="mount-item-calc-step-param<?=checkActive($isCalc,$mountItem,7,1)?>" data-type="option" data-val="1" data-factor="0">
                            <div class="mount-item-calc-step-param-btn">2 500 - 3 500</div>
                        </div>
                        <div class="mount-item-calc-step-param<?=checkActive($isCalc,$mountItem,7,2)?>" data-type="option" data-val="2" data-factor="<?=$factor_arr['height-3500']?>">
                            <div class="mount-item-calc-step-param-btn">3 500 - 4 500</div>
                        </div>
                    </div>
                </div>

            <? } ?>

            <? /* молдинги */ ?>
            <? if($item['IBLOCK_SECTION_ID'] == '1544' || $item['IBLOCK_SECTION_ID'] == '1545') { ?>
                <div class="mount-item-calc-step" data-type="step" data-opt="radio" data-val="2">
                    <div class="mount-item-calc-step-headline">Шаг 1. Тип монтажа</div>
                    <div class="mount-item-calc-step-wrap mould-type">
                        <div class="mount-item-calc-step-param mount-cornice<?=checkActive($isCalc,$mountItem,2,1)?>" data-type="option" data-val="1" data-factor="<?=$factor_arr['frame']?>">
                            <i class="m-icon-moulding-frame pass">
                                <span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span><span class="path6"></span><span class="path7"></span><span class="path8"></span><span class="path9"></span><span class="path10"></span><span class="path11"></span><span class="path12"></span><span class="path13"></span><span class="path14"></span><span class="path15"></span><span class="path16"></span><span class="path17"></span><span class="path18"></span><span class="path19"></span><span class="path20"></span><span class="path21"></span><span class="path22"></span><span class="path23"></span><span class="path24"></span><span class="path25"></span><span class="path26"></span><span class="path27"></span><span class="path28"></span><span class="path29"></span><span class="path30"></span><span class="path31"></span><span class="path32"></span><span class="path33"></span><span class="path34"></span><span class="path35"></span><span class="path36"></span><span class="path37"></span><span class="path38"></span><span class="path39"></span><span class="path40"></span><span class="path41"></span><span class="path42"></span><span class="path43"></span><span class="path44"></span><span class="path45"></span><span class="path46"></span>
                            </i>
                            <i class="m-icon-moulding-frame-act act">
                                <span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span><span class="path6"></span><span class="path7"></span><span class="path8"></span><span class="path9"></span><span class="path10"></span><span class="path11"></span><span class="path12"></span><span class="path13"></span><span class="path14"></span><span class="path15"></span><span class="path16"></span><span class="path17"></span><span class="path18"></span><span class="path19"></span><span class="path20"></span><span class="path21"></span><span class="path22"></span><span class="path23"></span><span class="path24"></span><span class="path25"></span><span class="path26"></span><span class="path27"></span><span class="path28"></span><span class="path29"></span><span class="path30"></span><span class="path31"></span><span class="path32"></span><span class="path33"></span><span class="path34"></span><span class="path35"></span><span class="path36"></span><span class="path37"></span><span class="path38"></span><span class="path39"></span><span class="path40"></span><span class="path41"></span><span class="path42"></span><span class="path43"></span><span class="path44"></span><span class="path45"></span><span class="path46"></span><span class="path47"></span><span class="path48"></span><span class="path49"></span>
                            </i>
                            <div class="mount-item-calc-step-param-btn">рамки</div>
                        </div>
                        <div class="mount-item-calc-step-param mount-cornice<?=checkActive($isCalc,$mountItem,2,2)?>" data-type="option" data-val="2" data-factor="<?=$factor_arr['wall']?>">
                            <i class="m-icon-moulding-wall pass">
                                <span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span><span class="path6"></span><span class="path7"></span><span class="path8"></span><span class="path9"></span><span class="path10"></span><span class="path11"></span><span class="path12"></span><span class="path13"></span><span class="path14"></span><span class="path15"></span><span class="path16"></span><span class="path17"></span><span class="path18"></span><span class="path19"></span><span class="path20"></span><span class="path21"></span><span class="path22"></span><span class="path23"></span><span class="path24"></span><span class="path25"></span><span class="path26"></span><span class="path27"></span><span class="path28"></span><span class="path29"></span><span class="path30"></span><span class="path31"></span><span class="path32"></span><span class="path33"></span><span class="path34"></span><span class="path35"></span><span class="path36"></span><span class="path37"></span><span class="path38"></span><span class="path39"></span>
                            </i>
                            <i class="m-icon-moulding-wall-act act">
                                <span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span><span class="path6"></span><span class="path7"></span><span class="path8"></span><span class="path9"></span><span class="path10"></span><span class="path11"></span><span class="path12"></span><span class="path13"></span><span class="path14"></span><span class="path15"></span><span class="path16"></span><span class="path17"></span><span class="path18"></span><span class="path19"></span><span class="path20"></span><span class="path21"></span><span class="path22"></span><span class="path23"></span><span class="path24"></span><span class="path25"></span><span class="path26"></span><span class="path27"></span><span class="path28"></span><span class="path29"></span><span class="path30"></span><span class="path31"></span><span class="path32"></span><span class="path33"></span><span class="path34"></span><span class="path35"></span><span class="path36"></span><span class="path37"></span><span class="path38"></span><span class="path39"></span><span class="path40"></span><span class="path41"></span><span class="path42"></span>
                            </i>
                            <div class="mount-item-calc-step-param-btn">вдоль стен</div>
                        </div>
                        <div class="mount-item-calc-step-param mount-cornice<?=checkActive($isCalc,$mountItem,2,3)?>" data-type="option" data-val="3" data-factor="<?=$factor_arr['ceiling']?>">
                            <i class="m-icon-moulding-ceiling pass">
                                <span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span><span class="path6"></span><span class="path7"></span><span class="path8"></span><span class="path9"></span><span class="path10"></span><span class="path11"></span><span class="path12"></span><span class="path13"></span><span class="path14"></span><span class="path15"></span><span class="path16"></span><span class="path17"></span><span class="path18"></span><span class="path19"></span><span class="path20"></span><span class="path21"></span><span class="path22"></span><span class="path23"></span><span class="path24"></span><span class="path25"></span><span class="path26"></span><span class="path27"></span><span class="path28"></span><span class="path29"></span><span class="path30"></span><span class="path31"></span><span class="path32"></span><span class="path33"></span><span class="path34"></span><span class="path35"></span><span class="path36"></span><span class="path37"></span><span class="path38"></span>
                            </i>
                            <i class="m-icon-moulding-ceiling-act act">
                                <span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span><span class="path6"></span><span class="path7"></span><span class="path8"></span><span class="path9"></span><span class="path10"></span><span class="path11"></span><span class="path12"></span><span class="path13"></span><span class="path14"></span><span class="path15"></span><span class="path16"></span><span class="path17"></span><span class="path18"></span><span class="path19"></span><span class="path20"></span><span class="path21"></span><span class="path22"></span><span class="path23"></span><span class="path24"></span><span class="path25"></span><span class="path26"></span><span class="path27"></span><span class="path28"></span><span class="path29"></span><span class="path30"></span><span class="path31"></span><span class="path32"></span><span class="path33"></span><span class="path34"></span><span class="path35"></span><span class="path36"></span><span class="path37"></span><span class="path38"></span>
                            </i>
                            <div class="mount-item-calc-step-param-btn">по периметру <br>потолка</div>
                        </div>
                    </div>
                </div>
                    <?if($isCalc && $mountItem->{2} == 2 || $isCalc && $mountItem->{2} == 3) { ?>
                        <div class="mount-item-calc-step" data-type="step" data-opt="radio" data-val="6">
                            <div class="mount-item-calc-step-headline">Шаг 2. Тип помещения (количество углов)</div>
                            <div class="mount-item-calc-step-wrap corners">
                                <?
                                foreach($factor_arr['corners'] as $corner) {
                                    $payForCorner = 0;
                                    if($corner > $factor_arr['corner-standart']) $payForCorner = $corner - $factor_arr['corner-standart'];
                                    ?>
                                    <div class="mount-item-calc-step-param<?=checkActive($isCalc,$mountItem,6,$corner)?>" data-type="option" data-val="<?=$corner?>" data-factor="<?=$factor_arr['corner']?>" data-qty="<?=$payForCorner?>">
                                        <i class="m-icon-<?=$corner?>-corners"></i>
                                        <div class="mount-item-calc-step-param-btn"><?=$corner?></div>
                                    </div>
                                <? } ?>
                            </div>
                        </div>
                    <? } ?>
                    <?if($isCalc && $mountItem->{2} == 3) { ?>
                        <div class="mount-item-calc-step" data-type="step" data-opt="radio" data-val="7">
                            <div class="mount-item-calc-step-headline">Шаг 3. Высота потолков, мм</div>
                            <div class="mount-item-calc-step-wrap height">
                                <div class="mount-item-calc-step-param<?=checkActive($isCalc,$mountItem,7,1)?>" data-type="option" data-val="1" data-factor="0">
                                    <div class="mount-item-calc-step-param-btn">2 500 - 3 500</div>
                                </div>
                                <div class="mount-item-calc-step-param<?=checkActive($isCalc,$mountItem,7,2)?>" data-type="option" data-val="2" data-factor="<?=$factor_arr['height-3500']?>">
                                    <div class="mount-item-calc-step-param-btn">3 500 - 4 500</div>
                                </div>
                            </div>
                        </div>
                    <? } ?>

            <? } ?>

            <? /* плинтусы */ ?>
            <? if($item['IBLOCK_SECTION_ID'] == '1546' || $item['IBLOCK_SECTION_ID'] == '1547') { ?>
                <div class="mount-item-calc-step" data-type="step" data-opt="radio" data-val="3">
                    <div class="mount-item-calc-step-headline">Шаг 1. Способ установки плинтуса</div>
                    <div class="mount-item-calc-step-wrap plintus-type">
                        <div class="mount-item-calc-step-param mount-cornice<?=checkActive($isCalc,$mountItem,3,1)?>" data-type="option" data-val="1" data-factor="<?=$factor_arr['plintus-standart']?>">
                            <i class="m-icon-plintus-standart"></i>
                            <div class="mount-item-calc-step-param-btn">стандарт</div>
                        </div>
                        <div class="mount-item-calc-step-param mount-cornice<?=checkActive($isCalc,$mountItem,3,2)?>" data-type="option" data-val="2" data-factor="<?=$factor_arr['plintus-light']?>">
                            <i class="m-icon-plintus-light"></i>
                            <div class="mount-item-calc-step-param-btn">скрытая <br>подсветка</div>
                        </div>
                    </div>
                </div>
                <div class="mount-item-calc-step" data-type="step" data-opt="radio" data-val="6">
                    <div class="mount-item-calc-step-headline">Шаг 2. Тип помещения (количество углов)</div>
                    <div class="mount-item-calc-step-wrap corners">
                        <?
                        foreach($factor_arr['corners'] as $corner) {
                            $payForCorner = 0;
                            if($corner > $factor_arr['corner-standart']) $payForCorner = $corner - $factor_arr['corner-standart'];
                            ?>
                            <div class="mount-item-calc-step-param<?=checkActive($isCalc,$mountItem,6,$corner)?>" data-type="option" data-val="<?=$corner?>" data-factor="<?=$factor_arr['corner']?>" data-qty="<?=$payForCorner?>">
                                <i class="m-icon-<?=$corner?>-corners"></i>
                                <div class="mount-item-calc-step-param-btn"><?=$corner?></div>
                            </div>
                        <? } ?>
                    </div>
                </div>
                <div class="mount-item-calc-step plintus-end" data-type="step" data-opt="radio" data-val="4">
                    <div class="mount-item-calc-step-headline">Шаг 3. Выберите вид окончания и укажите количество<br> (примыкания к наличнику дверей, встроенной мебели):</div>
                    <div class="mount-item-calc-step-wrap corners">
                        <?
                        $endQty = 0;
                        if($isCalc && isset($mountItem->{5})) $endQty = $mountItem->{5};
                        ?>
                        <div class="mount-item-calc-step-param<?=checkActive($isCalc,$mountItem,4,1)?>" data-type="option" data-val="1" data-factor="<?=$factor_arr['plintus-end-1']?>" data-qty="<?=$endQty?>">
                            <div class="plintus-end-more" data-fancybox="var_1_<?=$item['ID'].$pref?>" data-caption="Вариант 1: поворот в&nbsp;стену" href="/cart/mounting/img/var_1.jpg" title="Посмотреть фото">?</div>
                            <i class="m-icon-plintus-end-1 plintus-icon pass"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span><span class="path6"></span><span class="path7"></span><span class="path8"></span><span class="path9"></span><span class="path10"></span><span class="path11"></span><span class="path12"></span></i>
                            <i class="m-icon-plintus-end-1-act plintus-icon act"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span><span class="path6"></span><span class="path7"></span><span class="path8"></span><span class="path9"></span><span class="path10"></span><span class="path11"></span><span class="path12"></span><span class="path13"></span><span class="path14"></span><span class="path15"></span><span class="path16"></span><span class="path17"></span><span class="path18"></span><span class="path19"></span><span class="path20"></span></i>
                            <div class="mount-item-calc-step-param-btn"></div>
                        </div>
                        <div class="mount-item-calc-step-param<?=checkActive($isCalc,$mountItem,4,2)?>" data-type="option" data-val="2" data-factor="<?=$factor_arr['plintus-end-2']?>" data-qty="<?=$endQty?>">
                            <div class="plintus-end-more" data-fancybox="var_2_<?=$item['ID'].$pref?>" data-caption="Вариант 2: поворот в&nbsp;пол" href="/cart/mounting/img/var_2.jpg" title="Посмотреть фото">?</div>
                            <i class="m-icon-plintus-end-2 plintus-icon pass"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span><span class="path6"></span><span class="path7"></span><span class="path8"></span><span class="path9"></span><span class="path10"></span><span class="path11"></span><span class="path12"></span></i>
                            <i class="m-icon-plintus-end-2-act plintus-icon act"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span><span class="path6"></span><span class="path7"></span><span class="path8"></span><span class="path9"></span><span class="path10"></span><span class="path11"></span><span class="path12"></span><span class="path13"></span><span class="path14"></span></i>
                            <div class="mount-item-calc-step-param-btn"></div>
                        </div>
                        <div class="mount-item-calc-step-wrap input">
                            <label for="frame-id-5">Введите количество:</label>
                            <input type="text" id="frame-id-5" data-type="inp" value="<?=$endQty?>" data-for="pend">
                            <span class="inp-unit"> шт</span>
                        </div>
                    </div>
                </div>
            <? } ?>

            <div class="mount-item-bottom-btns">
                <div class="mount-err-mess" data-type="mount-err-mess">Выберите все&nbsp;опции и&nbsp;заполните все&nbsp;поля</div>
                <button type="button" class="mount-item-calc-act" data-type="count">Рассчитать</button>
                <button type="button" class="mount-item-calc-act mount-item-calc-cancel" data-type="remove-mount-item">Сбросить</button>
            </div>
        </div>
        <? } ?>
    </div>



    <?
    $html = ob_get_clean();
    return $html;
}

function renderMountParams($item,$mountItem,$n,$i=0) {
    $m_arr = Array('1542','1543','1544','1545','1546','1547');
    $factor_arr = getMountingData();
    $molded = in_array($item['IBLOCK_SECTION_ID'],$m_arr) ? true : false;
    $isCalc = false;
    if($mountItem && $mountItem->bqty == $item['COUNT'] || $mountItem && $mountItem->qty == $item['COUNT']) $isCalc = true;
    $pref = $i > 0 ? '-'.$i : '';
    ob_start();
    ?>
    <div class="mount-item-calc<?if($n == 0 && $molded) echo ' active'?>" data-val-id="<?=$item['ID'].$pref?>" data-type="calc">
        <div class="mount-item-calc-headline">Расчет стоимости монтажа</div>
        <div class="mount-item-calc-subheadline">Введите нужные параметры:</div>

        <? /* карнизы */ ?>
        <? if($item['IBLOCK_SECTION_ID'] == '1542' || $item['IBLOCK_SECTION_ID'] == '1543') { ?>

            <div class="mount-item-calc-step" data-type="step" data-opt="radio" data-val="1">
                <div class="mount-item-calc-step-headline">Шаг 1. Способ установки карниза</div>
                <div class="mount-item-calc-step-wrap">
                    <div class="mount-item-calc-step-param mount-cornice<?=checkActive($isCalc,$mountItem,1,1)?>" data-type="option" data-val="1" data-factor="<?=$factor_arr['two-sides']?>">
                        <i class="m-icon-cornice-standart"></i>
                        <div class="mount-item-calc-step-param-btn">стандарт</div>
                    </div>
                    <div class="mount-item-calc-step-param mount-cornice<?=checkActive($isCalc,$mountItem,1,2)?>" data-type="option" data-val="2" data-factor="<?=$factor_arr['one-side']?>">
                        <i class="m-icon-cornice-stretch"></i>
                        <div class="mount-item-calc-step-param-btn">натяжной</div>
                    </div>
                    <div class="mount-item-calc-step-param mount-cornice<?=checkActive($isCalc,$mountItem,1,3)?>" data-type="option" data-val="3" data-factor="<?=$factor_arr['one-side']?>">
                        <i class="m-icon-cornice-light"></i>
                        <div class="mount-item-calc-step-param-btn">скрытая <br>подсветка</div>
                    </div>
                </div>
            </div>
            <div class="mount-item-calc-step" data-type="step" data-opt="radio" data-val="6">
                <div class="mount-item-calc-step-headline">Шаг 2. Тип помещения (количество углов)</div>
                <div class="mount-item-calc-step-wrap corners">
                    <?
                    foreach($factor_arr['corners'] as $corner) {
                        $payForCorner = 0;
                        if($corner > $factor_arr['corner-standart']) $payForCorner = $corner - $factor_arr['corner-standart'];
                        ?>
                        <div class="mount-item-calc-step-param<?=checkActive($isCalc,$mountItem,6,$corner)?>" data-type="option" data-val="<?=$corner?>" data-factor="<?=$factor_arr['corner']?>" data-qty="<?=$payForCorner?>">
                            <i class="m-icon-<?=$corner?>-corners"></i>
                            <div class="mount-item-calc-step-param-btn"><?=$corner?></div>
                        </div>
                    <? } ?>
                </div>
            </div>
            <div class="mount-item-calc-step" data-type="step" data-opt="radio" data-val="7">
                <div class="mount-item-calc-step-headline">Шаг 3. Высота потолков, мм</div>
                <div class="mount-item-calc-step-wrap height">
                    <div class="mount-item-calc-step-param<?=checkActive($isCalc,$mountItem,7,1)?>" data-type="option" data-val="1" data-factor="0">
                        <div class="mount-item-calc-step-param-btn">2 500 - 3 500</div>
                    </div>
                    <div class="mount-item-calc-step-param<?=checkActive($isCalc,$mountItem,7,2)?>" data-type="option" data-val="2" data-factor="<?=$factor_arr['height-3500']?>">
                        <div class="mount-item-calc-step-param-btn">3 500 - 4 500</div>
                    </div>
                </div>
            </div>

        <? } ?>

        <? /* молдинги */ ?>
        <? if($item['IBLOCK_SECTION_ID'] == '1544' || $item['IBLOCK_SECTION_ID'] == '1545') { ?>
            <div class="mount-item-calc-step" data-type="step" data-opt="radio" data-val="2">
                <div class="mount-item-calc-step-headline">Шаг 1. Тип монтажа</div>
                <div class="mount-item-calc-step-wrap">
                    <div class="mount-item-calc-step-param mount-cornice<?=checkActive($isCalc,$mountItem,2,1)?>" data-type="option" data-val="1" data-factor="<?=$factor_arr['frame']?>">
                        <i class="m-icon-moulding-frame pass">
                            <span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span><span class="path6"></span><span class="path7"></span><span class="path8"></span><span class="path9"></span><span class="path10"></span><span class="path11"></span><span class="path12"></span><span class="path13"></span><span class="path14"></span><span class="path15"></span><span class="path16"></span><span class="path17"></span><span class="path18"></span><span class="path19"></span><span class="path20"></span><span class="path21"></span><span class="path22"></span><span class="path23"></span><span class="path24"></span><span class="path25"></span><span class="path26"></span><span class="path27"></span><span class="path28"></span><span class="path29"></span><span class="path30"></span><span class="path31"></span><span class="path32"></span><span class="path33"></span><span class="path34"></span><span class="path35"></span><span class="path36"></span><span class="path37"></span><span class="path38"></span><span class="path39"></span><span class="path40"></span><span class="path41"></span><span class="path42"></span><span class="path43"></span><span class="path44"></span><span class="path45"></span><span class="path46"></span>
                        </i>
                        <i class="m-icon-moulding-frame-act act">
                            <span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span><span class="path6"></span><span class="path7"></span><span class="path8"></span><span class="path9"></span><span class="path10"></span><span class="path11"></span><span class="path12"></span><span class="path13"></span><span class="path14"></span><span class="path15"></span><span class="path16"></span><span class="path17"></span><span class="path18"></span><span class="path19"></span><span class="path20"></span><span class="path21"></span><span class="path22"></span><span class="path23"></span><span class="path24"></span><span class="path25"></span><span class="path26"></span><span class="path27"></span><span class="path28"></span><span class="path29"></span><span class="path30"></span><span class="path31"></span><span class="path32"></span><span class="path33"></span><span class="path34"></span><span class="path35"></span><span class="path36"></span><span class="path37"></span><span class="path38"></span><span class="path39"></span><span class="path40"></span><span class="path41"></span><span class="path42"></span><span class="path43"></span><span class="path44"></span><span class="path45"></span><span class="path46"></span><span class="path47"></span><span class="path48"></span><span class="path49"></span>
                        </i>
                        <div class="mount-item-calc-step-param-btn">рамки</div>
                    </div>
                    <div class="mount-item-calc-step-param mount-cornice<?=checkActive($isCalc,$mountItem,2,2)?>" data-type="option" data-val="2" data-factor="<?=$factor_arr['wall']?>">
                        <i class="m-icon-moulding-wall pass">
                            <span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span><span class="path6"></span><span class="path7"></span><span class="path8"></span><span class="path9"></span><span class="path10"></span><span class="path11"></span><span class="path12"></span><span class="path13"></span><span class="path14"></span><span class="path15"></span><span class="path16"></span><span class="path17"></span><span class="path18"></span><span class="path19"></span><span class="path20"></span><span class="path21"></span><span class="path22"></span><span class="path23"></span><span class="path24"></span><span class="path25"></span><span class="path26"></span><span class="path27"></span><span class="path28"></span><span class="path29"></span><span class="path30"></span><span class="path31"></span><span class="path32"></span><span class="path33"></span><span class="path34"></span><span class="path35"></span><span class="path36"></span><span class="path37"></span><span class="path38"></span><span class="path39"></span>
                        </i>
                        <i class="m-icon-moulding-wall-act act">
                            <span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span><span class="path6"></span><span class="path7"></span><span class="path8"></span><span class="path9"></span><span class="path10"></span><span class="path11"></span><span class="path12"></span><span class="path13"></span><span class="path14"></span><span class="path15"></span><span class="path16"></span><span class="path17"></span><span class="path18"></span><span class="path19"></span><span class="path20"></span><span class="path21"></span><span class="path22"></span><span class="path23"></span><span class="path24"></span><span class="path25"></span><span class="path26"></span><span class="path27"></span><span class="path28"></span><span class="path29"></span><span class="path30"></span><span class="path31"></span><span class="path32"></span><span class="path33"></span><span class="path34"></span><span class="path35"></span><span class="path36"></span><span class="path37"></span><span class="path38"></span><span class="path39"></span><span class="path40"></span><span class="path41"></span><span class="path42"></span>
                        </i>
                        <div class="mount-item-calc-step-param-btn">вдоль стен</div>
                    </div>
                    <div class="mount-item-calc-step-param mount-cornice<?=checkActive($isCalc,$mountItem,2,3)?>" data-type="option" data-val="3" data-factor="<?=$factor_arr['ceiling']?>">
                        <i class="m-icon-moulding-ceiling pass">
                            <span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span><span class="path6"></span><span class="path7"></span><span class="path8"></span><span class="path9"></span><span class="path10"></span><span class="path11"></span><span class="path12"></span><span class="path13"></span><span class="path14"></span><span class="path15"></span><span class="path16"></span><span class="path17"></span><span class="path18"></span><span class="path19"></span><span class="path20"></span><span class="path21"></span><span class="path22"></span><span class="path23"></span><span class="path24"></span><span class="path25"></span><span class="path26"></span><span class="path27"></span><span class="path28"></span><span class="path29"></span><span class="path30"></span><span class="path31"></span><span class="path32"></span><span class="path33"></span><span class="path34"></span><span class="path35"></span><span class="path36"></span><span class="path37"></span><span class="path38"></span>
                        </i>
                        <i class="m-icon-moulding-ceiling-act act">
                            <span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span><span class="path6"></span><span class="path7"></span><span class="path8"></span><span class="path9"></span><span class="path10"></span><span class="path11"></span><span class="path12"></span><span class="path13"></span><span class="path14"></span><span class="path15"></span><span class="path16"></span><span class="path17"></span><span class="path18"></span><span class="path19"></span><span class="path20"></span><span class="path21"></span><span class="path22"></span><span class="path23"></span><span class="path24"></span><span class="path25"></span><span class="path26"></span><span class="path27"></span><span class="path28"></span><span class="path29"></span><span class="path30"></span><span class="path31"></span><span class="path32"></span><span class="path33"></span><span class="path34"></span><span class="path35"></span><span class="path36"></span><span class="path37"></span><span class="path38"></span>
                        </i>
                        <div class="mount-item-calc-step-param-btn">по периметру <br>потолка</div>
                    </div>
                </div>
            </div>
                <?if($isCalc && $mountItem->{2} == 2 || $isCalc && $mountItem->{2} == 3) { ?>
                    <div class="mount-item-calc-step" data-type="step" data-opt="radio" data-val="6">
                        <div class="mount-item-calc-step-headline">Шаг 2. Тип помещения (количество углов)</div>
                        <div class="mount-item-calc-step-wrap corners">
                            <?
                            foreach($factor_arr['corners'] as $corner) {
                                $payForCorner = 0;
                                if($corner > $factor_arr['corner-standart']) $payForCorner = $corner - $factor_arr['corner-standart'];
                                ?>
                                <div class="mount-item-calc-step-param<?=checkActive($isCalc,$mountItem,6,$corner)?>" data-type="option" data-val="<?=$corner?>" data-factor="<?=$factor_arr['corner']?>" data-qty="<?=$payForCorner?>">
                                    <i class="m-icon-<?=$corner?>-corners"></i>
                                    <div class="mount-item-calc-step-param-btn"><?=$corner?></div>
                                </div>
                            <? } ?>
                        </div>
                    </div>
                <? } ?>
                <?if($isCalc && $mountItem->{2} == 3) { ?>
                    <div class="mount-item-calc-step" data-type="step" data-opt="radio" data-val="7">
                        <div class="mount-item-calc-step-headline">Шаг 3. Высота потолков, мм</div>
                        <div class="mount-item-calc-step-wrap height">
                            <div class="mount-item-calc-step-param<?=checkActive($isCalc,$mountItem,7,1)?>" data-type="option" data-val="1" data-factor="0">
                                <div class="mount-item-calc-step-param-btn">2 500 - 3 500</div>
                            </div>
                            <div class="mount-item-calc-step-param<?=checkActive($isCalc,$mountItem,7,2)?>" data-type="option" data-val="2" data-factor="<?=$factor_arr['height-3500']?>">
                                <div class="mount-item-calc-step-param-btn">3 500 - 4 500</div>
                            </div>
                        </div>
                    </div>
                <? } ?>
        <? } ?>

        <? /* плинтусы */ ?>
        <? if($item['IBLOCK_SECTION_ID'] == '1546' || $item['IBLOCK_SECTION_ID'] == '1547') { ?>
            <div class="mount-item-calc-step" data-type="step" data-opt="radio" data-val="3">
                <div class="mount-item-calc-step-headline">Шаг 1. Способ установки плинтуса</div>
                <div class="mount-item-calc-step-wrap plintus-type">
                    <div class="mount-item-calc-step-param mount-cornice<?=checkActive($isCalc,$mountItem,3,1)?>" data-type="option" data-val="1" data-factor="<?=$factor_arr['plintus-standart']?>">
                        <i class="m-icon-plintus-standart"></i>
                        <div class="mount-item-calc-step-param-btn">стандарт</div>
                    </div>
                    <div class="mount-item-calc-step-param mount-cornice<?=checkActive($isCalc,$mountItem,3,2)?>" data-type="option" data-val="2" data-factor="<?=$factor_arr['plintus-light']?>">
                        <i class="m-icon-plintus-light"></i>
                        <div class="mount-item-calc-step-param-btn">скрытая <br>подсветка</div>
                    </div>
                </div>
            </div>
            <div class="mount-item-calc-step" data-type="step" data-opt="radio" data-val="4">
                <div class="mount-item-calc-step-headline">Шаг 2. Выберите вид окончания и укажите количество (примыкания к наличнику дверей, встроенной мебели):</div>
                <div class="mount-item-calc-step-wrap corners">
                    <?
                    $endQty = 0;
                    if($isCalc && isset($mountItem->{5})) $endQty = $mountItem->{5};
                    ?>
                    <div class="mount-item-calc-step-param<?=checkActive($isCalc,$mountItem,4,1)?>" data-type="option" data-val="1" data-factor="<?=$factor_arr['plintus-end-1']?>" data-qty="<?=$endQty?>">
                        <div class="plintus-end-more" data-fancybox="var_1" data-caption="Вариант 1: поворот в&nbsp;стену" href="/cart/mounting/img/var_1.jpg" title="Посмотреть фото">?</div>
                        <i class="m-icon-plintus-end-1 plintus-icon pass"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span><span class="path6"></span><span class="path7"></span><span class="path8"></span><span class="path9"></span><span class="path10"></span><span class="path11"></span><span class="path12"></span></i>
                        <i class="m-icon-plintus-end-1-act plintus-icon act"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span><span class="path6"></span><span class="path7"></span><span class="path8"></span><span class="path9"></span><span class="path10"></span><span class="path11"></span><span class="path12"></span><span class="path13"></span><span class="path14"></span><span class="path15"></span><span class="path16"></span><span class="path17"></span><span class="path18"></span><span class="path19"></span><span class="path20"></span></i>
                        <div class="mount-item-calc-step-param-btn"></div>
                    </div>
                    <div class="mount-item-calc-step-param<?=checkActive($isCalc,$mountItem,4,2)?>" data-type="option" data-val="2" data-factor="<?=$factor_arr['plintus-end-2']?>" data-qty="<?=$endQty?>">
                        <div class="plintus-end-more" data-fancybox="var_2" data-caption="Вариант 2: поворот в&nbsp;пол" href="/cart/mounting/img/var_2.jpg" title="Посмотреть фото">?</div>
                        <i class="m-icon-plintus-end-2 plintus-icon pass"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span><span class="path6"></span><span class="path7"></span><span class="path8"></span><span class="path9"></span><span class="path10"></span><span class="path11"></span><span class="path12"></span></i>
                        <i class="m-icon-plintus-end-2-act plintus-icon act"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span><span class="path6"></span><span class="path7"></span><span class="path8"></span><span class="path9"></span><span class="path10"></span><span class="path11"></span><span class="path12"></span><span class="path13"></span><span class="path14"></span></i>
                        <div class="mount-item-calc-step-param-btn"></div>
                    </div>
                    <div class="mount-item-calc-step-wrap input">
                        <label for="frame-id-5">Введите количество:</label>
                        <input type="text" id="frame-id-5" data-type="inp" value="<?=$endQty?>" data-for="pend">
                        <span class="inp-unit">, шт</span>
                    </div>
                </div>
            </div>
            <div class="mount-item-calc-step" data-type="step" data-opt="radio" data-val="6">
                <div class="mount-item-calc-step-headline">Шаг 3. Тип помещения (количество углов)</div>
                <div class="mount-item-calc-step-wrap corners">
                    <?
                    foreach($factor_arr['corners'] as $corner) {
                        $payForCorner = 0;
                        if($corner > $factor_arr['corner-standart']) $payForCorner = $corner - $factor_arr['corner-standart'];
                        ?>
                        <div class="mount-item-calc-step-param<?=checkActive($isCalc,$mountItem,6,$corner)?>" data-type="option" data-val="<?=$corner?>" data-factor="<?=$factor_arr['corner']?>" data-qty="<?=$payForCorner?>">
                            <i class="m-icon-<?=$corner?>-corners"></i>
                            <div class="mount-item-calc-step-param-btn"><?=$corner?></div>
                        </div>
                    <? } ?>
                </div>
            </div>
        <? } ?>
    </div>
    <?
    $html = ob_get_clean();
    return $html;
}

function renderMountTotal($item,$i=0) {
    ob_start();
    $pref = $i > 0 ? '-'.$i : '';
    ?>
    <div class="main-total-item" data-total-id="<?=$item['ID'].$pref?>">
        <div><?=mb_ucfirst(__get_product_name($item))?> - <span data-type="total-qty"><?=$item['COUNT']?></span> шт.</div>
        <div data-type="total-cost">0.00 RUB</div>
    </div>
    <?
    $html = ob_get_clean();
    return $html;
}

function checkActive($isCalc=false,$mountItem,$opt,$val) {
    if($isCalc == true && $mountItem->$opt == $val) return ' active';
}

function getPdfList($list) {
    ob_start();
    ?>
    <html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <style>
            @import url('https://fonts.googleapis.com/css?family=Roboto&subset=cyrillic');
            body {
                font-family: 'Roboto', sans-serif !important;
                font-weight: normal !important;
                padding: 20px 20px 0;
                -webkit-box-sizing: border-box;
                -moz-box-sizing: border-box;
                box-sizing: border-box;
                color: #000;
            }
            .logo {
                max-width:40%;
                float: left;
            }
            .header-title {
                margin: 0 0 15px 15%;
                float: right;
                width: 500px;
            }
            h1 {
                text-transform: uppercase;
                text-align: right;
                font-size: 18px;
                line-height: 20px;
                color: #000;
                font-family: 'Roboto', sans-serif;
                font-weight: normal;
                margin-top: -5px;
                padding: 0;
                margin: 0;
                margin-bottom: 10px;
            }
            .pacc-nav {
                clear: both;
                margin-bottom: 30px;
                width: 100%;
                font-size: 16px;
            }
            .pacc-nav th {
                height: 35px;
                border-bottom: 1px solid #849795;
                color: #849795;
                font-weight: normal;
            }
            .pacc-nav th:last-child {
                text-align: right;
            }
            .mount-item-opt {
                font-size: 12px;
                padding-bottom: 10px;
                width: 100%;
            }
            .pacc-nav tr td.pacc-stat-lbl,
            .pacc-nav tr td.pacc-stat-val {
                height: 35px;
                border-top: 1px dotted #000;
            }
            .pacc-nav tr:first-child td.pacc-stat-lbl,
            .pacc-nav tr:first-child td.pacc-stat-val {
                border-top: none;
            }
            .pacc-nav tr:nth-child(2) td.pacc-stat-lbl,
            .pacc-nav tr:nth-child(2) td.pacc-stat-val {
                border-top: none;
            }
            .pacc-nav tr td:last-child {
                text-align: right;
                font-size: 16px;
            }
            .pacc-nav-bestsells table {
                width: 100%;
                font-size: 14px;
            }
            .pacc-nav-bestsells tr td {
                height: 30px;
                border-bottom: 1px dotted #000;
            }
            .pacc-nav-bestsells tr:last-child td {
                border-bottom: none;
            }
            .pacc-nav-bestsells tr td:last-child {
                text-align: right;
            }
            .pacc-nav-bestsells table a {
                color: #000;
                text-decoration: none;
            }
            .pacc-nav tr td.mount-total {
                height: 35px;
                border-top: 1px solid #849795;
                border-bottom: 1px solid #849795;
                color: #849795;
            }
            .attention-wrap {
                padding: 30px 30px;
                background-color: #efefef;
                margin-top: 40px;
            }
            .attention-title {
                text-transform: uppercase;
                color: #849795;
            }
            .pricelist-footer {
                position: absolute;
                bottom: 0;
                font-size: 10px;
                width: 100%;
                margin-bottom: 10px;
            }
            .footer-title {
                width: 25%;
            }
            .footer-title span {
                color: #9c9c9d;
            }
            .footer-addr {
                font-size: 10px;
                color: #9c9c9d;
                width: 49%;
                text-align: center;
            }
            .footer-site {
                width: 25%;
                text-align: right;
            }
            .footer-site a{
                font-size: 12px;
                color: #849795;
                text-decoration: none;
                text-align: center;
            }
        </style>
    </head>

    <body>
    <img src="<?=$_SERVER["DOCUMENT_ROOT"]?>/img/e-logo.jpg" class="logo">

    <div class="header-title">
        <h1>Предварительная <br>стоимость монтажа</h1>
    </div>
    <table class="pacc-nav">
        <tr>
            <th>
                Изделие
            </th>
            <th>Стоимость монтажа</th>
        </tr>
        <?
        $list = getMountList($list);
        $m_arr = Array('1542','1543','1544','1545','1546','1547');
        foreach($list['items'] as $item) {
             ?>
            <tr>
                <td class="pacc-stat-lbl">
                    <?=$item['name']?> - <?=$item['qty']?> шт.
                </td>
                <td class="pacc-stat-val"><?=__cost_format($item['total_item'])?></td>
            </tr>
            <?if(in_array($item['section_id'],$m_arr)) { ?>
                <tr>
                    <td colspan="2">
                        <table class="mount-item-opt">
                            <?if(empty($item['options'])) { ?>
                                <tr><td>Не указаны параметры для расчета<td></tr>
                            <? } else {
                                foreach ($item['options'] as $o=>$option) { ?>
                                    <tr><td><?=$o+1?>. <?=$option['opt']?>: <span style="text-transform: lowercase;"><?=$option['val']?></span><td></tr>
                                <? }
                            }?>
                        </table>
                    </td>
                </tr>
            <? } ?>
        <? } ?>
        <?/*<tr>
            <td class="pacc-stat-lbl">
                Расходные материалы для монтажа
            </td>
            <td class="pacc-stat-val"><?=__cost_format($list['total'] - $list['total']/1.05)?></td>
        </tr>*/?>
        <tr>
            <td class="mount-total">
                ИТОГО
            </td>
            <td class="mount-total"><?=__cost_format($list['total'])?></td>
        </tr>
    </table>

    <div class="attention-wrap">
        <div class="attention-title">Обратите внимание!</div>
        <p>Установка производится на&nbsp;подготовленную поверхность (прошпаклеванную, прогрунтованную).</p>
        <p>Перед началом работ на&nbsp;объекте заказчик предоставляет пригодные для&nbsp;работы леса или&nbsp;туры.</p>
        <p>Окончательная стоимость определяется после&nbsp;выезда специалиста на&nbsp;объект.</p>
        <p style="color: #849795;">Гарантия качества на&nbsp;выполняемые работы специалистами компании 5&nbsp;лет.</p>
        <p>Минимальная стоимость заказа на&nbsp;монтаж архитектурного декора 15&nbsp;000&nbsp;RUB.</p>
    </div>

    <table class="pricelist-footer">
        <tr>
            <td class="footer-title">
                OOO "Декор"<br>
                <span>
        тел./факс: +7 495 315-31-10
        </span>
            </td>
            <td class="footer-addr">
                142350, Московская обл., городской округ Чехов,<br>
                дер. Ивачково, ул. Лесная, вл. 12, стр. 7
            </td>
            <td class="footer-site">
                <a href="https://perfom-decor.ru/" target="_blank">www.perfom-decor.ru</a>
            </td>
        </tr>
    </table>
    </body>
    </html>
    <?
    $html = ob_get_clean();
    return $html;
}

function getEmailList() {
    $list = $_COOKIE['mount'];
    $list = getMountList($list);
    $m_arr = Array('1542','1543','1544','1545','1546','1547');

    ob_start();
    ?>
    <table style="width: 720px; margin: 12px 0px;">
        <tbody>
        <tr>
            <th style="text-align: left;font: bold 16px Arial, Helvetica, sans-serif;color: #849795;border-bottom: 1px solid #849795;padding-bottom:10px;">
                Изделие
            </th>
            <th style=" text-align: right;font: bold 16px Arial, Helvetica, sans-serif;color: #849795;border-bottom: 1px solid #849795;padding-bottom:10px;">
                Стоимость монтажа
            </th>
        </tr>
        <? foreach($list['items'] as $i=>$item) {?>
            <tr>
                <td style="text-align: left;font: 16px Arial, Helvetica, sans-serif;color: #000;padding-top:10px;padding-bottom:10px;<?if($i > 0) echo ' ;border-top: 1px dotted #000;'?>">
                    <b><?=$item['name']?> - <?=$item['qty']?> шт.</b>
                </td>
                <td style="text-align: right;font: 16px Arial, Helvetica, sans-serif;color: #000;padding-top:10px;padding-bottom:10px;<?if($i > 0) echo ' ;border-top: 1px dotted #000;'?>">
                    <b><?=__cost_format($item['total_item'])?></b>
                </td>
            </tr>
            <?if(in_array($item['section_id'],$m_arr)) { ?>
                <tr>
                    <td colspan="2" style="padding-bottom:10px;">
                        <table style="width:100%;">
                            <?if(empty($item['options'])) { ?>
                                <tr><td style="text-align: left;font: 14px Arial, Helvetica, sans-serif;color: #000;border: none;">Не указаны параметры для расчета<td></tr>
                            <? } else {
                                foreach ($item['options'] as $o=>$option) { ?>
                                    <tr><td style="text-align: left;font: 14px Arial, Helvetica, sans-serif;color: #000;border: none;"><?=$o+1?>. <?=$option['opt']?>: <span style="font: 14px Arial, Helvetica, sans-serif;color: #000;text-transform: lowercase;"><?=$option['val']?></span><td></tr>
                                <? }
                            }?>
                        </table>
                    </td>
                </tr>
            <? } ?>
        <? } ?>
        <?/*<tr>
            <td style="text-align: left;font: 16px Arial, Helvetica, sans-serif;color: #000;border-top: 1px dotted #000;padding-top:10px;padding-bottom:10px;">
                <b>Расходные материалы для монтажа</b>
            </td>
            <td style="text-align: right;font: 16px Arial, Helvetica, sans-serif;color: #000;border-top: 1px dotted #000;padding-top:10px;padding-bottom:10px;">
                <b><?=__cost_format($list['total'] - $list['total']/1.05)?></b>
            </td>
        </tr>*/?>
        <tr>
            <td style="text-align: left;font: 16px Arial, Helvetica, sans-serif;color: #849795;border-top: 1px solid #849795;border-bottom: 1px solid #849795;padding-top:10px;padding-bottom:10px;">
                <b>Итого</b>
            </td>
            <td style="text-align: right;font: 16px Arial, Helvetica, sans-serif;color: #849795;border-top: 1px solid #849795;border-bottom: 1px solid #849795;padding-top:10px;padding-bottom:10px;">
                <b><?=__cost_format($list['total'])?></b>
            </td>
        </tr>

        </tbody>
    </table>
    <br>
    <table class="attention-wrap" style="width:720px;background-color:#efefef;padding: 30px 30px;">
        <tr class="attention-title"><td style="text-align: left;font: 16px Arial, Helvetica, sans-serif;color: #000;padding-top:10px;padding-bottom:10px;border:none;"><b>Обратите внимание!</b></td></tr>
        <tr><td style="text-align: left;font: 16px Arial, Helvetica, sans-serif;color: #000;padding-top:10px;padding-bottom:10px;border:none;">Установка производится на&nbsp;подготовленную поверхность (прошпаклеванную, прогрунтованную).</td></tr>
        <tr><td style="text-align: left;font: 16px Arial, Helvetica, sans-serif;color: #000;padding-top:10px;padding-bottom:10px;border:none;">Перед началом работ на&nbsp;объекте заказчик предоставляет пригодные для&nbsp;работы леса или&nbsp;туры.</td></tr>
        <tr><td style="text-align: left;font: 16px Arial, Helvetica, sans-serif;color: #000;padding-top:10px;padding-bottom:10px;border:none;">Окончательная стоимость определяется после&nbsp;выезда специалиста на&nbsp;объект.</td></tr>
        <tr ><td style="text-align: left;font: 16px Arial, Helvetica, sans-serif;color: #849795;padding-top:10px;padding-bottom:10px;border:none;">Гарантия качества на&nbsp;выполняемые работы специалистами компании 5&nbsp;лет.</td></tr>
        <tr><td style="text-align: left;font: 16px Arial, Helvetica, sans-serif;color: #000;padding-top:10px;padding-bottom:10px;border:none;">Минимальная стоимость заказа на&nbsp;монтаж архитектурного декора 15&nbsp;000&nbsp;RUB.</td></tr>
    </table>
    <?
    $html = ob_get_clean();
    return $html;
}

function getNotes() {
    ob_start(); ?>
        <div>
            <div class="dwnld-mod-rule">
                <div class="dwnld-rule-nmbr">1</div>
                <div class="dwnld-rule-txt">Справа от&nbsp;количества нажмите  «выбрать&nbsp;параметры».</div>
            </div>
            <div class="dwnld-mod-rule">
                <div class="dwnld-rule-nmbr">2</div>
                <div class="dwnld-rule-txt">Установите флажки напротив каждого параметра монтажа и нажимте кнопку&nbsp;«рассчитать».</div>
            </div>
            <div class="dwnld-mod-rule">
                <div class="dwnld-rule-nmbr">3</div>
                <div class="dwnld-rule-txt">Повторите пп.&nbsp;1&#8209;2 для&nbsp;каждой позиции.</div>
            </div>
        </div>
        <div>
            <div class="dwnld-mod-rule">
                <div class="dwnld-rule-nmbr">4</div>
                <div class="dwnld-rule-txt">Если вам необходимо ввести разные параметры монтажа для&nbsp;одного элемента, <br>скопируйте его, нажав кнопку «Дублировать», и&nbsp;повторите пп.&nbsp;1&#8209;2.</div>
            </div>
            <div class="dwnld-mod-rule">
                <div class="dwnld-rule-nmbr">5</div>
                <div class="dwnld-rule-txt">Для сохранения расчета нажмите кнопку «Сохранить расчет и&nbsp;вернуться в&nbsp;корзину».<br> Сохраненный расчет будет отправлен на&nbsp;e&#8209;mail вам и&nbsp;в&nbsp;сервисную службу только после&nbsp;оформления&nbsp;заказа.</div>
            </div>
        </div>


<?
    $html = ob_get_clean();
    return $html;
}