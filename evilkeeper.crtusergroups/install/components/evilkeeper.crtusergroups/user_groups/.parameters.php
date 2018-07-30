<?php
use \Bitrix\Main\Localization\Loc;

$arComponentParameters = [
    'PARAMETERS' => [
        'SEF_MODE' => [
            'list' => [
                'NAME' => Loc::getMessage('GROUP_LIST'),
                'DEFAULT' => 'index.php',
                'VARIABLES' => []
            ],
            'detail' => [
                'NAME' => Loc::getMessage('GROUP_DETAIL'),
                'DEFAULT' => '#ELEMENT_ID#/',
                'VARIABLES' => []
            ]
        ],
        'SEF_FOLDER' => [],
        'CACHE_TIME' => [],
        'SET_TITLE' => [],
        'NEW_TITLE' => [
            'NAME' => Loc::getMessage('NEW_TITLE')
        ],
    ]
];