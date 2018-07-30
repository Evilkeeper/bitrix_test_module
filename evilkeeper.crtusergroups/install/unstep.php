<?php
use \Bitrix\Main\Localization\Loc;

if(!check_bitrix_sessid()) return;

CAdminMessage::ShowNote(Loc::getMessage('UNINSTALLED'));
?>

<form action="<?=$APPLICATION->GetCurPage()?>">
	<input type="hidden" name="lang" value="<?=LANG?>" />
	<input type="submit" value="<?=Loc::getMessage('BACK')?>">
</form>