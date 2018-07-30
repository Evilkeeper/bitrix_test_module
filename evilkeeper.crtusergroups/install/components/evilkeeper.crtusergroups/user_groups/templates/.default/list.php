<?php

$arParams['ADD_DETAIL_LINKS'] = 'Y';
$APPLICATION->IncludeComponent(
    'evilkeeper.crtusergroups:user_groups.list',
    '',
    $arParams,
    $component
);