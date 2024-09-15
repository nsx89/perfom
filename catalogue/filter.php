<?
function FilterRegion($CATALOG_FILTER) {
    global $APPLICATION;

    $my_city = $APPLICATION->get_cookie('my_city');

    $arFilter_city = Array('IBLOCK_ID' => 7, 'ACTIVE' => 'Y', 'ID' => $my_city);
    $db_list_city = CIBlockElement::GetList(Array('SORT' => 'ASC'), $arFilter_city);
    $city_cur = $db_list_city->GetNextElement();

    if ($city_cur) {
        $city_cur = array_merge($city_cur->GetFields(), $city_cur->GetProperties());
        $currency_infо = get_currency_info($city_cur['country']['VALUE']);
        $arFilter_element = $currency_infо['filter'];
        if($arFilter_element) $CATALOG_FILTER[] = array("LOGIC" => "OR", $arFilter_element, array(">PROPERTY_COMPOSITEPART" => 0));
    }
    return $CATALOG_FILTER;
}

function CatalogFilter($section_id,$filter = null,$classes = null,$styles = null) {
    //$CATALOG_FILTER = Array('IBLOCK_ID' => IB_CATALOGUE, "SECTION_ID" => $section_id, 'INCLUDE_SUBSECTIONS' => 'Y', "ACTIVE" => "Y", "ACTIVE_DATE" => "Y", "!PROPERTY_HIDE_GENERAL" => "Y");
    $CATALOG_FILTER = Array('IBLOCK_ID' => IB_CATALOGUE, "SECTION_ID" => $section_id, 'INCLUDE_SUBSECTIONS' => 'Y', "ACTIVE" => "Y", "ACTIVE_DATE" => "Y", "PROPERTY_SHOW_PERFOM" => "Y");
    $CATALOG_FILTER = FilterRegion($CATALOG_FILTER);

    foreach ($filter as $k => $itemf) {
        if ($k == 'catalog_PRICE_1') {
            if ($itemf['from']) {
                $CATALOG_FILTER['>='.$k] = $itemf['from'];
            }
            if ($itemf['to']) {
                $CATALOG_FILTER['<='.$k] = $itemf['to'];
            }
        } else {
            if ($itemf['from']) {
                $CATALOG_FILTER['>=PROPERTY_'.$k] = $itemf['from'];
            }
            if ($itemf['to']) {
                $CATALOG_FILTER['<=PROPERTY_'.$k] = $itemf['to'];
            }
        }
    }
			if($classes) $classes = explode(',', $classes);
			if($styles) $styles = explode(',', $styles);
			// Пока не разделяем, фильтр в один массив $classes нет необходимости

            if($classes || $styles) {
                $temp_f['LOGIC'] = 'AND';

                $temp_classes['LOGIC'] = 'OR';
                if (count($classes) > 0) {
                    foreach ($classes as $c) {
                        $temp_classes['PROPERTY_'.$c] = 'Y';
                    }
                    $temp_f[] = $temp_classes;
                }

                $temp_styles['LOGIC'] = 'OR';
                if (count($styles) > 0) {
                    foreach ($styles as $c) {
                        $temp_styles['PROPERTY_'.$c] = 'Y';
                    }
                    $temp_f[] = $temp_styles;
                }

                $CATALOG_FILTER[] = $temp_f;
            }

	
    return $CATALOG_FILTER;
}

function CatalogFullFilter($section_id,$filter = null,$classes = null,$styles = null) {
    $CATALOG_FILTER_FULL = Array('IBLOCK_ID' => IB_CATALOGUE, "SECTION_ID" => $section_id, 'INCLUDE_SUBSECTIONS' => 'Y', "ACTIVE" => "Y", "ACTIVE_DATE" => "Y", "PROPERTY_SHOW_PERFOM" => "Y");
    $CATALOG_FILTER_FULL = FilterRegion($CATALOG_FILTER_FULL);
    
			$classes = explode(',', $classes);
			$styles = explode(',', $styles);
			// Пока не разделяем, фильтр в один массив $classes нет необходимости

			/*
			$temp_classes['LOGIC'] = 'OR';
			if (count($classes) > 0) {
				foreach ($classes as $c) {
					$temp_classes['PROPERTY_'.$c] = 'Y';
				}
				$CATALOG_FILTER_FULL[] = $temp_classes;
            }
			*/
			/*
			$temp_f['LOGIC'] = 'AND';

			$temp_classes['LOGIC'] = 'OR';
			if (count($classes) > 0) {
				foreach ($classes as $c) {
					$temp_classes['PROPERTY_'.$c] = 'Y';
				}
				$temp_f[] = $temp_classes;
            }

			$temp_styles['LOGIC'] = 'OR';
			if (count($styles) > 0) {
				foreach ($styles as $c) {
					$temp_styles['PROPERTY_'.$c] = 'Y';
				}
				$temp_f[] = $temp_styles;
            }

			$CATALOG_FILTER_FULL[] = $temp_f;
			*/

    return $CATALOG_FILTER_FULL;
}

function renderFilters($section_id,$product_items_full,$filter=false,$classes=false,$styles=false) {

    global $APPLICATION;
    $my_city = $APPLICATION->get_cookie('my_city');

    $arFilter = Array('IBLOCK_ID'=>IB_CATALOGUE, 'ID'=>$section_id, 'ACTIVE'=>'Y');
    $db_list = CIBlockSection::GetList(Array(), $arFilter, false, array('UF_*'));
    $sec_info = Array();
    $last_section = Array();
    while($section_item = $db_list->GetNext()) {
        $sec_info[$section_item['ID']] = $section_item;
        if(empty($last_section)) $last_section = $section_item;
    }

    $cl_class = array('class_01','class_02','class_03','class_20');
    $cl_style = array('class_04','class_05','class_06','class_07','class_08','class_09','class_10','class_11');

    $fl_classes = explode(',', $classes);
    $fl_styles = explode(',', $styles);

    // Пока не разделяем, фильтр в один массив $classes нет необходимости
    $fl_classes = array_merge($fl_classes, $fl_styles);

    // Проверка наличия класса, стиля
    $cl_class_f = false;
    $cl_style_f = false;
    if (count($fl_classes) > 0) {
        $cl_array = array();
        foreach ($fl_classes as $cl) {
            $cl_array[$cl] = true;
            if (in_array($cl, $cl_class)) $cl_class_f = true;
            if (in_array($cl, $cl_style)) $cl_style_f = true;
        }
    }

    $all = array();
    $group = array();
    $filter_full = array();

    $gr_class_array = array();

    $db = CIBlockProperty::GetList(array('SORT'=>'ASC'), array('IBLOCK_ID'=>IB_CATALOGUE, "ACTIVE" => "Y"));
    while ($prop = $db->GetNext()) {
        // Проверка классов и стилей
        if (preg_match("/class_(\d+)/", $prop['CODE'])) {
            $arFilter = Array('IBLOCK_ID' => IB_CATALOGUE, "SECTION_ID" => $section_id, "INCLUDE_SUBSECTIONS" => "Y", "ACTIVE" => "Y", "ACTIVE_DATE" => "Y", array("PROPERTY_".$prop['CODE'] => "Y"));
            $testgr_db = CIBlockElement::GetList(array(), $arFilter, array('PROPERTY_'.$prop['CODE']), false, array('PROPERTY_'.$prop['CODE']));
            $have_element = $testgr_db->GetNext();
            if (!$have_element) {
                continue;
            }
            $gr_class_array[$prop['CODE']] = true;
        }


        if (!preg_match("/S(\d+)/", $prop['CODE'])) continue;

        if ($prop['FILTRABLE'] != 'Y') continue;

        //проверяем, есть ли установленные свойства в товарах
        $arFilter = Array('IBLOCK_ID' => IB_CATALOGUE, "SECTION_ID" => $section_id, "INCLUDE_SUBSECTIONS" => "Y", "ACTIVE" => "Y", "ACTIVE_DATE" => "Y");
        $testgr_db = CIBlockElement::GetList(array(), $arFilter, array('PROPERTY_'.$prop['CODE']), false, array('PROPERTY_'.$prop['CODE']));
        $have_element = $testgr_db->GetNext();
        //print_r($have_element);
        if (!$have_element) {
            continue;
        }

        $all[$prop['NAME']] = $prop;
        $group[] = 'PROPERTY_'.$prop['CODE'];
    }

    ksort($all);


    // Установка флага работы группы фильтра
    $gr_class_f = false; $gr_style_f = false;
    foreach ($gr_class_array as $key => $gr) {
        if (in_array($key,$cl_class)) $gr_class_f = true;
        if (in_array($key,$cl_style)) $gr_style_f = true;

    }


    foreach($product_items_full as $product) {
        $ids_full[] = $product['ID'];
        if (!isset($sec_full) || !in_array($product['IBLOCK_SECTION_ID'], $sec_full)) $sec_full[] = $product['IBLOCK_SECTION_ID'];
        foreach ($all as $prop) {
            $num = $product[$prop['CODE']]['VALUE'];
            $num = 0 + str_replace(',', '.', $num);
            $num = ceil($num);
            if ($num > 0) {
                if (!isset($filter_full[$prop['CODE']]['from']) || $filter_full[$prop['CODE']]['from'] > $num) $filter_full[$prop['CODE']]['from'] = $num;
                if (!isset($filter_full[$prop['CODE']]['to']) || $filter_full[$prop['CODE']]['to'] < $num) $filter_full[$prop['CODE']]['to'] = $num;
                if (!isset($filter_full[$prop['CODE']]['sec']) || !in_array($product['IBLOCK_SECTION_ID'], $filter_full[$prop['CODE']]['sec'])) $filter_full[$prop['CODE']]['sec'][] = $product['IBLOCK_SECTION_ID'];
            }
        }
    }
    $arFilter_city = Array('IBLOCK_ID' => 7, 'ACTIVE' => 'Y', 'ID' => $my_city);
    $db_list_city = CIBlockElement::GetList(Array('SORT' => 'ASC'), $arFilter_city);
    $city_cur = $db_list_city->GetNextElement();
    $city_cur = array_merge($city_cur->GetFields(), $city_cur->GetProperties());
    $val_rub = false;
    // установка валют
    $currency_infо = get_currency_info($city_cur['country']['VALUE']);
    $name_price = $currency_infо['price'];
    $code_price = $currency_infо['code'];
    if($name_price) {
        $all[$name_price] = array('NAME' => $name_price, 'CODE'=>$code_price);
    } else {
        $val_rub = true;
    }
    if ($val_rub) {
        $filter_full['catalog_PRICE_1']['from'] = CPrice::GetList(array(), array('PRODUCT_ID'=>$ids_full), array('MIN' => "PRICE"))->Fetch();
        $filter_full['catalog_PRICE_1']['to'] = CPrice::GetList(array(), array('PRODUCT_ID'=>$ids_full), array('MAX' => "PRICE"))->Fetch();
        $filter_full['catalog_PRICE_1']['from'] = ceil($filter_full['catalog_PRICE_1']['from']['PRICE']);
        $filter_full['catalog_PRICE_1']['to'] = ceil($filter_full['catalog_PRICE_1']['to']['PRICE']);
        if(!isset($filter_full['catalog_PRICE_1']['sec'])) $filter_full['catalog_PRICE_1']['sec'] = $sec_full;
        if (($filter_full['catalog_PRICE_1']['to'] && $filter_full['catalog_PRICE_1']['from'])) {
            $all['Цена'] = array('NAME' => 'Цена', 'CODE'=>'catalog_PRICE_1');
        }
    }

ob_start();

if($gr_class_f || $gr_style_f || count($all)) { ?>

    <div class="cat-filters-wrap mob-filt-wrap" data-type="filt-item">
        <div class="mob-filters-title" data-type="filt-title">Фильтры <i class="icon-angle-down-2"></i></div>
        <div class="mob-filt-cont" data-type="filt-cont">

            <? if ($gr_class_f) { ?>
                <?
                //доп.проверка, для каких категорий есть классы, если категорий несколько

                $has_class = Array();
                $prop_arr = Array("LOGIC" => "OR");
                foreach($cl_class as $class) {
                    $prop_arr[] = array("PROPERTY_".$class => "Y");
                }
                foreach($section_id as $sec) {
                    $arFilter = Array('IBLOCK_ID' => IB_CATALOGUE, "SECTION_ID" => $sec, "INCLUDE_SUBSECTIONS" => "Y", "ACTIVE" => "Y", "ACTIVE_DATE" => "Y", $prop_arr);
                    $testgr_db = CIBlockElement::GetList(array(), $arFilter, array('PROPERTY_'.$prop['CODE']), false, array('PROPERTY_'.$prop['CODE']));
                    $have_element = $testgr_db->GetNext();
                    if ($have_element) {
                        $has_class[] = $sec_info[$sec]['NAME'];
                    }
                }
                if(count($section_id) != count($has_class)) {
                    $filt_sec = '';
                    foreach ($has_class as $sec) {
                        if (!empty($sec)) {
                            $filt_sec .= $sec.', ';
                        }
                    }
                    $filt_sec = mb_substr($filt_sec,0,-2);
                } ?>

                <?
                /*
                1. Класс "с орнаментом" убрать в категориях "карнизы".
                2. В категории "карнизы" убираем стили "классика" и "барокко"
                3. В категории "молдинги" классы убираем
                4. В категории "плинтусы" стили "классика" и "барокко" убираем
                
                1542 - карнизы
                1544 - молдинги
                1546 - плинтусы
                1550 - розетки
                1552 - кессоны
                1548 - угловые
                */
                $SECT_ID = 0; //id категории
                if (is_array($section_id)) $SECT_ID = $section_id[0];
                else $SECT_ID = $section_id;
                //echo $SECT_ID;
                ?>

                <? if (!in_array($SECT_ID, [1544])) { ?>
                    <div class="cat-filt-item active" data-type="filt-item">
                        <div class="cat-filt-item-title" data-type="filt-title">Класс <i class="icon-angle-down-2"></i></div>
                        <? if(!empty($filt_sec)) { ?>
                            <div class="cat-filt-item-sec">Только для: <?=$filt_sec?></div>
                        <? } ?>
                        <div class="cat-filt-item-cont" data-type="filt-cont">
                            <ul>
                                <? if ($gr_class_array['class_02'] && !in_array($SECT_ID, [1542, 1544])) { ?>
                                    <li <?=$cl_array['class_02']?'class="active" ':''?>data-type="filter-class"><a data-val="class_02">с орнаментом</a></li>
                                <? } ?>
                                <? if ($gr_class_array['class_03']) { ?>
                                    <li <?=$cl_array['class_03']?'class="active" ':''?>data-type="filter-class"><a data-val="class_03">гладкие</a></li>
                                <? } ?>
                                <? if ($gr_class_array['class_01']) { ?>
                                    <li <?=$cl_array['class_01']?'class="active" ':''?>data-type="filter-class"><a data-val="class_01">для скрытого освещения</a></li>
                                <? } ?>
                                <? if ($gr_class_array['class_20']) { ?>
                                    <li <?=$cl_array['class_20']?'class="active" ':''?>data-type="filter-class"><a data-val="class_20">для натяжного потолка</a></li>
                                <? } ?>
                            </ul>
                        </div>
                    </div>
                <? } ?>

            <? } ?>

            <? if ($gr_style_f && 1 == 2) { ?>
                <?
                //доп.проверка, для каких категорий есть стили, если категорий несколько
                $has_class = Array();
                $prop_arr = Array("LOGIC" => "OR");
                foreach($cl_style as $class) {
                    $prop_arr[] = array("PROPERTY_".$class => "Y");
                }
                foreach($section_id as $sec) {
                    $have_element = '';
                    $arFilter = Array('IBLOCK_ID' => IB_CATALOGUE, "SECTION_ID" => $sec, "INCLUDE_SUBSECTIONS" => "Y", "ACTIVE" => "Y", "ACTIVE_DATE" => "Y", $prop_arr);
                    $testgr_db = CIBlockElement::GetList(array(), $arFilter, array('PROPERTY_'.$prop['CODE']), false, array('PROPERTY_'.$prop['CODE']));
                    $have_element = $testgr_db->GetNext();
                    if ($have_element) {
                        $has_class[] = $sec_info[$sec]['NAME'];
                    }
                }
                if(count($section_id) != count($has_class)) {
                    $filt_sec = '';
                    foreach ($has_class as $sec) {
                        if (!empty($sec)) {
                            $filt_sec .= $sec.', ';
                        }
                    }
                    $filt_sec = mb_substr($filt_sec,0,-2);
                } ?>
                <div class="cat-filt-item active" data-type="filt-item">
                    <div class="cat-filt-item-title" data-type="filt-title">Стиль <i class="icon-angle-down-2"></i></div>
                    <? if(!empty($filt_sec)) { ?>
                        <div class="cat-filt-item-sec">Только для: <?=$filt_sec?></div>
                    <? } ?>
                    <div class="cat-filt-item-cont" data-type="filt-cont">
                        <ul>
                            <? if ($gr_class_array['class_04'] && !in_array($SECT_ID, [1542, 1546])) { ?>
                                <li <?=$cl_array['class_04']?'class="active" ':''?>data-type="filter-style"><a data-val="class_04">классика</a></li>
                            <? } ?>
                            <? if ($gr_class_array['class_05'] && !in_array($SECT_ID, [1542, 1546])) { ?>
                                <li <?=$cl_array['class_05']?'class="active" ':''?>data-type="filter-style"><a data-val="class_05">барокко</a></li>
                            <? } ?>
                            <? if ($gr_class_array['class_06']) { ?>
                                <li <?=$cl_array['class_06']?'class="active" ':''?>data-type="filter-style"><a data-val="class_06">неоклассика</a></li>
                            <? } ?>
                            <? if ($gr_class_array['class_07']) { ?>
                                <li <?=$cl_array['class_07']?'class="active" ':''?>data-type="filter-style"><a data-val="class_07">ар деко</a></li>
                            <? } ?>
                            <? if ($gr_class_array['class_08']) { ?>
                                <li <?=$cl_array['class_08']?'class="active" ':''?>data-type="filter-style"><a data-val="class_08">контемпорари</a></li>
                            <? } ?>
                            <? if ($gr_class_array['class_09']) { ?>
                                <li <?=$cl_array['class_09']?'class="active" ':''?>data-type="filter-style"><a data-val="class_09">лофт</a></li>
                            <? } ?>
                            <? if ($gr_class_array['class_10']) { ?>
                                <li <?=$cl_array['class_10']?'class="active" ':''?>data-type="filter-style"><a data-val="class_10">прованс</a></li>
                            <? } ?>
                            <? if ($gr_class_array['class_11']) { ?>
                                <li <?=$cl_array['class_11']?'class="active" ':''?>data-type="filter-style"><a data-val="class_11">минимализм</a></li>
                            <? } ?>
                        </ul>
                    </div>
                </div>
            <? } ?>

            <? foreach ($all as $if) {?>

                <?if ((isset($filter_full[$if['CODE']])&&isset($filter_full[$if['CODE']]['from'])?$filter_full[$if['CODE']]['from']:0) ==
                    (isset($filter_full[$if['CODE']])&&isset($filter_full[$if['CODE']]['to'])?$filter_full[$if['CODE']]['to']:0)) continue;

                $min = $filter_full[$if['CODE']]['from'];
                $max = $filter_full[$if['CODE']]['to'];
                if (!$filter[$if['CODE']]['from']) $filter[$if['CODE']]['from'] = $min;
                if (!$filter[$if['CODE']]['to']) $filter[$if['CODE']]['to'] = $max;
                ?>

                <?
                /*убрать длину из фильтров*/
                if($if['CODE']=='S2') continue;

                /*В случае карнизов только параметры S1 и S3 */
                if ((count($section_id) == 1) && ($last_section['CODE'] == 'karnizy') && (($if['CODE'] != 'S1') && ($if['CODE'] != 'S3') && ($if['CODE'] != 'catalog_PRICE_1'))) continue;

                /*В случае розеток только параметры S14 и S7 */
                if ((count($section_id) == 1) && ($last_section['CODE'] == 'rozetki') && (($if['CODE'] != 'S14') && ($if['CODE'] != 'S7') && ($if['CODE'] != 'catalog_PRICE_1'))) continue;
                ?>

            <div class="cat-filt-item params active" data-type="filt-item">
                <div class="cat-filt-item-title" data-type="filt-title"><?=$if['NAME']?><i class="icon-angle-down-2"></i></div>
                <?if(count($section_id) != count($filter_full[$if['CODE']]['sec'])) {
                    $filt_sec = '';
                    foreach ($filter_full[$if['CODE']]['sec'] as $sec) {
                        if (!empty($sec_info[$sec]['NAME'])) {
                             $filt_sec .= $sec_info[$sec]['NAME'].', ';
                        }
                    }
                    $filt_sec = mb_substr($filt_sec,0,-2);
                    ?>
                    <? if(!empty($filt_sec)) { ?>
                        <div class="cat-filt-item-sec">Только для: <?=$filt_sec?></div>
                    <? } ?>
                <?}?>
                <div class="cat-filt-item-cont" data-type="filt-cont">
                    <div class="category-filter-wrap">
                        <div class="category-filter"
                             data-name="category-filter"
                             data-type="<?=$if['CODE']?>"
                             data-fmin="<?=$min?>"
                             data-fmax="<?=$max?>"
                             data-from="<?=$filter[$if['CODE']]['from']?>" <?//значение from по фильтру, если нет - минимум?>
                             data-to="<?=$filter[$if['CODE']]['to']?>"><?//значение to по фильтру, если нет - максимум?>
                        </div>
                    </div>
                    <div class="category-filter-values">
                        <div class="category-filter-from">от <span data-type="from"></span></div>
                        <div class="category-filter-to">до <span data-type="to"></span></div>
                    </div>
                </div>
            </div>

            <? } ?>

            <button class="cat-reset-filters cat-apply-filters" data-type="apply-filt">Применить</button>
            <button class="cat-reset-filters" data-type="reset-filt">Сбросить все фильтры</button>
        </div>
    </div>

<? }
$html = ob_get_clean();
return $html;
}
?>
