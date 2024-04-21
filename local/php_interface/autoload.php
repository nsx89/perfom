<?
/**
 * подключается в init.php
 */
Bitrix\Main\Loader::registerAutoLoadClasses(null, [
    'CustomFile' => '/local/php_interface/classes/CustomFile.php',
    '\Bas\Pict' => '/local/php_interface/classes/classPict.php'
]);

