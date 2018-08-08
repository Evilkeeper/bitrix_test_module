<?php
use \Bitrix\Main\Localization\Loc;
?>

<?php if (empty($arResult['ITEMS'])):?>
    <p>Ничего не найдено.</p>
<?php else:?>
    <table border="1">
        <tr>
            <?php foreach ($arResult['COLUMNS'] as $COLUMN) {?>
                <th>
                    <?=Loc::getMessage($COLUMN)?>
                </th>
            <?php }?>
        </tr>
        <?php foreach($arResult['ITEMS'] as $ITEM) {?>
            <tr>
                <?php foreach ($arResult['COLUMNS'] as $COLUMN) {?>
                    <td><?=$ITEM[$COLUMN]?></td>
                <?php }?>
            </tr>
        <?php }?>
    </table>
<?php endif;?>