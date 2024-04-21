<?require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog.php");
if (!CModule::IncludeModule('iblock') || !CModule::IncludeModule("catalog")) {
    exit;
}
/*
универсальный - id 6105
монтажный - id 6104
стыковочный - id 6429 (158448)*/

$grue_arr_const = array(
	'6104'	 	=> array('id'=>'6104','fid'=>'ЧХ002025782','volume'=>290,'consumption'=>0.140),
	'6105' 		=> array('id'=>'6105','fid'=>'ЧХ002025783','volume'=>290,'consumption'=>0.140),
	'150861' 	=> array('id'=>'150861','fid'=>'ВР001023963','volume'=>290,'consumption'=>0.140),
	'150862' 	=> array('id'=>'150862','fid'=>'ВР001023964','volume'=>80,'consumption'=>0.225),
	'158449' 	=> array('id'=>'158449','fid'=>'ВР099022866','volume'=>290,'consumption'=>0.170),
	'158448' 	=> array('id'=>'158448','fid'=>'ВР099022867','volume'=>60,'consumption'=>0.170),
	'6107' 		=> array('id'=>'6107','fid'=>'ВР099022861','volume'=>290,'consumption'=>0.225),
	'6429' 		=> array('id'=>'6429','fid'=>'ВР099022862','volume'=>60,'consumption'=>0.225),
	);

$id_install = 6104;
$id_install_u = 6105;
$id_connect = 158448; // 6429;

$id_install_b = 150861;
$id_connect_b = 150862;

$glue_arr = get_glue_arr();


/**
 * E04.U.290 -> EB05.M.290 (клей универсальный меняем на клей монтажный (Бельгия)
 * E03.S.60 -> EB06.S.80 (клей стыковочный 60 мл меняем на клей стыковочный 80 мл (Бельгия)
 *
 */

if(!in_array($id_install_u,$glue_arr) && in_array($id_install_b,$glue_arr)) {
    $id_install_u = $id_install_b;
    $use_belgium_s = true;
}
if(!in_array($id_connect,$glue_arr) && in_array($id_connect_b,$glue_arr)) {
    $id_connect = $id_connect_b;
}


//к сожалению не через битру.
$res = array();
$cart = $_POST['prod'] ? json_decode($_POST['prod']) : json_decode($_COOKIE['basket']);


$stickLength = 0;
$cart_j = array();
$cart_adh = array();

$GLUET1 = 0;
$GLUET1_U = 0;
$GLUET2 = 0;


foreach ($cart as $citem) {
    if(in_array($citem->id,$glue_arr)) continue;
    if(strpos($citem->id,'s') !== false) {
        $cart_j[] = array('id'=>$citem->id, 'qty'=>$citem->qty);
        continue;
    }
    
    $arFilter = Array('IBLOCK_ID' => 12, 'ACTIVE' => 'Y', 'ID' => $citem->id);
    $db_list = CIBlockElement::GetList(Array("SORT" => "ASC"), $arFilter);
    $ob = $db_list->GetNextElement();
    if (!$ob) continue;
    $ob = array_merge($ob->GetFields(), $ob->GetProperties());

	/* у нас нет в корзине составного элемента и новый расчет по площади
    if ($ob['COMPOSITEPART']['VALUE']) { 
	$ids = $ob['COMPOSITEPART']['VALUE'];
        $ids['LOGIC'] = 'OR';
        $arFilter = Array('IBLOCK_ID' => 12, 'ACTIVE' => 'Y', 'ID' => $ids);
        $db_list = CIBlockElement::GetList(Array("SORT" => "ASC"), $arFilter);
        	while ($ob_comp = $db_list->GetNextElement()) {
        		$ob_comp = array_merge($ob_comp->GetFields(), $ob_comp->GetProperties());
			if (trim(substr($ob_comp['ARTICUL']['VALUE'],0,1)) == "4") {
				if ($ob_comp['S17']['VALUE']) {
        			$GLUET1_U += str_replace(',', '.', $ob_comp['S17']['VALUE'])*$citem->qty;
    				}
			} else {
    				if ($ob_comp['S17']['VALUE']) {
        			$GLUET1 += str_replace(',', '.', $ob_comp['S17']['VALUE'])*$citem->qty;
    				}
			}
			if ($ob_comp['S16']['VALUE']) {
        		//$GLUET2 += str_replace(',', '.', $ob_comp['S16']['VALUE'])*$citem->qty;
				// уменьшаем расход клей на стык на 25%
        		$GLUET2 += str_replace(',', '.', $ob_comp['S16']['VALUE'])*0.75*$citem->qty;
    			}             	
		}


    $cart_j[] = array('id'=>$citem->id, 'qty'=>$citem->qty);
    } else {
	if (trim(substr($ob['ARTICUL']['VALUE'],0,1)) == "4") {
		if ($ob['S17']['VALUE']) {
        		$GLUET1_U += str_replace(',', '.', $ob['S17']['VALUE'])*$citem->qty;
    		}

	} else {

    		if ($ob['S17']['VALUE']) {
        		$GLUET1 += str_replace(',', '.', $ob['S17']['VALUE'])*$citem->qty;
    		}
	}
	if ($ob['S16']['VALUE']) {
        	$GLUET2 += str_replace(',', '.', $ob['S16']['VALUE'])*$citem->qty;
    	}

    $cart_j[] = array('id'=>$citem->id, 'qty'=>$citem->qty);
  }
  */ 
  if (trim(substr($ob['ARTICUL']['VALUE'],0,1)) == "4") {
		if ($ob['SQUARE_M']['VALUE']) {
        		$GLUET1_U += str_replace(',', '.', $ob['SQUARE_M']['VALUE'])*$citem->qty;
    		}
	} else {

    		if ($ob['SQUARE_M']['VALUE']) {
        		$GLUET1 += str_replace(',', '.', $ob['SQUARE_M']['VALUE'])*$citem->qty;
    		}
	}
	if ($ob['SQUARE_S']['VALUE']) {
        	$GLUET2 += str_replace(',', '.', $ob['SQUARE_S']['VALUE'])*$citem->qty;
    	}

    $cart_j[] = array('id'=>$citem->id, 'qty'=>$citem->qty);
  
  
}

if ($GLUET1) {
    if(in_array($id_install,$glue_arr)) {
		$qty1 = ceil($GLUET1 * $grue_arr_const[$id_install_u]['consumption'] / $grue_arr_const[$id_install_u]['volume']);
		/*
        $qty1 = ceil($GLUET1/290);
		*/
        $cart_j[] = array('id'=>$id_install, 'qty'=>$qty1);
        $cart_adh[] = array('id'=>$id_install, 'qty'=>$qty1);
    }
}
if ($GLUET1_U) {
    if(in_array($id_install_u,$glue_arr)) {
		$qty1_U = ceil($GLUET1_U * $grue_arr_const[$id_install_u]['consumption'] / $grue_arr_const[$id_install_u]['volume']);
		/*
        $qty1_U = ceil($GLUET1_U / 290);
		*/
        $cart_j[] = array('id' => $id_install_u, 'qty' => $qty1_U);
        $cart_adh[] = array('id' => $id_install_u, 'qty' => $qty1_U);
    }
}
if ($GLUET2) {
    if(in_array($id_connect,$glue_arr)) {
		 $qty2 = ceil($GLUET2 * $grue_arr_const[$id_connect]['consumption'] / $grue_arr_const[$id_connect]['volume']);
		/*
        if ($use_belgium_s === true) {
            $qty2 = ceil($GLUET2 / 80);
        } else {
            $qty2 = ceil($GLUET2 / 60); // Старый объем 80
        }
		*/
        $cart_j[] = array('id' => $id_connect, 'qty' => $qty2);
        $cart_adh[] = array('id' => $id_connect, 'qty' => $qty2);
    }
}

if($_POST['task'] == 'glue_calc') {
   print json_encode($cart_j); 
}

if($_POST['task'] == 'glue_show') {

    ob_start();?>
    <p class="adh_text1">Дла ваших товаров нужен следующий клей:</p>
    <table class="add_adh_table">
    <?
    $all_price = 0;
    foreach($cart_adh as $citem) {
        $arFilter = Array('IBLOCK_ID' => $iblockid, 'ACTIVE' => 'Y', 'ID' => $citem['id']);
        $db_list = CIBlockElement::GetList(Array("SORT" => "ASC"), $arFilter);
        while ($ob = $db_list->GetNextElement()) {
            $ob = array_merge($ob->GetFields(), $ob->GetProperties());
            $cost = _makeprice(CPrice::GetBasePrice($ob['ID']));
            $ob['price'] = $cost['PRICE'];
            $ob = __get_product_images($ob);
            ?>
            <tr>
                <td>
                    <img src="<?=$ob['FILES_IMAGES'][0]?>" alt="<?= __get_product_name($ob) ?>"/>
                </td>
                <td><?= __get_product_name($ob) ?></td>
                <td><?=$citem['qty']?> шт.</td>
                <td><?=__cost_format(round($ob['price']*$citem['qty'],2))?></td>
            </tr>

        <?
        $all_price += round($ob['price']*$citem['qty'],2);
        }
    }
    ?>
    </table>
    <p>Добавить клей в корзину?</p>
    <p>Если вы нажмете "Да", ваш заказ увеличится на <b><?=__cost_format($all_price)?></b>.</p>

   <?$html = ob_get_clean();

    print json_encode($html);
}

if($_POST['prod']) {

    ob_start();
    foreach ($cart_adh as $citem) {
        $arFilter = Array('IBLOCK_ID' => $iblockid, 'ACTIVE' => 'Y', 'ID' => $citem['id']);
        $db_list = CIBlockElement::GetList(Array("SORT" => "ASC"), $arFilter);
        while ($ob = $db_list->GetNextElement()) {
            $item = array_merge($ob->GetFields(), $ob->GetProperties());
            ?>
            <tr data-type="prod-row" data-flex="<?= $item['FLEX']['VALUE'] ?>"
                data-id="<?= $item['ID'] ?>" data-adh="y">
                <td data-type="prod-numb"></td>
                <td><?= __get_product_name($item) ?></td>
                <?
                $cost = _makeprice(CPrice::GetBasePrice($item['ID']), 3109);
                $price = $cost['PRICE'];
                ?>
                <td data-type="prod-price" data-val="<?= $price ?>"><?= __cost_format($price, '3109') ?></td>
                <td>
                    <div class="order-prod-numb-wrap">
                        <div class="order-numb-prod-btn-plus" data-type="prod-minus"><i class="icon-minus"></i></div>
                        <input type="text" value="<?=$citem['qty']?>" name="prod-numb" class="order-prod-numb">
                        <div class="order-numb-prod-btn-minus" data-type="prod-plus"><i class="icon-plus"></i></div>
                    </div>
                </td>
                <td data-type="oneProdPrice"><?=__cost_format(round($price*$citem['qty'],2), '3109')?></td>
                <td><i class="icon-delete pacc-close" data-type="remove-product" title="Удалить товар"></i></td>
            </tr>
            <?
        }
    }
    $html = ob_get_clean();

    print json_encode($html);
}