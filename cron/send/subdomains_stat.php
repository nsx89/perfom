<?
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog.php");
if (!CModule::IncludeModule('iblock') || !CModule::IncludeModule("catalog")) {
    exit;
}

//exit;

$subdomains = [];
foreach ($city_loc_id AS $key => $item) {
	if ($item == 'perfom-decor.ru') continue;

	$subdomains[$key] = $item;
}
/*
echo '<pre>';
print_r($subdomains);
echo '</pre>';
*/

$ARR = array(
	'respectanna201612@mail.ru',
	'sale@stroy-remo.ru',
	'lepnina44@gmail.com',
	'AKoroleva@57.leso-torg.ru',	
	'rozet.sait@yandex.ru',
	'decor200777@mail.ru',
	'kaporskaya@smit.stbur.ru',
	'lepidecor@yandex.ru',
	'salon1-krd@decorinfo.ru',
	'aragondeco@mail.ru',
	'lepnina76@gmail.com',
);


$EMAILS_SEND = array();

$i = 0;
echo '<table>
	<tr>
		<th>Поддомен</th>
		<th>Email</th>
		<th>Дата подтверждения</th>
		<th>Через сколько минут подтверждено</th>
		<th>Дилер</th>
	</tr>';
foreach($subdomains AS $key => $subdomain) {

	$i++;

	$EMAILS = array();

	$DEALER_ID = '';

	$LOC_NAME = '';
	$arFilter = Array('IBLOCK_ID' => 7, 'ACTIVE' => 'Y', 'ID' => $key);
    $db_list = CIBlockElement::GetList(Array('SORT' => 'ASC'), $arFilter);
    if ($db_list) {
        $el = $db_list->GetNextElement();
        if ($el) {
            $el = array_merge($el->GetFields(), $el->GetProperties());
            $LOC_NAME = $el['NAME'];
        }
    }

	$arFilter = Array('IBLOCK_ID' => 6, 'ACTIVE' => 'Y', 'PROPERTY_city' => $key);
	$db_list = CIBlockElement::GetList(Array('SORT' => 'ASC'), $arFilter);
    if ($db_list) {
        $el = $db_list->GetNextElement();
        if ($el) {
            $el = array_merge($el->GetFields(), $el->GetProperties());

            //порядок текущего дилера при наличии ротации
			$email = $el['orderemail']['VALUE'] ? $el['orderemail']['VALUE'] : $el['email']['VALUE'];
			if (!in_array($email, $EMAILS)) {
				$DEALER_ID = $el['ID'];
				$EMAILS[] = $email;
			}
        }
    }

    $EMAIL = $EMAILS[0];
    
    if (empty($EMAIL)) continue;

    $STAT = [];
    $DATE = '';
    $DEALER = '';
    $MINUTES = '';
    $arFilter = Array('IBLOCK_ID' => 69, 'CODE' => $subdomain);
    $db_list = CIBlockElement::GetList(Array('SORT' => 'ASC'), $arFilter);
    if ($db_list) {
        $el = $db_list->GetNextElement();
        if ($el) {
            $el = array_merge($el->GetFields(), $el->GetProperties());
            $STAT = $el;
            $DATE = $STAT['DATE_CREATE'];
            $DEALER = (string)$STAT['NAME'];
            $DEALER = str_replace('&quot;', '"', $DEALER);
            $DEALER = str_replace('quot', '"', $DEALER);
            $DEALER = str_replace('&', '', $DEALER);
            $DEALER = str_replace('amp;', '', $DEALER);
            $DEALER = str_replace('";', '"', $DEALER);
            //$DEALER = htmlspecialcharsback($DEALER);
            $MINUTES = $STAT['SORT'].' мин.';
            if ($MINUTES > 60) $MINUTES .= " (".(round($MINUTES / 60, 1))." ч.)";
        }
    }

	echo '<tr>
		<td>'.$subdomain.'</td>
		<td>'.$EMAIL.'</td>
		<td>'.$DATE.'</td>
		<td>'.$MINUTES.'</td>
		<td>'.$DEALER.'</td>
	</tr>';

	$EMAILS_SEND[] = $email;
	
}
echo '</table>';

echo '<style>
table {
	border-collapse: collapse;
}
table td, table th {
	border: 1px solid #a9a9a9;
	padding: 3px 5px;
}
</style>';


?>