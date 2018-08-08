<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_before.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/local/modules/evilkeeper.currency/include.php");

use \Bitrix\Main\Localization\Loc;
use \Evilkeeper\Currency\CourseTable;

$moduleID = 'evilkeeper.currency';
$tableID = 'evilkeeper_currency_course';

/**
 * Проверяет поля на корректность
 * @param $value - введёное значение
 * @param $type - тип данных поля {int|float|date|*} (* - по умолчанию правильное)
 * @return bool - есть ли ошибка
 */
function fieldHasError($value, $type)
{
    switch ($type) {
        case 'int':
            preg_match('/[0-9]+/', $value, $res);
            return $value < 1 || $res[0] != $value;
        case 'float':
            preg_match('/[0-9]+\.?[0-9]+/', $value, $res);
            return $value < 0 || $res[0] != $value;
        case 'date':
            return strtotime(str_replace('.', '-', $value)) === false;
        default:
            return false;
    }
}

$POST_RIGHT = $APPLICATION->GetGroupRight($moduleID);
if ($POST_RIGHT == 'D')
    $APPLICATION->AuthForm(Loc::getMessage('ACCESS_DENIED'));

if (!\Bitrix\Main\Loader::includeModule('evilkeeper.currency')) {
    throw new RuntimeException(Loc::getMessage('FALSE'));
}

$sort = new CAdminSorting($tableID, 'ID', 'asc');
$listAdmin = new CAdminList($tableID);

$filter = [
    'find_id' => 'int',
    'find_code' => 'string',
    'find_date_from' => 'date',
    'find_date_to' => 'date',
    'find_course_from' => 'float',
    'find_course_to' => 'float'
];
$listAdmin->InitFilter(array_keys($filter));

foreach ($filter as $field => $type) {
    if (isset($$field) && $$field != '') {
        if (fieldHasError($$field, $type)) {
            $listAdmin->AddFilterError(Loc::getMessage($field.'_error'));
        }
        if ($type == 'date') {
            $$field = str_replace('.', '-', $$field);
            $$field = date_create($$field)->format('d.m.Y H:i:s');
        }
    }
}

$arFilter = [];
if (count($listAdmin->arFilterErrors) == 0)
{
    if (isset($find_id) && $find_id != 0) {
        $arFilter['=ID'] = $find_id;
    }
    if (isset($find_code) && $find_code != '') {
        $arFilter['=CODE'] = $find_code;
    }
    if (isset($find_date_from) && $find_date_from != '') {
        $arFilter['>=DATE'] = $find_date_from;
    }
    if (isset($find_date_to) && $find_date_to != '') {
        $arFilter['<=DATE'] = $find_date_to;
    }
    if (isset($find_course_from) && $find_course_from != 0) {
        $arFilter['>=COURSE'] = $find_course_from;
    }
    if (isset($find_course_to) && $find_course_to != 0) {
        $arFilter['<=COURSE'] = $find_course_to;
    }
}

$arFields = [];
if ($listAdmin->EditAction() && $POST_RIGHT == 'W') {
    foreach($FIELDS as $ID => $arFields) {
        if (!$listAdmin->IsUpdated($ID)) {
            continue;
        }

        $errorList = [];
        if ($arFields == '') {
            $errorList[] = Loc::getMessage('CODE_ERROR');
        }
        if (fieldHasError($arFields['DATE'], 'date')) {
            $errorList[] = Loc::getMessage('DATE_ERROR');
        }
        $arFields['DATE'] = new Bitrix\Main\Type\DateTime($arFields['DATE']);
        if (fieldHasError($arFields['COURSE'], 'float')) {
            $errorList[] = Loc::getMessage('COURSE_ERROR');
        }

        if (empty($errorList)) {
            $res = CourseTable::update($ID, $arFields);

            if (!$res->isSuccess()) {
                $listAdmin->AddGroupError(
                    Loc::getMessage(
                        'SAVE_ERROR', [
                            '#ID#' => $ID,
                            '#ERROR#' => implode(',', $res->getErrorMessages())
                        ]
                    )
                );
            }
        } else {
            $listAdmin->AddGroupError(
                Loc::getMessage(
                    'SAVE_ERROR', [
                        '#ID#' => $ID,
                        '#ERROR#' => implode(', ', $errorList)
                    ]
                )
            );
        }
    }
}

if (($arID = $listAdmin->GroupAction()) && $POST_RIGHT == 'W') {
    if ($_REQUEST['action_target'] == 'selected') {
        $rsData = CourseTable::getList([
            'filter' => $arFilter
        ]);
        while ($arRes = $rsData->Fetch()) {
            $arID[] = $arRes['ID'];
        }
    }

    foreach ($arID as $ID) {
        if (strlen($ID) <= 0) {
            continue;
        }
        $ID = IntVal($ID);

        switch ($_REQUEST['action']) {
            case 'delete':
                $res = CourseTable::delete($ID);
                if (!$res->isSuccess()) {
                    $listAdmin->AddGroupError(
                        Loc::getMessage(
                            'DELETE_ERROR', [
                                '#ID#' => $ID,
                                '#ERROR#' => implode(',', $res->getErrorMessages())
                            ]
                        )
                    );
                }
                break;
            default:
                $listAdmin->AddGroupError(
                    Loc::getMessage('ACTION_ERROR').$_REQUEST['action']
                );
                break;
        }
    }
}

$listAdmin->AddHeaders([
    [
        'id' => 'ID',
        'content' => 'ID',
        'sort' => 'id',
        'default' => true
    ],
    [
        'id' => 'CODE',
        'content' => Loc::getMessage('CODE'),
        'sort' => 'code',
        'default' => true
    ],
    [
        'id' => 'DATE',
        'content' => Loc::getMessage('DATE'),
        'sort' => 'date',
        'default' => true
    ],
    [
        'id' => 'COURSE',
        'content' => Loc::getMessage('COURSE'),
        'sort' => 'COURSE',
        'default' => true
    ]
]);

$arParams = [];
if (!empty($arFilter)) {
    $arParams['filter'] = $arFilter;
}

$usePageNavigation = true;
if (isset($_REQUEST['mode']) && $_REQUEST['mode'] == 'excel') {
    $usePageNavigation = false;
}
else {
    $navParams = CDBResult::GetNavParams(CAdminResult::GetNavSize(
        $sTableID,
        [
            'nPageSize' => 20,
            'sNavID' => $APPLICATION->GetCurPage()
        ]
    ));
    if ($navParams['SHOW_ALL']) {
        $usePageNavigation = false;
    } else {
        $navParams['PAGEN'] = (int)$navParams['PAGEN'];
        $navParams['SIZEN'] = (int)$navParams['SIZEN'];
    }
}

if ($usePageNavigation) {
    $arParams['limit'] = $navParams['SIZEN'];
    $arParams['offset'] = $navParams['SIZEN'] * ($navParams['PAGEN'] - 1);
}

$totalCount = CourseTable::getCount($arFilter);
if ($totalCount > 0) {
    $totalPages = ceil($totalCount / $navParams['SIZEN']);
    if ($navParams['PAGEN'] > $totalPages) {
        $navParams['PAGEN'] = $totalPages;
    }
    $arParams['limit'] = $navParams['SIZEN'];
    $arParams['offset'] = $navParams['SIZEN'] * ($navParams['PAGEN']-1);
} else {
    $navParams['PAGEN'] = 1;
    $arParams['limit'] = $navParams['SIZEN'];
    $arParams['offset'] = 0;
}

$list = \Evilkeeper\Currency\CourseTable::getList($arParams)->fetchAll();
$rsData = new CAdminResult($list, $tableID);

if ($usePageNavigation) {
    $rsData->NavStart($arParams['limit'], $navParams['SHOW_ALL'], $navParams['PAGEN']);
    $rsData->NavRecordCount = $totalCount;
    $rsData->NavPageCount = $totalPages;
    $rsData->NavPageNomer = $navParams['PAGEN'];
} else {
    $rsData->NavStart();
    $rsData->NavRecordCount = $totalCount;
    $rsData->NavPageCount = 1;
    $rsData->NavPageNomer = 1;
}
$listAdmin->NavText($rsData->GetNavPrint(Loc::getMessage('NAVIGATION_TITLE')));

while($arRes = $rsData->NavNext(true, "f_")) {
    $row = &$listAdmin->AddRow($f_ID, $arRes);

    $row->AddInputField('CODE', ['size' => 20]);
    $row->AddViewField('CODE', '<a href="evilkeeper_currency_edit.php?ID='.$f_ID.'&lang='.LANG.'">'.$f_CODE.'</a>');

    $row->AddCalendarField('DATE', ['size' => 20]);

    $row->AddInputField('COURSE', ['size' => 20]);

    $arActions = [];
    $arActions[] = [
        'ICON' => 'edit',
        'DEFAULT' => true,
        'TEXT' => Loc::getMessage('EDIT'),
        'ACTION' => $listAdmin->ActionRedirect('evilkeeper_currency_edit.php?ID='.$f_ID)
    ];

    if ($POST_RIGHT >= 'W') {
        $arActions[] = [
            'ICON' => 'delete',
            'TEXT' => Loc::getMessage('DELETE'),
            'ACTION' => 'if (confirm("'.Loc::getMessage('DELETE_CONFIRM').'")) '.$listAdmin->ActionDoGroup($f_ID, 'delete')
        ];
    }

    $row->AddActions($arActions);
}

$listAdmin->AddFooter([
    [
        'title' => Loc::getMessage('SELECTED_COUNT'),
        'value' => $rsData->SelectedRowsCount()
    ],
    [
        'counter' => true,
        'title' => Loc::getMessage('SELECTED_COUNT_PRESENT'),
        'value' => '0'
    ]
]);

/** some custom actions
$listAdmin->AddGroupActionTable([
    'action' => 'text',
]);
 */

$aContext = [
    [
        'TEXT' => Loc::getMessage('ADD'),
        'LINK' => 'evilkeeper_currency_edit.php?lang='.LANG,
        'TITLE' => Loc::getMessage('TEST'),
        'ICON' => 'btn_new'
    ],
];

$listAdmin->AddAdminContextMenu($aContext);
$listAdmin->CheckListMode();

$oFilter = new CAdminFilter(
    $tableID.'_filter',
    [
        'ID',
        'CODE',
        'DATE',
        'COURSES'
    ]
);

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_after.php");
?>

<form name="find_form" method="get" action="<?=$APPLICATION->GetCurPage()?>">
    <?$oFilter->Begin();?>
    <tr>
        <td>
            ID:
        </td>
        <td>
            <input type="text" name="find_id" size="47" value="<?=htmlspecialchars($find_id)?>"/>
        </td>
    </tr>
    <tr>
        <td>
            <?=Loc::getMessage('CODE')?>:
        </td>
        <td>
            <input type="text" name="find_code" size="47" value="<?=htmlspecialchars($find_code)?>"/>
        </td>
    </tr>
    <tr>
        <td><?=Loc::getMessage('DATE')?>:</td>
        <td>
            <input type="text" name="find_date_from" value="<?=htmlspecialchars($find_date_from)?>"/>
            -
            <input type="text" name="find_date_to" value="<?=$find_date_to?>"/>
        </td>
    </tr>
    <tr>
        <td>
            <?=Loc::getMessage('COURSE')?>:
        </td>
        <td>
            <input type="text" name="find_course_from" value="<?=$find_course_from?>"/>
            -
            <input type="text" name="find_course_to" value="<?=$find_course_to?>"/>
        </td>
    </tr>
    <?
    $oFilter->Buttons([
        'table_id' => $tableID,
        'url' => $APPLICATION->GetCurPage(),
        'form' => 'find_form'
    ]);
    $oFilter->End();
    ?>
</form>

<?$listAdmin->DisplayList();?>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_admin.php");?>
