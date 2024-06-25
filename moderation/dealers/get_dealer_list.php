<?
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog.php");
if (!CModule::IncludeModule('iblock') || !CModule::IncludeModule("catalog")) {
    exit;
}
require_once($_SERVER["DOCUMENT_ROOT"] . "/include/phone.php");
use \Bas\Pict;
global $USER;
$user_id = $USER->GetID();

function get_slider_img($path,$w=287,$h=287,$webp=false,$proportion_type=1) {
    $res = $path;

    $subdir = explode('/',str_replace('/upload','',$path));
    $subdir = array_diff($subdir,array(''));
    $file_name = array_pop($subdir);
    $file = $_SERVER["DOCUMENT_ROOT"].$path;
    $file_info = getimagesize($file);

    //изменить размер
    if($w && $h) {

        $proportion = BX_RESIZE_IMAGE_PROPORTIONAL_ALT;
        if($proportion_type == 2) $proportion = BX_RESIZE_IMAGE_EXACT;
        if($proportion_type == 3) $proportion = BX_RESIZE_IMAGE_PROPORTIONAL;

        $subdir = implode('/', $subdir);

        $arElement = Array(
            'FILE_NAME'=>$file_name,
            'SUBDIR'=>$subdir,
            'WIDTH'=>$file_info[0],
            'HEIGHT'=>$file_info[1],
            'CONTENT_TYPE' => $file_info['mime'],
        );
        $arSize = array('width'=>$w,'height'=>$h);
        $CustomFile = new CustomFile();
        $arPhotoSmall = $CustomFile->ResizeImageGet(
            $arElement,
            $arSize,
            $proportion,
            Array(
                "name" => "sharpen",
                "precision" => 0
            )
        );
        /*$arPhotoSmall = CFile::ResizeImageGet(
            $arElement,
            $arSize,
            $proportion,
            Array(
                "name" => "sharpen",
                "precision" => 0
            )
        );*/
        if(isset($arPhotoSmall['src'])) {
            $res = $arPhotoSmall['src'];
        }
    }

    //конвертировать в webp
    if($webp) {
        $arr = Array(
            'FILE_NAME' => $file_name,
            'SRC' => $res,
            'CONTENT_TYPE' => $file_info['mime']
        );
        $res = Pict::getWebp($arr, 100);
        $res = $res['WEBP_SRC'];
    }
    return $res;
}
function get_add_filters($arFilter,$str_query = false) {
    if($_REQUEST['subdealer'] == 'y') {
        $arFilter['PROPERTY_point_type'] = 'subdealer';
    }
    if($_REQUEST['retail'] == 'y') {
        $arFilter['PROPERTY_point_type'] = 'retail';
    }
    if($_REQUEST['subdealer'] == 'y' && $_REQUEST['retail'] == 'y') {
        //$arFilter['PROPERTY_point_type'] = "";
        $arFilter[] =  array(
            'LOGIC' => 'OR',
            $arFilter['PROPERTY_point_type'] = 'subdealer',
            $arFilter['PROPERTY_point_type'] = 'retail',
            );
    }
    if($_REQUEST['maindealer'] == 'y') {
        if($str_query) {
            $arFilter[] =  array('LOGIC' => 'OR',
                '?PROPERTY_contractor' => $str_query,
                array(
                    'PROPERTY_main_dealer' => 'Y',
                    '?PROPERTY_organization' => $str_query,
                ),
            );
        } else {
            $arFilter['PROPERTY_main_dealer'] = 'Y';
        }

    } else if($str_query) {
        $arFilter[] =  array('LOGIC' => 'OR',
            ["?NAME" => $str_query],
            ["?PROPERTY_address" => $str_query],
            ['?PROPERTY_trading_center' => $str_query],
            ['?PROPERTY_orientation' => $str_query],
            ['?PROPERTY_organization' => $str_query],
            ['?PROPERTY_trade_point' => $str_query],
            ['?PROPERTY_phones' => $str_query],
            ['?PROPERTY_email' => $str_query],
            ['?PROPERTY_orderemail' => $str_query],
            ['?PROPERTY_qs_email' => $str_query],
            ['?PROPERTY_href' => $str_query],
            ['?PROPERTY_contractor' => $str_query],
            ['?PROPERTY_equip' => $str_query],
            ['?PROPERTY_serv_comm' => $str_query],
        );
    }
    if($_REQUEST['published'] == 'y') {
        $arFilter['ACTIVE'] = 'Y';
    }
    if($_REQUEST['nopublished'] == 'y') {
        $arFilter['ACTIVE'] = 'N';
    }
    if($_REQUEST['published'] == 'y' && $_REQUEST['nopublished'] == 'y') {
        $arFilter['ACTIVE'] = '';
    }
    return $arFilter;
}


$http_host_temp = explode(":",$_SERVER['HTTP_HOST']);
$_SERVER['HTTP_HOST'] = $http_host_temp[0];

$err = array('qty'=>0,'mess'=>'');
$list = array();

$reg = $_REQUEST['reg']; //дилеры по региону
if(!$reg) $reg = 3109;
$id = $_REQUEST['id']; //дилер по id
$type = $_REQUEST['type']; //дилер на модерации (промеж. сохранение)
//if(!$reg) $reg = 3260; //Казань

if($type == 'search') {
    //поиск
    $str_query = mb_convert_case($_REQUEST['q'], MB_CASE_LOWER, "UTF-8");
    $str_query=preg_replace('/(\S+)/', '"\\1"',$str_query);

    $arFilter = Array("IBLOCK_ID"=> 6);
    $arFilter = get_add_filters($arFilter,$str_query);
    //print_r($arFilter);
    $list['position']['zoom'] = 9;
    $list['position']['lat'] = $lat = '55.75';
    $list['position']['lon'] = $lon = '37.57';
} elseif($id && $type == 'saved') {
    //сохраненные точки
    $arFilter = Array("IBLOCK_ID"=>50, "ID"=>$id, '!PROPERTY_accept'=>'Y', '!PROPERTY_reject'=>'Y', 'PROPERTY_temp'=>'Y');
} elseif($id && $type == 'mod') {
    //на модерации
    $arFilter = Array("IBLOCK_ID"=>50, "ID"=>$id, /*'!PROPERTY_accept'=>'Y', '!PROPERTY_reject'=>'Y'*/);
} elseif($id && $type == 'mod-spec-edit') {
    //на модерации
    $arFilter = Array("IBLOCK_ID"=>50, "ID"=>$id, '!PROPERTY_temp'=>'Y');
} elseif($id) {
    //по id
    $arFilter = Array("IBLOCK_ID"=>6, "ID"=>$id);
} else {
    //по региону
    $arFilter = Array("IBLOCK_ID"=>7, "ID"=>$reg, 'ACTIVE'=>'Y');
    $res = CIBlockElement::GetList(Array("sort"=>'ASC'), $arFilter, false, Array(), Array());
    while($ob = $res->GetNextElement()) {
        $city = array_merge($ob->GetFields(), $ob->GetProperties());
    }
    if(!$city) {
        $err['qty'] += 1;
        $err['mess'] = 'Город не найден';
    } else {
        if($city['reg_dealers']['VALUE']) {//Краснодарский вопрос
            $reg_dealers = $city['reg_dealers']['VALUE'];
            $main_dealers = $reg_dealers;
            $arFilter = Array("IBLOCK_ID"=>6, "ID"=>$reg_dealers);
            $arFilter = get_add_filters($arFilter,false);
            //zoom, lat, lon определяем ниже
        } else {
            $list['position']['zoom'] = $city['zoom']['VALUE'];
            $city_map = explode(',',$city['map']['VALUE']);
            $list['position']['lat'] = $lat = $city_map[0];
            $list['position']['lon'] = $lon = $city_map[1];
            if($city['dealers_list']['VALUE']) $main_dealers = $city['dealers_list']['VALUE'];
            if($reg == 3109) $main_dealers = array(6806, 3714, 3715, 3716, 30875, 27546);
            $arFilter = Array("IBLOCK_ID"=>6, "PROPERTY_city"=>$reg);
            $arFilter = get_add_filters($arFilter,false);
        }
    }
}



$main_dealer_arr = Array();
$dealer_arr = Array();
$not_published_arr = Array();

$res = CIBlockElement::GetList(Array("active"=>'Y,N',"sort"=>"asc"), $arFilter, false, Array(), Array());
$n = 0;
if(intval($res->SelectedRowsCount()) > 0) {
    $dist_arr = Array();
    while($ob = $res->GetNextElement()) {
        $item = array_merge($arFields = $ob->GetFields(),$arFields = $ob->GetProperties());
        if($id && $type == 'mod' && $item['accept']['VALUE'] == 'Y') {
            $err['qty'] += 1;
            $err['mess'] = 'Изменения приняты';
            break;
        }
        if($id && $type == 'mod' && $item['reject']['VALUE'] == 'Y') {
            $err['qty'] += 1;
            $err['mess'] = 'Изменения отклонены';
            break;
        }
        if($item['map']['VALUE'] == '') continue;
        $contact = Array();
        if($city) $contact['city'] = $city['NAME'];
        $contact['id'] = $item['ID'];
        $contact['org'] = $item['organization']['~VALUE'];
        $contact['point'] = $item['trade_point']['~VALUE'];
        $contact['phones'] = str_phone($item['phones']['~VALUE']);
        $contact['orderphones'] = trim(str_phone($item['order_phone']['~VALUE']));
        $contact['email'] = $item['email']['~VALUE'];
        $contact['orderemail'] = $item['orderemail']['~VALUE'];
        $contact['qsemail'] = $item['qs_email']['~VALUE'];
        $contact['url'] = $item['href']['~VALUE'];
        $contact['addr'] = $item['address']['~VALUE'];
        $contact['mall'] = $item['trading_center']['~VALUE'];
        $contact['mark'] = $item['orientation']['~VALUE'];
        $contact['weekdays'] = $item['workday']['~VALUE'];
        $contact['saturday'] = $item['saturday']['~VALUE'];
        $contact['sunday'] = $item['sunday']['~VALUE'];
        $contact['without'] = $item['without']['VALUE'];
        $contact['weekend'] = $item['weekend']['~VALUE'];
        $contact['active'] = $item['ACTIVE'];
        //print_r($item['add']);
        $contact['add'] = htmlspecialchars_decode($item['add']['~VALUE']['TEXT']);
        $contact['assort'] = $item['assort']['~VALUE'];
        $contact['serv'] = $item['serv']['~VALUE'];
        $contact['equip'] = htmlspecialchars_decode($item['equip']['~VALUE']['TEXT']);
        $contact['servComm'] = htmlspecialchars_decode($item['serv_comm']['~VALUE']['TEXT']);
        $contact['main'] = $item['main_dealer']['VALUE'];
        if($type == 'mod-spec-edit') {
            $contact['modResult'] = 'на модерации';
            if($item['accept']['VALUE'] == 'Y') $contact['modResult'] = 'принято';
            if($item['reject']['VALUE'] == 'Y') $contact['modResult'] = 'отклонено';
        }
        if($item['mod_act']) $contact['modAct'] = $item['mod_act']['VALUE'];
        if($item['ACTIVE'] == 'Y') {
            $contact['stat'] = 'Опубликовано';
            $contact['statClass'] = 'rel';
        }
        if($item['ACTIVE'] == 'N') {
            $contact['stat'] = 'Не опубликовано';
            $contact['statClass'] = 'no-rel';
        }
        $photo_arr = array();
        $dir = $_SERVER["DOCUMENT_ROOT"].'/img/dealers/'.$item['ID'].'/';
        $path = '/img/dealers/'.$item['ID'].'/';

        if(is_dir($dir) && file_exists($dir)) {
            $images = scandir($dir);
            $images = preg_grep('~\.(jpeg|jpg|png|JPEG|JPG|PNG)$~', $images);
            //print_r($images);
            $images = (array_values($images));
            for($i=0; $i < count($images); $i++) {
                $photo_arr_item = Array();
                $src = $path.$images[$i];
                $photo_arr_item['big']['old'] = $src;
                $photo_arr_item['big']['webp'] = get_slider_img($src,false,false,true);
                $photo_arr_item['small']['old'] = get_slider_img($src,216,150,false,2);
                $photo_arr_item['small']['webp'] = get_slider_img($src,216,150,true,2);
                $photo_arr[] = $photo_arr_item;
            }
        }

        $contact['photo'] = $photo_arr;
        $point_type = " - ";
        if($item['point_type']['~VALUE'] == 'retail') $point_type = "Собственная розница";
        if($item['point_type']['~VALUE'] == 'subdealer') {
            $point_type = "Субдилерская сеть";
            if($item['contractor']['~VALUE'] != '') {
                $contact['contractor'] = htmlspecialchars_decode($item['contractor']['~VALUE']);
            }
        }
        $contact['pointStat'] = $point_type;
        $contact['link'] = '?d='.$item['ID'];
        $contact['staff'] = Array();
        if($item['staff']['VALUE']) {
            foreach($item['staff']['VALUE'] as $staff_id) {
                $rsUsers = CUser::GetByID($staff_id);
                $arUser = $rsUsers->Fetch();
                if($arUser) {
                    $staff_arr = Array();
                    $staff_arr['fio'] = $arUser['NAME'];
                    $staff_arr['pos'] = $arUser['PERSONAL_PROFESSION'];
                    $staff_arr['phones'] = str_phone($arUser['PERSONAL_PHONE']);
                    $staff_arr['email'] = $arUser['EMAIL'];
                    $contact['staff'][] = $staff_arr;
                }
            }
        }

        if($id) {
            $modArr = Array();
            $arModFilter = Array("IBLOCK_ID"=>50, "PROPERTY_edit_id"=>$id, '!PROPERTY_accept'=>'Y', '!PROPERTY_reject'=>'Y', '!PROPERTY_temp'=>'Y','PROPERTY_dealer_id' => $user_id);
            $resMod = CIBlockElement::GetList(Array(), $arModFilter, false, Array(), Array());
            if(intval($resMod->SelectedRowsCount())>0) {
                while($obMod = $resMod->GetNextElement()) {
                    $itemMod = array_merge($arModFields = $obMod->GetFields(),$arModFields = $obMod->GetProperties());
                    $messArr = Array(
                        'type'=>$itemMod['mod_act']['VALUE'],
                        'date'=>ConvertTimeStamp($itemMod['DATE_CREATE_UNIX'], "FULL")
                    );
                    $modArr[] = $messArr;
                }
                $contact['mod'] = $modArr;
            }
            //ищем, где является региональным дилером
            if($type == 'saved' || $type == 'mod' || $type == 'mod-spec-edit') {
                $contact['orderDealer'] = $item['order_contact']['VALUE'];
                $contact['onlyReg'] = $item['only_reg_cont']['VALUE'];
                if(is_array($item['dealer_for_regions']['VALUE']) && !empty($item['dealer_for_regions']['VALUE'])) {
                    $arRegFilter = Array("IBLOCK_ID"=>7, "ACTIVE"=>"Y","ID"=>$item['dealer_for_regions']['VALUE']);
                    $resReg = CIBlockElement::GetList(Array("name"=>'asc'), $arRegFilter, false, Array(), Array("ID","NAME"));
                    while($obReg = $resReg->GetNextElement()) {
                        $itemReg = $obReg->GetFields();
                        $regArr[] = array('id'=>$itemReg['ID'],'name'=>$itemReg['NAME']);
                    }
                    if(!empty($regArr)) {
                        $contact['regArr'] = $regArr;
                    }
                }

            } else {
                $regArr = Array();
                $arRegFilter = Array("IBLOCK_ID"=>7, "ACTIVE"=>"Y","PROPERTY_reg_dealers"=>$id);
                $resReg = CIBlockElement::GetList(Array("name"=>'asc'), $arRegFilter, false, Array(), Array("ID","NAME"));
                while($obReg = $resReg->GetNextElement()) {
                    $itemReg = $obReg->GetFields();
                    $regArr[] = array('id'=>$itemReg['ID'],'name'=>$itemReg['NAME']);
                }
                if(!empty($regArr)) {
                    $contact['regArr'] = $regArr;
                    $contact['orderDealer'] = 'Y';
                }
                $contact['onlyReg'] = $item['only_reg_cont']['VALUE'];
            }

        }

        if($reg_dealers || $id || $type == 'search') { //Краснодар и выборка по id
            $arFilter = Array("IBLOCK_ID"=>7, "ID"=>$item['city']['VALUE'], 'ACTIVE'=>'Y');
            $c_res = CIBlockElement::GetList(Array("sort"=>'ASC'), $arFilter, false, Array(), Array());
            while($c_ob = $c_res->GetNextElement()) {
                $city = array_merge($c_ob->GetFields(), $c_ob->GetProperties());
            }
            if($city) {
                $contact['city'] = $city['NAME'];
                if($type != 'search') {
                    $list['position']['zoom'] = $city['zoom']['VALUE'];
                    $city_map = explode(',',$city['map']['VALUE']);
                    $list['position']['lat'] = $lat = $city_map[0];
                    $list['position']['lon'] = $lon = $city_map[1];
                    $list['position']['regD'] = 'y';
                    $contact['city'] = $city['NAME'];
                    $contact['cityId'] = $item['city']['VALUE'];
                    if($city['dealers_list']['VALUE'] != '') {
                        if(in_array($id,$city['dealers_list']['VALUE'])) $contact['orderDealer'] = 'Y';
                    }
                }

            }
        }

        if(in_array($item['ID'],$reg_dealers)) {
            $contact['regDealer'] = 'Y';
            $contact['orderDealer'] = 'Y';
        }

        list($lat, $lon) = explode(",", $item['map']['VALUE']);
        if ($lat != 0 && $lon != 0) {
            $contact['lat'] = round($lat,6);
            $contact['lon'] = round($lon,6);
        }

        //определяем главных
        if(in_array($item['ID'],$main_dealers) || !isset($main_dealers) && empty($main_dealer_arr) && $item['statClass'] !== 'no-rel') {
            if(($type!= 'search' && !$id) || ($type == 'search' && in_array($item['ID'],$main_dealers))) {
                $contact['orderDealer'] = 'Y';
            }
            $main_dealer_arr[] = $contact;
        }

        //сортируем по дистанции от центра города
        $distance = getDistanceBetweenPoints($list['position']['lat'], $list['position']['lon'], $lat, $lon);
        $dist_arr[round($distance, 2)][] = $contact;

    }

    ksort($dist_arr);


    //определяем главных дилеров и выносим вперед, неопубликованные - назад
    $order_dist_dealer_arr = Array();
    $main_dist_dealer_arr = Array();

    foreach($dist_arr as $d=>$dist) {
        foreach ($dist as $item) {
            if($item['orderDealer'] == 'Y') {
                if($reg == '3109' && !$id || $_REQUEST['nopublished'] == 'y' && $_REQUEST['published'] != 'y') $item['orderDealer'] = 'N';
                $order_dist_dealer_arr[] = $item;
            } elseif($item['main'] == 'Y') {
               $main_dist_dealer_arr[] = $item;
            } else if($item['statClass'] == 'no-rel') {
                $not_published_arr[] = $item;
            } else {
                $dealer_arr[] = $item;
            }
        }
    }
    if($reg == '3109') {
        $list['items'] = array_merge($main_dist_dealer_arr,$order_dist_dealer_arr,$dealer_arr,$not_published_arr);
    } else {
        $list['items'] = array_merge($order_dist_dealer_arr,$main_dist_dealer_arr,$dealer_arr,$not_published_arr);
    }

} else {
    $err['qty'] += 1;
    $err['mess'] = 'Дилеры не найдены';
}

print json_encode(array('err'=>$err, 'dealers'=>$list));