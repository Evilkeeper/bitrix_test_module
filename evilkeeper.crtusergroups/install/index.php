<?php

use \Bitrix\Main\IO\Directory;
use \Bitrix\Main\Localization\Loc;

/**
 * Class evilkeeper_crtusergroups
 * Класс модуля с компонентами вывода групп пользователей
 */
Class evilkeeper_crtusergroups extends CModule
{
    /** @var string */
    public $MODULE_ID = 'evilkeeper.crtusergroups';
    /** @var string */
    public $PARTNER_NAME = 'Evilkeeper';

    /**
     * evilkeeper_crtusergroups constructor.
     * Инициализация модуля.
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

        $this->MODULE_NAME = Loc::getMessage('MODULE_NAME');
        $this->MODULE_DESCRIPTION = Loc::getMessage('MODULE_DESCRIPTION');
    }

    /**
     * Устанавливает файлы компонентов модуля в проект.
     * @return bool - успешность установки
     */
    public function InstallFiles()
    {
        return CopyDirFiles(__DIR__.'/components', $_SERVER['DOCUMENT_ROOT'].'local/components', true,true);
    }

    /**
     * Удаляет файлы компонентов модуля из проекта
     * @return bool - успешность удаления
     */
    public function UnInstallFiles()
    {
        $componentPath = $_SERVER['DOCUMENT_ROOT'].'local/components/'.$this->MODULE_ID;
        Directory::deleteDirectory($componentPath);
        return !file_exists($componentPath);
    }

    /**
     * Устанавливает модуль в проект
     */
    public function DoInstall()
    {
        global $APPLICATION;
        $res = $this->InstallFiles();
        if (!$res) {
            throw new RuntimeException('Error occurred while installing files');
        }

        RegisterModule($this->MODULE_ID);
        $APPLICATION->IncludeAdminFile(
            Loc::getMessage('INSTALLING').' '.$this->MODULE_ID,
            __DIR__.'/step.php'
        );
    }

    /**
     * Удаляет модуль из проекта
     */
    public function DoUninstall()
    {
        global $APPLICATION;
        $res = $this->UnInstallFiles();
        if (!$res) {
            throw new RuntimeException('Error occurred while uninstalling files');
        }

        UnRegisterModule($this->MODULE_ID);
        $APPLICATION->IncludeAdminFile(
            Loc::getMessage('FORM_INSTALL_TITLE'),
            __DIR__.'/unstep.php'
        );
    }
}