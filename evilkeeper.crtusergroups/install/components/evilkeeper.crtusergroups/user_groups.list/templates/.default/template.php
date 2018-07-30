<?php
use \Bitrix\Main\Localization\Loc;
?>

<table border="1">
    <tr>
        <th>
            ID
        </th>
        <th>
            <?=Loc::getMessage('GROUP_NAME')?>
        </th>
        <th>
            <?=Loc::getMessage('GROUP_DESCRIPTION')?>
        </th>
        <?if ($arResult['ADD_DETAIL_URL']) {?>
            <th>
                <?=Loc::getMessage('DETAIL_LINK')?>
            </th>
        <?}?>
    </tr>
    <?foreach ($arResult['groups'] as $ITEM) {?>
        <tr>
            <td>
                <?=$ITEM['ID']?>
            </td>
            <td>
                <?=$ITEM['NAME']?>
            </td>
            <td>
                <?=$ITEM['DESCRIPTION']?>
            </td>
            <?if ($arResult['ADD_DETAIL_URL']) {?>
                <td>
                    <a href="<?=$ITEM['url']?>">
                        <?=Loc::getMessage('DETAILS')?>
                    </a>
                </td>
            <?}?>
        </tr>
    <?}?>
</table>