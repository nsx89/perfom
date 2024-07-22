<?
Class media extends CModule
{
    public $MODULE_ID = 'media';
    public $MODULE_VERSION;
    public $MODULE_VERSION_DATE;
    public $MODULE_NAME;
    public $MODULE_DESCRIPTION;

    // Свойства модуля
    public function __construct() {
        $this->MODULE_NAME = 'Media-центр';
        $this->MODULE_DESCRIPTION = 'Медиа-платформа для дизайнеров, потребителей, дилеров';
        $this->MODULE_VERSION = '1.0';
        $this->MODULE_VERSION_DATE = '2024-03-01';
    }

    // Установка
    public function DoInstall() {
        RegisterModule($this->MODULE_ID);
    }

    // Удаление
    public function DoUninstall() {
        UnRegisterModule($this->MODULE_ID);
    }
}
?>