<?php
if(!check_bitrix_sessid()) return;

CAdminMessage::ShowNote(GetMessage('INSTALLED'));
?>

<form action="<?=$APPLICATION->GetCurPage()?>">
	<input type="hidden" name="lang" value="<?=LANG?>" />
	<input type="submit" value="<?=GetMessage('BACK')?>">
</form>