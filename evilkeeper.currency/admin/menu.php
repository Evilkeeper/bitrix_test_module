<?php

use \Bitrix\Main\Localization\Loc;

$moduleID = 'evilkeeper.currency';

return [
    'parent_menu' => 'global_menu_services',
    'url' => '/bitrix/admin/'.str_replace('.', '_', $moduleID).'_list.php',
    'text' => Loc::getMessage('TEXT'),
    'title' => Loc::getMessage('TITLE'),
    'icon' => 'currency_menu_icon',
    'page_icon' => 'form_page_icon',
    'module_id' => $moduleID,
    'items_id' => 'menu_webforms',
    'items' => []
];