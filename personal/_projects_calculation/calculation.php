<?
require_once($_SERVER["DOCUMENT_ROOT"] . "/personal/projects_calculation/data.php");


/**
 * ========================= выбираем метод для конкретного карниза ============================
 *
 * @param $data_arr
 * @param $room
 * @return array
 */
function get_calculation_result($data_arr,$room) {

    switch ($room['cornice_article']) {
        case '1.50.501':
            return get_first_method($data_arr,$room);
            break;
        /*case '1.50.504':
            return get_third_method($data_arr,$room['walls'],$room['cornice_article']);
            break;*/
        case '1.50.502':
        case '1.50.503':
        case '1.50.504':
        case '1.50.524':
            return get_third_method($data_arr,$room);
            break;
    }
}

/**
 * ========================================= функции перебора (для остатков) ====================================
 * @param $superset
 * @param $size
 * @return array
 */
function combos($superset, $size) {
    $result = array();
    if(count($superset) < $size) return $result;
    $done = false;
    $current_combo = '';
    $distance_back = '';
    $new_last_index = '';
    $indexes = array();
    $indexes_last = $size - 1;
    $superset_last = count($superset) - 1;

    for ($i = 0; $i < $size; ++$i) {
        $indexes[$i] = $i;
    }

    while (!$done) {
        $current_combo = array();
        for ($i = 0; $i < $size; ++$i) {
            array_push($current_combo,$superset[$indexes[$i]]);
        }
        array_push($result,$current_combo);

        if ($indexes[$indexes_last] == $superset_last) {
            $done = true;
            for ($i = $indexes_last - 1; $i > -1 ; --$i) {
                $distance_back = $indexes_last - $i;
                $new_last_index = $indexes[$indexes_last - $distance_back] + $distance_back + 1;
                if ($new_last_index <= $superset_last) {
                    $indexes[$indexes_last] = $new_last_index;
                    $done = false;
                    break;
                }
            }
            if (!$done) {
                ++$indexes[$indexes_last - $distance_back];
                --$distance_back;
                for (; $distance_back; --$distance_back) {
                    $indexes[$indexes_last - $distance_back] = $indexes[$indexes_last - $distance_back - 1] + 1;
                }
            }
        }
        else {
            ++$indexes[$indexes_last];
        }
    }
    return $result;
}
/**
 * @param $a - входящий массив остатков
 * @param $b - количество необходимых раппортов
 * @return array
 */
function sub_sets($a,$b) {
    $subset = array();
    $initval = 1;
    do {
        $subset[$initval] = combos($a, $initval);   // Combination function call
        $initval++;

    } while ($initval < count($a));

    $first_result = false;
    $arr = array();

    foreach($subset as $set) {
        foreach ($set as $arr) {
            $sum = 0;
            foreach($arr as $item) {
                $sum += $item;
            }
            if($sum == $b) {
                $first_result = true;
                break;
            }
        }
        if($first_result) break;
    }
    if(!$first_result) return array();

    return $arr;
}

/**
 * ========================= 1.50.501 ============================
 *
 * @param $data_arr
 * @param $walls
 * @param $cornice_article
 * @return array
 */
function get_first_method($data_arr,$room) {

    $walls = $room['walls'];
    $cornice_article = $room['cornice_article'];

    $final_result = array();

    $cornice_params = get_cornice_params($cornice_article);

    $rest = isset($data_arr['rest'][$cornice_article]) ? $data_arr['rest'][$cornice_article] : Array(); //остатки

    foreach($walls as $k => $wall) {

        $prev_rest_rapport_qty = 0; //количество раппортов в остатках с предыдущих стен
        foreach($rest as $item) {
            $prev_rest_rapport_qty += $item['length'];
        }

        $using_rest = array();
        $kept_rest = array();
        $result = Array();

        $wall = $wall['wall_info'];

        $corner_length = $wall['corner_1']['length'] + $wall['corner_2']['length'];
        $total_length = $wall['length'] - $corner_length; //длина стены без углов
        $total_rapport_qty = floor($total_length/$cornice_params['rapport_length']); //количество целых раппортов для стены
        $total_need_cutting = $total_length - $total_rapport_qty*$cornice_params['rapport_length']; // длина подгонки

        $cutting_type = '';

        /*---------------------------------- подгонка --------------------------------------*/

        //торцовка без подгонки возле угла

        if($wall['corner_1']['type'] == 'trimming' || $wall['corner_2']['type'] == 'trimming') {

            if($wall['corner_1']['type'] == 'trimming' && $wall['corner_2']['type'] == 'trimming' || $wall['corner_1']['type'] == 'trimming' && $wall['corner_1']['trimming_fit'] != 'yes' || $wall['corner_2']['type'] == 'trimming' && $wall['corner_2']['trimming_fit'] != 'yes') {
                $cutting_type = 'trimming';
                $total_rapport_qty++;
            }

        }
        //торцовка с подгонкой возле угла

        if($wall['corner_1']['type'] == 'trimming' && $wall['corner_1']['trimming_fit'] == 'yes' || $wall['corner_2']['type'] == 'trimming' && $wall['corner_2']['trimming_fit'] == 'yes') {

            $fit_wall_cutting = 0;
            $y = 0;

            $cutting_type = 'trimming';
            if($wall['corner_1']['type'] == 'trimming') $fit_wall_calculation = $walls[$wall['corner_1']['trimming_fit_wall']]['calculation'];
            if($wall['corner_2']['type'] == 'trimming') $fit_wall_calculation = $walls[$wall['corner_2']['trimming_fit_wall']]['calculation'];
            foreach($fit_wall_calculation as $fit_wall) {
                if($wall['corner_1']['type'] == 'trimming') $fit_wall = array_reverse($fit_wall);
                foreach($fit_wall as $fit_wall_cornice) {
                    foreach($fit_wall_cornice as $fit_wall_cut) {
                        if($fit_wall_cut['type'] == 'cutting waste') {
                            $fit_wall_cutting += $cornice_params['max_cutting'] - ($fit_wall_cut['end'] - $fit_wall_cut['start']);
                            break;
                        }
                    }
                }
            }

            //на всякий случай пересчитаем количество необходимых карнизов и оставшуюся подгонку
            if($fit_wall_cutting != 0) { // если указанная подгонка найдена
                $total_length -= $fit_wall_cutting;
                $total_rapport_qty = floor($total_length/$cornice_params['rapport_length']); //количество целых раппортов для стены
                $total_need_cutting = $total_length - $total_rapport_qty*$cornice_params['rapport_length']; // длина подгонки
                $total_rapport_qty ++; //подгонка торцовки

            }

        }

        // подгонка для углов
        if($total_need_cutting != 0 && $cutting_type != 'trimming' && $cutting_type != 'trimming fit') {

            if($total_length > (4 * $cornice_params['rapport_length'])) { // 2 подгонки > 200
                $cutting_type = 'edge';
                $cutting_item = ceil($total_need_cutting/2); // длина одного участка подгонки
            }
            elseif($total_length > (2 *$cornice_params['rapport_length'])  && $total_need_cutting <= $cornice_params['max_cutting']*2) { // 1 подгонка >100 и подгонка <= 40
                $cutting_type = 'center';
                $cutting_item = $total_need_cutting; // длина одного участка подгонки
            } else { // ошибка
                $result['error'] = 'Для использования данного расчета введенная длина стены слишком мала. <br>Проект будет расчитан без данного участка. <br>Пожалуйста, обратитесь к нашим специалистам для расчета нетипичной стены.';
                $walls[$k]['calculation'] = $result;
                continue;
            }

        }

        //добавляем раппорты на углы
        if($wall['corner_1']['type'] != 'trimming') $total_rapport_qty += 1;
        if($wall['corner_2']['type'] != 'trimming') $total_rapport_qty += 1;

        // здесь все сложнее, разбираемся, какое минимальное количество карнизов нам надо:
        // если подгонка = 0 : 1
        // если длина одного участка подгонки <= 20 : 2
        // если длина одного участка подгонки > 20 : 3

        $min_total_cornice_qty = 0;
        $waste_rapport_qty = 0; //раппорты, которые пойдут в отходы возле угла и подгонок из-за толщины пильного диска

        if($wall['corner_1']['type'] != 'trimming' && $wall['corner_2']['type'] != 'trimming' || $wall['corner_1']['type'] == 'trimming' && $wall['corner_1']['trimming_fit'] == 'yes' || $wall['corner_2']['type'] == 'trimming' && $wall['corner_2']['trimming_fit'] == 'yes') {
            if($total_need_cutting == 0) {
                $min_total_cornice_qty = 1;
                $waste_rapport_qty = 2;
            } elseif($cutting_item <= $cornice_params['max_cutting']) {
                $min_total_cornice_qty = 2;
                $waste_rapport_qty = 4;
            } else {
                $min_total_cornice_qty = 3;
                $waste_rapport_qty = 6;
            }
            if($wall['corner_1']['type'] == 'trimming' && $wall['corner_1']['trimming_fit'] == 'yes' || $wall['corner_2']['type'] == 'trimming' && $wall['corner_2']['trimming_fit'] == 'yes') $waste_rapport_qty = ceil($waste_rapport_qty/2);
        }

        $total_cornice_qty = ceil(($total_rapport_qty - $prev_rest_rapport_qty + $waste_rapport_qty)/$cornice_params['rapport_qty']);//с учетом остатков

        $total_cornice_rapport_qty = $total_cornice_qty*$cornice_params['rapport_qty']+$prev_rest_rapport_qty; //количество раппортов в финальном количестве карнизов и остатках

        $use_rest = true;

        if($total_cornice_qty < $min_total_cornice_qty) {
            $total_cornice_qty = $min_total_cornice_qty;
            $min_total_cornice_rapport_qty = $total_cornice_qty * $cornice_params['rapport_qty'];
            if($total_cornice_rapport_qty <= $min_total_cornice_rapport_qty) {
                $total_cornice_rapport_qty = $min_total_cornice_rapport_qty;
                $use_rest = false;
            }
        }

        //если получается 0 карнизов (из-за остатков)
        if($total_cornice_qty <= 0) {
            $total_cornice_qty = 1;
            $total_cornice_rapport_qty = $total_cornice_qty*$cornice_params['rapport_qty'];
            $use_rest = false;
        }

        $result['cornice_qty'] = $total_cornice_qty;



        $rapport_rest = $total_cornice_rapport_qty - $total_rapport_qty - $waste_rapport_qty; //количество лишних раппортов


        /*------------------------------ разбираемся с остатками --------------------------------------------*/

        //если оcтатки с прошлых стен не нужны
        if($use_rest && $rapport_rest >= $prev_rest_rapport_qty) {
            $rapport_rest -= $prev_rest_rapport_qty;
            $use_rest = false;
            $total_rapport_qty += $prev_rest_rapport_qty;
        }

        if($use_rest && $prev_rest_rapport_qty != 0) {


            $need_rapport_qty = $prev_rest_rapport_qty - $rapport_rest; //необходимое количество раппортов

            if($need_rapport_qty >= $cornice_params['rapport_qty']) {
                $total_cornice_qty += floor($prev_rest_rapport_qty/$cornice_params['rapport_qty']);
                $need_rapport_qty = $total_rapport_qty - $total_cornice_qty*$cornice_params['rapport_qty'];
                $result['cornice_qty'] = $total_cornice_qty;
                $use_rest = false;
                //todo: проверка по другим карнизам - false и && в проверке
            }

            if($use_rest == true) {
                //если есть один или несколько кусоков, сумма которых равна необходимому количеству раппортов, или можо обрезать до необходимого количества

                $rest_length = array();
                foreach($rest as $r_item) {
                    $rest_length[] = $r_item['length'];
                }
                $r = sub_sets($rest_length,$need_rapport_qty);

                //если есть необходимое количество кусков, которые не надо резать
                if(!empty($r) && count($r) <= 2) {
                    foreach($rest as $i => $item) {
                        foreach($r as $p => $part) {
                            if($part == $item['length']) {
                                $arr = array(
                                    'rapport' => $item['length'],
                                    'waste' => '',
                                    'room' => $item['room'],
                                    'wall' => $item['wall'],
                                    'cornice' => $item['cornice'],
                                );
                                unset($rest[$i]);
                                unset($r[$p]);
                                $using_rest[] = $arr;
                            }
                        }
                    }
                    $rest = array_values($rest);
                    $rapport_rest = 0;

                } else { //если резать надо - не выбираем, берем подряд и обрезаем на нужном количестве
                    //проверим, может есть куски больше, чем нам нужно, берем минимально больший
                    $needed_piece = '';
                    foreach($rest as $i => $item) {
                        if($need_rapport_qty <= $item['length']) {
                            if($needed_piece == '' || $rest[$needed_piece] > $item['length']) {
                                $needed_piece = $i;
                            }
                        }
                    }
                    // если есть нужный кусок
                    if($needed_piece != '') {
                        $arr = array(
                            'rapport' => $rest[$needed_piece]['length'],
                            'waste' => $rest[$needed_piece] - $need_rapport_qty,
                            'room' => $rest[$needed_piece]['room'],
                            'wall' => $rest[$needed_piece]['wall'],
                            'cornice' => $rest[$needed_piece]['cornice'],
                        );
                        unset($rest[$needed_piece]);
                        $using_rest[] = $arr;
                        $rest = array_values($rest);
                        $rapport_rest = 0;
                    } else { //если нет - берем все подряд
                        if($rest[0]['length'] + $rest[1]['length'] >= $need_rapport_qty) {//но не больше 2
                            $sum = 0;
                            foreach($rest as $i => $item) {
                                $sum += $item['length'];
                                if($sum < $need_rapport_qty) {
                                    $arr = array(
                                        'rapport' => $item['length'],
                                        'waste' => '',
                                        'room' => $item['room'],
                                        'wall' => $item['wall'],
                                        'cornice' => $item['cornice'],
                                    );
                                    unset($rest[$i]);
                                    $using_rest[] = $arr;
                                } else {
                                    $excess = $sum - $need_rapport_qty; //лишнее
                                    $arr = array(
                                        'rapport' => $item['length'],
                                        'waste' => $excess,
                                        'room' => $item['room'],
                                        'wall' => $item['wall'],
                                        'cornice' => $item['cornice'],
                                    );
                                    unset($rest[$i]);
                                    $using_rest[] = $arr;
                                    break;
                                }
                            }
                            $rest = array_values($rest);
                            $rapport_rest = 0;
                        } else { // если > 2, считаем без остатков
                            $total_cornice_qty = ceil($total_rapport_qty/$cornice_params['rapport_qty']);
                            $total_cornice_rapport_qty = $total_cornice_qty*$cornice_params['rapport_qty'];
                            $result['cornice_qty'] = $total_cornice_qty;
                            $rapport_rest = $total_cornice_rapport_qty - $total_rapport_qty; //количество лишних раппортов
                        }

                    }

                }
            }

        }


        /*--------------------------------- пилим карнизы -----------------------------------------------------*/

        for($n = 0; $n < $total_cornice_qty; $n++) {

            $i = 0;

            $result['cutting'][$n] = array();//показываем целый карниз

            //первый карниз - с углами
            if($n == 0) {
                if($wall['corner_1']['type'] != 'trimming') { //если не торцовка
                    $result['cutting'][$n][$i]['rapport'] = 1;
                    $result['cutting'][$n][$i]['start'] = -$cornice_params['max_cutting'];
                    $result['cutting'][$n][$i]['end'] = $cornice_params['rapport_length'];
                    $result['cutting'][$n][$i++]['type'] = 'corner';
                    if($wall['corner_2']['type'] != 'trimming' || $wall['corner_2']['type'] == 'trimming' && $wall['corner_2']['trimming_fit'] == 'yes' ) {
                        //отходы
                        $result['cutting'][$n][$i]['rapport'] = 2;
                        $result['cutting'][$n][$i]['start'] = 2;
                        $result['cutting'][$n][$i]['end'] = 2;
                        $result['cutting'][$n][$i++]['type'] = 'waste';
                    }

                }
                if($wall['corner_2']['type'] != 'trimming') { //если не торцовка
                    if($wall['corner_1']['type'] != 'trimming' || $wall['corner_1']['type'] == 'trimming' && $wall['corner_1']['trimming_fit'] == 'yes' ) {
                        //отходы
                        $result['cutting'][$n][$i]['rapport'] = $cornice_params['rapport_qty'] - 1;
                        $result['cutting'][$n][$i]['start'] = $cornice_params['rapport_qty'] - 1;
                        $result['cutting'][$n][$i]['end'] = $cornice_params['rapport_qty'] - 1;
                        $result['cutting'][$n][$i++]['type'] = 'waste';
                    }
                    //угол
                    $result['cutting'][$n][$i]['rapport'] = $cornice_params['rapport_qty'];
                    $result['cutting'][$n][$i]['start'] = 0;
                    $result['cutting'][$n][$i]['end'] = $cornice_params['rapport_length'] + $cornice_params['max_cutting'];
                    $result['cutting'][$n][$i++]['type'] = 'corner';
                }
            }

            $first_cutting_part = 0;
            $second_cutting_part = 0;
            if($total_need_cutting != 0) {
                if ($cutting_item <= $cornice_params['max_cutting']) {
                    $first_cutting_part = $cutting_item;
                } else {
                    $first_cutting_part = 12;
                    $second_cutting_part = $cutting_item - 12;
                }
            }

            //второй карниз - подгонка
            if($n == 1 && $first_cutting_part != 0) {
                if($cutting_type != 'trimming' || $cutting_type == 'trimming' && $wall['corner_1']['type'] == 'trimming' && $wall['corner_1']['trimming_fit'] == 'yes') { //если не торцовка или торцовка с подгонкой

                    if($first_cutting_part != $cornice_params['max_cutting']) {
                        //отходы от подгонки
                        $result['cutting'][$n][$i]['rapport'] = 0;
                        $result['cutting'][$n][$i]['start'] = -$cornice_params['max_cutting'];
                        $result['cutting'][$n][$i]['end'] = -$first_cutting_part;
                        $result['cutting'][$n][$i++]['type'] = 'cutting waste';
                    }
                    //подгонка
                    $result['cutting'][$n][$i]['rapport'] = 1;
                    $result['cutting'][$n][$i]['start'] = -$first_cutting_part;
                    $result['cutting'][$n][$i]['end'] = $cornice_params['rapport_length'];
                    $result['cutting'][$n][$i++]['type'] = 'cutting';
                    //отходы
                    $result['cutting'][$n][$i]['rapport'] = 2;
                    $result['cutting'][$n][$i]['start'] = 2;
                    $result['cutting'][$n][$i]['end'] = 2;
                    $result['cutting'][$n][$i++]['type'] = 'waste';

                }
                if($cutting_type != 'trimming' || $cutting_type == 'trimming' && $wall['corner_2']['type'] == 'trimming' && $wall['corner_2']['trimming_fit'] == 'yes') { //если не торцовка или торцовка с подгонкой

                    //отходы
                    $result['cutting'][$n][$i]['rapport'] = $cornice_params['rapport_qty']-1;
                    $result['cutting'][$n][$i]['start'] = $cornice_params['rapport_qty']-1;
                    $result['cutting'][$n][$i]['end'] = $cornice_params['rapport_qty']-1;
                    $result['cutting'][$n][$i++]['type'] = 'waste';
                    //подгонка
                    $result['cutting'][$n][$i]['rapport'] = $cornice_params['rapport_qty'];
                    $result['cutting'][$n][$i]['start'] = 0;
                    $result['cutting'][$n][$i]['end'] = $cornice_params['rapport_length'] + $first_cutting_part;
                    $result['cutting'][$n][$i++]['type'] = 'cutting';
                    //отходы от подгонки
                    if($first_cutting_part != $cornice_params['max_cutting']) {
                        $result['cutting'][$n][$i]['rapport'] = $cornice_params['rapport_qty'] + 1;
                        $result['cutting'][$n][$i]['start'] = $cornice_params['rapport_length'] + $first_cutting_part;
                        $result['cutting'][$n][$i]['end'] = $cornice_params['rapport_length'] + $cornice_params['max_cutting'];
                        $result['cutting'][$n][$i++]['type'] = 'cutting waste';
                    }
                }

            }

            //третий карниз - подгонка
            if($n == 2 && $second_cutting_part != 0) {
                if($cutting_type != 'trimming' || $cutting_type == 'trimming' && $wall['corner_2']['type'] == 'trimming' && $wall['corner_2']['trimming_fit'] == 'yes' ) { //если не торцовка или торцовка  подгонкой

                    //отходы от подгонки
                    $result['cutting'][$n][$i]['rapport'] = 0;
                    $result['cutting'][$n][$i]['start'] = -$cornice_params['max_cutting'];
                    $result['cutting'][$n][$i]['end'] = -$second_cutting_part;
                    $result['cutting'][$n][$i++]['type'] = 'cutting waste';
                    //подгонка
                    $result['cutting'][$n][$i]['rapport'] = 1;
                    $result['cutting'][$n][$i]['start'] = -$second_cutting_part;
                    $result['cutting'][$n][$i]['end'] = $cornice_params['rapport_length'];
                    $result['cutting'][$n][$i++]['type'] = 'cutting';
                    //отходы
                    $result['cutting'][$n][$i]['rapport'] = 2;
                    $result['cutting'][$n][$i]['start'] = 2;
                    $result['cutting'][$n][$i]['end'] = 2;
                    $result['cutting'][$n][$i++]['type'] = 'waste';

                }
                if($cutting_type != 'trimming' || $cutting_type == 'trimming' && $wall['corner_1']['type'] == 'trimming' && $wall['corner_1']['trimming_fit'] == 'yes') { //если не торцовка или торцовка с подгонкой
                    //отходы
                    $result['cutting'][$n][$i]['rapport'] = $cornice_params['rapport_qty']-1;
                    $result['cutting'][$n][$i]['start'] = $cornice_params['rapport_qty']-1;
                    $result['cutting'][$n][$i]['end'] = $cornice_params['rapport_qty']-1;
                    $result['cutting'][$n][$i++]['type'] = 'waste';
                    //подгонка
                    $result['cutting'][$n][$i]['rapport'] = $cornice_params['rapport_qty'];
                    $result['cutting'][$n][$i]['start'] = 0;
                    $result['cutting'][$n][$i]['end'] = $cornice_params['rapport_length'] + $second_cutting_part;
                    $result['cutting'][$n][$i++]['type'] = 'cutting';
                    //отходы от подгонки
                    $result['cutting'][$n][$i]['rapport'] = $cornice_params['rapport_qty'] + 1;
                    $result['cutting'][$n][$i]['start'] = $cornice_params['rapport_length'] + $second_cutting_part;
                    $result['cutting'][$n][$i]['end'] = $cornice_params['rapport_length'] + $cornice_params['max_cutting'];
                    $result['cutting'][$n][$i++]['type'] = 'cutting waste';

                }
            }

            //торцовка

            //полная
            if($total_need_cutting != 0) {
                if($wall['corner_1']['type'] == 'trimming' && $wall['corner_1']['trimming_fit'] != 'yes' && $wall['corner_2']['type'] == 'trimming' && $wall['corner_2']['trimming_fit'] != 'yes') {
                    if($n == $total_cornice_qty - 1) {
                        //отходы от торцовки
                        $result['cutting'][$n][$i]['rapport'] = $cornice_params['rapport_qty'];
                        $result['cutting'][$n][$i]['start'] = $total_need_cutting;
                        $result['cutting'][$n][$i]['end'] = $cornice_params['rapport_length'];
                        $result['cutting'][$n][$i++]['type'] = 'cutting waste';
                    }

                } elseif ($cutting_type == 'trimming' && $wall['corner_2']['type'] != 'trimming' && $wall['corner_1']['trimming_fit'] != 'yes') {
                    //торцовка без подгонки слева
                    if($n == $total_cornice_qty - 1) {
                        //отходы от торцовки
                        $result['cutting'][$n][$i]['rapport'] = 1;
                        $result['cutting'][$n][$i]['start'] = 0;
                        $result['cutting'][$n][$i]['end'] =  $cornice_params['rapport_length'] - $total_need_cutting;
                        $result['cutting'][$n][$i++]['type'] = 'cutting waste';
                    }
                } elseif ($cutting_type == 'trimming' && $wall['corner_1']['type'] != 'trimming' && $wall['corner_2']['trimming_fit'] != 'yes') {
                    //торцовка без подгонки cghfdf
                    if($n == $total_cornice_qty - 1) {
                        //отходы от торцовки
                        $result['cutting'][$n][$i]['rapport'] = $cornice_params['rapport_qty'];
                        $result['cutting'][$n][$i]['start'] = $total_need_cutting;
                        $result['cutting'][$n][$i]['end'] =  $cornice_params['rapport_length'];
                        $result['cutting'][$n][$i++]['type'] = 'cutting waste';
                    }
                } elseif ($cutting_type == 'trimming' && $wall['corner_1']['trimming_fit'] == 'yes') {
                    //торцовка с подгонкой слева
                    if($n == 0) {
                        //отходы от торцовки
                        $result['cutting'][$n][$i]['rapport'] = 1;
                        $result['cutting'][$n][$i]['start'] = 0;
                        $result['cutting'][$n][$i]['end'] = $cornice_params['rapport_length'] - $total_need_cutting;
                        $result['cutting'][$n][$i++]['type'] = 'cutting waste';
                    }
                } elseif ($cutting_type == 'trimming' && $wall['corner_2']['trimming_fit'] == 'yes') {
                    //торцовка с подгонкой справа
                    if($n == $total_cornice_qty - 1) {
                        //отходы от торцовки
                        if($total_cornice_qty == 2 && $fit_wall_cutting!=0) {
                            $result['cutting'][$n][$i]['rapport'] = $cornice_params['rapport_qty']-2;
                            $result['cutting'][$n][$i]['start'] = $total_need_cutting;
                            $result['cutting'][$n][$i]['end'] =  $cornice_params['rapport_length'];
                            $result['cutting'][$n][$i++]['type'] = 'cutting waste';
                        } else {
                            $result['cutting'][$n][$i]['rapport'] = $cornice_params['rapport_qty'];
                            $result['cutting'][$n][$i]['start'] = $total_need_cutting;
                            $result['cutting'][$n][$i]['end'] =  $cornice_params['rapport_length'];
                            $result['cutting'][$n][$i++]['type'] = 'cutting waste';
                        }

                    }
                }
            }

            //print_r('$rapport_rest - '.$rapport_rest);echo '<br>';

            //если есть лишние раппорты
            if($rapport_rest != 0) {
                if($total_cornice_qty > 3) { //пилим с последнего целого
                    if($n == $total_cornice_qty - 1) {
                        if($rapport_rest > 1) {
                            //остатки
                            $result['cutting'][$n][$i]['rapport'] = '1 - '. ($rapport_rest - 1);
                            $result['cutting'][$n][$i]['start'] = 1;
                            $result['cutting'][$n][$i]['end'] = $rapport_rest - 1;
                            $result['cutting'][$n][$i++]['type'] = 'rest';

                            $rest_item = array();
                            $rest_item['length'] = $rapport_rest - 1;
                            $rest_item['room'] = $room['name'];
                            $rest_item['wall'] = $k + 1;
                            $rest_item['cornice'] = $n + 1;

                            $kept_rest[] = $rest_item;
                        }
                        //отходы из-за толщины пильного диска
                        if($rapport_rest > 0) {
                            $result['cutting'][$n][$i]['rapport'] = $rapport_rest;
                            $result['cutting'][$n][$i]['start'] = $rapport_rest;
                            $result['cutting'][$n][$i]['end'] = $rapport_rest;
                            $result['cutting'][$n][$i++]['type'] = 'waste';
                        }
                    }


                }
                elseif($cutting_type != 'trimming') {

                    $start_cutting = 3;
                    $end_cutting = 2;

                    if($n == 0) { //пилим с перового

                        if($rapport_rest < $cornice_params['rapport_qty'] - 4) {
                            if($rapport_rest > 1) {
                                //остатки
                                $result['cutting'][$n][$i]['rapport'] = '3 - '. ($rapport_rest+1);
                                $result['cutting'][$n][$i]['start'] = 3;
                                $result['cutting'][$n][$i]['end'] = $rapport_rest+1;
                                $result['cutting'][$n][$i++]['type'] = 'rest';

                                $rest_item = array();
                                $rest_item['length'] = $rapport_rest - 1;
                                $rest_item['room'] = $room['name'];
                                $rest_item['wall'] = $k + 1;
                                $rest_item['cornice'] = $n + 1;

                                $kept_rest[] = $rest_item;

                            }
                            //отходы из-за толщины пильного диска

                            $result['cutting'][$n][$i]['rapport'] = $rapport_rest+2;
                            $result['cutting'][$n][$i]['start'] = $rapport_rest+2;
                            $result['cutting'][$n][$i]['end'] = $rapport_rest+2;
                            $result['cutting'][$n][$i++]['type'] = 'waste';

                            $rapport_rest = 0;
                        } else {
                            //остатки
                            $result['cutting'][$n][$i]['rapport'] = '3 - '. ($cornice_params['rapport_qty'] - 2);
                            $result['cutting'][$n][$i]['start'] = 3;
                            $result['cutting'][$n][$i]['end'] = $cornice_params['rapport_qty'] - 2;
                            $result['cutting'][$n][$i++]['type'] = 'rest';

                            $rest_item = array();
                            $rest_item['length'] = $cornice_params['rapport_qty'] - 4;
                            $rest_item['room'] = $room['name'];
                            $rest_item['wall'] = $k + 1;
                            $rest_item['cornice'] = $n + 1;

                            $kept_rest[] = $rest_item;

                            $rapport_rest = $rapport_rest - ($cornice_params['rapport_qty'] - 4);

                        }

                    }

                    //пилим со второго, если не хватает первого
                    if($rapport_rest != 0 && $n == 1) {
                        if($rapport_rest < $cornice_params['rapport_qty'] - 4) {
                            if($rapport_rest > 1) {
                                //остатки
                                $result['cutting'][$n][$i]['rapport'] = '3 - '. ($rapport_rest+1);
                                $result['cutting'][$n][$i]['start'] = 3;
                                $result['cutting'][$n][$i]['end'] = $rapport_rest+1;
                                $result['cutting'][$n][$i++]['type'] = 'rest';

                                $rest_item = array();
                                $rest_item['length'] = $rapport_rest - 1;
                                $rest_item['room'] = $room['name'];
                                $rest_item['wall'] = $k + 1;
                                $rest_item['cornice'] = $n + 1;

                                $kept_rest[] = $rest_item;

                            }
                            //отходы из-за толщины пильного диска

                            $result['cutting'][$n][$i]['rapport'] = $rapport_rest+2;
                            $result['cutting'][$n][$i]['start'] = $rapport_rest+2;
                            $result['cutting'][$n][$i]['end'] = $rapport_rest+2;
                            $result['cutting'][$n][$i++]['type'] = 'waste';

                            $rapport_rest = 0;
                        } else {
                            //остатки
                            $result['cutting'][$n][$i]['rapport'] = '3 - '. ($cornice_params['rapport_qty'] - 2);
                            $result['cutting'][$n][$i]['start'] = 3;
                            $result['cutting'][$n][$i]['end'] = $cornice_params['rapport_qty'] - 2;
                            $result['cutting'][$n][$i++]['type'] = 'rest';

                            $rest_item = array();
                            $rest_item['length'] = $cornice_params['rapport_qty'] - 4;
                            $rest_item['room'] = $room['name'];
                            $rest_item['wall'] = $k + 1;
                            $rest_item['cornice'] = $n + 1;

                            $kept_rest[] = $rest_item;

                            $rapport_rest = $rapport_rest - ($cornice_params['rapport_qty'] - 4);

                        }
                    }

                    //пилим с третьего, если не хватает первого и второго
                    if($rapport_rest != 0 && $n == 2) {
                        if($rapport_rest < $cornice_params['rapport_qty'] - 4) {
                            if($rapport_rest > 1) {
                                //остатки
                                $result['cutting'][$n][$i]['rapport'] = '3 - '. ($rapport_rest+1);
                                $result['cutting'][$n][$i]['start'] = 3;
                                $result['cutting'][$n][$i]['end'] = $rapport_rest+1;
                                $result['cutting'][$n][$i++]['type'] = 'rest';

                                $rest_item = array();
                                $rest_item['length'] = $rapport_rest - 1;
                                $rest_item['room'] = $room['name'];
                                $rest_item['wall'] = $k + 1;
                                $rest_item['cornice'] = $n + 1;

                                $kept_rest[] = $rest_item;


                            }
                            //отходы из-за толщины пильного диска

                            $result['cutting'][$n][$i]['rapport'] = $rapport_rest+2;
                            $result['cutting'][$n][$i]['start'] = $rapport_rest+2;
                            $result['cutting'][$n][$i]['end'] = $rapport_rest+2;
                            $result['cutting'][$n][$i++]['type'] = 'waste';

                            $rapport_rest = 0;
                        } else {
                            //остатки
                            $result['cutting'][$n][$i]['rapport'] = '3 - '. ($cornice_params['rapport_qty'] - 2);
                            $result['cutting'][$n][$i]['start'] = 3;
                            $result['cutting'][$n][$i]['end'] = $cornice_params['rapport_qty'] - 2;
                            $result['cutting'][$n][$i++]['type'] = 'rest';

                            $rest_item = array();
                            $rest_item['length'] = $cornice_params['rapport_qty'] - 4;
                            $rest_item['room'] = $room['name'];
                            $rest_item['wall'] = $k + 1;
                            $rest_item['cornice'] = $n + 1;

                            $kept_rest[] = $rest_item;
                            $rapport_rest = $rapport_rest - ($cornice_params['rapport_qty'] - 4);

                        }
                    }

                }
                else { //если торцовка



                    if($n == 0) { //пилим с перового

                        $start_cutting = 1;
                        $end_cutting = $cornice_params['rapport_qty'];

                        $start_waste = 0;
                        $end_waste = 0;
                        if($wall['corner_1']['type'] == 'trimming' && $wall['corner_2']['type'] == 'trimming') { //если полная торцовка
                            if($total_need_cutting != 0 && $total_cornice_qty == $n + 1) {
                                $end_cutting = $cornice_params['rapport_qty'] - 1;
                                $end_waste = 1;
                            }
                        } elseif($wall['corner_1']['type'] == 'trimming') {
                            if($total_need_cutting != 0) {
                                $start_cutting = 2;
                                $start_waste = 1;
                            }
                            $end_cutting = $cornice_params['rapport_qty'] - 2;
                        } elseif($wall['corner_2']['type'] == 'trimming') {
                            $start_cutting = 3;
                            if($total_need_cutting != 0 && $total_cornice_qty == $n + 1) {
                                $end_cutting = $cornice_params['rapport_qty'] - 1;
                                $end_waste = 1;
                            }
                        }

                        if($rapport_rest < ($end_cutting - $start_cutting + 1)) {
                            $end_waste = 1;
                        }
                        if($rapport_rest <= ($end_cutting - $start_cutting + 1) ) {
                            $current_rest = $rapport_rest;
                            $rapport_rest = 0;
                        } else {
                            $current_rest = $end_cutting - $start_cutting + 1;
                            $rapport_rest -= $current_rest;
                        }

                        if($current_rest > 1) {
                            if($start_waste != 0) {
                                //отходы из-за толщины пильного диска
                                $result['cutting'][$n][$i]['rapport'] = $start_cutting;
                                $result['cutting'][$n][$i]['start'] = $start_cutting;
                                $result['cutting'][$n][$i]['end'] = $start_cutting;
                                $result['cutting'][$n][$i++]['type'] = 'waste';
                                $start_cutting++;
                                //$rapport_rest--;
                            }

                            $current_rest -= $start_waste + $end_waste;

                            //остатки
                            $result['cutting'][$n][$i]['rapport'] = $start_cutting. ' - '. ($start_cutting + $current_rest - 1);
                            $result['cutting'][$n][$i]['start'] = $start_cutting;
                            $result['cutting'][$n][$i]['end'] = $start_cutting + $current_rest - 1;
                            $result['cutting'][$n][$i++]['type'] = 'rest';

                            $rest_item = array();
                            $rest_item['length'] = $current_rest;
                            $rest_item['room'] = $room['name'];
                            $rest_item['wall'] = $k + 1;
                            $rest_item['cornice'] = $n + 1;

                            $kept_rest[] = $rest_item;
                        }

                        if($end_waste != 0) {
                            //отходы из-за толщины пильного диска
                            $result['cutting'][$n][$i]['rapport'] = $start_cutting + $current_rest;
                            $result['cutting'][$n][$i]['start'] = $start_cutting + $current_rest;
                            $result['cutting'][$n][$i]['end'] = $start_cutting + $current_rest;
                            $result['cutting'][$n][$i++]['type'] = 'waste';
                        }

                    }

                    //пилим со второго если не хватает первого

                    if($n == 1 && $rapport_rest != 0 ) {

                        $start_cutting = 1;
                        $end_cutting = $cornice_params['rapport_qty'];
                        $start_waste = 0;
                        $end_waste = 0;

                        if($wall['corner_1']['type'] == 'trimming' && $wall['corner_2']['type'] == 'trimming') { //если полная торцовка
                            if($total_need_cutting != 0 && $total_cornice_qty == $n + 1) {
                                $end_cutting = $cornice_params['rapport_qty'] - 1;
                                $end_waste = 1;
                            }
                        } elseif($wall['corner_1']['type'] == 'trimming' && $wall['corner_1']['trimming_fit'] == 'yes' && $first_cutting_part != 0) {
                            if($total_need_cutting != 0) {
                                $start_cutting = 3;
                            }
                        } elseif($wall['corner_2']['type'] == 'trimming' && $wall['corner_2']['trimming_fit'] == 'yes' && $first_cutting_part != 0) {
                            $end_cutting -= 2;
                            if($total_need_cutting != 0 && $total_cornice_qty == $n + 1) {
                                $end_cutting--;
                                $end_waste = 1;
                            }
                        }

                        if($rapport_rest < ($end_cutting - $start_cutting + 1)) {
                            $end_waste = 1;
                        }
                        if($rapport_rest <= ($end_cutting - $start_cutting + 1) ) {
                            $current_rest = $rapport_rest;
                            $rapport_rest = 0;
                        } else {
                            $current_rest = $end_cutting - $start_cutting + 1;
                            $rapport_rest -= $current_rest;
                        }

                        if($current_rest > 1) {
                            if($start_waste != 0) {
                                //отходы из-за толщины пильного диска
                                $result['cutting'][$n][$i]['rapport'] = $start_cutting;
                                $result['cutting'][$n][$i]['start'] = $start_cutting;
                                $result['cutting'][$n][$i]['end'] = $start_cutting;
                                $result['cutting'][$n][$i++]['type'] = 'waste';
                                $start_cutting++;
                                //$rapport_rest--;
                            }

                            $current_rest -= $start_waste + $end_waste;

                            //остатки
                            $result['cutting'][$n][$i]['rapport'] = $start_cutting. ' - '. ($start_cutting + $current_rest - 1);
                            $result['cutting'][$n][$i]['start'] = $start_cutting;
                            $result['cutting'][$n][$i]['end'] = $start_cutting + $current_rest - 1;
                            $result['cutting'][$n][$i++]['type'] = 'rest';

                            $rest_item = array();
                            $rest_item['length'] = $current_rest;
                            $rest_item['room'] = $room['name'];
                            $rest_item['wall'] = $k + 1;
                            $rest_item['cornice'] = $n + 1;

                            $kept_rest[] = $rest_item;

                        }

                        if($end_waste != 0) {
                            //отходы из-за толщины пильного диска
                            $result['cutting'][$n][$i]['rapport'] = $start_cutting + $current_rest;
                            $result['cutting'][$n][$i]['start'] = $start_cutting + $current_rest;
                            $result['cutting'][$n][$i]['end'] = $start_cutting + $current_rest;
                            $result['cutting'][$n][$i++]['type'] = 'waste';
                        }
                    }

                    //пилим с третьего если не хватает второго

                    if($n == 2 && $rapport_rest != 0 ) {

                        $start_cutting = 1;
                        $end_cutting = $cornice_params['rapport_qty'];
                        $start_waste = 0;
                        $end_waste = 0;

                        if($wall['corner_1']['type'] == 'trimming' && $wall['corner_2']['type'] == 'trimming') { //если полная торцовка
                            if($total_need_cutting != 0 && $total_cornice_qty == $n + 1) {
                                $end_cutting = $cornice_params['rapport_qty'] - 1;
                                $end_waste = 1;
                            }
                        } elseif($wall['corner_1']['type'] == 'trimming' && $wall['corner_1']['trimming_fit'] == 'yes' && $first_cutting_part != 0) {
                            if($total_need_cutting != 0) {
                                $end_cutting -= 2;
                            }
                        } elseif($wall['corner_2']['type'] == 'trimming' && $wall['corner_2']['trimming_fit'] == 'yes' && $first_cutting_part != 0) {
                            $start_cutting = 3;
                            if($total_need_cutting != 0 && $total_cornice_qty == $n + 1) {
                                $end_cutting--;
                                $end_waste = 1;
                            }
                        }

                        if($rapport_rest < ($end_cutting - $start_cutting + 1)) {
                            $end_waste = 1;
                        }
                        if($rapport_rest <= ($end_cutting - $start_cutting + 1) ) {
                            $current_rest = $rapport_rest;
                            $rapport_rest = 0;
                        } else {
                            $current_rest = $end_cutting - $start_cutting + 1;
                            $rapport_rest -= $current_rest;
                        }

                        if($current_rest > 1) {
                            if($start_waste != 0) {
                                //отходы из-за толщины пильного диска
                                $result['cutting'][$n][$i]['rapport'] = $start_cutting;
                                $result['cutting'][$n][$i]['start'] = $start_cutting;
                                $result['cutting'][$n][$i]['end'] = $start_cutting;
                                $result['cutting'][$n][$i++]['type'] = 'waste';
                                $start_cutting++;
                            }

                            $current_rest[] -= $start_waste + $end_waste;

                            //остатки
                            $result['cutting'][$n][$i]['rapport'] = $start_cutting. ' - '. ($start_cutting + $current_rest - 1);
                            $result['cutting'][$n][$i]['start'] = $start_cutting;
                            $result['cutting'][$n][$i]['end'] = $start_cutting + $current_rest - 1;
                            $result['cutting'][$n][$i++]['type'] = 'rest';

                            $rest_item = array();
                            $rest_item['length'] = $current_rest;
                            $rest_item['room'] = $room['name'];
                            $rest_item['wall'] = $k + 1;
                            $rest_item['cornice'] = $n + 1;

                            $kept_rest[] = $rest_item;
                        }

                        if($end_waste != 0) {
                            //отходы из-за толщины пильного диска
                            $result['cutting'][$n][$i]['rapport'] = $start_cutting + $current_rest;
                            $result['cutting'][$n][$i]['start'] = $start_cutting + $current_rest;
                            $result['cutting'][$n][$i]['end'] = $start_cutting + $current_rest;
                            $result['cutting'][$n][$i++]['type'] = 'waste';
                        }
                    }

                }
            }
            usort($result['cutting'][$n], function($a, $b) {
                return (int) $a['rapport'] - (int) $b['rapport'];
            });

        } //перебор карнизов

        $final_rest = array_merge($rest,$kept_rest);
        $result['using_rest'] = $using_rest;
        $walls[$k]['calculation'] = $result;
        $rest = $final_rest;

    } //$walls foreach


    $final_result['walls'] = $walls;
    $final_result['rest'] = $final_rest;

    //return $rapport_rest;
    return $final_result;
    //return $walls;
}

/**
 * ========================= 1.50.502 - 1.50.524 ============================
 *
 * @param $data_arr
 * @param $walls
 * @param $cornice_article
 * @return array
 */
function get_third_method($data_arr,$room) {

    $walls = $room['walls'];
    $cornice_article = $room['cornice_article'];

    $final_result = array();

    $cornice_params = get_cornice_params($cornice_article);

    $rest = isset($data_arr['rest'][$cornice_article]) ? $data_arr['rest'][$cornice_article] : Array(); //остатки

    foreach($walls as $k => $wall) {

        $prev_rest_rapport_qty = 0; //количество раппортов в остатках с предыдущих стен
        foreach($rest as $item) {
            $prev_rest_rapport_qty += $item['length'];
        }

        $using_rest = array();
        $kept_rest = array();
        $result = Array();
        $fit_rapport_nmbr = 0;

        $wall = $wall['wall_info'];

        $corner_length = $wall['corner_1']['length'] + $wall['corner_2']['length'];
        /*if($cornice_article == '1.50.502' && $wall['corner_2']['type'] != 'trimming') $corner_length = $wall['corner_1']['length'] + ($cornice_params['rapport_length'] - $wall['corner_2']['length']); //убираем несимметричный кусок со стороны правого угла*/
        if($cornice_article == '1.50.502' && $wall['corner_2']['type'] != 'trimming') $corner_length -= $cornice_params['max_cutting']; //убираем несимметричный кусок со стороны правого угла
        $total_length = $wall['length'] - $corner_length; //длина стены без углов

        $total_rapport_qty = ceil($total_length/$cornice_params['rapport_length']); //количество целых раппортов для стены
        $total_need_cutting = $total_rapport_qty*$cornice_params['rapport_length'] - $total_length; // длина подгонки

        $cutting_type = '';

        /*---------------------------------- подгонка --------------------------------------*/

        $min_trimming_rapports = 0; //минимальное количество раппортов, которое нужно при наличии торцовки

        //торцовка без подгонки возле угла

        if($wall['corner_1']['type'] == 'trimming' || $wall['corner_2']['type'] == 'trimming') {

            $min_trimming_rapports = 2; //минимальное количество раппортов, которое нужно при наличии торцовки

            if($wall['corner_1']['type'] == 'trimming' && $wall['corner_2']['type'] == 'trimming' || $wall['corner_1']['type'] == 'trimming' && $wall['corner_1']['trimming_fit'] != 'yes' || $wall['corner_2']['type'] == 'trimming' && $wall['corner_2']['trimming_fit'] != 'yes') {
                $cutting_type = 'trimming';
            }

        }

        //торцовка с подгонкой возле угла

        if($wall['corner_1']['type'] == 'trimming' && $wall['corner_1']['trimming_fit'] == 'yes' || $wall['corner_2']['type'] == 'trimming' && $wall['corner_2']['trimming_fit'] == 'yes') {

            $fit_wall_cutting = '';

            $cutting_type = 'trimming fit';
            if($wall['corner_1']['type'] == 'trimming') {
                $as_wall = $wall['corner_1']['trimming_fit_wall'];
                if($wall['corner_1']['trimming_fit'] == 'yes') {
                    $min_trimming_rapports++;
                }
            }
            if($wall['corner_2']['type'] == 'trimming') {
                $as_wall = $wall['corner_2']['trimming_fit_wall'];
                if($wall['corner_2']['trimming_fit'] == 'yes') {
                    $min_trimming_rapports++;
                }
            }
            $fit_wall_calculation = $walls[$as_wall]['calculation'];
            foreach($fit_wall_calculation as $fit_wall) {
                if($wall['corner_1']['type'] == 'trimming') $fit_wall = array_reverse($fit_wall);
                foreach($fit_wall as $fit_wall_cornice) {
                    foreach($fit_wall_cornice as $fit_wall_cut) {
                        if($fit_wall_cut['type'] == 'edge cutting' || $fit_wall_cut['type'] == 'center cutting') {
                            $fit_wall_cutting = $fit_wall_cut;
                            break 3;
                        }
                    }
                }
            }
            //на всякий случай пересчитаем количество необходимых карнизов и оставшуюся подгонку
            if($fit_wall_cutting != '') { // если указанная подгонка найдена
                $total_length += $fit_wall_cutting['end'] - $fit_wall_cutting['start'];
                $fit_rapport_nmbr = $fit_wall_cutting['rapport'];
                $total_rapport_qty = ceil($total_length/$cornice_params['rapport_length']); //количество целых раппортов для стены
                $total_need_cutting = $total_rapport_qty*$cornice_params['rapport_length'] - $total_length; // длина подгонки
            }

            //если из-за короткой стены не получается сделать подгонку при определенном типе карниза (когда на стену идет 1 карниз с малым числом раппортов)
            if($cornice_params['rapport_qty'] <= 2 && $total_rapport_qty <= $cornice_params['rapport_qty']) {
                $result['error'] = 'Подгонка длины как у стены № '.($as_wall+1).' при выбранном типе карниза невозможна из-за малой длины стены. Проект будет рассчитан без учета данной стены.';
                $walls[$k]['calculation'] = $result;
                continue;
            }



        }

        // подгонка для углов
        if($total_need_cutting != 0 && $cutting_type != 'trimming' && $cutting_type != 'trimming fit') {

            if($total_length > 2 * ($cornice_params['rapport_length'] - $cornice_params['max_cutting'])) { // 2 подгонки
                $cutting_type = 'edge';
                $cutting_item = $total_need_cutting/2; // длина одного участка подгонки
            }
            elseif($total_length > ($cornice_params['rapport_length'] - $cornice_params['max_cutting'])) { // 1 подгонка
                $cutting_type = 'center';
                $cutting_item = $total_need_cutting; // длина одного участка подгонки
            } else { // ошибка
                $result['error'] = 'Для использования данного расчета введенная длина стены слишком мала. <br>Проект будет расчитан без данного участка. <br>Пожалуйста, обратитесь к нашим специалистам для расчета нетипичной стены.';
                $walls[$k]['calculation'] = $result;
                continue;
            }

        }

        //добавляем раппорты на углы
        $corner_rapport_1 = 1;
        $corner_rapport_2 = 1;
        if($wall['corner_1']['type'] != 'trimming') {
            $total_rapport_qty++;
            $use_edge_1 = false;
            //если длина угла больше длины раппорта
            if($wall['corner_1']['type'] == 'inner' && $wall['corner_1']['length'] > $cornice_params['rapport_length'] && $wall['corner_1']['length'] < $cornice_params['rapport_length'] + $cornice_params['edge'] || $wall['corner_1']['type'] == 'outer'  && $wall['corner_1']['length_top'] > $cornice_params['rapport_length'] && $wall['corner_1']['length_top'] < $cornice_params['rapport_length'] + $cornice_params['edge']) {
                $use_edge_1 = true;
            }
            //если длилна угла больше длины раппорта и края
            if($wall['corner_1']['type'] == 'inner' && $wall['corner_1']['length'] > $cornice_params['rapport_length'] + $cornice_params['edge'] || $wall['corner_1']['type'] == 'outer' && $wall['corner_1']['length_top'] > $cornice_params['rapport_length'] + $cornice_params['edge']) {
                $total_rapport_qty++;
                $corner_rapport_1++;
                $use_edge_1 = false;
            }
        }
        if($wall['corner_2']['type'] != 'trimming') {
            $total_rapport_qty++;
            $use_edge_2 = false;
            //если длина угла больше длины раппорта
            if($wall['corner_2']['type'] == 'inner' && $wall['corner_2']['length'] > $cornice_params['rapport_length'] && $wall['corner_2']['length'] < $cornice_params['rapport_length'] + $cornice_params['edge'] || $wall['corner_2']['type'] == 'outer'  && $wall['corner_2']['length_top'] > $cornice_params['rapport_length'] && $wall['corner_2']['length_top'] < $cornice_params['rapport_length'] + $cornice_params['edge']) {
                $use_edge_2 = true;
            }
            //если длилна угла больше длины раппорта и края
            if($wall['corner_2']['type'] == 'inner' && $wall['corner_2']['length'] > $cornice_params['rapport_length'] + $cornice_params['edge'] || $wall['corner_2']['type'] == 'outer' && $wall['corner_2']['length_top'] > $cornice_params['rapport_length'] + $cornice_params['edge']) {
                $total_rapport_qty += 1;
                $use_edge_2 = false;
                $corner_rapport_2++;
            }
        }


        //проверка: торцовка + подгонка - подгонка не должна наслаиваться на остатки
        if($min_trimming_rapports != 0 && $total_rapport_qty < $min_trimming_rapports) {
            $result['error'] = 'Подгонка длины как у стены № '.($as_wall+1).' при выбранном типе карниза невозможна из-за малой длины стены. Проект будет рассчитан без учета данной стены.';
            $walls[$k]['calculation'] = $result;
            continue;
        }

        //$total_cornice_qty = ceil($total_rapport_qty/$cornice_params['rapport_qty']); //всего нужно карнизов

        $total_cornice_qty = ceil(($total_rapport_qty - $prev_rest_rapport_qty)/$cornice_params['rapport_qty']);//с учетом остатков

        $total_cornice_rapport_qty = $total_cornice_qty*$cornice_params['rapport_qty']+$prev_rest_rapport_qty; //количество раппортов в финальном количестве карнизов и остатках

        $use_rest = true;

        //если получается 0 карнизов (из-за остатков)
        if($total_cornice_qty <= 0) {
            $total_cornice_qty = 1;
            $total_cornice_rapport_qty = $total_cornice_qty*$cornice_params['rapport_qty'];
            $use_rest = false;
        }

        $result['cornice_qty'] = $total_cornice_qty;

        $rapport_rest = $total_cornice_rapport_qty - $total_rapport_qty; //количество лишних раппортов

        /*------------------------------ разбираемся с остатками --------------------------------------------*/

        //если оcтатки с прошлых стен не нужны
        if($use_rest && $rapport_rest >= $prev_rest_rapport_qty) {
            $rapport_rest -= $prev_rest_rapport_qty;
            $use_rest = false;
            $total_rapport_qty += $prev_rest_rapport_qty;
        }

        if($use_rest && $prev_rest_rapport_qty != 0) {

            $need_rapport_qty = $prev_rest_rapport_qty - $rapport_rest; //необходимое количество раппортов
            if($rapport_rest == 0 || $need_rapport_qty >= $cornice_params['rapport_qty']) {
                $total_cornice_qty += floor($prev_rest_rapport_qty/$cornice_params['rapport_qty']);
                $need_rapport_qty = $total_rapport_qty - $total_cornice_qty*$cornice_params['rapport_qty'];
                $result['cornice_qty'] = $total_cornice_qty;
            }

            //если есть один или несколько кусоков, сумма которых равна необходимому количеству раппортов, или можо обрезать до необходимого количества

            $rest_length = array();
            foreach($rest as $r_item) {
                $rest_length[] = $r_item['length'];
            }
            $r = sub_sets($rest_length,$need_rapport_qty);

            //если есть необходимое количество кусков, которые не надо резать
            if(!empty($r) && count($r) <= 2) {
                foreach($rest as $i => $item) {
                    foreach($r as $p => $part) {
                        if($part == $item['length']) {
                            $arr = array(
                                'rapport' => $item['length'],
                                'waste' => '',
                                'room' => $item['room'],
                                'wall' => $item['wall'],
                                'cornice' => $item['cornice'],
                            );
                            unset($rest[$i]);
                            unset($r[$p]);
                            $using_rest[] = $arr;
                        }
                    }
                }
                $rest = array_values($rest);
                $rapport_rest = 0;

            } else { //если резать надо - не выбираем, берем подряд и обрезаем на нужном количестве
                //проверим, может есть куски больше, чем нам нужно, берем минимально больший
                $needed_piece = '';
                foreach($rest as $i => $item) {
                    if($need_rapport_qty <= $item['length']) {
                        if($needed_piece == '' || $rest[$needed_piece] > $item['length']) {
                            $needed_piece = $i;
                        }
                    }
                }
                // если есть нужный кусок
                if($needed_piece != '') {
                    $arr = array(
                        'rapport' => $rest[$needed_piece]['length'],
                        'waste' => $rest[$needed_piece] - $need_rapport_qty,
                        'room' => $rest[$needed_piece]['room'],
                        'wall' => $rest[$needed_piece]['wall'],
                        'cornice' => $rest[$needed_piece]['cornice'],
                    );
                    unset($rest[$needed_piece]);
                    $using_rest[] = $arr;
                    $rest = array_values($rest);
                    $rapport_rest = 0;
                } else { //если нет - берем все подряд
                    if($rest[0]['length'] + $rest[1]['length'] >= $need_rapport_qty) {//но не больше 2
                        $sum = 0;
                        foreach($rest as $i => $item) {
                            $sum += $item['length'];
                            if($sum < $need_rapport_qty) {
                                $arr = array(
                                    'rapport' => $item['length'],
                                    'waste' => '',
                                    'room' => $item['room'],
                                    'wall' => $item['wall'],
                                    'cornice' => $item['cornice'],
                                );
                                unset($rest[$i]);
                                $using_rest[] = $arr;
                            } else {
                                $excess = $sum - $need_rapport_qty; //лишнее
                                $arr = array(
                                    'rapport' => $item['length'],
                                    'waste' => $excess,
                                    'room' => $item['room'],
                                    'wall' => $item['wall'],
                                    'cornice' => $item['cornice'],
                                );
                                unset($rest[$i]);
                                $using_rest[] = $arr;
                                break;
                            }
                        }
                        $rest = array_values($rest);
                        $rapport_rest = 0;
                    } else { // если > 2, считаем без остатков
                        $total_cornice_qty = ceil($total_rapport_qty/$cornice_params['rapport_qty']);
                        $total_cornice_rapport_qty = $total_cornice_qty*$cornice_params['rapport_qty'];
                        $result['cornice_qty'] = $total_cornice_qty;
                        $rapport_rest = $total_cornice_rapport_qty - $total_rapport_qty; //количество лишних раппортов
                    }

                }

            }
        }


        /*--------------------------------- пилим карнизы -----------------------------------------------------*/

        for($n = 0; $n < $total_cornice_qty; $n++) {

            $i = 0;

            $result['cutting'][$n] = array();//показываем целый карниз

            //первый карниз - с углом и подгонкой
            if($n == 0) {
                if($wall['corner_1']['type'] != 'trimming') { //если не торцовка
                    if($use_edge_1) {//если используем заводской край
                        $result['cutting'][$n][$i]['rapport'] = 0;
                        $result['cutting'][$n][$i]['start'] = 0;
                        $result['cutting'][$n][$i]['end'] = $cornice_params['edge'] - ($wall['corner_1']['length'] - $cornice_params['rapport_length']);
                        if($wall['corner_1']['type'] == 'outer') $result['cutting'][$n][$i]['end'] = $cornice_params['edge'] - ($wall['corner_1']['length_top'] - $cornice_params['rapport_length']);
                        $result['cutting'][$n][$i]['use_edge'] = true;
                        $result['cutting'][$n][$i++]['type'] = 'corner waste';
                    }
                    elseif($corner_rapport_1 == 2) {//если используется 2 раппорта
                        $result['cutting'][$n][$i]['rapport'] = 1;
                        $result['cutting'][$n][$i]['start'] = 0;
                        $result['cutting'][$n][$i]['end'] = $cornice_params['rapport_length'] -($wall['corner_1']['length'] - $cornice_params['rapport_length']);
                        if($wall['corner_2']['type'] == 'outer') $result['cutting'][$n][$i]['end'] = $cornice_params['rapport_length'] -($wall['corner_1']['length_top'] - $cornice_params['rapport_length']);
                        $result['cutting'][$n][$i++]['type'] = 'corner waste';
                    }
                    else {
                        $result['cutting'][$n][$i]['rapport'] = 1;
                        $result['cutting'][$n][$i]['start'] = 0;
                        $result['cutting'][$n][$i]['end'] = $cornice_params['rapport_length'] - $wall['corner_1']['length'];
                        // если угол внешний
                        if ($wall['corner_1']['type'] == 'outer') $result['cutting'][$n][$i]['end'] = $cornice_params['rapport_length'] - $wall['corner_1']['length_top'];
                        $result['cutting'][$n][$i++]['type'] = 'corner waste';
                    }
                }
                // если подгонка (по центру или по краям)
                if($total_need_cutting != 0 && $cutting_type != 'trimming' && $cutting_type != 'trimming fit') {
                    $result['cutting'][$n][$i]['rapport'] = 1 + $corner_rapport_1;
                    if($cornice_params['rapport_length'] < $cornice_params['cutting_center'] + $cornice_params['max_cutting']/2) $result['cutting'][$n][$i]['rapport'] .= ', '.($result['cutting'][$n][$i]['rapport']+1);
                    //сдвиг от оси на 1мм вправо
                    $result['cutting'][$n][$i]['start'] = $cornice_params['cutting_center'] - floor($cutting_item/2);
                    $result['cutting'][$n][$i]['end'] = $cornice_params['cutting_center'] + ceil($cutting_item/2);
                    $result['cutting'][$n][$i++]['type'] = 'edge cutting';
                }
                //если подгонка у левого угла как у стены (при торцовке справа)
                if($cutting_type == 'trimming fit' && $wall['corner_2']['type'] == 'trimming' && $fit_wall_cutting != '') {
                    $result['cutting'][$n][$i++] = $fit_wall_cutting;
                }
                //если подгонка торцовки слева
                //if($wall['corner_2']['type'] == 'trimming' && $total_need_cutting != 0 || $wall['corner_1']['type'] == 'trimming' && $wall['corner_2']['type'] == 'trimming' && $total_need_cutting != 0 ) {
                if($wall['corner_1']['type'] == 'trimming' && $total_need_cutting != 0 && $wall['corner_2']['type'] != 'trimming') {
                    $result['cutting'][$n][$i]['rapport'] = 1;
                    $result['cutting'][$n][$i]['start'] = 0;
                    $result['cutting'][$n][$i]['end'] = $total_need_cutting;
                    $result['cutting'][$n][$i++]['type'] = 'trimming cutting';
                }
            }

            //если есть лишние раппорты
            if($rapport_rest != 0) {
                $used = 1;
                if($total_need_cutting != 0) { // если есть подгонка
                    if($cutting_type == 'edge') {
                        $used = 1+$corner_rapport_2;
                    }
                }

                // если 1 карниз
                if($total_cornice_qty == 1) {

                    $rapport_nmbr = 2+$corner_rapport_1;

                    if($fit_rapport_nmbr!=0) $rapport_nmbr = $fit_rapport_nmbr + 1;

                    // крайние раппорты из остатков - отходы
                    //$rapport_nmbr = 3; // стартуем с 3-го раппорта для остатков, 1-ый для угла, 2-ой для подгонки
                    if($wall['corner_1']['type'] == 'trimming') $rapport_nmbr = 2; //если торцовка слева, стартуем со 2-го раппорта, подгонки возле торцовки нет

                    if($rapport_rest > 2) {

                        //отходы из-за толщины пильного диска
                        $result['cutting'][$n][$i]['rapport'] = $rapport_nmbr;
                        $result['cutting'][$n][$i]['start'] = $rapport_nmbr;
                        $result['cutting'][$n][$i]['end'] = $rapport_nmbr++;
                        $result['cutting'][$n][$i++]['type'] = 'waste';

                        $rapport_nmbr_last = $rapport_nmbr;
                        for($r = 1; $r < $rapport_rest - 2; $r++) {
                            $rapport_nmbr_last++;
                        }

                        $rest_item = array();
                        $rest_item['length'] = $rapport_nmbr_last - $rapport_nmbr + 1;
                        $rest_item['room'] = $room['name'];
                        $rest_item['wall'] = $k + 1;
                        $rest_item['cornice'] = $n + 1;

                        $kept_rest[] = $rest_item;

                        $result['cutting'][$n][$i]['rapport'] = $rapport_nmbr_last == $rapport_nmbr ? $rapport_nmbr : $rapport_nmbr .' - '. $rapport_nmbr_last;
                        $result['cutting'][$n][$i]['start'] = $rapport_nmbr;
                        $result['cutting'][$n][$i]['end'] = $rapport_nmbr_last++;
                        $result['cutting'][$n][$i++]['type'] = 'rest';

                        //отходы из-за толщины пильного диска
                        $result['cutting'][$n][$i]['rapport'] = $rapport_nmbr_last;
                        $result['cutting'][$n][$i]['start'] = $rapport_nmbr_last;
                        $result['cutting'][$n][$i]['end'] = $rapport_nmbr_last;
                        $result['cutting'][$n][$i++]['type'] = 'waste';

                    } else {

                        $rapport_nmbr_last = $rapport_nmbr;
                        for($r = 1; $r < $rapport_rest; $r++) {
                            $rapport_nmbr_last++;
                        }

                        //отходы из-за толщины пильного диска
                        $result['cutting'][$n][$i]['rapport'] = $rapport_nmbr_last == $rapport_nmbr ? $rapport_nmbr : $rapport_nmbr .' - '. $rapport_nmbr_last;
                        $result['cutting'][$n][$i]['start'] = $rapport_nmbr;
                        $result['cutting'][$n][$i]['end'] = $rapport_nmbr_last;
                        $result['cutting'][$n][$i++]['type'] = 'waste';

                    }

                } else { // если > 1 карниза
                    if($rapport_rest <= $cornice_params['rapport_qty'] - $used) {
                        //хватает последнего карниза для обрезки остатков

                        if($n == $total_cornice_qty - 1) {

                            if($rapport_rest > 1) {

                                $rest_item = array();
                                $rest_item['length'] = $rapport_rest - 1;
                                $rest_item['room'] = $room['name'];
                                $rest_item['wall'] = $k + 1;
                                $rest_item['cornice'] = $n + 1;

                                $kept_rest[] = $rest_item;

                                $result['cutting'][$n][$i]['rapport'] = '1 - '.($rapport_rest - 1);
                                $result['cutting'][$n][$i]['start'] = 1;
                                $result['cutting'][$n][$i]['end'] = $rapport_rest - 1;
                                $result['cutting'][$n][$i++]['type'] = 'rest';
                            }
                            //отходы из-за толщины пильного диска
                            $result['cutting'][$n][$i]['rapport'] = $rapport_rest;
                            $result['cutting'][$n][$i]['start'] = $rapport_rest;
                            $result['cutting'][$n][$i]['end'] = $rapport_rest;
                            $result['cutting'][$n][$i++]['type'] = 'waste';
                        }

                    } else {
                        // надо брать предпоследний и последний карниз для обрезки остатков

                        if($n == $total_cornice_qty - 2) {
                            $rest_cut = $rapport_rest - $cornice_params['rapport_qty'] + $used;
                            if($rest_cut != 1) {

                                $rest_item = array();
                                $rest_item['length'] = $cornice_params['rapport_qty'] - $cornice_params['rapport_qty'] + $rest_cut + 1;
                                $rest_item['room'] = $room['name'];
                                $rest_item['wall'] = $k + 1;
                                $rest_item['cornice'] = $n + 1;

                                $kept_rest[] = $rest_item;


                                $result['cutting'][$n][$i]['rapport'] = ($cornice_params['rapport_qty'] - $rest_cut).' - '.$cornice_params['rapport_qty'];
                                $result['cutting'][$n][$i]['start'] = $cornice_params['rapport_qty'] - $rest_cut;
                                $result['cutting'][$n][$i]['end'] = $cornice_params['rapport_qty'];
                                $result['cutting'][$n][$i++]['type'] = 'rest';
                            }
                            //отходы из-за толщины пильного диска
                            $result['cutting'][$n][$i]['rapport'] = $cornice_params['rapport_qty'] - ($rest_cut - 1);
                            $result['cutting'][$n][$i]['start'] = $cornice_params['rapport_qty'] - ($rest_cut - 1);
                            $result['cutting'][$n][$i]['end'] = $cornice_params['rapport_qty'] - ($rest_cut - 1);
                            $result['cutting'][$n][$i++]['type'] = 'waste';

                        }
                        if($n == $total_cornice_qty - 1) {
                            if($rapport_rest > 1) {

                                $rest_item = array();
                                $rest_item['length'] = $cornice_params['rapport_qty'] - $used - 1;
                                $rest_item['room'] = $room['name'];
                                $rest_item['wall'] = $k + 1;
                                $rest_item['cornice'] = $n + 1;

                                $kept_rest[] = $rest_item;

                                $result['cutting'][$n][$i]['rapport'] = '1 - ' . ($cornice_params['rapport_qty'] - $used - 1);
                                $result['cutting'][$n][$i]['start'] = 1;
                                $result['cutting'][$n][$i]['end'] = $cornice_params['rapport_qty'] - $used - 1;
                                $result['cutting'][$n][$i++]['type'] = 'rest';
                            }
                            //отходы из-за толщины пильного диска
                            if($cornice_params['rapport_qty'] - $used > 0) {
                                $result['cutting'][$n][$i]['rapport'] = $cornice_params['rapport_qty'] - $used;
                                $result['cutting'][$n][$i]['start'] = $cornice_params['rapport_qty'] - $used;
                                $result['cutting'][$n][$i]['end'] = $cornice_params['rapport_qty'] - $used;
                                $result['cutting'][$n][$i++]['type'] = 'waste';
                            }
                        }
                    }
                }



            }

            //последний карниз - с углом и подгонкой
            if($n == $total_cornice_qty - 1) {

                // если 2 подгонки
                if($cutting_type == 'edge') {
                    if($wall['corner_1']['type'] != 'trimming' || $wall['corner_2']['type'] == 'trimming' && $wall['corner_2']['trimming_fit'] == 'yes') { //если не торцовка или при торцовке есть подгонка
                        $result['cutting'][$n][$i]['rapport'] = $cornice_params['rapport_qty'] - $corner_rapport_2;
                        if($cornice_params['rapport_length'] < $cornice_params['cutting_center'] + $cornice_params['max_cutting']/2) $result['cutting'][$n][$i]['rapport'] .= ', '.($result['cutting'][$n][$i]['rapport']+1);
                        //сдвиг от оси на 1мм вправо
                        $result['cutting'][$n][$i]['start'] = $cornice_params['cutting_center'] - floor($cutting_item/2);
                        $result['cutting'][$n][$i]['end'] = $cornice_params['cutting_center'] + ceil($cutting_item/2);
                        $result['cutting'][$n][$i++]['type'] = 'edge cutting';
                    }
                }

                //если подгонка у правого угла как у стены (при торцовке слева)
                if($cutting_type == 'trimming fit' && $wall['corner_1']['type'] == 'trimming' && $fit_wall_cutting != '') {
                    if($fit_wall_cutting['rapport'] == 2 && $total_cornice_qty > 1) $fit_wall_cutting['rapport'] = $cornice_params['rapport_qty'] - 1; //если вдруг был короткий участок с подгонкой по центру
                    $result['cutting'][$n][$i++] = $fit_wall_cutting;
                }

                if($wall['corner_2']['type'] != 'trimming') { //если не торцовка
                    if($use_edge_2) {//если используем заводской край
                        $result['cutting'][$n][$i]['rapport'] = $cornice_params['rapport_qty']+1;
                        $result['cutting'][$n][$i]['start'] = $wall['corner_2']['length'] - $cornice_params['rapport_length'];
                        if($wall['corner_2']['type'] == 'outer') $result['cutting'][$n][$i]['start'] = $wall['corner_2']['length_top'] - $cornice_params['rapport_length'];
                        $result['cutting'][$n][$i]['end'] = $cornice_params['edge'];
                        $result['cutting'][$n][$i]['use_edge'] = true;
                        $result['cutting'][$n][$i++]['type'] = 'corner waste';
                    }
                    elseif($corner_rapport_2 == 2) {//если используется 2 раппорта
                        $result['cutting'][$n][$i]['rapport'] = $cornice_params['rapport_qty'];
                        $result['cutting'][$n][$i]['start'] = $wall['corner_2']['length'] - $cornice_params['rapport_length'];
                        if($wall['corner_2']['type'] == 'outer') $result['cutting'][$n][$i]['start'] = $wall['corner_2']['length_top'] - $cornice_params['rapport_length'];
                        $result['cutting'][$n][$i]['end'] = $cornice_params['rapport_length'];
                        $result['cutting'][$n][$i++]['type'] = 'corner waste';
                    }
                    else {
                        $result['cutting'][$n][$i]['rapport'] = $cornice_params['rapport_qty'];
                        $result['cutting'][$n][$i]['start'] = $wall['corner_2']['length'];
                        if($cornice_article == '1.50.502') $result['cutting'][$n][$i]['start'] = $cornice_params['rapport_length'] - $cornice_params['max_cutting'] - ($cornice_params['rapport_length'] - $wall['corner_2']['length']); //в карнизе 502 несимметричный раппорт
                        //если угол внешний
                        if($wall['corner_2']['type'] == 'outer') {
                            $result['cutting'][$n][$i]['start'] = $wall['corner_2']['length_top'];
                            if($cornice_article == '1.50.502') $result['cutting'][$n][$i]['start'] = $cornice_params['rapport_length'] - $cornice_params['max_cutting'] - ($cornice_params['rapport_length'] - $wall['corner_2']['length_top']); //в карнизе 502 несимметричный раппорт
                        }
                        $result['cutting'][$n][$i]['end'] = $cornice_params['rapport_length'];
                        $result['cutting'][$n][$i++]['type'] = 'corner waste';
                    }
                }

                //подгонка торцовки справа
                if($wall['corner_2']['type'] == 'trimming' && $total_need_cutting != 0 || $wall['corner_1']['type'] == 'trimming' && $wall['corner_2']['type'] == 'trimming' && $total_need_cutting != 0 ) {
                    $result['cutting'][$n][$i]['rapport'] = $cornice_params['rapport_qty'];
                    $result['cutting'][$n][$i]['start'] = $cornice_params['rapport_length'] - $total_need_cutting;
                    $result['cutting'][$n][$i]['end'] = $cornice_params['rapport_length'];
                    $result['cutting'][$n][$i++]['type'] = 'trimming cutting';
                }

            }

        }

        $final_rest = array_merge($rest,$kept_rest);
        $result['using_rest'] = $using_rest;
        $walls[$k]['calculation'] = $result;
        $rest = $final_rest;

    } //$walls foreach


    $final_result['walls'] = $walls;
    $final_result['rest'] = $final_rest;

    //return $rapport_rest;
    return $final_result;
    //return $walls;
}

