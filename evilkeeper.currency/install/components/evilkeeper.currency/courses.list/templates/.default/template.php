<?php
use \Bitrix\Main\Localization\Loc;
?>

<?php if (empty($arResult['ITEMS'])):?>
    <p>Ничего не найдено.</p>
<?php else:?>
    <?php if ($arResult['PAGINATION_TOP']):?>
        <?php $APPLICATION->IncludeComponent(
            'bitrix:main.pagenavigation',
            '',
            [
                'NAV_OBJECT' => $arResult['NAV'],
                'SHOW_COUNT' => 'N'
            ],
            false
        );?>
    <?php endif;?>
    <table border="1" style="margin: auto;">
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
    <?php if ($arResult['PAGINATION_BOTTOM']):?>
        <?php $APPLICATION->IncludeComponent(
            'bitrix:main.pagenavigation',
            '',
            [
                'NAV_OBJECT' => $arResult['NAV'],
                'SHOW_COUNT' => 'N'
            ],
            false
        );?>
    <?php endif;?>
<?php endif;?>