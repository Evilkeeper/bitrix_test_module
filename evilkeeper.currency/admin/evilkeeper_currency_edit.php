<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_before.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/local/modules/evilkeeper.currency/include.php");

use \Bitrix\Main\Localization\Loc;
use \Bitrix\Main\Type\DateTime;
use \Evilkeeper\Currency\CourseTable;

$moduleID = 'evilkeeper.currency';
$tableID = 'evilkeeper_currency_course';

/**
 * Проверяет поля на корректность
 * @param $value - введёное значение
 * @param $type - тип данных поля {int|float|date|*} (* - любое другое, по умолчанию правильное)
 * @return bool - есть ли ошибка
 */
function fieldHasError($value, $type)
{
    switch ($type) {
        case 'int':
            preg_match('/[0-9]+/', $value, $res);
            return $value < 1 || $res[0] != $value;
        case 'float':
            preg_match('/[0-9]+(\.[0-9]+)?/', $value, $res);
            return $value < 0 || $res[0] != $value;
        case 'date':
            return strtotime(str_replace('.', '-', $value)) === false;
        default:
            return false;
    }
}

function check($value) {
    mpr($value ? 'da' : 'net');
}

$POST_RIGHT = $APPLICATION->GetGroupRight($moduleID);
if ($POST_RIGHT == 'D')
    $APPLICATION->AuthForm(Loc::getMessage('ACCESS_DENIED'));

if (!\Bitrix\Main\Loader::includeModule($moduleID)) {
    throw new RuntimeException(Loc::getMessage('FALSE'));
}

$aTabs = [
    [
        'DIV' => 'edit',
        'TAB' => Loc::getMessage('TAB_NAME'),
        'ICON' => 'main_user_edit',
        'TITLE' => Loc::getMessage('TAB_TITLE')
    ]
];
$tabControl = new CAdminTabControl('tabControl', $aTabs);

$ID = intval($ID);
$message = null;
$bVarsFromForm = false;

if (
    isset($REQUEST_METHOD) && $REQUEST_METHOD == 'POST' &&
    ($save != '' || $apply != '') &&
    $POST_RIGHT == 'W' &&
    check_bitrix_sessid()
) {
    $res = [];

    if (!isset($CODE)) {
        $CODE = '';
    }

    if (!isset($DATE)) {
        $DATE = '';
    }
    if ($DATE != '' && fieldHasError($DATE, 'date')) {
        $res[] = Loc::getMessage('DATE_ERROR');
    }

    if (!isset($COURSE) || is_null($COURSE)) {
        $COURSE = '';
    }
    if (fieldHasError($COURSE, 'float')) {
        $res[] = Loc::getMessage('COURSE_ERROR');
    }

    if (!empty($res)) {
        $message = new CAdminMessage(GetMessage('SAVE_ERROR').implode("\n", $res));
        $bVarsFromForm = true;
    } else {
        $data = [
            'CODE' => $CODE,
            'DATE' => fieldHasError($DATE, 'date') ? NULL : new DateTime($DATE),
            'COURSE' => $COURSE
        ];

        if($ID > 0) {
            $res = CourseTable::update($ID, $data);
        } else {
            $res = CourseTable::add($data);
        }

        if($res->isSuccess())
        {
            if ($apply != '')
                LocalRedirect('/bitrix/admin/evilkeeper_currency_edit.php?ID='.$ID.'&mess=ok&lang='.LANG.'&'.$tabControl->ActiveTabParam());
            else
                LocalRedirect('/bitrix/admin/evilkeeper_currency_list.php?lang='.LANG);
        }
        else
        {
            $message = new CAdminMessage(GetMessage('SAVE_ERROR').implode("\n", $res->getErrorMessages()));
            $bVarsFromForm = true;
        }
    }
}

$str_SORT = 100;
$str_CODE = '';
$str_DATE = '';
$str_COURSE = '';

if ($ID > 0) {
    $amount = CourseTable::getCount([
        '=ID' => $ID
    ]);

    if ($amount === 0) {
        $ID = 0;
    } else {
        $element = CourseTable::getList([
            'filter' => ['=ID' => $ID]
        ])->fetch();

        $str_CODE = $element['CODE'];
        $str_DATE = $element['DATE'];
        $str_COURSE = $element['COURSE'];
    }
}

if ($bVarsFromForm) {
    $DB->InitTableVarsForEdit($tableID, '', 'str_');
}

$APPLICATION->SetTitle(($ID > 0 ? Loc::getMessage('TITLE_EDIT') : Loc::getMessage('TITLE_ADD')));

require($_SERVER["DOCUMENT_ROOT"].'/bitrix/modules/main/include/prolog_admin_after.php');

$aMenu = [
    [
        'TEXT' => Loc::getMessage('BACK'),
        'TITLE' => Loc::getMessage('BACK_TIP'),
        'LINK' => 'evilkeeper_currency_list.php?lang='.LANG,
        'ICON' => 'btn_list'
    ]
];

if ($ID > 0)
{
    $aMenu[] = [
        'TEXT' => Loc::getMessage('ADD'),
        'TITLE' => Loc::getMessage('ADD_TIP'),
        'LINK' => 'evilkeeper_currency_edit.php?lang='.LANG,
        'ICON' => 'btn_new',
    ];
    $aMenu[] = [
        'TEXT' => Loc::getMessage('DELETE'),
        'TITLE' => Loc::getMessage('DELETE_TIP'),
        'LINK' => "javascript:if (confirm('".Loc::getMessage('DELETE_CONFIRM')."'))window.location='evilkeeper_currency_list.php?ID=".$ID."&action=delete&lang=".LANG."&".bitrix_sessid_get()."';",
        'ICON' => 'btn_delete',
    ];
}

$context = new CAdminContextMenu($aMenu);
$context->Show();

if ($_REQUEST['mess'] == 'ok' && $ID > 0) {
    CAdminMessage::ShowMessage(['MESSAGE' => Loc::getMessage('SAVED'), 'TYPE' => 'OK']);
}

if ($message) {
    echo $message->Show();
}
?>

<form method="POST" Action="<?echo $APPLICATION->GetCurPage()?>" ENCTYPE="multipart/form-data" name="post_form">
<?=bitrix_sessid_post()?>
<?php
$tabControl->Begin();
$tabControl->BeginNextTab();
?>
    <?php if ($ID > 0):?>
    <tr>
        <td width="40%">ID:</td>
        <td width="60%"><?=$ID?></td>
    </tr>
    <?php endif;?>
    <tr>
        <td><?=Loc::getMessage('CODE')?><span class="required">*</span></td>
        <td><input type="text" name="CODE" value="<?=$str_CODE?>" size="20"/></td>
    </tr>
    <tr>
        <td><?=Loc::getMessage('DATE')?></td>
        <td><input type="text" name="DATE" value="<?=$str_DATE?>" size="20"/></td>
    </tr>
    <tr>
        <td><?=Loc::getMessage('COURSE')?><span class="required">*</span></td>
        <td><input type="text" name="COURSE" value="<?=$str_COURSE?>"/></td>
    </tr>
<?
$tabControl->Buttons(
    [
        'disabled' => ($POST_RIGHT < 'W'),
        'back_url' => 'evilkeeper_currency_list.php?lang='.LANG
    ]
);
?>
    <input type="hidden" name="lang" value="<?=LANG?>"/>
<?php if ($ID > 0 && !$bCopy):?>
    <input type="hidden" name="ID" value="<?=$ID?>"/>
<?endif;?>
<?php
$tabControl->End();

$tabControl->ShowWarnings('post_form', $message);
?>

<?=BeginNote();?>
    <span class="required">*</span><?=Loc::getMessage('REQUIRED_FIELDS')?>
<?=EndNote()?>

<?php
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_admin.php");