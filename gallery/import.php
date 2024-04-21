<?
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog.php");
if (!CModule::IncludeModule('iblock') || !CModule::IncludeModule("catalog")) {
        exit;
    }

$s_objects = array();

$s_objects[] = array('num' => 1, 'dir' => '001', 'name' => 'Зал заседаний Московской городской Думы', 'type' => array('i','01','10'), 'data' => '04.01.2017');
$s_objects[] = array('num' => 2, 'dir' => '002', 'name' => 'Офис компании 1С', 'type' => array('i','09','11'), 'data' => '05.01.2017');
$s_objects[] = array('num' => 3, 'dir' => '003', 'name' => 'ТВ-проект. "Школа Ремонта". Музыкальный салон мадам Ларкиной', 'type' => array('i','03',), 'data' => '05.01.2017');
$s_objects[] = array('num' => 4, 'dir' => '004', 'name' => 'ТВ проект. "Чистая работа"', 'type' => array('i','03','13'), 'data' => '06.01.2017');
$s_objects[] = array('num' => 5, 'dir' => '005', 'name' => 'Лобби гостиницы "Бородино", Москва', 'type' => array('i','08','14',), 'data' => '07.01.2017');
$s_objects[] = array('num' => 6, 'dir' => '006', 'name' => 'ТВ проект. "Квартирный вопрос"', 'type' => array('i','03','13'), 'data' => '07.01.2017');
$s_objects[] = array('num' => 7, 'dir' => '007', 'name' => 'Ресепшен бизнес-центра "Crosswall", Москва', 'type' => array('i','08','14'), 'data' => '12.01.2017');
$s_objects[] = array('num' => 8, 'dir' => '008', 'name' => 'Гостиная в частном доме', 'type' => array('i','05','13'), 'data' => '12.01.2017');
$s_objects[] = array('num' => 9, 'dir' => '009', 'name' => 'Переговорная бизнес-центра "Crosswall", Москва', 'type' => array('i','01','10'), 'data' => '12.01.2017');
$s_objects[] = array('num' => 10, 'dir' => '010', 'name' => 'Спальня с кессонами.  Квартира на Покровке', 'type' => array('i','03','15'), 'data' => '12.01.2017');
//$s_objects[] = array('num' => 11, 'dir' => '011', 'name' => 'ТВ-проект. "Фазенда". Столовая в частном доме', 'type' => array('i','03','16'), 'data' => '12.01.2017');
$s_objects[] = array('num' => 12, 'dir' => '012', 'name' => 'ТВ-проект. "Школа Ремонта". Классическая фантастика', 'type' => array('i','03','13'), 'data' => '12.01.2017');
$s_objects[] = array('num' => 13, 'dir' => '013', 'name' => 'ТВ-проект. "Фазенда". Гжель', 'type' => array('i','03','13'), 'data' => '12.01.2017');
$s_objects[] = array('num' => 14, 'dir' => '014', 'name' => 'Столовая в частном доме, Горки 1', 'type' => array('i','05','16',), 'data' => '12.01.2017');
$s_objects[] = array('num' => 15, 'dir' => '015', 'name' => 'ТВ-проект. "Школа Ремонта". Нежно-брутальное шале', 'type' => array('i','03','15',), 'data' => '12.01.2017');
$s_objects[] = array('num' => 16, 'dir' => '016', 'name' => 'Гостиная в квартире на Чистых прудах', 'type' => array('i','03','13',), 'data' => '12.01.2017');
$s_objects[] = array('num' => 17, 'dir' => '017', 'name' => 'ТВ-проект. "Школа Ремонта".', 'type' => array('i','03','15',), 'data' => '12.01.2017');
$s_objects[] = array('num' => 18, 'dir' => '018', 'name' => 'ТВ-проект. "Фазенда".', 'type' => array('i','05','16'), 'data' => '13.01.2017');
$s_objects[] = array('num' => 19, 'dir' => '019', 'name' => 'Фрагмент интерьера с использованием арочного обрамления', 'type' => array('i','03','13',), 'data' => '13.01.2017');
$s_objects[] = array('num' => 20, 'dir' => '020', 'name' => 'Отель «Сочи Марриотт Красная Поляна», Сочи', 'type' => array('i','08','17',), 'data' => '13.01.2017');
$s_objects[] = array('num' => 21, 'dir' => '021', 'name' => 'Фрагмент интерьера с использованием кронштейна', 'type' => array('i','03','13',), 'data' => '13.01.2017');
$s_objects[] = array('num' => 22, 'dir' => '022', 'name' => 'Фрагмент интерьера с использованием ниши', 'type' => array('i','03','13',), 'data' => '13.01.2017');
$s_objects[] = array('num' => 23, 'dir' => '023', 'name' => 'Интерьер ресторана "Флеш-Рояль"', 'type' => array('i','09','12'), 'data' => '13.01.2017');
$s_objects[] = array('num' => 24, 'dir' => '024', 'name' => 'Кабинет в частном доме, Крекшино', 'type' => array('i','05','18',), 'data' => '13.01.2017');
$s_objects[] = array('num' => 25, 'dir' => '025', 'name' => 'ТВ-проект. "Школа ремонта". Золотые джунгли', 'type' => array('i','03','13',), 'data' => '13.01.2017');
$s_objects[] = array('num' => 26, 'dir' => '026', 'name' => 'ТВ-проект. "Фазенда". Дачный кабинет', 'type' => array('i','05','18',), 'data' => '13.01.2017');
$s_objects[] = array('num' => 27, 'dir' => '027', 'name' => 'ТВ-проект. "Школа ремонта".', 'type' => array('i','03','13',), 'data' => '13.01.2017');
$s_objects[] = array('num' => 28, 'dir' => '028', 'name' => 'Квартира на Тверской', 'type' => array('i','03','19',), 'data' => '13.01.2017');
$s_objects[] = array('num' => 29, 'dir' => '029', 'name' => 'ТВ-проект. "Фазенда".', 'type' => array('i','03','13',), 'data' => '13.01.2017');
$s_objects[] = array('num' => 30, 'dir' => '030', 'name' => 'Спальня в частном доме, <br>Алма-Аты, Казахстан', 'type' => array('i','03','15',), 'data' => '13.01.2017');
//$s_objects[] = array('num' => 31, 'dir' => '031', 'name' => 'ТВ-проект. "Фазенда". Спальня в зефире', 'type' => array('i','03','15',), 'data' => '28.02.2017');

$s_objects[] = array('num' => 81, 'dir' => '081', 'name' => 'Фасад бизнес-центра "Цветной бульвар", Москва', 'type' => array('f','09'), 'data' => '01.03.2017');
$s_objects[] = array('num' => 82, 'dir' => '082', 'name' => 'Частный дом, Московская область', 'type' => array('f','05'), 'data' => '01.03.2017');
$s_objects[] = array('num' => 83, 'dir' => '083', 'name' => 'Административный комплекс "Дубровицы"', 'type' => array('f','01'), 'data' => '01.03.2017');
$s_objects[] = array('num' => 84, 'dir' => '084', 'name' => 'Жилой дом, Тула', 'type' => array('f','04'), 'data' => '01.03.2017');
$s_objects[] = array('num' => 85, 'dir' => '085', 'name' => 'Частный дом, Московская область', 'type' => array('f','05'), 'data' => '01.03.2017');
$s_objects[] = array('num' => 86, 'dir' => '086', 'name' => 'Коттеджный посёлок Европа', 'type' => array('f','05'), 'data' => '01.03.2017');
$s_objects[] = array('num' => 87, 'dir' => '087', 'name' => 'Здание администрации Парка "Сокольники"', 'type' => array('f','09'), 'data' => '01.03.2017');

$s_objects[] = array('num' => 32, 'dir' => '032', 'name' => 'ТВ-проект. "Школа ремонта". Каприз в тренде', 'type' => array('i','03','15',), 'data' => '02.07.2017');
$s_objects[] = array('num' => 33, 'dir' => '033', 'name' => 'ТВ-проект. "Школа ремонта". Море на четверых', 'type' => array('i','03','15',), 'data' => '04.07.2017');
$s_objects[] = array('num' => 34, 'dir' => '034', 'name' => 'ТВ-проект. "Школа ремонта". Мото Фьюжн', 'type' => array('i','03','13',), 'data' => '07.07.2017');
$s_objects[] = array('num' => 35, 'dir' => '035', 'name' => 'ТВ-проект. "Фазенда". Спальня в икате', 'type' => array('i','03','15',), 'data' => '23.07.2017');
$s_objects[] = array('num' => 36, 'dir' => '036', 'name' => 'Смирнитский', 'type' => array('i','03','18',), 'data' => '24.07.2017');
// 37 - 31 сложное построение, одинаковые
$s_objects[] = array('num' => 38, 'dir' => '038', 'name' => 'ТВ проект. "Квартирный вопрос". Шабельникова', 'type' => array('i','03','13',), 'data' => '25.07.2017');
$s_objects[] = array('num' => 39, 'dir' => '039', 'name' => 'ТВ-проект. "Фазенда". Омар Хайям', 'type' => array('i','03','13',), 'data' => '29.07.2017');
$s_objects[] = array('num' => 40, 'dir' => '040', 'name' => 'ТВ-проект. "Фазенда". <br>Прованс в дачной гостиной', 'type' => array('i','05','13',), 'data' => '05.08.2017');
//41 - дубль 32, 42 - дубль 35
$s_objects[] = array('num' => 43, 'dir' => '043', 'name' => 'ТВ-проект. "Школа ремонта". Родиться в тропиках', 'type' => array('i','05','13',), 'data' => '11.02.2018');
$s_objects[] = array('num' => 44, 'dir' => '044', 'name' => 'ТВ-проект. "Фазенда". <br>Солнечная спальня', 'type' => array('i','05','13',), 'data' => '11.02.2018');
$s_objects[] = array('num' => 45, 'dir' => '045', 'name' => 'ТВ-проект. "Школа ремонта". <br>Гламурная сказка', 'type' => array('i','05','13',), 'data' => '11.02.2018');
$s_objects[] = array('num' => 46, 'dir' => '046', 'name' => 'ТВ-проект. "Школа ремонта". <br>Комиксы в бетоне', 'type' => array('i','05','13',), 'data' => '11.02.2018');
$s_objects[] = array('num' => 47, 'dir' => '047', 'name' => 'ТВ-проект. "Школа ремонта". <br>Клубная классика', 'type' => array('i','05','13',), 'data' => '12.02.2018');
$s_objects[] = array('num' => 48, 'dir' => '048', 'name' => 'ТВ-проект. "Школа ремонта". <br>Золотистый портвейн на берегу озера', 'type' => array('i','05','13',), 'data' => '12.02.2018');
$s_objects[] = array('num' => 49, 'dir' => '049', 'name' => 'ТВ-проект. "Школа ремонта". <br>Позволительная роскошь', 'type' => array('i','05','13',), 'data' => '12.02.2018');
$s_objects[] = array('num' => 50, 'dir' => '050', 'name' => 'ТВ-проект. "Школа ремонта". <br>Сияние розового кварца', 'type' => array('i','05','13',), 'data' => '12.02.2018');
$s_objects[] = array('num' => 51, 'dir' => '051', 'name' => 'ТВ-проект. "Дорогая переДача". <br>Шале', 'type' => array('i','05','13',), 'data' => '12.02.2018');
$s_objects[] = array('num' => 52, 'dir' => '052', 'name' => 'ТВ-проект. "Квартирный вопрос". Спальня <br>в тисовой роще', 'type' => array('i','05','13',), 'data' => '12.02.2018');


$file_path = $_SERVER["DOCUMENT_ROOT"].'/gallery/objects/';

$import_obj = array();

foreach ($s_objects as $s_object) {

$temp_obj = array();
$temp_obj['number'] = $s_object['num'];
$temp_obj['name'] = $s_object['name'];
$temp_obj['data'] = $s_object['data'];
?>

<div style="display: none;">
<?require_once($file_path.$s_object["dir"].'/object_'.$s_object["num"].'.php');?>
</div>

<?
global $ob_items;

$temp_ob = array();
$i = 0;
foreach($ob_items as $key1 => $ob) {
	$i++;
	$temp_el = array();
	foreach($ob as $key2 => $el) {
	if (!$el['article']) continue;
/*		$temp_el['num_item'][] = $key2;
		$temp_el['ID'][] = $el['ID'];
		$temp_el['article'][] = $el['article'];
		$temp_el['NAME'][] = $el['NAME'];
		$temp_el['x'][] = $el['x'];
		$temp_el['y'][] = $el['y'];	
*/
		$res_el = array();
		$res_el['num_item'] = $key2;
		$res_el['ID'] = $el['ID'];
		$res_el['article'] = $el['article'];
		$res_el['NAME'] = $el['NAME'];
		$res_el['x'] = $el['x'];
		$res_el['y'] = $el['y'];
	$temp_el[] = $res_el;

	}

$temp_ob[] = array('num_img'=>$key1, 'IMAGE'=>$file_path.$s_object['dir'].'/'.$s_object['num'].'_'.$i.'.jpg', 'item'=>$temp_el);

}
$temp_obj['items'] = $temp_ob;

$import_obj[] = $temp_obj;
}

$art_res = CIBlock::GetList(Array(), Array("CODE"=>"gallery_articles"), false, Array(), Array());
if($arRes = $art_res->Fetch()) {
    $art_id = $arRes['ID'];
}

$points_res = CIBlock::GetList(Array(), Array("CODE"=>"gallery_points"), false, Array(), Array());
if($arRes = $points_res->Fetch()) {
    $points_id = $arRes['ID'];
}

// выдача
foreach ($import_obj as $obj) {

$ART = array();
$ART['NUMBER'] = $obj['number'];
$ART['DATE'] = $obj['data'];

$elArr = new CIBlockElement;

$arrArt = Array(
    "IBLOCK_SECTION_ID" => false,
    "IBLOCK_ID"      => $art_id,
    "PROPERTY_VALUES"=> $ART,
    "NAME"           => $obj['name'],
    "ACTIVE"         => "Y"
);

//$elArr->Add($arrArt);


echo 'Номер: '.$obj['number'].' / ';
echo 'наименование: '.$obj['name'].' / ';
echo 'Дата: '.$obj['data'].'<br>';
echo 'Изображения -<br>';
$n = 1;
	foreach ($obj['items'] as $item) { // 'елементы




	
		foreach ($item['item'] as $el) {

            $POINT = array();
            $POINT['ART_NUMBER'] = $obj['number'];
            $POINT['IMG_NUMBER'] = $item['num_img'];
            $POINT['ARTICLE'] = $el['article'];
            $POINT['X'] = $el['x'];
            $POINT['Y'] = $el['y'];

            $elPoint = new CIBlockElement;

            $arrPoint = Array(
                "IBLOCK_SECTION_ID" => false,
                "IBLOCK_ID"      => $points_id,
                "PROPERTY_VALUES"=> $POINT,
                "NAME"           => $n,
                "ACTIVE"         => "Y"
            );
            //$elPoint->Add($arrPoint);

            $n++;

		echo 'номер картинки: '.$item['num_img'].' / '.'путь картинки: '.$item['IMAGE'].'<br>';
		echo 'ID: '.$el['ID'].' / x:'.$el['x'].' / y:'.$el['y'].' / артикул:'.$el['article'].'<br>';


		}
		
	}
echo '<br>';	
}



?>
