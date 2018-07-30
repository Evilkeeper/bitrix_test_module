<?php

use \Bitrix\Main\IO\Directory;

Class evilkeeper_crtusergroups extends CModule
{
    var $MODULE_ID = 'evilkeeper.crtusergroups';

    var $MODULE_INSTALL_PATH;

    public function __construct()
    {
        $path = str_replace('\\', '/', __FILE__);
        $path = substr($path, 0, strlen($path) - strlen('/index.php'));
        include($path.'/version.php');
        $this->MODULE_INSTALL_PATH = $path;

        if (isset($arModuleVersion))
        {
            if (array_key_exists('VERSION', $arModuleVersion)) {
                $this->MODULE_VERSION = $arModuleVersion['VERSION'];
            }
            if (array_key_exists('VERSION_DATE', $arModuleVersion)) {
                $this->MODULE_VERSION_DATE = $arModuleVersion['VERSION_DATE'];
            }
        }

        $this->MODULE_NAME = 'Creative User Groups';
        $this->MODULE_DESCRIPTION = GetMessage('MODULE_DESCRIPTION');
        $this->PARTNER_NAME = 'Evilkeeper';
    }

    public function InstallFiles()
    {
        return CopyDirFiles($this->MODULE_INSTALL_PATH.'/components', $_SERVER['DOCUMENT_ROOT'].'local/components', true,true);
    }

    public function UnInstallFiles()
    {
        Directory::deleteDirectory($_SERVER['DOCUMENT_ROOT'].'local/components/creative');
    }

    public function DoInstall()
    {
        global $APPLICATION;
        $res = $this->InstallFiles();
        if (!$res) {
            die('Some error occurred :(');
        }

        RegisterModule($this->MODULE_ID);
        $APPLICATION->IncludeAdminFile(
            GetMessage('INSTALLING').' '.$this->MODULE_ID,
            $this->MODULE_INSTALL_PATH.'/step.php'
        );
    }

    public function DoUninstall()
    {
        global $APPLICATION;
        $this->UnInstallFiles();

        UnRegisterModule($this->MODULE_ID);
        $APPLICATION->IncludeAdminFile(
            GetMessage('FORM_INSTALL_TITLE'),
            $this->MODULE_INSTALL_PATH.'/unstep.php'
        );
    }
}