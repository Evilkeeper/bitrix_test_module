<?php
use \Bitrix\Main\Localization\Loc;
?>

<form>
    <table class="data-table">
        <tr>
            <th colspan="2"></th>
        </tr>
    <?php if (isset($arResult['CODE'])):?>
        <tr>
            <td>
                <label for="field-code"><?=Loc::getMessage('CODE')?></label>
            </td>
            <td>
                <input type="text" name="CODE" placeholder="<?=Loc::getMessage('CODE')?>" value="<?=$arResult['CODE']?>" id="field-code"/>
            </td>
        </tr>
    <?php endif;?>
    <?php if (isset($arResult['DATE'])):?>
        <tr>
            <td>
                <label for="field-date-from"><?=Loc::getMessage('DATE')?></label>
            </td>
            <td>
                <input type="text" name="DATE_FROM" placeholder="<?=Loc::getMessage('DATE_FROM')?>" value="<?=$arResult['DATE']['FROM']?>" id="field-date-from"/>
                -
                <input type="text" name="DATE_TO" placeholder="<?=Loc::getMessage('DATE_TO')?>" value="<?=$arResult['DATE']['TO']?>"/>
            </td>
        </tr>
        <br/>
    <?php endif;?>
    <?php if (isset($arResult['COURSE'])):?>
        <tr>
            <td>
                <label for="field-course"><?=Loc::getMessage('COURSE')?></label>
            </td>
            <td>
                <input type="text" name="COURSE_FROM" placeholder="<?=Loc::getMessage('COURSE_FROM')?>" value="<?=$arResult['COURSE']['FROM']?>" id="field-course"/>
                -
                <input type="text" name="COURSE_TO" placeholder="<?=Loc::getMessage('COURSE_TO')?>" value="<?=$arResult['COURSE']['TO']?>"/>
            </td>
        </tr>
        <br/>
    <?php endif;?>
    </table>
    <br/>
    <input type="submit" value="<?=Loc::getMessage('SUBMIT')?>"/>
    <a href="./">
        <input type="button" value="<?=Loc::getMessage('RESET')?>"/>
    </a>
</form>
<hr/>