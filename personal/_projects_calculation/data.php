<?php
/**
 * Created by PhpStorm.
 * User: nadida
 * Date: 16.09.2019
 * Time: 17:13
 */

/**
 * =========================== параметры карнизов ============================
 *
 * @param $article
 * @return mixed
 */
function get_cornice_params($article) {
    $cornice_arr = Array(

        '1.50.501' => Array(
            'rapport_length' => 50,  //длина раппорта
            'rapport_qty' => 40,     //количество раппортов в карнизе
            'max_cutting' => 15,     //максимальная длина подгонки в одном раппорте
        ),

        '1.50.502' => Array(
            'rapport_length' => 250,  //длина раппорта
            'rapport_qty' => 8,       //количество раппортов в карнизе
            'max_cutting' => 125,     //максимальная длина подгонки в одном раппорте
            //'cutting_center' => 188,  //длина расстояния от начала раппорта до центра подгонки
            'cutting_center' => 63,  //длина расстояния от начала раппорта до центра подгонки
        ),

        '1.50.503' => Array( //2004
            'rapport_length' => 165,  //длина раппорта
            'rapport_qty' => 12,      //количество раппортов в карнизе
            'max_cutting' => 110,     //максимальная длина подгонки в одном раппорте
            'cutting_center' => 83, //длина расстояния от начала раппорта до центра подгонки
        ),

        '1.50.504' => Array(
            'rapport_length' => 500,  //длина раппорта
            'rapport_qty' => 4,      //количество раппортов в карнизе
            'max_cutting' => 300,     //максимальная длина подгонки в одном раппорте
            'cutting_center' => 500, //длина расстояния от начала раппорта до центра подгонки//
        ),

        '1.50.524' => Array( //1500
            'rapport_length' => 750,  //длина раппорта
            'rapport_qty' => 2,       //количество раппортов в карнизе
            'max_cutting' => 560,     //максимальная длина подгонки в одном раппорте
            'cutting_center' => 375,  //длина расстояния от начала раппорта до центра подгонки
            'edge' => 250, //длина расстояния от начала раппорта до центра подгонки//
        ),
    );
    return $cornice_arr[$article];
}

/**
 * =========================== параметры углов ============================
 *
 * @param $article
 * @return array
 */
function get_cornice_corners($article) {

    $corners_arr = Array(

        '1.50.501' => Array(//1.50.501

            'inner' => Array(//внутренние углы

                Array(
                    'title' => '1',//картинка угла называется по названию угла 1.png и 1-b.png увеличенная
                    'length' => '65',//длина угла, измеренная по низу карниза от начала второго раппорта с края в сторону края карниза
                    'length_top' => '',//длина угла, измеренная по верху карниза
                ),

            ),
            'outer' => Array(//внешние углы

                Array(
                    'title' => '1',
                    'length' => '65',
                    'length_top' => '65',
                )

            )
        ),

        '1.50.502' => Array(//1.50.502

            'inner' => Array(//внутренние углы

                Array(
                    'title' => '1',
                    'length' => '240',
                    'length_top' => '',
                ),

                Array(
                    'title' => '2',
                    'length' => '302',
                    'length_top' => '',
                ),

                Array(
                    'title' => '3',
                    'length' => '221',
                    'length_top' => '',
                ),

                Array(
                    'title' => '4',
                    'length' => '234',
                    'length_top' => '',
                ),

            ),
            'outer' => Array(//внешние углы

                Array(
                    'title' => '1',
                    'length' => '140',
                    'length_top' => '223',
                ),

                Array(
                    'title' => '2',
                    'length' => '149',
                    'length_top' => '233',
                ),

                Array(
                    'title' => '3',
                    'length' => '114',
                    'length_top' => '197',
                ),

                Array(
                    'title' => '4',
                    'length' => '167',
                    'length_top' => '251',
                ),

            )
        ),

        '1.50.503' => Array(//1.50.503

            'inner' => Array(//внутренние углы

                Array(
                    'title' => '1',
                    'length' => '154',
                    'length_top' => '',
                ),

                Array(
                    'title' => '2',
                    'length' => '83',
                    'length_top' => '',
                ),

                Array(
                    'title' => '3',
                    'length' => '31',
                    'length_top' => '',
                ),

                Array(
                    'title' => '4',
                    'length' => '183',
                    'length_top' => '',
                ),

            ),
            'outer' => Array(//внешние углы

                Array(
                    'title' => '1',
                    'length' => '143',
                    'length_top' => '196',
                ),

                Array(
                    'title' => '2',
                    'length' => '119',
                    'length_top' => '72',
                ),

                Array(
                    'title' => '3',
                    'length' => '101',
                    'length_top' => '47',
                ),

                Array(
                    'title' => '4',
                    'length' => '58',
                    'length_top' => '5',
                ),

            )
        ),

        '1.50.504' => Array(//1.50.504

            'inner' => Array(//внутренние углы

                Array(
                    'title' => '1',
                    'length' => '500',
                    'length_top' => '',
                ),

            ),
            'outer' => Array(//внешние углы

                Array(
                    'title' => '1',
                    'length' => '310',
                    'length_top' => '448',
                ),

            )
        ),

        '1.50.524' => Array(//1.50.524

            'inner' => Array(//внутренние углы

                Array(
                    'title' => '1',
                    'length' => '813',
                    'length_top' => '',
                ),

            ),
            'outer' => Array(//внешние углы

                Array(
                    'title' => '1',
                    'length' => '649',
                    'length_top' => '787',
                ),

            )
        ),

    );

    return $corners_arr[$article];
}

/**
 * =========================== параметры подгоночных участков (для визуализации) ============================
 *
 * @param $article
 * @return mixed
 */
function get_cutting_params($article) {
    $cornice_arr = Array(

        '1.50.501' => Array(
            'length_px' => 284,  //длина картинки подгоночного участка (px)
            'length_mm' => 165, //длина реального такого же подгоночного участка (мм)
        ),
        '1.50.502' => Array(
            'length_px' => 391,
            'length_mm' => 437,
        ),
        /*'1.50.503' => Array(
            'length_px' => 297,
            'length_mm' => 330,
        ),*/
        '1.50.503' => Array(
            'length_px' => 280,
            'length_mm' => 313,
        ),
        '1.50.504' => Array(
            'length_px' => 305,
            'length_mm' => 500,
        ),
        '1.50.524' => Array(
            'length_px' => 687,
            'length_mm' => 1125,
        ),
    );
    return $cornice_arr[$article];
}

/**
 * =========================== описание мотнажа углов (для визуализации) ============================
 *
 * @param $article
 * @return mixed
 */
function get_corner_mounting_description($type) {
    $mounting_arr = Array(
        'inner' => Array(
            'name' => 'внутренний',
            'text' => 'Запилите изделия четко в вертикальной плоскости строго на половину угла между ними. При правильной подготовке карнизов для внутренних углов помещения, <b>тыльная сторона изделия, должна получиться длиннее лицевой</b>.',
        ),
        'outer' => Array(
            'name' => 'внешний',
            'text' => 'Запилите изделия в вертикальной плоскости строго на половину угла между ними. При правильной подготовке карнизов для внешних углов помещения, <b>тыльная сторона изделия, должна получиться короче лицевой</b>.',
        ),
        'trimming' => Array(
            'name' => 'торцовка',
            'text' => 'Запилите изделие по линии распила максимально аккуратно, под 90⁰ к стене и потолку.',
        ),
    );
    return $mounting_arr[$type];
}


