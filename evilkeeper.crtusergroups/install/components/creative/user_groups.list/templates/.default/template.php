<table border="1">
    <tr>
        <th>
            ID
        </th>
        <th>
            <?=GetMessage('GROUP_NAME')?>
        </th>
        <th>
            <?=GetMessage('GROUP_DESCRIPTION')?>
        </th>
        <?if ($arResult['ADD_DETAIL_URL']) {?>
            <th>
                <?=GetMessage('DETAIL_LINK')?>
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
                        <?=GetMessage('DETAILS')?>
                    </a>
                </td>
            <?}?>
        </tr>
    <?}?>
</table>