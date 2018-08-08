<?php
use \Bitrix\Main\Localization\loc;

$arComponentDescription = array(
    'NAME' => Loc::getMessage('COMP_NAME'),
    'DESCRIPTION' => Loc::getMessage('COMP_DESCR'),
    'PATH' => [
        'ID' => 'creative_components',
        'NAME' => Loc::getMessage('FOLDER_NAME'),
        'CHILD' => [
            'ID' => 'currency_components',
            'NAME' => Loc::getMessage('SUBFOLDER_NAME')
        ]
    ],
    'CACHE_PATH' => 'Y'
);