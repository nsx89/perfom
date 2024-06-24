<?
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog.php");
if (!CModule::IncludeModule('iblock') || !CModule::IncludeModule("catalog")) {
    exit;
}



exit;

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

/*$ARR = array(
	'shop@piterra.ru',
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
);*/

$ARR = array('evroplast.cheboksary@gmail.com');


$EMAILS_SEND = array();

$i = 0;
echo '<table>
	<tr>
		<th>Поддомен</th>
		<th>Email</th>
		<th>Дата подтверждения</th>
		<th>Через сколько минут подтверждено</th>
		<th>Дилер</th>
		<th>SEND</th>
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
    //$EMAIL = 'nsx89@mail.ru'; 
    
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

    //if (!empty($DATE)) continue;
    if ($subdomain <> 'cheboksary.perfom-decor.ru') continue;

	$EMAILS_SEND[] = $email;

	$LINK = 'https://'.$subdomain.'/cart/confirm?subdomain='.$subdomain.'&dealer='.$DEALER_ID;

	$SUBJECT = 'Перфом заказ № 29999 c сайта '.$subdomain.' - '.$LOC_NAME;

	$CLIENT = '<strong>Вы получили данное письмо, в рамках тестового заказа, для проверки вашего Email.</strong><br /><br />
	<a href="'.$LINK.'" style="text-decoration: none; border: none;"><img src="https://'.$subdomain.'/cron/send/img/button.png" alt="Подтвердить получение тестовой заявки" /></a>
	<br /><br />
	<strong>Если не подтвердите получение, данное письмо будет считаться не полученным, <br>и с вами свяжется региональный менеджер.</strong><br /><br />
	<table style="width: 500px;">
		<tbody>
			<tr>
				<td style="width: 30%;padding: 0 6px 1px 0;vertical-align: top;border-bottom: 1px dotted #ccc;">Имя:</td>
				<td style="text-align: left;border-bottom: 1px dotted #ccc;">Тест</td>
			</tr>
			<tr>
				<td style="width: 30%;padding: 0 6px 1px 0;vertical-align: top;border-bottom: 1px dotted #ccc;">Фамилия:</td>
				<td style="text-align: left;border-bottom: 1px dotted #ccc;">Тест</td>
			</tr>
			<tr>
				<td style="width: 30%;padding: 0 6px 1px 0;vertical-align: top;border-bottom: 1px dotted #ccc;">Телефон:</td>
				<td style="text-align: left;border-bottom: 1px dotted #ccc;"><span class="js-phone-number">+7 (911) 111-11-11</span></td>
			</tr>
			<tr>
				<td style="width: 30%;padding: 0 6px 1px 0;vertical-align: top;border-bottom: 1px dotted #ccc;">E-mail:</td>
				<td style="text-align: left;border-bottom: 1px dotted #ccc;">noreply@perfom-decor.ru</td>
			</tr>
			<tr>
				<td style="width: 30%;padding: 0 6px 1px 0;vertical-align: top;border-bottom: 1px dotted #ccc;">Комментарии:</td>
				<td style="text-align: left;border-bottom: 1px dotted #ccc;">ТЕСТ</td>
			</tr>
		</tbody>
	</table>
	<br />
	домен: <a href="https://'.$subdomain.'" target="_blank" rel=" noopener noreferrer">'.$subdomain.'</a><br />
	<br />
	ip: <a href="http://whois.domaintools.com/91.234.152.117" target="_blank" rel=" noopener noreferrer">91.234.152.117</a><br />
	<br />
	Авто-определение региона: '.$LOC_NAME.'<br />
	Выбранный регион: '.$LOC_NAME.'<br />
	<br />
	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/124.0.0.0 Safari/537.36<br />
	<br />
	Версия сайта: десктоп<br />
	<br />';

	$PRODUCT_LIST = '<table class="cart_t_mr_css_attr" style="width: 720px;margin: 12px 0px;">
		<tbody>
			<tr>
				<th style="width: 15%;padding: 6px 12px;text-align: left;font: bold 13px Arial, Helvetica, sans-serif;color: #fff;border: 1px #eaeaea solid;background: #849795;">
					Миниатюра
				</th>
				<th style="width: 33%;padding: 6px 12px;text-align: left;font: bold 13px Arial, Helvetica, sans-serif;color: #fff;border: 1px #eaeaea solid;background: #849795;">
					Наименование
				</th>
				<th style="width: 19%;padding: 6px 12px;text-align: left;font: bold 13px Arial, Helvetica, sans-serif;color: #fff;border: 1px #eaeaea solid;background: #849795;">
					Цена
				</th>
				<th style="width: 10%;text-align: center;padding: 6px 12px;text-align: left;font: bold 13px Arial, Helvetica, sans-serif;color: #fff;border: 1px #eaeaea solid;background: #849795;">
					Количество
				</th>
				<th style="width: 28%;padding: 6px 12px;text-align: left;font: bold 13px Arial, Helvetica, sans-serif;color: #fff;border: 1px #eaeaea solid;background: #849795;">
					Сумма
				</th>
			</tr>
			<tr class="cart_row_mr_css_attr">
				<td class="c_1_mr_css_attr" style="border: none;width:116px;height:104px;overflow:hidden;background-color:#4e4e4e;vertical-align:middle;" align="right">
					<a href="#" target="_blank" rel=" noopener noreferrer">
						<img
							style="width:100%;height:auto;max-height:104px;"
							src="https://perfom-decor.ru/cron/catalog/data/images/nope.jpg"
							alt="карниз 6.50.248"
						/>
					</a>
				</td>

				<td style="border: 1px #eaeaea solid;padding: 2px 12px;">
					<input type="hidden" class="data-item-id_mr_css_attr" value="74279" />
					<span class="number_mr_css_attr">1.</span>&nbsp;
					<a href="#" target="_blank" rel=" noopener noreferrer">тест</a>
				</td>
				<td style="border: 1px #eaeaea solid;padding: 2px 12px;">
					<span><span class="cart_item_cost_mr_css_attr">0 RUB</span></span>
				</td>

				<td class="cart_item_count_mr_css_attr" style="border: 1px #eaeaea solid;padding: 2px 12px;">
					<span class="cart_item_count_mr_css_attr">1</span>
				</td>
				<td style="border: 1px #eaeaea solid;padding: 2px 12px;">
					<span><span class="cart_item_amount_mr_css_attr">0 RUB</span></span>
				</td>
			</tr>

			<tr>
				<td style="text-align: right;border: 1px #eaeaea solid;padding: 6px 12px;" colspan="3">Всего товаров</td>
				<td style="text-align: left;border: 1px #eaeaea solid;padding: 6px 12px;" colspan="1">
					<span><b>1</b></span> шт.
				</td>
				<td style="text-align: left;border: 1px #eaeaea solid;padding: 6px 12px;" colspan="1">
					<span><b>0 RUB</b></span>
				</td>
			</tr>

			<tr>
				<td style="text-align: left;padding: 12px 12px;font: 16px Arial;border: 0px #eaeaea solid;" colspan="5">
					<small>Онлайн заказ не является публичной офертой.</small><br />
					<br />
				</td>
			</tr>
			<tr>
				<td style="text-align: left;padding: 0px 12px;font: 18px Arial;border: 0px #eaeaea solid;" colspan="5">
					Ваш Перфом<br />
					<br />
				</td>
			</tr>
		</tbody>
	</table>';

	$fields = array('EMAIL'=>$EMAIL, 'PRODUCT_LIST'=>$PRODUCT_LIST, 'NUM_Z' => $SUBJECT, 'DEALER_INFO'=> '', 'CLIENT'=>$CLIENT, 'DEALER_CONTACTS'=>'');
    //$SEND = CEvent::SendImmediate("EUROPLAST_ORDER_SALE_COPY", s1, $fields, "N");

	echo '<tr>
		<td>'.$subdomain.'</td>
		<td>'.$EMAIL.'</td>
		<td>'.$DATE.'</td>
		<td>'.$MINUTES.'</td>
		<td>'.$DEALER.'</td>
		<td>'.$SEND.'</td>
	</tr>';
	
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