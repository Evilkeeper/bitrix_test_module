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
    'PARAMETERS' => [
        'INCLUDE_FILTER' => [
            'NAME' => Loc::getMessage('INCLUDE_FILTER'),
            'TYPE' => 'CHECKBOX'
        ],
        'FILTER' => [
            'NAME' => Loc::getMessage('FILTER'),
            'TYPE' => 'LIST',
            'MULTIPLE' => 'Y',
            'VALUES' => $filterValues
        ],
        'COLUMNS' => [
            'NAME' => Loc::getMessage('COLUMNS'),
            'TYPE' => 'LIST',
            'MULTIPLE' => 'Y',
            'VALUES' => $listValues
        ]
    ]
];
