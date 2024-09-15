<?
define("LEGIT_REQUEST", true);
require_once($_SERVER["DOCUMENT_ROOT"] . "/responsive/include/ed/Mobile_Detect_Point.php");
$detectPoint = new Mobile_Detect_Point;
if ($detectPoint->isMobile()) {
    require_once($_SERVER["DOCUMENT_ROOT"] . "/question_service/mobile/form.php");
} else {
    require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog.php");
    LocalRedirect('/question_service/');

    }