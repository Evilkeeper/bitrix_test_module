<?php
use \Bitrix\Main\Localization\Loc;
?>

<p><a href="..">К списку групп</a></p>
<table border="1">
    <tr>
        <th>
            <?=Loc::getMessage('PROPERTY')?>
        </th>
        <th>
            <?=Loc::getMessage('VALUE')?>
        </th>
    </tr>
    <?foreach ($arResult['ITEM'] as $property => $value) {?>
        <tr>
            <td style="text-align: right;">
                <?=$property?>
            </td>
            <td>
                <?=$value?>
            </td>
        </tr>
    <?}?>
</table>