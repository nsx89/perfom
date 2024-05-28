
<? if (!$USER->IsAuthorized()): ?>
<? $APPLICATION->IncludeComponent('bitrix:system.auth.authorize', 'dealer', array('AUTH_RESULT' => $APPLICATION->arAuthResult)); ?>
<? endif; ?>
