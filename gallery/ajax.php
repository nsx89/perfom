<?
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog.php");
if (!CModule::IncludeModule('iblock')) exit;

require_once($_SERVER["DOCUMENT_ROOT"] . "/local/php_interface/init.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/gallery/img_size.php");


set_time_limit(1);


$obj_num = htmlspecialcharsbx($_POST['obj_num']);
$img_num = htmlspecialcharsbx($_POST['img_num']);
$obj_img = unserialize($_POST['obj_img']);
$obj_dir = htmlspecialcharsbx($_POST['obj_dir']);
$flex = htmlspecialcharsbx($_POST['flex']);

if (empty($obj_num)) exit;


echo imgSize($obj_num,$img_num,$obj_img,$obj_dir,$flex);


require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog.php");
?>