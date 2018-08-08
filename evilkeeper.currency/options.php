<?php
use \Bitrix\Main\HttpApplication;
use \Bitrix\Main\Config\Option;
use \Bitrix\Main\Localization\Loc;

CJSCore::Init(['jquery2']);

$request = HttpApplication::getInstance()->getContext()->getRequest();
$module_id = htmlspecialcharsbx($request["mid"] != "" ? $request["mid"] : $request["id"]);
$arTabs = [
    [
        'DIV' => 'settings',
        'TAB' => Loc::getMessage('TAB'),
        'TITLE' => Loc::getMessage('TITLE')
    ]
];

$currencies = [];
if ($request->isPost() && check_bitrix_sessid() && $request['apply']) {
    $currencies = $request->getPost('currencies') ?: [];
    foreach ($currencies as $key => $currency) {
        if (trim($currency) == '') {
            unset($currencies[$key]);
        }
    }
    Option::set($module_id, 'currencies', implode(',', $currencies));
} else {
    $currencies = explode(',', Option::get($module_id, 'currencies'));
}

$tabControl = new CAdminTabControl(
    'tabControl',
    $arTabs
);

$tabControl->Begin();
?>

<form action="<?=$APPLICATION->GetCurPage()?>?mid=<?=$module_id?>&lang=<?=LANG?>" method="post">
    <?php $tabControl->BeginNextTab()?>
    <tr>
        <td class="adm-detail-valign-top adm-detail-content-cell-l" width="50%">
            <?=Loc::getMessage('CURRENCIES')?>
        </td>
        <td class="adm-detail-content-cell-r" width="50%">
            <?php foreach ($currencies as $currency) {?>
                <div>
                    <input type="text" size="30" maxlength="255" value="<?=$currency?>" name="currencies[]"/>
                </div>
            <?php }?>
            <div>
                <input type="text" size="30" maxlength="255" value="" name="currencies[]"/>
            </div>
            <br/>
            <input type="button" value="<?=Loc::getMessage('ADD_FIELD')?>" id="add-field"/>
        </td>
    </tr>

    <?php $tabControl->Buttons();?>
    <input type="submit" name="apply" value="<?=Loc::getMessage('SUBMIT')?>" class="adm-btn-save"/>

    <?=bitrix_sessid_post()?>
</form>

<script>
    $(document).on("click", "#add-field", function() {
        $(this).prev().before('<div><input type="text" size="30" maxlength="255" value="" name="currencies[]"/></div>');
    });
</script>

<?php
$tabControl->end();
