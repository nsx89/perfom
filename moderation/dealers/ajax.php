<?
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog.php");
if (!CModule::IncludeModule('iblock') || !CModule::IncludeModule("catalog")) {
    exit;
}
require_once($_SERVER["DOCUMENT_ROOT"] . "/include/phone.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/moderation/dealers/parameters.php");
use \Bas\Pict;
global $USER;
$user_id = $USER->GetID();
$rsUser = CUser::GetByID($user_id);
$user = $rsUser->Fetch();

$send_to_hidden_emails = 'nadida.hi@yandex.ru';

function new_pass() {
    $chars = "qazxswedcvfrtgbnhyujmkiolp1234567890QAZXSWEDCVFRTGBNHYUJMKIOLP0123456789";
    $max = 10;
    $size = StrLen($chars)-1;
    $password = null;
    while($max--)
        $password.=$chars[rand(0,$size)];
    return $password;
}
function delDir($dir) {
    if($dir == $_SERVER["DOCUMENT_ROOT"].'/img/dealers/'
        || $dir == $_SERVER["DOCUMENT_ROOT"].'/upload/dealers/temp/') {
        return;
    }
    $files = array_diff(scandir($dir), ['.','..']);
    foreach ($files as $file) {
        (is_dir($dir.'/'.$file)) ? delDir($dir.'/'.$file) : unlink($dir.'/'.$file);
    }
    return rmdir($dir);
}
function get_img_name($item) {
    $res = $item;
    $item = explode(".",$item);
    if(count($item) > 1) {
        unset($item[count($item) - 1]);
        $res = implode($item);
    }
    return mb_strtolower($res);
}
function delImg($dir,$img_name) {
    if($dir == $_SERVER["DOCUMENT_ROOT"].'/img/dealers/') return;
    $files = array_diff(scandir($dir), ['.','..']);
    foreach ($files as $file) {
        if (is_dir($dir.'/'.$file)) {
            delImg($dir.'/'.$file,$img_name);
        } else {
            $file_name = get_img_name($file);
            if($file_name == $img_name) {
                unlink($dir.'/'.$file);
                //удаляем дубль .webp
                delImg($dir,$img_name);
            }
        }
    }
}
function delAllImg($id=false) {
    if($id && $id != '') {
        $img_path =  $_SERVER["DOCUMENT_ROOT"].'/img/dealers/'.$id;
        $resize_img_path = $_SERVER["DOCUMENT_ROOT"].'/upload/resize_cache/img/dealers/'.$id;
        if($img_path == $_SERVER["DOCUMENT_ROOT"].'/img/dealers/') return;
        $images = scandir($img_path);
        if($images) {
            $images_all = array_diff(scandir($img_path), ['.', '..']);
            $images = preg_grep('~\.(jpeg|jpg|png)$~', $images);
            $images = (array_values($images));
            //если в папке есть картинки
            if (count($images) > 0) {
                delDir($img_path);
                delDir($resize_img_path);
            }
        }
    }
}
function writeStaff($item) {
    $res = Array();
    $phone = '';
    foreach ($item['tel'] as $t=>$u_tel) {
        $phone .= $u_tel;
        if($t < count($item['tel']) - 1) $phone .= '; ';
    }
    /*if(!$item['access'] || $item['access'] == '' ) $item['access'] = 'read';
    $code = 'DEALERS_'.$item['access'];*/
    $code = 'DEALERS_STAFF';
    $rsGroups = CGroup::GetList($by = "c_sort", $order = "asc", Array ("STRING_ID" => $code));
    $group = $rsGroups->Fetch();

    $user = new CUser;
    $rsUsers = CUser::GetList(($by="id"), ($order="asc"), Array("EMAIL"=>$item['mail']));
    $arUser = $rsUsers->Fetch();
    //если пользователь есть - обновляем данные
    if($arUser) {
        $user_groups = CUser::GetUserGroup($arUser['ID']);
        if(!in_array($group['ID'],$user_groups)) $user_groups[] = $group['ID'];

        $fields = Array(
            "NAME"                  => $item['fio'],
            "ACTIVE"                => "Y",
            "GROUP_ID"              => $user_groups,
            "PERSONAL_PHONE"        => $phone,
            "PERSONAL_PROFESSION"    => $item['pos']
        );
        if($user->Update($arUser['ID'], $fields)) {
            $res['id'] = $arUser['ID'];
        } else {
            $res['err'][] = $user->LAST_ERROR;
        }

    } else {
        //если нет - сохраняем нового
        $pass = new_pass();
        $arFields = Array(
            "NAME"                  => $item['fio'],
            "EMAIL"                 => $item['mail'],
            "LOGIN"                 => $item['mail'],
            "ACTIVE"                => "Y",
            "GROUP_ID"              => array($group['ID']),
            "PASSWORD"              => $pass,
            "CONFIRM_PASSWORD"      => $pass,
            "PERSONAL_PHONE"        => $phone,
            "PERSONAL_PROFESSION"   => $item['pos']
        );

        $ID = $user->Add($arFields);
        if (intval($ID) > 0) {
            $res['id'] = $ID;
        } else {
            $res['err'][] = $user->LAST_ERROR;
        }
    }
    return $res;
}
function checkRegions($id,$reg,$order,$d_reg,$only_reg) {
    if($id /*&& $reg != '3109'*/) {
        if($order == 'Y') {
            $arFilter = Array("IBLOCK_ID"=>7, "ACTIVE"=>"Y", "ID"=>$reg);
            $res = CIBlockElement::GetList(Array(), $arFilter, false, Array(), Array());
            while($ob = $res->GetNextElement()) {
                $item = array_merge($arFields = $ob->GetFields(),$arFields = $ob->GetProperties());
                if($only_reg != 'Y') {
                    if($item['dealers_list']['VALUE'] == '' || !in_array($id,$item['dealers_list']['VALUE'])) {
                        if($item['dealers_list']['VALUE'] == '') $dealers_list = Array($id);
                        if(!in_array($id,$item['dealers_list']['VALUE'])) {
                            $dealers_list = $item['dealers_list']['VALUE'];
                            $dealers_list[] = $id;
                        }

                        $el = new CIBlockElement;
                        CIBlockElement::SetPropertyValuesEX($item['ID'],7,Array('dealers_list'=>$dealers_list));
                    }
                } else {
                    if(is_array($item['dealers_list']['VALUE']) && in_array($id,$item['dealers_list']['VALUE'])) {
                        $dealers_list = Array();
                        foreach($item['dealers_list']['VALUE'] as $v) {
                            if($v != $id) $dealers_list[] = $v;
                        }
                        if(empty($dealers_list)) $dealers_list = false;
                        $el = new CIBlockElement;
                        CIBlockElement::SetPropertyValuesEX($item['ID'],7,Array('dealers_list'=>$dealers_list));
                    }
                }

            }
            //регионы
            $arFilter = Array("IBLOCK_ID"=>7, "ACTIVE"=>"Y",Array("LOGIC" => "OR",Array("PROPERTY_reg_dealers"=>$id,"ID"=>$d_reg),Array("ID"=>$d_reg),Array("PROPERTY_reg_dealers"=>$id)));
            $res = CIBlockElement::GetList(Array(), $arFilter, false, Array(), Array());
            if(intval($res->SelectedRowsCount())>0) {
                while($ob = $res->GetNextElement()) {
                    $item = array_merge($arFields = $ob->GetFields(),$arFields = $ob->GetProperties());
                    $dealers_list = Array();
                    //добавить в регион
                    if(is_array($d_reg) && in_array($item['ID'],$d_reg)) {
                        if(is_array($item['reg_dealers']['VALUE']) && !empty($item['reg_dealers']['VALUE'])) $dealers_list = $item['reg_dealers']['VALUE'];
                        $dealers_list[] = $id;
                    }
                    //удалить из региона
                    if(!is_array($d_reg) || !in_array($item['ID'],$d_reg)) {
                        foreach($item['reg_dealers']['VALUE'] as $k=>$v) {
                            if($v != $id) $dealers_list[] = $v;
                        }
                    }
                    if(empty($dealers_list)) $dealers_list = false;
                    $el = new CIBlockElement;
                    CIBlockElement::SetPropertyValuesEX($item['ID'],7,Array('reg_dealers'=>$dealers_list));
                }
            }
        } else {
            $arFilter = Array("IBLOCK_ID"=>7, "ACTIVE"=>"Y", "ID"=>$reg, "PROPERTY_dealers_list"=>$id);
            $res = CIBlockElement::GetList(Array(), $arFilter, false, Array(), Array());
            while($ob = $res->GetNextElement()) {
                $item = array_merge($arFields = $ob->GetFields(),$arFields = $ob->GetProperties());
                $dealers_list = Array();
                foreach($item['dealers_list']['VALUE'] as $k=>$v) {
                    if($v != $id) $dealers_list[] = $v;
                }
                if(empty($dealers_list)) $dealers_list = false;
                $el = new CIBlockElement;
                CIBlockElement::SetPropertyValuesEX($item['ID'],7,Array('dealers_list'=>$dealers_list));
            }
            //удалить из всех регионов регионального дилера
            $arFilter = Array("IBLOCK_ID"=>7, "ACTIVE"=>"Y","PROPERTY_reg_dealers"=>$id);
            $res = CIBlockElement::GetList(Array(), $arFilter, false, Array(), Array());
            while($ob = $res->GetNextElement()) {
                $item = array_merge($arFields = $ob->GetFields(),$arFields = $ob->GetProperties());
                $dealers_list = Array();
                foreach($item['reg_dealers']['VALUE'] as $v) {
                    if($v != $id) $dealers_list[] = $v;
                }
                if(empty($dealers_list)) $dealers_list = false;
                $el = new CIBlockElement;
                CIBlockElement::SetPropertyValuesEX($item['ID'],7,Array('reg_dealers'=>$dealers_list));
            }
        }
    }
}

$type = $_REQUEST['type'];
$id = $_REQUEST['id'];
$reg = $_REQUEST['reg'];
$act = $_REQUEST['act'];
$publish = $_REQUEST['publish'];
$org = $_REQUEST['org'];
$point = $_REQUEST['point'];
$addr = $_REQUEST['addr'];
$tel = $_REQUEST['tel'] ? $_REQUEST['tel'] : Array();
$mail = $_REQUEST['mail'];
$url = $_REQUEST['url'];
$work = $_REQUEST['work'];
$sat = $_REQUEST['sat'];
$sun = $_REQUEST['sun'];
$without = $_REQUEST['without'];
$weekend = $_REQUEST['weekend'];
$folder = $_REQUEST['folder'];
$pointtype = $_REQUEST['pointtype'];
$contractor = $_REQUEST['contractor'];
$lat = $_REQUEST['lat'];
$lon = $_REQUEST['lon'];
$mall = $_REQUEST['mall'];
$mark = $_REQUEST['mark'];
$add = $_REQUEST['add'];
$equip = $_REQUEST['equip'];
$assort = $_REQUEST['assort'];
$serv = $_REQUEST['serv'];
$serv_comm = $_REQUEST['serv-comm'];
$staff = $_REQUEST['staff'];
$img = $_REQUEST['img'];

$main = $_REQUEST['main'];
$order = $_REQUEST['order'];
$mail_order = $_REQUEST['mail-order'];
$mail_qs = $_REQUEST['mail-qs'] ? $_REQUEST['mail-qs'] : Array();
$tel_order = $_REQUEST['tel-order'];
$d_reg = $_REQUEST['d-reg'];
$only_reg = $_REQUEST['only-reg'];

//print_r(implode(', ',$mail_qs));

if($type == 'reg-dealer') {
    if(!$id || !$reg || !$act) {
        if($reg) {
            print $reg;
        } else {
            print '3109';
        }
        die();
    }
    $arFilter = Array("IBLOCK_ID"=>7, "ID"=>$reg, 'ACTIVE'=>'Y');
    $res = CIBlockElement::GetList(Array("sort"=>'ASC'), $arFilter, false, Array(), Array());
    while($ob = $res->GetNextElement()) {
        $city = array_merge($ob->GetFields(), $ob->GetProperties());
    }
    $regDealers = $city['reg_dealers']['VALUE'];
    if($regDealers == '') $regDealers = Array();
    if($act == 'add') {
        if(!in_array($id,$regDealers)) {
            $regDealers[] = $id;
            CIBlockElement::SetPropertyValuesEX($city['ID'],7,array("reg_dealers"=>$regDealers));
        }
    }
    if($act == 'remove') {
        if(in_array($id,$regDealers)) {
            unset($regDealers[array_search($id,$regDealers)]);
            if(empty($regDealers)) $regDealers = '';
        }
        if($id == 'all') {
            $regDealers = '';
        }
        CIBlockElement::SetPropertyValuesEX($city['ID'],7,array("reg_dealers"=>$regDealers));
    }

    print $reg;
}

/** СПИСОК ТОЧЕК, КОТОРЫЕ БЫЛИ/ЕСТЬ НА МОДЕРАЦИИ */
if($type == 'mod-spec') {
    $err = array('qty'=>0,'mess'=>'');
    $list = array();
    $arFilter = Array("IBLOCK_ID"=>50, 'PROPERTY_dealer_id'=>$user_id, '!PROPERTY_temp'=>'Y');
    $res = CIBlockElement::GetList(Array("created"=>"desc"), $arFilter, false, Array(), Array());
    if(intval($res->SelectedRowsCount()) > 0) {
        while($ob = $res->GetNextElement()) {
            $item = array_merge($arFields = $ob->GetFields(), $arFields = $ob->GetProperties());
            $arRegFilter = Array("IBLOCK_ID"=>7, "ACTIVE"=>"Y","ID"=>$item['city']['VALUE']);
            $resReg = CIBlockElement::GetList(Array("name"=>'asc'), $arRegFilter, false, Array(), Array());
            while($obReg = $resReg->GetNextElement()) $itemReg = array_merge($arRegFields = $obReg->GetFields(),$arRegFields = $obReg->GetProperties());

            $contact = array();
            $contact['city'] = $itemReg['NAME'];
            $contact['id'] = $item['ID'];
            //$contact['id'] = $item['edit_id']['VALUE'];
            $contact['org'] = $item['organization']['~VALUE'];
            $contact['point'] = $item['trade_point']['~VALUE'];
            $contact['phones'] = str_phone($item['phones']['~VALUE']);
            $contact['orderphones'] = str_phone($item['order_phone']['~VALUE']);
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
            $contact['main'] = $item['main_dealer']['VALUE'];
            $contact['orderDealer'] = $item['order_contact']['VALUE'];
            switch ($item['mod_act']['VALUE']) {
                case 'new':
                    $act = 'Создание';
                    break;
                case 'rem':
                    $act = 'Удаление';
                    break;
                default:
                    $act = "Изменение";
            }
            $contact['result'] = 'На модерации';
            $contact['resultClass'] = 'mod';
            if($item['accept']['VALUE'] == 'Y') {
                $contact['result'] = 'Принято';
                $contact['resultClass'] = 'accept';
            }
            if($item['reject']['VALUE'] == 'Y') {
                $contact['result'] = 'Отклонено';
                $contact['resultClass'] = 'reject';
            }
            $contact['modAct'] = $act;
            $contact['modActTime'] = ConvertTimeStamp($item['DATE_CREATE_UNIX'], "SHORT");

            if ($item['ACTIVE'] == 'Y') {
                $contact['stat'] = 'Опубликовано';
                $contact['statClass'] = 'rel';
            }
            if ($item['ACTIVE'] == 'N') {
                $contact['stat'] = 'Не опубликовано';
                $contact['statClass'] = 'no-rel';
            }
            $point_type = " - ";
            if ($item['point_type']['~VALUE'] == 'retail') $point_type = "Собственная розница";
            if ($item['point_type']['~VALUE'] == 'subdealer') {
                $point_type = "Субдилерская сеть";
                if ($item['contractor']['~VALUE'] != '') {
                    $contact['contractor'] = htmlspecialchars_decode($item['contractor']['~VALUE']);
                }
            }
            $contact['pointStat'] = $point_type;
            $contact['link'] = '?d=' . $item['ID'];

            $list[] = $contact;
        }

    } else {
        $err['qty'] += 1;
        $err['mess'] = 'Дилеры не найдены';
    }
    print json_encode(array('err'=>$err, 'dealers'=>$list));
}

/** ДОБАВИТЬ/РЕДАКТИРОВАТЬ ТОЧКУ (img) */
if($type == 'save') {

    $err = Array();
    $err_qty = 0;

    if(!$reg || !$point || !$org || !$addr || !$tel || !$mail || !$work || !$lat || !$lon) {
        print(json_encode(['errQty'=>count($err),'errMess'=>$err]));
        die();
    }
    //print_r($_REQUEST);
    $staff_id = Array();
    if($staff && !empty($staff)) {
        foreach($staff as $item) {
            $staff_item = writeStaff($item);
            if($staff_item['id']) {
                $staff_id[] = $staff_item['id'];
            }
            if($staff_item['err']) {
                $err[] = $staff_item['err'];
            }
        }
    }

    $resc = CIBlock::GetList(Array(), Array('CODE' => 'dealer'));
    while($arrc = $resc->Fetch())
    {
        $blockid = $arrc["ID"];
    }

    //название города
    $cityFilter = Array("IBLOCK_ID" => 7, "ACTIVE" => "Y", "ID" => $reg);
    $city_res = CIBlockElement::GetList(Array(),$cityFilter,false, Array(), Array());
    while($city_item = $city_res->GetNextElement()) {
        $city_item = array_merge($city_item->GetFields(), $city_item->GetProperties());
        $city_name = $city_item['NAME'];
    }

    $name = $city_name.', '.$point.' ('.$org.')';

    $PROP = array();
    $PROP['trade_point'] = $point;
    $PROP['organization'] = $org;
    $PROP['city'] = $reg;
    if($addr) $PROP['address'] = $addr;
    $PROP['phones'] = implode('; ',$tel);
    if($mail) $PROP['email'] = $mail;
    if($url) $PROP['href'] = $url;
    if($work) $PROP['workday'] = $work;
    if($sat == '') {
        $PROP['saturday'] = 'Выходной';
    } else {
        $PROP['saturday'] = $sat;
    }
    if($sun == '') {
        $PROP['sunday'] = 'Выходной';
    } else {
        $PROP['sunday'] = $sun;
    }
    if($weekend) $PROP['weekend'] = $weekend;
    if($without) $PROP['without'] = $without;
    if($lat && $lon) $PROP['map'] = $lat.','.$lon;
    if($mall) $PROP['trading_center'] = $mall;
    if($mark) $PROP['orientation'] = $mark;
    if($add) $PROP['add'] = $add;
    if($pointtype) $PROP['point_type'] = $pointtype;
    $PROP['contractor'] = $contractor ? $contractor : '';
    $PROP['assort'] = ($assort && !empty($assort)) ? $assort : false;
    $PROP['serv'] = ($serv && !empty($serv)) ? $serv : false;
    if($equip) $PROP['equip'] = $equip;
    if($serv_comm) $PROP['serv_comm'] = $serv_comm;
    $PROP['staff'] = ($staff_id && !empty($staff_id)) ? $staff_id : false;
    $PROP['only_reg_cont'] = $only_reg;

    $PROP['main_dealer'] = $main;
    if($order == 'Y') {
        $PROP['order_phone'] = $tel_order ? $tel_order : '';
        $PROP['orderemail'] = $mail_order ? $mail_order : '';
        $PROP['qs_email'] = implode(', ',$mail_qs);
    }
    /* todo: открыть после изменения вывода контактов в публичной части, изменений в заказе и справочной*/
    /*else {
        $PROP['order_phone'] = '';
        $PROP['orderemail'] = '';
        $PROP['qs_email'] = '';
    }*/

    $active = !$publish ? 'Y' : $publish;



    //если старый контакт
    if(isset($id) && $id != '') {

        $el = new CIBlockElement;
        $arFields = Array(
            'NAME' => $name,
            'ACTIVE' => $active
        );
        $res = $el->Update($id, $arFields);

        CIBlockElement::SetPropertyValuesEX($id,$blockid,$PROP);

        //работаем с картинками


        $img_path =  $_SERVER["DOCUMENT_ROOT"].'/img/dealers/'.$id;
        $resize_img_path = $_SERVER["DOCUMENT_ROOT"].'/upload/resize_cache/img/dealers/'.$id;

        if($img_path != $_SERVER["DOCUMENT_ROOT"].'/img/dealers/') {
            if(isset($img) && !empty($img)) {
                //если есть сохраняемые файлы
                if(!file_exists($img_path)) {
                    mkdir($img_path);
                }
                foreach($img as $img_item) {
                    $item_name_arr = explode("/",$img_item);
                    $item_name = array_pop($item_name_arr);
                    $new_img_arr[] = $item_name; //записываем имена всех сохраняемых файлов
                    //если из папки images
                    if(in_array('images',$item_name_arr)) {
                    }
                    //если из папки temp - перемещаем в images
                    if(in_array('temp',$item_name_arr)) {
                        $temp_path = $_SERVER["DOCUMENT_ROOT"].$img_item;

                        if (!rename(
                            $temp_path,
                            $img_path.'/'.$item_name
                        )) {
                            $err[] = 'Ошибка перемещения загруженного файла';
                        }
                    }

                }

                //после переноса файлов удаляем папку temp
                $temp_img_path = $_SERVER["DOCUMENT_ROOT"].'/upload/dealers/temp/'.$folder;
                if(file_exists($temp_img_path) && $temp_img_path != $_SERVER["DOCUMENT_ROOT"].'/upload/dealers/temp/') {
                    delDir($temp_img_path);
                }

                //после переноса всех файлов проверяем, какие старые файлы из папки images нам надо удалить
                $images = array_diff(scandir($img_path), ['.','..']);
                if($images) {
                    $images = preg_grep('~\.(jpeg|jpg|png)$~', $images);
                    $images = (array_values($images));
                    if(count($images) > 0) {
                        foreach($images as $img_item) {
                            if(!in_array($img_item,$new_img_arr)) {
                                // удаляем все расширения с таким именем
                                $img_name = get_img_name($img_item);
                                // из папки images
                                delImg($img_path,$img_name);
                                // из resize_cache
                                delImg($resize_img_path,$img_name);
                            }
                        }
                    }
                }
            } else {
                //если нет сохраняемых файлов удаляем папки из images и resize_cache
                delDir($img_path);
                delDir($resize_img_path);
            }
        }



    } else {

        $el = new CIBlockElement;


        $arLoadProductArray = Array(
            "IBLOCK_SECTION_ID" => false,
            "IBLOCK_ID"      => $blockid,
            "PROPERTY_VALUES"=> $PROP,
            "NAME"           => $name,
            "ACTIVE"         => $active
        );

        if($id = $el->Add($arLoadProductArray)) {

            $img_path =  $_SERVER["DOCUMENT_ROOT"].'/img/dealers/'.$id;

            if($img_path != $_SERVER["DOCUMENT_ROOT"].'/img/dealers/') {
                if(isset($img) && !empty($img)) {
                    //если есть сохраняемые файлы
                    if(!file_exists($img_path)) {
                        mkdir($img_path);
                    }
                    foreach($img as $img_item) {
                        $item_name_arr = explode("/",$img_item);
                        $item_name = array_pop($item_name_arr);
                        $new_img_arr[] = $item_name; //записываем имена всех сохраняемых файлов
                        //если из папки images
                        if(in_array('images',$item_name_arr)) {
                            if (!copy($_SERVER["DOCUMENT_ROOT"].$img_item, $img_path.'/'.$item_name)) {
                                $err[] = 'Не удалось скопировать файл';
                            }
                        }
                        //если из папки temp - перемещаем в images
                        if(in_array('temp',$item_name_arr)) {
                            $temp_path = $_SERVER["DOCUMENT_ROOT"].$img_item;

                            if (!rename(
                                $temp_path,
                                $img_path.'/'.$item_name
                            )) {
                                $err[] = 'Ошибка перемещения загруженного файла';
                            }
                        }

                    }

                    //после переноса файлов удаляем папку temp
                    $temp_img_path = $_SERVER["DOCUMENT_ROOT"].'/upload/dealers/temp/'.$folder;
                    if(file_exists($temp_img_path) && $temp_img_path != $_SERVER["DOCUMENT_ROOT"].'/upload/dealers/temp/') {
                        delDir($temp_img_path);
                    }

                } 
            }


        } else {
            $err[] = $el->LAST_ERROR;
        }
    }

    checkRegions($id,$reg,$order,$d_reg,$only_reg);



    print(json_encode(['errQty'=>count($err),'errMess'=>$err,'id'=>$id]));

}

/** УДАЛИТЬ ТОЧКУ (img) */
if($type == 'remove') {
    $err = Array();
    if(!$id) {
        print(json_encode(['errQty'=>count($err),'errMess'=>$err]));
        die();
    }
    if(CIBlockElement::Delete($id)) {
        delAllImg($id);
        $arFilter = Array("IBLOCK_ID"=>50,'PROPERTY_edit_id'=>$id);
        $res = CIBlockElement::GetList(Array(), $arFilter, false, Array(), Array());
        if (intval($res->SelectedRowsCount())>0) {
            while($ob = $res->GetNextElement()) {
                $item = $ob->GetFields();
                if(CIBlockElement::Delete($item['ID'])) {
                    delAllImg($item['ID']);
                }
            }
        }
    } else {
        $err[] = 'Ошибка удаления точки';
    }
    print(json_encode(['errQty'=>count($err),'errMess'=>$err]));
}

/** ОТПРАВИТЬ НА МОДЕРАЦИЮ (img) */
if($type == 'mod') {
    $err = array();
    if(!$reg || !$point || !$org || !$addr || !$tel || !$mail || !$work || !$lat || !$lon) {
        print(json_encode(['errQty'=>count($err),'errMess'=>$err]));
        die();
    }


    // название города
    $cityFilter = Array("IBLOCK_ID" => 7, "ACTIVE" => "Y", "ID" => $reg);
    $city_res = CIBlockElement::GetList(Array(),$cityFilter,false, Array(), Array());
    while($city_item = $city_res->GetNextElement()) {
        $city_item = array_merge($city_item->GetFields(), $city_item->GetProperties());
        $city_name = $city_item['NAME'];
    }

    // персонал
    $staff_id = Array();
    if($staff && !empty($staff)) {
        foreach($staff as $item) {
            $staff_item = writeStaff($item);
            if($staff_item['id']) {
                $staff_id[] = $staff_item['id'];
            }
            if($staff_item['err']) {
                $err[] = $staff_item['err'];
            }
        }
    }

    $name = $city_name.', '.$point.' ('.$org.')';

    $PROP = array();

    $PROP['trade_point'] = $point;
    $PROP['organization'] = $org;
    $PROP['city'] = $reg;
    if($addr) $PROP['address'] = $addr;
    $PROP['phones'] = implode('; ',$tel);
    if($mail) $PROP['email'] = $mail;
    if($mail_order) $PROP['orderemail'] = $mail_order;
    $PROP['qs_email'] = implode(', ',$mail_qs);
    if($url) $PROP['href'] = $url;
    if($work) $PROP['workday'] = $work;
    if($sat == '') {
        $PROP['saturday'] = 'Выходной';
    } else {
        $PROP['saturday'] = $sat;
    }
    if($sun == '') {
        $PROP['sunday'] = 'Выходной';
    } else {
        $PROP['sunday'] = $sun;
    }
    if($weekend) $PROP['weekend'] = $weekend;
    if($without) $PROP['without'] = $without;
    if($lat && $lon) $PROP['map'] = $lat.','.$lon;
    if($mall) $PROP['trading_center'] = $mall;
    if($mark) $PROP['orientation'] = $mark;
    if($add) $PROP['add'] = $add;
    if($pointtype) $PROP['point_type'] = $pointtype;
    $PROP['contractor'] = $contractor ? $contractor : '';
    $PROP['assort'] = ($assort && !empty($assort)) ? $assort : false;
    $PROP['serv'] = ($serv && !empty($serv)) ? $serv : false;
    if($equip) $PROP['equip'] = $equip;
    if($serv_comm) $PROP['serv_comm'] = $serv_comm;
    $PROP['staff'] = ($staff_id && !empty($staff_id)) ? $staff_id : false;
    $PROP['dealer_id'] = $USER->GetID();
    $active = !$publish ? 'Y' : $publish;
    $PROP['only_reg_cont'] = $only_reg;
    $PROP['dealer_for_regions'] = is_array($d_reg) && !empty($d_reg) ? $d_reg : false;
    $PROP['order_contact'] = $order;

    $PROP['main_dealer'] = $main;
    if($order == 'Y') {
        $PROP['order_phone'] = $tel_order ? $tel_order : '';
        $PROP['orderemail'] = $mail_order ? $mail_order : '';
        $PROP['qs_email'] = implode(', ',$mail_qs);
    }
    /* todo: открыть после изменения вывода контактов в публичной части, изменений в заказе и справочной*/
    /*else {
        $PROP['order_phone'] = '';
        $PROP['orderemail'] = '';
        $PROP['qs_email'] = '';
    }*/

    // если точка редактируется
    if($id) {
        // ищем точку
        $arFilter = Array("IBLOCK_ID"=>6, "ID"=>$id);
        $res = CIBlockElement::GetList(Array(), $arFilter, false, Array(), Array());
        if(intval($res->SelectedRowsCount()) > 0) {
            $ob = $res->GetNextElement();
            $item = array_merge($arFields = $ob->GetFields(),$arFields = $ob->GetProperties());

            $PROP['mod_act'] = 'change';
            $act = 'Изменение';
            $PROP['edit_id'] = $id;

        } else {
            $err[] = 'Редактруемая точка не найдена в базе';
        }
    } else {
        //если новая точка
        $PROP['mod_act'] = 'new';
        $act = 'Создание';

    }

    $el = new CIBlockElement;

    $arLoadProductArray = Array(
        "IBLOCK_SECTION_ID" => false,
        "IBLOCK_ID"      => 50,
        "PROPERTY_VALUES"=> $PROP,
        "NAME"           => $name,
        "ACTIVE"         => $active
    );

    if($id = $el->Add($arLoadProductArray)) {
        // работа с изображениями

        $img_path =  $_SERVER["DOCUMENT_ROOT"].'/img/dealers/'.$id;

        if($img_path != $_SERVER["DOCUMENT_ROOT"].'/img/dealers/') {
            if(isset($img) && !empty($img)) {
                //если есть сохраняемые файлы
                if(!file_exists($img_path)) {
                    mkdir($img_path);
                }
                foreach($img as $img_item) {
                    $item_name_arr = explode("/",$img_item);
                    $item_name = array_pop($item_name_arr);
                    $new_img_arr[] = $item_name; //записываем имена всех сохраняемых файлов
                    //если из папки images
                    if(in_array('images',$item_name_arr)) {
                        if (!copy($_SERVER["DOCUMENT_ROOT"].$img_item, $img_path.'/'.$item_name)) {
                            $err[] = 'Не удалось скопировать файл';
                        }
                    }
                    //если из папки temp - перемещаем в images
                    if(in_array('temp',$item_name_arr)) {
                        $temp_path = $_SERVER["DOCUMENT_ROOT"].$img_item;

                        if (!rename(
                            $temp_path,
                            $img_path.'/'.$item_name
                        )) {
                            $err[] = 'Ошибка перемещения загруженного файла';
                        }
                    }

                }

                //после переноса файлов удаляем папку temp
                $temp_img_path = $_SERVER["DOCUMENT_ROOT"].'/upload/dealers/temp/'.$folder;
                if(file_exists($temp_img_path) && $temp_img_path != $_SERVER["DOCUMENT_ROOT"].'/upload/dealers/temp/') {
                    delDir($temp_img_path);
                }

            }
        }


        //отправляем письмо

        $send_to_emails = get_mod_emails();
        if($send_to_emails != '') {
            $subj = 'Кабинет дилеров: запрос на модерацию торговой точки';

            $msg = '';
            $msg .= '<p style="margin-top: 20px"><b>Запрос на модерацию:</b></p>';
            $msg .= '<b>Торговая точка</b>: <a href="https://'.$_SERVER['HTTP_HOST'].'/moderation/?type=mod&id='.$id.'#etc4" style="color: #fe5000;" target="_blank">'.$org.' '.$point.'</a><br>';
            $msg .= '<b>Действие</b>: '.$act.'<br>';
            $msg .= '<b>Специалист</b>: '.$user['NAME'];

            $fields = array('EMAIL' => $send_to_emails, 'HIDDEN_EMAIL' => $send_to_hidden_emails, 'SUBJ' => $subj, 'TEXT' => $msg);
            if($_SERVER['HTTP_HOST'] == 'eplast.loc') {
                CEvent::SendImmediate('E_TEMPLATE', s1, $fields, "N");
            } else {
                CEvent::Send('E_TEMPLATE', s1, $fields, "N");
            }
        }





    } else {
        $err[] = $el->LAST_ERROR;
    }








    print(json_encode(['errQty'=>count($err),'errMess'=>$err]));
}

/** ПРИНЯТЬ МОДЕРАЦИЮ (img) */
if($type == 'mod-accept') {
    $err = array();

    if(!$id) {
        print(json_encode(['errQty'=>count($err),'errMess'=>$err]));
        die();
    }

    $arFilter = Array("IBLOCK_ID"=>50, "ID"=>$id);
    $res = CIBlockElement::GetList(Array(), $arFilter, false, Array(), Array());
    while($ob = $res->GetNextElement()) {
        $item = array_merge($arFields = $ob->GetFields(),$arFields = $ob->GetProperties());
    }
    if($item) {

        if($item['mod_act']['VALUE'] == 'rem') {
            if(CIBlockElement::Delete($item['edit_id']['VALUE'])) {
                if($item['edit_id']['VALUE'] != '') {
                    //удаляем старые папки с фотками
                    $path = $_SERVER["DOCUMENT_ROOT"].'/img/dealers/'.$item['edit_id']['VALUE'];
                    if(file_exists($path) && $path != $_SERVER["DOCUMENT_ROOT"].'/img/dealers/') delDir($path);
                    //и ресайз
                    $resize_path = $_SERVER["DOCUMENT_ROOT"].'/upload/resize_cache/img/dealers/'.$item['edit_id']['VALUE'];
                    if(file_exists($resize_path)) delDir($resize_path);
                }
            } else {
                $err[] = 'Ошибка удаления точки';
            }
        } else {
            if($item['mod_act']['VALUE'] == 'change') {
                $arFilter = Array("IBLOCK_ID"=>6, 'ID' => $item['edit_id']['VALUE']);
                $res = CIBlockElement::GetList(Array(), $arFilter, false, Array(), Array());
                while($ob = $res->GetNextElement()) {
                    $old_item = array_merge($arFields = $ob->GetFields(),$arFields = $ob->GetProperties());
                    $main_id = $old_item['ID'];
                }
            }

            $name = $item['NAME'];

            $PROP = array();

            $PROP['trade_point'] = htmlspecialchars_decode($item['trade_point']['~VALUE']);
            $PROP['organization'] = htmlspecialchars_decode($item['organization']['~VALUE']);
            $PROP['city'] = $item['city']['VALUE'];
            $PROP['address'] = htmlspecialchars_decode($item['address']['~VALUE']);
            $PROP['phones'] = $item['phones']['VALUE'];
            $PROP['email'] = $item['email']['VALUE'];
            $PROP['orderemail'] = $item['orderemail']['VALUE'];
            $PROP['qs_email'] = $item['qs_email']['VALUE'];
            $PROP['href'] = $item['href']['VALUE'];
            $PROP['workday'] = $item['workday']['VALUE'];
            $PROP['saturday'] = $item['saturday']['VALUE'];
            $PROP['sunday'] = $item['sunday']['VALUE'];
            $PROP['weekend'] = $item['weekend']['VALUE'];
            $PROP['without'] = $item['without']['VALUE'];
            $PROP['map'] = $item['map']['VALUE'];
            $PROP['trading_center'] = htmlspecialchars_decode($item['trading_center']['~VALUE']);
            $PROP['orientation'] = htmlspecialchars_decode($item['orientation']['~VALUE']);
            $PROP['add'] = $item['add']['VALUE']['TEXT'];
            $PROP['point_type'] = $item['point_type']['VALUE'];
            $PROP['contractor'] = htmlspecialchars_decode($item['contractor']['~VALUE']);
            $PROP['assort'] = $item['assort']['VALUE'];
            $PROP['serv'] = $item['serv']['VALUE'];
            $PROP['equip'] = $item['equip']['VALUE']['TEXT'];
            $PROP['serv_comm'] = $item['serv_comm']['VALUE']['TEXT'];
            $PROP['staff'] = $item['staff']['VALUE'];
            $PROP['only_reg_cont'] = $item['only_reg_cont']['VALUE'];

            $PROP['main_dealer'] = $item['main_dealer']['VALUE'];
            $PROP['order_phone'] = $item['order_phone']['VALUE'];
            $PROP['orderemail'] = $item['orderemail']['VALUE'];
            $PROP['qs_email'] = $item['qs_email']['VALUE'];



            if($main_id && $main_id != '') {
                //меняем

                CIBlockElement::SetPropertyValuesEX($main_id,6,$PROP);

                $el = new CIBlockElement;
                $arFields = Array(
                    'NAME' => $name,
                    'ACTIVE' => $item['ACTIVE']
                );
                $res = $el->Update($main_id, $arFields);

                //удаляем старые папки с фотками
                $path = $_SERVER["DOCUMENT_ROOT"].'/img/dealers/'.$main_id;
                if(file_exists($path) && $path != $_SERVER["DOCUMENT_ROOT"].'/img/dealers/') delDir($path);
                //и ресайз
                $resize_path = $_SERVER["DOCUMENT_ROOT"].'/upload/resize_cache/img/dealers/'.$main_id;
                if(file_exists($resize_path)) delDir($resize_path);

            } else {
                //создаем новый
                $el = new CIBlockElement;
                $arLoadProductArray = Array(
                    "IBLOCK_SECTION_ID" => false,
                    "IBLOCK_ID"      => 6,
                    "PROPERTY_VALUES"=> $PROP,
                    "NAME"           => $name,
                    "ACTIVE"         => $item['ACTIVE']
                );
                if($main_id = $el->Add($arLoadProductArray)) {

                } else {
                    $err[] = $el->LAST_ERROR;
                }
            }

            //переносим картинки, если есть
            $mod_path = $_SERVER["DOCUMENT_ROOT"].'/img/dealers/'.$item['ID'];
            $mod_resize_path = $_SERVER["DOCUMENT_ROOT"].'/upload/resize_cache/img/dealers/'.$item['ID'];
            if(file_exists($mod_path) && $mod_path != $_SERVER["DOCUMENT_ROOT"].'/img/dealers/') {
                $main_path = $_SERVER["DOCUMENT_ROOT"].'/img/dealers/'.$main_id;
                $images = array_diff(scandir($mod_path), ['.','..']);
                $images = (array_values($images));
                if(count($images) > 0) {
                    if(file_exists($main_path) || mkdir($main_path)) {
                        foreach($images as $img) {
                            if (!rename(
                                $mod_path.'/'.$img,
                                $main_path.'/'.$img
                            )) {
                                $err[] = 'Ошибка перемещения загруженного файла '.$img.'<br>';
                            }
                        };

                    }
                }
                if(file_exists($mod_path)) delDir($mod_path);
                if(file_exists($mod_resize_path)) delDir($mod_resize_path);
            }
        }

        checkRegions($main_id,$item['city']['VALUE'],$item['order_contact']['VALUE'],$item['dealer_for_regions']['VALUE'],$item['only_reg_cont']['VALUE']);


        //ставим галку "Принято"
        CIBlockElement::SetPropertyValuesEX($item['ID'],50,Array("accept" => 'Y'));

        //отправляем письмо

        $send_to_emails = get_user_email($item['dealer_id']['VALUE']);
        if($send_to_emails != '') {
            $subj = 'Кабинет дилеров: результаты модерации';

            $msg = '';
            $msg .= '<p style="margin-top: 20px"><b>Результаты модерации:</b></p>';
            $msg .= '<b>Действие</b>: Принято<br>';
            $msg .= '<b>Модератор</b>: '.$user['NAME'].'<br>';
            $msg .= '<b>Торговая точка</b>: <a href="https://'.$_SERVER['HTTP_HOST'].'/moderation/?type=mod-spec-edit&id='.$id.'#etc4" style="color: #fe5000;" target="_blank">'.$item['organization']['VALUE'].' '.$item['trade_point']['VALUE'].'</a>';

            $fields = array('EMAIL' => $send_to_emails, 'HIDDEN_EMAIL' => $send_to_hidden_emails, 'SUBJ' => $subj, 'TEXT' => $msg);
            if($_SERVER['HTTP_HOST'] == 'eplast.loc') {
                CEvent::SendImmediate('E_TEMPLATE', s1, $fields, "N");
            } else {
                CEvent::Send('E_TEMPLATE', s1, $fields, "N");
            }
        }


    } else {
        $err[] = 'Модерируемая информация не найдена в базе.';
    }

    print(json_encode(['errQty'=>count($err),'errMess'=>$err]));
}

/** ОТКЛОНИТЬ МОДЕРАЦИЮ (img) */
if($type == 'mod-reject') {
    $err = array();
    if(!$id) {
        print(json_encode(['errQty'=>count($err),'errMess'=>$err]));
        die();
    }
    CIBlockElement::SetPropertyValuesEX($id,50,Array("reject" => 'Y'));

    // и картинки удаляем, чтоб не захламляли память
    $mod_path = $_SERVER["DOCUMENT_ROOT"].'/img/dealers/'.$id;
    $mod_resize_path = $_SERVER["DOCUMENT_ROOT"].'/upload/resize_cache/img/dealers/'.$id;
    if($mod_path != $_SERVER["DOCUMENT_ROOT"].'/img/dealers/') {
        if(file_exists($mod_path)) delDir($mod_path);
        if(file_exists($mod_resize_path)) delDir($mod_resize_path);
    }

    $arFilter = Array("IBLOCK_ID"=>50, "ID"=>$id);
    $res = CIBlockElement::GetList(Array(), $arFilter, false, Array(), Array());
    while($ob = $res->GetNextElement()) {
        $item = array_merge($arFields = $ob->GetFields(),$arFields = $ob->GetProperties());
    }
    if($item) {
        //отправляем письмо

        $send_to_emails = get_user_email($item['dealer_id']['VALUE']);
        if ($send_to_emails != '') {
            $subj = 'Кабинет дилеров: результаты модерации';

            $msg = '';
            $msg .= '<p style="margin-top: 20px"><b>Результаты модерации:</b></p>';
            $msg .= '<b>Действие</b>: Отклонено<br>';
            $msg .= '<b>Модератор</b>: ' . $user['NAME'].'<br>';
            $msg .= '<b>Торговая точка</b>: <a href="https://' . $_SERVER['HTTP_HOST'] . '/moderation/?type=mod-spec-edit&id=' . $id . '#etc4" style="color: #fe5000;" target="_blank">' . $item['organization']['VALUE'] . ' ' . $item['trade_point']['VALUE'] . '</a>';

            $fields = array('EMAIL' => $send_to_emails, 'HIDDEN_EMAIL' => $send_to_hidden_emails, 'SUBJ' => $subj, 'TEXT' => $msg);
            if($_SERVER['HTTP_HOST'] == 'eplast.loc') {
                CEvent::SendImmediate('E_TEMPLATE', s1, $fields, "N");
            } else {
                CEvent::Send('E_TEMPLATE', s1, $fields, "N");
            }

        }
    }

    print(json_encode(['errQty'=>count($err),'errMess'=>$err]));

}

/** УДАЛИТЬ - ОТПРАВИТЬ НА МОДЕРАЦИЮ */
if($type == 'mod-remove') {
    $err = array();

    if(!$id) {
        print(json_encode(['errQty'=>count($err),'errMess'=>$err]));
        die();
    }

    $arFilter = Array("IBLOCK_ID"=>6, "ID"=>$id);
    $res = CIBlockElement::GetList(Array(), $arFilter, false, Array(), Array());
    while($ob = $res->GetNextElement()) {
        $item = array_merge($arFields = $ob->GetFields(),$arFields = $ob->GetProperties());
    }
    if($item) {

        $name = $item['NAME'];

        $PROP = array();

        $PROP['trade_point'] = $item['trade_point']['VALUE'];
        $PROP['organization'] = $item['organization']['VALUE'];
        $PROP['city'] = $item['city']['VALUE'];
        $PROP['address'] = $item['address']['VALUE'];
        $PROP['phones'] = $item['phones']['VALUE'];
        $PROP['email'] = $item['email']['VALUE'];
        $PROP['orderemail'] = $item['orderemail']['VALUE'];
        $PROP['qs_email'] = $item['qs_email']['VALUE'];
        $PROP['href'] = $item['href']['VALUE'];
        $PROP['workday'] = $item['workday']['VALUE'];
        $PROP['saturday'] = $item['saturday']['VALUE'];
        $PROP['sunday'] = $item['sunday']['VALUE'];
        $PROP['weekend'] = $item['weekend']['VALUE'];
        $PROP['without'] = $item['without']['VALUE'];
        $PROP['map'] = $item['map']['VALUE'];
        $PROP['trading_center'] = $item['trading_center']['VALUE'];
        $PROP['orientation'] = $item['orientation']['VALUE'];
        $PROP['add'] = $item['add']['VALUE']['TEXT'];;
        $PROP['point_type'] = $item['point_type']['VALUE'];
        $PROP['contractor'] = $item['contractor']['VALUE'];
        $PROP['assort'] = $item['assort']['VALUE'];
        $PROP['serv'] = $item['serv']['VALUE'];;
        $PROP['equip'] = $item['equip']['VALUE']['TEXT'];
        $PROP['serv_comm'] = $item['serv_comm']['VALUE']['TEXT'];
        $PROP['staff'] = $item['staff']['VALUE'];
        $PROP['mod_act'] = 'rem';
        $PROP['edit_id'] = $id;
        $PROP['dealer_id'] = $USER->GetID();
        $PROP['order_contact'] = $order;

        $PROP['main_dealer'] = $main;
        if($order == 'Y') {
            $PROP['order_phone'] = $tel_order ? $tel_order : '';
            $PROP['orderemail'] = $mail_order ? $mail_order : '';
            $PROP['qs_email'] = implode(', ',$mail_qs);
            $PROP['only_reg_cont'] = $only_reg;
            $PROP['dealer_for_regions'] = is_array($d_reg) && !empty($d_reg) ? $d_reg : false;
        } else {
            $PROP['order_phone'] = '';
            $PROP['orderemail'] = '';
            $PROP['qs_email'] = '';
            $PROP['only_reg_cont'] = 'N';
            $PROP['dealer_for_regions'] = false;
        }

        $el = new CIBlockElement;
        $arLoadProductArray = Array(
            "IBLOCK_SECTION_ID" => false,
            "IBLOCK_ID"      => 50,
            "PROPERTY_VALUES"=> $PROP,
            "NAME"           => $name,
            "ACTIVE"         => $item['ACTIVE']
        );
        if($main_id = $el->Add($arLoadProductArray)) {
            //отправляем письмо

            $send_to_emails = get_mod_emails();
            if($send_to_emails != '') {
                $subj = 'Кабинет дилеров: запрос на модерацию торговой точки';

                $msg = '';
                $msg .= '<p style="margin-top: 20px"><b>Запрос на модерацию:</b></p>';
                $msg .= '<b>Торговая точка</b>: <a href="https://'.$_SERVER['HTTP_HOST'].'/moderation/?type=mod&id='.$main_id.'#etc4" style="color: #fe5000;" target="_blank">'.$item['organization']['VALUE'].' '.$item['trade_point']['VALUE'].'</a><br>';
                $msg .= '<b>Действие</b>: Удаление<br>';
                $msg .= '<b>Специалист</b>: '.$user['NAME'];

                $fields = array('EMAIL' => $send_to_emails, 'HIDDEN_EMAIL' => $send_to_hidden_emails, 'SUBJ' => $subj, 'TEXT' => $msg);
                if($_SERVER['HTTP_HOST'] == 'eplast.loc') {
                    CEvent::SendImmediate('E_TEMPLATE', s1, $fields, "N");
                } else {
                    CEvent::Send('E_TEMPLATE', s1, $fields, "N");
                }
            }
        } else {
            $err[] = $el->LAST_ERROR;
        }
    } else {
        $err[] = 'Удаляемая точка не найдена в базе.';
    }

    print(json_encode(['errQty'=>count($err),'errMess'=>$err]));
}

/** ПРОМЕЖУТОЧНОЕ СОХРАНЕНИЕ (img) */
if($type == 'mod-save') {
    $err = array();
    if(!$reg || !$point || !$org || !$addr || !$tel || !$mail || !$work || !$lat || !$lon) {
        print(json_encode(['errQty'=>count($err),'errMess'=>$err]));
        die();
    }

    // название города
    $cityFilter = Array("IBLOCK_ID" => 7, "ACTIVE" => "Y", "ID" => $reg);
    $city_res = CIBlockElement::GetList(Array(),$cityFilter,false, Array(), Array());
    while($city_item = $city_res->GetNextElement()) {
        $city_item = array_merge($city_item->GetFields(), $city_item->GetProperties());
        $city_name = $city_item['NAME'];
    }

    // персонал
    $staff_id = Array();
    if($staff && !empty($staff)) {
        foreach($staff as $item) {
            $staff_item = writeStaff($item);
            if($staff_item['id']) {
                $staff_id[] = $staff_item['id'];
            }
            if($staff_item['err']) {
                $err[] = $staff_item['err'];
            }
        }
    }

    $name = $city_name.', '.$point.' ('.$org.')';

    $PROP = array();

    $PROP['trade_point'] = $point;
    $PROP['organization'] = $org;
    $PROP['city'] = $reg;
    if($addr) $PROP['address'] = $addr;
    $PROP['phones'] = implode('; ',$tel);
    if($mail) $PROP['email'] = $mail;
    if($mail_order) $PROP['orderemail'] = $mail_order;
    $PROP['qs_email'] = implode(', ',$mail_qs);
    if($url) $PROP['href'] = $url;
    if($work) $PROP['workday'] = $work;
    if($sat == '') {
        $PROP['saturday'] = 'Выходной';
    } else {
        $PROP['saturday'] = $sat;
    }
    if($sun == '') {
        $PROP['sunday'] = 'Выходной';
    } else {
        $PROP['sunday'] = $sun;
    }
    if($weekend) $PROP['weekend'] = $weekend;
    if($without) $PROP['without'] = $without;
    if($lat && $lon) $PROP['map'] = $lat.','.$lon;
    if($mall) $PROP['trading_center'] = $mall;
    if($mark) $PROP['orientation'] = $mark;
    if($add) $PROP['add'] = $add;
    if($pointtype) $PROP['point_type'] = $pointtype;
    $PROP['contractor'] = $contractor ? $contractor : '';
    $PROP['assort'] = ($assort && !empty($assort)) ? $assort : false;
    $PROP['serv'] = ($serv && !empty($serv)) ? $serv : false;
    if($equip) $PROP['equip'] = $equip;
    if($serv_comm) $PROP['serv_comm'] = $serv_comm;
    $PROP['staff'] = ($staff_id && !empty($staff_id)) ? $staff_id : false;
    $PROP['dealer_id'] = $USER->GetID();
    $active = !$publish ? 'Y' : $publish;
    $PROP['temp'] = 'Y';
    $PROP['order_contact'] = $order;

    $PROP['main_dealer'] = $main;
    if($order == 'Y') {
        $PROP['order_phone'] = $tel_order ? $tel_order : '';
        $PROP['orderemail'] = $mail_order ? $mail_order : '';
        $PROP['qs_email'] = implode(', ',$mail_qs);
        $PROP['only_reg_cont'] = $only_reg;
        $PROP['dealer_for_regions'] = is_array($d_reg) && !empty($d_reg) ? $d_reg : false;
    } else {
        $PROP['order_phone'] = '';
        $PROP['orderemail'] = '';
        $PROP['qs_email'] = '';
        $PROP['only_reg_cont'] = 'N';
        $PROP['dealer_for_regions'] = false;
    }

    // если есть в промежуточных сохранениях
    if($id) {
        $arFilter = array("IBLOCK_ID" => 50, "ID" => $id, "PROPERTY_temp"=>'Y', "PROPERTY_dealer_id"=>$user_id);
        $res = CIBlockElement::GetList(array(), $arFilter, false, array(), array());
        // если точка сохранена и редактируется
        if (intval($res->SelectedRowsCount()) > 0) {
            $ob = $res->GetNextElement();
            $item = array_merge($arFields = $ob->GetFields(), $arFields = $ob->GetProperties());

            $saved_id = $item['ID'];

            //записываем изменения свойств
            CIBlockElement::SetPropertyValuesEX($saved_id, 50, $PROP);

            // записываем изменения полей
            $el = new CIBlockElement;
            $arFields = array(
                'NAME' => $name,
                'ACTIVE' => $active
            );
            $res = $el->Update($saved_id, $arFields);

            $saved_before = true;

        }
    }

    if(!$saved_id) {
        //если новая точка
        if($id) {
            $PROP['edit_id'] = $id;
            $PROP['mod_act'] = 'change';
        } else {
            $PROP['mod_act'] = 'new';
        }


        $el = new CIBlockElement;

        $arLoadProductArray = Array(
            "IBLOCK_SECTION_ID" => false,
            "IBLOCK_ID"      => 50,
            "PROPERTY_VALUES"=> $PROP,
            "NAME"           => $name,
            "ACTIVE"         => $active
        );

        if($saved_id = $el->Add($arLoadProductArray)) {

        } else {
            $err[] = $el->LAST_ERROR;
        }
    }

    if($saved_id) {
        // работаем с картинками
        // если первое сохранение - папки "/img/.../$id/" и "/temp/.../"
        // если повторное сохранение - папки "/img/.../$saved_id/" и "/temp/.../"


        $img_path =  $_SERVER["DOCUMENT_ROOT"].'/img/dealers/'.$saved_id;
        $resize_img_path = $_SERVER["DOCUMENT_ROOT"].'/upload/resize_cache/img/dealers/'.$saved_id;

        if($img_path != $_SERVER["DOCUMENT_ROOT"].'/img/dealers/') {
            if(isset($img) && !empty($img)) {
                //если есть сохраняемые файлы
                if(!file_exists($img_path)) {
                    mkdir($img_path);
                }
                foreach($img as $img_item) {
                    $item_name_arr = explode("/",$img_item);
                    $item_name = array_pop($item_name_arr);
                    $new_img_arr[] = $item_name; //записываем имена всех сохраняемых файлов
                    //если из папки /img/dealers/$id/ - копируем
                    if(in_array('images',$item_name_arr) && $id != $saved_id) {
                        if (!copy($_SERVER["DOCUMENT_ROOT"].$img_item, $img_path.'/'.$item_name)) {
                            $err[] = 'Не удалось скопировать файл';
                        }
                    }
                    //если из папки temp - перемещаем в images
                    if(in_array('temp',$item_name_arr)) {
                        $temp_path = $_SERVER["DOCUMENT_ROOT"].$img_item;

                        if (!rename(
                            $temp_path,
                            $img_path.'/'.$item_name
                        )) {
                            $err[] = 'Ошибка перемещения загруженного файла';
                        }
                    }

                }

                //после переноса файлов удаляем папку temp
                $temp_img_path = $_SERVER["DOCUMENT_ROOT"].'/upload/dealers/temp/'.$folder;
                if(file_exists($temp_img_path) && $temp_img_path != $_SERVER["DOCUMENT_ROOT"].'/upload/dealers/temp/') {
                    delDir($temp_img_path);
                }

                //после переноса всех файлов проверяем, какие старые файлы из папки images нам надо удалить
                $images = array_diff(scandir($img_path), ['.','..']);
                if($images) {
                    $images = preg_grep('~\.(jpeg|jpg|png)$~', $images);
                    $images = (array_values($images));
                    if(count($images) > 0) {
                        foreach($images as $img_item) {
                            if(!in_array($img_item,$new_img_arr)) {
                                // удаляем все расширения с таким именем
                                $img_name = get_img_name($img_item);
                                // из папки images
                                delImg($img_path,$img_name);
                                // из resize_cache
                                delImg($resize_img_path,$img_name);
                            }
                        }
                    }
                }
            } else {
                //если нет сохраняемых файлов удаляем папки из images и resize_cache
                delDir($img_path);
                delDir($resize_img_path);
            }
        }




    }

    print(json_encode(['errQty'=>count($err),'errMess'=>$err, 'id'=>$saved_id]));
}

/** ПРОМЕЖУТОЧНОЕ СОХРАНЕНИЕ - УДАЛИТЬ  (img) */
if($type == 'remove-saved') {
    $err = Array();
    if(!$id) {
        print(json_encode(['errQty'=>count($err),'errMess'=>$err]));
        die();
    }
    if(CIBlockElement::Delete($id)) {
        delAllImg($id);
    } else {
        $err[] = 'Ошибка удаления точки';
    }
    print(json_encode(['errQty'=>count($err),'errMess'=>$err]));
}

/** ПРОМЕЖУТОЧНОЕ СОХРАНЕНИЕ - ОТПРАВИТЬ НА МОДЕРАЦИЮ */
if($type == 'mod-saved') {
    $err = Array();
    if(!$id) {
        print(json_encode(['errQty'=>count($err),'errMess'=>$err]));
        die();
    }

    $arFilter = Array("IBLOCK_ID"=>50, "ID"=>$id);
    $res = CIBlockElement::GetList(Array(), $arFilter, false, Array(), Array());
    while($ob = $res->GetNextElement()) {
        $item = array_merge($arFields = $ob->GetFields(),$arFields = $ob->GetProperties());
    }
    if($item) {
        CIBlockElement::SetPropertyValuesEX($id,50,Array("temp" => 'N'));

        //отправляем письмо

        $send_to_emails = get_mod_emails();
        if($send_to_emails != '') {
            $subj = 'Кабинет дилеров: запрос на модерацию торговой точки';

            $msg = '';
            $msg .= '<p style="margin-top: 20px"><b>Запрос на модерацию:</b></p>';
            $msg .= '<b>Торговая точка</b>: <a href="https://'.$_SERVER['HTTP_HOST'].'/moderation/?type=mod&id='.$id.'#etc4" style="color: #fe5000;" target="_blank">'.$item['organization']['VALUE'].' '.$item['trade_point']['VALUE'].'</a><br>';
            $act = $item['mod_act']['VALUE'] == 'new' ? 'Создание' : 'Изменение';
            $msg .= '<b>Действие</b>: '.$act.'<br>';
            $msg .= '<b>Специалист</b>: '.$user['NAME'];

            $fields = array('EMAIL' => $send_to_emails, 'HIDDEN_EMAIL' => $send_to_hidden_emails, 'SUBJ' => $subj, 'TEXT' => $msg);
            if($_SERVER['HTTP_HOST'] == 'eplast.loc') {
                CEvent::SendImmediate('E_TEMPLATE', s1, $fields, "N");
            } else {
                CEvent::Send('E_TEMPLATE', s1, $fields, "N");
            }
        }

    } else {
        $err[] = 'Данная точка не найдена в базе.';
    }


    print(json_encode(['errQty'=>count($err),'errMess'=>$err]));
}

/** ПОЛУЧИТЬ СПИСОК ГЛАВНЫХ ДИЛЕРОВ */
if($type == 'get-main') {
    $res_arr = Array();
    $arFilter = Array("IBLOCK_ID"=>6,/* "ACTIVE"=>"Y",*/ "PROPERTY_main_dealer"=>'Y');
    $res = CIBlockElement::GetList(Array(), $arFilter, false, Array(), Array("PROPERTY_organization"));
    while($ob = $res->GetNextElement()) {
        $item = array_merge($arFields = $ob->GetFields(),$arFields = $ob->GetProperties());
        $res_arr[] = htmlspecialchars_decode($item['PROPERTY_ORGANIZATION_VALUE'], ENT_QUOTES);
        //$item['PROPERTY_ORGANIZATION_VALUE'];
    }
    print(json_encode($res_arr));
}