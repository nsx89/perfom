<?
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_before.php");
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_after.php");

require_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/iblock/prolog.php');

require_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/media/classes/custom_admin.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/media/classes/custom_paginate.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/media/classes/media_pages.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/media/classes/media_category.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/media/classes/media_filter.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/media/classes/media_types.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/media/classes/media_constructor.php');

$APPLICATION->SetAdditionalCSS(MEDIA_FOLDER."/css/admin/bitrix.css");
$APPLICATION->SetAdditionalCSS(MEDIA_FOLDER."/css/admin/cropper.css");
$APPLICATION->SetAdditionalCSS(MEDIA_FOLDER."/css/admin/chosen.css");
$APPLICATION->SetAdditionalCSS(MEDIA_FOLDER."/css/admin/style.css");

$APPLICATION->AddHeadScript(MEDIA_FOLDER."/js/admin/jquery.js" );
$APPLICATION->AddHeadScript(MEDIA_FOLDER."/js/admin/cropper.js" );
$APPLICATION->AddHeadScript(MEDIA_FOLDER."/js/admin/chosen.js" );
$APPLICATION->AddHeadScript(MEDIA_FOLDER."/js/admin/history.js" );
$APPLICATION->AddHeadScript(MEDIA_FOLDER."/js/admin/main.js" );

$APPLICATION->SetTitle('Медиа-центр');
?>