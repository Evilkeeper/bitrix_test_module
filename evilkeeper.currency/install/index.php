<?php

use \Bitrix\Main\Localization\Loc;
use \Bitrix\Main\IO\Directory;
use \Bitrix\Main\IO\File;

Class evilkeeper_currency extends CModule
{
    /** @var string */
    public $MODULE_ID = 'evilkeeper.currency';
    /** @var string */
    public $PARTNER_NAME = 'Evilkeeper';

    /**
     * Инициализация параметров модуля
     * evilkeeper_currency constructor.
     */
    public function __construct()
    {
        include(__DIR__.'/version.php');

        if (!empty($arModuleVersion['VERSION'])) {
            $this->MODULE_VERSION = $arModuleVersion['VERSION'];
        }
        if (!empty($arModuleVersion['VERSION_DATE'])) {
            $this->MODULE_VERSION_DATE = $arModuleVersion['VERSION_DATE'];
        }

        $this->MODULE_NAME = Loc::getMessage('CURRENCY_MODULE_NAME');
        $this->MODULE_DESCRIPTION = Loc::getMessage('CURRENCY_MODULE_DESCRIPTION');
    }

    /**
     * Установка файлов модуля (компоненты)
     * @return bool - успешность установки
     */
    public function InstallFiles(): bool
    {
        return CopyDirFiles(__DIR__.'/components', $_SERVER['DOCUMENT_ROOT'].'local/components', true,true) &&
            CopyDirFiles(__DIR__.'/admin', $_SERVER['DOCUMENT_ROOT'].'bitrix/admin');
    }

    /**
     * Удаление файлов модуля (компоненты)
     * @return bool - успешность удаления
     */
    public function UnInstallFiles(): bool
    {
        $path = $_SERVER['DOCUMENT_ROOT'].'local/components/'.$this->MODULE_ID;
        Directory::deleteDirectory($path);
        File::deleteFile($_SERVER['DOCUMENT_ROOT'].'/bitrix/admin/evilkeeper_currency_edit.php');
        return !file_exists($path);
    }

    /**
     * Установка базы данных модуля
     * @return bool - успешность установки
     */
    public function InstallDB(): bool
    {
        global $DB, $DBType;
        if (!file_exists(__DIR__.'/db/'.$DBType)) {
            return false;
        }
        $errors = $DB->RunSQLBatch(__DIR__.'/db/'.$DBType.'/install.sql');
        if (is_array($errors)) {
            throw new RuntimeException(implode("\n", $errors));
        }
        return true;
    }

    /**
     * Удаление базы данных модуля
     * @return bool - успешность удаления
     */
    public function UnInstallDB(): bool
    {
        global $DB, $DBType;
        if (!file_exists(__DIR__.'/db/'.$DBType)) {
            return false;
        }
        $errors = $DB->RunSQLBatch(__DIR__.'/db/'.$DBType.'/uninstall.sql');
        if (is_array($errors)) {
            throw new RuntimeException(implode("\n", $errors));
        }
        return true;
    }

    /**
     * Установка модуля
     */
    public function DoInstall()
    {
        global $APPLICATION;

        if (!$this->InstallDB()) {
            throw new RuntimeException(Loc::getMessage('CURRENCY_INSTALL_DB_ERROR'));
        }
        if (!$this->InstallFiles()) {
            throw new RuntimeException(Loc::getMessage('CURRENCY_INSTALL_FILES_ERROR'));
        }
        \Bitrix\Main\ModuleManager::registerModule($this->MODULE_ID);
        CAgent::AddAgent(
            'Evilkeeper\Currency\Agent::getData();',
            $this->MODULE_ID,
            'Y'
        );

        $APPLICATION->IncludeAdminFile(
            Loc::getMessage('CURRENCY_INSTALL_TITLE'),
            __DIR__.'/step.php'
        );
    }

    /**
     * Удаление модуля
     */
    public function DoUninstall()
    {
        global $APPLICATION;
        if (!$this->UnInstallFiles()) {
            throw new RuntimeException(Loc::getMessage('CURRENCY_UNINSTALL_FILES_ERROR'));
        }
        if (!$this->UnInstallDB()) {
            throw new RuntimeException(Loc::getMessage('CURRENCY_UNINSTALL_DB_ERROR'));
        }
        \CAgent::RemoveModuleAgents($this->MODULE_ID);
        \Bitrix\Main\ModuleManager::unRegisterModule($this->MODULE_ID);

        $APPLICATION->IncludeAdminFile(
            Loc::getMessage('CURRENCY_UNINSTALL_TITLE'),
            __DIR__.'/unstep.php'
        );
    }
}