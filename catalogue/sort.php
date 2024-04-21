<?
function getSortParams($section_id) {
    $sec_sort = Array();
    $arFilter = Array('IBLOCK_ID'=>IB_CATALOGUE, 'ACTIVE'=>'Y', 'ID'=>$section_id);
    $db_list = CIBlockSection::GetList(Array(), $arFilter, false, array('UF_*'));
    if (intval($db_list->SelectedRowsCount())>0) {
        while($section_item = $db_list->GetNext()) {

            //сортировка: ищем пересечения параметров при нескольких категориях (+1 запрос в бд в каждой категории)
            //глушим, потому что сортировать, например, по ширине плинтусы и колонны - нелогично
            /*
            $sort_arr = Array();
            $sort_ids = Array();
            if($section_item['UF_FILTER1'] != '') $sort_ids[] = $section_item['UF_FILTER1'];
            if($section_item['UF_FILTER2'] != '') $sort_ids[] = $section_item['UF_FILTER2'];
            if(!empty($sort_ids)) {
                $rsEnum = CUserFieldEnum::GetList(array(), array("ID" =>$sort_ids));
                while($arEnum = $rsEnum->GetNext()) {
                    $sort_arr[$arEnum["XML_ID"]] = $arEnum["VALUE"];
                }
            }
            if(empty($sec_sort)) {
                $sec_sort = $sort_arr;
            } else {
                $sec_sort = array_uintersect($sec_sort,$sort_arr,"strcasecmp");
            }*/

            //сортировка по параметрам только если 1 категория
            if (intval($db_list->SelectedRowsCount()) == 1) {
                $sort_ids = array();
                if ($section_item['UF_FILTER1'] != '') $sort_ids[] = $section_item['UF_FILTER1'];
                if ($section_item['UF_FILTER2'] != '') $sort_ids[] = $section_item['UF_FILTER2'];
                if (!empty($sort_ids)) {
                    $rsEnum = CUserFieldEnum::GetList(array(), array("ID" => $sort_ids));
                    while ($arEnum = $rsEnum->GetNext()) {
                        $sec_sort[$arEnum["XML_ID"]] = $arEnum["VALUE"];
                    }
                }
            }
        }
    }
    return $sec_sort;
}

function getSort($section_id,$get_sort_params = false) {

    $wtf = Array();

    $sec_sort = getSortParams($section_id);

    if(isset($_COOKIE['sort_params'])) {
        $sort_params = json_decode($_COOKIE['sort_params']);
    } else {
        // по умолчанию если есть высота по стене, то сортировка по ней
        if(!isset($sort_params->prop_param)) {
            if(!empty($sec_sort)) {
                foreach($sec_sort as $k=>$v) {
                    if($v == 'S3') {
                        $sort_params->prop_param->val = 'S3';
                        $sort_params->prop_param->sort = 'asc';
                        $sec_id = '';
                        foreach($section_id as $id) {
                            $sec_id .= $id.'-';
                        }
                        $sec_id = substr($sec_id,0,-1);
                        $sort_params->id_sec = $sec_id;
                    }
                }
            }
        }
        if(!isset($sort_params->main_param)) {
            $sort_params->main_param = 6;
        }
    }

    $section_id = implode(',',$section_id);
    if($sort_params->prop_param && $section_id == $sort_params->id_sec) {
        // по свойству
        if ($sort_params->main_param && $sort_params->main_param == 1) {
            $wtf = Array('PROPERTY_'.$sort_params->prop_param->val=>$sort_params->prop_param->sort,'catalog_PRICE_1'=>'ASC');  // по цене по возрастанию
        } elseif ($sort_params->main_param && $sort_params->main_param == 2) {
            $wtf = Array('PROPERTY_'.$sort_params->prop_param->val=>$sort_params->prop_param->sort,'catalog_PRICE_1'=>'desc');  // по цене по убыванию
        } elseif ($sort_params->main_param && $sort_params->main_param == 3) {
            $wtf = Array('PROPERTY_'.$sort_params->prop_param->val=>$sort_params->prop_param->sort,'PROPERTY_ARTICUL'=>'ASC');   // по артикулу по возрастанию
        } elseif ($sort_params->main_param && $sort_params->main_param == 4) {
            $wtf = Array('PROPERTY_'.$sort_params->prop_param->val=>$sort_params->prop_param->sort,'PROPERTY_ARTICUL'=>'desc');   // по артикулу по убыванию
        } elseif ($sort_params->main_param && $sort_params->main_param == 5) {
            $wtf = Array('PROPERTY_'.$sort_params->prop_param->val=>$sort_params->prop_param->sort,'created'=>'desc');   // новинки
        } elseif ($sort_params->main_param && $sort_params->main_param == 6) {
            $wtf = Array('PROPERTY_'.$sort_params->prop_param->val=>$sort_params->prop_param->sort,'sort'=>'asc');   // популярное
        } else {
            $wtf = Array('PROPERTY_'.$sort_params->prop_param->val=>$sort_params->prop_param->sort,'catalog_PRICE_1'=>'ASC');
        }
    }
    elseif ($sort_params->main_param && $sort_params->main_param == 1) {
        $wtf = Array('catalog_PRICE_1'=>'ASC');  // по цене по возрастанию
    } elseif ($sort_params->main_param && $sort_params->main_param == 2) {
        $wtf = Array('catalog_PRICE_1'=>'desc');  // по цене по убыванию
    } elseif ($sort_params->main_param && $sort_params->main_param == 3) {
        $wtf = Array('PROPERTY_ARTICUL'=>'ASC');   // по артикулу по возрастанию
    } elseif ($sort_params->main_param && $sort_params->main_param == 4) {
        $wtf = Array('PROPERTY_ARTICUL'=>'desc');   // по артикулу по убыванию
    } elseif ($sort_params->main_param && $sort_params->main_param == 5) {
        $wtf = Array('created'=>'desc');   // новинки
    } elseif ($sort_params->main_param && $sort_params->main_param == 6) {
        $wtf = Array('sort'=>'asc');   // популярное
    } else {
        $wtf = Array('sort'=>'asc');
    }
    if($get_sort_params) {
        return $sort_params;
    } else {
        return $wtf;
    }
}

//вывод сортировки в каталоге
function renderSort($section_id) {

    $sec_sort = getSortParams($section_id);
    $sort_params = getSort($section_id,true);
    $sort = '';
    ob_start();
?>
    <div class="cat-sort-item cat-sort-item-new<?=($sort_params->main_param == 6 && !$sort_params->prop_param->val)?' active':''?>">
        <div class="cat-sort-item-title" data-val="6" data-type="sort-param" data-sort="asc">
            популярное
        </div>
    </div>
    <? /*
    <div class="cat-sort-item cat-sort-item-new<?=($sort_params->main_param == 5 && !$sort_params->prop_param->val)?' active':''?>">
        <div class="cat-sort-item-title" data-val="5" data-type="sort-param" data-sort="asc">
            новинки
        </div>
    </div>
    */ ?>

    <?foreach($sec_sort as $k=>$v) { ?>
        <div class="cat-sort-item<?=($k == $sort_params->prop_param->val)?' active':''?>">
            <div class="cat-sort-item-title" data-val="<?=$k?>" data-type="sort-param" data-sort="<?if($sort_params->prop_param->sort == 'asc' && $k == $sort_params->prop_param->val) {echo 'desc';} else {echo 'asc';}?>">
                <?=$v?>
            </div>
            <i class="icon-sort-arrow icon-arrow-up<?=($sort_params->prop_param->sort == 'asc'&& $k == $sort_params->prop_param->val)?' active':''?>" data-type="sort-param" data-val="<?=$k?>" data-sort="asc" title="По возрастанию"></i>
            <i class="icon-sort-arrow icon-arrow-down<?=($sort_params->prop_param->sort == 'desc'&& $k == $sort_params->prop_param->val)?' active':''?>" data-type="sort-param" data-val="<?=$k?>" data-sort="desc" title="По убыванию"></i>
        </div>
        <?if($k == $sort_params->prop_param->val) $fparam_sort = true;?>
    <? } ?>
    <div class="cat-sort-item <?=(!$fparam_sort && ($sort_params->main_param == 1)||!$fparam_sort && ($sort_params->main_param == 2))?' active':''?>">
        <div class="cat-sort-item-title" data-type="sort-param" data-val="<?if($sort_params->main_param == 1) {echo 2;} else {echo 1;}?>">
            цена
        </div>
        <i class="icon-sort-arrow icon-arrow-up<?=(!$fparam_sort && ($sort_params->main_param == 1))?' active':''?>" data-type="sort-param" data-val="1" title="По возрастанию"></i>
        <i class="icon-sort-arrow icon-arrow-down<?=(!$fparam_sort && ($sort_params->main_param == 2))?' active':''?>" data-type="sort-param" data-val="2" title="По убыванию"></i>
    </div>
    <div class="cat-sort-item <?=(!$fparam_sort && ($sort_params->main_param == 3)||!$fparam_sort && ($sort_params->main_param == 4))?' active':''?>">
        <div class="cat-sort-item-title" data-type="sort-param" data-val="<?if($sort_params->main_param == 3) {echo 4;} else {echo 3;}?>">
            артикул
        </div>
        <i class="icon-sort-arrow icon-arrow-up<?=(!$fparam_sort && ($sort_params->main_param == 3))?' active':''?>" data-type="sort-param" data-val="3" title="По возрастанию"></i>
        <i class="icon-sort-arrow icon-arrow-down<?=(!$fparam_sort && ($sort_params->main_param == 4))?' active':''?>" data-type="sort-param" data-val="4" title="По убыванию"></i>
    </div>
    <?$sort = ob_get_clean();
    return $sort;
}