
<? if (!$USER->IsAuthorized()): ?>
<? $APPLICATION->IncludeComponent('bitrix:system.auth.authorize', '', array('AUTH_RESULT' => $APPLICATION->arAuthResult)); ?>
<? endif; ?>
