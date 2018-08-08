<?php

use \Bitrix\Main\Localization\Loc;

if (!check_bitrix_sessid()) {
    return;
}

CAdminMessage::ShowNote(Loc::getMessage('CURRENCY_UNINSTALL_SUCCESS'));
?>

<form action="<?=$APPLICATION->GetCurPage()?>">
    <input type="hidden" name="lang" value="<?=LANG?>"/>
    <input type="submit" value="<?=Loc::getMessage('CURRENCY_INSTALL_RETURN')?>"/>
</form>
