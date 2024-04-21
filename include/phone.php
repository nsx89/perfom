<?
global $phoneCodes; 

$phoneCodes = Array(
'7'=>Array(
        'name'=>'Россия',
        'cityCodeLength'=>3,
        'zeroHack'=>false,
        'exceptions'=>Array(7272,726,7262,725,7252,724,7242,723,7232,722,7222,721,7212,7213,7187,718,7182,717,7172,716,7162,715,7152,714,
	7142,713,7132,712,7122,711,7112,710,7102,71622,3902,8622,8553,86133,86137,8182,8512,3852,4722,4162,4832,8162,4922,8442,8172,4732,8712,
	87137,87240,49679,343,47148,4932,3412,3952,34551,8362,843,4012,4842,3842,48456,8332,49232,4217,4942,861,391,86138,3522,4712,4742,
	3519,8722,47545,81536,8152,8552,8555,8662,3463,3466,831,3435,3843,8617,383,3494,3812,4862,3532,3537,8412,342,8142,4967,8112,8793,4852,
	863,4912,846,812,8342,8452,4812,83375,40161,8622,8652,3462,8212,4752,4822,3456,8482,3822,35163,4872,3452,3012,8422,347,4212,3467,
	8352,351,8202,3022,49449,4242,4112,85594),
        'exceptions_max'=>5,
        'exceptions_min'=>3
    ),
'375'=>Array(
        'name'=>'Беларусь',
        'cityCodeLength'=>2,
        'zeroHack'=>false,
        'exceptions'=>Array(),
        'exceptions_max'=>2,
        'exceptions_min'=>2
    ),
'994'=>Array(
        'name'=>'Азербайджан',
        'cityCodeLength'=>2,
        'zeroHack'=>false,
        'exceptions'=>Array(50,51,70,77,55,40,60,70),
        'exceptions_max'=>2,
        'exceptions_min'=>2
    ),
'373'=>Array(
        'name'=>'Азербайджан',
        'cityCodeLength'=>3,
        'zeroHack'=>false,
        'exceptions'=>Array(630,631,767,78,79,60,68,69,671,672,673,674,675,676,22),
        'exceptions_max'=>3,
        'exceptions_min'=>2
    ),
'380'=>Array(
        'name'=>'Украина',
        'cityCodeLength'=>4,
        'zeroHack'=>false,
        'exceptions'=>Array(91,68,67,96,97,991,996,39,94,67,96,97,98,50,66,95,99,92,63,93,44,652),
        'exceptions_max'=>3,
        'exceptions_min'=>2
    ),
'370'=>Array(
        'name'=>'Литва',
        'cityCodeLength'=>3,
        'zeroHack'=>false,
        'exceptions'=>Array(65,62,61,60,67,5),
        'exceptions_max'=>3,
        'exceptions_min'=>2
    ),
'372'=>Array(
        'name'=>'Эстония',
        'cityCodeLength'=>2,
        'zeroHack'=>false,
        'exceptions'=>Array(539,595,6),
        'exceptions_max'=>3,
        'exceptions_min'=>2
    ),
'993'=>Array(
        'name'=>'Туркменистан',
        'cityCodeLength'=>3,
        'zeroHack'=>false,
        'exceptions'=>Array(12,6),
        'exceptions_max'=>3,
        'exceptions_min'=>1
    ),
'374'=>Array(
        'name'=>'Армения',
        'cityCodeLength'=>2,
        'zeroHack'=>false,
        'exceptions'=>Array(10,11),
        'exceptions_max'=>3,
        'exceptions_min'=>2
    ),
'996'=>Array(
        'name'=>'Кыргызстан',
        'cityCodeLength'=>3,
        'zeroHack'=>false,
        'exceptions'=>Array(312,3222,50,77,54,58,51,55,57,70,56),
        'exceptions_max'=>3,
        'exceptions_min'=>2
    ),
'420'=>Array(
        'name'=>'Чехия ',
        'cityCodeLength'=>2,
        'zeroHack'=>false,
        'exceptions'=>Array(),
        'exceptions_max'=>3,
        'exceptions_min'=>2
    ),
);


function str_phone($str_phone = '')
{
$phones = explode(';', $str_phone);
$format_phones = '';
$minus_s = 0;
$i_cur = 1;
	foreach ($phones as $cur_phone) {
	$phoneNumber = preg_replace('/\s|\+|-|\(|\)/','', $cur_phone);
		if(is_numeric($phoneNumber)){ // если номер
		
		$format_phones .= phone($cur_phone).', ';
		$minus_s = 2;
		if ($i_cur == 2) {
			$format_phones .= '<br>';
			$minus_s = 6;
			}
		$i_cur += 1;
		} else {
		$format_phones .= $cur_phone.'  ';
		}	
	}
$format_phones = substr($format_phones,0,strlen($format_phones)-$minus_s);

return $format_phones;
}


function phone($phone = '', $convert = true, $trim = true)
{
    global $phoneCodes;
    if (empty($phone)) {
        return '';
    }
    // очистка от лишнего мусора с сохранением информации о "плюсе" в начале номера
    $phone=trim($phone);
    $phone = preg_replace("/[^0-9A-Za-z]/", "", $phone);
    if (strlen($phone) == 10) $phone = '+7'.$phone;
    if ((strlen($phone) == 11) && ($phone[0] == '8')) $phone = '+7'.substr($phone,1,10); // лишняя цифра, возможно 8
    if (($phone[ 0] == '+') || (strlen($phone) > 10 )) $plus = '+';  
    $phone = preg_replace("/[^0-9A-Za-z]/", "", $phone);
    $OriginalPhone = $phone;
 
    // конвертируем буквенный номер в цифровой
    if ($convert == true && !is_numeric($phone)) {
        $replace = array('2'=>array('a','b','c'),
        '3'=>array('d','e','f'),
        '4'=>array('g','h','i'),
        '5'=>array('j','k','l'),
        '6'=>array('m','n','o'),
        '7'=>array('p','q','r','s'),
        '8'=>array('t','u','v'),
        '9'=>array('w','x','y','z'));
 
        foreach($replace as $digit=>$letters) {
            $phone = str_ireplace($letters, $digit, $phone);
        }
    }
 
    // заменяем 00 в начале номера на +
    if (substr($phone,  0, 2)=="00")
    {
        $phone = substr($phone, 2, strlen($phone)-2);
        $plus=true;
    }
 
    // если телефон длиннее 7 символов, начинаем поиск страны
    if (strlen($phone)>7)
    foreach ($phoneCodes as $countryCode=>$data)
    {
        $codeLen = strlen($countryCode);
        if (substr($phone,  0, $codeLen)==$countryCode)
        {
            // как только страна обнаружена, урезаем телефон до уровня кода города
            $phone = substr($phone, $codeLen, strlen($phone)-$codeLen);
            $zero=false;
            // проверяем на наличие нулей в коде города
            if ($data['zeroHack'] && $phone[ 0]=='0')
            {
                $zero=true;
                $phone = substr($phone, 1, strlen($phone)-1);
            }
 
            $cityCode=NULL;
            // сначала сравниваем с городами-исключениями
            if ($data['exceptions_max']!= 0)
            for ($cityCodeLen=$data['exceptions_max']; $cityCodeLen>=$data['exceptions_min']; $cityCodeLen--)
            if (in_array(intval(substr($phone,  0, $cityCodeLen)), $data['exceptions']))
            {
                $cityCode = ($zero ? "0" : "").substr($phone,  0, $cityCodeLen);
                $phone = substr($phone, $cityCodeLen, strlen($phone)-$cityCodeLen);
                break;
            }
            // в случае неудачи с исключениями вырезаем код города в соответствии с длиной по умолчанию
            if (is_null($cityCode))
            {
                $cityCode = substr($phone,  0, $data['cityCodeLength']);
                $phone = substr($phone, $data['cityCodeLength'], strlen($phone)-$data['cityCodeLength']);
            }
            // возвращаем результат
            return ($plus ? "+" : "").$countryCode.' ('.$cityCode.') '.phoneBlocks($phone);
        }
    }
    // возвращаем результат без кода страны и города
    return ($plus ? "+" : "").phoneBlocks($phone);
}
 
// функция превращает любое число в строку формата XX-XX-... или XXX-XX-XX-... в зависимости от четности кол-ва цифр
function phoneBlocks($number){
    $add='';
    if (strlen($number)%2)
    {
        $add = $number[ 0];
        $add .= (strlen($number)<=5 ? "-" : "");
        $number = substr($number, 1, strlen($number)-1);
    }
    return $add.implode("-", str_split($number, 2));
}

?>
