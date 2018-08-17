<?php
namespace Evilkeeper\Currency;

use \Bitrix\Main\Localization\Loc;

if (!\Bitrix\Main\Loader::includeModule('evilkeeper.currency')) {
    throw new \RuntimeException(Loc::getMessage('MODULE_ERROR'));
}

$listValues = array_keys(CourseTable::getEntity()->getFields());
$listValues = array_combine($listValues, $listValues);
$filterValues = $listValues;
unset($filterValues['ID']);

$arComponentParameters = [
    'GROUPS' => [
        'FILTER'    =>  [
            'NAME'  =>  Loc::getMessage('FILTER_GROUP'),
            'SORT'  =>  '750',
        ],
    ],
    'PARAMETERS' => [
        'ITEMS_COUNT' => [
            'PARENT' => 'VISUAL',
            'NAME' => Loc::getMessage('ITEMS_COUNT'),
            'TYPE' => 'NUMBER'
        ],
        'TOP_PAGES' => [
            'PARENT' => 'VISUAL',
            'NAME' => Loc::getMessage('PAGINATION_TOP'),
            'TYPE' => 'CHECKBOX'
        ],
        'BOTTOM_PAGES' => [
            'PARENT' => 'VISUAL',
            'NAME' => Loc::getMessage('PAGINATION_BOTTOM'),
            'TYPE' => 'CHECKBOX'
        ],
        'INCLUDE_FILTER' => [
            'PARENT' => 'FILTER',
            'NAME' => Loc::getMessage('INCLUDE_FILTER'),
            'TYPE' => 'CHECKBOX'
        ],
        'FILTER' => [
            'PARENT' => 'FILTER',
            'NAME' => Loc::getMessage('FILTER'),
            'TYPE' => 'LIST',
            'MULTIPLE' => 'Y',
            'VALUES' => $filterValues
        ],
        'COLUMNS' => [
            'PARENT' => 'FILTER',
            'NAME' => Loc::getMessage('COLUMNS'),
            'TYPE' => 'LIST',
            'MULTIPLE' => 'Y',
            'VALUES' => $listValues
        ]
    ]
];
