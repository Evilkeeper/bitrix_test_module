<?php

/**
 * Created by PhpStorm.
 * User: michael
 * Date: 25.07.18
 * Time: 12:11
 */

use Bitrix\Main\GroupTable;

class UsersDetail extends CBitrixComponent
{
    /**
     * @return mixed|void
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\ObjectPropertyException
     * @throws \Bitrix\Main\SystemException
     */
    public function executeComponent()
    {
        global $APPLICATION;

        if ($this->arParams['SET_TITLE'] == 'Y') {
            $APPLICATION->SetTitle($this->arParams['NEW_TITLE']);
        }

        $this->arResult['ITEM'] = GroupTable::getList(
            [
                'filter' => [
                    '=ID' => $this->arParams['ELEMENT_ID']
                ]
            ]
        )->fetch();

        $this->includeComponentTemplate();
    }
}