<?php
$filter = [];
if (isset($arResult['FILTER'])) {
    $filter = $APPLICATION->IncludeComponent(
        'evilkeeper.currency:courses.filter',
        '',
        $arResult['FILTER']
    );
}

$APPLICATION->IncludeComponent(
    'evilkeeper.currency:courses.list',
    '',
    [
        'ITEMS_COUNT' => $arResult['ITEMS_COUNT'],
        'PAGINATION_TOP' => $arResult['PAGINATION_TOP'],
        'PAGINATION_BOTTOM' => $arResult['PAGINATION_BOTTOM'],
        'COLUMNS' => $arResult['COLUMNS'],
        'FILTER' => $filter,
    ]
);
