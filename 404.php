<?
/*ini_set('display_errors', 1);
error_reporting(E_ALL);*/
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog.php");
$APPLICATION->SetTitle("404");
header("HTTP/1.0 404 Not Found");
if (!CModule::IncludeModule('iblock') || !CModule::IncludeModule("catalog")) {
    exit;
}
require($_SERVER["DOCUMENT_ROOT"] . "/include/header.php"); ?>
<link href="/css/main.css?v=1" type="text/css"  rel="stylesheet"/>
<link href="/css/responsive.css" type="text/css"  rel="stylesheet"/>
<div class="content-wrapper content-wrapper-error">
    <img src="/img/404.png?v=1" alt="404" class="error-img">
    <h1 class="error-title">Ошибка</h1>
    <div class="error-btns">
        <a href="/" class="error-back-main">На главную страницу</a>
    <? if($_SERVER['HTTP_REFERER'] !== false) { ?>
        <a href="<?=$_SERVER['HTTP_REFERER']?>" class="error-back">Назад</a>
    <? } ?>
    </div>
</div>

<? require($_SERVER["DOCUMENT_ROOT"] . "/include/footer.php");
if (defined("B_PROLOG_INCLUDED") && B_PROLOG_INCLUDED === true) {
    require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/epilog.php");
}