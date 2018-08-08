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
        'COLUMNS' => $arResult['COLUMNS'],
        'FILTER' => $filter
    ]
);
